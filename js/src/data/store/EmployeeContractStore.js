
Ext.namespace("data.store");

data.store.EmployeeContractStore = Ext.extend(Ext.data.JsonStore, {
	constructor: function(c) {
		// Make sure the correct parameters were specified.
		if (!c || !c.employee)
			throw "EmployeeContractStore requires an employee.";

		var employeeContract = new data.model.EmployeeContract();

		this.employee = c.employee;
		this.regularOnly = c.regularOnly

		var config = Ext.applyIf(c || {}, {
			url:      '/manager/contract/employee',
			root:     'contracts',
			autoLoad: true,
			fields:   employeeContract.getRecord(),
			baseParams: {
				id:          c.employee.data.id,
				day:         c.day,
				regularOnly: c.regularOnly
			}
		});

		data.store.EmployeeContractStore.superclass.constructor.call(this, config);
	},

	setAsOf: function(newDate) {
		this.reload({
			params: {
				id:          this.employee.data.id,
				regularOnly: this.regularOnly,
				day:         newDate.format('Y-m-d')
			}
		});
	}
});

