@require('details.organization')

<script>
  var Details = function() {
    let me = this;
    me.data = null;

    me.init = function() {
      me.organization = new OrganizationsDetail();
      me.organization.init();

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
        items: [{
            title: 'INFO',
            itemId: 'info-tab',
            layout: 'fit',
            items: [{
              xtype: 'component',
              html: '<iframe id="info-iframe" frameborder="0" style="width:100%;height:100%;"></iframe>'
            }]
          },
          me.organization.grid,
        ],
        listeners: {
          tabchange: function(tabs, tab) {
            console.log(tab);
            if (tab.itemId == 'info-tab') {
              me.loadInfo(me.data.id);
            } else {
              me.organization.storeLoad();
            }
          }
        }
      });


      me.loadInfo = function(helpdeskId) {
        setTimeout(function() {
          var iframe = document.getElementById('info-iframe');
          if (iframe) {
            iframe.src = '{{ route('helpdesk.show', ':id') }}'.replace(':id', helpdeskId);
          }
        }, 150);
      }

      me.set = function(data) {
        me.data = data;
        me.tabs.setTitle(data.title.toUpperCase());
        me.loadInfo(data.id);
        me.organization.storeLoad();
      }

      me.show = function() {
        me.tabs.expand();
      }
    }
  }
</script>
