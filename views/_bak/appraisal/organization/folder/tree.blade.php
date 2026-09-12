<script>
  var TreeFolder = function() {
    let me = Ext.utils.grids(
      this);

    me.selectedPeriod = null;
    me.selectedPeriodYear = null;
    me.selectedPeriodSmt = null;
    me.selectedOrganization = null;

    me.init = function() {
      me.store = Ext.create('Ext.data.TreeStore', {
        fields: [{
            name: 'id',
            type: 'int'
          },
          {
            name: 'parent_id',
            type: 'int'
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
          id: 0,
          name: 'PT Qualita Indonesia',
          icon: '{{ asset('images/icons/home.png') }}',
          expanded: true
        },
        proxy: {
          type: 'ajax',
          url: '{{ route('organization.data') }}'
        },
        listeners: {}
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

      me.grid = Ext.create('Ext.tree.Panel', {
        title: 'Organizations',
        region: 'west',
        width: 280,
        split: true,
        rootVisible: true,
        multiSelect: true,
        singleExpand: true,
        border: true,
        store: me.store,
        useArrows: false,
        hideHeaders: true,
        columns: [{
          dataIndex: 'text',
          xtype: 'treecolumn',
          flex: 1
        }],
        viewConfig: {
          markDirty: false,
          enableTextSelection: true,
          getRowClass: function(rec) {
            return rec.get('deleted_at') ? 'disabled' : '';
          },
          listeners: {
            itemclick: function(obj, rec) {
              if (!me.selectedPeriod) return;

              me.selectedOrganization = rec.get('id');

              if (listTemplate && listTemplate.store) {
                if (listTemplate.searchField) listTemplate.searchField.setValue('');
                listTemplate.lastQuery = null;
                listTemplate.store.getProxy().setExtraParam('query', null);
                listTemplate.store.getProxy().setExtraParam('period_year', me.selectedPeriodYear);
                listTemplate.store.getProxy().setExtraParam('period_smt', me.selectedPeriodSmt);
                listTemplate.store.getProxy().setExtraParam('start', 0);
                listTemplate.store.getProxy().setExtraParam('limit', listTemplate.pageSize || 25);
                listTemplate.store.loadPage(1);
              } else {
                console.error("listTemplate.store is not defined!");
              }

              if (typeof http !== "undefined" && http.request) {
                http.request({
                  url: '{{ route('appraisal.period.organization.data') }}',
                  method: 'GET',
                  success: function(response) {
                    try {
                      const responseData = JSON.parse(response.responseText);
                      const filteredData = responseData.data.filter(
                        item =>
                        item.period_id == me.selectedPeriod &&
                        item.organization_id == rec.get('id')
                      );

                      if (listTemplate) {
                        listTemplate.selectedTemplate =
                          filteredData.length > 0 ? filteredData[0].template_id : null;

                        if (typeof listTemplate.autoSelectTemplate === "function") {
                          listTemplate.autoSelectTemplate();
                        } else {
                          console.error("listTemplate.autoSelectTemplate is not defined!");
                        }
                      } else {
                        console.error("listTemplate is not defined!");
                      }
                    } catch (error) {
                      console.error("Error parsing JSON:", error);
                    }
                  },
                  failure: function() {
                    console.error("Failed to fetch appraisal period organization data.");
                  }
                });
              } else {
                console.error("http.request is not defined!");
              }
            }
          }
        }
      });
    };
  }
</script>
