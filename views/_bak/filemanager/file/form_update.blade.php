<script>
  var FormUpdate = function() {
    let me = Ext.utils.windowForms(this);

    me.timeout = null;

    me.init = function() {
      me.parent = Ext.create('Ext.ux.form.field.TreeCombo', {
        name: 'folder_id',
        fieldLabel: 'Folder',
        rootVisible: true,
        canSelectFolders: true,
        editable: false,
        allowBlank: false,
        store: Ext.create('Ext.data.TreeStore', {
          folderSort: false,
          autoLoad: false,
          root: {
            id: '{{ $parentFolder->id }}',
            text: '{{ $parentFolder->name }}',
            icon: '{{ asset('images/icons/desktop.png') }}',
            expanded: true
          },
          proxy: {
            type: 'ajax',
            url: '{{ route('filemanager.folder.data') }}'
          },
          listeners: {
            load: function(store) {
              if (store.getCount() > 0) {
                let rootNode = store.getRootNode();
                me.parent.setValue(rootNode.getId());
              }
            }
          }
        })
      });

      me.parent.setValue('{{ $parentFolder->id }}');

      me.form = Ext.widget('form', {
        bodyPadding: 10,
        autoHeight: true,
        border: false,
        layout: {
          type: 'vbox',
          align: 'stretch'
        },
        fieldDefaults: {
          labelAlign: 'top',
        },
        items: [{
            xtype: 'hidden',
            name: '_token',
            value: '{{ csrf_token() }}'
          },
          {
            xtype: 'hidden',
            name: 'file_ids',
            value: '' // Untuk menampung file ID yang akan dipindahkan
          },
          me.parent
        ],
        buttons: [{
            text: 'Save',
            cls: 'btn-green',
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

      me.createWindowForm('Form Move Folder', me.form, {
        width: 400,
        maximized: false
      });
    };

    me.edit = function() {
      var recs = gridFile.getValues();
      if (recs.length > 0) {
        me.show();
        me.reset();

        var fileIds = recs;
        me.form.getForm().findField('file_ids').setValue(fileIds.join(
          ','));

      } else {
        Ext.example.msg('Warning!', 'Please Select Data');
      }
    }

    me.save = function() {
      var form = me.form.getForm();
      if (form.isValid()) {
        form.submit({
          url: '{{ route('filemanager.update.file') }}',
          method: 'PUT',
          params: {
            '_method': 'PUT',
            '_token': '{{ csrf_token() }}',
            'file_ids': form.findField('file_ids').getValue(),
            'folder_id': form.findField('folder_id').getValue()
          },
          success: function(form, action) {
            var result = action.result;
            if (result.success) {
              gridFile.storeLoad();
              Ext.example.msg('Success', result.message);
              me.close();
            } else {
              Ext.example.msg('Error', result.message);
            }
          },
          failure: function(form, action) {
            Ext.example.msg('Error', 'Failed to move files.');
          }
        });
      } else {
        Ext.example.msg('Error', 'Please select the destination folder.');
      }
    };
  }
</script>
