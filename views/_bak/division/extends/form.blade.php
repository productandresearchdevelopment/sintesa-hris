<script>
  var Forms = function() {
    let me = Ext.utils.windowForms(this);

    me.data = null;

    me.init = function() {
      me.inputColor = new Ext.inputColor();

      me.storeCompany = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        proxy: {
          type: 'ajax',
          url: '{{ route('company.data') }}',
          reader: {
            root: 'data',
            totalProperty: 'count'
          }
        },
        autoLoad: true,
      });

      me.form = Ext.widget('form', {
        bodyPadding: 15,
        width: 500,
        border: false,
        defaultType: 'textfield',
        layout: {
          type: 'vbox',
          align: 'stretch'
        },
        submitEmptyText: false,
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
            name: 'id'
          },
          {
            xtype: 'hidden',
            name: '_method'
          },
          {
            xtype: 'textfield',
            name: 'name',
            fieldLabel: 'Name',
            flex: 1
          },
          {
            xtype: 'combo',
            fieldLabel: 'Company',
            name: 'company_id',
            store: me.storeCompany,
            queryMode: 'local',
            displayField: 'name',
            valueField: 'id',
            forceSelection: true,
            editable: false,
            allowBlank: false,
            flex: 1,
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
            handler: me.close
          }
        ]
      });

      me.createWindowForm('Form Division', me.form, {
        maximized: false,
        header: true,
        title: "owner"
      });
    };

    me.create = function() {
      me.show();
      me.reset();
      me.data = null;
      me.form.url = '{{ route('division.create') }}';
      me.setField('_method', 'POST');
    }

    me.edit = function() {
      me.data = null;
      var rec = grids.getRec(true);
      if (rec) {
        me.show();
        me.reset();
        me.form.getEl().mask('Loading');
        http.request({
          method: 'get',
          url: '{{ route('division.get') }}/' + rec.id,
          success: function(response) {
            me.data = Ext.decode(response.responseText);
            me.editRender(me.data);
            me.form.getEl().unmask();
          },
          failure: function() {
            Ext.msg.failed('Internal Server Error!');
            me.form.getEl().unmask();
            me.close();
          }
        });
      } else Ext.example.msg('Warning!', 'Please select data!');
    }

    me.editRender = function(rec) {
      me.form.url = '{{ route('division.update') }}';
      me.setField('_method', 'PUT');
      me.setField('id', rec.id);
      me.setField('name', rec.name);
      me.setField('company_id', rec.company_id);
    }

    // Save the form
    me.save = function() {
      me.submit(me.form.url, {
        success: grids.storeLoad
      });
    }
  }
</script>
