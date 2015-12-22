
Ext.namespace("action.contractemployee");

action.contractemployee.DoAssignmentAdd = function(contract) {
	return new Ext.Action({
		id:      'action.contractemployee.doassignmentadd',
		text:    'Add',
		iconCls: 'icon-assignment-add',
		handler: function() {
			// Get the panel containing the form data.
			var formPanel = Ext.getCmp(
				'ui.panel.contractemployee.assignmentaddpanel');

			// Make sure the form is valid.
			if (!formPanel.getForm().isValid()) {
				// Display an error message.
				Ext.Msg.alert('Form Incomplete', 'Please resolve form ' +
					'validation problems before continuing.');
				return;
			}

			// Show the progress bar while the contract is being added.
			Ext.Msg.progress('Assigning Employee to Contract', 'Please ' +
				'wait while the employee is assigned to the contract...');

			// Create a new ServerIO object.
			var io = new util.io.ServerIO();

			// Submit the form.
			io.doFormRequest(formPanel, {
				// Set the URL.
				url: '/manager/assignment/add',

				// The function to invoke after success.
				mysuccess: function(data) {
					// Get the grid.
					var grid = Ext.getCmp('ui.grid.contractemployeegrid');

					// Reload the data store.
					grid.getStore().reload();
				}
			});
		}
	});
}

