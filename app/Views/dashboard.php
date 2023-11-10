<?= $this->extend('template/admin_template'); ?>

<?= $this->section('content'); ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= $totalauthors ?></h3>

                        <p>Total Authors</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person"></i>
                    </div>

                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-6 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= $totalposts ?></h3>

                        <p>Total Posts</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-list"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Blog Stats</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection(); ?>

<?= $this->section('pagescripts'); ?>
<script>
    $(function(){
        var barChartCanvas = $('#barChart').get(0).getContext('2d');
        var barchartdata = <?= json_encode($barchartdata) ?>;
        
        var authors =[];
        for (var i = 0; i < barchartdata.length; i++) {
            authors[i] = barchartdata[i].author_name;
        }

        var post_count = [];
        for (var i = 0; i < barchartdata.length; i++) {
            post_count[i] = parseInt(barchartdata[i].post_count);
        }

        var barChartData = {
            labels: authors,
            datasets: [{
                label: 'Posts',
                backgroundColor: '#00a65a',
                borderColor: '#00a65a',
                borderWidth: 1,
                data: post_count
            }]
        }

        var barChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            datasetFill: false,
            scales: {
                yAxes:[{
                    ticks:{
                        stepSize: 1,
                        beginAtZero : true
                    }
                }]
            }
        }

        new Chart(barChartCanvas, {
            type: 'bar',
            data: barChartData,
            options: barChartOptions
        })

    })
</script>
<?= $this->endSection(); ?>