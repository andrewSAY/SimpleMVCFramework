var form_dialog_construct = function(divId, errorDialogId, actionAfterSuccessPost)
{
    if (typeof divId != 'string') {
        throw  new Error(divIdId + ' is not string');
    }
    if (typeof actionAfterSuccessPost != 'function') {
        throw  new Error(actionAfterSuccessPost + ' is not function ');
    }
    if (divId.indexOf('#') != 0) {
        throw  new Error(divId + ' is not identificator of DOM-element ');
    }
    var obj =  {
        errorDialog: error_dialog_construct(errorDialogId),
        _dialog: $(divId).dialog({
            autoOpen: false,
            closeOnEscape: false,
            resizable: false,
            title: "",
            modal: true,
            width: 430,
            open: function () {
                $(this).parent().find('.ui-dialog-buttonpane button:eq(1)').focus();
                $(this).parent().find('.ui-dialog-titlebar button:eq(0)').hide();
            }
        }),
        actionFirstButton: function(){
            var button = $(this).find('.form_place form:eq(0) input[type=submit]:eq(0)');
            $(button).click();
        },
        actionSecondButton: function () {
            $(this).dialog("close");
        },
        actionAfterPost: actionAfterSuccessPost,
        open: function (caption, url, width,captionFirstButton, captionSecondButton) {
            var self = this;
            if(captionFirstButton == null)
            {
                captionFirstButton= "Сохранить";
            }
            if(captionSecondButton == null)
            {
                captionSecondButton = "Закрыть";
            }
            if(width == null)
            {
                width = 430;
            }
            self._dialog.dialog('option', 'title', caption);
            self._dialog.dialog('option', 'width', width);
            self._dialog.dialog('option', 'buttons',[
                {
                    text: captionFirstButton,
                    click: self.actionFirstButton
                },
                {
                    text: captionSecondButton,
                    click: self.actionSecondButton
                }
            ]);
            self._dialog.off('submit');
            self._dialog.find('.status_bar').show().find('span:eq(0)').text('Загрузка формы');
            self._dialog.find('.form_place').html('');
            var bottomPanel = $(self._dialog.parent().find('.ui-dialog-buttonpane'));
            bottomPanel.hide();
            self._dialog.find('.form_place').load(url, null, function (responseText, textStatus, jqXHR) {
                self._dialog.find('.status_bar').hide();
                self.errorHandle(jqXHR, textStatus, url);
                bottomPanel.show();

            });
            self._dialog.dialog('open');
            self._dialog.on('submit', '.form_place form:eq(0)', function (e) {
                e.preventDefault();
                self._dialog.find('.status_bar').show().find('span:eq(0)').text('Обработка запроса');
                bottomPanel.hide();
                var data = $(this).serializeArray();
                $.post($(this).attr('action'), data)
                    .done(function (data) {
                        if (data.status == 'ok') {
                            self.actionAfterPost(data, self);
                        }
                        if (data.status == 'failed') {
                            self._dialog.find('.form_place').html(data.view);
                        }
                    })
                    .fail(function(data){
                        self.errorDialog.open('Возникла ошибка при обработке ответа от сервера');
                        console.log(print_object(data));
                    })
                    .always(function (jqXHR, textStatus) {
                        bottomPanel.show();
                        self._dialog.find('.status_bar').hide();
                        self.errorHandle(jqXHR, textStatus, url);
                    });
                return false;
            });

        },
        close: function(){
            var self = this;
            self._dialog.dialog('close');
        },
        postData: function(url, data, afterSuccessAction, afterFailedAction)
        {
            var self = this;
            $.post(url, data)
                .done(function (data) {
                    if (data.status == 'ok' && typeof afterSuccessAction == 'function') {
                        afterSuccessAction(data);
                    }
                    if (data.status == 'failed' && typeof afterFailedAction == 'function') {
                        afterFaildeAction(data);
                    }
                })
                .always(function (jqXHR, textStatus) {
                    self.errorHandle(jqXHR, textStatus, url);
                });
        },

        errorHandle: function (jqXHR, textStatus, url) {
            var self = this;
            if (textStatus == "error") {
                self._dialog.dialog("close");
                var msg = "К сожалению, при выполнении операции была получена ошибка: " + '<b>' + jqXHR.status +" "+ jqXHR.statusText + '</b><br/>'+" ("+url+")";
                self.errorDialog.open(msg); //+ jqXHR.responseText);
                return;
            }
        }
    }
    return obj;
}