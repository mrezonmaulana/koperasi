
<script type="text/javascript">
  function hapusKaryawan(teid,nama_karyawan){

    if (confirm("Apakah anda yakin akan menghapus karyawan "+nama_karyawan+" ?")) {

        $.post("<?php echo base_url('Masterdata/hapus_karyawan')?>",{teid:teid},function(data){
            window.location.reload();
        });
    }
  }

  function editKaryawan(teid) {

    window.location.replace("<?php echo base_url('Masterdata/edit_karyawan') ?>/"+teid);
  }

  function tambahAnggota(tipe,id=""){
    $("#formAddAnggota .modal-title").html('Tambah / Edit Anggota');
    $("#formAddAnggota #btn_save_anggota").css('display','');
    $("#formAddAnggota #pilih_image_disini").css('display','');
    if ( tipe == 1 ) {
      $("select[name='status_anggota']").val(1);
      $("[data-db='id_anggota']").html("<b>(New)</b>");
      $("[data-db='data_image']").val('<?php echo base_url(); ?>assets/images/empty_image.png');
      $("img#blah").attr('src','<?php echo base_url(); ?>assets/images/empty_image.png');
      $("[data-db='nik']").val('');
      $("[data-db='no_kk']").val('');
      $("[data-db='pasangan']").val('');
      $("[data-db='no_hp']").val('');
      $("[data-db='nama_anggota']").val('');
      $("[data-db='alamat_ktp']").val('');
      $("[data-db='alamat_domisili']").val('');
      $("[data-db='jenis_anggota']").val('');
      $("[data-db='status_anggota']").val('');
      $("button#realAddAnggota").click();
    }else {

      $.post("<?php echo base_url('Pinjaman/detailAnggota')?>",{teid:id},function(data){

          var newRes = JSON.parse(data);
          $("[data-db='id_anggota']").html(newRes[0].teid);
          $("input[name='teid']").val(newRes[0].teid);
          $("[data-db='nik']").val(newRes[0].nik);
          $("[data-db='no_kk']").val(newRes[0].no_kk);
          $("[data-db='pasangan']").val(newRes[0].pasangan);
          $("[data-db='no_hp']").val(newRes[0].no_telp);
          $("[data-db='nama_anggota']").val(newRes[0].nama_karyawan);
          $("[data-db='alamat_ktp']").val(newRes[0].alamat);
          $("[data-db='alamat_domisili']").val(newRes[0].alamat_domisili);
          $("[data-db='jenis_anggota']").val(newRes[0].trid);
          $("[data-db='status_anggota']").val(newRes[0].is_aktif);
          $("[data-db='data_image']").val(newRes[0].foto);
          $("img#blah").attr('src',"<?php echo base_url(); ?>"+newRes[0].foto);

          if ( tipe == 3 ){
            $("#formAddAnggota .modal-title").html('Detail Anggota');
            $("#formAddAnggota #btn_save_anggota").css('display','none');
            $("#formAddAnggota #pilih_image_disini").css('display','none');
          }
          $("button#realAddAnggota").click();
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
      $.post("<?php echo base_url('Pinjaman/hapusAnggota')?>",{teid:id},function(data){

        var resData = JSON.parse(data);

        if ( resData.status == '200' ) {
          var timerInterval;
            Swal.fire({
              title: "Success!",
              html: "Data Anggota Berhasil Dihapus",
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
      var url = "<?php echo base_url('Pinjaman/list_anggota')?>/";
      url += "?id_anggota_filter="+document.frm_cari.id_anggota_filter.value;
      url += "&nama_anggota_filter="+document.frm_cari.nama_anggota_filter.value;
      url += "&nik_filter="+document.frm_cari.nik_filter.value;
      url += "&jenis_anggota_filter="+document.frm_cari.jenis_anggota_filter.value;
      url += "&status_anggota_filter="+document.frm_cari.status_anggota_filter.value;
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
                  Data Keanggotaan
                </h3>
              </div>
              <form method="post" name="frm_cari" id="frm_cari" action="<?php echo base_url('Pinjaman/list_anggota')?>">
                <input type="hidden" name="is_excel" id="is_excel" value="0"/>
                <div class='card-body'>
                  <div class="row">
                    <table width="100%" class="table no-border table-sm">
                      <tr>
                        <td width="15%">ID Anggota</td>
                        <td width="35%"><input type="text" class="form-control form-control-sm rounded-0" name="id_anggota_filter" value="<?php echo $id_anggota_filter; ?>"></td>
                        <td width="15%">Nama Anggota</td>
                        <td width="35%"><input type="text" class="form-control form-control-sm rounded-0" name="nama_anggota_filter" value="<?php echo $nama_anggota_filter; ?>"></td>
                      </tr>
                      <tr>
                        <td width="15%">NIK</td>
                        <td width="35%"><input type="text" class="form-control form-control-sm rounded-0" name="nik_filter" value="<?php echo $nik_filter; ?>"></td>
                        <td width="15%">Jenis Anggota</td>
                        <td width="35%"><select class="custom-select custom-select-sm rounded-0" id="exampleSelectRounded0" name="jenis_anggota_filter">
                                        <option></option>
                                        <option value="2" <?php echo  ( $jenis_anggota_filter == '2' ) ? "selected='selected'" : '' ?> >Nasabah</option>
                                        <option value="1"  <?php echo  ( $jenis_anggota_filter == '1' ) ? "selected='selected'" : '' ?>>Pengurus</option>
                                      </select>
                        </td>
                      </tr>
                      <tr>
                        <td width="15%">Ada Simpanan Sukarela</td>
                        <td width="35%"><select class="custom-select custom-select-sm rounded-0" id="exampleSelectRounded0" name="have_simpanan_filter">
                          <option></option>
                          <option value="1">Ya</option>
                          <option value="0">TIdak</option>
                        </select>
                        </td>
                        <td width="15%">Ada Pinjaman</td>
                        <td width="35%"><select class="custom-select custom-select-sm rounded-0" id="exampleSelectRounded0" name="have_pinjaman_filter">
                          <option></option>
                          <option value="1">Ya</option>
                          <option value="0">TIdak</option>
                        </select>
                        </td>
                      </tr>
                      <tr>
                        <td width="15%">Status</td>
                        <td width="35%"><select class="custom-select custom-select-sm rounded-0" id="exampleSelectRounded0" name="status_anggota_filter">
                          <option></option>
                          <option value="1" <?php echo  ( $status_anggota_filter == '1' ) ? "selected='selected'" : '' ?>>Aktif</option>
                          <option value="0" <?php echo  ( $status_anggota_filter == '0' ) ? "selected='selected'" : '' ?>>TIdak Aktif</option>
                        </select></td>
                      </tr>
                    </table>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="button" class="btn btn-success btn-sm" id="btnLihat" onclick="convertXLS(1);">Lihat</button>
                  <button type="button" class="btn btn-warning btn-sm" onclick="convertXLS(2);"><i class='fas fa-file-excel'></i> Download Ke Excel</button>
                  <button type="button" class="btn btn-info btn-sm" onclick="convertXLS(3);"><i class='fas fa-print'></i> Cetak</button>

                  <div style="float:right">
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#formAddAnggota" id="realAddAnggota" style="display:none"><i class='fas fa-plus'></i> Tambah Anggota</button>
                    <button type="button" class="btn btn-danger btn-sm" id="dummyAddAnggota" onclick="tambahAnggota(1);"><i class='fas fa-plus'></i> Tambah Anggota</button>
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
                    <th>ID / Nama</th>
                    <th>NIK</th>
                    <th>Alamat</th>
                    <th>No HP</th>
                    <th>Tipe</th>
                    <th>Fungsi</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php 
                          $no=1;
                           foreach($list->result_array() as $i) {
                              echo "<tr>";
                              echo "<td>".$no."</td>";
                              echo "<td><a href='#' onclick='tambahAnggota(3,".$i['teid'].");return false;'>".$i['teid']." / ".$i['nama_karyawan']."</a></td>";
                              echo "<td>".$i['nik']."</td>";
                              echo "<td>".$i['alamat']."</td>";
                              echo "<td>".$i['no_telp']."</td>";
                              echo "<td>".$i['nama_role']."</td>";
                              echo "<td><a href='#' class='btn btn-warning btn-sm' title='Edit Anggota' onclick='tambahAnggota(2,".$i['teid'].");return false;'><i class='fas fa-edit'></i></a>&nbsp;<a href='#' class='btn btn-danger btn-sm' title='Hapus Anggota' onclick='hapusAnggota(".$i['teid'].");return false;'><i class='fas fa-trash'></i></a></td>";
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

function saveData(){
   var data_teid = $("input[name='teid']").val();
   var data_nik = $("input[name='nik']").val();
   var data_nama_anggota = $("input[name='nama_anggota']").val();
   var data_no_kk = $("input[name='no_kk']").val();
   var data_no_hp = $("input[name='no_hp']").val();
   var data_alamat_ktp = $("textarea[name='alamat_ktp']").val();
   var data_alamat_domisili = $("textarea[name='alamat_domisili']").val();
   var data_jenis_anggota = $("select[name='jenis_anggota']").val();
   var data_data_image = $("input[name='data_image']").val();
   var data_status_anggota = $("select[name='status_anggota']").val();
   var data_pasangan = $("input[name='pasangan']").val();

   if ( data_nik == "" ) {
      toastr.error('Data NIK Tidak Boleh Kosong');
      return false;
   }

   if ( data_nama_anggota == "" ) {
      toastr.error('Data Nama Anggota Tidak Boleh Kosong');
      return false;
   }

   if ( data_no_kk == "" ) {
      toastr.error('Data No. KK Tidak Boleh Kosong');
      return false;
   }

   if ( data_no_hp == "" ) {
      toastr.error('Data No. HP Tidak Boleh Kosong');
      return false;
   }

   if ( data_alamat_ktp == "" ) {
      toastr.error('Data Alamat KTP Tidak Boleh Kosong');
      return false;
   }

   if ( data_alamat_domisili == "" ) {
      toastr.error('Data Alamat Domisili Tidak Boleh Kosong');
      return false;
   }

   if ( data_jenis_anggota == "" ) {
      toastr.error('Data Jenis Anggota Tidak Boleh Kosong');
      return false;
   }

   Swal.fire({
    title: "Apakah anda yakin?",
    text: "Pastikan anggota baru sudah memenuhi persyaratan Registrasi !",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Lanjutkan Simpan Data!"
  }).then((result) => {
    if (result.isConfirmed) {
      var dataSend = {
        teid: data_teid,
        nik: data_nik,
        nama_anggota: data_nama_anggota,
        no_kk: data_no_kk,
        no_hp: data_no_hp,
        alamat_ktp: data_alamat_ktp,
        alamat_domisili: data_alamat_domisili,
        jenis_anggota: data_jenis_anggota,
        data_image: data_data_image,
        status_anggota: data_status_anggota,
        pasangan: data_pasangan,
      };
      $.post("<?php echo base_url('Pinjaman/saveAnggota')?>",dataSend,function(data){

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
<div class="modal fade" id="formAddAnggota">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah / Edit Anggota</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table width="100%" class="table no-border table-sm">
            <tr>
              <td width="15%" rowspan="7" align="center"><div style="width:3cm;height:4cm;border: 1px solid #ccc;padding:2px"><img id="blah" src="<?php echo base_url(); ?>assets/images/empty_image.png" alt="Foto Anggota" style="height: 3.81cm;width:2.79cm" /></div>
              <input type='file' id="imgInp" style="display:none" />
              
              <!--our custom file upload button-->
              <label class="btn btn-sm btn-warning" for="imgInp" style="margin-top:5px" id="pilih_image_disini"><i class='fas fa-image'></i> Pilih Foto</label>
              <input type="hidden" name="data_image" id="data_image" data-db="data_image">
            </td>
              <td width="12%">ID Anggota</td>
              <td width="30%" data-db="id_anggota"><b>(New)</b></td>
              <input type="hidden" name="teid" id="teid" value="0">
              <td width="12%">No. KK <font style="color:red">*</font></td>
              <td width="30%"><input type="text" class="form-control form-control-sm rounded-0" id="no_kk" name="no_kk" data-db="no_kk"></td>
            </tr>
            <tr>
              <td>NIK <font style="color:red">*</font></td>
              <td><input type="text" class="form-control form-control-sm rounded-0" id="nik" name="nik" minlength="16" maxlength="16" data-db="nik"></td>
              <td>No. HP <font style="color:red">*</font></td>
              <td><input type="text" class="form-control form-control-sm rounded-0" id="no_hp" name="no_hp" data-db="no_hp"></td>
            </tr>
            <tr>
              <td>Nama Anggota <font style="color:red">*</font></td>
              <td><input type="text" class="form-control form-control-sm rounded-0" id="nama_anggota" name="nama_anggota" data-db="nama_anggota"></td>
              <td>Pasangan</td>
              <td><input type="text" class="form-control form-control-sm rounded-0" id="pasangan" name="pasangan" data-db="pasangan"></td>
            </tr>
            <tr>
              <td width="12%">Alamat KTP <font style="color:red">*</font></td>
              <td width="35%" colspan="3">
                <textarea class="form-control form-control-sm rounded-0" name="alamat_ktp" style="resize: none;" data-db="alamat_ktp"></textarea>
              </td>
            </tr>
            <tr>
              <td width="12%">Alamat Domisili <font style="color:red">*</font></td>
              <td width="35%" colspan="3">
                <textarea class="form-control form-control-sm rounded-0" name="alamat_domisili" style="resize: none;" data-db="alamat_domisili"></textarea>
              </td>
            </tr>
            <tr>
              <td width="12%">Jenis Anggota <font style="color:red">*</font></td>
              <td width="30%" ><select class="custom-select custom-select-sm rounded-0" id="exampleSelectRounded0" name="jenis_anggota" data-db="jenis_anggota" required>
                              <option></option>
                              <option value="2">Nasabah</option>
                              <option value="1">Pengurus</option>
                            </select>
              </td>
              <td width="12%">Status</td>
              <td width="30%"><select class="custom-select custom-select-sm rounded-0" id="exampleSelectRounded0" name="status_anggota" data-db="status_anggota">
                <option value="1">Aktif</option>
                <option value="0">TIdak Aktif</option>
              </select></td>
            </tr>
            <tr>
              <td colspan="4" style="color:red;font-weight: bold;"><i> *) Data Wajib Isi</i></td>
            </tr>
          </table>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class='fas fa-times'></i> Tutup</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="saveData();" id="btn_save_anggota"><i class='fas fa-save'></i> Simpan Data</button>
      </div>
    </div>
  </div>
</div>