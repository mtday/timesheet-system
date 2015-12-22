
Ext.namespace("action.form");

action.form.DoFormDelete = function() {
	return new Ext.Action({
		id:       'action.form.doformdelete',
		text:     'Delete',
		iconCls:  'icon-form-delete',
		disabled: true,
		handler: function() {
			// Get the grid.
			var grid = Ext.getCmp('ui.grid.formgrid');

			// Generate the array of ids to delete.
			var ids = grid.getSelectedIds();

			// Check to see if we have multiple forms to delete.
			var f = ids.length > 1 ? 'forms' : 'form';
			var F = ids.length > 1 ? 'Forms' : 'Form';

			// Confirm the deletion of the forms.
			Ext.Msg.confirm('Are you sure?',
				'Are you sure you want to delete the specified ' + f + '?',

				// Handle the confirmation response.
				function(btn) {
					// Make sure the user clicked the 'yes' button.
					if (btn != 'yes')
						return;

					// Let the user know what we are doing.
					Ext.Msg.progress('Deleting ' + F,
						'Please wait while removing the ' + f + '...');

					// Create the ServerIO object.
					var io = new util.io.ServerIO();

					// Send the Ajax request.
					io.doAjaxRequest({
						// Add the URL.
						url: '/manager/forms/delete',

						// Add the parameters to send to the server.
						params: {
							ids: ids.join(',')
						},

						// Add the mysuccess function.
						mysuccess: function(data) {
							// Get the grid.
							var grid = Ext.getCmp('ui.grid.formgrid');

							// Reload the data store.
							grid.getStore().reload();
						}
					});
				}
			);
		}
	});
}

