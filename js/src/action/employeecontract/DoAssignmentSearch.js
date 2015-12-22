
Ext.namespace("action.employeecontract");

action.employeecontract.DoAssignmentSearch = function(employee) {
	return new Ext.Action({
		id:      'action.employeecontract.doassignmentsearch',
		iconCls: 'icon-search',
		handler: function() {
			// Get the text field.
			var txt = Ext.getCmp('ui.field.employeecontract.search').getValue();

			// Get the grid.
			var grid = Ext.getCmp('ui.grid.employeecontractgrid');

			// Check to see if the text is valid.
			if (txt != undefined && txt.length > 0) {
				// Get the regular expression.
				var r = new RegExp(txt, 'i');

				// Add the filter to the store.
				grid.getStore().filterBy(function(rec, recId) {
					// Check the searchable fields.
					return rec.data.description.match(r) ||
						   rec.data.contract_num.match(r) ||
						   rec.data.job_code.match(r) ||
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

