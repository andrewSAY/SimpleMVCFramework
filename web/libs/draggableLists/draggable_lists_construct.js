var draggable_lists_contruct = function (id, data, options) {

    var divO = null;
    var divS = null;
    var divR = null;
    var sourceList = new Array();
    var recipientList = new Array();
    var _options = {
        onCreateNode: function (event, node, div) {
        },
        onMoveNode: function (event, node, sourceList, recipientList, direction) {
        },
        sort: function (x, y) {
            return (x.name > y.name);
        }
    }

    /*
     data structure
     data = {
     sourceList: [
     node:{
     name: 'name of node'
     ....
     }
     ],
     recipientList: [
     node:{
     name: 'name of node'
     ....
     }
     ]
     }
     */
    _construct(id, data, options);
    function _construct(id, data, options) {
        if (typeof id != 'string') {
            throw  new Error(id + ' is not string');
        }
        if (typeof data != 'object') {
            throw  new Error(data + ' is not object');
        }
        if (id.indexOf('#') != 0) {
            throw  new Error(id + ' is not identificator of DOM-element ');
        }

        for (
            var p in options) {
            _options[p] = options[p];
        }

        divO = $($(id)).addClass('dl_container');
        divS = $($('<div>')).addClass('dl_source_column').addClass('dl_column');
        divR = $($('<div>')).addClass('dl_recipient_column').addClass('dl_column');
        divS.appendTo(divO);
        divR.appendTo(divO);

        sourceList = data.sourceList;
        recipientList = data.recipientList;

        create_view();

    }

    function create_view() {
        resort(recipientList, false);
        resort(sourceList, false);

        divR.empty();
        divS.empty();

        for (
            var i in sourceList) {
            var node = create_node_element(sourceList[i]);
            node.element.appendTo(divS);
            node.inListPosition = i;
            node.element.trigger('dl.create_element', [node, node.element]);
        }

        for (
            var i  in recipientList) {
            var node = create_node_element(recipientList[i]);
            node.element.appendTo(divR);
            node.inListPosition = i;
            node.element.trigger('dl.create_element', [node, node.element]);
        }
    }

    function create_node_element(node) {
        var divNode = $($('<div>')).addClass('dl_column_node');
        var pName = $($('<p>')).addClass('dl_column_node_name');
        pName.text(node.name);
        pName.appendTo(divNode);

        for (
            f in node) {
            divNode.attr(f, node[f]);
        }
        divNode.on('dl.create_element', _options.onCreateNode);
        divNode.on('dl.move_element', _options.onMoveNode);

        divNode.on('click', function () {
            var left = 0;
            var direction;
            divNode.attr('style', 'position: absolute')

            if (divNode.parent('.dl_source_column').length == 1) {
                left = divR.position().left;
                direction = 'right';
            }
            if (divNode.parent('.dl_recipient_column').length == 1) {
                left = divS.position().left;
                direction = 'left';
            }
            $(this).animate({'left': left}, {
                duration: 100,
                complete: function () {
                    divNode.attr('style', '');
                    if (direction == 'right') {
                        divNode.appendTo(divR);
                        sourceList.splice(node.inListPosition, 1);
                        recipientList.push(node);
                        resort(recipientList, true, divR);
                        resort(sourceList, true, divS);

                    }
                    if (direction == 'left') {
                        divNode.appendTo(divS);
                        recipientList.splice(node.inListPosition, 1);
                        sourceList.push(node);
                        resort(sourceList, true, divS);
                        resort(recipientList, true, divR);
                    }
                    divNode.trigger('dl.move_element', [node, sourceList,recipientList,direction]);
                }
            });
        });
        node.element = divNode;
        return node;
    }

    function resort(list, withElements, divSort) {
        list.sort(_options.sort);

        if (withElements) {
            for (
                i = 0; i < list.length; i++) {
                list[i].inListPosition = i;
                if (i == 0) {
                    list[i].element.prependTo(divSort);
                    continue;
                }

                list[i].element.insertAfter(list[i - 1].element);
            }
        }

    }

    this.getSourceList = function() {
        return sourceList;
    };

    this.getRecipientList = function() {
        return recipientList;
    };

    this.getContainer = function(){
        return divO;
    }

    this.getSourceContainer = function(){
        return divS;
    }

    this.getRecipientContainer = function(){
        return divR;
    }

}
