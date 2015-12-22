
Ext.namespace("data.store");

data.store.AuditLogStore = Ext.extend(Ext.data.JsonStore, {
	constructor: function(c) {
		if (!c || !c.type || !c.id)
			throw "AuditLogStore requires type and id fields.";

		var auditLog = new data.model.AuditLog();

		var config = Ext.applyIf(c || {}, {
			url:      '/' + c.type + '/timesheet/audit',
			root:     'logs',
			autoLoad: true,
			fields:   auditLog.getRecord(),
			baseParams: {
				id: c.id
			}
		});

		data.store.AuditLogStore.superclass.constructor.call(this, config);
	}
});

