<?php

    $servername="localhost";
    $username="root";
    $password="";
    $dbname="egm_passwords";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if(!$conn){
        die("Connection failed: ".mysqli_connect_error());
    }

    function load_category_list($conn){
        $sql = "SELECT * FROM tbl_categories WHERE category_status = 'Active' AND user_id = '".$_SESSION["user_id"]."' ORDER BY category_name ASC";

        $result = mysqli_query($conn, $sql);


        while($row = mysqli_fetch_assoc($result))
        {
            $output .= '<option value="'.$row["category_id"].'">'.$row["category_name"].'</option>';
        }
        return $output;
    }

?>