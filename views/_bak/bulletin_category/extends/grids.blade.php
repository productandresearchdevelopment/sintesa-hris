<script>
  var Grids = function() {
    let me = Ext.utils.grids(this);

    me.selected = null;

    me.init = function() {
      me.store = me.httpStore('{{ route('bulletin.category.data') }}', [{
          name: 'id',
          type: 'int'
        },
        {
          name: 'name',
          type: 'string'
        },
        {
          name: 'alias',
          type: 'string'
        },
        {
          name: 'color',
          type: 'string'
        },
        {
          name: 'description',
          type: 'string'
        },
        {
          name: 'count_bulletin',
          type: 'string'
        },
        {
          name: 'bulletins',
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
      ]);

      me.menus = Ext.create('Ext.menu.Menu', {
        items: [
          @if ($user->hasRoute('category.push'))
            {
              text: 'Create',
              iconCls: 'icon-add',
              handler: forms.create
            }, {
              text: 'Edit',
              iconCls: 'icon-edit',
              handler: forms.edit
            },
          @endif

          @if ($user->hasRoute('bulletin.category.delete'))
            {
              text: 'Delete',
              iconCls: 'icon-remove',
              handler: function() {
                let recs = me.getValues();
                if (recs.length) {
                  Ext.ajaxConfirm('Remove Category', {
                    mask: me.grid,
                    url: '{{ route('bulletin.category.delete') }}',
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

          @if ($user->hasRoute('category.restore') || $user->hasRoute('owners.forcedelete'))
            '-',
            @if ($user->hasRoute('bulletin.category.restore'))
              {
                text: 'Restore',
                iconCls: 'icon-refresh',
                handler: function() {
                  let recs = me.getValues();
                  if (recs.length) {
                    Ext.ajaxConfirm('Restore Category', {
                      mask: me.grid,
                      url: '{{ route('bulletin.category.restore') }}',
                      params: {
                        '_method': 'PUT',
                        '_token': '{{ csrf_token() }}',
                        data: Ext.encode(recs)
                      },
                      success: me.storeLoad
                    });
                  } else Ext.msg.warning(
                    'Please select data!');
                }
              },
            @endif

            @if ($user->hasRoute('bulletin.category.forcedelete'))
              {
                text: 'Forever Remove',
                iconCls: 'icon-remove',
                handler: function() {
                  let recs = me.getValues();
                  if (recs.length) {
                    Ext.ajaxConfirm('Forever Remove Category', {
                      mask: me.grid,
                      url: '{{ route('bulletin.category.forcedelete') }}',
                      params: {
                        '_method': 'DELETE',
                        '_token': '{{ csrf_token() }}',
                        data: Ext.encode(recs)
                      },
                      success: me.storeLoad
                    });
                  } else Ext.msg.warning(
                    'Please select data!');
                }
              }
            @endif
          @endif
        ]
      });

      me.grid = Ext.create('Ext.grid.Panel', {
        region: 'center',
        store: me.store,
        selType: 'checkboxmodel',
        border: true,
        cls: 'large-grid',
        tbar: me.tbar(me.menus),
        columns: [{
            text: "#",
            dataIndex: 'alias',
            width: 80,
            align: 'center',
            renderer: function(val, meta, rec) {
              let r = rec.data;
              return me.renderBox(r.alias, r.color, r.name, meta);
            }
          },
          {
            text: "NAME",
            dataIndex: 'name',
            minWidth: 200
          },
          {
            text: "DESCRIPTION",
            dataIndex: 'description',
            minWidth: 200,
            flex: 1
          },
          {
            text: "BULLETIN",
            dataIndex: 'count_bulletin',
            minWidth: 200,
            flex: 1
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
              name: 'ACTIVE',
            },
            {
              id: 2,
              name: 'TRASH'
            }
          ]
        }, ]),
        viewConfig: {
          stripeRows: false,
          getRowClass: function(rec) {
            if (rec.get('deleted_at')) return 'disabled';
          },
          listeners: {
            itemcontextmenu: function(obj, rec, node, index, e) {
              e.stopEvent();
              let hasDeletedAt = rec.get('deleted_at') !== null;
              me.updateMenuItemsVisibility(rec)
              me.menus.showAt(e.getXY());
            },
            itemclick: function(obj, rec) {
              let hasDeletedAt = rec.get('deleted_at') !== null;
              me.updateMenuItemsVisibility(rec)
              if (!hasDeletedAt) {
                me.selected = rec;
                details.set(rec.data)
              }
            },
            itemdblclick: function(obj, rec) {
              let hasDeletedAt = rec.get('deleted_at') !== null;
              if (!hasDeletedAt) {
                me.selected = rec;
                details.show();
              }
            },
          }
        }
      });
    }
  }
</script>
