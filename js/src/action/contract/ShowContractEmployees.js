
Ext.namespace("action.contract");

action.contract.ShowContractEmployees = function() {
	return new Ext.Action({
		id:       'action.contract.showcontractemployees',
		text:     'Assigned Employees',
		iconCls:  'icon-employees',
		disabled: true,
		handler: function() {
			// Get the contract grid.
			var contractGrid = Ext.getCmp('ui.grid.contractgrid');

			// Get the selected contract.
			var contract = contractGrid.getSelectionModel().
				getSelections()[0];

			// Create the contract employee grid.
			var assignmentGrid = new ui.grid.ContractEmployeeGrid({
				contract: contract,
				day:      (new Date()).format('Y-m-d'),
				renderTo: 'contract-employee-grid'
			});

			// Hide the contract grid and show the assignment grid.
			contractGrid.hide();
			assignmentGrid.show();
		}
	});
}

