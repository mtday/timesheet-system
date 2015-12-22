
Ext.namespace("action.employee");

action.employee.DoEmployeeDeactivate = function() {
	return new Ext.Action({
		id:       'action.employee.doemployeedeactivate',
		text:     'Deactivate',
		iconCls:  'icon-employee-edit',
		disabled: true,
		handler: function() {
			// Get the grid.
			var grid = Ext.getCmp('ui.grid.employeegrid');

			// Generate the array of ids to deactivate.
			var ids = grid.getSelectedIds();

			// Check to see if we have multiple employees to deactivate.
			var e = ids.length > 1 ? 'employees' : 'employee';
			var E = ids.length > 1 ? 'Employees' : 'Employee';

			// Show the progress bar while the employee is being saved.
			Ext.Msg.progress('Deactivating ' + E,
				'Please wait while deactivating the ' + e + '...');

			// Create the ServerIO object.
			var io = new util.io.ServerIO();

			// Submit the form.
			io.doAjaxRequest({
				// Set the URL.
				url: '/manager/employee/deactivate',

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

