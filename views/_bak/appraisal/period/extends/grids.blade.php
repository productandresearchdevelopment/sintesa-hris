    <script>
      var Grids = function() {
        let me = Ext.utils.grids(this);

        me.selected = null;

        me.init = function() {
          me.store = me.httpStore('{{ route('appraisal.period.data') }}', [{
              name: 'id',
              type: 'auto'
            },
            {
              name: 'appraisal_period_organizations',
              type: 'auto'
            },
            {
              name: 'company',
              type: 'auto'
            },
            {
              name: 'company_id',
              type: 'auto'
            },
            {
              name: 'period',
              type: 'auto'
            },
            {
              name: 'smester',
              type: 'auto'
            },
            {
              name: 'start_date',
              type: 'auto'
            },
            {
              name: 'end_date',
              type: 'auto'
            },
            {
              name: 'is_closed',
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
              @if ($user->hasRoute('appraisal.period.create') || $user->hasRoute('appraisal.period.update'))
                {
                  text: 'Create',
                  iconCls: 'icon-add',
                  handler: forms.create
                }, {
                  text: 'Edit',
                  iconCls: 'icon-edit',
                  handler: forms.edit
                },
                '-',
              @endif

              @if ($user->hasRoute('appraisal.period.delete'))
                {
                  text: 'Delete',
                  iconCls: 'icon-remove',
                  handler: function() {
                    let recs = me.getValues();
                    if (recs.length) {
                      Ext.ajaxConfirm('Remove Data', {
                        mask: me.grid,
                        url: '{{ route('appraisal.period.delete') }}',
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

              @if ($user->hasRoute('appraisal.period.restore') || $user->hasRoute('owners.forcedelete'))
                @if ($user->hasRoute('appraisal.period.restore'))
                  {
                    text: 'Restore',
                    iconCls: 'icon-refresh',
                    handler: function() {
                      let recs = me.getValues();
                      if (recs.length) {
                        Ext.ajaxConfirm('Restore Data', {
                          mask: me.grid,
                          url: '{{ route('appraisal.period.restore') }}',
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

                @if ($user->hasRoute('appraisal.period.forcedelete'))
                  {
                    text: 'Forever Remove',
                    iconCls: 'icon-remove',
                    handler: function() {
                      let recs = me.getValues();
                      if (recs.length) {
                        Ext.ajaxConfirm('Forever Remove Data', {
                          mask: me.grid,
                          url: '{{ route('appraisal.period.forcedelete') }}',
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
              '->', {
                xtype: 'searchfield',
                flex: 1,
                maxWidth: 300,
                minWidth: 180,
                store: me.store
              }
            ],
            columns: [
              @if (in_array(strtolower($user->role->name ?? ''), ['superadmin', 'developer', 'administrator']))
                {
                  text: "Company",
                  dataIndex: 'company',
                  width: 180,
                  flex: 1,
                  renderer: function(val) {
                    return val?.name || '-';
                  }
                },
              @endif {
                text: "Period",
                dataIndex: 'period',
                width: 200,
                flex: 1,
              },
              {
                text: "Semester",
                dataIndex: 'smester',
                width: 200,
                flex: 1,
              },
              {
                text: "Start Date",
                dataIndex: 'start_date',
                width: 200,
                flex: 1,
              },
              {
                text: "End Date",
                dataIndex: 'end_date',
                width: 200,
                flex: 1,
              },
              {
                text: "Is Closed",
                dataIndex: 'is_closed',
                width: 200,
                flex: 1,
                renderer: function(value) {
                  return value == 1 || value === true ?
                    '<span style="color: green;">✔</span>' :
                    '<span style="color: red;">✖</span>';
                }
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
              }]),
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
                          'appraisal.period.create',
                          'appraisal.period.update',
                          'appraisal.period.delete',
                          'appraisal.period.restore',
                          'appraisal.period.forcedelete',
                      ]))
                    me.menus.showAt(e.getXY());
                  @endif
                },
                itemclick: function(obj, rec) {
                  me.updateMenuItemsVisibility(rec)
                  if (!rec.get('deleted_at')) {
                    me.selected = rec;
                    // viewDetail.load("{{ route('appraisal.period.view') }}/" + rec.get('id'));
                  }
                },
                // itemdblclick: function(obj, rec) {
                //   if (!rec.get('deleted_at')) {
                //     me.selected = rec;
                //     viewDetail.expand();
                //   }
                // }
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
