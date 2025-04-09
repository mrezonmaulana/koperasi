<script type="text/javascript">
function back() {

    window.location.replace("<?php echo base_url('Masterdata/satuan')?>");
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
            <h1 class="m-0 text-dark"><i class='fa fa-puzzle-piece'></i> Master Satuan</h1>
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
                  Edit Satuan
                </h3>
              </div>
              <form class='form-horizontal' name='frm' id='frm' method='post' action="<?php echo base_url('masterdata/act_add_satuan'); ?>">
                <input type='hidden' name='tsid' id='tsid' value='<?php echo $list->tsid;?>'/>
                  <div class='card-body'>
              
                  <div class='form-group-row'>
                           <label for="inputEmail3" class="col-sm-3 col-form-label">Nama Satuan</label>
                            <div class="col-sm-12">
                              <input type="text" class="form-control" name='nama_satuan' id='nama_satuan' value='<?php echo $list->satuan;?>'>
                            </div>
                  </div>

                  <div class="form-group-row col-2">
                    

                                      
                                         <label class="col col-form-label"> Is Aktif </label>
                                         <?php 

                                          $checkedy = $checkedn= "";

                                          if ( $list->is_aktif == '1') {
                                            $checkedy = "selected='selected'";
                                          }elseif ( $list->is_aktif == '0') {
                                            $checkedn = "selected='selected'";
                                          }
                                         ?>
                                         <select name='is_aktif' id='is_aktif' class='form-control'>
                                              <option value='1' <?php echo $checkedy; ?>>Ya</option>
                                              <option value='0' <?php echo $checkedn; ?>>Tidak</option>
                                          </select> 
                                        
                                    </div>
                                

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
