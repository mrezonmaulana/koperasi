
<script type="text/javascript">
  function hapusPenerimaan(id,type){

        var text = "Penerimaan Barang";
        if ( type == 3 ) {

          text = "Pemakaian Barang";
        }

        if ( confirm('Apakah anda yakin akan menghapus '+text+' ini ?')) {

            $.post("<?php echo base_url('Transaksi/hapus_transaksi')?>",{ttbid:id},function(data){
                window.location.reload();
            }); 

        }
  }

  function closePenerimaan(id,type){

        var text = "Penerimaan Barang";
        if ( type == 3 ) {

          text = "Pemakaian Barang";
        }

        if ( confirm('Apakah anda yakin akan meng-close '+text+' ini ?')) {

            $.post("<?php echo base_url('Transaksi/close_transaksi')?>",{ttbid:id},function(data){
                window.location.reload();
            }); 

        }
  }

  function editPenerimaan(id,type){

       window.location.replace("<?php echo base_url('Transaksi/edit_penjualan')?>?from=&ttbid="+id+"&type="+type);
  }

  function cetakPenerimaan(id,type) {

     
      var url = "<?php echo base_url('Laporan/view_transaksi')?>/"+id+"_"+type;
      NewWindow(url,"","1800","1800","yes");
    
     
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
            <h1 class="m-0 text-dark"><i class='fa fa-drumstick-bite'></i> Pemakaian Barang</h1>
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
                          List Pemakaian Barang
                          <span style='float:right'>
                            <input type='button' class='btn btn-warning' value='Tambah Pemakaian' onclick="window.location.replace('<?php echo base_url('Transaksi/add_pemakaian')?>')">
                          </span>
                          </th></tr>
                      <tr>
                        <th width='1%'>No</th>
                        <th width='10%'>No Pemakaian</th>
                        <th width='10%'>Tgl Pemakaian</th>
                        <th width="7%">User Pengaju</th>
                        <th width='7%'>Jumlah</th>
                        <th width='6%'>Fungsi</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                          $no=1;
                           foreach($list_penerimaan->result_array() as $i) {

                              

                              echo "
                                  <Tr>
                                    <td>".$no."</td>
                                    <td>".$i['reff_code']."<br><span style='font-size:8pt;color:red;font-weight:bold'>Ket : ".$i['keterangan']." </span></td>
                                    <td>".date('d F Y',strtotime($i['tgl_trans']))."</td>
                                    <td>".$i['user']."</td>
                                    <td align='right' class='text_jumlah'>".$i['total']."</td>
                                    <td align='center'>
                                      <button title='Cetak Pemakaian Barang' class='btn btn-warning btn-sm' onclick='cetakPenerimaan(".$i['ttbid'].",3);'><i class='fa fa-print'></i></button>";

                                       if ( $i['ada_terima'] == 1 AND $i['is_closed']==0) {

                                        echo"
                                      <button title='Close CIU' class='btn btn-danger btn-sm' onclick='closePenerimaan(".$i['ttbid'].",3);'><i class='fa fa-door-open'></i></button>
                                    </td>
                                  </tr>
                              ";

                                       } elseif($i['is_closed']==0 AND $i['ada_terima'] == 0 AND intval($i['tbmid']) == 0) {

                                          echo"
                                      <button title='Hapus Pemakaian Barang' class='btn btn-danger btn-sm' onclick='hapusPenerimaan(".$i['ttbid'].",3);'><i class='fa fa-times'></i></button>
                                      
                                    </td>
                                  </tr>
                              ";
                                            
                                       } 
                                      

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
