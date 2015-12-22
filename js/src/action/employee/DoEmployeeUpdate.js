
Ext.namespace("action.employee");

action.employee.DoEmployeeUpdate = function() {
	return new Ext.Action({
		id:      'action.employee.doemployeeupdate',
		text:    'Update',
		iconCls: 'icon-employee-edit',
		handler: function() {
			// Get the form panel.
			var form = Ext.getCmp('ui.panel.employee.employeeupdatepanel').form;

			// Make sure the form is valid.
			if (!form.getForm().isValid()) {
				// Display an error message.
				Ext.Msg.alert('Form Incomplete', 'Please resolve form ' +
					'validation problems before continuing.');
				return;
			}

			// Get the form values.
			var vals = form.getForm().getValues();

			// Make sure the passwords are correct.
			if (vals.password && vals.password != vals.confirm) {
				// Display an error message.
				Ext.Msg.alert('Invalid Password', 'The confirm password ' +
					'does not match the password specified.');
				return;
			}

			// Show the progress bar while the employee is being saved.
			Ext.Msg.progress('Updating Employee',
				'Please wait while the employee is saved...');

			// Create the ServerIO object.
			var io = new util.io.ServerIO();

			// Submit the form.
			io.doFormRequest(form, {
				// Set the URL.
				url: '/manager/employee/update',

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

