var random = function(min, max){
    min = min ? Math.ceil(min) : 10000;
    max = max ? Math.floor(max) : 99999;
    return Math.floor(Math.random() * (max - min) + min);
}

var mask = {
    show: function (){
        $('#modal-overlay-loading').show();
    },

    hide: function (){
        $('#modal-overlay-loading').hide();
    }
};

var mergeObject = function(obj, obj2){
    if(obj && obj2) {
        for (var key in obj2) {
            obj[key] = obj2[key];
        }
    }
    return obj;
}

var ajaxTimerDelay = null;

var ajax = function (config){
    /*  EXAMPLE ------------------------------------------------------------------------
        ajax({
            type: 'post', // DEFAULT GET
            data: {id: 123, param2: 'ok'},
            action: function (success, respon){
                if(success) {
                    console.log(respon);
                }
            }
        })
    */
    if(config) {
        if(config.url) {
            let attr = {
                type: 'get',
                jsonp: true,
                data: {},
                action: function () {},
                success: function (respon) {
                    if (!respon || respon.success) {
                        msg.toast.success(respon.message || null);
                        this.action(true, respon);
                    }
                    else if (!respon.success) {
                        msg.toast.warning(respon.message || 'Your data not received');
                        this.action(false, respon);
                    }

                },
                error: function (xhr) {
                    msg.toast.responFailed(xhr);
                    this.action(false, xhr);
                }
            };

            if (config.params) {
                attr.data = config.params;
            }

            if (config.type) {
                if (config.type.toLowerCase() !== 'post' && config.type.toLowerCase() !== 'get') {
                    attr.data['_method'] = config.type;
                    config.type = 'post';
                }
            }

            if (config.csrf) {
                attr.data['_token'] = config.csrf;
            }
            else if(config.type !== 'get') attr.data['_token'] = csrfToken;

            mergeObject(attr, config);

            if(attr.delay){
                clearTimeout(ajaxTimerDelay);
                ajaxTimerDelay = setTimeout(function (){
                    $.ajax(attr);
                    ajaxTimerDelay = null;
                }, attr.delay);
            }
            else $.ajax(attr);
        }
        else console.error('Ajax url is not set');
    }
    else console.error('Ajax, config undefined');
}

var ajaxConfirm = function (config){
    /*  EXAMPLE ------------------------------------------------------------------------
        ajaxConfirm({
            type: 'post', // DEFAULT GET
            data: {id: 123, param2: 'ok'},
            // confirm: 'Confirm Message?'
            confirm: {
                title: 'Title Message',
                message: 'Confirm Message?',
                actionYes: function(e){
                    close();
                    e.action: function (success, respon){
                        if(success) {
                            console.log(respon);
                        }
                    }
                    ajax(e);
                }
            }
        })
    */
    if(config) {
        if(config.url) {
            let confirm = config.confirm;
            let actionYes = function (obj){
                obj.close();
                ajax(config);
            }

            if(typeof confirm === 'string'){
                msg.dialog.confirm({
                    message: confirm,
                    actionYes: actionYes
                });
            }
            else if(typeof confirm === 'object'){
                msg.dialog.confirm({
                    title: confirm.title || null,
                    message: confirm.message || null,
                    actionYes: confirm.actionYes || actionYes
                });
            }
            else{
                msg.dialog.confirm({
                    actionYes: actionYes
                });
            }
        }
        else console.error('Ajax url is not set');
    }
    else console.error('Ajax, config undefined');
}

var ajaxInput = function (config){
    /*  EXAMPLE ------------------------------------------------------------------------
        ajaxInput({
            type: 'post', // DEFAULT GET
            data: {id: 123, param2: 'ok'},
            input: {
                title: 'Send Data',
                label: 'Input',
                type: 'combo',
                data: {
                    value: 'id',
                    display: 'name',
                    displayTpl: '{name}',
                    items: [
                        {id: 1, name: 'A'},
                        {id: 2, name: 'B'},
                        {id: 3, name: 'C'}
                    ]
                },
                actionYes: function (val){
                    console.log(val);
                }
            }
        });
    */
    if(config) {
        if(config.url) {
            let input = config.input;
            let actionYes = function (val, obj){
                obj.close();
                if(config.params) config.params.value = val;
                else config.params = {value: val};
                ajax(config);
            }

            if(typeof input === 'string'){
                msg.dialog.input({
                    title: input,
                    actionYes: actionYes
                });
            }
            else if(typeof input === 'object'){
                if(!input.actionYes){
                    input.actionYes = actionYes;
                }
                msg.dialog.input(input);
            }
            else{
                msg.dialog.input({actionYes: actionYes});
            }
        }
        else console.error('Ajax url is not set');
    }
    else console.error('Ajax, config undefined');
}

var serializeObject = function (data){
    let result = {};
    data.forEach(function (e){
        result[e.name] = e.value;
    });
    return result;
}

var ucfirst = function (str){
    str = str.toLowerCase();
    let arrStr = str.split(' ');
    let result = [];
    arrStr.forEach(function (e){
        result.push(e.substr(0, 1).toUpperCase() + e.substr(1, e.length));
    });
    return result.join(' ');
}

String.prototype.find = function(text){
    let position = this.search(text);
    if(position >= 0){
        return position + 1;
    }
    return null;
}







