
Ext.namespace("action.supervisor");

action.supervisor.Disapprove = function(timesheetId) {
	return new Ext.Action({
		id:       'action.supervisor.disapprove',
		text:     'Disapprove',
		iconCls:  'icon-timesheet-disapprove',
		renderTo: 'disapprove-button-' + timesheetId,
		handler: function() {
			// Show the progress bar while the employee is being saved.
			Ext.Msg.progress('Disapproving Timesheet',
				'Please wait while disapproving the timesheet...');

			// Create the ServerIO object.
			var io = new util.io.ServerIO();

			// Submit the form.
			io.doAjaxRequest({
				// Set the URL.
				url: '/supervisor/timesheet/disapprove',

				// Add the parameters to send to the server.
				params: {
					ids: timesheetId
				},

				// The function to invoke after success.
				mysuccess: function(data) {
					// Clear the disapprove button.
					document.getElementById(
						'disapprove-button-' + timesheetId).innerHTML = '';

					// Add the verify button.
					new Ext.Button(new action.supervisor.Approve(timesheetId));

					// Get the status image element.
					var statImg = document.getElementById(
						'approval-status-' + timesheetId);

					// Update the image.
					statImg.innerHTML =
						'<img src="/images/icons/bullet_red.png" border="0" ' +
							 'alt="Not Approved" title="Not Approved"/>';
				}
			});
		}
	});
}

