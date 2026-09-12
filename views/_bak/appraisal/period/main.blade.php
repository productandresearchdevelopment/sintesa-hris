@extends('headers.head-extjs')

@section('body')
  @require('extends.grids')
  @require('extends.forms')

  <script>
    Ext.require(['Ext.ux.form.SearchField']);

    var grids = new Grids();
    var forms = new Forms();

    Ext.onReady(function() {
      Ext.tip.QuickTipManager.init();

      grids.init();
      forms.init();

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
            grids.grid
          ]
        }]
      });

      grids.storeLoad();
    });
  </script>
@endsection
