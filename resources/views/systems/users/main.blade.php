@extends('headers.head-extjs')

@section('body')
  @require('extends.grid')
  @require('extends.form')
  @require('extends.form-role')
  @require('extends.form_import')

  <script>
    Ext.require(['Ext.ux.form.SearchField']);

    var roles = @json($roles);

    var grids = new Grids();
    var forms = new Forms();
    var formRoles = new FormsRole();
    var formsImport = new FormsImport();
    var viewDetail = new Ext.panelViewDetail();

    Ext.onReady(function() {
      Ext.tip.QuickTipManager.init();

      grids.init();
      forms.init();
      formRoles.init();

      viewDetail.build();

      Ext.create('Ext.container.Viewport', {
        layout: 'border',
        padding: 5,
        border: false,
        items: [
          viewDetail.panel,
          grids.grid
        ]
      });
      grids.storeLoad();
    });
  </script>
@endsection
