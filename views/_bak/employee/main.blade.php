@extends('headers.head-extjs')

@section('body')
  @require('extends.grids')
  @require('extends.forms')
  @require('extends.forms_import')
  @require('extends.forms_approval')
  @require('extends.forms_reject')

  <script>
    Ext.require(['Ext.ux.form.SearchField']);

    var grids = new Grids();
    var forms = new Forms();
    var formsImport = new FormsImport();
    var formsApproval = new FormsApproval();
    var formsReject = new FormsReject();
    var viewDetail = new Ext.panelViewDetail();

    Ext.onReady(function() {
      Ext.tip.QuickTipManager.init();

      grids.init();
      forms.init();
      viewDetail.build();

      Ext.create('Ext.container.Viewport', {
        layout: 'border',
        padding: 5,
        border: false,
        items: [
          viewDetail.panel,
          grids.grid,
        ]
      });
      grids.storeLoad();
    });
  </script>
@endsection
