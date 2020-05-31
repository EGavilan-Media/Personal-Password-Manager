<?php

//profile_action.php

include "connection.php";

session_start();

$output = '';
if(isset($_POST["action"])){

  // User Register
    if($_POST["action"] == "register_user"){

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = sha1($_POST['password']);

    // Check if email already exists.
    $sql = "SELECT * FROM tbl_users WHERE user_email = '$email'";
    $result = mysqli_query($conn, $sql);
    $checkrows = mysqli_num_rows($result);

    if($checkrows > 0) {
      $output = array(
        'status'        => 'error',
        'error'	      	=>	'Email already exists!'
      );
    } else {
      $sql = "INSERT INTO tbl_users (user_first_name, 
                                      user_last_name, 
                                      user_email,
                                      user_password,
                                      user_created_date) 
                              VALUES('$firstname', 
                                    '$lastname',
                                    '$email',
                                    '$password',
                                    NOW())";
      if(mysqli_query($conn, $sql)){
        $output = array(
            'status'        => 'success'
        );
      }
    }

    echo json_encode($output);

  }

  // User login
  if($_POST["action"] == "login_user"){

    $email = $_POST['email'];
    $password = sha1($_POST['password']);	
  
    $sql = "SELECT * FROM tbl_users WHERE user_email = '$email' AND user_password = '$password'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_row($result);

    if(mysqli_num_rows($result) > 0){    
      $_SESSION['user_id']       = $row[0];
      $_SESSION['first_name']    = $row[1];
      $_SESSION['last_name']     = $row[2];
      $_SESSION['email']         = $row[3];
      $_SESSION['created_date']  = $row[5];

      $output = array(
        'status'        => 'success'
      );

    } else {
      $output = array(
        'status'        => 'error',
        'error'	      	=> 'Incorrect email or password.'
      );
    }

    echo json_encode($output);

  }

  // Single fetch
  if($_POST["action"] == "single_fetch"){

    $id = $_SESSION['user_id'];
      
    $sql = "SELECT user_id, user_first_name, user_last_name, user_email FROM tbl_users WHERE user_id = '$id'";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_row($result);

    $output = array(
      "id"		            =>	$row[0],
      "firstname"		      =>	$row[1],
      "lastname"		      => 	$row[2],
      "email"   		      => 	$row[3]
    );

    echo json_encode($output);

  }

  // Single edit fetch
  if($_POST["action"] == "update_user"){

    $id = $_SESSION['user_id'];

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];

    $sql = "UPDATE tbl_users SET user_first_name = '$firstname',
                              user_last_name = '$lastname'
                              WHERE user_id = '$id'";

    if(mysqli_query($conn, $sql)){

      $output = array(
        'status'        => 'success'
      );

      $_SESSION['first_name']       = $firstname;
      $_SESSION['last_name']        = $lastname;

    }else{
      $output = array(
        'status'        => 'error'
      );
    }

    echo json_encode($output);

  }

  // Update User Password
  if($_POST["action"] == "update_password"){

    $id = $_SESSION['user_id'];

    $password = sha1($_POST['current_password']);
    $new_password = sha1($_POST['new_password']);

    $sql = "SELECT * FROM tbl_users WHERE user_password = '$password' AND user_id = '$id'";
    $result = mysqli_query($conn, $sql);
    $checkrows = mysqli_num_rows($result);

    if($checkrows > 0) {

        $sql = "UPDATE tbl_users SET user_password = '$new_password' WHERE user_id = '$id'";
        $result = mysqli_query($conn, $sql);

        if($result > 0)	{
          $output = array(
            'status'	=>	'success'
          );                
          echo json_encode($output);
        } 

    } else {
        $output = array(
            'error'		     =>	'true'
        );
        echo json_encode($output); 
    }
  }
}

?>