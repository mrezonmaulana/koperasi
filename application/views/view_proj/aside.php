<script type="text/javascript">
  var display = '<?php if ( $_SESSION['is_admin'] != '1' ) { echo 'hidden'; } else { echo ''; } ?>';
  $(document).ready(function(){

       var id = <?php echo $_SESSION['tuid'];?>;
       getMenuAkses(id);

  });

  function getMenuAkses(id){
     $.post("<?php echo base_url('Login/getMenu')?>",{},function(data){
              var hasil = JSON.parse(data);
              
              if ( hasil.length > 0 ) {

                var tmnidna = 0;
                var jml_menu_detail = 1;
                $.each(hasil,function(k,v){
                  var li_menunya = "";
                  if ( v.jml_detail > 0 ) {
                    if ( tmnidna != v.tmnidna ) {
                      li_menunya = `<li class="nav-item has-treeview" id='`+v.id_li+`' hidden><a href="#" class="nav-link" id='`+v.id_li_a+`'><i class="nav-icon fas `+v.icon+`"></i><p>`+v.nama_menu+`<i class="right fas fa-angle-left"></i></p></a><ul class="nav nav-treeview header-`+v.id_li+`"><li class="nav-item"><a href="<?php echo base_url();?>`+v.url_menu_detail+`" class="nav-link" id='`+v.id_li_detail+`' hidden><i  class="nav-icon fas `+v.icon_detail+`"></i><p>`+v.nama_menu_detail+`</p></a></li></ul></li>`;
                    }else{
                      $("ul.header-"+v.id_li).append(`<li class="nav-item"><a href="<?php echo base_url();?>`+v.url_menu_detail+`" class="nav-link" id='`+v.id_li_detail+`' hidden><i  class="nav-icon fas `+v.icon_detail+`"></i><p>`+v.nama_menu_detail+`</p></a></li>`);
                      /*if ( jml_menu_detail == (v.jml_detail-1) ) {
                        li_menunya = `<li class="nav-item"><a href="<?php echo base_url();?>`+v.url_menu_detail+`" class="nav-link" id='`+v.id_li_detail+`' hidden><i  class="nav-icon fas `+v.icon_detail+`"></i><p>`+v.nama_menu_detail+`cc</p></a></li></ul></li>`;
                        jml_menu_detail = 1;
                      } else {
                         li_menunya = `<li class="nav-item"><a href="<?php echo base_url();?>`+v.url_menu_detail+`" class="nav-link" id='`+v.id_li_detail+`' hidden><i  class="nav-icon fas `+v.icon_detail+`"></i><p>`+v.nama_menu_detail+`bb</p></a></li>`;
                      }*/

                      jml_menu_detail = jml_menu_detail + 1;
                    }
                  } else {
                     li_menunya = `<li class="nav-item has-treeview" id="`+v.id_li+`" hidden><a href="<?php echo base_url() ?>`+v.url+`" class="nav-link" id="`+v.id_li_a+`"><i class="nav-icon fas `+v.icon+`"></i><p>`+v.nama_menu+`</p></a></li>`;
                  }
                  $("ul.list_menu").append(li_menunya);

                  tmnidna = v.tmnidna;
                });

                $("ul.list_menu").append(`<li class="nav-item has-treeview" id="login"><a href="#" class="nav-link" id="link_login"><i class="nav-icon fa fa-user"></i><p>Akun<i class="right fas fa-angle-left"></i></p></a><ul class="nav nav-treeview"><li class="nav-item"><a href="<?php echo base_url('Login/user_settings');?>" class="nav-link" id="settings"><i class="nav-icon fas fa-key"></i><p>Ubah Password</p></a></li><li class="nav-item" `+display+` ><a href="<?php echo base_url('Login/list_user');?>" class="nav-link" id="user"><i class="nav-icon fas fa-users"></i><p>Data Akun</p></a></li><li class="nav-item"><a href="<?php echo base_url('Login/logout');?>" class="nav-link"><i class="nav-icon fas fa-power-off"></i><p>Keluar</p></a></li></ul></li>`);
                getAkses(id);
              }
        }); 
  }

  function getAkses(id){
      $.post("<?php echo base_url('Login/cek_akses')?>",{id_user:id},function(data){
              var hasil = JSON.parse(data);
              
              if ( hasil.length > 0 ) {

                $.each(hasil,function(k,v){
                  if ( $("#"+v.id_header).length > 0 ) {
                    $("#"+v.id_header).removeAttr('hidden','');
                    if ( v.id_anak!='' && $("#"+v.id_anak).length > 0 ){
                      $("#"+v.id_anak).removeAttr('hidden','');
                    }
                  }
                });

                setCurrent();
                cekApprove();
              }
        }); 
  }

  function setCurrent(){


      var url = location.href;
      
      url = url.split('/');
      
      if ( url.length < 5 ) {

          var text = url[3].toLowerCase();
          $('#'+text).removeClass("nav-item has-treeview").addClass("nav-item has-treeview menu-open");
          $('#link_'+text).removeClass("nav-link").addClass("nav-link active");

      } else if ( url.length > 4 ) {

          var text = url[3].toLowerCase();
          var text2 = url[4].toLowerCase();
          
          if ( text2.match('_') != null ) {

                text_baru = text2.split('_');
                text2 = text_baru[1];

                if ( text2.match('from') != null ) {
                    text_baru = text2.split('?');
                    text2 = text_baru[0];
                } 

          }

          $('#'+text).removeClass("nav-item has-treeview").addClass("nav-item has-treeview menu-open");
          $('#link_'+text).removeClass("nav-link").addClass("nav-link active");
          $('#'+text2).removeClass("nav-link").addClass("nav-link active");

      }

  }

  function cekApprove(){

    var id = <?php echo $_SESSION['tuid'];?>;
    var is_user_approve = <?php echo $_SESSION['is_user_approve'];?>;

    $.post("<?php echo base_url('Login/cek_po_approve')?>",{id_user:id,is_user_approve:is_user_approve},function(data){
            
            var hasil = JSON.parse(data);

            if (parseInt(hasil.jml_data) > 0) {
              $("p#ajuan_barang").html("Pengajuan Barang <span class='badge badge-danger right'>"+hasil.jml_data+"</span>");
            } 

              
        }); 


  }
</script>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
  </ul>
</nav>

<aside class="main-sidebar sidebar-light-primary elevation-4">
  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?php echo base_url();?>assets/images/warung_oksi.png" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">Selamat Datang ! 

        </a>
        <span style="font-size:10pt"> User Logged : <?php echo $_SESSION['nama_real'] ?> </span>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column list_menu" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item has-treeview" id="home">
            <a href="<?php echo base_url('Home') ?>" class="nav-link" id="link_home">
              <i class="nav-icon fas fa-home"></i>
                <p>
                  Home  
                </p>
            </a>
          </li>
       </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>