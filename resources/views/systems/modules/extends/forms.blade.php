<script>
    var Forms = function(){
        let me = Ext.utils.windowForms(this);

        me.init = function(){
            me.form = Ext.widget('form', {
                bodyPadding: '10 15 10 15',
                autoHeight: true,
                border: false,
                fieldDefaults: {labelAlign: 'left', labelWidth: 80, msgTarget: 'side'},
                defaults: {anchor: '100%'},
                items: [
                    {xtype: 'hidden', name: 'type_id'},
                    {xtype: 'hidden', name: '_method'},
                    {xtype: 'hidden', name: '_token', value: '{{ csrf_token() }}'},

                    {xtype: 'textfield', fieldLabel : 'Title', name: 'text'},

                    Ext.create('Ext.ux.form.field.TreeCombo',{
                        name: 'parent', fieldLabel: 'Parent',
                        rootVisible: true, canSelectFolders: true, editable: false,
                        store: Ext.create('Ext.data.TreeStore',{
                            folderSort: false,
                            root: {id: '0', text: 'Root', icon: '{{ asset('images/icons/home.png') }}', expanded: true},
                            proxy: {type: 'ajax', url: '{{ route('auth.module.data') }}'},
                        })
                    }),

                    {xtype: 'textfield', fieldLabel : 'Url', name: 'url'},

                    {
                        xtype: 'combo', name: 'route', fieldLabel : 'Route',
                        forceSelection: true, editable: true, queryMode: 'local', triggerAction: 'all',
                        displayField: 'id', valueField: 'id',
                        store: Ext.create('Ext.data.Store', {
                            fields : ['id', 'url', 'prefix', 'method', 'action'],
                            data : dataRoutes
                        })
                    },

                    {xtype: 'textfield', fieldLabel : 'Param', name: 'param'},

                    {xtype: 'textfield', name: 'auth', fieldLabel : 'Auth'},

                    {xtype: 'textfield', name: 'icon', fieldLabel : 'Icon'},

                    {
                        xtype: 'combo', name: 'device', fieldLabel : 'Device',
                        forceSelection: true, editable: false, queryMode: 'local', triggerAction: 'all',
                        displayField: 'name', valueField: 'id',
                        store: Ext.create('Ext.data.Store', {
                            fields : [{name: 'id', type: 'int'}, {name: 'name', type: 'string'}],
                            data : [
                                {id: 0, name: 'All Device'},
                                {id: 2, name: 'Desktop Only'},
                                {id: 1, name: 'Mobile Only'},
                            ]
                        })
                    },

                    {xtype: 'textfield', fieldLabel : 'Description', name: 'description'},
                    {
                        xtype: 'checkboxgroup', columns: 2, margin : '0 0 0 80',
                        items: [
                            {xtype: 'checkbox', name: 'is_active', boxLabel: 'Active', inputValue: 1, checked: true},
                            {xtype: 'checkbox', name: 'is_locked', boxLabel: 'Locked', inputValue: 1, checked: true},
                        ]
                    }
                ],
                buttons: [
                    {text: 'Save', iconCls:'icon-save-bright', handler: me.save},
                    {text: 'Cancel', iconCls:'icon-cancel', handler: me.close}
                ]
            });

            me.createWindowForm('Form', me.form, {maximized: false, header: true, title: "Create Modules", width: 400});

        }

        me.setup = function (type, title){
            me.show();
            switch(parseInt(type)){
                @foreach ($types as $type)
                case {{ $type->id }} :
                    me.win.setIconCls('icon-{{ $type->icon }}');
                    me.setTitle('&nbsp; '+title+' {{ $type->name }}');
                    me.getField('url').{{ $type->xurl ? 'show()' : 'hide()' }};
                    me.getField('route').{{ $type->xroute ? 'show()' : 'hide()' }};
                    me.getField('param').{{ $type->xroute ? 'show()' : 'hide()' }};
                    me.getField('auth').{{ $type->xauth ? 'show()' : 'hide()' }};
                    me.getField('device').{{ $type->xdevice ? 'show()' : 'hide()' }};
                    me.getField('icon').{{ $type->xicon ? 'show()' : 'hide()' }};
                    break;
                @endforeach
            }
            me.reset();

            let rec = grids.getRec(true);
            let parent = rec ? rec.id : 0;
            let store = me.getField('parent').store;
            store.proxy.extraParams.selected = parent;
            store.load();
            me.setField('parent',parent);
        }

        me.create = function(menu){
            var form  = me.form.getForm();
            var rec = grids.getRec(true);

            me.setup(menu.value, 'Create');
            me.form.url = '{{ route('auth.module.create') }}';
            me.setField('type_id', menu.value);
        }

        me.edit = function(){
            var rec = grids.getRec(true);
            if(rec){
                me.setup(rec.type_id, 'Edit');

                me.form.url = '{{ route('auth.module.update', '') }}/' + rec.id;

                me.setField('_method', 'PUT');
                me.setField('type_id', rec.type_id);
                me.setField('parent', rec.parent ? rec.parent : '0');
                me.setField('text', rec.text);
                me.setField('icon', rec.menuIcon);
                me.setField('route', rec.route);
                me.setField('param', rec.param);
                me.setField('url', rec.url);
                me.setField('is_active', rec.is_active);
                me.setField('is_locked', rec.is_locked);
                me.setField('auth', rec.auth);
                me.setField('device', rec.device);
                me.setField('description', rec.description);
            }
            else Ext.example.msg('Warning', 'Please Select Data!');
        }

        me.save = function(){
            Ext.MessageBox.confirm('Confirm', 'Save data module?', function(res){
                if(res=='yes'){
                    me.submit(me.form.url, {
                        success: function (){
                            grids.store.proxy.extraParams.selected = me.getValue('parent');
                            grids.storeLoad();
                            me.close();
                        }
                    });
                }
            });
        }

    }

</script>
