<script>
  var TreeFolder = function() {
    let me = Ext.utils.grids(
      this);

    me.selectedOrganization = null;

    me.init = function() {
      me.store = Ext.create('Ext.data.TreeStore', {
        fields: [{
            name: 'id',
            type: 'auto'
          },
          {
            name: 'parent_id',
            type: 'auto'
          },
          {
            name: 'name',
            type: 'string'
          },
          {
            name: 'position_id',
            type: 'string'
          },
          {
            name: 'position',
            type: 'auto'
          },
          {
            name: 'division',
            type: 'auto'
          },
          {
            name: 'division_id',
            type: 'int'
          },
          {
            name: 'company',
            type: 'auto'
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
            name: 'text',
            type: 'string'
          },
          {
            name: 'alias',
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
          text: 'Organizations',
          icon: '{{ asset('images/icons/home.png') }}',
          expanded: true
        },
        proxy: {
          type: 'ajax',
          url: '{{ route('organization.data') }}'
        },
        listeners: {
          load: function() {
            me.autoSelectFirstNode();
          }
        }
      });

      me.autoSelectFirstNode = function() {
        if (!me.store) return;
        let root = me.store.getRootNode();
        if (!root || !root.hasChildNodes()) return;

        let findFirstOrg = function(node) {
          if (!node) return null;
          let id = node.get('id');
          if (id && id !== '0' && (typeof id !== 'string' || id.indexOf('company_') === -1)) {
            return node;
          }
          if (node.childNodes && node.childNodes.length > 0) {
            for (let i = 0; i < node.childNodes.length; i++) {
              let res = findFirstOrg(node.childNodes[i]);
              if (res) return res;
            }
          }
          return null;
        };

        let target = findFirstOrg(root);
        if (target) {
          if (me.grid && me.grid.getSelectionModel()) {
            me.grid.getSelectionModel().select(target);
          }
          me.handleSelectOrg(target);
        }
      };

      me.handleSelectOrg = function(rec) {
        if (!rec) return;
        if (typeof rec.get('id') === 'string' && rec.get('id').indexOf('company_') !== -1) {
          if (typeof grids !== 'undefined' && grids.store) {
            grids.store.proxy.extraParams['organization'] = null;
            grids.store.load();
          }
          return;
        }
        me.selectedOrganization = rec.get('id');
        if (typeof grids !== 'undefined' && grids.store) {
          grids.store.proxy.extraParams['organization'] = me.selectedOrganization;
          grids.store.load();
        }
      };

      me.grid = Ext.create('Ext.tree.Panel', {
        title: 'Organizations',
        region: 'west',
        width: 280,
        split: true,
        rootVisible: false,
        multiSelect: true,
        singleExpand: true,
        border: true,
        store: me.store,
        useArrows: false,
        hideHeaders: false,
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
            text: 'Root',
            dataIndex: 'name',
            xtype: 'treecolumn',
            flex: 1
          },
        ],
        viewConfig: {
          markDirty: false,
          enableTextSelection: true,
          getRowClass: function(rec) {
            return rec.get('deleted_at') ? 'disabled' : '';
          },
          listeners: {
            itemclick: function(obj, rec) {
              me.handleSelectOrg(rec);
            }
          }
        },
      });
    };
  };
</script>
