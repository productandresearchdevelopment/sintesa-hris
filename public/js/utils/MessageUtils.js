var popover = {
    init: function () {
        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
        const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
        $(document).on("click", ".popover .close-popover", function (event) {
            $(this).closest(".popover").popover('hide');
        });
    },
    set: function (element, message){
        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
        const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))

        if(typeof message == "object"){
            if(message.title){
                let xtpl = `<table width="100%" class="fs-13">
                                <tr>
                                    <td valign="center" class="fw-smbold fs-14" style="padding-right: 30px;">{0}</td>
                                    <td valign="center" width="2" class="fs-14">{2}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="fs-13" style="padding-top: 5px">{1}</td>
                                </tr>
                            </table>`;
                let close = ((message.close == undefined) || (message.close)) ? "<i href='#' class='bi bi-x-lg pointer close-popover'></i>" : '';
                message = String.format(xtpl, message.title, message.content, close);
            }
            else message = '<span class="fs-13">'+message.content+'</span>';
        }

        let xpopover = new bootstrap.Popover(element, {
            container: 'body',
            content: function () { return message; },
            html: true,
            sanitize: false,
            placement: 'top',
            trigger: 'focus',
        });

        $(document).on("click", ".close-popover", function () {
            xpopover.hide();
        });

        setTimeout(function (){

        }, 200);

    }
};

var msg = {
    toast: {
        show: function (title, message, option) {
            let opt = option || {};

            let position = opt.position || 'top-center';
            let animation = opt.animation || 'slide';
            let delay = opt.timeout || 3000;
            let bgcolor = opt.bgcolor || '#cccccc';
            let textColor = opt.textColor || '#333333';
            let closeable = opt.closeable || true;
            let textAlign = opt.textAlign || 'left'
            let icon = opt.icon || false
            let loaderBg = opt.loaderColor || '#999999';

            $.toast({
                text: message,
                heading: title,
                showHideTransition: animation,
                position: position,
                bgColor: bgcolor,
                textColor: textColor,
                textAlign: textAlign,
                loaderBg: loaderBg,
                hideAfter: delay,
                allowToastClose: closeable,
                icon: icon,
            });
        },

        info: function (message) {
            this.show('Info', message, {
                bgcolor: 'var(--color-blue)',
                textColor: 'var(--primary-text-color)',
                loaderColor: 'var(--color-blue-second)',
                icon: 'info',
            });
        },

        warning: function (message) {
            this.show('Warning', message, {
                bgcolor: 'var(--color-orange)',
                textColor: '#FFFFFF',
                loaderColor: 'var(--color-orange-second)',
                icon: 'warning',
            });
        },

        error: function (message) {
            this.show('Error', message, {
                bgcolor: 'var(--color-red)',
                textColor: 'var(--primary-text-color)',
                loaderColor: 'var(--color-red-second)',
                icon: 'error',
            });
        },

        success: function (message) {
            if (message === undefined) message = 'Data was successfully sent, and has been received';

            this.show('Success', message, {
                bgcolor: 'var(--color-green)',
                textColor: 'var(--primary-text-color)',
                loaderColor: 'var(--color-green-second)',
                icon: 'success',
                timeout: 3000
            });
        },

        responFailed: function (respon) {
            let title = 'Failed';
            let message = 'Server Error';

            if (typeof respon == "object") {
                if (respon.response) respon = respon.response;
                if (respon.status == 200) {
                    title = 'Failed';
                    message = respon.statusText;
                    if (respon.responseText) {
                        let res = JSON.parse(respon.responseText);
                        title = 'Failed';
                        message = res.message;
                    }
                } else if (respon.status) {
                    title = 'Error (' + respon.status + ')';
                    message = respon.statusText;
                    if (respon.responseText) {
                        let res = JSON.parse(respon.responseText);
                        title = 'Failed ' + respon.status + ' (' + respon.statusText + ')';
                        message = res.message;
                    }
                }
            } else if (respon) message = respon;

            this.show(title, message, {
                bgcolor: 'var(--color-red)',
                textColor: 'var(--primary-text-color)',
                loaderColor: 'var(--color-red-second)',
                icon: 'error',
                timeout: 5000,
            });
        },
    },

    dialog: {
        confirm: function (config) {
            let me = this;

            let prop = {
                title: 'Confirm',
                message: 'Process will continue?',
                autoClose: true,
                btnYes: 'Yes',
                btnNo: 'No',
                onShow: function () {
                },
                onHide: function () {
                },
                actionYes: function () {
                },
                actionNo: function () {
                    me.modal.hide();
                },
            }

            me.el = '#msg-dialog-confirm';

            me.modal = new bootstrap.Modal(me.el);

            me.show = function (){
                me.modal.show();
            }

            me.close = function (){
                me.modal.hide();
            }

            me.init = function () {
                $(me.el + '-title').html(me.title);
                $(me.el + '-message').html(me.message);
                $(me.el + '-btn-yes').html(me.btnYes);
                $(me.el + '-btn-no').html(me.btnNo);

                $(me.el).on('shown.bs.modal', function (){
                    me.onShow(me);
                });

                $(me.el).on('hidden.bs.modal', function (){
                    me.onHide(me);
                });

                $(me.el + '-btn-yes').off('click').on('click', function (){
                    if(me.autoClose) me.close();
                    me.actionYes(me);
                });

                $(me.el + '-btn-no').off('click').on('click', function (){
                    me.actionNo(me);
                });

                me.show();
            }

            if (config) {
                mergeObject(prop, config);
            }

            mergeObject(me, prop);

            me.init();
        },

        input: function (config){
            /* EXAMPLE INPUT DIALOG ------------------------------------------------------------------------------
               msg.dialog.input({
                    title: 'Change Password',
                    label: 'New Password',
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
               });
            */

            let me = this;
            let input = null;
            let container = null;
            let conf = config || {};

            let prop = {
                type: 'text',
                title: 'Send',
                label: 'Please input this value?',
                btnYes: 'Send',
                btnNo: 'Cancel',
                allowBlank: false,
                data: null,
                onShow: function () {},
                onHide: function () {},
                actionYes: function () {},
                actionNo: function () { me.modal.hide(); },
                action: function (){},
            }

            if(conf.data) {
                if(Array.isArray(conf.data)) {
                    conf.data = {
                        value: 'id',
                        display: 'name',
                        displayTpl: '{name}',
                        items: conf.data
                    }
                }
                else if (typeof conf.data === "object"){
                    if(!conf.data.value)  conf.data.value = 'id';
                    if(!conf.data.display)  conf.data.display = 'name';
                    if(!conf.data.items)  conf.data.items = [];
                    if(!conf.data.displayTpl){
                        conf.data.displayTpl = '{'+conf.data.display+'}';
                    }
                }
            }

            me.el = '#msg-dialog-input';
            me.modal = new bootstrap.Modal(me.el);

            me.setupElement = function (){
                input = {
                    text: $('#msg-dialog-input-text'),
                    password: $('#msg-dialog-input-password'),
                    date: $('#msg-dialog-input-date'),
                    combo: $('#msg-dialog-input-combo'),
                }

                container = {
                    text: $('.dialoginput.text'),
                    password: $('.dialoginput.password'),
                    date: $('.dialoginput.date'),
                    combo: $('.dialoginput.combo'),
                }
            }

            me.init = function () {
                $(me.el + '-title').html(me.title);
                $(me.el + '-label').html(me.label);
                $(me.el + '-btn-yes').html(me.btnYes);
                $(me.el + '-btn-no').html(me.btnNo);

                $(me.el).on('shown.bs.modal', function (){
                    $(me.el + '-value').val('');
                    me.onShow(me);
                });

                $(me.el).on('hidden.bs.modal', function (){
                    me.onHide(me);
                });

                $(me.el + '-btn-yes').off('click').on('click', function (){
                    me.handlerButtonYes();
                });

                $(me.el + '-btn-no').off('click').on('click', function (){
                    me.handlerButtonNo();
                });

                me.show();
            }

            me.handlerButtonYes = function (){
                let value = null;
                let item = null;

                if (me.type === 'combo') {
                    value = input.combo.val();
                    if(value){
                        let src = {};
                        src[me.data.value] = value;
                        item = me.data.items.find(src);
                    }
                }
                else if (me.type === 'date') {
                    value = input.date.val();
                    if(value) value = value.toDate('d/m/Y').format('Y-m-d');
                }
                else if (me.type === 'password') value = input.password.val();
                else value = input.text.val();

                if(value || me.allowBlank) {
                    me.actionYes(value, me, item);
                    me.action('yes', me, value, item);
                }
                else {
                    msg.toast.warning("Input value can't be empty");
                }
            }

            me.handlerButtonNo = function (){
                me.actionNo(me);
                me.action('no', me);
            }

            me.show = function (){
                me.modal.show();

                $('.dialoginput').hide();

                if(!input && !container) { me.setupElement(); }

                if(me.type === 'date') {
                    $('.datepicker').datepicker({
                        uiLibrary: 'bootstrap5',
                        format: 'dd/mm/yyyy',
                    });
                    input.date.val('');
                    container.date.show();
                }
                else if(me.type === 'password') {
                    let btnPassword = $('#msg-dialog-input-btn-password');
                    btnPassword.off('click').on('click', function () {
                        let type = input.password.attr("type");
                        if (type === 'password') {
                            input.password.attr("type", "text");
                            btnPassword.html('<i class="bi bi-eye"></i>');
                        } else {
                            input.password.attr("type", "password");
                            btnPassword.html('<i class="bi bi-eye-slash"></i>');
                        }
                    });
                    input.password.val('');
                    container.password.show();
                }
                else if(me.type === 'combo') {
                    input.combo.html(null);
                    if(me.data) {
                        let data = me.data;
                        me.data.items.forEach(function (e){
                            let tpl = String.format(data.displayTpl, e);
                            let option = String.format('<option value="{0}">{1}</option>', e[data.value], tpl);
                            input.combo.append(option);
                        });
                    }
                    input.combo.val(null);
                    container.combo.show();
                }
                else {
                    input.text.val('');
                    container.text.show()
                }
            }

            me.close = function (){
                me.modal.hide();
            }

            mergeObject(prop, conf);
            mergeObject(me, prop);

            me.init();
        }
    }
};






