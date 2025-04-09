<script type="text/javascript">
function showDetail(){
    var tpids = $("#tpid").val();
    var ada = 0;
    if ( tpids != '' && tpids != ' ') {


         $.each($("input[name='tpid[]']"),function(k,v){
                if (this.value == tpids) {

                  alert('Project Sudah Ada Di List');
                  ada = 1;
                } else {

                }
            });

        if ( ada === 0 ) {
            var total_all_anggar = document.getElementById('total_all_anggaran').value;
            var total_all_bar = document.getElementById('total_all_barang').value;
            var sisa = parseFloat(backToNormal(document.getElementById('sisa_biaya')));
            $.post("<?php echo base_url('Ajuan/detail_project')?>",{tpids:tpids},function(data){
              
                var new_detail = JSON.parse(data);
                var data_proj = new_detail.detail_project;
                console.log(data_proj.biaya_total_barang);

                addRow(data_proj.tpid,data_proj.nama_project_new,data_proj.sisa_penggunaan_pagu,data_proj.biaya_total_barang);
                sisa = sisa + parseFloat(data_proj.sisa_penggunaan_pagu);
                document.getElementById('sisa_biaya').value = formatRupiah("'"+sisa+"'","Rp. ");


            });


        }


    } else {
        
    }
}

function addAjuan(){
  var tblList     = document.getElementById("detail_pengajuan");
  var tblBody     = tblList.tBodies[0];
  var lastRow     = tblBody.rows.length;
  var row         = tblBody.insertRow(lastRow);  

  row.setAttribute('id','row_ajuan_'+lastRow);

  var newCell = row.insertCell(0);
        newCell.innerHTML = lastRow+1;

    var newCell = row.insertCell(1);
        newCell.align ="center";
        newCell.innerHTML = "<input type='text' name='desc[]' id='desc[]' class='form-control'/>";

        var newCell = row.insertCell(2);
        newCell.align ="center";
        newCell.innerHTML = "<input type='text' name='nom_ajuan[]' id='nom_ajuan["+lastRow+"]' class='form-control' style='text-align:right' onblur='hitungTotal();'/>";

    var newCell = row.insertCell(3);
        newCell.align ="center";
        newCell.innerHTML = "<i class='fa fa-times' style='color:red;cursor:pointer' onclick='hapusPengajuan("+lastRow+");'></i>";


    setNominal("nom_ajuan["+lastRow+"]");
}

function hitungTotal(){

    var total_semua = 0;

    $.each($("input[name='nom_ajuan[]']"),function(k,v){
        if (backToNormal(this) != '' && backToNormal(this) > 0){
            var nominal_row = backToNormal(this);
            total_semua = parseFloat(total_semua) + parseFloat(nominal_row);
        }
    });


    $('#total_ajuan_project').val(formatRupiah("'"+total_semua+"'",'Rp. '));

    hitungSisa();
}

function hitungSisa(){
    var sisabiaya = backToNormal(document.getElementById('sisa_biaya'));
    var ajuanproject = backToNormal(document.getElementById('total_ajuan_project'));

    var sisaall = parseFloat(sisabiaya) - parseFloat(ajuanproject);

    if ( sisaall <= 0 ) {

      alert('Sisa Setelah Ajuan Project Tidak Boleh 0 atau kurang dari 0');
      document.getElementById('sisa_biaya_project').value = formatRupiah('0','Rp. ');

    } else {

      document.getElementById('sisa_biaya_project').value = formatRupiah("'"+sisaall+"'","Rp. ");

    }

}

function addRow(tpid,nama_project,anggaran,biaya_barang){
  var tblList     = document.getElementById("table_project");
  var tblBody     = tblList.tBodies[0];
  var lastRow     = tblBody.rows.length;
  var row         = tblBody.insertRow(lastRow);  


var new_anggaran = formatRupiah("'"+anggaran+"'","Rp. ");
var new_biaya_barang = formatRupiah("'"+biaya_barang+"'","Rp. ");
var sisa_setelah_barang = parseFloat(anggaran) - parseFloat(biaya_barang);

row.setAttribute('id','row_project_'+tpid);


var newCell = row.insertCell(0);
      newCell.innerHTML = "<input type='hidden' name='tpid[]' id='tpid[]' class='form-control' value='"+tpid+"'/><input type='hidden' name='sisa_set_brg[]' id='sisa_set_brg[]' value='"+anggaran+"'/>"+nama_project;

  var newCell = row.insertCell(1);
      newCell.align ="right";
      newCell.innerHTML = "<input type='hidden' name='anggaran[]' id='anggaran["+lastRow+"]' class='form-control' value='"+anggaran+"'/>"+new_anggaran;

      /*var newCell = row.insertCell(2);
      newCell.align ="right";
      newCell.innerHTML = "<input type='hidden' name='biaya_barang[]' id='biaya_barang["+lastRow+"]' class='form-control' value='"+biaya_barang+"'/>"+new_biaya_barang;*/

  var newCell = row.insertCell(2);
      newCell.align ="center";
      newCell.innerHTML = "<i class='fa fa-times' style='color:red;cursor:pointer' onclick='hapusProject("+tpid+","+lastRow+");'></i>";
} 

function hapusPengajuan(id) {

      var nominal = backToNormal(document.getElementById('nom_ajuan['+id+']'));
      var total_ajuan_project = backToNormal(document.getElementById('total_ajuan_project'));
      var pengurang = total_ajuan_project - nominal;
      document.getElementById('total_ajuan_project').value = formatRupiah("'"+pengurang+"'","Rp. ");

      $("#row_ajuan_"+id).remove(); 

      hitungTotal();
}

function hapusProject(tpid,id) {

    var anggar = parseFloat(document.getElementById('anggaran['+id+']').value);
    var sisa_biaya = parseFloat(backToNormal(document.getElementById('sisa_biaya')));
    var total_ajuan_project = parseFloat(backToNormal(document.getElementById('total_ajuan_project')));
    
    var sisa = 0;

    sisa = sisa_biaya - anggar;

    if ( sisa < total_ajuan_project ) {

        alert('Gagal Hapus Project ! - Info : Sisa Setelah Ajuan Kas Akan Minus');

    } else {

      document.getElementById('sisa_biaya').value = formatRupiah("'"+sisa+"'","Rp. ");

      $("#row_project_"+tpid).remove(); 

    }

}

function chkform(){

    var tgl_ajuan  = document.getElementById('tgl_ajuan').value;
    var teid_pengaju  = document.getElementById('teid_pengaju').value;
    var sisa_sbm_ajuan = backToNormal(document.getElementById('sisa_biaya'));
    var total_ajuan_proj = backToNormal(document.getElementById('total_ajuan_project'));
    var sisa_biaya_proj = backToNormal(document.getElementById('sisa_biaya_project'));


    var tpid       = $("input[name='tpid[]']");
    var desc       = $("input[name='desc[]']");
    

    if ( tpid.length > 0 ) {

        var sisa_setelah_barang = $("input[name='sisa_set_brg[]']"); 
        
        var arr_tpid = {};

        $.each(tpid,function(k,v){

          arr_tpid[this.value] = sisa_setelah_barang[k].value;

        });
    }

    console.log(arr_tpid);

    if ( desc.length > 0 ) {

        var arr_ajuan = {};

        var nominal_ajuan = $("input[name='nom_ajuan[]']");

        $.each(desc,function(k,v){

          arr_ajuan[k] = this.value+':'+(backToNormal(nominal_ajuan[k]));

        });
    }

    console.log(arr_ajuan);



    var sisa_biaya = backToNormal(document.getElementById('sisa_biaya'));
    var ajuan_kas  = backToNormal(document.getElementById('total_ajuan_project'));

    if ( (parseFloat(sisa_biaya) - parseFloat(ajuan_kas)) <= 0 ) {

      alert('Sisa Setelah Ajuan Project Tidak Boleh 0 atau kurang dari 0');

    } else {

        if(confirm('Apakah anda yakin akan memproses ajuan ini ?')) {
            $.post("<?php echo base_url('Ajuan/act_add_ajuan');?>",{teid_pengaju:teid_pengaju,tgl_ajuan:tgl_ajuan,sisa_sbm_ajuan:sisa_sbm_ajuan,total_ajuan_proj:total_ajuan_proj,sisa_biaya_proj:sisa_biaya_proj,arrtpid:JSON.stringify(arr_tpid),arrajuan:JSON.stringify(arr_ajuan)},function(data){
                alert('Proses Ajuan Berhasil');
                window.location.replace("<?php echo base_url('Ajuan/list_pengajuan')?>");
            });
        }
    }
}

function cekProject(id) {

    if ( parseInt(id.value) > 0 ) {

        $('#tpid').empty();


        var rows = $("#table_project tbody tr").length;
        var rowsaju = $("#detail_pengajuan tbody tr").length;

        if ( rowsaju > 0 ) {
            var descnya = $("input[name='desc[]']");

            $.each(descnya,function(k,v){
                hapusPengajuan(k);
            });
        }

        if ( rows > 0 ) {
            var tpidnya = $("input[name='tpid[]']");

            $.each(tpidnya,function(k,v){
                hapusProject(this.value,k);
            });
        }

        $.post("<?php echo base_url('Project/list_project_teid')?>",{teid_pelaksana:id.value},function(data){

          var list = JSON.parse(data);
          var list_real = list.detail_data;

          if ( list.detail_data.length > 0 ) {
                $('#tpid').append( new Option(' -- Pilih Project -- ',0) );
            for ( i = 0 ; i < list.detail_data.length ; i++ ) { 
                /*addRow(list_real[i].tpid,list_real[i].nama_project,0,0);*/
                 $('#tpid').append( new Option(list_real[i].nama_project,list_real[i].tpid) );
            }

          }
        });
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
            <h1 class="m-0 text-dark"><i class='fa fa-hands-helping'></i> Ajuan Project</h1>
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
                <div class='col-5' style="float:left">
                    <div class='card'>
                      <div class='card-header'>
                        <h3 class='card-title' style='margin-top:10px'>
                          Pilih Project
                        </h3>
                      </div>
                      <form class='form-horizontal' name='frm' id='frm' method='post' action="#">
                          <div class='card-body'>


                            <div class='form-group-row'>

                              <div class='col' style="float:left">
                                  <div class="col-6" style='float:left'>
                                  <label class="col col-form-label">Tanggal Ajuan</label>
                                    <div class="input-group">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text">
                                          <i class="far fa-calendar-alt"></i>
                                        </span>
                                      </div>
                                     <input type="text" class="form-control float-right" name="tgl_ajuan" id="tgl_ajuan">
                                    </div>
                                  </div>
                                  <div class="col-6"  style='float:left'>
                                  <label class="col col-form-label">Pengaju</label>
                                       <select class='form-control' name='teid_pengaju' id='teid_pengaju' onchange="cekProject(this)">
                                          <option></option>
                                           <?php 
                                                foreach ($list_pelaksana->result_array() as $z) {
                                                  echo "<option value='".$z['teid']."'>".$z['nama_karyawan']."</option>";
                                                }
                                           ?>
                                       </select>
                                  </div>
                              </div>
                          </div>

                          <div class='form-group-row'>
                                <div class='col' style="float:left">
                                  
                                   <label class="col col-form-label">Project</label>
                                    <div class="col">
                                      <select name='tpid' id='tpid' class='form-control' required="" onchange="showDetail()">
                                        <option></option>
                                       
                                      </select>
                                    </div>

                              </div>
                          </div>

                          <div class='form-group-row'>
                            
                          </div>

                          </div>
                         <!-- <div class='card-footer'>
                         <input type='button' class='btn btn-info' value='Proses' onclick="showDetail();"/>
                        </div> -->
                       </form>
                      </div>
                  </div>

                  <div class='col-7' id='detail_proj' style='float:left'>
                    <div class='card' style="height: 248px">
                      <form class='form-horizontal' name='frm' id='frm' method='post' action="<?php echo base_url('project/act_add_ajuan'); ?>" style='overflow-y: auto'>
                          <div class='card-body'>                         
                              <table width='100%' id='table_project' class='table table-bordered'>
                                  <thead>
                                    <tr>
                                      <th width='69%'>Nama Project</th>
                                      <th width="30%">Sisa Real Cost</th>
                                      <th width='1%'>&nbsp;</th>
                                    </tr>
                                  </thead>
                                  <tbody></tbody>
                              </table>
                          </div>
                       </form>
                      </div>
                  </div>

            </div> 

            <div class='col-12' hidden>
              <div class='col'>
                <div class='card'>
                      <div class='card-body'>
                        <div class='col-6' style='float:left'>
                          <label>Total Nilai Kontrak</label>
                          <input type='text' class='form-control' value="Rp. 0" id="total_nilai_kontraknya" style='text-align: right' readonly=""/>
                        </div>
                        <div class='col-6' style='float:left'>
                          <label>Total Biaya Barang</label>
                          <input type='text' class='form-control' value="Rp. 0" id="total_nilai_barangnya"  style='text-align: right'readonly=""/>
                        </div>
                       <!--  <div class='col-4' style='float:left'>
                          <label>Sisa</label>
                          <input type='text' class='form-control' id='sisa_biaya' readonly="" value="Rp. 0" />
                        </div> -->
                      </div>
                    </div>
                </div>
              
            </div>

            <div class='col-12' id='detail_aju'>  

              <div class='col-8' style="float:left">
                    <div class='card'>
                      <div class='card-header'>
                        <h3 class='card-title' style="margin-top:5px">
                          Ajuan Kas
                        </h3>
                        <span style='float:right'>
                          <button class='btn-sm btn-warning' onclick="addAjuan()"><i class='fa fa-plus'></i> Tambah Baris</button>
                        </span>
                      </div>
                      <form class='form-horizontal' name='frm' id='frm' method='post' action="#">
                          <input type='hidden' id='total_all_anggaran' value='0'>
                          <input type='hidden' id='total_all_barang' value='0'>
                          <div class='card-body'>
                            <div class='form-group-row'>
                                  <table class='table table-bordered' id='detail_pengajuan'>
                                    <thead>
                                      <tr>
                                        <td width='1%'>No</td>
                                        <td>Deskripsi</td>
                                        <td width='35%'>Nominal</td>
                                        <td width='1%'>&nbsp;</td>
                                      </tr>
                                    </thead>
                                    <tbody></tbody>
                                  </table>
                            </div>                          
                          </div>
                       </form>
                      </div>
                  </div>

                  <div class='col-4' style="float:left">
                    <div class='card'>
                      <div class='card-header'>
                        <h3 class='card-title'>
                          List Biaya
                        </h3>
                      </div>
                      <form class='form-horizontal' name='frm' id='frm' method='post' action="#">
                          <div class='card-body'>
                             <div class='col'>
                               <label>Sisa Sebelum Ajuan Kas Project</label>
                                  <input type='text' class='form-control' id='sisa_biaya' readonly="" value="Rp. 0"  style="text-align: right" />
                             </div>
                             <div class='col'>
                               <label>Total Ajuan Kas Project</label>
                                  <input type='text' class='form-control' id='total_ajuan_project' readonly="" value="Rp. 0"  style="text-align: right" />
                             </div>
                             <div class='col'>
                               <label>Sisa Setelah Ajuan Kas Project</label>
                                  <input type='text' class='form-control' id='sisa_biaya_project' readonly="" value="Rp. 0"  style="text-align: right" />
                             </div>
                          </div>
                         <div class='card-footer'>
                         <input type='button' class='btn btn-primary' value='Proses Ajuan' onclick="chkform();"/>
                        </div>
                       </form>
                      </div>
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
  $(function() {
    $('#tgl_ajuan').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true
    }, 
    function(start, end, label) {
        var years = moment().diff(start, 'years');
        
    });
});



</script>