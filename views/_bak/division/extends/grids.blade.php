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
                name: 'name',
                type: 'string'
              },
              {
                name: 'company',
                type: 'auto'
              },
              {
                name: 'company_id',
                type: 'string'
              },
              {
                name: 'deleted_at',
                type: 'date'
              },
            ],
            remoteSort: true,
            proxy: {
              type: 'ajax',
              url: '{{ route('division.data') }}',
              reader: {
                root: 'data',
                totalProperty: 'count'
              },
              simpleSortMode: true,
            },
          })

          me.menus = Ext.create('Ext.menu.Menu', {
            items: [{
                text: 'Create',
                iconCls: 'icon-add',
                handler: forms.create
              },
              {
                text: 'Edit',
                iconCls: 'icon-edit',
                handler: forms.edit
              },
              {
                text: 'Delete',
                iconCls: 'icon-remove',
                handler: function() {
                  let recs = me.getValues();
                  if (recs.length) {
                    Ext.ajaxConfirm('Delete Data', {
                      mask: me.grid,
                      url: '{{ route('division.delete') }}',
                      params: {
                        '_method': 'DELETE',
                        '_token': '{{ csrf_token() }}',
                        data: Ext.encode(
                          recs
                        )
                      },
                      success: me.storeLoad
                    });
                  } else {
                    Ext.msg.warning(
                      'Please select data!'
                    );
                  }
                }
              },
              '-',
              {
                text: 'Restore',
                iconCls: 'icon-refresh',
                handler: function() {
                  let recs = me.getValues();
                  if (recs.length) {
                    Ext.ajaxConfirm('Restore Data', {
                      mask: me.grid,
                      url: '{{ route('division.restore') }}',
                      params: {
                        '_method': 'PUT',
                        '_token': '{{ csrf_token() }}',
                        data: Ext.encode(
                          recs
                        )
                      },
                      success: me.storeLoad
                    });
                  } else {
                    Ext.msg.warning(
                      'Please select data!'
                    );
                  }
                }
              },
              {
                text: 'Forever Remove',
                iconCls: 'icon-trash',
                handler: function() {
                  let selection = me.getValues();
                  if (selection.length) {
                    Ext.ajaxConfirm('Forever Remove Data', {

                      url: '{{ route('division.forcedelete') }}',
                      method: 'DELETE',
                      params: {
                        '_token': '{{ csrf_token() }}',
                        data: Ext.encode(selection)
                      },
                      success: me.storeLoad,
                      failure: function() {
                        Ext.Msg.alert('Failed',
                          'Failed to permanently delete division.'
                        );
                      }
                    });
                  } else {
                    Ext.Msg.alert('Warning',
                      'Please select a record to delete.'
                    );
                  }
                }
              },
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
                width: 200,
                flex: 1,
              },
              {
                text: "Company",
                dataIndex: 'company',
                width: 200,
                flex: 1,
                renderer: function(val) {
                  return val ? val.name : '-';
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
                          'division.create',
                          'division.update',
                          'division.delete',
                          'division.restore',
                          'division.forcedelete',
                      ]))
                    me.menus.showAt(e.getXY());
                  @endif
                },
                itemclick: function(obj, rec) {
                  let hasDeletedAt = rec.get('deleted_at') !== null;
                  me.updateMenuItemsVisibility(rec)
                },
              },
            }
          });

        };
      };
    </script>


    <style>
      .deleted_item {
        background-color: #f8f9fa;
        color: #6c757d;
        opacity: 0.6;
      }

      .division-checkbox input[type="checkbox"],
      .division-item {
        cursor: pointer;
      }
    </style>
