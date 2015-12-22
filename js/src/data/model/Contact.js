
Ext.namespace("data.model");

data.model.Contact = Ext.extend(Ext.util.Observable, {
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
				id:        'company_name',
				name:      'company_name',
				dataIndex: 'company_name',
				header:    'Company Name',
				width:     130,
				sortable:  true
			}, {
				id:        'poc_name',
				name:      'poc_name',
				dataIndex: 'poc_name',
				header:    'POC',
				width:     100,
				sortable:  true
			}, {
				id:        'poc_title',
				name:      'poc_title',
				dataIndex: 'poc_title',
				header:    'POC Title',
				width:     130,
				sortable:  true
			}, {
				id:        'poc_phone',
				name:      'poc_phone',
				dataIndex: 'poc_phone',
				header:    'POC Phone',
				width:     100,
				sortable:  true
			}, {
				id:        'poc_phone2',
				name:      'poc_phone2',
				dataIndex: 'poc_phone2',
				header:    'POC Other Phone',
				width:     100,
				sortable:  true
			}, {
				id:        'poc_fax',
				name:      'poc_fax',
				dataIndex: 'poc_fax',
				header:    'POC Fax',
				width:     100,
				hidden:    true,
				sortable:  true
			}, {
				id:        'poc_email',
				name:      'poc_email',
				dataIndex: 'poc_email',
				header:    'POC Email',
				width:     180,
				sortable:  true
			}, {
				id:        'street',
				name:      'street',
				dataIndex: 'street',
				header:    'Street',
				width:     130,
				hidden:    true,
				sortable:  true
			}, {
				id:        'city',
				name:      'city',
				dataIndex: 'city',
				header:    'City',
				width:     100,
				hidden:    true,
				sortable:  true
			}, {
				id:        'state',
				name:      'state',
				dataIndex: 'state',
				header:    'State',
				width:     40,
				hidden:    true,
				sortable:  true
			}, {
				id:        'zip',
				name:      'zip',
				dataIndex: 'zip',
				header:    'Zip',
				width:     50,
				hidden:    true,
				sortable:  true
			}, {
				id:        'description',
				name:      'description',
				dataIndex: 'description',
				header:    'Description',
				width:     240,
				hidden:    true,
				sortable:  true
			}, {
				id:        'comments',
				name:      'comments',
				dataIndex: 'comments',
				header:    'Comments',
				width:     240,
				hidden:    true,
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

