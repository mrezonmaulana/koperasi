<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/select2/css/select2.min.css">
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/select2/js/select2.min.js"></script>
<script type="text/javascript">

$(document).ready(function(){

  $("select[name='nm_warna']").select2();

});

  function hapusBarang(tbrid,nama_barang){

    if (confirm('Apakah anda yakin akan menghapus barang '+nama_barang+' ?')) {
          $.post("<?php echo base_url('Masterdata/hapus_barang')?>",{tbrid:tbrid},function(data){
              window.location.reload();
          });
    }
  }

  function editBarang(tbrid) {

    window.location.replace("<?php echo base_url('Masterdata/edit_barang') ?>/"+tbrid);
  }
</script>

<style>
    .select2-container{
        width: 100% !important;
    }
    form fieldset div span{
        padding: 0px 0 !important;
        margin:0px 0 !important;
    }

    .select2-container--default .select2-selection--single{
        padding-left: 5px !important;
        line-height: 0px !important;
        padding : .33979rem .15rem !important;
    }
    .select2-results{
        font-size: 80%!important;
    }

    .select2-container .select2-selection--single {

      height: 34px !important;
    }
</style>

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
            <h1 class="m-0 text-dark"><i class='fa fa-coins'></i> Master Barang</h1>
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
              <div class='card-header'>
                <h3 class='card-title' style='margin-top:10px'>
                  List Barang
                </h3>
                    <span style='float:right !important'>
                      <a href="<?php echo base_url('Masterdata/tambah_barang')?>">
                      <button class='btn btn-warning btn-sm'>Tambah Barang</button>
                      </a>
                    </span>
              </div>
              <div class='card-body'>

                  <form name="frm_cari" id="frm_cari" action="<?php echo base_url('Masterdata/barang'); ?>" method="post">
                      
                      <table width="100%" class="table table-bordered">
                        <tr>
                          <td width="15%">Nama Barang</td>
                          <td>
                            <input type="text" class="form-control form-control-sm" name="nm_barang" id="nm_barang" value="<?php echo $nm_barang;?>">
                          </td>
                          <td>Jenis</td>
                            <td>
                             <select name="nm_jenis" id="nm_jenis" class="form-control form-control-sm">
                               <option></option>
                               <?php 
                                foreach ($list_jenis->result_array() as $key) {

                                  if ( $key['tjbid'] == $nm_jenis) {
                                    echo "<option value='".$key['tjbid']."' selected='selected'>".$key['nm_jenis']."</option>";
                                  } else {
                                    echo "<option value='".$key['tjbid']."'>".$key['nm_jenis']."</option>";
                                  }
                                }
                               ?>
                             </select>
                          </td>

                        </tr>
                        <tr>
                          <td width="15%">Tipe</td>
                          <td>
                            <select name="nm_tipe" id="nm_tipe" class="form-control form-control-sm">
                               <option></option>
                               <?php 
                                foreach ($list_tipe->result_array() as $key) {
                                  if ( $key['typbid'] == $nm_tipe) {
                                    echo "<option value='".$key['typbid']."' selected='selected'>".$key['nama_tipe']."</option>";
                                  } else {
                                    echo "<option value='".$key['typbid']."'>".$key['nama_tipe']."</option>";
                                  }
                                }
                               ?>
                             </select>
                          </td>
                           <td width="15%">Warna</td>
                          <td>
                            <select name="nm_warna" id="nm_warna" class="form-control form-control-sm">
                               <option></option>
                                <?php 
                                foreach ($list_warna->result_array() as $key) {

                                  if ( $key['twid'] == $nm_warna) {
                                    echo "<option value='".$key['twid']."' selected='selected'>".$key['nama_warna']."</option>";
                                  } else {
                                  echo "<option value='".$key['twid']."'>".$key['nama_warna']."</option>";

                                  }
                                }
                               ?>
                             </select>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <input type='submit' name='btn_cari' id='btn_cari' class='btn btn-primary btn-sm' value='Cari'/>
                          </td>
                        </tr>
                      </table>
                  </form>


                  <table id='example2' class='table table-bordered table-hover'>  
                    <thead>
                      <tr>
                        <th width='1%'>No</th>
                        <th>Nama Barang</th>
                        <th>Jenis / Tipe / Warna</th>
                        
                        <Th>Harga Jual (Kg)</Th>
                        <!-- <th width='15%'>Nama Satuan</th> -->
                        <th width='10%'>Fungsi</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                          $no=1;
                           foreach($list->result_array() as $i) {
                              echo "
                                  <Tr>
                                    <td>".$no."</td>
                                    <td>".$i['nama_barang']."<br><span style='font-size:8pt;color:red;'>User & Time Create : ".$i['user_create']." - ".date('d-M-Y H:i:s',strtotime($i['create_time']))."</span>
                                    <br><span style='font-size:8pt;color:red;'>User & Time Edit : ".$i['user_edit']." - ".($i['user_edit'] == '-' ? '' : date('d-M-Y H:i:s',strtotime($i['modify_time'])))."</span></td>
                                    <td>".$i['kd_jenis'].' ( '.$i['nm_jenis']." ) / ".$i['nama_tipe']." / ".$i['nama_warna']."</td>
                                    <td>".$i['harga_jual']."</td>
                                    ";
                                    /**/
                                    echo"<td align='center'>
                                      <button class='btn btn-warning btn-sm' onclick='editBarang(".$i['tbrid'].");'><i class='fa fa-edit'></i></button>&nbsp;";
                                      if ($i['ada_pakai']>0){
                                        echo "<button class='btn btn-danger btn-sm' title='Barang tidak bisa dihapus, data sudah dipakai' onclick='hapusBarang(".$i['tbrid'].",\"".$i['nama_barang']."\");' disabled><i class='fa fa-times'></i></button>";
                                      }
                                        else{
                                          echo "<button class='btn btn-danger btn-sm' title='Hapus Barang' onclick='hapusBarang(".$i['tbrid'].",\"".$i['nama_barang']."\");'><i class='fa fa-times'></i></button>";
                                        }
                                    echo"</td>
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
  $(function () {
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": true,
    });
  });
</script>
