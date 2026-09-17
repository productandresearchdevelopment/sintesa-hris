<script>
  var Grids = function() {
    let me = Ext.utils.grids(this);

    me.selected = null;

    me.init = function() {
      me.store = me.httpStore('{{ route('leave.type.data') }}', [{
          name: 'id',
          type: 'int'
        },
        {
          name: 'group',
          type: 'string'
        },
        {
          name: 'name',
          type: 'string'
        },
        {
          name: 'alias',
          type: 'string'
        },
        {
          name: 'color',
          type: 'string'
        },
        {
          name: 'description',
          type: 'string'
        },
        {
          name: 'property',
          type: 'auto'
        },
        {
          name: 'company_id',
          type: 'int'
        },
        {
          name: 'company_name',
          type: 'string'
        },
        {
          name: 'flag_reduce_balance',
          type: 'boolean'
        },
      ]);

      me.menus = Ext.create('Ext.menu.Menu', {
        items: [
          @if ($user->hasRoute('leave.type.push'))
            {
              text: 'Create',
              iconCls: 'icon-add',
              handler: forms.create
            }, {
              text: 'Edit',
              iconCls: 'icon-edit',
              handler: forms.edit
            },
          @endif

          @if ($user->hasRoute('leave.type.delete'))
            {
              text: 'Delete',
              iconCls: 'icon-remove',
              handler: function() {
                let recs = me.getValues();
                if (recs.length) {
                  Ext.ajaxConfirm('Remove Leave Type', {
                    mask: me.grid,
                    url: '{{ route('leave.type.delete') }}',
                    params: {
                      '_method': 'DELETE',
                      '_token': '{{ csrf_token() }}',
                      data: Ext.encode(recs)
                    },
                    success: me.storeLoad
                  });
                } else {
                  Ext.msg.warning('Please select data!');
                }
              }
            },
          @endif
        ]
      });

      me.grid = Ext.create('Ext.grid.Panel', {
        region: 'center',
        store: me.store,
        selType: 'checkboxmodel',
        border: true,
        cls: 'large-grid',
        tbar: me.tbar(me.menus),
        columns: [{
            text: "#",
            dataIndex: 'alias',
            width: 80,
            align: 'center',
            renderer: function(val, meta, rec) {
              let r = rec.data;
              return me.renderBox(r.alias, r.color, r.name, meta);
            }
          },
          @if ($isSuperUser)
            {
              text: "COMPANY",
              dataIndex: 'company_name',
              width: 160,
              renderer: function(val) {
                return val ? val : '<span class="text-muted">GLOBAL</span>';
              }
            },
          @endif
          {
            text: "NAME",
            dataIndex: 'name',
            minWidth: 200
          },
          {
            text: "DESCRIPTION",
            dataIndex: 'description',
            minWidth: 200,
            flex: 1
          },
          {
            text: "REDUCE BALANCE",
            dataIndex: 'flag_reduce_balance',
            width: 150,
            align: 'center',
            renderer: function(val) {
              return val ?
                '<i class="bi bi-check-circle-fill text-success" style="font-size: 1.2em;"></i>' :
                '<i class="bi bi-x-circle-fill text-danger" style="font-size: 1.2em;"></i>';
            }
          },
        ],
        bbar: me.bottomBar([]),
        viewConfig: {
          stripeRows: false,
          listeners: {
            itemcontextmenu: function(obj, rec, node, index, e) {
              e.stopEvent();
              me.selected = rec;
              me.menus.showAt(e.getXY());
            },
            itemclick: function(obj, rec) {
              me.selected = rec;
            },
            itemdblclick: function(obj, rec) {
              me.selected = rec;
            }
          }
        }
      });
    };
  };
</script>
