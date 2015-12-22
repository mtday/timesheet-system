
Ext.namespace("ui.panel.form");

ui.panel.form.FormUpdatePanel = Ext.extend(Ext.form.FormPanel, {
	constructor: function(c) {
		var grid = Ext.getCmp('ui.grid.formgrid');

		var sel = grid.getSelectionModel().getSelected();

		var fileUpload = new Ext.ux.form.FileUploadField({
			fieldLabel: 'File',
			name:       'file',
			emptyText:  'Select a File',
			buttonText: '',
			allowBlank: true,
			width:      400,
			buttonCfg: {
				iconCls: 'icon-form-add'
			}
		});

		var config = Ext.applyIf(c || {}, {
			id:         'ui.panel.form.formupdatepanel',
			width:      400,
			border:     false,
			frame:      false,
			autoHeight: true,
			bodyStyle:  'padding: 10px;',
			labelWidth: 80,
			fileUpload: true,
			items: [
				{
					xtype:      'hidden',
					name:       'id',
					value:      sel.data.id
				}, {
					xtype:      'textfield',
					fieldLabel: 'Name',
					name:       'name',
					allowBlank: false,
					width:      400,
					value:      sel.data.name
				},

				fileUpload,

				{
					xtype:            'htmleditor',
					fieldLabel:       'Description',
					name:             'description',
					allowBlank:       false,
					enableAlignments: false,
					width:            475,
					height:           200,
					value:            sel.data.description
				}
			]
		});

		ui.panel.form.FormUpdatePanel.superclass.constructor.call(this, config);
	}
});

