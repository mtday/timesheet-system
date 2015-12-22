
Ext.namespace("data.store");

data.store.ContactStore = Ext.extend(Ext.data.JsonStore, {
	constructor: function(c) {
		var contact = new data.model.Contact();

		var config = Ext.applyIf(c || {}, {
			url:        '/manager/contact/json',
			root:       'contacts',
			autoLoad:   true,
			fields:     contact.getRecord()
		});

		data.store.ContactStore.superclass.constructor.call(this, config);
	}
});

