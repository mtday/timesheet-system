
Ext.namespace("ui.panel.contractemployee");

ui.panel.contractemployee.AssignmentUpdatePanel = Ext.extend(Ext.form.FormPanel, {
	constructor: function(c) {
		if (!c || !c.contract)
			throw "AssignmentUpdatePanel requires a contract to be provided.";
		this.contract = c.contract;

		var config = Ext.applyIf(c || {}, {
			id:         'ui.panel.contractemployee.assignmentupdatepanel',
			title:      'Update Contract Assignment',
			width:      450,
			autoHeight: true,
			bodyStyle:  'padding: 10px;',
			labelWidth: 120,
			items: [
				{
					xtype: 'hidden',
					name:  'id'
				}, {
					xtype: 'hidden',
					name:  'contract_id',
					value: c.contract.id
				}, {
					xtype: 'hidden',
					name:  'employee_id'
				}, {
					xtype:      'textfield',
					name:       'contract',
					fieldLabel: 'Contract',
					width:      250,
					disabled:   true
				}, {
					xtype:      'textfield',
					name:       'employee',
					fieldLabel: 'Employee',
					width:      250,
					disabled:   true
				}, {
					xtype:      'datefield',
					fieldLabel: 'Assignment Start',
					name:       'start'
				}, {
					xtype:      'datefield',
					fieldLabel: 'Assignment End',
					name:       'end'
				}, {
					xtype:      'textfield',
					fieldLabel: 'Labor Category',
					name:       'labor_cat',
					width:      300
				}, {
					xtype:      'textfield',
					fieldLabel: 'Item Name',
					name:       'item_name',
					width:      300
				}
			],
			buttons: [
				new Ext.Button(new action.contractemployee.DoAssignmentUpdate()),
				new Ext.Button(new action.contractemployee.ShowContractEmployeeGrid())
			]
		});

		ui.panel.contractemployee.AssignmentUpdatePanel.superclass.constructor.call(this, config);
	},

	setInitialFocus: function() {
		this.getForm().findField('start').focus();
	},

	setValues: function(assignment) {
		// Set the form values.
		this.getForm().findField('id').
			setValue(assignment.data.id);
		this.getForm().findField('contract_id').
			setValue(assignment.data.contract_id);
		this.getForm().findField('employee_id').
			setValue(assignment.data.employee_id);
		this.getForm().findField('contract').
			setValue(this.contract.data.description);
		this.getForm().findField('employee').
			setValue(assignment.data.full_name);
		this.getForm().findField('start').setValue(assignment.data.start);
		this.getForm().findField('end').setValue(assignment.data.end);
		this.getForm().findField('labor_cat').
			setValue(assignment.data.labor_cat);
		this.getForm().findField('item_name').
			setValue(assignment.data.item_name);

		// Set the form focus.
		this.setInitialFocus();
	}
});

