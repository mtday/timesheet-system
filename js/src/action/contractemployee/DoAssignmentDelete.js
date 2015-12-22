
Ext.namespace("action.contractemployee");

action.contractemployee.DoAssignmentDelete = function(contract) {
	return new Ext.Action({
		id:       'action.contractemployee.doassignmentdelete',
		text:     'Delete',
		iconCls:  'icon-assignment-delete',
		disabled: true,
		handler: function() {
			// Get the grid.
			var grid = Ext.getCmp('ui.grid.contractemployeegrid');

			// Generate the array of employee ids to delete.
			var ids = grid.getSelectedIds();

			// Check to see if we have multiple contract assignments to delete.
			var c = ids.length > 1 ?
				'contract assignments' : 'contract assignment';
			var C = ids.length > 1 ?
				'Contract Assignments' : 'Contract Assignment';

			// Confirm the deletion of the contracts.
			Ext.Msg.confirm('Are you sure?',
				'Are you sure you want to delete the specified ' + c + '?',

				// Handle the confirmation response.
				function(btn) {
					// Make sure the user clicked the 'yes' button.
					if (btn != 'yes')
						return;

					// Let the user know what we are doing.
					Ext.Msg.progress('Deleting ' + C,
						'Please wait while removing the ' + c + '...');

					// Create the ServerIO object.
					var io = new util.io.ServerIO();

					// Send the Ajax request.
					io.doAjaxRequest({
						// Add the URL.
						url: '/manager/assignment/delete',

						// Add the parameters to send to the server.
						params: {
							ids: ids.join(',')
						},

						// Add the mysuccess function.
						mysuccess: function(data) {
							// Get the grid.
							var grid = Ext.getCmp(
								'ui.grid.contractemployeegrid');

							// Reload the data store.
							grid.getStore().reload();
						}
					});
				}
			);
		}
	});
}

