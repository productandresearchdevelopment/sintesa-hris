<script>
  var Forms = function() {
    let me = Ext.utils.windowForms(this);

    me.data = null;

    me.init = function() {
      me.inputColor = new Ext.inputColor();
      me.categoryStore = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        data: category,
        autoLoad: true,
      }, );

      me.form = Ext.widget('form', {
        bodyPadding: 15,
        width: '100%',
        height: '100%',
        border: false,
        layout: {
          type: 'hbox',
          align: 'stretch'
        },
        scrollable: true,
        submitEmptyText: false,
        fieldDefaults: {
          labelAlign: 'top',
          allowBlank: false
        },
        items: [{
            xtype: 'container',
            layout: {
              type: 'vbox',
              align: 'stretch'
            },
            flex: 4,
            margin: '0 10 0 0',
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
                xtype: 'hidden',
                name: '_method'
              },
              {
                xtype: 'textfield',
                name: 'title',
                fieldLabel: 'Title'
              },
              {
                xtype: 'textfield',
                name: 'description',
                fieldLabel: 'Description',
                allowBlank: true
              },
              {
                xtype: 'combobox',
                name: 'category_id',
                fieldLabel: 'Category',
                store: me.categoryStore,
                queryMode: 'local',
                displayField: 'name',
                valueField: 'id',
                emptyText: 'Select a category',
              },
              {
                xtype: 'checkbox',
                name: 'is_pinned',
                fieldLabel: 'Pinned to Home',
                inputValue: 1,
                uncheckedValue: 0,
                listeners: {
                  change: function(checkbox, newValue, oldValue) {
                    // console.log('Checkbox value:', newValue ? 1 : 0);
                  }
                }
              },
              {
                xtype: 'filefield',
                name: 'file',
                fieldLabel: 'Cover Image',
                allowBlank: true,
                listeners: {
                  afterrender: function(filefield) {
                    Ext.defer(function() {
                      var inputEl = filefield.fileInputEl.dom;
                      inputEl.setAttribute('accept',
                        'image/*');
                    }, 100);
                  },
                  change: function(filefield, value, eOpts) {
                    var file = filefield.fileInputEl.dom.files[0];
                    if (file) {
                      if (!file.type.startsWith('image/')) {
                        Ext.Msg.alert('Error',
                          'Please select a valid image file.');
                        filefield
                          .reset();
                        return;
                      }

                      var reader = new FileReader();
                      reader.onload = function(e) {
                        filefield.up('form').down(
                            'image[name=imagePreview]')
                          .setSrc(e.target.result);
                      };
                      reader.readAsDataURL(file);
                    }
                  }
                }
              },
              {
                xtype: 'image',
                name: 'imagePreview',
                maxWidth: 300,
                maxHeight: 300,
                style: {
                  objectFit: 'contain',
                },
                listeners: {
                  afterrender: function(image) {
                    let imgElement = image.getEl()
                      .dom;
                    imgElement.onload = function() {
                      let naturalWidth = imgElement.naturalWidth;
                      let naturalHeight = imgElement
                        .naturalHeight;
                      let aspectRatio = naturalWidth /
                        naturalHeight;

                      if (naturalWidth > naturalHeight) {
                        image.setWidth(300);
                        image.setHeight(300 / aspectRatio);
                      } else {
                        image.setHeight(300);
                        image.setWidth(300 * aspectRatio);
                      }
                    };
                  }
                }
              },
            ]
          },

          {
            xtype: 'container',
            layout: {
              type: 'vbox',
              align: 'stretch'
            },
            flex: 6,
            items: [
              //HTML EDITOR
              {
                xtype: 'htmleditorwithimage',
                name: 'content',
                fieldLabel: 'Content',
                height: 460,
                enableColors: true,
                enableAlignments: true,
              }
            ]
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

      me.window = Ext.create('Ext.window.Window', {
        layout: 'fit',
        modal: true,
        maximized: true,
        closeAction: 'hide',
        items: [me.form],
        listeners: {
          show: function() {
            me.form.updateLayout();
          }
        }
      });
    };

    // Tampilin form
    me.show = function() {
      if (!me.window) {
        me.init();
      }
      me.window.show();
    };

    // Tutup form
    me.close = function() {
      if (me.window) {
        me.window.hide();
      }
    };

    // Create bulletin form
    me.create = function() {
      me.show();
      me.reset();
      me.data = null;
      me.window.setTitle('Create Bulletin');

      me.form.down('image[name=imagePreview]').setSrc(null);
      me.form.getForm().setValues({
        _method: 'POST'
      });
      me.form.url = '{{ route('bulletin.create') }}';
    };

    // Editing bulletin
    me.edit = function() {
      me.data = null;
      me.window.setTitle('Edit Bulletin');
      var rec = grids.getRec(true);
      if (rec) {
        me.show();
        me.reset();
        me.form.getEl().mask('Loading');
        Ext.Ajax.request({
          method: 'GET',
          url: '{{ route('bulletin.data') }}/' + rec.id,
          success: function(response) {
            me.data = Ext.decode(response.responseText);
            me.editRender(me.data);
            me.form.getEl().unmask();
          },
          failure: function() {
            Ext.Msg.alert('Error', 'Internal Server Error!');
            me.form.getEl().unmask();
            me.close();
          }
        });
      } else {
        Ext.Msg.alert('Warning', 'Please select a record to edit.');
      }
    };

    me.editRender = function(rec) {
      var coverImageId = rec.data.cover_image_id;
      if (coverImageId) {
        var imageUrl = `{{ route('file', '') }}/${coverImageId}`
        me.form.down('image[name=imagePreview]').setSrc(imageUrl);
      } else {
        me.form.down('image[name=imagePreview]').setSrc('');
      }

      me.form.getForm().setValues({
        _method: 'PUT',
        id: rec.data.id,
        title: rec.data.title,
        category_id: rec.data.category ? rec.data.category.id : null,
        content: rec.data.content,
        description: rec.data.description,
        is_pinned: rec.data.is_pinned
      });

      me.form.down('filefield[name=file]').reset();
      me.form.url = '{{ route('bulletin.update') }}';
    };

    me.save = function() {
      var form = me.form.getForm();
      if (!form.isValid()) {
        Ext.Msg.alert('Warning', 'Please complete all required fields.');
        return;
      }
      var values = form.getValues();

      var formData = new FormData();
      formData.append('_token', values._token || '{{ csrf_token() }}');
      formData.append('_method', values._method || 'POST');
      if (values.id) formData.append('id', values.id);
      formData.append('title', values.title || '');
      formData.append('category_id', values.category_id || '');
      formData.append('content', values.content || '');
      if (values.description) formData.append('description', values.description);
      formData.append('is_pinned', values.is_pinned ? 1 : 0);

      var fileField = me.form.down('filefield[name=file]');
      var fileInput = (fileField && fileField.fileInputEl && fileField.fileInputEl.dom && fileField.fileInputEl.dom.files)
        ? fileField.fileInputEl.dom.files[0]
        : null;

      if (fileInput) {
        formData.append('file', fileInput);
      }

      me.form.getEl().mask('Saving...');
      Ext.Ajax.request({
        url: me.form.url,
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
          } else {
            Ext.Msg.alert('Error', (res && res.message) ? res.message : 'Failed to save bulletin.');
          }
        },
        failure: function(response) {
          me.form.getEl().unmask();
          var message = 'Server error';
          try {
            var res = Ext.decode(response.responseText);
            if (res && res.message) message = res.message;
          } catch(e) {}
          Ext.Msg.alert('Error', message);
        }
      });
    };
  };



  Ext.define('BulletinApp.view.HtmlEditorWithImage', {
    extend: 'Ext.form.field.HtmlEditor',
    xtype: 'htmleditorwithimage',

    initComponent: function() {
      var me = this;
      me.callParent(arguments);

      // Ambil toolbar bawaan
      var toolbar = me.getToolbar();

      // Tambahkan tombol 'Insert Image' ke toolbar
      toolbar.add({
        iconCls: 'icon-image',
        text: 'Insert Image',
        tooltip: 'Insert Image',
        handler: function() {
          me.showImageUploadDialog();
        }
      });

      me.on('change', me.wrapContentInDiv, me);
    },

    showImageUploadDialog: function() {
      var me = this;
      var fileInput = document.createElement('input');
      fileInput.type = 'file';
      fileInput.accept = 'image/*';

      fileInput.onchange = function() {
        var file = fileInput.files[0];
        if (file) {
          var reader = new FileReader();
          reader.onload = function(e) {
            me.insertImageAtCursor(e.target.result);
          };
          reader.readAsDataURL(file);
        }
      };
      fileInput.click();
    },

    insertImageAtCursor: function(imageSrc) {
      var me = this;
      var imgTag = '<img src="' + imageSrc +
        '" style="max-width: 60%; height: auto; display: block; margin: 0 auto;" />';
      me.focus();
      me.insertAtCursor(imgTag);
      me.wrapContentInDiv();
    },

    wrapContentInDiv: function() {
      var me = this;
      var editorContent = me.getValue().trim();
      if (editorContent && !editorContent.startsWith('<div>')) {
        me.setValue('<div>' + editorContent + '</div>');
      }
    }
  });
</script>

<style>
  .x-html-editor-tb {
    position: sticky !important;
    top: 0;
    z-index: 1000;
    background-color: #fff;
    /* Menjaga latar belakang tetap putih */
  }
</style>
