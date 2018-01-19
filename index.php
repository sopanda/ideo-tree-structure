<?php 

    $servername="55.mysql.ideo";
    $username="root";
    $password="root";
    $dbname="wp-szkolenie-3"; 

    // Create connection 
    $conn = new mysqli($servername, $username, $password, $dbname); 

    // Check connection 
    if ($conn->connect_error) 
    { 
        die("Connection failed: " . $conn->connect_error); 
        } 
        $sql = "SELECT ID, Title, ParentID FROM drzewko"; 
        $result = $conn->query($sql); if ($result->num_rows > 0) 
        { 
            // output data of each row 
            while($row = $result->fetch_assoc()) 
            { 
                echo "id: " . $row["ID"]. "<br>". 
                     " - Name: " . $row["Title"]."<br>". 
                     " - Parent: " . $row["ParentID"]. "<br>"; 
            } 
        }  else { 
                    echo "0 results"; 
                } 
                    $conn->close(); 
?>