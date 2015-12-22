
Ext.namespace("action.contract");

action.contract.DoContractActivate = function() {
	return new Ext.Action({
		id:       'action.contract.docontractactivate',
		text:     'Activate',
		iconCls:  'icon-contract-edit',
		disabled: true,
		handler: function() {
			// Get the grid.
			var grid = Ext.getCmp('ui.grid.contractgrid');

			// Generate the array of ids to delete.
			var ids = grid.getSelectedIds();

			// Check to see if we have multiple contracts to delete.
			var c = ids.length > 1 ? 'contracts' : 'contract';
			var C = ids.length > 1 ? 'Contracts' : 'Contract';

			// Show the progress bar while the contract is being saved.
			Ext.Msg.progress('Activating ' + C,
				'Please wait while activating the ' + c + '...');

			// Create the ServerIO object.
			var io = new util.io.ServerIO();

			// Submit the form.
			io.doAjaxRequest({
				// Set the URL.
				url: '/manager/contract/activate',

				// Add the parameters to send to the server.
				params: {
					ids: ids.join(',')
				},

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

