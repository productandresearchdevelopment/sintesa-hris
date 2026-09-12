@require('details.organization')

<script>
  var Details = function() {
    let me = this;
    me.init = function() {
      me.organization = new OrganizationsDetail();

      me.organization.init();

      me.tabs = Ext.create('Ext.tab.Panel', {
        id: 'detail-panel',
        region: 'east',
        title: 'SET ORGANIZATION',
        split: true,
        width: 400,
        minWidth: 300,
        cls: 'tabx',
        border: false,
        collapsible: true,
        collapsed: true,
        items: [
          me.organization.grid,
        ]
      });

      me.set = function(data) {
        let gridsParams = grids.store.proxy.extraParams
        me.tabs.setTitle(data.name.toUpperCase());

        me.organization.storeLoad();
      }

      me.show = function() {
        me.tabs.expand();
      }

    }
  }
</script>
