
Ext.namespace("ui.tbar");

ui.tbar.ContactToolbar = Ext.extend(Ext.Toolbar, {
	constructor: function(c) {
		var config = Ext.applyIf(c || {}, {
			items: [
				new action.contact.ShowContactAdd(),
				new action.contact.ShowContactUpdate(),
				new action.contact.DoContactDelete(),

				'->',

				new Ext.form.TextField({
					id: 'ui.field.contact.search',
					width: 100,
					listeners: {
						specialkey: function(tf, evt) {
							// Listen for the Enter key.
							if (evt.ENTER == evt.getKey()) {
								// Get the search action.
								var search = Ext.getCmp(
									'action.contact.docontactsearch');

								// Invoke the handler.
								search.handler();
							}
						}
					}
				}),
				new action.contact.DoContactSearch()
			]
		});

		ui.tbar.ContactToolbar.superclass.constructor.call(this, config);
	}
});

