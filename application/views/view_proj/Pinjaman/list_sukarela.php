
<script type="text/javascript">
  function tambahAnggota(tipe,id=""){
    $("#formAddPinjaman .modal-title").html('Tambah Simpanan Sukarela');
    if ( tipe == 1 ) {
      $("#data-title-form").html('Tambah Simpanan Sukarela');
      $("select[name='teid']").val('');
      $("td#contentKalkulasi").html('');
      $("a#btnKalkulasi").css('display','');
      $("input[name='tsid_sukarela']").val('0');
      $("input[name='tenor']").val('');
      $("input[name='nominal']").val('');
      $("button#realAddAnggota").click();
      $("#formAddPinjaman #btn_save_anggota").css('display','');
    }else {

      $.post("<?php echo base_url('Pinjaman/detailSukarela')?>",{teid:id},function(data){

          var newRes = JSON.parse(data);
          $("#data-title-form").html('Detail Simpanan Sukarela');
          $("select[name='teid']").val(newRes[0].teid);
          var tglData = newRes[0].tanggal;
          var tglDataExplode = tglData.split('-');
          var tglDataNew = tglDataExplode[2]+'-'+tglDataExplode[1]+'-'+tglDataExplode[0];
          $("input[name='tgl_mulai']").val(tglDataNew);
          
          $("input[name='nominal']").val(formatRupiah(newRes[0].nominal,'Rp. '));
          $("button#realAddAnggota").click();
          $("#formAddPinjaman #btn_save_anggota").css('display','none');
      });
    }
  }

  function hapusAnggota(id){
    const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger"
    },
    buttonsStyling: false
  });
  swalWithBootstrapButtons.fire({
    title: "Apakah anda yakin ?",
    text: "Anda tidak dapat mengembalikan data yang sudah terhapus!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Ya, Hapus Data!",
    cancelButtonText: "Tidak, Batalkan!",
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("<?php echo base_url('Pinjaman/hapusSukarela')?>",{tsid:id},function(data){

        var resData = JSON.parse(data);

        if ( resData.status == '200' ) {
          var timerInterval;
            Swal.fire({
              title: "Success!",
              html: "Data Simpanan Sukarela Berhasil Dihapus",
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
          swalWithBootstrapButtons.fire({
            title: "Gagal Terhapus!",
            text: "Data Anggota Gagal Dihapus.",
            icon: "error"
          });
        }

      });
      
    } else if (
      /* Read more about handling dismissals below */
      result.dismiss === Swal.DismissReason.cancel
    ) {
      
    }
  });
  }

  function convertXLS(tipe){
    if ( tipe == 1 ) {
      $("input[name='is_excel']").val(0);
      $("form[name='frm_cari']").submit();
    }else if (tipe == 2){
      $("input[name='is_excel']").val(1);
      $("form[name='frm_cari']").submit();
    }else{
      var url = "<?php echo base_url('Pinjaman/list_sukarela')?>/";
      url += "?nama_anggota_filter="+document.frm_cari.nama_anggota_filter.value;

      var tgl_start_ex = document.frm_cari.tgl_start.value;
      tgl_start_ex = tgl_start_ex.split('/');
      tgl_start_new = tgl_start_ex[1]+'-'+tgl_start_ex[0]+'-'+tgl_start_ex[2];

      var tgl_end_ex = document.frm_cari.tgl_end.value;
      tgl_end_ex = tgl_end_ex.split('/');
      tgl_end_new = tgl_end_ex[1]+'-'+tgl_end_ex[0]+'-'+tgl_end_ex[2];

      url += "&tgl_start="+tgl_start_new;
      url += "&tgl_end="+tgl_end_new;
      url += "&is_print=1";
      newwindow=window.open(url,"cetak_anggota",'height=1800,width=1800');
      if (window.focus) {newwindow.focus()}
       return false;
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
                  Data Simpanan Sukarela
                </h3>
              </div>
              <form method="post" name="frm_cari" id="frm_cari" action="<?php echo base_url('Pinjaman/list_sukarela')?>">
                <input type="hidden" name="is_excel" id="is_excel" value="0"/>
                <div class='card-body'>
                  <div class="row">
                    <table width="100%" class="table no-border table-sm">
                      <tr>
                        <td width="15%">Tanggal Bayar</td>
                        <td width="35%">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                              </span>
                            </div>
                           <input type="text" class="form-control float-right form-control-sm" name="tgl_start" id="periode_pinjam1" value='<?php echo $tgl_start; ?>'>
                          </div>
                        </td>
                        <td width="35%" colspan="2">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                              </span>
                            </div>
                           <input type="text" class="form-control float-right form-control-sm" name="tgl_end" id="periode_pinjam2" value='<?php echo $tgl_end; ?>'>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td width="15%">Nama Anggota</td>
                        <td width="35%"><input type="text" class="form-control form-control-sm rounded-0" name="nama_anggota_filter" value="<?php echo $nama_anggota_filter; ?>"></td>
                        <td></td>
                        <td></td>
                      </tr>
                    </table>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="button" class="btn btn-success btn-sm" id="btnLihat" onclick="convertXLS(1);">Lihat</button>
                  <button type="button" class="btn btn-warning btn-sm" onclick="convertXLS(2);"><i class='fas fa-file-excel'></i> Download Ke Excel</button>
                  <button type="button" class="btn btn-info btn-sm" onclick="convertXLS(3);"><i class='fas fa-print'></i> Cetak</button>

                  <div style="float:right">
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#formAddPinjaman" id="realAddAnggota" style="display:none"><i class='fas fa-plus'></i> Tambah Simpanan</button>
                    <button type="button" class="btn btn-danger btn-sm" id="dummyAddAnggota" onclick="tambahAnggota(1);"><i class='fas fa-plus'></i> Tambah Simpanan</button>
                  </div>
                </div>
              </form>
            </div>
            <div class="card">
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover table-sm">
                  <thead>
                  <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th width="12%">Tanggal Bayar</th>
                    <th>Nasabah</th>
                    <th>Pengurus</th>
                    <th>No HP</th>
                    <th>Nominal</th>
                    <th width="12%">Fungsi</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php 
                          $no=1;
                           foreach($list->result_array() as $i) {
                              echo "<tr>";
                              echo "<td align='center'>".$no."</td>";
                              echo "<td align='center'>".$i['no_reff']."</td>";
                              echo "<td align='center'>".$i['tanggal']."</td>";
                              echo "<td>".$i['nama_karyawan']."</td>";
                              echo "<td>".$i['pengurus']."</td>";
                              echo "<td align='center'>".$i['no_telp']."</td>";
                              echo "<td align='right'>Rp. ".number_format($i['nominal_pinjam'],0,',','.')."</td>";
                              echo "<td align='center'><a href='#' class='btn btn-warning btn-sm' title='Detail Pinjaman' onclick='tambahAnggota(2,".$i['tsid'].");return false;'><i class='fas fa-eye'></i></a>&nbsp;<a href='#' class='btn btn-danger btn-sm' title='Hapus Anggota' onclick='hapusAnggota(".$i['tsid'].");return false;'><i class='fas fa-trash'></i></a></td>";
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

    $("#imgInp").change(function(){
    readURL(this);
    });

    $('#reservation').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale:{format: 'DD-MM-YYYY'}
    }, 
    function(start, end, label) {
        var years = moment().diff(start, 'years');
        
    });

    $('#periode_pinjam1').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true
    }, 
    function(start, end, label) {
        var years = moment().diff(start, 'years');
        
    });

    $('#periode_pinjam2').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true
    }, 
    function(start, end, label) {
        var years = moment().diff(start, 'years');
        
    });


    $("#btnKalkulasi").click(function(){
      kalkulasiPinjaman();
    });
  });

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
        $('#blah').attr('src', e.target.result);
        $('[data-db="data_image"]').val(e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
  }
}

function kalkulasiPinjaman(arrJson=''){
  var nominal = backToNormal(document.getElementById('nominal'));
  var tenor   = $("input[name='tenor']").val();

  console.log(arrJson);

  if ( nominal == 0 ) {
     alert('Silahkan isi nominal');
  }else{

    var biayaProvisi    = 7500;
    var biayaPinjamanNett = nominal - biayaProvisi;
    var simpananWajib = 5000;
    var jasaPinjaman  = 10;

    if ( arrJson.tpid > 0 ) {
      var biayaProvisi    = arrJson.provisi;
      var biayaPinjamanNett = nominal - biayaProvisi;
      var simpananWajib = arrJson.simpanan_wajib / arrJson.tenor;
      var jasaPinjaman  = (arrJson.jasa_pinjaman / arrJson.jumlah_pinjaman)*100;
    }
  
    var jasaPinjamanNom = (jasaPinjaman/100) * nominal;
    var simpananWajibTenor = simpananWajib * tenor;
    var totalPiutang = parseFloat(nominal) + parseFloat(simpananWajibTenor) + parseFloat(jasaPinjamanNom);
    var cicilanPiutang = totalPiutang / tenor;

    var tableKalkulasi  = "<table width='100%' class='table no-border table-sm' style='font-size:10pt'>";
        tableKalkulasi += "<tr><td width='30%'> Total Pinjaman <font style='float:right'>:</font></td><td align='right'>"+formatRupiahNew(nominal,'Rp')+"</td></tr>";
        tableKalkulasi += "<tr><td width='30%'> Biaya Provisi <font style='float:right'>:</font></td><td align='right'>-"+formatRupiahNew(biayaProvisi,'Rp')+"</td></tr>";
        tableKalkulasi += "<tr><td width='30%'> Pinjaman Di Terima <font style='float:right'>:</font></td><td align='right'>"+formatRupiahNew(biayaPinjamanNett,'Rp')+"</td></tr>";
        tableKalkulasi += "<tr><td width='30%'> Tenor <font style='float:right'>:</font></td><td align='right'>"+tenor+" Minggu</td></tr>";
        tableKalkulasi += "<tr><td width='30%'> Simpanan Wajib ( "+formatRupiahNew(simpananWajib,'Rp')+" x Lama Tenor )<font style='float:right'>:</font></td><td align='right'>"+formatRupiahNew(simpananWajibTenor,'Rp')+"</td></tr>";
        tableKalkulasi += "<tr><td width='30%'> Jasa Pinjaman ( "+jasaPinjaman+"% dari Total Pinjaman )<font style='float:right'>:</font></td><td align='right'>"+formatRupiahNew(jasaPinjamanNom,'Rp')+"</td></tr>";
        tableKalkulasi += "<tr style='font-weight:bold'><td width='30%' > Total Piutang <font style='float:right'>:</font></td><td align='right'>"+formatRupiahNew(totalPiutang,'Rp')+"</td></tr>";
        tableKalkulasi += "<tr style='font-weight:bold'><td width='30%' > Cicilan Per Minggu <font style='float:right'>:</font></td><td align='right'>"+formatRupiahNew(cicilanPiutang,'Rp')+"</td></tr>";
        tableKalkulasi += "</table>";
        tableKalkulasi += "<input type='hidden' name='biaya_provisi' id='biaya_provisi' value='"+biayaProvisi+"'>";
        tableKalkulasi += "<input type='hidden' name='jumlah_real_pinjaman' id='jumlah_real_pinjaman' value='"+biayaPinjamanNett+"'>";
        tableKalkulasi += "<input type='hidden' name='nominal_simpanan_wajib' id='nominal_simpanan_wajib' value='"+simpananWajibTenor+"'>";
        tableKalkulasi += "<input type='hidden' name='nominal_jasa_pinjaman' id='nominal_jasa_pinjaman' value='"+jasaPinjamanNom+"'>";
        tableKalkulasi += "<input type='hidden' name='total_piutang' id='total_piutang' value='"+totalPiutang+"'>";
        tableKalkulasi += "<input type='hidden' name='jumlah_cicilan' id='jumlah_cicilan' value='"+cicilanPiutang+"'>";

    $("td#contentKalkulasi").html(tableKalkulasi);

    if ( arrJson.tpid > 0 ) {
      $("#formAddPinjaman #btn_save_anggota").css('display','none');
    }else{
      $("#formAddPinjaman #btn_save_anggota").css('display','');
    } 
  }
}

function saveData(){
   var data_tsid = $("input[name='tsid_sukarela']").val();
   var data_teid = $("select[name='teid']").val();
   var data_tanggal_pinjam = $("input[name='tgl_mulai']").val();
   var data_nominal_pinjam = backToNormal(document.getElementById('nominal'));
   var keterangan = '';
   
   Swal.fire({
    title: "Apakah anda yakin?",
    text: "Pastikan Data Simpanan Sukarela Sudah Sesuai !",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Lanjutkan Simpan Data!"
  }).then((result) => {
    if (result.isConfirmed) {
      var dataSend = {
        tsid: data_tsid,
        teid: data_teid,
        tanggal_pinjam: data_tanggal_pinjam,
        nominal_pinjam: data_nominal_pinjam,
        keterangan: keterangan,
      };
      $.post("<?php echo base_url('Pinjaman/saveSukarela')?>",dataSend,function(data){

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
<div class="modal fade" id="formAddPinjaman">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id='data-title-form'>Tambah Simpanan Sukarela</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table width="100%" class="table no-border table-sm">
          <input type='hidden' name='tsid_sukarela' id='tsid_sukarela' value='0'>
            <tr>
              <td width="15%">Anggota</td>
              <td>
                <select name="teid" class="custom-select custom-select-sm rounded-0" id="exampleSelectRounded0">
                <option><--- Pilih Anggota --></option>
                <?php 
                  foreach( $list_karyawan->result_array() as $i ){
                    echo "<option value='".$i['teid']."'>".$i['nik']." - ".$i['nama_karyawan']."</option>";
                  }
                ?>
              </select>
              </td>
              <td>Tanggal Bayar</td>
              <td width="35%">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="far fa-calendar-alt"></i>
                    </span>
                  </div>
                 <input type="text" class="form-control float-right form-control-sm" name="tgl_mulai" id="reservation">
                </div>
              </td>
            </tr>
            <tr>
              <td width="15%">Nominal</td>
              <td>
                <input type="text" name="nominal" id="nominal" class="form-control form-control-sm" onkeyup="setNominal('nominal');">
              </td>
            </tr>
            <tr style="display:none">
              <td><a href="#" id="btnKalkulasi" class="btn btn-sm btn-warning">Kalkulasi Pinjaman</td>
              <td colspan="3" id="contentKalkulasi"></td>
            </tr>
          </table>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class='fas fa-times'></i> Tutup</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="saveData();" id="btn_save_anggota"><i class='fas fa-save' ></i> Simpan Data</button>
      </div>
    </div>
  </div>
</div>