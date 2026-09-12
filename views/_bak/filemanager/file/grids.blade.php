<script>
  var FileGrids = function() {
    let me = Ext.utils.grids(this);

    me.selected = null;
    me.trashStatus = 1;
    let trashParam;

    me.init = function() {
      me.store = Ext.create('Ext.data.Store', {
        pageSize: 50,
        autoload: true,
        fields: [{
            name: 'id',
            type: 'string'
          },
          {
            name: 'helpdesk_id',
            type: 'string'
          },
          {
            name: 'helpdesk_answer_id',
            type: 'int'
          },
          {
            name: 'iq_bulletin',
            type: 'int'
          },
          {
            name: 'folder_id',
            type: 'string'
          },
          {
            name: 'foldername',
            type: 'string',
            mapping: 'foldermanager.name'
          },
          {
            name: 'folder_name',
            type: 'string'
          },
          {
            name: 'folder_level',
            type: 'string',
            mapping: 'foldermanager.level'
          },
          {
            name: 'file',
            type: 'auto'
          },
          {
            name: 'organizations',
            type: 'auto',
          },
          {
            name: 'users',
            type: 'auto',
          },
          {
            name: 'name',
            type: 'string'
          },
          {
            name: 'category',
            type: 'string'
          },
          {
            name: 'path',
            type: 'string'
          },
          {
            name: 'type',
            type: 'string'
          },
          {
            name: 'mime',
            type: 'string'
          },
          {
            name: 'extension',
            type: 'string'
          },
          {
            name: 'size',
            type: 'float'
          },
          {
            name: 'link',
            type: 'string'
          },
          {
            name: 'tag',
            type: 'string'
          },
          {
            name: 'filename_origin',
            type: 'string'
          },
          {
            name: 'watermark',
            type: 'string'
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
          url: '{{ route('filemanager.data') }}',
          reader: {
            root: 'data',
            totalProperty: 'count'
          },
          simpleSortMode: true,
        },
        listeners: {
          beforeload: function(store) {
            let selectFolder = treeFolder.getRec(true);
            store.proxy.extraParams['filter-folder'] = selectFolder ? selectFolder.id : null;

            // Tambahkan filter berdasarkan type file
            let fileType = me.fileTypeFilter.text === 'File Type' ? 'all' : me.fileTypeFilter.text
              .toLowerCase();
            store.proxy.extraParams['file-type'] = fileType;
            me.updateMenuItems(me.trashStatus);
          },
          load: function(store, records, successful) {
            if (successful) {
              // Ambil nilai trash dari payload yang dikirim
              trashParam = store.getProxy().extraParams['trash'];

              me.grid.columns.forEach(col => {
                if (col.dataIndex === 'deleted_at' || col.dataIndex === 'deleted_by') {
                  col.setVisible(trashParam == 2); // Tampilkan jika trashParam = 2
                }
              });

              // Perbarui item menu berdasarkan nilai trash
              me.updateMenuItems(parseInt(trashParam, 10));
            }
          }
        }
      });

      me.menus = Ext.create('Ext.menu.Menu', {
        items: [
          @if ($user->hasRoute('filemanager.push.file') || $user->hasRoute('filemanager.push.link'))
            {
              text: 'New Folder',
              iconCls: 'icon-folder',
              handler: formFolder.create
            }, {
              text: 'New File',
              iconCls: 'icon-file',
              handler: formFile.create
            }, {
              text: 'New Link',
              iconCls: 'icon-earth',
              handler: formLink.create
            },
          @endif

          @if ($user->hasRoute('filemanager.set.tag'))
            {
              text: 'Set Tag',
              iconCls: 'icon-tag',
              handler: formTag.open
            },
          @endif
          @if ($user->hasRoute('filemanager.set.name'))
            {
              text: 'Set Name',
              iconCls: 'icon-copy',
              handler: formRename.edit
            },
          @endif
          '-',
          {
            text: 'Download',
            iconCls: 'icon-print',
            handler: me.download
          },

          '-',
          @if ($user->hasRoute(['filemanager.set.tag', 'filemanager.update']))
            {
              text: 'Move Folder',
              iconCls: 'icon-undo',
              handler: function() {
                var rec = gridFile.getRec(true);
                if (rec) {
                  formUpdate.edit(); // Berikan record sebagai parameter
                } else {
                  Ext.msg.warning('Please Select Data');
                }
              }
            },
          @endif

          @if ($user->hasRoute('filemanager.delete'))
            {
              text: 'Delete',
              iconCls: 'icon-remove',
              handler: function() {
                let recs = me.getValues();
                if (recs.length) {
                  Ext.ajaxConfirm('Remove File', {
                    mask: me.grid,
                    url: '{{ route('filemanager.delete') }}',
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

          @if ($user->hasRoute(['filemanager.restore', 'filemanager.forcedelete']))
            {
              text: 'Restore',
              iconCls: 'icon-refresh',
              handler: function() {
                let recs = me.getValues();
                if (recs.length) {
                  Ext.ajaxConfirm('Restore File', {
                    mask: me.grid,
                    url: '{{ route('filemanager.restore') }}',
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
                    url: '{{ route('filemanager.forcedelete') }}',
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

      // Fungsi untuk memperbarui item menu berdasarkan status trash
      me.updateMenuItems = function(trashValue) {
        me.menus.items.each(function(menuItem) {
          if (trashValue === 2) { // TRASH
            if (menuItem.text === 'Restore' || menuItem.text === 'Forever Remove') {
              menuItem.show();
            } else {
              menuItem.hide();
            }
          } else { // ACTIVE
            if (menuItem.text === 'Restore') {
              menuItem.hide();
            } else {
              menuItem.show();
            }
          }
        });
      }

      me.fileTypeFilter = Ext.create('Ext.button.Split', {
        text: 'File Type',
        iconCls: 'bi-filter',
        iconAlign: 'left',
        menu: {
          xtype: 'menu',
          items: [{
              text: 'All',
              value: 'all',
              iconCls: 'bi-filter'
            },
            {
              text: 'Document',
              value: 'document',
              iconCls: 'bi-file-earmark-break-fill'
            },
            {
              text: 'Image',
              value: 'image',
              iconCls: 'bi-image-fill'
            },
            {
              text: 'Archive',
              value: 'archive',
              iconCls: 'bi-file-earmark-zip-fill'
            },
            {
              text: 'Audio',
              value: 'audio',
              iconCls: 'bi-file-music-fill'
            },
            {
              text: 'Video',
              value: 'video',
              iconCls: 'bi-file-play-fill'
            },
            {
              text: 'Link',
              value: 'link',
              iconCls: 'bi-link-45deg'
            }
          ],
          listeners: {
            click: function(menu, item) {
              me.fileTypeFilter.setText(item.text);
              me.fileTypeFilter.setIconCls(item.iconCls);
              me.store.load({
                params: {
                  'file-type': item.value
                }
              });
            }
          }
        }
      });

      // Inisialisasi grid
      me.grid = Ext.create('Ext.grid.Panel', {
        region: 'center',
        store: me.store,
        selType: 'checkboxmodel',
        border: true,
        rowLines: false,
        columns: [{
            text: "File Name",
            dataIndex: 'name',
            width: 300,
            renderer: function(val, meta, rec) {
              let r = rec.data;
              let tpl = `<i class="bi {icon}" style="color: #{color}; margin-right: 10px; font-size: 18px;"></i>
                                       <span style="line-height: 40px">{name}</span>`;
              r.icon = 'bi-file-earmark-fill';
              r.color = '666666';

              // Ubah ikon dan warna berdasarkan ekstensi file
              if (r.extension == 'xls' || r.extension == 'xlsx') {
                r.icon = 'bi-file-earmark-excel-fill';
                r.color = '068800';
              } else if (r.extension == 'doc' || r.extension == 'docx') {
                r.icon = 'bi-file-earmark-word-fill';
                r.color = '145adc';
              } else if (r.extension == 'ppt' || r.extension == 'pptx') {
                r.icon = 'bi-file-earmark-ppt-fill';
                r.color = 'ff9000';
              } else if (r.extension == 'txt') {
                r.icon = 'bi-file-earmark-text-fill';
                r.color = '0654af';
              } else if (r.extension == 'pdf') {
                r.icon = 'bi-file-earmark-pdf-fill';
                r.color = 'e10a0a';
              } else if (r.extension == 'zip' || r.extension == 'rar') {
                r.icon = 'bi-file-earmark-zip-fill';
                r.color = '8100ce';
              } else if (r.type == 'image') {
                r.icon = 'bi-image-fill';
                r.color = '48CFCB';
              } else if (r.link) {
                r.icon = 'bi-file-earmark-code-fill';
                r.color = 'B99470';
              } else if (r.extension == 'mp3') {
                r.icon = 'bi-file-earmark-music-fill';
                r.color = 'ffa200';
              } else if (r.extension == 'mp4') {
                r.icon = 'bi-file-earmark-play-fill';
                r.color = 'A02334';
              }
              return String.format(tpl, r);
            }
          },
          {
            text: "Location",
            dataIndex: 'folder_name',
            width: 150,
            renderer: function(val, meta, rec) {
              let r = rec.raw;
              return r.folder_name ? r.folder_name : '-';
            }
          },
          {
            text: "Type File",
            dataIndex: 'extension',
            width: 120,
            renderer: function(val, meta, rec) {
              let r = rec.data;
              if (r.extension == 'xls' || r.extension == 'xlsx')
                return 'ExcelSpreadsheet';
              else if (r.extension == 'doc' || r.extension == 'docx')
                return 'WordDocument';
              else if (r.extension == 'ppt' || r.extension == 'pptx')
                return 'Presentation';
              else if (r.extension == 'txt') return 'Text';
              else if (r.extension == 'pdf') return 'PDF File';
              else if (r.extension == 'zip') return 'Zip File';
              else if (r.type == 'image') return 'Image';
              else if (r.type == 'audio') return 'Audio';
              else if (r.type == 'video') return 'Video';
              else if (r.link) return 'Link Browser';
              else return "Application/" + val.toUpperCase();
            }
          },
          {
            text: "Size",
            dataIndex: 'size',
            width: 120,
            align: 'right',
            sortable: true,
            renderer: function(val) {
              if (val) {
                let size = val ? val : 0;
                if (size < 1000) return size + ' B';
                else if (size < 1000000) return Ext.util.Format.number((size / 1000), '0,000.00') + ' KB';
                else if (size < 1000000000) return Ext.util.Format.number((size / 1000000), '0,000.00') +
                  ' MB';
              }
              return '<i class="bi bi-infinity"></i>';
            }
          },
          {
            text: "Created At",
            dataIndex: 'created_at',
            width: 150,
            align: 'center',
            renderer: function(val) {
              return Ext.Date.format(val, "d/m/Y H:i");
            }
          },
          {
            text: "Updated At",
            dataIndex: 'updated_at',
            width: 150,
            align: 'center',
            renderer: function(val) {
              return Ext.Date.format(val, "d/m/Y H:i");
            }
          },
          {
            text: "Created By",
            dataIndex: 'created_by',
            width: 150,
            align: 'center',
            renderer: function(val) {
              return val.name;
            }
          },
          {
            text: "Tag",
            dataIndex: 'tag',
            minWidth: 80,
            flex: 1,
            renderer: function(val) {
              if (val) return val;
              return '-';
            }
          },
          {
            text: "Deleted At",
            dataIndex: 'deleted_at',
            width: 150,
            align: 'center',
            renderer: function(val) {
              return Ext.Date.format(val, "d/m/Y H:i");
            }
          },
          {
            text: "Deleted By",
            dataIndex: 'deleted_by',
            width: 150,
            align: 'center',
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
                name: 'ACTIVE',
                listeners: {
                  click: function() {
                    me.trashStatus = 1;
                    me.store.load({
                      params: {
                        trash: me.trashStatus
                      }
                    });
                    me.updateMenuItems(me.trashStatus);
                  }
                }
              },
              {
                id: 2,
                name: 'TRASH',
                listeners: {
                  click: function() {
                    me.trashStatus = 2;
                    me.store.load({
                      params: {
                        trash: me.trashStatus
                      }
                    });
                    me.updateMenuItems(me.trashStatus);
                  }
                }
              }
            ]
          },
          me.fileTypeFilter
        ]),
        viewConfig: {
          stripeRows: false,
          getRowClass: function(rec) {
            if (rec.get('deleted_at')) return 'disabled';
          },
          listeners: {
            itemcontextmenu: function(obj, rec, node, index, e) {
              e.stopEvent();

              let hasDeletedAt = rec.get('deleted_at') !== null;
              let isElementLink = rec.get('link') !== null && rec.get('link') !== '';

              if (isElementLink) {
                me.menus.add({
                  id: 'editLinkMenu',
                  text: 'Edit Link',
                  iconCls: 'icon-edit',
                  handler: function() {
                    formLink.edit();
                  }
                });
              }

              me.updateMenuItemsVisibility(rec)

              // Show menu at cursor position
              me.menus.showAt(e.getXY());

              // Event listener untuk menghapus instance menu 'Edit Link' setelah menu ditutup
              me.menus.on('hide', function() {
                let existingLinkMenu = me.menus.down('#editLinkMenu');
                if (existingLinkMenu) {
                  me.menus.remove(existingLinkMenu);
                }
              }, me, {
                single: true
              });
            },
            itemclick: function(obj, rec) {
              me.updateMenuItemsVisibility(rec)
              if (!rec.get('deleted_at')) {
                me.selected = rec;
                var searchField = Ext.getCmp('search-user');
                if (searchField) {
                  searchField.setValue('');
                }
                details.set(rec.data)
              }
            },
            itemdblclick: function(obj, rec) {
              if (!rec.get('deleted_at')) {
                me.selected = rec;
                var searchField = Ext.getCmp('search-user');
                if (searchField) {
                  searchField.setValue('');
                }
                details.show();
              }
            }
          }
        }
      });
    }

    me.download = function() {
      let recs = me.getValues();

      if (recs.length === 1) {
        let rec = recs[0];
        let downloadUrl = '{{ route('filemanager.download', ':id') }}'.replace(':id', rec);
        window.location.href = downloadUrl;
      } else if (recs.length > 1) {
        Ext.Ajax.request({
          url: '{{ route('filemanager.downloadMultiple') }}',
          method: 'POST',
          params: {
            _token: '{{ csrf_token() }}',
            data: Ext.encode(recs)
          },
          success: function(response) {
            let result = Ext.decode(response.responseText);
            if (result.url) {
              window.location.href = result.url;
            } else {
              Ext.example.msg('Error', 'An error occurred while generating the download.');
            }
          },
          failure: function(response) {
            Ext.example.msg('Error', 'An error occurred while downloading the files.');
          }
        });
      } else {
        Ext.example.msg('Warning!', 'Please select a file to download.');
      }
    }


  }
</script>
