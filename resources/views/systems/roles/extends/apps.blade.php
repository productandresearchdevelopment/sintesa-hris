<script>
  var GridApps = function() {
    var me = Ext.utils.grids(this);

    me.selected = null;
    me.group = null;

    me.init = function() {
      me.store = Ext.create('Ext.data.Store', {
        fields: [{
            name: 'id',
            type: 'int'
          },
          {
            name: 'name',
            type: 'string'
          },
          {
            name: 'auth',
            type: 'bool'
          }
        ],
        proxy: {
          type: 'ajax',
          url: '{{ route('auth.app.data') }}'
        },
        autoLoad: false
      });

      me.grid = Ext.create('Ext.grid.Panel', {
        id: 'grid-apps',
        region: 'center',
        border: true,
        title: 'Apps',
        store: me.store,
        columnLines: true,
        columns: [{
            xtype: 'checkcolumn',
            text: '#',
            dataIndex: 'auth',
            width: 40,
            stopSelection: false,
            listeners: {
              checkchange: me.setAuth
            }
          },
          {
            text: 'App Name',
            dataIndex: 'name',
            flex: 1
          }
        ],
        viewConfig: {
          listeners: {
            itemclick: me.setSelected
          }
        }
      });
    };

    me.setSelected = function(obj, rec) {
      me.selected = rec.get('id');
    };

    me.setAuth = function(col, rowIndex, checked) {
      var role = gridRoles.getRec(true);
      var rec = me.store.getAt(rowIndex);

      if (rec && role) {
        http.request({
          method: 'post',
          url: '{{ route('auth.app.set.role', '') }}/' + role.id,
          params: {
            app: rec.get('id'),
            auth: checked ? 1 : 0,
            '_method': 'PUT',
            '_token': '{{ csrf_token() }}'
          },
          success: function() {
            Ext.example.msg('Success!', 'App permission updated.');
          },
          failure: function() {
            Ext.example.msg('Failed!', 'Update App permission failed.');
            me.storeLoad();
          }
        });
      } else {
        Ext.example.msg('Warning!', 'Please select role first!');
      }
    };

    me.storeLoad = function() {
      setTimeout(function() {
        var rec = gridRoles.getRec(true);
        me.group = rec;
        if (rec) {
          me.store.proxy.url = '{{ route('auth.app.data') }}/' + rec.id;
          me.store.load({
            callback: function() {
              me.grid.getEl()?.unmask();
            }
          });
        }
      }, 300);
    };
  };
</script>
