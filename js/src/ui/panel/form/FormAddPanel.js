
Ext.namespace("ui.panel.form");

ui.panel.form.FormAddPanel = Ext.extend(Ext.form.FormPanel, {
	constructor: function(c) {
		var fileUpload = new Ext.ux.form.FileUploadField({
			fieldLabel: 'File',
			name:       'file',
			emptyText:  'Select a File',
			buttonText: '',
			allowBlank: false,
			width:      400,
			buttonCfg: {
				iconCls: 'icon-form-add'
			}
		});

		var config = Ext.applyIf(c || {}, {
			id:         'ui.panel.form.formaddpanel',
			width:      400,
			border:     false,
			frame:      false,
			autoHeight: true,
			bodyStyle:  'padding: 10px;',
			labelWidth: 80,
			fileUpload: true,
			items: [
				{
					xtype:      'textfield',
					fieldLabel: 'Name',
					name:       'name',
					allowBlank: false,
					width:      400
				},

				fileUpload,

				{
					xtype:            'htmleditor',
					fieldLabel:       'Description',
					name:             'description',
					allowBlank:       false,
					enableAlignments: false,
					width:            475,
					height:           200
				}
			]
		});

		ui.panel.form.FormAddPanel.superclass.constructor.call(this, config);
	}
});

