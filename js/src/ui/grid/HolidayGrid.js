
Ext.namespace("ui.grid");

ui.grid.HolidayGrid = Ext.extend(Ext.grid.GridPanel, {
	constructor: function(c) {
		var holiday = new data.model.Holiday();

		var config = Ext.applyIf(c || {}, {
			title:            'Holidays',
			id:               'ui.grid.holidaygrid',
			store:            new data.store.HolidayStore(),
			stripeRows:       true,
			width:            510,
			height:           300,
			tbar:             new ui.tbar.HolidayToolbar(),
			cm:               holiday.getColumnModel(),
			loadMask:         true
		});

		ui.grid.HolidayGrid.superclass.constructor.call(this, config);

		this.getSelectionModel().addListener('selectionchange', function(model) {
			// Get the number of selected rows.
			var count = model.getSelections().length;

			// Get the buttons.
			var holidayDel = Ext.getCmp('action.holiday.doholidaydelete');
			var holidayUpd = Ext.getCmp('action.holiday.showholidayupdate');

			// Update the buttons based on the selected rows.
			if (holidayDel)
				(count > 0) ? holidayDel.enable() : holidayDel.disable();
			if (holidayUpd)
				(count == 1) ? holidayUpd.enable() : holidayUpd.disable();
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

