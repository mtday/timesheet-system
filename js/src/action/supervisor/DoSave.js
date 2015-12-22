
Ext.namespace("action.timesheet.supervisor");

action.timesheet.supervisor.DoSave = function(timesheetId) {
	return new Ext.Action({
		id:      'action.timesheet.dosave',
		text:    'Save Timesheet',
		iconCls: 'icon-timesheet-save',
		handler: function() {
			// Reset the inactivity timer.
			resetTimer();

			// Show the progress bar while the save happens.
			Ext.Msg.progress('Saving Timesheet',
				'Please wait while your timesheet is saved...');

			// Create a new ServerIO object.
			var io = new util.io.ServerIO();

			// Submit the form.
			io.doAjaxRequest({
				// Set the URL.
				url: '/supervisor/timesheet/save',

				// Set the request parameters.
				params: {
					// Add the current timesheet id.
					id: timesheetId,

					// Add the timesheet data.
					data: getCellData(timesheetId)
				},

				// Invoked on a successful save.
				mysuccess: function(data) {
					// Clear all the dirty flags on the timesheet.
					clearDirtyFlags(timesheetId);

					// Reset the inactivity timer.
					resetTimer();
				}
			});
		}
	});
}

