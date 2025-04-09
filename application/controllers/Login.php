<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		$this->load->helper("url");

		$this->load->model('login_model');
	}

	public function index(){

		if (isset($_SESSION['tuid'])){	

			return redirect('Home');

		}else{

			require ('Head.php');
			$test = new Head();
			$test->header();
			$this->load->view('view_proj/login');
		}
	}

	public function cek_login(){

		$jml_data = 0;

		$data = $this->login_model->login();

		if ( isset($data) == 1 ) {

			$jml_data = 1;
		}

		if ( $jml_data == 1 ) {

			$this->session->set_userdata(['tuid' => $data->tuid]);
			$this->session->set_userdata(['login_name' => $data->login_name]);
			$this->session->set_userdata(['is_admin' => $data->is_admin]);
			$this->session->set_userdata(['teid' => $data->teid]);
			$this->session->set_userdata(['nama_real' => $data->nama_karyawan]);
			$this->session->set_userdata(['is_user_approve' => intval($data->is_user_approve)]);
			$this->login_model->updateLogin($data->tuid,'1');

			//if ( $data->is_admin == 0 ) {
				return redirect('Home');
			//} else {
				
			//}

		} else {

			 return redirect()->login();
		}
		
	}


	public function hapus_user() {
		
		$tuid = $_POST['tuid'];
		$is_aktif = intval($_POST['is_aktif']) == 1 ? 0 : 1;
		
		$data = $this->login_model->list_user_row($tuid);
		if ( $data->is_login == 1 ) {

			 die('1');

		} else {
			$this->login_model->hapus_user($tuid,$is_aktif);
		}

	}

	public function user_settings(){

		$from = isset($_GET['from']);

		if (empty($_SESSION['tuid'])) {

			return redirect('');
		}

		$user_id = $_SESSION['tuid'];

		if ( $from == 'list' ) {
			$user_id = $_GET['user_id'];
		}

		$data['list'] = $this->login_model->list_user_row($user_id);
		$data['is_hidden'] = $from == 'list' ? 'hidden' : '';
		$data['list_menu'] = $this->login_model->list_allmenu();
		$data['from']	   = $from;
		$this->load->view('view_proj/Head');
		$this->load->view('view_proj/User/edit_user',$data);	

	}

	public function akses_user(){

		$from = isset($_GET['from']) ? $_GET['from'] : '';

		if (empty($_SESSION['tuid'])) {

			return redirect('');
		}

		$user_id = $_SESSION['tuid'];

		if ( $from == 'list' ) {
			$user_id = $_GET['user_id'];
		}


		$data['list'] = $this->login_model->list_user_row($user_id);
		$data['is_hidden'] = $from == 'list' ? 'hidden' : '';
		$data['list_menu'] = $this->login_model->list_allmenu();
		$data['from'] = $from;
		$this->load->view('view_proj/Head');
		$this->load->view('view_proj/User/edit_akses',$data);	

	}

	public function edit_user(){

		$from = isset($_GET['from']) ? $_GET['from'] : '';

		if (empty($_SESSION['tuid'])) {

			return redirect('');
		}

		$user_id = $_SESSION['tuid'];

		if ( $from == 'list' ) {
			$user_id = $_GET['user_id'];
		}

		$data['list'] = $this->login_model->list_user_row($user_id);
		$data['is_hidden'] = $from == 'list' ? 'hidden' : '';
		$data['list_menu'] = $this->login_model->list_allmenu();
		$data['from'] = $from;
		$this->load->view('view_proj/Head');
		$this->load->view('view_proj/User/edit_user',$data);	

	}

	public function setAdmin(){

		$tuid = $_POST['tuid'];
		$is_admin = $_POST['is_admin'];

		$this->login_model->setAdmin($tuid,$is_admin);
	}

	public function setUserApprove(){

		$tuid = $_POST['tuid'];
		$is_user_approve = $_POST['is_user_approve'];

		$this->login_model->setUserApprove($tuid,$is_user_approve);
	}

	public function list_user() {

		if (empty($_SESSION['tuid'])) {

			return redirect('');
		}

		$data['list'] = $this->login_model->list_user($_SESSION['tuid']);
		$this->load->view('view_proj/Head');
		$this->load->view('view_proj/User/list_user',$data);	
	}

	public function add_user() {

		if (empty($_SESSION['tuid'])) {

			return redirect('');
		}
		
		$data['list'] = $this->login_model->list_allmenu();
		$data['list_karyawan'] = $this->login_model->list_karyawan();
		$this->load->view('view_proj/Head');
		$this->load->view('view_proj/User/add_user',$data);			
	}

	public function act_add_user() {

		$login_name = $_POST['login_name'];
		$login_pass = $_POST['new_pass'];
		$is_admin =  (isset($_POST['is_admin']) == 1) ? 1 : 0;
		$tmndid  = $_POST['tmndid'];
		$teid = (isset($_POST['teid'])) ? $_POST['teid'] : '';

		
		$ok = $this->login_model->insert_user($login_name,md5($login_pass),$is_admin,$tmndid,$teid);

		if ( $ok == 'exist' ) {
				die("<script languange='javascript'>

										alert('Username Sudah Ada, Pembuatan User Login Gagal!');
										window.location.replace('". base_url('Login/list_user') ."');

							</script>");
		}  else {
			return redirect('Login/list_user');
		}
	}

	public function act_edit_akses(){

		$tuid = $_POST['tuid'];
		$tmndid = (isset($_POST['tmndid'])) ? $_POST['tmndid'] : array();
		$data = $this->login_model->update_akses($tuid,$tmndid);
		return redirect('Login/list_user');

	}

	public function cek_user_pass() {

		$tuid = $_POST['tuid'];
		$currpass = $_POST['curr_pass'];
		$newpass = $_POST['new_pass'];
		$from = (isset($_POST['asal'])) ? $_POST['asal'] : '';
		$data = $this->login_model->list_user_row($tuid);

		if ( $from == 'list' ) {
			$this->login_model->update_user($tuid,md5($newpass));
				die('1');
		}else{
				if ( $data->login_pass == md5($currpass) ) {

					$this->login_model->update_user($tuid,md5($newpass));
					die('1');

				} else {

					die('0');
				}
		}

	}

	public function logout(){

		$this->login_model->updateLogin($_SESSION['tuid'],'0');

		session_destroy();
		return redirect('');
	}

	public function cek_akses() {

		$id_user = $_POST['id_user'];

		$data = $this->login_model->cek_akses($id_user,$_SESSION['is_admin']);

		die($data);
	}

	public function cek_po_approve(){

		$id_user = $_POST['id_user'];
		$is_user_approve = $_POST['is_user_approve'];

		$data = $this->login_model->cek_po_approve($id_user,$is_user_approve);

		die($data);
	}

	public function getMenu(){

		$data = $this->login_model->getDataMenu();

		die($data);
	}

}