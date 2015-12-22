
Ext.namespace("action.supervisor");

action.supervisor.DoDisapprove = function() {
	return new Ext.Action({
		id:       'action.supervisor.dodisapprove',
		text:     'Disapprove',
		iconCls:  'icon-timesheet-disapprove',
		disabled: true,
		handler: function() {
			// Get the grid.
			var grid = Ext.getCmp('ui.grid.supervisedgrid');

			// Generate the array of ids to disapprove.
			var ids = grid.getSelectedIds();

			// Check to see if we have multiple timesheets to disapprove.
			var t = ids.length > 1 ? 'timesheets' : 'timesheet';
			var T = ids.length > 1 ? 'Timesheets' : 'Timesheet';

			// Show the progress bar while the employee is being saved.
			Ext.Msg.progress('Disapproving ' + T,
				'Please wait while disapproving the ' + t + '...');

			// Create the ServerIO object.
			var io = new util.io.ServerIO();

			// Submit the form.
			io.doAjaxRequest({
				// Set the URL.
				url: '/supervisor/timesheet/disapprove',

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

