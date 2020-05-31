<?php

//site_action.php

include "connection.php";
session_start();
$output = '';
if(isset($_POST["action"])){

  // Fetch expense
  if($_POST["action"] == "site_fetch"){

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
      $searchQuery = " and (site_id LIKE '%".$searchValue."%'
                            OR site_url LIKE '%".$searchValue."%'
                            OR site_name LIKE '%".$searchValue."%'
                            OR site_username LIKE '%".$searchValue."%'
                            OR site_password LIKE '%".$searchValue."%' ) ";
    }

    // Total number of records without filtering
    $sqlSite = mysqli_query($conn,"SELECT count(*) AS allcount FROM tbl_sites WHERE user_id = '".$_SESSION["user_id"]."'");
    $records = mysqli_fetch_assoc($sqlSite);
    $totalRecords = $records['allcount'];

    // Total number of records with filtering
    $sqlSite = mysqli_query($conn,"SELECT count(*) AS allcount FROM tbl_sites WHERE user_id = '".$_SESSION["user_id"]."'".$searchQuery);
    $records = mysqli_fetch_assoc($sqlSite);
    $totalRecordwithFilter = $records['allcount'];

    // Fetch records
    $siteQuery="SELECT site.site_id,
                    site.site_url,
                    site.site_name,
                    site.site_username,
                    site.site_password,
                    cat.category_name
                    FROM tbl_sites AS site INNER JOIN tbl_categories AS cat
                    ON site.category_id=cat.category_id WHERE site.user_id = '".$_SESSION["user_id"]."' AND cat.category_status = 'Active'
                    ".$searchQuery."ORDER BY ".$columnName." ".$columnSortOrder." LIMIT ".$row.",".$rowPerPage;

    $siteRecords = mysqli_query($conn, $siteQuery);
    $data = array();

    while ($row = mysqli_fetch_assoc($siteRecords)){
      $data[] = array(
        "site_url"                 =>  '<a target="_blank" href="'.$row['site_url'].'">'.$row['site_url'].' <i class="fas fa-external-link-alt"></i></a>', 
        "site_username"            =>  $row['site_username'],
        "site_name"                =>  $row['site_name'],
        "category_name"            =>  $row['category_name'],
        "site_password"            =>  $row['site_password'],
        "site_id"                  =>  $row['site_id'],
        "view"                     =>  '<button type="button" class="btn btn-info view_site" data-toggle="modal" data-target="#readModal" id="'.$row['site_id'].'"><i class="fas fa-eye"></i></button>',
        "update"                   =>  '<button type="button" class="btn btn-primary update_site" id="'.$row['site_id'].'"><i class="fas fa-edit"></i></button>',
        "delete"                   =>  '<button type="button" class="btn btn-danger delete_site" id="'.$row['site_id'].'"><i class="fas fa-trash-alt"></i></button>'
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

  // Create site
  if($_POST["action"] == "create_site"){

    $category_id = $_POST['category_id'];
    $url = $_POST['url'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $note = $_POST['note'];

    $sql = "INSERT INTO tbl_sites (user_id,
                                    category_id,
                                    site_url,
                                    site_name, 
                                    site_username,
                                    site_password,
                                    site_note,
                                    site_created_date) 
                            VALUES('".$_SESSION["user_id"]."', 
                                  '$category_id',
                                  '$url',
                                  '$name',
                                  '$username',
                                  '$password',
                                  '$note',
                                  NOW())";

    if(mysqli_query($conn, $sql)){
      $output = array(
        'status'        => 'success',
        'message'       => 'New site has been successfully added.'
      );
    }

    echo json_encode($output);

  }

  // Single fetch
    if($_POST["action"] == "single_fetch"){

      // Fetch records
      $sql = "SELECT site.site_url,
                      site.site_name,
                      cat.category_name,
                      site.site_username,
                      site.site_password,
                      site.site_note,
                      site.site_created_date
                      FROM tbl_sites AS site INNER JOIN tbl_categories AS cat
                      ON site.category_id=cat.category_id WHERE site_id = '".$_POST["site_id"]."'";

        $result = mysqli_query($conn, $sql);

        $row = mysqli_fetch_row($result);

        $output = array(
          "url"		            =>	$row[0],
          "name"		          => 	$row[1],
          "category"  		    => 	$row[2],
          "username"		      => 	$row[3],
          "password"		      => 	$row[4],
          "note"		          => 	$row[5],
          "created_date"	    =>	$row[6]
        );

      echo json_encode($output);

    }

  // Update site
  if($_POST["action"] == "update_fetch"){

    $sql = "SELECT site_id, category_id, site_url, site_name, site_username, site_password, site_note FROM tbl_sites WHERE site_id = '".$_POST["site_id"]."'";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_row($result);

    $output = array(
      "site_id"		                    =>	$row[0],
      "category_id"		                =>	$row[1],
      "url"		                        =>	$row[2],
      "name"		                      =>	$row[3],
      "username"		                  =>	$row[4],
      "password"		                  =>	$row[5],
      "note"		                      =>	$row[6]
    );

    echo json_encode($output);

  }

  // Single update fetch
  if($_POST["action"] == "update_site"){

    $site_id = $_POST['site_id'];
    $category_id = $_POST['category_id'];
    $site_url = $_POST['url'];
    $site_name = $_POST['name'];
    $site_username = $_POST['username'];
    $site_password = $_POST['password'];
    $site_note = $_POST['note'];

    $sql = "UPDATE tbl_sites SET category_id = '$category_id',
                                site_url = '$site_url',
                                site_name = '$site_name',
                                site_username = '$site_username',
                                site_password = '$site_password',
                                site_note = '$site_note'
                                WHERE site_id = '$site_id'";

    if(mysqli_query($conn, $sql)){

      $output = array(
        'status'        => 'success',
        'message'	    	=> 'Site has been updated successfully.',
      );

    }

    echo json_encode($output);

  }

  // Delete site
  if($_POST["action"] == "delete_site"){

    $sql = "DELETE FROM tbl_sites WHERE site_id = '".$_POST["site_id"]."'";

    if(mysqli_query($conn, $sql)){

      $output = array(
        'status'        => 'success',
        'message'	    	=> 'Site has been deleted successfully.',
      );

    }

    echo json_encode($output);

  }

}

?>