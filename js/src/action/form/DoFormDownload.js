
Ext.namespace("action.form");

action.form.DoFormDownload = function() {
	return new Ext.Action({
		id:       'action.form.doformdownload',
		text:     'Download',
		iconCls:  'icon-unknown',
		disabled: true,
		handler: function() {
			// Get the grid.
			var grid = Ext.getCmp('ui.grid.formgrid');

			// Get the selected form.
			var form = grid.getSelectionModel().getSelected();

			// Set the document location.
			document.location = '/forms/' + form.data.file_name;
		}
	});
}

