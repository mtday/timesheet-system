
Ext.namespace("action.supervisor");

action.supervisor.DoApprove = function() {
	return new Ext.Action({
		id:       'action.supervisor.doapprove',
		text:     'Approve',
		iconCls:  'icon-timesheet-approve',
		disabled: true,
		handler: function() {
			// Get the grid.
			var grid = Ext.getCmp('ui.grid.supervisedgrid');

			// Generate the array of ids to approve.
			var ids = grid.getSelectedIds();

			// Check to see if we have multiple timesheets to approve.
			var t = ids.length > 1 ? 'timesheets' : 'timesheet';
			var T = ids.length > 1 ? 'Timesheets' : 'Timesheet';

			// Show the progress bar while the employee is being saved.
			Ext.Msg.progress('Approving ' + T,
				'Please wait while approving the ' + t + '...');

			// Create the ServerIO object.
			var io = new util.io.ServerIO();

			// Submit the form.
			io.doAjaxRequest({
				// Set the URL.
				url: '/supervisor/timesheet/approve',

				// Add the parameters to send to the server.
				params: {
					ids: ids.join(',')
				},

				// The function to invoke after success.
				mysuccess: function(data) {
					// Get the grid.
					var grid = Ext.getCmp('ui.grid.supervisedgrid');

					// Reload the data store.
					grid.getStore().reload();
				}
			});
		}
	});
}

