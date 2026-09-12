<script>
  var FormAnswer = function() {
    let me = Ext.utils.windowForms(this);

    let helpdeskData = Ext.create('Ext.data.Store', {
      fields: ['id', 'title'],
      proxy: {
        type: 'ajax',
        url: '{{ route('helpdesk.data') }}',
        reader: {
          type: 'json',
          root: 'data'
        }
      },
      autoLoad: true
    });

    let userData = Ext.create('Ext.data.Store', {
      fields: ['id', 'name'],
      proxy: {
        type: 'ajax',
        url: '{{ route('auth.user.data') }}',
        reader: {
          root: 'data',
          totalProperty: 'count'
        }
      },
      autoLoad: true,
    });

    me.data = null;

    me.init = function() {

      me.fileInputs = [{
        xtype: 'filefield',
        name: 'files[]',
        fieldLabel: 'File 1',
        buttonText: 'Select File',
        allowBlank: true,
        width: '100%',
        margin: '0 0 10 0'
      }];

      me.fileContainer = Ext.create('Ext.container.Container', {
        layout: {
          type: 'vbox',
          align: 'stretch'
        },
        style: {
          'overflow': 'hidden'
        },
        items: me.fileInputs
      });

      me.messageField = Ext.create('Ext.form.field.TextArea', {
        id: 'message-field-answer',
        name: 'message',
        fieldLabel: 'Message',
        allowBlank: false,
        flex: 1,
        listeners: {
          change: function(field) {
            const textAreaEl = field.getEl().down('textarea');
            if (textAreaEl) {
              const value = textAreaEl.dom.value;
              const cursorPosition = textAreaEl.dom.selectionStart;

              me.handleMention(field, cursorPosition);

              const userIdsInMessage = value.match(/@(\w+)/g)?.map(user => {
                const username = user.slice(1).replace(/\s+/g, '');
                const userDataMatch = userData.data.findBy(record =>
                  record.get('name').replace(/\s+/g, '') === username
                );
                return userDataMatch ? userDataMatch.data.id : null;
              }).filter(id => id);

              me.form.getForm().findField('user_mentions').setValue(JSON.stringify(userIdsInMessage));
            }
          }
        }
      });

      me.userSelectionField = Ext.create('Ext.form.ComboBox', {
        name: 'user_id',
        fieldLabel: 'Select User',
        editable: true,
        allowBlank: true,
        store: userData,
        queryMode: 'local',
        displayField: 'name',
        valueField: 'id',
        hidden: true,
        listeners: {
          select: function(combo, records) {
            const selectedUser = records[0];
            if (selectedUser) {
              me.insertMention(selectedUser.data.name, selectedUser.data.id);
              combo.setValue('');
              combo.hide();
            }
          }
        }
      });

      me.addMoreButton = Ext.create('Ext.button.Button', {
        text: 'Add File',
        cls: 'btn-green',
        iconCls: 'icon-plus-bright',
        handler: function() {
          var fileInputCount = me.fileInputs.length;

          if (fileInputCount < 4) {
            var newFileInput = Ext.create('Ext.container.Container', {
              layout: 'hbox',
              margin: '0 0 10 0',
              items: [{
                  xtype: 'filefield',
                  name: 'files[]',
                  fieldLabel: 'File ' + (fileInputCount + 1),
                  flex: 1,
                  width: '100%'
                },
                {
                  xtype: 'button',
                  text: 'Remove',
                  cls: 'btn-red',
                  margin: '23 0 0 10',
                  handler: function() {
                    me.fileContainer.remove(newFileInput, true);
                    me.fileInputs = me.fileInputs.filter(function(item) {
                      return item !== newFileInput;
                    });
                    me.updateFileLabels();
                    if (me.fileInputs.length < 4) {
                      me.addMoreButton.show();
                    }
                    if (me.fileInputs.length === 0) {
                      me.addInitialFileInput();
                    }
                  }
                }
              ]
            });

            me.fileContainer.add(newFileInput);
            me.fileInputs.push(newFileInput);

            if (me.fileInputs.length >= 4) {
              me.addMoreButton.hide();
            }
          }
        }
      });

      me.addInitialFileInput = function() {
        var initialFileInput = Ext.create('Ext.container.Container', {
          layout: 'hbox',
          margin: '0 0 10 0',
          items: [{
            xtype: 'filefield',
            name: 'files[]',
            fieldLabel: 'File 1',
            flex: 1,
            width: '100%'
          }]
        });

        me.fileContainer.add(initialFileInput);
        me.fileInputs.push(initialFileInput);
        me.updateFileLabels();
      };

      me.resetFileInputs = function() {
        me.fileContainer.removeAll(true);
        me.fileInputs = [];
        me.addInitialFileInput();
        me.addMoreButton.show();
      };

      me.form = Ext.widget('form', {
        bodyPadding: 15,
        width: 800,
        border: false,
        layout: {
          type: 'hbox',
          align: 'stretch'
        },
        submitEmptyText: false,
        fieldDefaults: {
          labelAlign: 'top',
        },
        items: [
          // Column for Helpdesk information
          {
            xtype: 'panel',
            title: 'Helpdesk Information',
            layout: {
              type: 'vbox',
              align: 'stretch'
            },
            flex: 3,
            margin: '0 10 0 0',
            bodyPadding: 10,
            border: true,
            items: [{
                xtype: 'displayfield',
                name: 'titleHelpdesk',
                fieldLabel: 'Title',
                value: me.data ? me.data.title : 'Default Title'
              },
              {
                xtype: 'displayfield',
                name: 'messageHelpdesk',
                fieldLabel: 'Message',
                renderer: function(value) {
                  if (value) {
                    return value.replace(/(@\w+)/g, '<span style="color: #007bff;">$1</span>');
                  } else {
                    return '-';
                  }
                }
              },
              {
                xtype: 'displayfield',
                fieldLabel: 'File',
              },
              {
                xtype: 'container',
                itemId: 'uploadsContainer',
                name: 'uploadsContainer',
                layout: {
                  type: 'vbox',
                  align: 'stretch'
                },
                padding: '10 0 0 0',
                items: []
              }
            ]
          },
          // Column for Form
          {
            xtype: 'panel',
            title: 'Answer Form',
            layout: {
              type: 'vbox',
              align: 'stretch'
            },
            flex: 7,
            border: true,
            bodyPadding: 10,
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
                xtype: 'hidden',
                name: 'user_mentions',
                value: []
              },
              {
                xtype: 'hidden',
                name: 'helpdesk_id',
                value: me.data ? me.data.helpdesk_id : null
              },
              me.messageField,
              me.userSelectionField,
              me.fileContainer,
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
                items: [
                  me.addMoreButton,
                  {
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
          }
        ],
      });

      me.createWindowForm('Helpdesk Form Answer', me.form, {
        maximized: true,
        header: true,

      });
    };

    // Menangani deteksi mention
    me.handleMention = function(field, cursorPosition) {
      const value = field.getValue();
      if (cursorPosition > 0 && value.charAt(cursorPosition - 1) === '@') {
        me.userSelectionField.show();
        me.userSelectionField.focus();
      } else {
        me.userSelectionField.hide();
      }
    };

    // Fungsi untuk memasukkan mention
    me.insertMention = function(userName, userId) {
      const messageField = me.messageField;
      const value = messageField.getValue();
      const cursorPosition = messageField.getEl().down('textarea').dom.selectionStart;

      const formattedUserName = userName.replace(/\s+/g, '');

      messageField.userIds = messageField.userIds || [];

      if (!me.form.getForm().findField('user_mentions').getValue().includes(userId)) {
        const newValue =
          `${value.slice(0, cursorPosition - 1)}@${formattedUserName} ${value.slice(cursorPosition)}`;
        messageField.setValue(newValue);
        messageField.focus();

        const textareaEl = messageField.getEl().down('textarea').dom;
        textareaEl.setSelectionRange(cursorPosition + formattedUserName.length + 1, cursorPosition +
          formattedUserName.length + 1);

        messageField.userIds.push(userId);
      } else {
        messageField.setValue(value.slice(0, cursorPosition - 1) + value.slice(cursorPosition));
        messageField.focus();
      }

      me.userSelectionField.hide();
    };

    me.updateFileLabels = function() {
      Ext.each(me.fileInputs, function(input, index) {
        var fileField = input.down('filefield') || input.down('displayfield');
        if (fileField) {
          fileField.setFieldLabel('File ' + (index + 1));
        }
      });
    };

    me.create = function() {
      var rec = grids.getRec(true);
      if (rec) {
        me.show();
        me.reset();
        me.resetFileInputs();
        me.form.getEl().mask('Loading');

        http.request({
          method: 'get',
          url: '{{ route('helpdesk.get', '') }}/' + rec.id,
          success: function(response) {
            me.data = null;
            let data = Ext.decode(response.responseText);

            // Set values to the form fields
            me.form.getForm().findField('helpdesk_id').setValue(data.id);
            me.form.getForm().findField('titleHelpdesk').setValue(data.title);
            me.form.getForm().findField('messageHelpdesk').setValue(data.message);

            var uploads = data.uploads;
            var uploadsContainer = Ext.ComponentQuery.query('container[itemId=uploadsContainer]')[0];

            if (uploadsContainer) {
              if (uploads && uploads.length > 0) {
                var uploadItems = [];

                uploads.forEach(function(upload) {
                  var fileUrl = '{{ route('file', ['id' => '__id__']) }}'.replace('__id__', upload.id);
                  var fileExtension = upload.filename_origin.split('.').pop().toLowerCase();
                  var fileIcon = '';

                  if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                    uploadItems.push({
                      xtype: 'component',
                      html: `<div class="image-icon text-center">
                   <img src="${fileUrl}" alt="${upload.filename_origin}"
                        style="width: 100px; height: 100px; object-fit: contain;" />
                   <p class="m-0 text-muted">${upload.filename_origin}</p>
               </div>`,
                    });
                  } else {
                    let fileIcon, fileIconColor;

                    if (fileExtension === 'pdf') {
                      fileIcon = 'bi-file-earmark-pdf-fill';
                      fileIconColor = '#e10a0a';
                    } else if (['doc', 'docx'].includes(fileExtension)) {
                      fileIcon = 'bi-file-earmark-word-fill';
                      fileIconColor = '#145adc';
                    } else if (['ppt', 'pptx'].includes(fileExtension)) {
                      fileIcon = 'bi-file-earmark-ppt-fill';
                      fileIconColor = '#ff9000';
                    } else if (fileExtension === 'zip') {
                      fileIcon = 'bi-file-earmark-zip-fill';
                      fileIconColor = '#8100ce';
                    } else {
                      fileIcon = 'bi-file-earmark-code-fill';
                      fileIconColor = '#0075f3';
                    }

                    uploadItems.push({
                      xtype: 'component',
                      html: `<div class="file-icon text-center">
                   <i class="bi ${fileIcon}" style="color: ${fileIconColor}; font-size: 60px"></i>
                   <p class="mt-1 mb-2 text-muted">${upload.filename_origin}</p>
                   <a href="${fileUrl}" target="_blank" class="btn btn-primary">View</a>
               </div>`,
                      style: {
                        margin: '8px'
                      }
                    });
                  }
                });


                uploadsContainer.removeAll();
                uploadsContainer.add(uploadItems);
              } else {
                var uploadsItems = [{
                  xtype: 'component',
                  html: 'No files uploaded',
                }];
                uploadsContainer.removeAll();
                uploadsContainer.add(uploadsItems);
              }
            } else {
              console.error('Uploads container not found');
            }

            me.form.url = '{{ route('helpdesk.answer.store') }}';
            me.form.getForm().findField('_method').setValue('');
            me.form.getEl().unmask();
          },

          failure: function() {
            Ext.Msg.alert('Error', 'Internal Server Error!');
            me.form.getEl().unmask();
            me.close();
          }
        });
      } else {
        Ext.Msg.alert('Warning!', 'Please select data!');
      }
    };

    me.edit = function() {
      me.data = null;
      var rec = grids.getRec(true);
      if (rec) {
        me.show();
        me.reset();
        me.form.getEl().mask('Loading');
        http.request({
          method: 'get',
          url: '{{ route('helpdesk.answer.get') }}/' + rec.id,
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
      me.form.url = '{{ route('helpdesk.answer.update', ':id') }}'.replace(':id', rec.id);
      me.form.getForm().findField('_method').setValue('PUT');

      // Set the values for Helpdesk fields
      me.form.getForm().findField('helpdesk_id').setValue(rec.helpdesk_id);
      me.form.getForm().findField('message').setValue(rec.message);
      me.form.getForm().findField('titleHelpdesk').setValue(rec.helpdesk.title);

      // Handle the file uploads in the edit form
      var uploads = rec.uploads;
      var uploadsContainer = Ext.ComponentQuery.query('container[itemId=uploadsContainer]')[0];

      if (uploadsContainer) {
        if (uploads && uploads.length > 0) {
          var uploadItems = [];

          uploads.forEach(function(upload) {
            var fileUrl = '{{ route('file', ['id' => '__id__']) }}'.replace('__id__', upload.id);
            var fileExtension = upload.filename_origin.split('.').pop().toLowerCase();
            var fileIcon = '';

            if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
              uploadItems.push({
                xtype: 'component',
                html: `<div class="image-icon text-center">
                               <img src="${fileUrl}" alt="${upload.filename_origin}"
                                    style="width: 100px; height: 100px; object-fit: contain;" />
                               <p class="m-0 text-muted">${upload.filename_origin}</p>
                           </div>`,
              });
            } else {
              let fileIcon, fileIconColor;

              if (fileExtension === 'pdf') {
                fileIcon = 'bi-file-earmark-pdf-fill';
                fileIconColor = '#e10a0a';
              } else if (['doc', 'docx'].includes(fileExtension)) {
                fileIcon = 'bi-file-earmark-word-fill';
                fileIconColor = '#145adc';
              } else if (['ppt', 'pptx'].includes(fileExtension)) {
                fileIcon = 'bi-file-earmark-ppt-fill';
                fileIconColor = '#ff9000';
              } else if (fileExtension === 'zip') {
                fileIcon = 'bi-file-earmark-zip-fill';
                fileIconColor = '#8100ce';
              } else {
                fileIcon = 'bi-file-earmark-code-fill';
                fileIconColor = '#0075f3';
              }

              uploadItems.push({
                xtype: 'component',
                html: `<div class="file-icon text-center">
                               <i class="bi ${fileIcon}" style="color: ${fileIconColor}; font-size: 60px"></i>
                               <p class="mt-1 mb-2 text-muted">${upload.filename_origin}</p>
                               <a href="${fileUrl}" target="_blank" class="btn btn-primary">View</a>
                           </div>`,
                style: {
                  margin: '8px'
                }
              });
            }
          });

          uploadsContainer.removeAll();
          uploadsContainer.add(uploadItems);
        } else {
          var uploadsItems = [{
            xtype: 'component',
            html: 'No files uploaded',
          }];
          uploadsContainer.removeAll();
          uploadsContainer.add(uploadsItems);
        }
      } else {
        console.error('Uploads container not found');
      }

      me.fileContainer.removeAll();
      me.fileInputs = [];

      if (record.uploads && record.uploads.length) {
        Ext.each(record.uploads, function(file, index) {
          var fileInput = Ext.create('Ext.container.Container', {
            layout: 'hbox',
            margin: '0 0 10 0',
            items: [{
              xtype: 'displayfield',
              fieldLabel: 'File ' + (index + 1),
              value: file.filename_origin + ' (' + file.extension + ')',
              flex: 1
            }, {
              xtype: 'hidden',
              name: 'existing_files[]',
              value: file.id
            }, {
              xtype: 'button',
              text: 'Remove',
              cls: 'btn-red',
              margin: '23 0 0 10',
              handler: function() {
                me.fileContainer.remove(fileInput, true);
                me.fileInputs = me.fileInputs.filter(function(item) {
                  return item !== fileInput;
                });
                me.updateFileLabels();

                if (me.fileInputs.length < 4) {
                  me.addMoreButton.show();
                }

                if (me.fileInputs.length === 0) {
                  me.addInitialFileInput();
                }
              }
            }]
          });
          me.fileContainer.add(fileInput);
          me.fileInputs.push(fileInput);
        });

        if (me.fileInputs.length >= 4) {
          me.addMoreButton.hide();
        }
      }
    };

    me.save = function() {
      var form = me.form.getForm();
      var values = form.getValues();

      // Mendapatkan ID file yang sudah ada
      var existingFiles = form.getValues().existing_files || [];

      // Mendapatkan file baru
      var newFiles = me.fileInputs.filter(function(input) {
        var fileField = input.down('filefield');
        return fileField && fileField.getValue();
      }).map(function(input) {
        var fileField = input.down('filefield');
        return fileField ? fileField.fileInputEl.dom.files[0] : null;
      }).filter(function(file) {
        return file !== null;
      });

      var formData = new FormData();
      formData.append('_token', values._token);
      formData.append('_method', values._method);
      formData.append('helpdesk_id', values.helpdesk_id);
      formData.append('message', values.message);

      existingFiles.forEach(function(fileId) {
        formData.append('existing_files[]', fileId);
      });

      newFiles.forEach(function(file) {
        formData.append('files[]', file);
      });

      formData.append('user_mentions', JSON.stringify(values.user_mentions));

      me.submit(me.form.url, {
        params: formData,
        success: function(obj, response) {
          grids.storeLoad();
        },
        failure: function(obj, response) {
          Ext.Msg.alert('Error', 'Failed to save data.');
        }
      });
    };
  }
</script>
