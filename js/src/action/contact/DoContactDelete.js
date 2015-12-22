
Ext.namespace("action.contact");

action.contact.DoContactDelete = function() {
	return new Ext.Action({
		id:       'action.contact.docontactdelete',
		text:     'Delete',
		iconCls:  'icon-contact-delete',
		disabled: true,
		handler: function() {
			// Get the grid.
			var grid = Ext.getCmp('ui.grid.contactgrid');

			// Generate the array of ids to delete.
			var ids = grid.getSelectedIds();

			// Check to see if we have multiple contacts to delete.
			var c = ids.length > 1 ? 'contacts' : 'contact';
			var C = ids.length > 1 ? 'Contacts' : 'Contact';

			// Confirm the deletion of the contacts.
			Ext.Msg.confirm('Are you sure?',
				'Are you sure you want to delete the specified ' + c + '? '
						+ 'Any associated VRs will be deleted as well.',

				// Handle the confirmation response.
				function(btn) {
					// Make sure the user clicked the 'yes' button.
					if (btn != 'yes')
						return;

					// Let the user know what we are doing.
					Ext.Msg.progress('Deleting ' + C,
						'Please wait while removing the ' + c + '...');

					// Create the ServerIO object.
					var io = new util.io.ServerIO();

					// Send the Ajax request.
					io.doAjaxRequest({
						// Add the URL.
						url: '/manager/contact/delete',

						// Add the parameters to send to the server.
						params: {
							ids: ids.join(',')
						},

						// Add the mysuccess function.
						mysuccess: function(data) {
							// Get the grid and reload it.
							var grid = Ext.getCmp('ui.grid.contactgrid');
							grid.getStore().reload();
						}
					});
				}
			);
		}
	});
}

