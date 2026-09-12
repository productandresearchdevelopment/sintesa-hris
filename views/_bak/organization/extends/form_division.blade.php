<script>
  var FormDivision = function() {
    let me = Ext.utils.windowForms(this);

    me.timeout = null;

    me.init = function() {
      me.form = Ext.widget('form', {
        bodyPadding: 10,
        autoHeight: true,
        border: false,
        layout: {
          type: 'vbox',
          align: 'stretch'
        },
        fieldDefaults: {
          labelAlign: 'top',
          allowBlank: false
        },
        items: [{
            xtype: 'hidden',
            name: '_token',
            value: '{{ csrf_token() }}'
          },
          {
            xtype: 'hidden',
            name: '_method',
            value: 'PUT'
          },
          Ext.create('Ext.form.ComboBox', {
            name: 'division_id',
            fieldLabel: 'Division',
            editable: false,
            store: Ext.create('Ext.data.Store', {
              fields: ['id', 'name'],
              proxy: {
                type: 'ajax',
                url: '{{ route('division.data') }}',
                reader: {
                  root: 'data',
                  totalProperty: 'count'
                }
              },
              autoLoad: true,
            }),
            queryMode: 'local',
            displayField: 'name',
            valueField: 'id',
          }),
        ],
        buttons: [{
            text: 'Save',
            cls: 'btn-green',
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
      me.createWindowForm('Set Division', me.form, {
        width: 400,
        maximized: false
      });
    };

    me.open = function() {
      var rec = grids.getRec(true);
      if (rec) {
        me.show();
        me.reset();

        me.form.url = '{{ route('organization.set.division', ['organizationId' => ':organizationId']) }}'.replace(
          ':organizationId', rec.id);

        me.setField('division_id', rec.division_id || null);
      } else {
        Ext.example.msg('Warning!', 'Please Select Data');
      }
    }

    me.save = function() {
      me.submit(me.form.url, {
        success: function() {
          grids.storeLoad();
          me.close();
        }
      });
    }
  }
</script>
