
<script type="text/javascript">
function back() {

    window.location.replace("<?php echo base_url('Masterdata/warna')?>");
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
            <h1 class="m-0 text-dark"><i class='fa fa-tint'></i> Master Warna Barang</h1>
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
                  Edit Warna
                </h3>
              </div>
              <form class='form-horizontal' name='frm' id='frm' method='post' action="<?php echo base_url('masterdata/act_edit_warna'); ?>">
                  <div class='card-body'>
              
                  <div class='form-group-row'>

                        <div class='col-6' style='float:left'>

                           <label for="inputEmail3" class="col-sm-3 col-form-label">Nama Warna</label>
                            <div class="col-sm-12">
                              <input type="hidden" name="twid" id="twid" value="<?php echo $list->twid; ?>"/>
                              <input type="text" class="form-control form-control-sm" name='nama_warna' id='nama_warna' value="<?php echo $list->nama_warna; ?>">
                            </div>
                          </div>

                          <div class='col-6' style='float:left'>
                           <label for="inputEmail3" class="col-sm-3 col-form-label">Kategori Warna</label>
                            <div class="col-sm-12">
                              <select name="tkwid" id="tkwid" class="form-control form-control-sm">
                                <option></option>
                                <?php 

                                  foreach ($kategori_warna->result_array() as $key) {

                                    if ( $key['tkwid'] == $list->tkwid) {
                                         echo "<option value='".$key['tkwid']."' selected='selected'>".$key['kat_warna']."</option>";
                                     } else {
                                         echo "<option value='".$key['tkwid']."'>".$key['kat_warna']."</option>";
                                    }
                                   
                                  }
                                ?>
                              </select>
                            </div>
                           </div>
                  </div>


                  <?php $is_aktifya = $is_aktifno = $is_makananya = $is_makananno = "";

                        if ($list->is_aktif == 1){

                            $is_aktifya="checked='checked'";

                        }elseif($list->is_aktif == 0 AND $list->is_aktif != 1) {
                            $is_aktifno = "checked='checked'";
                        }

                  ?>

                  <div class='col col-12 form-group-row'>
                            <div class="col-1" style="float:left">
                            <label for="inputEmail3" class="col-form-label">Is Aktif ?</label>
                                <div class='form-check'>
                                    <input type='radio' class='form-check-input' value='1' name='is_aktif' id='is_aktifya' <?php echo $is_aktifya;?> />
                                    <label class="form-check-label">Ya</label>
                                </div> 
                            </div>
                            <div class="col-6"  style="float:left">
                            <label for="inputEmail3" class="col-sm-5 col-form-label">&nbsp;</label>

                                <div class='form-check'>
                                    <input type='radio' class='form-check-input' value='0' name='is_aktif' id='is_aktifno' <?php echo $is_aktifno;?>/>
                                    <label class="form-check-label">Tidak</label>
                                </div> 
                            </div>
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
