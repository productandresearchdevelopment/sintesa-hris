@extends('headers.head-extjs')

@section('body')
  @require('extends.grids')
  @require('extends.form')

  <script>
    // Memastikan bahwa modul Ext.ux.form.SearchField tersedia
    Ext.require(['Ext.ux.form.SearchField']);

    // Inisialisasi objek grid, form, dan detail view
    var grids = new Grids();
    var forms = new Forms();
    var viewDetail = new Ext.panelViewDetail(); // Membuat instance dari Ext.panelViewDetail
    var category = @json($category); // buat dalam bentuk json dari index controller


    // Fungsi yang akan dipanggil ketika ExtJS sudah siap
    Ext.onReady(function() {
      Ext.tip.QuickTipManager.init();

      grids.init();
      forms.init();
      viewDetail.build();

      // Membuat container viewport dengan layout border
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
          tbar: grids.tbar(grids.menus),
          items: [
            viewDetail.panel,
            grids.grid
          ]
        }]
      });

      grids.storeLoad();
    });
  </script>
@endsection
