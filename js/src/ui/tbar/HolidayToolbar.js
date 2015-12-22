
Ext.namespace("ui.tbar");

ui.tbar.HolidayToolbar = Ext.extend(Ext.Toolbar, {
	constructor: function(c) {
		var config = Ext.applyIf(c || {}, {
			items: [
				new action.holiday.ShowHolidayAdd(),
				new action.holiday.DoHolidayDelete(),

				'->',

				new Ext.form.TextField({
					id: 'ui.field.holiday.search',
					width: 100,
					listeners: {
						specialkey: function(tf, evt) {
							// Listen for the Enter key.
							if (evt.ENTER == evt.getKey()) {
								// Get the search action.
								var search = Ext.getCmp(
									'action.holiday.doholidaysearch');

								// Invoke the handler.
								search.handler();
							}
						}
					}
				}),
				new action.holiday.DoHolidaySearch()
			]
		});

		ui.tbar.HolidayToolbar.superclass.constructor.call(this, config);
	}
});

