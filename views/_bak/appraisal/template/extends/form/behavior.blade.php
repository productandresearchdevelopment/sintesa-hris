<script>
  var FormBehavior = function() {
    let me = Ext.utils.windowForms(this);
    me.data = null;

    var isReadOnly =
      {{ in_array(strtolower(optional($user->role)->name), ['hrga', 'developer', 'superadmin']) ? 'false' : 'true' }};

    me.store = Ext.create('Ext.data.Store', {
      fields: ['template_id', 'category_id', 'group_kpi', 'question', 'formula_description', 'weight',
        'remove_flag'
      ],
      data: []
    });

    me.groupKpiStore = Ext.create('Ext.data.Store', {
      autoload: true,
      fields: [{
        name: 'group_kpi',
        type: 'string'
      }],
      remoteSort: true,
      proxy: {
        type: 'ajax',
        url: '{{ route('appraisal.question.data') }}',
        extraParams: {
          category: 2
        },
        reader: {
          root: 'data',
          totalProperty: 'count'
        },
        simpleSortMode: true,
      },
      listeners: {
        load: function(store, records) {
          let uniqueData = [];
          let seenValues = new Set();
          records.forEach(record => {
            let value = record.get('group_kpi');
            if (!seenValues.has(value)) {
              seenValues.add(value);
              uniqueData.push(record);
            }
          });
          store.removeAll();
          store.add(uniqueData);
        }
      }
    });

    me.getGrid = function() {
      let actionColumn = {
        xtype: 'actioncolumn',
        text: '#',
        width: 100,
        hidden: isReadOnly,
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
      };

      return [{
        xtype: 'grid',
        store: me.store,
        cls: 'large-grid',
        border: true,
        margin: '10 0 0 0',
        columns: [{
            text: 'Group KPI',
            dataIndex: 'group_kpi',
            flex: 1,
            renderer: function(value, metaData, record, rowIndex, colIndex, store) {
              metaData.tdCls = 'wrap-cell';
              let isFirstOccurrence = rowIndex === 0 || store.getAt(rowIndex - 1).get('group_kpi') !==
                value;
              return isFirstOccurrence ? value : '';
            }
          },
          {
            text: 'Question',
            dataIndex: 'question',
            flex: 2,
            renderer: function(value, metaData) {
              metaData.tdCls = 'wrap-cell';
              return value;
            }
          },
          {
            text: 'Formula',
            dataIndex: 'formula_description',
            flex: 2,
            renderer: function(value, metaData) {
              metaData.tdCls = 'wrap-cell';
              return value;
            }
          },
          {
            text: 'Weight',
            dataIndex: 'weight',
            flex: 1
          },
          {
            text: 'Marked for Deletion',
            dataIndex: 'remove_flag',
            hidden: true
          },
          actionColumn
        ],
        tbar: isReadOnly ? undefined : [{
            xtype: 'tbfill'
          },
          {
            text: 'Add',
            iconCls: 'icon-plus',
            handler: me.openAddModal
          }
        ],
        viewConfig: {
          emptyText: '<div style="text-align:center; color: #888; padding: 20px;">No Data Available</div>',
          deferEmptyText: false,
          getRowClass: function(record, rowIndex, rowParams, store) {
            return '';
          }
        }
      }];
    };

    me.sortStore = function() {
      let sortedData = me.store.getRange().sort((a, b) => {
        let groupCompare = a.get('group_kpi').localeCompare(b.get('group_kpi'));
        if (groupCompare !== 0) {
          return groupCompare;
        }

        return new Date(a.get('created_at')) - new Date(b.get('created_at'));
      });
      me.store.removeAll();
      me.store.loadData(sortedData);
    };

    me.openAddModal = function() {
      me.showFormModal('Add Behavior & Work Processes', function(values) {
        me.store.add(values);
        me.sortStore();
      });
    };

    me.openEditModal = function(record) {
      me.showFormModal('Edit Behavior & Work Processes', function(values) {
        record.set(values);
        me.sortStore();
      }, record.data);
    };

    me.confirmDelete = function(record) {
      Ext.Msg.confirm('Confirm Delete', 'Are you sure you want to delete this record?', function(answer) {
        if (answer === 'yes') {
          record.set('remove_flag', true);
          me.store.remove(record);
          me.sortStore();
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
            name: 'group_kpi',
            fieldLabel: 'Group KPI',
            store: me.groupKpiStore,
            displayField: 'group_kpi',
            valueField: 'group_kpi',
            editable: true,
            queryMode: 'local',
            value: initialValues.group_kpi || '',
          },
          {
            xtype: 'textareafield',
            name: 'question',
            fieldLabel: 'Question',
            height: 80,
            value: initialValues.question || '',
          },
          {
            xtype: 'textareafield',
            name: 'formula_description',
            fieldLabel: 'Formula',
            height: 80,
            value: initialValues.formula_description || '',
          },
          {
            xtype: 'numberfield',
            name: 'weight',
            fieldLabel: 'Weight',
            value: initialValues.weight || '',
          }
        ],
        buttons: isReadOnly ? [{
          text: 'Close',
          cls: 'btn-blue',
          handler: function() {
            win.close();
          }
        }] : [{
          text: 'Save',
          formBind: true,
          handler: function() {
            let values = form.getValues();
            let existingRecord = me.groupKpiStore.findRecord('group_kpi', values.group_kpi, 0, false,
              true, true);
            if (!existingRecord) me.groupKpiStore.add({
              group_kpi: values.group_kpi
            });
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

<style>
  .wrap-cell .x-grid-cell-inner {
    white-space: normal !important;
    line-height: 1.4;
  }
</style>
