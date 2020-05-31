<?php include('include/header.php'); ?>
<div id="layoutSidenav_content">
    <div class="container">
        <!-- Category DataTable -->
        <div class="card mb-3" style="margin-top:30px">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-9 font-weight-bold"><i class="fas fa-list"></i> Categories</div>
                    <div class="col-md-3" align="right">
                        <button type="button" id="add_button" class="btn btn-primary btn-sm">Create New Category</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <span id="sucess_message"></span>
                <table class="table table-bordered" id="categoryTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Update</th>
                    </tr>
                    </thead>
                    <tfoot>
                    </tfoot>
                </table>
                </div>
            </div>
        </div>
        <!-- End Category DataTable -->

        <!-- Category Modal -->
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
                        <form id="category_form">
                            <div id="alert_error_message" class="alert alert-danger collapse" role="alert">
                                Please check in on some of the fields below.
                            </div>
                            <div class="mb-3">
                                <label for="category">Category <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="category" name="category" maxlength="50"
                                    placeholder="Enter category">
                                <div id="category_error_message" class="text-danger"></div>
                            </div>
                            <div class="mb-3">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="custom-select">
                                    <option value="" hidden >Status</option>
                                    <option>Active</option>
                                    <option>Inactive</option>
                                </select>
                                <div id="status_error_message" class="text-danger"></div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="category_id" id="category_id"/>
                                <input type="hidden" name="action" id="action"/>
                                <input type="submit" name="button_action" id="button_action" class="btn btn-primary"/>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Category Modal -->
    </div>

<?php include('include/footer.php'); ?>

<script>

    $(document).ready(function(){
        var datatable = $('#categoryTable').DataTable({
            'processing': true,
            'serverSide': true,
            'ajax': {
                url:'category_action.php',
                type: 'POST',
                data: {action:'category_fetch'}
            },
            'columns': [
                { data: 'category_created_date' },
                { data: 'category_name'},
                { data: 'category_status'},
                { data: 'update',"orderable":false}
            ]
            });

        $('#add_button').click(function(){
            $('#modal_title').text('Create New Category');
            $('#button_action').val('Save');
            $('#action').val('create_category');
            $('#formModal').modal('show');
            clear_field();
            $('#sucess_message').html('');            
        });

        function clear_field()  {
            $("#alert_error_message").hide();
            $('#category_form')[0].reset();
            $("#category_error_message").hide();
            $("#status_error_message").hide();
            $("#category").removeClass("is-invalid");
            $("#status").removeClass("is-invalid");
        }

        $('#category_form').on('submit', function(event){
            event.preventDefault();
            addCategory();
        });

        var error_category = false;
        var error_status = false;

        $("#category").focusout(function() {
            check_category();
        });

        $("#status").focusout(function() {
            check_status();
        });

        function check_category() {
            if( $.trim( $('#category').val() ) == '' ){
                $("#category_error_message").html("Category is a required field.");
                $("#category_error_message").show();
                $("#category").addClass("is-invalid");
                error_category = true;
            } else {
                $("#category_error_message").hide();
                $("#category").removeClass("is-invalid");
            }
        }

        function check_status() {
            if( $.trim( $('#status').val() ) == '' ){
                $("#status_error_message").html("Status is a required field.");
                $("#status_error_message").show();
                $("#status").addClass("is-invalid");
                error_status = true;
            } else {
                $("#status_error_message").hide();
                $("#status").removeClass("is-invalid");
            }
        }

        function addCategory(){
            error_category = false;
            error_status = false;

            check_category();
            check_status();

            if(error_category == false && error_status == false) {

                data=$('#category_form').serialize();
                $.ajax({
                    type:"POST",
                    data: data,
                    url:"category_action.php",
                    dataType:"json",
                    success:function(data){
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
                    error:function(){
                        alert("Oops! Something went wrong.");
                    }
                });
                return false;
            }else{
                $("#alert_error_message").show();
            }
        }

        $(document).on('click', '.update_category', function(){
            category_id = $(this).attr('id');
            clear_field();
            $.ajax({
                url:"category_action.php",
                method:"POST",
                data:{action:'update_fetch', category_id:category_id},
                success:function(data){
                    var data = JSON.parse(data);
                    $('#category_id').val(data['category_id']);
                    $('#category').val(data.category_name);
                    $('#status').val(data.category_status);
                    $('#modal_title').text('Update Category');
                    $('#button_action').val('Update');
                    $('#action').val('update_category');
                    $('#formModal').modal('show');
                }
            });
        });
    });
</script>
