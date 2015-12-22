
Ext.namespace("action.contractemployee");

action.contractemployee.ShowAssignmentAdd = function(contract) {
	return new Ext.Action({
		id:      'action.contractemployee.showassignmentadd',
		text:    'Add',
		iconCls: 'icon-assignment-add',
		handler: function() {
			// Get the add panel and the grid.
			var assignmentAddPanel =
				Ext.getCmp('ui.panel.contractemployee.assignmentaddpanel');
			var contractEmployeeGrid =
				Ext.getCmp('ui.grid.contractemployeegrid');

			// Make sure the panel exists.
			if (!assignmentAddPanel)
				assignmentAddPanel =
					new ui.panel.contractemployee.AssignmentAddPanel({
						contract: contract,
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

