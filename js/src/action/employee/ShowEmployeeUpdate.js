
Ext.namespace("action.employee");

action.employee.ShowEmployeeUpdate = function() {
	return new Ext.Action({
		id:       'action.employee.showemployeeupdate',
		text:     'Update',
		iconCls:  'icon-employee-edit',
		disabled: true,
		handler: function() {
			// Get the grid.
			var employeeGrid = Ext.getCmp('ui.grid.employeegrid');

			// Get the selected employee.
			var employee = employeeGrid.getSelectionModel().getSelections()[0];

			// Hide the grid.
			employeeGrid.hide();

			// Delete the existing panel.
			var existing = Ext.getCmp('ui.panel.employee.employeeupdatepanel');
			if (existing)
				existing.destroy();

			// Create the panel.
			var employeeUpdPanel = new ui.panel.employee.EmployeeUpdatePanel({
				renderTo: 'employee-update-panel',
				employee: employee
			});

			// Set the focus and values.
			employeeUpdPanel.setValues(employee);
		}
	});
}

