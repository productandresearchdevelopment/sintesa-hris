<script>
  var UsersDetail = function() {
    let me = Ext.utils.grids(this);

    let userData = Ext.create('Ext.data.Store', {
      fields: ['id', 'name'],
      proxy: {
        type: 'ajax',
        url: '{{ route('auth.user.data') }}',
        reader: {
          root: 'data',
          totalProperty: 'count'
        }
      },
      autoLoad: true,
    });

    me.init = function() {
      me.userComboBox = Ext.create('Ext.form.FieldContainer', {
        fieldLabel: 'Select User',
        labelAlign: 'top',
        layout: 'hbox',
        width: 380,
        items: [{
          xtype: 'combo',
          store: userData,
          queryMode: 'local',
          displayField: 'name',
          valueField: 'id',
          margin: '5 0 0 0',
          width: 380,
          listConfig: {
            maxHeight: 200,
            width: 380
          },
          listeners: {
            select: function(combo, records) {
              const selectedUser = records[0].data;
              me.selectUser(selectedUser);
              combo.clearValue();
            }
          }
        }]
      });

      me.selectedUsersStore = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        proxy: {
          type: 'ajax',
          url: '{{ route('filemanager.data.user', '') }}/'
        },
        listeners: {
          load: function(store) {
            const grid = me.grid || Ext.ComponentQuery.query('#grid-organizations-filemanager-users')[0];
            if (grid && grid.getEl()) {
              grid.getEl().unmask();
            }

            const actionColumn = grid.down('actioncolumn');
            actionColumn.setVisible(store.getCount() > 0);
          }
        }
      });

      me.grid = Ext.create('Ext.grid.Panel', {
        title: 'USERS',
        region: 'center',
        border: true,
        sortableColumns: false,
        enableColumnHide: false,
        enableColumnMove: false,
        enableLocking: false,
        id: 'grid-organizations-filemanager-users',
        store: me.selectedUsersStore,
        viewConfig: {
          emptyText: '<div style="text-align: center; padding: 20px;">No Data</div>',
          deferEmptyText: false
        },
        columns: [{
            text: 'List User',
            dataIndex: 'name',
            flex: 1
          },
          {
            xtype: 'actioncolumn',
            text: '#',
            width: 50,
            align: 'center',
            items: [],
            renderer: function(value, meta) {
              meta.tdCls += ' x-action-col-cell';
              return '<i class="bi bi-trash-fill delete-icon" style="cursor: pointer; font-size: 16px;" data-qtip="Remove User"></i>';
            }
          }
        ],
        dockedItems: [{
          xtype: 'toolbar',
          dock: 'top',
          padding: 10,
          items: [me.userComboBox]
        }],
        listeners: {
          afterrender: function(grid) {
            grid.getEl().on('click', function(e, target) {
              if (Ext.fly(target).hasCls('delete-icon')) {
                const rowEl = Ext.fly(target).up('.x-grid-row');
                const record = grid.getView().getRecord(rowEl);

                if (record) {
                  Ext.Msg.confirm('Confirm Delete', 'Are you sure you want to remove this user?',
                    function(btn) {
                      if (btn === 'yes') {
                        me.removeUser(record.data);
                      }
                    });
                }
              }
            });
          }
        }
      });


      me.selectUser = function(user) {
        var rec = gridFile.getRec(true);
        if (!rec) {
          Ext.Msg.alert('Error', 'Please select a file manager first.');
          return;
        }

        var fileManagerId = rec.id;

        var existingUser = me.selectedUsersStore.findRecord('id', user.id);
        if (existingUser) {
          return;
        }

        http.request({
          method: 'post',
          url: '{{ route('filemanager.set.user') }}',
          params: {
            _token: '{{ csrf_token() }}',
            fileManagerId: fileManagerId,
            userId: user.id
          },
          success: function() {
            me.storeLoad();
          }
        });
      };

      me.removeUser = function(user) {
        var rec = gridFile.getRec(true);
        if (!rec) {
          Ext.Msg.alert('Error', 'Please select a file manager first.');
          return;
        }

        var fileManagerId = rec.id;

        http.request({
          method: 'delete',
          url: '{{ route('filemanager.remove.user') }}',
          params: {
            _token: '{{ csrf_token() }}',
            fileManagerId: fileManagerId,
            userId: user.id
          },
          success: function() {
            me.storeLoad();
          }
        });
      };

      me.storeLoad = function() {
        setTimeout(() => {
          var rec = gridFile.getRec(true);
          if (rec) {
            const grid = me.grid || Ext.ComponentQuery.query('#grid-organizations-filemanager-users')[0];
            if (grid && grid.getEl()) {
              grid.getEl().mask();
            }
            me.selectedUsersStore.removeAll();
            me.selectedUsersStore.proxy.url = '{{ route('filemanager.data.user', '') }}/' + rec.id;
            me.selectedUsersStore.load();
          }
        }, 500);
      };
    };
  };
</script>

<style>
  .x-grid-row .x-grid-td {
    background-color: white;
  }

  .bi-trash-fill {
    color: red;
  }
</style>
