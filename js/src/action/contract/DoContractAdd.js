
Ext.namespace("action.contract");

action.contract.DoContractAdd = function() {
	return new Ext.Action({
		id:      'action.contract.docontractadd',
		text:    'Add',
		iconCls: 'icon-contract-add',
		handler: function() {
			// Get the panel containing the form data.
			var formPanel = Ext.getCmp('ui.panel.contract.contractaddpanel');

			// Make sure the form is valid.
			if (!formPanel.getForm().isValid()) {
				// Display an error message.
				Ext.Msg.alert('Form Incomplete', 'Please resolve form ' +
					'validation problems before continuing.');
				return;
			}

			// Show the progress bar while the contract is being added.
			Ext.Msg.progress('Adding Contract',
				'Please wait while the contract is added...');

			// Create a new ServerIO object.
			var io = new util.io.ServerIO();

			// Submit the form.
			io.doFormRequest(formPanel, {
				// Set the URL.
				url: '/manager/contract/add',

				// The function to invoke after success.
				mysuccess: function(data) {
					// Get the grid.
					var grid = Ext.getCmp('ui.grid.contractgrid');

					// Reload the data store.
					grid.getStore().reload();
				}
			});
		}
	});
}

