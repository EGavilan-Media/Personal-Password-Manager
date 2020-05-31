<?php include('include/header.php'); ?>
<div id="layoutSidenav_content">
    <div class="container">
        <!-- Note DataTable -->
        <div class="card mb-3" style="margin-top:30px">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-9 font-weight-bold"><i class="fas fa-sticky-note"></i> Notes</div>
                    <div class="col-md-3" align="right">
                        <button type="button" id="add_button" class="btn btn-primary btn-sm">Create New Note</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <span id="sucess_message"></span>
                <table class="table table-bordered" id="noteTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Note</th>
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
        <!-- End Note DataTable -->

        <!-- Note Modal -->
        <div class="modal fade" id="formModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal_title"></h4>
                        <button class="close" data-dismiss="modal">
                        <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="note_form">
                            <div id="alert_error_message" class="alert alert-danger collapse" role="alert">
                                Please check in on some of the fields below.
                            </div>
                            <div class="mb-3">
                                <label for="title">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" maxlength="50"
                                    placeholder="Enter title">
                                <div id="title_error_message" class="text-danger"></div>
                            </div>
                            <div class="mb-3">
                                <label for="note">Note <span class="text-danger">*</span></label>
                                <textarea id="note" name="note" class="form-control" rows="10" maxlength="500" autocomplete="off" placeholder="Enter note"></textarea>
                                <div id="note_error_message" class="text-danger"></div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="note_id" id="note_id"/>
                                <input type="hidden" name="action" id="action"/>
                                <input type="submit" name="button_action" id="button_action" class="btn btn-primary"/>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Note Modal -->

        <!-- Delete Note Modal -->
        <div class="modal fade" id="deleteModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete Confirmation</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <h5 align="center">Are you sure you want to delete this note?</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" name="ok_button" id="ok_button" class="btn btn-primary">OK</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Delete Note Modal -->

        <!-- View Note Modal-->
        <div class="modal fade" id="readModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Note Details</h5>
                        <button class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <tr>
                                <th>Title</th>
                                <td>
                                    <div id="view_title"></div>
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
        <!-- End View Note Modal-->
    </div>

<?php include('include/footer.php'); ?>

<script>

    $(document).ready(function(){
        var datatable = $('#noteTable').DataTable({
            'processing': true,
            'serverSide': true,
            'ajax': {
                url:'note_action.php',
                type: 'POST',
                data: {action:'note_fetch'}
            },
            'columns': [
                { data: 'created_date' },
                { data: 'title'},
                { data: 'description'},
                { data: 'view',"orderable":false},
                { data: 'update',"orderable":false},
                { data: 'delete',"orderable":false}
            ]
        });

        function clear_field(){
            $("#alert_error_message").hide();
            $('#note_form')[0].reset();
            $("#title_error_message").hide();
            $("#title").removeClass("is-invalid");
            $("#note_error_message").hide();
            $("#note").removeClass("is-invalid");
        }

        $('#add_button').click(function(){
            $('#modal_title').text('Create New Note');
            $('#button_action').val('Save');
            $('#action').val('create_note');
            $('#formModal').modal('show');
            clear_field();
            $('#sucess_message').html('');
        });

        var error_title = false;
        var error_note = false;

        $("#title").focusout(function () {
            check_title();
        });

        $("#note").focusout(function() {
            check_note();
        });

        function check_title() {
            if ($.trim($('#title').val()) == '') {
                $("#title_error_message").html("Title is a required field.");
                $("#title_error_message").show();
                $("#title").addClass("is-invalid");
                error_title = true;
            } else {
                $("#title_error_message").hide();
                $("#title").removeClass("is-invalid");
            }
        }

        function check_note() {
            if( $.trim( $('#note').val() ) == '' ){
                $("#note_error_message").html("Note is a required field.");
                $("#note_error_message").show();
                $("#note").addClass("is-invalid");
                error_note = true;
            } else {
                $("#note_error_message").hide();
                $("#note").removeClass("is-invalid");
            }
        }

        $('#note_form').on('submit', function(event){
            event.preventDefault();

            error_title = false;
            error_note = false;

            check_title();
            check_note();

            if (error_title == false && error_note == false) {
                
                data = $('#note_form').serialize();
                
                $.ajax({
                    type: "POST",
                    data: data,
                    url: "note_action.php",
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

        var note_id = '';
        $(document).on('click', '.view_note', function(){
            note_id = $(this).attr('id');
            $.ajax({
                url:"note_action.php",
                method:"POST",
                data:{action:'single_fetch', note_id:note_id},
                success:function(data){
                    var data = JSON.parse(data);
                    $('#view_title').text(data['title']);
                    $('#view_note').text(data['note']);
                    $('#view_created_date').text(data['created_date']);
                }
            });
        });

        $(document).on('click', '.update_note', function(){
            note_id = $(this).attr('id');
            clear_field();
            $.ajax({
                url:"note_action.php",
                method:"POST",
                data:{action:'update_fetch', note_id:note_id},
                success:function(data){
                    var data = JSON.parse(data);
                    $('#note_id').val(data['note_id']);
                    $('#title').val(data.title);
                    $('#note').val(data.note);
                    $('#modal_title').text('Update Note');
                    $('#button_action').val('Update');
                    $('#action').val('update_note');
                    $('#formModal').modal('show');
                }
            });
        });

        $(document).on('click', '.delete_note', function(){
            note_id = $(this).attr('id');
            $('#deleteModal').modal('show');
        });

        $('#ok_button').click(function(){
            $.ajax({
                url:"note_action.php",
                method:"POST",
                data:{note_id:note_id, action:"delete_note"},
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
    });
</script>
