@require('details.organization')
@require('details.user')
@require('details.info')

<script>
  var Details = function() {
    let me = this;
    me.init = function() {
      me.organization = new OrganizationsDetail();
      me.user = new UsersDetail();
      me.infoDetail = new InfoDetail();

      me.organization.init();
      me.user.init();

      me.tabs = Ext.create('Ext.tab.Panel', {
        id: 'detail-panel',
        region: 'east',
        title: 'DETAIL',
        split: true,
        width: 400,
        minWidth: 300,
        cls: 'tabx',
        border: false,
        collapsible: true,
        collapsed: true,
        items: [
          me.infoDetail.panel,
          me.organization.grid,
          me.user.grid,
        ]
      });

      me.set = function(data) {
        let gridsParams = gridFile.store.proxy.extraParams
        me.tabs.setTitle(data.name.toUpperCase());

        me.infoDetail.set(data);

        me.organization.storeLoad();
        me.user.storeLoad();
      }

      me.show = function() {
        me.tabs.expand();
      }

    }
  }
</script>
