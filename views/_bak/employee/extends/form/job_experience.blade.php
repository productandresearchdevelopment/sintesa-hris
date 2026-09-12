<script>
  var FormJobExperience = function() {
    let me = Ext.utils.windowForms(this);
    me.data = null;

    me.store = Ext.create('Ext.data.Store', {
      fields: ['employ_id', 'name', 'start_date', 'end_date', 'job_title', 'job_description', 'salary',
        'reason_leaving',
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
        columns: [{
            text: 'Name',
            dataIndex: 'name',
            flex: 1
          },
          {
            text: 'Start Year',
            dataIndex: 'start_date',
            flex: 1,
            renderer: function(val) {
              if (!val) return '';
              return new Date(val).getFullYear();
            }
          },
          {
            text: 'End Year',
            dataIndex: 'end_date',
            flex: 1,
            renderer: function(val) {
              if (!val) return '';
              return new Date(val).getFullYear();
            }
          },
          {
            text: 'Job Title',
            dataIndex: 'job_title',
            flex: 1
          },
          {
            text: 'Job Description',
            dataIndex: 'job_description',
            flex: 1
          },
          {
            text: 'Salary',
            dataIndex: 'salary',
            flex: 1
          },
          {
            text: 'Reason Leaving',
            dataIndex: 'reason_leaving',
            flex: 1
          },
          {
            text: 'Description',
            dataIndex: 'description',
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
                  window.location = '{{ route('employee.export.excel.format.import.job-experience') }}';
                }
              },
              {
                text: 'Upload File',
                iconCls: 'icon-excel',
                handler: function() {
                  forms.importJobExperience.open();
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
      me.showFormModal('Add New Job Experience', function(values) {
        me.store.add(values);
      });
    };

    me.openEditModal = function(record) {
      me.showFormModal('Edit Job Experience', function(values) {
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
            xtype: 'textfield',
            name: 'name',
            fieldLabel: 'Name',
            value: initialValues.name || ''
          },
          {
            xtype: 'numberfield',
            name: 'start_date',
            fieldLabel: 'Start Year',
            value: initialValues.start_date ?
              new Date(initialValues.start_date).getFullYear() : '',
            minValue: 1900,
            maxValue: new Date().getFullYear(),
            allowDecimals: false,
            allowExponential: false,
            hideTrigger: true,
            allowBlank: false
          },
          {
            xtype: 'numberfield',
            name: 'end_date',
            fieldLabel: 'End Year',
            value: initialValues.end_date ?
              new Date(initialValues.end_date).getFullYear() : '',
            minValue: 1900,
            maxValue: new Date().getFullYear(),
            allowDecimals: false,
            allowExponential: false,
            hideTrigger: true,
            allowBlank: true
          },
          {
            xtype: 'textfield',
            name: 'job_title',
            fieldLabel: 'Job Title',
            value: initialValues.job_title || ''
          },
          {
            xtype: 'textareafield',
            name: 'job_description',
            fieldLabel: 'Job Description',
            height: 80,
            value: initialValues.job_description || '',
          },
          {
            xtype: 'numberfield',
            name: 'salary',
            fieldLabel: 'Salary',
            value: initialValues.salary || ''
          },
          {
            xtype: 'textareafield',
            name: 'reason_leaving',
            fieldLabel: 'Reason Leaving',
            value: initialValues.reason_leaving || ''
          },
          {
            xtype: 'textareafield',
            name: 'description',
            fieldLabel: 'Description',
            height: 80,
            value: initialValues.description || ''
          },
        ],
        buttons: [{
            text: 'Save',
            formBind: true,
            handler: function() {
              let values = form.getValues();
              if (values.start_date && values.end_date) {
                if (parseInt(values.end_date) < parseInt(values.start_date)) {
                  Ext.Msg.alert('Error', 'End Year must be greater than Start Year');
                  return;
                }
              }
              if (values.start_date) {
                values.start_date = values.start_date + '-01-01';
              }
              if (values.end_date) {
                values.end_date = values.end_date + '-01-01';
              }

              onSubmit(values);
              win.close();
            },
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
