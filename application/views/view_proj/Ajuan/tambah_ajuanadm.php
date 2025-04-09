
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

function getKontrak(obj) {

   var val = obj.value;
   var data = val.split(':');
   var anggaran = data[1];
   var pagu = data[2];
   var bidang = data[3];
   if ( bidang == 1) {
      document.getElementById('for_backup').innerHTML = 'Backup';
      $("#kontrak").val('');
      $("#for_kontrak").removeAttr("hidden");
   }else{
      document.getElementById('for_backup').innerHTML = 'Backup & Kontrak';
      $("#for_kontrak").attr("hidden","hidden");
      $("#kontrak").val('');
   }

   document.getElementById('anggaran').value = formatRupiah("'"+anggaran+"'","Rp. ");
   document.getElementById('pagu').value = formatRupiah("'"+pagu+"'","Rp. ");

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
            <h1 class="m-0 text-dark"><i class='fa fa-money-check-alt'></i> Ajuan Administrasi</h1>
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

              <form class='form-horizontal' name='frm' id='frm' method='post' action="<?php echo base_url('Ajuan/act_add_ajuanadm'); ?>">
                  <div class='card-body'>
                    <div class='form-group-row'>
                              <div class="col-sm-12" style='float:left'>
                              <label for="inputEmail3" class="col">Project</label>
                                 <select name='tpid' id='tpid' class='form-control' onchange="getKontrak(this)" required="">
                                  <option></option>
                                  <?php 
                                    foreach ($list_project->result_array() as $key) {
                                        echo "
                                          <option value='".$key['tpid'].":".$key['anggaran'].":".$key['pagu'].":".$key['tbid']."'>".$key['nama_project']." ( ".$key['nama_bidang']." )</option>
                                        ";
                                    }
                                  ?>
                                 </select>
                              </div>
                              <div class="col-sm-3" style='float:left'>
                              <label for="inputEmail3" class="col">Nilai Kontrak</label>
                                <input type="text" class="form-control" id='anggaran' required="" readonly="" style='text-align:right'>
                              </div>

                              <div class="col-sm-3" style='float:left'>
                              <label for="inputEmail3" class="col">Real Cost</label>
                                <input type="text" class="form-control" id='pagu' required="" readonly="" style='text-align:right'>
                              </div>

                              <div class="col-sm-3" style='float:left'>
                              <label for="inputEmail3" class="col" id="for_backup">Backup & Kontrak</label>
                                <input type="text" class="form-control" name='backup' id='backup' required="" style='text-align:right'>
                              </div>

                              <div class="col-sm-3" style='float:left' id="for_kontrak" hidden>
                              <label for="inputEmail3" class="col" >Kontrak</label>
                                <input type="text" class="form-control" name='kontrak_ajuan' id='kontrak'  style='text-align:right'>
                              </div>

                              <div class="col-sm-3" style='float:left'>
                              <label for="inputEmail3" class="col">PHO</label>
                                <input type="text" class="form-control" name='pho' id='pho' required="" style='text-align:right'>
                              </div>

                              <div class="col-sm-3" style='float:left'>
                              <label for="inputEmail3" class="col">Keuangan</label>
                                <input type="text" class="form-control" name='keuangan' id='keuangan' required="" style='text-align:right'>
                              </div>

                              <div class="col-sm-3" style='float:left'>
                              <label for="inputEmail3" class="col">PMI</label>
                                <input type="text" class="form-control" name='pmi' id='pmi' required="" style='text-align:right'>
                              </div>

                              <div class="col-sm-3" style='float:left'>
                              <label for="inputEmail3" class="col">BASP</label>
                                <input type="text" class="form-control" name='basp' id='basp' required="" style='text-align:right'>
                              </div>

                              <div class="col-sm-3" style='float:left'>
                              <label for="inputEmail3" class="col">BKD</label>
                                <input type="text" class="form-control" name='bkd' id='bkd' required="" style='text-align:right'>
                              </div>
                              <div class="col-sm-3" style='float:left'>
                              <label for="inputEmail3" class="col">Ajuan Lain</label>
                                <input type="text" class="form-control" name='ajuan_lain' id='ajuan_lain' style='text-align:right'>
                              </div>
                              <div class="col-sm-7" style='float:left'>
                              <label for="inputEmail3" class="col">Keterangan</label>
                                <input type="text" class="form-control" name='keterangan' id='keterangan' >
                              </div>
                    </div>
                  </div>

                 <div class='card-footer'>
                   <input type='submit' class='btn btn-info' value='Simpan' onclick="return backNormal();"/>
                   
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
function backNormal()
{

  checkNom(document.getElementById("backup"));
  checkNom(document.getElementById("kontrak"));
  checkNom(document.getElementById("pho"));
  checkNom(document.getElementById("keuangan"));
  checkNom(document.getElementById("pmi"));
  checkNom(document.getElementById("basp"));
  checkNom(document.getElementById("bkd"));
  checkNom(document.getElementById("ajuan_lain"));

  return true;

}


  $(function() {
   /* $('#tgl_ajuan').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true
    }, 
    function(start, end, label) {
        var years = moment().diff(start, 'years');
        
    });*/
});

setNominal("backup");
setNominal("kontrak");
setNominal("pho");
setNominal("keuangan");
setNominal("pmi");
setNominal("basp");
setNominal("bkd");
setNominal("ajuan_lain");

</script>