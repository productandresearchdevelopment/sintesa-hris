<style>
  .cellediting tr.x-grid-row.x-grid-row-over td {
    background-color: transparent;
  }
</style>

<script>
  var OrganizationsDetail = function() {
    let me = Ext.utils.grids(this);

    me.init = function() {
      me.store = Ext.create('Ext.data.TreeStore', {
        id: 'organizations-store-tree-filemanager',
        fields: [{
            name: 'id',
            type: 'int'
          },
          {
            name: 'parent_id',
            type: 'int'
          },
          {
            name: 'division_id',
            type: 'int'
          },
          {
            name: 'company_id',
            type: 'int'
          },
          {
            name: 'path',
            type: 'string'
          },
          {
            name: 'name',
            type: 'string'
          },
          {
            name: 'authorized1',
            type: 'auto'
          },
          {
            name: 'authorized2',
            type: 'auto'
          },
          {
            name: 'description',
            type: 'string'
          },
          {
            name: 'deleted_at',
            type: 'date'
          }
        ],
        root: {
          id: '0',
          text: 'PT Qualita Indonesia',
          icon: '{{ asset('images/icons/home.png') }}'
        },
        proxy: {
          type: 'ajax',
          url: '{{ route('filemanager.data.organization', '') }}/'
        },
        listeners: {
          load: function() {
            const grid = me.grid || Ext.ComponentQuery.query('#grid-organizations-filemanager')[0];

            if (grid && grid.getEl()) {
              grid.getEl().unmask();
            }

            if (me.selected && grid && grid.store) {
              const node = grid.store.getNodeById(me.selected);
              if (node) {
                grid.expandPath(node.getPath());
                grid.selectPath(node.getPath());
              }
            }
          }
        }
      });

      me.grid = Ext.create('Ext.tree.Panel', {
        id: 'grid-organizations-filemanager',
        region: 'center',
        title: 'ORGANIZATIONS',
        rootVisible: false,
        singleExpand: true,
        border: true,
        sortableColumns: false,
        enableColumnHide: false,
        enableColumnMove: false,
        enableLocking: false,
        store: me.store,
        columns: [{
            text: '<img src="{{ asset('images/icons/home.png') }}">',
            dataIndex: 'home',
            width: 35,
            align: 'center',
            renderer: function(val, obj, rec) {
              if (val) return '<img src="{{ asset('images/icons/yes.png') }}">';
            }
          },
          {
            text: 'PT Qualita Indonesia',
            dataIndex: 'name',
            xtype: 'treecolumn',
            flex: 1
          },
        ],

        viewConfig: {
          listeners: {
            itemclick: me.setSelected,
            checkchange: me.setAuth,
            itemcontextmenu: function(obj, rec, node, index, e) {
              me.setSelected(obj, rec);
              e.stopEvent();
              me.menus.showAt(e.getXY());
            }
          }
        }
      })
    }

    me.setSelected = function(obj, rec) {
      me.selected = rec.get('id');
    }

    me.setAuth = function() {
      var filemanager = gridFile.getRec(true);
      var rec = me.getRec(true);
      if (rec) {
        http.request({
          method: 'post',
          url: '{{ route('filemanager.set.organization', '') }}/' + filemanager.id,
          params: {
            organization: rec.id,
            auth: (rec.checked ? 1 : 0),
            '_method': 'PUT',
            '_token': '{{ csrf_token() }}',
          },
          failure: function() {
            Ext.example.msg('Failed!', 'Set Organization Group!');
            me.storeLoad();
          }
        });
      } else {
        Ext.example.msg('Warning!', 'Please Select Data!');
      }
    }

    me.storeLoad = function() {
      setTimeout(function() {
        var rec = gridFile.getRec(true);
        me.group = rec;

        if (rec) {
          const grid = me.grid || Ext.ComponentQuery.query('#grid-organizations-filemanager')[0];

          if (grid && grid.getEl()) {
            grid.getEl().mask('Proses');
          }

          me.store.proxy.url = '{{ route('filemanager.data.organization', '') }}/' + rec.id;
          me.store.load();
        }
      }, 500);
    };
  };
</script>
