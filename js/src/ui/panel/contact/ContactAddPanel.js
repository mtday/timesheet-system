
Ext.namespace("ui.panel.contact");

ui.panel.contact.ContactAddPanel = Ext.extend(Ext.form.FormPanel, {
	constructor: function(c) {
		var config = Ext.applyIf(c || {}, {
			id:         'ui.panel.contact.contactaddpanel',
			width:      690,
			autoHeight: true,
			bodyStyle:  'padding: 10px;',
			labelWidth: 120,
			border:     false,
			frame:      false,
			items: new Ext.Panel({
				layout: 'column',
				border: false,
				frame: false,
				items: [
					new Ext.Panel({
						columnWidth: 0.6,
						layout: 'form',
						border: false,
						frame: false,
						items: [
							{
								xtype:      'textfield',
								fieldLabel: 'Company Name',
								name:       'company_name',
								allowBlank: false,
								width:      240
							}, {
								xtype:      'textfield',
								fieldLabel: 'POC',
								name:       'poc_name',
								allowBlank: false,
								width:      240
							}, {
								xtype:      'textfield',
								fieldLabel: 'POC Title',
								name:       'poc_title',
								allowBlank: true,
								width:      240
							}, {
								xtype:      'textfield',
								fieldLabel: 'POC Phone',
								name:       'poc_phone',
								allowBlank: true,
								width:      130
							}, {
								xtype:      'textfield',
								fieldLabel: 'POC Other Phone',
								name:       'poc_phone2',
								allowBlank: true,
								width:      130
							}, {
								xtype:      'textfield',
								fieldLabel: 'POC Fax',
								name:       'poc_fax',
								allowBlank: true,
								width:      130
							}, {
								xtype:      'textfield',
								fieldLabel: 'POC Email',
								name:       'poc_email',
								allowBlank: true,
								width:      240
							}, {
								xtype:      'textfield',
								fieldLabel: 'Street',
								name:       'street',
								allowBlank: true,
								width:      240
							}, {
								xtype:      'textfield',
								fieldLabel: 'City',
								name:       'city',
								allowBlank: true,
								width:      160
							}, {
								xtype:      'textfield',
								fieldLabel: 'State',
								name:       'state',
								allowBlank: true,
								width:      60,
								value:      'MD'
							}, {
								xtype:      'numberfield',
								fieldLabel: 'Zip Code',
								name:       'zip',
								allowBlank: true,
								width:      60
							}
						]
					}),
					new Ext.Panel({
						columnWidth: 0.4,
						layout: 'form',
						border: false,
						frame: false,
						labelAlign: 'top',
						items: [
							{
								xtype: 'textarea',
								name: 'comments',
								fieldLabel: 'Comments',
								allowBlank: true,
								width: 255,
								height: 260
							}
						]
					})
				]
			}),
			buttons: [
				new Ext.Button(new action.contact.DoContactAdd()),
				{
					text: 'Cancel',
					handler: function() {
						var win = Ext.getCmp('ui.window.contact.showcontactaddwindow');
						if (win)
							win.close();
					}
				}
			]
		});

		ui.panel.contact.ContactAddPanel.superclass.constructor.call(this, config);
	},

	setInitialFocus: function() {
		this.getForm().findField('company_name').focus();
	}
});

