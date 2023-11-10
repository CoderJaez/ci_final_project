<?= $this->extend('template/admin_template'); ?>

<?= $this->section('content'); ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Author Management</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalID">
                    Add Author
                </button>
            </div>
        </div>
        <table id="dataTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>AUTHOR ID</th>
                    <th>LAST NAME</th>
                    <th>FIRST NAME</th>
                    <th>EMAIL</th>
                    <th>BIRTH DATE</th>
                    <th>ACTION</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modalID" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Author Details</h5>
                        <buton type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </buton>
                    </div>
                    <div class="modal-body">
                        <form class="needs-validation" novalidate>
                            <div class="card-body">
                                <input type="hidden" id="id" name="id">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name">
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter a valid first name.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name">
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter a valid last name.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email">
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter a valid email.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="birthdate">Birth Date</label>
                                    <div class="input-group date" id="birthdatepicker" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" id="birthdate" name="birthdate" data-target="#birthdatepicker">
                                        <div class="input-group-append" data-target="#birthdatepicker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
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
        $('#birthdatepicker').datetimepicker({
            format: 'YYYY-MM-DD'
        });

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
                        url: "<?= base_url('authors'); ?>",
                        type: "POST",
                        data: jsondata,
                        success: function(data) {
                            $(document).Toasts('create', {
                                class: 'bg-success',
                                title: 'Success',
                                subtitle: 'Author',
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
                                subtitle: 'Author',
                                body: 'Record not added.',
                                autohide: true,
                                delay: 3000
                            });
                        }
                    });
                } else {
                    $.ajax({
                        url: "<?= base_url('authors'); ?>/"+formdata.id,
                        type: "PUT",
                        data: jsondata,
                        success: function(data) {
                            $(document).Toasts('create', {
                                class: 'bg-success',
                                title: 'Success',
                                subtitle: 'Author',
                                body: 'Record successfully udpated.',
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
                                subtitle: 'Author',
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
            url: "<?= base_url('authors/list'); ?>",
            type: "POST"
        },
        columns: [{
                data: "id",
            },
            {
                data: 'last_name',
            },
            {
                data: 'first_name',
            },
            {
                data: 'email',
            },
            {
                data: 'birthdate',
            },
            {
                data: '',
                defaultContent: `<td>
            <button class="btn btn-primary btn-sm" id="editRow">Edit</button>
            <button class="btn btn-primary btn-sm" id="deleteRow">Delete</button>
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
        let id = table.row(row).data().id;

        $.ajax({
            url: "<?= base_url('authors'); ?>/" + id,
            type: "GET",
            success: function(data) {
                $("#modalID").modal("show");
                $("#id").val(data.id);
                $("#first_name").val(data.first_name);
                $("#last_name").val(data.last_name);
                $("#email").val(data.email);
                $("#birthdate").val(data.birthdate);
            },
            error: function(result) {
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: 'Error',
                    subtitle: 'Author',
                    body: 'Record not found.',
                    autohide: true,
                    delay: 3000
                });
            }
        });
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