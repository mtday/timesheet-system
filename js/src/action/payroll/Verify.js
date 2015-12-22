
Ext.namespace("action.payroll");

action.payroll.Verify = function(timesheetId) {
	return new Ext.Action({
		id:       'action.payroll.verify',
		text:     'Verify',
		iconCls:  'icon-timesheet-verify',
		renderTo: 'verify-button-' + timesheetId,
		handler: function() {
			// Show the progress bar while the employee is being saved.
			Ext.Msg.progress('Verifying Timesheet',
				'Please wait while verifying the timesheet...');

			// Create the ServerIO object.
			var io = new util.io.ServerIO();

			// Submit the form.
			io.doAjaxRequest({
				// Set the URL.
				url: '/payroll/timesheet/verify',

				// Add the parameters to send to the server.
				params: {
					ids: timesheetId
				},

				// The function to invoke after success.
				mysuccess: function(data) {
					// Clear the verify button.
					document.getElementById(
						'verify-button-' + timesheetId).innerHTML = '';

					// Add the unverify button.
					new Ext.Button(new action.payroll.Unverify(timesheetId));

					// Get the status image element.
					var statImg = document.getElementById(
						'payroll-status-' + timesheetId);

					// Update the image.
					statImg.innerHTML =
						'<img src="/images/icons/bullet_green.png" border="0" ' +
							 'alt="Payroll Processed" title="Payroll Processed"/>';
				}
			});
		}
	});
}

