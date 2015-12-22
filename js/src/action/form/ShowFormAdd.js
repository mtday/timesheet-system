
Ext.namespace("action.form");

action.form.ShowFormAdd = function() {
	return new Ext.Action({
		id:      'action.form.showformadd',
		text:    'Add',
		iconCls: 'icon-form-add',
		handler: function() {
			// Get the add panel and the grid.
			var formAddPanel =
				Ext.getCmp('ui.panel.form.formaddpanel');
			var formGrid = Ext.getCmp('ui.grid.formgrid');

			// Make sure the panel exists.
			if (!formAddPanel)
				formAddPanel = new ui.panel.form.FormAddPanel();

			// Create the window used to show the panel.
			var win = new Ext.Window({
				title:       'Add a new Form',
				width:       600,
				height:      340,
				layout:      'fit',
				items:       formAddPanel,
				bodyStyle:   'background-color: white;',
				closeAction: 'close',
				buttons: [
					{
						text:    'Add',
						iconCls: 'icon-form-add',
						handler: function() {
							// Make sure the form is valid.
							if (!formAddPanel.getForm().isValid()) {
								// Display an error message.
								Ext.Msg.alert('Form Incomplete', 'Please resolve form ' +
									'validation problems before continuing.');
								return;
							}

							// Show the progress bar while the form is being added.
							Ext.Msg.progress('Adding Form',
								'Please wait while the form is added...');

							// Create a new ServerIO object.
							var io = new util.io.ServerIO();

							// Submit the form.
							io.doFormRequest(formAddPanel, {
								// Set the URL.
								url: '/manager/forms/add',

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

