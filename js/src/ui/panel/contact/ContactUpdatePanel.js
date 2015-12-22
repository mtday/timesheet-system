
Ext.namespace("ui.panel.contact");

ui.panel.contact.ContactUpdatePanel = Ext.extend(Ext.form.FormPanel, {
	constructor: function(c) {
		var config = Ext.applyIf(c || {}, {
			id:         'ui.panel.contact.contactupdatepanel',
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
								xtype: 'hidden',
								name:  'id'
							}, {
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
				new Ext.Button(new action.contact.DoContactUpdate()),
				{
					text: 'Cancel',
					handler: function() {
						var win = Ext.getCmp('ui.window.contact.showcontactupdatewindow');
						if (win)
							win.close();
					}
				}
			]
		});

		ui.panel.contact.ContactUpdatePanel.superclass.constructor.call(this, config);
	},

	setInitialFocus: function() {
		this.form.findField('company_name').focus();
	},

	setValues: function(contact) {
		// Set the form values.
		this.form.findField('id').setValue(contact.data.id);
		this.form.findField('company_name').setValue(contact.data.company_name);
		this.form.findField('poc_name').setValue(contact.data.poc_name);
		this.form.findField('poc_title').setValue(contact.data.poc_title);
		this.form.findField('poc_phone').setValue(contact.data.poc_phone);
		this.form.findField('poc_phone2').setValue(contact.data.poc_phone2);
		this.form.findField('poc_fax').setValue(contact.data.poc_fax);
		this.form.findField('poc_email').setValue(contact.data.poc_email);
		this.form.findField('street').setValue(contact.data.street);
		this.form.findField('city').setValue(contact.data.city);
		this.form.findField('state').setValue(contact.data.state);
		this.form.findField('zip').setValue(contact.data.zip);
		this.form.findField('comments').setValue(contact.data.comments);

		// Set the form focus.
		this.setInitialFocus();
	}
});

