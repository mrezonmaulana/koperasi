
<script type="text/javascript">
  function hapusSupplier(tspid,nama_supplier){

    if (confirm('Apakah anda yakin akan menghapus supplier '+nama_supplier+' ?'))
    {

      $.post("<?php echo base_url('Masterdata/hapus_supplier')?>",{tspid:tspid},function(data){
          window.location.reload();
      });

    }
  }

  function editSupplier(tspid) {

    window.location.replace("<?php echo base_url('Masterdata/edit_supplier') ?>/"+tspid);
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
            <h1 class="m-0 text-dark"><i class='fa fa-truck-moving'></i> Master Supplier</h1>
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
                  List Supplier
                </h3>
                    <span style='float:right !important'>
                      <a href="<?php echo base_url('Masterdata/tambah_supplier')?>">
                      <button class='btn btn-warning'>Tambah Supplier</button>
                      </a>
                    </span>
              </div>
              <div class='card-body'>
                  <table id='example2' class='table table-bordered table-hover'>  
                    <thead>
                      <tr>
                        <th width='1%'>No</th>
                        <th>Nama Supplier</th>
                        <!-- <th width='15%'>Nama Satuan</th> -->
                        <th width='15%'>Fungsi</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                          $no=1;
                           foreach($list->result_array() as $i) {
                              echo "
                                  <Tr>
                                    <td>".$no."</td>
                                    <td>".$i['nama_supplier']."</td>";
                                    /*<td>".$i['satuan']."</td>*/
                                    echo"<td align='center'>
                                      <button class='btn btn-warning btn-sm' onclick='editSupplier(".$i['tspid'].");'><i class='fa fa-edit'></i></button>&nbsp;";
                                    if ( $i['ada_pakai'] > 0 ) 
                                      echo "<button class='btn btn-danger btn-sm' title='Supplier tidak bisa dihapus, data sudah dipakai' onclick='hapusSupplier(".$i['tspid'].",\"".$i['nama_supplier']."\");' disabled><i class='fa fa-times'></i></button>";
                                    else{
                                      echo "<button class='btn btn-danger btn-sm' title='Hapus Supplier' onclick='hapusSupplier(".$i['tspid'].",\"".$i['nama_supplier']."\");'><i class='fa fa-times'></i></button>";
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
