
Ext.namespace("ui.grid");

ui.grid.ContractEmployeeGrid = Ext.extend(Ext.grid.GridPanel, {
	constructor: function(c) {
		// Make sure the correct parameters were specified.
		if (!c || !c.contract)
			throw "ContractEmployeeGrid requires a contract.";

		var contractEmployee = new data.model.ContractEmployee();

		var config = Ext.applyIf(c || {}, {
			id:               'ui.grid.contractemployeegrid',
			title:            'Employees Assigned to ' +
								c.contract.data.description,
			stripeRows:       true,
			autoExpandColumn: 'full_name',
			autoWidth:        true,
			height:           300,
			cm:               contractEmployee.getColumnModel(),
			loadMask:         true,
			store: new data.store.ContractEmployeeStore({
				contract: c.contract,
				day:      c.day
			}),
			tbar: new ui.tbar.ContractEmployeeToolbar({
				contract: c.contract,
				day:      c.day
			})
		});

		ui.grid.ContractEmployeeGrid.superclass.constructor.call(this, config);

		this.getSelectionModel().addListener('selectionchange', function(model) {
			// Get the number of selected rows.
			var count = model.getSelections().length;

			// Get the buttons.
			var employeeDel = Ext.getCmp('action.contractemployee.doassignmentdelete');
			var employeeUpd = Ext.getCmp('action.contractemployee.showassignmentupdate');

			// Update the buttons based on the selected rows.
			(count > 0) ? employeeDel.enable() : employeeDel.disable();
			(count == 1) ? employeeUpd.enable() : employeeUpd.disable();
		});
	},

	getSelectedIds: function() {
		// This will hold all the ids.
		var ids = [ ];

		// Get the selected records.
		var records = this.getSelectionModel().getSelections();

		// Iterate over the selected records.
		for (var i = 0; i < records.length; i++)
			// Add the id to the list.
			ids.push(records[i].data.id);

		// Return the ids.
		return ids;
	}
});

