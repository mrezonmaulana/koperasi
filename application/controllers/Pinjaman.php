<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pinjaman extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper("url");
		$this->load->model('pinjaman_model');

		if (empty($_SESSION['tuid'])) {

			return redirect('');
		}
	}

	public function list_anggota(){

		$is_excel   = ((isset($_POST['is_excel']) && ($_POST['is_excel']) != '0') || ( isset($_GET['is_excel']) && ($_GET['is_excel']) != '0' ) ) ? "1" : "0";
		$is_print   = ((isset($_POST['is_print']) && ($_POST['is_print']) != '0') || ( isset($_GET['is_print']) && ($_GET['is_print']) != '0' ) ) ? "1" : "0";

		$id_anggota_filter = ( isset($_POST['id_anggota_filter']) && ($_POST['id_anggota_filter']) != '' ) ? $_POST['id_anggota_filter'] : "";
		if ($id_anggota_filter == '' ){
			$id_anggota_filter = ( isset($_GET['id_anggota_filter']) && ($_GET['id_anggota_filter']) != '' ) ? $_GET['id_anggota_filter'] : "";
		}

		$nik_filter = ( isset($_POST['nik_filter']) && ($_POST['nik_filter']) != '' ) ? $_POST['nik_filter'] : "";
		if ($nik_filter == '' ){
			$nik_filter = ( isset($_GET['nik_filter']) && ($_GET['nik_filter']) != '' ) ? $_GET['nik_filter'] : "";
		}

		$nama_anggota_filter = ( isset($_POST['nama_anggota_filter']) && ($_POST['nama_anggota_filter']) != '' ) ? $_POST['nama_anggota_filter'] : "";
		if ($nama_anggota_filter == '' ){
			$nama_anggota_filter = ( isset($_GET['nama_anggota_filter']) && ($_GET['nama_anggota_filter']) != '' ) ? $_GET['nama_anggota_filter'] : "";
		}

		$jenis_anggota_filter = ( isset($_POST['jenis_anggota_filter']) && ($_POST['jenis_anggota_filter']) != '' ) ? $_POST['jenis_anggota_filter'] : "";
		if ($jenis_anggota_filter == '' ){
			$jenis_anggota_filter = ( isset($_GET['jenis_anggota_filter']) && ($_GET['jenis_anggota_filter']) != '' ) ? $_GET['jenis_anggota_filter'] : "";
		}

		$status_anggota_filter = ( isset($_POST['status_anggota_filter']) && ($_POST['status_anggota_filter']) != '' ) ? $_POST['status_anggota_filter'] : "";
		if ($status_anggota_filter == '' ){
			$status_anggota_filter = ( isset($_GET['status_anggota_filter']) && ($_GET['status_anggota_filter']) != '' ) ? $_GET['status_anggota_filter'] : "";
		}

		$sql_filter = "";
		$sql_filter .= ($id_anggota_filter != '') ? " AND a.teid = ".$id_anggota_filter : "";
		$sql_filter .= ($nik_filter !='') ? " AND a.nik = ".$nik_filter : "";
		$sql_filter .= ($nama_anggota_filter !='') ? " AND lower(a.nama_karyawan) like lower('%".$nama_anggota_filter."%')" : "";
		$sql_filter .= ($jenis_anggota_filter != '') ? " AND a.trid = ".$jenis_anggota_filter : "";
		$sql_filter .= ($status_anggota_filter != '') ? " AND a.is_aktif = ".$status_anggota_filter : "";

		$data['list'] = $this->pinjaman_model->list_karyawan($sql_filter);
		$data['id_anggota_filter'] = $id_anggota_filter;
		$data['nik_filter'] = $nik_filter;
		$data['nama_anggota_filter'] = $nama_anggota_filter;
		$data['jenis_anggota_filter'] = $jenis_anggota_filter;
		$data['status_anggota_filter'] = $status_anggota_filter;

		if ( $is_excel == "1" ) {
			header('content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=list_anggota_koperasi.xls');
			$this->load->view('view_proj/Pinjaman/list_anggota_excel',$data);
		}else{
			if ( $is_print == '1'){
				$this->load->view('view_proj/tpl_cetak');
				$this->load->view('view_proj/Pinjaman/list_anggota_print',$data);
				$this->load->view('view_proj/tpl_cetak_foot');
			}else{
				$this->load->view('view_proj/Head');
				$this->load->view('view_proj/Pinjaman/list_anggota',$data);
			}
		}

	}

	public function saveAnggota(){
		$res = $this->pinjaman_model->saveData($_POST,'tbl_emp');
		die($res);
	}

	public function detailAnggota(){
		$res = $this->pinjaman_model->getData($_POST,'tbl_emp','teid');
		die($res);	
	}

	public function hapusAnggota(){
		$res = $this->pinjaman_model->delData($_POST,'tbl_emp','teid');
		die($res);	
	}


	public function list_koperasi(){

		$is_excel   = ((isset($_POST['is_excel']) && ($_POST['is_excel']) != '0') || ( isset($_GET['is_excel']) && ($_GET['is_excel']) != '0' ) ) ? "1" : "0";
		$is_print   = ((isset($_POST['is_print']) && ($_POST['is_print']) != '0') || ( isset($_GET['is_print']) && ($_GET['is_print']) != '0' ) ) ? "1" : "0";

		$tgl_start = ( isset($_POST['tgl_start']) && ($_POST['tgl_start']) != '' ) ? $_POST['tgl_start'] : date('m/d/Y');
		if ($tgl_start == '' ){
			$tgl_start = ( isset($_GET['tgl_start']) && ($_GET['tgl_start']) != '' ) ? $_GET['tgl_start'] : date('m/d/Y');
		}

		$tgl_end = ( isset($_POST['tgl_end']) && ($_POST['tgl_end']) != '' ) ? $_POST['tgl_end'] : date('m/d/Y');
		if ($tgl_end == '' ){
			$tgl_end = ( isset($_GET['tgl_end']) && ($_GET['tgl_end']) != '' ) ? $_GET['tgl_end'] : date('m/d/Y');
		}

		$nama_anggota_filter = ( isset($_POST['nama_anggota_filter']) && ($_POST['nama_anggota_filter']) != '' ) ? $_POST['nama_anggota_filter'] : "";
		if ($nama_anggota_filter == '' ){
			$nama_anggota_filter = ( isset($_GET['nama_anggota_filter']) && ($_GET['nama_anggota_filter']) != '' ) ? $_GET['nama_anggota_filter'] : "";
		}

		$sql_filter = "";
		$sql_filter .= ($tgl_start != '' && $tgl_end != '') ? " AND DATE(b.tanggal) BETWEEN '".date('Y-m-d',strtotime($tgl_start))."' AND '".date('Y-m-d',strtotime($tgl_end))."' " : "";
		$sql_filter .= ($nama_anggota_filter !='') ? " AND lower(a.nama_karyawan) like lower('%".$nama_anggota_filter."%')" : "";
		
		$data['list'] = $this->pinjaman_model->list_pinjaman($sql_filter);
		$data['list_karyawan'] = $this->pinjaman_model->list_karyawan();
		$data['tgl_start'] = date('m/d/Y',strtotime($tgl_start));
		$data['tgl_end'] = date('m/d/Y',strtotime($tgl_end));
		$data['nama_anggota_filter'] = $nama_anggota_filter;

		if ( $is_excel == "1" ) {
			header('content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=list_pinjaman_anggota.xls');
			$this->load->view('view_proj/Pinjaman/list_koperasi_excel',$data);
		}else{
			if ( $is_print == '1'){
				$this->load->view('view_proj/tpl_cetak');
				$this->load->view('view_proj/Pinjaman/list_koperasi_print',$data);
				$this->load->view('view_proj/tpl_cetak_foot');
			}else{
				$this->load->view('view_proj/Head');
				$this->load->view('view_proj/Pinjaman/list_koperasi',$data);
			}
		}

	}

	public function saveKoperasi(){
		$res = $this->pinjaman_model->saveData($_POST,'tbl_pinjaman');
		die($res);
	}

	public function detailKoperasi(){
		$res = $this->pinjaman_model->getData($_POST,'tbl_pinjaman','tpid');
		die($res);	
	}

	public function detailPiutangPinjaman(){
		$res = $this->pinjaman_model->getPiutangPinjaman($_POST);
		die($res);	
	}

	public function hapusKoperasi(){
		$res = $this->pinjaman_model->delData($_POST,'tbl_pinjaman','tpid');
		die($res);	
	}



	public function list_cicilan(){

		$is_excel   = ((isset($_POST['is_excel']) && ($_POST['is_excel']) != '0') || ( isset($_GET['is_excel']) && ($_GET['is_excel']) != '0' ) ) ? "1" : "0";
		$is_print   = ((isset($_POST['is_print']) && ($_POST['is_print']) != '0') || ( isset($_GET['is_print']) && ($_GET['is_print']) != '0' ) ) ? "1" : "0";

		$tgl_start = ( isset($_POST['tgl_start']) && ($_POST['tgl_start']) != '' ) ? $_POST['tgl_start'] : date('m/d/Y');
		if ($tgl_start == '' ){
			$tgl_start = ( isset($_GET['tgl_start']) && ($_GET['tgl_start']) != '' ) ? $_GET['tgl_start'] : date('m/d/Y');
		}

		$tgl_end = ( isset($_POST['tgl_end']) && ($_POST['tgl_end']) != '' ) ? $_POST['tgl_end'] : date('m/d/Y');
		if ($tgl_end == '' ){
			$tgl_end = ( isset($_GET['tgl_end']) && ($_GET['tgl_end']) != '' ) ? $_GET['tgl_end'] : date('m/d/Y');
		}

		$nama_anggota_filter = ( isset($_POST['nama_anggota_filter']) && ($_POST['nama_anggota_filter']) != '' ) ? $_POST['nama_anggota_filter'] : "";
		if ($nama_anggota_filter == '' ){
			$nama_anggota_filter = ( isset($_GET['nama_anggota_filter']) && ($_GET['nama_anggota_filter']) != '' ) ? $_GET['nama_anggota_filter'] : "";
		}

		$sql_filter = "";
		$sql_filter .= ($tgl_start != '' && $tgl_end != '') ? " AND DATE(b.tanggal) BETWEEN '".date('Y-m-d',strtotime($tgl_start))."' AND '".date('Y-m-d',strtotime($tgl_end))."' " : "";
		$sql_filter .= ($nama_anggota_filter !='') ? " AND lower(a.nama_karyawan) like lower('%".$nama_anggota_filter."%')" : "";
		
		$data['list'] = $this->pinjaman_model->list_cicilan($sql_filter);
		$data['list_karyawan'] = $this->pinjaman_model->list_pinjaman();
		$data['tgl_start'] = date('m/d/Y',strtotime($tgl_start));
		$data['tgl_end'] = date('m/d/Y',strtotime($tgl_end));
		$data['nama_anggota_filter'] = $nama_anggota_filter;

		if ( $is_excel == "1" ) {
			header('content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=list_cicilan_pinjaman.xls');
			$this->load->view('view_proj/Pinjaman/list_cicilan_excel',$data);
		}else{
			if ( $is_print == '1'){
				$this->load->view('view_proj/tpl_cetak');
				$this->load->view('view_proj/Pinjaman/list_cicilan_print',$data);
				$this->load->view('view_proj/tpl_cetak_foot');
			}else{
				$this->load->view('view_proj/Head');
				$this->load->view('view_proj/Pinjaman/list_cicilan',$data);
			}
		}

	}

	public function saveCicilan(){
		$res = $this->pinjaman_model->saveData($_POST,'tbl_cicilan');
		die($res);
	}

	public function hapusCicilan(){
		$res = $this->pinjaman_model->delData($_POST,'tbl_cicilan','tcid');
		die($res);	
	}


	public function list_sukarela(){

		$is_excel   = ((isset($_POST['is_excel']) && ($_POST['is_excel']) != '0') || ( isset($_GET['is_excel']) && ($_GET['is_excel']) != '0' ) ) ? "1" : "0";
		$is_print   = ((isset($_POST['is_print']) && ($_POST['is_print']) != '0') || ( isset($_GET['is_print']) && ($_GET['is_print']) != '0' ) ) ? "1" : "0";

		$tgl_start = ( isset($_POST['tgl_start']) && ($_POST['tgl_start']) != '' ) ? $_POST['tgl_start'] : date('m/d/Y');
		if ($tgl_start == '' ){
			$tgl_start = ( isset($_GET['tgl_start']) && ($_GET['tgl_start']) != '' ) ? $_GET['tgl_start'] : date('m/d/Y');
		}

		$tgl_end = ( isset($_POST['tgl_end']) && ($_POST['tgl_end']) != '' ) ? $_POST['tgl_end'] : date('m/d/Y');
		if ($tgl_end == '' ){
			$tgl_end = ( isset($_GET['tgl_end']) && ($_GET['tgl_end']) != '' ) ? $_GET['tgl_end'] : date('m/d/Y');
		}

		$nama_anggota_filter = ( isset($_POST['nama_anggota_filter']) && ($_POST['nama_anggota_filter']) != '' ) ? $_POST['nama_anggota_filter'] : "";
		if ($nama_anggota_filter == '' ){
			$nama_anggota_filter = ( isset($_GET['nama_anggota_filter']) && ($_GET['nama_anggota_filter']) != '' ) ? $_GET['nama_anggota_filter'] : "";
		}

		$sql_filter = "";
		$sql_filter .= ($tgl_start != '' && $tgl_end != '') ? " AND DATE(b.tanggal) BETWEEN '".date('Y-m-d',strtotime($tgl_start))."' AND '".date('Y-m-d',strtotime($tgl_end))."' " : "";
		$sql_filter .= ($nama_anggota_filter !='') ? " AND lower(a.nama_karyawan) like lower('%".$nama_anggota_filter."%')" : "";
		
		$data['list'] = $this->pinjaman_model->list_sukarela($sql_filter);
		$data['list_karyawan'] = $this->pinjaman_model->list_karyawan();
		$data['tgl_start'] = date('m/d/Y',strtotime($tgl_start));
		$data['tgl_end'] = date('m/d/Y',strtotime($tgl_end));
		$data['nama_anggota_filter'] = $nama_anggota_filter;

		if ( $is_excel == "1" ) {
			header('content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=list_pinjaman_anggota.xls');
			$this->load->view('view_proj/Pinjaman/list_sukarela_excel',$data);
		}else{
			if ( $is_print == '1'){
				$this->load->view('view_proj/tpl_cetak');
				$this->load->view('view_proj/Pinjaman/list_sukarela_print',$data);
				$this->load->view('view_proj/tpl_cetak_foot');
			}else{
				$this->load->view('view_proj/Head');
				$this->load->view('view_proj/Pinjaman/list_sukarela',$data);
			}
		}

	}

	public function saveSukarela(){
		$res = $this->pinjaman_model->saveData($_POST,'tbl_sukarela');
		die($res);
	}

	public function detailSukarela(){
		$res = $this->pinjaman_model->getData($_POST,'tbl_sukarela','tsid');
		die($res);	
	}

	public function hapusSukarela(){
		$res = $this->pinjaman_model->delData($_POST,'tbl_sukarela','tsid');
		die($res);	
	}

	public function list_pengambilan(){

		$is_excel   = ((isset($_POST['is_excel']) && ($_POST['is_excel']) != '0') || ( isset($_GET['is_excel']) && ($_GET['is_excel']) != '0' ) ) ? "1" : "0";
		$is_print   = ((isset($_POST['is_print']) && ($_POST['is_print']) != '0') || ( isset($_GET['is_print']) && ($_GET['is_print']) != '0' ) ) ? "1" : "0";

		$tgl_start = ( isset($_POST['tgl_start']) && ($_POST['tgl_start']) != '' ) ? $_POST['tgl_start'] : date('m/d/Y');
		if ($tgl_start == '' ){
			$tgl_start = ( isset($_GET['tgl_start']) && ($_GET['tgl_start']) != '' ) ? $_GET['tgl_start'] : date('m/d/Y');
		}

		$tgl_end = ( isset($_POST['tgl_end']) && ($_POST['tgl_end']) != '' ) ? $_POST['tgl_end'] : date('m/d/Y');
		if ($tgl_end == '' ){
			$tgl_end = ( isset($_GET['tgl_end']) && ($_GET['tgl_end']) != '' ) ? $_GET['tgl_end'] : date('m/d/Y');
		}

		$nama_anggota_filter = ( isset($_POST['nama_anggota_filter']) && ($_POST['nama_anggota_filter']) != '' ) ? $_POST['nama_anggota_filter'] : "";
		if ($nama_anggota_filter == '' ){
			$nama_anggota_filter = ( isset($_GET['nama_anggota_filter']) && ($_GET['nama_anggota_filter']) != '' ) ? $_GET['nama_anggota_filter'] : "";
		}

		$sql_filter = "";
		$sql_filter .= ($tgl_start != '' && $tgl_end != '') ? " AND DATE(b.tanggal) BETWEEN '".date('Y-m-d',strtotime($tgl_start))."' AND '".date('Y-m-d',strtotime($tgl_end))."' " : "";
		$sql_filter .= ($nama_anggota_filter !='') ? " AND lower(a.nama_karyawan) like lower('%".$nama_anggota_filter."%')" : "";
		
		$data['list'] = $this->pinjaman_model->list_pengambilan($sql_filter);
		$data['list_karyawan'] = $this->pinjaman_model->list_karyawan_sukarela();
		$data['tgl_start'] = date('m/d/Y',strtotime($tgl_start));
		$data['tgl_end'] = date('m/d/Y',strtotime($tgl_end));
		$data['nama_anggota_filter'] = $nama_anggota_filter;

		if ( $is_excel == "1" ) {
			header('content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=list_pinjaman_anggota.xls');
			$this->load->view('view_proj/Pinjaman/list_pengambilan_excel',$data);
		}else{
			if ( $is_print == '1'){
				$this->load->view('view_proj/tpl_cetak');
				$this->load->view('view_proj/Pinjaman/list_pengambilan_print',$data);
				$this->load->view('view_proj/tpl_cetak_foot');
			}else{
				$this->load->view('view_proj/Head');
				$this->load->view('view_proj/Pinjaman/list_pengambilan',$data);
			}
		}

	}

	public function savePengambilan(){
		$res = $this->pinjaman_model->saveData($_POST,'tbl_pengambilan_sukarela');
		die($res);
	}

	public function detailPengambilan(){
		$res = $this->pinjaman_model->getData($_POST,'tbl_pengambilan_sukarela','tpsid');
		die($res);	
	}

	public function hapusPengambilan(){
		$res = $this->pinjaman_model->delData($_POST,'tbl_pengambilan_sukarela','tpsid');
		die($res);	
	}

}