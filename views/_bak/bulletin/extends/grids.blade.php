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
                name: 'description',
                type: 'string'
              },
              {
                name: 'cover_image_id',
                type: 'string'
              },
              {
                name: 'cover_image',
                type: 'string'
              },
              {
                name: 'cover',
                type: 'string'
              },
              {
                name: 'category_id',
                type: 'int'
              },
              {
                name: 'category',
                type: 'auto',
              },
              {
                name: 'is_pinned',
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
            ],
            remoteSort: true,
            proxy: {
              type: 'ajax',
              url: '{{ route('bulletin.data') }}',
              reader: {
                root: 'data',
                totalProperty: 'count'
              },
              simpleSortMode: true,
            },
          })

          // Menu konteks untuk operasi grid
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
                text: 'Set Pin',
                iconCls: 'icon-tag',
                action: 'set_pin',
                handler: function() {
                  let recs = me.getValues();
                  if (recs.length) {
                    let data = recs.map(rec => ({
                      id: rec,
                      is_pinned: 1
                    }));

                    Ext.ajaxConfirm('Set Pin Bulletin', {
                      mask: me.grid,
                      url: '{{ route('bulletin.set.pin') }}',
                      params: {
                        '_method': 'PUT',
                        '_token': '{{ csrf_token() }}',
                        data: Ext.encode(data)
                      },
                      success: me.storeLoad
                    });
                  } else {
                    Ext.msg.warning('Please select data!');
                  }
                }
              },
              {
                text: 'Set Unpin',
                iconCls: 'icon-tag',
                action: 'set_unpin',
                handler: function() {
                  let selected = me.getValues();
                  if (selected.length) {
                    let data = selected.map(rec => ({
                      id: rec,
                      is_pinned: 0
                    }));

                    Ext.ajaxConfirm('Set Unpin Bulletin', {
                      mask: me.grid,
                      url: '{{ route('bulletin.set.pin') }}',
                      params: {
                        '_method': 'PUT',
                        '_token': '{{ csrf_token() }}',
                        data: Ext.encode(data)
                      },
                      success: me.storeLoad
                    });
                  } else {
                    Ext.msg.warning('Please select data!');
                  }
                }
              },
              {
                text: 'Delete',
                iconCls: 'icon-remove',
                handler: function() {
                  let recs = me.getValues();
                  if (recs.length) {
                    Ext.ajaxConfirm('Remove Bulletin', {
                      mask: me.grid,
                      url: '{{ route('bulletin.delete') }}',
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
              '-',
              {
                text: 'Restore',
                iconCls: 'icon-refresh',
                handler: function() {
                  let recs = me.getValues();
                  if (recs.length) {
                    Ext.ajaxConfirm('Restore Data', {
                      mask: me.grid,
                      url: '{{ route('bulletin.restore') }}',
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

                      url: '{{ route('bulletin.forcedelete') }}',
                      method: 'DELETE',
                      params: {
                        '_token': '{{ csrf_token() }}',
                        // id: selection[0].get(
                        //     'id'
                        // ), // Mengirimkan id dari bulletin yang dipilih
                        data: Ext.encode(selection)
                      },
                      success: me.storeLoad,
                      failure: function() {
                        Ext.Msg.alert('Failed',
                          'Failed to permanently delete bulletin.'
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
                text: "Title",
                dataIndex: 'title',
                width: 200,
                flex: 1,
              },
              {
                text: "Description",
                dataIndex: 'description',
                width: 500,
                flex: 1,
              },
              {
                text: "Category",
                dataIndex: 'category',
                width: 150,
                renderer: function(val, meta, rec) {
                  let r = rec.data;
                  if (r.category) return r.category ? `${r.category.name}` :
                    '-';
                }
              },
              {
                text: "Is Pinned",
                dataIndex: 'is_pinned',
                width: 100,
                renderer: function(val, meta, rec) {
                  let r = rec.data;
                  if (r.is_pinned == 1) {
                    return '✅';
                  } else {
                    return '❌';
                  }
                }
              },
              {
                text: "Created At",
                dataIndex: 'created_at',
                width: 150,
                flex: 1,
                align: 'center',
                renderer: function(val, meta, rec) {
                  let value = rec.data.created_at;
                  const date = new Date(value);

                  const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                  const dayName = dayNames[date.getDay()];

                  const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt',
                    'Nov', 'Des'
                  ];
                  const day = date.getDate().toString().padStart(2, '0');
                  const month = monthNames[date.getMonth()];
                  const year = date.getFullYear();

                  let hours = date.getHours();
                  const minutes = date.getMinutes().toString().padStart(2, '0');
                  const period = hours >= 12 ? 'PM' : 'AM';
                  hours = hours % 12 || 12;

                  return `${dayName}, ${day} ${month} ${year} | ${hours}:${minutes} ${period}`;
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
                  let isPinned = rec.get('is_pinned') == 1;

                  me.updateMenuItemsVisibility(rec)

                  me.menus.items.each(function(item) {
                    let text = item.text;
                    let action = item.action;

                    if (!hasDeletedAt) {
                      if (action === 'set_pin') {
                        if (isPinned) {
                          item.hide();
                        } else {
                          item.show();
                        }
                      } else if (action === 'set_unpin') {
                        if (isPinned) {
                          item.show();
                        } else {
                          item.hide();
                        }
                      }
                    }
                  });

                  @if (
                      $user->hasRoute([
                          'bulletin.create',
                          'bulletin.update',
                          'bulletin.delete',
                          'bulletin.restore',
                          'bulletin.forcedelete',
                          'bulletin.set.pin',
                      ]))
                    me.menus.showAt(e.getXY());
                  @endif
                },
                itemclick: function(obj, rec) {
                  let hasDeletedAt = rec.get('deleted_at') !== null;
                  let isPinned = rec.get('is_pinned') == 1;
                  me.updateMenuItemsVisibility(rec)
                  me.menus.items.each(function(item) {
                    let text = item.text;
                    let action = item.action;

                    if (!hasDeletedAt) {
                      if (action === 'set_pin') {
                        if (isPinned) {
                          item.hide();
                        } else {
                          item.show();
                        }
                      } else if (action === 'set_unpin') {
                        if (isPinned) {
                          item.show();
                        } else {
                          item.hide();
                        }
                      }
                    }
                  });
                  if (!hasDeletedAt) {
                    me.selected = rec;
                    viewDetail.load("{{ route('bulletin.view') }}/" + rec.get('id'));
                  }
                },
                itemdblclick: function(obj, rec) {
                  if (!rec.get('deleted_at')) {
                    me.selected = rec;
                    viewDetail.expand();
                  }
                }
              }
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

      .bulletin-checkbox input[type="checkbox"],
      .bulletin-item {
        cursor: pointer;
      }
    </style>
