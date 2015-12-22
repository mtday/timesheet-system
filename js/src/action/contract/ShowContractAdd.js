
Ext.namespace("action.contract");

action.contract.ShowContractAdd = function() {
	return new Ext.Action({
		id:      'action.contract.showcontractadd',
		text:    'Add',
		iconCls: 'icon-contract-add',
		handler: function() {
			// Get the add panel and the grid.
			var contractAddPanel =
				Ext.getCmp('ui.panel.contract.contractaddpanel');
			var contractGrid = Ext.getCmp('ui.grid.contractgrid');

			// Make sure the panel exists.
			if (!contractAddPanel)
				contractAddPanel = new ui.panel.contract.ContractAddPanel({
					renderTo: 'contract-add-panel'
				});

			// Hide the grid and show the panel.
			contractGrid.hide();
			contractAddPanel.show();

			// Set the focus.
			contractAddPanel.setInitialFocus();
		}
	});
}

