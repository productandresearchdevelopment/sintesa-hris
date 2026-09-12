@extends('headers.head-extjs')

@section('body')
  @require('folder.tree')
  @require('template.list')

  <script>
    Ext.require(['Ext.ux.form.SearchField', 'Ext.ux.CheckColumn']);

    var treeFolder = new TreeFolder();
    var listTemplate = new ListTemplate();

    Ext.onReady(function() {
      Ext.tip.QuickTipManager.init();

      treeFolder.init();
      listTemplate.init();

      Ext.create('Ext.container.Viewport', {
        layout: 'border',
        padding: '0 5 5 5',
        border: false,
        items: [{
          xtype: 'panel',
          layout: 'border',
          region: 'center',
          bodyPadding: '0',
          tbar: [],
          border: false,
          items: [
            treeFolder.grid,
            listTemplate.panel
          ]
        }]
      });
    });
  </script>
@endsection
