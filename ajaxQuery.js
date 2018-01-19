$(document).ready(function () {
    $.ajax({
        url: "fetch.php",
        method: "POST",
        dataType: "json",
        success: function (data) {
            $('#treeview').treeview({
                data: data,
                backColor: "#073f57",
                color: "#fefefe",
                onhoverColor: "#073f57"
            });
            console.log(data);
            $('#treeview').treeview('collapseAll', { silent: true });
        }
    });

    $('#show-list').on('click', function(e) {
        $('#treeview').treeview('expandAll', { silent: true });
    });

    $('#collapse-list').on('click', function(e) {
        $('#treeview').treeview('collapseAll', { silent: true });
    });
});