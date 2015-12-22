
Ext.namespace("ui.panel.contract");

ui.panel.contract.ContractUpdatePanel = Ext.extend(Ext.form.FormPanel, {
	constructor: function(c) {
		var config = Ext.applyIf(c || {}, {
			id:         'ui.panel.contract.contractupdatepanel',
			title:      'Update Contract',
			width:      400,
			autoHeight: true,
			bodyStyle:  'padding: 10px;',
			labelWidth: 120,
			items: [
				{
					xtype: 'hidden',
					name:  'id'
				}, {
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
							id:         'contract-admin-modify-yes',
							inputValue: 1,
							style:      'border: 0px;'
						}, {
							boxLabel:   'No',
							name:       'admin',
							id:         'contract-admin-modify-no',
							inputValue: 0,
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
							id:         'contract-active-modify-yes',
							inputValue: 1,
							style:      'border: 0px;'
						}, {
							boxLabel:   'No',
							name:       'active',
							id:         'contract-active-modify-no',
							inputValue: 0,
							style:      'border: 0px;'
						}
					]
				}
			],
			buttons: [
				new Ext.Button(new action.contract.DoContractUpdate()),
				new Ext.Button(new action.contract.ShowContractGrid())
			]
		});

		ui.panel.contract.ContractUpdatePanel.superclass.constructor.call(this, config);
	},

	setInitialFocus: function() {
		this.getForm().findField('contract_num').focus();
	},

	setValues: function(contract) {
		// Set the form values.
		this.getForm().findField('id').setValue(contract.data.id);
		this.getForm().findField('contract_num').
			setValue(contract.data.contract_num);
		this.getForm().findField('description').
			setValue(contract.data.description);
		this.getForm().findField('job_code').setValue(contract.data.job_code);
		this.getForm().findField('admin').
			setValue('contract-admin-modify-yes', contract.data.admin == "1");
		this.getForm().findField('admin').
			setValue('contract-admin-modify-no', contract.data.admin == "0");
		this.getForm().findField('active').
			setValue('contract-active-modify-yes', contract.data.active == "1");
		this.getForm().findField('active').
			setValue('contract-active-modify-no', contract.data.active == "0");

		// Set the form focus.
		this.setInitialFocus();
	}
});

