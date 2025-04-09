<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasir extends CI_Controller {

		public function __construct()
	{
		parent::__construct();
		$this->load->helper("url");
		$this->load->model('project_model');
		$this->load->model('masterdata_model');
		$this->load->model('project_model');
		$this->load->model('ajuan_model');
		$this->load->model('pembayaran_model');
		$this->load->model('transaksi_model');

		if (empty($_SESSION['tuid'])) {

			return redirect('');
		}

		
		$this->load->view('view_proj/Head');
		
		 
	}

	public function list_bill(){

		$data_filter['tgl1'] = (isset($_POST['tgl1'])) ? $_POST['tgl1'] : date('m/d/Y');
		$data_filter['tgl2'] = (isset($_POST['tgl2'])) ? $_POST['tgl2'] : date('m/d/Y');
		$data_filter['status_bill'] = (isset($_POST['status_bill'])) ? $_POST['status_bill'] : '';

		$addsql = " AND DATE(a.tgl_bill) BETWEEN '".date('Y-m-d',strtotime($data_filter['tgl1']))."' AND '".date('Y-m-d',strtotime($data_filter['tgl2']))."' ";

		if ( $data_filter['status_bill'] != '' ) {

			if ( $data_filter['status_bill'] == 'f' ) {

				$addsql .= " AND a.is_final = 3 AND round(COALESCE(h.amount_bayar,0)) = 0";

			} else if ( $data_filter['status_bill'] == 'p'){
				$addsql .= " AND a.is_final = 3 AND round(COALESCE(h.amount_bayar,0)) > 0";
			} else {

				$addsql .= " AND a.is_final = 1 ";

			} 
		}

		$data = array();
		foreach ($data_filter as $key => $value) {
			$data[$key] = $value;
		}

		$data['list_penerimaan'] = $this->transaksi_model->list_bill($addsql);
		$this->load->view('view_proj/Kasir/list_bill',$data);
	}

	public function add_bill($tbmid=0){

		$data['list_barang'] = $this->masterdata_model->list_barang(" AND (CASE WHEN COALESCE(f.is_manual,0) = 0 THEN a.harga_jual ELSE 1 END) <> 0 AND a.is_aktif = 1 ");
		$data['list_konsumen'] = $this->masterdata_model->list_konsumen(" AND a.is_aktif = 1");
		$data['list_bill']   = $this->transaksi_model->list_bill(" AND a.tbmid = ".$tbmid)->row();	
		$data['tbmid']       = $tbmid;
		$this->load->view('view_proj/Kasir/add_bill',$data);
	}

	public function edit_bill($tbmid=0){

		$data['list_barang'] = $this->masterdata_model->list_barang(" AND a.harga_jual <> 0 AND a.is_aktif = 1 ");
		$data['list_bill']   = $this->transaksi_model->list_bill(" AND a.tbmid = ".$tbmid)->row();
		$data['list_detail']   = $this->transaksi_model->list_bill_detail(" AND a.tbmid = ".$tbmid);
		$data['list_konsumen'] = $this->masterdata_model->list_konsumen(" AND a.is_aktif = 1");
		$data['list_edc'] = $this->masterdata_model->list_edc(" AND is_aktif = '1' AND is_aktif = 1 ");
		$data['tbmid']       = $tbmid;
		$this->load->view('view_proj/Kasir/edit_bill',$data);
	}

	public function act_add_bill(){

		$data = $this->transaksi_model->insert_bill($_POST);
		if (isset($_POST['tbmid']) AND $_POST['tbmid'] > 0){
			if ( $_POST['is_final'] != 1 ) {
				return redirect('Kasir/edit_bill/'.$_POST['tbmid']);
			}else{
				if ( $_POST['is_final'] == 1 AND ( $_POST['total_bayar'] > $_POST['total_bayar_pasien'] ) ){
					return redirect('Kasir/edit_bill/'.$_POST['tbmid']);
				}else{
					return redirect('Kasir/list_bill');	
				}
			}
		}else{
			return redirect('Kasir/edit_bill/'.$data);
		}
	}

	public function cancel_pembayaran(){

		$tbmid = $_POST['tbmid'];
		$type  = $_POST['type'];

		$this->transaksi_model->cancel_pembayaran($tbmid,$type);
	}

	public function list_master_barang() {

		$tbrid = $_POST['tbrid'];
		$data = $this->masterdata_model->list_barang_stok(" AND a.tbrid = ".$tbrid." AND a.harga_jual <> 0 "," AND a.tbrid = ".$tbrid," AND zz.tbrid = ".$tbrid," AND a.tbrid = ".$tbrid);

		$hasil = json_encode($data->result_array());

		die($hasil);
	}

}