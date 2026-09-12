<script>
  var Grids = function() {
    let me = Ext.utils.grids(this);

    me.selected = null;

    me.init = function() {
      me.store = Ext.create('Ext.data.Store', {
        autoload: true,
        fields: [{
            name: 'id',
            type: 'int'
          },
          {
            name: 'title',
            type: 'string'
          },
          {
            name: 'organization_id',
            mapping: 'organization.id',
            type: 'int'
          },
          {
            name: 'organization_name',
            mapping: 'organization.name',
            type: 'string'
          },
          {
            name: 'category_id',
            mapping: 'category.id',
            type: 'int'
          },
          {
            name: 'category_name',
            mapping: 'category.name',
            type: 'string'
          },
          {
            name: 'created_by_avatar',
            type: 'string'
          },
          {
            name: 'created_by_name',
            mapping: 'created_by.name',
            type: 'string'
          },
          {
            name: 'message',
            type: 'string'
          },
          {
            name: 'user_mentions',
            type: 'auto'
          },
          {
            name: 'created_at',
            type: 'date'
          },
          {
            name: 'deleted_at',
            type: 'date'
          },
        ],
        remoteSort: true,
        proxy: {
          type: 'ajax',
          url: '{{ route('helpdesk.data') }}',
          reader: {
            root: 'data',
            totalProperty: 'count'
          },
          simpleSortMode: true
        },
      });

      me.menus = Ext.create('Ext.menu.Menu', {
        items: [
          @if ($user->hasRoute('helpdesk.store'))
            {
              text: 'Create',
              iconCls: 'icon-add',
              handler: formHelpdesk.create
            }, {
              text: 'Edit',
              iconCls: 'icon-edit',
              handler: formHelpdesk.edit
            },
          @endif

          @if ($user->hasRoute('helpdesk.destroy'))
            {
              text: 'Delete',
              iconCls: 'icon-remove',
              handler: function() {
                let selectedIds = me.getValues();
                if (selectedIds.length) {
                  Ext.ajaxConfirm('Remove Data', {
                    mask: me.grid,
                    url: '{{ route('helpdesk.destroy') }}',
                    params: {
                      '_method': 'DELETE',
                      '_token': '{{ csrf_token() }}',
                      data: Ext.encode(selectedIds)
                    },
                    success: me.storeLoad
                  });
                } else Ext.msg.warning('Please select data!');
              }
            },
          @endif

          @if ($user->hasRoute('helpdesk.closed') || $user->hasRoute('helpdesk.unclosed'))
            {
              text: 'Close',
              iconCls: 'icon-lock',
              handler: function() {
                let selectedIds = me.getValues();
                if (selectedIds.length) {
                  Ext.Msg.confirm('Confirmation', 'Are you sure you want to close the selected data?',
                    function(btn) {
                      if (btn === 'yes') {
                        Ext.Ajax.request({
                          url: '{{ route('helpdesk.closed') }}',
                          method: 'PUT',
                          params: {
                            '_token': '{{ csrf_token() }}',
                            data: Ext.encode(selectedIds)
                          },
                          success: function() {
                            Ext.Msg.alert('Success', 'Data has been closed');
                            me.storeLoad();
                          }
                        });
                      }
                    });
                } else {
                  Ext.Msg.warning('Please select data!');
                }
              }
            }, {
              text: 'Unclose',
              iconCls: 'icon-unlock',
              handler: function() {
                let selectedIds = me.getValues();
                if (selectedIds.length) {
                  Ext.Msg.confirm('Confirmation', 'Are you sure you want to unclose the selected data?',
                    function(btn) {
                      if (btn === 'yes') {
                        Ext.Ajax.request({
                          url: '{{ route('helpdesk.unclosed') }}',
                          method: 'PUT',
                          params: {
                            '_token': '{{ csrf_token() }}',
                            data: Ext.encode(selectedIds)
                          },
                          success: function() {
                            Ext.Msg.alert('Success', 'Data has been unclosed');
                            me.storeLoad();
                          }
                        });
                      }
                    });
                } else {
                  Ext.Msg.warning('Please select data!');
                }
              }
            },
          @endif

          @if ($user->hasRoute('helpdesk.answer.store'))
            '-',
            {
              text: 'Create Answer',
              iconCls: 'icon-add',
              handler: formAnswer.create
            },
          @endif

          @if ($user->hasRoute('helpdesk.restore') || $user->hasRoute('helpdesk.forcedelete'))
            '-',
            {
              text: 'Restore',
              iconCls: 'icon-refresh',
              handler: function() {
                let selectedIds = me.getValues();
                if (selectedIds.length) {
                  Ext.ajaxConfirm('Restore Data', {
                    mask: me.grid,
                    url: '{{ route('helpdesk.restore') }}',
                    params: {
                      '_method': 'PUT',
                      '_token': '{{ csrf_token() }}',
                      data: Ext.encode(selectedIds)
                    },
                    success: me.storeLoad
                  });
                } else Ext.msg.warning('Please select data!');
              }
            },
            {
              text: 'Forever Remove',
              iconCls: 'icon-remove',
              handler: function() {
                let selectedIds = me.getValues();
                if (selectedIds.length) {
                  Ext.ajaxConfirm('Forever Remove Data', {
                    mask: me.grid,
                    url: '{{ route('helpdesk.forcedelete') }}',
                    params: {
                      '_method': 'DELETE',
                      '_token': '{{ csrf_token() }}',
                      data: Ext.encode(selectedIds)
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
            text: "Title",
            dataIndex: 'title',
            width: 200,
            flex: 1,
          },
          {
            text: "Message",
            dataIndex: 'message',
            width: 500,
            flex: 1,
            renderer: function(val, meta, rec) {
              let r = rec.data;
              if (r.message) return r.message.replace(/(@\w+)/g, '<span style="color: #007bff;">$1</span>');
            }
          },
          {
            text: "Category",
            dataIndex: 'category_name',
            width: 200,
            flex: 1,
          },
          {
            text: "Mentions",
            dataIndex: 'mentions',
            width: 150,
            renderer: function(val, meta, rec) {
              let r = rec.data;
              if (r.user_mentions) return r.user_mentions.length > 0 ? `${r.user_mentions.length} person` :
                '-';
            }
          },
          {
            text: "Created At",
            dataIndex: 'created_at',
            width: 150,
            align: 'center',
            renderer: function(val, meta, rec) {
              let value = rec.data.created_at;
              let date = new Date(value);
              let hour = date.getHours();
              let minute = date.getMinutes();
              let period = hour >= 12 ? 'PM' : 'AM';
              hour = hour % 12 || 12;
              minute = minute < 10 ? '0' + minute : minute;
              return `${hour}:${minute} ${period}`;
            }
          }
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
        }]),
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

              @if (
                  $user->hasRoute([
                      'helpdesk.store',
                      'helpdesk.update',
                      'helpdesk.destroy',
                      'helpdesk.restore',
                      'helpdesk.forcedelete',
                  ]))
                me.menus.showAt(e.getXY());
              @endif
            },
            itemclick: function(obj, rec) {
              me.updateMenuItemsVisibility(rec)
              if (!rec.get('deleted_at')) {
                me.selected = rec;
                details.set(rec.data)
              }
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

<style>
  .deleted_item {
    background-color: #f8f9fa;
    color: #6c757d;
    opacity: 0.6;
  }

  .helpdesk-checkbox input[type="checkbox"],
  .helpdesk-item {
    cursor: pointer;
  }
</style>
