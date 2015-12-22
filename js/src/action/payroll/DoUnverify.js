
Ext.namespace("action.payroll");

action.payroll.DoUnverify = function() {
	return new Ext.Action({
		id:       'action.payroll.dounverify',
		text:     'Unverify',
		iconCls:  'icon-timesheet-unverify',
		disabled: true,
		handler: function() {
			// Get the grid.
			var grid = Ext.getCmp('ui.grid.payrollgrid');

			// Generate the array of ids to unverify.
			var ids = grid.getSelectedIds();

			// Check to see if we have multiple timesheets to unverify.
			var t = ids.length > 1 ? 'timesheets' : 'timesheet';
			var T = ids.length > 1 ? 'Timesheets' : 'Timesheet';

			// Show the progress bar while the employee is being saved.
			Ext.Msg.progress('Unverifying ' + T,
				'Please wait while unverifying the ' + t + '...');

			// Create the ServerIO object.
			var io = new util.io.ServerIO();

			// Submit the form.
			io.doAjaxRequest({
				// Set the URL.
				url: '/payroll/timesheet/unverify',

				// Add the parameters to send to the server.
				params: {
					ids: ids.join(',')
				},

				// The function to invoke after success.
				mysuccess: function(data) {
					// Get the grid.
					var grid = Ext.getCmp('ui.grid.payrollgrid');

					// Reload the data store.
					grid.getStore().reload();
				}
			});
		}
	});
}

