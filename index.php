<?php
include("response.php");
?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="./jstree/dist/themes/default/style.min.css" />
    <link rel="stylesheet" href="./style.css" />
    <title>Europe</title>
</head>

<body>
    <div class="container wrapper">
        <div>
            <h2 class="text-center title">Europe</h2>
            <div class="buttons">
                <button class="btn btn-primary" id="show-list">Rozwiń listę</button>
                <button class="btn btn-primary" id="collapse-list">Schowaj listę</button>
                <button class="btn btn-primary" id="sort-btn">Sortuj</button>
            </div>
        </div>
        <div id="tree-container" class="treeview"></div>
    </div>
    <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.2.min.js"></script>
    <script src="./jstree/dist/jstree.min.js"></script>
    <script src="./query.js"></script>
</body>

</html>