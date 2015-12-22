
Ext.namespace("data.store");

data.store.ContractEmployeeStore = Ext.extend(Ext.data.JsonStore, {
	constructor: function(c) {
		// Make sure the correct parameters were specified.
		if (!c || !c.contract)
			throw "ContractEmployeeStore requires a contract.";

		var contractEmployee = new data.model.ContractEmployee();

		this.contract = c.contract;

		var config = Ext.applyIf(c || {}, {
			url:      '/manager/employee/contract',
			root:     'employees',
			autoLoad: true,
			fields:   contractEmployee.getRecord(),
			baseParams: {
				id:  c.contract.data.id,
				day: c.day
			}
		});

		data.store.ContractEmployeeStore.superclass.constructor.call(this, config);
	},

	setAsOf: function(newDate) {
		this.reload({
			params: {
				id:  this.contract.data.id,
				day: newDate.format('Y-m-d')
			}
		});
	}
});

