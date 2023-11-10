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
    </div>
</section>

<?= $this->endSection(); ?>

<?= $this->section('pagescripts'); ?>
<script>
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
</script>
<?= $this->endSection(); ?>