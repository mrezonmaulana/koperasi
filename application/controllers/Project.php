<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends CI_Controller {

		public function __construct()
	{
		parent::__construct();
		$this->load->helper("url");
		$this->load->model('project_model');
		$this->load->model('masterdata_model');

		if (empty($_SESSION['tuid'])) {

			return redirect('');
		}
	}

	public function search_wilayah(){

		$term = $_GET['term'];

		$row_detail = $this->project_model->getAlamat($term);

		$data = array();
		foreach ( $row_detail->result_array() as $k){
			  $datas = array('label'=>$k['kelurahan'],'value'=>$k['kode_pos']);
			  array_push($data,$datas);
		}



		/*$haha = array(
						array('label'=> 'aaa','value' => '1'),
						array('label'=> 'bbb','value' => '2'),
						array('label'=> 'ccc','value' => '3'),
					);*/
		die(json_encode($data));
	}

	public function list_ourproject(){

		$data['list_project'] = $this->project_model->data_list('tbl_project','tpid');
		$this->load->view('view_proj/Head');
		$this->load->view('view_proj/Project/list_ourproject',$data);
	}

	public function add_newproject(){

		$data['list_bidang'] = $this->masterdata_model->list_bidang();
		$data['list_truck'] = $this->masterdata_model->list_kendaraan();
		$data['list_satuan'] = $this->masterdata_model->list_satuan();
		$data['list_barang'] = $this->masterdata_model->list_barang();
		$data['list_pelaksana'] = $this->masterdata_model->list_pelaksana('1');
		$data['list_kepalatukang'] = $this->masterdata_model->list_pelaksana('2');
		$data['list_perusahaan'] = $this->masterdata_model->list_perusahaan();
		$this->load->view('view_proj/Head');
		$this->load->view('view_proj/Project/tambah_project',$data);
	}

	public function edit_ourproject($tpid) {

		$data['list_bidang'] = $this->masterdata_model->list_bidang();
		$data['list_pelaksana'] = $this->masterdata_model->list_pelaksana('1');
		$data['list_kepalatukang'] = $this->masterdata_model->list_pelaksana('2');
		$data['list_project'] = $this->project_model->list_project_row($tpid);
		$data['list_perusahaan'] = $this->masterdata_model->list_perusahaan();
		$this->load->view('view_proj/Head');
		$this->load->view('view_proj/Project/edit_project',$data);
	}

	public function list_project_teid() {

		$teid_pelaksana = $_POST['teid_pelaksana'];

		$data['list_project'] = $this->project_model->data_list_teid($teid_pelaksana);

		$jml_project = $data['list_project']->num_rows();

		if ( $jml_project > 0 ) {

			$data_project = array();

			$seq = 0;
			foreach ($data['list_project']->result_array() as $key => $value) {
					$data_project[$seq] = array(
														'tpid'   => $value['tpid'],
														'nama_project' => $value['nama_project'].' ( '.$value['nama_bidang'].' )',
														'anggaran' => $value['anggaran'],
														'nilai_barang' => $value['total_barang'],
													);

			$seq++;
			}

			/*die(print_r($data_project));*/
			die(json_encode(array('detail_data'=>$data_project)));

		}

	}

	public function act_add_barang_project() {


		$tpid = (isset($_POST['tpid'])) ? $_POST['tpid'] : array();
		$tgl_kirim = (isset($_POST['tgl_kirim'])) ? $_POST['tgl_kirim'] : array();
		$no_faktur = (isset($_POST['no_faktur'])) ? $_POST['no_faktur'] : array();
		$tbrid = (isset($_POST['tbrid'])) ? $_POST['tbrid'] : array();
		$tkid = (isset($_POST['pengirim'])) ? $_POST['pengirim'] : array();
		$vol = (isset($_POST['jumlah'])) ? $_POST['jumlah'] : array();
		$satuan = (isset($_POST['satuan'])) ? $_POST['satuan'] : array();
		$harga_satuan = (isset($_POST['harga_satuan'])) ? $_POST['harga_satuan'] : array();
		$total = (isset($_POST['subtot'])) ? $_POST['subtot'] : array();
		$sisa_guna_pagu = (isset($_POST['sisa_guna_pagu'])) ? $_POST['sisa_guna_pagu'] : array();
		$from_data = (isset($_POST['from_data'])) ? $_POST['from_data'] : array();


		$this->project_model->updateProject($tpid,$sisa_guna_pagu);
		$cek_data = $this->project_model->cekDetailProject($tpid);

		foreach ($tgl_kirim as $key => $value) {

			if ($no_faktur[$key] != '0' AND $no_faktur[$key] != '') {

				$data['tpid'] = $tpid;
				$data['tgl_kirim'] = date('Y-m-d',strtotime($value));
				$data['no_faktur'] = $no_faktur[$key];
				$data['tbrid'] = $tbrid[$key];
				$data['tkid'] = $tkid[$key];
				$data['vol'] = $vol[$key];
				$data['satuan'] = $satuan[$key];
				$data['harga_satuan'] = $harga_satuan[$key];
				$data['total'] = $total[$key];

				$this->project_model->insertData($data,'tbl_project_barang');

			}

		}

		return redirect('Project/list_ourproject');

		
	}

	public function detail_ourproject($tpid) {

		$data['list_bidang'] = $this->masterdata_model->list_bidang();
		$data['list_pelaksana'] = $this->masterdata_model->list_pelaksana('1');
		$data['list_kepalatukang'] = $this->masterdata_model->list_pelaksana('2');
		$data['list_project'] = $this->project_model->list_project_row($tpid);
		$data['list_barang'] = $this->masterdata_model->list_barang();
		$data['list_truck'] = $this->masterdata_model->list_kendaraan();
		$data['list_satuan'] = $this->masterdata_model->list_satuan();
		$data['list_project_barang'] = $this->project_model->list_project_barang($tpid);
		$this->load->view('view_proj/Head');
		$this->load->view('view_proj/Project/detail_project',$data);
	}

	public function act_add_project(){

		$data = array();
		$data['tgl_mulai'] = date('Y-m-d',strtotime($_POST['tgl_mulai']));
		$data['tbid']      = intval($_POST['bidang']);
		$data['teid_pelaksana'] = intval($_POST['pelaksana']);
		$data['teid_kepalatukang'] = intval($_POST['kepala_tukang']);
		$data['nama_project'] = $_POST['nama_project'];
		$data['anggaran'] = (intval($_POST['anggaran']) > 0 ) ? $_POST['anggaran'] : 0;
		$data['alamat']   = $_POST['alamat'];
		$data['keterangan'] = $_POST['keterangan'];
		$data['kode_pos'] = $_POST['kode_pos'];
		$data['kelurahan'] = $_POST['kelurahan'];
		$data['kecamAtan'] = $_POST['kecamatan'];
		$data['kabupaten'] = $_POST['kabupaten'];
		$data['pagu'] = (intval($_POST['pagu']) > 0 ) ? $_POST['pagu'] : 0;
		$data['pot_other_persen']  = $_POST['potongan_lain'];
		$data['pot_other_amount'] =  ($_POST['potongan_lain'] / 100)*$data['pagu'];
		$data['sisa_penggunaan_pagu'] = $data['pagu'] - $data['pot_other_amount'];
		$data['tphid'] = $_POST['tphid'];
		$data['teid_kepala'] = $_POST['teid_kepala'];

		$this->project_model->insertData($data,'tbl_project');

		return redirect('Project/list_ourproject');
	}

	public function act_edit_project(){

		$data = array();
		$data['tgl_mulai'] = date('Y-m-d',strtotime($_POST['tgl_mulai']));
		$data['tbid']      = $_POST['bidang'];
		$data['teid_pelaksana'] = $_POST['pelaksana'];
		$data['teid_kepalatukang'] = $_POST['kepala_tukang'];
		$data['nama_project'] = $_POST['nama_project'];
		$data['anggaran'] = $_POST['anggaran'];
		$data['alamat']   = $_POST['alamat'];
		$data['keterangan'] = $_POST['keterangan'];
		$data['tphid'] = $_POST['tphid'];
		$data['teid_kepala'] = $_POST['teid_kepala'];
		$data['pagu'] = $_POST['pagu'];
		$data['pot_other_persen'] = $_POST['potongan_lain'];
		$data['pot_other_amount'] = $_POST['pot_other_amount'];
		$update_pagu = $_POST['pagu'] - $_POST['pagu_orig'];
		


		$this->project_model->editData($data,'tbl_project','tpid',$_POST['tpid'],$update_pagu);

		return redirect('Project/list_ourproject');
	}

	function hapus_project() {
		$tpid = $_POST['tpid'];
		$this->project_model->hapus_project($tpid);
	}

	function get_kepala() {

		$data['list_detail_usaha'] = $this->masterdata_model->list_perusahaan_row($_POST['tphid']);

		die(json_encode($data));

	}

	function update_tanggal() {
		$this->project_model->update_tgl($_POST);
	}

	function hapus_barang_ajax(){
		$tpbid = $_POST['tpbid'];

		$this->project_model->delete_barang($tpbid);
	}

}