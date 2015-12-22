
Ext.namespace("ui.tbar");

ui.tbar.PayrollToolbar = Ext.extend(Ext.Toolbar, {
	constructor: function(c) {
		var config = Ext.applyIf(c || {}, {
			items: [
				// Add the payroll actions.
				new action.payroll.DoView(),
				new action.payroll.DoViewAuditLog(),
				new action.payroll.DoVerify(),
				new action.payroll.DoUnverify(),
				new action.payroll.DoExport(),
				new action.payroll.DoUnexport(),

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
							var grid = Ext.getCmp('ui.grid.payrollgrid');

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
									'action.payroll.dosearch');

								// Invoke the handler.
								if (search)
									search.handler();
							}
						}
					}
				}),
				new action.payroll.DoSearch()
			]
		});

		ui.tbar.PayrollToolbar.superclass.constructor.call(this, config);
	}
});

