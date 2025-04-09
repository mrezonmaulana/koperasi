<script type="text/javascript">

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

function cekFaktur(id,from_data) {
  var faktur = document.getElementById('no_faktur['+id+']').value;

  if (faktur == 0 || faktur == '' || faktur == ' ') {
      document.getElementById('jumlah['+id+']').value = 0;
      document.getElementById('harga_satuan['+id+']').value = 0;
  } else {

  }
  hitungSubtotal(id,from_data);
}

function cetakBarang(tpid,no_faktur) {
    var id = tpid+'_'+no_faktur;

    NewWindow("<?php echo base_url('Laporan/view_bon_barang');?>/"+id,"","450","500","yes");
}

function addrow(tbrid,nama_barang,tgl_kirim='',no_faktur='',tkid='',jumlah=0,satuan=0,harga_satuan=0,total=0,from_data='f',is_print='f',tpbid=0){
  var tblList     = document.getElementById("table_barang");
  var tblBody     = tblList.tBodies[0];
  var lastRow     = tblBody.rows.length;
  var row         = tblBody.insertRow(lastRow);  
  var tpid        = document.getElementById('tpid').value;

  var sudah_bayar = '<?php echo $list_project->tpid_bayar?>';

 row.setAttribute('id','row_'+lastRow);
 var newCell = row.insertCell(0);
      newCell.align ="center";
      newCell.innerHTML = "<input type='hidden' name='from_data[]' id='from_data[]' value='"+from_data+"'/><input type='text' name='tgl_kirim[]' id='tgl_kirim["+lastRow+"]' data-type='tgl' class='form-control' value='"+tgl_kirim+"'/>";

var newCell = row.insertCell(1);
      newCell.align ="center";
      newCell.innerHTML = "<input type='text' name='no_faktur[]' id='no_faktur["+lastRow+"]' class='form-control' value='"+no_faktur+"' onblur=\"cekFaktur("+lastRow+",\'"+from_data+"\');\"/>";

  var newCell = row.insertCell(2);
      newCell.align ="center";
      newCell.innerHTML = "<input type='hidden' name='tbrid[]' id='tbrid[]' class='form-control' value='"+tbrid+"'/>"+nama_barang;

  var newCell = row.insertCell(3);
      newCell.align ="center";
      newCell.innerHTML = "<select name='pengirim[]' id='pengirim["+lastRow+"]' class='form-control'><option></option><?php foreach($list_truck->result_array() as $a){ echo '<option value=\''.$a['tkid'].'\'>'.$a['no_polisi'].'</option>'; }?></select>";

  var newCell = row.insertCell(4);
      newCell.align ="center";
      newCell.innerHTML = "<input type='text' name='jumlah[]' id='jumlah["+lastRow+"]' value='"+jumlah+"' class='form-control' onblur=\"hitungSubtotal("+lastRow+",\'"+from_data+"\');\"/>";

   var newCell = row.insertCell(5);
      newCell.align ="center";
      newCell.innerHTML = "<select name='satuan[]' id='satuan["+lastRow+"]' class='form-control'><option></option><?php foreach($list_satuan->result_array() as $b){ echo '<option value=\''.$b['tsid'].'\'>'.$b['satuan'].'</option>'; }?></select>";

    var newCell = row.insertCell(6);
      newCell.align ="center";
      newCell.innerHTML = "<input type='text' name='harga_satuan[]' id='harga_satuan["+lastRow+"]' value='"+harga_satuan+"' class='form-control' style='text-align:right' onblur=\"hitungSubtotal("+lastRow+",\'"+from_data+"\');\"/>";

    var newCell = row.insertCell(7);
      newCell.align ="center";
      newCell.innerHTML = "<input type='text' name='subtot[]' id='subtot["+lastRow+"]' value='"+total+"' class='form-control' style='text-align:right' readonly/>";
      newCell.innerHTML += "<input type='hidden' name='subtot_orig[]' id='subtot_orig["+lastRow+"]' value='"+total+"'/>";


    var newCell = row.insertCell(8);
    newCell.align="center";
    if ( is_print == 't' ) {

      var newtgl = tgl_kirim.replace(/\//g,'-');

      newCell.innerHTML = "<i class='fa fa-print' title='Cetak Bon Barang ( No Faktur. "+no_faktur+" Tanggal "+tgl_kirim+" )' style='cursor:pointer' onclick=\"cetakBarang("+tpid+",\'"+no_faktur+":"+newtgl+"\');\"></i> ";

      if ( sudah_bayar == 0 ) {
        newCell.innerHTML +="<i class='fa fa-times' style='color:red;cursor:pointer' title='Hapus Item' onclick=\"removeItem("+lastRow+",\'"+from_data+"\',"+tpbid+");\"></i>";
      }
    } else{
      newCell.innerHTML = "<i class='fa fa-times' style='color:red;cursor:pointer' title='Hapus Item' onclick=\"removeItem("+lastRow+",\'"+from_data+"\',"+tpbid+");\"></i>";
    }

      setNominal("harga_satuan["+lastRow+"]");
      getDate(lastRow)

      if ( from_data == 't' ) {

          setNominalStatic("harga_satuan["+lastRow+"]");

          if ( tkid != '' ) {
            setPengirim(lastRow,tkid);
          }

          if ( satuan != '' ) {
            setSatuan(lastRow,satuan);
          }

          hitungSubtotal(lastRow,"'"+from_data+"'");
      } 

} 

function removeItem(id,from_data,id_data){
      
      document.getElementById('jumlah['+id+']').value = 0;
      document.getElementById('harga_satuan['+id+']').value = 0;
      hitungSubtotal(id,from_data);
      $("#row_"+id).remove();

      if ( from_data == 't' ) {
         return cekValid();
      }
}

function setPengirim(id,tkid) {
    $("select[id='pengirim["+id+"]']").attr('selected','selected').val(tkid);
}

function setSatuan(id,satuan) {
    $("select[id='satuan["+id+"]']").attr('selected','selected').val(satuan);
}

function hitungSubtotal(id,from_data) {

    var nominal = backToNormal(document.getElementById('harga_satuan['+id+']'));
    var qty     = document.getElementById('jumlah['+id+']').value;
    var total   = nominal*qty;
    document.getElementById('subtot['+id+']').value = formatRupiah("'"+total+"'",'Rp. ');
    var total_orig = parseFloat(document.getElementById('subtot_orig['+id+']').value);
    var sisa_pagu = parseFloat(document.getElementById('sisa_guna_pagu').value);

    var sisa_asli = total_orig - total;
    document.getElementById('subtot_orig['+id+']').value = total;
    sisa_pagu = sisa_pagu + sisa_asli;

    document.getElementById('sisa_guna_pagu').value = sisa_pagu;
    document.getElementById('sisa_pagu').innerHTML = formatRupiah("'"+sisa_pagu+"'","Rp. ");

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

function backNormal(){
    var harga_satuan = $("input[name='harga_satuan[]']");
    var subtot = $("input[name='subtot[]']");

    $.each(harga_satuan,function(k,v){
       checkNom(this);
    });

    $.each(subtot,function(k,v){
       checkNom(this);
    });
}

function cekValid(){

    var total_harga = backToNormal(document.getElementById('total'));
    var nilai_kontrak = document.getElementById('nilai_kontrak').value;
    if ( parseFloat(total_harga) > parseFloat(nilai_kontrak) ) {
         toastr.error(' Biaya total barang melebihi nilai kontrak. ');
         return false;
    }

    var no_fak = $("input[name='no_faktur[]']");
    var ada_error = 0;
    if ( no_fak.length > 0 ) {
        $.each(no_fak,function(k,v){
          if ( this.value == '' ) {
             toastr.error(' No Faktur Tidak Boleh Kosong. ');
             ada_error = 1;
          }
        });
    }
    
    if ( ada_error == 1 ) {

        return false;
    }

    document.frm.submit();
}

function checkList(tbrid) {
        /*var c = $("input[name='tbrid[]']").length;
        
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
        }*/
}
</script>


<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/autocomplete.css">

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
                  Detail Project
                </h3>
              </div>
              <form class='form-horizontal' name='frm' id='frm' method='post' action="<?php echo base_url('project/act_add_barang_project'); ?>">
                <input type="hidden" name="tpid" id="tpid" value="<?php echo $list_project->tpid; ?>"/>
                  <div class='card-body'>


                    <div class='form-group-row'>

                      <div class='col-6' style="float:left">
                          <label class="col col-form-label">Tanggal Mulai</label>
                          <div class="col">
                            <div class="input-group">
                             
                               <?php echo date('d F Y',strtotime($list_project->tgl_mulai));?>
                            </div>
                          </div>
                      </div>

                      <div class='col-6' style="float:left">
                          
                           <label class="col col-form-label">Bidang</label>
                            <div class="col">
                              <?php echo $list_project->nama_bidang; ?>
                            </div>

                      </div>
                  </div>

                  <div class='form-group-row'>
                           <div class='col-4' style="float:left">

                                <label for="inputEmail3" class="col col-form-label">Pelaksana</label>
                                <div class="col">
                                  <?php echo $list_project->pelaksana?>
                                </div>
                           </div>

                           <div class='col-4' style="float:left">

                                <label for="inputEmail3" class="col col-form-label">Kepala Tukang</label>
                                <div class="col">
                                  <?php echo $list_project->kepala_tukang; ?>
                                </div>
                           </div>

                           <div class='col-4' style="float:left">
                                <label for="inputEmail3" class="col col-form-label">Nama Project</label>
                                <div class="col">
                                  <?php echo $list_project->nama_project; ?>
                                </div>
                           </div>
                  </div>

                  <div class='form-group-row'>
                        <div class='col-3' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">Kode Pos</label>
                                <div class="col">
                                  <?php echo $list_project->kode_pos;?>
                                </div>

                        </div>

                        <div class='col-3' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">Kelurahan</label>
                                <div class="col">
                                  <?php echo $list_project->kelurahan;?>
                                </div>

                        </div>

                         <div class='col-3' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">Kecamatan</label>
                                <div class="col">
                                  <?php echo $list_project->kecamatan;?>
                                </div>

                        </div>

                         <div class='col-3' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">Kabupaten</label>
                                <div class="col">
                                  <?php echo $list_project->kabupaten;?>
                                </div>

                        </div>

                  </div>

                  <div class='form-group-row'>
                    <div class='col-3' style="float:left">

                        <label for="inputEmail3" class="col col-form-label">Nilai Kontrak</label>
                            <div class="col">
                              <font id='rupiah'><?php echo $list_project->anggaran; ?></font>
                              <input type='hidden' id='nilai_kontrak' value='<?php echo $list_project->anggaran; ?>'/>
                            </div>

                    </div>

                    <div class='col-3' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">Real Cost</label>
                                <div class="col">
                                 <font id='pagu'><?php echo $list_project->pagu; ?></font>
                                </div>

                        </div>


                        <div class='col-3' style="float:left">

                        <label for="inputEmail3" class="col col-form-label">Sisa Penggunaan Real Cost</label>
                            <div class="col">
                              <font id='sisa_pagu'></font>
                              <input type='hidden' name='sisa_guna_pagu' id='sisa_guna_pagu' value='<?php echo $list_project->sisa_penggunaan_pagu; ?>'/>
                            </div>

                    </div>

                    <div class='col-3' style="float:left">

                        <label for="inputEmail3" class="col col-form-label">Keterangan</label>
                            <div class="col">
                              <?php echo $list_project->keterangan; ?>
                            </div>

                    </div>


                           
                  </div>

                  <div class='form-group-row'>
                        <div class='col-12' style="float:left">

                            <label for="inputEmail3" class="col col-form-label">Data Barang
                                <span style='float:right;font-size:9pt;margin-top:4px'><i>* Note : Jika ingin menghapus barang , isi <b style='color:red'>No Faktur</b> dengan '0' atau biarkan tidak terisi</i></span>
                                </label>
                                <div class="col">
                                    <table class='table table-bordered' id='table_barang'>
                                      <thead>
                                        <tr>
                                          <th width='14%'>Tgl Kirim</th>
                                          <th width='9%'>No. Faktur</th>
                                          <th width='15%'>Nama Barang</th>
                                          <th width='15%'>Pengirim</th>
                                          <th width='5%'>Jumlah</th>
                                          <th width='10%'>Satuan</th>
                                          <th width='15%'>Harga Satuan</th>
                                          <th width='16%'>Sub Total</th>
                                          <th width='1%'>
                                            <?php if ($list_project->biaya_total_barang > 0 ) {

                                              echo "<a title='Cetak Semua' style='cursor:pointer' onclick=\"cetakBarang(".$list_project->tpid.",0);\"><i class='fa fa-print'></i></a>";

                                            } ?>
                                          </th>
                                        </tr>
                                      </thead>
                                      <tbody></tbody>
                                      <tfoot>
                                        <tr>
                                          <td colspan='6' style="text-align: right">Total</td>
                                          <td colspan='2'><input type='text' name='total' id='total' class='form-control' value='0' style='text-align:right' readonly /></td>
                                        </tr>
                                      </tfoot>
                                    </table>
                                </div>

                        </div>
                  </div>

                  </div>
                 <div class='card-footer'>
                  <?php if ( $list_project->tpid_bayar == 0 ) { ?>
                 <input type='button' class='btn btn-info' value='Simpan' onclick="backNormal();return cekValid();"/>
                 <span style='float:right'>
                                  <!-- <button class='btn btn-warning' onclick="addrow();"><i class='fa fa-plus'></i> Tambah Barang</button> -->
                                  <input type='button' value="Tambah Barang" id='cek_barang' onclick="$('#popup_barang').click();" class='btn btn-danger' />
                                  <input type='button' value="Tambah Barang" id='popup_barang'  data-toggle="modal" data-target="#modal-default"  hidden/>
                                </span>
                  <?php } else { 
                      echo "<font style='color:red'><i>Tidak bisa tambah barang atau edit barang, karena pembayaran project sudah dibuat </i></font> ";
                  } ?>
                </div>
               </form>
              </div>
            </div>
            
          </div>
        
        </div>
        
        <div class="row">
        
        </div>
        
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
<?php foreach($list_project_barang->result_array() as $k){ ?>
    var is_print = 'f';
    if ( row_awal != "<?php echo $k['no_faktur'].':'.$k['tgl_kirim']; ?>" ) {
       is_print = 't';
    }
    addrow("<?php echo $k['tbrid'] ;?>","<?php echo $k['nama_barang'] ;?>","<?php echo date('m/d/Y',strtotime($k['tgl_kirim']));?>","<?php echo $k['no_faktur'] ;?>","<?php echo $k['tkid'] ;?>","<?php echo $k['vol'] ;?>","<?php echo $k['satuan'] ;?>","<?php echo $k['harga_satuan'] ;?>","<?php echo $k['total'] ;?>",'t',is_print,"<?php echo $k['tpbid'];?>");

    row_awal = "<?php echo $k['no_faktur'].':'.$k['tgl_kirim']; ?>";

<?php }?>

function getDate(id) {
  
  $("input[data-type='tgl']").daterangepicker({
        singleDatePicker: true,
        showDropdowns: true
    }, 
    function(start, end, label) {
        var years = moment().diff(start, 'years');
        
    });
}

  $(document).ready(function(){



});
</script>

<script type="text/javascript">
document.getElementById('rupiah').innerHTML = formatRupiah("<?php echo $list_project->anggaran?>",'Rp. ') ;
document.getElementById('pagu').innerHTML = formatRupiah("<?php echo $list_project->pagu?>",'Rp. ') ;
document.getElementById('sisa_pagu').innerHTML = formatRupiah("<?php echo $list_project->sisa_penggunaan_pagu?>",'Rp. ') ;


const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
});
</script>