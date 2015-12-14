var messenger_two_button_constructor = function (divId) {
    if (typeof divId != 'string') {
        throw  new Error(divIdId + ' is not string');
    }
    if (divId.indexOf('#') != 0) {
        throw  new Error(divId + ' is not identificator of DOM-element ');
    }
    var obj = {
        id: divId,
        _dialog: null,
        actinoYES: null,
        actionNO: null,
        paramYES: null,
        paramNo: null,
        numberOfDefaultButton: 1, // нумерация с 0
        defaultAction: function () {
            var self = this;
            self._dialog.dialog("close");
        },
        afterClose: true,
        init: function () {
            var self = this;
            var obj = $("<div>").attr("id", self.id);
            var objBody = "<table align='center'><tr><td align='center'><div id=\"msgTxt\"></div></td></tr></table>";
            obj.html(objBody);
            $("body").append($(obj));
            self._dialog = $(obj);
            self._dialog.dialog({
                autoOpen: false,
                title: "Сообщение",
                modal: true,
                width: 430,
                open: function () {
                    $(this).parent().find('.ui-dialog-buttonpane button:eq(' + self.numberOfDefaultButton + ')').focus();
                },
                buttons: {
                    "Да": function () {
                        if (typeof self.actinoYES != 'function') {
                            self.defaultAction();
                        }
                        else {
                            if (self.paramYES == null) {
                                self.actinoYES();
                            }
                            else {
                                self.actinoYES(self.paramYES);
                            }
                        }
                        if (self.afterClose) {
                            $(this).dialog("close");
                        }
                    },
                    "Нет": function () {
                        if (typeof self.actionNO != 'function') {
                            self.defaultAction();
                        }
                        else {
                            if (self.paramNo == null) {
                                self.actionNO();
                            }
                            else {
                                self.actionNO(self.paramNo);
                            }
                        }
                        if (self.afterClose) {
                            $(this).dialog("close");
                        }
                    }
                }
            });

        },
        alert: function (title, msg, functionYES, paramYes, functionNO, paramNo, afterClose) {
            var self = this;
            if (functionYES == null || functionYES == undefined) {
                self.actinoYES = self.defaultAction;
            }

            if (paramYes != null) {
                self.paramYES = paramYes;
            }

            if (paramNo != null) {
                self.paramNo = paramNo;
            }

            else {
                self.actinoYES = functionYES;
            }

            if (functionNO == null || functionNO == undefined) {
                self.actionNO = self.defaultAction;
            }
            else {
                self.actionNO = functionNO;
            }
            if (afterClose != null && afterClose != undefined) {
                self.afterClose = afterClose;
            }

            this.init();
            self._dialog.find("#msgTxt").html(msg);
            self._dialog.dialog('option', 'title', title);
            self._dialog.dialog("open");

        }
    }
    obj.init();
    return obj;
}
