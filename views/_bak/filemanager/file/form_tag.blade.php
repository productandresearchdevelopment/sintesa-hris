<script>
    var FormTag = function(){
        let me = Ext.utils.windowForms(this);

        me.timeout = null;

        me.init = function(){
            me.form = Ext.widget('form', {
                bodyPadding: 10,
                autoHeight: true,
                border: false,
                layout: {type: 'vbox', align: 'stretch'},
                fieldDefaults:{labelAlign: 'top', allowBlank: false},
                items : [
                    {xtype: 'hidden', name: '_token', value: '{{ csrf_token() }}'},
                    {xtype: 'hidden', name: 'id', value: ''},
                    {xtype: 'textfield', name: 'tag', fieldLabel: 'Tag'}
                ],
                buttons: [
                    { text: 'Save', cls: 'btn-green', iconCls: 'icon-save-bright', handler: me.save },
                    { text: 'Cancel', cls: 'btn-red', iconCls:'icon-close', handler: me.close }
                ]
            });
            me.createWindowForm('Directory', me.form, {width: 400, maximized: false});
        };

        me.open = function(){
            var rec = gridFile.getRec(true)
            if(rec){
                me.show();
                me.reset();

                me.form.url = '{{ route('filemanager.set.tag') }}';

                me.setField('id', rec.id);
                me.setField('tag', rec.tag);
            }
            else Ext.example.msg('Warning!', 'Please Select Data');
        }

        me.save = function(){
            me.submit(me.form.url, {
                success: function(){
                    gridFile.storeLoad();
                    me.close();
                }
            });
        }
    }

</script>
