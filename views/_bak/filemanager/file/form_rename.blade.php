<script>
 var FormRename = function() {
  let me = Ext.utils.windowForms(this);

  me.timeout = null;

  me.init = function() {
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
      name: 'id',
      value: ''
     },
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
   me.createWindowForm('Form Rename', me.form, {
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
    store.load();

    setTimeout(function() {
     me.parent.setValue(node);
     me.timeout = null;
    }, 100);
   }, 200);
  }

  me.edit = function() {
   var rec = gridFile.getRec(true)
   if (rec) {
    me.show();
    me.reset();

    me.form.url = '{{ route('filemanager.set.name') }}';

    me.setField('id', rec.id);
    me.setField('name', rec.filename);
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
