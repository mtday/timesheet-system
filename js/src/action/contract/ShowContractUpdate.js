
Ext.namespace("action.contract");

action.contract.ShowContractUpdate = function() {
	return new Ext.Action({
		id:       'action.contract.showcontractupdate',
		text:     'Update',
		iconCls:  'icon-contract-edit',
		disabled: true,
		handler: function() {
			// Get the update panel and the grid.
			var contractUpdPanel =
				Ext.getCmp('ui.panel.contract.contractupdatepanel');
			var contractGrid = Ext.getCmp('ui.grid.contractgrid');

			// Make sure the panel exists.
			if (!contractUpdPanel)
				contractUpdPanel = new ui.panel.contract.ContractUpdatePanel({
					renderTo: 'contract-update-panel'
				});

			// Hide the grid and show the panel.
			contractGrid.hide();
			contractUpdPanel.show();

			// Get the selected contract.
			var contract = contractGrid.getSelectionModel().getSelections()[0];

			// Set the focus.
			contractUpdPanel.setValues(contract);
		}
	});
}

