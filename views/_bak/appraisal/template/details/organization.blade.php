<style>
  .selected-node .x-grid-cell {
    font-weight: bold;
    background-color: #d0f0ff !important;
  }
</style>

<script>
  var OrganizationsDetail = function() {
    var me = Ext.utils.grids(this);

    me.selected = null;
    me.group = null;
    me.loading = false;
    me.pendingTimer = null;
    me.requestToken = 0;

    me.init = function() {
      me.store = Ext.create('Ext.data.TreeStore', {
        id: 'store-modules',
        fields: [{
            name: 'id',
            type: 'int'
          },
          {
            name: 'parent_id',
            type: 'int'
          },
          {
            name: 'name',
            type: 'string'
          },
          {
            name: 'created_at',
            type: 'date'
          },
          {
            name: 'updated_at',
            type: 'date'
          },
        ],
        root: {
          id: '0',
          text: 'PT Sintesa Talenta Asia',
          icon: '{{ asset('images/icons/home.png') }}'
        },
        proxy: {
          type: 'ajax',
          url: '{{ route('appraisal.question.template.data.organization', '') }}/'
        },
        listeners: {
          beforeload: function() {
            me.loading = true;
          },
          load: function() {
            me.loading = false;
            me.grid.getEl().unmask();
            if (me.selected) {
              var node = me.grid.store.tree.getNodeById(me.selected);
              if (node) {
                me.grid.expandPath(node.getPath());
                me.grid.selectPath(node.getPath());
              }
            }
          },
          exception: function() {
            me.loading = false;
            me.grid.getEl().unmask();
          }
        }
      });

      me.grid = Ext.create('Ext.tree.Panel', {
        id: 'grid-modules',
        region: 'center',
        title: 'ORGANIZATION',
        rootVisible: false,
        singleExpand: true,
        border: true,
        sortableColumns: false,
        enableColumnHide: false,
        enableColumnMove: false,
        enableLocking: false,
        store: me.store,
        columns: [{
            text: '<img src="{{ asset('images/icons/home.png') }}">',
            dataIndex: 'home',
            width: 35,
            align: 'center',
            renderer: function(val, obj, rec) {
              if (val) return '<img src="{{ asset('images/icons/yes.png') }}">';
            }
          },
          {
            text: 'Name',
            dataIndex: 'name',
            xtype: 'treecolumn',
            flex: 1
          },
        ],

        viewConfig: {
          listeners: {
            itemclick: me.setSelected,
            checkchange: me.setAuth,
          }
        }
      })
    }

    me.setSelected = function(obj, rec) {
      me.selected = rec.get('id');
    }

    me.setAuth = function() {
      var template = grids.getRec(true);
      var rec = me.getRec(true);
      if (rec && template) {
        http.request({
          method: 'GET',
          url: '{{ route('appraisal.period.data') }}',
          success: function(response) {
            try {
              let periods = JSON.parse(response.responseText);
              let periodList = periods.data || [];
              let period = periodList.find(p =>
                String(p.smester) === String(template.period_smt) &&
                String(p.period) === String(template.period_year) &&
                (!template.division?.company_id || !p.company_id || String(p.company_id) === String(template.division.company_id))
              ) || periodList.find(p =>
                String(p.smester) === String(template.period_smt) &&
                String(p.period) === String(template.period_year)
              );

              if (!period) {
                Ext.example.msg('Warning!', 'Period not found for year ' + template.period_year + ' SMT ' + template.period_smt);
                return;
              }
              let period_id = period.id;

              http.request({
                method: 'post',
                url: '{{ route('appraisal.question.template.set.organization', '') }}/' + template.id,
                params: {
                  period: period_id,
                  organization: rec.id,
                  auth: (rec.checked ? 1 : 0),
                  '_method': 'PUT',
                  '_token': '{{ csrf_token() }}',
                },
                success: function() {
                  Ext.example.msg('Success', 'Organization assignment updated successfully.');
                },
                failure: function() {
                  Ext.example.msg('Failed!', 'Failed to update organization assignment.');
                  me.storeLoad();
                }
              });
            } catch (error) {
              console.error("Error parsing period data:", error);
            }
          },
          failure: function() {
            console.error("Failed to fetch period data.");
            Ext.example.msg("Error!", "Failed to fetch period data.");
          }
        });
      } else {
        Ext.example.msg('Warning!', 'Please Select Data!');
      }
    }

    me.storeLoad = function() {
      if (me.pendingTimer) {
        clearTimeout(me.pendingTimer);
        me.pendingTimer = null;
      }

      me.pendingTimer = setTimeout(function() {
        me.pendingTimer = null;

        var rec = grids.getRec(true);
        me.group = rec;
        if (!rec) return;

        if (me.loading) {
          me.pendingTimer = setTimeout(arguments.callee, 200);
          return;
        }

        me.requestToken++;
        var currentToken = me.requestToken;

        me.grid.getEl().mask('Proses');
        me.store.proxy.url = '{{ route('appraisal.question.template.data.organization', '') }}/' + rec.id;
        me.store.getRootNode().removeAll();
        me.store.load({
          callback: function() {
            if (currentToken !== me.requestToken) {
              me.store.getRootNode().removeAll();
            }
          }
        });
      }, 500);
    }
  };
</script>
