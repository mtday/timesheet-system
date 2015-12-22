
Ext.namespace("ui.panel.holiday");

ui.panel.holiday.HolidayAddPanel = Ext.extend(Ext.form.FormPanel, {
	constructor: function(c) {
		var config = Ext.applyIf(c || {}, {
			id:         'ui.panel.holiday.holidayaddpanel',
			title:      'Add a new Holiday',
			width:      400,
			autoHeight: true,
			bodyStyle:  'padding: 10px;',
			labelWidth: 120,
			items: [
				{
					xtype:      'textfield',
					fieldLabel: 'Description',
					name:       'description',
					allowBlank: false,
					width:      220
				}, {
					xtype:      'textfield',
					fieldLabel: 'Configuration',
					name:       'config',
					allowBlank: false,
					width:      220
				}
			],
			buttons: [
				new Ext.Button(new action.holiday.DoHolidayAdd()),
				new Ext.Button(new action.holiday.ShowHolidayGrid())
			]
		});

		ui.panel.holiday.HolidayAddPanel.superclass.constructor.call(this, config);
	},

	setInitialFocus: function() {
		this.getForm().findField('description').focus();
	}
});

