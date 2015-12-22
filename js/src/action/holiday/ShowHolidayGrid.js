
Ext.namespace("action.holiday");

action.holiday.ShowHolidayGrid = function() {
	return new Ext.Action({
		id:      'action.holiday.showholidaygrid',
		text:    'Back to Holidays',
		iconCls: 'icon-holiday-go',
		handler: function() {
			// Get the panels.
			var holidayAddPanel =
				Ext.getCmp('ui.panel.holiday.holidayaddpanel');
			var holidayUpdPanel =
				Ext.getCmp('ui.panel.holiday.holidayupdatepanel');

			// Hide the panels.
			if (holidayAddPanel) holidayAddPanel.hide();
			if (holidayUpdPanel) holidayUpdPanel.hide();

			// Show the grid.
			Ext.getCmp('ui.grid.holidaygrid').show();
		}
	});
}

