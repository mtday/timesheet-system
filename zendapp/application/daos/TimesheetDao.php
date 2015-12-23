<?php

class TimesheetDao extends BaseDao
{
	/**
	 * The name of the associated database table.
	 */
	public $_name = 'timesheets';

	/**
	 * Apply the necessary order calls to the provided select statement.
	 *
	 * @param select The select object to invoke order on.
	 */
	public function setDefaultOrder($select)
	{
		// Set the order.
		$select->order('pp_start desc')
			   ->order('t.employee_id');
	}

	/**
	 * Verify the timesheets with the specified ids in the database.
	 *
	 * @param ids The ids of the timesheets to verify.
	 *
	 * @param verifier The payroll person that verified the timesheet.
	 *
	 * @param verified Whether the timesheets should be verified or unverified.
	 *
	 * @return Returns the number of rows verified.
	 */
	public function verify($ids, $verifier, $verified = true)
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Keep track of the activated rows.
		$count = 0;

		// Get the AuditLogDao instance.
		$auditLogDao = new AuditLogDao();

		// Make sure some ids were specified.
		if (isset($ids) && count($ids) > 0)
			// Iterate over the provided ids.
			foreach ($ids as $id)
				// Make sure the id is valid.
				if (is_numeric($id)) {
					// Update the object in the database.
					$count += $db->update($this->_name,
							array('verified' => $verified,
								'verified_by' => $verifier->id), "id = $id");

					// Add an audit log for this timesheet verification.
					$auditLogDao->add(array(
						'timesheet_id' => $id,
						'log' => ($verified ?
							'Timesheet verified by ' :
							'Timesheet un-verified by ') .
								$verifier->full_name . '.'
					));
				}

		// Return the count.
		return $count;
	}

	/**
	 * Approve the timesheets with the specified ids in the database.
	 *
	 * @param ids The ids of the timesheets to approve.
	 *
	 * @param approver The supervisor that approved the timesheet.
	 *
	 * @param approved Whether the timesheets should be approved or disapproved.
	 *
	 * @return Returns the number of rows approved.
	 */
	public function approve($ids, $approver, $approved = true)
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Keep track of the activated rows.
		$count = 0;

		// Get the AuditLogDao instance.
		$auditLogDao = new AuditLogDao();

		// Make sure some ids were specified.
		if (isset($ids) && count($ids) > 0)
			// Iterate over the provided ids.
			foreach ($ids as $id)
				// Make sure the id is valid.
				if (is_numeric($id)) {
					// Update the object in the database.
					$count += $db->update($this->_name,
							array('approved' => $approved,
								'approved_by' => $approver->id), "id = $id");

					// Add an audit log for this timesheet approval.
					$auditLogDao->add(array(
						'timesheet_id' => $id,
						'log' => ($approved ?
							'Timesheet approved by ' :
							'Timesheet disapproved by ') .
								$approver->full_name . '.'
					));
				}

		// Return the count.
		return $count;
	}

	/**
	 * Export the timesheets with the specified ids in the database.
	 *
	 * @param ids The ids of the timesheets to export.
	 *
	 * @param exporter The payroll person that exported the timesheet.
	 *
	 * @param config The associated configuration.
	 *
	 * @return Returns the data export value.
	 */
	public function export($ids, $exporter, $config = null)
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Get the AuditLogDao instance.
		$auditLogDao = new AuditLogDao();

		// This will hold the output.
		$output = '';

		// Make sure some ids were specified.
		if (isset($ids) && count($ids) > 0) {
			// Get the config information.
			$company = $config->qb->company;
			$createtime = $config->qb->createtime;
			$payrollVendor = $config->qb->payrollvendor;

			// This will hold the output information.
			$output .= "!TIMERHDR\tVER\tREL\tCOMPANYNAME\tIMPORTEDBEFORE\tFROMTIMER\tCOMPANYCREATETIME\n";
			$output .= "TIMERHDR\t 8\t 0\t$company\tN\tY\t$createtime\n";
			$output .= "!TIMEACT\tDATE\tJOB\tEMP\tITEM\tPITEM\tDURATION\tPROJ\tNOTE\tXFERTOPAYROLL\tBILLINGSTATUS\n";

			// Get all the time sheets.
			$timesheets = $this->getGroup($ids);

			// Iterate over the provided ids.
			foreach ($timesheets as $timesheet) {
				// Iterate over the available contracts for this timesheet.
				foreach ($timesheet->contracts as $contract) {
					// Track the total hours for this contract.
					$total = 0;
					$lastDay = gmdate('n/j/y', strtotime(
								$timesheet->pay_period->end . " -0000"));
					$lastNote = gmdate('n/j D', strtotime(
								$timesheet->pay_period->end . " -0000"));

					foreach ($timesheet->bills as $bill) {
						// Skip any bills that aren't for this contract assignment.
						if (is_numeric($bill->assignment_id)) {
							if ($bill->assignment_id != $contract->assignment_id)
								continue;
						} else {
							if ($bill->contract_id != $contract->contract_id)
								continue;
						}

						// Calculate the day when the bill occurred.
						$day = gmdate('n/j/y', strtotime($bill->day . " -0000"));

						// Get the job and item.
						$job = $contract->job_code;
						if (! $contract->admin) {
							$item = $contract->item_name;
							$pay = $timesheet->employee->full_name . ':' .
								$timesheet->employee->first_name . ' - Pay';
							$emp = substr($item, 0, strpos($item, ':'));
						} else {
							$item = $timesheet->employee->full_name . ':' .
								$timesheet->employee->first_name . ' - ' .
								substr($job, strpos($job, ':') + 1);
							$pay = null;
							$emp = $payrollVendor;
						}

						// Build the hours value.
						$hours = $bill->hours;
						if (isset($pay) || $contract->admin)
							$total += $hours;

						// These are left blank.
						$payrollItem = "";
						$project = "";

						// The note for these hours.
						$note = gmdate('n/j D', strtotime($bill->day . " -0000"));
						$note .= ' ' . $item;

						// Billable and payroll transfer.
						$billable = '1';
						$transferToPayroll = 'N';

						// Create the output for this entry.
						if (! $contract->admin)
							$output .= "TIMEACT\t$day\t$job\t$emp\t$item\t" .
									"$payrollItem\t$hours\t$project\t$note\t" .
									"$transferToPayroll\t$billable\n";
					}

					// Add the payroll payment for these hours.
					if ($total > 0) {
						// Use either "Mike Day:Mike - PTO" or "Mike Day:Mike - Pay".
						if ($contract->admin)
							$item = $timesheet->employee->full_name . ':' .
								$timesheet->employee->first_name . ' - ' .
								substr($job, strpos($job, ':') + 1);
						else
							$item = $timesheet->employee->full_name . ':' .
								$timesheet->employee->first_name . ' - Pay';

						$vendor = $payrollVendor;

						// Handle consultants differently.
						if ($timesheet->employee->personnel_type == 'Consultant')
							$vendor = "Consultant - " .
								$timesheet->employee->full_name;

						// Write the output.
						$output .= "TIMEACT\t$lastDay\t$job\t$vendor\t" .
								"$item\t$payrollItem\t$total\t$project\t" .
								"$lastNote\t$transferToPayroll\t$billable\n";
					}
				}

				// Update the object in the database.
				$db->update($this->_name,
						array('exported' => 1), "id = $timesheet->id");

				// Add an audit log for this timesheet verification.
				$auditLogDao->add(array(
					'timesheet_id' => $timesheet->id,
					'log' => 'Timesheet exported by ' . $exporter->full_name . '.'
				));
			}
		}

		// Return the output.
		return $output;
	}

	/**
	 * Unexport the timesheets with the specified ids in the database.
	 *
	 * @param ids The ids of the timesheets to export.
	 *
	 * @param exporter The payroll person that is unexporting the timesheet.
	 *
	 * @return Returns the number of rows exported.
	 */
	public function unexport($ids, $exporter)
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Get the AuditLogDao instance.
		$auditLogDao = new AuditLogDao();

		// This will hold the count.
		$count = 0;

		// Make sure some ids were specified.
		if (isset($ids) && count($ids) > 0)
			// Iterate over the provided ids.
			foreach ($ids as $id)
				// Make sure the id is a number.
				if (is_numeric($id)) {
					// Update the object in the database.
					$count += $db->update($this->_name,
						array('exported' => 0), "id = $id");

					// Add an audit log for this timesheet verification.
					$auditLogDao->add(array(
						'timesheet_id' => $id,
						'log' => 'Timesheet un-exported by ' .
								$exporter->full_name . '.'
					));
				}

		// Return the count.
		return $count;
	}

	/**
	 * Add the provided array of data as a row in the database.
	 *
	 * @param arr An array of the values to insert into the database.
	 *
	 * @return Returns the auto-generated id of the inserted row.
	 */
	public function add($arr)
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Insert the object into the database.
		$db->insert($this->_name, $arr);

		// Retrieve the last insert id.
		$id = $db->lastInsertId();

		// Get the AuditLogDao instance.
		$auditLogDao = new AuditLogDao();

		// Add an audit log for this timesheet creation.
		$auditLogDao->add(array(
			'timesheet_id' => $id,
			'log' => 'Initial empty timesheet creation.'
		));

		// Return the id of the new timesheet.
		return $id;
	}

	/**
	 * Used to retrieve the specified timesheet.
	 *
	 * @param id The id of the timesheet to retrieve.
	 *
	 * @return Returns the requested timesheet.
	 */
	public function get($id)
	{
		// Make sure the id is valid.
		if (!isset($id) || !is_numeric($id))
			return null;

		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Build the query.
		$select->from($this->_name)
			   ->where('id = ?', $id);

		// Retrieve the timesheet.
		$obj = $db->query($select)->fetchObject();

		// Make sure the timesheet was found.
		if (!isset($obj) || !isset($obj->id))
			return null;

		// Post-process the timesheet.
		$this->postProcess($obj);

		// Enhance the timesheet with all the peripheral information.
		$obj = $this->enhanceTimesheet($obj);

		// Return the retrieved object.
		return $obj;
	}

	/**
	 * Used to retrieve an employee timesheet for a specific pay period.
	 *
	 * @param id The employee id for which the timesheet will be retrieved.
	 *
	 * @param pp The pay period for which the timesheet will be retrieved.
	 *
	 * @return Returns the requested employee timesheet.
	 */
	public function getForEmployee($id, $pp)
	{
		// Make sure the id is valid.
		if (!isset($id) || !is_numeric($id))
			return null;

		// Make sure the pay period is valid.
		if (!isset($pp) || !isset($pp->start))
			return null;

		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Build the query.
		$select->from($this->_name)
			   ->where('pp_start = ?', $pp->start)
			   ->where('employee_id = ?', $id);

		// Retrieve the timesheet.
		$obj = $db->query($select)->fetchObject();

		// Make sure an object was found.
		if (!isset($obj) || !isset($obj->id)) {
			// Create an empty timesheet.
			$this->add(array(
				'employee_id' => $id,
				'pp_start'    => $pp->start
			));

			// Re-run the query.
			$obj = $db->query($select)->fetchObject();
		}

		// Make sure the timesheet was found.
		if (!isset($obj) || !isset($obj->id))
			return null;

		// Post-process the timesheet.
		$this->postProcess($obj);

		// Enhance the timesheet with all the peripheral information.
		$obj = $this->enhanceTimesheet($obj);

		// Return the retrieved object.
		return $obj;
	}

	/**
	 * Used to retrieve the latest incomplete employee timesheet.
	 *
	 * @param id The employee id for which the timesheet will be retrieved.
	 *
	 * @return Returns the latest incomplete employee timesheet.
	 */
	public function getLatestForEmployee($id)
	{
		// Make sure the id is valid.
		if (!isset($id) || !is_numeric($id))
			return null;

		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the first incomplete timesheet.
		$incomplete = $db->select()
			   ->from($this->_name)
			   ->where('employee_id = ?', $id)
			   ->where('completed = false')
			   ->order('pp_start');

		// Retrieve all the incomplete timesheets.
		$objs = $db->query($incomplete)->fetchAll();

		// Get the incomplete timesheets that have bills.
		$withBills = array();
		foreach ($objs as $ts) {
			// Post-process the timesheet.
			$this->postProcess($ts);

			// Add the timesheet enhancement information.
			$this->enhanceTimesheet($ts);

			// Add the timesheet summary information.
			$this->addSummary($ts, $ts->pay_period);

			// Only add timesheets that have some hours.
			if ($ts->total)
				$withBills[] = $ts;
		}

		// If there is only one, then use it.
		$obj = null;
		if (count($withBills) == 1)
			$obj = $withBills[0];

		// Make sure an object was found.
		if (!isset($obj) || !isset($obj->id)) {
			// Get the most recent completed timesheet.
			$complete = $db->select()
				   ->from($this->_name)
				   ->where('employee_id = ?', $id)
				   ->where('completed = true')
				   ->order('pp_start DESC')
				   ->limit(1);

			// Retrieve the timesheet.
			$obj = $db->query($complete)->fetchObject();

			// Make sure the timesheet was found.
			if (!isset($obj) || !isset($obj->id) && count($objs) == 0) {
				// Get the current pay period.
				$payPeriodDao = new PayPeriodDao();
				$currPP = $payPeriodDao->getCurrent();

				// Make sure the pay period exists.
				if (isset($currPP) && isset($currPP->start)) {
					// Create an empty timesheet for the current pay period.
					$this->add(array(
						'employee_id' => $id,
						'pp_start'    => $currPP->start
					));

					// Retrieve the newly added timesheet.
					$obj = $db->query($incomplete)->fetchObject();
				}
			}

			// Use an existing timesheet.
			if (!isset($obj) || !isset($obj->id)) {
				if (count($withBills) > 0)
					// Use the first incomplete timesheet that has bills.
					$obj = $withBills[0];
				else if (count($objs) > 0)
					// Use the first incomplete timesheet.
					$obj = $objs[0];
			}
		}

		// Make sure a timesheet was found.
		if (!isset($obj) || !isset($obj->id))
			return null;

		// Check to see if the timesheet has already been enhanced.
		if (!isset($obj->pay_period)) {
			// Post-process the timesheet.
			$this->postProcess($obj);

			// Add the timesheet enhancement information.
			$this->enhanceTimesheet($obj);

			// Add the timesheet summary information.
			$this->addSummary($obj, $obj->pay_period);
		}

		// Return the retrieved object.
		return $obj;
	}

	/**
	 * Used to retrieve a group of timesheets.
	 *
	 * @param ids A list of ids representing the timesheets to retrieve.
	 *
	 * @return Returns the requested timesheets.
	 */
	public function getGroup($ids)
	{
		// Make sure some ids were specified.
		if (!isset($ids) || count($ids) == 0)
			return null;

		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Build the query.
		$select->from(array('t' => $this->_name))
			   ->where('id IN (' . join(',', $ids) . ')');

		// Add ordering.
		$this->setDefaultOrder($select);

		// Retrieve all the objects.
		$objs = $db->query($select)->fetchAll();

		// Perform post-processing on all the objects.
		if (isset($objs) && count($objs) > 0)
			foreach ($objs as $obj) {
				// Post-process the timesheet.
				$this->postProcess($obj);

				// Add the timesheet enhancement information.
				$this->enhanceTimesheet($obj);
			}

		// Return the objects.
		return $objs;
	}

	/**
	 * Used to enhance the provided timesheet with data from other tables.
	 *
	 * @param obj The timesheet object to enhance.
	 *
	 * @return Returns the enhanced timesheet.
	 */
	public function enhanceTimesheet($obj)
	{
		// Set the other timesheet information.
		if (is_numeric($obj->id)) {
			// Set the bills in the timesheet.
			$billDao = new BillDao();
			$obj->bills = $billDao->getForTimesheet(
					$obj->id, $obj->employee_id);

			// Set the employee.
			$employeeDao = new EmployeeDao();
			$obj->employee = $employeeDao->get($obj->employee_id);

			// Set the audit log info.
			$auditLogDao = new AuditLogDao();
			$obj->audit_log = $auditLogDao->getForTimesheet($obj->id);

			// Set the pay period.
			$payPeriodDao = new PayPeriodDao();
			$obj->pay_period = $payPeriodDao->get($obj->pp_start);

			// Set the employee's contracts.
			$contractDao = new ContractDao();
			$obj->contracts = $contractDao->getEmployeeContractsForPayPeriod(
					$obj->employee_id, $obj->pay_period);

			// Set the holidays.
			$holidayDao = new HolidayDao();
			$obj->holidays = $holidayDao->getForPayPeriod($obj->pay_period);
		}

		// Perform post-processing.
		$this->postProcess($obj);

		// Return the enhanced timesheet object.
		return $obj;
	}

	/**
	 * Used to retrieve timesheet status information for a pay period.
	 *
	 * @param pp The pay period for which the timesheet status information will
	 * be retrieved.
	 *
	 * @return Returns the requested timesheet status information.
	 */
	public function getStatus($pp)
	{
		// Make sure the pay period is valid.
		if (!isset($pp) || !isset($pp->start))
			return null;

		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Get the DAOs we are joining across.
		$employeeDao = new EmployeeDao();

		// Build the query.
		$select->from(array('e' => $employeeDao->_name),
					array('division', 'email', 'first_name', 'last_name',
						'login', 'personnel_type', 'active'))
			   ->joinLeft(array('t' => $this->_name),
					   'e.id = t.employee_id')
			   ->where('(t.pp_start = ? OR t.pp_start IS NULL)', $pp->start)
			   ->order('last_name')
			   ->order('first_name');

		// Add ordering.
		$this->setDefaultOrder($select);

		// Retrieve all the objects.
		$objs = $db->query($select)->fetchAll();

		// Perform post-processing on all the objects.
		if (isset($objs) && count($objs) > 0)
			foreach ($objs as $obj) {
				// Post-process the timesheet.
				$this->postProcess($obj);

				// Post-process the employee.
				$employeeDao->postProcess($obj);

				// Add the timesheet summary information.
				$this->addSummary($obj, $pp);
			}

		// Return the objects.
		return $objs;
	}

	/**
	 * Used to retrieve timesheet status information for a supervisor's
	 * employees for a specific pay period.
	 *
	 * @param pp The pay period for which the timesheet status information will
	 * be retrieved.
	 *
	 * @param supervisor The supervisor for which timesheets status will be
	 * retrieved.
	 *
	 * @return Returns the requested timesheet status information.
	 */
	public function getStatusForSupervised($pp, $supervisor)
	{
		// Make sure the pay period is valid.
		if (!isset($pp) || !isset($pp->start) ||
				!isset($supervisor) || !isset($supervisor->id))
			return null;

		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Get the DAOs we are joining across.
		$employeeDao = new EmployeeDao();
		$supervisorDao = new SupervisorDao();

		// Build the query.
		$select->from(array('e' => $employeeDao->_name),
					array('division', 'email', 'first_name', 'last_name',
						'login', 'personnel_type', 'active'))
			   ->join(array('s' => $supervisorDao->_name),
					   'e.id = s.employee_id')
			   ->joinLeft(array('t' => $this->_name),
					   'e.id = t.employee_id')
			   ->where('(t.pp_start = ? OR t.pp_start IS NULL)', $pp->start)
			   ->where('s.supervisor_id = ?', $supervisor->id)
			   ->order('last_name')
			   ->order('first_name');

		// Add ordering.
		$this->setDefaultOrder($select);

		// Retrieve all the objects.
		$objs = $db->query($select)->fetchAll();

		// Perform post-processing on all the objects.
		if (isset($objs) && count($objs) > 0)
			foreach ($objs as $obj) {
				// Post-process the timesheet.
				$this->postProcess($obj);

				// Post-process the employee.
				$employeeDao->postProcess($obj);

				// Add the timesheet summary information.
				$this->addSummary($obj, $pp);
			}

		// Return the objects.
		return $objs;
	}

	/**
	 * Used to add timesheet summary information to a timesheet.
	 *
	 * @param ts The timesheet for which the timesheet summary information will
	 * be retrieved.
	 *
	 * @param pp The pay period for which the timesheet summary information will
	 * be retrieved.
	 *
	 * @return Returns the requested timesheet summary information.
	 */
	public function addSummary($ts, $pp)
	{
		// Make sure the timesheet is valid.
		if (!isset($ts) || !isset($pp) || !isset($pp->start))
			return;

		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Get the DAOs we are joining across.
		$contractDao = new ContractDao();
		$billDao = new BillDao();

		// Make sure the employee id is available.
		if (isset($ts->employee_id)) {
			// Build the query.
			$select->from(array('c' => $contractDao->_name),
						array('contract_num', 'admin'))
				   ->join(array('b' => $billDao->_name),
						   'c.id = b.contract_id',
						   array('sum(hours) as hours'))
				   ->where('b.employee_id = ?', $ts->employee_id)
				   ->where('b.day >= ?', $pp->start)
				   ->where('b.day <= ?', $pp->end)
				   ->group(array('contract_num', 'admin'));

			// Retrieve all the objects.
			$objs = $db->query($select)->fetchAll();
		} else
			$objs = null;

		// Keep track of the total hours.
		$total = 0;

		// Keep track of the total billable hours.
		$billable = 0;

		// Perform post-processing on the provided timesheet.
		if (isset($objs) && count($objs) > 0)
			// Iterate over the summary info.
			foreach ($objs as $obj) {
				// Check to see if these are admin contracts.
				if ($obj->contract_num == "PTO")
					$ts->pto = $obj->hours;
				else if ($obj->contract_num == "HOL")
					$ts->holiday = $obj->hours;
				else if ($obj->contract_num == "OH")
					$ts->overhead = $obj->hours;
				else if ($obj->contract_num == "GA")
					$ts->ga = $obj->hours;
				else if ($obj->contract_num == "BP")
					$ts->bp = $obj->hours;
				else if ($obj->contract_num == "JURY")
					$ts->jury = $obj->hours;
				else if ($obj->contract_num == "BER")
					$ts->bereavement = $obj->hours;
				else if ($obj->contract_num == "LWP")
					$ts->lwop = $obj->hours;

				// Add the billable hours.
				if ($obj->admin == "0")
					$billable += $obj->hours;

				// Add the total hours.
				$total += $obj->hours;
			}

		// Make sure the contracts have values.
		if (!isset($ts->pto)) $ts->pto = "";
		if (!isset($ts->holiday)) $ts->holiday = "";
		if (!isset($ts->overhead)) $ts->overhead = "";
		if (!isset($ts->ga)) $ts->ga = "";
		if (!isset($ts->bp)) $ts->bp = "";
		if (!isset($ts->jury)) $ts->jury = "";
		if (!isset($ts->bereavement)) $ts->bereavement = "";
		if (!isset($ts->lwop)) $ts->lwop = "";
		if (!isset($ts->primary)) $ts->primary = "1";

		// Add the total and billable.
		$ts->total = $total ? $total : "";
		$ts->billable = $billable ? $billable : "";
	}
}

