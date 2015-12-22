
Ext.namespace("action.contract");

action.contract.DoContractDelete = function() {
	return new Ext.Action({
		id:       'action.contract.docontractdelete',
		text:     'Delete',
		iconCls:  'icon-contract-delete',
		disabled: true,
		handler: function() {
			// Get the grid.
			var grid = Ext.getCmp('ui.grid.contractgrid');

			// Generate the array of ids to delete.
			var ids = grid.getSelectedIds();

			// Check to see if we have multiple contracts to delete.
			var c = ids.length > 1 ? 'contracts' : 'contract';
			var C = ids.length > 1 ? 'Contracts' : 'Contract';

			// Confirm the deletion of the contracts.
			Ext.Msg.confirm('Are you sure?',
				'Are you sure you want to delete the specified ' + c + '?',

				// Handle the confirmation response.
				function(btn) {
					// Make sure the user clicked the 'yes' button.
					if (btn != 'yes')
						return;

					// Let the user know what we are doing.
					Ext.Msg.progress('Deleting ' + C,
						'Please wait while removing the ' + c + '...');

					// Create the ServerIO object.
					var io = new util.io.ServerIO();

					// Send the Ajax request.
					io.doAjaxRequest({
						// Add the URL.
						url: '/manager/contract/delete',

						// Add the parameters to send to the server.
						params: {
							ids: ids.join(',')
						},

						// Add the mysuccess function.
						mysuccess: function(data) {
							// Get the grid.
							var grid = Ext.getCmp('ui.grid.contractgrid');

							// Reload the data store.
							grid.getStore().reload();
						}
					});
				}
			);
		}
	});
}

