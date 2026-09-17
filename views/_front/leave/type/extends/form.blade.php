<script>
  var Forms = function() {
    let me = Ext.utils.windowForms(this);

    me.data = null;

    me.init = function() {
      me.inputColor = new Ext.inputColor();

      @if ($isSuperUser)
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
      @endif

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
          allowBlank: true
        },
        items: [{
            xtype: 'hidden',
            name: '_token',
            value: '{{ csrf_token() }}'
          },
          {
            xtype: 'textfield',
            name: 'name',
            fieldLabel: 'Name',
            allowBlank: false,
            flex: 1
          },
          @if ($isSuperUser)
            {
              xtype: 'combo',
              fieldLabel: 'Company',
              name: 'company_id',
              store: me.storeCompany,
              queryMode: 'local',
              displayField: 'name',
              valueField: 'id',
              forceSelection: false,
              editable: false,
              allowBlank: true,
              emptyText: '-- Global / All Companies --',
              flex: 1,
            },
          @endif
          {
            xtype: 'fieldcontainer',
            defaultType: 'textfield',
            layout: {
              type: 'hbox',
              align: 'stretch'
            },
            items: [{
                name: 'alias',
                fieldLabel: 'Alias',
                allowBlank: true,
                flex: 1,
                margin: '0 10 0 0'
              },
              me.inputColor.field
            ]
          },
          {
            xtype: 'textareafield',
            name: 'description',
            fieldLabel: 'Description',
            allowBlank: true,
            height: 100
          },
          {
            xtype: 'checkboxfield',
            name: 'flag_reduce_balance',
            fieldLabel: 'Reduce Balance',
            boxLabel: 'This leave type reduces leave balance',
            uncheckedValue: '0',
            inputValue: '1',
            allowBlank: true
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

      me.createWindowForm('Leave Type', me.form, {
        maximized: false,
        header: true,
        title: "Leave Type"
      });
    };

    me.create = function() {
      me.show();
      me.reset();
      me.setField('flag_reduce_balance', false);
      @if ($isSuperUser)
        me.setField('company_id', null);
      @endif
      me.data = null;
      me.form.url = '{{ route('leave.type.push') }}';
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
          url: '{{ route('leave.type.get') }}/' + rec.id,
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
      me.form.url = '{{ route('leave.type.push') }}/' + rec.id;

      me.setField('name', rec.name);
      me.setField('alias', rec.alias);
      me.setField('description', rec.description);
      me.setField('color', rec.color);
      @if ($isSuperUser)
        me.setField('company_id', rec.company_id || null);
      @endif
      var isReduce = rec.flag_reduce_balance === true || rec.flag_reduce_balance === 1 || rec
        .flag_reduce_balance === '1' || rec.flag_reduce_balance === 'true';
      me.setField('flag_reduce_balance', isReduce);
    }

    me.save = function() {
      me.submit(me.form.url, {
        success: grids.storeLoad
      });
    }
  }
</script>
