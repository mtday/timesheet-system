
Ext.namespace("ui.panel.employeecontract");

ui.panel.employeecontract.AssignmentAddPanel = Ext.extend(Ext.form.FormPanel, {
	constructor: function(c) {
		if (!c || !c.employee)
			throw "AssignmentAddPanel requires an employee to be provided.";

		var config = Ext.applyIf(c || {}, {
			id:         'ui.panel.employeecontract.assignmentaddpanel',
			title:      'Add a new Contract Assignment',
			width:      470,
			autoHeight: true,
			bodyStyle:  'padding: 10px;',
			labelWidth: 120,
			items: [
				{
					xtype: 'hidden',
					name:  'employee_id',
					value: c.employee.id
				}, new Ext.form.ComboBox({
					fieldLabel:     'Contract',
					name:           'contract_id',
					displayField:   'description',
					valueField:     'id',
					hiddenName:     'contract_id',
					mode:           'local',
					forceSelection: true,
					triggerAction:  'all',
					selectOnFocus:  true,
					width:          320,
					allowBlank:     false,
					store: new data.store.ContractStore({
						regularOnly: true
					})
				}), {
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
					width:      300,
					allowBlank: false
				}, {
					xtype:      'textfield',
					fieldLabel: 'Item Name',
					name:       'item_name',
					width:      300,
					allowBlank: false
				}
			],
			buttons: [
				new Ext.Button(new action.employeecontract.DoAssignmentAdd()),
				new Ext.Button(new action.employeecontract.ShowEmployeeContractGrid())
			]
		});

		ui.panel.employeecontract.AssignmentAddPanel.superclass.constructor.call(this, config);
	},

	setInitialFocus: function() {
		this.getForm().findField('contract_id').focus();
	}
});

