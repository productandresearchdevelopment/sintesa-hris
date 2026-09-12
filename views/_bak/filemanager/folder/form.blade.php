<script>
  var FormFolder = function() {
    let me = Ext.utils.windowForms(this);

    me.timeout = null;
    me.parent = null;
    me.init = function() {
      me.parent = Ext.create('Ext.ux.form.field.TreeCombo', {
        name: 'parent_id',
        fieldLabel: 'Parent',
        rootVisible: true,
        canSelectFolders: true,
        editable: false,

        store: Ext.create('Ext.data.TreeStore', {
          folderSort: false,
          autoLoad: false,
          root: {
            id: '0',
            text: 'File Explorer',
            icon: '{{ asset('images/icons/folder.png') }}',
            expanded: true
          },
          proxy: {
            type: 'ajax',
            url: '{{ route('filemanager.folder.data') }}'
          },
        })
      });

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
          allowBlank: false
        },
        items: [{
            xtype: 'hidden',
            name: '_token',
            value: '{{ csrf_token() }}'
          },
          me.parent,
          {
            xtype: 'textfield',
            name: 'name',
            fieldLabel: 'Name'
          },
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

      me.createWindowForm('Directory', me.form, {
        width: 400,
        maximized: false
      });
    };

    me.parentLoad = function(node) {
      node = node || 0;
      let store = me.parent.store;
      store.getRootNode().removeAll();
      if (me.timeout) clearTimeout(me.timeout);
      me.timeout = setTimeout(function() {
        store.proxy.url = '{{ route('filemanager.folder.data') }}';
        store.proxy.extraParams = {
          selected: node
        };
        store.load();

        setTimeout(function() {
          me.parent.setValue(node);
          me.timeout = null;
        }, 100);
      }, 200);
    }

    me.create = function() {
      me.show();
      me.reset();
      me.form.url = '{{ route('filemanager.folder.push') }}';

      var rec = treeFolder.getRec();
      if (rec) {
        me.parentLoad(rec.get('id'));
      }
    }

    me.edit = function() {
      var rec = treeFolder.getRec()
      if (rec && rec.get('id')) {
        me.show();
        me.reset();

        me.form.url = '{{ route('filemanager.folder.push') }}/' + rec.get('id') + '?project=' + rec.get(
          'upload_id');

        me.setField('name', rec.get('name'));
        me.parentLoad(rec.get('parent_id'));
      } else Ext.example.msg('Warning!', 'Please Select Data');
    }

    me.save = function() {
      if (me.getValue('parent_id') == 0) {
        Ext.example.msg('Warning!', "Can't create folder on Root!");
      } else {
        me.submit(me.form.url, {
          success: function() {
            treeFolder.loadFolder(me.getValue('parent_id'));
            me.close();
          }
        });
      }
    }
  }
</script>
