
Ext.namespace("action.supervisor");

action.supervisor.DoEdit = function() {
	return new Ext.Action({
		id:       'action.supervisor.doedit',
		text:     'Edit',
		iconCls:  'icon-timesheet-edit',
		disabled: true,
		handler: function() {
			// Get the grid.
			var grid = Ext.getCmp('ui.grid.supervisedgrid');

			// Generate the array of ids to edit.
			var ids = grid.getSelectedIds();

			// Check to see if we have multiple timesheets to edit.
			var t = ids.length > 1 ? 'timesheets' : 'timesheet';
			var T = ids.length > 1 ? 'Timesheets' : 'Timesheet';

			// Show the progress bar while the employee is being saved.
			Ext.Msg.progress('Editing ' + T,
				'Please wait while loading the ' + t + '...');

			// Go show the requested timesheets.
			document.location = '/supervisor/timesheet/view?edit=true&ids=' + ids.join(',');
		}
	});
}

