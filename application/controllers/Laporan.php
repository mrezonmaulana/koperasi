<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

		public function __construct()
	{
		parent::__construct();
		$this->load->helper("url");
		$this->load->model('pinjaman_model');

		if (empty($_SESSION['tuid'])) {

			return redirect('');
		}

		$url = explode('/',$_SERVER['REQUEST_URI']);

		if ( count($url) > 2 ) {

			$apakah_view = explode('_',$url[2]);
			
			if ( $apakah_view[0] == 'view' ) {

				$this->load->view('view_proj/tpl_cetak');

			}
			else{

				$this->load->view('view_proj/Head');
			
			} 
		} else {
			$this->load->view('view_proj/Head');
		}
	}

	public function index(){

		$this->load->view('view_proj/Laporan/report_list');
	}

	public function view_pinjaman_anggota(){

		$data_filter['tgl1'] = (isset($_POST['tgl1'])) ? $_POST['tgl1'] : date('m/d/Y');
		$data_filter['tgl2'] = (isset($_POST['tgl2'])) ? $_POST['tgl2'] : date('m/d/Y');

		$addsql_filter = " AND DATE(b.tanggal) BETWEEN '".date('Y-m-d',strtotime($data_filter['tgl1']))."' AND '".date('Y-m-d',strtotime($data_filter['tgl2']))."' ";

		$data['list_report']    = $this->pinjaman_model->list_pinjaman($addsql_filter);
		$data['tgl1']   = $data_filter['tgl1'];
		$data['tgl2']   = $data_filter['tgl2'];

		$this->load->view('view_proj/Laporan/pinjaman_anggota', $data);

		$this->load->view('view_proj/tpl_cetak_foot');
	}

	public function view_cicilan_anggota(){

		$data_filter['tgl1'] = (isset($_POST['tgl1'])) ? $_POST['tgl1'] : date('m/d/Y');
		$data_filter['tgl2'] = (isset($_POST['tgl2'])) ? $_POST['tgl2'] : date('m/d/Y');

		$addsql_filter = " AND DATE(b.tanggal) BETWEEN '".date('Y-m-d',strtotime($data_filter['tgl1']))."' AND '".date('Y-m-d',strtotime($data_filter['tgl2']))."' ";

		$data['list_report']    = $this->pinjaman_model->list_cicilan($addsql_filter);
		$data['tgl1']   = $data_filter['tgl1'];
		$data['tgl2']   = $data_filter['tgl2'];

		$this->load->view('view_proj/Laporan/cicilan_anggota', $data);

		$this->load->view('view_proj/tpl_cetak_foot');
	}

	public function view_aging_pinjaman(){

		$data_filter['tgl2'] = (isset($_POST['tgl2'])) ? $_POST['tgl2'] : date('m/d/Y');

		$addsql_filter = " AND DATE(a.jurnal_date) <= '".date('Y-m-d',strtotime($data_filter['tgl2']))."' ";

		$data['list_report']    = $this->pinjaman_model->list_aging_pinjaman($addsql_filter);
		$data['tgl2']   = $data_filter['tgl2'];

		$this->load->view('view_proj/Laporan/aging_pinjaman', $data);

		$this->load->view('view_proj/tpl_cetak_foot');
	}
}