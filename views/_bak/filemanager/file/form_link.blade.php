<script>
 var FormLink = function() {
  let me = Ext.utils.windowForms(this);

  me.timeout = null;

  me.init = function() {
   me.parent = Ext.create('Ext.ux.form.field.TreeCombo', {
    name: 'folder_id',
    fieldLabel: 'Folder',
    rootVisible: true,
    canSelectFolders: true,
    editable: false,
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
     {
      xtype: 'hidden',
      name: '_method',
      value: 'POST'
     },
     me.parent,
     {
      xtype: 'textfield',
      name: 'name',
      fieldLabel: 'Name'
     },
     {
      xtype: 'textfield',
      name: 'link',
      fieldLabel: 'Link',
      emptyText: 'https://example.com/file.pdf'
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
   me.createWindowForm('Form New Link', me.form, {
    width: 400,
    maximized: false
   });
  };

  me.parentLoad = function(node) {
   node = node || 0;
   let store = me.parent.store;
   store.getRootNode().removeAll();
   if (me.timeoutArea) clearTimeout(me.timeoutArea);
   me.timeout = setTimeout(function() {
    store.proxy.url = '{{ route('filemanager.folder.data') }}';
    store.proxy.extraParams = {
     selected: node
    };
    store.load({
        callback: function(records) {
            if (node) me.parent.setValue(node);
        }
    });
    me.timeout = null;
    }, 200);
  };

  me.create = function() {
   me.show();
   me.reset();
   me.form.url = '{{ route('filemanager.push.link') }}';
   me.form.getForm().findField('_method').setValue('');

   var rec = treeFolder.getRec();
   if (rec) me.parentLoad(rec.get('id'));
  }

  me.edit = function() {
   var rec = gridFile.getRec(true)
   if (rec) {
    me.show();
    me.reset();

    me.form.url = '{{ route('filemanager.update.link', ':id') }}'.replace(':id', rec.id);
    me.form.getForm().findField('_method').setValue('PUT');

    me.setField('name', rec.name);
    me.setField('link', rec.link);
    me.parentLoad(rec.folder_id);
   } else Ext.example.msg('Warning!', 'Please Select Data');
  }

  me.save = function() {
   me.submit(me.form.url, {
    success: function() {
     gridFile.storeLoad();
     me.close();
    }
   });
  }
 }
</script>
