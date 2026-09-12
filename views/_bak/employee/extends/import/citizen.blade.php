<script>
  var FormsImportCitizen = function() {
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
            id: 'form-import-main-citizen',
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
            id: 'form-import-response-citizen',
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


    me.open = function() {
      me.show();
      me.reset();
      Ext.getCmp('form-import-response-citizen').hide();
      Ext.getCmp('form-import-main-citizen').show();
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
      formData.append('preview_only', true);

      Ext.Ajax.request({
        url: '{{ route('employee.import.citizen') }}',
        rawData: formData,
        headers: {
          'Content-Type': null
        },
        success: function(response) {
          let parsedResponse;
          try {
            parsedResponse = Ext.decode(response.responseText);
          } catch (error) {
            Ext.Msg.alert("Error", "Failed to parse server response.");
            return;
          }

          if (parsedResponse.success && parsedResponse.data) {
            if (typeof forms.citizenInfo !== 'undefined' && forms.citizenInfo.loadImportedData) {
              forms.citizenInfo.loadImportedData(parsedResponse.data);

              Ext.getCmp('form-import-response-citizen').show();
              Ext.getCmp('form-import-main-citizen').hide();

              let logs = [];
              logs.push('-------------------------------------------');
              logs.push('IMPORT PREVIEW');
              logs.push('-------------------------------------------');
              logs.push('TOTAL ROWS: ' + parsedResponse.data.length);
              logs.push('-------------------------------------------');
              logs.push('Data berhasil di-load ke grid.');
              logs.push('Silakan cek data di grid dan klik Save jika sudah benar.');

              Ext.getCmp('form-import-response-citizen').setValue(logs.join("\n"));
            } else {
              Ext.Msg.alert("Error", "Form Citizen tidak tersedia.");
            }
          } else if (parsedResponse.error) {
            Ext.getCmp('form-import-response-citizen').show();
            Ext.getCmp('form-import-main-citizen').hide();

            let logs = [];
            logs.push('-------------------------------------------');
            logs.push('ERROR');
            logs.push('-------------------------------------------');
            if (parsedResponse.message) {
              logs.push(parsedResponse.message);
            }
            if (parsedResponse.errorLog) {
              logs.push('-------------------------------------------');
              logs.push('ERROR LOG:');
              parsedResponse.errorLog.forEach(function(e) {
                logs.push('ROW (' + e.row + ') : ' + e.message);
              });
            }

            Ext.getCmp('form-import-response-citizen').setValue(logs.join("\n"));
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
