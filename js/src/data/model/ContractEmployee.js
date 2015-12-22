
Ext.namespace("data.model");

data.model.ContractEmployee = Ext.extend(Ext.util.Observable, {
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
				id:        'login',
				name:      'login',
				dataIndex: 'login',
				header:    'Login',
				width:     80,
				sortable:  true
			}, {
				id:        'first_name',
				name:      'first_name',
				dataIndex: 'first_name',
				header:    'First Name',
				width:     70,
				sortable:  true,
				hidden:    true
			}, {
				id:        'last_name',
				name:      'last_name',
				dataIndex: 'last_name',
				header:    'Last Name',
				width:     90,
				sortable:  true,
				hidden:    true
			}, {
				id:        'full_name',
				name:      'full_name',
				dataIndex: 'full_name',
				header:    'Full Name',
				width:     130,
				sortable:  true
			}, {
				id:        'labor_cat',
				name:      'labor_cat',
				dataIndex: 'labor_cat',
				header:    'Labor Category',
				width:     130,
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
				id:        'email',
				name:      'email',
				dataIndex: 'email',
				header:    'Email',
				width:     160,
				sortable:  true,
				hidden:    true
			}, {
				id:        'division',
				name:      'division',
				dataIndex: 'division',
				header:    'Division',
				width:     100,
				sortable:  true,
				hidden:    true
			}, {
				id:        'personnel_type',
				name:      'personnel_type',
				dataIndex: 'personnel_type',
				header:    'Type',
				width:     100,
				sortable:  true,
				hidden:    true
			}, {
				id:        'privileges',
				name:      'privileges',
				dataIndex: 'privileges',
				header:    'Privileges',
				width:     60,
				sortable:  true,
				hidden:    true
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
			}, {
				id:        'admin',
				name:      'admin',
				dataIndex: 'admin',
				header:    'Admin',
				width:     60,
				hidden:    true,
				sortable:  true,
				renderer:  function(val) {
					return ("" + val) == "1" ? "Yes" : "No";
				}
			}, {
				id:        'payroll',
				name:      'payroll',
				dataIndex: 'payroll',
				header:    'Payroll',
				width:     60,
				hidden:    true,
				sortable:  true,
				renderer:  function(val) {
					return ("" + val) == "1" ? "Yes" : "No";
				}
			}, {
				id:        'manager',
				name:      'manager',
				dataIndex: 'manager',
				header:    'Manager',
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

