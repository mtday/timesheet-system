
Ext.namespace("action.employee");

action.employee.DoProfileUpdate = function() {
	return new Ext.Action({
		id:      'action.employee.doprofileupdate',
		text:    'Update',
		iconCls: 'icon-employee-edit',
		handler: function() {
			// Get the form panel.
			var form = Ext.getCmp('ui.panel.employee.profileupdatepanel');

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
			Ext.Msg.progress('Updating Profile',
				'Please wait while your profile is saved...');

			// Create the ServerIO object.
			var io = new util.io.ServerIO();

			// Submit the form.
			io.doFormRequest(form, {
				// Set the URL.
				url: '/user/profile/update',

				// The function to invoke after success.
				mysuccess: function(data) {
					// Nothing to do.
				}
			});
		}
	});
}

