
Ext.namespace("action.payroll");

action.payroll.DoView = function() {
	return new Ext.Action({
		id:       'action.payroll.doview',
		text:     'View',
		iconCls:  'icon-timesheet-view',
		disabled: true,
		handler: function() {
			// Get the grid.
			var grid = Ext.getCmp('ui.grid.payrollgrid');

			// Generate the array of ids to view.
			var ids = grid.getSelectedIds();

			// Check to see if we have multiple timesheets to view.
			var t = ids.length > 1 ? 'timesheets' : 'timesheet';
			var T = ids.length > 1 ? 'Timesheets' : 'Timesheet';

			// Show the progress bar while the employee is being saved.
			Ext.Msg.progress('Viewing ' + T,
				'Please wait while loading the ' + t + '...');

			// Go show the requested timesheets.
			document.location = '/payroll/timesheet/view?ids=' + ids.join(',');
		}
	});
}

