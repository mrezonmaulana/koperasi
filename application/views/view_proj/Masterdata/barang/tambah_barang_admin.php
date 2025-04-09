<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/select2/css/select2.min.css">
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/select2/js/select2.min.js"></script>
<script type="text/javascript">

$(document).ready(function(){

  $("select[name='template_barang']").select2();


});

function back() {

    window.location.replace("<?php echo base_url('Masterdata/barang')?>");
}

function setSatuanBesar(obj){

  $("#text_satuan_kecil").html('');
  $("select[id='satuan_besar']").val('').trigger('change');
  if ( obj.value != '' ) {
     var inner_text = $("select[id='satuan'] option:selected").text();
     $("#text_satuan_kecil").html(inner_text);
  }
}

function cekSatuanKecil(obj){

  var satuan_kecil = $("select[id='satuan']").val();
  $("#text_satuan_besar").html('');

  if ( satuan_kecil == '' ) {
    alert('Pilih Satuan Kecil Terlebih Dahulu');
    obj.value = '';
    return false;
  }

  if ( obj.value == satuan_kecil) {
    alert('Satuan besar tidak boleh sama dengan satuan kecil');
    obj.value = '';
    return false;
  }

  if ( obj.value != '' ) {
     var inner_text = $("select[id='satuan_besar'] option:selected").text();
     $("#text_satuan_besar").html(inner_text);
  }
}

function pilihSatuan(){
  var satuan = $("select[name='satuan']").val();

  if ( satuan == '' ) {
    alert('Pilih satuan kecil terlebih dahulu');
  }else{
    NewWindow("<?php echo base_url()?>Masterdata/list_satuan_pilih","","500","600","yes");
  }
}

function setispo(obj,id) {

  var inputan = $("input[name='is_po[]']");

  $.each(inputan,function(k,v){
      if ($(this).attr('data-id') == id) {
         $(this).val('1');
      }else{
        $(this).val('0');
      }
  });
}

function addSatuan(tsid,nama_satuan,isikecil=""){
    var tblList     = document.getElementById("table_satuan");
    var tblBody     = tblList.tBodies[0];
    var lastRow     = tblBody.rows.length;
    var row         = tblBody.insertRow(lastRow);

    var inner_text = $("select[id='satuan'] option:selected").text();

    $("select[id='satuan']").css('pointer-events','none');


    row.style.fontSize="8pt";
    row.setAttribute("id","row-satuan-"+lastRow);
    var newCell = row.insertCell(0);
        newCell.align ="center";
        newCell.style.padding=".45rem";
        newCell.innerHTML = 1;

    var newCell = row.insertCell(1);
        newCell.align ="center";
        newCell.style.padding=".45rem";
        newCell.innerHTML = nama_satuan+"<input type='hidden' name='tsid_detail[]' id='tsid_detail[]' value='"+tsid+"'/>";

    var newCell = row.insertCell(2);
        newCell.align ="center";
        newCell.style.padding=".45rem";
        newCell.innerHTML = "<input type='radio' name='is_po_real[]' id='is_po_real[]' onchange='setispo(this,"+lastRow+");'/><input type='hidden' name='is_po[]' id='is_po[]' data-id='"+lastRow+"' />";

    var newCell = row.insertCell(3);
        newCell.align ="center";
        newCell.style.padding=".45rem";
        newCell.innerHTML = "<input type='text' class='form-control form-control-sm' style='text-align:center' name='konversi_detail[]' id='konversi_detail[]' value='"+isikecil+"'/>";

    var newCell = row.insertCell(4);
        newCell.align ="center";
        newCell.style.padding=".45rem";
        newCell.innerHTML = inner_text;

     var newCell = row.insertCell(5);
        newCell.align ="center";
        newCell.style.padding=".45rem";
        newCell.innerHTML = "<button class='btn btn-danger btn-sm' onclick='removeRow("+lastRow+"); return false;'><i class='fa fa-times' ></i></button>";
}

function removeRow(id){
  $("#row-satuan-"+id).remove();

  cekjmldetail();
}

function cekjmldetail(){
  var tsid_detail = $("input[name='tsid_detail[]'");

  if ( tsid_detail.length == 0 ) {
     $("select[id='satuan']").css('pointer-events','auto');
  }
}

function setTemplate(obj){

  if ( obj.value != '0' ) {

      $("#table_satuan tbody").empty();

      $.post("<?php echo base_url('Masterdata/list_master_barang');?>",{tbrid:obj.value},function(data){

                    var hasil = JSON.parse(data);
                    var num = 0;
            
                    $.each(hasil,function(k,v){
                        $("input[name='nama_barang']").val(v.nama_barang);
                        $("select[name='satuan']").val(v.satuan);
                        $("select[name='tjbid']").val(v.tjbid);
                        $("select[name='typbid']").val(v.typbid);
                        $("input[name='gram']").val(v.gram);
                        $("input[name='lebar']").val(v.lebar);
                        $("select[name='twid']").val(v.twid);
                        $("input[name='hna']").val(v.hna);
                        $("input[name='harga_jual']").val(v.harga_jual);

                        var detail_satuan = v.list_satuan;
                        detail_satuan = detail_satuan.split(":");
                        
                        $.each(detail_satuan,function(key,value){

                            var listnya = value.replace('\"','');
                            listnya = listnya.replace('\"','');

                            var new_list = listnya.split("_");

                            addSatuan(new_list[0],new_list[1],new_list[2]);

                        })

                    });

                    obj.value='0';
              });
  }
}
</script>
<style>
    .select2-container{
        width: 300px !important;
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
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-12">
            <div class='card'>
              <div class='card-header'>
                <h3 class='card-title' style='margin-top:10px'>
                  Tambah Barang
                </h3>
                <span style="float:right">
                    <select name='template_barang' id='template_barang' class='form-control form-control-sm' onchange="setTemplate(this);">
                        <option value='0'>--- Pilih Dari Template ---</option>
                        <?php 

                        foreach ($list_template->result_array() as $key) {
                          echo "<option value='".$key['tbrid']."'>".$key['nama_barang']." - ".$key['nama_warna']."</option>";
                        }

                        ?>
                    </select>
                  </span>
              </div>
              <form class='form-horizontal' name='frm' id='frm' method='post' action="<?php echo base_url('masterdata/act_add_barang'); ?>">
                  <div class='card-body'>
              
                  <div class='form-group-row'>
                          <div class='col col-12'>
                              
                              <div class='col col-6' style='float:left'>
                                  <label class="col-form-label">Nama Barang</label>
                                  <input type="text" class="form-control form-control-sm" name='nama_barang' id='nama_barang' required="">
                              </div>
                              <div class='col col-3' style='float:left'>
                                  <label class="col-form-label">Satuan Kecil</label>
                                  <select name='satuan' id='satuan' class='form-control form-control-sm' required="">
                                <option></option>
                                <?php 
                                  foreach ($list->result_array() as $k) {
                                    echo "<option value='".$k['tsid']."'>".$k['satuan']."</option>";
                                  }
                                ?>
                              </select>
                              </div>

                              <div class='col col-3' style='float:left'>
                                  <label class="col-form-label">Jenis Barang</label>

                                  <select name='tjbid' id='tjbid' class='form-control form-control-sm' required="">
                                  <option></option>
                                    <?php 
                                      foreach ($list_jenis->result_array() as $k) {
                                        echo "<option value='".$k['tjbid']."'>".$k['kd_jenis']." ( ".$k['nm_jenis']." )</option>";
                                      }
                                    ?>
                                  </select>

                                  <select name='tkgid' id='tkgid' class='form-control form-control-sm' required="" hidden>
                                  <option></option>
                                    <?php 
                                      foreach ($list_kategori->result_array() as $k) {
                                        echo "<option value='".$k['tkgid']."' selected>".$k['nama_kategori']."</option>";
                                      }
                                    ?>
                                  </select>
                              </div>

                          </div>
                  </div>

                  <div class='form-group-row'>
                        
                        <div class='col col-12'>

                             <div class='col col-3' style='float:left'>
                                  <label class="col-form-label">Type Barang</label>

                                  <select name='typbid' id='typbid' class='form-control form-control-sm' required="">
                                  <option></option>
                                    <?php 
                                      foreach ($list_tipe->result_array() as $k) {
                                        echo "<option value='".$k['typbid']."'>".$k['nama_tipe']."</option>";
                                      }
                                    ?>
                                  </select>
                              </div>

                              <div class='col col-3' style='float:left'>
                                  <label class="col-form-label">Gram</label>
                                  <input type="text" class="form-control form-control-sm" name='gram' id='gram' required="">
                              </div>

                              <div class='col col-3' style='float:left'>
                                  <label class="col-form-label">Lebar</label>
                                  <input type="text" class="form-control form-control-sm" name='lebar' id='lebar' required="">
                              </div>

                               <div class='col col-3' style='float:left'>
                                  <label class="col-form-label">Warna Barang</label>

                                  <select name='twid' id='twid' class='form-control form-control-sm' required="">
                                  <option></option>
                                    <?php 
                                      foreach ($list_warna->result_array() as $k) {
                                        echo "<option value='".$k['twid']."'>".$k['nama_warna']."</option>";
                                      }
                                    ?>
                                  </select>
                              </div>


                              <div class='col col-3' style='float:left' hidden>
                                  <label class="col-form-label">Hna</label>
                                  <input type="number" class="form-control form-control-sm" name='hna' id='hna'>
                              </div>

                              <div class='col col-3' style='float:left'>
                                  <label class="col-form-label">Harga Jual (Kg)</label>
                                  <input type="number" class="form-control form-control-sm" name='harga_jual' id='harga_jual' required="">
                              </div>

                              <div class='col col-3' style='float:left'>
                                  <label class="col-form-label">Is Aktif</label>
                                  <select name='is_aktif' id='is_aktif' class='form-control form-control-sm'>
                                    <option value='1'>Ya</option>
                                    <option value='0'>Tidak</option>
                              </select>
                              </div>

                              <div class='col col-6' style="float:left">
                                 <label class="col-form-label">Satuan Besar</label>
                                 <table class='table table-bordered' id='table_satuan'>
                                    <thead>
                                      <tr>
                                        <th colspan="6" style="padding:.35rem !important;font-size:8pt;text-align: right">
                                          <button class="btn btn-default btn-sm" onclick="pilihSatuan(); return false;">Tambah</button>
                                        </th>
                                      </tr>
                                      <tr>
                                        <th style="padding:.35rem !important;font-size:8pt;text-align:center" width="10%">Jml</th>
                                        <th style="padding:.35rem !important;font-size:8pt;text-align:center" width="20%">Satuan</th>
                                        <th style="padding:.35rem !important;font-size:8pt;text-align:center" width="20%">Untuk PO</th>
                                        <th style="padding:.35rem !important;font-size:8pt;text-align:center" width="20%">Konversi</th>
                                        <th style="padding:.35rem !important;font-size:8pt;text-align:center">Satuan Kecil</th>
                                        <th style="padding:.35rem !important;font-size:8pt;text-align:center" width="5%">&nbsp;</th>
                                      </tr>
                                    </thead>
                                    <tbody></tbody>
                                 </table>
                              </div>

                               <!--   <div class='col col-3' style='float:left'>
                                  <label class="col-form-label">Satuan Besar</label>
                                  <select name='satuan_besar' id='satuan_besar' class='form-control form-control-sm' required="" onchange="cekSatuanKecil(this);">
                                    <option></option>
                                    <?php 
                                      foreach ($list_satuan_besar->result_array() as $k) {
                                        echo "<option value='".$k['tsid']."'>".$k['satuan']."</option>";
                                      }
                                    ?>
                                  </select>
                              </div>

                              <div class='col col-3' style='float:left'>
                                  <label class="col-form-label">Konversi Satuan Kecil</label>
                                  <div class="input-group input-group-sm">
                                  <span class="input-group-append">
                                    <span class="input-group-text">1</span>
                                  </span>
                                  <span class="input-group-append">
                                    <span class="input-group-text"><font id="text_satuan_besar"></font></span>
                                  </span>
                                  <input type="text" class="form-control form-control-sm" name='konversi_satuan' id='konversi_satuan' style="text-align:center" required="">
                                  <span class="input-group-append">
                                    <button type="button" class="btn btn-default btn-flat"><font id="text_satuan_kecil"></font></button>
                                  </span>
                                </div>
                              </div> -->
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
       
          </section>
      </div><!-- /.container-fluid -->
    
  </div>
  
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
