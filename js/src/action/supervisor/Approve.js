
Ext.namespace("action.supervisor");

action.supervisor.Approve = function(timesheetId) {
	return new Ext.Action({
		id:       'action.supervisor.approve',
		text:     'Approve',
		iconCls:  'icon-timesheet-approve',
		renderTo: 'approve-button-' + timesheetId,
		handler: function() {
			// Show the progress bar while the employee is being saved.
			Ext.Msg.progress('Approving Timesheet',
				'Please wait while approving the timesheet...');

			// Create the ServerIO object.
			var io = new util.io.ServerIO();

			// Submit the form.
			io.doAjaxRequest({
				// Set the URL.
				url: '/supervisor/timesheet/approve',

				// Add the parameters to send to the server.
				params: {
					ids: timesheetId
				},

				// The function to invoke after success.
				mysuccess: function(data) {
					// Clear the approve button.
					document.getElementById(
						'approve-button-' + timesheetId).innerHTML = '';

					// Add the disapprove button.
					new Ext.Button(new action.supervisor.Disapprove(timesheetId));

					// Get the status image element.
					var statImg = document.getElementById(
						'approval-status-' + timesheetId);

					// Update the image.
					statImg.innerHTML =
						'<img src="/images/icons/bullet_green.png" border="0" ' +
							 'alt="Approved" title="Approved"/>';
				}
			});
		}
	});
}

