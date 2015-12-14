var table_grid_construct = function (tableId, formDialog) {
    if (typeof tableId != 'string') {
        throw  new Error(tableId + ' is not string');
    }
    if (tableId.indexOf('#') != 0) {
        throw  new Error(tableId + ' is not identificator of DOM-element ');
    }
    var tObject = {
        currentRowIndex: -1,
        selectedRowIndex: -1,
        selectedRow: null,
        tableId: tableId,
        table: null,
        dataTable: null,
        init: function () {
            var self = this;
            var selector = self.tableId + ' tbody tr';
            $('#tabs')
                .off('click')
                .off('dblclick')
                .off('mouseenter')
                .off('mouseleave');


            $('#tabs').on('mouseenter', selector,function () {
                self.currentRowIndex = $(this).index();
            }).on('mouseleave', selector,function () {
                self.currentRowIndex = -1;
            }).on('click', selector, function () {
                self.selectedRowIndex = $(this).index();
                $(self.tableId + ' tbody tr').removeClass('selected_row');
                $(this).addClass('selected_row');
            });

            $('#tabs').on('dblclick', selector + ' td', function () {
                if (typeof formDialog == 'object') {
                    if (typeof formDialog.open == 'function') {
                        formDialog.open('Редактирование пользователя ' + $(this).parent('tr').find('td:eq(0)').text(),
                            $(this).parent('tr').find('input[type=hidden]').val());
                    }
                }
            });
            $('#tabs').on('click', selector + ' a', function (e) {
                e.preventDefault();
                if (typeof formDialog == 'object') {
                    if (typeof formDialog.open == 'function') {
                        formDialog.open('Удаление пользователя ' + $(this).parents('tr').find('td:eq(0)').text(),
                            $(this).attr('href'),430, "Да", "Нет");
                    }
                }
            });
            $('#tabs').on('click', '.grid_actions li a', function (e) {
                e.preventDefault();
                if (typeof formDialog == 'object') {
                    if (typeof formDialog.open == 'function') {
                        formDialog.open('Создание нового пользователя', $(this).attr('href'));
                    }
                }
            });

        },
        replaceSelectedTR: function (newTD) {
            var self = this;
            newTD = JSON.parse(newTD);
            self.dataTable.row($('.selected_row')[0]).data(newTD.data)
        },
        addTR: function (newTD) {
            var self = this;
            var data = JSON.parse(newTD);
            self.dataTable.row.add(
                data.data
            ).draw(false);
        },
        removeSelectedTR: function () {
            var self = this;
            self.dataTable.rows('.selected_row').remove().draw(false);
        },
        applyDataTable: function () {
            var self = this;
            if (self.table == null) {
                self.table = $(self.tableId);
            }
            self.dataTable = self.table.DataTable({
                language: {
                    url: '/libs/dataTables1.10.5/translations/dataTable.ru.translate'
                },
                lengthMenu: [5, 10, 25, 50],
                autoWidth: false
            });
            self.table.on( 'page.dt', function (){
                self.unselectTR();
            });
        },
        unselectTR: function () {
            var self = this;
            $(self.tableId + ' tbody tr.selected_row').removeClass('selected_row');
            self.selectedRowIndex = -1;
        }
    };

    tObject.init();
    return tObject;
}
