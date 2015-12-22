
Ext.namespace("ui.tbar");

ui.tbar.ContractToolbar = Ext.extend(Ext.Toolbar, {
	constructor: function(c) {
		var config = Ext.applyIf(c || {}, {
			items: [
				new action.contract.ShowContractAdd(),
				new action.contract.ShowContractUpdate(),
				new action.contract.DoContractActivate(),
				new action.contract.DoContractDeactivate()
			].concat(isAdmin ? [
				new action.contract.DoContractDelete()
			] : []).concat([

				'-',

				new action.contract.ShowContractEmployees(),

				'->',

				new Ext.form.Label({
					text: 'Include inactive contracts',
					style: 'padding-right:10px;'
				}),
				new Ext.form.Checkbox({
					id: 'ui.field.contract.inactive',
					checked: false,
					listeners: {
						check: function(cb, checked) {
							// Get the grid.
							var grid = Ext.getCmp('ui.grid.contractgrid');

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
					id: 'ui.field.contract.search',
					width: 100,
					listeners: {
						specialkey: function(tf, evt) {
							// Listen for the Enter key.
							if (evt.ENTER == evt.getKey()) {
								// Get the search action.
								var search = Ext.getCmp(
									'action.contract.docontractsearch');

								// Invoke the handler.
								search.handler();
							}
						}
					}
				}),
				new action.contract.DoContractSearch()
			])
		});

		ui.tbar.ContractToolbar.superclass.constructor.call(this, config);
	}
});

