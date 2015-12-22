
Ext.namespace("data.store");

data.store.ContractStore = Ext.extend(Ext.data.JsonStore, {
	constructor: function(c) {
		var contract = new data.model.Contract();

		var config = Ext.applyIf(c || {}, {
			url:        '/manager/contract/json',
			root:       'contracts',
			autoLoad:   true,
			fields:     contract.getRecord(),
			baseParams: {
				regularOnly: c ? c.regularOnly : undefined
			},
			listeners: {
				load: function(store, records, options) {
					// Get the filter checkbox.
					var filterCB = Ext.getCmp('ui.field.contract.inactive');

					// If the filter field exists and is not checked, then
					// hide all the inactive contracts.
					if (filterCB)
						filterCB.getValue() ?
							store.clearInactiveFilter() :
							store.setInactiveFilter();
				}
			}
		});

		data.store.ContractStore.superclass.constructor.call(this, config);
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

