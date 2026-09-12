<script>
  var TreeFolder = function() {
    let me = Ext.utils.grids(
      this);

    me.selectedOrganization = null;

    me.init = function() {
      me.store = Ext.create('Ext.data.TreeStore', {
        fields: [{
            name: 'id',
            type: 'int'
          },
          {
            name: 'parent_id',
            type: 'int'
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
          id: 0,
          name: 'PT Qualita Indonesia',
          icon: '{{ asset('images/icons/home.png') }}',
          expanded: true
        },
        proxy: {
          type: 'ajax',
          url: '{{ route('organization.data') }}'
        },
        listeners: {}
      });

      me.grid = Ext.create('Ext.tree.Panel', {
        title: 'Organizations',
        region: 'west',
        width: 280,
        split: true,
        rootVisible: true,
        multiSelect: true,
        singleExpand: true,
        border: true,
        store: me.store,
        useArrows: false,
        hideHeaders: true,
        columns: [{
          dataIndex: 'text',
          xtype: 'treecolumn',
          flex: 1
        }],
        viewConfig: {
          markDirty: false,
          enableTextSelection: true,
          getRowClass: function(rec) {
            return rec.get('deleted_at') ? 'disabled' : '';
          },
          listeners: {
            itemclick: function(obj, rec) {
              me.selectedOrganization = rec.get('id');
              grids.store.proxy.extraParams['organization'] = me.selectedOrganization;
              grids.store.load();
            }
          }
        },
      });
    };
  };
</script>
