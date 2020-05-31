<?php include('include/header.php'); ?>
    <div id="layoutSidenav_content">
        <div class="container">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs mt-5" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active font-weight-bold" id="profile-tab" data-toggle="tab" name="profile" href="#profile" role="tab" aria-controls="home" aria-selected="true">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" id="profile-tab" data-toggle="tab" href="#edit-profile" role="tab" aria-controls="profile" aria-selected="false">Edit Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" id="messages-tab" data-toggle="tab" href="#edit-password" role="tab" aria-controls="messages" aria-selected="false">Edit Password</a>
                </li>
            </ul class="mb-5">
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <strong>First Name: </strong>
                                </div>
                                <div class="col-md-10"> <?php echo $_SESSION['first_name']; ?>
                                </div>
                                <div class="col-md-2">
                                    <strong>Last Name: </strong>
                                </div>
                                <div class="col-md-10">
                                    <?php echo $_SESSION['last_name']; ?>
                                </div>
                                <div class="col-md-2">
                                    <strong>E-mail: </strong>
                                </div>
                                <div class="col-md-10">
                                    <?php echo $_SESSION['email']; ?>
                                </div>
                                <div class="col-md-2">
                                    <strong>Created: </strong>
                                </div>
                                <div class="col-md-10">
                                    <?php echo $_SESSION['created_date']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="edit-profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="card">
                        <div class="card-body">    
                            <div class="col-md-6">
                                <br>
                                <form id="user_form">
                                    <div id="alert_error_message" class="alert alert-danger collapse" role="alert">
                                        Please check in on some of the fields below.
                                    </div>
                                    <div id="alert_sucess_message" class="alert alert-success collapse" role="alert">
                                        Your profile has been updated successfully.
                                    </div>
                                    <div class="mb-3">
                                        <label for="firstname">First Name *</label>
                                        <input type="text" class="form-control" id="firstname" name="firstname" maxlength="50"
                                            placeholder="Enter First name">
                                        <div id="firstname_error_message" class="text-danger"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="lastname">Last Name *</label>
                                        <input type="text" class="form-control" id="lastname" name="lastname" maxlength="50"
                                            placeholder="Enter last name">
                                        <div id="lastname_error_message" class="text-danger"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email">Email *</label>
                                        <input type="email" class="form-control" id="email" name="email" maxlength="100"
                                            placeholder="Enter email" readonly>
                                        <div id="email_error_message" class="text-danger"></div>
                                    </div>
                                    <hr class="mb-4">
                                    <button class="btn btn-primary btn-block" type="submit">Update Profile</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="edit-password" role="tabpanel" aria-labelledby="messages-tab">
                    <div class="card">
                        <div class="card-body">    
                            <div class="col-md-6">
                                <br>
                                <form id="update_password_form">
                                    <div id="update_password_alert_error_message" class="alert alert-danger collapse" role="alert">
                                        Please check in on some of the fields below.
                                    </div>
                                    <div id="update_password_alert_sucess_message" class="alert alert-success collapse" role="alert">
                                        Password updated successfully!
                                    </div>
                                    <div class="mb-3">
                                        <label for="password">Current Password *</label>
                                        <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Enter current Password">
                                        <div id="current_password_error_message" class="text-danger"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password">New password *</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password" maxlength="50"
                                            placeholder="Enter password">
                                        <div id="new_password_error_message" class="text-danger"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirm-password">Confirm Password *</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                            maxlength="50" placeholder="Enter confirm password">
                                        <div id="confirm_password_error_message" class="text-danger"></div>
                                    </div>
                                    <hr class="mb-4">
                                    <button class="btn btn-primary btn-block" type="submit">Update Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php include('include/footer.php'); ?>
    <script>

        $(document).ready(function(){

            getUser();

            $('#user_form').on('submit', function (event) {
                event.preventDefault();
                updateUser();
            });

            $('#update_password_form').on('submit', function (event) {
                event.preventDefault();
                updatePassword();
            });

            var error_firstname = false;
            var error_lastname = false;
            var error_current_password = false;
            var error_new_password = false;
            var error_confirm_password = false;

            $("#profile-tab").click(function(){
                location.reload();
            });

            $("#firstname").focusout(function() {
                check_firstname();
            });

            $("#lastname").focusout(function() {
                check_lastname();
            });

            $("#current_password").focusout(function() {
                check_current_password();
            });

            $("#new_password").focusout(function() {
                check_new_password();
            });

            $("#confirm_password").focusout(function() {
                check_confirm_password();
            });

            function check_firstname() {

                if ($.trim($('#firstname').val()) == '') {
                    $("#firstname_error_message").html("Input is blank!");
                    $("#firstname_error_message").show();
                    $("#firstname").addClass("is-invalid");
                    error_firstname = true;
                } else {
                    $("#firstname_error_message").hide();
                    $("#firstname").removeClass("is-invalid");
                }
            }

            function check_lastname() {

                if ($.trim($('#lastname').val()) == '') {
                    $("#lastname_error_message").html("Input is blank!");
                    $("#lastname_error_message").show();
                    $("#lastname").addClass("is-invalid");
                    error_lastname = true;
                } else {
                    $("#lastname_error_message").hide();
                    $("#lastname").removeClass("is-invalid");
                }
            }

            function check_current_password() {
                var current_password_length = $("#current_password").val().length;

                if( $.trim( $('#current_password').val() ) == '' ){
                    $("#current_password_error_message").html("Current password is a required field.");
                    $("#current_password_error_message").show();
                    $("#current_password").addClass("is-invalid");
                    error_current_password = true;
                }else if(current_password_length < 8) {
                    $("#current_password_error_message").html("At least 8 characters.");
                    $("#current_password_error_message").show();
                    $("#current_password").addClass("is-invalid");
                    error_current_password = true;
                } else {
                    $("#current_password_error_message").hide();
                    $("#current_password").removeClass("is-invalid");
                }
            }

            function check_new_password() {

                var current_password = $("#current_password").val();
                var new_password = $("#new_password").val();
                var new_password_length = $("#new_password").val().length;

                if( $.trim( $('#new_password').val() ) == '' ){
                    $("#new_password_error_message").html("New password is a required field.");
                    $("#new_password_error_message").show();
                    $("#new_password").addClass("is-invalid");
                    error_new_password = true;
                }else if(new_password_length < 8) {
                    $("#new_password_error_message").html("At least 8 characters.");
                    $("#new_password_error_message").show();
                    $("#new_password").addClass("is-invalid");
                    error_new_password = true;
                }else if(new_password == current_password) {
                    $("#new_password_error_message").html("New password cannot be same as your current password.");
                    $("#new_password_error_message").show();
                    $("#new_password").addClass("is-invalid");
                    error_confirm_password = true;
                }else{
                    $("#new_password_error_message").hide();
                    $("#new_password").removeClass("is-invalid");
                }
            }

            function check_confirm_password() {

                var new_password = $("#new_password").val();
                var confirm_password = $("#confirm_password").val();

                if( $.trim( $('#confirm_password').val() ) == '' ){
                    $("#confirm_password_error_message").html("Confirm password is a required field.");
                    $("#confirm_password_error_message").show();
                    $("#confirm_password").addClass("is-invalid");
                    error_confirm_password = true;
                }else if(new_password !=  confirm_password) {
                    $("#confirm_password_error_message").html("Passwords do not match.");
                    $("#confirm_password_error_message").show();
                    $("#confirm_password").addClass("is-invalid");
                    error_confirm_password = true;
                } else {
                    $("#confirm_password_error_message").hide();
                    $("#confirm_password").removeClass("is-invalid");
                }
            }

            function updateUser() {

                error_firstname = false;
                error_lastname = false;

                check_firstname();
                check_lastname();

                if (error_firstname == false && error_lastname == false) {

                    var dataAction = {
                        "action": "update_user"
                    };

                    data = $('#user_form').serialize();
                    data = data + "&" + $.param(dataAction);
                    
                    $.ajax({
                        type: "POST",
                        data: data,
                        url: "profile_action.php",
                        dataType: "json",
                        success: function (data) {
                            if (data.status == 'success') {
                                $("#alert_sucess_message").show();
                                $("#alert_error_message").hide();
                            } else if (data.status=='error') {
                                alert("Oops! Something went wrong.");
                            }
                        },
                        error: function () {
                            alert("Oops! Something went wrong.");
                        }
                    });
                    return false;
                } else {
                    $("#alert_sucess_message").hide();
                    $("#alert_error_message").show();
                    return false;
                }
            }

            function updatePassword(){

                error_current_password = false;
                error_new_password = false;
                error_confirm_password = false;

                check_current_password();
                check_new_password();
                check_confirm_password();

                if(error_current_password == false && error_new_password == false && error_confirm_password == false) {

                    var dataAction = {
                        "action": "update_password"
                    };

                    data=$('#update_password_form').serialize();
                    data=data + "&" + $.param(dataAction);

                    $.ajax({
                        type:"POST",
                        data: data, 
                        url:"profile_action.php",
                        dataType:"json",
                        success:function(data){
                            if(data.status) {
                                $("#update_password_alert_sucess_message").show();
                                $("#update_password_alert_error_message").hide();
                                $('#update_password_form')[0].reset();
                            }else if(data.error) {
                                $("#current_password_error_message").html("Passwords do not match.");
                                $("#current_password_error_message").show();
                            }else{
                                alert("Oops! Something went wrong.", "", "error");
                            }
                        },error:function(){
                        alert("Oops! Something went wrong.");
                        }
                    });
                    return false;
                }else{
                    $("#update_password_alert_sucess_message").hide();
                    $("#update_password_alert_error_message").show();
                    return false;
                }
            }

            function getUser() {
                $.ajax({
                    url: "profile_action.php",
                    type: "POST",
                    data: { action: 'single_fetch' }, 
                    success: function (data) {
                        var data = JSON.parse(data);
                        $('#id').val(data['id']);
                        $('#firstname').val(data.firstname);
                        $('#lastname').val(data.lastname);
                        $('#email').val(data.email);
                    }
                });
            }
        });
    </script>