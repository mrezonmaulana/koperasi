function setNominal(obj) {
  var rupiah = document.getElementById(obj);
    rupiah.addEventListener('keyup', function(e){
      // tambahkan 'Rp.' pada saat form di ketik
      // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
      rupiah.value = formatRupiah(this.value, 'Rp. ');
    });
 
}

function setNominalStatic(obj){
  var rupiah = document.getElementById(obj);
  rupiah.value = formatRupiah(rupiah.value, 'Rp. ');
}

function formatRupiahNew(angka,prefix){
   var curr = new Intl.NumberFormat(['ban', 'id']).format(angka);

   return curr;
}

function formatRupiah(angka, prefix){

      /*var new_angka = angka.replace(/["']/g, "");
      new_angka     = Math.round(new_angka);
      angka = "'"+new_angka+"'";*/

      var number_string = angka.replace(/[^,\d]/g, '').toString();

      var split       = number_string.split(',');
      var sisa        = split[0].length % 3;
      var rupiah        = split[0].substr(0, sisa);
      var ribuan        = split[0].substr(sisa).match(/\d{3}/gi);
 
      // tambahkan titik jika yang di input sudah menjadi angka ribuan
      if(ribuan){

        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
        
      }
 
      rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
      return prefix == undefined ? rupiah : (rupiah ? prefix + rupiah : '');
    }

 function checkNom(obj){
      var nominalnya = obj.value;
      nominalnya = nominalnya.replace('Rp. ','');
      nominalnya = nominalnya.replace(/\./g,'');
      obj.value = nominalnya;
  }

  function backToNormal(obj){
      var nominalnya = obj.value;
      nominalnya = nominalnya.replace('Rp. ','');
      nominalnya = nominalnya.replace(/\./g,'');
      
      return nominalnya;
  }