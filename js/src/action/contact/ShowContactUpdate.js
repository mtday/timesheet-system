
Ext.namespace("action.contact");

action.contact.ShowContactUpdate = function() {
	return new Ext.Action({
		id:       'action.contact.showcontactupdate',
		text:     'Update',
		iconCls:  'icon-contact-edit',
		disabled: true,
		handler: function() {
			var grid = Ext.getCmp('ui.grid.contactgrid');

			var contact = grid.getSelectionModel().getSelections()[0];

			var panel = new ui.panel.contact.ContactUpdatePanel();

			new Ext.Window({
				id:        'ui.window.contact.showcontactupdatewindow',
				title:     'Update Contact',
				width:     700,
				height:    377,
				bodyStyle: 'background-color: white;',
				items:     panel
			}).show();

			panel.setValues(contact);
		}
	});
}

