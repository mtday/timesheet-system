
Ext.namespace("action.form");

action.form.ShowFormUpdate = function() {
	return new Ext.Action({
		id:      'action.form.showformupdate',
		text:    'Update',
		iconCls: 'icon-form-edit',
		disabled: true,
		handler: function() {
			// Get the update panel and the grid.
			var formUpdatePanel =
				Ext.getCmp('ui.panel.form.formupdatepanel');
			var formGrid = Ext.getCmp('ui.grid.formgrid');

			// Make sure the panel exists.
			if (!formUpdatePanel)
				formUpdatePanel = new ui.panel.form.FormUpdatePanel();

			// Create the window used to show the panel.
			var win = new Ext.Window({
				title:       'Update Form',
				width:       600,
				height:      340,
				layout:      'fit',
				items:       formUpdatePanel,
				bodyStyle:   'background-color: white;',
				closeAction: 'close',
				buttons: [
					{
						text:    'Update',
						iconCls: 'icon-form-edit',
						handler: function() {
							// Make sure the form is valid.
							if (!formUpdatePanel.getForm().isValid()) {
								// Display an error message.
								Ext.Msg.alert('Form Incomplete', 'Please resolve form ' +
									'validation problems before continuing.');
								return;
							}

							// Show the progress bar while the form is being added.
							Ext.Msg.progress('Updating Form',
								'Please wait while the form is updated...');

							// Create a new ServerIO object.
							var io = new util.io.ServerIO();

							// Submit the form.
							io.doFormRequest(formUpdatePanel, {
								// Set the URL.
								url: '/manager/forms/update',

								// The function to invoke after success.
								mysuccess: function(data) {
									// Get the grid.
									var grid = Ext.getCmp('ui.grid.formgrid');

									// Reload the data store.
									grid.getStore().reload();

									// Close the window.
									win.close();
								}
							});
						}
					}, {
						text: 'Cancel',
						handler: function() {
							win.close();
						}
					}
				]
			});

			// Show the window.
			win.show();
		}
	});
}

