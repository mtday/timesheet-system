
Ext.namespace("action.supervisor");

action.supervisor.DoViewAuditLog = function(timesheetId) {
	return new Ext.Action({
		id:       'action.supervisor.doviewauditlog',
		text:     'View Audit Log',
		iconCls:  'icon-timesheet-auditlog',
		disabled: (typeof(timesheetId) == "undefined"),
		handler: function() {
			// Get the grid.
			var grid = Ext.getCmp('ui.grid.supervisedgrid');

			// Get the id of the timesheet to view.
			var id = (typeof(timesheetId) == "undefined") ?
				grid.getSelectedIds()[0] : timesheetId;

			// Create the Window to display the information.
			var win = new Ext.Window({
				title:  'Timesheet Audit Log',
				width:  580,
				height: 340,
				layout: 'fit',
				items: new ui.grid.AuditLogGrid({
					type: 'supervisor',
					id:   id
				}),
				buttons: [
					{
						text: 'Close',
						handler: function() {
							// Close the window.
							win.close();
						}
					}
				]
			});

			// Show the window.
			win.show();
		}
	});
}

