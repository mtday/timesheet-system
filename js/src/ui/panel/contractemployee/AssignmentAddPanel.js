
Ext.namespace("ui.panel.contractemployee");

ui.panel.contractemployee.AssignmentAddPanel = Ext.extend(Ext.form.FormPanel, {
	constructor: function(c) {
		if (!c || !c.contract)
			throw "AssignmentAddPanel requires a contract to be provided.";

		var config = Ext.applyIf(c || {}, {
			id:         'ui.panel.contractemployee.assignmentaddpanel',
			title:      'Add a new Contract Assignment',
			width:      450,
			autoHeight: true,
			bodyStyle:  'padding: 10px;',
			labelWidth: 120,
			items: [
				{
					xtype: 'hidden',
					name:  'contract_id',
					value: c.contract.id
				}, new Ext.form.ComboBox({
					fieldLabel:     'Employee',
					name:           'employee_id',
					displayField:   'full_name',
					valueField:     'id',
					hiddenName:     'employee_id',
					mode:           'local',
					forceSelection: true,
					triggerAction:  'all',
					selectOnFocus:  true,
					width:          210,
					allowBlank:     false,
					store: new data.store.EmployeeStore({
						activeOnly: true
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
				new Ext.Button(new action.contractemployee.DoAssignmentAdd()),
				new Ext.Button(new action.contractemployee.ShowContractEmployeeGrid())
			]
		});

		ui.panel.contractemployee.AssignmentAddPanel.superclass.constructor.call(this, config);
	},

	setInitialFocus: function() {
		this.getForm().findField('employee_id').focus();
	}
});

