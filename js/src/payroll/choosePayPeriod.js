
// Invoked when the user wants to choose a new payroll pay period.
function choosePayPeriod() {
	// Create and display the date picker.
	new Ext.DatePicker({
		// Where to draw the picker.
		renderTo: 'date-picker',

		// Invoked after a date is chosen.
		handler: function(picker, chosenDate) {
			// Hide the date picker.
			picker.hide();

			// Show the progress bar while the timesheets are loaded.
			Ext.Msg.progress('Loading Timesheets',
				'Please wait while the timesheets are loaded...');

			// Go show the chosen pay period.
			document.location = '/payroll/index/view/day/' +
				chosenDate.format('Y-m-d');
		}
	}).show();
}

// Invoked when the user wants to choose a new timesheet pay period.
function chooseTimesheetPayPeriod(empIds) {
	// Create and display the date picker.
	new Ext.DatePicker({
		// Where to draw the picker.
		renderTo: 'date-picker',

		// Invoked after a date is chosen.
		handler: function(picker, chosenDate) {
			// Hide the date picker.
			picker.hide();

			// Show the progress bar while the timesheets are loaded.
			Ext.Msg.progress('Loading Timesheets',
				'Please wait while the timesheets are loaded...');

			// Go show the chosen pay period.
			document.location = '/payroll/timesheet/choose/day/' +
				chosenDate.format('Y-m-d') + '?ids=' + empIds;
		}
	}).show();
}

