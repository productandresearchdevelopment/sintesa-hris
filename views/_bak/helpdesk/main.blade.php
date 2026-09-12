@extends('headers.head-extjs')

@section('body')
  @require('extends.grids')
  @require('extends.form')
  @require('answer.extends.form')
  @require('./detail')

  <script>
    Ext.require(['Ext.ux.form.SearchField']);

    var grids = new Grids();
    var formHelpdesk = new FormHelpdesk();
    var formAnswer = new FormAnswer();
    var details = new Details();

    Ext.onReady(function() {

      Ext.tip.QuickTipManager.init();

      grids.init();
      formHelpdesk.init();
      formAnswer.init();
      details.init();

      Ext.create('Ext.container.Viewport', {
        layout: 'border',
        padding: '0 5 5 5',
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
