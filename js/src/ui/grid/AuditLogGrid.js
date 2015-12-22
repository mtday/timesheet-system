
Ext.namespace("ui.grid");

ui.grid.AuditLogGrid = Ext.extend(Ext.grid.GridPanel, {
	constructor: function(c) {
		if (!c || !c.type || !c.id)
			throw "AuditLogGrid requires type and id fields.";

		var auditLog = new data.model.AuditLog();

		var config = Ext.applyIf(c || {}, {
			id:               'ui.grid.auditloggrid',
			border:           false,
			stripeRows:       true,
			autoExpandColumn: 'log',
			autoWidth:        true,
			height:           300,
			cm:               auditLog.getColumnModel(),
			loadMask:         true,
			store: new data.store.AuditLogStore({
				type: c.type,
				id:   c.id
			})
		});

		ui.grid.AuditLogGrid.superclass.constructor.call(this, config);
	}
});

