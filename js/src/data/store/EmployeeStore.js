
Ext.namespace("data.store");

data.store.EmployeeStore = Ext.extend(Ext.data.JsonStore, {
	constructor: function(c) {
		var employee = new data.model.Employee();

		var config = Ext.applyIf(c || {}, {
			url:        '/manager/employee/json',
			root:       'employees',
			autoLoad:   true,
			fields:     employee.getRecord(),
			baseParams: {
				activeOnly: c ? c.activeOnly : undefined
			},
			listeners: {
				load: function(store, records, options) {
					// If specific configuration options were passed in,
					// don't do any filtering.
					if (c) return;

					// Get the filter checkbox.
					var filterCB = Ext.getCmp('ui.field.employee.inactive');

					// If the filter field exists and is not checked, then
					// hide all the inactive employees.
					if (filterCB)
						filterCB.getValue() ?
							store.clearInactiveFilter() :
							store.setInactiveFilter();
				}
			}
		});

		data.store.EmployeeStore.superclass.constructor.call(this, config);
	},

	setInactiveFilter: function() {
		this.filterBy(function(record) {
			// Only return true if the record is active.
			return record.data.active == "1";
		});
	},

	clearInactiveFilter: function() {
		this.clearFilter(false);
	}
});

