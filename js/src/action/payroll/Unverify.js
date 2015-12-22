
Ext.namespace("action.payroll");

action.payroll.Unverify = function(timesheetId) {
	return new Ext.Action({
		id:       'action.payroll.unverify',
		text:     'Unverify',
		iconCls:  'icon-timesheet-unverify',
		renderTo: 'unverify-button-' + timesheetId,
		handler: function() {
			// Show the progress bar while the employee is being saved.
			Ext.Msg.progress('Unverifying Timesheet',
				'Please wait while unverifying the timesheet...');

			// Create the ServerIO object.
			var io = new util.io.ServerIO();

			// Submit the form.
			io.doAjaxRequest({
				// Set the URL.
				url: '/payroll/timesheet/unverify',

				// Add the parameters to send to the server.
				params: {
					ids: timesheetId
				},

				// The function to invoke after success.
				mysuccess: function(data) {
					// Clear the unverify button.
					document.getElementById(
						'unverify-button-' + timesheetId).innerHTML = '';

					// Add the verify button.
					new Ext.Button(new action.payroll.Verify(timesheetId));

					// Get the status image element.
					var statImg = document.getElementById(
						'payroll-status-' + timesheetId);

					// Update the image.
					statImg.innerHTML =
						'<img src="/images/icons/bullet_red.png" border="0" ' +
							 'alt="Not Processed" title="Not Processed"/>';
				}
			});
		}
	});
}

