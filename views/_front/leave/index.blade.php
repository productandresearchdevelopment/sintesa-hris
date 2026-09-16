@extends('headers.head-extjs')

@section('body')
  @require('extends.grids')
  @require('extends.form')
  @require('extends.approval')
  @require('extends.details')

  <script>
    Ext.require(['Ext.ux.form.SearchField']);

    var currentEmployee = @json($currentEmployee);
    var allLeaveTypes = @json($types);

    var grids = new Grids();
    var forms = new Forms();
    var approval = new Approval();
    var details = new Details();

    Ext.onReady(function() {
      Ext.tip.QuickTipManager.init();

      grids.init();
      forms.init();
      approval.init();
      details.init();

      Ext.create('Ext.container.Viewport', {
        layout: 'border',
        padding: 5,
        border: false,
        items: [
          grids.grid,
        ]
      });
      grids.storeLoad();
    });
  </script>
@endsection
