@extends('headers.head-extjs')

@section('body')
  @require('file.grids')
  @require('file.form_file')
  @require('file.form_link')
  @require('file.form_rename')
  @require('file.form_update')
  @require('file.form_tag')
  @require('folder.tree')
  @require('folder.form')
  @require('file.detail')

  <script>
    Ext.require(['Ext.ux.form.SearchField', 'Ext.ux.CheckColumn']);

    var dataOrganizations = @json($organization);
    var dataUsers = @json($users);

    var gridFile = new FileGrids();
    var treeFolder = new TreeFolder();
    var formFolder = new FormFolder();
    var formFile = new FormFile();
    var formLink = new FormLink();
    var formRename = new FormRename();
    var formUpdate = new FormUpdate();
    var formTag = new FormTag();
    var details = new Details();

    Ext.onReady(function() {
      Ext.tip.QuickTipManager.init();

      treeFolder.init();
      gridFile.init();
      formFolder.init();
      formFile.init();
      formLink.init();
      formRename.init();
      formUpdate.init();
      formTag.init();
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
          tbar: gridFile.tbar(gridFile.menus),
          border: false,
          items: [
            treeFolder.grid,
            gridFile.grid,
            details.tabs,
          ]
        }]
      });

      gridFile.storeLoad();
    });
  </script>
@endsection
