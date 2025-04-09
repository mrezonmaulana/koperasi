<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/autocomplete.css">
<script type="text/javascript">
function cekNominalnya(){

    var nominal = backToNormal(document.getElementById('rupiah'));

    if ( (nominal == '' && nominal==' ') || nominal == 0 ) {
        
          alert('Lengkapi data diatas sebelum tambah barang');

    } else {

          $('#popup_barang').click();
    }
}
function chooseBarang(){

    $.each($('.pil_brg'),function(k,v){
        if ( this.checked == true ) {
            var nama_bar = document.getElementById("nama_brg["+this.value+"]").value;
            addrow(this.value,nama_bar);
        }
    });


    $.each($('.pil_brg'),function(k,v){
        if ( this.checked == true ) {
            this.checked = false;
        }
    });


    $("#tutup_modal").click();
}
function addrow(tbrid,nama_barang){
  var tblList     = document.getElementById("table_barang");
  var tblBody     = tblList.tBodies[0];
  var lastRow     = tblBody.rows.length;
  var row         = tblBody.insertRow(lastRow);

  var newCell = row.insertCell(0);
      newCell.align ="center";
      newCell.innerHTML = lastRow+1;

  var newCell = row.insertCell(1);
      newCell.align ="center";
      newCell.innerHTML = "<input type='hidden' name='tbrid[]' id='tbrid[]' class='form-control' value='"+tbrid+"'/>"+nama_barang;

  var newCell = row.insertCell(2);
      newCell.align ="center";
      newCell.innerHTML = "<select name='pengirim[]' id='pengirim[]' class='form-control'><option></option><?php foreach($list_truck->result_array() as $a){ echo '<option>'.$a['no_polisi'].'</option>'; }?></select>";

  var newCell = row.insertCell(3);
      newCell.align ="center";
      newCell.innerHTML = "<input type='text' name='jumlah[]' id='jumlah["+lastRow+"]' class='form-control' />";

   var newCell = row.insertCell(4);
      newCell.align ="center";
      newCell.innerHTML = "<select name='satuan[]' id='satuan[]' class='form-control'><option></option><?php foreach($list_satuan->result_array() as $b){ echo '<option>'.$b['satuan'].'</option>'; }?></select>";

    var newCell = row.insertCell(5);
      newCell.align ="center";
      newCell.innerHTML = "<input type='text' name='harga_satuan[]' id='harga_satuan["+lastRow+"]' value='0' class='form-control' style='text-align:right' onblur='hitungSubtotal("+lastRow+");'/>";

    var newCell = row.insertCell(6);
      newCell.align ="center";
      newCell.innerHTML = "<input type='text' name='subtot[]' id='subtot["+lastRow+"]' class='form-control' style='text-align:right' readonly/>";

      setNominal("harga_satuan["+lastRow+"]");
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
                        alert('hahaha');
                        document.getElementById('choose['+tbrid+']').checked = true;
                        return true;
                    }


                
            });
        }
}

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
                  Tambah Project
                </h3>
              </div>
              <form class='form-horizontal' name='frm' id='frm' method='post' action="<?php echo base_url('project/act_add_project'); ?>">
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
                             <input type="text" class="form-control float-right" name="tgl_mulai" id="reservation">
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
                                    echo "<option value='".$k['tbid']."'>".$k['nama_bidang']."</option>";
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
                                    echo "<option value='".$k['tphid']."'>".$k['nama_perusahaan']."</option>";
                                  }
                                ?>
                              </select>
                            </div>

                        </div>

                       <div class='col-3' style="float:left">
                          
                           <label class="col col-form-label">Direktur Perusahaan</label>
                            <div class="col">
                              <input type='hidden' name='teid_kepala' id='teid_kepala'/>
                              <input type='text' id='teid_kepala_text' readonly="" class='form-control' />
                              
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
                                        echo "<option value='".$l['teid']."'>".$l['nama_karyawan']."</option>";
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
                                        echo "<option value='".$j['teid']."'>".$j['nama_karyawan']."</option>";
                                      }
                                    ?>
                                  </select>
                                </div>
                           </div>

                           <div class='col-4' style="float:left">
                                <label for="inputEmail3" class="col col-form-label">Nama Project</label>
                                <div class="col">
                                  <input type="text" class="form-control" name='nama_project' id='nama_project' required="">
                                </div>
                           </div>

                           <div class='col-4' style="float:left">
                                <label for="inputEmail3" class="col col-form-label">Alamat</label>
                                <div class="col">
                                  <input type="text" class="form-control" name='alamat' id='alamat' required="">
                                </div>
                           </div>
                  </div> 

                  <div class='form-group-row'>
                        <div class='col-3' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">Kode Pos</label>
                                <div class="col">
                                  <input type="text" class="form-control" name='kode_pos' id='kode_pos'>
                                </div>

                        </div>

                        <div class='col-3' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">Kelurahan</label>
                                <div class="col">
                                  <input type="text" class="form-control" name='kelurahan' id='kelurahan' readonly="">
                                </div>

                        </div>

                         <div class='col-3' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">Kecamatan</label>
                                <div class="col">
                                  <input type="text" class="form-control" name='kecamatan' id='kecamatan' readonly="">
                                </div>

                        </div>

                         <div class='col-3' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">Kabupaten</label>
                                <div class="col">
                                  <input type="text" class="form-control" name='kabupaten' id='kabupaten' readonly="">
                                </div>

                        </div>

                  </div>

                  <div class='form-group-row'>
                        <div class='col-3' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">Nilai Kontrak</label>
                                <div class="col">
                                  <input type="text" class="form-control" name='anggaran' id='rupiah' onkeyup="setNominal('rupiah');" onblur="setPagu();" required="">
                                </div>

                        </div>

                        <div class='col-3' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">Real Cost</label>
                                <div class="col">
                                  <input type="text" class="form-control" name='pagu' id='pagu'>
                                </div>

                        </div>

                        <div class='col-2' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">Potongan Lain-lain</label>
                                <div class="col">
                                  <select name='potongan_lain' id='potongan_lain' class='form-control'>
                                    <option></option>
                                    <option value='10'>10%</option>
                                    <option value='15'>15%</option>
                                    <option value='20'>20%</option>
                                  </select>
                                </div>

                        </div>

                        <div class='col-4' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">Keterangan</label>
                                <div class="col">
                                  <input type="text" class="form-control" name='keterangan' id='keterangan'>
                                </div>

                        </div>
                  </div>


                  <div class='form-group-row' hidden>
                        <div class='col-12' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">Data Barang</label>
                                <div class="col">
                                    <table class='table table-bordered' id='table_barang'>
                                      <thead>
                                        <tr>
                                          <th width='1%'>No</th>
                                          <th>Nama Barang</th>
                                          <th width='15%'>Pengirim</th>
                                          <th width='10%'>Jumlah</th>
                                          <th width='15%'>Satuan</th>
                                          <th width='15%'>Harga Satuan</th>
                                          <th width='15%'>Sub Total</th>
                                        </tr>
                                      </thead>
                                      <tbody></tbody>
                                      <tfoot>
                                        <tr>
                                          <td colspan='6' style="text-align: right">Total</td>
                                          <td><input type='text' name='total' id='total' class='form-control' value='0' style='text-align:right' readonly /></td>
                                        </tr>
                                      </tfoot>
                                    </table>
                                </div>

                        </div>
                  </div>
                  </div>
                 <div class='card-footer'>
                 <input type='submit' class='btn btn-info' value='Simpan' onclick="checkNom(document.getElementById('rupiah'));checkNom(document.getElementById('pagu'));"/>
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
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">List Barang</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
               <table class='table table-sm table-bordered'>
                 <thead>
                   <tr>
                     <th style='text-align:center'>No</th>
                     <th style='text-align:center'>Nama Barang</th>
                     <th style='text-align:center'>Pilih</th>
                   </tr>
                 </thead>
                 <tbody>
                      <?php 
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
                      ?>
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