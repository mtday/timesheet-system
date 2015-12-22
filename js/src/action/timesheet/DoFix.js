
Ext.namespace("action.timesheet");

action.timesheet.DoFix = function(timesheetId) {
	return new Ext.Action({
		id:      'action.timesheet.dofix',
		text:    'Fix Timesheet',
		iconCls: 'icon-timesheet-fix',
		handler: function() {
			// Reset the inactivity timer.
			resetTimer();

			// Confirm with the user if they really want to do it.
			Ext.Msg.confirm('Fix Timesheet',
				'Are you sure you want to re-open this completed timesheet '
					+ 'to make changes?',
				function(answer) {
					// Make sure the user wants to go on.
					if (answer != 'yes')
						return;

					// Show the progress bar while the completion happens.
					Ext.Msg.progress('Fix Timesheet',
						'Please wait while your timesheet is re-opened...');

					// Create a new ServerIO object.
					var io = new util.io.ServerIO();

					// Submit the form.
					io.doAjaxRequest({
						// Set the URL.
						url: '/user/timesheet/fix',

						// Set the request parameters.
						params: {
							// Add the current timesheet id.
							id: timesheetId
						},

						// Invoked on a successful save.
						mysuccess: function(data) {
							// Refresh the timesheet page.
							document.location = '/user/timesheet/view/' +
								data.payPeriod;
						}
					});
				});
		}
	});
}

