@extends('headers.head-extjs')

@section('body')
  @require('folder.tree')
  @require('employee.grids')
  @require('employee.forms')
  @require('employee.detail')

  <script>
    Ext.require(['Ext.ux.form.SearchField']);

    var treeFolder = new TreeFolder();
    var grids = new Grids();
    var forms = new Forms();
    var details = new Details();
    var user = @json($user);

    Ext.onReady(function() {
      Ext.tip.QuickTipManager.init();

      treeFolder.init();
      grids.init();
      forms.init();
      details.init();

      Ext.create('Ext.container.Viewport', {
        layout: 'border',
        padding: 5,
        border: false,
        items: [{
          xtype: 'panel',
          layout: 'border',
          region: 'center',
          bodyPadding: '0',
          border: false,
          items: [
            treeFolder.grid,
            grids.grid
          ]
        }]
      });
    });
  </script>
@endsection
