<script>
  var Forms = function() {
    let me = Ext.utils.windowForms(this);

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
        text: 'PT Qualita Indonesia',
        icon: '{{ asset('images/icons/home.png') }}',
        expanded: true
      },
    });

    me.positionData = Ext.create('Ext.data.Store', {
      fields: ['id', 'name'],
      proxy: {
        type: 'ajax',
        url: '{{ route('globaldata.data') }}',
        extraParams: {
          group: 'position'
        },
        reader: {
          root: 'data',
          totalProperty: 'count'
        }
      },
      autoLoad: true
    });

    me.createTreeCombo = function(name, label) {
      return Ext.create('Ext.ux.TreePicker', {
        name: name,
        fieldLabel: label,
        labelAlign: 'top',
        store: me.orgStore,
        displayField: 'text',
        valueField: 'id',
        rootVisible: true,
        editable: false,
        allowBlank: false,
        minPickerHeight: 200,
        maxPickerHeight: 200,
      });
    };

    me.setTreePickerValue = function(fieldName, idStr) {
      if (!idStr || idStr === '0' || idStr === 0) {
        // Kalau root, langsung set ke '0' tanpa hit API
        var field = me.getField(fieldName);
        if (field) {
          field.setValue('0');
          field.setRawValue('PT Qualita Indonesia');
        }
        return;
      }

      var field = me.getField(fieldName);
      Ext.Ajax.request({
        url: '{{ route('organization.path', ['id' => ':id']) }}'.replace(':id', idStr),
        method: 'GET',
        success: function(resp) {
          var ids = Ext.decode(resp.responseText) || [];
          ids = ids.map(function(x) {
            return String(x);
          });

          var treePath = '/' + ids.join('/');

          field.getPicker().expandPath(treePath, 'id', '/', function(success, lastNode) {
            if (success && lastNode) {
              field.setValue(String(lastNode.getId()));
              field.setRawValue(lastNode.get('text'));
            } else {
              field.setValue(idStr);
            }
          });
        },
        failure: function() {
          field.setValue(idStr);
        }
      });
    };

    me.init = function() {
      me.form = Ext.widget('form', {
        bodyPadding: '10 15 10 15',
        scrollable: true,
        autoScroll: true,
        height: 400,
        border: false,
        fieldDefaults: {
          labelAlign: 'top',
          labelWidth: 80,
          msgTarget: 'side'
        },
        defaults: {
          anchor: '100%'
        },
        items: [{
            xtype: 'hidden',
            name: '_method'
          },
          {
            xtype: 'hidden',
            name: '_token',
            value: '{{ csrf_token() }}'
          },
          {
            xtype: 'hidden',
            name: 'company_id',
            value: null
          },

          {
            xtype: 'textfield',
            fieldLabel: 'Name',
            afterLabelTextTpl: '<span style="color:red;">*</span>',
            name: 'name',
            allowBlank: false
          },
          {
            xtype: 'textfield',
            fieldLabel: 'Alias',
            name: 'alias'
          },

          me.createTreeCombo('parent_id', 'Parent'),
          {
            xtype: 'combobox',
            fieldLabel: 'Position',
            name: 'position_id',
            store: me.positionData,
            queryMode: 'local',
            displayField: 'name',
            valueField: 'id'
          },
          {
            xtype: 'textfield',
            fieldLabel: 'Description',
            name: 'description'
          },
          me.createTreeCombo('authorized1', 'Authorized 1'),
          me.createTreeCombo('authorized2', 'Authorized 2')
        ],
        buttons: [{
            text: 'Save',
            iconCls: 'icon-save-bright',
            handler: me.save
          },
          {
            text: 'Cancel',
            cls: 'btn-red',
            iconCls: 'icon-cancel',
            handler: me.close
          }
        ]
      });

      me.createWindowForm('Form Organization', me.form, {
        maximized: false,
        header: true,
        title: 'Create Organization',
        width: 400
      });
    };

    me.setup = function(companyId, rec) {
      me.show();
      me.reset();

      me.getField('company_id').setValue(companyId);
      me.orgStore.getProxy().setExtraParam('company_id', companyId);

      function normalize(val) {
        if (val === null || val === undefined || val === '') return null;
        return String(val);
      }

      function extractId(val) {
        if (!val) return null;
        if (typeof val === 'object') return normalize(val.id);
        return normalize(val);
      }

      var parentId = rec ? extractId(rec.parent_id) : null;
      var auth1Id = rec ? extractId(rec.authorized1) : null;
      var auth2Id = rec ? extractId(rec.authorized2) : null;
      var positionId = rec ? normalize(rec.position_id) : null;

      me.orgStore.load({
        callback: function() {
          if (rec) {
            me.setField('name', rec.text || '');
            me.setField('alias', rec.alias || '');
            me.setField('description', rec.description || '');

            var setPosition = function() {
              var field = me.getField('position_id');
              var record = me.positionData.findRecord('id', positionId);

              if (record) {
                field.setValue(record.get('id'));
              } else {
                field.setValue(positionId);
              }
            };

            if (me.positionData.isLoaded && me.positionData.isLoaded()) {
              setPosition();
            } else {
              me.positionData.load({
                callback: function() {
                  setPosition();
                }
              });
            }

            me.setTreePickerValue('parent_id', parentId || '0');

            if (auth1Id) me.setTreePickerValue('authorized1', auth1Id);
            if (auth2Id) me.setTreePickerValue('authorized2', auth2Id);

          } else {
            ['parent_id', 'authorized1', 'authorized2'].forEach(function(name) {
              var f = me.getField(name);
              if (f) {
                f.clearValue && f.clearValue();
                f.setValue(null);
              }
            });

            me.setTreePickerValue('parent_id', '0');
          }
        }
      });
    };

    me.create = function(companyId) {
      me.setup(companyId, null);
      me.form.url = '{{ route('organization.create') }}';
      me.setField('_method', '');
    };

    me.edit = function(companyId) {
      var rec = grids.getRec(true);
      if (rec) {
        me.setup(companyId, rec);
        me.form.url = '{{ route('organization.update', '') }}/' + rec.id;
        me.setField('_method', 'PUT');
      } else {
        Ext.example.msg('Warning', 'Please Select Data!');
      }
    };

    me.save = function() {
      const parentId = me.getValue('parent_id');
      const isRootParent = parentId === '0' || parentId === 0;
      const isUnauthorized =
        me.getValue('authorized1') === '0' || me.getValue('authorized1') === 0 ||
        me.getValue('authorized2') === '0' || me.getValue('authorized2') === 0;

      me.submit(me.form.url, {
        success: function() {
          grids.store.proxy.extraParams.selected = me.getValue('parent_id');
          grids.storeLoad();
          me.close();
        }
      });
    };
  };
</script>
