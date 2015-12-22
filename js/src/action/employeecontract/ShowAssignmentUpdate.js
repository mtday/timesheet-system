
Ext.namespace("action.employeecontract");

action.employeecontract.ShowAssignmentUpdate = function(employee) {
	return new Ext.Action({
		id:       'action.employeecontract.showassignmentupdate',
		text:     'Update',
		iconCls:  'icon-assignment-edit',
		disabled: true,
		handler: function() {
			// Get the grid.
			var employeeContractGrid =
				Ext.getCmp('ui.grid.employeecontractgrid');

			// Make sure the panel exists.
			var assignmentUpdPanel = new ui.panel.employeecontract.AssignmentUpdatePanel({
				employee: employee,
				renderTo: 'assignment-update-panel'
			});

			// Hide the grid and show the panel.
			employeeContractGrid.hide();
			assignmentUpdPanel.show();

			// Get the selected employee assignment.
			var assignment = employeeContractGrid.
				getSelectionModel().getSelections()[0];

			// Set the focus.
			assignmentUpdPanel.setValues(assignment);
		}
	});
}

