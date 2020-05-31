<?php

//note_action.php
include "connection.php";
session_start();
$output = '';
if(isset($_POST["action"])){

  // Fetch User Notes
  if($_POST["action"] == "note_fetch"){

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
      $searchQuery = " and (created_date LIKE '%".$searchValue."%'
                            OR title LIKE '%".$searchValue."%'
                            OR description LIKE '%".$searchValue."%' ) ";
    }

    // Total number of records without filtering
    $sqlNote = mysqli_query($conn,"SELECT count(*) AS allcount FROM tbl_notes WHERE user_id = '".$_SESSION["user_id"]."'");
    $records = mysqli_fetch_assoc($sqlNote);
    $totalRecords = $records['allcount'];

    // Total number of records with filtering
    $sqlNote = mysqli_query($conn,"SELECT count(*) AS allcount FROM tbl_notes WHERE user_id = '".$_SESSION["user_id"]."'".$searchQuery);
    $records = mysqli_fetch_assoc($sqlNote);
    $totalRecordwithFilter = $records['allcount'];

    // Fetch records
    $noteQuery = "SELECT * FROM tbl_notes WHERE user_id = '".$_SESSION["user_id"]."'".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT ".$row.",".$rowPerPage;

    $notesRecords = mysqli_query($conn, $noteQuery);
    $data = array();

    while ($row = mysqli_fetch_assoc($notesRecords)){

      $description = $row['description'];

      if(strlen($description)>100){
        $description = substr($row['description'], 0, 100);
        $description = $description."...";
      }

      $data[] = array(
        "created_date"    =>  $row['created_date'],
        "title"           =>  $row['title'],
        "description"     =>  $description,
        "view"            =>  '<button type="button" class="btn btn-info view_note" data-toggle="modal" data-target="#readModal" id="'.$row['id'].'"><i class="fas fa-eye"></i></button>',
        "update"          =>  '<button type="button" class="btn btn-primary update_note" id="'.$row['id'].'"><i class="fas fa-edit"></i></button>',
        "delete"          =>  '<button type="button" class="btn btn-danger delete_note" id="'.$row['id'].'"><i class="fas fa-trash-alt"></i></button>'
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

  // Create Note
  if($_POST["action"] == "create_note"){

    $title = $_POST['title'];
    $note = $_POST['note'];

    $sql = "INSERT INTO tbl_notes (user_id,
                                title, 
                                description, 
                                created_date) 
                          VALUES('".$_SESSION["user_id"]."',
                              '$title',
                              '$note',
                              NOW())";

    if(mysqli_query($conn, $sql)){
      $output = array(
        'status'        => 'success',
        'message'       => 'New note has been successfully added.'
      );
    }

    echo json_encode($output);

  }

  // Single fetch
  if($_POST["action"] == "single_fetch"){

    $sql = "SELECT title, description, created_date FROM tbl_notes WHERE id = '".$_POST["note_id"]."'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_row($result);

    $output = array(
      "title"		            =>	$row[0],
      "note"      		      =>	$row[1],
      "created_date"		    => 	$row[2]
    );

    echo json_encode($output);

  }

  // Single edit fetch
  if($_POST["action"] == "update_fetch"){

    $sql = "SELECT id, title, description FROM tbl_notes WHERE id = '".$_POST["note_id"]."'";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_row($result);

    $output = array(
      "note_id"		    =>	$row[0],
      "title"		      =>	$row[1],
      "note"		      =>	$row[2]
    );

    echo json_encode($output);

  }

  // Single update fetch
  if($_POST["action"] == "update_note"){

    $note_id = $_POST['note_id'];
    $title = $_POST['title'];
    $note = $_POST['note'];

    $sql = "UPDATE tbl_notes SET title = '$title',
                            description = '$note'
                            WHERE id = '$note_id'";

    if(mysqli_query($conn, $sql)){

      $output = array(
        'status'        => 'success',
        'message'	    	=> 'Note has been updated successfully.',
      );

    }

    echo json_encode($output);

  }

  // Delete note
  if($_POST["action"] == "delete_note"){

    $sql = "DELETE FROM tbl_notes WHERE id = '".$_POST["note_id"]."'";

    if(mysqli_query($conn, $sql)){

      $output = array(
        'status'        => 'success',
        'message'	    	=> 'Note has been deleted successfully.',
      );

    }

    echo json_encode($output);

  }
}

?>