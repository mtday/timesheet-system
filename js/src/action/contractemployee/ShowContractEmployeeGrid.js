
Ext.namespace("action.contractemployee");

action.contractemployee.ShowContractEmployeeGrid = function() {
	return new Ext.Action({
		id:      'action.contractemployee.showassignmentgrid',
		text:    'Back to Assignments',
		iconCls: 'icon-assignment-go',
		handler: function() {
			// Get the panels.
			var assignmentAddPanel =
				Ext.getCmp('ui.panel.contractemployee.assignmentaddpanel');
			var assignmentUpdPanel =
				Ext.getCmp('ui.panel.contractemployee.assignmentupdatepanel');

			// Hide the panels.
			if (assignmentAddPanel) assignmentAddPanel.hide();
			if (assignmentUpdPanel) assignmentUpdPanel.hide();

			// Show the grid.
			Ext.getCmp('ui.grid.contractemployeegrid').show();
		}
	});
}

