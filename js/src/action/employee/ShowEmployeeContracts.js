
Ext.namespace("action.employee");

action.employee.ShowEmployeeContracts = function() {
	return new Ext.Action({
		id:       'action.employee.showemployeecontracts',
		text:     'Assigned Contracts',
		iconCls:  'icon-contracts',
		disabled: true,
		handler: function() {
			// Get the employee grid.
			var employeeGrid = Ext.getCmp('ui.grid.employeegrid');

			// Get the selected employee.
			var employee = employeeGrid.getSelectionModel().
				getSelections()[0];

			// Create the employee contract grid.
			var assignmentGrid = new ui.grid.EmployeeContractGrid({
				employee: employee,
				day:      (new Date()).format('Y-m-d'),
				renderTo: 'employee-contract-grid'
			});

			// Hide the employee grid and show the assignment grid.
			employeeGrid.hide();
			assignmentGrid.show();
		}
	});
}

