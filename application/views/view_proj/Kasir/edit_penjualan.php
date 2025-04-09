<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/autocomplete.css">
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

function addrow(tbrid,nama_barang,satuan,hna,nama_satuan,jumlah=0){
  var tblList     = document.getElementById("table_barang");
  var tblBody     = tblList.tBodies[0];
  var lastRow     = tblBody.rows.length;
  var row         = tblBody.insertRow(lastRow);

  var new_hna = formatRupiah("'"+hna+"'",'Rp. ');

  var newCell = row.insertCell(0);
      newCell.align ="center";
      newCell.innerHTML = lastRow+1;

  var newCell = row.insertCell(1);
      newCell.align ="center";
      newCell.innerHTML = "<input type='hidden' name='tbrid[]' id='tbrid[]' class='form-control' value='"+tbrid+"'/>"+nama_barang;


  var newCell = row.insertCell(2);
      newCell.align ="center";
      newCell.innerHTML = "<input type='text' name='jumlah[]' id='jumlah["+lastRow+"]' value='"+jumlah+"' class='form-control' style='text-align:center' onblur='hitungSubtotal("+lastRow+");'/>";

   var newCell = row.insertCell(3);
      newCell.align ="center";
      newCell.innerHTML = "<input type='hidden' name='satuan[]' id='satuan[]' value='"+satuan+"'/>"+nama_satuan;

    var newCell = row.insertCell(4);
      newCell.align ="center";
      newCell.innerHTML = "<input type='text' name='harga_satuan[]' id='harga_satuan["+lastRow+"]' value='"+new_hna+"' class='form-control' readonly='readonly' style='text-align:right'/>";

    var newCell = row.insertCell(5);
      newCell.align ="center";
      newCell.innerHTML = "<input type='text' name='subtot[]' id='subtot["+lastRow+"]' class='form-control' style='text-align:right' readonly/>";

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

    $.each(harga_satuan,function(k,v){
       checkNom(this);
    });

    $.each(subtot,function(k,v){
       checkNom(this);
    });
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
            <h1 class="m-0 text-dark"><i class='fa fa-upload'></i> Pengajuan Barang</h1>
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
                  Edit Pengajuan
                </h3>
              </div>
              <form class='form-horizontal' name='frm' id='frm' method='post' action="<?php echo base_url('transaksi/act_add_penjualan'); ?>">
                <input type='text' name='ttbid' id='ttbid' value='<?php echo $header->ttbid;?>'/>
                  <div class='card-body'>


                    <div class='form-group-row'>

                      <div class='col-3' style="float:left">
                          <label class="col col-form-label">Tanggal Pengajuan</label>
                          <div class="col">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text">
                                  <i class="far fa-calendar-alt"></i>
                                </span>
                              </div>
                             <input type="text" class="form-control float-right" name="tgl_trans" id="reservation"  readonly="readonly" value="<?php echo date('m/d/Y',strtotime($header->tgl_trans))?>">
                            </div>
                          </div>
                      </div>

                     <div class='col-3' style="float:left">

                           <label for="inputEmail3" class="col col-form-label">Kategori Barang</label>
                                
                            <select name='tkgid' id='tkgid' class='form-control' onchange="clearListOrder();" required="" style="pointer-events: none">
                              <option></option>
                              <?php 
                                foreach ($list_kategori->result_array() as $key) {

                                  if ( $key['tkgid'] == $header->tkgid) {
                                  echo "<option value='".$key['tkgid']."' selected='selected'>".$key['nama_kategori']."</option>";

                                  }else{

                                  echo "<option value='".$key['tkgid']."'>".$key['nama_kategori']."</option>";
                                  }
                                }
                              ?>
                            </select>
                              

                      </div>


                      <div class='col-6' style="float:left">

                           <label for="inputEmail3" class="col col-form-label">Supplier</label>
                                
                            <select name='tspid' id='tspid' class='form-control' required="">
                              <option></option>
                              <?php 
                                foreach ($list_supplier->result_array() as $key) {
                                  if ( $key['tspid'] == $header->tspid){
                                  echo "<option value='".$key['tspid']."' selected='selected'>".$key['nama_supplier']."</option>";

                                  }else{

                                  echo "<option value='".$key['tspid']."'>".$key['nama_supplier']."</option>";
                                  }
                                }
                              ?>
                            </select>
                              

                      </div>
                      
                      <div class='col' style="float:left">
                            <label for="inputEmail3" class="col col-form-label">Keterangan</label>
                                <input type="text" class="form-control" name='keterangan' id='keterangan' value='<?php echo $header->keterangan; ?>' required="">
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
                                          <td colspan='5' style="text-align: right">Total</td>
                                          <td><input type='text' name='total' id='total' class='form-control' value='0' style='text-align:right' readonly /></td>
                                        </tr>
                                      </tfoot>
                                    </table>
                                </div>

                        </div>
                  </div>
                  </div>
                 <div class='card-footer'>
                 <input type='submit' class='btn btn-info' value='Simpan' onclick="backNormal()"/>
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
<?php foreach($detail->result_array() as $k){ ?>
    

    addrow("<?php echo $k['tbrid'];?>","<?php echo $k['nama_barang'];?>","<?php echo $k['tsid'];?>","<?php echo $k['harga_satuan'];?>","<?php echo $k['satuan'];?>","<?php echo $k['vol'];?>");

<?php }?>

</script>