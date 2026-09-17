<script>
  var TreeFolder = function() {
    let me = Ext.utils.grids(this);

    me.selectedPeriod = null;
    me.selectedPeriodYear = null;
    me.selectedPeriodSmt = null;
    me.selectedOrganization = null;
    me.selectedOrganizationNode = null;

    me.init = function() {
      me.store = Ext.create('Ext.data.TreeStore', {
        fields: [{
            name: 'id',
            type: 'auto'
          },
          {
            name: 'parent_id',
            type: 'auto'
          },
          {
            name: 'name',
            type: 'string'
          },
          {
            name: 'position_id',
            type: 'string'
          },
          {
            name: 'position',
            type: 'auto'
          },
          {
            name: 'division',
            type: 'auto'
          },
          {
            name: 'division_id',
            type: 'int'
          },
          {
            name: 'company',
            type: 'auto'
          },
          {
            name: 'company_id',
            type: 'int'
          },
          {
            name: 'path',
            type: 'string'
          },
          {
            name: 'text',
            type: 'string'
          },
          {
            name: 'alias',
            type: 'string'
          },
          {
            name: 'authorized1',
            type: 'auto'
          },
          {
            name: 'authorized2',
            type: 'auto'
          },
          {
            name: 'description',
            type: 'string'
          },
          {
            name: 'deleted_at',
            type: 'date'
          }
        ],
        root: {
          id: '0',
          text: 'Root',
          icon: '{{ asset('images/icons/home.png') }}',
          expanded: true
        },
        proxy: {
          type: 'ajax',
          url: '{{ route('organization.data') }}'
        },
        listeners: {
          load: function() {
            me.autoSelectFirstNode();
          }
        }
      });

      me.storePeriod = Ext.create('Ext.data.Store', {
        fields: [
          'id',
          'period',
          'smester',
          {
            name: 'displayPeriod',
            convert: function(v, record) {
              return record.get('period') + ' - (SMT ' + record.get('smester') + ')';
            }
          }
        ],
        proxy: {
          type: 'ajax',
          url: '{{ route('appraisal.period.data') }}',
          reader: {
            root: 'data',
            totalProperty: 'count'
          }
        },
        autoLoad: true,
        listeners: {
          load: function(store) {
            if (store.getCount() > 1) {
              me.showPeriodSelection();
            } else if (store.getCount() === 1) {
              const record = store.getAt(0);
              me.selectedPeriod = record.get('id');
              me.selectedPeriodYear = record.get('period');
              me.selectedPeriodSmt = record.get('smester');
              me.storeLoad();
              if (me.grid) me.grid.show();
              me.autoSelectFirstNode();
            }
          }
        }
      });

      me.showPeriodSelection = function() {
        const periodCombo = Ext.create('Ext.form.ComboBox', {
          store: me.storePeriod,
          queryMode: 'local',
          displayField: 'displayPeriod',
          valueField: 'id',
          editable: false,
          padding: 20,
          listeners: {
            select: function(combo, records) {
              setTimeout(() => {
                const record = records[0];
                me.selectedPeriod = record.get('id');
                me.selectedPeriodYear = record.get('period');
                me.selectedPeriodSmt = record.get('smester');
                me.storeLoad();
                me.grid.show();
                me.autoSelectFirstNode();
                companySelectionWin.close();
              }, 100);
            }
          }
        });

        const companySelectionWin = Ext.create('Ext.window.Window', {
          title: 'Select Period',
          modal: true,
          width: 300,
          layout: 'fit',
          items: [periodCombo],
          closable: false
        });

        companySelectionWin.show();
      };

      me.autoSelectFirstNode = function() {
        if (!me.selectedPeriod) return;
        if (!me.store) return;
        let root = me.store.getRootNode();
        if (!root || !root.hasChildNodes()) return;

        let findFirstOrg = function(node) {
          if (!node) return null;
          let id = node.get('id');
          if (id && id !== '0' && (typeof id !== 'string' || id.indexOf('company_') === -1)) {
            return node;
          }
          if (node.childNodes && node.childNodes.length > 0) {
            for (let i = 0; i < node.childNodes.length; i++) {
              let res = findFirstOrg(node.childNodes[i]);
              if (res) return res;
            }
          }
          return null;
        };

        let target = findFirstOrg(root);
        if (target) {
          if (me.grid && me.grid.getSelectionModel()) {
            me.grid.getSelectionModel().select(target);
          }
          me.handleSelectOrg(target);
        }
      };

      me.handleSelectOrg = function(rec) {
        if (!me.selectedPeriod || !rec) return;
        if (typeof rec.get('id') === 'string' && rec.get('id').indexOf('company_') !== -1) {
          return;
        }

        me.selectedOrganizationNode = rec;
        me.selectedOrganization = rec.get('id');

        if (typeof listTemplate !== 'undefined' && listTemplate.store) {
          if (listTemplate.searchField) listTemplate.searchField.setValue('');
          listTemplate.lastQuery = null;
          listTemplate.store.getProxy().setExtraParam('query', null);
          listTemplate.store.getProxy().setExtraParam('period_year', me.selectedPeriodYear);
          listTemplate.store.getProxy().setExtraParam('period_smt', me.selectedPeriodSmt);
          listTemplate.store.getProxy().setExtraParam('start', 0);
          listTemplate.store.getProxy().setExtraParam('limit', listTemplate.pageSize || 25);
          listTemplate.store.loadPage(1);
        }

        if (typeof http !== "undefined" && http.request) {
          http.request({
            url: '{{ route('appraisal.period.organization.data') }}',
            method: 'GET',
            success: function(response) {
              try {
                const responseData = JSON.parse(response.responseText);
                const filteredData = (responseData.data || []).filter(
                  item =>
                  item.period_id == me.selectedPeriod &&
                  item.organization_id == rec.get('id')
                );

                if (typeof listTemplate !== 'undefined') {
                  listTemplate.selectedTemplate =
                    filteredData.length > 0 ? filteredData[0].template_id : null;

                  if (typeof listTemplate.autoSelectTemplate === "function") {
                    listTemplate.autoSelectTemplate();
                  }
                }
              } catch (error) {
                console.error("Error parsing JSON:", error);
              }
            },
            failure: function() {
              console.error("Failed to fetch appraisal period organization data.");
            }
          });
        }
      };

      me.grid = Ext.create('Ext.tree.Panel', {
        title: 'Organizations',
        region: 'west',
        width: 280,
        split: true,
        rootVisible: false,
        multiSelect: true,
        singleExpand: true,
        border: true,
        store: me.store,
        useArrows: false,
        hideHeaders: false,
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
            text: 'Root',
            dataIndex: 'name',
            xtype: 'treecolumn',
            flex: 1
          },
        ],
        viewConfig: {
          markDirty: false,
          enableTextSelection: true,
          getRowClass: function(rec) {
            return rec.get('deleted_at') ? 'disabled' : '';
          },
          listeners: {
            itemclick: function(obj, rec) {
              me.handleSelectOrg(rec);
            }
          }
        }
      });
    };
  }
</script>
