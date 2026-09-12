<style>
    .cellediting tr.x-grid-row.x-grid-row-over  td{background-color: transparent;}
</style>

<script>
    var Roles = function(){
        let me = Ext.utils.grids(this);

        me.init = function(){
            me.store = Ext.create('Ext.data.Store', {
                data: dataRoles,
                fields: [
                    {name: 'id', type: 'int'},
                    {name: 'name', type: 'string'},
                    {name: 'alias', type: 'string'},
                    {name: 'home', type: 'int'},
                    {name: 'color', type: 'string'},
                    {name: 'description', type: 'string'},
                    {name: 'checked', type: 'bool'},
                ]
            });

            me.grid  = Ext.create('Ext.grid.Panel', {
                title: 'ROLES',
                selType: 'checkboxmodel',
                border: true,
                store: me.store,
                tbar: [
                    '->',
                    {xtype: 'button', iconCls: 'icon-save-dark', text: 'Save', handler: me.save},
                    {xtype: 'button', iconCls: 'icon-refresh', text: 'Reset', handler: me.reset},
                ],
                columns: [
                    {
                        text: 'Role', dataIndex: 'name', flex: 1,
                        renderer: function(val, meta, rec){
                            return '<span style="color: #'+rec.get('color')+'; font-size: 12px; font-weight: 600">'+val+'</span>';
                        }
                    },
                ],
                viewConfig: {stripeRows: false}
            });
        }

        me.save = function(){
            var rec = grids.getRec(true);
            if(rec) {
                var data = me.getValues();
                http.request({
                    method: 'post',
                    url: '{{ route('auth.module.update.roles','') }}/' + rec.id,
                    params: {
                        _method: 'PUT',
                        _token: '{{ csrf_token() }}',
                        roles: Ext.encode(data)
                    },
                    success: function (obj, r) {
                        grids.storeLoad();
                        Ext.example.msg('Update Roles', 'Roles Has Update !');
                    }
                });
            }
        }

        me.deselectAll = function(){
            me.grid.getSelectionModel().deselectAll();
        }

        me.reset = function (){
            setTimeout(function (){
                me.deselectAll();
                var rec = grids.getRec(true);
                if(rec) {
                    dataRoles.forEach(function (e, i) {
                        if (find(rec.roles, e.id)) {
                            me.grid.getSelectionModel().select(i, true);
                        }
                    });
                }
            }, 100);
        }

    }
</script>
