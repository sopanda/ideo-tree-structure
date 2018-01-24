<?php
include("response.php");
?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="./jstree/dist/themes/default/style.min.css" />
    <link rel="stylesheet" href="./style.css" />
    <title>Europe</title>
</head>

<body>
    <div class="container wrapper">
        <div>
            <h2 class="text-center title">Europe</h2>
            <div class="buttons">
                <button class="btn btn-primary single-btn" id="show-list">Rozwiń listę</button>
                <button class="btn btn-primary single-btn" id="collapse-list">Schowaj listę</button>
                <button class="btn btn-primary single-btn" id="new-hl">New High Level</button>
            </div>
        </div>
        <div id="tree-container" class="treeview"></div>
        <!-- Modal HTML -->
        <div id="myModal" class="modal fade">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Delete</h4>
                    </div>
                    <div class="modal-body">
                        <p>Do you want delete parent with childs?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger single-btn" id="deleteWithChild" data-dismiss="modal">Yes</button>
                        <button type="button" class="btn btn-danger single-btn" id="deleteOnlyMe" data-dismiss="modal">Only parent</button>
                        <button type="button" class="btn btn-primary single-btn" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="./jstree/dist/jstree.min.js"></script>
    <script src="./query.js"></script>
</body>

</html>