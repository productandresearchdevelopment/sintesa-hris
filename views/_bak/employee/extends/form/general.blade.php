<script>
  var FormGeneral = function() {
    let me = Ext.utils.windowForms(this);
    me.data = null;

    me.orgStore = Ext.create('Ext.data.TreeStore', {
      fields: ['id', 'text', 'leaf', 'icon'],
      proxy: {
        type: 'ajax',
        url: '{{ route('organization.data') }}',
        extraParams: {
          company_id: null
        },
        reader: {
          type: 'json'
        }
      },
      root: {
        id: '0',
        text: 'PT Sintesa Talenta Asia',
        expanded: true
      }
    });

    me.orgPicker = Ext.create('Ext.ux.TreePicker', {
      name: 'org_id',
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

    me.officeData = Ext.create('Ext.data.Store', {
      fields: ['id', 'company_id', 'name'],
      proxy: {
        type: 'ajax',
        url: '{{ route('office.data') }}',
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

    me.getFields = function() {
      return [{
          xtype: 'container',
          layout: 'hbox',
          items: [{
              xtype: 'textfield',
              fieldLabel: 'Nik',
              afterLabelTextTpl: '<span style="color:red;">*</span>',
              name: 'nik',
              allowBlank: false,
              flex: 1,
              margin: '0 10 0 0'
            },
            {
              xtype: 'textfield',
              fieldLabel: 'Fullname',
              afterLabelTextTpl: '<span style="color:red;">*</span>',
              name: 'fullname',
              allowBlank: false,
              flex: 1
            },
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
              allowBlank: false,
              flex: 1,
              margin: '0 10 0 0',
            },
            {
              xtype: 'textfield',
              fieldLabel: 'Nickname',
              name: 'nickname',
              allowBlank: true,
              flex: 1
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
              allowBlank: false,
              listeners: {
                select: function(combo, record) {
                  const selectedRecord = Array.isArray(record) ? record[0] : record;
                  if (selectedRecord) {
                    const companyId = selectedRecord.get('id');
                    me.officeData.proxy.extraParams.company_id = companyId;
                    me.officeData.load();
                    me.orgStore.proxy.extraParams.company_id = companyId;
                    me.orgStore.load();
                    me.divisionData.proxy.extraParams.company_id = companyId;
                    me.divisionData.load();
                  }
                }
              }
            },
            me.orgPicker,
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
              allowBlank: false,
              listeners: {
                select: function(combo, record) {
                  this.setValue(record);
                }
              }
            },
            {
              xtype: 'combo',
              labelAlign: 'top',
              name: 'office_id',
              fieldLabel: 'Office',
              store: me.officeData,
              displayField: 'name',
              valueField: 'id',
              queryMode: 'local',
              typeAhead: true,
              flex: 1,
              margin: '0 10 0 0',
              allowBlank: true,
              listeners: {
                select: function(combo, record) {
                  this.setValue(record);
                }
              }
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
              allowBlank: false,
              listeners: {
                select: function(combo, record) {
                  this.setValue(record);
                }
              }
            }
          ]
        },
        {
          xtype: 'container',
          layout: 'hbox',
          items: [{
              xtype: 'timefield',
              fieldLabel: 'Shift Start Time',
              name: 'shift_start_time',
              flex: 1,
              margin: '0 10 0 0',
              allowBlank: true,
              format: 'H:i',
              submitFormat: 'H:i'
            },
            {
              xtype: 'timefield',
              fieldLabel: 'Shift End Time',
              name: 'shift_end_time',
              flex: 1,
              allowBlank: true,
              format: 'H:i',
              submitFormat: 'H:i'
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
              allowBlank: false,
              flex: 1,
              margin: '0 10 0 0'
            },
            {
              xtype: 'textfield',
              fieldLabel: 'Email',
              afterLabelTextTpl: '<span style="color:red;">*</span>',
              name: 'email',
              vtype: 'email',
              allowBlank: false,
              flex: 1,
              margin: '0 10 0 0'
            },
            {
              xtype: 'textfield',
              fieldLabel: 'Leave Saldo',
              afterLabelTextTpl: '<span style="color:red;">*</span>',
              name: 'leave_saldo',
              allowBlank: false,
              flex: 1,
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
              allowBlank: false
            },
            {
              xtype: 'datefield',
              fieldLabel: 'Birth Date',
              afterLabelTextTpl: '<span style="color:red;">*</span>',
              name: 'birth_date',
              flex: 1,
              allowBlank: false
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
              allowBlank: false,
              flex: 1,
              margin: '0 10 0 0',
              listeners: {
                select: function(combo, record) {
                  this.setValue(record);
                }
              }
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
              allowBlank: false,
              flex: 1,
              margin: '0 10 0 0',
              listeners: {
                select: function(combo, record) {
                  this.setValue(record);
                }
              }
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
              allowBlank: false,
              flex: 1,
              listeners: {
                select: function(combo, record) {
                  this.setValue(record);
                }
              }
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
              allowBlank: false,
              flex: 1,
              margin: '0 10 0 0'
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
              allowBlank: false,
              listeners: {
                select: function(combo, record) {
                  this.setValue(record);
                }
              }
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
              allowBlank: false,
              listeners: {
                select: function(combo, record) {
                  this.setValue(record);
                }
              }
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
              allowBlank: true,
              flex: 1,
              margin: '0 10 0 0'
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
              listeners: {
                select: function(combo, record) {
                  this.setValue(record);
                }
              }
            },
            {
              xtype: 'combo',
              labelAlign: 'top',
              name: 'address_permanent_province_id',
              fieldLabel: 'Permanent Province',
              store: me.cityData,
              displayField: 'province',
              valueField: 'id',
              queryMode: 'local',
              typeAhead: true,
              flex: 1,
              listeners: {
                select: function(combo, record) {
                  this.setValue(record);
                }
              }
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
              allowBlank: false,
              listeners: {
                select: function(combo, record) {
                  this.setValue(record);
                }
              }
            },
            {
              xtype: 'textfield',
              fieldLabel: 'Bank Account',
              afterLabelTextTpl: '<span style="color:red;">*</span>',
              name: 'bank_account',
              allowBlank: true,
              flex: 1,
              allowBlank: false,
            },
          ]
        },
        {
          xtype: 'container',
          layout: 'hbox',
          items: [{
              xtype: 'combo',
              labelAlign: 'top',
              name: 'emergency_relation_id',
              fieldLabel: 'Emergency Relation',
              store: me.emergencyData,
              displayField: 'name',
              valueField: 'id',
              queryMode: 'local',
              typeAhead: true,
              flex: 1,
              margin: '0 10 0 0',
              allowBlank: true,
              listeners: {
                select: function(combo, record) {
                  this.setValue(record);
                }
              }
            },
            {
              xtype: 'textfield',
              fieldLabel: 'Emergency Contact Name',
              name: 'emergency_contact_name',
              allowBlank: true,
              flex: 1,
              margin: '0 10 0 0',
              allowBlank: true
            },
            {
              xtype: 'textfield',
              fieldLabel: 'Emergency Contact Phone',
              name: 'emergency_contact_phone',
              allowBlank: true,
              flex: 1,
              margin: '0 10 0 0',
              allowBlank: true
            },
            {
              xtype: 'textfield',
              fieldLabel: 'Emergency Contact Address',
              name: 'emergency_contact_address',
              allowBlank: true,
              flex: 1,
              allowBlank: true
            }
          ]
        },
        {
          xtype: 'container',
          margin: '0 0 20 0',
          itemId: 'fileGeneralContainer',
          height: 300,
          layout: {
            type: 'vbox',
            align: 'stretch'
          },
          items: [{
              xtype: 'label',
              text: 'Profile Picture:',
              margin: '0 0 3 0',
              style: 'color: #666666; font-size: 11px; font-weight: 600;'
            },
            {
              xtype: 'box',
              html: `
                  <div id="dropZone" style="width: 100%; height: 280px; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; flex-direction: column; cursor: pointer; text-align: center;">
                    <img id="imagePreview" style="display: none; max-width: 150px; max-height: 150px; object-fit: contain;"/>
                    <span id="dropText" style="font-size: 14px; color: #888;">Drop or Select File</span>
                    <span id="fileSizeText" style="font-size: 14px; color: #888;">Max Size: 2 MB</span>
                    <input type="file" id="fileInputGeneral" style="display: none;" accept="image/png, image/jpeg, image/jpg, image/webp"/>
                  </div>
                `,
              listeners: {
                afterrender: function() {
                  const dropZone = document.getElementById('dropZone');
                  const dropText = document.getElementById('dropText');
                  const fileSizeText = document.getElementById('fileSizeText');
                  const imagePreview = document.getElementById('imagePreview');
                  const fileInput = document.getElementById('fileInputGeneral');

                  dropZone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    dropZone.style.borderColor = '#007bff';
                  });

                  dropZone.addEventListener('dragleave', function(e) {
                    dropZone.style.borderColor = '#ccc';
                  });

                  dropZone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    dropZone.style.borderColor = '#ccc';
                    const file = e.dataTransfer.files[0];
                    me.handleFileUpload(file);
                  });

                  dropZone.addEventListener('click', function() {
                    fileInput.click();
                  });

                  fileInput.addEventListener('change', function() {
                    const file = fileInput.files[0];
                    me.handleFileUpload(file);
                  });
                }
              }
            },
          ]
        }
      ];
    };

    me.handleFileUpload = function(file) {
      const dropText = document.getElementById('dropText');
      const fileSizeText = document.getElementById('fileSizeText');
      const imagePreview = document.getElementById('imagePreview');

      if (file) {
        if (file.size > 2 * 1024 * 1024) {
          alert('File size exceeds 2 MB. Please select a smaller file.');
          return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
          imagePreview.src = e.target.result;
          imagePreview.style.display = 'block';
          dropText.style.display = 'none';
          fileSizeText.style.display = 'none';
          imagePreview.style.width = '200px';
          imagePreview.style.height = '200px';
        };
        reader.readAsDataURL(file);
      }
    }
  };
</script>
