
Ext.namespace("ui.panel.employee");

ui.panel.employee.ProfileUpdatePanel = Ext.extend(Ext.form.FormPanel, {
	constructor: function(c) {
		if (!c || !c.employee)
			throw "ProfileUpdatePanel requires an employee.";

		var form = this;

		var config = Ext.applyIf(c || {}, {
			id:         'ui.panel.employee.profileupdatepanel',
			border:     true,
			frame:      false,
			title:       'Update Profile',
			width:       390,
			autoHeight:  true,
			bodyStyle:   'padding: 10px;',
			labelWidth:  110,
			items: [
				{
					xtype:      'textfield',
					fieldLabel: 'First Name',
					name:       'first_name',
					allowBlank: false,
					width:      160
				}, {
					xtype:      'textfield',
					fieldLabel: 'Last Name',
					name:       'last_name',
					allowBlank: false,
					width:      160
				}, {
					xtype:      'textfield',
					fieldLabel: 'Suffix',
					name:       'suffix',
					allowBlank: true,
					width:      40
				}, {
					xtype:      'textfield',
					fieldLabel: 'Login',
					name:       'login',
					allowBlank: false,
					width:      80
				}, {
					xtype:      'textfield',
					inputType:  'password',
					fieldLabel: 'Password',
					name:       'password',
					width:      110
				}, {
					xtype:      'textfield',
					inputType:  'password',
					fieldLabel: 'Confirm Password',
					name:       'confirm',
					width:      110
				}, {
					xtype:      'textfield',
					fieldLabel: 'Email',
					name:       'email',
					allowBlank: false,
					width:      250
				}, {
					xtype:      'textfield',
					fieldLabel: 'Division',
					name:       'division',
					disabled:   true,
					allowBlank: false,
					width:      200
				}, {
					xtype:      'textfield',
					fieldLabel: 'Personnel Type',
					name:       'personnel_type',
					disabled:   true,
					allowBlank: false,
					width:      200
				}
			],
			buttons: [
				new Ext.Button(new action.employee.DoProfileUpdate())
			]
		});

		ui.panel.employee.ProfileUpdatePanel.superclass.constructor.call(this, config);

		// Set the values in the form.
		this.setValues(c.employee);
	},

	setInitialFocus: function() {
		this.getForm().findField('first_name').focus();
	},

	setValues: function(employee) {
		// Set the form values.
		this.getForm().findField('first_name').
			setValue(employee.data.first_name);
		this.getForm().findField('last_name').
			setValue(employee.data.last_name);
		this.getForm().findField('suffix').setValue(employee.data.suffix);
		this.getForm().findField('login').setValue(employee.data.login);
		this.getForm().findField('password').setValue('');
		this.getForm().findField('confirm').setValue('');
		this.getForm().findField('email').setValue(employee.data.email);
		this.getForm().findField('division').setValue(employee.data.division);
		this.getForm().findField('personnel_type').
			setValue(employee.data.personnel_type);

		// Set the form focus.
		this.setInitialFocus();
	}
});

