var XExt = {
    Grid: function (me) {
        Ext.tip.QuickTipManager.init();
        Ext.require(['Ext.ux.form.SearchField']);

        me.getStore = function () {
            return me.grid.getStore();
        }

        me.refresh = function () {
            me.grid.getView().refresh();
        }

        me.getAll = function (pluck) {
            let recs = me.store.data.items;
            if (pluck === undefined) pluck = true;
            if (pluck) {
                recs = Ext.pluck(recs, 'data');
            }
            return recs;
        }

        me.getRecs = function (pluck) {
            let recs = me.grid.getSelectionModel().getSelection();
            if (pluck === undefined || pluck) {
                recs = Ext.pluck(recs, 'data');
            }
            return recs;
        }

        me.getRec = function (pluck) {
            let recs = me.getRecs(pluck);
            if (recs.length) return recs[0];
            return null;
        }

        me.getValues = function (field) {
            field = field || 'id';
            let recs = me.getRecs();
            if (recs.length) {
                recs = Ext.pluck(recs, field);
            }
            return recs;
        }

        me.clearSelected = function () {
            me.grid.getSelectionModel().deselectAll();
        }

        me.setSelected = function (data, field) {
            let dataStore = me.getAll();
            dataStore.forEach(function (rec, index) {
                let select = false;
                if (field) {
                    let fieldValue = {};
                    fieldValue[field] = rec[field];
                    if (data.find(fieldValue)) {
                        select = true;
                    }
                }
                else if (data.find(rec.id)) select = true;

                if (select) {
                    me.grid.getSelectionModel().select(index, true);
                }
            });
        }

        me.storeLoad = function (action) {
            if (me.timeout) clearTimeout(me.timeout);
            me.timeout = setTimeout(function () {
                me.store.load({
                    scope: this,
                    callback: function (records, operation, success) {
                        if (action) {
                            action(records, operation, success);
                        }
                    }
                });
                me.grid.getSelectionModel().clearSelections();
                me.timeout = null;
            }, 200);
        }

        me.setParam = function (param, value) {
            me.store.proxy.extraParams[param] = value;
        }

        me.getFilter = function (param) {
            let params = me.store.proxy.extraParams;
            if (params) {
                for (var key in params) {
                    if (key === param || key === 'filter_' + param) {
                        return params[key];
                    }
                }
            }
            return null;
        }

        me.sendValues = function (config) {
            let data = me.getValues();
            if (data && data.length) {
                if (config.params) config.params.data = data;
                else config.params = { data: data };

                let attr = {
                    type: 'post',
                    action: function (success) {
                        if (success) me.storeLoad();
                    }
                }

                mergeObject(attr, config);

                if (config.input) ajaxInput(attr);
                else if (config.confirm) ajaxConfirm(attr);
                else ajax(attr);
            }
            else msg.toast.warning('Please select the data to be processed!');
        }

        me.sendValue = function (config) {
            let data = me.getRec();
            if (data) {
                data = data.id;

                if (config.pathParam === undefined) config.pathParam = true;

                if (config.pathParam) {
                    config.url = config.url + '/' + data;
                }
                else {
                    if (config.params) config.params.data = data;
                    else config.params = { id: data };
                }

                let attr = {
                    type: 'post',
                    action: function (success) {
                        if (success) me.storeLoad();
                    }
                }

                mergeObject(attr, config);

                if (config.input) ajaxInput(attr);
                else if (config.confirm) ajaxConfirm(attr);
                else ajax(attr);
            }
            else msg.toast.warning('Please select the data to be processed!');
        }

        me.renderBox = function (config, meta) {
            if (config) {
                let text = null;
                let hint = null;
                let color = 'FFFFFF';

                if (typeof config === 'string') text = config;
                else {
                    if (config.text) text = config.text;
                    else if (config.alias) text = config.alias;
                    else if (config.name) text = config.name;
                    else if (config.title) text = config.title;

                    if (config.hint) hint = config.hint;
                    else if (config.name) hint = config.name;

                    if (config.color) color = config.color;
                }

                if (meta && hint) meta.tdAttr = `data-qtip='` + hint + `'`;

                let tpl = `<div class="box-border" style="background-color: #{1}99; border-color: #{1}; color: #FFFFFF">{0}</div>`;

                return String.format(tpl, text, color);
            }
            return null;
        }

        me.renderText = function (config, meta) {
            if (config) {
                let text = null;
                let hint = null;
                let color = null;
                let fontSize = 13;

                if (typeof config === 'string') text = config;
                else {
                    if (config.text) text = config.text;
                    else if (config.alias) text = config.alias;
                    else if (config.name) text = config.name;
                    else if (config.title) text = config.title;

                    if (config.hint) hint = config.hint;
                    else if (config.name) hint = config.name;

                    if (config.color) color = config.color;
                    if (config.fontSize) fontSize = config.fontSize;
                }

                if (meta && hint) meta['tdAttr'] = 'data-qtip="' + hint + '"';

                if (color) return '<div style="color: #' + color + 'CC; font-size: ' + fontSize + 'px;">' + text + '</div>';
                else return '<div style="font-size: ' + fontSize + 'px;">' + text + '</div>';
            }
            return null;
        }

        me.renderIcon = function (config, meta) {
            if (config) {
                config.fontSize = 16;
                return me.renderText(config, meta);
            }
            return null;
        }

        me.renderCircleImage = function (config) {
            if (!config.text) config.text = " ";
            if (!config.color) config.color = "666666";
            if (!config.size) config.size = 40;

            config.alt = config.text.substr(0, 1);

            let tpl = `<div class="photo photo-circle" style="--size: {size}px; display: inline-block; background-color: #{color}99;">
                            <img src="{src}" alt="{alt}">
                       </div>`;
            return String.format(tpl, config);
        }

        me.createMenu = function (config) {
            let m = this;

            m.items = [];

            m.init = function () {
                if (config.create) {
                    m.items.push({
                        text: 'Create',
                        iconCls: 'lni lni-circle-plus',
                        handler: config.create
                    });
                }
                if (config.edit) {
                    m.items.push({
                        text: 'Edit',
                        iconCls: 'lni lni-pencil-alt',
                        handler: config.edit
                    });
                }
                if (config.delete) {
                    m.items.push({
                        text: 'Delete', iconCls: 'bi bi-x-lg',
                        handler: function () {
                            me.sendValues({
                                type: 'delete',
                                url: config.delete,
                                confirm: {
                                    title: 'Confirm Delete',
                                    message: 'Are you sure you want to delete data?',
                                },
                            });
                        }
                    },);
                }

                if (config.menu) {
                    let items = [];
                    config.menu.forEach(function (e) {
                        let item = m.createItems(e);
                        if (item) items.push(item);
                    });

                    if (items && items.length) {
                        if (m.items.length) m.items.push('-');
                        m.items = m.items.concat(items);
                    }
                }

                if (m.items.length && (config.restore || config.forceDelete)) {
                    m.items.push('-');
                }
                if (config.restore) {
                    m.items.push({
                        text: 'Restore', iconCls: 'bi bi-arrow-counterclockwise',
                        handler: function () {
                            me.sendValues({
                                type: 'delete',
                                url: config.restore,
                                confirm: {
                                    title: 'Confirm Restore',
                                    message: 'Data will be restore?',
                                },
                            });
                        }
                    });
                }
                if (config.forceDelete) {
                    m.items.push({
                        text: 'Permanent Delete', iconCls: 'bi bi-trash',
                        handler: function () {
                            me.sendValues({
                                type: 'delete',
                                url: config.forceDelete,
                                confirm: {
                                    title: 'Confirm Permanent Delete',
                                    message: 'Data will be permanently deleted?',
                                },
                            });
                        }
                    });
                }
            }

            m.createItems = function (menu) {
                if (menu.menu) {
                    let items = [];
                    menu.menu.forEach(function (e) {
                        items.push(m.createItems(e));
                    });
                    if (items.length) menu.menu = { items: items };
                    return menu;
                }

                if (!menu.handler) {
                    if (menu.sendValues) {
                        menu.handler = function () {
                            me.sendValues(menu.sendValues);
                        }
                        return menu;
                    }
                    else if (menu.sendValue) {
                        menu.handler = function () {
                            me.sendValue(menu.sendValue);
                        }
                        return menu;
                    }
                    else if (menu.link) {
                        menu.handler = function () {
                            window.location = menu.link;
                        }
                        return menu;
                    }
                }

                return menu.handler ? menu : null;
            }

            m.init();

            if (m.items.length) {
                return Ext.create('Ext.menu.Menu', { items: m.items });
            }
            return null;
        }

        me.mask = function () {
            if (me.grid && me.grid.getEl()) {
                me.grid.getEl().mask('Load');
            }
        }

        me.unmask = function () {
            if (me.grid && me.grid.getEl()) {
                me.grid.getEl().unmask();
            }
        }

        return me;
    },

    WindowForm: function (me, config) {
        me.build = function () {
            me.win = Ext.create('Ext.ux.jgb.XWindowForm', config);

            if (config.init) {
                config.init();
            }

            Ext.apply(me, me.win);
        }
        if (me.init) me.init();
        else me.init = function () {
            me.build();
        }
        return me;
    },

    Form: function (me, config) {
        me.build = function () {
            me.form = Ext.create('Ext.ux.jgb.XForm', config);

            if (config.init) {
                config.init();
            }

            Ext.apply(me, me.form);
        }

        if (me.init) me.init();
        else me.init = function () {
            me.build();
        }

        return me;
    },

    Fields: {
        password: function (config) {
            if (!config) config = {};

            let name = config.name || 'password';
            delete config.name;

            let attrContainer = {
                cls: 'field-password',
                items: [
                    { xtype: 'textfield', inputType: 'password', name: name, flex: 1 },
                    {
                        xtype: 'button',
                        width: 24,
                        cls: 'button-password-visibility',
                        text: '<i class="bi bi-eye-slash"></i>',
                        listeners: {
                            click: function (e) {
                                let input = $("input[name='" + name + "']");
                                if (e.getText() == '<i class="bi bi-eye"></i>') {
                                    e.setText('<i class="bi bi-eye-slash"></i>');
                                    input.attr("type", "password");
                                }
                                else {
                                    e.setText('<i class="bi bi-eye"></i>');
                                    input.attr("type", "text");
                                }
                            }
                        }
                    }
                ]
            }

            Ext.apply(attrContainer, config);

            return Ext.create('Ext.form.FieldContainer', attrContainer);
        },

        color: function (config) {
            /* EXAMPLE
            me.inputColor = XExt.Fields.color({
                flex: 1,
                name: 'color',
                fieldLabel: 'Color',
                margin: '0 0 0 10',
            });
            */
            let me = this;

            if (!config) config = {};

            me.id = config.name || Ext.id();
            me.name = config.name || 'color';

            delete config.name;

            config.id = 'field-color-' + me.id;

            me.colors = ['000000', '333333', '666666', '999999', 'CCCCCC', 'FFFFFF', 'FFCCCC', 'CC9999', '996666', 'FF9999', '663333', 'CC6666', 'FF6666', '993333', 'CC3333', 'FF3333', '330000', '660000', '990000', 'CC0000', 'FF0000', 'FF3300', 'FF6633', 'CC3300', 'FF9966', 'CC6633', '993300', 'FF6600', 'FFCC99', 'CC9966', '996633', 'FF9933', '663300', 'CC6600', 'FF9900', 'FFCC66', 'CC9933', '996600', 'FFCC33', 'CC9900', 'FFCC00', 'FFFFCC', 'CCCC99', '999966', 'FFFF99', '666633', 'CCCC66', 'FFFF66', '999933', 'CCCC33', 'FFFF33', '333300', '666600', '999900', 'CCCC00', 'FFFF00', 'CCFF00', 'CCFF33', '99CC00', 'CCFF66', '99CC33', '669900', '99FF00', 'CCFF99', '99CC66', '669933', '99FF33', '336600', '66CC00', '66FF00', '99FF66', '66CC33', '339900', '66FF33', '33CC00', '33FF00', 'CCFFCC', '99CC99', '669966', '99FF99', '336633', '66CC66', '66FF66', '339933', '33CC33', '33FF33', '003300', '006600', '009900', '00CC00', '00FF00', '00FF33', '33FF66', '00CC33', '66FF99', '33CC66', '009933', '00FF66', '99FFCC', '66CC99', '339966', '33FF99', '006633', '00CC66', '00FF99', '66FFCC', '33CC99', '009966', '33FFCC', '00CC99', '00FFCC', 'CCFFFF', '99CCCC', '669999', '99FFFF', '336666', '66CCCC', '66FFFF', '339999', '33CCCC', '33FFFF', '003333', '006666', '009999', '00CCCC', '00FFFF', '00CCFF', '33CCFF', '0099CC', '66CCFF', '3399CC', '006699', '0099FF', '99CCFF', '6699CC', '336699', '3399FF', '003366', '0066CC', '0066FF', '6699FF', '3366CC', '003399', '3366FF', '0033CC', '0033FF', 'CCCCFF', '9999CC', '666699', '9999FF', '333366', '6666CC', '6666FF', '333399', '3333CC', '3333FF', '000033', '000066', '000099', '0000CC', '0000FF', '3300FF', '6633FF', '3300CC', '9966FF', '6633CC', '330099', '6600FF', 'CC99FF', '9966CC', '663399', '9933FF', '330066', '6600CC', '9900FF', 'CC66FF', '9933CC', '660099', 'CC33FF', '9900CC', 'CC00FF', 'FFCCFF', 'CC99CC', '996699', 'FF99FF', '663366', 'CC66CC', 'FF66FF', '993399', 'CC33CC', 'FF33FF', '330033', '660066', '990099', 'CC00CC', 'FF00FF', 'FF00CC', 'FF33CC', 'CC0099', 'FF66CC', 'CC3399', '990066', 'FF0099', 'FF99CC', 'CC6699', '993366', 'FF3399', '660033', 'CC0066', 'FF0066', 'FF6699', 'CC3366', '990033', 'FF3366', 'CC0033', 'FF0033'];
            me.colorPicker = Ext.create('Ext.menu.ColorPicker', {
                colors: me.colors,
                autoScroll: true,
                handler: function (obj, val) {
                    me.input.setValue(val);
                }
            });

            me.button = Ext.create('Ext.button.Button', {
                id: 'btn-color-' + me.id,
                xtype: 'button',
                style: 'background: #DDDDDD',
                menu: me.colorPicker,
            });

            me.input = Ext.create('Ext.form.field.Text', {
                id: 'input-color-' + me.id,
                xtype: 'textfield',
                name: me.name,
                width: '100%',
                maxLength: 8,
                listeners: {
                    change: function (e, val) {
                        let color = val;
                        if (color.substr(0, 1) == '#') {
                            color = color.substr(1, 12);
                            e.setValue(color);
                        }
                        $('#btn-color-' + me.id).css('background-color', '#' + color);
                    }
                }
            });

            me.attrContainer = {
                cls: 'field-color-picker',
                items: [me.input, me.button]
            }

            Ext.apply(me.attrContainer, config);

            return Ext.create('Ext.form.FieldContainer', me.attrContainer);
        }
    },


}


