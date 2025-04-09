<!-- 
	<style type="text/css">
	body
	{
		background-image: url("<?php echo base_url()?>assets/images/background-1.png") !important;
	}

	.content-wrapper {
		background : none !important;
	}
	</style>
	<div class='content-wrapper' style="margin-left:0px ">
		<section class='content'>
		<div class='content-fluid'>
			<div class='row'>
				<div class='col-md-4' style="margin:auto;margin-top:120px;">
				<img src="<?php echo base_url();?>assets/images/logo_app.png" width="440"/><br>
					<div class='card card-info'>
						<div class='card-header' style='background-color:#2398d7 !important;'>
							<h3 class='card-title'>Otorisasi</h3>	
						</div>
						<form class='form-horizontal' name='frm' id='frm' method='post' action="<?php echo base_url('login/cek_login'); ?>">
							<div class='card-body'>
								<div class='input-group'>
				                    
				                    	<div class="input-group-prepend">
                    					<span class="input-group-text"><i class='fa fa-user'></i></span>
                  						</div>
				                      <input type="text" class="form-control" name='username' id='username'>
				                    
								</div>
								<br>
								<div class='input-group'>
				                    
				                    	<div class="input-group-prepend">
                    					<span class="input-group-text"><i class='fa fa-key'></i></span>
                  						</div>
				                      <input type="password" class="form-control" name='password' id='password'>
				                    
								</div>
							</div>
							<div class='card-footer'>
								<input type='submit' class='btn btn-info btn-sm' style="background-color: #2398d7 !important;border-color: #2398d7 !important;" value='Masuk'/>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	</div> -->

<body class="hold-transition login-page">
	<div class="login-box">
  <div class="login-logo">
     <img src="<?php echo base_url();?>assets/images/warung_oksi.png" width="100"><br>
    <a href="../../index2.html" style="font-size:20pt;"><b></b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Silahkan login untuk membuka aplikasi</p>

      <form name='frm' id='frm' method='post' action="<?php echo base_url('login/cek_login'); ?>">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Username" name='username' id='username'>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name='password' id='password'>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-danger btn-block">Masuk</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <!-- /.social-auth-links -->

    </div>
    <!-- /.login-card-body -->
  </div>
</div>
</body>
<!-- <div class='content-wrapper' style="margin-top:200px;">
	
</div> -->

