
Ext.namespace("action.payroll");

action.payroll.DoExport = function() {
	return new Ext.Action({
		id:       'action.payroll.doexport',
		text:     'Export',
		iconCls:  'icon-timesheet-export',
		disabled: true,
		handler: function() {
			// Get the grid.
			var grid = Ext.getCmp('ui.grid.payrollgrid');

			// Generate the array of ids to export.
			var ids = grid.getSelectedIds();

			// Invoke the export controller.
			document.location = '/payroll/timesheet/export?ids=' + ids.join(',');

			// Reload the grid in 5 seconds.
			setTimeout(function() {
				// Reload the data store.
				grid.getStore().reload();
			}, 5000);
		}
	});
}

