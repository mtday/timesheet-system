
Ext.namespace("action.employeecontract");

action.employeecontract.ShowEmployeeContractGrid = function() {
	return new Ext.Action({
		id:      'action.employeecontract.showassignmentgrid',
		text:    'Back to Assignments',
		iconCls: 'icon-assignment-go',
		handler: function() {
			// Get the panels.
			var assignmentAddPanel =
				Ext.getCmp('ui.panel.employeecontract.assignmentaddpanel');
			var assignmentUpdPanel =
				Ext.getCmp('ui.panel.employeecontract.assignmentupdatepanel');

			// Hide the panels.
			if (assignmentAddPanel) assignmentAddPanel.hide();
			if (assignmentUpdPanel) assignmentUpdPanel.hide();

			// Show the grid.
			Ext.getCmp('ui.grid.employeecontractgrid').show();
		}
	});
}

