@extends('headers.head-extjs')

@section('body')
  @require('extends.grids')
  @require('extends.forms')
  @require('extends.forms_import')

  @if (in_array(strtolower(optional($user->role)->name), ['hrga', 'developer', 'superadmin']))
    @require('./detail')
  @endif

  <script>
    Ext.require(['Ext.ux.form.SearchField']);

    var grids = new Grids();
    var forms = new Forms();
    var formsImport = new FormsImport();
    var divisions = @json($divisions);
    var userRole = '{{ strtolower(optional($user->role)->name) }}';

    @if (in_array(strtolower(optional($user->role)->name), ['hrga', 'developer', 'superadmin']))
      var details = new Details();
    @endif

    Ext.onReady(function() {
      Ext.tip.QuickTipManager.init();

      grids.init();
      forms.init();

      @if (in_array(strtolower(optional($user->role)->name), ['hrga', 'developer', 'superadmin']))
        details.init();
      @endif

      var centerItems = [grids.grid];

      @if (in_array(strtolower(optional($user->role)->name), ['hrga', 'developer', 'superadmin']))
        centerItems.push(details.tabs);
      @endif

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
          items: centerItems
        }]
      });

      grids.storeLoad();
    });
  </script>
@endsection
