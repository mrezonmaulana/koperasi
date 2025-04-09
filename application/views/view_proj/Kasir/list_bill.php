<script type="text/javascript">
 $(function() {
    $("input[data-type='pilih_tgl']").daterangepicker({
        singleDatePicker: true,
        showDropdowns: true
    }, 
    function(start, end, label) {
        var years = moment().diff(start, 'years');
        
    });
});
</script>
<script type="text/javascript">
  function hapusPenerimaan(id,type){

        var text = "Penerimaan Barang";
        if ( type == 2 ) {

          text = "Pengajuan Barang";
        }

        if ( confirm('Apakah anda yakin akan menghapus '+text+' ini ?')) {

            $.post("<?php echo base_url('Transaksi/hapus_transaksi')?>",{ttbid:id},function(data){
                window.location.reload();
            }); 

        }
  }

  function closePenerimaan(id,type){

        var text = "Bill";
        if ( type == 3 ) {

          text = "Pembayaran";
        }

        if ( confirm('Apakah anda yakin akan meng-cancel '+text+' ini ?')) {

            $.post("<?php echo base_url('Kasir/cancel_pembayaran')?>",{tbmid:id,type:type},function(data){
                window.location.reload();
            }); 

        }
  }

  function editPenerimaan(id,type){

       window.location.replace("<?php echo base_url('Transaksi/edit_penjualan')?>?from=&ttbid="+id+"&type="+type);
  }

  function cetakPenerimaan(id,type) {

     
      var url = "<?php echo base_url('Laporan/view_transaksi_bill')?>/"+id+"_"+type;
      NewWindow(url,"","1800","1800","yes");
    
     
  }

  function lihat_detail(id,type) {

      window.location.replace("<?php echo base_url('Kasir/edit_bill/"+id+"');?>");
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
            <h1 class="m-0 text-dark"><i class='fa fa-cash-register'></i> Kasir</h1>
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
                      <tr>
                        <th colspan='7'>
                          List Pembayaran
                          <span style='float:right'>
                            <input type='button' class='btn btn-warning btn-sm' value='Tambah Pembayaran' onclick="window.location.replace('<?php echo base_url('Kasir/add_bill')?>')">
                          </span>
                        </th>
                      </tr>
                      <tr>
                        <th colspan="7">
                          <form method="post" name="frm_cari" id="frm_cari" action="<?php echo base_url('Kasir/list_bill')?>">
                              <table width="100%">
                                <tr>
                                  <td width="20%">
                                    Periode Bill
                                  </td>
                                  <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                          </span>
                                        </div>
                                       <input type="text" class="form-control form-control-sm float-right" id="tgl1" name="tgl1" data-type='pilih_tgl' value='<?php echo $tgl1;?>'>
                                      </div>
                                  </td>
                                  <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                          </span>
                                        </div>
                                       <input type="text" class="form-control form-control-sm float-right" id="tgl2" name="tgl2" data-type='pilih_tgl' value='<?php echo $tgl2;?>'>
                                      </div>
                                  </td>
                                </tr>
                                <tr>
                                  <td>Status Bill</td>
                                  <td colspan="2">
                                    <?php 

                                      $sel_bill_d = $sel_bill_f = $sel_bill_p = "";

                                      if ( $status_bill == 'f' ) {
                                        $sel_bill_d = "selected='selected'";
                                      }

                                      if ( $status_bill == 't' ) {
                                        $sel_bill_f = "selected='selected'";
                                      }

                                      if ( $status_bill == 'p' ) {
                                        $sel_bill_p = "selected='selected'";
                                      }
                                    ?>

                                    <select name="status_bill" id="status_bill" class='form-control form-control-sm'>
                                      <option></option>
                                      <option value='f' <?php echo $sel_bill_d;?> >Belum Lunas [Draft]</option>
                                      <option value='p' <?php echo $sel_bill_p;?> >Pembayaran Partial [Draft]</option>
                                      <option value='t' <?php echo $sel_bill_f;?> >Sudah Lunas [Final]</option>
                                    </select>
                                  </td>
                                </tr>
                                <tr>
                                  <td colspan="3">
                                    <input type='submit' value='Cari' name='btn_cari' id='btn_cari' class='btn btn-primary btn-sm'/>
                                  </td>
                                </tr>
                              </table>
                          </form>
                        </th>
                      </tr>
                      <tr>
                        <th width='1%'>No</th>
                        <th width='12%'>Nomor</th>
                        <th width='10%'>Tgl</th>
                        <th width='8%'>Pelanggan</th>
                        <th width="7%">User</th>
                        <th width='6%'>Jumlah</th>
                        <th width='6%'>Fungsi</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                          $no=1;
                           foreach($list_penerimaan->result_array() as $i) {

                              echo "
                                  <Tr style='font-size:10pt !important'>
                                    <td>".$no."</td>
                                    <td>
                                      <div style='font-size:10pt;width:auto'>Bill [ ".$i['no_bill']." ]</div>
                                      <div style='font-size:10pt;width:auto'>Pay [ ".$i['no_bayar']." ]</div>
                                      <div style='font-size:10pt;width:auto'>Status Bill [ ".$i['status']." ]</div>

                                    </td>
                                    <td>
                                      <div style='font-size:10pt;width:auto'>Bill [ ".date('d F Y',strtotime($i['tgl_trans']))." ]</div>
                                      <div style='font-size:10pt;width:auto'>Pay [ ".($i['create_time_bayar'] != '' ? date('d F Y',strtotime($i['create_time_bayar'])) : '-' )." ]</div>
                                    </td>
                                    <td>".$i['nama_konsumen']."</td>
                                    <td>
                                      <div style='font-size:10pt;width:auto'>Bill [ ".$i['user']." ]</div>
                                      <div style='font-size:10pt;width:auto'>Pay [ ".$i['user_bayar']." ]</div>
                                    </td>
                                    <td align='right' class='text_jumlah'>".number_format($i['total_amount'],2,',','.')."</td>
                                    <td align='center'>";
                                      
                                      if ( $i['is_final'] == 3 ) {
                                          echo "<button title='Cetak Bill Sementara' class='btn btn-warning btn-sm' onclick='cetakPenerimaan(".$i['tbmid'].",3);'><i class='fa fa-print'></i></button>&nbsp;";
                                          echo "<button title='Lihat Detail' class='btn btn-success btn-sm' onclick='lihat_detail(".$i['tbmid'].",2);'><i class='fa fa-eye'></i></button>&nbsp;";
                                          echo "<button title='Cancel Bill' class='btn btn-danger btn-sm' onclick='closePenerimaan(".$i['tbmid'].",1);'><i class='fa fa-times'></i></button>";
                                      } else if ( $i['is_final'] == 1 ) {
                                          echo "<button title='Cetak Bill' class='btn btn-warning btn-sm' onclick='cetakPenerimaan(".$i['tbmid'].",1);'><i class='fa fa-print'></i></button>&nbsp;";
                                          echo "<button title='Cancel Bayar' class='btn btn-danger btn-sm' onclick='closePenerimaan(".$i['tbmid'].",3);'><i class='fa fa-times'></i></button>";
                                      }

                                  echo "
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