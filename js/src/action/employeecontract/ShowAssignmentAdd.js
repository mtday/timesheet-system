
Ext.namespace("action.employeecontract");

action.employeecontract.ShowAssignmentAdd = function(employee) {
	return new Ext.Action({
		id:      'action.employeecontract.showassignmentadd',
		text:    'Add',
		iconCls: 'icon-assignment-add',
		handler: function() {
			// Get the add panel and the grid.
			var assignmentAddPanel =
				Ext.getCmp('ui.panel.employeecontract.assignmentaddpanel');
			var contractEmployeeGrid =
				Ext.getCmp('ui.grid.employeecontractgrid');

			// Make sure the panel exists.
			if (!assignmentAddPanel)
				assignmentAddPanel =
					new ui.panel.employeecontract.AssignmentAddPanel({
						employee: employee,
						renderTo: 'assignment-add-panel'
				});

			// Hide the grid and show the panel.
			contractEmployeeGrid.hide();
			assignmentAddPanel.show();

			// Set the focus.
			assignmentAddPanel.setInitialFocus();
		}
	});
}

