
Ext.namespace("action.contract");

action.contract.DoContractUpdate = function() {
	return new Ext.Action({
		id:      'action.contract.docontractupdate',
		text:    'Update',
		iconCls: 'icon-contract-edit',
		handler: function() {
			// Get the form panel.
			var formPanel = Ext.getCmp('ui.panel.contract.contractupdatepanel');

			// Make sure the form is valid.
			if (!formPanel.getForm().isValid()) {
				// Display an error message.
				Ext.Msg.alert('Form Incomplete', 'Please resolve form ' +
					'validation problems before continuing.');
				return;
			}

			// Show the progress bar while the contract is being saved.
			Ext.Msg.progress('Updating Contract',
				'Please wait while the contract is saved...');

			// Create the ServerIO object.
			var io = new util.io.ServerIO();

			// Submit the form.
			io.doFormRequest(formPanel, {
				// Set the URL.
				url: '/manager/contract/update',

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

