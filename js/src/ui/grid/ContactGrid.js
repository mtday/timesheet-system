
Ext.namespace("ui.grid");

ui.grid.ContactGrid = Ext.extend(Ext.grid.GridPanel, {
	constructor: function(c) {
		var contact = new data.model.Contact();

		var config = Ext.applyIf(c || {}, {
			title:            'Contacts',
			id:               'ui.grid.contactgrid',
			store:            new data.store.ContactStore(),
			stripeRows:       true,
			autoExpandColumn: 'company_name',
			autoWidth:        true,
			autoHeight:       true,
			tbar:             new ui.tbar.ContactToolbar(),
			cm:               contact.getColumnModel(),
			loadMask:         true
		});

		ui.grid.ContactGrid.superclass.constructor.call(this, config);

		this.getSelectionModel().addListener('selectionchange', function(model) {
			// Get the number of selected rows.
			var count = model.getSelections().length;

			// Get the buttons.
			var contactDel = Ext.getCmp('action.contact.docontactdelete');
			var contactUpd = Ext.getCmp('action.contact.showcontactupdate');

			// Update the buttons based on the selected rows.
			if (contactDel)
				(count > 0) ? contactDel.enable() : contactDel.disable();
			if (contactUpd)
				(count == 1) ? contactUpd.enable() : contactUpd.disable();
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

