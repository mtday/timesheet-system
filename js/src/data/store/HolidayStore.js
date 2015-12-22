
Ext.namespace("data.store");

data.store.HolidayStore = Ext.extend(Ext.data.JsonStore, {
	constructor: function(c) {
		var holiday = new data.model.Holiday();

		var config = Ext.applyIf(c || {}, {
			url:        '/admin/holiday/json',
			root:       'holidays',
			autoLoad:   true,
			fields:     holiday.getRecord()
		});

		data.store.HolidayStore.superclass.constructor.call(this, config);
	}
});

