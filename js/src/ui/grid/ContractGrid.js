
Ext.namespace("ui.grid");

ui.grid.ContractGrid = Ext.extend(Ext.grid.GridPanel, {
	constructor: function(c) {
		var contract = new data.model.Contract();

		var config = Ext.applyIf(c || {}, {
			title:            'Contracts',
			id:               'ui.grid.contractgrid',
			store:            new data.store.ContractStore(),
			stripeRows:       true,
			autoExpandColumn: 'description',
			autoWidth:        true,
			height:           300,
			tbar:             new ui.tbar.ContractToolbar(),
			cm:               contract.getColumnModel(),
			loadMask:         true
		});

		ui.grid.ContractGrid.superclass.constructor.call(this, config);

		this.getSelectionModel().addListener('selectionchange', function(model) {
			// Get the number of selected rows.
			var count = model.getSelections().length;

			// Get the buttons.
			var contractDel = Ext.getCmp('action.contract.docontractdelete');
			var contractAct = Ext.getCmp('action.contract.docontractactivate');
			var contractDea = Ext.getCmp('action.contract.docontractdeactivate');
			var contractUpd = Ext.getCmp('action.contract.showcontractupdate');
			var contractEmp = Ext.getCmp('action.contract.showcontractemployees');

			var allActive = true;
			for (var s = 0; s < count && allActive; s++)
				allActive = model.getSelections()[s].data.active == "1";

			var allInactive = true;
			for (var s = 0; s < count && allInactive; s++)
				allInactive = model.getSelections()[s].data.active == "0";

			// Update the buttons based on the selected rows.
			if (contractDel)
				(count > 0) ? contractDel.enable() : contractDel.disable();
			if (contractUpd)
				(count == 1) ? contractUpd.enable() : contractUpd.disable();
			if (contractEmp)
				(count == 1 && model.getSelections()[0].data.admin == "0") ?
					contractEmp.enable() : contractEmp.disable();
			if (contractAct)
				(count > 0 && allInactive) ?
					contractAct.enable() : contractAct.disable();
			if (contractDea)
				(count > 0 && allActive) ?
					contractDea.enable() : contractDea.disable();
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

