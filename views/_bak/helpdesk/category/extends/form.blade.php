<script>
  var FormCategory = function() {
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
          {
            xtype: 'textfield',
            name: 'name',
            fieldLabel: 'Name',
            allowBlank: false,
          },
          {
            xtype: 'textfield',
            name: 'description',
            fieldLabel: 'Description',
            allowBlank: true
          }
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

      me.createWindowForm('Category Form', me.form, {
        width: 400,
        maximized: false
      });
    };

    me.create = function() {
      me.show();
      me.reset();
      me.form.url = '{{ route('helpdesk.category.store') }}';
      me.form.getForm().findField('_method').setValue('');
    }

    me.edit = function(record) {
      me.show();
      me.reset();
      me.form.url = '{{ route('helpdesk.category.update', ':id') }}'.replace(':id', record.id);
      me.form.getForm().findField('_method').setValue('PUT');

      me.setField('name', record.name);
      me.setField('description', record.description);
    }

    me.save = function() {
      me.submit(me.form.url, {
        success: function() {
          grids.storeLoad();
          me.close();
        },
        failure: function() {
          Ext.Msg.alert('Error', 'Internal Server Error!');
          me.close();
        }
      });
    }
  }
</script>
