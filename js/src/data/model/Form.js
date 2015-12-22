
Ext.namespace("data.model");

data.model.Form = Ext.extend(Ext.util.Observable, {
	constructor: function(c) {
		this.fields = [
			{
				id:        'id',
				name:      'id',
				dataIndex: 'id',
				header:    'ID',
				width:     40,
				hidden:    true,
				sortable:  true
			}, {
				id:        'name',
				name:      'name',
				dataIndex: 'name',
				header:    'Name',
				width:     200,
				sortable:  true
			}, {
				id:        'download',
				name:      'download',
				dataIndex: 'download',
				header:    'Download',
				mapping:   'file_name',
				width:     62,
				sortable:  false,
				renderer:  function(val, a, b) {
					// Split the file name.
					var parts = val.split('.');

					// Get the file type based on the extension.
					var type = parts[parts.length - 1].toLowerCase();

					// Return the image and link.
					return '<div style="text-align:center;"><a href="/forms/' + val
							+ '" target="_blank"><img src="/images/filetypes/'
							+ type + '.png" border="0" alt="Download Form"/></a></div>';
				}
			}, {
				id:        'file_name',
				name:      'file_name',
				dataIndex: 'file_name',
				header:    'File Name',
				hidden:    true,
				sortable:  true,
				internal:  true
			}, {
				id:        'description',
				name:      'description',
				dataIndex: 'description',
				header:    'Description',
				sortable:  true,
				internal:  true
			}, {
				id:        'last_update',
				name:      'last_update',
				dataIndex: 'last_update',
				header:    'Last Update',
				width:     110,
				sortable:  true
			}
		];
	},

	getRecord: function() {
		return Ext.data.Record.create(this.fields);
	},

	getColumnModel: function() {
		var flds = [ ];
		for (var f = 0; f < this.fields.length; f++)
			if (!this.fields[f].internal)
				flds.push(this.fields[f]);
		return new Ext.grid.ColumnModel(flds);
	}
});

