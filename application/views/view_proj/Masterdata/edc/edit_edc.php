
<script type="text/javascript">
function back() {

    window.location.replace("<?php echo base_url('Masterdata/edc')?>");
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
            <h1 class="m-0 text-dark"><i class='fa fa-box-open'></i> Master EDC & No. Rekening</h1>
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
                  Edit EDC Atau No. Rekening
                </h3>
              </div>
              <form class='form-horizontal' name='frm' id='frm' method='post' action="<?php echo base_url('masterdata/act_edit_edc'); ?>">
                  <div class='card-body'>
              
                  <div class='form-group-row'>
                           <label for="inputEmail3" class="col-sm-3 col-form-label">Nama EDC / No.Rekening</label>
                            <div class="col-sm-12">
                              <input type="hidden" name="tedid" id="tedid" value="<?php echo $list->tedid; ?>"/>
                              <input type="text" class="form-control" name='nama_edc' id='nama_edc' value="<?php echo $list->nama_edc; ?>">
                            </div>
                  </div>

                  <?php $is_transya = $is_transno = "";

                        if ($list->is_trans == 1){
                            $is_transya="selected='selected'";
                        }elseif($list->is_trans == 2) {
                            $is_transno = "selected='selected'";
                        }


                  ?>

                   <div class='form-group-row'>
                           <label for="inputEmail3" class="col-sm-3 col-form-label">Jenis Transaksi</label>
                            <div class="col-sm-2">
                              <select name='is_trans' id='is_trans' class='form-control' required="">
                                    <option value='1' <?php echo $is_transya;?> >Debet / Credit</option>
                                    <option value='2' <?php echo $is_transno;?> >Transfer</option>
                              </select>
                            </div>
                  </div>

                  <?php $is_aktifya = $is_aktifno = "";

                        if ($list->is_aktif == 1){

                            $is_aktifya="checked='checked'";

                        }elseif($list->is_aktif == 0 AND $list->is_aktif != 1) {
                            $is_aktifno = "checked='checked'";
                        }

                  ?>

                  <div class='form-group-row'>
                            <label for="inputEmail3" class="col-sm-5 col-form-label">Is Aktif ?</label>
                            <div class="col-sm-6">
                                <div class='form-check'>
                                    <input type='radio' class='form-check-input' value='1' name='is_aktif' id='is_aktifya' <?php echo $is_aktifya; ?> />
                                    <label class="form-check-label">Ya</label>
                                </div> 
                            </div>
                            <div class="col-sm-6">
                                <div class='form-check'>
                                    <input type='radio' class='form-check-input' value='0' name='is_aktif' id='is_aktifno' <?php echo $is_aktifno; ?>/>
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
