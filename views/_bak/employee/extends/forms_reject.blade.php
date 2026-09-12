<script>
  var FormsReject = function() {
    let me = Ext.utils.windowForms(this);
    me.data = null;

    me.organizationData = Ext.create('Ext.data.TreeStore', {
      folderSort: false,
      root: {
        id: 0,
        text: 'PT Qualita Indonesia',
        icon: '{{ asset('images/icons/home.png') }}',
        expanded: true
      },
      proxy: {
        type: 'ajax',
        url: '{{ route('organization.data') }}'
      },
    });

    me.divisionData = Ext.create('Ext.data.Store', {
      fields: ['id', 'name'],
      proxy: {
        type: 'ajax',
        url: '{{ route('division.data') }}',
        reader: {
          root: 'data',
          totalProperty: 'count'
        }
      },
      autoLoad: true
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

    me.placementData = Ext.create('Ext.data.Store', {
      fields: ['id', 'name'],
      proxy: {
        type: 'ajax',
        url: '{{ route('placement.data') }}',
        reader: {
          root: 'data',
          totalProperty: 'count'
        }
      },
      autoLoad: true
    });

    me.genderData = Ext.create('Ext.data.Store', {
      fields: ['id', 'name'],
      proxy: {
        type: 'ajax',
        url: '{{ route('globaldata.data') }}',
        extraParams: {
          group: 'gender'
        },
        reader: {
          root: 'data',
          totalProperty: 'count'
        }
      },
      autoLoad: true
    });

    me.maritalData = Ext.create('Ext.data.Store', {
      fields: ['id', 'name'],
      proxy: {
        type: 'ajax',
        url: '{{ route('globaldata.data') }}',
        extraParams: {
          group: 'marital'
        },
        reader: {
          root: 'data',
          totalProperty: 'count'
        }
      },
      autoLoad: true
    });

    me.religionData = Ext.create('Ext.data.Store', {
      fields: ['id', 'name'],
      proxy: {
        type: 'ajax',
        url: '{{ route('globaldata.data') }}',
        extraParams: {
          group: 'religion'
        },
        reader: {
          root: 'data',
          totalProperty: 'count'
        }
      },
      autoLoad: true
    });

    me.cityData = Ext.create('Ext.data.Store', {
      fields: ['id', 'province', 'city', 'lat', 'long'],
      proxy: {
        type: 'ajax',
        url: '{{ route('city.data') }}',
        reader: {
          root: 'data',
          totalProperty: 'count'
        }
      },
      autoLoad: true
    })

    me.bankData = Ext.create('Ext.data.Store', {
      fields: ['id', 'name'],
      proxy: {
        type: 'ajax',
        url: '{{ route('globaldata.data') }}',
        extraParams: {
          group: 'bank'
        },
        reader: {
          root: 'data',
          totalProperty: 'count'
        }
      },
      autoLoad: true
    });

    me.emergencyData = Ext.create('Ext.data.Store', {
      fields: ['id', 'name'],
      proxy: {
        type: 'ajax',
        url: '{{ route('globaldata.data') }}',
        extraParams: {
          group: 'emergency_relation'
        },
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
        scrollable: true,
        layout: {
          type: 'vbox',
          align: 'stretch'
        },
        fieldDefaults: {
          labelAlign: 'top',
        },
        items: [{
            xtype: 'container',
            layout: {
              type: 'hbox',
              align: 'stretch'
            },
            items: [{
                xtype: 'container',
                itemId: 'profileContainer',
                layout: {
                  type: 'vbox',
                  align: 'center'
                },
                items: [{
                    xtype: 'label',
                    text: 'Profile Picture:',
                    margin: '0 0 0 0',
                    style: 'color: #666666; font-size: 11px; font-weight: 600;'
                  },
                  {
                    xtype: 'image',
                    itemId: 'profileImage',
                    margin: '0 0 0 20',
                    src: '',
                    height: 100,
                    width: 100
                  }
                ]
              },
              {
                xtype: 'container',
                flex: 1,
                margin: '0 0 0 0',
                layout: {
                  type: 'vbox',
                  align: 'stretch'
                },
                items: [{
                  xtype: 'textfield',
                  fieldLabel: 'Nik',
                  afterLabelTextTpl: '<span style="color:red;">*</span>',
                  name: 'nik',
                  margin: '0 0 10 0',
                  readOnly: true
                }, {
                  xtype: 'textfield',
                  fieldLabel: 'Fullname',
                  afterLabelTextTpl: '<span style="color:red;">*</span>',
                  name: 'fullname',
                  margin: '0 0 10 0',
                  readOnly: true
                }]
              }
            ]
          },
          {
            xtype: 'container',
            layout: 'hbox',
            items: [{
                xtype: 'datefield',
                fieldLabel: 'Join Date',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                name: 'join_date',
                flex: 1,
                margin: '0 10 0 0',
                readOnly: true
              },
              {
                xtype: 'textfield',
                fieldLabel: 'Nickname',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                name: 'nickname',
                flex: 1,
                readOnly: true
              }
            ]
          },
          {
            xtype: 'container',
            layout: 'hbox',
            items: [{
                xtype: 'combo',
                labelAlign: 'top',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                name: 'company_id',
                fieldLabel: 'Company',
                store: me.companyData,
                displayField: 'name',
                valueField: 'id',
                queryMode: 'local',
                typeAhead: true,
                flex: 1,
                margin: '0 10 0 0',
                readOnly: true
              },
              Ext.create('Ext.ux.form.field.TreeCombo', {
                name: 'org_id',
                fieldLabel: 'Organization',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                labelAlign: 'top',
                rootVisible: true,
                canSelectFolders: true,
                editable: false,
                flex: 1,
                store: me.organizationData,
                readOnly: true
              })
            ]
          },
          {
            xtype: 'container',
            layout: 'hbox',
            items: [{
                xtype: 'combo',
                labelAlign: 'top',
                name: 'placement_id',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                fieldLabel: 'Placement',
                store: me.placementData,
                displayField: 'name',
                valueField: 'id',
                queryMode: 'local',
                typeAhead: true,
                flex: 1,
                margin: '0 10 0 0',
                readOnly: true
              },
              {
                xtype: 'combo',
                labelAlign: 'top',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                name: 'division_id',
                fieldLabel: 'Division',
                store: me.divisionData,
                displayField: 'name',
                valueField: 'id',
                queryMode: 'local',
                typeAhead: true,
                flex: 1,
                readOnly: true
              }
            ]
          },
          {
            xtype: 'container',
            layout: 'hbox',
            items: [{
                xtype: 'textfield',
                fieldLabel: 'Phone No',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                name: 'phone',
                flex: 1,
                margin: '0 10 0 0',
                readOnly: true
              },
              {
                xtype: 'textfield',
                fieldLabel: 'Email',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                name: 'email',
                vtype: 'email',
                flex: 1,
                margin: '0 10 0 0',
                readOnly: true
              },
              {
                xtype: 'textfield',
                fieldLabel: 'Leave Saldo',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                name: 'leave_saldo',
                flex: 1,
                readOnly: true
              },
            ]
          },
          {
            xtype: 'container',
            layout: 'hbox',
            items: [{
                xtype: 'textfield',
                fieldLabel: 'Birth Place',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                name: 'birth_place',
                flex: 1,
                margin: '0 10 0 0',
                readOnly: true
              },
              {
                xtype: 'datefield',
                fieldLabel: 'Birth Date',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                name: 'birth_date',
                flex: 1,
                margin: '0 10 0 0',
                readOnly: true
              },
            ]
          },
          {
            xtype: 'container',
            layout: 'hbox',
            items: [{
                xtype: 'combo',
                fieldLabel: 'Gender',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                name: 'gender_id',
                store: me.genderData,
                displayField: 'name',
                valueField: 'id',
                queryMode: 'local',
                typeAhead: true,
                flex: 1,
                margin: '0 10 0 0',
                readOnly: true
              },
              {
                xtype: 'combo',
                fieldLabel: 'Marital',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                name: 'marital_id',
                store: me.maritalData,
                displayField: 'name',
                valueField: 'id',
                queryMode: 'local',
                typeAhead: true,
                flex: 1,
                margin: '0 10 0 0',
                readOnly: true
              },
              {
                xtype: 'combo',
                fieldLabel: 'Religion',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                name: 'religion_id',
                store: me.religionData,
                displayField: 'name',
                valueField: 'id',
                queryMode: 'local',
                typeAhead: true,
                flex: 1,
                readOnly: true
              }
            ]
          },
          {
            xtype: 'container',
            layout: 'hbox',
            items: [{
                xtype: 'textfield',
                fieldLabel: 'Current Address',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                name: 'address',
                flex: 1,
                margin: '0 10 0 0',
                readOnly: true
              },
              {
                xtype: 'combo',
                labelAlign: 'top',
                name: 'address_city_id',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                fieldLabel: 'City',
                store: me.cityData,
                displayField: 'city',
                valueField: 'id',
                queryMode: 'local',
                typeAhead: true,
                flex: 1,
                margin: '0 10 0 0',
                readOnly: true
              },
              {
                xtype: 'combo',
                labelAlign: 'top',
                name: 'address_province_id',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                fieldLabel: 'Province',
                store: me.cityData,
                displayField: 'province',
                valueField: 'id',
                queryMode: 'local',
                typeAhead: true,
                flex: 1,
                readOnly: true
              },
            ]
          },
          {
            xtype: 'container',
            layout: 'hbox',
            items: [{
                xtype: 'textfield',
                fieldLabel: 'Permanent Address',
                name: 'address_permanent',
                flex: 1,
                margin: '0 10 0 0',
                readOnly: true
              },
              {
                xtype: 'combo',
                labelAlign: 'top',
                name: 'address_permanent_city_id',
                fieldLabel: 'Permanent City',
                store: me.cityData,
                displayField: 'city',
                valueField: 'id',
                queryMode: 'local',
                typeAhead: true,
                margin: '0 10 0 0',
                flex: 1,
                readOnly: true
              },
              {
                xtype: 'combo',
                labelAlign: 'top',
                name: 'address_permanent_province_id',
                fieldLabel: 'Permanent Proviece',
                store: me.cityData,
                displayField: 'province',
                valueField: 'id',
                queryMode: 'local',
                typeAhead: true,
                flex: 1,
                readOnly: true
              },
            ]
          },
          {
            xtype: 'container',
            layout: 'hbox',
            items: [{
                xtype: 'combo',
                labelAlign: 'top',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                name: 'bank_id',
                fieldLabel: 'Bank',
                store: me.bankData,
                displayField: 'name',
                valueField: 'id',
                queryMode: 'local',
                typeAhead: true,
                flex: 1,
                margin: '0 10 0 0',
                readOnly: true
              },
              {
                xtype: 'textfield',
                fieldLabel: 'Bank Account',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                name: 'bank_account',
                flex: 1,
                readOnly: true
              },
            ]
          },
          {
            xtype: 'container',
            layout: 'hbox',
            items: [{
                xtype: 'combo',
                labelAlign: 'top',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                name: 'emergency_relation_id',
                fieldLabel: 'Emergency Relation',
                store: me.emergencyData,
                displayField: 'name',
                valueField: 'id',
                queryMode: 'local',
                typeAhead: true,
                flex: 1,
                margin: '0 10 0 0',
                readOnly: true
              },
              {
                xtype: 'textfield',
                fieldLabel: 'Emergency Contact Name',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                name: 'emergency_contact_name',
                flex: 1,
                margin: '0 10 0 0',
                readOnly: true
              },
              {
                xtype: 'textfield',
                fieldLabel: 'Emergency Contact Phone',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                name: 'emergency_contact_phone',
                flex: 1,
                margin: '0 10 0 0',
                readOnly: true
              },
              {
                xtype: 'textfield',
                fieldLabel: 'Emergency Contact Address',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                name: 'emergency_contact_address',
                flex: 1,
                readOnly: true
              }
            ]
          },
          {
            xtype: 'container',
            flex: 1
          },
          {
            xtype: 'textarea',
            fieldLabel: 'Notes',
            name: 'note',
            flex: 1,
            margin: '0 10 0 0',
          },
          {
            xtype: 'container',
            layout: {
              type: 'hbox',
              pack: 'end'
            },
            style: {
              'border-top': '1px solid #ccc',
              'margin-top': '15px',
              'padding-top': '15px',
            },
            items: [{
                xtype: 'container',
                flex: 1
              },
              {
                xtype: 'button',
                text: 'Save',
                iconCls: 'icon-save-bright',
                handler: me.save,
              },
              {
                xtype: 'button',
                text: 'Cancel',
                cls: 'btn-red',
                iconCls: 'icon-close',
                handler: me.close,
              }
            ]
          }
        ]
      });

      me.createWindowForm('Form Reject', me.form, {
        maximized: true,
        header: true,
      });
    };

    me.open = function() {
      var rec = grids.getRec(true);
      if (rec) {
        me.show();
        me.reset();
        me.form.getEl().mask('Loading');
        Ext.Ajax.request({
          method: 'get',
          url: '{{ route('employee.request.get.by.employee', ':id') }}'.replace(':id', rec.id),
          success: function(response) {
            me.data = Ext.decode(response.responseText);
            me.editRender(me.data);
            me.form.getEl().unmask();
          },
          failure: function(response) {
            Ext.Msg.alert("Error", "Failed to load data.");
            me.form.getEl().unmask();
          }
        });
      }
    };

    me.editRender = function(data) {
      me.form.getForm().setValues(data);

      let org_id = data ? data.org_id : null;
      let org = me.getField('org_id').store;
      org.proxy.extraParams.selected = org_id;
      org.load();

      me.getField('org_id').setValue(org_id);

      const profilePictureUrl = '{{ route('file', ':id') }}'.replace(':id', data.photo_id);

      const profilePictureContainer = me.form.down('container[itemId=profileContainer]');
      const profileImage = profilePictureContainer.down('image');

      if (data.photo_id) {
        profileImage.setSrc(profilePictureUrl);
        profilePictureContainer.show();
      } else {
        profilePictureContainer.hide();
      }
    };

    me.save = function() {
      const form = me.form.getForm();

      const formData = new FormData();
      formData.append('_token', '{{ csrf_token() }}');
      formData.append('note', form.findField('note').getValue());
      formData.append('id', me.data.id);
      formData.append('_method', 'PUT');

      Ext.Ajax.request({
        url: '{{ route('employee.request.reject') }}',
        rawData: formData,
        headers: {
          'Content-Type': null
        },
        success: function(response) {
          Ext.Msg.alert('Success', 'Data has been rejected!');
          grids.storeLoad();
          me.close();
        },
        failure: function(response) {
          Ext.Msg.alert("Error", "Failed to reject data.");
        }
      });
    };

    me.init();
  }
</script>
