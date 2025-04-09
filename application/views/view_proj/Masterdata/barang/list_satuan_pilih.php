<script type="text/javascript">
function pilih(tsid,nama_satuan){

  
  var satuan_kecil = $(window.opener.document).find("select[name='satuan']").val();
  var tsid_detail  = $(window.opener.document).find("input[name='tsid_detail[]'");

  if ( satuan_kecil == tsid ) {
    alert('Satuan Besar Tidak Boleh Sama dengan Satuan Kecil');

  } else {
    var is_done = 1;
    if ( tsid_detail.length > 0 ) {

        $.each(tsid_detail,function(k,v){
          if ( this.value == tsid ) {
            is_done = 0;
          }
        });
    }

    if ( is_done == 1 ) {
      window.opener.addSatuan(tsid,nama_satuan);
    }else{
      alert('Satuan sudah ditambahkan di list');
    }
  }

}
</script>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      
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
                  List Satuan
                </h3>
              </div>
              
                  <div class='card-body'>
                    <div class='row'>
                      <div class='col'>
                        <form class='form-horizontal' name='frm' id='frm' method='post' action='<?php echo base_url('masterdata/list_satuan_pilih'); ?>'>
                          <label style='float-left'>Nama Satuan</label><br>
                          <input type="text" class='form-control form-control-sm' name='nm_satuan' id='nm_satuan' style="float:left;width:88%"  value='<?php echo $nm_satuan; ?>'/>
                          <input type='submit' name='btn_cari' id='btn_cari' value='Cari' class='btn btn-primary btn-sm' style="float:left;margin-left:5px;" />
                        </form>
                        <hr>
                        <table width="100%" class='table table-bordered'>
                            <thead>
                              <tr>
                                        <th style="text-align:center" width="5%">No</th>
                                        <th style="text-align:center" >Nama Satuan</th>
                                        <th style="text-align:center" width="15%">&nbsp;</th>
                                      </tr>
                            </thead>
                            <tbody style="">
                                <?php 
                                  $no=1;
                                   foreach ($list->result_array() as $key => $value) {
                                     echo "<tr>
                                          <td>".$no."</td>
                                          <td>".$value['satuan']."</td>
                                          <td>
                                            <button class='btn btn-primary' onclick=\"pilih('".$value['tsid']."','".$value['satuan']."'); return false;\">Pilih</button>
                                          </td>
                                          </tr>";

                                          $no++;
                                   }
                                ?>
                            </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                 <div class='card-footer'>
                 <input type='button' class='btn btn-sm btn-warning' value='Tutup' onclick="window.close();"/>
                </div>
               
              </div>
            </div>
            
          </div>
          <!-- ./col -->
        </div>
       
          </section>
      </div><!-- /.container-fluid -->
    </div>
  </body>