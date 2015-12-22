
Ext.namespace("action.contractemployee");

action.contractemployee.ShowAssignmentUpdate = function(contract) {
	return new Ext.Action({
		id:       'action.contractemployee.showassignmentupdate',
		text:     'Update',
		iconCls:  'icon-assignment-edit',
		disabled: true,
		handler: function() {
			// Get the grid.
			var contractEmployeeGrid =
				Ext.getCmp('ui.grid.contractemployeegrid');

			// Make sure the panel exists.
			var assignmentUpdPanel = new ui.panel.contractemployee.AssignmentUpdatePanel({
				contract: contract,
				renderTo: 'assignment-update-panel'
			});

			// Hide the grid and show the panel.
			contractEmployeeGrid.hide();
			assignmentUpdPanel.show();

			// Get the selected contract assignment.
			var assignment = contractEmployeeGrid.
				getSelectionModel().getSelections()[0];

			// Set the focus.
			assignmentUpdPanel.setValues(assignment);
		}
	});
}

