
<script type="text/javascript">
  function hapusPenerimaan(id,type){

        var text = "Penerimaan Barang";
        if ( type == 2 ) {

          text = "Pengeluaran Barang";
        }

        if ( confirm('Apakah anda yakin akan menghapus '+text+' ini ?')) {

            $.post("<?php echo base_url('Transaksi/hapus_transaksi')?>",{ttbid:id},function(data){
                window.location.reload();
            }); 

        }
  }

  function cetakPenerimaan(id,type) {

     if ( type == 1 ) {
      var url = "<?php echo base_url('Laporan/view_transaksi')?>/"+id+"_"+type;
      NewWindow(url,"","1800","1800","yes");
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
            <h1 class="m-0 text-dark"><i class='fa fa-download'></i> Penerimaan Barang</h1>
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
                      <tr><th colspan='7'>
                          List Penerimaan Barang
                          <span style='float:right'>
                            <input type='button' class='btn btn-warning' value='Tambah Penerimaan' onclick="window.location.replace('<?php echo base_url('Transaksi/add_penerimaan')?>')">
                          </span>
                          </th></tr>
                      <tr>
                        <th width='1%'>No</th>
                        <th width='20%'>No. Terima</th>
                        <th width='10%'>Tgl Terima</th>
                        <th width=''>Supplier</th>
                        <th width='15%'>No. Faktur</th>
                        <th width='15%'>Jumlah</th>
                        <th width='15%'>Fungsi</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                          $no=1;
                           foreach($list_penerimaan->result_array() as $i) {
                              echo "
                                  <Tr>
                                    <td>".$no."</td>
                                    <td>".$i['reff_code']."</td>
                                    <td>".date('d M Y',strtotime($i['tgl_trans']))."</td>
                                    <td>".$i['nama_media']."</td>
                                    <td>".$i['no_faktur']."</td>
                                    <td align='right' class='text_jumlah'>".number_format($i['total'],2,',','.')."</td>
                                    <td align='center'>
                                      <button title='Cetak Ajuan' class='btn btn-warning btn-sm' onclick='cetakPenerimaan(".$i['ttbid'].",1);'><i class='fa fa-print'></i></button>";

                                      if ( $i['ada_pemakaian'] > 0 ) {

                                      } else {
                                        
                                        echo "
                                        <button title='Hapus Ajuan' class='btn btn-danger btn-sm' onclick='hapusPenerimaan(".$i['ttbid'].",1);'><i class='fa fa-times'></i></button> ";

                                      }


                                    echo"
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

    /*var text_jml = $('.text_jumlah');

    if ( text_jml.length > 0 ) {

       $.each(text_jml,function(k,v){
           this.innerHTML = formatRupiah(this.innerHTML,"Rp. ");
       });
    }*/

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
