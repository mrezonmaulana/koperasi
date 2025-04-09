<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Masterdata extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper("url");
		$this->load->model('masterdata_model');

		if (empty($_SESSION['tuid'])) {

			return redirect('');
		}
	}

	public function konfig(){

		$sql_filter = "";
		$keterangan = ( isset($_POST['keterangan']) && ($_POST['keterangan']) != '' ) ? $_POST['keterangan'] : "";
		if ($keterangan == '' ){
			$keterangan = ( isset($_GET['keterangan']) && ($_GET['keterangan']) != '' ) ? $_GET['keterangan'] : "";
		}

		$sql_filter .= ($keterangan != '') ? " AND lower(a.keterangan) like lower('%".$keterangan."%')" : "";

		$data['list'] = $this->masterdata_model->list_konfig($sql_filter);
		$data['keterangan'] = $keterangan;
		$this->load->view('view_proj/Head');
		$this->load->view('view_proj/Masterdata/konfig/list_konfig',$data);
	}

	public function detailKonfig(){
		$res = $this->masterdata_model->getData($_POST,'tbl_config');
		die($res);	
	}

	public function saveKonfigurasi(){
		$res = $this->masterdata_model->saveData($_POST,'tbl_config');
		die($res);
	}

	public function coa(){

		$data['list_mandatory'] = $this->masterdata_model->list_mandatory_coa();
		$this->load->view('view_proj/Head');
		$this->load->view('view_proj/Masterdata/coa/list_coa',$data);

	}

	public function getListCoa(){
		$coatid = ( isset($_POST['coatid']) && ($_POST['coatid']) != '' ) ? $_POST['coatid'] : "";
		$sql_filter = " AND a.coatid = ".$coatid;
		$res = $this->masterdata_model->list_coa($sql_filter,'t');
		die($res);
	}

	public function saveCOA(){
		$res = $this->masterdata_model->saveData($_POST,'tbl_coa');
		die($res);
	}

}