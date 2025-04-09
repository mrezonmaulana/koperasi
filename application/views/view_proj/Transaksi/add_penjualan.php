<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/autocomplete.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/select2/css/select2.min.css">
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/select2/js/select2.min.js"></script>
<script type="text/javascript">
function cekNominalnya(from=""){

  var kategori_barang = $("#tkgid").val();
  var tbrid_search    = $("select[name='tbrid_search']").val();

    if ( from == 'search' ) {
    $("button[id='tombol_cari']").html("Tunggu Sebentar");
    $("button[id='tombol_cari']").attr("disabled",true);
  }

  if ( kategori_barang == '' ) {

     alert('Pilih kategori barang terlebih dahulu');

  } else {

      clearListMaster();

      $.post("<?php echo base_url('Transaksi/list_master_barang');?>",{tkgid:kategori_barang,tbrid_search:tbrid_search},function(data){

            var hasil = JSON.parse(data);
            var num = 0;
            var jml_data = hasil.length;
            $.each(hasil,function(k,v){
                addrowmodal(v.tbrid,v.nama_barang_new,v.nama_satuan_po,v.new_hna_po,v.satuan_po);
                num = num + 1;
            });

              if ( from == 'search' ) {
                  $("button[id='tombol_cari']").html("<i class='fa fa-search'></i> Cari");
                  $("button[id='tombol_cari']").attr("disabled",false);
                }

            if ( num == jml_data && from != 'search') {
                $("#popup_barang").click();
            }
      });

  }
    
}


function chooseBarang(){

    $.each($('.pil_brg'),function(k,v){
        if ( this.checked == true ) {
            var nama_bar = document.getElementById("nama_brg["+this.value+"]").value;
            var satuan = document.getElementById("satuan["+this.value+"]").value;
            var hna = document.getElementById("hna["+this.value+"]").value;
            var nama_satuan = document.getElementById("nama_satuan["+this.value+"]").value;
            addrow(this.value,nama_bar,satuan,hna,nama_satuan);
        }
    });


    $.each($('.pil_brg'),function(k,v){
        if ( this.checked == true ) {
            this.checked = false;
        }
    });


    $("#tutup_modal").click();
}

function clearListMaster(){

    $("#list_master_barang tbody").empty();
    
}

function clearListOrder(){

    $("#table_barang tbody").empty();
    
}

function addrowmodal(tbrid,nama_barang,nama_satuan,hna,tsid) {

    var tblList     = document.getElementById("list_master_barang");
    var tblBody     = tblList.tBodies[0];
    var lastRow     = tblBody.rows.length;
    var row         = tblBody.insertRow(lastRow);

    var newCell = row.insertCell(0);
        newCell.align ="center";
        newCell.innerHTML = lastRow+1;

    var newCell = row.insertCell(1);
        newCell.align ="center";
        newCell.innerHTML = nama_barang+"<input type='hidden' name='nama_brg[]' id='nama_brg["+tbrid+"]' value='"+nama_barang+"'/>";

    var newCell = row.insertCell(2);
        newCell.align ="center";
        newCell.innerHTML = nama_satuan+"<input type='hidden' name='satuan[]' id='satuan["+tbrid+"]' value='"+tsid+"'/><input type='hidden' name='nama_satuan[]' id='nama_satuan["+tbrid+"]' value='"+nama_satuan+"'/>";

    var newCell = row.insertCell(3);
        newCell.align ="right";
        newCell.innerHTML = hna+"<input type='hidden' name='hna[]' id='hna["+tbrid+"]' value='"+hna+"'/>";

    var newCell = row.insertCell(4);
        newCell.align ="center";
        newCell.innerHTML = "<input type='checkbox' class='pil_brg' value='"+tbrid+"' name='choose["+tbrid+"]' id='choose["+tbrid+"]' onclick='return checkList("+tbrid+");'/>";
}

function addrow(tbrid,nama_barang,satuan,hna,nama_satuan){
  var tblList     = document.getElementById("table_barang");
  var tblBody     = tblList.tBodies[0];
  var lastRow     = tblBody.rows.length;
  var row         = tblBody.insertRow(lastRow);

  var new_hna = formatRupiah("'"+hna+"'",'Rp. ');


  var combo_handfeel = "<select name='handfeel[]' id='handfeel[]' class='form-control form-control-sm'><option></option>";
        combo_handfeel += "<option value='SUPER SOFT'> SUPER SOFT</option>";
        combo_handfeel += "<option value='SOFT'> SOFT</option>";
        combo_handfeel += "<option value='SOFT ISI'> SOFT ISI</option>";
  combo_handfeel += "</select>";

  var newCell = row.insertCell(0);
      newCell.align ="center";
      newCell.innerHTML = lastRow+1;

  var newCell = row.insertCell(1);
      newCell.align ="center";
      newCell.innerHTML = "<input type='hidden' name='tbrid[]' id='tbrid[]' class='form-control form-control-sm' value='"+tbrid+"'/>"+nama_barang;

  var newCell = row.insertCell(2);
      newCell.align ="center";
      newCell.innerHTML = combo_handfeel;  

  var newCell = row.insertCell(3);
      newCell.align ="center";
      newCell.innerHTML = "<input type='text' name='no_match[]' id='no_match["+lastRow+"]' class='form-control form-control-sm' style='text-align:center'/>";


  var newCell = row.insertCell(4);
      newCell.align ="center";
      newCell.innerHTML = "<input type='text' name='jumlah[]' id='jumlah["+lastRow+"]' class='form-control form-control-sm' style='text-align:center' onblur='hitungSubtotal("+lastRow+");'/>";

   var newCell = row.insertCell(5);
      newCell.align ="center";
      newCell.innerHTML = "<input type='hidden' name='satuan[]' id='satuan[]' value='"+satuan+"'/><input type='hidden' name='harga_satuan[]' id='harga_satuan["+lastRow+"]' value='"+new_hna+"' class='form-control form-control-sm' readonly='readonly' style='text-align:right'/><input type='hidden' name='subtot[]' id='subtot["+lastRow+"]' class='form-control form-control-sm' style='text-align:right' readonly/>"+nama_satuan;

      //setNominal("harga_satuan["+lastRow+"]");
      hitungSubtotal(lastRow);
} 

function hitungSubtotal(id) {

    var nominal = backToNormal(document.getElementById('harga_satuan['+id+']'));
    var qty     = document.getElementById('jumlah['+id+']').value;
    var total   = nominal*qty;
    document.getElementById('subtot['+id+']').value = formatRupiah("'"+total+"'",'Rp. ');

    hitungTotal();
}

function hitungTotal(){

    var total_semua = 0;

    $.each($("input[name='subtot[]']"),function(k,v){
        if (backToNormal(this) != '' && backToNormal(this) > 0){
            var nominal_row = backToNormal(this);
            total_semua = parseFloat(total_semua) + parseFloat(nominal_row);
        }
    });


    $('#total').val(formatRupiah("'"+total_semua+"'",'Rp. '));
}

function checkList(tbrid) {
        var c = $("input[name='tbrid[]']").length;
        
        if ( c > 0 ) {

                $.each($("input[name='tbrid[]']"),function(k,v){
               
                    if ( this.value == tbrid ) {
                        alert('Barang sudah ditambahkan di list');
                        document.getElementById('choose['+tbrid+']').checked = false;
                        return false;

                    } else {
                        document.getElementById('choose['+tbrid+']').checked = true;
                        return true;
                    }


                
            });
        }
}

function backNormal(){
    var harga_satuan = $("input[name='harga_satuan[]']");
    var subtot = $("input[name='subtot[]']");
    var total  = backToNormal(document.getElementById('total'));

    $.each(harga_satuan,function(k,v){
       checkNom(this);
    });

    $.each(subtot,function(k,v){
       checkNom(this);
    });

     if ( parseFloat(total) <= 0 ) { 
      alert('Total Harga Tidak Boleh 0');
      return false;
    }
    else{
      return true;
    }
}
</script>
<style>
    .select2-container{
        width: 100% !important;
    }
    form fieldset div span{
        padding: 0px 0 !important;
        margin:0px 0 !important;
    }

    .select2-container--default .select2-selection--single{
        padding-left: 5px !important;
        line-height: 0px !important;
        padding : .33979rem .15rem !important;
        font-size:10pt !important;
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
            <h1 class="m-0 text-dark"><i class='fa fa-upload'></i> PO Celup</h1>
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
                  Tambah PO
                </h3>
              </div>
              <form class='form-horizontal' name='frm' id='frm' method='post' action="<?php echo base_url('transaksi/act_add_penjualan'); ?>">
                  <div class='card-body'>


                    <div class='form-group-row'>

                      <div class='col-3' style="float:left">
                          <label class="col col-form-label">Tanggal PO</label>
                          <div class="col">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text">
                                  <i class="far fa-calendar-alt"></i>
                                </span>
                              </div>
                             <input type="text" class="form-control form-control-sm float-right" name="tgl_trans" id="reservation">
                            </div>
                          </div>
                      </div>

                     <div class='col-3' style="float:left" >

                           <label for="inputEmail3" class="col col-form-label">No. SJ</label>
                            <input type="text" name="no_sj" id="no_sj" class="form-control form-control-sm"/>

                            <select name='tkgid' id='tkgid' class='form-control form-control-sm' onchange="clearListOrder();" required="" hidden="">
                              <option></option>
                              <?php 
                                foreach ($list_kategori->result_array() as $key) {
                                  echo "<option value='".$key['tkgid']."' selected='selected'>".$key['nama_kategori']."</option>";
                                }
                              ?>
                            </select>
                              

                      </div>


                      <div class='col-6' style="float:left">

                           <label for="inputEmail3" class="col col-form-label">Supplier</label>
                                
                            <select name='tspid' id='tspid' class='form-control form-control-sm' required="">
                              <option></option>
                              <?php 
                                foreach ($list_supplier->result_array() as $key) {
                                  echo "<option value='".$key['tspid']."'>".$key['nama_supplier']."</option>";
                                }
                              ?>
                            </select>
                              

                      </div>
                      
                      <div class='col' style="float:left">
                            <label for="inputEmail3" class="col col-form-label">Keterangan</label>
                                <input type="text" class="form-control form-control-sm" name='keterangan' id='keterangan'>
                      </div>

                  </div>


                  <div class='form-group-row'>
                        <div class='col-12' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">Data Barang</label>
                                <div class="col">
                                    <table class='table table-bordered' id='table_barang'>
                                      <thead>
                                        <tr>
                                          <th width='1%'>No</th>
                                          <th>Nama Barang</th>  
                                          <th width="15%">Handfeel</th>
                                          <th width="15%">No Match</th>
                                          <th width='10%'>Jumlah</th>
                                          <th width='18%'>Satuan</th>
                                          <!-- <th width='18%'>Harga Satuan</th>
                                          <th width='18%'>Sub Total</th> -->
                                        </tr>
                                      </thead>
                                      <tbody></tbody>
                                      <!-- <tfoot>
                                        <tr>
                                          <td colspan='6' style="text-align: right">Total</td>
                                          <td></td>
                                        </tr>
                                      </tfoot> -->
                                      <input type='hidden' name='total' id='total' class='form-control form-control-sm' value='0' style='text-align:right' readonly />
                                    </table>
                                </div>

                        </div>
                  </div>
                  </div>
                 <div class='card-footer'>
                 <input type='submit' class='btn btn-info' value='Simpan' onclick="return backNormal()"/>
                 <span style='float:right'>
                                  <!-- <button class='btn btn-warning' onclick="addrow();"><i class='fa fa-plus'></i> Tambah Barang</button> -->
                                  <input type='button' value="Tambah Barang" id='cek_barang' onclick="cekNominalnya()" class='btn btn-danger'/>
                                  <input type='button' value="Tambah Barang" id='popup_barang'  data-toggle="modal" data-target="#modal-default"  hidden/>
                                </span>
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
        </section>
      </div>


      <div class="modal fade" id="modal-default">
        <div class="modal-dialog" style="max-width: 700px !important">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">List Barang</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

                <table width="100%" class='table table-sm table-bordered' >
                 <tr>
                   <td width="85%">
                    <select name="tbrid_search" id="tbrid_search" class='form-control form-control-sm' placholder="Cari Barang Disini ...">
                      <option value='0'></option>
                      <?php
                          foreach ($list_barang->result_array() as $key) {
                             echo "<option value='".$key['tbrid']."'>".$key['nama_barang']." - ".$key['nama_warna']."</option>";
                          }
                       ?>
                    </select>
                   </td>
                   <td>
                     <button class='btn btn-default btn-sm' id="tombol_cari" style="width:100%" onclick="cekNominalnya('search');">  
                       <i class='fa fa-search'></i> Cari
                     </button>
                   </td>
                 </tr>
               </table>

               <table class='table table-sm table-bordered' id="list_master_barang">
                 <thead>
                   <tr>
                     <th style='text-align:center'>No</th>
                     <th style='text-align:center'>Nama Barang</th>
                     <th style='text-align:center'>Satuan</th>
                     <th style='text-align:center'>Harga</th>
                     <th style='text-align:center'>Pilih</th>
                   </tr>
                 </thead>
                 <tbody>
  
                 </tbody>
               </table>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal" id="tutup_modal">Tutup</button>
              <button type="button" class="btn btn-primary" onclick="chooseBarang()">Pilih</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>

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

  $("select[id='tbrid_search']").select2();

});


</script>