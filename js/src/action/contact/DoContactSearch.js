
Ext.namespace("action.contact");

action.contact.DoContactSearch = function() {
	return new Ext.Action({
		id:      'action.contact.docontactsearch',
		iconCls: 'icon-search',
		handler: function() {
			// Get the text field.
			var txt = Ext.getCmp('ui.field.contact.search').getValue();

			// Get the grid.
			var grid = Ext.getCmp('ui.grid.contactgrid');

			// Check to see if the text is valid.
			if (txt != undefined && txt.length > 0) {
				// Get the regular expression.
				var r = new RegExp(txt, 'i');

				// Add the filter to the store.
				grid.getStore().filterBy(function(rec, recId) {
					// Check the searchable fields.
					return rec.data.contact_name.match(r) ||
						   (rec.data.street && rec.data.street.match(r)) ||
						   (rec.data.city && rec.data.city.match(r)) ||
						   (rec.data.state && rec.data.state.match(r)) ||
						   (rec.data.zip && rec.data.zip.match(r)) ||
						   (rec.data.poc_name && rec.data.poc_name.match(r)) ||
						   (rec.data.poc_title && rec.data.poc_title.match(r)) ||
						   (rec.data.poc_phone && rec.data.poc_phone.match(r)) ||
						   (rec.data.poc_fax && rec.data.poc_fax.match(r)) ||
						   (rec.data.poc_email && rec.data.poc_email.match(r));
				});
			} else
				// Clear the filter on the store.
				grid.getStore().clearFilter();
		}
	});
}

