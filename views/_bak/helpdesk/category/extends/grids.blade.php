<script>
  var CategoryGrids = function() {
    let me = Ext.utils.grids(this);

    me.selected = null;

    me.init = function() {
      me.store = Ext.create('Ext.data.Store', {
        pageSize: 50,
        fields: [{
            name: 'id',
            type: 'string'
          },
          {
            name: 'name',
            type: 'string',
          },
          {
            name: 'description',
            type: 'string',
          },
          {
            name: 'organizations',
            type: 'auto'
          },
          {
            name: 'created_at',
            type: 'date'
          },
          {
            name: 'updated_at',
            type: 'date'
          },
          {
            name: 'deleted_at',
            type: 'date'
          },
          {
            name: 'created_by',
            type: 'auto'
          },
          {
            name: 'updated_by',
            type: 'auto'
          },
          {
            name: 'deleted_by',
            type: 'auto'
          },
        ],
        remoteSort: true,
        proxy: {
          type: 'ajax',
          url: '{{ route('helpdesk.category.data') }}',
          reader: {
            root: 'data',
            totalProperty: 'count'
          },
          simpleSortMode: true
        },
      });

      me.menus = Ext.create('Ext.menu.Menu', {
        items: [
          @if ($user->hasRoute('helpdesk.category.store'))
            {
              text: 'Create',
              iconCls: 'icon-add',
              handler: formCategory.create
            },
          @endif

          @if ($user->hasRoute('helpdesk.category.update'))
            {
              text: 'Edit',
              iconCls: 'icon-edit',
              handler: function() {
                var rec = grids.getRec(true);
                if (rec) {
                  formCategory.edit(rec);
                } else Ext.msg.warning('Please Select Data');
              }
            },
          @endif

          @if ($user->hasRoute('helpdesk.category.destroy'))
            {
              text: 'Delete',
              iconCls: 'icon-remove',
              handler: function() {
                let recs = me.getValues();
                if (recs.length) {
                  Ext.ajaxConfirm('Remove Category', {
                    mask: me.grid,
                    url: '{{ route('helpdesk.category.destroy') }}',
                    params: {
                      '_method': 'DELETE',
                      '_token': '{{ csrf_token() }}',
                      data: Ext.encode(recs)
                    },
                    success: me.storeLoad
                  });
                } else Ext.msg.warning('Please select data!');
              }
            },
          @endif

          '-',

          @if ($user->hasRoute(['helpdesk.category.restore', 'helpdesk.category.forcedelete']))
            {
              text: 'Restore',
              iconCls: 'icon-refresh',
              handler: function() {
                let recs = me.getValues();
                if (recs.length) {
                  Ext.ajaxConfirm('Restore Category', {
                    mask: me.grid,
                    url: '{{ route('helpdesk.category.restore') }}',
                    params: {
                      '_method': 'DELETE',
                      '_token': '{{ csrf_token() }}',
                      data: Ext.encode(recs)
                    },
                    success: me.storeLoad
                  });
                } else Ext.msg.warning('Please select data!');
              }
            }, {
              text: 'Forever Remove',
              iconCls: 'icon-remove',
              handler: function() {
                let recs = me.getValues();
                if (recs.length) {
                  Ext.ajaxConfirm('Forever Remove Category', {
                    mask: me.grid,
                    url: '{{ route('helpdesk.category.forcedelete') }}',
                    params: {
                      '_method': 'DELETE',
                      '_token': '{{ csrf_token() }}',
                      data: Ext.encode(recs)
                    },
                    success: me.storeLoad
                  });
                } else Ext.msg.warning('Please select data!');
              }
            }
          @endif
        ]
      });

      me.grid = Ext.create('Ext.grid.Panel', {
        region: 'center',
        store: me.store,
        selType: 'checkboxmodel',
        border: true,
        cls: 'large-grid',
        columns: [{
            text: "Name",
            dataIndex: 'name',
            width: 400,
          },
          {
            text: "Description",
            dataIndex: 'description',
            width: 500,
            flex: 1,
            renderer: function(val, meta, rec) {
              let r = rec.data;
              if (r.description) return r.description;
              return '-';
            }
          },
        ],
        bbar: me.bottomBar([{
          xtype: 'filter',
          id: 'trash',
          name: 'Trash',
          param: 'trash',
          iconCls: 'icon-trash',
          items: [{
              id: 1,
              name: 'ACTIVE'
            },
            {
              id: 2,
              name: 'TRASH'
            }
          ]
        }, {
          xtype: 'filter',
          id: 'folder',
          name: 'Organization',
          param: 'folder',
          iconCls: 'icon-folder',
          items: dataOrganizations.map(function(item) {
            return {
              id: item.id,
              name: item.name
            };
          })
        }, ]),
        viewConfig: {
          stripeRows: false,
          getRowClass: function(rec) {
            if (rec.get('deleted_at')) return 'disabled';
          },
          listeners: {
            itemcontextmenu: function(obj, rec, node, index, e) {
              e.stopEvent();
              me.loadProperty();
              let hasDeletedAt = rec.get('deleted_at') !== null;
              me.updateMenuItemsVisibility(rec)

              @if (
                  $user->hasRoute([
                      'helpdesk.category.store',
                      'helpdesk.category.update',
                      'helpdesk.category.delete',
                      'helpdesk.category.restore',
                      'helpdesk.category.forcedelete',
                  ]))
                me.menus.showAt(e.getXY());
              @endif
            },
            itemclick: function(obj, rec) {
              me.updateMenuItemsVisibility(rec)
              if (!rec.get('deleted_at')) {
                me.selected = rec;
                details.set(rec.data)
              };
            },
            itemdblclick: function(obj, rec) {
              if (!rec.get('deleted_at')) {
                me.selected = rec;
                details.show();
              }
            }
          }
        }
      });
    }
  }
</script>
