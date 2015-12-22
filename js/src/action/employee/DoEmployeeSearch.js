
Ext.namespace("action.employee");

action.employee.DoEmployeeSearch = function() {
	return new Ext.Action({
		id:      'action.employee.doemployeesearch',
		iconCls: 'icon-search',
		handler: function() {
			// Get the text field.
			var txt = Ext.getCmp('ui.field.employee.search').getValue();

			// Get the grid.
			var grid = Ext.getCmp('ui.grid.employeegrid');

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
						   rec.data.email.match(r);
				});
			} else
				// Clear the filter on the store.
				grid.getStore().clearFilter();
		}
	});
}

