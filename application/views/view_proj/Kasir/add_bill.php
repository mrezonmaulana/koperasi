
<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/autocomplete.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/select2/css/select2.min.css">
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/select2/js/select2.min.js"></script>
<style type="text/css">
#table_barang tr th {
  font-size:10pt;
  padding:.30rem !important;
}
#table_barang tr td {
  font-size:10pt;
  padding:.30rem !important;
}
#table_barang tr td .form-control-sm{
  font-size:.7rem !important;
}

#table_barang tr td span{
  font-size:.7rem !important;
}
</style>
<script type="text/javascript">
function cekNominalnya(){

  var kategori_barang = $("#tkgid").val();

  if ( kategori_barang == '' ) {

     alert('Pilih kategori barang terlebih dahulu');

  } else {

      clearListMaster();

      $.post("<?php echo base_url('Transaksi/list_master_barang');?>",{tkgid:kategori_barang},function(data){
            var hasil = JSON.parse(data);
            var num = 0;
            var jml_data = hasil.length;
            $.each(hasil,function(k,v){
                addrowmodal(v.tbrid,v.nama_barang,v.nama_satuan,v.hna,v.satuan);
                num = num + 1;
            });

            if ( num == jml_data ) {
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

function setQty(id,type,obj) {

    var jml = document.getElementById("jumlah["+id+"]").value;

    if ( type == 1 ) {
      var qty_new = parseInt(jml) + 1;
      document.getElementById('jumlah['+id+']').value = qty_new;
      if ( qty_new > 1 ) {
        $("button[id='btn_qty_down["+id+"]']").removeAttr('hidden','');
      }
      hitungSubtotal(id);
    } else if (type == 2 ){
      var qty_new = parseInt(jml) - 1;
      document.getElementById('jumlah['+id+']').value = qty_new;
      if ( qty_new == 1) {
        $("button[id='btn_qty_down["+id+"]']").attr('hidden','hidden');
      }
      hitungSubtotal(id);
    } else {
      $("table#table_barang tbody tr.makanan_"+id).remove();
      hitungTotal();
    }

}

function setnewsatuan(obj,id) {

  var harga = $('option:selected', obj).attr('data-harga');
  var stok = $('option:selected', obj).attr('data-stok');
  var isbesar = $('option:selected', obj).attr('data-isbesar');

  $("input[id='diskon_persen["+id+"]']").val(0);
  $("input[id='diskon["+id+"]']").val(0);

  var new_harga = formatRupiah("'"+harga+"'",'Rp. ');
  /*$("#harga_satuan_text_"+id).html(new_harga);*/
  $("input[id='harga_satuan["+id+"]']").val(harga);
//  
  $("select[id='satuan_stok["+id+"]']").val(obj.value).select2();

  if ( isbesar == 't' ) {
    $("select[id='satuan_stok["+id+"]']").trigger('change').select2();
    $("select[id='satuan_stok["+id+"]']").css('pointer-events','none').select2('destroy');
  }else{
    $("span[id='status_stok["+id+"]']").html(stok);
    $("select[id='satuan_stok["+id+"]']").select2();
  }
  
  hitungSubtotal(id);
}

function setnewsatuanmanual(obj,id) {

  var harga = $("input[id='harga_satuan_def["+id+"]']").val();
  var stok = $('option:selected', obj).attr('data-stok');
  var isbesar = $('option:selected', obj).attr('data-isbesar');
  var isikecil = $('option:selected', obj).attr('data-isikecil');

  $("input[id='diskon_persen["+id+"]']").val(0);
  $("input[id='diskon["+id+"]']").val(0);

  harga = harga * isikecil;
  harga = parseFloat(harga).toFixed();

  var new_harga = formatRupiah("'"+harga+"'",'Rp. ');
  /*$("#harga_satuan_text_"+id).html(new_harga);*/
  $("input[id='harga_satuan["+id+"]']").val(harga);
//  
  $("select[id='satuan_stok["+id+"]']").val(obj.value).select2();

  if ( isbesar == 't' ) {
    $("select[id='satuan_stok["+id+"]']").trigger('change').select2();
    $("select[id='satuan_stok["+id+"]']").css('pointer-events','none').select2('destroy');
  }else{
    $("span[id='status_stok["+id+"]']").html(stok);
    $("select[id='satuan_stok["+id+"]']").select2();
  }
  
  hitungSubtotal(id);
}

function setnewsatuan2(obj,id) {

  var harga = $('option:selected', obj).attr('data-harga');
  var stok = $('option:selected', obj).attr('data-stok');
  var isbesar = $('option:selected', obj).attr('data-isbesar');
  var isikecil = $('option:selected', obj).attr('data-isikecil');
  var data_satuan_jual = $("select[id='satuan["+id+"]']").val();
  $("input[id='diskon_persen["+id+"]']").val(0);
  $("input[id='diskon["+id+"]']").val(0);




  //var new_harga = formatRupiah("'"+harga+"'",'Rp. ');
  //$("#harga_satuan_text_"+id).html(new_harga);
  //$("input[id='harga_satuan["+id+"]']").val(harga);

  if ( data_satuan_jual != obj.value ) {
      stok = parseFloat(stok).toFixed(2);
  } else {
      stok = parseFloat(stok/isikecil).toFixed(2);
  }

  $("span[id='status_stok["+id+"]']").html(stok);


  //$("select[id='satuan_stok["+id+"]']").val(obj.value);

  /*if ( isbesar == 't' ) {
    $("select[id='satuan_stok["+id+"]']").css('pointer-events','none');
  }else{
    $("select[id='satuan_stok["+id+"]']").css('pointer-events','auto');
  }*/


  //hitungSubtotal(id);
}

function setDiskon(tipe,obj,id){

  var nominal = backToNormal(document.getElementById('harga_satuan['+id+']'));
  var isikecil = $('option:selected', $("select[id='satuan["+id+"]']")).attr('data-isikecil');
  var harga = $('option:selected', $("select[id='satuan["+id+"]']")).attr('data-harga');
  
  var total   = harga / isikecil;
  var hasil_diskon = hasil_diskon_persen = 0;
  if ( tipe == 1 ) { // persen

    hasil_diskon =  ( obj.value / 100 ) * total;

    $("input[id='diskon["+id+"]']").val(hasil_diskon);

  } else if ( tipe == 2 ) { // nominal

    hasil_diskon_persen = ( obj.value / total ) * 100;
  
    $("input[id='diskon_persen["+id+"]']").val(hasil_diskon_persen.toFixed(2));    

  } 

  hitungSubtotal(id);

}

function setDiskonManual(tipe,obj,id){

  var nominal = backToNormal(document.getElementById('harga_satuan['+id+']'));
  var isikecil = $('option:selected', $("select[id='satuan["+id+"]']")).attr('data-isikecil');
  var harga = $("input[id='harga_satuan_def["+id+"]']").val();
  
  var total   = harga;
  var hasil_diskon = hasil_diskon_persen = 0;
  if ( tipe == 1 ) { // persen

    hasil_diskon =  ( obj.value / 100 ) * total;

    $("input[id='diskon["+id+"]']").val(hasil_diskon);

  } else if ( tipe == 2 ) { // nominal

    hasil_diskon_persen = ( obj.value / total ) * 100;
  
    $("input[id='diskon_persen["+id+"]']").val(hasil_diskon_persen.toFixed(2));    

  } 

  hitungSubtotal(id);

}

function addrow(tbrid,nama_barang,harga_jual,stok,nama_satuan,satuan,list_satuan,konversi_satuan){
  var tblList     = document.getElementById("table_barang");
  var tblBody     = tblList.tBodies[0];
  var lastRow     = tblBody.rows.length;
  var row         = tblBody.insertRow(lastRow);

  var data_satuan = list_satuan.split(':');

  var new_stok = stok;

  var combo_satuan = "<select name='satuan[]' id='satuan["+lastRow+"]' class='form-control form-control-sm' onchange='setnewsatuan(this,"+lastRow+")'><option value='"+satuan+"' data-harga='"+harga_jual+"' data-stok='"+stok+"' data-isbesar='f' data-isikecil='1'>"+nama_satuan+"</option>";
  var combo_satuan_stok = "<select name='satuan_stok[]' id='satuan_stok["+lastRow+"]' class='form-control form-control-sm'  onchange='setnewsatuan2(this,"+lastRow+")'><option></option>";
  $.each(data_satuan,function(k,v){
      var list_satuann = v.replace('\"','');
      list_satuann = list_satuann.replace('\"','');
      var detail_satuan = list_satuann.split('_');
      var selected_satuan = "";
      if ( detail_satuan[0] == satuan ) {
        selected_satuan = "selected='selected'";
        new_stok = stok / konversi_satuan;
      }

      combo_satuan += "<option value='"+detail_satuan[0]+"' "+selected_satuan+" data-harga='"+detail_satuan[1]+"' data-stok='"+detail_satuan[3]+"' data-isbesar='t' data-isikecil='"+detail_satuan[4]+"' >"+detail_satuan[2]+"</option>";
      combo_satuan_stok += "<option value='"+detail_satuan[0]+"' "+selected_satuan+" data-harga='"+detail_satuan[1]+"' data-stok='"+detail_satuan[3]+"' data-isikecil='"+detail_satuan[4]+"'>"+detail_satuan[2]+"</option>";
  });

  combo_satuan += "</select>";
  combo_satuan_stok += "</select>";


  var new_harga_jual = formatRupiah("'"+harga_jual+"'",'Rp. ');
  row.setAttribute('class','makanan_'+lastRow);

  var newCell = row.insertCell(0);
      newCell.align ="center";
      newCell.innerHTML = "<input type='hidden' name='tbrid[]' id='tbrid[]' class='form-control form-control-sm' value='"+tbrid+"'/><input type='hidden' name='nama_barang["+lastRow+"]' id='nama_barang["+lastRow+"]' class='form-control form-control-sm' value='"+nama_barang+"'/>"+nama_barang ;


    var div_stok = "<div style='width:100%;border:1px solid #ccc;margin-top:2px;border-radius:5px'><span style='float:left;padding:5px;text-align:center;background:#eee;font-size:10pt;'> Stok</span><span style='float:left;padding:5px;text-align:center;font-size:10pt;' id='status_stok["+lastRow+"]'>"+new_stok+"</span><br style='clear:both'/></div>";

    var newCell = row.insertCell(1);
      newCell.align ="center";
      //newCell.innerHTML = stok+" [ "+nama_satuan+" ] <input type='hidden' name='satuan[]' id='satuan[]' class='form-control form-control-sm' value='"+satuan+"'/>" ;
      newCell.innerHTML = combo_satuan;

    var newCell = row.insertCell(2);
      newCell.align ="center";
      //newCell.innerHTML = stok+" [ "+nama_satuan+" ] <input type='hidden' name='satuan[]' id='satuan[]' class='form-control form-control-sm' value='"+satuan+"'/>" ;
      newCell.innerHTML = combo_satuan_stok+div_stok;

  var newCell = row.insertCell(3);
      newCell.align ="center";
      newCell.innerHTML = "<input type='text' name='jumlah[]' id='jumlah["+lastRow+"]' class='form-control form-control-sm form-control form-control-sm-sm' value='1' style='text-align:center' onblur='hitungSubtotal("+lastRow+");'  onkeypress='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);' maxlength='6'/>";

    var newCell = row.insertCell(4);
      newCell.align ="center";
      newCell.innerHTML = "<font id='harga_satuan_text_"+lastRow+"'>"+new_harga_jual+"</font><input type='hidden' name='harga_satuan_def[]' id='harga_satuan_def["+lastRow+"]' value='0'  class='form-control form-control-sm'  style='text-align:right'/><input type='hidden' name='harga_satuan[]' id='harga_satuan["+lastRow+"]' value='"+harga_jual+"' class='form-control form-control-sm' readonly='readonly' style='text-align:right'/>";

    var newCell = row.insertCell(5);
      newCell.align ="center";
      newCell.innerHTML = "<font id='subtot_text_bf_"+lastRow+"'></font>";

    var newCell = row.insertCell(6);
      newCell.align ="center";
      newCell.innerHTML = "<input type='text' readonly='readonly' value='%' class='form-control form-control-sm' style='width:40%;float:left;text-align:center' onkeypress='return onlyNumberKey(event)' /><input type='text' class='form-control form-control-sm' style='width:60%;float:left;text-align:center' onkeypress='return onlyNumberKey(event)' value='Rp.' readonly='readonly'/> <input type='text' name='diskon_persen[]' id='diskon_persen["+lastRow+"]' class='form-control form-control-sm' style='width:40%;float:left;' onblur='setDiskon(1,this,"+lastRow+");' /><input type='text' name='diskon[]' id='diskon["+lastRow+"]' class='form-control form-control-sm' style='width:60%;float:left;text-align:right' onkeypress='return onlyNumberKey(event)' onblur='setDiskon(2,this,"+lastRow+");'/> ";


    var newCell = row.insertCell(7);
      newCell.align ="center";
      newCell.innerHTML = "<font id='subtot_text_"+lastRow+"'></font><input type='hidden' name='subtot[]' id='subtot["+lastRow+"]' class='form-control form-control-sm' style='text-align:right' readonly/>";

    
    var newCell = row.insertCell(8);
        newCell.align = "center";
        newCell.innerHTML = "<button class='btn btn-danger btn-sm'style='font-size:.675rem !important' onclick='setQty("+lastRow+",3,this);'><i class='fa fa-times'></i></button>";

      hitungSubtotal(lastRow);

      ganticombo(lastRow);
} 

function addrowmanual(tbrid,nama_barang,harga_jual,stok,nama_satuan,satuan,list_satuan,konversi_satuan){
  var tblList     = document.getElementById("table_barang");
  var tblBody     = tblList.tBodies[0];
  var lastRow     = tblBody.rows.length;
  var row         = tblBody.insertRow(lastRow);

  var data_satuan = list_satuan.split(':');

  var new_stok = stok;

  var combo_satuan = "<select name='satuan[]' id='satuan["+lastRow+"]' class='form-control form-control-sm' onchange='setnewsatuanmanual(this,"+lastRow+")'><option value='"+satuan+"' data-harga='"+harga_jual+"' data-stok='"+stok+"' data-isbesar='f' data-isikecil='1'>"+nama_satuan+"</option>";
  var combo_satuan_stok = "<select name='satuan_stok[]' id='satuan_stok["+lastRow+"]' class='form-control form-control-sm'  onchange='setnewsatuan2(this,"+lastRow+")'><option></option>";
  $.each(data_satuan,function(k,v){
      var list_satuann = v.replace('\"','');
      list_satuann = list_satuann.replace('\"','');
      var detail_satuan = list_satuann.split('_');
      var selected_satuan = "";
      if ( detail_satuan[0] == satuan ) {
        selected_satuan = "selected='selected'";
        new_stok = stok / konversi_satuan;
      }

      combo_satuan += "<option value='"+detail_satuan[0]+"' "+selected_satuan+" data-harga='"+detail_satuan[1]+"' data-stok='"+detail_satuan[3]+"' data-isbesar='t' data-isikecil='"+detail_satuan[4]+"' >"+detail_satuan[2]+"</option>";
      combo_satuan_stok += "<option value='"+detail_satuan[0]+"' "+selected_satuan+" data-harga='"+detail_satuan[1]+"' data-stok='"+detail_satuan[3]+"' data-isikecil='"+detail_satuan[4]+"'>"+detail_satuan[2]+"</option>";
  });

  combo_satuan += "</select>";
  combo_satuan_stok += "</select>";


  var new_harga_jual = formatRupiah("'"+harga_jual+"'",'Rp. ');
  row.setAttribute('class','makanan_'+lastRow);

  var newCell = row.insertCell(0);
      newCell.align ="center";
      newCell.innerHTML = "<input type='hidden' name='tbrid[]' id='tbrid[]' class='form-control form-control-sm' value='"+tbrid+"'/><input type='hidden' name='nama_barang["+lastRow+"]' id='nama_barang["+lastRow+"]' class='form-control form-control-sm' value='"+nama_barang+"'/>"+nama_barang ;


    var div_stok = "<div style='width:100%;border:1px solid #ccc;margin-top:2px;border-radius:5px'><span style='float:left;padding:5px;text-align:center;background:#eee;font-size:10pt;'> Stok</span><span style='float:left;padding:5px;text-align:center;font-size:10pt;' id='status_stok["+lastRow+"]'>"+new_stok+"</span><br style='clear:both'/></div>";

    var newCell = row.insertCell(1);
      newCell.align ="center";
      //newCell.innerHTML = stok+" [ "+nama_satuan+" ] <input type='hidden' name='satuan[]' id='satuan[]' class='form-control form-control-sm' value='"+satuan+"'/>" ;
      newCell.innerHTML = combo_satuan;

    var newCell = row.insertCell(2);
      newCell.align ="center";
      //newCell.innerHTML = stok+" [ "+nama_satuan+" ] <input type='hidden' name='satuan[]' id='satuan[]' class='form-control form-control-sm' value='"+satuan+"'/>" ;
      newCell.innerHTML = combo_satuan_stok+div_stok;

  var newCell = row.insertCell(3);
      newCell.align ="center";
      newCell.innerHTML = "<input type='text' name='jumlah[]' id='jumlah["+lastRow+"]' class='form-control form-control-sm form-control form-control-sm-sm' value='1' style='text-align:center' onblur='hitungSubtotal("+lastRow+");'  onkeypress='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);' maxlength='6'/>";

    var newCell = row.insertCell(4);
      newCell.align ="center";
      newCell.innerHTML = "<input type='text' name='harga_satuan_def[]' id='harga_satuan_def["+lastRow+"]' value='"+harga_jual+"' onblur='hitungSubtotalManual("+lastRow+");' class='form-control form-control-sm'  style='text-align:right'/><input type='hidden' name='harga_satuan[]' id='harga_satuan["+lastRow+"]' value='"+harga_jual+"' class='form-control form-control-sm' readonly='readonly' style='text-align:right'/>";

    var newCell = row.insertCell(5);
      newCell.align ="center";
      newCell.innerHTML = "<font id='subtot_text_bf_"+lastRow+"'></font>";

    var newCell = row.insertCell(6);
      newCell.align ="center";
      newCell.innerHTML = "<input type='text' readonly='readonly' value='%' class='form-control form-control-sm' style='width:40%;float:left;text-align:center' onkeypress='return onlyNumberKey(event)' /><input type='text' class='form-control form-control-sm' style='width:60%;float:left;text-align:center' onkeypress='return onlyNumberKey(event)' value='Rp.' readonly='readonly'/> <input type='text' name='diskon_persen[]' id='diskon_persen["+lastRow+"]' class='form-control form-control-sm' style='width:40%;float:left;' onblur='setDiskonManual(1,this,"+lastRow+");' /><input type='text' name='diskon[]' id='diskon["+lastRow+"]' class='form-control form-control-sm' style='width:60%;float:left;text-align:right' onkeypress='return onlyNumberKey(event)' onblur='setDiskonManual(2,this,"+lastRow+");'/> ";


    var newCell = row.insertCell(7);
      newCell.align ="center";
      newCell.innerHTML = "<font id='subtot_text_"+lastRow+"'></font><input type='hidden' name='subtot[]' id='subtot["+lastRow+"]' class='form-control form-control-sm' style='text-align:right' readonly/>";

    
    var newCell = row.insertCell(8);
        newCell.align = "center";
        newCell.innerHTML = "<button class='btn btn-danger btn-sm'style='font-size:.675rem !important' onclick='setQty("+lastRow+",3,this);'><i class='fa fa-times'></i></button>";

      hitungSubtotal(lastRow);

      ganticombo(lastRow);
}

function ganticombo(id){
  $("select[id='satuan["+id+"]']").select2();
  $("select[id='satuan_stok["+id+"]']").select2();
}

function hitungSubtotalManual(id){
  var isikecil = $('option:selected', $("select[id='satuan["+id+"]']")).attr('data-isikecil');
  var harga_def =$("input[id='harga_satuan_def["+id+"]']").val() ;
  var new_harga = harga_def * isikecil;
  new_harga = parseFloat(new_harga).toFixed();
  $("input[id='harga_satuan["+id+"]']").val(new_harga);
  hitungSubtotal(id);

}

function hitungSubtotal(id) {

    var nominal = backToNormal(document.getElementById('harga_satuan['+id+']'));
    var qty     = document.getElementById('jumlah['+id+']').value;
    var diskon  = (document.getElementById('diskon['+id+']').value!='') ? document.getElementById('diskon['+id+']').value : 0;
    var isikecil = $('option:selected', $("select[id='satuan["+id+"]']")).attr('data-isikecil');
    var total   = nominal*qty;
    total = total.toFixed();
    diskon = diskon*isikecil*qty;
    var total_akhir = total - diskon;
    document.getElementById('subtot_text_bf_'+id).innerHTML = formatRupiah("'"+total+"'",'Rp. ');
    document.getElementById('subtot['+id+']').value = formatRupiah("'"+total_akhir+"'",'Rp. ');
    document.getElementById('subtot_text_'+id).innerHTML = formatRupiah("'"+total_akhir+"'",'Rp. ');

    hitungTotal();
}

function hitungTotal(){

    var total_semua = 0;
    //var total_diskon = backToNormal(document.getElementById('total_diskon'));
    //var ppn_persen = (parseInt(document.getElementById('ppn_persen').value) != '' ) ? document.getElementById('ppn_persen').value : 0;
    //var ppn_rp = 0;

    $.each($("input[name='subtot[]']"),function(k,v){
        if (backToNormal(this) != '' && backToNormal(this) > 0){
            var nominal_row = backToNormal(this);
            total_semua = parseFloat(total_semua) + parseFloat(nominal_row);
        }
    });

    /*if ( (total_semua - total_diskon) < 0 ) {

        alert('Diskon Tidak Boleh Lebih Besar Dari Total Harga Barang');
        $("#total_diskon").val('0');

    }else{

        total_semua -= total_diskon;
    }*/

    /*ppn_rp = (ppn_persen/100)*total_semua;
    $("#ppn_rp").val(ppn_rp);

    total_semua += ppn_rp;*/

    /*if ( total_semua > 0 ) {

        $("table#table_barang tfoot tr.checkout")[0].removeAttribute('hidden');
    } else{
          $("table#table_barang tfoot tr.checkout")[0].setAttribute('hidden','hidden');
    }*/

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

function backNormal(is_final){
    document.getElementById('is_final').value = is_final;
    var harga_satuan = $("input[name='harga_satuan[]']");
    var subtot = $("input[name='subtot[]']");
    

    if ( subtot.length == 0 ) {

        alert('Barang Belum Dipilih');
        return false;

    }

    checkNom(document.getElementById('total'));

    $.each(harga_satuan,function(k,v){
       checkNom(this);
    });

    $.each(subtot,function(k,v){
       checkNom(this);
    });

    return true;
}

 function onlyNumberKey(evt) { 
          
        // Only ASCII charactar in that range allowed 
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode 
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)) 
            return false; 
        return true; 
    } 

  function setMakanan(obj) {

      if ( obj.value != '' ) {

          var allow = '1';
          $.each($("input[name='tbrid[]']"),function(k,v){
               
                    if ( this.value == obj.value ) {
                        //alert('Barang sudah ditambahkan di list, gunakan fungsi tambah qty ');
                        allow = '1';
                        //obj.value='';
                    } else {
                        allow = '1';
                    }
            });


          if (  allow == '1' ) {

              $.post("<?php echo base_url('Kasir/list_master_barang');?>",{tbrid:obj.value},function(data){

            var hasil = JSON.parse(data);
            var num = 0;
            
                    $.each(hasil,function(k,v){
                      if ( v.is_manual == 1 ) {
                        addrowmanual(v.tbrid,v.nama_barang_new,v.harga_jual,v.stok,v.nama_satuan,v.satuan,v.list_satuan,v.konversi_satuan);
                      }else{
                        addrow(v.tbrid,v.nama_barang_new,v.harga_jual,v.stok,v.nama_satuan,v.satuan,v.list_satuan,v.konversi_satuan);
                      }
                    });

                    $("select[name='tkgid']").val('').select2();
              });

          }

      }
  }

  function cancelCheckout(){
    $("h3.card-title").html('Tambah Pembayaran');
      $("div#detail_check_makanan").empty();
      $("div#form-konfirm.form-group-row").fadeOut('slow');
      $("div#form-isi.form-group-row").fadeIn('slow');
      $("div#form-isi.form-group-row").removeAttr('hidden','');
      $("#btn_simpan").attr('hidden','hidden');
      $("#btn_cancel").attr('hidden','hidden');
      $("#btn_simpan_sementara").removeAttr('hidden','');
  }

  function monthtext(val) {

      switch(val) {
      case '01':
          var nama_bulan = 'Januari';
        break;
      case '02':
          var nama_bulan = 'Februari';
        break;
      case '03':
          var nama_bulan = 'Maret';
        break;
      case '04':
          var nama_bulan = 'April';
        break;
      case '05':
          var nama_bulan = 'Mei';
        break;
      case '06':
          var nama_bulan = 'Juni';
        break;
      case '07':
          var nama_bulan = 'Juli';
        break;
      case '08':
          var nama_bulan = 'Agustus';
        break;
      case '09':
          var nama_bulan = 'September';
        break;
      case '10':
          var nama_bulan = 'Oktober';
        break;
      case '11':
          var nama_bulan = 'November';
        break;
      case '12':
          var nama_bulan = 'Desember';
        break;
      default:
        var nama_bulan = 'kosong';
    } 

    return nama_bulan;
  }

  function checkOut(){

      var tgl_trans_awal = $("input[name='tgl_trans']").val();
      var tgl_trans_var = tgl_trans_awal.split('/');
      var tgl_trans = tgl_trans_var[1]+' '+monthtext(tgl_trans_var[0])+' '+tgl_trans_var[2];
      $("#tgl_bayar_text").val(tgl_trans);

      var pelanggan = $("input[name='nama_pelanggan']").val();
      $("#pelanggan_text").val(pelanggan);

      var no_meja   = $("input[name='no_meja']").val();
      $("#no_meja_text").html("No. Meja : [ "+no_meja+" ]");

      var tblList     = document.getElementById("table_barang");
      var tblBody     = tblList.tBodies[0];
      var lastRow     = tblBody.rows.length;

      var detail_check_makanan = $("div#detail_check_makanan");
      for (i=0;i<lastRow;i++){

        var tambahan = "";

        if ( document.getElementById('jumlah['+i+']').value > 1 ) {

            tambahan = "@ "+document.getElementById('harga_satuan['+i+']').value;
        }

        detail_check_makanan.append("<div>"+
                                      "<div style='width:10%;float:left'>"+document.getElementById('jumlah['+i+']').value+" x</div>"+
                                      "<div style='width:70%;float:left'>"+document.getElementById('nama_barang['+i+']').value+" "+tambahan+"</div>"+
                                      "<div style='width:20%;float:left;text-align:right'>"+document.getElementById('subtot['+i+']').value+"</div>"+
                                  "</div>");
      }

        detail_check_makanan.append("<br style='clear:both'>");




      $("h3.card-title").html('Konfirmasi Pembayaran');
      $("div#form-isi.form-group-row").fadeOut('slow');
      $("div#form-konfirm.form-group-row").fadeIn('slow');
      $("div#form-konfirm.form-group-row").removeAttr('hidden','');
      $("#btn_simpan_sementara").attr('hidden','hidden');
      $("#btn_simpan").removeAttr('hidden','');
      $("#btn_cancel").removeAttr('hidden','');
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
            <h1 class="m-0 text-dark"><i class='fa fa-cash-register'></i> Kasir</h1>
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
                  Tambah Pembayaran
                </h3>
              </div>
              <form class='form-horizontal' name='frm' id='frm' method='post' action="<?php echo base_url('Kasir/act_add_bill'); ?>">
                  <input type='hidden' name='is_final' id='is_final'/>
                  <div class='card-body'>
                    <div class='form-group-row' id='form-isi'>

                        <div class='col col-12'>
                            
                            <div class='col' style="float:left">

                                  <div class='col col-7' style="float:left">
                                    <label class="col col-form-label">Tanggal Bill</label>
                                    <div class="col">
                                      <div class="input-group">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                          </span>
                                        </div>
                                       <input type="text" class="form-control form-control-sm float-right" name="tgl_trans" id="reservation" value="<?php echo date('m/d/Y')?>" readonly="">
                                      </div>
                                    </div>
                                   </div>

                                     <div class='col col-5' style="float:left">

                                     <label for="inputEmail3" class="col col-form-label">Kasir</label>
                                      <?php echo $_SESSION['nama_real'];?>
                                   </div>

                                   <div class='col col-6' style="float:left">

                                     <label for="inputEmail3" class="col col-form-label">Pelanggan</label>
                                      <!-- <input type='text' name='nama_pelanggan' id='nama_pelanggan' class="form-control form-control-sm" required="" /> -->
                                      <select name="nama_pelanggan" id="nama_pelanggan" class="form-control form-control-sm" required="">
                                        <option></option>
                                        <?php 
                                          foreach ($list_konsumen->result_array() as $key) {
                                            echo "<option value='".$key['tknid']."'>".$key['nama_konsumen']."</option>";
                                          }
                                        ?>
                                      </select>
                                   </div>

                                   <div class='col col-4' style="float:left" hidden>

                                     <label for="inputEmail3" class="col col-form-label">No. Meja</label>
                                      <input type='text' name='no_meja' id='no_meja' class="form-control form-control-sm" value="0" />
                                   </div>


                                  <div class='col col-6' style="float:left">

                                             <label for="inputEmail3" class="col col-form-label">Pilih Barang</label>
                                                  
                                              <select name='tkgid' id='tkgid' class='form-control form-control-sm' onchange="setMakanan(this);">
                                                <option></option>
                                                <?php 
                                                  foreach ($list_barang->result_array() as $key) {
                                                    echo "<option value='".$key['tbrid']."'>".$key['nama_barang_new']." [".$key['kd_jenis']."] @".$key['harga_jual']." (Kg)</option>";
                                                  }
                                                ?>
                                              </select>
                              

                                       </div>

                                       <div class='col col-12' style="float:left;margin-top:10px;">
                                
                                          <table class='table table-bordered' id='table_barang'>
                                                <thead>
                                                  <tr>
                                                    <th>Barang</th>
                                                    <th width="12%">Satuan Jual</th>                                          
                                                    <th width="10%">Satuan Stok</th>
                                                    <th width='7%'>Qty</th>
                                                    <th width='11%'>Harga (Kg)</th>
                                                    <th width='11%'>Tot. Sblm Diskon</th>
                                                    <th width='14%'>Diskon / Kg</th>
                                                    <th width='11%'>Tot. Stlh Diskon</th>
                                                    <th width='5%'>&nbsp;</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                               
                                                  <tr>
                                                    <td colspan='7' style="text-align: right">Total</td>
                                                    <td><input type='text' name='total' id='total' class='form-control form-control-sm form-control form-control-sm-sm' value='0' style='text-align:right' readonly /></td>
                                                    <td>&nbsp;</td>
                                                  </tr>
                                                  <tr class='checkout' hidden>
                                                    <td colspan="5">
                                                      <button class='btn btn-warning' onclick="checkOut();return false;"><i class='fa fa-shopping-cart'></i> Checkout</button>
                                                    </td>
                                                  </tr>
                                                </tfoot>
                                              </table>

                                      </div>

                              
                            </div>

                            

                        </div>

                  </div>
                  <div class="form-group-row" id="form-konfirm" hidden>
                    <div class='col col-12' >

                        <div class='col col-6'>
                          <div class='col col-4' style='float:left'>
                              <label for="inputEmail3" class="col col-form-label">Tanggal Bill</label>
                              <input type='text' id='tgl_bayar_text' class='form-control form-control-sm' value='01/01/2020' style="border:none" />
                          </div>
                          <div class='col col-4' style='float:left'>
                              <label for="inputEmail3" class="col col-form-label">Kasir</label>
                              <input type='text' id='kasir_text' class='form-control form-control-sm' value='<?php echo $_SESSION['nama_real'];?>' style="border:none"/>
                          </div>
                          <div class='col col-4' style='float:left'>
                              <label for="inputEmail3" class="col col-form-label">Pelanggan</label>
                              <input type='text' id='pelanggan_text' class='form-control form-control-sm' style="border:none" />
                          </div>
                          <div class='col col-12' style='float:left'>
                              <label for="inputEmail3" class="col col-form-label">Detail Barang

                                  <span id='no_meja_text' style="float:right"></span>
                              </label>
                              <div style="border:1px solid #eee;padding:10px" id='detail_check_makanan'>
                                
                              </div>
                          </div>

                        </div>
                    </div>
                  </div>
                </div>
                 <div class='card-footer'>
                 <button id='btn_simpan_sementara' class='btn btn-info btn-sm' onclick="$('#btn_simpan_sementara_real').click();return false;"><i class='fa fa-save'></i> Simpan Sementara</button>
                 <input type='submit' class='btn btn-info' id='btn_simpan_sementara_real' value='Simpan Sementara' onclick="return backNormal('3');" hidden/>
                 <button class='btn btn-danger' id='btn_cancel' onclick="cancelCheckout();return false;" hidden><i class='fa fa-ban'></i> Batal Checkout</button>
                 <input type='submit' class='btn btn-info' id='btn_simpan' value='Simpan Final' onclick="return backNormal('1');" hidden/>
                 <span style='float:right'>
                                  <!-- <button class='btn btn-warning' onclick="addrow();"><i class='fa fa-plus'></i> Tambah Barang</button> -->
                                  <input type='button' value="Tambah Barang" id='cek_barang' onclick="cekNominalnya()" class='btn btn-danger' hidden/>
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
                      <!-- <?php 
                        $nos = 1;
                        foreach ($list_barang->result_array() as $key) {
                          echo "
                            <input type='hidden' name='nama_brg[]' id='nama_brg[".$key['tbrid']."]' value='".$key['nama_barang']."'/>
                            <tr>
                              <td width='1%'>".$nos.".</td>
                              <td>".$key['nama_barang']."</td>
                              <td align='center'><input type='checkbox' class='pil_brg' value='".$key['tbrid']."' onclick='return checkList(".$key['tbrid'].");' name='choose[".$key['tbrid']."]' id='choose[".$key['tbrid']."]'/></td>
                            </tr>
                          ";

                          $nos++;
                        }
                      ?> -->
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
    $("#reservation").daterangepicker({
        singleDatePicker: true,
        showDropdowns: true
    }, 
    function(start, end, label) {
        var years = moment().diff(start, 'years');
        
    });
});
</script>

<script type="text/javascript">



$(document).ready(function(){

  $("select[name='nama_pelanggan']").select2();
  $("select[name='tkgid']").select2();

});


</script>