<script>
  var FormOrganization = function() {
    let me = Ext.utils.windowForms(this);
    me.data = null;
    me.selectedOrganization = null;

    me.store = Ext.create('Ext.data.TreeStore', {
      folderSort: false,
      root: {
        id: '0',
        text: 'PT Qualita Indonesia',
        expanded: true,
        selectable: false
      },
      proxy: {
        type: 'ajax',
        url: '{{ route('organization.data') }}'
      },
      listeners: {
        load: function(store) {
          if (me.selectedOrganization) {
            let node = store.getNodeById(me.selectedOrganization);
            if (node) {
              me.treePanel.getSelectionModel().select(node);
            }
          }
        }
      }
    });

    me.treePanel = Ext.create('Ext.tree.Panel', {
      name: 'organization_id',
      store: me.store,
      rootVisible: true,
      useArrows: true,
      singleExpand: true,
      width: '100%',
      height: '100%',
      selModel: {
        mode: 'SINGLE',
        allowDeselect: false,
        listeners: {
          beforeselect: function(tree, record) {
            return record.get('id') !== '0';
          }
        }
      },
      margin: '0',
      border: false,
      listeners: {
        afterrender: function() {
          if (me.selectedOrganization) {
            let node = me.store.getNodeById(me.selectedOrganization);
            if (node) {
              me.treePanel.getSelectionModel().select(node);
            }
          }
        },
        selectionchange: function(tree, selected) {
          if (selected.length > 0) {
            me.selectedOrganization = selected[0].get('id');
          }
        }
      }
    });

  };
</script>
