<?= $this->extend('template/admin_template'); ?>

<?= $this->section('content'); ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Post Management</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalID">
                    Add Post
                </button>

            </div>
        </div>
        <table id="dataTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>AUTHOR NAME</th>
                    <th>TITLE</th>
                    <th>DESCRIPTION</th>
                    <th>CREATED AT</th>
                    <th>ACTION</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modalID" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Post Details</h5>
                        <buton type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </buton>
                    </div>
                    <div class="modal-body">
                        <form class="needs-validation" novalidate>
                            <div class="card-body">
                                <input type="hidden" id="id" name="id">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter a valid title.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input type="text" class="form-control" id="description" name="description" placeholder="Enter Description" required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter a valid description.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="content">Content</label>
                                    <textarea type="textarea" rows="5" class="form-control" id="content" name="content" placeholder="Enter Content" required></textarea>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter a valid content.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="author_id">Author</label>
                                    <select class="form-control custom-select" name="author_id" id="author_id" required>
                                        <option value="">Select Author</option>
                                        <?php foreach ($authors as $author) : ?>
                                            <option value="<?= $author['id']; ?>"><?= $author['first_name'] . ' ' . $author['last_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter a valid author.
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
                        url: "<?= base_url('posts'); ?>",
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
                        url: "<?= base_url('posts'); ?>/" + formdata.id,
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
            url: "<?= base_url('posts/list'); ?>",
            type: "POST"
        },
        columns: [{
                data: "id",
            },
            {
                data: 'author_name',
            },
            {
                data: 'title',
            },
            {
                data: 'description',
            },
            {
                data: 'created_at',
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
            url: "<?= base_url('posts'); ?>/" + id,
            type: "GET",
            success: function(data) {
                $("#modalID").modal("show");
                $("#id").val(data.id);
                $("#author_id").val(data.author_id);
                $("#title").val(data.title);
                $("#description").val(data.description);
                $("#content").val(data.content);
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
                url: "<?= base_url('posts'); ?>/" + id,
                type: "DELETE",
                success: function(data) {
                    $(document).Toasts('create', {
                        class: 'bg-success',
                        title: 'Success',
                        subtitle: 'Author',
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
        $("#author_id").val("");
        $("#title").val("");
        $("#description").val("");
        $("#content").val("");
    }

    $(document).ready(function() {
        'use strict';

        let form = $(".needs-validation");

        form.each(function() {
            $(this).on('submit', function(event) {
                console.log(this.checkValidity())
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