
Ext.namespace("data.model");

data.model.EmployeeContract = Ext.extend(Ext.util.Observable, {
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
				id:        'assignment_id',
				name:      'assignment_id',
				dataIndex: 'assignment_id',
				header:    'Assignment ID',
				width:     40,
				hidden:    true,
				sortable:  true
			}, {
				id:        'employee_id',
				name:      'employee_id',
				dataIndex: 'employee_id',
				header:    'Employee ID',
				width:     40,
				hidden:    true,
				sortable:  true
			}, {
				id:        'contract_id',
				name:      'contract_id',
				dataIndex: 'contract_id',
				header:    'Contract ID',
				width:     40,
				hidden:    true,
				sortable:  true
			}, {
				id:        'contract_num',
				name:      'contract_num',
				dataIndex: 'contract_num',
				header:    'Contract Number',
				width:     120,
				hidden:    true,
				sortable:  true
			}, {
				id:        'description',
				name:      'description',
				dataIndex: 'description',
				header:    'Description',
				width:     200,
				sortable:  true
			}, {
				id:        'job_code',
				name:      'job_code',
				dataIndex: 'job_code',
				header:    'Job Code',
				width:     160,
				sortable:  true
			}, {
				id:        'labor_cat',
				name:      'labor_cat',
				dataIndex: 'labor_cat',
				header:    'Labor Category',
				width:     90,
				sortable:  true
			}, {
				id:        'item_name',
				name:      'item_name',
				dataIndex: 'item_name',
				header:    'Item Name',
				width:     220,
				sortable:  true
			}, {
				id:        'start',
				name:      'start',
				dataIndex: 'start',
				header:    'Start',
				width:     80,
				sortable:  true
			}, {
				id:        'end',
				name:      'end',
				dataIndex: 'end',
				header:    'End',
				width:     80,
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

