/*
    created by : ANDIKA
    modified: June 2020
    email: andika2000@gmail.com
    libname: Uwa.FileEditor

    --------------------------------------------------------------------------------------
    EXAMPLES CREATE CLASS OBJECT COMPONENT
    --------------------------------------------------------------------------------------
    >   let fileEditor = new Uwa.FileEditor({
            prefixFile: prefixFile,
            maxFile: source.property || 0,
            renderTo: '.display-file',
            files: value,
            action: function(file, action){ console.log(file) }
        });

*/

Uwa.FileEditor = function(property){
    let me = this;
    let prop = property || {};

    let noimage = 'images/noimage.png';

    me.resultFiles = [];

    me.id = Uwa.autoId('fileeditor');
    me.renderTo = prop.renderTo || null;
    me.files = prop.files || [];
    me.prefixFile = prop.prefixFile || '/file';
    me.maxFile = prop.maxFile || 6;
    me.previewContainer = prop.previewContainer || null;
    me.action = prop.action || function(file, action) { return null; };

    // FILE UTIL PROPERTY  ---------------------------------------------------------------------------------------------
    me.autoResize = prop.autoResize || true;
    me.maxWidth = prop.maxWidth || 1024;
    me.maxHeight = prop.maxHeight || 768;
    me.multipleFile = (me.maxFile == 1) ? false : true;
    me.fileType = prop.fileType || '*/*';

    me.fileUtil = new FileUtil({
        id: Uwa.autoId('inputfileeditor'),
        fileType: me.fileType,
        autoResize: me.autoResize,
        maxWidth: me.maxWidth,
        maxHeight: me.maxHeight,
        multipleFile: me.multipleFile
    })

    me.init = function(){
        if(typeof me.files == 'string') me.resultFiles.push(me.files);
        else me.resultFiles = me.files;
        if(me.renderTo) me.setRenderTo(me.renderTo, me.resultFiles);
    }

    me.actionPreview = function(file){
        if(me.previewContainer) {
            if (me.resultFiles.length) {
                if(!file) {
                    let index = me.resultFiles.length;
                    file = me.resultFiles[index - 1];
                }

                if(typeof file == 'object') {
                    if (file.type == 'image') {
                        let src = '/file/' + file.id;
                        $(me.previewContainer).attr("src", src);
                    }
                    else $(me.previewContainer).attr("src", noimage);
                }
                else{
                    if (file.substr(5, 5) == 'image') $(me.previewContainer).attr("src", file);
                    else $(me.previewContainer).attr("src", noimage);
                }
            }
            else $(me.previewContainer).attr("src", noimage);
        }
    }

    me.setRenderTo = function(renderTo, files){
        setTimeout(function () {
            me.renderTo = renderTo;
            $(me.renderTo).html('<div id="'+me.id+'" class="uwa-fileeditor-container '+me.id+'"></div>');
            me.resultFiles = files;
            me.load(me.resultFiles);
        }, 200)
    }

    me.load = function(act){
        let html = [];

        // CREATE NODE FILE --------------------------------------------------------
        let tplFile = `<div class="box vbox box-{index} thumb-{type}">
                            <img class="thumb thumb-{index}" src="{src}" alt=" ">
                            <div class="delete">
                                <input type="hidden" value="{index}">
                            </div>
                       </div>`;
        for(let i=0; i < me.resultFiles.length; i++){
            let file = me.resultFiles[i];
            let type = "";
            let src = "";

            if(typeof file == "object") {
                if(file.type == 'image') src = me.prefixFile + '/' + file.id;
                else type = "type "+file.extension;
            }
            else if(file.substr(0,4) == 'data'){
                src = file;
                type = " type ";
                let xtype = file.substring(file.indexOf('/')+1,file.indexOf(';')).split('.');
                type += xtype[xtype.length - 1];
            }
            html.push(String.format(tplFile, {src: src, index: i, type: type}));
            if((i+1) >= me.maxFile && me.maxFile) i = me.resultFiles.length;
        }


        // BUTTON ADD FILE ---------------------------------------------------------
        if(!me.maxFile || me.resultFiles.length < me.maxFile){
            html.push('<div class="box add"></div>');
        }

        $('#'+me.id).html(html.join(''));
        $('#'+me.id+' .add').click(me.add);
        $('#'+me.id+' .delete').click(me.delete);

        if(me.renderTo) {
            $(me.renderTo + ' .vbox').click(function () {
                let cls = $(this).attr("class").split(/\s+/);
                let index = cls[2].substring(4, 100);
                me.action(me.resultFiles[index], 'click');
                me.actionPreview(me.resultFiles[index]);
            });

            if(!isNull(act) && act == 'add'){
                let index = me.resultFiles.length-1;
                me.action(me.resultFiles[index], 'add');
            }

            me.actionPreview();
        }
    }

    me.add = function(){
        me.fileUtil.load(function (files) {
            me.resultFiles = me.resultFiles.concat(files);
            me.load('add');
        })
    }

    me.delete = function(){
        let con = confirm("Delete File?");
        if(con) {
            let index = $(this).children().val();
            me.resultFiles.splice(index, 1);
            me.load();
        }
    }

    me.init()
}

