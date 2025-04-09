
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

function lepasHeader(obj){
  var nama_id = $(obj).attr('data-acuan');
  var group = $("input[type='checkbox']").filter("[data-acuan='"+nama_id+"']");

  if ( group.length > 0 ) {

     
    var jml = 0;  
     $.each(group,function(k,v){
        if ( this.checked == true ) {
           jml = jml + 1;
        }
     });

    if ( jml == group.length ) {
       document.getElementById('checkbox_link_'+nama_id).checked = true;
    }else{
       document.getElementById('checkbox_link_'+nama_id).checked = false;
    }
  }

  
}

function setAnakAll(nama,obj){
  if ( obj.checked == true ) {
     $.each($("input[type='checkbox']"),function(k,v){
       if ( $(this).attr('data-acuan') == nama ) {
          this.checked = true;
       }
     });
  }else{

    $.each($("input[type='checkbox']"),function(k,v){
       if ( $(this).attr('data-acuan') == nama ) {
          this.checked = false;
       }
     });
     
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
                  Tambah Akun
                </h3>
              </div>
              <form class='form-horizontal' name='frm' id='frm' method='post' action="<?php echo base_url('Login/act_add_user'); ?>">
                  <div class='card-body'>


                    <div class='form-group-row'>
                           <label for="inputEmail3" class="col-sm-3 col-form-label">Karyawan</label>
                            <div class="col-sm-12">
                              <select name='teid' id='teid' class='form-control' required="">
                                <option></option>
                                <?php
                                    foreach($list_karyawan->result_array() as $k) {
                                      echo "<option value='".$k['teid']."'>".$k['nama_karyawan']."</option>";
                                    }
                                 ?>
                              </select>
                            </div>
                  </div>

                    <div class='form-group-row'>
                             <label for="inputEmail3" class="col-sm-4 col-form-label">Username</label>
                              <div class="col-sm-12">
                                <input type="text" class="form-control" name='login_name' id='login_name' required="">
                              </div>
                    </div>
                    <div class='form-group-row'>
                             <label for="inputEmail3" class="col-sm-4 col-form-label">Password</label>
                              <div class="col-sm-12">
                                <input type="password" class="form-control" name='curr_pass' id='curr_pass' required="">
                              </div>
                    </div>

                    <div class='form-group-row'>
                             <label for="inputEmail3" class="col-sm-5 col-form-label">Ketik Ulang Password</label>
                              <div class="col-sm-12">
                                <input type="password" class="form-control" name='new_pass' id='new_pass' required="">
                              </div>
                    </div>

                    <div class='form-group-row'>
                            <label for="inputEmail3" class="col-sm-5 col-form-label">Apkah akun ini admin ?</label>
                            <div class="col-sm-12">
                            <div class='form-check'>
                                <input type='checkbox' class='form-check-input' value='1' name='is_admin' id='is_admin'/>
                                <label class="form-check-label">Ya</label>
                            </div>  
                            </div>
                    </div>

                    <div class="form-group-row">
                        <label for="inputEmail3" class="col-sm col-form-label">Akses Menu ( Jika bukan admin )</label>
                        <div class="col-sm-12">
                          <?php 

                            $tmnid = "";
                            foreach ($list->result_array() as $key => $value) {
                                
                                if ( $tmnid != $value['tmnid'] AND $value['id_anak']!='') {
                                  $header = "<div class='form-check'>
                                              <input type='checkbox' class='form-check-input' value='1' name='tmnid[]' id='checkbox_link_".$value['id_header']."' onclick='setAnakAll(\"".$value['id_header']."\",this);'/>
                                              <label class='form-check-label'>".$value['header_menu']."</label>
                                          </div>";
                                }else{
                                  $header = "";
                                }


                                if ( $value['id_anak'] != '' ) {
                                  $spasi ="style='margin-left:15px;'";
                                  $acuan_header = "data-acuan='".$value['id_header']."'";
                                }else{
                                  $spasi = "";
                                  $acuan_header = "";
                                }

                                echo $header."<div class='form-check' ".$spasi.">
                                      <input type='checkbox' class='form-check-input' value='".$value['tmndid']."' name='tmndid[]' id='tmndid[]' ".$acuan_header." onclick='lepasHeader(this);'/>
                                      <label class='form-check-label'>".$value['nama_menu']."</label>
                                  </div> ";

                                  $tmnid = $value['tmnid'];
                            }

                          ?>
                        </div>
                    </div>
                  </div>

                 <div class='card-footer'>
                   <input type='submit' class='btn btn-info' value='Simpan' onclick="return checkPass()"/>
                   <input type='button' class='btn btn-warning' value='Kembali' onclick="back()"/>
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
