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
                  Konfigurasi Panel
                </h3>
              </div>
              <form method="post" name="frm_cari" id="frm_cari" action="<?php echo base_url('Masterdata/konfig')?>">
                <div class='card-body'>
                  <div class="row">
                    <table width="100%" class="table no-border table-sm">
                      <tr>
                        <td width="15%">Keterangan</td>
                        <td><input type="text" name="keterangan" id="keterangan" class="form-control form-control-sm" value="<?php echo $keterangan; ?>"></td>
                      </tr>
                    </table>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-success btn-sm" id="btnLihat">Lihat</button>
                </div>
              </form>
            </div>
            <div class="card">
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover table-sm">
                  <thead>
                  <tr>
                    <th>Keterangan</th>
                    <th>Data</th>
                    <th>Tipe</th>
                    <th>Fungsi</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php 
                          $no=1;
                           foreach($list->result_array() as $i) {
                              echo "<tr>";
                              echo "<td>".$i['keterangan']."</td>";
                              echo "<td>".$i['data']."</td>";
                              echo "<td>".$i['tipe_data']."</td>";
                               echo "<td><a href='#' class='btn btn-warning btn-sm' title='Edit Anggota' onclick='editKonfigurasi(".$i['tcid'].");return false;'><i class='fas fa-edit'></i></a><button type='button' data-toggle='modal' data-target='#formEditKonfigurasi' id='realEditKonfigurasi_".$i['tcid']."' style='display:none'><i class='fas fa-plus'></i>Edit Konfigurasi</button></td>";
                              echo "</tr>";
                              $no++;
                           } 
                        ?>
                  </tbody>
                  <tfoot>
                  </tfoot>
                </table>
              </div>
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
  $(function () {
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": true,
    });
  });

function saveData(){
   var data_tcid = $("input[name='data_tcid']").val();
   var data_konfig = $("input[name='data']").val();
   

   if ( data_konfig == "" ) {
      toastr.error('Data Konfigurasi Tidak Boleh Kosong');
      return false;
   }

   Swal.fire({
    title: "Apakah anda yakin?",
    text: "Pastikan Data Konfigutasi Sudah Sesuai !",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Lanjutkan Simpan Data!"
  }).then((result) => {
    if (result.isConfirmed) {
      var dataSend = {
        tcid: data_tcid,
        data_konfig: data_konfig,
      };
      $.post("<?php echo base_url('Masterdata/saveKonfigurasi')?>",dataSend,function(data){

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
                $("button#btnLihat").trigger('click');
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
<div class="modal fade" id="formEditKonfigurasi">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Konfigurasi</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="data_tcid" id="data_tcid" data-db="tcid">
        <table width="100%" class="table no-border table-sm">
        <tr>
          <td width="15%">Keterangan</td>
          <td data-db="keterangan"></td>
        </tr>
        <tr>
          <td>Tipe Data</td>
          <td data-db="tipe_data"></td>
        </tr>
        <tr>
          <td colspan="2">
            <input type="text" name="data" id="data" class="form-control form-control-sm" data-db="data">
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