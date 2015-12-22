
Ext.namespace("ui.tbar");

ui.tbar.EmployeeContractToolbar = Ext.extend(Ext.Toolbar, {
	constructor: function(c) {
		// Make sure the correct parameters were specified.
		if (!c || !c.employee)
			throw "EmployeeContractToolbar requires an employee.";

		var config = Ext.applyIf(c || {}, {
			items: [
				new action.employeecontract.ShowAssignmentAdd(c.employee),
				new action.employeecontract.ShowAssignmentUpdate(c.employee),
				new action.employeecontract.DoAssignmentDelete(c.employee),

				'-',

				new action.employee.ShowEmployeeGrid(),

				'->',

				new Ext.form.Label({
					text: 'As of',
					style: 'padding-right:10px;'
				}),
				new Ext.form.DateField({
					id: 'ui.field.employeecontract.asof',
					value: c.day,
					listeners: {
						select: function(field, newVal) {
							var grid = Ext.getCmp('ui.grid.employeecontractgrid');
							grid.store.setAsOf(newVal);
						},
						change: function(field, newVal, oldVal) {
							var grid = Ext.getCmp('ui.grid.employeecontractgrid');
							grid.store.setAsOf(newVal);
						}
					}
				}),

				'-',

				new Ext.form.TextField({
					id: 'ui.field.employeecontract.search',
					width: 100,
					listeners: {
						specialkey: function(tf, evt) {
							// Listen for the Enter key.
							if (evt.ENTER == evt.getKey()) {
								// Get the search action.
								var search = Ext.getCmp(
									'action.employeecontract.doassignmentsearch');

								// Invoke the handler.
								search.handler();
							}
						}
					}
				}),
				new action.employeecontract.DoAssignmentSearch(c.employee)
			]
		});

		ui.tbar.EmployeeContractToolbar.superclass.constructor.call(this, config);
	}
});

