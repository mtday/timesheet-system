
Ext.namespace("action.contact");

action.contact.ShowContactAdd = function() {
	return new Ext.Action({
		id:      'action.contact.showcontactadd',
		text:    'Add',
		iconCls: 'icon-contact-add',
		handler: function() {
			new Ext.Window({
				id:        'ui.window.contact.showcontactaddwindow',
				title:     'Add a new Contact',
				width:     700,
				height:    377,
				bodyStyle: 'background-color: white;',
				items:     new ui.panel.contact.ContactAddPanel()
			}).show();
		}
	});
}

