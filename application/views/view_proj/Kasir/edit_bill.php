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

  //var new_harga = formatRupiah("'"+harga+"'",'Rp. ');
  /*$("#harga_satuan_text_"+id).html(new_harga);*/
  $("input[id='harga_satuan["+id+"]']").val(harga);
//  
  $("select[id='satuan_stok["+id+"]']").val(obj.value).select2();

  if ( isbesar == 't' ) {
    $("select[id='satuan_stok["+id+"]']").trigger('change').select2();
    $("select[id='satuan_stok["+id+"]']").css('pointer-events','none').select2('destroy');
  }else{
     $("span[id='status_stok["+id+"]']").html(stok);
    $("select[id='satuan_stok["+id+"]']").select2();;
  }

  hitungSubtotal(id);
}

function setnewsatuanmanual(obj,id) {

  var harga = $("input[id='harga_satuan_def["+id+"]']").val();
  var stok = $('option:selected', obj).attr('data-stok');
  var isbesar = $('option:selected', obj).attr('data-isbesar');
  var isikecil = $('option:selected', obj).attr('data-isikecil');

  harga = harga * isikecil;
  harga = parseFloat(harga).toFixed();

  //var new_harga = formatRupiah("'"+harga+"'",'Rp. ');
  /*$("#harga_satuan_text_"+id).html(new_harga);*/
  $("input[id='harga_satuan["+id+"]']").val(harga);
//  
  $("select[id='satuan_stok["+id+"]']").val(obj.value).select2();

  if ( isbesar == 't' ) {
    $("select[id='satuan_stok["+id+"]']").trigger('change').select2();
    $("select[id='satuan_stok["+id+"]']").css('pointer-events','none').select2('destroy');
  }else{
     $("span[id='status_stok["+id+"]']").html(stok);
    $("select[id='satuan_stok["+id+"]']").select2();;
  }

  hitungSubtotal(id);
}

function setnewsatuan2(obj,id) {

  var harga = $('option:selected', obj).attr('data-harga');
  var stok = $('option:selected', obj).attr('data-stok');
  var isbesar = $('option:selected', obj).attr('data-isbesar');
  var isikecil = $('option:selected', obj).attr('data-isikecil');
  var data_satuan_jual = $("select[id='satuan["+id+"]']").val();




  //var new_harga = formatRupiah("'"+harga+"'",'Rp. ');
  //$("#harga_satuan_text_"+id).html(new_harga);
  //$("input[id='harga_satuan["+id+"]']").val(harga);

  if ( data_satuan_jual != obj.value ) {
     var stok_beda = parseFloat(stok).toFixed(2);
  } else {
      var stok_beda = stok/isikecil;
      stok_beda = parseFloat(stok_beda).toFixed(2);
  }

  $("span[id='status_stok["+id+"]']").html(stok_beda);


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
  
  var object_value = (obj.value !='') ? obj.value : 0;

  var total   = harga / isikecil;
  var hasil_diskon = hasil_diskon_persen = 0;
  if ( tipe == 1 ) { // persen

    hasil_diskon =  ( object_value / 100 ) * total;

    $("input[id='diskon["+id+"]']").val(hasil_diskon);

  } else if ( tipe == 2 ) { // nominal

    hasil_diskon_persen = ( object_value / total ) * 100;
  
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

function addrow(tbrid,nama_barang,harga_jual,qty,stok,nama_satuan,satuan,list_satuan,konversi_satuan,tsid_stok,tsid,harga_hna){

  var tblList     = document.getElementById("table_barang");
  var tblBody     = tblList.tBodies[0];
  var lastRow     = tblBody.rows.length;
  var row         = tblBody.insertRow(lastRow);
  var data_satuan = list_satuan.split(':');

  var new_stok = stok;

  harga_hna = parseFloat(harga_hna).toFixed();

  var combo_satuan = "<select name='satuan[]' id='satuan["+lastRow+"]' class='form-control form-control-sm' onchange='setnewsatuan(this,"+lastRow+")'><option value='"+satuan+"' data-harga='"+harga_hna+"' data-stok='"+stok+"' data-isbesar='f' data-isikecil='1'>"+nama_satuan+"</option>";
  var combo_satuan_stok = "<select name='satuan_stok[]' id='satuan_stok["+lastRow+"]' class='form-control form-control-sm'  onchange='setnewsatuan2(this,"+lastRow+")'><option></option>";

  $.each(data_satuan,function(k,v){
      var list_satuann = v.replace('\"','');
      list_satuann = list_satuann.replace('\"','');
      var detail_satuan = list_satuann.split('_');
      var selected_satuan = selected_satuan2 = "";
      if ( detail_satuan[0] == tsid ) {
        selected_satuan = "selected='selected'";
      }

      if (detail_satuan[0] == tsid_stok) {
         selected_satuan2 = "selected='selected'";
      }

      combo_satuan += "<option value='"+detail_satuan[0]+"' "+selected_satuan+" data-harga='"+detail_satuan[1]+"' data-stok='"+detail_satuan[3]+"' data-isbesar='t' data-isikecil='"+detail_satuan[4]+"'>"+detail_satuan[2]+"</option>";
      combo_satuan_stok += "<option value='"+detail_satuan[0]+"' "+selected_satuan2+" data-harga='"+detail_satuan[1]+"' data-stok='"+detail_satuan[3]+"' data-isikecil='"+detail_satuan[4]+"'>"+detail_satuan[2]+"</option>";
  });

  combo_satuan += "</select>";
  combo_satuan_stok += "</select>";
  

  var new_harga_jual = formatRupiah("'"+harga_hna+"'",'Rp. ');
  row.setAttribute('class','makanan_'+lastRow);

  var newCell = row.insertCell(0);
      newCell.align ="center";
      newCell.innerHTML = "<input type='hidden' name='tbrid[]' id='tbrid[]' class='form-control form-control-sm' value='"+tbrid+"'/><input type='hidden' name='nama_barang["+lastRow+"]' id='nama_barang["+lastRow+"]' class='form-control form-control-sm' value='"+nama_barang+"'/>"+nama_barang;

   var div_stok = "<div style='width:100%;border:1px solid #ccc;margin-top:2px;border-radius:5px'><span style='float:left;padding:5px;text-align:center;background:#eee;font-size:10pt;'>Sisa Stok</span><span style='float:left;padding:5px;text-align:center;font-size:10pt;' id='status_stok["+lastRow+"]'>"+new_stok+"</span><br style='clear:both'/></div>";

  var newCell = row.insertCell(1);
      newCell.innerHTML = combo_satuan;

      var newCell = row.insertCell(2);
      newCell.align ="center";
      //newCell.innerHTML = stok+" [ "+nama_satuan+" ] <input type='hidden' name='satuan[]' id='satuan[]' class='form-control form-control-sm' value='"+satuan+"'/>" ;
      newCell.innerHTML = combo_satuan_stok+div_stok;

  var newCell = row.insertCell(3);
      newCell.align ="center";
      newCell.innerHTML = "<input type='text' name='jumlah[]' id='jumlah["+lastRow+"]' class='form-control form-control-sm' value='"+qty+"' style='text-align:center' onblur='hitungSubtotal("+lastRow+");'  onkeypress='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);' maxlength='6'/>";

    var newCell = row.insertCell(4);
      newCell.align ="center";
      newCell.innerHTML = "<font id='harga_satuan_text_"+lastRow+"'>"+new_harga_jual+"</font><input type='hidden' name='harga_satuan_def[]' id='harga_satuan_def["+lastRow+"]' value='0' class='form-control form-control-sm'  style='text-align:right'/><input type='hidden' name='harga_satuan[]' id='harga_satuan["+lastRow+"]' value='"+harga_jual+"' class='form-control form-control-sm' readonly='readonly' style='text-align:right'/>";


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

function addrowmanual(tbrid,nama_barang,harga_jual,qty,stok,nama_satuan,satuan,list_satuan,konversi_satuan,tsid_stok,tsid,harga_hna){

  var tblList     = document.getElementById("table_barang");
  var tblBody     = tblList.tBodies[0];
  var lastRow     = tblBody.rows.length;
  var row         = tblBody.insertRow(lastRow);
  var data_satuan = list_satuan.split(':');

  var new_stok = stok;

  harga_hna = parseFloat(harga_hna).toFixed();

  var combo_satuan = "<select name='satuan[]' id='satuan["+lastRow+"]' class='form-control form-control-sm' onchange='setnewsatuanmanual(this,"+lastRow+")'><option value='"+satuan+"' data-harga='"+harga_hna+"' data-stok='"+stok+"' data-isbesar='f' data-isikecil='1'>"+nama_satuan+"</option>";
  var combo_satuan_stok = "<select name='satuan_stok[]' id='satuan_stok["+lastRow+"]' class='form-control form-control-sm'  onchange='setnewsatuan2(this,"+lastRow+")'><option></option>";

  $.each(data_satuan,function(k,v){
      var list_satuann = v.replace('\"','');
      list_satuann = list_satuann.replace('\"','');
      var detail_satuan = list_satuann.split('_');
      var selected_satuan = selected_satuan2 = "";
      if ( detail_satuan[0] == tsid ) {
        selected_satuan = "selected='selected'";
      }

      if (detail_satuan[0] == tsid_stok) {
         selected_satuan2 = "selected='selected'";
      }

      combo_satuan += "<option value='"+detail_satuan[0]+"' "+selected_satuan+" data-harga='"+detail_satuan[1]+"' data-stok='"+detail_satuan[3]+"' data-isbesar='t' data-isikecil='"+detail_satuan[4]+"'>"+detail_satuan[2]+"</option>";
      combo_satuan_stok += "<option value='"+detail_satuan[0]+"' "+selected_satuan2+" data-harga='"+detail_satuan[1]+"' data-stok='"+detail_satuan[3]+"' data-isikecil='"+detail_satuan[4]+"'>"+detail_satuan[2]+"</option>";
  });

  combo_satuan += "</select>";
  combo_satuan_stok += "</select>";
  

  var new_harga_jual = formatRupiah("'"+harga_jual+"'",'Rp. ');
  row.setAttribute('class','makanan_'+lastRow);

  var newCell = row.insertCell(0);
      newCell.align ="center";
      newCell.innerHTML = "<input type='hidden' name='tbrid[]' id='tbrid[]' class='form-control form-control-sm' value='"+tbrid+"'/><input type='hidden' name='nama_barang["+lastRow+"]' id='nama_barang["+lastRow+"]' class='form-control form-control-sm' value='"+nama_barang+"'/>"+nama_barang;

   var div_stok = "<div style='width:100%;border:1px solid #ccc;margin-top:2px;border-radius:5px'><span style='float:left;padding:5px;text-align:center;background:#eee;font-size:10pt;'>Sisa Stok</span><span style='float:left;padding:5px;text-align:center;font-size:10pt;' id='status_stok["+lastRow+"]'>"+new_stok+"</span><br style='clear:both'/></div>";

  var newCell = row.insertCell(1);
      newCell.innerHTML = combo_satuan;

      var newCell = row.insertCell(2);
      newCell.align ="center";
      //newCell.innerHTML = stok+" [ "+nama_satuan+" ] <input type='hidden' name='satuan[]' id='satuan[]' class='form-control form-control-sm' value='"+satuan+"'/>" ;
      newCell.innerHTML = combo_satuan_stok+div_stok;

  var newCell = row.insertCell(3);
      newCell.align ="center";
      newCell.innerHTML = "<input type='text' name='jumlah[]' id='jumlah["+lastRow+"]' class='form-control form-control-sm' value='"+qty+"' style='text-align:center' onblur='hitungSubtotal("+lastRow+");'  onkeypress='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);' maxlength='6'/>";

    var newCell = row.insertCell(4);
      newCell.align ="center";
      newCell.innerHTML = "<input type='text' name='harga_satuan_def[]' id='harga_satuan_def["+lastRow+"]' value='"+harga_hna+"' onblur='hitungSubtotalManual("+lastRow+");' class='form-control form-control-sm'  style='text-align:right'/><input type='hidden' name='harga_satuan[]' id='harga_satuan["+lastRow+"]' value='"+harga_jual+"' class='form-control form-control-sm' readonly='readonly' style='text-align:right'/>";


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

function hitungTotalAkhir(){



    var total_semua = 0;
    var total_diskon = backToNormal(document.getElementById('total_diskon'));
    var ppn_persen = (parseInt(document.getElementById('ppn_persen').value) != '' ) ? document.getElementById('ppn_persen').value : 0;
    var sudah_dibayar = (parseInt(document.getElementById('sudah_dibayar').value) != '' ) ? parseFloat(backToNormal(document.getElementById('sudah_dibayar'))) : 0;
    var ongkir = parseInt(backToNormal(document.getElementById('ongkir')));
    var ppn_rp = 0;

    $.each($("input[name='subtot[]']"),function(k,v){
        if (backToNormal(this) != '' && backToNormal(this) > 0){
            var nominal_row = backToNormal(this);
            total_semua = parseFloat(total_semua) + parseFloat(nominal_row);
        }
    });

    total_semua -= sudah_dibayar;

    if ( (total_semua - total_diskon) < 0 ) {

        alert('Diskon Tidak Boleh Lebih Besar Dari Total Harga Barang');
        $("#total_diskon").val('0');

    }else{

        total_semua -= total_diskon;
    }

    ppn_rp = (ppn_persen/100)*total_semua;
    $("#ppn_rp").val(formatRupiah("'"+ppn_rp+"'",'Rp. '));

    total_semua += ppn_rp;


    total_semua += ongkir;

    $('#total_bayar').val(formatRupiah("'"+total_semua+"'",'Rp. '));

    hitungKembalian();


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

    if ( total_semua > 0 ) {

        $("table#table_barang tfoot tr.checkout")[0].removeAttribute('hidden');
    } else{
          $("table#table_barang tfoot tr.checkout")[0].setAttribute('hidden','hidden');
    }

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
    var sisa_tagihan = backToNormal(document.getElementById('sisa_tagihan'));
    var angunan = $("textarea[name='angunan']").val();
    

    if ( subtot.length == 0 ) {

        alert('Barang Belum Dipilih');
        return false;

    }


    if ( parseFloat(sisa_tagihan) > 0  && is_final == 1 && angunan == '') {

      alert('Sisa Tagihan Masih Ada, Isi agunan jika ini pembayaran cicilan');
      return false;
    }

    checkNom(document.getElementById('total'));

    if ( is_final == 1 ) {
        
        checkNom(document.getElementById('total_diskon'));
        checkNom(document.getElementById('ppn_rp'));       
        checkNom(document.getElementById('total_bayar'));       
        checkNom(document.getElementById('total_bayar_pasien'));       
        checkNom(document.getElementById('sudah_dibayar'));       
        checkNom(document.getElementById('ongkir'));       
    }

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


          if (  allow === '1' ) {

              $.post("<?php echo base_url('Kasir/list_master_barang');?>",{tbrid:obj.value},function(data){

            var hasil = JSON.parse(data);
            var num = 0;
            
                    $.each(hasil,function(k,v){
                      if ( v.is_manual == 1 ) {
                        addrowmanual(v.tbrid,v.nama_barang_new,v.harga_jual,1,v.stok,v.nama_satuan,v.satuan,v.list_satuan,v.konversi_satuan,0,0,v.harga_jual);
                      }else{
                        addrow(v.tbrid,v.nama_barang_new,v.harga_jual,1,v.stok,v.nama_satuan,v.satuan,v.list_satuan,v.konversi_satuan,0,0,v.harga_jual);  
                      }
                      
                    });

                    obj.value='';
              });

          }

      }
  }

  function cancelCheckout(){
    $("h3.card-title").html('Edit Pembayaran');
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

      var sudah_dibayar = document.getElementById('sudah_dibayar').value;
      document.getElementById('sudah_dibayar').value = formatRupiah("'"+sudah_dibayar+"'","Rp. ");

      var ongkir = document.getElementById('ongkir').value;
      document.getElementById('ongkir').value = formatRupiah("'"+ongkir+"'","Rp. ");

      var tgl_trans_awal = $("input[name='tgl_trans']").val();
      var tgl_trans_var = tgl_trans_awal.split('/');
      var tgl_trans = tgl_trans_var[1]+' '+monthtext(tgl_trans_var[0])+' '+tgl_trans_var[2];
      $("#tgl_bayar_text").val(tgl_trans);

      var pelanggan = $("select[name='nama_pelanggan'] option:selected").text();
      $("#pelanggan_text").val(pelanggan);

      var no_meja   = $("input[name='no_meja']").val();
      $("#no_meja_text").html("No. Meja : [ "+no_meja+" ]");

      var tblList     = document.getElementById("table_barang");
      var tblBody     = tblList.tBodies[0];
      var lastRow     = tblBody.rows.length;

      var detail_check_makanan = $("div#detail_check_makanan");
      for (i=0;i<lastRow;i++){

        var tambahan = "";

        var satuan_jual_pilih_val = $("select[id='satuan["+i+"]'] option:selected").val();
        var satuan_stok_pilih_val = $("select[id='satuan_stok["+i+"]'] option:selected").val();
        //var satuan_jual_pilih_text = $("select[id='satuan["+i+"]'] option:selected").text();
        //var satuan_stok_pilih_text = $("select[id='satuan_stok["+i+"]'] option:selected").text();

        if ( satuan_jual_pilih_val != satuan_stok_pilih_val ) {
            var info_satuan = 'KG';
        }else{
            var info_satuan = 'ROLL';
        }

        detail_check_makanan.append("<div style='width:100%'>"+
                                      "<div style='width:15%;float:left;font-size:10pt;'>"+document.getElementById('jumlah['+i+']').value+" "+info_satuan+"</div>"+
                                      "<div style='width:65%;float:left;font-size:10pt;'>"+document.getElementById('nama_barang['+i+']').value+" "+tambahan+"</div>"+
                                      "<div style='width:20%;float:left;text-align:right;font-size:10pt;'>"+document.getElementById('subtot['+i+']').value+"</div>"+
                                  "</div>");
      }

        detail_check_makanan.append("<div>"+
                                      "<div style='width:15%;float:left;border-top:1px solid black;font-size:10pt;'>&nbsp;</div>"+
                                      "<div style='width:65%;float:left;text-align:right;border-top:1px solid black;font-size:10pt;'>Subtotal :</div>"+
                                      "<div style='width:20%;float:left;text-align:right;border-top:1px solid black;font-size:10pt;'>"+document.getElementById('total').value+"</div>"+
                                  "</div>");

        detail_check_makanan.append("<br style='clear:both'>");




      $("h3.card-title").html('Konfirmasi Pembayaran');
      $("div#form-isi.form-group-row").fadeOut('slow');
      $("div#form-konfirm.form-group-row").fadeIn('slow');
      $("div#form-konfirm.form-group-row").removeAttr('hidden','');
      $("#btn_simpan_sementara").attr('hidden','hidden');
      $("#btn_simpan").removeAttr('hidden','');
      $("#btn_cancel").removeAttr('hidden','');

      hitungTotalAkhir();
  }

  function hitungKembalian(from=''){

     var total_bayar = backToNormal(document.getElementById('total_bayar'));
     var bayar_pasien = backToNormal(document.getElementById('total_bayar_pasien'));
     var cara_bayar   = document.getElementById('cara_bayar').value;
     var sisa = 0;


     if ( (cara_bayar == 2 || cara_bayar == 3) && (parseFloat(bayar_pasien) > parseFloat(total_bayar)) ) {

          alert('Nominal Bayar Tidak Boleh 0 Dan Tidak Boleh Lebih Besar Dari Total Tagihan');
          document.getElementById('total_bayar_pasien').value = 0;
          bayar_pasien = 0;
     } 




     if ( parseFloat(bayar_pasien) < parseFloat(total_bayar) ) {

          sisa = parseFloat(total_bayar) - parseFloat(bayar_pasien);
     }

     document.getElementById('sisa_tagihan').value = formatRupiah("'"+sisa+"'",'Rp. ');

      if ( parseFloat(bayar_pasien) > parseFloat(total_bayar) ) {

          sisa = parseFloat(bayar_pasien) - parseFloat(total_bayar);

      }else{
          sisa = 0;
      }

      document.getElementById('kembalian').innerHTML = formatRupiah("'"+sisa+"'",'Rp. ');
     
  }

  function cekCara(obj){

      $("input[name='total_bayar_pasien']").val('0');
      $("select[id='edc']").val('');

      if ( obj.value == 1 ) {
          $("input[name='total_bayar_pasien']").removeAttr('readonly','');
          $("div#kotak_edc").attr('hidden','hidden');
          $("div#kotak_no_batch").attr('hidden','hidden');
          $("div#kotak_no_kartu").attr('hidden','hidden');
          $("div#kotak_atas_nama").attr('hidden','hidden');

      } else{
          $("input[name='total_bayar_pasien']").attr('readonly','readonly');
          $("div#kotak_edc").removeAttr('hidden','');
          $("div#kotak_no_batch").removeAttr('hidden','');
          $("div#kotak_no_kartu").removeAttr('hidden','');

          if ( obj.value == 2 ) {
            $('#edc option[tipe_trans="2"]').attr('disabled',true);
            $('#edc option[tipe_trans="1"]').attr('disabled',false);
            $("div#kotak_no_batch").attr('class','col col-3');
            $("div#kotak_no_kartu").attr('class','col col-3');
            $("div#kotak_no_batch label").html('No Batch');
            $("div#kotak_no_kartu label").html('No Kartu');
            $("div#kotak_edc label").html('EDC');
          }
          else if ( obj.value == 3 ) {
            $('#edc option[tipe_trans="1"]').attr('disabled',true);
             $('#edc option[tipe_trans="2"]').attr('disabled',false);
             $("div#kotak_no_batch").attr('class','col col-6');
              $("div#kotak_no_kartu").attr('class','col col-6');
              $("div#kotak_no_batch label").html('No. Rek');
              $("div#kotak_no_kartu label").html('Nama Bank');
              $("div#kotak_edc label").html('Ke Rekening');
              $("div#kotak_atas_nama").removeAttr('hidden','');

          }

      }

      hitungKembalian();
  }

  function lepasNominal() {

      var no_batch = document.getElementById('no_batch').value;
      var no_kartu = document.getElementById('no_kartu').value;
      var atas_nama = document.getElementById('atas_nama').value;
      var cara_bayar = $("select[name='cara_bayar']").val();


      if ( no_batch != ''  && no_kartu != '' ) {
          if ( cara_bayar == 2 ) {
          $ ("input[name='total_bayar_pasien']").removeAttr('readonly','');
          } else {
            if ( atas_nama != '' ) {
              $ ("input[name='total_bayar_pasien']").removeAttr('readonly','');
            }
          }

      } else {
          $("input[name='total_bayar_pasien']").attr('readonly','readonly');
          $("input[name='total_bayar_pasien']").val('0');
          hitungKembalian();
      }
  }

  function kosongkanpayment(){

    $("input[name='sudah_dibayar']").val(0);
    hitungTotalAkhir();
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
                  Edit Pembayaran
                </h3>
              </div>
              <form class='form-horizontal' name='frm' id='frm' method='post' action="<?php echo base_url('Kasir/act_add_bill'); ?>">
                  <input type='hidden' name='tbmid' id='tbmid' value='<?php echo $tbmid;?>'/>
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
                                       <input type="text" class="form-control form-control-sm float-right" name="tgl_trans" id="reservation" value="<?php if ( $tbmid > 0 ) { echo date('m/d/Y',strtotime($list_bill->tgl_trans)); } else {echo date('m/d/Y');}?>" readonly="">
                                      </div>
                                    </div>
                                   </div>

                                     <div class='col col-5' style="float:left">

                                     <label for="inputEmail3" class="col col-form-label">Kasir</label>
                                      <?php if ( $tbmid > 0 ){ echo $list_bill->user; } else {echo $_SESSION['nama_real'];}?>
                                   </div>

                                   <div class='col col-6' style="float:left">

                                     <label for="inputEmail3" class="col col-form-label">Pelanggan</label>
                                      <!-- <input type='text' name='nama_pelanggan' id='nama_pelanggan' class="form-control" value='<?php echo $list_bill->nama_pelanggan;?>' required="" /> -->
                                      <select name="nama_pelanggan" id="nama_pelanggan" class="form-control form-control-sm" required="" style="pointer-events: none">
                                        <option></option>
                                        <?php 
                                          foreach ($list_konsumen->result_array() as $key) {
                                            if ( $key['tknid'] == $list_bill->nama_pelanggan) {
                                              echo "<option value='".$key['tknid']."' selected='selected'>".$key['nama_konsumen']."</option>";
                                            }else{
                                              echo "<option value='".$key['tknid']."'>".$key['nama_konsumen']."</option>";
                                            }
                                          }
                                        ?>
                                      </select>
                                   </div>

                                   <div class='col col-4' style="float:left" hidden>

                                     <label for="inputEmail3" class="col col-form-label">No. Meja</label>
                                      <input type='text' name='no_meja' id='no_meja' class="form-control" value='<?php echo $list_bill->no_meja;?>' required="" />
                                   </div>


                                  <div class='col col-6' style="float:left">

                                             <label for="inputEmail3" class="col col-form-label">List Barang</label>
                                                  
                                              <select name='tkgid' id='tkgid' class='form-control form-control-sm' onchange="setMakanan(this);">
                                                <option></option>
                                                <?php 
                                                  foreach ($list_barang->result_array() as $key) {
                                                    echo "<option value='".$key['tbrid']."'>".$key['nama_barang_new']." @".$key['harga_jual']." (Kg)</option>";
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
                                                    <td colspan="1"><input type='text' name='total' id='total' class='form-control form-control-sm form-control form-control-sm-sm' value='0' style='text-align:right' readonly /></td>
                                                    <td>&nbsp;</td>
                                                  </tr>
                                                  <tr class='checkout' hidden>
                                                    <td colspan="9">
                                                      <button class='btn btn-warning btn-sm' id="btn_checkout" onclick="checkOut();return false;"><i class='fa fa-shopping-cart'></i> Checkout</button>
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

                        <div class='col col-6' style="float:left">
                          <div class='col col-4' style='float:left'>
                              <label for="inputEmail3" class="col col-form-label">Tanggal Bill</label>
                              <input type='text' id='tgl_bayar_text' class='form-control form-control-sm' value='01/01/2020' style="border:none" readonly="" />
                          </div>
                          <div class='col col-4' style='float:left'>
                              <label for="inputEmail3" class="col col-form-label">Kasir</label>
                              <input type='text' id='kasir_text' class='form-control form-control-sm' value='<?php echo $_SESSION['nama_real'];?>' style="border:none" readonly=""/>
                          </div>
                          <div class='col col-4' style='float:left'>
                              <label for="inputEmail3" class="col col-form-label">Pelanggan</label>
                              <input type='text' id='pelanggan_text' class='form-control form-control-sm' style="border:none" readonly=""/>
                          </div>
                          <div class='col col-12' style='float:left'>
                              <label for="inputEmail3" class="col col-form-label">Detail Barang

                                  <span id='no_meja_text' style="float:right" hidden></span>
                              </label>
                              <div style="border:1px solid #eee;padding:10px" id='detail_check_makanan'>
                                
                              </div>
                          </div>

                        </div>

                        <div class='col col-6' style="float:left">
                          <div class='col col-4' style="float:left;" hidden>
                               <label for="inputEmail3" class="col col-form-label">Diskon</label>
                               <input type='text' id='total_diskon' name='total_diskon' class='form-control form-control-sm' style="text-align:right" value='0' onblur="hitungTotalAkhir()" />
                            </div>
                            <div class='col col-4' style="float:left;" hidden>
                               <label for="inputEmail3" class="col col-form-label">PPN (%)</label>
<input type='text' id='ppn_persen' name='ppn_persen' class='form-control form-control-sm' style="width:25%;float:left;"  value='0' onblur="hitungTotalAkhir()" onkeypress="return onlyNumberKey(event);" />
                               <input type='text' id='ppn_rp' name='ppn_rp' class='form-control form-control-sm' style="width:75%;float:left;text-align:right" readonly=""value='0' />
                            </div>  

                              <div class='col col-4' style="float:left;">
                               <label for="inputEmail3" class="col col-form-label">Sudah Dibayar</label>
                               <button style="float:left;width:25%" class='btn btn-sm btn-default' onclick="kosongkanpayment(); return false;"><i class='fa fa-times'></i></button>
                               <input type='text' id='sudah_dibayar' name='sudah_dibayar' class='form-control form-control-sm' style="float:left;width:75%;text-align:right" value='<?php echo $list_bill->amount_bayar_partial; ?>' readonly="" />
                            </div>

                              <div class='col col-4' style="float:left;">
                               <label for="inputEmail3" class="col col-form-label">Ongkir</label>
                               <input type='text' id='ongkir' name='ongkir' class='form-control form-control-sm' style="text-align:right" value='<?php echo $list_bill->ongkir_bill; ?>' onblur="hitungTotalAkhir()"/>
                            </div>

                            <div class='col col-4' style="float:left;">
                               <label for="inputEmail3" class="col col-form-label">Total Tagihan</label>
                               <input type='text' id='total_bayar' name='total_bayar' class='form-control form-control-sm' style="text-align:right" readonly="" />
                            </div>

                          

                            <div class='col col-4' style="float:left;">
                               <label for="inputEmail3" class="col col-form-label">Cara Bayar</label>
                               <select name='cara_bayar' id='cara_bayar' class='form-control form-control-sm' onchange="cekCara(this)">
                                                <option value='1'>Cash</option>
                                                <option value='2'>Debet / Credit</option>
                                                <option value='3'>Transfer</option>
                                </select>
                            </div>
                            <div class='col col-8' id='kotak_edc' style="float:left;" hidden>
                               <label for="inputEmail3" class="col col-form-label">EDC</label>
                               <select name='tedid' id='edc' class='form-control form-control-sm'>
                                                <option></option>
                                                <?php 
                                                  $jenis_trans = "";
                                                  foreach ($list_edc->result_array() as $key) {
                                                    $jenis_trans = ($key['is_trans'] == 1) ? ' [ DEBET / CREDIT ] ' : ' [ TRANSFER ] ';
                                                    echo "<option value='".$key['tedid']."' tipe_trans='".$key['is_trans']."'>".$key['nama_edc']." ".$jenis_trans."</option>";
                                                  }
                                                ?>
                                                
                                </select>
                            </div>

                            <div class='col col-3' id='kotak_no_batch' style="float:left;" hidden>
                               <label for="inputEmail3" class="col col-form-label">No Batch</label>
                               <input type='text' id='no_batch' name='no_batch' class='form-control form-control-sm' onkeyup='lepasNominal()'/>
                            </div>
                            <div class='col col-3' id='kotak_no_kartu' style="float:left;" hidden>
                               <label for="inputEmail3" class="col col-form-label">No Kartu</label>
                               <input type='text' id='no_kartu' name='no_kartu' class='form-control form-control-sm' onkeyup='lepasNominal()'/>
                            </div>
                            <div class='col col-6' id='kotak_atas_nama' style="float:left;" hidden>
                               <label for="inputEmail3" class="col col-form-label">Atas Nama</label>
                               <input type='text' id='atas_nama' name='atas_nama' class='form-control form-control-sm' onkeyup='lepasNominal()'/>
                            </div>
                            <div class='col col-6' style="float:left;">
                               <label for="inputEmail3" class="col col-form-label">Total Bayar</label>
                               <input type='text' id='total_bayar_pasien' name='total_bayar_pasien' class='form-control form-control-sm' onkeyup="hitungKembalian('input');" value='0' style="text-align: right" />
                            </div>

                            <div class='col col-12' style="float:left;">
                               <label for="inputEmail3" class="col col-form-label">Agunan</label>
                               <textarea class='form-control form-control-sm' name="angunan" id="angunan" style="resize:none"></textarea>
                            </div>

                            <div class='col col-6' style="float:left;">
                               <label for="inputEmail3" class="col col-form-label">Sisa Tagihan</label>
                               <div style="text-align: right"><input type='text' class='form-control form-control-sm' id='sisa_tagihan' value='0' readonly="" /></div>
                            </div>
                            <div class='col col-6' style="float:left;">
                               <label for="inputEmail3" class="col col-form-label">Kembalian</label>
                               <div style="text-align: right"><font id='kembalian'>Rp. 0</font></div>
                            </div>

                        </div>
                    </div>
                  </div>
                </div>
                 <div class='card-footer'>
                  <button id='btn_simpan_sementara' class='btn btn-info btn-sm' onclick="$('#btn_simpan_sementara_real').click();return false;"><i class='fa fa-save'></i> Simpan Sementara</button>
                 <input type='submit' class='btn btn-info' id='btn_simpan_sementara_real' value='Simpan Sementara' onclick="return backNormal('3');" hidden/>
                 <button class='btn btn-danger btn-sm' id='btn_cancel' onclick="cancelCheckout();return false;" hidden><i class='fa fa-ban'></i> Batal Checkout</button>

                 <button id='btn_simpan' class='btn btn-success btn-sm' onclick="$('#btn_simpan_real').click();return false;" hidden><i class='fa fa-check'></i> Simpan Pembayaran</button>
                 <input type='submit' class='btn btn-info btn-sm' id='btn_simpan_real' value='Simpan Final' onclick="return backNormal('1');" hidden/>
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

var row_awal = "";
var angka = 0;
<?php 

$no = 0;
foreach($list_detail->result_array() as $k){ 

  ?>

    var is_manual = "<?php echo $k['is_manual']?>";

    if ( is_manual == 1 ) {

      addrowmanual("<?php echo $k['tbrid'];?>","<?php echo $k['nama_barang'];?>","<?php echo $k['harga_satuan'];?>","<?php echo $k['qty'];?>","<?php echo $k['stok'];?>","<?php echo $k['nama_satuan_kecil'];?>","<?php echo $k['satuan_kecil'];?>",'<?php echo $k['list_satuan'];?>',"<?php echo $k['konversi_satuan'];?>","<?php echo $k['tsid_stok'];?>","<?php echo $k['tsid']?>","<?php echo $k['harga_jual']?>");  

      $("input[id='diskon["+angka+"]']").val('<?php echo round($k['discount_amount']);?>');
      setDiskonManual(2,document.getElementById('diskon['+angka+']'),angka);

      setnewsatuanmanual(document.getElementById('satuan['+angka+']'),angka);
      $("select[id='satuan_stok["+angka+"]']").val('<?php echo $k['tsid_stok']?>').select2();

    } else {

      addrow("<?php echo $k['tbrid'];?>","<?php echo $k['nama_barang'];?>","<?php echo $k['harga_satuan'];?>","<?php echo $k['qty'];?>","<?php echo $k['stok'];?>","<?php echo $k['nama_satuan_kecil'];?>","<?php echo $k['satuan_kecil'];?>",'<?php echo $k['list_satuan'];?>',"<?php echo $k['konversi_satuan'];?>","<?php echo $k['tsid_stok'];?>","<?php echo $k['tsid']?>","<?php echo $k['harga_jual']?>");  

      $("input[id='diskon["+angka+"]']").val('<?php echo round($k['discount_amount']);?>');
      setDiskon(2,document.getElementById('diskon['+angka+']'),angka);

      setnewsatuan(document.getElementById('satuan['+angka+']'),angka);
      $("select[id='satuan_stok["+angka+"]']").val('<?php echo $k['tsid_stok']?>').select2();
    }
  

     /*addrow(v.tbrid,v.nama_barang,v.harga_jual,v.stok,v.nama_satuan,v.satuan,v.list_satuan,v.konversi_satuan);*/

     angka = angka +1;



<?php 
$no = $no + 1;
}?>


$(document).ready(function(){

  $("#btn_checkout").trigger('click');
  setNominal('total_diskon');
  setNominal('total_bayar_pasien');
  setNominal('ongkir');
  
  $("select[name='nama_pelanggan']").select2();
  $("select[name='tkgid']").select2();

});


</script>