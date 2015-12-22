
Ext.namespace("ui.tbar");

ui.tbar.SupervisedToolbar = Ext.extend(Ext.Toolbar, {
	constructor: function(c) {
		var config = Ext.applyIf(c || {}, {
			items: [
				// Add the supervisor actions.
				new action.supervisor.DoView(),
				new action.supervisor.DoEdit(),
				new action.supervisor.DoViewAuditLog(),
				new action.supervisor.DoApprove(),
				new action.supervisor.DoDisapprove(),

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
							var grid = Ext.getCmp('ui.grid.supervisedgrid');

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
									'action.supervisor.dosearch');

								// Invoke the handler.
								if (search)
									search.handler();
							}
						}
					}
				}),
				new action.supervisor.DoSearch()
			]
		});

		ui.tbar.SupervisedToolbar.superclass.constructor.call(this, config);
	}
});

