<script>
  var FormFamily = function() {
    let me = Ext.utils.windowForms(this);
    me.data = null;

    me.relationData = Ext.create('Ext.data.Store', {
      fields: ['id', 'name'],
      proxy: {
        type: 'ajax',
        url: '{{ route('globaldata.data') }}',
        extraParams: {
          group: 'familly'
        },
        reader: {
          root: 'data',
          totalProperty: 'count'
        }
      },
      autoLoad: true
    });

    me.store = Ext.create('Ext.data.Store', {
      fields: ['employ_id', 'relation_id', 'nik', 'name', 'birth_date', 'occupation_id',
        'occupation_description', 'address', 'phone'
      ],
      data: []
    });

    me.loadImportedData = function(importedData) {
      me.store.add(importedData);
    };

    me.occupationData = Ext.create('Ext.data.Store', {
      fields: ['id', 'name'],
      proxy: {
        type: 'ajax',
        url: '{{ route('globaldata.data') }}',
        extraParams: {
          group: 'familly_occupation'
        },
        reader: {
          root: 'data',
          totalProperty: 'count'
        }
      },
      autoLoad: true
    });

    me.getGrid = function() {
      return [{
        xtype: 'grid',
        store: me.store,
        cls: 'large-grid',
        columns: [{
            text: 'NIK',
            dataIndex: 'nik',
            flex: 1,
            align: 'center'
          },
          {
            text: 'Name',
            dataIndex: 'name',
            flex: 1
          },
          {
            text: 'Birth Date',
            dataIndex: 'birth_date',
            flex: 1,
            renderer: function(value) {
              return value || '-';
            }
          },
          {
            text: 'Relation',
            dataIndex: 'relation_id',
            flex: 1,
            renderer: function(value) {
              let relation = me.relationData.findRecord('id', value);
              return relation ? relation.get('name') : value;
            }
          },
          {
            text: 'Occupation',
            dataIndex: 'occupation_id',
            flex: 1,
            renderer: function(value) {
              let occupation = me.occupationData.findRecord('id', value);
              return occupation ? occupation.get('name') : value;
            }
          },
          {
            text: 'Occupation Description',
            dataIndex: 'occupation_description',
            flex: 1
          },
          {
            text: 'Address',
            dataIndex: 'address',
            flex: 1
          },
          {
            text: 'Phone',
            dataIndex: 'phone',
            flex: 1
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
                  window.location = '{{ route('employee.export.excel.format.import.family') }}';
                }
              },
              {
                text: 'Upload File',
                iconCls: 'icon-excel',
                handler: function() {
                  forms.importFamily.open();
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
      me.showFormModal('Add New Family', function(values) {
        me.store.add(values);
      });
    };

    me.openEditModal = function(record) {
      me.showFormModal('Edit Family', function(values) {
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
                xtype: 'textfield',
                labelAlign: 'top',
                name: 'nik',
                fieldLabel: 'NIK',
                value: initialValues.nik || '',
                flex: 1,
                margin: '0 10 0 0'
              },
              {
                xtype: 'textfield',
                labelAlign: 'top',
                name: 'name',
                fieldLabel: 'Name',
                value: initialValues.name || '',
                flex: 1
              }
            ]
          },
          {
            xtype: 'container',
            layout: 'hbox',
            items: [{
              xtype: 'datefield',
              labelAlign: 'top',
              name: 'birth_date',
              fieldLabel: 'Birth Date',
              value: initialValues.birth_date || '',
              flex: 1,
              format: 'm/d/Y',
              submitFormat: 'm/d/Y'
            }]
          },
          {
            xtype: 'container',
            layout: 'hbox',
            items: [{
                xtype: 'combo',
                labelAlign: 'top',
                name: 'occupation_id',
                fieldLabel: 'Occupation',
                store: me.occupationData,
                displayField: 'name',
                valueField: 'id',
                value: initialValues.occupation_id || '',
                queryMode: 'local',
                typeAhead: true,
                flex: 1,
                margin: '0 10 0 0'
              },
              {
                xtype: 'combo',
                labelAlign: 'top',
                name: 'relation_id',
                fieldLabel: 'Relation',
                store: me.relationData,
                displayField: 'name',
                valueField: 'id',
                value: initialValues.relation_id || '',
                queryMode: 'local',
                typeAhead: true,
                flex: 1
              }
            ]
          },
          {
            xtype: 'textareafield',
            labelAlign: 'top',
            name: 'occupation_description',
            fieldLabel: 'Occupation Description',
            height: 80,
            value: initialValues.occupation_description || ''
          },
          {
            xtype: 'container',
            layout: 'hbox',
            items: [{
                xtype: 'textfield',
                labelAlign: 'top',
                name: 'address',
                fieldLabel: 'Address',
                value: initialValues.address || '',
                flex: 1,
                margin: '0 10 0 0'
              },
              {
                xtype: 'textfield',
                labelAlign: 'top',
                name: 'phone',
                fieldLabel: 'Phone',
                value: initialValues.phone || '',
                flex: 1
              }
            ]
          },
        ],
        buttons: [{
            text: 'Save',
            formBind: true,
            handler: function() {
              let values = form.getValues();
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
