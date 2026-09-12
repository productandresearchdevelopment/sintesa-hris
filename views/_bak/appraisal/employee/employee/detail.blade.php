<script>
  var Details = function() {
    let me = this;
    me.init = function() {
      me.tabs = Ext.create('Ext.tab.Panel', {
        id: 'detail-panel',
        region: 'east',
        title: 'VIEW DETAIL',
        split: true,
        width: 400,
        minWidth: 300,
        cls: 'tabx',
        border: false,
        collapsible: true,
        collapsed: true,
        items: []
      });

      me.set = function(data) {
        // let gridsParams = grids.store.proxy.extraParams
        console.log(data);
        me.tabs.setTitle(data.period + ' ' + data.smester);
      }

      me.show = function() {
        me.tabs.expand();
      }

    }
  }
</script>
