<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajuan extends CI_Controller {

		public function __construct()
	{
		parent::__construct();
		$this->load->helper("url");
		$this->load->model('project_model');
		$this->load->model('masterdata_model');
		$this->load->model('project_model');
		$this->load->model('ajuan_model');

		if (empty($_SESSION['tuid'])) {

			return redirect('');
		}

		$url = explode('/',$_SERVER['REQUEST_URI']);

		
		if ( $url[2] == 'cetak_ajuan' || $url[2] == 'cetak_ajuankas' ) {
			$this->load->view('view_proj/tpl_cetak');
			}
		else{
			$this->load->view('view_proj/Head');
		
		} 
	}


	public function add_newajuan() {

		$data['list_project'] = $this->project_model->data_list('tbl_project','tpid');
		$data['list_pelaksana'] = $this->masterdata_model->list_pelaksana('1');
		$this->load->view('view_proj/Ajuan/tambah_ajuan_resto',$data);
	}

	public function edit_newajuan($tpaid) {

		$get_pengaju = $this->ajuan_model->get_data_ajuan($tpaid);
		$data['pengajunya']   = $get_pengaju; 
		$data['list_project'] = $this->project_model->data_list('tbl_project','tpid');
		$data['list_pelaksana'] = $this->masterdata_model->list_pelaksana('1');
		$this->load->view('view_proj/Ajuan/edit_ajuan',$data);
	}

	public function list_pengajuan() {

		$data['list_ajuan'] = $this->ajuan_model->data_list('tbl_project_ajuan','tpaid');
		$data['list_ajuankas'] = $this->ajuan_model->data_list('tbl_ajuan_kasbon','takid');
		$data['list_ajuanadm'] = $this->ajuan_model->data_list('tbl_project_ajuan_adm a JOIN tbl_project b ON (a.tpid = b.tpid)','tpaaid');
		$this->load->view('view_proj/Ajuan/list_newajuan.php',$data);
	}

	public function detail_project(){
		$tpid = $_POST['tpids'];
		$data['detail_project'] = $this->project_model->list_project_row($tpid);

		die(json_encode($data));
	}

	public function act_add_ajuan(){

		$this->ajuan_model->insertAjuan($_POST);
	}

	public function hapus_ajuan() {
			$tpaid = $_POST['tpaid'];
			$this->ajuan_model->hapus_ajuan($tpaid);
	}

	public function hapus_ajuankas() {
			$takid = $_POST['takid'];
			$this->ajuan_model->hapus_ajuankas($takid);
			return redirect('Ajuan/list_pengajuan');
	}

	public function hapus_ajuanadm(){

		$tpaaid = $_POST['tpaaid'];
			$this->ajuan_model->hapus_ajuanadm($tpaaid);
			return redirect('Ajuan/list_pengajuan');
	}

	public function cetak_ajuan($tpaid) {	
		
		$data['list_ajuan'] = $this->ajuan_model->list_ajuan_row($tpaid);
		$data['list_project'] = $this->ajuan_model->list_ajuan_project_row($tpaid);
		$data['list_ajuan_kas'] = $this->ajuan_model->list_ajuan_kas_row($tpaid);
		$this->load->view('view_proj/Ajuan/cetak_ajuan',$data);
		$this->load->view('view_proj/tpl_cetak_foot');
	}

	public function cetak_ajuankas($takid) {


		$data['list_ajuankas'] = $this->ajuan_model->ajuankasbon_row($takid);
		$this->load->view('view_proj/Ajuan/cetak_ajuankas',$data);
		$this->load->view('view_proj/tpl_cetak_foot');
	}

	function act_add_ajuankasbon(){

		$this->ajuan_model->insertAjuanKasbon($_POST);
		return redirect('Ajuan/list_pengajuan');
	}

	function add_ajuankasbon(){

		$data['list_pelaksana'] = $this->masterdata_model->list_pelaksana('');
		$data['list_kepalatukang'] = $this->masterdata_model->list_pelaksana('2');
		$this->load->view('view_proj/Ajuan/ajuankasbon',$data);
	}

	function add_ajuanadm(){

		$data['list_project'] = $this->project_model->data_list('tbl_project','tpid'," AND zz.tpid IS NULL ");
		$this->load->view('view_proj/Ajuan/tambah_ajuanadm',$data);
	}

	function act_add_ajuanadm(){

		$this->ajuan_model->insertadm($_POST);
		return redirect('Ajuan/list_pengajuan');
	}
}