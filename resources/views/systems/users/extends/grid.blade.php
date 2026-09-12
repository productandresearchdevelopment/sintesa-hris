<script>
  var Grids = function() {
    let me = Ext.utils.grids(this);
    me.init = function() {
      me.store = me.httpStore('{{ route('auth.user.data') }}', [{
          name: 'id',
          type: 'string'
        },
        {
          name: 'role_id',
          type: 'int'
        },
        {
          name: 'role',
          type: 'auto'
        },
        {
          name: 'organization_id',
          type: 'auto'
        },
        {
          name: 'organization',
          type: 'auto'
        },
        {
          name: 'employ_id',
          type: 'auto'
        },
        {
          name: 'employee',
          type: 'auto'
        },
        {
          name: 'username',
          type: 'string'
        },
        {
          name: 'email',
          type: 'string'
        },
        {
          name: 'name',
          type: 'string'
        },
        {
          name: 'phone',
          type: 'string'
        },
        {
          name: 'address',
          type: 'string'
        },
        {
          name: 'photo_id',
          type: 'string'
        },
        {
          name: 'receive_notif',
          type: 'int'
        },
        {
          name: 'description',
          type: 'string'
        },
        {
          name: 'last_ip',
          type: 'string'
        },
        {
          name: 'last_module',
          type: 'int'
        },
        {
          name: 'last_url',
          type: 'string'
        },
        {
          name: 'last_active',
          type: 'date'
        },
        {
          name: 'email_validation_code',
          type: 'string'
        },
        {
          name: 'email_validation_sent_at',
          type: 'date'
        },
        {
          name: 'email_validation_at',
          type: 'date'
        },
        {
          name: 'property',
          type: 'auto'
        },
        {
          name: 'created_by',
          type: 'string'
        },
        {
          name: 'updated_by',
          type: 'string'
        },
        {
          name: 'deleted_by',
          type: 'string'
        },
        {
          name: 'created_at',
          type: 'date'
        },
        {
          name: 'updated_at',
          type: 'date'
        },
        {
          name: 'deleted_at',
          type: 'date'
        },
      ]);

      me.menus = Ext.create('Ext.menu.Menu', {
        items: [
          @if ($user->hasRoute('auth.user.push'))
            {
              text: 'Create',
              iconCls: 'icon-add',
              handler: forms.create
            }, {
              text: 'Edit',
              iconCls: 'icon-edit',
              handler: forms.edit
            },
          @endif

          @if ($user->hasRoute('auth.user.send.email.validation'))
            {
              text: 'Resend Validation Email',
              iconCls: 'icon-email',
              handler: function() {
                let recs = me.getValues();
                if (recs.length) {
                  Ext.ajaxConfirm('Resend Validation Email', {
                    mask: me.grid,
                    url: '{{ route('auth.user.send.email.validation') }}',
                    params: {
                      '_token': '{{ csrf_token() }}',
                      data: Ext.encode(recs)
                    },
                    success: me.storeLoad
                  });
                } else Ext.msg.warning('Please select data!');
              }
            },
          @endif

          @if ($user->hasRoute('auth.user.set.password'))
            {
              text: 'Reset Password',
              iconCls: 'icon-key',
              handler: function() {
                let recs = me.getValues();
                if (recs.length) {
                  Ext.MessageBox.prompt('Update Password', 'Enter Password', function(res, text) {
                    if (res == 'ok') {
                      me.grid.getEl().mask('Sending');
                      http.request({
                        method: 'post',
                        url: '{{ route('auth.user.set.password') }}',
                        params: {
                          '_method': 'PUT',
                          '_token': '{{ csrf_token() }}',
                          'data': Ext.encode(recs),
                          'password': text
                        },
                        success: function(r) {
                          Ext.msg.success('Update password!');
                          me.storeLoad();
                          me.grid.getEl().unmask();
                        },
                        failure: function(obj, res) {
                          Ext.msg.failed('Internal Server Error!');
                          me.grid.getEl().unmask();
                        }
                      });
                    }
                  });
                } else Ext.msg.warning('Please select data!');
              }
            },
          @endif

          @if (hasRoute(['auth.user.import.format', 'auth.user.import.data']))
            {
              text: 'Import From Excel',
              iconCls: 'icon-excel',
              menu: {
                items: [
                  @if (hasRoute('auth.user.import.format'))
                    {
                      text: 'Download Format',
                      iconCls: 'icon-cloud',
                      handler: function() {
                        window.location = ' {{ route('auth.user.import.format') }} ';
                      }
                    },
                  @endif

                  @if (hasRoute('auth.user.import.data'))
                    {
                      text: 'Upload File',
                      iconCls: 'icon-excel',
                      handler: function() {
                        formsImport.open();
                      }
                    },
                  @endif
                ]
              }
            },
          @endif

          @if ($user->hasRoute('auth.user.delete'))
            {
              text: 'Delete',
              iconCls: 'icon-remove',
              handler: function() {
                let recs = me.getValues();
                if (recs.length) {
                  Ext.ajaxConfirm('Remove User', {
                    mask: me.grid,
                    url: '{{ route('auth.user.delete') }}',
                    params: {
                      '_method': 'DELETE',
                      '_token': '{{ csrf_token() }}',
                      data: Ext.encode(recs)
                    },
                    success: me.storeLoad
                  });
                } else Ext.msg.warning('Please select data!');
              }
            },
          @endif

          @if (hasRoute(['auth.user.restore', 'auth.users.forcedelete']))
            {
              text: 'Trashed',
              iconCls: 'icon-trash',
              menu: {
                items: [
                  @if ($user->hasRoute('auth.user.restore'))
                    {
                      text: 'Restore',
                      iconCls: 'icon-refresh',
                      handler: function() {
                        let recs = me.getValues();
                        if (recs.length) {
                          Ext.ajaxConfirm('Restore User', {
                            mask: me.grid,
                            url: '{{ route('auth.user.restore') }}',
                            params: {
                              '_method': 'PUT',
                              '_token': '{{ csrf_token() }}',
                              data: Ext.encode(recs)
                            },
                            success: me.storeLoad
                          });
                        } else Ext.msg.warning('Please select data!');
                      }
                    },
                  @endif

                  @if ($user->hasRoute('auth.user.forcedelete'))
                    {
                      text: 'Forever Remove',
                      iconCls: 'icon-remove',
                      handler: function() {
                        let recs = me.getValues();
                        if (recs.length) {
                          Ext.ajaxConfirm('Forever Remove User', {
                            mask: me.grid,
                            url: '{{ route('auth.user.forcedelete') }}',
                            params: {
                              '_method': 'DELETE',
                              '_token': '{{ csrf_token() }}',
                              data: Ext.encode(recs)
                            },
                            success: me.storeLoad
                          });
                        } else Ext.msg.warning('Please select data!');
                      }
                    }
                  @endif
                ]
              }
            },
          @endif
        ]
      });

      me.grid = Ext.create('Ext.grid.Panel', {
        region: 'center',
        store: me.store,
        selType: 'checkboxmodel',
        border: true,
        cls: 'large-grid',
        tbar: me.tbar(me.menus),
        columns: [{
            text: "#",
            dataIndex: 'photo_id',
            width: 70,
            align: 'center',
            renderer: function(val) {
              let imageUrl = val ? '{{ route('file') }}/' + val : '{{ asset('images/nouser.png') }}';
              return `<img src="${imageUrl}" style="width: 40px; height: 40px; border-radius: 50%;" alt="User Photo" />`;
            }
          },
          {
            text: "ROLE",
            dataIndex: 'role_id',
            width: 80,
            align: 'center',
            renderer: function(val, meta, rec) {
              let role = find(roles, val);
              return role ? me.renderBox(role.alias, role.color, role.name, meta) : '-';
            }
          },
          {
            text: "UID",
            dataIndex: 'id',
            width: 80,
            hidden: true
          },
          {
            text: "USER NAME",
            dataIndex: 'username',
            width: 200
          },
          {
            text: "NAME",
            dataIndex: 'name',
            minWidth: 200,
            flex: 1
          },
          {
            text: "EMAIL",
            dataIndex: 'email',
            width: 200
          },
          {
            text: '<i class="bi bi-envelope" style="font-size: 16px"></i>',
            dataIndex: 'email_validation_at',
            width: 70,
            align: 'center',
            renderer: function(val, meta, rec) {
              if (val) {
                return '<i class="bi bi-envelope-check" style="color: #145adc; font-size: 16px"></i>';
              } else if (rec.get('email')) return '<i class="bi bi-envelope" style="font-size: 16px"></i>';
              return '';
            }
          },
          {
            text: "LAST ACTIVE",
            dataIndex: 'last_active',
            width: 170,
            align: 'center',
            renderer: function(val) {
              return val ? Ext.Date.format(val, "Y-m-d h:i:s") : '';
            }
          },
          {
            text: "#",
            dataIndex: 'last_active',
            width: 35,
            renderer: function(val) {
              if (val) {
                let diff = dates.diffServer(val);
                if ((diff.distance * 1) < (1000 * 60 * 5))
                  return "<img src='{{ asset('images/icons/online.png') }}'>";
                else return "<img src='{{ asset('images/icons/offline.png') }}'>";
              }
              return "<img src='{{ asset('images/icons/offline.png') }}'>";
            }
          },
        ],
        bbar: me.bottomBar([{
            xtype: 'filter',
            id: 'trash',
            name: 'Trash',
            param: 'trash',
            iconCls: 'icon-trash',
            items: [{
                id: 1,
                name: 'ACTIVE',
                checked: true
              },
              {
                id: 2,
                name: 'TRASH'
              }
            ]
          },
          {
            xtype: 'filter',
            id: 'role',
            name: 'Role',
            param: 'role',
            items: roles
          },
        ]),
        viewConfig: {
          enableTextSelection: true,
          stripeRows: false,
          getRowClass: function(rec) {
            if (rec.get('deleted_at')) return 'disabled';
          },
          listeners: {
            itemcontextmenu: function(obj, rec, node, index, e) {
              e.stopEvent();
              me.menus.showAt(e.getXY());
            },
            itemclick: function(obj, rec) {
              viewDetail.load("{{ route('auth.user.view') }}/" + rec.get('id'))
            },
            itemdblclick: function(obj, rec) {
              viewDetail.expand();
            }
          }
        }
      });
    }

    me.actionUpdate = function(url, data, value, action) {
      me.grid.mask('Saving');
      http.request({
        method: 'post',
        url: url,
        params: {
          '_token': '{{ csrf_token() }}',
          data: Ext.encode(Ext.pluck(data, 'id')),
          value: value
        },
        success: function(respon) {
          me.grid.unmask();
          let result = Ext.decode(respon.responseText);
          if (result) {
            if (result.success) {
              me.grid.unmask();
              Ext.msg.success('Data has been saved');
              me.storeLoad();
              if (action) action(result);
            } else {
              Ext.msg.failed(result.message);
              if (action) action(result);
            }
          } else {
            Ext.msg.failed('Save data failed');
            if (action) action(null);
          }
        },
        failure: function() {
          Ext.msg.failed('Save data failed');
          me.grid.unmask();
          if (action) action(null);
        }
      });
    }
  }
</script>
