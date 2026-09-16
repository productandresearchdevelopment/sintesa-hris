<style>
  .x-form-item input[type="date"]::-webkit-calendar-picker-indicator {
    display: none !important;
    -webkit-appearance: none !important;
  }
</style>

<script>
  var Forms = function() {
    let me = Ext.utils.windowForms(this);

    me.data = null;

    me.init = function() {
      var rawTypes = @json($types);
      var typeData = [];
      if (rawTypes && rawTypes.length) {
        rawTypes.forEach(function(t) {
          typeData.push({
            id: t.id,
            name: t.name
          });
        });
      }

      var typeStore = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        data: typeData
      });

      me.form = Ext.widget('form', {
        bodyPadding: 20,
        width: 540,
        border: false,
        layout: {
          type: 'vbox',
          align: 'stretch'
        },
        submitEmptyText: false,
        fieldDefaults: {
          labelAlign: 'top',
          labelSeparator: '',
          allowBlank: false,
          msgTarget: 'under'
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
            xtype: 'combobox',
            name: 'type_id',
            fieldLabel: 'Leave Type',
            afterLabelTextTpl: '<span style="color:red;">*</span>',
            margin: '0 0 12 0',
            store: typeStore,
            displayField: 'name',
            valueField: 'id',
            editable: false,
            emptyText: 'Select Leave Type...',
            allowBlank: false,
            listeners: {
              change: function() {
                me.calcDuration();
                me.checkAttachmentRequirement();
              }
            }
          },
          {
            xtype: 'container',
            layout: 'hbox',
            margin: '0 0 12 0',
            items: [{
                xtype: 'datefield',
                name: 'start_date',
                fieldLabel: 'Start Date',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                format: 'Y-m-d',
                altFormats: 'Y-m-d|d-m-Y|d/m/Y|Y/m/d',
                submitFormat: 'Y-m-d',
                emptyText: 'YYYY-MM-DD',
                flex: 1,
                margin: '0 10 0 0',
                allowBlank: false,
                listeners: {
                  change: function() {
                    me.calcDuration();
                  }
                }
              },
              {
                xtype: 'datefield',
                name: 'end_date',
                fieldLabel: 'End Date',
                afterLabelTextTpl: '<span style="color:red;">*</span>',
                format: 'Y-m-d',
                altFormats: 'Y-m-d|d-m-Y|d/m/Y|Y/m/d',
                submitFormat: 'Y-m-d',
                emptyText: 'YYYY-MM-DD',
                flex: 1,
                allowBlank: false,
                listeners: {
                  change: function() {
                    me.calcDuration();
                  }
                }
              }
            ]
          },
          {
            xtype: 'container',
            layout: 'hbox',
            margin: '0 0 12 0',
            items: [{
                xtype: 'numberfield',
                name: 'duration',
                fieldLabel: 'Duration (Days)',
                minValue: 1,
                value: 1,
                flex: 1,
                margin: '0 10 0 0',
                readOnly: true,
                allowBlank: false
              },
              {
                xtype: 'textfield',
                name: 'remaining_leave',
                fieldLabel: 'Remaining Leave',
                readOnly: true,
                flex: 1,
                value: currentEmployee ? (currentEmployee.leave_saldo || 0) : 0
              }
            ]
          },
          {
            xtype: 'textareafield',
            name: 'description',
            fieldLabel: 'Notes / Reason',
            margin: '0 0 12 0',
            allowBlank: true,
            height: 80
          },
          {
            xtype: 'filefield',
            name: 'file_id',
            fieldLabel: 'Attachment (Doctor letter / Document - Optional)',
            allowBlank: true,
            listeners: {
              afterrender: function(field) {
                Ext.defer(function() {
                  if (field.fileInputEl && field.fileInputEl.dom) {
                    field.fileInputEl.dom.setAttribute('accept', '.jpg,.jpeg,.png,.pdf,.doc,.docx');
                  }
                }, 100);
              },
              change: function(cmp) {
                if (cmp.fileInputEl && cmp.fileInputEl.dom && cmp.fileInputEl.dom.files && cmp.fileInputEl
                  .dom.files[0]) {
                  var file = cmp.fileInputEl.dom.files[0];
                  if (file.size > 5 * 1024 * 1024) {
                    Ext.Msg.alert('Warning', 'The attachment file size (' + (file.size / (1024 * 1024))
                      .toFixed(2) + ' MB) exceeds the maximum limit of 5MB. Please choose a smaller file.'
                    );
                    cmp.reset();
                  }
                }
              }
            }
          }
        ],
        buttons: [{
            text: 'Save',
            iconCls: 'icon-save-bright',
            handler: function() {
              me.save();
            }
          },
          {
            text: 'Cancel',
            cls: 'btn-red',
            iconCls: 'icon-close',
            handler: function() {
              me.close();
            }
          }
        ]
      });

      me.createWindowForm('Leave Request', me.form, {
        width: 560,
        maximized: false,
        header: true
      });
    };

    me.checkAttachmentRequirement = function() {
      var form = me.form.getForm();
      var typeField = form.findField('type_id');
      var fileField = form.findField('file_id');
      if (!typeField || !fileField) return;

      var selectedTypeId = typeField.getValue();
      var selectedType = null;
      if (selectedTypeId && typeof allLeaveTypes !== 'undefined' && allLeaveTypes && allLeaveTypes.length) {
        selectedType = allLeaveTypes.find(function(t) {
          return t.id == selectedTypeId;
        });
      }

      var isSick = selectedType && selectedType.name && selectedType.name.toUpperCase().indexOf('SAKIT') !== -1;
      if (isSick) {
        fileField.setFieldLabel('Attachment (Doctor letter / Document) <span style="color:red;">*</span>');
        fileField.allowBlank = false;
      } else {
        fileField.setFieldLabel('Attachment (Doctor letter / Document - Optional)');
        fileField.allowBlank = true;
        fileField.clearInvalid();
      }
    };

    me.calcDuration = function() {
      var form = me.form.getForm();
      var typeField = form.findField('type_id');
      var startField = form.findField('start_date');
      var endField = form.findField('end_date');
      var durationField = form.findField('duration');
      var remainingField = form.findField('remaining_leave');

      var baseSaldo = (currentEmployee && currentEmployee.leave_saldo !== undefined && currentEmployee
          .leave_saldo !== null) ?
        parseFloat(currentEmployee.leave_saldo) :
        0;

      if (me.data && me.data.leave_saldo !== undefined && me.data.leave_saldo !== null) {
        baseSaldo = parseFloat(me.data.leave_saldo);
      }

      var startVal = startField ? startField.getValue() : null;
      var endVal = endField ? endField.getValue() : null;

      if (startVal && endVal && !isNaN(startVal) && !isNaN(endVal)) {
        if (startVal <= endVal) {
          var diffTime = endVal.getTime() - startVal.getTime();
          var diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1;
          durationField.setValue(diffDays);

          var rem = baseSaldo - diffDays;
          remainingField.setValue(rem);

          if (diffDays > baseSaldo || rem < 0) {
            remainingField.setFieldStyle('color: #dc2626; font-weight: bold; background-color: #fee2e2;');
            durationField.setFieldStyle('color: #dc2626; font-weight: bold; background-color: #fee2e2;');
            if (endField) {
              endField.markInvalid('Duration (' + diffDays + ' days) exceeds remaining leave balance (' +
                baseSaldo + ' days)');
            }
          } else {
            remainingField.setFieldStyle('color: #0f172a; font-weight: 500; background-color: #f8fafc;');
            durationField.setFieldStyle('color: #0f172a; font-weight: 500; background-color: #f8fafc;');
            if (endField) {
              endField.clearInvalid();
            }
          }
        } else {
          durationField.setValue(0);
          remainingField.setValue(baseSaldo);
          remainingField.setFieldStyle('color: #0f172a; font-weight: 500; background-color: #f8fafc;');
          durationField.setFieldStyle('color: #0f172a; font-weight: 500; background-color: #f8fafc;');
          if (endField) {
            endField.markInvalid('End Date cannot be earlier than Start Date');
          }
        }
      } else {
        durationField.setValue(1);
        remainingField.setValue(baseSaldo);
        remainingField.setFieldStyle('color: #0f172a; font-weight: 500; background-color: #f8fafc;');
        durationField.setFieldStyle('color: #0f172a; font-weight: 500; background-color: #f8fafc;');
        if (endField) {
          endField.clearInvalid();
        }
      }
    };

    me.create = function() {
      me.show();
      me.reset();
      me.data = null;
      me.window.setTitle('Add Leave Request');
      me.form.url = '{{ route('leave.push') }}';
      me.calcDuration();
      me.checkAttachmentRequirement();
    };

    me.edit = function(data) {
      if (!data) return;
      me.show();
      me.reset();
      me.data = data;
      me.window.setTitle('Edit Leave Request');

      var form = me.form.getForm();
      form.setValues({
        id: data.id,
        type_id: data.type_id,
        start_date: data.start_date,
        end_date: data.end_date,
        duration: data.duration,
        description: data.description
      });

      me.calcDuration();
      me.checkAttachmentRequirement();
      me.form.url = '{{ route('leave.push') }}/' + data.id;
    };

    me.save = function() {
      var form = me.form.getForm();
      if (!form.isValid()) {
        Ext.Msg.alert('Warning', 'Please complete all required fields.');
        return;
      }
      var values = form.getValues();

      var startVal = form.findField('start_date').getValue();
      var endVal = form.findField('end_date').getValue();

      if (startVal && endVal && startVal > endVal) {
        Ext.Msg.alert('Warning', 'Start Date cannot be after End Date.');
        return;
      }

      var typeField = form.findField('type_id');
      var selectedTypeId = typeField ? typeField.getValue() : null;
      var selectedType = null;
      if (selectedTypeId && typeof allLeaveTypes !== 'undefined' && allLeaveTypes && allLeaveTypes.length) {
        selectedType = allLeaveTypes.find(function(t) {
          return t.id == selectedTypeId;
        });
      }

      var baseSaldo = (currentEmployee && currentEmployee.leave_saldo !== undefined && currentEmployee
          .leave_saldo !== null) ?
        parseFloat(currentEmployee.leave_saldo) :
        0;

      if (me.data && me.data.leave_saldo !== undefined && me.data.leave_saldo !== null) {
        baseSaldo = parseFloat(me.data.leave_saldo);
      }

      var isReduceBalance = false;
      if (selectedType) {
        if (typeof selectedType.property === 'string') {
          try {
            var p = JSON.parse(selectedType.property);
            isReduceBalance = p.flag_reduce_balance === true || p.flag_reduce_balance === 1 || p
              .flag_reduce_balance === '1' || p.flag_reduce_balance === 'true';
          } catch (e) {}
        } else if (typeof selectedType.property === 'object' && selectedType.property !== null) {
          isReduceBalance = selectedType.property.flag_reduce_balance === true || selectedType.property
            .flag_reduce_balance === 1 || selectedType.property.flag_reduce_balance === '1' || selectedType.property
            .flag_reduce_balance === 'true';
        }
        if (selectedType.flag_reduce_balance === true || selectedType.flag_reduce_balance === 1 || selectedType
          .flag_reduce_balance === '1') {
          isReduceBalance = true;
        }
      }

      var duration = parseFloat(values.duration || 0);

      if (isReduceBalance && duration > baseSaldo) {
        Ext.Msg.alert('Warning', 'Requested duration (' + duration +
          ' days) cannot exceed your available remaining leave balance (' + baseSaldo + ' days).');
        return;
      }

      var fileField = me.form.down('filefield[name=file_id]');
      var fileInput = (fileField && fileField.fileInputEl && fileField.fileInputEl.dom && fileField.fileInputEl.dom
          .files) ?
        fileField.fileInputEl.dom.files[0] :
        null;

      if (fileInput && fileInput.size > 5 * 1024 * 1024) {
        Ext.Msg.alert('Warning', 'The attachment file size (' + (fileInput.size / (1024 * 1024)).toFixed(2) +
          ' MB) exceeds the maximum limit of 5MB.');
        return;
      }

      var isSick = selectedType && selectedType.name && selectedType.name.toUpperCase().indexOf('SAKIT') !== -1;
      if (isSick) {
        var isNew = !values.id;
        var hasExistingFile = me.data && me.data.file_id;
        if (!fileInput && (isNew || !hasExistingFile)) {
          Ext.Msg.alert('Warning', 'Attachment (Doctor letter / Medical document) is required for Sick leave.');
          return;
        }
      }

      var startFormatted = Ext.Date.format(startVal, 'Y-m-d');
      var endFormatted = Ext.Date.format(endVal, 'Y-m-d');

      var formData = new FormData();
      formData.append('_token', values._token || '{{ csrf_token() }}');
      if (values.id) formData.append('id', values.id);
      formData.append('type_id', values.type_id || '');
      formData.append('start_date', startFormatted);
      formData.append('end_date', endFormatted);
      formData.append('duration', values.duration || '1');
      if (values.description) formData.append('description', values.description);

      if (fileInput) {
        formData.append('file_id', fileInput);
      }

      var targetUrl = (values.id) ? ('{{ route('leave.push') }}/' + values.id) : '{{ route('leave.push') }}';

      me.form.getEl().mask('Saving...');
      Ext.Ajax.request({
        url: targetUrl,
        rawData: formData,
        headers: {
          'Content-Type': null
        },
        success: function(response) {
          me.form.getEl().unmask();
          var res = Ext.decode(response.responseText);
          if (res && res.success) {
            grids.storeLoad();
            me.close();
            Ext.example.msg('Success', res.message || 'Leave request submitted successfully.');
          } else {
            Ext.Msg.alert('Error', (res && res.message) ? res.message : 'Failed to save leave request.');
          }
        },
        failure: function(response) {
          me.form.getEl().unmask();
          var message = 'Server error';
          try {
            var res = Ext.decode(response.responseText);
            if (res && res.message) message = res.message;
          } catch (e) {}
          Ext.Msg.alert('Error', message);
        }
      });
    };
  };
</script>
