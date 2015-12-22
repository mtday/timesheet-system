
Ext.namespace("action.contact");

action.contact.DoContactAdd = function() {
	return new Ext.Action({
		id:      'action.contact.docontactadd',
		text:    'Add',
		iconCls: 'icon-contact-add',
		handler: function() {
			// Get the panel containing the form data.
			var formPanel = Ext.getCmp('ui.panel.contact.contactaddpanel');

			// Make sure the form is valid.
			if (!formPanel.getForm().isValid()) {
				// Display an error message.
				Ext.Msg.alert('Form Incomplete', 'Please resolve form ' +
					'validation problems before continuing.');
				return;
			}

			// Show the progress bar while the contact is being added.
			Ext.Msg.progress('Adding Contact',
				'Please wait while the contact is added...');

			// Create a new ServerIO object.
			var io = new util.io.ServerIO();

			// Submit the form.
			io.doFormRequest(formPanel, {
				// Set the URL.
				url: '/manager/contact/add',

				// The function to invoke after success.
				mysuccess: function(data) {
					// Get the grid.
					var grid = Ext.getCmp('ui.grid.contactgrid');

					// Reload the data store.
					grid.getStore().reload();

					// Close the window.
					var win = Ext.getCmp('ui.window.contact.showcontactaddwindow');
					if (win) win.close();
				},

				// The function to invoke after failure.
				myfailure: function(data) {
					// Close the window.
					var win = Ext.getCmp('ui.window.contact.showcontactaddwindow');
					if (win) win.close();
				}
			});
		}
	});
}

