<script>
  var TreeFolder = function() {
    let me = Ext.utils.grids(
      this); // Menginisialisasi objek grid yang akan digunakan untuk menampilkan data dalam bentuk tree structure.

    me.timeout = null; // Menyimpan reference ke timeout untuk fungsi yang dijadwalkan (debouncing).

    me.init = function() {
      // Membuat store untuk tree panel yang memuat data folder
      me.store = Ext.create('Ext.data.TreeStore', {
        fields: [ // Definisi field yang digunakan dalam store
          {
            name: 'parent_id',
            type: 'int'
          },
          {
            name: 'project_id',
            type: 'int'
          },
          {
            name: 'is_task',
            type: 'int'
          },
          {
            name: 'path',
            type: 'string'
          },
          {
            name: 'level',
            type: 'int'
          },
          {
            name: 'name',
            type: 'string'
          },
          {
            name: 'sort',
            type: 'int'
          },
          {
            name: 'deleted_at',
            type: 'date'
          }
        ],
        root: { // Root node untuk tree panel
          id: '0',
          name: 'File Explorer',
          icon: '{{ asset('images/icons/cloud.png') }}',
          expanded: true
        },
        proxy: { // Konfigurasi proxy untuk melakukan permintaan data dari server
          type: 'ajax',
          url: '{{ route('filemanager.folder.data') }}',
          reader: {
            type: 'json',
            rootProperty: 'children'
          }
        },
        autoLoad: true
      });

      // Membuat menu konteks (context menu) untuk opsi operasi pada folder
      me.menus = Ext.create('Ext.menu.Menu', {
        items: [
          @if ($user->hasRoute('filemanager.folder.push'))
            {
              text: 'New Folder',
              iconCls: 'icon-folder',
              handler: formFolder.create // Membuka form untuk membuat folder baru
            }, {
              text: 'New File',
              iconCls: 'icon-file',
              handler: formFile.create // Membuka form untuk membuat file baru
            }, {
              text: 'New Link',
              iconCls: 'icon-earth',
              handler: formLink.create // Membuka form untuk membuat link baru
            },
          @endif

          {
            id: 'menu-folder-separator',
            xtype: 'menuseparator' // Membuat separator untuk memisahkan item menu
          },
          @if ($user->hasRoute('filemanager.folder.delete'))
            {
              id: 'menu-folder-edit',
              text: 'Edit',
              iconCls: 'icon-edit',
              handler: formFolder.edit // Membuka form untuk mengedit folder
            }, {
              id: 'menu-folder-delete',
              text: 'Delete',
              iconCls: 'icon-remove',
              handler: function() {
                let recs = me.getValues(); // Mengambil folder yang dipilih
                if (recs.length) {
                  Ext.ajaxConfirm('Delete', {
                    mask: me.grid,
                    url: '{{ route('filemanager.folder.delete') }}',
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

          @if ($user->hasRoute('filemanager.folder.restore'))
            {
              id: 'menu-folder-restore',
              text: 'Restore',
              iconCls: 'icon-refresh',
              handler: function() {
                let recs = me.getValues();
                if (recs.length) {
                  Ext.ajaxConfirm('Restore', {
                    mask: me.grid,
                    url: '{{ route('filemanager.folder.restore') }}',
                    params: {
                      '_method': 'DELETE',
                      '_token': '{{ csrf_token() }}',
                      data: Ext.encode(recs)
                    },
                    success: me.storeLoad,
                  });
                } else Ext.msg.warning(
                  'Please select data!');
              }
            },
          @endif

          @if ($user->hasRoute('filemanager.folder.forcedelete'))
            {
              id: 'menu-folder-forcedelete',
              text: 'Forever Remove',
              iconCls: 'icon-trash',
              handler: function() {
                let recs = me.getValues();
                if (recs.length) {
                  Ext.Msg.confirm('Confirm', 'Are you sure you want to force delete this folder?', function(
                    btn) {
                    if (btn === 'yes') {
                      Ext.Ajax.request({
                        url: '{{ route('filemanager.folder.forcedelete') }}',
                        method: 'DELETE',
                        params: {
                          '_method': 'DELETE',
                          '_token': '{{ csrf_token() }}',
                          data: Ext.encode(recs)
                        },
                        success: function(response) {
                          let result = Ext.decode(response.responseText);
                          if (result.success) {
                            me.storeLoad();
                            Ext.example.msg('Success', result.message);
                          } else {
                            Ext.Msg.alert('Error', result.message ||
                              'An unexpected error occurred.');
                            me.storeLoad();
                          }
                        },
                        failure: function(response) {
                          Ext.Msg.alert('Error',
                            'Unable to force delete the folder because it contains folders or files.'
                          );
                        }
                      });
                    }
                  });
                } else {
                  Ext.msg.warning('Please select data!');
                }
              }
            },
          @endif


        ]
      });

      me.grid = Ext.create('Ext.tree.Panel', {
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
          dataIndex: 'name',
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
              me.updateMenuItemsVisibility(rec)
              let onlyCurrentFolderCheckbox = me.grid.down('#onlyCurrentFolder');
              if (onlyCurrentFolderCheckbox) {
                let onlyCurrentFolder = onlyCurrentFolderCheckbox.checked ? 1 : 0;
                gridFile.store.proxy.extraParams['only-current-folder'] = onlyCurrentFolder;
                gridFile.storeLoad();
              } else {
                console.error('Checkbox "Only current folder" tidak ditemukan.');
              }
            },
            itemcontextmenu: function(obj, rec, node, index, e) {
              e.stopEvent();

              me.updateMenuItemsVisibility(rec)
              me.menus.showAt(e.getXY());
            }
          }
        },
        bbar: {
          xtype: 'toolbar',
          cls: 'custom-bbar',
          items: me.bottomBar([{
              xtype: 'button',
              itemId: 'filterButton',
              text: 'Active',
              iconCls: 'icon-trash',
              minWidth: 100,
              padding: '5 10',
              cls: 'custom-filter-button',
              style: {
                marginLeft: '20px'
              },
              menu: {
                items: [{
                    text: 'ALL TRASH',
                    itemId: 'menuAll trash',
                    iconCls: 'icon-yes',
                    handler: function() {
                      me.applyFilter('all trash');
                    }
                  },
                  {
                    text: 'ACTIVE',
                    itemId: 'menuActive',
                    iconCls: 'icon-yes',
                    handler: function() {
                      me.applyFilter('active');
                    }
                  },
                  {
                    text: 'TRASH',
                    itemId: 'menuTrash',
                    iconCls: 'icon-yes',
                    handler: function() {
                      me.applyFilter('trash');
                    }
                  }
                ]
              }
            },
            {
              xtype: 'checkbox',
              itemId: 'onlyCurrentFolder',
              boxLabel: 'Only this folder',
              style: {
                maxWidth: 'max-content',
                marginLeft: '10px',
                marginRight: '20px'
              },
              listeners: {
                change: function(checkbox, newValue) {
                  gridFile.store.proxy.extraParams['only-current-folder'] = newValue ? 1 : 0;
                  gridFile.store.load();
                }
              }
            }
          ], false)
        }

      });
      me.applyFilter('all trash');
    };

    me.applyFilter = function(filterValue) {
      let store = me.store;
      let loadingMask = null;

      if (me.grid.rendered) {
        loadingMask = new Ext.LoadMask({
          msg: 'Loading...',
          target: me.grid
        });
        loadingMask.show();
      } else {
        me.grid.on('afterrender', function() {
          loadingMask = new Ext.LoadMask({
            msg: 'Loading...',
            target: me.grid
          });
          loadingMask.show();
        }, me, {
          single: true
        });
      }

      store.clearFilter(true);
      store.proxy.extraParams.filter = filterValue;

      store.load({
        callback: function(records, operation, success) {
          if (loadingMask) {
            loadingMask.hide();
            loadingMask.destroy();
          }

          if (filterValue === 'trash') {
            let trashRoot = {
              id: 'trash-root',
              name: 'Trash',
              icon: '{{ asset('images/icons/trash.png') }}',
              expanded: true,
              children: []
            };
            store.getRootNode().removeAll();
            Ext.each(records, function(record) {
              if (!record.get('parent_id') || record.get('deleted_at')) {
                trashRoot.children.push(record.data);
              }
            });
            store.setRootNode(trashRoot);
          } else {
            store.getRootNode().removeAll();
            store.setRootNode({
              id: '0',
              name: 'File Explorer',
              icon: '{{ asset('images/icons/cloud.png') }}',
              expanded: true
            });
          }
        }
      });

      let filterButton = me.grid.down('#filterButton');
      filterButton.setText(filterValue
        .toUpperCase());

      let menuItems = filterButton.menu.items.items;

      Ext.each(menuItems, function(item) {
        let expectedItemId = 'menu' + filterValue.charAt(0).toUpperCase() + filterValue.slice(1);

        if (item.itemId === expectedItemId) {
          item.addCls('active-item');
        } else {
          item.removeCls('active-item');
        }
      });
    };

    me.loadFolder = function(value) {
      value = value ? value : 0;
      if (me.timeout) clearTimeout(me.timeout);
      me.timeout = setTimeout(function() {
        let store = me.store;
        store.getRootNode().removeAll();
        store.proxy.url = '{{ route('filemanager.folder.data') }}';
        store.proxy.extraParams = {
          selected: value,
          'only-current-folder': me.grid.down('#onlyCurrentFolder').checked ? 1 : 0
        };
        store.load();

        me.timeout = null;
      }, 200);
    };
  };
</script>

<style>
  .x-menu-item-icon.icon-yes {
    display: none;
  }

  .active-item .x-menu-item-icon {
    display: inline-block;
    background-image: url('{{ asset('images/icons/yes.png') }}');
    width: 16px;
    height: 16px;
    vertical-align: middle;
  }

  .custom-filter-button {
    border: 1px solid #d0d0d0;
    border-radius: 5px;
    background-color: transparent !important;
    color: #333;
  }

  .custom-bbar {
    padding: 15px 0 15px 8px;
  }
</style>
