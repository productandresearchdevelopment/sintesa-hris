@extends('headers.head-extjs')

@section('body')
  @require('extends.grids')
  @require('extends.form')
  @require('./detail')

  <script>
    Ext.require(['Ext.ux.form.SearchField', 'Ext.ux.CheckColumn']);

    var dataOrganizations = @json($organization);

    var grids = new CategoryGrids();
    var formCategory = new FormCategory();
    var details = new Details();

    Ext.onReady(function() {
      Ext.tip.QuickTipManager.init();


      grids.init();
      formCategory.init();
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
          tbar: grids.tbar(grids.menus),
          items: [
            grids.grid,
            details.tabs,
          ]
        }]
      });

      grids.storeLoad();
    });
  </script>
@endsection
