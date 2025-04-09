
<script type="text/javascript">
  function hapusAjuan(id,type){

    if ( type == 1 ) {

        if ( confirm('Apakah anda yakin akan menghapus Ajuan ini ?')) {

            $.post("<?php echo base_url('Ajuan/hapus_ajuan')?>",{tpaid:id},function(data){
                window.location.reload();
            }); 

        }
      } else {

        if ( confirm('Apakah anda yakin akan menghapus Ajuan Kasbon ini ?')) {

            $.post("<?php echo base_url('Ajuan/hapus_ajuankas')?>",{takid:id},function(data){
                window.location.reload();
            }); 

        }
      } 

    
  }

  function cetakAjuan(id,type) {

     if ( type == 1 ) {
      var url = "<?php echo base_url('Ajuan/cetak_ajuan')?>/"+id;
      NewWindow(url,"","500","700","yes");
     } else {
       var url = "<?php echo base_url('Ajuan/cetak_ajuankas')?>/"+id;
      NewWindow(url,"","600","400","yes");
     }
  }


   $(document).ready(function(){

    
  });
</script>
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
            <h1 class="m-0 text-dark"><i class='fa fa-chart-bar'></i> Laporan</h1>
          </div><!-- /.col -->
          
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="row">
          <div class="col-12">
            <div class='card'>
              
              <div class='card-body'>
                  <table id='example2' class='table table-bordered'>  
                    <tbody>
                      <tr>
                        <td><a href="<?php echo base_url('Laporan/view_pinjaman_anggota');?>"  onclick="NewWindow(this.href,'','1800','1800','yes');return false;"><i class='fa fa-book'></i> Laporan Pinjaman Anggota</a></td>
                        <td><a href="<?php echo base_url('Laporan/view_cicilan_anggota');?>"  onclick="NewWindow(this.href,'','1800','1800','yes');return false;"><i class='fa fa-book'></i> Laporan Pembayaran Pinjaman Anggota</a></td>
                      </tr>
                      <tr>
                        <td><a href="<?php echo base_url('Laporan/view_aging_pinjaman');?>"  onclick="NewWindow(this.href,'','1800','1800','yes');return false;"><i class='fa fa-book'></i> Laporan Umur Pinjaman</a></td>
                      </tr>
                    </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
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
<script>
 
</script>
