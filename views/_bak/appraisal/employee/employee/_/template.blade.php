<script>
  var FormTemplate = function() {
    let me = Ext.utils.windowForms(this);
    me.data = null;
    me.selectedTemplate = null;

    me.store = Ext.create('Ext.data.Store', {
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
      autoLoad: true,
      listeners: {
        load: function(store, records) {
          me.autoSelectTemplate();
        }
      }
    });

    me.autoSelectTemplate = function() {
      if (me.selectedTemplate) {
        let selectedItem = document.querySelector(`.template-item[data-id="${me.selectedTemplate}"]`);
        if (selectedItem) {
          let radio = selectedItem.querySelector('.template-radio');
          if (radio) {
            radio.checked = true;
            selectedItem.classList.add('selected');
          }
        }
      }
    };

    me.searchField = Ext.create('Ext.form.field.Text', {
      emptyText: 'Search template...',
      enableKeyEvents: true,
      width: '50%',
      cls: 'search-field',
      listeners: {
        keyup: function(field) {
          let query = field.getValue().toLowerCase();
          me.store.filterBy(function(record) {
            return record.get('title').toLowerCase().includes(query);
          });
        }
      }
    });

    me.listView = Ext.create('Ext.view.View', {
      store: me.store,
      flex: 1,
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
          }
        },
        refresh: function() {
          me.autoSelectTemplate();
        }
      }
    });

    me.panel = Ext.create('Ext.panel.Panel', {
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
  };
</script>

<style>
  .template-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 0 10px;
    max-height: 380px;
    overflow-y: auto;
    width: 100%;
  }

  .template-item {
    display: flex;
    align-items: center;
    padding: 10px;
    border: 1px solid #d1d1d1;
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
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-color: black;
  }

  .template-item.selected .template-radio {
    accent-color: black;
  }

  .search-field {
    margin-left: auto;
  }
</style>
