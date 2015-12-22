
Ext.namespace("ui.grid");

ui.grid.EmployeeGrid = Ext.extend(Ext.grid.GridPanel, {
	constructor: function(c) {
		var employee = new data.model.Employee();

		var config = Ext.applyIf(c || {}, {
			title:            'Employees',
			id:               'ui.grid.employeegrid',
			store:            new data.store.EmployeeStore(),
			stripeRows:       true,
			autoExpandColumn: 'full_name',
			autoWidth:        true,
			height:           300,
			tbar:             new ui.tbar.EmployeeToolbar(),
			cm:               employee.getColumnModel(),
			loadMask:         true
		});

		ui.grid.EmployeeGrid.superclass.constructor.call(this, config);

		this.getSelectionModel().addListener('selectionchange', function(model) {
			// Get the number of selected rows.
			var count = model.getSelections().length;

			// Get the buttons.
			var employeeDel = Ext.getCmp('action.employee.doemployeedelete');
			var employeeAct = Ext.getCmp('action.employee.doemployeeactivate');
			var employeeDea = Ext.getCmp('action.employee.doemployeedeactivate');
			var employeeUpd = Ext.getCmp('action.employee.showemployeeupdate');
			var employeeCon = Ext.getCmp('action.employee.showemployeecontracts');

			var allActive = true;
			for (var s = 0; s < count && allActive; s++)
				allActive = model.getSelections()[s].data.active == "1";

			var allInactive = true;
			for (var s = 0; s < count && allInactive; s++)
				allInactive = model.getSelections()[s].data.active == "0";

			// Update the buttons based on the selected rows.
			if (employeeDel)
				(count > 0) ? employeeDel.enable() : employeeDel.disable();
			if (employeeUpd)
				(count == 1) ? employeeUpd.enable() : employeeUpd.disable();
			if (employeeCon)
				(count == 1) ? employeeCon.enable() : employeeCon.disable();
			if (employeeAct)
				(count > 0 && allInactive) ?
					employeeAct.enable() : employeeAct.disable();
			if (employeeDea)
				(count > 0 && allActive) ?
					employeeDea.enable() : employeeDea.disable();
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

