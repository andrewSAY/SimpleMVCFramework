var error_dialog_construct = function(divId)
{
    if (typeof divId != 'string') {
        throw  new Error(divIdId + ' is not string');
    }
    if (divId.indexOf('#') != 0) {
        throw  new Error(divId + ' is not identificator of DOM-element ');
    }
    var obj = {

        _dialog: $(divId).dialog({
            autoOpen: false,
            title: "Сообщение об ошибке",
            modal: true,
            width: 1030,
            buttons: {
                "Ok": function () {
                    $(this).dialog("close");
                }
            }
        }),
        open: function (message) {
            var self = this;
            self._dialog.find('.message').html(message);
            self._dialog.dialog('open');
        }

    }
    return obj;
}
