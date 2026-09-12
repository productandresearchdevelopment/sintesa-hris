<script>
  var FormsDetail = function() {
    let me = Ext.utils.windowForms(this);
    me.data = null;

    let parentStore = Ext.create('Ext.data.TreeStore', {
      folderSort: false,
      root: {
        id: '0',
        text: 'PT Qualita Indonesia',
        icon: '{{ asset('images/icons/home.png') }}',
        expanded: true
      },
      proxy: {
        type: 'ajax',
        url: '{{ route('organization.data') }}'
      }
    });

    let periodStore = Ext.create('Ext.data.Store', {
      fields: [
        'id', 'period', 'smester', 'appraisal_period_organizations',
        {
          name: 'customDisplay',
          convert: function(value, record) {
            return record.get('period') + ' (SMT ' + record.get('smester') + ')';
          }
        }
      ],
      proxy: {
        type: 'ajax',
        url: '{{ route('appraisal.period.data') }}',
        reader: {
          root: 'data',
          totalProperty: 'count'
        }
      },
      autoLoad: true
    });

    me.init = function() {
      me.form = Ext.widget('form', {
        bodyPadding: 15,
        width: '100%',
        height: '100%',
        border: false,
        layout: {
          type: 'hbox',
          align: 'stretch'
        },
        scrollable: true,
        fieldDefaults: {
          labelAlign: 'top',
        },
        items: [{
            xtype: 'container',
            layout: {
              type: 'vbox',
              align: 'stretch'
            },
            flex: 4,
            margin: '0 20 0 0',
            items: [{
              xtype: 'combobox',
              fieldLabel: 'Period',
              name: 'period_id',
              store: periodStore,
              queryMode: 'local',
              displayField: 'customDisplay',
              valueField: 'id',
              editable: false,
              forceSelection: true,
              allowBlank: false,
              width: '100%',
              listeners: {
                select: function(combo, records) {
                  let record = records[0];
                  if (!record) return;

                  let organizations = record.get('appraisal_period_organizations');

                  Ext.defer(function() {
                    let treeComboStore = me.getField('organization_id');
                    console.log(treeComboStore);

                    if (treeComboStore) {
                      if (organizations && organizations.organization_id) {
                        treeComboStore.proxy.extraParams.selected = organizations.organization_id;
                        store.proxy.extraParams.selected = record.get('id');
                        store.load();;
                      }
                    } else {
                      console.warn("TreeCombo tidak ditemukan dalam form.");
                    }
                  }, 500);
                }
              }

            }]
          },
          {
            xtype: 'container',
            layout: {
              type: 'vbox',
              align: 'stretch'
            },
            flex: 6,
            items: [{
              xtype: 'treecombo',
              name: 'organization_id',
              fieldLabel: 'Organization',
              rootVisible: true,
              canSelectFolders: true,
              editable: false,
              store: parentStore,
              width: '100%',
              listeners: {
                select: function(combo, record) {
                  let form = me.form.getForm();
                  let periodId = form.findField('period_id').getValue();

                  if (!periodId) {
                    Ext.Msg.alert('Warning', 'Please select a period first.');
                    return;
                  }

                  let organizationId = record.get('id');

                  Ext.Ajax.request({
                    url: '{{ route('appraisal.question.template.set.organization') }}',
                    method: 'POST',
                    params: {
                      period_id: periodId,
                      organization_id: organizationId
                    },
                    success: function(response) {
                      Ext.Msg.alert('Success', 'Organization set successfully.');
                    },
                    failure: function(response) {
                      Ext.Msg.alert('Error', 'Failed to set organization.');
                    }
                  });
                }
              }
            }]
          }
        ],
        buttons: [{
            text: 'Save',
            iconCls: 'icon-save-bright',
            handler: me.save
          },
          {
            text: 'Cancel',
            cls: 'btn-red',
            iconCls: 'icon-close',
            handler: function() {
              if (me.window) {
                me.window.hide();
              }
            }
          }
        ]
      });

      me.window = Ext.create('Ext.window.Window', {
        layout: 'fit',
        modal: true,
        maximized: true,
        closeAction: 'hide',
        items: [me.form]
      });
    };

    me.detail = function() {
      me.data = null;
      me.window.setTitle('Detail Template');
      var rec = grids.getRec(true);
      if (rec) {
        me.window.show();
        me.reset();
        me.form.getEl().mask('Loading');
        Ext.Ajax.request({
          method: 'GET',
          url: '{{ route('appraisal.question.template.data') }}/' + rec.id,
          success: function(response) {
            me.data = Ext.decode(response.responseText);
            me.render(me.data, 'edit');
            me.form.getEl().unmask();
          },
          failure: function() {
            Ext.Msg.alert('Error', 'Internal Server Error!');
            me.form.getEl().unmask();
            me.window.hide();
          }
        });
      } else {
        Ext.Msg.alert('Warning', 'Please select a record to edit.');
      }
    };

    me.render = function(rec, action) {
      me.form.url = '{{ route('appraisal.question.template.update', ':id') }}'.replace(':id', rec.id);

      me.form.getForm().setValues({
        _method: 'PUT',
        id: rec.id,
        title: rec.title,
        period_year: Number(rec.period_year),
        division_id: rec.division_id,
        period_smt: rec.period_smt,
        is_locked: rec.is_locked,
        is_archived: rec.is_archived,
        description: rec.description
      });
    };

    me.save = function() {
      if (me.form.getForm().isValid()) {
        var form = me.form.getForm();
        var values = form.getValues();

        Ext.Ajax.request({
          url: me.form.url,
          method: 'POST',
          params: values,
          success: function(response) {
            grids.storeLoad();
            Ext.Msg.alert('Success', 'Data has been saved!');
            me.window.hide();
          },
          failure: function(response) {
            let jsonResponse = Ext.decode(response.responseText);

            if (jsonResponse.errors) {
              let errorMessage = '';
              Ext.Object.each(jsonResponse.errors, function(field, errors) {
                errorMessage += errors.join(', ') + "\n";
              });

              Ext.Msg.alert('Error', errorMessage);
            } else {
              Ext.Msg.alert('Error', 'Failed to save data.');
            }
          }
        });
      } else {
        Ext.Msg.alert('Error', 'Please fill all required fields!');
      }
    };
  };
</script>
