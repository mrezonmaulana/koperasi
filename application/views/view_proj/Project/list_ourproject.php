
<script type="text/javascript">
  function hapusProject(tpid,nama_project){

    if ( confirm('Apakah anda yakin akan menghapus Project '+nama_project+' ?')) {

        $.post("<?php echo base_url('Project/hapus_project')?>",{tpid:tpid},function(data){
            window.location.reload();
        }); 

    }
    
  }

  function detailProject(tpid) {
      window.location.replace("<?php echo base_url('Project/detail_ourproject') ?>/"+tpid);
  }

  function editProject(tpid) {

    window.location.replace("<?php echo base_url('Project/edit_ourproject') ?>/"+tpid);
  }

  function UpdateTgl() {
    var curr_tpid = $("#curr_tpid").val();
    var tgl_selese = $("#tgl_selesainya").val();

    $.post("<?php echo base_url('Project/update_tanggal')?>",{tpid:curr_tpid,tgl:tgl_selese},function(data){
        alert('Proses Berhasil');
        $("#tutup_modal").click();
        window.location.reload();
    });
  }

  $(document).ready(function(){

     
  });

  function setTpid(tpid,tgl_selesai) {
    
    if ( tgl_selesai != '' && tgl_selesai != ' '){
      var tglnya = tgl_selesai.split('-');
      var tgl = tglnya[1]+'/'+tglnya[2]+'/'+tglnya[0];
      document.getElementById('tgl_selesainya').value = tgl;
    }
    document.getElementById('curr_tpid').value = tpid;


    $('#buatModal').click();
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
        <div class="row">
          <div class="col-12">
            <div class='card'>
              <div class='card-header'>
                <h3 class='card-title' style='margin-top:10px'>
                  List Project
                </h3>
              </div>
              <div class='card-body'>
                  <table id='example2' class='table table-bordered table-hover'>  
                    <thead>
                      <tr>
                        <th width='1%'>No</th>
                        <th>Nama Project</th>
                        <th width='10%'>Tgl Mulai</th>
                        <!-- <th width='10%'>Alamat</th> -->
                        <th width='10%'>Tgl Selesai</th>
                        <th width='10%'>Pelaksana</th>
                        <th width='10%'>Kepala Tukang</th>
                        <!-- <th width='15%'>Nama Satuan</th> -->
                        <th width='18%'>Fungsi</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                          $no=1;
                           foreach($list_project->result_array() as $i) {
                              echo "
                                  <Tr>
                                    <td>".$no."</td>
                                    <td>".$i['nama_project']." <br> ( ".$i['nama_bidang']." )</td>
                                    
                                    
                                    <td>".date('d M Y',strtotime($i['tgl_mulai']))."</td>
                                    <td>".(($i['tgl_selesai']!='') ? date('d M Y',strtotime($i['tgl_selesai'])) : '')."</td>
                                    <td>".$i['pelaksana']."</td>
                                    <td>".$i['kepala_tukang']."</td>
                                    <td align='center'>

                                      ";
                                      if ( $i['tpid_bayar'] == 0) {
                                        echo "<button class='btn btn-warning btn-sm' title='Edit Project' onclick='editProject(".$i['tpid'].");'><i class='fa fa-edit'></i></button>";
                                      }
                                     echo" <button class='btn btn-primary btn-sm' title='Detail Barang' onclick='detailProject(".$i['tpid'].");'><i class='fa fa-eye'></i></button>
                                      
                                      ";
                                    if ( $i['tpid_bayar'] == 0 ) {
                                        echo"
                                        
                                        <button class='btn btn-info btn-sm' title='Ubah Tanggal Selesai' onclick=\"setTpid(".$i['tpid'].",'".$i['tgl_selesai']."');\"><i class='fa fa-calendar'></i></button>
                                      <button class='btn btn-info btn-sm' id='buatModal' title='Ubah Tanggal Selesai' data-toggle='modal' data-target='#modal-default' style='display:none'><i class='fa fa-calendar'></i></button>
                                        <button class='btn btn-danger btn-sm' title='Hapus Project' onclick='hapusProject(".$i['tpid'].",\"".$i['nama_project']."\");'><i class='fa fa-times'></i></button>
                                        ";
                                      }

                                      echo"
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
  
  </div>
  
  <?php 

    $this->load->view('view_proj/footer.php');
    
  ?>

  
  <aside class="control-sidebar control-sidebar-dark">
  
  </aside>


   <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Tanggal Selesai Project</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
               <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text">
                                  <i class="far fa-calendar-alt"></i>
                                </span>
                              </div>
                              <input type='hidden' name='curr_tpid' id='curr_tpid'/>
                             <input type="text" class="form-control float-right" name="tgl_selesainya" id="tgl_selesainya">
                            </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal" id="tutup_modal">Tutup</button>
              <button type="button" class="btn btn-primary" onclick="UpdateTgl()">Simpan</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
  
</div>


</body>
</html>
<script>

  $(function() {
    $('#tgl_selesainya').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true
    }, 
    function(start, end, label) {
        var years = moment().diff(start, 'years');
        
    });
});

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
