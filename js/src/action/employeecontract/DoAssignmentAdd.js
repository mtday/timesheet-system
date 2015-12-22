
Ext.namespace("action.employeecontract");

action.employeecontract.DoAssignmentAdd = function(employee) {
	return new Ext.Action({
		id:      'action.employeecontract.doassignmentadd',
		text:    'Add',
		iconCls: 'icon-assignment-add',
		handler: function() {
			// Get the panel containing the form data.
			var formPanel = Ext.getCmp(
				'ui.panel.employeecontract.assignmentaddpanel');

			// Make sure the form is valid.
			if (!formPanel.getForm().isValid()) {
				// Display an error message.
				Ext.Msg.alert('Form Incomplete', 'Please resolve form ' +
					'validation problems before continuing.');
				return;
			}

			// Show the progress bar while the contract is being added.
			Ext.Msg.progress('Assigning Contract to Employee', 'Please ' +
				'wait while the contract is assigned to the employee...');

			// Create a new ServerIO object.
			var io = new util.io.ServerIO();

			// Submit the form.
			io.doFormRequest(formPanel, {
				// Set the URL.
				url: '/manager/assignment/add',

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

