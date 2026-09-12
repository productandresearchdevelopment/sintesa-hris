@extends('headers.head-extjs')

@section('body')
  @require('extends.grids')
  @require('extends.form')
  @require('./detail')

  <script>
    Ext.require(['Ext.ux.form.SearchField']);

    var grids = new Grids();
    var forms = new Forms();
    var details = new Details();

    Ext.onReady(function() {
      Ext.tip.QuickTipManager.init();

      grids.init();
      forms.init();
      details.init();

      Ext.create('Ext.container.Viewport', {
        layout: 'border',
        padding: 5,
        border: false,
        items: [
          grids.grid,
          details.tabs,
        ]
      });
      grids.storeLoad();
    });
  </script>
@endsection
