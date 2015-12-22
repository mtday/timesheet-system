
Ext.namespace("data.store");

data.store.SupervisorStore = Ext.extend(Ext.data.JsonStore, {
	constructor: function(c) {
		if (!c || !c.employee)
			throw "SupervisorStore requires an employee.";

		var supervisor = new data.model.Supervisor();

		var config = Ext.applyIf(c || {}, {
			url:        '/user/supervisor/json',
			root:       'supervisors',
			autoLoad:   true,
			fields:     supervisor.getRecord(),
			baseParams: {
				id: c.employee.data.id
			}
		});

		data.store.SupervisorStore.superclass.constructor.call(this, config);
	}
});

