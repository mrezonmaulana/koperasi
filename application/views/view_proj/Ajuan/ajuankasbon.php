
<script type="text/javascript">
function checkPass() {

   var curr_pass = $("#curr_pass").val();
   var new_pass = $("#new_pass").val();

   if ( curr_pass != new_pass ) {
      alert('Password Tidak Sama');
      $('#new_pass').focus();

      return false;
   } else {

      return true;
   }
}

function back(){

    window.location.replace("<?php echo base_url('Login/list_user')?>");
    
}

function chkform(){
  
  var total = backToNormal(document.getElementById('jumlah'));
  checkNom(document.getElementById('jumlah'));

  if ( parseFloat(total) <= 0 ) {

    alert('Jumlah Kas Bon Tidak Boleh 0');
    return false;
  }else{
    return true;
  }

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
            <h1 class="m-0 text-dark"><i class='fa fa-hand-holding-usd'></i> Ajuan Kasbon</h1>
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

              <form class='form-horizontal' name='frm' id='frm' method='post' action="<?php echo base_url('Ajuan/act_add_ajuankasbon'); ?>">
                  <div class='card-body'>
                    <div class='form-group-row'>
                              <div class="col-sm-6" style='float:left'>
                              <label class="col">Tanggal Ajuan</label>
                                    <div class="input-group">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text">
                                          <i class="far fa-calendar-alt"></i>
                                        </span>
                                      </div>
                                     <input type="text" class="form-control float-right" name="tgl_ajuan" id="tgl_ajuan">
                                    </div>
                              </div>
                              <div class="col-sm-6" style='float:left'>
                              <label for="inputEmail3" class="col">Karyawan</label>

                                 <select name='teid_pengaju' id='teid_pengaju' class='form-control' required="">
                                  <option></option>
                                  <?php 
                                    foreach ($list_pelaksana->result_array() as $key) {
                                        echo "
                                          <option value='".$key['teid']."'>".$key['nama_karyawan']."</option>
                                        ";
                                    }
                                  ?>
                                 </select>
                              
                              </div>
                    </div>
                    <div class='form-group-row'>
                              <div class="col" style='float:left'>
                              <label for="inputEmail3" class="col">Jumlah</label>
                                <input type="text" class="form-control" name='jumlah' id='jumlah' required="" style='text-align:right'>
                              </div>
                    </div>
                    <div class='form-group-row'>
                              <div class="col-sm-12" style='float:left'>
                              <label for="inputEmail3" class="col">Keperluan</label>
                                <input type="text" class="form-control" name='keperluan' id='keperluan' required="">
                              </div>
                    </div>
                    
                  </div>

                 <div class='card-footer'>
                   <input type='submit' class='btn btn-info' value='Simpan' onclick="return chkform();"/>
                   
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
<script type="text/javascript">
  $(function() {
    $('#tgl_ajuan').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true
    }, 
    function(start, end, label) {
        var years = moment().diff(start, 'years');
        
    });
});

setNominal("jumlah");



</script>