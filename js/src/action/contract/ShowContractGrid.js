
Ext.namespace("action.contract");

action.contract.ShowContractGrid = function() {
	return new Ext.Action({
		id:      'action.contract.showcontractgrid',
		text:    'Back to Contracts',
		iconCls: 'icon-contract-go',
		handler: function() {
			// Get the panels.
			var contractAddPanel =
				Ext.getCmp('ui.panel.contract.contractaddpanel');
			var contractUpdPanel =
				Ext.getCmp('ui.panel.contract.contractupdatepanel');
			var contractEmpPanel =
				Ext.getCmp('ui.panel.contract.contractemployeepanel');
			var assignmentGrid =
				Ext.getCmp('ui.grid.contractemployeegrid');

			// Hide the panels.
			if (contractAddPanel) contractAddPanel.destroy();
			if (contractUpdPanel) contractUpdPanel.destroy();
			if (contractEmpPanel) contractEmpPanel.destroy();
			if (assignmentGrid) assignmentGrid.destroy();

			// Show the grid.
			Ext.getCmp('ui.grid.contractgrid').show();
		}
	});
}

