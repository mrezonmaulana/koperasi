
<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/autocomplete.css">
<script type="text/javascript">
function setPagu(){
  var nominal = backToNormal(document.getElementById('rupiah'));
  var pagu = (10.9999 / 100) * nominal;
  var realpagu = nominal - pagu;
  document.getElementById('pagu').value = formatRupiah("'"+realpagu+"'",'Rp. ');
}

function cekKepala(id){

  if ( parseInt(id.value) > 0 ) {

     $.post("<?php echo base_url('Project/get_kepala')?>",{tphid:id.value},function(data){
        var list = JSON.parse(data);

        document.getElementById('teid_kepala').value = list.list_detail_usaha.teid;
        document.getElementById('teid_kepala_text').value = list.list_detail_usaha.nama_kepala;

     });
   } else {
    document.getElementById('teid_kepala').value = '';
        document.getElementById('teid_kepala_text').value = '';
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
            <h1 class="m-0 text-dark"><i class='fa fa-project-diagram'></i> Project</h1>
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
                  Edit Project
                </h3>
              </div>
              <form class='form-horizontal' name='frm' id='frm' method='post' action="<?php echo base_url('project/act_edit_project'); ?>">
                <input type="hidden" name="tpid" id="tpid" value="<?php echo $list_project->tpid; ?>"/>
                  <div class='card-body'>


                    <div class='form-group-row'>

                      <div class='col-3' style="float:left">
                          <label class="col col-form-label">Tanggal Mulai</label>
                          <div class="col">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text">
                                  <i class="far fa-calendar-alt"></i>
                                </span>
                              </div>
                             <input type="text" class="form-control float-right" name="tgl_mulai" id="reservation" value='<?php echo date('m/d/Y',strtotime($list_project->tgl_mulai));?>'>
                            </div>
                          </div>
                      </div>

                      <div class='col-3' style="float:left">
                          
                           <label class="col col-form-label">Bidang</label>
                            <div class="col">
                              <select name='bidang' id='bidang' class='form-control' required="">
                                <option></option>
                                <?php 
                                  foreach ($list_bidang->result_array() as $k ) {
                                    $sel_bidang = "";
                                    if ( $list_project->tbid == $k['tbid'] ) {
                                        $sel_bidang = "selected='selected'";
                                    }
                                    echo "<option value='".$k['tbid']."' ".$sel_bidang.">".$k['nama_bidang']."</option>";
                                  }
                                ?>
                              </select>
                            </div>

                      </div>

                      <div class='col-3' style="float:left">
                          
                           <label class="col col-form-label">Perusahaan</label>
                            <div class="col">
                              <select name='tphid' id='tphid' class='form-control' required="" onchange="cekKepala(this)">
                                <option></option>
                                <?php 
                                  foreach ($list_perusahaan->result_array() as $k ) {
                                    $sels = "";
                                    if ( $k['tphid'] == $list_project->tphid ) {
                                      $sels = "selected='selected'";
                                    }
                                    echo "<option value='".$k['tphid']."' ".$sels.">".$k['nama_perusahaan']."</option>";
                                  }
                                ?>
                              </select>
                            </div>

                      </div>

                       <div class='col-3' style="float:left">
                          
                           <label class="col col-form-label">Direktur Perusahaan</label>
                            <div class="col">
                              <input type='hidden' name='teid_kepala' id='teid_kepala' value='<?php echo $list_project->teid_kepala; ?>'/>
                              <input type='text' id='teid_kepala_text' value='<?php echo $list_project->nama_kepala; ?>' class='form-control' readonly=""/>
                            </div>

                      </div>
                  </div>

                  <div class='form-group-row'>
                           <div class='col-2' style="float:left">

                                <label for="inputEmail3" class="col col-form-label">Pelaksana</label>
                                <div class="col">
                                  <select name='pelaksana' id='pelaksana' class='form-control' required="">
                                    <option></option>
                                    <?php 
                                      foreach ($list_pelaksana->result_array() as $l ) {
                                        $sel_pelaksana = "";
                                        if ( $list_project->teid_pelaksana == $l['teid'] ) {
                                          $sel_pelaksana = "selected='selected'";
                                        }
                                        echo "<option value='".$l['teid']."' ".$sel_pelaksana.">".$l['nama_karyawan']."</option>";
                                      }
                                    ?>
                                  </select>
                                </div>
                           </div>

                           <div class='col-2' style="float:left">

                                <label for="inputEmail3" class="col col-form-label">Kepala Tukang</label>
                                <div class="col">
                                  <select name='kepala_tukang' id='kepala_tukang' class='form-control' required="">
                                    <option></option>
                                    <?php 
                                      foreach ($list_kepalatukang->result_array() as $j ) {
                                        $sel_kelapatukang = "";
                                        if ( $list_project->teid_kepalatukang == $j['teid'] ) {
                                          $sel_kelapatukang = "selected='selected'";
                                        }
                                        echo "<option value='".$j['teid']."' ".$sel_kelapatukang.">".$j['nama_karyawan']."</option>";
                                      }
                                    ?>
                                  </select>
                                </div>
                           </div>

                           <div class='col-4' style="float:left">
                                <label for="inputEmail3" class="col col-form-label">Nama Project</label>
                                <div class="col">
                                  <input type="text" class="form-control" name='nama_project' id='nama_project' value='<?php echo $list_project->nama_project; ?>' required="">
                                </div>
                           </div>

                           <div class='col-4' style="float:left">
                                <label for="inputEmail3" class="col col-form-label">Alamat</label>
                                <div class="col">
                                  <input type="text" class="form-control" name='alamat' id='alamat' value='<?php echo $list_project->alamat; ?>' required="">
                                </div>
                           </div>
                  </div>

                  <div class='form-group-row'>
                        <div class='col-3' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">Kode Pos</label>
                                <div class="col">
                                  <input type="text" class="form-control" name='kode_pos' id='kode_pos' value="<?php echo $list_project->kode_pos;?>">
                                </div>

                        </div>

                        <div class='col-3' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">Kelurahan</label>
                                <div class="col">
                                  <input type="text" class="form-control" name='kelurahan' id='kelurahan' readonly="" value="<?php echo $list_project->kelurahan;?>">
                                </div>

                        </div>

                         <div class='col-3' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">Kecamatan</label>
                                <div class="col">
                                  <input type="text" class="form-control" name='kecamatan' id='kecamatan' readonly="" value="<?php echo $list_project->kecamatan;?>">
                                </div>

                        </div>

                         <div class='col-3' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">Kabupaten</label>
                                <div class="col">
                                  <input type="text" class="form-control" name='kabupaten' id='kabupaten' readonly="" value="<?php echo $list_project->kabupaten;?>">
                                </div>

                        </div>

                  </div>

                  <div class='form-group-row'>
                    <div class='col-3' style="float:left">

                        <?php 

                          $readonly_nilai = "";

                          if ( $list_project->ada_ajuan > 0 or $list_project->biaya_total_barang > 0) {
                              $readonly_nilai = "readonly";
                          }

                        ?>

                        <label for="inputEmail3" class="col col-form-label">Nilai Kontrak</label>
                            <div class="col">
                              <input type="text" class="form-control" name='anggaran' id='rupiah' onkeyup="setNominal('rupiah');" onblur="setPagu();" value='<?php echo $list_project->anggaran; ?>' required="" <?php echo $readonly_nilai;?> >
                            </div>

                    </div>

                    <div class='col-3' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">Real Cost</label>
                                <div class="col">
                                  <input type="text" class="form-control" name='pagu' id='pagu' value='<?php echo $list_project->pagu; ?>' <?php echo $readonly_nilai;?>>
                                  <input type="hidden" name='pagu_orig' id='pagu_orig' value='<?php echo $list_project->pagu; ?>'>
                                </div>

                        </div>

                    <div class='col-3' style="float:left">

                        <label for="inputEmail3" class="col col-form-label">Potongan Lain</label>
                            <div class="col">
                              <?php $sel_pot10 = $sel_pot15 = $sel_pot20 = "";

                                  if ( $list_project->pot_other_persen == 10 ) {
                                      $sel_pot10 = "selected='selected'";
                                  }elseif ( $list_project->pot_other_persen == 15 ) {
                                      $sel_pot15 = "selected='selected'";
                                  }elseif ( $list_project->pot_other_persen == 20 ) {
                                      $sel_pot20 = "selected='selected'";
                                  }

                              ?>

                              <input type='hidden' name='pot_other_amount' value='<?php echo $list_project->pot_other_amount; ?>'>
                              <select name='potongan_lain' id='potongan_lain' class='form-control'>
                                    <option></option>
                                    <option value='10' <?php echo $sel_pot10; ?>>10%</option>
                                    <option value='15' <?php echo $sel_pot15; ?>>15%</option>
                                    <option value='20' <?php echo $sel_pot20; ?>>20%</option>
                                  </select>
                            </div>

                    </div>

                    <div class='col-3' style="float:left">

                        <label for="inputEmail3" class="col col-form-label">Keterangan</label>
                            <div class="col">
                              <input type="text" class="form-control" name='keterangan' id='keterangan' value='<?php echo $list_project->keterangan; ?>'>
                            </div>

                    </div>
                           
                  </div>

                  

                  </div>
                 <div class='card-footer'>
                 <input type='button' class='btn btn-info' value='Simpan' onclick="checkNom(document.getElementById('rupiah'));checkNom(document.getElementById('pagu'));document.frm.submit();"/>
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
    $('#reservation').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true
    }, 
    function(start, end, label) {
        var years = moment().diff(start, 'years');
        
    });
});


  $(document).ready(function(){

$( "#kode_pos" ).autocomplete({
  source: "<?php echo base_url('Project/search_wilayah')?>",
  select: function( event, ui ) {

    var hasil = ui.item.label;

    hasil = hasil.split(' - ');

    var hasil2 = hasil[1].split(',');

    $('#kelurahan').val(hasil2[0]);
    $('#kecamatan').val(hasil2[1]);
    $('#kabupaten').val(hasil2[2]);
  }
});

});
</script>

<script type="text/javascript">
document.getElementById('rupiah').value = formatRupiah("<?php echo $list_project->anggaran?>",'Rp. ') ;
document.getElementById('pagu').value = formatRupiah("<?php echo $list_project->pagu?>",'Rp. ') ;
</script>