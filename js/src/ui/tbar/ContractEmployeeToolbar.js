
Ext.namespace("ui.tbar");

ui.tbar.ContractEmployeeToolbar = Ext.extend(Ext.Toolbar, {
	constructor: function(c) {
		// Make sure the correct parameters were specified.
		if (!c || !c.contract)
			throw "ContractEmployeeToolbar requires a contract.";

		var config = Ext.applyIf(c || {}, {
			items: [
				new action.contractemployee.ShowAssignmentAdd(c.contract),
				new action.contractemployee.ShowAssignmentUpdate(c.contract),
				new action.contractemployee.DoAssignmentDelete(c.contract),

				'-',

				new action.contract.ShowContractGrid(),

				'->',

				new Ext.form.Label({
					text: 'As of',
					style: 'padding-right:10px;'
				}),
				new Ext.form.DateField({
					id: 'ui.field.contractemployee.asof',
					value: c.day,
					listeners: {
						select: function(field, val) {
							var grid = Ext.getCmp('ui.grid.contractemployeegrid');
							grid.getStore().setAsOf(val);
						},
						change: function(field, newVal, oldVal) {
							var grid = Ext.getCmp('ui.grid.contractemployeegrid');
							grid.getStore().setAsOf(newVal);
						}
					}
				}),

				'-',

				new Ext.form.TextField({
					id: 'ui.field.contractemployee.search',
					width: 100,
					listeners: {
						specialkey: function(tf, evt) {
							// Listen for the Enter key.
							if (evt.ENTER == evt.getKey()) {
								// Get the search action.
								var search = Ext.getCmp(
									'action.contractemployee.doassignmentsearch');

								// Invoke the handler.
								search.handler();
							}
						}
					}
				}),
				new action.contractemployee.DoAssignmentSearch(c.contract)
			]
		});

		ui.tbar.ContractEmployeeToolbar.superclass.constructor.call(this, config);
	}
});

