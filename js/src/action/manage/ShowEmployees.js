
Ext.namespace("action.manage");

action.manage.ShowEmployees = function() {
	return new Ext.Action({
		id:      'action.manage.showemployees',
		text:    'Employees',
		iconCls: 'icon-employees',
		handler: function() {
			// Create a new EmployeeGrid.
			var grid = new ui.grid.EmployeeGrid();

			// Get the manage panel.
			var managePanel = Ext.getCmp('ui.panel.manage.managepanel');

			// Have the manage panel display the grid.
			managePanel.display(grid);
		}
	});
}

