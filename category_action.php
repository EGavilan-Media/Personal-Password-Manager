<?php

//category_action.php
include "connection.php";
session_start();
$output = '';
if(isset($_POST["action"])){

  // Fetch category
  if($_POST["action"] == "category_fetch"){

    // Read value
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $rowPerPage = $_POST['length'];
    $columnIndex = $_POST['order'][0]['column'];
    $columnName = $_POST['columns'][$columnIndex]['data'];
    $columnSortOrder = $_POST['order'][0]['dir'];
    $searchValue = $_POST['search']['value'];

    // Search
    $searchQuery = " ";
    if($searchValue != ''){
      $searchQuery = " and (category_created_date LIKE '%".$searchValue."%'
                            OR category_status LIKE '%".$searchValue."%'
                            OR category_name LIKE '%".$searchValue."%' ) ";
    }

    // Total number of records without filtering
    $sqlCategory = mysqli_query($conn,"SELECT count(*) AS allcount FROM tbl_categories WHERE user_id = '".$_SESSION["user_id"]."'");
    $records = mysqli_fetch_assoc($sqlCategory);
    $totalRecords = $records['allcount'];

    // Total number of records with filtering
    $sqlCategory = mysqli_query($conn,"SELECT count(*) AS allcount FROM tbl_categories WHERE user_id = '".$_SESSION["user_id"]."'".$searchQuery);
    $records = mysqli_fetch_assoc($sqlCategory);
    $totalRecordwithFilter = $records['allcount'];

    // Fetch records
    $catQuery = "SELECT * FROM tbl_categories WHERE user_id = '".$_SESSION["user_id"]."'".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT ".$row.",".$rowPerPage;

    $catRecords = mysqli_query($conn, $catQuery);
    $data = array();

    while ($row = mysqli_fetch_assoc($catRecords)){
      $status = '';
      if($row["category_status"] == "Active")
      {
        $status = '<label class="badge badge-success">Active</label>';
      }

      if($row["category_status"] == "Inactive")
      {
        $status = '<label class="badge badge-danger">Inactive</label>';
      }

      $data[] = array(
        "category_created_date"        =>  $row['category_created_date'],
        "category_name"                =>  $row['category_name'],
        "category_status"              =>  $status,
        "update"                       =>  '<button type="button" class="btn btn-primary update_category" id="'.$row['category_id'].'"><i class="fas fa-edit"></i></button>'
      );
    }

    $response = array(
      "draw"                  => intval($draw),
      "iTotalRecords"         => $totalRecords,
      "iTotalDisplayRecords"  => $totalRecordwithFilter,
      "aaData"                => $data

    );

    echo json_encode($response);

  }

  // Create Category
  if($_POST["action"] == "create_category"){

    $category_name = $_POST['category'];
    $category_status = $_POST['status'];

    $sql = "INSERT INTO tbl_categories (user_id, 
                                      category_name, 
                                      category_status, 
                                      category_created_date) 
                                    VALUES('".$_SESSION["user_id"]."',
                                      '$category_name', 
                                      '$category_status', 
                                      NOW())";

    if(mysqli_query($conn, $sql)){
      $output = array(
        'status'           => 'success',
        'message'          => 'New category has been successfully added.'
      );
    }

    echo json_encode($output);

  }

  // Single edit fetch
  if($_POST["action"] == "update_fetch"){


    $sql = "SELECT category_id, category_name, category_status, category_created_date FROM tbl_categories WHERE category_id = '".$_POST["category_id"]."'";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_row($result);

    $output = array(
      "category_id"		                =>	$row[0],
      "category_name"		              =>	$row[1],
      "category_status"		            =>	$row[2]
    );

    echo json_encode($output);

  }
  // Update category
  if($_POST["action"] == "update_category"){

    $category_id = $_POST['category_id'];
    $category_name = $_POST['category'];
    $category_status = $_POST['status'];

    $sql = "UPDATE tbl_categories SET category_name = '$category_name',
                                category_status = '$category_status'
                                WHERE category_id = '$category_id'";

    $result = mysqli_query($conn, $sql);

    $output = array(
      'status'        => 'success',
      'message'       => 'Category has been successfully updated.'
    );

      echo json_encode($output);

  }



}

?>