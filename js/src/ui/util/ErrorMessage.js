
// Define the namespace for this function.
Ext.namespace('ui.util');

// Define the error message function.
ui.util.ErrorMessage = function(title, message) {
	// Show the error message.
	Ext.Msg.show({
	   title:   title,
	   msg:     message,
	   buttons: Ext.Msg.OK,
	   icon:    Ext.MessageBox.ERROR
	});
}

