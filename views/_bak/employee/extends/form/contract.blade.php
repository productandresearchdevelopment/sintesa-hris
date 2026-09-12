<script>
  var FormContract = function() {
    let me = Ext.utils.windowForms(this);
    me.data = null;

    me.termData = Ext.create('Ext.data.Store', {
      fields: ['id', 'name'],
      proxy: {
        type: 'ajax',
        url: '{{ route('globaldata.data') }}',
        extraParams: {
          group: 'contract_status'
        },
        reader: {
          root: 'data',
          totalProperty: 'count'
        }
      },
      autoLoad: true
    });

    me.store = Ext.create('Ext.data.Store', {
      fields: ['employ_id', 'status_id', 'start_date', 'end_date', 'description', 'file_id', 'files',
        'remove_flag'
      ],
      data: []
    });

    me.loadImportedData = function(importedData) {
      me.store.add(importedData);
    };

    me.getGrid = function() {
      return [{
        xtype: 'grid',
        store: me.store,
        cls: 'large-grid',
        columns: [{
            text: 'Term',
            dataIndex: 'status_id',
            flex: 1,
            renderer: function(value) {
              let term = me.termData.findRecord('id', value);
              return term ? term.get('name') : value;
            }
          },
          {
            text: 'Start Date',
            dataIndex: 'start_date',
            flex: 1
          },
          {
            text: 'End Date',
            dataIndex: 'end_date',
            flex: 1
          },
          {
            text: 'Description',
            dataIndex: 'description',
            flex: 1
          },
          {
            text: 'File',
            dataIndex: 'file_id',
            flex: 1,
            renderer: function(value) {
              if (value) {
                let fileName = value.replace(/^.*[\\\/]/, '');
                let fileExtension = fileName.split('.').pop().toLowerCase();

                let iconClass = 'bi-file-earmark-fill';
                let color = '666666';

                switch (fileExtension) {
                  case 'xls':
                  case 'xlsx':
                    iconClass = 'bi-file-earmark-excel-fill';
                    color = '068800';
                    break;
                  case 'doc':
                  case 'docx':
                    iconClass = 'bi-file-earmark-word-fill';
                    color = '145adc';
                    break;
                  case 'ppt':
                  case 'pptx':
                    iconClass = 'bi-file-earmark-ppt-fill';
                    color = 'ff9000';
                    break;
                  case 'txt':
                    iconClass = 'bi-file-earmark-text-fill';
                    color = '0654af';
                    break;
                  case 'pdf':
                    iconClass = 'bi-file-earmark-pdf-fill';
                    color = 'e10a0a';
                    break;
                  case 'zip':
                  case 'rar':
                    iconClass = 'bi-file-earmark-zip-fill';
                    color = '8100ce';
                    break;
                  case 'jpg':
                  case 'png':
                  case 'jpeg':
                  case 'webp':
                  case 'gif':
                  case 'svg':
                  case 'bmp':
                    iconClass = 'bi-image-fill';
                    color = '48CFCB';
                    break;
                  case 'mp3':
                    iconClass = 'bi-file-earmark-music-fill';
                    color = 'ffa200';
                    break;
                  case 'mp4':
                    iconClass = 'bi-file-earmark-play-fill';
                    color = 'A02334';
                    break;
                  default:
                    iconClass = 'bi-file-earmark-fill';
                }

                return `<div style="display: flex; align-items: center; justify-content: center;">
                       <i class="bi ${iconClass}" style="color: #${color}; margin-right: 10px; font-size: 18px;" title="${fileName}"></i>
                       </div>`;
              }
              return '';
            }
          },
          {
            text: 'Files',
            dataIndex: 'files',
            hidden: true
          },
          {
            text: 'Marked for Deletion',
            dataIndex: 'remove_flag',
            hidden: true
          },
          {
            xtype: 'actioncolumn',
            text: '#',
            width: 100,
            items: [{
                iconCls: 'icon-edit',
                tooltip: 'Edit',
                margin: '0 5 0 0',
                handler: function(grid, rowIndex) {
                  let record = grid.getStore().getAt(rowIndex);
                  me.openEditModal(record);
                }
              },
              {
                iconCls: 'icon-trash',
                tooltip: 'Delete',
                handler: function(grid, rowIndex) {
                  let record = grid.getStore().getAt(rowIndex);
                  me.confirmDelete(record);
                }
              }
            ]
          }

        ],
        tbar: [{
            text: 'Add',
            handler: me.openAddModal
          },
          {
            text: 'Import Data',
            iconCls: 'icon-excel',
            menu: [{
                text: 'Download Format',
                iconCls: 'icon-cloud',
                handler: function() {
                  window.location = '{{ route('employee.export.excel.format.import.contract') }}';
                }
              },
              {
                text: 'Upload File',
                iconCls: 'icon-excel',
                handler: function() {
                  forms.importContract.open();
                }
              }
            ]
          }
        ],
        viewConfig: {
          emptyText: '<div style="text-align:center; color: #888; padding: 20px;">No Data Available</div>',
          deferEmptyText: false
        }
      }];
    };

    me.openAddModal = function() {
      me.showFormModal('Add New Contract', function(values) {
        me.store.add(values);
      });
    };

    me.openEditModal = function(record) {
      me.showFormModal('Edit Contract', function(values) {
        record.set(values);
      }, record.data);
    };

    me.confirmDelete = function(record) {
      Ext.Msg.confirm('Confirm Delete', 'Are you sure you want to delete this record?', function(answer) {
        if (answer === 'yes') {
          record.set('remove_flag', true);
          me.store.remove(record);
        }
      });
    };

    me.showFormModal = function(title, onSubmit, initialValues = {}) {
      let form = Ext.create('Ext.form.Panel', {
        bodyPadding: 20,
        width: 400,
        autoScroll: true,
        height: 'auto',
        maxHeight: 400,
        defaults: {
          labelAlign: 'top'
        },
        layout: {
          type: 'vbox',
          align: 'stretch'
        },
        items: [{
            xtype: 'combo',
            name: 'status_id',
            fieldLabel: 'Term',
            store: me.termData,
            displayField: 'name',
            valueField: 'id',
            value: initialValues.status_id || '',
            queryMode: 'local',
            typeAhead: true,
          },
          {
            xtype: 'container',
            layout: 'hbox',
            items: [{
              xtype: 'datefield',
              labelAlign: 'top',
              name: 'start_date',
              fieldLabel: 'Start Date',
              value: initialValues.start_date || '',
              flex: 1,
              margin: '0 10 0 0'
            }, {
              xtype: 'datefield',
              labelAlign: 'top',
              name: 'end_date',
              fieldLabel: 'End Date',
              value: initialValues.end_date || '',
              flex: 1
            }]
          },
          {
            xtype: 'textareafield',
            name: 'description',
            fieldLabel: 'Description',
            height: 80,
            value: initialValues.description || '',
          },
          initialValues.file_id ? {
            xtype: 'container',
            layout: 'hbox',
            id: 'current_file_container',
            items: [{
                xtype: 'displayfield',
                name: 'current_file_name',
                labelAlign: 'top',
                fieldLabel: 'File',
                labelStyle: 'line-height: 18px;',
                flex: 1,
                value: initialValues.file_id || 'Existing File'
              },
              {
                xtype: 'component',
                cls: 'change-file-icon',
                margin: '0 0 0 10',
                height: 40,
                width: 40,
                style: 'display: flex; align-items: center; justify-content: center;',
                html: '<i class="bi bi-pencil-square" style="cursor: pointer; font-size: 18px;" title="Change File"></i>',
                listeners: {
                  afterrender: function(comp) {
                    comp.getEl().on('click', function() {
                      let container = comp.up('#current_file_container');
                      form.remove(container);
                      const newFileField = form.add({
                        xtype: 'filefield',
                        name: 'file_id',
                        fieldLabel: 'File'
                      });

                      Ext.defer(function() {
                        const fileInputEl = newFileField.fileInputEl.dom;
                        if (fileInputEl) {
                          fileInputEl.click();
                        }
                      }, 100);
                    });
                  }
                }
              }
            ]
          } : {
            xtype: 'filefield',
            name: 'file_id',
            fieldLabel: 'File',
            value: initialValues.file_id || ''
          }
        ],
        buttons: [{
          text: 'Save',
          formBind: true,
          handler: function() {
            let values = form.getValues();
            let fileField = form.down('filefield[name=file_id]');
            if (fileField) {
              values.file_id = fileField.getValue();
              values.files = fileField.fileInputEl.dom.files[0];
            } else {
              values.file_id = initialValues.file_id;
            }
            onSubmit(values);
            win.close();
          }
        }, {
          text: 'Cancel',
          cls: 'btn-red',
          handler: function() {
            win.close();
          }
        }]
      });

      let win = Ext.create('Ext.window.Window', {
        title: title,
        modal: true,
        layout: 'fit',
        items: [form]
      });

      win.show();
    };

  };
</script>
