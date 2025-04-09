<script type="text/javascript">
function editKonfigurasi(tcid){
  $.post("<?php echo base_url('Masterdata/detailKonfig')?>",{tcid:tcid},function(datares){

          var newRes = JSON.parse(datares);
          $("input[name='data_tcid']").val(newRes[0].tcid);
          $("[data-db='keterangan']").html(newRes[0].keterangan);
          $("[data-db='tipe_data']").html(newRes[0].tipe_data);
          $("[data-db='data']").val(newRes[0].data);

          $("button#realEditKonfigurasi_"+tcid).click();
      });
}

function addCOA(id){
  var getCoatid = $("input[name='jenis_coatid']").val();
  $("input[name='coacode']").val('');
  $("input[name='coaname']").val('');
  $("input[name='data_coaid']").val('0');
  $("select[name='coatid']").val(getCoatid);
  $("select[name='tcmid']").val('');
  $("button#tambahCOAReal").click();
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
    
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="row">
          <div class="col-12">
            <div class='card'>
              <div class='card-header'>
                <h3 class='card-title' style=''>
                  Chart Of Account
                </h3>
                <div style="float:right">
                    <a href='#' class='btn btn-primary btn-sm' title='Tambah Chart Of Account' onclick='addCOA(0);return false;'><i class='fas fa-plus'></i> Tambah COA</a>
                    <button type='button' data-toggle='modal' data-target='#formAddCOA' id='tambahCOAReal' style='display:none'><i class='fas fa-plus'></i>Tambah COA</button>
                </div>
              </div>
              <form method="post" name="frm_cari" id="frm_cari" action="<?php echo base_url('Masterdata/konfig')?>">
                <div class='card-body'>
                  <input type="hidden" name="jenis_coatid" value="1"/>
                  <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                  <li class="nav-item">
                  <a class="nav-link active" id="custom-content-above-aset-tab" data-toggle="pill" href="#custom-content-above-aset" role="tab" aria-controls="custom-content-above-aset" aria-selected="true" onclick="reloadDataCoa(1);">Aset</a>
                  </li>
                  <li class="nav-item">
                  <a class="nav-link" id="custom-content-above-liabilitas-tab" data-toggle="pill" href="#custom-content-above-liabilitas" role="tab" aria-controls="custom-content-above-liabilitas" aria-selected="false" onclick="reloadDataCoa(2);">Liabilitas</a>
                  </li>
                  <li class="nav-item">
                  <a class="nav-link" id="custom-content-above-equitas-tab" data-toggle="pill" href="#custom-content-above-equitas" role="tab" aria-controls="custom-content-above-equitas" aria-selected="false" onclick="reloadDataCoa(3);">Equitas</a>
                  </li>
                  <li class="nav-item">
                  <a class="nav-link" id="custom-content-above-income-tab" data-toggle="pill" href="#custom-content-above-income" role="tab" aria-controls="custom-content-above-income" aria-selected="false" onclick="reloadDataCoa(4);">Pendapatan</a>
                  </li>
                  <li class="nav-item">
                  <a class="nav-link" id="custom-content-above-biaya-tab" data-toggle="pill" href="#custom-content-above-biaya" role="tab" aria-controls="custom-content-above-biaya" aria-selected="false" onclick="reloadDataCoa(5);">Biaya</a>
                  </li>
                  </ul>

                  <table id="example2" class="table table-bordered table-hover table-sm masterCOA">
                  <thead>
                  <tr>
                    <th>COA</th>
                    <th width="10%">Fungsi</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                  </tfoot>
                </table>
                </div>
              </form>
            </div>
          </div>
        </div>
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
<script>

  var table =  $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": true,
  });

reloadDataCoa(1);

function reloadDataCoa(coatid){
  $("input[name='jenis_coatid']").val(coatid);
  var rows = table
          .rows()
          .remove()
          .draw();
  $.post("<?php echo base_url('Masterdata/getListCoa')?>",{coatid:coatid},function(datares){
       var newRes = JSON.parse(datares);
       if ( newRes.length > 0 ){      
         $.each(newRes,function(k,v){
          table.row
          .add([
              v.coacode+' '+v.coaname,
              'buat fungsi',
          ])
          .draw(false);
         });
       }else{

       }
  });
}

function saveData(){
   var data_coaid = $("input[name='data_coaid']").val();
   var data_coatid = $("select[name='coatid']").val();   
   var data_tcmid = $("select[name='tcmid']").val();   
   var data_coacode = $("input[name='coacode']").val();   
   var data_coaname = $("input[name='coaname']").val();
   var data_aktif = $("select[name='is_aktif']").val();      

   Swal.fire({
    title: "Apakah anda yakin?",
    text: "Pastikan Data COA Sudah Sesuai !",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Lanjutkan Simpan Data!"
  }).then((result) => {
    if (result.isConfirmed) {
      var dataSend = {
        coaid: data_coaid,
        coatid: data_coatid,
        tcmid: data_tcmid,
        coacode:data_coacode,
        coaname:data_coaname,
        is_aktif:data_aktif
      };
      $.post("<?php echo base_url('Masterdata/saveCOA')?>",dataSend,function(data){

          var newRes = JSON.parse(data);

          if ( newRes.status == '200' ) {

            var timerInterval;
            Swal.fire({
              title: "Success!",
              html: newRes.msg,
              icon:"success",
              timer: 2000,
              timerProgressBar: true,
              didOpen: () => {
                Swal.showLoading();
                var timer = Swal.getPopup().querySelector("b");
                timerInterval = setInterval(() => {
                  timer.textContent = `${Swal.getTimerLeft()}`;
                }, 100);
              },
              willClose: () => {
                clearInterval(timerInterval);
              }
            }).then((result) => {
              /* Read more about handling dismissals below */
              if (result.dismiss === Swal.DismissReason.timer) {
                $("#formAddCOA .close").click();
                reloadDataCoa(data_coatid);
              }
            });

            
          }else{
            Swal.fire({
              title: "Failed!",
              text: newRes.msg,
              icon: "error"
            });
          }
      });
    }
  });

}

</script>
<div class="modal fade" id="formAddCOA">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah / Edit COA</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="data_coaid" id="data_coaid" data-db="coaid">
        <table width="100%" class="table no-border table-sm">
        <tr>
          <td width="15%">Jenis COA</td>
          <td colspan="3">
              <select name="coatid" class="custom-select custom-select-sm rounded-0" id="exampleSelectRounded0" style="pointer-events: none;">
                <option value='1'>Aset</option>
                <option value='2'>Liabilitas</option>
                <option value='3'>Ekuitas</option>
                <option value='4'>Pendapatan</option>
                <option value='5'>Biaya</option>
              </select>
          </td>
        </tr>
        <tr>
          <td width="15%">Kode COA</td>
          <td><input type="text" name="coacode" id="coacode" class="form-control form-control-sm"></td>
          <td width="15%">Nama COA</td>
          <td><input type="text" name="coaname" id="coaname" class="form-control form-control-sm"></td>
        </tr>
        <tr>
          <td width="15%">Mandatory COA</td>
          <td>
            <select class="custom-select custom-select-sm rounded-0" id="exampleSelectRounded0" name="tcmid">
              <option></option>
              <?php foreach ($list_mandatory->result_array() as $i){
                echo "<option value='".$i['tcmid']."'>".$i['keterangan']."</option>";
              } ?>
            </select>
          </td>
          <td width="15%">Status Aktif</td>
          <td>
            <select class="custom-select custom-select-sm rounded-0" id="exampleSelectRounded0" name="is_aktif">
              <option></option>
              <option value="1">Ya</option>
              <option value="0">TIdak</option>
            </select>
          </td>
        </tr>
        </table>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class='fas fa-times'></i> Tutup</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="saveData();" id="btn_save_konfigurasi"><i class='fas fa-save'></i> Simpan Data</button>
      </div>
    </div>
  </div>
</div>