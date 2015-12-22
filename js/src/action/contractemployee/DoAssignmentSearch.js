
Ext.namespace("action.contractemployee");

action.contractemployee.DoAssignmentSearch = function(contract) {
	return new Ext.Action({
		id:      'action.contractemployee.doassignmentsearch',
		iconCls: 'icon-search',
		handler: function() {
			// Get the text field.
			var txt = Ext.getCmp('ui.field.contractemployee.search').getValue();

			// Get the grid.
			var grid = Ext.getCmp('ui.grid.contractemployeegrid');

			// Check to see if the text is valid.
			if (txt != undefined && txt.length > 0) {
				// Get the regular expression.
				var r = new RegExp(txt, 'i');

				// Add the filter to the store.
				grid.getStore().filterBy(function(rec, recId) {
					// Check the searchable fields.
					return rec.data.full_name.match(r) ||
						   rec.data.login.match(r) ||
						   rec.data.division.match(r) ||
						   rec.data.personnel_type.match(r) ||
						   rec.data.email.match(r) ||
						   rec.data.labor_cat.match(r) ||
						   rec.data.item_name.match(r) ||
						   (rec.data.start &&
								rec.data.start.match(r)) ||
						   (rec.data.end &&
								rec.data.end.match(r));
				});
			} else
				// Clear the filter on the store.
				grid.getStore().clearFilter();
		}
	});
}

