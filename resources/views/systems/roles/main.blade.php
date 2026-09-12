@extends('headers.head-extjs')

@section('body')
  @require('extends.forms')
  @require('extends.roles')
  @require('extends.modules')
  @require('extends.apps')
  @require('./tabs')

  <script>
    Ext.require(['Ext.util.*']);

    var forms = new Forms();
    var gridRoles = new GridRoles();
    var gridModules = new GridModules();
    var gridApps = new GridApps();
    var tabsPanel = new TabsPanel(gridModules, gridApps);

    Ext.onReady(function() {
      Ext.tip.QuickTipManager.init();

      forms.init();
      gridRoles.init();
      gridModules.init();
      gridApps.init();
      tabsPanel.init();

      Ext.create('Ext.container.Viewport', {
        id: 'main-container',
        layout: 'border',
        padding: '5',
        border: true,
        items: [
          gridRoles.grid,
          tabsPanel.panel
        ]
      });

      gridRoles.storeLoad();
    });
  </script>
@endsection
