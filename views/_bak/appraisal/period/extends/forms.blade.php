<script>
  var Forms = function() {
    let me = Ext.utils.windowForms(this);
    me.data = null;

    me.getYearStore = function() {
      let years = [],
        currentYear = new Date().getFullYear();
      for (let i = currentYear - 20; i <= currentYear + 20; i++) {
        years.push({
          year: i
        });
      }
      return Ext.create('Ext.data.Store', {
        fields: ['year'],
        data: years
      });
    };

    me.init = function() {
      me.form = Ext.widget('form', {
        bodyPadding: '10 15 10 15',
        autoHeight: true,
        border: false,
        fieldDefaults: {
          labelAlign: 'top',
          labelWidth: 80,
          msgTarget: 'side'
        },
        defaults: {
          anchor: '100%'
        },
        scrollable: true,
        items: [{
            xtype: 'hidden',
            name: 'id'
          },
          {
            xtype: 'hidden',
            name: 'appraisal_period_organization_id'
          },
          {
            xtype: 'hidden',
            name: '_token',
            value: '{{ csrf_token() }}'
          },
          {
            xtype: 'hidden',
            name: '_method',
            value: 'PUT'
          },
          {
            xtype: 'combo',
            fieldLabel: 'Period',
            afterLabelTextTpl: '<span style="color:red;">*</span>',
            name: 'period',
            store: me.getYearStore(),
            displayField: 'year',
            valueField: 'year',
            queryMode: 'local',
            forceSelection: true,
            editable: false,
            allowBlank: false,
          },
          {
            xtype: 'combo',
            fieldLabel: 'Semester',
            afterLabelTextTpl: '<span style="color:red;">*</span>',
            name: 'smester',
            store: [
              [1, '1'],
              [2, '2']
            ],
            queryMode: 'local',
            forceSelection: true,
            editable: false,
            allowBlank: false,
          },
          {
            xtype: 'datefield',
            fieldLabel: 'Start Date',
            afterLabelTextTpl: '<span style="color:red;">*</span>',
            name: 'start_date',
            format: 'Y-m-d',
            allowBlank: false,
          },
          {
            xtype: 'datefield',
            fieldLabel: 'End Date',
            afterLabelTextTpl: '<span style="color:red;">*</span>',
            name: 'end_date',
            format: 'Y-m-d',
            allowBlank: false,
          },
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
            handler: me.close
          }
        ]
      });

      me.createWindowForm('Form Period', me.form, {
        maximized: false,
        header: true,
        title: "Create Period",
        width: 400
      });
    };

    me.create = function() {
      me.show();
      me.reset();
      me.data = null;
      me.setTitle('Create Appraisal Period');

      me.form.getForm().findField('_method').setValue('POST');
      me.form.url = '{{ route('appraisal.period.create') }}';
    };

    me.edit = function() {
      me.data = null;
      me.setTitle('Edit Appraisal Period');
      var rec = grids.getRec(true);
      if (rec) {
        me.show();
        me.reset();
        me.form.getEl().mask('Loading');
        Ext.Ajax.request({
          method: 'GET',
          url: '{{ route('appraisal.period.data') }}/' + rec.id,
          success: function(response) {
            me.data = Ext.decode(response.responseText);
            me.render(me.data);
            me.form.getEl().unmask();
          },
          failure: function() {
            Ext.Msg.alert('Error', 'Internal Server Error!');
            me.form.getEl().unmask();
            me.close();
          }
        });
      } else {
        Ext.Msg.alert('Warning', 'Please select a record to edit.');
      }
    };

    me.render = function(rec) {
      me.form.url =
        '{{ route('appraisal.period.update') }}';

      me.form.getForm().setValues({
        _method: 'PUT',
        id: rec.id,
        period: Number(rec.period),
        smester: rec.smester,
        start_date: rec.start_date,
        end_date: rec.end_date,
      });
    };

    me.save = function() {
      if (me.form.getForm().isValid()) {
        var form = me.form.getForm();
        var values = form.getValues();

        var formData = new FormData();
        for (let key in values) {
          if (values.hasOwnProperty(key)) {
            formData.append(key, values[key]);
          }
        }

        Ext.Ajax.request({
          url: me.form.url,
          rawData: formData,
          headers: {
            'Content-Type': null
          },
          success: function(response) {
            grids.storeLoad();
            Ext.Msg.alert('Success', 'Data has been saved!');
            me.close();
          },
          failure: function(response) {
            let jsonResponse = JSON.parse(response.responseText);

            if (jsonResponse.errors) {
              let errorMessage = '';

              Object.keys(jsonResponse.errors).forEach(field => {
                errorMessage += jsonResponse.errors[field].join(', ') + "\n";
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
