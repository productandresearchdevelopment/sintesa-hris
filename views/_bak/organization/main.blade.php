@extends('headers.head-extjs')

@section('body')
  @require('extends.grids')
  @require('extends.form')
  @require('extends.form_division')

  <script>
    Ext.require([
      'Ext.form.*', 'Ext.layout.container.Column',
      'Ext.fx.target.Element', 'Ext.window.MessageBox', 'Ext.dd.*',
      'Ext.data.*', 'Ext.grid.*', 'Ext.tree.*', 'Ext.ux.CheckColumn',
      'Ext.util.*', 'Ext.data.*', 'Ext.XTemplate'
    ]);

    var grids = new Grids();
    var forms = new Forms();
    var formDivision = new FormDivision();

    Ext.onReady(function() {
      Ext.tip.QuickTipManager.init();

      grids.init();
      forms.init();
      formDivision.init();

      Ext.create('Ext.container.Viewport', {
        id: 'main-container',
        layout: 'border',
        bodyPadding: '0',
        border: false,
        items: [
          grids.grid,
        ]
      });

    });
  </script>
@endsection
