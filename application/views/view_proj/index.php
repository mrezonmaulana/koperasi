<script src="<?php echo base_url()?>assets/plugins/chart.js/Chart.min.js"></script>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  

  <!-- Main Sidebar Container -->
  <?php 

    $this->load->view('view_proj/aside.php');
    /*require_once base_url('Head/leftmenu');*/
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $list['project']->jml ?></h3>

                <p>Transaksi Kasir</p>
              </div>
              <div class="icon">
                <i class="fa fa-cash-register"></i>
              </div>
              <a href="<?php echo base_url('Kasir/list_bill');?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo $list['project_ajuan']->jml + $list['ajuan_kasbon']->jml + $list['project_ajuan_adm']->jml;?></h3>

                <p>Ajuan</p>
              </div>
              <div class="icon">
                <i class="fa fa-hands-helping"></i>
              </div>
              <a href="<?php echo base_url('Ajuan/list_pengajuan');?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo $list['karyawan']->jml ?></h3>

                <p>Karyawan</p>
              </div>
              <div class="icon">
                <i class="fa fa-people-carry"></i>
              </div>
              <a href="<?php echo base_url('Masterdata/karyawan');?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">

          <div class='col-lg-12 col-6'>
              <h3>Masterdata</h3>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
              <div class="inner">
                <h3><?php echo $list['bidang']->jml ?></h3>
                <p>Kategori</p>
              </div>
              <div class="icon">
                <i class="fa fa-box-open"></i>
              </div>
              <a href="<?php echo base_url('Masterdata/kategori');?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?php echo $list['barang']->jml ?></h3>
                <p>Barang</p>
              </div>
              <div class="icon">
                <i class="fa fa-coins"></i>
              </div>
              <a href="<?php echo base_url('Masterdata/barang');?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
              <div class="inner">
                <h3><?php echo $list['kendaraan']->jml ?></h3>
                <p>EDC</p>
              </div>
              <div class="icon">
                <i class="fa fa-money-check"></i>
              </div>
              <a href="<?php echo base_url('Masterdata/edc');?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box bg-default">
              <div class="inner">
                <h3><?php echo $list['supplier']->jml ?></h3>
                <p>Supplier</p>
              </div>
              <div class="icon">
                <i class="fa fa-truck-moving"></i>
              </div>
              <a href="<?php echo base_url('Masterdata/supplier');?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
        </div>

        <div class="row">
          <div class='col-lg-6'>
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title"><i class='fa fa-cash-register'></i> Pendapatan Kasir Migguan</h3>
                  <a href="javascript:void(0);"></a>
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <p class="d-flex flex-column">
                    <span class="text-bold text-lg">Rp. <?php echo $list['all_sales'];?></span>
                    <span>Pendapatan Kasir Keseluruhan</span>
                  </p>
                  <p class="ml-auto d-flex flex-column text-right">
                    <span class="<?php echo $list['text_persen']?>">
                      <i class="fas <?php echo $list['text_persen_panah']?>"></i> <?php echo $list['selisih_persen']?>%
                    </span>
                    <span class="text-muted">Sejak Minggu Lalu</span>
                  </p>
                </div>
                <!-- /.d-flex -->

                <div class="position-relative mb-4">
                  <canvas id="visitors-chart" height="200"></canvas>
                </div>

                <div class="d-flex flex-row justify-content-end">
                  <span class="mr-2">
                    <i class="fas fa-square text-primary"></i> Minggu Ini
                  </span>

                  <span>
                    <i class="fas fa-square text-gray"></i> Minggu Lalu
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div class='col-lg-6'>
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title"><i class='fa fa-cash-register'></i> Pendapatan Kasir Bulanan</h3>
                  <a href="javascript:void(0);"></a>
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <p class="d-flex flex-column">
                    <span class="text-bold text-lg">Rp. <?php echo $list['all_sales'];?></span>
                    <span>Pendapatan Kasir Keseluruhan</span>
                  </p>
                  <p class="ml-auto d-flex flex-column text-right">
                    <span class="<?php echo $list['text_persen_month']?>">
                      <i class="fas <?php echo $list['text_persen_month_panah']?>"></i> <?php echo $list['selisih_month_persen']?>%
                    </span>
                    <span class="text-muted">Sejak Bulan Lalu</span>
                  </p>
                </div>
                <!-- /.d-flex -->

                <div class="position-relative mb-4">
                  <canvas id="sales-chart" height="200"></canvas>
                </div>

                <div class="d-flex flex-row justify-content-end">
                  <span class="mr-2">
                    <i class="fas fa-square text-primary"></i> Tahun Ini
                  </span>

                  <span>
                    <i class="fas fa-square text-gray"></i> Tahun Lalu
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class='row'>
          <div class='col-lg-12'>
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title"><i class='fa fa-toilet-paper'></i> Penjualan Barang (Qty)</h3>
                  <a href="javascript:void(0);"></a>
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <p class="d-flex flex-column">
                    <span class="text-bold text-lg"><i class='fa fa-crown'></i><?php echo $list['makanan_juara'];?></span>
                    <span>Barang Qty Tertinggi Sampai Hari Ini</span>
                  </p>
                  
                </div>
                <!-- /.d-flex -->

                <div class="position-relative mb-4">
                  <canvas id="product-chart" height="200"></canvas>
                </div>

                
              </div>
            </div>
          </div>
        </div>

        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php 

    $this->load->view('view_proj/footer.php');
    /*require_once base_url('Head/leftmenu');*/
  ?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->


</body>
</html>

<script type="text/javascript">
  $(function () {
  'use strict'

  var ticksStyle = {
    fontColor: '#495057',
    fontStyle: 'bold'
  }

  var mode      = 'index'
  var intersect = true

  var $salesChart = $('#sales-chart')
  var salesChart  = new Chart($salesChart, {
    type   : 'bar',
    data   : {
      labels  : ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL','AGT','SEPT','OKT','NOV','DES'],
      datasets: [
        {
          backgroundColor: '#007bff',
          borderColor    : '#007bff',
          data           : [<?php echo $list['curr_year_01']; ?>, <?php echo $list['curr_year_02']; ?>, <?php echo $list['curr_year_03']; ?>, <?php echo $list['curr_year_04']; ?>, <?php echo $list['curr_year_05']; ?>, <?php echo $list['curr_year_06']; ?>, <?php echo $list['curr_year_07']; ?>,<?php echo $list['curr_year_08']; ?>,<?php echo $list['curr_year_09']; ?>,<?php echo $list['curr_year_10']; ?>,<?php echo $list['curr_year_11']; ?>,<?php echo $list['curr_year_12']; ?>]
        },
        {
          backgroundColor: '#ced4da',
          borderColor    : '#ced4da',
          data           : [<?php echo $list['last_year_01']; ?>, <?php echo $list['last_year_02']; ?>, <?php echo $list['last_year_03']; ?>, <?php echo $list['last_year_04']; ?>, <?php echo $list['last_year_05']; ?>, <?php echo $list['last_year_06']; ?>, <?php echo $list['last_year_07']; ?>,<?php echo $list['last_year_08']; ?>,<?php echo $list['last_year_09']; ?>,<?php echo $list['last_year_10']; ?>,<?php echo $list['last_year_11']; ?>,<?php echo $list['last_year_12']; ?>]
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips           : {
        mode     : mode,
        intersect: intersect
      },
      hover              : {
        mode     : mode,
        intersect: intersect
      },
      legend             : {
        display: false
      },
      scales             : {
        yAxes: [{
          // display: false,
          gridLines: {
            display      : true,
            lineWidth    : '4px',
            color        : 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks    : $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function (value, index, values) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }
              return 'Rp. ' + value
            },suggestedMax: 200
          }, ticksStyle)
        }],
        xAxes: [{
          display  : true,
          gridLines: {
            display: false
          },
          ticks    : ticksStyle
        }]
      }
    }
  })

  var $productChart = $('#product-chart')
  var productChart  = new Chart($productChart, {
    type   : 'bar',
    data   : {
      labels  : [<?php echo $list['new_makanan']?>],
      datasets: [
        {
          backgroundColor: '#007bff',
          borderColor    : '#007bff',
          data           : [<?php echo $list['terjual_new_makanan']?>]
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips           : {
        mode     : mode,
        intersect: intersect
      },
      hover              : {
        mode     : mode,
        intersect: intersect
      },
      legend             : {
        display: false
      },
      scales             : {
        yAxes: [{
          // display: false,
          gridLines: {
            display      : true,
            lineWidth    : '4px',
            color        : 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks    : $.extend({
            beginAtZero: true,
            callback: function (value, index, values) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }
              return 'x' + value
            },
            suggestedMax: 10
          }, ticksStyle)
        }],
        xAxes: [{
          display  : true,
          gridLines: {
            display: false
          },
          ticks    : ticksStyle
        }]
      }
    }
  })

  var $visitorsChart = $('#visitors-chart')
  var visitorsChart  = new Chart($visitorsChart, {
    data   : {
      labels  : ["<?php echo $list['hari_0']?>", '<?php echo $list['hari_1']?>', '<?php echo $list['hari_2']?>', '<?php echo $list['hari_3']?>', '<?php echo $list['hari_4']?>', '<?php echo $list['hari_5']?>', '<?php echo $list['hari_6']?>'],
      datasets: [{
        type                : 'line',
        data                : [<?php echo $list['curr_week_0']; ?>, <?php echo $list['curr_week_1']; ?>, <?php echo $list['curr_week_2']; ?>, <?php echo $list['curr_week_3']; ?>, <?php echo $list['curr_week_4']; ?>, <?php echo $list['curr_week_5']; ?>, <?php echo $list['curr_week_6']; ?>],
        backgroundColor     : 'transparent',
        borderColor         : '#007bff',
        pointBorderColor    : '#007bff',
        pointBackgroundColor: '#007bff',
        fill                : false
        // pointHoverBackgroundColor: '#007bff',
        // pointHoverBorderColor    : '#007bff'
      },
        {
          type                : 'line',
          data                : [<?php echo $list['last_week_0']; ?>, <?php echo $list['last_week_1']; ?>, <?php echo $list['last_week_2']; ?>, <?php echo $list['last_week_3']; ?>, <?php echo $list['last_week_4']; ?>, <?php echo $list['last_week_5']; ?>, <?php echo $list['last_week_6']; ?>],
          backgroundColor     : 'tansparent',
          borderColor         : '#ced4da',
          pointBorderColor    : '#ced4da',
          pointBackgroundColor: '#ced4da',
          fill                : false
          // pointHoverBackgroundColor: '#ced4da',
          // pointHoverBorderColor    : '#ced4da'
        }]
    },
    options: {
      maintainAspectRatio: false,
      tooltips           : {
        mode     : mode,
        intersect: intersect
      },
      hover              : {
        mode     : mode,
        intersect: intersect
      },
      legend             : {
        display: false
      },
      scales             : {
        yAxes: [{
          // display: false,
          gridLines: {
            display      : true,
            lineWidth    : '4px',
            color        : 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks    : $.extend({
            beginAtZero : true,
            callback: function (value, index, values) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }
              return 'Rp. ' + value
            },
            suggestedMax: 200
          }, ticksStyle)
        }],
        xAxes: [{
          display  : true,
          gridLines: {
            display: false
          },
          ticks    : ticksStyle
        }]
      }
    }
  })
})

</script>
