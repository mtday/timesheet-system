
Ext.namespace("data.model");

data.model.Contract = Ext.extend(Ext.util.Observable, {
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
				id:        'contract_num',
				name:      'contract_num',
				dataIndex: 'contract_num',
				header:    'Contract Number',
				width:     120,
				sortable:  true
			}, {
				id:        'description',
				name:      'description',
				dataIndex: 'description',
				header:    'Description',
				width:     220,
				sortable:  true
			}, {
				id:        'job_code',
				name:      'job_code',
				dataIndex: 'job_code',
				header:    'Job Code',
				width:     220,
				sortable:  true
			}, {
				id:        'admin',
				name:      'admin',
				dataIndex: 'admin',
				header:    'Administrative',
				width:     100,
				hidden:    true,
				sortable:  true,
				renderer:  function(val) {
					return ("" + val) == "1" ? "Yes" : "No";
				}
			}, {
				id:        'active',
				name:      'active',
				dataIndex: 'active',
				header:    'Active',
				width:     60,
				hidden:    true,
				sortable:  true,
				renderer:  function(val) {
					return ("" + val) == "1" ? "Yes" : "No";
				}
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

