
Ext.namespace("action.contact");

action.contact.DoContactUpdate = function() {
	return new Ext.Action({
		id:      'action.contact.docontactupdate',
		text:    'Update',
		iconCls: 'icon-contact-edit',
		handler: function() {
			// Get the form panel.
			var formPanel = Ext.getCmp('ui.panel.contact.contactupdatepanel');

			// Make sure the form is valid.
			if (!formPanel.getForm().isValid()) {
				// Display an error message.
				Ext.Msg.alert('Form Incomplete', 'Please resolve form ' +
					'validation problems before continuing.');
				return;
			}

			// Show the progress bar while the contact is being saved.
			Ext.Msg.progress('Updating Contact',
				'Please wait while the contact is saved...');

			// Create the ServerIO object.
			var io = new util.io.ServerIO();

			// Submit the form.
			io.doFormRequest(formPanel, {
				// Set the URL.
				url: '/manager/contact/update',

				// The function to invoke after success.
				mysuccess: function(data) {
					// Get the grid.
					var grid = Ext.getCmp('ui.grid.contactgrid');

					// Reload the data store.
					grid.getStore().reload();

					// Close the window.
					var win = Ext.getCmp('ui.window.contact.showcontactupdatewindow');
					if (win) win.close();
				},

				// The function to invoke after failure.
				myfailure: function(data) {
					// Close the window.
					var win = Ext.getCmp('ui.window.contact.showcontactupdatewindow');
					if (win) win.close();
				}
			});
		}
	});
}

