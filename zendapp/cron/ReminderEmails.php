<?php

// Identify the root of the system.
$root = dirname(__FILE__) . "/.."; // (The zendapp directory)

// Set the include path.
set_include_path(
    $root . '/application'                                  . PATH_SEPARATOR .
    $root . '/application/daos'                             . PATH_SEPARATOR .
    $root . '/application/util'                             . PATH_SEPARATOR .
    $root . '/application/modules/admin/controllers'        . PATH_SEPARATOR .
    $root . '/application/modules/admin/forms'              . PATH_SEPARATOR .
    $root . '/application/modules/admin/views/helpers'      . PATH_SEPARATOR .
    $root . '/application/modules/payroll/controllers'      . PATH_SEPARATOR .
    $root . '/application/modules/payroll/views/helpers'    . PATH_SEPARATOR .
    $root . '/application/modules/manager/controllers'      . PATH_SEPARATOR .
    $root . '/application/modules/manager/views/helpers'    . PATH_SEPARATOR .
    $root . '/application/modules/supervisor/controllers'   . PATH_SEPARATOR .
    $root . '/application/modules/supervisor/views/helpers' . PATH_SEPARATOR .
    $root . '/application/modules/user/controllers'         . PATH_SEPARATOR .
    $root . '/application/modules/user/views/helpers'       . PATH_SEPARATOR .
    $root . '/application/modules/default/controllers'      . PATH_SEPARATOR .
    $root . '/application/modules/default/forms'            . PATH_SEPARATOR .
    $root . '/application/modules/default/views/helpers'    . PATH_SEPARATOR .
    $root . '/library'                                      . PATH_SEPARATOR .
    get_include_path()
);

try {
	// Perform all system initialization via Bootstrap.
	require_once 'Bootstrap.php';
	Bootstrap::prepare();

	// Log what we are doing.
	print("ReminderEmails CRON is Running...\n");

	// Get the mail configuration.
	$config = Bootstrap::$registry->config->mail;

	// Create the mail server login info.
	$mailconfig = array('auth' => 'login',
					    'port' => $config->port,
					    'username' => $config->user,
					    'password' => $config->pass);

	// Create the transport.
	$transport = new Zend_Mail_Transport_Smtp($config->host, $mailconfig);

	// Get yesterday.
	$day = gmdate('Y-m-d', strtotime(gmdate('Y-m-d') . " -0000") - (24 * 60 * 60));
	$dayTime = strtotime($day . " -0000");
	$dayName = gmdate('D', $dayTime);

	// Make sure yesterday was not a weekend day.
	if ($dayName != "Sat" && $dayName != "Sun") {
		// Log what we are doing.
		print("Processing Yesterday ($dayName, $day).\n");

		// Get the pay period containing yesterday.
		$payPeriodDao = new PayPeriodDao();
		$payPeriod = $payPeriodDao->getContaining($day);

		// Make sure the pay period was found.
		if (isset($payPeriod)) {
			// Log what we are doing.
			print("Found associated pay period.\n");

			// Get all the active employees in the system.
			$employeeDao = new EmployeeDao();
			$employees = $employeeDao->getAll(true);

			// This is used to retrieve timesheets.
			$timesheetDao = new TimesheetDao();

			// This is used to log our timesheet reviews.
			$auditLogDao = new AuditLogDao();

			// Make sure some active employees were found.
			if (isset($employees) && count($employees) > 0) {
				// Iterate over the available employees.
				foreach ($employees as $employee) {
					// Log what we are doing.
					print("\nFound employee to check: " . $employee->full_name . "\n");

					// Keep track of whether we need to send notification info.
					$notify = false;

					// Get the timesheet for this employee.
					$timesheet = $timesheetDao->getForEmployee(
							$employee->id, $payPeriod);

					// Make sure the timesheet exists.
					if (isset($timesheet)) {
						// Log what we are doing.
						print("  Found timesheet for employee.\n");

						// If the timesheet has been completed, then we don't need
						// to do anything.
						if ($timesheet->completed == "1") {
							// Audit log that we reviewed this timesheet.
							$auditLogDao->add(array(
								'timesheet_id' => $timesheet->id,
								'log' => "Daily timesheet review: " .
									"Timesheet has been completed."
							));
							print("  Timesheet already completed.\n");

							// Continue to the next timesheet.
							continue;
						}

						// Determine whether the timesheet contains
						// non-administrative contracts that haven't expired.
						print("  Checking administrative status of contracts...\n");
						$allAdminContracts = true;
						foreach ($timesheet->contracts as $contract)
							$allAdminContracts &= $contract->admin == "1" ||
								TimesheetHelper::isExpired(
										$timesheet, $dayTime, $contract->id);
						print("  Contracts are all admin or expired? " .
								($allAdminContracts ? "Yes" : "No") . "\n");

						// Check to see if this is a billable employee.
						if (! $allAdminContracts) {
							// This will keep track of the last time an employee
							// billed hours in this timesheet.
							$lastBill = 0;

							// Make sure some bills are available for this timesheet.
							if (isset($timesheet->bills) && count($timesheet->bills) > 0) {
								// Iterate over the bills to find the most recent
								// time for which hours were added.
								foreach ($timesheet->bills as $bill) {
									// Retrieve the time for this bill.
									$time = strtotime($bill->day . " -0000");

									// Update the last bill time.
									$lastBill = ($time > $lastBill) ? $time : $lastBill;
								}
							}

							// Check to see if the timesheet is out-of-date.
							if ($lastBill < $dayTime) {
								// Audit log that we reviewed this timesheet.
								$auditLogDao->add(array(
									'timesheet_id' => $timesheet->id,
									'log' => "Daily timesheet review: " .
										"Timesheet hours were NOT " .
										"entered for yesterday."
								));

								// Log what we are doing.
								print("    Timesheet hours were NOT entered for yesterday.\n");

								// Need to send notification email.
								$notify = true;
							} else {
								// Audit log that we reviewed this timesheet.
								$auditLogDao->add(array(
									'timesheet_id' => $timesheet->id,
									'log' => "Daily timesheet review: " .
										"Timesheet hours were entered " .
										"for yesterday."
								));

								// Log what we are doing.
								print("    Timesheet is okay.\n");
							}
						} else {
							// This is an overhead employee, so no notifications
							// are necessary.

							// Audit log that we reviewed this timesheet.
							$auditLogDao->add(array(
								'timesheet_id' => $timesheet->id,
								'log' => "Daily timesheet review: " .
									"No active non-overhead contracts."
							));

							// Log what we are doing.
							print("    No active non-overhead contracts.\n");
						}
					} else
						// The timesheet does not exist when it should.
						$notify = true;

					// Check to see if we need to notify.
					if ($notify) {
						// Create the employee email.
						$employeeEmail = <<<EMAIL

Incomplete Timesheet Notice:

Please be sure to enter timesheet hours on a daily basis. This is a Defense
Contract Auditing Agency (DCAA) requirement with which employees must comply.

Notices like this may impact your annual performance review. This is an
automated message - if you have any questions, please contact your supervisor.

EMAIL;

						// Log what we are doing.
						print("    Sending reminder email.\n");

						// Send the employee email.
						$employeeMail = new Zend_Mail();
						$employeeMail->setBodyText($employeeEmail)
									 ->setFrom($config->from, $config->name)
									 ->addTo($employee->email,
											 $employee->full_name)
									 ->setSubject("Incomplete Timesheet Notice")
									 ->send($transport);

						// Create the supervisor email.
						$supervisorEmail = <<<EMAIL

Incomplete Timesheet Notice:

Employee: $employee->full_name
Day:      $day

EMAIL;

						// Make sure the employee has some supervisors.
						$sups = array();
						if (isset($employee->supervisors) &&
								count($employee->supervisors) > 0)
							// Iterate over the supervisors.
							foreach ($employee->supervisors as $sup)
								// Make sure this is a primary supervisor.
								if ($sup->primary == "1")
									$sups[] = $sup;

						// Log what we are doing.
						print("    Sending supervisor email.\n");

						// Send an email to each primary supervisor.
						foreach ($sups as $sup) {
							// Send the supervisor email.
							$supervisMail = new Zend_Mail();
							$supervisMail->setBodyText($supervisorEmail)
										 ->setFrom($config->from, $config->name)
										 ->addTo($sup->email,
												 $sup->full_name)
										 ->setSubject("Incomplete Timesheet: " .
												 $employee->full_name)
										 ->send($transport);
						}
					}
				}
			}
		} else {
			// Log the error message.
			Logger::getLogger()->info("Reminder Emails cron job failed since " .
					"the pay period was not found.");
			print("Reminder Emails cron job failed since " .
					"the pay period was not found.\n");
		}
	} else {
		// Log a status message.
		Logger::getLogger()->info("Reminder Emails cron job not processing " .
				"since yesterday was not a weekday.");
		print("Reminder Emails cron job not processing " .
				"since yesterday was not a weekday.\n");
	}
} catch (Exception $ex) {
	// Log the error.
	Logger::getLogger()->info("Cron Job Failed: " . $ex->getMessage());
	Logger::getLogger()->info("$ex");
	print("Cron Job Failed: " . $ex->getMessage() . "\n");
	print("$ex\n");
}

