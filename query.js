$(document).ready(function () {
    $('#tree-container').jstree({
        'plugins': ['contextmenu', 'dnd', 'wholerow', "sort"],
        'core': {
            'data': {
                'url': 'response.php?operation=get_node',
                'data': function (node) {
                    return {
                        'id': node.id
                    };
                }
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
        var new_Name = data.text;
        var myReg = new RegExp("[^A-Za-z0-9]");
        if (myReg.test(new_Name)) {
            alert("Special characters are not allowed.");
            $("#tree-container").jstree(true).refresh();
        } else {
            $.get('response.php?operation=rename_node', {
                    'id': data.node.id,
                    'text': data.text
                })
                .fail(function () {
                    data.instance.refresh();
                });
        }
    }).on('delete_node.jstree', function (e, data) {
        e.preventDefault();
        $("#myModal").modal('show');
        $("#deleteWithChild").on("click", function (e) {
            $.get('response.php?operation=delete_withChild', {
                    'id': data.node.id
                })
                .done(function() {
                    $("#tree-container").jstree(true).refresh();
                })
                .fail(function () {
                    data.instance.refresh();
                });
        });
        $("#deleteOnlyMe").on("click", function (e) {
            $.get('response.php?operation=delete_onlyMe', {
                    'id': data.node.id,
                    'node_parent': data.node.parent
                })
                .done(function() {
                    $("#tree-container").jstree(true).refresh();
                })
                .fail(function () { 
                    data.instance.refresh();
                });
        });
    }).bind("move_node.jstree", function (e, data) {
        console.log(data);
        $.get('response.php?operation=move_node', {
                'id': data.node.id,
                'new_parent': data.parent
            })
            .fail(function () {
                data.instance.refresh();
                console.log("fail to move");
            });
    });

    $("#show-list").on("click", function (e) {
        $('#tree-container').jstree('open_all');
    });

    $("#collapse-list").on("click", function (e) {
        $('#tree-container').jstree('close_all');
    });


});