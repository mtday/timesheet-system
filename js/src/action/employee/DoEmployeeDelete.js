
Ext.namespace("action.employee");

action.employee.DoEmployeeDelete = function() {
	return new Ext.Action({
		id:       'action.employee.doemployeedelete',
		text:     'Delete',
		iconCls:  'icon-employee-delete',
		disabled: true,
		handler: function() {
			// Get the grid.
			var grid = Ext.getCmp('ui.grid.employeegrid');

			// Generate the array of ids to delete.
			var ids = grid.getSelectedIds();

			// Check to see if we have multiple employees to delete.
			var e = ids.length > 1 ? 'employees' : 'employee';
			var E = ids.length > 1 ? 'Employees' : 'Employee';

			// Confirm the deletion of the employees.
			Ext.Msg.confirm('Are you sure?',
				'Are you sure you want to delete the specified ' + e + '?',

				// Handle the confirmation response.
				function(btn) {
					// Make sure the user clicked the 'yes' button.
					if (btn != 'yes')
						return;

					// Let the user know what we are doing.
					Ext.Msg.progress('Deleting ' + E,
						'Please wait while removing the ' + e + '...');

					// Create the ServerIO object.
					var io = new util.io.ServerIO();

					// Send the Ajax request.
					io.doAjaxRequest({
						// Add the URL.
						url: '/manager/employee/delete',

						// Add the parameters to send to the server.
						params: {
							ids: ids.join(',')
						},

						// Add the mysuccess function.
						mysuccess: function(data) {
							// Get the grid.
							var grid = Ext.getCmp('ui.grid.employeegrid');

							// Reload the data store.
							grid.getStore().reload();
						}
					});
				}
			);
		}
	});
}

