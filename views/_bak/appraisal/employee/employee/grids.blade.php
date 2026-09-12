    <script>
      var Grids = function() {
        let me = Ext.utils.grids(this);

        me.selected = null;

        me.init = function() {
          me.store = Ext.create('Ext.data.Store', {
            fields: [{
                name: 'id',
                type: 'auto'
              },
              {
                name: 'org_id',
                type: 'int'
              },
              {
                name: 'organization',
                type: 'auto'
              },
              {
                name: 'division_id',
                type: 'int'
              },
              {
                name: 'division',
                type: 'auto'
              },
              {
                name: 'company_id',
                type: 'int'
              },
              {
                name: 'company',
                type: 'auto'
              },
              {
                name: 'placement_id',
                type: 'int'
              },
              {
                name: 'placement',
                type: 'auto'
              },
              {
                name: 'nik',
                type: 'string'
              },
              {
                name: 'nickname',
                type: 'string'
              },
              {
                name: 'fullname',
                type: 'string'
              },
              {
                name: 'evaluator1',
                type: 'auto'
              },
              {
                name: 'evaluator2',
                type: 'auto'
              },
              {
                name: 'last_contract',
                type: 'auto'
              },
              {
                name: 'contracts',
                type: 'auto'
              },
              {
                name: 'last_career',
                type: 'auto'
              },
              {
                name: 'careers',
                type: 'auto'
              },
              {
                name: 'deleted_at',
                type: 'date'
              }
            ],
            proxy: {
              type: 'ajax',
              url: '{{ route('appraisal.employee.data.employee') }}',
              extraParams: {
                group: 'position'
              },
              reader: {
                root: 'data',
                totalProperty: 'count'
              }
            },
          });

          me.grid = Ext.create('Ext.grid.Panel', {
            title: 'Employee',
            region: 'center',
            store: me.store,
            border: true,
            cls: 'large-grid',
            tbar: [
              @if ($user->hasRoute('appraisal.employee.export.excel'))
                {
                  text: 'Export Excel',
                  iconCls: 'icon-excel',
                  handler: function() {
                    let filters = me.store.proxy.extraParams;
                    let query = '';
                    if (me.store.filters.items.length) query = me.store.filters.items[0].value;
                    filters.query = query;

                    let params = [];
                    for (var key in filters) {
                      var value = filters[key];
                      params.push(key + '=' + encodeURIComponent(value));
                    }

                    window.location = '{{ route('appraisal.employee.export.excel') }}?' + params.join(
                      '&');
                  }
                },
              @endif
              '->', {
                xtype: 'searchfield',
                flex: 1,
                maxWidth: 300,
                minWidth: 180,
                store: me.store
              }
            ],
            columns: [{
                text: 'ID EMPLOYEE',
                dataIndex: 'nik',
                flex: 1
              },
              {
                text: 'Name',
                dataIndex: 'fullname',
                flex: 1
              },
              {
                text: 'POSITION',
                dataIndex: 'organization',
                flex: 1,
                renderer: function(val) {
                  return val ? val.name : '-';
                }
              },
              {
                text: "Contract",
                dataIndex: 'last_contract',
                minWidth: 200,
                renderer: function(val) {
                  return val ? val.status?.name : '-';
                }
              },
              {
                text: "Career",
                dataIndex: 'last_career',
                minWidth: 200,
                renderer: function(val) {
                  return val ? val.career?.name : '-';
                }
              },
            ],
            bbar: me.bottomBar(
              [{
                xtype: 'filter',
                id: 'trash',
                name: 'Trash',
                param: 'trash',
                iconCls: 'icon-trash',
                items: [{
                    id: 1,
                    name: 'ACTIVE'
                  },
                  {
                    id: 2,
                    name: 'TRASH'
                  }
                ]
              }]),
            viewConfig: {
              stripeRows: false,
              getRowClass: function(rec) {
                if (rec.get('deleted_at')) return 'disabled';
                return '';
              },
              listeners: {
                itemclick: function(obj, rec) {
                  if (!rec.get('deleted_at')) {
                    me.selected = rec;
                  }
                },
                itemdblclick: function(obj, rec) {
                  if (!rec.get('deleted_at')) {
                    me.selected = rec;
                    if (typeof forms !== "undefined" && typeof forms.detail === "function") {
                      forms.detail(rec.data);
                    } else {
                      console.error("forms.detail is not defined");
                    }
                  }
                }
              },
              emptyText: '<div style="text-align:center;padding:20px;">No data available, choose organization first</div>',
              deferEmptyText: false,
            }
          });

        };
      };
    </script>


    <style>
      .deleted_item {
        background-color: #f8f9fa;
        color: #6c757d;
        opacity: 0.6;
      }

      .appraisal-checkbox input[type="checkbox"],
      .appraisal-item {
        cursor: pointer;
      }
    </style>
