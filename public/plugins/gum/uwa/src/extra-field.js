/*
    created by : ANDIKA
    modified: June 2020
    email: andika2000@gmail.com
    libname: Uwa.ExtraField

    --------------------------------------------------------------------------------------
    EXAMPLES CREATE CLASS OBJECT COMPONENT
    --------------------------------------------------------------------------------------
    >   var extraField = new Uwa.ExtraField('my-id')
    >   var extraField = new Uwa.ExtraField({
            flex: 1,
            margin: '2 0 30 0',
            border: true,
            {etc}
        });

    >   var component = Ext.widget('propertygrid', {
            flex: 1,
            margin: '2 0 30 0',
            border: true,
            {etc}
        });

        var extraField = new Uwa.ExtraField(component, {
            keyId: 'index',
            keyValue: 'value',
            keyValueFile: 'files',
            prefixFile: 'http://localhost/images',
        })

    --------------------------------------------------------------------------------------
    EXAMPLES LOAD DATA SOURCE << EXAMPLE SOURCES "example.json" >>
    --------------------------------------------------------------------------------------

    me.extraField.loadSource({
        fields: [{
            "id": 1,
            "type":"text",
            "name":"Text (text)",
            "group":null,
            "default":null,
            "options": null
        }],
        values: [{detail_id: 1, value: 'MY TEXT'}],
    });
*/

Uwa.ExtraField = function(component, property){
    let me = this;

    let styleCls = 'uwa-grid-property';

    me.cid = null;
    me.component = null;

    me.sources = {
        source: {},
        sourceConfig: {},
        files: {},
        signatures: {},
        map: []
    };

    me.fields = [];
    me.values = [];
    me.keyId = 'index';
    me.keyValue = 'value';
    me.prefixFile = '/file';

    me.init = function(){
        me.cid = (typeof component == 'string') ? component : null;
        me.component = (typeof component == 'object') ? component : null;
        if(me.component || me.cid) {
            if(me.component){
                if(me.component.constructor.name == "Object"){
                    me.component.xtype = "propertygrid";
                    me.component = Ext.widget('propertygrid', me.component);
                }
                me.cid = me.component.getId();
            }
            else if(me.cid) me.component = Ext.getCmp(me.cid);

            if(me.component){
                let cmp = me.component;
                if(!cmp.hasCls(styleCls)) cmp.addCls(styleCls);
                cmp.addListener('beforeedit', function (obj, rec) {
                    let sourceName = rec.record.data.name;
                    if(sourceName.substr(0,20) == 'source-property-file'){
                        return false;
                    }
                    else if(sourceName.substr(0,25) == 'source-property-signature'){
                        return false;
                    }
                    return true;
                });
            }
            me.clear();
        }

        if(!isNull(property.fields)) me.fields = property.fields;
        if(!isNull(property.values)) me.values = property.values;
        if(!isNull(property.keyId)) me.keyId = property.keyId;
        if(!isNull(property.keyValue)) me.keyValue = property.keyValue;
        if(!isNull(property.prefixFile)) me.prefixFile = property.prefixFile;
    }

    me.get = function(){
        let message = null;
        let success = true;
        let result = [];
        let values = me.component.source;
        for(var sourceId in values) {
            let map = find(me.sources.map, {sourceId: sourceId});
            if(map && map.type != 'hide' && map.type != 'hidden'){
                let value = values[sourceId];
                let file = map.file;
                let signature = map.signature;
                let dataType = 'string';

                if(!isNull(file)){
                    value = file.resultFiles;
                    if(value && !value.length) value = null;
                    dataType = 'file';
                }
                else if(!isNull(signature)) {
                    value = signature.get();
                    dataType = 'signature';
                }
                else {
                    dataType = 'string';
                    switch (map.field.type) {
                        case 'date':
                            value = Ext.Date.format(value, 'Y-m-d');
                            break;
                        case 'time':
                            value = Ext.Date.format(value, 'H:i:s');
                            break;
                        case 'datetime':
                            value = Ext.Date.format(value, 'Y-m-d H:i:s');
                            break;
                    }
                }

                let res = {};
                res[me.keyId] = map.id;
                res[me.keyValue] = value;
                res['type'] = dataType;

                if(success && !message){
                    if(map.field.required && (!value || (dataType == 'file' && !value.length))){
                        success = false;
                        message = map.field.label + ' Is Required';
                    }
                }

                result.push(res);
            }
        }
        return {success: success, message: message, data: result};
    }

    me.clear = function(){
        me.sources = { source: {}, sourceConfig: {}, files: {}, signatures: {}, map: [] };
        me.component.setSource(me.sources.source, me.sources.sourceConfig);
    }

    me.loadSource = function(datasource){
        me.clear();
        if(!isNull(datasource)){
            if(datasource instanceof Array) me.values = datasource;
            else{
                if(!isNull(datasource.fields)) me.fields = datasource.fields;
                if(!isNull(datasource.values)) me.values = datasource.values;
            }

            me.setupSource(me.fields);

            me.component.setSource(me.sources.source, me.sources.sourceConfig);
        }
    }

    me.setupSource = function (fields){
        fields.forEach(function (field) {
            if(field.type != 'hide' && field.type != 'hidden' && !(field.type == 'group' && (field.label.toLowerCase() == 'hide' || field.label.toLowerCase() == 'hidden'))) {
                let sid = null;

                if (field.type == 'group') {
                    let items = field.items;
                    if(items && items.length){
                        let sorce = false;
                        items.forEach(function (e){
                            if(e.type != 'hide' && e.type != 'hidden' && !(e.type == 'group' && (e.label.toLowerCase() == 'hide' || e.label.toLowerCase() == 'hidden'))) {
                                sorce = true;
                                return sorce;
                            }
                        });

                        if(sorce) {
                            let gid = Uwa.autoId('source-property');
                            me.sources.sourceConfig[gid] = me.sourceConfig.group(field.label);
                            me.sources.source[gid] = null;
                            me.setupSource(field.items);
                        }
                    }
                }
                else if (field.type == 'file') {
                    sid = Uwa.autoId('source-property-file');
                    me.sources.source[sid] = me.sourceFileValue(sid, field);
                    me.sources.sourceConfig[sid] = me.sourceConfig.file(field);
                }
                else if (field.type == 'signature') {
                    sid = Uwa.autoId('source-property-signature');
                    me.sources.source[sid] = me.sourceSignatureValue(sid);
                    me.sources.sourceConfig[sid] = me.sourceConfig.signature(field);
                }
                else {
                    sid = Uwa.autoId('source-property');
                    switch (field.type) {
                        case ('text'):
                            me.sources.sourceConfig[sid] = me.sourceConfig.text(field);
                            break;
                        case ('hyperlink'):
                            me.sources.sourceConfig[sid] = me.sourceConfig.text(field);
                            break;
                        case ('textarea'):
                            me.sources.sourceConfig[sid] = me.sourceConfig.textArea(field);
                            break;
                        case ('date'):
                            me.sources.sourceConfig[sid] = me.sourceConfig.date(field);
                            break;
                        case ('time'):
                            me.sources.sourceConfig[sid] = me.sourceConfig.time(field);
                            break;
                        case ('datetime'):
                            me.sources.sourceConfig[sid] = me.sourceConfig.dateTime(field);
                            break;
                        case ('check'):
                            me.sources.sourceConfig[sid] = me.sourceConfig.check(field);
                            break;
                        case ('number'):
                            me.sources.sourceConfig[sid] = me.sourceConfig.number(field);
                            break;
                        case ('combo'):
                            me.sources.sourceConfig[sid] = me.sourceConfig.combo(field);
                            break;
                    }
                    me.sources.source[sid] = me.sourceValue(field);
                }

                me.sources.map.push({
                    id: field.index,
                    sourceId: sid,
                    field: field,
                    file: me.sources.files[sid],
                    signature: me.sources.signatures[sid]
                });
            }
        });
    }

    me.sourceFileValue = function(sid, field){
        let value = [];

        if(me.values){
            let search = {}; search[me.keyId] = field[me.keyId]
            let val = find(me.values, search);
            if(val){
                val = val[me.keyValue];
                if(val) value = val;
            }
        }

        if(!Array.isArray(value)) value = [value];

        let prop = field || {};

        let fileType = prop.fileType || '*';
        let maxWidth = prop.maxWidth || 1024;
        let maxHeight = prop.maxHeight || 768;
        let maxFile = prop.maxFile || 6;
        let autoResize = prop.autoResize || true;

        me.sources.files[sid] = new Uwa.FileEditor({
            prefixFile: me.prefixFile,
            renderTo: '#display-file-'+sid,
            files: value,
            maxFile: maxFile,
            fileType: fileType,
            maxWidth: maxWidth,
            maxHeight: maxHeight,
            autoResize: autoResize
        });

        return '<div id="display-file-'+sid+'" class="display-file"></div>';
    }

    me.sourceSignatureValue = function(sid){
        let value = me.values || null;
        me.sources.signatures[sid] = new me.Signature(sid);
        return `
                    <div id="display-signature-`+sid+`" class="display-signature" style="height: 150px; width: 100%; border: 1px solid #DDD"></div>
                    <div id="display-signature-clear-`+sid+`" style="background: #EEEEEE; border: 1px solid #DDD; border-radius: 5px; padding: 7px; margin-top: 5px; text-align: center">CLEAR</div>
               `;
    }

    me.sourceValue = function(field){
        let value = (field.default == undefined) ? null : field.default;
        if(me.values && me.values.length){
            let search = {}; search[me.keyId] = field[me.keyId];
            let val = find(me.values, search);
            value = (val) ? val[me.keyValue] : value;
        }

        if(value){
            switch(field.type){
                case ('datetime'): value = dates.parse(value); break;
                case ('date'): value = dates.parse(value); break;
                case ('time'): value = dates.parseTime(value); break;
                case ('check'): value = value ? 1 : 0; break;
                case ('number'): value = parseFloat(value); break;
            }
        }
        return value;
    }

    me.Signature = function(sid){
        let m = this;

        m.sig = null;

        m.init = function(){
            setTimeout(function () {
                m.sig = $('#display-signature-'+sid).signature();
                $('#display-signature-clear-'+sid).click(function() {
                    m.sig.signature('clear');
                });
            },500);
        }

        m.get = function () {
            return m.sig.signature('toDataURL', 'image/jpeg');
        }

        m.init();
    }

    me.sourceConfig = {
        group: function (text) {
            if(text) {
                return { displayName: '<div class="display-group">' + text + '</div>' };
            }
            return null;
        },

        text: function (field) {
            return {
                displayName: field.label,
                type: 'string',
                editor: {xtype: 'textfield', allowBlank: true, cls: styleCls}
            }
        },

        textArea: function (field) {
            return {
                displayName: '<div class="display-textarea">' + field.label + '</div>',
                type: 'string',
                editor: {
                    xtype: 'textarea',
                    allowBlank: true,
                    height: 119,
                    margin: '1 0 0 0',
                    cls: styleCls
                },
                renderer: function (val) {
                    val = val || '';
                    return '<div class="display-textarea">' + val.replace(/\n/g, "<br/>") + '</div>';
                }
            }
        },

        date: function (field) {
            let format = 'd/m/Y';
            return {
                displayName: field.label,
                type: 'date',
                editor: {
                    xtype: 'datefield',
                    allowBlank: true,
                    cls: styleCls,
                    format: format
                },
                renderer: function (val) {
                    return Ext.Date.format(val, format);
                }
            }
        },

        time: function (field) {
            let format = 'H:i';
            return {
                displayName: field.label,
                type: 'date',
                editor: {
                    xtype: 'timefield',
                    format: format,
                    allowBlank: true,
                    cls: 'grid-property-detail'
                },
                renderer: function (val) {
                    return Ext.Date.format(val, format);
                }
            }
        },

        dateTime: function (field) {
            let format = 'd/m/Y H:i';
            return {
                displayName: field.label,
                type: 'datefield',
                editor: {
                    xtype: 'datetimefield',
                    format: format,
                    allowBlank: true,
                    cls: styleCls
                },
                renderer: function (val) {
                    return Ext.Date.format(val, format);
                }
            }
        },

        number: function (field) {
            return {
                displayName: field.label,
                type: 'int',
                editor: {
                    xtype: 'numberfield',
                    hideTrigger: true,
                    allowBlank: true,
                    cls: styleCls
                },
                renderer: function (val) {
                    if (field.property == 'currency') {
                        return Ext.util.Format.number(val, '0,000');
                    }
                    return val;
                }
            }
        },

        check: function (field) {
            return {
                displayName: field.label,
                type: 'boolean',
                editor: {
                    xtype: 'combo',
                    forceSelection: true,
                    editable: false,
                    queryMode: 'local',
                    triggerAction: 'all',
                    displayField: 'name',
                    valueField: 'id',
                    store: Ext.create('Ext.data.Store', {
                        data: [{id: 1, name: 'Yes'}, {id: 0, name: 'No'}],
                        fields: [
                            {name: 'id', type: 'int'},
                            {name: 'name', type: 'string'}
                        ]
                    })
                },
                renderer: function (val) {
                    if (val == 0) return 'No';
                    else if (val == 1) return 'Yes';
                    else return '';
                }
            }
        },

        combo: function (field) {
            let options = !field.required ? ['-'] : [];
            field.options.forEach(function (e){
                if(e) options.push(e);
            });

            return {
                type: 'combo',
                displayName: field.label,
                editor: {
                    xtype: 'combo',
                    allowBlank: true,
                    forceSelection: true,
                    editable: (field.editable == undefined) ? false : field.editable,
                    queryMode: 'local',
                    triggerAction: 'all',
                    cls: styleCls,
                    store: options || []
                }
            }
        },

        file: function (field) {
            return {
                displayName: field.label,
                editor: {xtype: 'displayfield'},
                renderer: function (val) { return val }
            }
        },

        signature: function (field) {
            return {
                displayName: field.label,
                editor: {xtype: 'displayfield'},
                renderer: function (val) { return val }
            }
        }
    }

    me.init();
}


