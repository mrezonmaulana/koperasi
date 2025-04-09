
<script type="text/javascript">
  function hapusBayar(id,type){

    if ( type == 1 ) {

        if ( confirm('Apakah anda yakin akan menghapus Pembayaran Project ini ?')) {

            $.post("<?php echo base_url('Pembayaran/hapus_pembayaran')?>",{tppid:id},function(data){
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

      var c = document.querySelectorAll('.anggaran');
      var d = document.querySelectorAll('.anggarankas');

      
      if ( c.length > 0 ) {
          $.each($('.anggaran'),function(k,v){
              var tp = this.id;
              var newtp = tp.split('_');
              document.getElementById('text_anggaran_'+newtp[1]).innerHTML = formatRupiah(this.value,'Rp. ');
              
          });
      }

      if ( d.length > 0 ) {
          $.each($('.anggarankas'),function(k,v){
              var tp = this.id;
              var newtp = tp.split('_');
              document.getElementById('text_anggarankas_'+newtp[1]).innerHTML = formatRupiah(this.value,'Rp. ');
              
          });
      }
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
            <h1 class="m-0 text-dark"><i class='fa fa-file-invoice'></i> Pembayaran Project</h1>
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
                  <table id='example2' class='table table-bordered table-hover'>  
                    <thead>
                      <tr><th colspan='6'>
                          List Pembayaran Project
                          <span style='float:right'>
                            <input type='button' class='btn btn-warning' value='Tambah Pembayaran' onclick="window.location.replace('<?php echo base_url('Pembayaran/add_bayarproject')?>')">
                          </span>
                          </th></tr>
                      <tr>
                        <th width='1%'>No</th>
                        <th width='20%'>Tanggal Pembayaran</th>
                        <th>Project</th>
                        <th width='15%'>Jumlah</th>
                        <th width='15%'>Fungsi</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                          $no=1;
                           foreach($list_pembayaran->result_array() as $i) {
                              echo "
                                  <Tr>
                                    <td>".$no."</td>
                                    <td>".date('d M Y',strtotime($i['tgl_bayar']))."</td>
                                    <td>".$i['nama_project']."</td>
                                    <td align='right' class='text_jumlah'>".$i['jumlah']."</td>
                                    <td align='center'>
                                      
                                      <button title='Hapus Ajuan' class='btn btn-danger btn-sm' onclick='hapusBayar(".$i['tppid'].",1);'><i class='fa fa-times'></i></button>
                                    </td>
                                  </tr>
                              ";

                              $no++;
                           } 

                           
                        ?>
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

  
  <aside class="control-sidebar control-sidebar-dark">
    
  </aside>
  
</div>
</body>
</html>
<script>

  $(document).ready(function(){

    var text_jml = $('.text_jumlah');

    if ( text_jml.length > 0 ) {

       $.each(text_jml,function(k,v){
           this.innerHTML = formatRupiah(this.innerHTML,"Rp. ");
       });
    }

  });

  $(function () {
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": true,
    });

    /*$('#example3').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": true,
    });*/
  });
</script>
