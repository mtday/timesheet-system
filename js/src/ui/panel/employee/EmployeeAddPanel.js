
Ext.namespace("ui.panel.employee");

ui.panel.employee.EmployeeAddPanel = Ext.extend(Ext.form.FormPanel, {
	constructor: function(c) {
		var config = Ext.applyIf(c || {}, {
			id:         'ui.panel.employee.employeeaddpanel',
			title:      'Add a new Employee',
			width:      400,
			autoHeight: true,
			bodyStyle:  'padding: 10px;',
			labelWidth: 110,
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
					allowBlank: false,
					width:      110
				}, {
					xtype:      'textfield',
					inputType:  'password',
					fieldLabel: 'Confirm Password',
					name:       'confirm',
					allowBlank: false,
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
					forceSelection: true,
					triggerAction:  'all',
					selectOnFocus:  true,
					width:          180,
					value:          'Employee',
					allowBlank:     false,
					store: new Ext.data.SimpleStore({
						fields: [ 'type', 'display' ],
						data: [ [ 'Employee',   'Employee'    ],
								[ 'Consultant', 'Consultant'  ] ]
					})
				}), new Ext.form.ComboBox({
					fieldLabel:     'Primary Supervisor',
					name:           'supervisor',
					displayField:   'full_name',
					valueField:     'id',
					hiddenName:     'supervisor',
					mode:           'local',
					forceSelection: true,
					triggerAction:  'all',
					selectOnFocus:  true,
					width:          180,
					allowBlank:     false,
					store: new data.store.EmployeeStore({
						activeOnly: true
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
							id:         'privileges-admin',
							inputValue: 1,
							checked:    false
						}
					] : []).concat(isPayroll || isAdmin ? [
						{
							boxLabel:   'Payroll',
							name:       'payroll',
							id:         'privileges-payroll',
							inputValue: 1,
							checked:    false
						}
					] : []).concat(isManager || isAdmin ? [
						{
							boxLabel:   'Manager',
							name:       'manager',
							id:         'privileges-manager',
							inputValue: 1,
							checked:    false
						}
					] : []).concat(isSecurity || isAdmin ? [
						{
							boxLabel:   'Security',
							name:       'security',
							id:         'privileges-security',
							inputValue: 1,
							checked:    false
						}
					] : []).concat(isWiki || isAdmin ? [
						{
							boxLabel:   'Wiki',
							name:       'wiki',
							id:         'privileges-wiki',
							inputValue: 1,
							checked:    false
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
							id:         'employee-active-yes',
							inputValue: 1,
							checked:    true,
							style:      'border: 0px;'
						}, {
							boxLabel:   'No',
							name:       'active',
							id:         'employee-active-no',
							inputValue: 0,
							checked:    false,
							style:      'border: 0px;'
						}
					]
				}
			],
			buttons: [
				new Ext.Button(new action.employee.DoEmployeeAdd()),
				new Ext.Button(new action.employee.ShowEmployeeGrid())
			]
		});

		ui.panel.employee.EmployeeAddPanel.superclass.constructor.call(this, config);
	},

	setInitialFocus: function() {
		this.getForm().findField('first_name').focus();
	}
});

