<script>
  var FormCareer = function() {
    let me = Ext.utils.windowForms(this);
    me.data = null;

    me.placementData = Ext.create('Ext.data.Store', {
      fields: ['id', 'name'],
      proxy: {
        type: 'ajax',
        url: '{{ route('placement.data') }}',
        reader: {
          root: 'data',
          totalProperty: 'count'
        }
      },
      autoLoad: true
    });

    me.careerData = Ext.create('Ext.data.Store', {
      fields: ['id', 'name'],
      proxy: {
        type: 'ajax',
        url: '{{ route('globaldata.data') }}',
        extraParams: {
          group: 'career'
        },
        reader: {
          root: 'data',
          totalProperty: 'count'
        }
      },
      autoLoad: true
    });

    me.organizationData = Ext.create('Ext.data.TreeStore', {
      folderSort: false,
      root: {
        id: 0,
        text: 'PT Qualita Indonesia',
        icon: '{{ asset('images/icons/home.png') }}',
        expanded: true
      },
      proxy: {
        type: 'ajax',
        url: '{{ route('organization.data') }}'
      },
    });

    me.store = Ext.create('Ext.data.Store', {
      fields: ['career_id', 'employ_id', 'placement_id', 'org_id', 'file_id', 'files', 'existing_file', 'date',
        'description'
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
        region: 'center',
        columns: [{
            text: 'Career',
            dataIndex: 'career_id',
            flex: 1,
            renderer: function(value) {
              let career = me.careerData.findRecord('id', value);
              return career ? career.get('name') : value;
            }
          },
          {
            text: 'Placement',
            dataIndex: 'placement_id',
            flex: 1,
            renderer: function(value) {
              let placement = me.placementData.findRecord('id', value);
              return placement ? placement.get('name') : value;
            }
          },
          {
            text: 'Organization',
            dataIndex: 'org_id',
            flex: 1,
            renderer: function(value) {
              const searchId = String(value);

              function findRecordInTree(node, id) {
                if (String(node.get('id') || node.data.id) === id) {
                  return node;
                }
                if (node.hasChildNodes()) {
                  for (let i = 0; i < node.childNodes.length; i++) {
                    let foundNode = findRecordInTree(node.childNodes[i], id);
                    if (foundNode) {
                      return foundNode;
                    }
                  }
                }
                return null;
              }

              let rootNode = me.organizationData.getRootNode();
              let organization = findRecordInTree(rootNode, searchId);
              return organization ? (organization.get('name') || organization.data.name || organization.raw
                .name) : value;
            }
          },
          {
            text: 'Date',
            dataIndex: 'date',
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
            text: 'Existing Files',
            dataIndex: 'existing_file',
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
                  window.location = '{{ route('employee.export.excel.format.import.career') }}';
                }
              },
              {
                text: 'Upload File',
                iconCls: 'icon-excel',
                handler: function() {
                  forms.importCareer.open();
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
      me.showFormModal('Add New Career', function(values) {
        me.store.add(values);
      });
    };

    me.openEditModal = function(record) {
      me.showFormModal('Edit Career', function(values) {
        record.set(values);
      }, record.data);
    };

    me.confirmDelete = function(record) {
      Ext.Msg.confirm('Confirm Delete', 'Are you sure you want to delete this record?', function(answer) {
        if (answer === 'yes') {
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
            xtype: 'container',
            layout: 'hbox',
            items: [{
                xtype: 'combo',
                labelAlign: 'top',
                name: 'career_id',
                fieldLabel: 'Career',
                store: me.careerData,
                displayField: 'name',
                valueField: 'id',
                value: initialValues.career_id || '',
                queryMode: 'local',
                typeAhead: true,
                flex: 1,
                margin: '0 10 0 0'
              },
              {
                xtype: 'combo',
                labelAlign: 'top',
                name: 'placement_id',
                fieldLabel: 'Placement',
                store: me.placementData,
                displayField: 'name',
                valueField: 'id',
                value: initialValues.placement_id || '',
                queryMode: 'local',
                typeAhead: true,
                flex: 1
              }
            ]
          },
          Ext.create('Ext.ux.form.field.TreeCombo', {
            name: 'org_id',
            fieldLabel: 'Organization',
            labelAlign: 'top',
            rootVisible: true,
            canSelectFolders: true,
            editable: false,
            value: initialValues.org_id || '',
            store: me.organizationData,
            listeners: {
              select: function(combo, record) {
                this.setValue(record.getId());
              }
            }
          }),
          {
            xtype: 'datefield',
            labelAlign: 'top',
            name: 'date',
            fieldLabel: 'Date',
            value: initialValues.date || ''
          },
          {
            xtype: 'textareafield',
            name: 'description',
            fieldLabel: 'Description',
            height: 80,
            value: initialValues.description || ''
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
          },
          {
            text: 'Cancel',
            cls: 'btn-red',
            handler: function() {
              win.close();
            }
          }
        ]
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
