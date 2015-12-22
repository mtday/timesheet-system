
Ext.namespace("ui.panel.employee");

ui.panel.employee.EmployeeUpdatePanel = Ext.extend(Ext.Panel, {
	constructor: function(c) {
		if (!c || !c.employee)
			throw "EmployeeUpdatePanel requires an employee.";

		var panel = this;

		this.supervisorGrid = new ui.grid.SupervisorGrid({
			employee:    c.employee,
			columnWidth: 0.44
		})

		// Add the update panel.
		this.form = new Ext.form.FormPanel({
			title:       'Update Employee',
			width:       400,
			autoHeight:  true,
			bodyStyle:   'padding: 10px;',
			labelWidth:  110,
			items: [
				{
					xtype: 'hidden',
					name:  'id'
				}, {
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
					allowBlank: false,
					width:      250
				}, new Ext.form.ComboBox({
					fieldLabel:     'Personnel Type',
					name:           'personnel_type',
					displayField:   'display',
					valueField:     'type',
					hiddenName:     'personnel_type',
					mode:           'local',
					forceSelection: false,
					triggerAction:  'all',
					selectOnFocus:  false,
					width:          180,
					allowBlank:     false,
					store: new Ext.data.SimpleStore({
						fields: [ 'type', 'display' ],
						data: [ [ 'Employee',   'Employee'    ],
								[ 'Consultant', 'Consultant'  ] ]
					})
				}), {
					xtype:      'checkboxgroup',
					fieldLabel: 'Privileges',
					name:       'privileges',
					columns:    2,
					items: (isAdmin ? [
						{
							boxLabel:   'Admin',
							name:       'admin',
							id:         'privileges-modify-admin',
							inputValue: 1
						}
					] : []).concat(isPayroll || isAdmin ? [
						{
							boxLabel:   'Payroll',
							name:       'payroll',
							id:         'privileges-modify-payroll',
							inputValue: 1
						}
					] : []).concat(isManager || isAdmin ? [
						{
							boxLabel:   'Manager',
							name:       'manager',
							id:         'privileges-modify-manager',
							inputValue: 1
						}
					] : []).concat(isSecurity || isAdmin ? [
						{
							boxLabel:   'Security',
							name:       'security',
							id:         'privileges-modify-security',
							inputValue: 1
						}
					] : []).concat(isWiki || isAdmin ? [
						{
							boxLabel:   'Wiki',
							name:       'wiki',
							id:         'privileges-modify-wiki',
							inputValue: 1
						}
					] : [])
				}, {
					xtype:      'radiogroup',
					fieldLabel: 'Active',
					name:       'active',
					items: [
						{
							boxLabel:   'Yes',
							name:       'active',
							id:         'employee-active-modify-yes',
							inputValue: 1,
							style:      'border: 0px;'
						}, {
							boxLabel:   'No',
							name:       'active',
							id:         'employee-active-modify-no',
							inputValue: 0,
							style:      'border: 0px;'
						}
					]
				}
			],
			buttons: [
				new Ext.Button(new action.employee.DoEmployeeUpdate()),
				new Ext.Button(new action.employee.ShowEmployeeGrid())
			]
		});

		var config = Ext.applyIf(c || {}, {
			id:         'ui.panel.employee.employeeupdatepanel',
			border:     false,
			frame:      false,
			autoHeight: true,
			width:      780,
			layout:     'column',
			items: [
				// Add the update form.
				panel.form,

				// Add the supervisor grid.
				new Ext.Panel({
					border:    false,
					frame:     false,
					bodyStyle: 'padding-left:20px;',
					items:     panel.supervisorGrid
				})
			]
		});

		ui.panel.employee.EmployeeUpdatePanel.superclass.constructor.call(this, config);
	},

	setInitialFocus: function() {
		this.form.getForm().findField('first_name').focus();
	},

	setValues: function(employee) {
		// Set the form values.
		this.form.getForm().findField('id').setValue(employee.data.id);
		this.form.getForm().findField('first_name').
			setValue(employee.data.first_name);
		this.form.getForm().findField('last_name').
			setValue(employee.data.last_name);
		this.form.getForm().findField('suffix').setValue(employee.data.suffix);
		this.form.getForm().findField('login').setValue(employee.data.login);
		this.form.getForm().findField('password').setValue('');
		this.form.getForm().findField('confirm').setValue('');
		this.form.getForm().findField('email').setValue(employee.data.email);
		this.form.getForm().findField('division').
			setValue(employee.data.division);
		this.form.getForm().findField('personnel_type').
			setValue(employee.data.personnel_type);
		if (isAdmin)
			this.form.getForm().findField('privileges').
				setValue('privileges-modify-admin',
						employee.data.admin == "1");
		if (isPayroll)
			this.form.getForm().findField('privileges').
				setValue('privileges-modify-payroll',
						employee.data.payroll == "1");
		if (isManager)
			this.form.getForm().findField('privileges').
				setValue('privileges-modify-manager',
						employee.data.manager == "1");
		if (isSecurity)
			this.form.getForm().findField('privileges').
				setValue('privileges-modify-security',
						employee.data.security == "1");
		if (isWiki)
			this.form.getForm().findField('privileges').
				setValue('privileges-modify-wiki',
						employee.data.wiki == "1");

		this.form.getForm().findField('active').
			setValue('employee-active-modify-yes', employee.data.active == "1");
		this.form.getForm().findField('active').
			setValue('employee-active-modify-no', employee.data.active == "0");

		// Set the form focus.
		this.setInitialFocus();
	}
});

