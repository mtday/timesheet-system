
Ext.namespace("data.store");

data.store.SupervisedStore = Ext.extend(Ext.data.JsonStore, {
	constructor: function(c) {
		if (!c || !c.payPeriodStart)
			throw "SupervisedStore requires a pay period start date."

		var timesheetStatus = new data.model.TimesheetStatus();

		var config = Ext.applyIf(c || {}, {
			url:        '/supervisor/status/json',
			root:       'status',
			autoLoad:   true,
			fields:     timesheetStatus.getRecord(),
			baseParams: {
				ppStart: c.payPeriodStart
			},
			listeners: {
				load: function(store, records, options) {
					// Get the filter checkbox.
					var filterCB = Ext.getCmp('ui.field.employee.inactive');

					// If the filter field exists and is not checked, then
					// hide all the inactive employees.
					if (filterCB)
						filterCB.getValue() ?
							store.clearInactiveFilter() :
							store.setInactiveFilter();
					else
						store.setInactiveFilter();
				}
			}
		});

		data.store.SupervisedStore.superclass.constructor.call(this, config);
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

