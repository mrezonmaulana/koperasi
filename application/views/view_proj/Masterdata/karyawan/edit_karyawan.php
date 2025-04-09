<script type="text/javascript">
function back() {

    window.location.replace("<?php echo base_url('Masterdata/karyawan')?>");
}
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
            <h1 class="m-0 text-dark"><i class='fa fa-people-carry'></i> Master Karyawan</h1>
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
          <div class="col-12">
            <div class='card'>
              <div class='card-header'>
                <h3 class='card-title' style='margin-top:10px'>
                  Tambah Karyawan
                </h3>
              </div>
              <form class='form-horizontal' name='frm' id='frm' method='post' action="<?php echo base_url('masterdata/act_add_karyawan'); ?>">
                   <input type="hidden" class="form-control" name='teid' id='teid' value='<?php echo $list->teid;?>'>  
                  <div class='card-body'>
                      <div class="form-group-row">
                          <div class="col col-12">
                                <div class="col col-4" style='float:left'>
                                    <label class="col-form-label"> Nama Karyawan </label>
                                    <input type="text" class="form-control" name='nama_karyawan' id='nama_karyawan' value='<?php echo $list->nama_karyawan;?>' required="">        
                                </div>
                                 <div class="col col-3" style='float:left'>
                                    <label class="col-form-label"> Role </label>
                                    <select name='trid' id='trid' class='form-control' required="">
                                          <option></option>
                                        <?php
                                            foreach($list_role->result_array() as $k) {

                                              $checked_role = "";

                                              if ( $k['trid'] == $list->trid ) {

                                                 $checked_role = "selected='selected'";
                                              }

                                              echo "<option value='".$k['trid']."' ".$checked_role.">".$k['nama_role']."</option>";
                                            }
                                           ?>
                                      </select>
                                </div>
                                <div class="col col-3" style='float:left'>
                                    <label class="col-form-label"> No Hp </label>
                                    <input type="text" class="form-control" name='no_telp' id='no_telp' value='<?php echo $list->no_telp;?>'>    
                                </div>
                                <div class="col col-2" style='float:left'>
                                    <label class="col-form-label"> Jenis Kelamin </label>
                                      <?php $checked_m = $checked_f = "";

                                          if ( $list->jenis_kelamin == 'm') {
                                             $checked_m = "selected='selected'";
                                          }elseif($list->jenis_kelamin == 'f') {
                                            $checked_f = "selected='selected'";
                                          }

                                      ?>
                                     <select name='jenis_kelamin' id='jenis_kelamin' class='form-control' required="">
                                          <option value='m' <?php echo $checked_m; ?>>Laki - Laki</option>
                                          <option value='f' <?php echo $checked_f; ?>>Perempuan</option>
                                      </select>   
                                </div>
                          </div>

                          <div class="col col-12">
                                <div class="col col-4" style='float:left'>
                                    <label class="col-form-label"> Tempat Lahir </label>
                                    <input type="text" class="form-control" name='tempat_lahir' id='tempat_lahir' value='<?php echo $list->tempat_lahir;?>'>        
                                </div>
                                <div class="col col-4" style='float:left'>
                                    <label class="col-form-label"> Tanggal Lahir </label>
                                      <div class="input-group">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text">
                                          <i class="far fa-calendar-alt"></i>
                                        </span>
                                      </div>
                                     <input type="text" class="form-control float-right" name="tgl_lahir" id="tgl_lahir" value='<?php echo date('m/d/Y',strtotime($list->tgl_lahir));?>'>
                                    </div>
                                </div>
                                <div class="col col-4" style='float:left'>
                                    <label class="col-form-label"> Nik </label>
                                    <input type="text" class="form-control" name='nik' id='nik' value='<?php echo $list->nik;?>'>    
                                </div>
                          </div>

                          <div class="col col-12">
                                <div class="col col-10" style='float:left'>
                                    <label class="col-form-label"> Alamat </label>
                                    <input type="text" class="form-control" name='alamat' id='alamat' value='<?php echo $list->alamat;?>'>        
                                </div>
                                <div class="col col-2" style='float:left'>
                                    <label class="col-form-label"> Is Aktif </label>
                                      <?php $checked_aktify = $checked_aktifn = "";

                                          if ( $list->is_aktif == '1') {
                                             $checked_aktify = "selected='selected'";
                                          }elseif($list->is_aktif == '0') {
                                            $checked_aktifn = "selected='selected'";
                                          }

                                      ?>
                                     <select name='is_aktif' id='is_aktif' class='form-control'>
                                          <option value='1' <?php echo $checked_aktify; ?>>Ya</option>
                                          <option value='0' <?php echo $checked_aktifn; ?>>Tidak</option>
                                      </select>   
                                </div>
                          </div>
                      </div>
                  <!-- <div class='form-group-row'>
                           <label for="inputEmail3" class="col-sm-3 col-form-label">Nama Karyawan</label>
                        <div class="col-sm-12">
                            
                        </div>
                  </div>
                  <div class='form-group-row'>
                           <label for="inputEmail3" class="col-sm-3 col-form-label">Role</label>
                            <div class="col-sm-12">
                              
                            </div>
                  </div> -->
                  </div>
                 <div class='card-footer'>
                 <input type='submit' class='btn btn-info' value='Simpan'/>
                 <input type='button' class='btn btn-warning' value='Kembali' onclick="back();"/>
                </div>
               </form>
              </div>
            </div>
            
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
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
  $(function() {
    $('#tgl_lahir').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true
    }, 
    function(start, end, label) {
        var years = moment().diff(start, 'years');
        
    });
});
</script>