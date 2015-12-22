
Ext.namespace("action.employee");

action.employee.DoEmployeeActivate = function() {
	return new Ext.Action({
		id:       'action.employee.doemployeeactivate',
		text:     'Activate',
		iconCls:  'icon-employee-edit',
		disabled: true,
		handler: function() {
			// Get the grid.
			var grid = Ext.getCmp('ui.grid.employeegrid');

			// Generate the array of ids to activate.
			var ids = grid.getSelectedIds();

			// Check to see if we have multiple employees to activate.
			var e = ids.length > 1 ? 'employees' : 'employee';
			var E = ids.length > 1 ? 'Employees' : 'Employee';

			// Show the progress bar while the employee is being saved.
			Ext.Msg.progress('Activating ' + E,
				'Please wait while activating the ' + e + '...');

			// Create the ServerIO object.
			var io = new util.io.ServerIO();

			// Submit the form.
			io.doAjaxRequest({
				// Set the URL.
				url: '/manager/employee/activate',

				// Add the parameters to send to the server.
				params: {
					ids: ids.join(',')
				},

				// The function to invoke after success.
				mysuccess: function(data) {
					// Get the grid.
					var grid = Ext.getCmp('ui.grid.employeegrid');

					// Reload the data store.
					grid.getStore().reload();
				}
			});
		}
	});
}

