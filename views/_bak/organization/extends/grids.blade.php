<script>
  var Grids = function() {
    let me = Ext.utils.grids(this);

    me.selected = null;
    me.selectedCompany = null;

    me.init = function() {

      me.storeCompany = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        proxy: {
          type: 'ajax',
          url: '{{ route('company.data') }}',
          reader: {
            root: 'data',
            totalProperty: 'count'
          }
        },
        autoLoad: true,
        listeners: {
          load: function(store) {
            if (store.getCount() > 1) {
              me.showCompanySelection();
            } else {
              const singleCompanyId = Array.isArray(store.getAt(0)) ? store.getAt(0).get('id') : store.getAt(
                0).data.id;
              setTimeout(() => {
                me.store.proxy.extraParams.company_id = singleCompanyId;
                me.selectedCompany = singleCompanyId;
                me.storeLoad();
                me.grid.show();
              }, 100);
            }
          }
        }
      });

      me.showCompanySelection = function() {
        const companyCombo = Ext.create('Ext.form.ComboBox', {
          store: me.storeCompany,
          queryMode: 'local',
          displayField: 'name',
          valueField: 'id',
          editable: false,
          padding: 20,
          listeners: {
            select: function(combo, records) {
              setTimeout(() => {
                const selectedCompanyId = records[0].get('id');
                me.store.proxy.extraParams.company_id = selectedCompanyId;
                me.selectedCompany = selectedCompanyId;
                me.storeLoad();
                me.grid.show();
                companySelectionWin.close();
              }, 100);
            }
          }
        });

        const companySelectionWin = Ext.create('Ext.window.Window', {
          title: 'Select Company',
          modal: true,
          width: 300,
          layout: 'fit',
          items: [companyCombo],
          closable: false
        });

        companySelectionWin.show();
      };


      me.menus = Ext.create('Ext.menu.Menu', {
        id: 'main-menu',
        items: [
          @if ($user->hasRoute('organization.create'))
            {
              text: 'Create',
              iconCls: 'icon-add',
              handler: function() {
                forms.create(me.selectedCompany);
              }
            },
          @endif

          @if ($user->hasRoute('organization.update'))
            {
              text: 'Edit',
              iconCls: 'icon-edit',
              handler: function() {
                forms.edit(me.selectedCompany);
              }
            },
          @endif

          @if ($user->hasRoute('organization.set.division'))
            {
              text: 'Set Division',
              iconCls: 'icon-tag',
              handler: formDivision.open
            },
          @endif

          @if ($user->hasRoute('organization.delete'))
            {
              text: 'Delete',
              iconCls: 'icon-remove',
              handler: function() {
                let recs = me.getValues();
                if (recs.length) {
                  Ext.ajaxConfirm('Remove Organization', {
                    mask: me.grid,
                    url: '{{ route('organization.delete') }}',
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
          @if ($user->hasRoute(['organization.restore', 'organization.forcedelete']))
            {
              text: 'Restore',
              iconCls: 'icon-refresh',
              handler: function() {
                let recs = me.getValues();
                if (recs.length) {
                  Ext.ajaxConfirm('Restore File', {
                    mask: me.grid,
                    url: '{{ route('organization.restore') }}',
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
              iconCls: 'icon-trash',
              handler: function() {
                let recs = me.getValues();
                if (recs.length) {
                  Ext.ajaxConfirm('Forever Remove File', {
                    mask: me.grid,
                    url: '{{ route('organization.forcedelete') }}',
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
        ]
      });

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
          id: '0',
          text: 'PT Qualita Indonesia',
          icon: '{{ asset('images/icons/home.png') }}',
          expanded: true
        },
        proxy: {
          type: 'ajax',
          url: '{{ route('organization.data') }}'
        },
        listeners: {
          load: function() {
            if (me.selected) {
              var node = me.store.tree.getNodeById(me.selected);
              if (node) {
                me.grid.expandPath(node.getPath());
                me.grid.selectPath(node.getPath());
              }
            }
          }
        }
      });

      me.grid = Ext.create('Ext.tree.Panel', {
        id: 'grid-main',
        title: 'Organizations',
        region: 'center',
        rootVisible: false,
        multiSelect: true,
        singleExpand: true,
        border: true,
        hidden: true,
        selType: 'checkboxmodel',
        store: me.store,
        sortableColumns: false,
        enableColumnHide: false,
        enableColumnMove: false,
        enableLocking: false,
        dockedItems: [{
          dock: 'top',
          xtype: 'toolbar',
          items: [{
              text: 'Menu',
              iconCls: 'icon-menu',
              menu: me.menus
            },
            '->',
            {
              text: 'Refresh',
              iconCls: 'icon-refresh',
              handler: function() {
                me.store.proxy.extraParams.selected = me.selected;
                me.storeLoad();
              }
            }
          ]
        }],
        columns: [{
            text: 'Name',
            dataIndex: 'text',
            xtype: 'treecolumn',
            minWidth: 200,
            width: 300,
            locked: true
          },
          {
            text: 'Alias',
            dataIndex: 'alias',
            width: 100
          },
          {
            text: 'Division',
            dataIndex: 'division',
            width: 200,
            renderer: function(val) {
              return val ? val.name : null;
            }
          },
          {
            text: 'Position',
            dataIndex: 'position',
            width: 200,
            renderer: function(val) {
              return val ? val.name : null;
            }
          },
          {
            text: 'Authorized1',
            dataIndex: 'authorized1',
            width: 200,
            renderer: function(val) {
              return val ? val.name : null;
            }
          },
          {
            text: 'Authorized2',
            dataIndex: 'authorized2',
            width: 200,
            renderer: function(val) {
              return val ? val.name : null;
            }
          },
          {
            text: 'Description',
            dataIndex: 'description',
            width: 200
          },
        ],
        viewConfig: {
          enableTextSelection: true,
          plugins: {
            ptype: 'treeviewdragdrop',
            containerScroll: true
          },
          listeners: {
            drop: me.move,
            itemcontextmenu: function(obj, rec, node, index, e) {
              e.stopEvent();
              me.updateMenuItemsVisibility(rec)
              me.menus.showAt(e.getXY());
            },
            itemclick: function(obj, rec) {
              let hasDeletedAt = rec.get('deleted_at') !== null;
              me.updateMenuItemsVisibility(rec)
            },
          }
        }
      })
    }

    me.move = function(node, data, drop, mode) {
      me.selected = data.records[0].data.id;
      var from = data.records[0].data;
      var to = drop.data;
      http.request({
        method: 'POST',
        url: '{{ route('organization.move', '') }}/' + mode,
        params: {
          _method: 'PUT',
          _token: '{{ csrf_token() }}',
          from: Ext.encode(from),
          to: Ext.encode(to)
        },
        success: function() {
          Ext.example.msg('Moved', 'Organization Has Moved !');
          me.storeLoad();
        }
      });
    }
  };
</script>
