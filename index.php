<?php

  include('include/header.php');

  include('connection.php');

?>
<div id="layoutSidenav_content">
    <div class="container">
        <!-- Site DataTable -->
        <div class="card mb-3" style="margin-top:30px">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-9 font-weight-bold"><i class="fas fa-key"></i> Passwords</div>
                    <div class="col-md-3" align="right">
                        <button type="button" id="add_button" class="btn btn-primary btn-sm">Add New Site</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <span id="sucess_message"></span>
                <table class="table table-bordered" id="siteTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Website</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Category</th>
                        <th>View</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tfoot>
                    </tfoot>
                </table>
                </div>
            </div>
        </div>
        <!-- End Site DataTable -->

        <!-- MODALS -->
        <!-- Product Modal -->
        <div class="modal fade" id="formModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="modal_title"></h5>
                    <button class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form id="site_form">
                            <div id="alert_error_message" class="alert alert-danger collapse" role="alert">
                                Please check in on some of the fields below.
                            </div>
                            <div class="form-group">
                                <label>URL <i class="text-danger"> *</i></label>
                                <input type="text" id="url" name="url" class="form-control" maxlength="100" autocomplete="off" placeholder="Enter URL">
                                <div id="url_error_message" class="text-danger"></div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Name <i class="text-danger"> *</i></label>
                                    <input type="text" id="name" name="name" class="form-control" maxlength="50" autocomplete="off" placeholder="Enter name">
                                    <div id="name_error_message" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Category <i class="text-danger"> *</i></label>
                                    <select name="category_id" id="category_id" class="custom-select">
                                    <option value="" hidden>Select Category</option>
                                    <?php
                                        echo load_category_list($conn);
                                    ?>
                                    </select>
                                    <div id="category_error_message" class="text-danger"></div>
                                </div>              
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Username <i class="text-danger"> *</i></label>
                                    <input type="text" id="username" name="username" class="form-control" maxlength="50" autocomplete="off" placeholder="Enter username">
                                    <div id="username_error_message" class="text-danger"></div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Password <i class="text-danger"> *</i></label>
                                    <input type="text" id="password" name="password" class="form-control" maxlength="100" autocomplete="off" placeholder="Enter password">
                                    <div id="password_error_message" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="form-group">
                            <label>Note <i class="text-danger"> *</i></label>
                            <textarea id="note" name="note" class="form-control" rows="5" maxlength="500" autocomplete="off" placeholder="Enter note"></textarea>
                            <div id="note_error_message" class="text-danger"></div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="site_id" id="site_id"/>
                                <input type="hidden" name="action" id="action"/>
                                <input type="submit" name="button_action" id="button_action" class="btn btn-primary"/>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Site Modal -->

        <!-- Delete Site Modal -->
        <div class="modal fade" id="deleteModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete Confirmation</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <h5 align="center">Are you sure you want to delete this site?</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" name="ok_button" id="ok_button" class="btn btn-primary">OK</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Delete Site Modal -->

        <!-- View Site Modal-->
        <div class="modal fade" id="readModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Site Details</h5>
                        <button class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <tr>
                                <th>URL</th>
                                <td>
                                    <div>
                                        <a id="h_url"><span id="view_url"></span> <i class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>
                                    <div id="view_name"></div>
                                </td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td>
                                    <div id="view_category"></div>
                                </td>
                            </tr>
                            <tr>
                                <th>Username</th>
                                <td>
                                    <input type="text" id="view_username" name="view_username" class="form-control" readonly data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom">
                                    <div id="copied_username_alert" class="collapse"><i class="fas fa-copy"></i> Copied!</div>
                                </td>
                            </tr>
                            <tr>
                                <th>Password</th>
                                <td>
                                    <input type="text" id="view_password" name="view_password" class="form-control" readonly data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom">
                                    <div id="copied_password_alert" class="collapse"><i class="fas fa-copy"></i> Copied!</div>
                                </td>
                            </tr>
                            <tr>
                                <th>Note</th>
                                <td>
                                    <div id="view_note"></div>
                                </td>
                            </tr>
                            <tr>
                                <th>Created</th>
                                <td>
                                    <div id="view_created_date"></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>            
        </div>
        <!-- End View Site Modal-->
    </div>

<?php include('include/footer.php'); ?>

<script>

    $(document).ready(function(){
        var datatable = $('#siteTable').DataTable({
            'processing': true,
            'serverSide': true,
            'ajax': {
                url:'site_action.php',
                type: 'POST',
                data: {action:'site_fetch'}
            },
            'columns': [
                { data: 'site_url'},
                { data: 'site_name'},
                { data: 'site_username'},
                { data: 'site_password'},
                { data: 'category_name'},
                { data: 'view',"orderable":false},
                { data: 'update',"orderable":false},
                { data: 'delete',"orderable":false}
            ]
        });

        function clear_field(){
            $("#alert_error_message").hide();
            $('#site_form')[0].reset();
            $("#url_error_message").hide();
            $("#url").removeClass("is-invalid");
            $("#name_error_message").hide();
            $("#name").removeClass("is-invalid");
            $("#category_error_message").hide();
            $("#category_id").removeClass("is-invalid");
            $("#username_error_message").hide();
            $("#username").removeClass("is-invalid");
            $("#password_error_message").hide();
            $("#password").removeClass("is-invalid");
        }

        $('#add_button').click(function(){
            $('#modal_title').text('Add New Site');
            $('#button_action').val('Save');
            $('#action').val('create_site');
            $('#formModal').modal('show');
            clear_field();
            $('#sucess_message').html('');
        });

        var error_url = false;
        var error_name = false;
        var error_category = false;
        var error_username = false;
        var error_password = false;

        $("#url").focusout(function () {
            check_url();
        });

        $("#name").focusout(function () {
            check_name();
        });

        $("#category_id").focusout(function() {
            check_category();
        });

        $("#username").focusout(function () {
            check_username();
        });

        $("#password").focusout(function () {
            check_password();
        });

        function check_url() {
            var pattern = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
            var url = $("#url").val();
            
            if( $.trim( $('#url').val() ) == '' ){
                $("#url_error_message").html("URL is a required field.");
                $("#url_error_message").show();
                $("#url").addClass("is-invalid");
                error_url = true;
            }else if(!url.match(pattern)) {
                $("#url_error_message").html("Enter a valid URL.");
                $("#url_error_message").show();
                $("#url").addClass("is-invalid");
                error_url = true;
            }
            else{
                $("#url_error_message").hide();
                $("#url").removeClass("is-invalid");
            }
        }

        function check_name() {
            if( $.trim( $('#name').val() ) == '' ){
                $("#name_error_message").html("Name is a required field.");
                $("#name_error_message").show();
                $("#name").addClass("is-invalid");
                error_name = true;
            } else {
                $("#name_error_message").hide();
                $("#name").removeClass("is-invalid");
            }
        }

        function check_category() {
            if( $.trim( $('#category_id').val() ) == '' ){
                $("#category_error_message").html("Category is a required field.");
                $("#category_error_message").show();
                $("#category_id").addClass("is-invalid");
                error_category = true;
            } else {
                $("#category_error_message").hide();
                $("#category_id").removeClass("is-invalid");
            }
        }

        function check_username() {
            if( $.trim( $('#username').val() ) == '' ){
                $("#username_error_message").html("Username is a required field.");
                $("#username_error_message").show();
                $("#username").addClass("is-invalid");
                error_username = true;
            } else {
                $("#username_error_message").hide();
                $("#username").removeClass("is-invalid");
            }
        }

        function check_password() {
            if( $.trim( $('#password').val() ) == '' ){
                $("#password_error_message").html("Password is a required field.");
                $("#password_error_message").show();
                $("#password").addClass("is-invalid");
                error_password = true;
            } else {
                $("#password_error_message").hide();
                $("#password").removeClass("is-invalid");
            }
        }

        $('#site_form').on('submit', function(event){
            event.preventDefault();

            error_url = false;
            error_name = false;
            error_category = false;
            error_username = false;
            error_password = false;

            check_url();
            check_name();
            check_category();
            check_username();
            check_password();

            if (error_url == false && error_name == false && error_category == false && error_username == false && error_password == false) {
                
                data = $('#site_form').serialize();

                $.ajax({
                    type: "POST",
                    data: data,
                    url: "site_action.php",
                    dataType: "json",
                    success: function (data) {
                        $('#sucess_message').show();
                        $('#sucess_message').html('<div class="alert alert-success">'+data.message+'</div>');
                        $("#alert_error_message").hide();
                        clear_field();
                        $('#formModal').modal('hide');
                        datatable.ajax.reload();
                        setTimeout(function () {
                            $('#sucess_message').hide();
                        }, 2000);                      
                    },
                    error: function () {
                        alert("Oops! Something went wrong.");
                    }
                });
            } else {
                $("#alert_error_message").show();
            }
        });

        var site_id = '';
        $(document).on('click', '.view_site', function(){
            site_id = $(this).attr('id');
            $.ajax({
                url:"site_action.php",
                method:"POST",
                data:{action:'single_fetch', site_id:site_id},
                success:function(data){
                    var data = JSON.parse(data);
                    var url = data['url'];
                    document.getElementById("h_url").href = url;
                    document.getElementById("h_url").target = "_blank";
                    $('#view_url').text(data['url']);
                    document.getElementById("view_url").class = "fas fa-external-link-alt"; 
                    $('#view_username').val(data.username);
                    $('#view_category').text(data['category']);
                    $('#view_name').text(data['name']);
                    $('#view_password').val(data['password']);
                    $('#view_note').text(data['note']);
                    $('#view_created_date').text(data['created_date']);
                }
            });
        });

        $(document).on('click', '.update_site', function(){
            site_id = $(this).attr('id');
            clear_field();
            $.ajax({
                url:"site_action.php",
                method:"POST",
                data:{action:'update_fetch', site_id:site_id},
                success:function(data){
                    var data = JSON.parse(data);
                    $('#site_id').val(data['site_id']);
                    $('#url').val(data.url);
                    $('#name').val(data.name);
                    $('#category_id').val(data.category_id);
                    $('#username').val(data.username);
                    $('#password').val(data.password);
                    $('#note').val(data.note);
                    $('#modal_title').text('Update Site');
                    $('#button_action').val('Update');
                    $('#action').val('update_site');
                    $('#formModal').modal('show');
                }
            });
        });

        $(document).on('click', '.delete_site', function(){
            site_id = $(this).attr('id');
            $('#deleteModal').modal('show');
        });

        $('#ok_button').click(function(){
            $.ajax({
                url:"site_action.php",
                method:"POST",
                data:{site_id:site_id, action:"delete_site"},
                dataType: "json",
                success:function(data){
                    $('#sucess_message').show();
                    $('#sucess_message').html('<div class="alert alert-success">'+data.message+'</div>');
                    $('#deleteModal').modal('hide');
                    datatable.ajax.reload();
                    setTimeout(function () {
                        $('#sucess_message').hide();
                    }, 2000);
                }
            })
        });

        $('#view_username').click(function(){
            copyUsername();
        });

        $('#view_password').click(function(){
            copyPassword();
        });

        function copyUsername() {
            var copyUsername = document.getElementById("view_username");
            copyUsername.select();
            document.execCommand("copy");
            $('#copied_username_alert').show();
            $('#copied_password_alert').hide();

            setTimeout(function () {
                $('#copied_username_alert').hide();
            }, 2000);
        }

        function copyPassword() {
            var copyPassword = document.getElementById("view_password");
            copyPassword.select();
            document.execCommand("copy");
            $('#copied_password_alert').show();
            $('#copied_username_alert').hide();

            setTimeout(function () {
                $('#copied_password_alert').hide();
            }, 2000);
        }
    });
</script>
