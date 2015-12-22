
Ext.namespace("action.employee");

action.employee.ShowEmployeeGrid = function() {
	return new Ext.Action({
		id:      'action.employee.showemployeegrid',
		text:    'Back to Employees',
		iconCls: 'icon-employee-go',
		handler: function() {
			// Get the panels.
			var employeeAddPanel =
				Ext.getCmp('ui.panel.employee.employeeaddpanel');
			var employeeUpdPanel =
				Ext.getCmp('ui.panel.employee.employeeupdatepanel');
			var assignmentGrid =
				Ext.getCmp('ui.grid.employeecontractgrid');

			// Hide the panels.
			if (employeeAddPanel) employeeAddPanel.destroy();
			if (employeeUpdPanel) employeeUpdPanel.destroy();
			if (assignmentGrid) assignmentGrid.destroy();

			// Show the grid.
			Ext.getCmp('ui.grid.employeegrid').show();
		}
	});
}

