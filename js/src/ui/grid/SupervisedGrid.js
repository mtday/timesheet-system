
Ext.namespace("ui.grid");

ui.grid.SupervisedGrid = Ext.extend(Ext.grid.GridPanel, {
	constructor: function(c) {
		if (!c || !c.payPeriodStart)
			throw "SupervisedGrid requires a pay period start date."

		var timesheetStatus = new data.model.TimesheetStatus();
		var grid = this;

		var store = new data.store.SupervisedStore({
			payPeriodStart: c.payPeriodStart
		});
		store.setInactiveFilter();

		var config = Ext.applyIf(c || {}, {
			title:            'Supervised Employee Timesheets',
			id:               'ui.grid.supervisedgrid',
			stripeRows:       true,
			autoExpandColumn: 'full_name',
			autoWidth:        true,
			autoHeight:       true,
			tbar:             new ui.tbar.SupervisedToolbar(),
			cm:               timesheetStatus.getColumnModel(),
			loadMask:         true,
			store:            store
		});

		ui.grid.SupervisedGrid.superclass.constructor.call(this, config);

		this.getSelectionModel().addListener('selectionchange', function(model) {
			// Get the number of selected rows.
			var count = model.getSelections().length;
			var ids = grid.getSelectedIds();

			// Get the buttons.
			var view = Ext.getCmp('action.supervisor.doview');
			var edit = Ext.getCmp('action.supervisor.doedit');
			var audit = Ext.getCmp('action.supervisor.doviewauditlog');
			var approve = Ext.getCmp('action.supervisor.doapprove');
			var disapprove = Ext.getCmp('action.supervisor.dodisapprove');

			var allApproved = true;
			for (var s = 0; s < count && allApproved; s++)
				allApproved = model.getSelections()[s].data.approved == "1";

			var noneApproved = true;
			for (var s = 0; s < count && noneApproved; s++)
				noneApproved = model.getSelections()[s].data.approved == "0";

			var allCompleted = true;
			for (var s = 0; s < count && allCompleted; s++)
				allCompleted = model.getSelections()[s].data.completed == "1";

			// Update the buttons based on the selected rows.
			(ids.length > 0) ? view.enable() : view.disable();
			(ids.length > 0) ? edit.enable() : edit.disable();
			(ids.length == 1) ? audit.enable() : audit.disable();
			if (approve)
				(count > 0 && allCompleted && noneApproved) ?
					approve.enable() : approve.disable();
			if (disapprove)
				(count > 0 && allApproved) ? disapprove.enable() : disapprove.disable();
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

