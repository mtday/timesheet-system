
Ext.namespace("data.store");

data.store.FormStore = Ext.extend(Ext.data.JsonStore, {
	constructor: function(c) {
		var form = new data.model.Form();

		var config = Ext.applyIf(c || {}, {
			url:        '/user/forms/json',
			root:       'forms',
			autoLoad:   true,
			fields:     form.getRecord()
		});

		data.store.FormStore.superclass.constructor.call(this, config);
	}
});

