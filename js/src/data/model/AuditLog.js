
Ext.namespace("data.model");

data.model.AuditLog = Ext.extend(Ext.util.Observable, {
	constructor: function(c) {
		this.fields = [
			{
				id:        'log',
				name:      'log',
				dataIndex: 'log',
				header:    'Log Message',
				width:     240,
				sortable:  true,
				renderer:  function(val) {
					return '<div style="white-space:normal;">' + val + '</div>';
				}
			}, {
				id:        'timestamp',
				name:      'timestamp',
				dataIndex: 'timestamp',
				header:    'Timestamp',
				width:     140,
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

