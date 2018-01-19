<?php
    $connect = mysqli_connect("55.mysql.ideo", "root", "root", "wp-szkolenie-3");
    $query = "
                SELECT * FROM drzewko
             ";
    $result = mysqli_query($connect, $query);

    while($row = mysqli_fetch_array($result)) {
        $sub_data["id"] = $row["id"];
        // $sub_data["title"] = $row["title"];
        $sub_data["text"] = $row["title"];
        $sub_data["parent_id"] = $row["parent_id"];
        $data[] = $sub_data;
    }

    foreach($data as $key => &$value) {
        $output[$value["id"]] = &$value;
    }

    foreach($data as $key => &$value) {
        if($value["parent_id"] && isset($output[$value["parent_id"]])) {
                $output[$value["parent_id"]]["nodes"][] = &$value;
            }
    }

    foreach($data as $key => &$value) {
        if($value["parent_id"] && isset($output[$value["parent_id"]])) {
                unset($data[$key]);
            }
    }
    
    echo json_encode($data);
?>