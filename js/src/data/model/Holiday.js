
Ext.namespace("data.model");

data.model.Holiday = Ext.extend(Ext.util.Observable, {
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
				id:        'description',
				name:      'description',
				dataIndex: 'description',
				header:    'Description',
				width:     140,
				sortable:  true
			}, {
				id:        'config',
				name:      'config',
				dataIndex: 'config',
				header:    'Config',
				width:     260,
				sortable:  true
			}, {
				id:        'day',
				name:      'day',
				dataIndex: 'day',
				header:    'Day',
				width:     90,
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

