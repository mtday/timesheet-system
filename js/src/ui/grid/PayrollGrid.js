
Ext.namespace("ui.grid");

ui.grid.PayrollGrid = Ext.extend(Ext.grid.GridPanel, {
	constructor: function(c) {
		if (!c || !c.payPeriodStart)
			throw "PayrollGrid requires a pay period start date."

		var timesheetStatus = new data.model.TimesheetStatus({ payroll: true });
		var grid = this;

		var store = new data.store.PayrollStore({
			payPeriodStart: c.payPeriodStart
		});
		store.setInactiveFilter();

		var config = Ext.applyIf(c || {}, {
			title:            'Payroll Timesheets',
			id:               'ui.grid.payrollgrid',
			stripeRows:       true,
			autoExpandColumn: 'full_name',
			autoWidth:        true,
			autoHeight:       true,
			tbar:             new ui.tbar.PayrollToolbar(),
			cm:               timesheetStatus.getColumnModel(),
			loadMask:         true,
			store:            store
		});

		ui.grid.PayrollGrid.superclass.constructor.call(this, config);

		this.getSelectionModel().addListener('selectionchange', function(model) {
			// Get the number of selected rows.
			var count = model.getSelections().length;
			var ids = grid.getSelectedIds();

			// Get the buttons.
			var view = Ext.getCmp('action.payroll.doview');
			var verify = Ext.getCmp('action.payroll.doverify');
			var unverify = Ext.getCmp('action.payroll.dounverify');
			var audit = Ext.getCmp('action.payroll.doviewauditlog');
			var exprt = Ext.getCmp('action.payroll.doexport');
			var unexprt = Ext.getCmp('action.payroll.dounexport');

			var allExported = true;
			for (var s = 0; s < count && allExported; s++)
				allExported = model.getSelections()[s].data.exported == "1";

			var noneExported = true;
			for (var s = 0; s < count && noneExported; s++)
				noneExported = model.getSelections()[s].data.exported == "0";

			var allVerified = true;
			for (var s = 0; s < count && allVerified; s++)
				allVerified = model.getSelections()[s].data.verified == "1";

			var noneVerified = true;
			for (var s = 0; s < count && noneVerified; s++)
				noneVerified = model.getSelections()[s].data.verified == "0";

			var allApproved = true;
			for (var s = 0; s < count && allApproved; s++)
				allApproved = model.getSelections()[s].data.approved == "1";

			// Update the buttons based on the selected rows.
			(ids.length > 0) ? view.enable() : view.disable();
			(ids.length == 1) ? audit.enable() : audit.disable();
			if (verify)
				(count > 0 && allApproved && noneVerified) ?
					verify.enable() : verify.disable();
			if (unverify)
				(count > 0 && allVerified) ? unverify.enable() : unverify.disable();
			if (exprt)
				(count > 0 && allVerified && noneExported) ?
					exprt.enable() : exprt.disable();
			if (unexprt)
				(count > 0 && allVerified && allExported) ?
					unexprt.enable() : unexprt.disable();
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
			if (records[i].data.id)
				ids.push(records[i].data.id);

		// Return the ids.
		return ids;
	}
});

