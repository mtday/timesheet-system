
Ext.namespace("data.model");

data.model.TimesheetStatus = Ext.extend(Ext.util.Observable, {
	constructor: function(c) {
		var payroll = c && c.payroll;

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
				id:        'primary',
				name:      'primary',
				dataIndex: 'primary',
				header:    'Primary',
				width:     60,
				sortable:  true,
				hidden:    payroll ? true : false,
				renderer:  function(val) {
					return ("" + val) == "1" ? "Yes" : "No";
				}
			}, {
				id:        'login',
				name:      'login',
				dataIndex: 'login',
				header:    'Login',
				width:     80,
				sortable:  true,
				hidden:    true
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
				sortable:  true,
				renderer:  function(val, row, obj) {
					if (obj.data.personnel_type != "Employee")
						return '<font color="#C03030">' + val +
							' (' + obj.data.personnel_type + ')</font>';
					return val;
				}
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
				header:    'Personnel Type',
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
			}, {
				id:        'pp_start',
				name:      'pp_start',
				dataIndex: 'pp_start',
				header:    'Pay Period',
				width:     80,
				hidden:    true,
				sortable:  true
			}, {
				id:        'total',
				name:      'total',
				dataIndex: 'total',
				header:    'Total',
				width:     46,
				sortable:  true,
				renderer:  function(val) {
					if (!val)
						return val;
					val = Math.round(val * 20) / 20;
					var pieces = ("" + val).split(/\./);
					if (pieces.length == 1)
						val += ".00";
					else if (pieces[1].length == 1)
						val += "0";
					return val;
				}
			}, {
				id:        'billable',
				name:      'billable',
				dataIndex: 'billable',
				header:    'Billable',
				width:     46,
				sortable:  true,
				renderer:  function(val) {
					if (!val)
						return val;
					val = Math.round(val * 20) / 20;
					var pieces = ("" + val).split(/\./);
					if (pieces.length == 1)
						val += ".00";
					else if (pieces[1].length == 1)
						val += "0";
					return val;
				}
			}, {
				id:        'pto',
				name:      'pto',
				dataIndex: 'pto',
				header:    'PTO',
				width:     46,
				sortable:  true,
				renderer:  function(val) {
					if (!val)
						return val;
					val = Math.round(val * 20) / 20;
					var pieces = ("" + val).split(/\./);
					if (pieces.length == 1)
						val += ".00";
					else if (pieces[1].length == 1)
						val += "0";
					return val;
				}
			}, {
				id:        'holiday',
				name:      'holiday',
				dataIndex: 'holiday',
				header:    'HOL',
				width:     46,
				sortable:  true,
				renderer:  function(val) {
					if (!val)
						return val;
					val = Math.round(val * 20) / 20;
					var pieces = ("" + val).split(/\./);
					if (pieces.length == 1)
						val += ".00";
					else if (pieces[1].length == 1)
						val += "0";
					return val;
				}
			}, {
				id:        'overhead',
				name:      'overhead',
				dataIndex: 'overhead',
				header:    'OH',
				width:     46,
				sortable:  true,
				renderer:  function(val) {
					if (!val)
						return val;
					val = Math.round(val * 20) / 20;
					var pieces = ("" + val).split(/\./);
					if (pieces.length == 1)
						val += ".00";
					else if (pieces[1].length == 1)
						val += "0";
					return val;
				}
			}, {
				id:        'bp',
				name:      'bp',
				dataIndex: 'bp',
				header:    'B&P',
				width:     46,
				hidden:    payroll ? false : true,
				sortable:  true,
				renderer:  function(val) {
					if (!val)
						return val;
					val = Math.round(val * 20) / 20;
					var pieces = ("" + val).split(/\./);
					if (pieces.length == 1)
						val += ".00";
					else if (pieces[1].length == 1)
						val += "0";
					return val;
				}
			}, {
				id:        'ga',
				name:      'ga',
				dataIndex: 'ga',
				header:    'G&A',
				width:     46,
				hidden:    payroll ? false : true,
				sortable:  true,
				renderer:  function(val) {
					if (!val)
						return val;
					val = Math.round(val * 20) / 20;
					var pieces = ("" + val).split(/\./);
					if (pieces.length == 1)
						val += ".00";
					else if (pieces[1].length == 1)
						val += "0";
					return val;
				}
			}, {
				id:        'jury',
				name:      'jury',
				dataIndex: 'jury',
				header:    'JUR',
				width:     46,
				hidden:    payroll ? false : true,
				sortable:  true,
				renderer:  function(val) {
					if (!val)
						return val;
					val = Math.round(val * 20) / 20;
					var pieces = ("" + val).split(/\./);
					if (pieces.length == 1)
						val += ".00";
					else if (pieces[1].length == 1)
						val += "0";
					return val;
				}
			}, {
				id:        'bereavement',
				name:      'bereavement',
				dataIndex: 'bereavement',
				header:    'BER',
				width:     46,
				hidden:    payroll ? false : true,
				sortable:  true,
				renderer:  function(val) {
					if (!val)
						return val;
					val = Math.round(val * 20) / 20;
					var pieces = ("" + val).split(/\./);
					if (pieces.length == 1)
						val += ".00";
					else if (pieces[1].length == 1)
						val += "0";
					return val;
				}
			}, {
				id:        'lwop',
				name:      'lwop',
				dataIndex: 'lwop',
				header:    'LWOP',
				width:     46,
				sortable:  true,
				renderer:  function(val) {
					if (!val)
						return val;
					val = Math.round(val * 20) / 20;
					var pieces = ("" + val).split(/\./);
					if (pieces.length == 1)
						val += ".00";
					else if (pieces[1].length == 1)
						val += "0";
					return val;
				}
			}, {
				id:        'completed',
				name:      'completed',
				dataIndex: 'completed',
				header:    'Completed',
				width:     60,
				sortable:  true,
				hidden:    payroll ? true : false,
				renderer:  function(val) {
					return ("" + val) == "1" ? "Yes" : "No";
				}
			}, {
				id:        'approved',
				name:      'approved',
				dataIndex: 'approved',
				header:    'Approved',
				width:     60,
				sortable:  true,
				hidden:    payroll ? true : false,
				renderer:  function(val) {
					return ("" + val) == "1" ? "Yes" : "No";
				}
			}, {
				id:        'approved_by',
				name:      'approved_by',
				dataIndex: 'approved_by',
				header:    'Approved By',
				width:     100,
				sortable:  true,
				hidden:    true
			}, {
				id:        'verified',
				name:      'verified',
				dataIndex: 'verified',
				header:    'Verified',
				width:     60,
				sortable:  true,
				hidden:    payroll ? true : false,
				renderer:  function(val) {
					return ("" + val) == "1" ? "Yes" : "No";
				}
			}, {
				id:        'verified_by',
				name:      'verified_by',
				dataIndex: 'verified_by',
				header:    'Verified By',
				width:     100,
				sortable:  true,
				hidden:    true
			}, {
				id:        'exported',
				name:      'exported',
				dataIndex: 'exported',
				header:    'Exported',
				width:     60,
				sortable:  true,
				hidden:    payroll ? true : false,
				renderer:  function(val) {
					return ("" + val) == "1" ? "Yes" : "No";
				}
			}, {
				id:        'summary',
				name:      'summary',
				dataIndex: 'summary',
				header:    'Summary',
				width:     60,
				sortable:  true,
				hidden:    payroll ? false : true,
				renderer:  function(val, grid, obj) {
					var status = [ ];
					if (obj.get('completed') == "1")
						status.push("C");
					if (obj.get('approved') == "1")
						status.push("A");
					if (obj.get('verified') == "1")
						status.push("V");
					if (obj.get('exported') == "1")
						status.push("E");
					return status.join(" ");
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

