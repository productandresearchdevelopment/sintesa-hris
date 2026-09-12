<script>
  var FormsImport = function() {
    let me = Ext.utils.windowForms(this);

    me.init = function() {
      me.form = Ext.widget('form', {
        bodyPadding: '10 20',
        border: false,
        autoHeight: true,
        width: 400,
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
            xtype: 'fieldcontainer',
            id: 'form-import-main',
            layout: {
              type: 'vbox',
              align: 'stretch'
            },
            items: [{
              xtype: 'filefield',
              name: 'file',
              fieldLabel: 'File Excel',
              emptyText: 'Select an excel format',
              listeners: {
                afterrender: function(cmp) {
                  setTimeout(function() {
                    $('#' + cmp.fileInputEl.id).attr("accept",
                      'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'
                    )
                  }, 200);
                }
              }
            }]
          },
          {
            xtype: 'textarea',
            id: 'form-import-response',
            height: 200,
            allowBlank: true,
          }
        ],
        buttons: [{
            text: 'Upload',
            iconCls: 'icon-save-bright',
            cls: 'btn-green',
            handler: me.save
          },
          {
            text: 'Cancel',
            iconCls: 'icon-close',
            cls: 'btn-red',
            handler: me.close
          }
        ]
      });

      me.createWindowForm('Import', me.form, {
        header: true,
        maximized: false
      });
    };

    // FORM METHOD -------------------------------------------------------------------------------------------------
    me.open = function() {
      me.show();
      me.reset();
      Ext.getCmp('form-import-response').hide();
      Ext.getCmp('form-import-main').show();
    }

    me.save = function() {
      const form = me.form.getForm();
      const fileField = form.findField('file').fileInputEl.dom.files[0];

      if (!fileField) {
        Ext.Msg.alert('Error', 'Please select a file to upload.');
        return;
      }

      const formData = new FormData();
      formData.append('_token', '{{ csrf_token() }}');
      formData.append('file', fileField);

      Ext.Ajax.request({
        url: '{{ route('employee.import') }}',
        rawData: formData,
        headers: {
          'Content-Type': null
        },
        success: function(response) {
          Ext.getCmp('form-import-response').show();
          Ext.getCmp('form-import-main').hide();

          let parsedResponse;
          try {
            parsedResponse = Ext.decode(response.responseText);
          } catch (error) {
            Ext.Msg.alert("Error", "Failed to parse server response.");
            return;
          }

          if (parsedResponse && parsedResponse.message) {
            let message = parsedResponse.message;
            let logs = [];

            logs.push('-------------------------------------------');
            logs.push('RESULT');
            logs.push('-------------------------------------------');
            logs.push('TOTAL ROW: ' + message.totalRow);
            logs.push('TOTAL SUCCESS: ' + message.totalSuccess);
            logs.push('TOTAL ERROR: ' + message.totalError);
            logs.push('-------------------------------------------');
            logs.push('ERROR LOG :');
            message.errorLog.forEach(function(e) {
              logs.push('ROW (' + e.row + ') : ' + e.message);
            });

            Ext.getCmp('form-import-response').setValue(logs.join("\n"));
            grids.storeLoad();
            Ext.defer(me.close, 5000);
          } else {
            Ext.Msg.alert("Error", "Unexpected server response format.");
          }
        },
        failure: function(response) {
          Ext.Msg.alert("Error", "File upload failed.");
        }
      });
    };


    me.init();
  }
</script>
