<script>
  var Forms = function() {
    let me = Ext.utils.windowForms(this);

    me.data = null;

    me.orgStore = Ext.create('Ext.data.TreeStore', {
      fields: ['id', 'text', 'leaf', 'icon'],
      proxy: {
        type: 'ajax',
        url: '{{ route('organization.data') }}',
        reader: {
          type: 'json'
        },
      },
      root: {
        id: '0',
        text: 'PT Sintesa Talenta Asia',
        icon: '{{ asset('images/icons/home.png') }}',
        expanded: true
      }
    });

    me.orgPicker = Ext.create('Ext.ux.TreePicker', {
      afterLabelTextTpl: '<span style="color:red;">*</span>',
      name: 'organization_id',
      fieldLabel: 'Organization',
      labelAlign: 'top',
      store: me.orgStore,
      displayField: 'text',
      valueField: 'id',
      rootVisible: true,
      canSelectFolders: true,
      editable: false,
      allowBlank: false,
      flex: 1,
      minPickerHeight: 200,
      maxPickerHeight: 200,
    });

    me.companyData = Ext.create('Ext.data.Store', {
      fields: ['id', 'name'],
      proxy: {
        type: 'ajax',
        url: '{{ route('company.data') }}',
        reader: {
          root: 'data',
          totalProperty: 'count'
        }
      },
      autoLoad: true
    });

    me.init = function() {
      me.form = Ext.widget('form', {
        bodyPadding: 10,
        autoHeight: true,
        border: false,
        autoScroll: true,
        defaultType: 'textfield',
        layout: 'border',
        flex: 1,
        submitEmptyText: false,
        fieldDefaults: {
          labelAlign: 'top',
          allowBlank: false,
          margin: '2 10'
        },
        items: [{
          xtype: 'panel',
          region: 'center',
          layout: {
            type: 'vbox',
            align: 'stretch'
          },
          border: false,
          autoScroll: true,
          bodyPadding: '0 20 0 0',
          margin: '0 0 10 0',
          items: [{
              xtype: 'hidden',
              name: '_token',
              value: '{{ csrf_token() }}'
            },
            {
              xtype: 'combo',
              afterLabelTextTpl: '<span style="color:red;">*</span>',
              name: 'role_id',
              fieldLabel: 'Role',
              forceSelection: true,
              editable: false,
              queryMode: 'local',
              triggerAction: 'all',
              displayField: 'name',
              valueField: 'id',
              store: Ext.create('Ext.data.Store', {
                fields: [{
                    name: 'id',
                    type: 'int'
                  },
                  {
                    name: 'name',
                    type: 'string'
                  },
                  {
                    name: 'property',
                    type: 'auto'
                  },
                ],
                data: roles
              }),
              listeners: {
                change: function(obj, val) {
                  me.onSelectRole(val);
                },
              }
            },
            {
              xtype: 'combo',
              labelAlign: 'top',
              afterLabelTextTpl: '<span style="color:red;">*</span>',
              name: 'company_id',
              fieldLabel: 'Company',
              store: me.companyData,
              displayField: 'name',
              valueField: 'id',
              queryMode: 'local',
              allowBlank: false,
              listeners: {
                select: function(combo, record) {
                  const selectedRecord = Array.isArray(record) ? record[0] : record;
                  if (selectedRecord) {
                    const companyId = selectedRecord.get('id');
                    me.orgStore.proxy.extraParams.company_id = companyId;
                    me.orgStore.load();
                  }
                }
              }
            },
            {
              xtype: 'container',
              layout: {
                type: 'vbox',
                align: 'stretch'
              },
              defaults: {
                labelAlign: 'top',
                margin: '5 10 0 10',
                flex: 1
              },
              items: [
                me.orgPicker,
                {
                  xtype: 'combo',
                  afterLabelTextTpl: '<span style="color:red;">*</span>',
                  name: 'employ_id',
                  fieldLabel: 'Employee',
                  forceSelection: true,
                  editable: true,
                  queryMode: 'remote',
                  triggerAction: 'all',
                  displayField: 'fullname',
                  valueField: 'id',
                  store: Ext.create('Ext.data.Store', {
                    autoLoad: true,
                    fields: [{
                        name: 'id',
                        type: 'auto'
                      },
                      {
                        name: 'nik',
                        type: 'string'
                      },
                      {
                        name: 'fullname',
                        type: 'string'
                      },
                    ],
                    proxy: {
                      type: 'ajax',
                      url: '{{ route('employee.data') }}',
                      extraParams: {
                        limit: 500
                      },
                      reader: {
                        root: 'data',
                        totalProperty: 'count'
                      }
                    },
                  }),
                  displayTpl: new Ext.XTemplate(
                    '<tpl for=".">{fullname}</tpl>'
                  ),
                  listConfig: {
                    getInnerTpl: function() {
                      return '( <b>{nik}</b> ) - {fullname}';
                    }
                  }
                }
              ]
            },
            {
              xtype: 'fieldcontainer',
              autoHeight: true,
              margin: '0 10 0 0',
              layout: {
                type: 'hbox',
                align: 'stretch'
              },
              items: [{
                  xtype: 'textfield',
                  afterLabelTextTpl: '<span style="color:red;">*</span>',
                  name: 'username',
                  fieldLabel: 'User Name',
                  flex: 1
                },
                {
                  xtype: 'textfield',
                  name: 'password',
                  fieldLabel: 'Password',
                  flex: 1
                },
              ]
            },
            {
              xtype: 'fieldcontainer',
              autoHeight: true,
              margin: '0 10 0 0',
              layout: {
                type: 'hbox',
                align: 'stretch'
              },
              items: [{
                  xtype: 'textfield',
                  afterLabelTextTpl: '<span style="color:red;">*</span>',
                  name: 'name',
                  fieldLabel: 'Name',
                  flex: 1
                },
                {
                  xtype: 'textfield',
                  afterLabelTextTpl: '<span style="color:red;">*</span>',
                  name: 'email',
                  fieldLabel: 'Email',
                  vtype: 'email',
                  allowBlank: true,
                  flex: 1
                },
              ]
            },
            {
              xtype: 'fieldcontainer',
              autoHeight: true,
              margin: '0 10 0 0',
              layout: {
                type: 'hbox',
                align: 'stretch'
              },
              items: [{
                  xtype: 'textfield',
                  name: 'phone',
                  fieldLabel: 'Phone',
                  allowBlank: true,
                  flex: 1
                },
                {
                  xtype: 'textfield',
                  name: 'address',
                  fieldLabel: 'Address',
                  allowBlank: true,
                  flex: 1
                },
              ]
            },
            {
              xtype: 'textareafield',
              name: 'description',
              fieldLabel: 'Description',
              allowBlank: true,
              flex: 1,
              minHeight: 100,
              margin: 10,
            }
          ]
        }],
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

      me.createWindowForm('User Form', me.form, {
        maximized: true,
        header: false,
        title: "User"
      });
    };

    me.onSelectRole = function(id) {}
    me.onSelectOrganization = function(id) {}

    me.create = function() {
      me.show();
      me.reset();
      me.data = null;
      me.form.url = '{{ route('auth.user.push') }}';

      me.setField('role_id', grids.extraParams.role);

      setTimeout(function() {
        me.getField('password').allowBlank = false;
        me.setField('username', '');
        me.setField('password', '');
      }, 200);

      var orgField = me.getField('organization_id');
      orgField.store.load({
        callback: function() {
          orgField.setValue('0');
        }
      });

      me.onSelectRole();
      me.onSelectOrganization();
    };

    me.edit = function() {
      me.data = null;
      var rec = grids.getRec(true);
      if (rec) {
        me.show();
        me.reset();
        me.mask('Loading');

        http.request({
          method: 'get',
          url: '{{ route('auth.user.get') }}/' + rec.id,
          success: function(response) {
            me.data = Ext.decode(response.responseText);
            me.form.url = '{{ route('auth.user.push') }}/' + rec.id;

            me.getField('password').allowBlank = true;
            me.setField('password', '');
            me.setField('role_id', me.data.role_id);
            me.setField('username', me.data.username);
            me.setField('name', me.data.name);
            me.setField('phone', me.data.phone);
            me.setField('email', me.data.email);
            me.setField('description', me.data.description);
            me.setField('company_id', me.data?.organization?.company_id || '');
            me.setField('employ_id', me.data.employ_id);

            var orgId = me.data?.organization_id ? String(me.data.organization_id) : null;
            var orgField = me.getField('organization_id');
            var orgStore = orgField.store;

            if (!orgId) {
              me.unmask();
              return;
            }

            orgStore.proxy.extraParams.company_id = me.data?.organization?.company_id;
            orgStore.load({
              callback: function() {
                Ext.Ajax.request({
                  url: '{{ route('organization.path', ['id' => ':id']) }}'.replace(':id', orgId),
                  method: 'GET',
                  success: function(resp) {
                    var ids = Ext.decode(resp.responseText) || [];
                    ids = ids.map(String);
                    var treePath = '/' + ids.join('/');

                    orgField.getPicker().expandPath(treePath, 'id', '/', function(success,
                      lastNode) {
                      if (success && lastNode) {
                        orgField.setValue(String(lastNode.getId()));
                      } else {
                        orgField.setValue(orgId);
                      }
                      me.unmask();
                    });
                  },
                  failure: function() {
                    orgField.setValue(orgId);
                    me.unmask();
                  }
                });
              }
            });
          },
          failure: function() {
            Ext.msg.failed('Internal Server Error!');
            me.form.getEl().unmask();
            me.close();
          }
        });
      } else Ext.example.msg('Warning!', 'Please select data!');
    };

    me.save = function() {
      me.submit(me.form.url, {
        success: grids.storeLoad
      });
    };
  }
</script>
