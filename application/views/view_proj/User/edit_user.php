
<script type="text/javascript">
function changePass(){
    var curr_pass = $('#curr_pass').val();
    var new_pass = $('#new_pass').val();
    var tuid = $('#tuid').val();
    var from = "<?php echo $from;?>";


    
    // cek password lama apakah sesuai dengan data 
    $.post("<?php echo base_url('Login/cek_user_pass')?>",{tuid:tuid,curr_pass:curr_pass,new_pass:new_pass,asal:from},function(data){
              if ( data == 1 ) {
                 alert('Data Berhasil Dirubah');
                 if ( from == 'list') {
                  window.location.replace('<?php echo base_url()?>/Login/list_user');
                 }else{
                  window.location.replace('<?php echo base_url()?>/Login/user_settings');
                 }
              } else {
                  alert('Password Lama Tidak Sesuai');
                  $('#curr_pass').focus();
              }
    });
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
            <h1 class="m-0 text-dark"><i class='fa fa-user'></i> Akun</h1>
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
          <div class="col-6" style="margin:auto !important">
            <div class='card'>
              <div class='card-header'>
                <h3 class='card-title' style='margin-top:10px'>
                  Pengaturan Akun
                </h3>
              </div>
              <form class='form-horizontal' name='frm' id='frm' method='post' action="<?php echo base_url('masterdata/act_edit_user'); ?>">
                  <div class='card-body'>
                    <div class='form-group-row'>
                             <label for="inputEmail3" class="col-sm-4 col-form-label">Username</label>
                              <div class="col-sm-12">
                                <input type="hidden" name="tuid" id="tuid" value="<?php echo $list->tuid; ?>"/>
                                <input type="text" class="form-control" name='login_name' id='login_name' readonly='readonly' value="<?php echo $list->login_name; ?>">
                              </div>
                    </div>
                    <div class='form-group-row' <?php echo $is_hidden;?> >
                             <label for="inputEmail3" class="col-sm-4 col-form-label">Password Lama</label>
                              <div class="col-sm-12">
                                <input type="password" class="form-control" name='curr_pass' id='curr_pass' required="">
                              </div>
                    </div>

                    <div class='form-group-row'>
                             <label for="inputEmail3" class="col-sm-4 col-form-label">Password Baru</label>
                              <div class="col-sm-12">
                                <input type="password" class="form-control" name='new_pass' id='new_pass' required="">
                              </div>
                    </div>
                  </div>

                 <div class='card-footer'>
                   <input type='button' class='btn btn-info' value='Ubah' onclick="changePass();"/>
                </div>
               </form>
              </div>
            </div>
            
          </div>
          <!-- ./col -->
        </div>
        
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
