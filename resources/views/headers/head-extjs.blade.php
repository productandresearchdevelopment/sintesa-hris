@extends('headers.head')

@section('header')
  <link rel="stylesheet" type="text/css"
    href="{{ asset('plugins/extjs/resources/ext-theme-neptune/ext-theme-neptune-all-debug.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/extjs/examples/shared/example.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('css/style-extjs.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/gum/uwa/uwa.css') }}" />

  <script type="text/javascript" src="{{ asset('plugins/extjs/ext-all.js') }}"></script>
  <script type="text/javascript" src="{{ asset('plugins/extjs/packages/ext-theme-neptune/build/ext-theme-neptune.js') }}">
  </script>
  <script type="text/javascript" src="{{ asset('plugins/extjs/examples/shared/examples.js') }}"></script>
  <script type="text/javascript" src="{{ asset('plugins/gum/uwa/uwa.js') }}"></script>


  <script type="text/javascript">
    Ext.Loader.setConfig({
      enabled: true
    });
    Ext.Loader.setPath('Ext.ux', '{{ asset('plugins/extjs/examples/ux') }}/');
    Ext.util.Format.thousandSeparator = '.';
    Ext.util.Format.decimalSeparator = ',';

    // CUSTOM EXT-JS ===================================================================

    var colorList = ['000000', '333333', '666666', '999999', 'CCCCCC', 'FFFFFF', 'FFCCCC', 'CC9999', '996666', 'FF9999',
      '663333', 'CC6666', 'FF6666', '993333', 'CC3333', 'FF3333', '330000', '660000', '990000', 'CC0000',
      'FF0000', 'FF3300', 'FF6633', 'CC3300', 'FF9966', 'CC6633', '993300', 'FF6600', 'FFCC99', 'CC9966',
      '996633', 'FF9933', '663300', 'CC6600', 'FF9900', 'FFCC66', 'CC9933', '996600', 'FFCC33', 'CC9900',
      'FFCC00', 'FFFFCC', 'CCCC99', '999966', 'FFFF99', '666633', 'CCCC66', 'FFFF66', '999933', 'CCCC33',
      'FFFF33', '333300', '666600', '999900', 'CCCC00', 'FFFF00', 'CCFF00', 'CCFF33', '99CC00', 'CCFF66',
      '99CC33', '669900', '99FF00', 'CCFF99', '99CC66', '669933', '99FF33', '336600', '66CC00', '66FF00',
      '99FF66', '66CC33', '339900', '66FF33', '33CC00', '33FF00', 'CCFFCC', '99CC99', '669966', '99FF99',
      '336633', '66CC66', '66FF66', '339933', '33CC33', '33FF33', '003300', '006600', '009900', '00CC00',
      '00FF00', '00FF33', '33FF66', '00CC33', '66FF99', '33CC66', '009933', '00FF66', '99FFCC', '66CC99',
      '339966', '33FF99', '006633', '00CC66', '00FF99', '66FFCC', '33CC99', '009966', '33FFCC', '00CC99',
      '00FFCC', 'CCFFFF', '99CCCC', '669999', '99FFFF', '336666', '66CCCC', '66FFFF', '339999', '33CCCC',
      '33FFFF', '003333', '006666', '009999', '00CCCC', '00FFFF', '00CCFF', '33CCFF', '0099CC', '66CCFF',
      '3399CC', '006699', '0099FF', '99CCFF', '6699CC', '336699', '3399FF', '003366', '0066CC', '0066FF',
      '6699FF', '3366CC', '003399', '3366FF', '0033CC', '0033FF', 'CCCCFF', '9999CC', '666699', '9999FF',
      '333366', '6666CC', '6666FF', '333399', '3333CC', '3333FF', '000033', '000066', '000099', '0000CC',
      '0000FF', '3300FF', '6633FF', '3300CC', '9966FF', '6633CC', '330099', '6600FF', 'CC99FF', '9966CC',
      '663399', '9933FF', '330066', '6600CC', '9900FF', 'CC66FF', '9933CC', '660099', 'CC33FF', '9900CC',
      'CC00FF', 'FFCCFF', 'CC99CC', '996699', 'FF99FF', '663366', 'CC66CC', 'FF66FF', '993399', 'CC33CC',
      'FF33FF', '330033', '660066', '990099', 'CC00CC', 'FF00FF', 'FF00CC', 'FF33CC', 'CC0099', 'FF66CC',
      'CC3399', '990066', 'FF0099', 'FF99CC', 'CC6699', '993366', 'FF3399', '660033', 'CC0066', 'FF0066',
      'FF6699', 'CC3366', '990033', 'FF3366', 'CC0033', 'FF0033'
    ];

    http = new Ext.data.Connection();

    Ext.ajaxConfirm = function(title, properties) {
      if (properties != undefined) {
        title = title || '';
        Ext.MessageBox.confirm('Confirm', 'Confirm ' + title + ' ?', function(res) {
          if (res == 'yes') {
            if (properties.mask != undefined) properties.mask.getEl().mask('Loading');
            http.request({
              method: (properties.method == undefined) ? 'post' : properties.method,
              url: properties.url,
              params: properties.params,
              success: function(respon, request) {
                let result = Ext.decode(respon.responseText);
                Ext.example.msg('Success!', title + ' Success!');
                if (properties.mask != undefined) properties.mask.getEl().unmask();
                if (typeof properties.success == 'function') {
                  properties.success(respon, result);
                }
              },
              failure: function(obj, res) {
                Ext.responFailure(obj, res);
                if (properties.mask != undefined) properties.mask.getEl().unmask();
              }
            });
          }
        });
      } else console.log('httpPostConfirm Error', title, properties);
    }

    Ext.ajaxInputDialog = function(title, property) {
      if (property != undefined) {
        Ext.inputDialog.set({
          title: title || '',
          label: property.label ? property.label : null,
          type: property.type ? property.type : null,
          autoClose: false,
          action: function(value, input, win) {
            if (value) {
              let params = property.params || {};
              let nameValue = property.nameValue || 'message';
              params[nameValue] = value;

              if (property.mask != undefined) property.mask.getEl().mask('Loading');
              http.request({
                method: (property.method == undefined) ? 'post' : property.method,
                url: property.url,
                params: params,
                success: function(respon, request) {
                  Ext.example.msg('Success!', title + ' Success!');
                  if (property.mask != undefined) property.mask.getEl()
                    .unmask();
                  if (typeof property.success == 'function') {
                    property.success(respon);
                  }
                  win.close();
                },
                failure: function(obj, res) {
                  Ext.responFailure(obj, res);
                  if (property.mask != undefined) {
                    property.mask.getEl().unmask();
                  }
                  win.close();
                }
              });
            } else Ext.msg.warning('Please Input Textbox');
          }
        });
        Ext.inputDialog.show();
      } else console.log('httpPostConfirm Error', title, properties);
    }

    Ext.inputDialog = {
      set: function(property) {
        let me = this;

        me.type = 'textfield';
        me.title = 'INPUT FORM';
        me.label = 'VALUE';
        me.value = null;
        me.autoClose = true;
        me.buttonLabel = 'OK';
        me.hiddenButtonClose = false;
        me.buttonCloseLabel = 'Cancel';
        me.enableKeyEvents = true;
        me.action = function(value, input, win) {
          console.log(value, input, win);
        };
        me.actionClose = function(value, input, win) {
          /* console.log(value, input, win); */
        };

        let prop = property || {};

        if (prop.type) me.type = prop.type;

        if (me.type == 'textarea') me.type = 'textarea';
        else if (me.type == 'number') me.type = 'numberfield';
        else if (me.type == 'date') me.type = 'datefield';

        if (prop.title != undefined) me.title = prop.title;
        if (prop.label != undefined) me.label = prop.label;
        if (prop.value != undefined) me.value = prop.value;
        if (prop.autoClose != undefined) me.autoClose = prop.autoClose;
        if (prop.enableKeyEvents != undefined) me.enableKeyEvents = prop.enableKeyEvents;
        if (prop.buttonLabel != undefined) me.buttonLabel = prop.buttonLabel;
        if (prop.hiddenButtonClose != undefined) me.hiddenButtonClose = prop.hiddenButtonClose;
        if (prop.buttonCloseLabel != undefined) me.buttonCloseLabel = prop.buttonCloseLabel;
        if (prop.action != undefined) me.action = prop.action;
        if (prop.actionClose != undefined) me.actionClose = prop.actionClose;

        me.inputProperty = {}
        if (property.inputProperty) {
          me.inputProperty = property.inputProperty
        }

        me.inputProperty.fieldLabel = me.label;
        me.inputProperty.labelAlign = 'top';
        me.inputProperty.allowBlank = true;
        me.inputProperty.margin = '5 10 15 10';
        me.inputProperty.width = 300;
        me.inputProperty.enableKeyEvents = me.enableKeyEvents;
        me.inputProperty.value = me.value;
        me.inputProperty.listeners = {
          keyup: function(obj, e) {
            if (e.getKey() == e.ENTER) {
              if (me.enableKeyEvents) {
                let value = input.getValue();
                me.action(value, input, win);
                if (me.autoClose) win.close();
              }
            }
          }
        }

        return me;
      },

      show: function() {
        let me = this;

        let input = Ext.widget(me.type, me.inputProperty);

        let win = Ext.create('widget.window', {
          title: me.title,
          closable: true,
          closeAction: 'destroy',
          autoHeight: true,
          autoHeight: true,
          modal: true,
          items: [input],
          buttons: [{
              text: me.buttonLabel,
              iconCls: 'icon-yes-bright',
              handler: function() {
                let value = input.getValue();
                me.action(value, input, win);
                if (me.autoClose) win.close();
              }
            },
            {
              text: me.buttonCloseLabel,
              hidden: me.hiddenButtonClose,
              iconCls: 'icon-close',
              cls: 'btn-red',
              handler: function() {
                win.close();
                me.actionClose();
              }
            }
          ]
        });

        win.show();
        if (!me.value) input.setValue('');
        input.focus();
      }
    }

    /*
        EXAMPLE
        var formPeriod = Ext.inputDialogPeriod;
        formPeriod.show({
            title: 'Period',
            buttonLabel: 'OK',
            buttonIcon: 'icon-yes-bright',
            autoClose: true,
            startValue: '2023-01-01',
            finishValue: '2023-12-31',
            handler: function(stringValue, dateValue, win){
                console.log(stringValue, dateValue, win);
                win.close();
            }
        });
    */
    Ext.inputDialogPeriod = {
      show: function(property) {
        let p = property ? property : {};
        let config = {
          title: p.title || 'PERIOD',
          buttonLabel: p.buttonLabel || 'OK',
          buttonIcon: p.buttonLabel || null,
          autoClose: (p.autoClose == undefined) ? true : p.autoClose,
          startValue: p.startValue || '{{ date('Y-m-01') }}',
          finishValue: p.finishValue || '{{ date('Y-m-t') }}',
          handler: p.handler || function() {
            return true;
          }
        };

        let win = Ext.create('widget.window', {
          title: config.title,
          closable: true,
          closeAction: 'destroy',
          autoHeight: true,
          autoWidth: true,
          layout: {
            type: 'hbox',
            align: 'stretch'
          },
          modal: true,
          bodyPadding: 20,
          items: [{
              xtype: 'datefield',
              width: 180,
              id: 'uBInTuSiSChanSpLItyl',
              format: 'd/m/Y',
              value: config.startValue
            },
            {
              xtype: 'tbtext',
              text: '<b>TO</b>',
              margin: '7 10'
            },
            {
              xtype: 'datefield',
              width: 180,
              id: 'liGNumnIanGREtroatur',
              format: 'd/m/Y',
              value: config.finishValue
            }
          ],
          buttons: [{
              text: config.buttonLabel,
              iconCls: config.buttonIcon,
              handler: function() {
                let field1 = Ext.getCmp('uBInTuSiSChanSpLItyl');
                let field2 = Ext.getCmp('liGNumnIanGREtroatur');
                let date1 = field1.getValue();
                let date2 = field2.getValue();
                let val1 = Ext.Date.format(date1, 'Y-m-d');
                let val2 = Ext.Date.format(date2, 'Y-m-d');
                config.handler({
                  start: val1,
                  end: val2
                }, {
                  start: date1,
                  end: date2
                }, win);
                if (config.autoClose) win.close();
              }
            },
            {
              text: 'Cancel',
              iconCls: 'icon-close',
              cls: 'btn-red',
              handler: function() {
                win.close();
              }
            }
          ]
        });

        win.show();
      }
    }

    Ext.menuFilter = function(id) {
      let cmp = Ext.getCmp(id);
      let items = cmp.menu.items.items;
      let item = null;
      for (let i = 0; i < items.length; i++) {
        if (items[i].checked) {
          item = items[i];
          cmp.setText('<b>' + item.text + '</b>');
          return item.value;
        }
      }
      return null;
    }

    Ext.responFailure = function(obj, res) {
      let message = "Error";
      let respon = res.response;

      if (respon.status == 404) message = "Server Not Found...";
      else if (respon.status == 403) message = "Access Denied (403)";
      else if (respon.status == 500) message = "Internal Server Error (500)";
      else if (respon.status == 200) {
        message = "Error (200)";
        if (res.result != undefined && res.result.message != undefined) message = res.result.message;
        else if (respon.responseText != undefined) message = respon.responseText;
      }
      Ext.example.msg('<span style="color: red">Failed!</span>', message);
    }

    Ext.renderBox = function(text, color, hint, meta) {
      if (text) {
        hint = hint || null;
        color = color || 'DDDDDD';
        if (hint) meta['tdAttr'] = 'data-qtip="' + hint + '"';
        return String.format('<div class="box-color" style="background: #{0}">{1}</div>', color, text);
      }
      return null;
    }

    Ext.msg = {
      show: function(title, message, timer) {
        Ext.example.msg(title, message, (timer || 2500));
      },

      success: function(message, time) {
        let title = '<span style="color: #3387CC">Success</span>';
        this.show(title, message, time);
      },

      warning: function(message, time) {
        let title = '<span style="color: #FF960D">Warning</span>';
        message = '<span style="font-size: 12px; line-height: 20px">' + message + '</span>';
        this.show(title, message, time);
      },

      error: function(message, time) {
        let title = '<span style="color: #CC0000">Error</span>';
        message = '<span style="font-size: 12px; line-height: 20px">' + message + '</span>';
        this.show(title, message, time);
      },

      failed: function(message, time) {
        let title = '<span style="color: #FF3366">Failed</span>';
        message = '<span style="font-size: 12px; line-height: 20px">' + message + '</span>';
        this.show(title, message, time);
      }
    }

    Ext.inputColor = function(prop) {
      let me = {};
      let property = prop ? prop : {};

      me.id = property.id ? property.id : random();
      me.name = property.name ? property.name : 'color';
      me.label = property.label ? property.label : 'Color';
      me.flex = property.flex ? property.flex : 0;
      me.width = property.width ? property.width : 100;
      me.default = property.default ? property.default : '';

      me.build = function() {
        me.input = Ext.create('Ext.form.field.Text', {
          id: 'inputcolor-' + me.id,
          name: me.name,
          fieldLabel: me.label,
          flex: 1,
          maxLength: 6,
          value: me.default,
          listeners: {
            change: me.onChangeColor,
          }
        });

        me.colorPicker = Ext.create('Ext.menu.ColorPicker', {
          colors: colorList,
          autoScroll: true,
          handler: function(obj, val) {
            me.input.setValue(val);
          }
        });

        me.field = Ext.create('Ext.form.FieldContainer', {
          id: 'inputcolor-container-' + me.id,
          flex: me.flex,
          width: me.width,
          cls: 'inputcolor-container',
          layout: {
            type: 'hbox',
            align: 'stretch'
          },
          margin: 0,
          items: [
            me.input,
            {
              xtype: 'tbtext',
              id: 'inputcolor-indicator-' + me.id,
              cls: 'inputcolor-box-color',
              hidden: true,
              listeners: {
                render: function() {
                  setTimeout(function() {
                    $('#inputcolor-indicator-' + me.id).click(
                      function() {
                        if (me.colorPicker.hidden) {
                          let xy = me.input.getXY();
                          xy[0] = xy[0] + 0;
                          xy[1] = xy[1] + 58;
                          me.colorPicker.showAt(xy);
                        }
                      });
                  }, 200);
                }
              }
            }
          ]
        });
      }

      me.isHex = function(hex) {
        return typeof hex === 'string' &&
          hex.length === 6 &&
          !isNaN(Number('0x' + hex))
      }

      me.onChangeColor = function() {
        let color = me.input.value;
        if (color.substr(0, 1) == '#') {
          color = color.substr(1, 6);
          me.input.setValue(color);
        }

        if (!me.isHex(color)) color = 'CCCCCC';
        $('#inputcolor-indicator-' + me.id).css('background-color', '#' + color);
      }

      me.build();

      return me;
    }

    Ext.gridCheck = function(prop) {
      let me = {};

      let property = prop ? prop : {};

      me.id = property.id ? property.id : ('gridcheck' + random());
      me.fields = property.fields ? property.fields : [];
      me.data = property.data ? property.data : [];
      me.groupField = property.groupField ? property.groupField : null;
      me.groupFieldName = property.groupFieldName ? property.groupFieldName : (me.groupField ? me.groupField :
        null);
      me.groupTpl = property.groupTpl ? property.groupTpl : null;
      me.columns = property.columns ? property.columns : [];
      me.gridProperty = property.gridProperty ? property.gridProperty : {};

      me.fields.push({
        name: 'checked',
        type: 'boolean',
        defaultValue: false
      });

      me.build = function() {
        me.store = Ext.create('Ext.data.Store', {
          groupField: me.groupField,
          data: me.data,
          fields: me.fields,
          listeners: {
            datachanged: function() {
              setTimeout(function() {
                me.setEventCheckAll();
                me.setEventCheckGroup();
                me.checkCheckAll();
              }, 300);
            }
          }
        });

        if (me.groupField) {
          let tpl = `<input type="checkbox" id="` + me.id + `-gridcheck-group-{id}" class="` + me.id +
            `-gridcheck-group" value="{id}" style="margin: 0px 25px 0px 14px"> <b>{name}</b>`;
          let groupHeaderTpl = Ext.create('Ext.XTemplate', '{rows:this.tpl}', {
            tpl: function(records) {
              var rec = records[0];
              if (!me.groupTpl) {
                return String.format(tpl, {
                  id: rec.get(me.groupField),
                  name: rec.get(me.groupFieldName)
                });
              } else return String.format(me.groupTpl, rec);
            }
          });

          me.feature = {
            ftype: 'grouping',
            collapsible: false,
            hideGroupedHeader: false,
            startCollapsed: false,
            groupHeaderTpl: groupHeaderTpl
          }
        } else me.feature = null;

        // CREATE COLUMNS ------------------------------------------------------------------------------------------
        let columnCheck = [{
          id: me.id + '-column-check',
          xtype: 'checkcolumn',
          header: '<input type="checkbox" id="' + me.id +
            '-gridcheck-all" class="gridcheck-all" value="' + me.id + '">',
          dataIndex: 'checked',
          width: 50,
          listeners: {
            checkchange: function() {
              me.checkCheckAll();
            }
          }
        }];
        me.columns = columnCheck.concat(me.columns);

        // CREATE GRID ---------------------------------------------------------------------------------------------
        let gridProperty = {
          id: me.id,
          cls: 'large-grid',
          sortableColumns: false,
          enableColumnHide: false,
          enableColumnMove: false,
          enableLocking: false,
          plugins: [new Ext.grid.plugin.CellEditing({
            clicksToEdit: 1
          })],
          features: me.feature,
          store: me.store,
          columns: me.columns,
          viewConfig: {
            stripeRows: false,
            markDirty: false
          }
        }

        me.gridProperty = {
          ...gridProperty,
          ...me.gridProperty
        };

        me.grid = Ext.create('Ext.grid.Panel', me.gridProperty);
      }

      me.setEventCheckAll = function() {
        setTimeout(function() {
          $('.gridcheck-all').change(function() {
            let checked = (this.checked) ? true : false;
            me.onEventCheckAll(checked);
          });
        }, 200);
      }

      me.onEventCheckAll = function(checked) {
        let oid = null;
        me.store.data.items.forEach(function(e) {
          if (me.groupField) {
            if (e.get(me.groupField) != oid) {
              oid = e.get(me.groupField);
              $('#' + me.id + '-gridcheck-group-' + oid).prop('checked', checked);
            }
          }
          e.set('checked', checked);
        });
      }

      me.setEventCheckGroup = function() {
        $('.' + me.id + '-gridcheck-group').change(function() {
          let value = this.value;
          let checked = (this.checked) ? true : false;
          me.store.data.items.forEach(function(e) {
            if (e.get(me.groupField).toString() == value.toString()) {
              e.set('checked', checked);
            }
          });
          me.checkCheckAll();
        });
      }

      me.checkCheckAll = function() {
        let data = me.store.data.items;

        $('#' + me.id + '-gridcheck-all').prop('checked', true);
        $('.' + me.id + '-gridcheck-group').prop('checked', true);
        for (var i = 0; i < data.length; i++) {
          let rec = data[i];
          if (!rec.get('checked')) {
            $('#' + me.id + '-gridcheck-all').prop('checked', false);
            $('#' + me.id + '-gridcheck-group-' + rec.get(me.groupField)).prop('checked', false);
          }
        }
      }

      me.checkAll = function(checked) {
        if (!checked) checked = true;
        $('#' + me.id + '-gridcheck-all').prop('checked', checked);
        me.onEventCheckAll(checked);
      }

      me.getChecked = function() {
        return Ext.pluck(me.store.data.items, 'data').grep({
          checked: true
        })
      }

      me.getCheckAll = function() {
        return $('#' + me.id + '-gridcheck-all').is(':checked');
      }

      me.setup = function() {
        me.setEventCheckAll();
        me.setEventCheckGroup();
      }

      me.build();

      return me;
    }

    Ext.panelViewDetail = function() {
      let me = this;

      me.tplNodata = `<div id="view-detail" style="position: absolute; top: 0; left:0; right:0; background: #FAFAFA">
                                <div style="font-size: 11px; padding: 30px 0px; color: #ccc; text-align: center">
                                    NO DISPLAY DATA
                                </div>
                            </div>`;

      me.build = function() {
        me.panel = Ext.create('Ext.panel.Panel', {
          id: 'viewdetail',
          header: false,
          region: 'east',
          split: false,
          margin: '0 0 0 5',
          width: 400,
          maxWidth: (screen.width - 10),
          minWidth: 300,
          border: true,
          collapsible: true,
          collapsed: true,
          hidden: true,
          tbar: [{
              xtype: 'tbtext',
              text: '<span style="font-size: 13px; font-weight: 600">VIEW DETAIL</span></i>',
            },
            '->',
            {
              text: '<i class="bi bi-chevron-right" style="font-size: 16px;"></i>',
              cls: 'btn-transparent',
              handler: function() {
                me.collapse();
              }
            }
          ],
          html: me.tplNodata,
        });
      }

      me.load = function(url) {
        setTimeout(function() {
          if (!Ext.getCmp('viewdetail').collapsed) {
            if (url) {
              let html = `<iframe id="framedetail" name="framedetail" src="{0}"></iframe>`;
              Ext.getCmp('viewdetail').update(String.format(html, url));
            } else Ext.getCmp('viewdetail').update(me.tplNodata);
          }
        }, 200);
      }

      me.expand = function() {
        me.panel.show();
        me.panel.expand();
      }

      me.collapse = function() {
        me.panel.collapse();
        setTimeout(function() {
          me.panel.hide();
        }, 400);
      }
    }

    Ext.utils = {
      grids: function(me) {
        me.extraParams = {}

        // me.bbarItems = [];

        me.refresh = function() {
          me.grid.getView().refresh();
        }

        me.httpStore = function(uri, fields, action) {
          return Ext.create('Ext.data.Store', {
            pageSize: 100,
            fields: fields,
            remoteSort: true,
            proxy: {
              type: 'ajax',
              url: uri,
              reader: {
                root: 'data',
                totalProperty: 'count'
              },
              simpleSortMode: true
            },
            listeners: (action != undefined) ? action : {}
          });
        }

        me.getAll = function(pluck) {
          let recs = me.store.data.items;
          if (pluck != undefined && pluck) {
            recs = Ext.pluck(recs, 'data');
          }
          return recs;
        }

        me.getRecs = function(pluck) {
          let recs = me.grid.getSelectionModel().getSelection();
          if (pluck != undefined && pluck) {
            recs = Ext.pluck(recs, 'data');
          }
          return recs;
        }

        me.getRec = function(pluck) {
          let recs = me.getRecs(pluck);
          if (recs.length) return recs[0];
          return null;
        }

        me.getValues = function() {
          let selectedRecords = me.grid.getSelectionModel().getSelection();
          return selectedRecords.map(record => record.getId());
        };


        me.tbar = function(menus, text) {
          menus = menus || null;
          text = (text == undefined) ? 'Menu' : (text ? text : '');
          let mainMenu = [];
          if (menus && menus.items.length) mainMenu.push({
            text: text,
            iconCls: 'icon-menu',
            menu: menus
          });
          mainMenu.push('->');
          mainMenu.push({
            xtype: 'searchfield',
            flex: 1,
            maxWidth: 300,
            minWidth: 180,
            store: me.store
          });

          return mainMenu;
        }

        me.bottomBar = function(items, paging) {
          /*
              EXAMPLES
              [
                  {
                      id: 'database',
                      param: 'db', // PARAMETER ON SEND AJAX
                      name: 'Database Variant',
                      hidden: true / false,
                      iconCls: 'icon-filter',
                      property: {value: 'id', name: 'name' (OR) display: '{id} - {name}'},
                      handler: function(val, rec, obj){},
                      value: 1,
                      items: [
                          {id: 1, name: 'Mysql'},
                          {id: 2, name: 'Maria DB'},
                          {id: 3, name: 'Sqlite'},
                      ]
                  }
              ]
          */
          let results = [];
          items = items ? items : [];
          items.forEach(function(item) {
            if (item.xtype && item.xtype == 'filter') {
              let result = me.filter(item, item.value);
              if (result) results.push(result);
            } else results.push(item);
          });

          if (paging !== false) {
            results.push('->', Ext.create('Ext.PagingToolbar', {
              store: me.store,
              displayInfo: true,
              displayMsg: 'Displaying Data : {0} - {1} of {2}',
              emptyMsg: "No Display Data",
              style: {
                padding: '0px'
              },
            }));
          }

          return results;
        }

        me.filter = function(filter, value) {
          let display = '{name}';
          let keyValue = 'id';
          let parameterName = (filter.param != undefined && filter.param) ? filter.param : 'filter-' +
            filter.id;

          if (filter.property) {
            if (filter.property.value) keyValue = filter.property.value;
            if (filter.property.name) display = '{' + filter.property.name + '}';
            if (filter.property.display) display = filter.property.display;
          }

          let textDefault = '<b>ALL ' + (filter.name ? filter.name.toUpperCase() : 'DATA') + '</b>';

          let data = me.filterItem({
            id: filter.id,
            textDefault: textDefault,
            items: filter.items,
            display: display,
            keyValue: keyValue,
            parameterName: parameterName,
            value: value,
            action: (filter.handler ? filter.handler : null)
          });

          let iconCls = (filter.iconCls != undefined && filter.iconCls) ? filter.iconCls :
            'icon-filter';

          return {
            id: 'filter-' + filter.id,
            text: data.text,
            iconCls: iconCls,
            hidden: (filter.hidden != undefined) ? filter.hidden : false,
            menu: {
              items: data.items
            }
          };
        }

        me.filterItem = function(property) {
          let id = Ext.id();
          let textDefault = '<b>ALL DATA</b>';
          let items = [];
          let display = '{name}';
          let keyValue = 'id';
          let parameterName = 'filter-' + id;
          let value = null;
          let action = function() {}

          if (property) {
            if (property.id) id = property.id;
            if (property.textDefault) textDefault = property.textDefault;
            if (property.display) display = property.display;
            if (property.keyValue) keyValue = property.keyValue;
            if (property.value) value = property.value;
            if (property.items) items = property.items;
            if (property.action) action = property.action;

            if (property.parameterName) parameterName = property.parameterName;
            else parameterName = 'filter-' + id;
          }

          let checked = items.find({
            checked: true
          });
          if (checked) value = checked[keyValue];

          let handler = function(menu) {
            let val = menu.value;
            let btnMenu = menu.parentMenu.ownerButton;
            btnMenu.setText(menu.text);
            menu.setChecked(true);

            action(val, menu, btnMenu);

            if (me.store.proxy.url) {
              me.extraParams[parameterName] = val;
              me.setExtraParams();
              me.storeLoad();
            }
          }

          let textResult = textDefault;
          let result = [{
            text: textDefault,
            value: null,
            checked: value ? false : true,
            group: 'group' + id,
            cls: 'filter-menu-item',
            handler: handler
          }];
          items.forEach(function(item) {
            let checked = (item[keyValue] == value) ? true : false;
            if (checked) {
              textResult = String.format(display, item);
              me.extraParams[parameterName] = item[keyValue];
            }

            result.push({
              text: String.format(display, item),
              value: item[keyValue],
              checked: checked,
              cls: 'filter-menu-item',
              group: 'group' + id,
              handler: handler,
            });
          });

          return {
            text: textResult,
            items: result
          };
        }

        me.getFilter = function(filterName) {
          let result = null;
          if (me.store) {
            result = me.store.proxy.extraParams[filterName];
            result = result ? result : null;
          }
          return result;
        }

        me.setParam = function(paramName, value) {
          if (me.store) {
            me.extraParams[paramName] = value;
            me.store.proxy.extraParams[paramName] = value;
            return true;
          }
          return false;
        }

        me.setExtraParams = function() {
          Ext.apply(me.store.getProxy().extraParams, me.extraParams);
        }

        me.storeLoad = function(action) {
          if (me.timeout) clearTimeout(me.timeout);
          me.timeout = setTimeout(function() {
            me.setExtraParams();
            me.store.load(action);
            me.grid.getSelectionModel().clearSelections();
            me.timeout = null;
          }, 200);
        }

        me.renderBox = function(text, color, hint, meta) {
          if (text) {
            hint = hint || null;
            color = color || 'DDDDDD';
            if (hint) meta['tdAttr'] = 'data-qtip="' + hint + '"';
            let tpl = `<div class="box-color" style="">
                                      <div class="bg" style="background: #{0}"></div>
                                      <div class="text" style="color: #{0}">{1}</div>
                                   </div>`;
            return String.format(tpl, color, text);
            //return String.format('<div class="box-color" style="background: #{0}; filter: alpha(opacity=50); border: 1px solid #{0}; color: #{0}">{1}</div>', color, text);
          }
          return null;
        }

        me.renderText = function(text, hint, meta, color) {
          if (text) {
            hint = (hint != undefined) ? hint : null;
            color = (color != undefined) ? color : null;
            '333333';
            if (hint) meta['tdAttr'] = 'data-qtip="' + hint + '"';
            return String.format('<div style="color: #{0}">{1}</div>', color, text);
          }
          return null;
        }

        me.contextMenu = function(obj, rec, node, index, e) {
          if (me.menus != undefined && me.menus.items.length) {
            e.stopEvent();
            me.menus.showAt(e.getXY());
          }
        }

        me.renderImageShape = function(property) {
          return renderImageShape(property)
        }

        me.mask = function() {
          if (me.grid && me.grid.getEl()) {
            me.grid.getEl().mask('Load');
          }
        }

        me.unmask = function() {
          if (me.grid && me.grid.getEl()) {
            me.grid.getEl().unmask();
          }
        }

        me.updateMenuItemsVisibility = function(rec) {
          let hasDeletedAt = rec.get('deleted_at') !== null;
          let isArchived = rec.get('is_archived') == 1;

          me.menus.items.each(function(item) {
            let text = item.text;

            if (hasDeletedAt) {
              if (text === 'Restore' || text === 'Forever Remove') {
                item.show();
              } else {
                item.hide();
              }
              return;
            }

            if (isArchived) {
              if (text === 'Set Archived') {
                item.hide();
              } else {
                item.show();
              }
            } else {
              if (text === 'Set Unarchived') {
                item.hide();
              } else {
                item.show();
              }
            }
          });
        };

        return me;
      },

      forms: function(me) {
        me.getForm = function() {
          return me.form.getForm();
        }

        me.getField = function(name) {
          let form = me.getForm();
          return form.findField(name);
        }

        me.getValue = function(name) {
          let field = me.getField(name);
          return field.getValue();
        }

        me.getValueCombo = function(name) {
          let field = me.getField(name);
          let data = field.displayTplData;
          if (data.length) return data[0];
          return null;
        }

        me.setField = function(name, value) {
          let field = me.getField(name);
          field.setValue(value);
        }

        me.reset = function() {
          let form = me.getForm();
          form.reset();
        }

        me.mask = function() {
          me.form.mask('Load');
        }

        me.unmask = function() {
          me.form.unmask();
        }

        me.submit = function(url, properties, autoclose) {
          let property = (properties !== undefined && properties) ? properties : {};
          let form = me.getForm();
          let params = (property.params != undefined) ? property.params : {};
          if (form.isValid()) {
            form.submit({
              url: url,
              waitMsg: 'Proses',
              params: params,
              submitEmptyText: false,
              success: function(obj, respon) {
                Ext.msg.success('Data Is Saved!');
                if (property.success != undefined) {
                  property.success(obj, respon);
                }
                if (property.autoclose == undefined || property.autoclose) me
                  .close();
              },
              failure: Ext.responFailure
            });
          } else {
            Ext.msg.warning('Please check your input data!');
            if (property.invalid != undefined) {
              property.invalid();
            }
          }
        }

        return me;
      },

      windowForms: function(me) {
        me = Ext.utils.forms(me);

        me.createWindowForm = function(title, form, properties) {
          let property = (properties !== undefined) ? properties : {};

          property.items = form;
          property.title = title;
          property.closeAction = 'hide';
          property.closable = (property.closable != undefined) ? property.closable : true;
          property.resizable = (property.resizable != undefined) ? property.resizable : false;
          property.modal = (property.modal != undefined) ? property.modal : true;
          property.autoScroll = (property.autoScroll != undefined) ? property.autoScroll : true;
          property.header = (property.header != undefined) ? property.header : true;
          property.maximized = (property.maximized != undefined) ? property.maximized :
            {{ isMobile() ? 'true' : 'false' }};
          property.layout = {
            type: 'vbox',
            align: 'stretch'
          };

          if (!property.header && title) {
            property.dockedItems = [{
              xtype: 'toolbar',
              dock: 'top',
              padding: '10 5',
              cls: 'win-toolbar-hide-header',
              items: [{
                id: 'win-tbtext-title',
                text: title,
                cls: 'btn-transparent',
                iconCls: 'icon-back',
                handler: me.close,
                iconAlign: 'left'
              }, ]
            }];
          }

          if (property.width != undefined) {
            property.width = property.width;
            property.autoWidth = false;
          } else property.autoWidth = true;

          if (property.height != undefined) {
            property.height = properties.height;
            property.autoHeight = false;
          } else property.autoHeight = true;

          me.win = Ext.create('Ext.window.Window', property);
        }

        me.show = function(action) {
          me.win.show();
        };

        me.close = function() {
          me.win.hide();
        };

        me.setTitle = function(text) {
          if (me.win.header) me.win.setTitle(text);
          else Ext.getCmp('win-tbtext-title').setText(text);
        };

        return me;
      }
    }

    /*
       EXAMPLE
       var form = new FormInput({
            title: 'Form Title',
            width: 500,
            fields: [{xtype: 'textfield', name: 'name', fieldLabel: 'Name'}],
            init: true,
            handler: function(form){
                form.submit('http://localhost/post', {
                    params: {
                        logo: me.logo.get(),
                        labels: Ext.encode(labels),
                        sections: Ext.encode(sections),
                        priorities: Ext.encode(priorities),
                        users: Ext.encode(users)
                    },
                    success: grids.storeLoad
                });
            }
       });

       form.show(function(e){
            e.setTitle('EDIT');
            e.setField('name', 'Odang Sukoto');
       });
    */

    Ext.FormInput = function(property) {
      let me = Ext.utils.windowForms(this);

      if (!property) property = {};

      let config = {
        title: property.title || 'Form',
        width: property.width || 400,
        option: property.option || null,
        init: (property.init == undefined) ? true : property.init,
        fields: property.fields || [{
          xtype: 'textfield',
          name: 'value',
          fieldLabel: 'Value'
        }],
        onshow: property.onshow || function(e) {
          return true;
        },
        handler: property.handler || function(e) {
          return true;
        },
      };

      me.init = function() {
        me.form = Ext.widget('form', {
          width: config.width,
          autoHeight: true,
          bodyPadding: '10 15',
          border: false,
          layout: {
            type: 'vbox',
            align: 'stretch'
          },
          submitEmptyText: false,
          fieldDefaults: {
            labelAlign: 'top',
            allowBlank: true
          },
          items: config.fields,
          buttons: [{
              text: 'OK',
              iconCls: 'icon-yes-bright',
              cls: "btn-green",
              handler: function() {
                config.handler(me);
              }
            },
            {
              text: 'Cancel',
              cls: 'btn-red',
              iconCls: 'icon-close',
              handler: me.close
            }
          ]
        });

        me.createWindowForm(config.title, me.form, {
          maximized: false,
          header: true
        });
      };

      me.show = function(action) {
        me.win.show();
        me.reset();
        if (action) action(me);
        if (config.onshow) config.onshow(me);
      }

      if (config.init) {
        me.init();
      }
    }
  </script>

  @yield('head')
@endsection

<body>
  @yield('body')
  @yield('script')
</body>
