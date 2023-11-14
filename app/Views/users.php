<?= $this->extend('template/admin_template'); ?>

<?= $this->section('content'); ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">User Management</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">
        <!-- <div class="row mb-2">
            <div class="col-sm-12">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalID">
                    Add User
                </button>
            </div>
        </div> -->
        <table id="dataTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>ACTION</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modalID" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">User Details</h5>
                        <buton type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </buton>
                    </div>
                    <div class="modal-body">
                        <form class="needs-validation" novalidate>
                            <div class="card-body">
                                <input type="hidden" id="id" name="id">
                                <div class="form-group">
                                    <label for="name">Full Name</label>

                                    <input readonly type="text" class="form-control" id="name" name="name" placeholder="Enter FullName">
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter a valid first name.
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input readonly type="email" class="form-control" id="email" name="email" placeholder="Enter Email">
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter a valid email.
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email">Role</label>
                                    <select name="group" id="group" class="form-control" required>
                                        <option value="admin">Admin</option>
                                        <option value="user">User</option>
                                    </select>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter a valid role.
                                    </div>
                                </div>

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<?= $this->endSection(); ?>

<?= $this->section('pagescripts'); ?>
<script>
    $(function() {

        $("form").submit(function(event) {
            event.preventDefault();

            let formdata = $(this).serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});

            let jsondata = JSON.stringify(formdata);

            if (this.checkValidity()) {
                if (!formdata.id) {
                    $.ajax({
                        url: "<?= base_url('users'); ?>",
                        type: "POST",
                        data: jsondata,
                        success: function(data) {
                            $(document).Toasts('create', {
                                class: 'bg-success',
                                title: 'Success',
                                subtitle: 'User',
                                body: 'Record successfully added.',
                                autohide: true,
                                delay: 3000
                            });
                            $("#modalID").modal("hide");
                            clearform();
                            table.ajax.reload();
                        },
                        error: function(result) {
                            $(document).Toasts('create', {
                                class: 'bg-danger',
                                title: 'Error',
                                subtitle: 'User',
                                body: 'Record not added.',
                                autohide: true,
                                delay: 3000
                            });
                        }
                    });
                } else {
                    $.ajax({
                        url: "<?= base_url('users'); ?>/" + formdata.id,
                        type: "PUT",
                        data: jsondata,
                        success: function(data) {
                            $(document).Toasts('create', {
                                class: 'bg-success',
                                title: 'Success',
                                subtitle: 'User',
                                body: 'Record successfully udpated.',
                                autohide: true,
                                delay: 3000
                            });
                            $("#modalID").modal("hide");
                            clearform();
                            table.ajax.reload();
                        },
                        error: function(result) {
                            console.log(result)
                            $(document).Toasts('create', {
                                class: 'bg-danger',
                                title: 'Error',
                                subtitle: 'User',
                                body: 'Record not updated.',
                                autohide: true,
                                delay: 3000
                            });
                        }
                    });
                }
            }
        });
    });


    let table = $("#dataTable").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?= base_url('users/list'); ?>",
            type: "POST"
        },
        columns: [{
                data: "user_id",
            },
            {
                data: 'name',
            },

            {
                data: 'email',
            },
            {
                data: 'role',
            },
            {
                data: '',
                defaultContent: `<td>
            <button class="btn btn-warning btn-sm" id="editRow">Edit role</button>
            <button class="btn btn-danger btn-sm" id="deleteRow">Delete</button>
            </td>`
            }
        ],
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: true,
        lengthMenu: [5, 10, 20, 50]

    });

    $(document).on("click", "#editRow", function() {
        let row = $(this).parents("tr")[0];
        let id = table.row(row).data().user_id;

        $.ajax({
            url: "<?= base_url('users'); ?>/" + id,
            type: "GET",
            success: function(data) {
                $("#modalID").modal("show");
                $("#id").val(data.user_id);
                $("#name").val(data.name);
                $("#email").val(data.email);
                $("#group").val(data.group);
            },
            error: function(result) {
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: 'Error',
                    subtitle: 'User',
                    body: 'Record not found.',
                    autohide: true,
                    delay: 3000
                });
            }
        });
    });

    $(document).on("click", "#deleteRow", function() {
        let row = $(this).parents("tr")[0];
        let id = table.row(row).data().user_id;


        if (confirm("Are you sure you want to delete this record?")) {
            $.ajax({
                url: "<?= base_url('users'); ?>/" + id,
                type: "DELETE",
                success: function(data) {
                    console.log(data)
                    $(document).Toasts('create', {
                        class: 'bg-success',
                        title: 'Success',
                        subtitle: 'User',
                        body: 'Record was deleted.',
                        autohide: true,
                        delay: 3000
                    });
                    table.ajax.reload();
                },
                error: function(result) {
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Error',
                        subtitle: 'User',
                        body: 'Record not found.',
                        autohide: true,
                        delay: 3000
                    });
                }
            });
        }
    });

    function clearform() {
        $("#id").val("");
        $("#first_name").val("");
        $("#last_name").val("");
        $("#email").val("");
        $("#birthdate").val("");
    }

    $(document).ready(function() {
        'use strict';

        let form = $(".needs-validation");

        form.each(function() {
            $(this).on('submit', function(event) {
                if (this.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                $(this).addClass('was-validated');
            });
        });
    });
</script>
<?= $this->endSection(); ?>