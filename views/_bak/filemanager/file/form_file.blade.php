<script>
var FormFile = function() {
    let me = Ext.utils.windowForms(this);

    me.timeout = null;

    me.init = function() {
        me.parent = Ext.create('Ext.ux.form.field.TreeCombo', {
            name: 'folder_id',
            fieldLabel: 'Folder',
            rootVisible: true,
            canSelectFolders: true,
            editable: false,
            value: '{{ $parentFolder->id }}',
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

        // Tambahkan field untuk nama file
        me.fileNameField = Ext.create('Ext.form.field.Text', {
            name: 'file_name',
            fieldLabel: 'File Name (Optional)',
            allowBlank: true
        });

        me.fileField = Ext.create('Ext.form.field.File', {
            name: 'file',
            fieldLabel: 'File',
            allowBlank: false // File wajib diisi
        });

        // Tambahkan listener untuk mengisi otomatis fileNameField
        me.fileField.on('change', function(field, value) {
            let fileName = value.split('\\').pop(); // Ambil nama file saja
            me.fileNameField.setValue(fileName); // Isi fileNameField dengan nama file
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
            me.fileNameField, // Tambahkan field nama file ke dalam form
            me.fileField
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
            }]
        });
        me.createWindowForm('Form New File', me.form, {
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
    };

    me.create = function() {
        me.show();
        me.reset();
        me.form.url = '{{ route('filemanager.push.file') }}';

        var rec = treeFolder.getRec();
        if (rec) me.parentLoad(rec.get('id'));
    };

    me.save = function() {
        me.submit(me.form.url, {
            success: function() {
                gridFile.storeLoad();
                me.close();
            }
        });
    };
};

</script>
