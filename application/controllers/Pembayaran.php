<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran extends CI_Controller {

		public function __construct()
	{
		parent::__construct();
		$this->load->helper("url");
		$this->load->model('project_model');
		$this->load->model('masterdata_model');
		$this->load->model('project_model');
		$this->load->model('ajuan_model');
		$this->load->model('pembayaran_model');

		if (empty($_SESSION['tuid'])) {

			return redirect('');
		}

		
		$this->load->view('view_proj/head');
		
		 
	}

	public function list_bayarproject(){


		$data['list_pembayaran'] = $this->pembayaran_model->list_pembayaran('1');
		$this->load->view('view_proj/Pembayaran/list_pembayaran',$data);
	}

	public function add_bayarproject() {

		$data['list_project'] = $this->project_model->data_list('tbl_project','tpid',' AND a.tgl_selesai IS NOT NULL AND xx.tpid IS NULL');
		$this->load->view('view_proj/Pembayaran/tambah_pembayaran_project',$data);
	}

	public function act_add_pembayaran(){

		$this->pembayaran_model->insertPembayaran($_POST);

		return redirect('Pembayaran/list_bayarproject');
	}

	public function hapus_pembayaran() {

		$tppid = $_POST['tppid'];

		$this->pembayaran_model->hapus_pembayaran($tppid);
	}

}