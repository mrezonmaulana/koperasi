<script type="text/javascript">
$(document).ready(function(){

//$("select[id='satuan']").trigger('change');
/*$("select[id='satuan_besar']").trigger('change');

if ( $("select[id='satuan']").value != '' ) {
     var inner_text = $("select[id='satuan'] option:selected").text();
     $("#text_satuan_kecil").html(inner_text);
  }*/

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

function setispo(id) {

  var inputan = $("input[name='is_po[]']");

  $.each(inputan,function(k,v){
      if ($(this).attr('data-id') == id) {
         $(this).val('1');
      }else{
        $(this).val('0');
      }
  });
}

function addSatuan(tsid,nama_satuan,isikecil="",jml_pakai=0,is_po=0,tbsid=0){
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
        if ( tbsid == 0) {
          newCell.innerHTML = nama_satuan+"<input type='hidden' name='tsid_detail[]' id='tsid_detail[]' value='"+tsid+"'/>";
        }else{
          newCell.innerHTML = nama_satuan;
        }

    var newCell = row.insertCell(2);
        newCell.align ="center";
        newCell.style.padding=".45rem";

        if ( tbsid > 0 ) {
            if (is_po == 1) {
              newCell.innerHTML = "<i class='fa fa-check'></i>";
            }else{
              newCell.innerHTML = "";
            }
        } else{

          if ( is_po == 1 ) {
            newCell.innerHTML = "<input type='radio' name='is_po_real[]' id='is_po_real[]' onchange='setispo("+lastRow+");' checked='checked'/><input type='hidden' name='is_po[]' id='is_po[]' data-id='"+lastRow+"' value='"+is_po+"'/>";        
          } else {

            newCell.innerHTML = "<input type='radio' name='is_po_real[]' id='is_po_real[]' onchange='setispo("+lastRow+");'/><input type='hidden' name='is_po[]' id='is_po[]' data-id='"+lastRow+"' value='"+is_po+"' />";        
          }
        }

    var newCell = row.insertCell(3);
        newCell.align ="center";
        newCell.style.padding=".45rem";

        if( tbsid > 0) {
          newCell.innerHTML = isikecil;
        } else {
          if ( jml_pakai > 0 ) {
              newCell.innerHTML = "<input type='text' class='form-control form-control-sm' style='text-align:center' name='konversi_detail[]' id='konversi_detail[]' value='"+isikecil+"' readonly='readonly'/>";

          } else {

              newCell.innerHTML = "<input type='text' class='form-control form-control-sm' style='text-align:center' name='konversi_detail[]' id='konversi_detail[]' value='"+isikecil+"'/>";
          }
        }

    var newCell = row.insertCell(4);
        newCell.align ="center";
        newCell.style.padding=".45rem";
        newCell.innerHTML = inner_text;

     var newCell = row.insertCell(5);
        newCell.align ="center";
        newCell.style.padding=".45rem";

        if ( jml_pakai > 0 ) {
          newCell.innerHTML = "";
        } else {
          newCell.innerHTML = "<button class='btn btn-danger btn-sm' onclick='removeRow("+lastRow+","+tbsid+"); return false;'><i class='fa fa-times' ></i></button>";
          
        }

}

function removeRow(id,id_data){

  if ( id_data > 0 ) {

    $.post("<?php echo base_url('Masterdata/hapus_satuan_barang');?>",{tbsid:id_data},function(data){

       $("#row-satuan-"+id).remove();
       cekjmldetail();

    });


  } else {    
    $("#row-satuan-"+id).remove();
    cekjmldetail();
  }

}

function cekjmldetail(){
  var tsid_detail = $("input[name='tsid_detail[]'");

  if ( tsid_detail.length == 0 ) {
     $("select[id='satuan']").css('pointer-events','auto');
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
                  Edit Barang
                </h3>
              </div>
              <form class='form-horizontal' name='frm' id='frm' method='post' action="<?php echo base_url('masterdata/act_add_barang'); ?>">
                <input type='hidden' name='tbrid' id='tbrid' value="<?php echo $list->tbrid;?>"/>
                  <div class='card-body'> 
              
                  <div class='form-group-row'>
                          <div class='col col-12'>
                              
                              <div class='col col-6' style='float:left'>
                                  <label class="col-form-label">Nama Barang</label>
                                  <input type="text" class="form-control form-control-sm" name='nama_barang' id='nama_barang' value="<?php echo $list->nama_barang; ?>" required="">
                              </div>
                              <div class='col col-3' style='float:left'>
                                  <label class="col-form-label">Satuan Kecil</label>
                                  <select name='satuan' id='satuan' class='form-control form-control-sm' required="">
                                <option></option>
                                <?php 
                                  foreach ($list_satuan->result_array() as $k) {

                                    if ( $k['tsid'] == $list->satuan) {

                                      echo "<option value='".$k['tsid']."' selected='selected'>".$k['satuan']."</option>";

                                    } else {

                                      echo "<option value='".$k['tsid']."'>".$k['satuan']."</option>";  
                                    }

                                    
                                  }
                                ?>
                              </select>
                              </div>

                              <div class='col col-3' style='float:left'>
                                  <label class="col-form-label">Jenis Barang</label>

                                      <select name='tjbid' id='tjbid' class='form-control form-control-sm form-control form-control-sm-sm' required="">
                                  <option></option>
                                    <?php 
                                      foreach ($list_jenis->result_array() as $k) {

                                        if ( $k['tjbid'] == $list->tjbid) {
                                          echo "<option value='".$k['tjbid']."' selected='selected'>".$k['kd_jenis']." ( ".$k['nm_jenis']." )</option>";
                                        } else {
                                          echo "<option value='".$k['tjbid']."'>".$k['kd_jenis']." ( ".$k['nm_jenis']." )</option>";
                                        }

                                      }
                                    ?>
                                  </select>


                                  <select name='tkgid' id='tkgid' class='form-control form-control-sm' required="" hidden>
                                  <option></option>
                                  <?php 
                                    foreach ($list_kategori->result_array() as $k) {


                                        if ( $k['tkgid'] == $list->tkgid) {

                                        echo "<option value='".$k['tkgid']."' selected='selected'>".$k['nama_kategori']."</option>";

                                      } else {

                                        echo "<option value='".$k['tkgid']."'>".$k['nama_kategori']."</option>";  
                                      }

                                      
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

                                        if ( $k['typbid'] == $list->typbid ) {

                                          echo "<option value='".$k['typbid']."' selected='selected'>".$k['nama_tipe']."</option>";

                                        } else{

                                          echo "<option value='".$k['typbid']."'>".$k['nama_tipe']."</option>";

                                        }

                                      }
                                    ?>
                                  </select>
                              </div>

                               <div class='col col-3' style='float:left'>
                                  <label class="col-form-label">Gram</label>
                                  <input type="text" class="form-control form-control-sm" name='gram' id='gram' required="" value="<?php echo $list->gram;?>">
                              </div>

                              <div class='col col-3' style='float:left'>
                                  <label class="col-form-label">Lebar</label>
                                  <input type="text" class="form-control form-control-sm" name='lebar' id='lebar' required="" value="<?php echo $list->lebar;?>">
                              </div>

                               <div class='col col-3' style='float:left'>
                                  <label class="col-form-label">Warna Barang</label>

                                  <select name='twid' id='twid' class='form-control form-control-sm' required="">
                                  <option></option>
                                    <?php 
                                      foreach ($list_warna->result_array() as $k) {
                                        if ( $k['twid'] == $list->twid ) {
                                          echo "<option value='".$k['twid']."' selected='selected'>".$k['nama_warna']."</option>";
                                        }else{
                                        echo "<option value='".$k['twid']."'>".$k['nama_warna']."</option>";

                                        }
                                      }
                                    ?>
                                  </select>
                              </div>

                              <div class='col col-3' style='float:left' hidden>
                                  <label class="col-form-label">Hna</label>
                                  <input type="number" class="form-control form-control-sm" name='hna' id='hna' required="" value="<?php echo $list->hna; ?>">
                              </div>

                              <div class='col col-3' style='float:left'>
                                  <label class="col-form-label">Harga Jual (Kg)</label>
                                  <input type="number" class="form-control form-control-sm" name='harga_jual' id='harga_jual' required="" value="<?php echo $list->harga_jual; ?>">
                              </div>

                              <div class='col col-3' style='float:left'>
                                  <label class="col-form-label">Is Aktif</label>

                                  <?php $checkedy = $checkedn = "";

                                        if ( $list->is_aktif == '1' ) {
                                           $checkedy = "selected='selected'";
                                        }elseif ( $list->is_aktif == '0' ) {
                                           $checkedn = "selected='selected'";
                                        }
                                   ?>
                                  <select name='is_aktif' id='is_aktif' class='form-control form-control-sm'>
                                    <option value='1' <?php echo $checkedy;?>>Ya</option>
                                    <option value='0' <?php echo $checkedn;?>>Tidak</option>
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
                                    <tbody>
                                      
                                    </tbody>
                                 </table>
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

<script type="text/javascript">


var row_awal = "";
<?php foreach($detail_satuan->result_array() as $k){ ?>
    
    addSatuan("<?php echo $k['tsid']?>","<?php echo $k['satuan']?>","<?php echo round($k['isikecil'],2);?>","<?php echo $k['jml_pakai'];?>","<?php echo $k['is_po'];?>","<?php echo $k['tbsid'];?>");
    
<?php }?>

</script>