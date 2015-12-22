
Ext.namespace("ui.tbar");

ui.tbar.EmployeeToolbar = Ext.extend(Ext.Toolbar, {
	constructor: function(c) {
		var config = Ext.applyIf(c || {}, {
			items: [
				new action.employee.ShowEmployeeAdd(),
				new action.employee.ShowEmployeeUpdate(),
				new action.employee.DoEmployeeActivate(),
				new action.employee.DoEmployeeDeactivate()
			].concat(isAdmin ? [
				new action.employee.DoEmployeeDelete()
			] : []).concat([

				'-',

				new action.employee.ShowEmployeeContracts(),

				'->',

				new Ext.form.Label({
					text: 'Include inactive employees',
					style: 'padding-right:10px;'
				}),
				new Ext.form.Checkbox({
					id: 'ui.field.employee.inactive',
					checked: false,
					listeners: {
						check: function(cb, checked) {
							// Get the grid.
							var grid = Ext.getCmp('ui.grid.employeegrid');

							// Update the grid filters.
							if (grid) {
								var store = grid.getStore();
								checked ? store.clearInactiveFilter() :
										  store.setInactiveFilter();
							}
						}
					}
				}),

				'-',

				new Ext.form.TextField({
					id: 'ui.field.employee.search',
					width: 100,
					listeners: {
						specialkey: function(tf, evt) {
							// Listen for the Enter key.
							if (evt.ENTER == evt.getKey()) {
								// Get the search action.
								var search = Ext.getCmp(
									'action.employee.doemployeesearch');

								// Invoke the handler.
								search.handler();
							}
						}
					}
				}),
				new action.employee.DoEmployeeSearch()
			])
		});

		ui.tbar.EmployeeToolbar.superclass.constructor.call(this, config);
	}
});

