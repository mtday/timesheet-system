
Ext.namespace("ui.tbar");

ui.tbar.FormToolbar = Ext.extend(Ext.Toolbar, {
	constructor: function(c) {
		var config = Ext.applyIf(c || {}, {
			items: (isManager ? [
				new action.form.ShowFormAdd(),
				new action.form.ShowFormUpdate(),
				new action.form.DoFormDelete(),

				'-'

			] : []).concat([
				new action.form.DoFormDownload(),

				'->',

				new Ext.form.TextField({
					id: 'ui.field.form.search',
					width: 100,
					listeners: {
						specialkey: function(tf, evt) {
							// Listen for the Enter key.
							if (evt.ENTER == evt.getKey()) {
								// Get the search action.
								var search = Ext.getCmp(
									'action.form.doformsearch');

								// Invoke the handler.
								search.handler();
							}
						}
					}
				}),
				new action.form.DoFormSearch()
			])
		});

		ui.tbar.FormToolbar.superclass.constructor.call(this, config);
	}
});

