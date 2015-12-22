
Ext.namespace("action.employee");

action.employee.ShowEmployeeAdd = function() {
	return new Ext.Action({
		id:      'action.employee.showemployeeadd',
		text:    'Add',
		iconCls: 'icon-employee-add',
		handler: function() {
			// Get the add panel and the grid.
			var employeeAddPanel =
				Ext.getCmp('ui.panel.employee.employeeaddpanel');
			var employeeGrid = Ext.getCmp('ui.grid.employeegrid');

			// Make sure the panel exists.
			if (!employeeAddPanel)
				employeeAddPanel = new ui.panel.employee.EmployeeAddPanel({
					renderTo: 'employee-add-panel'
				});

			// Hide the grid and show the panel.
			employeeGrid.hide();
			employeeAddPanel.show();

			// Set the focus.
			employeeAddPanel.setInitialFocus();
		}
	});
}

