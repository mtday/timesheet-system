
Ext.namespace("action.employee");

action.employee.DoEmployeeAdd = function() {
	return new Ext.Action({
		id:      'action.employee.doemployeeadd',
		text:    'Add',
		iconCls: 'icon-employee-add',
		handler: function() {
			// Get the panel containing the form data.
			var formPanel = Ext.getCmp('ui.panel.employee.employeeaddpanel');

			// Make sure the form is valid.
			if (!formPanel.getForm().isValid()) {
				// Display an error message.
				Ext.Msg.alert('Form Incomplete', 'Please resolve form ' +
					'validation problems before continuing.');
				return;
			}

			// Get the form values.
			var vals = formPanel.getForm().getValues();

			// Make sure the passwords are correct.
			if (vals.password != vals.confirm) {
				// Display an error message.
				Ext.Msg.alert('Invalid Password', 'The confirm password ' +
					'does not match the password specified.');
				return;
			}

			// Show the progress bar while the employee is being added.
			Ext.Msg.progress('Adding Employee',
				'Please wait while the employee is added...');

			// Create a new ServerIO object.
			var io = new util.io.ServerIO();

			// Submit the form.
			io.doFormRequest(formPanel, {
				// Set the URL.
				url: '/manager/employee/add',

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

