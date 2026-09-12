    <script>
      var Grids = function() {
        let me = Ext.utils.grids(this);

        me.selected = null;

        me.init = function() {
          me.store = me.httpStore('{{ route('appraisal.question.template.data') }}', [{
              name: 'id',
              type: 'auto'
            },
            {
              name: 'division',
              type: 'auto'
            },
            {
              name: 'appraisal_questions',
              type: 'auto'
            },
            {
              name: 'title',
              type: 'string'
            },
            {
              name: 'period_year',
              type: 'auto'
            },
            {
              name: 'period_smt',
              type: 'auto'
            },
            {
              name: 'is_locked',
              type: 'auto'
            },
            {
              name: 'description',
              type: 'auto'
            },
            {
              name: 'is_archived',
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
          ]);

          me.menus = Ext.create('Ext.menu.Menu', {
            items: [
              @if ($user->hasRoute('appraisal.question.template.create') || $user->hasRoute('appraisal.question.template.update'))
                {
                  text: 'Create',
                  iconCls: 'icon-add',
                  handler: forms.create
                }, {
                  text: 'Edit',
                  iconCls: 'icon-edit',
                  handler: forms.edit
                },
                '-', {
                  text: 'Duplicate',
                  iconCls: 'icon-tag',
                  handler: forms.duplicate
                }, {
                  text: 'Set Archived',
                  iconCls: 'icon-folder-hide',
                  handler: function() {
                    let recs = me.getValues();
                    if (recs.length) {
                      Ext.ajaxConfirm('Archive Data?', {
                        mask: me.grid,
                        url: '{{ route('appraisal.question.template.set.archived') }}',
                        params: {
                          '_method': 'PUT',
                          '_token': '{{ csrf_token() }}',
                          data: Ext.encode(recs)
                        },
                        success: me.storeLoad
                      });
                    } else Ext.msg.warning('Please select data!');
                  }
                }, {
                  text: 'Set Unarchived',
                  iconCls: 'icon-folder',
                  handler: function() {
                    let recs = me.getValues();
                    if (recs.length) {
                      Ext.ajaxConfirm('Unarchive Data', {
                        mask: me.grid,
                        url: '{{ route('appraisal.question.template.set.archived') }}',
                        params: {
                          '_method': 'PUT',
                          '_token': '{{ csrf_token() }}',
                          data: Ext.encode(recs)
                        },
                        success: me.storeLoad
                      });
                    } else Ext.msg.warning('Please select data!');
                  }
                },
                '-',
              @endif

              @if ($user->hasRoute('appraisal.question.template.delete'))
                {
                  text: 'Delete',
                  iconCls: 'icon-remove',
                  handler: function() {
                    let recs = me.getValues();
                    if (recs.length) {
                      Ext.ajaxConfirm('Remove Data', {
                        mask: me.grid,
                        url: '{{ route('appraisal.question.template.delete') }}',
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

              @if ($user->hasRoute('appraisal.question.template.restore') || $user->hasRoute('owners.forcedelete'))
                @if ($user->hasRoute('appraisal.question.template.restore'))
                  {
                    text: 'Restore',
                    iconCls: 'icon-refresh',
                    handler: function() {
                      let recs = me.getValues();
                      if (recs.length) {
                        Ext.ajaxConfirm('Restore Data', {
                          mask: me.grid,
                          url: '{{ route('appraisal.question.template.restore') }}',
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

                @if ($user->hasRoute('appraisal.question.template.forcedelete'))
                  {
                    text: 'Forever Remove',
                    iconCls: 'icon-remove',
                    handler: function() {
                      let recs = me.getValues();
                      if (recs.length) {
                        Ext.ajaxConfirm('Forever Remove Data', {
                          mask: me.grid,
                          url: '{{ route('appraisal.question.template.forcedelete') }}',
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
                  },
                @endif

                @if ($user->hasRoute('appraisal.question.template.import'))
                  '-',
                  {
                    text: 'Import Data',
                    iconCls: 'icon-excel',
                    menu: [{
                        text: 'Download Format',
                        iconCls: 'icon-cloud',
                        handler: function() {
                          window.location =
                            '{{ route('appraisal.question.template.export.excel.format.import') }}';
                        }
                      },
                      {
                        text: 'Upload File',
                        iconCls: 'icon-excel',
                        handler: function() {
                          formsImport.open();
                        }
                      },
                    ]
                  },
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
            tbar: [{
                text: 'Menu',
                iconCls: 'icon-menu',
                menu: me.menus
              },
              @if ($user->hasRoute('appraisal.question.template.export.excel'))
                {
                  text: 'Export Excel',
                  iconCls: 'icon-excel',
                  handler: function() {
                    let filters = me.store.proxy.extraParams;
                    let query = '';
                    if (me.store.filters.items.length) query = me.store.filters.items[0].value;
                    filters.query = query;

                    let params = [];
                    for (var key in filters) {
                      var value = filters[key];
                      params.push(key + '=' + encodeURIComponent(value));
                    }

                    window.location = '{{ route('appraisal.question.template.export.excel') }}?' + params.join(
                      '&');
                  }
                },
              @endif
              '->', {
                xtype: 'searchfield',
                flex: 1,
                maxWidth: 300,
                minWidth: 180,
                store: me.store
              }
            ],
            columns: [{
                text: "Title",
                dataIndex: 'title',
                width: 200,
                flex: 1,
              },
              {
                text: "Division",
                dataIndex: 'division',
                width: 200,
                flex: 1,
                renderer: function(val, meta, rec) {
                  let r = rec.data;
                  if (r.division) return r.division ? `${r.division.name}` :
                    '-';
                }
              },
              {
                text: "Period Year",
                dataIndex: 'period_year',
                width: 200,
                flex: 1,
              },
              {
                text: "Period SMT",
                dataIndex: 'period_smt',
                width: 200,
                flex: 1,
              },
              {
                text: "Is Locked",
                dataIndex: 'is_locked',
                width: 200,
                flex: 1,
                renderer: function(value) {
                  return value == 1 || value === true ?
                    '<span style="color: green;">✔</span>' :
                    '<span style="color: red;">✖</span>';
                }
              },
              {
                text: "Description",
                dataIndex: 'description',
                width: 200,
                flex: 1,
              },
            ],
            bbar: me.bottomBar(
              [{
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
                },
                @if (in_array(strtolower(optional($user->role)->name), ['hrga', 'developer', 'superadmin']))
                  {
                    xtype: 'filter',
                    id: 'division',
                    name: 'Division',
                    param: 'division',
                    iconCls: 'icon-filter',
                    items: divisions
                  },
                @endif {
                  xtype: 'filter',
                  id: 'archived',
                  name: 'active',
                  param: 'archived',
                  iconCls: 'icon-filter',
                  items: [{
                    id: 1,
                    name: 'ARCHIVED'
                  }]
                },
              ]),
            viewConfig: {
              stripeRows: false,
              getRowClass: function(rec) {
                if (rec.get('deleted_at')) return 'disabled';
              },
              listeners: {
                itemcontextmenu: function(obj, rec, node, index, e) {
                  e.stopEvent();
                  me.updateMenuItemsVisibility(rec)

                  @if (
                      $user->hasRoute([
                          'appraisal.question.template.create',
                          'appraisal.question.template.update',
                          'appraisal.question.template.delete',
                          'appraisal.question.template.restore',
                          'appraisal.question.template.forcedelete',
                      ]))
                    me.menus.showAt(e.getXY());
                  @endif
                },
                itemclick: function(obj, rec) {
                  me.updateMenuItemsVisibility(rec)
                  if (!rec.get('deleted_at')) {
                    me.selected = rec;
                    @if (in_array(strtolower(optional($user->role)->name), ['hrga', 'developer', 'superadmin']))
                      details.set(rec.data);
                    @endif
                  }
                },
                itemdblclick: function(obj, rec) {
                  if (!rec.get('deleted_at')) {
                    me.selected = rec;
                    @if (in_array(strtolower(optional($user->role)->name), ['hrga', 'developer', 'superadmin']))
                      details.show();
                    @else
                      forms.edit();
                    @endif
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

      .appraisal-checkbox input[type="checkbox"],
      .appraisal-item {
        cursor: pointer;
      }
    </style>
