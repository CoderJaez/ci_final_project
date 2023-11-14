<?= $this->extend('template/admin_template'); ?>

<?= $this->section('content'); ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Support Ticket Management</h1>

            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalID">
                    Add Ticket
                </button>
            </div>
        </div>
        <table id="dataTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Office/Section/Division</th>
                    <th>Severity</th>
                    <th>Description</th>
                    <th>ACTION</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modalID" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Offce Details</h5>
                        <buton type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </buton>
                    </div>
                    <div class="modal-body">
                        <form class="needs-validation" novalidate>
                            <div class="card-body">
                                <input type="hidden" id="id" name="id">
                                <input type="hidden" id="user_id" name="user_id" value="<?= auth()->user()->id ?>">


                                <?php if (auth()->user()->inGroup('admin')) : ?>
                                    <div class="form-group">
                                        <label for="code">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="PENDING">PENDING</option>
                                            <option value="PROCESSING">PROCESSING</option>
                                            <option value="RESOLVED">RESOLVED</option>
                                        </select>
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter a valid status.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="code">Remarks</label>
                                        <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="10" required></textarea>
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter a valid remarks.
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="code">Full Name</label>
                                        <input readonly type="text" class="form-control" id="name" name="name" placeholder="Enter Full Name" required value="<?= auth()->user()->name ?>">
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter a valid name.
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="code">Email</label>
                                        <input readonly type="text" class="form-control" id="email" name="email" placeholder="Email" required value="<?= auth()->user()->email ?>">
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter a valid email.
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <label for="name">Office/Section/Division</label>
                                    <select name="office_id" id="office_id" class="form-control" required>
                                        <?php foreach ($offices as $office) : ?>
                                            <option value="<?= $office->id ?>"><?= $office->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter a valid office name.
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name">Severity</label>
                                    <select name="severity_id" id="severity_id" class="form-control" required>
                                        <?php foreach ($severities as $severity) : ?>
                                            <option value="<?= $severity->id ?>"><?= $severity->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter a valid severity.
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="code">Description</label>
                                    <textarea class="form-control" name="description" id="description" cols="30" rows="5" required></textarea>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter a valid description.
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
                        url: "<?= base_url('tickets'); ?>",
                        type: "POST",
                        data: jsondata,
                        success: function(data) {
                            $(document).Toasts('create', {
                                class: 'bg-success',
                                title: 'Success',
                                subtitle: 'Office',
                                body: 'Record successfully added.',
                                autohide: true,
                                delay: 3000
                            });
                            $("#modalID").modal("hide");
                            clearform();
                            table.ajax.reload();
                        },
                        error: function(result) {
                            console.log(result.responseJSON.messages)
                            $(document).Toasts('create', {
                                class: 'bg-danger',
                                title: 'Error',
                                subtitle: 'Office',
                                body: 'Record not added.',
                                autohide: true,
                                delay: 3000
                            });
                        }
                    });
                } else {
                    $.ajax({
                        url: "<?= base_url('tickets'); ?>/" + formdata.id,
                        type: "PUT",
                        data: jsondata,
                        success: function(data) {
                            $(document).Toasts('create', {
                                class: 'bg-success',
                                title: 'Success',
                                subtitle: 'Ticket',
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
                                subtitle: 'TIcket',
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
            url: "<?= base_url('tickets/list'); ?>",
            type: "POST"
        },
        columns: [{
                data: "id",
            },
            {
                data: 'name',
            },
            {
                data: 'email',
            },
            {
                data: 'office',
            },
            {
                data: 'severity',
            },
            {
                data: 'description',
            },
            {
                data: '',
                defaultContent: `<td>
            <button class="btn btn-warning btn-sm" id="editRow">Edit</button>
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
        let id = table.row(row).data().id;

        $.ajax({
            url: "<?= base_url('tickets'); ?>/" + id,
            type: "GET",
            success: function(data) {
                console.log(data)
                $("#modalID").modal("show");
                $("#id").val(data.id);
                $("#severity_id").val(data.severity_id);
                $("#office_id").val(data.office_id);
                $("#description").val(data.description);
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

    $(document).on("click", "#deleteRow", function() {
        let row = $(this).parents("tr")[0];
        let id = table.row(row).data().id;

        if (confirm("Are you sure you want to delete this record?")) {
            $.ajax({
                url: "<?= base_url('tickets'); ?>/" + id,
                type: "DELETE",
                success: function(data) {
                    $(document).Toasts('create', {
                        class: 'bg-success',
                        title: 'Success',
                        subtitle: 'Ticket',
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
                        subtitle: 'Author',
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
        $("#description").val("");
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