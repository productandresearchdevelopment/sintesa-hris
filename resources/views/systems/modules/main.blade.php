@extends('headers.head-extjs')

@section('body')
  @require('extends.grids')
  @require('extends.forms')
  @require('extends.roles')

  <script>
    Ext.require([
      'Ext.form.*', 'Ext.layout.container.Column',
      'Ext.fx.target.Element', 'Ext.window.MessageBox', 'Ext.dd.*',
      'Ext.data.*', 'Ext.grid.*', 'Ext.tree.*', 'Ext.ux.CheckColumn',
      'Ext.util.*', 'Ext.data.*', 'Ext.XTemplate'
    ]);

    var dataTypes = @json($types);
    var dataRoutes = @json($routes);
    var dataRoles = @json($roles);

    var grids = new Grids();
    var forms = new Forms();
    var roles = new Roles();

    Ext.onReady(function() {
      Ext.tip.QuickTipManager.init();

      roles.init();
      grids.init();
      forms.init();

      Ext.create('Ext.container.Viewport', {
        id: 'main-container',
        layout: 'border',
        bodyPadding: '0',
        border: false,
        items: [
          grids.grid,
          {
            xtype: 'tabpanel',
            id: 'form-tab',
            region: 'east',
            width: 400,
            title: 'PROPERTY',
            split: true,
            collapsible: true,
            collapsed: true,
            border: false,
            items: [roles.grid]
          }
        ]
      });

    });
  </script>
@endsection
