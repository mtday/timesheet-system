
Ext.namespace("ui.panel.contract");

ui.panel.contract.ContractAddPanel = Ext.extend(Ext.form.FormPanel, {
	constructor: function(c) {
		var config = Ext.applyIf(c || {}, {
			id:         'ui.panel.contract.contractaddpanel',
			title:      'Add a new Contract',
			width:      400,
			autoHeight: true,
			bodyStyle:  'padding: 10px;',
			labelWidth: 120,
			items: [
				{
					xtype:      'textfield',
					fieldLabel: 'Contract Number',
					name:       'contract_num',
					allowBlank: false,
					width:      160
				}, {
					xtype:      'textfield',
					fieldLabel: 'Description',
					name:       'description',
					allowBlank: false,
					width:      220
				}, {
					xtype:      'textfield',
					fieldLabel: 'Job Code',
					name:       'job_code',
					allowBlank: false,
					width:      220
				}, {
					xtype:      'radiogroup',
					fieldLabel: 'Administrative',
					name:       'admin',
					items: [
						{
							boxLabel:   'Yes',
							name:       'admin',
							id:         'contract-admin-yes',
							inputValue: 1,
							checked:    false,
							style:      'border: 0px;'
						}, {
							boxLabel:   'No',
							name:       'admin',
							id:         'contract-admin-no',
							inputValue: 0,
							checked:    true,
							style:      'border: 0px;'
						}
					]
				}, {
					xtype:      'radiogroup',
					fieldLabel: 'Active',
					name:       'active',
					items: [
						{
							boxLabel:   'Yes',
							name:       'active',
							id:         'contract-active-yes',
							inputValue: 1,
							checked:    true,
							style:      'border: 0px;'
						}, {
							boxLabel:   'No',
							name:       'active',
							id:         'contract-active-no',
							inputValue: 0,
							checked:    false,
							style:      'border: 0px;'
						}
					]
				}
			],
			buttons: [
				new Ext.Button(new action.contract.DoContractAdd()),
				new Ext.Button(new action.contract.ShowContractGrid())
			]
		});

		ui.panel.contract.ContractAddPanel.superclass.constructor.call(this, config);
	},

	setInitialFocus: function() {
		this.getForm().findField('contract_num').focus();
	}
});

