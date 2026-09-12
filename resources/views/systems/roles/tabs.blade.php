<script>
  var TabsPanel = function(gridModules, gridApps) {
    var me = this;

    me.init = function() {
      me.panel = Ext.create('Ext.tab.Panel', {
        region: 'center',
        border: false,
        activeTab: 0,
        enableTabScroll: true,
        defaults: {
          layout: 'fit',
          border: false
        },
        items: [{
            title: 'Modules',
            items: [gridModules.grid]
          },
          {
            title: 'Apps',
            items: [gridApps.grid]
          }
        ]
      });
    };
  };
</script>
