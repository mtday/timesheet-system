
Ext.namespace("action.holiday");

action.holiday.ShowHolidayAdd = function() {
	return new Ext.Action({
		id:      'action.holiday.showholidayadd',
		text:    'Add',
		iconCls: 'icon-holiday-add',
		handler: function() {
			// Get the add panel and the grid.
			var holidayAddPanel =
				Ext.getCmp('ui.panel.holiday.holidayaddpanel');
			var holidayGrid = Ext.getCmp('ui.grid.holidaygrid');

			// Make sure the panel exists.
			if (!holidayAddPanel)
				holidayAddPanel = new ui.panel.holiday.HolidayAddPanel({
					renderTo: 'holiday-add-panel'
				});

			// Hide the grid and show the panel.
			holidayGrid.hide();
			holidayAddPanel.show();

			// Set the focus.
			holidayAddPanel.setInitialFocus();
		}
	});
}

