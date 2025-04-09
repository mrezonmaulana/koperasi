
<script type="text/javascript">
  function hapusPenerimaan(id,type){

        var text = "Penerimaan Barang";
        if ( type == 2 ) {

          text = "Pengajuan Barang";
        }

        if ( confirm('Apakah anda yakin akan menghapus '+text+' ini ?')) {

            $.post("<?php echo base_url('Transaksi/hapus_transaksi')?>",{ttbid:id},function(data){
                window.location.reload();
            }); 

        }
  }

  function closePenerimaan(id,type){

        var text = "Penerimaan Barang";
        if ( type == 2 ) {

          text = "Pengajuan Barang";
        }

        if ( confirm('Apakah anda yakin akan meng-close '+text+' ini ?')) {

            $.post("<?php echo base_url('Transaksi/close_transaksi')?>",{ttbid:id},function(data){
                window.location.reload();
            }); 

        }
  }

  function editPenerimaan(id,type){

       window.location.replace("<?php echo base_url('Transaksi/edit_penjualan')?>?from=&ttbid="+id+"&type="+type);
  }

  function cetakPenerimaan(id,type,tipe_view) {

      if ( tipe_view == 1 ) {
        var url = "<?php echo base_url('Laporan/view_transaksi')?>/"+id+"_"+type;
      } else {
        var url = "<?php echo base_url('Laporan/view_transaksi_only')?>/"+id+"_"+type;  
      }
     
      
      NewWindow(url,"","1800","1800","yes");
    
     
  }


   $(document).ready(function(){

      var c = document.querySelectorAll('.anggaran');
      var d = document.querySelectorAll('.anggarankas');

      
      if ( c.length > 0 ) {
          $.each($('.anggaran'),function(k,v){
              var tp = this.id;
              var newtp = tp.split('_');
              document.getElementById('text_anggaran_'+newtp[1]).innerHTML = formatRupiah(this.value,'Rp. ');
              
          });
      }

      if ( d.length > 0 ) {
          $.each($('.anggarankas'),function(k,v){
              var tp = this.id;
              var newtp = tp.split('_');
              document.getElementById('text_anggarankas_'+newtp[1]).innerHTML = formatRupiah(this.value,'Rp. ');
              
          });
      }
  });

   function openModal(ttbid,tipe,nopo) {

     $("#ttbid_approve").val(ttbid);
     $("#title_po").html("[ "+nopo+" ]");
     $("#ket_approve").val("");
     $("#open_approve").click();
   }

   function actApprove(status){

    var ttbid = $("#ttbid_approve").val();
    var ket_approve = $("#ket_approve").val();

    if ( ket_approve == '' ) {

       alert('Harap Isi keterangan Approval');
       $("#ket_approve").focus();
       return false;
    }

     var text = "";

     if  ( status == 'ya' ) {
        text = "Apakah anda yakin akan meng-approve Pengajuan Barang ini ? ";
     }else{
        text = "Apakah anda yakin akan meng-reject Pengajuan Barang ini ? ";
     }

     if(confirm(text)){

       $.post("<?php echo base_url('Transaksi/act_approve_po');?>",{ttbid:ttbid,ket_approve:ket_approve,status_approve:status},function(data){
            $("#tutup_modal").click();
            window.location.reload();

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
            <h1 class="m-0 text-dark"><i class='fa fa-upload'></i> PO Celup</h1>
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
              
              <div class='card-body'>
                  <table id='example2' class='table table-bordered table-hover'>  
                    <thead>
                      <tr><th colspan='7'>
                          List PO Celup
                          <span style='float:right'>
                            <input type='button' class='btn btn-warning' value='Tambah PO' onclick="window.location.replace('<?php echo base_url('Transaksi/add_penjualan')?>')">
                          </span>
                          </th></tr>
                      <tr>
                        <th width='1%'>No</th>
                        <th width='10%'>No PO</th>
                        <th width='10%'>Tgl PO</th>
                        <th width='10%'>Supplier</th>
                        <th width="7%">User PO</th>
                        <!-- <th width='7%'>Jumlah</th> -->
                        <th width='6%'>Fungsi</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                          $no=1;
                           foreach($list_penerimaan->result_array() as $i) {

                              $status_po = "";

                              if ( $i['is_closed'] > 0 ) {
                                 $status_po = " <span style='color:red;font-size:8pt;'>Status Ajuan Sudah Di tutup</span> ";
                              }

                              if ( $i['status_approve'] == 1 ) {
                                 $status_po .= " <span style='color:red;font-size:8pt;'>Approved By ".$i['user_approve']." - ".date('d M Y H:i',strtotime($i['approve_time']))." [ ".$i['ket_approve']." ]</span> "; 
                              }

                              if ( $i['status_approve'] == 2 ) {
                                 $status_po .= " <span style='color:red;font-size:8pt;'>Rejected By ".$i['user_approve']." - ".date('d M Y H:i',strtotime($i['approve_time']))." [ ".$i['ket_approve']." ]</span> "; 
                              }

                              echo "
                                  <Tr>
                                    <td>".$no."</td>
                                    <td><a href='#' onclick='cetakPenerimaan(".$i['ttbid'].",2,2);return false;'>".$i['reff_code']."</a> ".$status_po."</td>
                                    <td>".date('d F Y',strtotime($i['tgl_trans']))."</td>
                                    <td>".$i['nama_media']."</td>
                                    <td>".$i['user']."</td>
                                    
                                    <td align='center'> ";


                                    if ( $i['status_approve'] == 1 OR $i['status_approve'] == 2) {

                                       if ( $i['status_approve'] == 1 ) {

                                          if ( $_SESSION['is_user_approve'] < 1 or $_SESSION['tuid'] == 1 or  $_SESSION['is_user_approve'] == 1) {

                                            echo " <button title='Cetak Ajuan' class='btn btn-warning btn-sm' onclick='cetakPenerimaan(".$i['ttbid'].",2,1);'><i class='fa fa-print'></i></button>";

                                              if ( ( $i['ada_terima'] <> 2 AND $i['ada_terima'] <> 0 ) AND $i['is_closed']==0) {

                                                            echo"
                                                          <button title='Close Ajuan' class='btn btn-danger btn-sm' onclick='closePenerimaan(".$i['ttbid'].",2);'><i class='fa fa-door-open'></i></button>
                                                        
                                                  ";

                                                           } elseif($i['is_closed']==0 AND $i['ada_terima'] == 0) {

                                                              echo"
                                                          <button title='Hapus Ajuan' class='btn btn-danger btn-sm' onclick='hapusPenerimaan(".$i['ttbid'].",2);'><i class='fa fa-times'></i></button>
                                                          <button title='Edit Ajuan' class='btn btn-success btn-sm' onclick='editPenerimaan(".$i['ttbid'].",2);'><i class='fa fa-edit'></i></button>
                                                       
                                                  ";
                                                  
                                             } 

                                          } else {

                                            echo " <button title='Cetak Ajuan' class='btn btn-warning btn-sm' onclick='cetakPenerimaan(".$i['ttbid'].",2,1);'><i class='fa fa-print'></i></button>";

                                          }

                                       } else {

                                        if ($_SESSION['is_user_approve'] == 1) {

                                          if ( ( $i['ada_terima'] <> 2 AND $i['ada_terima'] <> 0 ) AND $i['is_closed']==0) {

                                                            echo"
                                                          <button title='Close Ajuan' class='btn btn-danger btn-sm' onclick='closePenerimaan(".$i['ttbid'].",2);'><i class='fa fa-door-open'></i></button>
                                                        
                                                  ";

                                                           } elseif($i['is_closed']==0 AND $i['ada_terima'] == 0) {

                                                              echo"
                                                          <button title='Hapus Ajuan' class='btn btn-danger btn-sm' onclick='hapusPenerimaan(".$i['ttbid'].",2);'><i class='fa fa-times'></i></button>
                                                       
                                                  ";
                                                  
                                             } 

                                        }else{

                                          echo"
                                                        <button title='Hapus Ajuan' class='btn btn-danger btn-sm' onclick='hapusPenerimaan(".$i['ttbid'].",2);'><i class='fa fa-times'></i></button>";
                                        }


                                       } 

                                        

                                    } elseif ($i['status_approve'] == 3) {

                                       if ( $_SESSION['is_user_approve'] == 1 or $_SESSION['tuid'] == 1) {

                                        echo " <button title='Approval Ajuan' class='btn btn-warning btn-sm'  onclick='openModal(".$i['ttbid'].",2,\"".$i['reff_code']."\")'><i class='fa fa-user-check'></i> Proses</button>
                                        <button title='Approval Ajuan' class='btn btn-warning btn-sm' id='open_approve' data-toggle='modal' data-target='#modal-default' hidden><i class='fa fa-user-check'></i> Proses</button>";

                                       } else {

                                        echo "<span>Menuggu Approval</span>";

                                       }
                                    } 

                                    echo " </td>
                                                  </tr>";


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

  
  <aside class="control-sidebar control-sidebar-dark">
    
  </aside>

  <div class="modal fade" id="modal-default">
        <div class="modal-dialog" style="max-width: 700px !important">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Approval PO <div style='font-size:11pt;' id='title_po'></div></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
               <input type='hidden' name='ttbid_approve' id='ttbid_approve'/>
               <textarea name='ket_approve' id='ket_approve' class='form-control' placeholder='Keterangan Approve...' style="resize: none"></textarea>
            </div>
            <div class="modal-footer justify-content-between">
              <span style="float:left">
              <button type="button" class="btn btn-default" data-dismiss="modal" id="tutup_modal">Tutup</button>
              </span>
              <span style="float:right">
              <button type="button" class="btn btn-success" onclick="actApprove('ya')">Approve</button>
              <button type="button" class="btn btn-danger" onclick="actApprove('tidak')">Reject</button>
              </span>
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

  $(document).ready(function(){

    var text_jml = $('.text_jumlah');

    if ( text_jml.length > 0 ) {

       $.each(text_jml,function(k,v){
           this.innerHTML = formatRupiah(this.innerHTML,"Rp. ");
       });
    }

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

    /*$('#example3').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": true,
    });*/
  });
</script>
