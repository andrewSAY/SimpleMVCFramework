function mainUserManager() {
    var grid = null;
    var groupTree = null;

    var formDialog = form_dialog_construct('#form_dialog', '#error_dialog', afterSuccessPostAction);


    $('#tabs').tabs({
        beforeLoad: function (event, ui) {
            ui.panel.text('Получение данных с сервера...');
            ui.jqXHR.fail(function (jqXHR) {
                ui.panel.html('<h1>' + jqXHR.status + ' ' + jqXHR.statusText  + '</h1><br/> Не удалось загрузить данные с сервера. Пожалуйста, повторите попытку или обратитесь к администратору.');
            });
            ui.jqXHR.success(function (jqXHR) {
                ui.panel.html(jqXHR.responseText);
                if (typeof table_grid_construct == 'function') {
                    grid = table_grid_construct('#grid', formDialog);
                }
                if (typeof tree_view_group_constructor == 'function') {
                    groupTree = tree_view_group_constructor('#groupTree', formDialog);
                }
            });
        },
        load: function (event, ui) {
            if (ui.panel.find(grid.tableId).length > 0) {
               grid.applyDataTable();
            }
            if(ui.panel.find(groupTree.id).length>0)
            {
                groupTree.applyTree();
                groupTree.loadDataOnTree(dataGroupTree);
            }
        }

    });



    function afterSuccessPostAction(data, submited) {
        if (data.entity == 'user') {
            if (data.action == 'create') {
                grid.addTR(data.view);
            }
            if (data.action == 'create_in_group') {
                groupTree.updateNode(data.view);
            }
            if (data.action == 'update') {
                grid.replaceSelectedTR(data.view);
            }
            if (data.action == 'delete') {
                grid.removeSelectedTR();
            }
        }
        if(data.entity == 'group')
        {
            if(data.action == 'create')
            {
                groupTree.appendNodeToRoot(data.view);
            }
            if(data.action == 'create_in')
            {
                groupTree.appendNodeToNode(data.view, data.owner);
            }
            if(data.action == 'update')
            {
                groupTree.updateNode(data.view);
            }
            if(data.action == 'update_users_list')
            {
                groupTree.updateNode(data.view);
            }
            if(data.action == 'delete')
            {
                groupTree.removeRNode();
            }
        }
        submited.close();
    }
}
