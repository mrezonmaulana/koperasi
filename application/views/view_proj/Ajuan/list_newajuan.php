
<script type="text/javascript">
  function hapusAjuan(id,type){

    if ( type == 1 ) {

        if ( confirm('Apakah anda yakin akan menghapus Ajuan ini ?')) {

            $.post("<?php echo base_url('Ajuan/hapus_ajuan')?>",{tpaid:id},function(data){
                window.location.reload();
            }); 

        }
      } else {

        if ( type == 3) {

          if ( confirm('Apakah anda yakin akan menghapus Ajuan Administrasi ini ?')) {

              $.post("<?php echo base_url('Ajuan/hapus_ajuanadm')?>",{tpaaid:id},function(data){
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
            <h1 class="m-0 text-dark"><i class='fa fa-hands-helping'></i> Ajuan</h1>
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
                      <tr><th colspan='7'>List Ajuan Kas</th></tr>
                      <tr>
                        <th width='1%'>No</th>
                        <th width='15%'>Tanggal</th>
                        <th>User Create</th>
                        <th width="15%">Tipe Biaya</th>
                        <th width='15%'>Jumlah Ajuan</th>
                        <!-- <th width='15%'>Nama Satuan</th> -->
                        <th width='15%'>Fungsi</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                          $no=1;
                           foreach($list_ajuan->result_array() as $i) {


                              echo "
                                  <Tr>
                                    <td>".$no."</td>
                                    <td>".date('d F Y',strtotime($i['tanggal_ajuan']))."</td>
                                    <td>".$i['pengaju']."</td>
                                    <td>".($i['is_out'] == 1 ? 'Pengeluaran' : 'Pemasukan')."</td>
                                    <td align='right'><font id='text_anggaran_".$i['tpaid']."'></font>
                                     <input type='hidden' id='anggaran_".$i['tpaid']."' class='anggaran'  value='".$i['total_ajuan']."'/>
                                    </td>
                                    <td align='center'>
                                      <button title='Cetak Ajuan' class='btn btn-warning btn-sm' onclick='cetakAjuan(".$i['tpaid'].",1);'><i class='fa fa-print'></i></button>
                                      ";

                                      
                                    echo"
                                      <button title='Hapus Ajuan' class='btn btn-danger btn-sm' onclick='hapusAjuan(".$i['tpaid'].",1);'><i class='fa fa-times'></i></button>
                                    </td>";
                                  
                                    echo"
                                  </tr>
                              ";
                                  

                              $no++;
                           } 

                           
                        ?>
                    </tbody>
                </table>
                <br>
                <table id='example3' class='table table-bordered table-hover'>  
                    <thead>
                      <tr><th colspan='7'>List Ajuan Kas Bon</th></tr>
                      <tr>
                        <th width='1%'>No</th>
                        <th width='15%'>Tanggal</th>
                        <th width='15%'>Pemberi Uang</th>
                        <th>Karyawan</th>
                        <th width='15%'>Jumlah Ajuan</th>
                        <th width='15%'>Fungsi</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                          $noa=1;
                           foreach($list_ajuankas->result_array() as $a) {
                              echo "
                                  <Tr>
                                    <td>".$noa."</td>
                                    <td>".date('d F Y',strtotime($a['tanggal_ajuan']))."</td>
                                    <td>Manager Keuangan</td>
                                    <td>".$a['nama_pengaju']."</td>
                                    <td align='right'><font id='text_anggarankas_".$a['takid']."'></font>
                                     <input type='hidden' id='anggarankas_".$a['takid']."' class='anggarankas'  value='".$a['jumlah']."'/>
                                    </td>
                                    <td align='center'>
                                      <button title='Cetak Ajuan' class='btn btn-warning btn-sm' onclick='cetakAjuan(".$a['takid'].",2);'><i class='fa fa-print'></i></button>
                                      <button title='Hapus Ajuan' class='btn btn-danger btn-sm' onclick='hapusAjuan(".$a['takid'].",2);'><i class='fa fa-times'></i></button>
                                    </td>
                                  </tr>
                              ";

                              $noa++;
                           } 

                           
                        ?>
                    </tbody>
                </table>


                <table id='example4' class='table table-bordered table-hover' style="display:none">  
                    <thead>
                      <tr><th colspan='11'>List Ajuan Administrasi</th></tr>
                      <tr>
                        <th width='1%'>No</th>
                        <th width='10%'>Project</th>
                        <th width='10%'>Nilai Kontrak</th>
                        <th width='10%'>Real Cost</th>
                        <th width='10%'>Backup & Kontrak</th>
                        <th width='10%'>Pho</th>
                        <th width='10%'>Keuangan</th>
                        <th width='10%'>Pmi</th>
                        <th width='10%'>Basp</th>
                        <th width='10%'>Bkd</th>
                        <th width='10%'>Fungsi</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                          $noad=1;
                           foreach($list_ajuanadm->result_array() as $a) {
                              $tot_backup = $a['backup']+$a['kontrak_ajuan'];
                              echo "
                                  <Tr>
                                    <td>".$noad."</td>
                                    <td>".$a['nama_project']."</td>
                                    <td align='right' class='row_ajuan'>".$a['anggaran']."</td>
                                    <td align='right' class='row_ajuan'>".$a['pagu']."</td>
                                    <td align='right' class='row_ajuan'>
                                     ".$tot_backup."
                                    </td>
                                    <td align='right' class='row_ajuan'>
                                     ".$a['pho']."
                                    </td>
                                    <td align='right' class='row_ajuan'>
                                     ".$a['keuangan']."
                                    </td>
                                    <td align='right' class='row_ajuan'>
                                     ".$a['pmi']."
                                    </td>
                                    <td align='right' class='row_ajuan'>
                                     ".$a['basp']."
                                    </td>
                                    <td align='right' class='row_ajuan'>
                                     ".$a['bkd']."
                                    </td>
                                    <td align='center'>
                                      <button title='Hapus Ajuan' class='btn btn-danger btn-sm' onclick='hapusAjuan(".$a['tpaaid'].",3);'><i class='fa fa-times'></i></button>
                                    </td>
                                  </tr>
                              ";

                              $noad++;
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

  $(document).ready(function(){

    $.each($('.row_ajuan'),function(k,v){

    nilai = this.innerHTML;

    this.innerHTML = formatRupiah("'"+nilai+"'","");

  });

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

    $('#example3').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": true,
    });
\
  });
</script>
