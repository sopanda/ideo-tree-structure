$(document).ready(function () {
    $('#tree-container').jstree({
        'plugins': ['contextmenu', 'dnd', 'wholerow', 'types'],
        'core': {
            'data': {
                'url': 'response.php?operation=get_node',
                'data': function (node) {
                    return {
                        'id': node.id
                    };
                },
                dataType: "json"
            },
            'check_callback': true,
            'themes': {
                'responsive': false
            }
        }
    }).on('create_node.jstree', function (e, data) {
        $.get('response.php?operation=create_node', {
                'id': data.node.parent,
                'position': data.position,
                'text': data.node.text
            })
            .done(function (d) {
                data.instance.set_id(data.node, d.id);
            })
            .fail(function () {
                data.instance.refresh();
            });
    }).on('rename_node.jstree', function (e, data) {
        $.get('response.php?operation=rename_node', {
                'id': data.node.id,
                'text': data.text
            })
            .fail(function () {
                data.instance.refresh();
            });
    }).on('delete_node.jstree', function (e, data) {
        $.get('response.php?operation=delete_node', {
                'id': data.node.id
            })
            .fail(function () {
                data.instance.refresh();
            });
    });

    $("#show-list").on("click", function (e) {
        $('#tree-container').jstree('open_all');
    });

    $("#collapse-list").on("click", function (e) {
        $('#tree-container').jstree('close_all');
    });

    $("#sort-btn").on("click", function (e) {

    });

});