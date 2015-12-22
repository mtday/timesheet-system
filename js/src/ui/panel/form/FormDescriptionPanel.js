
Ext.namespace("ui.panel.form");

ui.panel.form.FormDescriptionPanel = Ext.extend(Ext.Panel, {
	constructor: function(c) {
		var form = this;

		this.contentPanel = new Ext.Panel({
			border:    false,
			frame:     false,
			bodyStyle: 'padding: 0px 0px 15px 0px; font-size: 11pt;',
			html:      'Select a single form on the left to view its description.'
		});

		var config = Ext.applyIf(c || {}, {
			id:         'ui.panel.form.formdescriptionpanel',
			title:      'Form Description',
			width:      330,
			height:     350,
			bodyStyle:  'padding: 10px;overflow: auto;',
			items:      this.contentPanel
		});

		ui.panel.form.FormDescriptionPanel.superclass.constructor.call(this, config);
	},

	showDescription: function(form) {
		this.contentPanel.update(form.data.description);
	},

	showDefaultDescription: function() {
		var html = 'Select a single form on the left to view its description.';
		this.contentPanel.update(html);
	}
});

