
Ext.namespace("data.model");

data.model.Supervisor = Ext.extend(Ext.util.Observable, {
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
				id:        'login',
				name:      'login',
				dataIndex: 'login',
				header:    'Login',
				width:     80,
				hidden:    true,
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
				id:        'email',
				name:      'email',
				dataIndex: 'email',
				header:    'Email',
				width:     160,
				hidden:    true,
				sortable:  true
			}, {
				id:        'division',
				name:      'division',
				dataIndex: 'division',
				header:    'Division',
				width:     100,
				hidden:    true,
				sortable:  true
			}, {
				id:        'personnel_type',
				name:      'personnel_type',
				dataIndex: 'personnel_type',
				header:    'Type',
				width:     100,
				hidden:    true,
				sortable:  true
			}, {
				id:        'privileges',
				name:      'privileges',
				dataIndex: 'privileges',
				header:    'Privileges',
				width:     60,
				hidden:    true,
				sortable:  true
			}, {
				id:        'primary',
				name:      'primary',
				dataIndex: 'primary',
				header:    'Primary',
				width:     60,
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

