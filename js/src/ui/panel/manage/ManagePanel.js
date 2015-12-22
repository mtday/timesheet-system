
Ext.namespace("ui.panel.manage");

ui.panel.manage.ManagePanel = Ext.extend(Ext.Panel, {
	constructor: function(c) {
		var config = Ext.applyIf(c || {}, {
			id:         'ui.panel.manage.managepanel',
			width:      780,
			autoHeight: true,
			bodyStyle:  'padding: 10px;',
			tbar:       new ui.tbar.ManageToolbar(),
			items: [
				new Ext.Panel({
					border:    false,
					frame:     false,
					bodyStyle: 'padding: 0px 0px 15px 0px;' +
									'text-align:center;',
					html:      'Select an action above.'
				})
			]
		});

		ui.panel.manage.ManagePanel.superclass.constructor.call(this, config);
	},

	display: function(component) {
		this.remove(0, true);
		this.add(component);
		this.doLayout();
	}
});

