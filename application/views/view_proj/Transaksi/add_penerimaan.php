<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/autocomplete.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/select2/css/select2.min.css">
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/select2/js/select2.min.js"></script>
<script type="text/javascript">

function cekNominalnya(from=''){

  var kategori_barang = $("#tkgid").val();
  var tipe_penerimaan = $("select[name='tipe_penerimaan']").val();
  var no_po           = $("select[name='no_po']").val();
  var tbrid_search    = $("select[name='tbrid_search']").val();
  var no_po_real      = "0_0_0";

  if ( from == 'search' ) {
    $("button[id='tombol_cari']").html("Tunggu Sebentar");
    $("button[id='tombol_cari']").attr("disabled",true);
  }

  if ( kategori_barang == '' ) {

     alert('Pilih kategori barang terlebih dahulu');

  } else {


      if ( tipe_penerimaan == 'po' && (no_po == null || no_po == '' || no_po == ' ')) {

            alert('Pilih No PO Terlebih Dahulu');

      } else {

        clearListMaster();

        if ( no_po != null && no_po != '' && no_po != ' ' ) {

           no_po_real = no_po;
        }


        $.post("<?php echo base_url('Transaksi/list_master_barang');?>",{tkgid:kategori_barang,with_satuan:'t',tipe_terima:tipe_penerimaan,nopo:no_po_real,tbrid_search:tbrid_search},function(data){

              var hasil = JSON.parse(data);
              var num = 0;
              var jml_data = hasil.length;
              $.each(hasil,function(k,v){
                  addrowmodal(v.tbrid,v.nama_barang_new,v.list_satuan,v.hna,v.satuan_besar,v.ttbdid_po);
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
    
}

function clearListMaster(){

    $("#list_master_barang tbody").empty();
    
}

function clearListOrder(){

    $("#table_barang tbody").empty();
  
}

function setfromcombo(tbrid,obj){
  var harga = $('option:selected', obj).attr('data-harga');
  var nama_satuan = $('option:selected', obj).text();
  var tsid = $('option:selected', obj).val();

  harga = parseFloat(harga).toFixed();

  $("#text_harga_"+tbrid).html(harga);
  $("input[id='hna["+tbrid+"]']").val(harga);
  $("input[id='satuan["+tbrid+"]']").val(tsid);
  $("input[id='nama_satuan["+tbrid+"]']").val(nama_satuan);

}
  
function addrowmodal(tbrid,nama_barang,nama_satuan,hna,tsid,ttbdid_po) {

    var tblList     = document.getElementById("list_master_barang");
    var tblBody     = tblList.tBodies[0];
    var lastRow     = tblBody.rows.length;
    var row         = tblBody.insertRow(lastRow);

    var data_satuan = nama_satuan.split(":");
    var combo_satuan = "<select name='pilihan_satuan[]' id='pilihan_satuan["+tbrid+"]' class='form-control form-control-sm' onchange='setfromcombo("+tbrid+",this);'><option></option>";
    $.each(data_satuan,function(k,v){
        var detail_satuan = v.replace('\"','');
        detail_satuan = detail_satuan.replace('\"','');
        var rowsatuan = detail_satuan.split("_");
        combo_satuan += "<option value='"+rowsatuan[0]+"' data-harga='"+rowsatuan[2]*hna+"'>"+rowsatuan[1]+"</option>";
    });
    combo_satuan +="</select>";

    var newCell = row.insertCell(0);
        newCell.align ="center";
        newCell.innerHTML = lastRow+1;

    var newCell = row.insertCell(1);
        newCell.align ="center";
        newCell.innerHTML = nama_barang+"<input type='hidden' name='nama_brg[]' id='nama_brg["+tbrid+"]' value='"+nama_barang+"'/><input type='hidden' name='ttbdid_po[]' id='ttbdid_po["+tbrid+"]' value='"+ttbdid_po+"'/>";

    var newCell = row.insertCell(2);
        newCell.align ="center";
        newCell.innerHTML = combo_satuan;
        newCell.innerHTML += "<input type='hidden' name='satuan[]' id='satuan["+tbrid+"]' /><input type='hidden' name='nama_satuan[]' id='nama_satuan["+tbrid+"]'/>";

    var newCell = row.insertCell(3);
        newCell.align ="right";
        newCell.innerHTML = "<font id='text_harga_"+tbrid+"'>"+hna+"</font><input type='hidden' name='hna[]' id='hna["+tbrid+"]' value='"+hna+"'/>";

    var newCell = row.insertCell(4);
        newCell.align ="center";
        newCell.innerHTML = "<input type='checkbox' class='pil_brg' value='"+tbrid+"' name='choose["+tbrid+"]' id='choose["+tbrid+"]' onclick='return checkList("+tbrid+");'/>";

        ganticombo(tbrid);
}

function ganticombo(tbrid){

  $("select[id='pilihan_satuan["+tbrid+"]']").select2();
}

function chooseBarang(){

    $.each($('.pil_brg'),function(k,v){
        if ( this.checked == true ) {
            var nama_bar = document.getElementById("nama_brg["+this.value+"]").value;
            var satuan = document.getElementById("satuan["+this.value+"]").value;
            var hna = document.getElementById("hna["+this.value+"]").value;
            var nama_satuan = document.getElementById("nama_satuan["+this.value+"]").value;
            var tsid = document.getElementById("satuan["+this.value+"]").value;
            var ttbdid_po = document.getElementById("ttbdid_po["+this.value+"]").value;
            addrow(this.value,nama_bar,nama_satuan,tsid,hna,ttbdid_po,0);
        }
    });


    $.each($('.pil_brg'),function(k,v){
        if ( this.checked == true ) {
            this.checked = false;
        }
    });


    $("#tutup_modal").click();
}
function addrow(tbrid,nama_barang,nama_satuan,tsid,harga,ttbdid,vol_sisa){
  var tblList     = document.getElementById("table_barang");
  var tblBody     = tblList.tBodies[0];
  var lastRow     = tblBody.rows.length;
  var row         = tblBody.insertRow(lastRow);

  var new_harga = formatRupiah("'"+harga+"'",'Rp. ');

  var newCell = row.insertCell(0);
      newCell.align ="center";
      newCell.innerHTML = lastRow+1;

  var newCell = row.insertCell(1);
      newCell.align ="center";
      newCell.innerHTML = "<input type='hidden' name='tbrid[]' id='tbrid[]' class='form-control form-control-sm' value='"+tbrid+"'/><input type='hidden' name='nama_barang[]' id='nama_barang[]' value='"+nama_barang+"'/><input type='hidden' name='reff_id[]' id='reff_id[]' value='"+ttbdid+"'/>"+nama_barang;


  var newCell = row.insertCell(2);
      newCell.align ="center";
      newCell.innerHTML = "<input type='hidden' name='jumlah_orig[]' id='jumlah_orig["+lastRow+"]' value='"+vol_sisa+"'/><input type='text' name='jumlah[]' id='jumlah["+lastRow+"]' value='"+vol_sisa+"' class='form-control form-control-sm' onblur='hitungSubtotal("+lastRow+");'/>";

   var newCell = row.insertCell(3);
      newCell.align ="center";
      newCell.innerHTML = "<input type='hidden' name='satuan[]' id='satuan[]' value='"+tsid+"'/>"+nama_satuan;

    var newCell = row.insertCell(4);
      newCell.align ="center";
      newCell.innerHTML = "<input type='text' name='harga_satuan[]' id='harga_satuan["+lastRow+"]' value='"+new_harga+"' class='form-control form-control-sm' readonly='readonly' style='text-align:right' onblur='hitungSubtotal("+lastRow+");'/>";

    var newCell = row.insertCell(5);
      newCell.align ="center";
      newCell.innerHTML = "<input type='text' name='subtot[]' id='subtot["+lastRow+"]' class='form-control form-control-sm' style='text-align:right' readonly/>";

      
      hitungSubtotal(lastRow);
} 

function hitungSubtotal(id) {

    var nominal = backToNormal(document.getElementById('harga_satuan['+id+']'));
    var tipe_penerimaan = document.getElementById('tipe_penerimaan').value;
    var qty     = document.getElementById('jumlah['+id+']').value;
    var qty_orig     = document.getElementById('jumlah_orig['+id+']').value;

    /*if  ( tipe_penerimaan == 'po' ) {
       if ( parseInt(qty_orig) < parseInt(qty) ) {
           alert('Qty Tidak Boleh Melebihi Qty PO atau Sisa Qty PO');
           document.getElementById('jumlah['+id+']').value = qty_orig;
           qty = qty_orig;
           hitungSubtotal(id);
       }
    }*/

    var total   = nominal*qty;
    document.getElementById('subtot['+id+']').value = formatRupiah("'"+total+"'",'Rp. ');

    hitungTotal();
}

function hitungTotal(){

    var total_semua = 0;
    var total_diskon = backToNormal(document.getElementById('total_diskon'));
    var ppn = (parseInt(document.getElementById('ppn').value) != '' ) ? document.getElementById('ppn').value : 0;
    var ppn_rp = 0;

    $.each($("input[name='subtot[]']"),function(k,v){
        if (backToNormal(this) != '' && backToNormal(this) > 0){
            var nominal_row = backToNormal(this);
            total_semua = parseFloat(total_semua) + parseFloat(nominal_row);
        }
    });

    

    if ( (total_semua - total_diskon) < 0 ) {

        alert('Diskon Tidak Boleh Lebih Besar Dari Total Harga Barang');
        $("#total_diskon").val('0');

    }else{

        total_semua -= total_diskon;
    }

    ppn_rp = (ppn/100)*total_semua;
    $("#ppn_rp").val(ppn_rp);

    total_semua += ppn_rp;

    $('#total').val(formatRupiah("'"+total_semua+"'",'Rp. '));
}

function checkList(tbrid) {
        var c = $("input[name='tbrid[]']").length;
        
        if ( c > 0 ) {

                $.each($("input[name='tbrid[]']"),function(k,v){
               
                 /*   if ( this.value == tbrid ) {
                        alert('Barang sudah ditambahkan di list');
                        document.getElementById('choose['+tbrid+']').checked = false;
                        return false;

                    } else {*/
                        document.getElementById('choose['+tbrid+']').checked = true;
                        return true;
                    //}


                
            });
        }
}

function backNormal(){
    var harga_satuan = $("input[name='harga_satuan[]']");
    var subtot = $("input[name='subtot[]']");
    var total  = backToNormal(document.getElementById('total'));

    checkNom(document.getElementById('total_diskon'));
    checkNom(document.getElementById('total'));

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

function setData(obj){

  if ( obj.value == '' ) {
   //$("select[name='tkgid']").val('');
   $("select[name='tspid']").val('');  
   $("select[name='tkgid']").css('pointer-events','auto');
   $("select[name='tspid']").css('pointer-events','auto');
  }
  
   $("#no_po_real").val('');  
   $("#table_barang tbody").empty();

   var hasil = obj.value;

   if ( hasil != '' ) {

     hasil = hasil.split('_');

     var ttbid = hasil[0];
     var tkgid = hasil[1];
     var tspid = hasil[2];

     $("#no_po_real").val($( "select[name='no_po'] option:selected" ).text());
        $("select[name='tkgid']").val(tkgid);
        $("select[name='tkgid']").css('pointer-events','none');
        $("select[name='tspid']").val(tspid);
        $("select[name='tspid']").css('pointer-events','none');

    /* $.post("<?php echo base_url('Transaksi/get_barang')?>",{po_id:ttbid},function(data){

        var list_barang = JSON.parse(data);

        if ( list_barang.length > 0 ) {

            $.each(list_barang,function(k,v){

                addrow(v.tbrid,v.nama_barang,v.satuan,v.tsid,v.harga_satuan,v.ttbdid,v.vol_sisa);
            });


        }
        
        

     });*/

   }
}
 
function checkPO(obj){

  $("select[name='no_po']").empty();
  $("#cek_barang").css('display','');
  $("select[name='no_po']").trigger('change');
  $("#total").val('0');

  if ( obj.value == 'po' ) {

      //$("#cek_barang").css('display','none');

      $.post("<?php echo base_url('Transaksi/get_po')?>",{id:1},function(data){

        var hasil = JSON.parse(data);

        if ( hasil.length > 0 ) {
            
            $("select[name='no_po']").append( new Option('-- Pilih PO -- ','') );

            $.each(hasil,function(k,v){

              $("select[name='no_po']").append( new Option(v.reff_code,v.ttbid+'_'+v.tkgid+'_'+v.tspid) );

            });
        }
        

      });
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
            <h1 class="m-0 text-dark"><i class='fa fa-download'></i> Penerimaan Barang</h1>
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
                  Tambah Penerimaan
                </h3>
              </div>
              <form class='form-horizontal' name='frm' id='frm' method='post' action="<?php echo base_url('transaksi/act_add_penerimaan'); ?>">
                  <div class='card-body'>


                    <div class='form-group-row'>

                      <div class='col-3' style="float:left">
                          <label class="col col-form-label">Tanggal Penerimaan</label>
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

                       <div class='col-2' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">No Faktur</label>
                              
                                  <input type="text" class="form-control form-control-sm" name='no_faktur' id='no_faktur' required="">
                              

                      </div>

                      <div class='col-2' style="float:left">
                            <label for="inputEmail3" class="col col-form-label">Tipe PO</label>
                                
                            <select name='tipe_penerimaan' id='tipe_penerimaan' class='form-control form-control-sm' onchange="checkPO(this);">
                              <option></option>
                              <option value='po'>Dengan PO</option>
                              <option value='tanpa_po'>Tanpa PO</option>
                             
                            </select>
                              
                      </div>

                      <div class='col-5' style="float:left">
                            <label for="inputEmail3" class="col col-form-label">No. PO</label>
                            <input type='hidden' name='no_po_real' id='no_po_real'/>    
                            <select name='no_po' id='no_po' class='form-control form-control-sm' onchange="setData(this)">                             
                            </select>
                              
                      </div>


                       <div class='col-6' style="float:left" hidden>

                           <label for="inputEmail3" class="col col-form-label">Kategori Barang</label>
                                
                            <select name='tkgid' id='tkgid' class='form-control form-control-sm' onchange="clearListOrder();">
                              <option></option>
                              <?php 
                                foreach ($list_kategori->result_array() as $key) {
                                  echo "<option value='".$key['tkgid']."' selected='selected'>".$key['nama_kategori']."</option>";
                                }
                              ?>
                            </select>
                              

                      </div>

                      <div class='col-12' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">Supplier</label>
                                
                            <select name='tspid' id='tspid' class='form-control form-control-sm'>
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
                                          <th width='10%'>Jumlah</th>
                                          <th width='18%'>Satuan</th>
                                          <th width='18%'>Harga Satuan</th>
                                          <th width='18%'>Sub Total</th>
                                        </tr>
                                      </thead>
                                      <tbody></tbody>
                                      <tfoot>
                                        <tr>
                                          <td colspan='5' style="text-align: right">Diskon Total</td>
                                          <td><input type='text' name='total_diskon' id='total_diskon' class='form-control form-control-sm' value='0' style='text-align:right' onblur="hitungTotal()" /></td>
                                        </tr>
                                        <tr>
                                          <td colspan='5' style="text-align: right">PPN %</td>
                                          <td>
                                            <input type='text' name='ppn' id='ppn' class='form-control form-control-sm' value='0' style='text-align:right' onblur="hitungTotal()"/>
                                            <input type='hidden' name='ppn_rp' id='ppn_rp' class='form-control form-control-sm' value='0' style='text-align:right'/>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td colspan='5' style="text-align: right">Total</td>
                                          <td><input type='text' name='total' id='total' class='form-control form-control-sm' value='0' style='text-align:right' readonly /></td>
                                        </tr>
                                      </tfoot>
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
                     <th style='text-align:center' width="20%">Satuan</th>
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

  setNominal("total_diskon");
  $("select[id='tbrid_search']").select2();

});


</script>