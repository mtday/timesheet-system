
Ext.namespace("action.form");

action.form.DoFormSearch = function() {
	return new Ext.Action({
		id:      'action.form.doformsearch',
		iconCls: 'icon-search',
		handler: function() {
			// Get the text field.
			var txt = Ext.getCmp('ui.field.form.search').getValue();

			// Get the grid.
			var grid = Ext.getCmp('ui.grid.formgrid');

			// Check to see if the text is valid.
			if (txt != undefined && txt.length > 0) {
				// Get the regular expression.
				var r = new RegExp(txt, 'i');

				// Add the filter to the store.
				grid.getStore().filterBy(function(rec, recId) {
					// Check the searchable fields.
					return rec.data.name.match(r) ||
						   rec.data.file_name.match(r) ||
						   ("" + rec.data.description).match(r) ||
						   rec.data.last_update.match(r);
				});
			} else
				// Clear the filter on the store.
				grid.getStore().clearFilter();
		}
	});
}

