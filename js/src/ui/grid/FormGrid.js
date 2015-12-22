
Ext.namespace("ui.grid");

ui.grid.FormGrid = Ext.extend(Ext.grid.GridPanel, {
	constructor: function(c) {
		var form = new data.model.Form();

		var config = Ext.applyIf(c || {}, {
			title:            'Forms',
			id:               'ui.grid.formgrid',
			store:            new data.store.FormStore(),
			stripeRows:       true,
			autoExpandColumn: 'name',
			width:            510,
			height:           350,
			tbar:             new ui.tbar.FormToolbar(),
			cm:               form.getColumnModel(),
			loadMask:         true
		});

		ui.grid.FormGrid.superclass.constructor.call(this, config);

		this.getSelectionModel().addListener('selectionchange', function(model) {
			// Get the number of selected rows.
			var count = model.getSelections().length;

			// Get the buttons.
			var formDL = Ext.getCmp('action.form.doformdownload');
			var formDel = Ext.getCmp('action.form.doformdelete');
			var formUpd = Ext.getCmp('action.form.showformupdate');

			// Update the buttons based on the selected rows.
			if (formDel)
				(count > 0) ? formDel.enable() : formDel.disable();
			if (formUpd)
				(count == 1) ? formUpd.enable() : formUpd.disable();

			// Update the download action.
			if (formDL) {
				if (count == 1) {
					formDL.enable();
					var parts = model.getSelections()[0].data.file_name.split('.');
					var type = parts[parts.length - 1].toLowerCase();
					formDL.setIconClass('icon-' + type);
				} else {
					formDL.disable();
					formDL.setIconClass('icon-unknown');
				}
			}

			// Update the description panel.
			var descPnl = Ext.getCmp('ui.panel.form.formdescriptionpanel');
			if (descPnl)
				(count == 1) ?
					descPnl.showDescription(model.getSelections()[0]) :
					descPnl.showDefaultDescription();
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

