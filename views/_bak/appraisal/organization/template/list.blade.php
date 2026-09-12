<script>
  var ListTemplate = function() {
    let me = Ext.utils.grids(this);
    me.selectedTemplate = null;
    me.pageSize = 25;
    me.totalCount = 0;
    me.isLoadingMore = false;
    let searchTimer = null;

    me.init = function() {
      me.store = Ext.create('Ext.data.Store', {
        pageSize: me.pageSize,
        fields: ['id', 'title'],
        proxy: {
          type: 'ajax',
          url: '{{ route('appraisal.question.template.data') }}',
          extraParams: {
            group: 'position'
          },
          reader: {
            root: 'data',
            totalProperty: 'count'
          }
        },
        listeners: {
          load: function(store, records, successful) {
            me.isLoadingMore = false;
            if (successful && store.getProxy().getReader().rawData) {
              me.totalCount = store.getProxy().getReader().rawData.count || store.getTotalCount() || 0;
            }
          }
        }
      });

      me.autoSelectTemplate = function() {
        let radioButtons = document.querySelectorAll('.template-radio');
        if (me.selectedTemplate) {
          let selectedItem = document.querySelector(`.template-item[data-id="${me.selectedTemplate}"]`);
          if (selectedItem) {
            let radio = selectedItem.querySelector('.template-radio');
            if (radio) {
              radio.checked = true;
              selectedItem.classList.add('selected');
            }
          }
        } else {
          radioButtons.forEach(radio => {
            radio.checked = false;
          });

          document.querySelectorAll('.template-item').forEach(el => el.classList.remove('selected'));
        }
      };

      me.lastQuery = null;

      me.searchField = Ext.create('Ext.form.field.Text', {
        emptyText: 'Search template...',
        enableKeyEvents: true,
        width: '50%',
        cls: 'search-field',
        listeners: {
          keyup: {
            fn: function(field, e) {
              let key = e.getKey();
              if (e.isNavKeyPress() && key !== e.BACKSPACE && key !== e.DELETE) {
                return;
              }
              let query = field.getValue().trim();
              if (query === me.lastQuery) {
                return;
              }
              me.lastQuery = query;

              let proxy = me.store.getProxy();
              if (query !== '') {
                proxy.setExtraParam('query', query);
              } else {
                proxy.setExtraParam('query', null);
              }
              proxy.setExtraParam('start', 0);
              proxy.setExtraParam('limit', me.pageSize);

              me.isLoadingMore = false;
              me.totalCount = 0;
              me.store.loadPage(1);
            },
            buffer: 600
          }
        }
      });

      me.updateEmptyText = function() {
        let listViewEl = me.listView ? me.listView.getEl() : null;
        if (listViewEl && (!me.store || me.store.getCount() === 0)) {
          let msg = (typeof treeFolder !== "undefined" && treeFolder.selectedOrganization) ?
            'No template found' :
            'No data available, choose organization first';
          listViewEl.setHTML(
            '<div style="text-align:center;padding:20px;color:#999;">' + msg + '</div>'
          );
        }
      };

      me.loadMore = function() {
        if (me.isLoadingMore) return;
        let currentCount = me.store.getCount();
        if (me.totalCount && currentCount >= me.totalCount) return;

        me.isLoadingMore = true;
        let proxy = me.store.getProxy();
        proxy.setExtraParam('start', currentCount);
        proxy.setExtraParam('limit', me.pageSize);

        me.store.load({
          addRecords: true,
          params: {
            start: currentCount,
            limit: me.pageSize
          },
          callback: function(records, operation, success) {
            me.isLoadingMore = false;
          }
        });
      };

      me.bindScrollListener = function() {
        let el = me.listView ? me.listView.getEl() : null;
        if (!el || !el.dom) return;

        if (!el.dom._scrollBound) {
          el.dom._scrollBound = true;
          el.dom.addEventListener('scroll', function(e) {
            let target = e.target;
            if (!target) return;
            let scrollTop = target.scrollTop;
            let clientHeight = target.clientHeight;
            let scrollHeight = target.scrollHeight;

            if (scrollHeight > 0 && (scrollTop + clientHeight >= scrollHeight - 60)) {
              me.loadMore();
            }
          }, true);
        }
      };

      me.listView = Ext.create('Ext.view.View', {
        store: me.store,
        flex: 1,
        autoScroll: true,
        tpl: new Ext.XTemplate(
          '<div class="template-list">',
          '<tpl for=".">',
          '<div class="template-item" data-id="{id}">',
          '<input type="radio" name="template" value="{id}" class="template-radio"> {title}',
          '</div>',
          '</tpl>',
          '</div>'
        ),
        itemSelector: '.template-item',
        listeners: {
          itemclick: function(view, record, item) {
            let radio = item.querySelector('.template-radio');
            if (radio) {
              radio.checked = true;
              me.selectedTemplate = record.get('id');
              document.querySelectorAll('.template-item').forEach(el => el.classList.remove('selected'));
              item.classList.add('selected');

              if (treeFolder.selectedPeriod && treeFolder.selectedOrganization) {
                http.request({
                  method: 'POST',
                  url: '{{ route('appraisal.period.organization.set.template') }}',
                  params: {
                    period: treeFolder.selectedPeriod,
                    template: me.selectedTemplate,
                    organization: treeFolder.selectedOrganization,
                    '_method': 'PUT',
                    '_token': '{{ csrf_token() }}'
                  },
                  success: function() {
                    Ext.example.msg('Success!', 'Template Updated!');
                  },
                  failure: function() {
                    console.error("Failed to update template.");
                    Ext.example.msg('Failed!', 'Update Failed!');
                  }
                });
              }
            }
          },
          refresh: function() {
            me.autoSelectTemplate();
            me.updateEmptyText();
            me.bindScrollListener();
          },
          boxready: function() {
            me.bindScrollListener();
          }
        }
      });

      me.panel = Ext.create('Ext.panel.Panel', {
        title: 'Template',
        region: 'center',
        border: true,
        flex: 1,
        layout: {
          type: 'vbox',
          align: 'stretch'
        },
        items: [{
            layout: {
              type: 'hbox',
              align: 'middle'
            },
            padding: '10px',
            items: [{
                xtype: 'component',
                flex: 1
              },
              me.searchField
            ]
          },
          me.listView
        ]
      });
    }
  }
</script>

<style>
  .template-container {
    display: flex;
    flex-direction: column;
    height: 100%;
    border-left: 1px solid #eef0f2;
    border-right: 1px solid #eef0f2;
    border-bottom: 1px solid #eef0f2;
  }

  .template-header {
    padding: 9px 9px 10px;
    font-size: 13px;
    font-weight: 600;
    line-height: 15px;
    color: #555555;
    border-top: 1px solid #eef0f2;
    border-bottom: 1px solid #eef0f2;
    background-color: #EEF0F2;
  }

  .template-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 10px;
    height: 100%;
    overflow-y: auto;
    box-sizing: border-box;
  }

  .template-item {
    display: flex;
    align-items: center;
    padding: 10px;
    border: 1px solid #eef0f2;
    border-radius: 8px;
    background-color: #fff;
    transition: all 0.3s ease;
    cursor: pointer;
    width: 100%;
  }

  .template-item:hover {
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  .template-radio {
    margin-right: 10px;
    accent-color: #007bff;
    transform: scale(1.2);
  }

  .template-item.selected {
    border-color: black;
  }

  .template-item.selected .template-radio {
    accent-color: black;
  }
</style>
