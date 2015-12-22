
Ext.namespace("action.employeecontract");

action.employeecontract.DoAssignmentUpdate = function(employee) {
	return new Ext.Action({
		id:      'action.employeecontract.doassignmentupdate',
		text:    'Update',
		iconCls: 'icon-assignment-edit',
		handler: function() {
			// Get the form panel.
			var formPanel = Ext.getCmp(
				'ui.panel.employeecontract.assignmentupdatepanel');

			// Make sure the form is valid.
			if (!formPanel.getForm().isValid()) {
				// Display an error message.
				Ext.Msg.alert('Form Incomplete', 'Please resolve form ' +
					'validation problems before continuing.');
				return;
			}

			// Show the progress bar while the contract assignment is being
			// saved.
			Ext.Msg.progress('Updating Contract Assignment',
				'Please wait while the contract assignment is saved...');

			// Create the ServerIO object.
			var io = new util.io.ServerIO();

			// Submit the form.
			io.doFormRequest(formPanel, {
				// Set the URL.
				url: '/manager/assignment/update',

				// The function to invoke after success.
				mysuccess: function(data) {
					// Get the grid.
					var grid = Ext.getCmp('ui.grid.employeecontractgrid');

					// Reload the data store.
					grid.getStore().reload();
				}
			});
		}
	});
}

