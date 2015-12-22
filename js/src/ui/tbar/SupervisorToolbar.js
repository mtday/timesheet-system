
Ext.namespace("ui.tbar");

ui.tbar.SupervisorToolbar = Ext.extend(Ext.Toolbar, {
	constructor: function(c) {
		if (!c || !c.employee)
			throw "SupervisorToolbar requires an employee.";

		var config = Ext.applyIf(c || {}, {
			items: [
				new action.supervisor.ShowSupervisorAdd(c.employee),
				new action.supervisor.DoSupervisorDelete(c.employee)
			]
		});

		ui.tbar.SupervisorToolbar.superclass.constructor.call(this, config);
	}
});

