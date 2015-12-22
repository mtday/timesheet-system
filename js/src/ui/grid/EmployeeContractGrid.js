
Ext.namespace("ui.grid");

ui.grid.EmployeeContractGrid = Ext.extend(Ext.grid.GridPanel, {
	constructor: function(c) {
		// Make sure the correct parameters were specified.
		if (!c || !c.employee)
			throw "EmployeeContractGrid requires an employee.";

		var employeeContract = new data.model.EmployeeContract();

		var config = Ext.applyIf(c || {}, {
			id:               'ui.grid.employeecontractgrid',
			title:            'Contracts Assigned to ' +
								c.employee.data.full_name,
			stripeRows:       true,
			autoExpandColumn: 'description',
			autoWidth:        true,
			height:           300,
			cm:               employeeContract.getColumnModel(),
			loadMask:         true,
			store: new data.store.EmployeeContractStore({
				employee:    c.employee,
				day:         c.day,
				regularOnly: true
			}),
			tbar: new ui.tbar.EmployeeContractToolbar({
				employee: c.employee,
				day:      c.day
			})
		});

		ui.grid.EmployeeContractGrid.superclass.constructor.call(this, config);

		this.getSelectionModel().addListener('selectionchange', function(model) {
			// Get the number of selected rows.
			var count = model.getSelections().length;

			// Get the buttons.
			var contractDel = Ext.getCmp('action.employeecontract.doassignmentdelete');
			var contractUpd = Ext.getCmp('action.employeecontract.showassignmentupdate');

			// Update the buttons based on the selected rows.
			(count > 0) ? contractDel.enable() : contractDel.disable();
			(count == 1) ? contractUpd.enable() : contractUpd.disable();
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

