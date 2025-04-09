
<script type="text/javascript">
  function hapusUser(tuid,login_name,is_aktif){

    var text = "Apakah anda yakin akan Non-aktifkan user "+login_name+" ini ? ";

    if ( parseInt(is_aktif) < 1) {
        text = "Apakah anda yakin akan meng-aktifkan user "+login_name+" ini ? ";
    } 

    if ( confirm(text)) {

        $.post("<?php echo base_url('Login/hapus_user')?>",{tuid:tuid,is_aktif},function(data){

            if ( data == 1) {
               alert('Gagal Menghapus User, karena user sedang dipakai!');
            } else {
              window.location.reload();
            }
        }); 

    }
  }

  function editUser(tuid){
      window.location.replace("<?php echo base_url('Login/edit_user?from=list&user_id=') ?>"+tuid);
  }

    function editAkses(tuid){
      window.location.replace("<?php echo base_url('Login/akses_user?from=list&user_id=') ?>"+tuid);
  }

  function setAdmin(tuid,login_name,status) {

     if ( confirm ('Apakah anda yakin ?')) {

        $.post("<?php echo base_url('Login/setAdmin')?>",{tuid:tuid,is_admin:status},function(data){

            if ( data == 1) {
               alert('Gagal Set Admin!');
            } else {
              window.location.reload();
            }
        }); 
     }
  }

  function setUserApprove(tuid,login_name,status) {

    var text = "";

    if ( status == 1 ) { 
        text = "Apakah anda yakin akan menjadikan "+login_name+" sebagai user non approvel PO ? ";
    }else{
       text = 'Apakah anda yakin akan menjadikan '+login_name+' sebagai user approval PO ?';
    }

     if ( confirm (text)) {

        $.post("<?php echo base_url('Login/setUserApprove')?>",{tuid:tuid,is_user_approve:status},function(data){

            if ( data == 1) {
               alert('Gagal Set User Approve!');
            } else {
              window.location.reload();
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
            <h1 class="m-0 text-dark"><i class='fa fa-user'></i> Akun</h1>
          </div><!-- /.col -->
          
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="row">
          <div class="col-12">
            <div class='card'>
              <div class='card-header'>
                <h3 class='card-title' style='margin-top:10px'>
                  Data Akun
                </h3>
                    <span style='float:right !important'>
                      <a href="<?php echo base_url('Login/add_user')?>">
                      <button class='btn btn-info'>Tambah Akun</button>
                      </a>
                    </span>
              </div>
              <div class='card-body'>
                  <table id='example2' class='table table-bordered table-hover'>  
                    <thead>
                      <tr>
                        <th width='1%'>No</th>
                        <th width="5%">Username</th>
                        <Th>Nama Karyawan</Th>
                        <th width="2%">Apakah admin</th>
                        <th width="15%">Apakah User Approve PO</th>
                        <!-- <th width='15%'>Nama Satuan</th> -->
                        <th width="20%">Fungsi</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                          $no=1;
                           foreach($list->result_array() as $i) {
                              echo "
                                  <Tr>
                                    <td>".$no."</td>
                                    <td>".$i['login_name']."</td>
                                    <td>".$i['nama_karyawan']."</td>
                                    <td>".($i['is_admin'] == 1 ? 'Ya' : 'Tidak')."</td>
                                    <td>".($i['is_user_approve'] == 1 ? 'Ya' : 'Tidak')."</td>
                                    ";
                                    /*<td>".$i['satuan']."</td>*/
                                    echo"<td align='center'>";
                                        

                                      

                                      if ( $i['is_aktif'] == 1 ) {

                                          echo"<button class='btn btn-warning btn-sm' onclick='editUser(".$i['tuid'].");' title='Edit Password'><i class='fa fa-key'></i></button> ";

                                          if ( $i['is_admin'] == 1 ) {
                                           echo "<button class='btn btn-success btn-sm' title='Status Admin / Non Admin' onclick='setAdmin(".$i['tuid'].",\"".$i['login_name']."\",".$i['is_admin'].");'><i class='fa fa-user-shield'></i></button>";
                                        }else{
                                          echo "<button class='btn btn-success btn-sm' title='Status Admin / Non Admin' onclick='setAdmin(".$i['tuid'].",\"".$i['login_name']."\",".$i['is_admin'].");'><i class='fa fa-user-shield'></i></button>";
                                        }

                                          echo" <button class='btn btn-info btn-sm' title='User Akses' onclick='editAkses(".$i['tuid'].");'><i class='fa fa-sign-language'></i></button>";


                                        echo"
                                        <button class='btn btn-danger btn-sm' title='Non-aktifkan User' onclick='hapusUser(".$i['tuid'].",\"".$i['login_name']."\",".$i['is_aktif'].");'><i class='fa fa-ban'></i></button> ";

                                          if ( intval($i['is_user_approve']) > 0 ) {
                                           echo "<button class='btn btn-warning btn-sm' title='Lepas status user approve PO' onclick='setUserApprove(".$i['tuid'].",\"".$i['login_name']."\",".intval($i['is_user_approve']).");'><i class='fa fa-clipboard-check'></i></button>";
                                        }else{
                                          echo "<button class='btn btn-warning btn-sm' title='Jadikan user approve PO' onclick='setUserApprove(".$i['tuid'].",\"".$i['login_name']."\",".intval($i['is_user_approve']).");'><i class='fa fa-clipboard-check'></i></button>";
                                        }

                                      }else{
                                        echo"
                                        <button class='btn btn-success btn-sm' title='Aktifkan User' onclick='hapusUser(".$i['tuid'].",\"".$i['login_name']."\",".$i['is_aktif'].");'><i class='fa fa-check'></i></button> ";
                                      }

                                      

                                      echo "
                                        </td>
                                  </tr>
                              ";

                              $no++;
                           } 
                        ?>
                    </tbody>
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
</script>
