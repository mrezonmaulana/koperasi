<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {

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

	public function list_penerimaan(){


		$data['list_penerimaan'] = $this->transaksi_model->data_list('1');
		$this->load->view('view_proj/Transaksi/list_penerimaan',$data);
	}

	public function list_penjualan(){


		$data['list_penerimaan'] = $this->transaksi_model->data_list('2');
		$this->load->view('view_proj/Transaksi/list_penjualan',$data);
	}

	public function list_pemakaian(){


		$data['list_penerimaan'] = $this->transaksi_model->data_list('3');
		$this->load->view('view_proj/Transaksi/list_pemakaian',$data);
	}

	public function hapus_transaksi() {

		$ttbid = $_POST['ttbid'];
		$this->transaksi_model->hapus_transaksi($ttbid);
	}

	public function close_transaksi(){

		$ttbid = $_POST['ttbid'];
		$this->transaksi_model->close_transaksi($ttbid);

	}

	public function add_penerimaan(){

		$data['list_barang'] = $this->masterdata_model->list_barang_search();
		$data['list_satuan'] = $this->masterdata_model->list_satuan();
		$data['list_supplier'] = $this->masterdata_model->list_supplier();
		$data['list_kategori'] = $this->masterdata_model->list_kategori(" AND COALESCE(a.is_makanan,0) = 0");
		$this->load->view('view_proj/Transaksi/add_penerimaan',$data);
	}

	public function add_penjualan(){

		$data['list_barang'] = $this->masterdata_model->list_barang(" AND a.is_aktif = 1 ");
		$data['list_satuan'] = $this->masterdata_model->list_satuan();
		$data['list_supplier'] = $this->masterdata_model->list_supplier(" AND a.is_aktif = 1 ");
		$data['list_kategori'] = $this->masterdata_model->list_kategori(" AND COALESCE(a.is_makanan,0) = 0");
		$this->load->view('view_proj/Transaksi/add_penjualan',$data);
	}

	public function add_pemakaian(){

		$data['list_barang'] = $this->masterdata_model->list_barang();
		$data['list_satuan'] = $this->masterdata_model->list_satuan();
		$data['list_supplier'] = $this->masterdata_model->list_supplier();
		$data['list_kategori'] = $this->masterdata_model->list_kategori(" AND COALESCE(a.is_makanan,0) = 0");
		$this->load->view('view_proj/Transaksi/add_pemakaian',$data);
	}

	public function act_add_penerimaan(){

		$this->transaksi_model->insertData($_POST,'1');

		return redirect('Transaksi/list_penerimaan');
	}

	public function act_add_penjualan(){

		$this->transaksi_model->insertData($_POST,'2');

		return redirect('Transaksi/list_penjualan');
	}

	public function act_add_pemakaian(){

		$this->transaksi_model->insertData($_POST,'3');

		return redirect('Transaksi/list_pemakaian');
	}


	public function list_master_barang(){

		$tkgid = $_POST['tkgid'];
		$is_stok = isset($_POST['is_stok']) ? $_POST['is_stok'] : 'f';
		$with_satuan = isset($_POST['with_satuan']) ? $_POST['with_satuan'] : 'f';
		$tipe_terima = isset($_POST['tipe_terima']) ? $_POST['tipe_terima'] : '';
		$nopo        = isset($_POST['nopo']) ? $_POST['nopo'] : '';
		$tbrid_search        = isset($_POST['tbrid_search']) ? $_POST['tbrid_search'] : '';

		if ( $is_stok == 't' ) {

			$data = $this->masterdata_model->list_barang_stok("AND a.tkgid = '".$tkgid."'");

		} else {

			if ($with_satuan == 't') {

				$addsql = " AND a.tkgid = '".$tkgid."'";

				if ( $tbrid_search != '0' ) {
					$addsql .= " AND a.tbrid = ".$tbrid_search;
				}

				$data = $this->masterdata_model->list_barang_new_satuan($addsql,$nopo);	
			} else {

				$addsql = " AND a.tkgid = '".$tkgid."'";

				if ( $tbrid_search != '0' ) {
					$addsql .= " AND a.tbrid = ".$tbrid_search;
				}

				$data = $this->masterdata_model->list_barang($addsql);	
			}

		}
		

		die(json_encode($data->result_array()));
	}

	public function get_po(){

		$data = $this->transaksi_model->list_po();

		$data_list = json_encode($data->result_array());

		die($data_list);
	}

	public function get_barang(){

		$ttbid = $_POST['po_id'];

		$data = $this->transaksi_model->detail_po($ttbid);

		$data_list = json_encode($data->result_array());

		die($data_list);	
	}

	public function edit_penjualan(){

		$ttbid = $_GET['ttbid'];
		$type  = $_GET['type'];

		$data['header'] = $this->transaksi_model->data_list($type,$ttbid);
		$data['detail'] = $this->transaksi_model->detail_penerimaan($ttbid,$type);

		$tspid = $data['header']->tspid;

		$data['list_barang'] = $this->masterdata_model->list_barang(" AND a.is_aktif = 1 ");
		$data['list_satuan'] = $this->masterdata_model->list_satuan();
		$data['list_supplier'] = $this->masterdata_model->list_supplier(" AND (CASE WHEN a.tspid <> ".$tspid." THEN a.is_aktif ELSE 1 END)  = 1 ");
		$data['list_kategori'] = $this->masterdata_model->list_kategori();
		$this->load->view('view_proj/Transaksi/edit_penjualan',$data);
	}

	public function cek_stok(){

		$tbrid = $_POST['tbrid'];

		$data = $this->masterdata_model->list_barang_stok(" AND a.tbrid = '".$tbrid."' ");

		die(json_encode($data->row()));
	}

	public function act_approve_po(){

		$data_post = array();
		$data_post['ttbid'] = $_POST['ttbid'];
		$data_post['ket_approve'] = $_POST['ket_approve'];
		$data_post['status_approve'] = ($_POST['status_approve']=='ya') ? '1' : '2';

		$data = $this->transaksi_model->act_approve_po($data_post);
	}

	public function list_migrasi(){

		$this->load->view('view_proj/Transaksi/list_migrasi');
	}

	public function download_template(){

	     // Load database and query
	    $query = $this->masterdata_model->list_barang_template();

	    // Load database utility class
	    $this->load->dbutil();
	    // Create CSV output
	    $data = $this->dbutil->csv_from_result($query);

	    // Load download helper
	    $this->load->helper('download');
	    // Stream download
	    force_download('template_migrasi_stok_barang.csv', $data);

	}

	public function download_template_stockan(){

	     // Load database and query

	    $query = $this->masterdata_model->list_barang_template(" AND COALESCE(e.is_manual,0)  = 1 ");

	    // Load database utility class
	    $this->load->dbutil();
	    // Create CSV output
	    $data = $this->dbutil->csv_from_result($query);

	    // Load download helper
	    $this->load->helper('download');
	    // Stream download
	    force_download('template_migrasi_stok_barang.csv', $data);

	}

	public function act_upload_migrasi()
	{

		$tgl_trans = $_POST['tgl_trans'];
		$keterangan = $_POST['keterangan'];

		$datas = array();
		$datas['tgl_trans'] = $tgl_trans;
		$datas['keterangan'] = $keterangan;

		if ( isset($_POST['import'])) {

            $file = $_FILES['userfile']['tmp_name'];

			// Medapatkan ekstensi file csv yang akan diimport.
			$ekstensi  = explode('.', $_FILES['userfile']['name']);

			// Tampilkan peringatan jika submit tanpa memilih menambahkan file.
			if (empty($file)) {
				echo 'File tidak boleh kosong!';
			} else {
				// Validasi apakah file yang diupload benar-benar file csv.
				if (strtolower(end($ekstensi)) === 'csv' && $_FILES["userfile"]["size"] > 0) {

					$i = 0;
					$handle = fopen($file, "r");
					$fp = fread( fopen($file, "r"), filesize($_FILES["userfile"]['tmp_name']));			
					if(strpos($fp, "\t")!==false){ //default, tp bisa disesuaikan, lihat code dibawah
						$sep = "\t";
					}elseif(strpos($fp, ",")!==false){
						$sep = ",";
					}else{
						$sep = ";";
					}		


					$data = array();
					$no = 0;
					while (($row = fgetcsv($handle, 2048,$sep))) {
						$i++;
						if ($i == 1) continue;
						
						if (intval($row[0]) > 0) {
							$data[$no] = [
								'tbrid'  => $row[0],
								'nama_barang' => $row[1],
								'tsid' => $row[2],
								'nama_satuan' => $row[3],
								'vol' => $row[4],
							];

							// Simpan data ke database.
							//$this->pelanggan->save($data);
							$no++;
						}
					}

					$this->transaksi_model->insert_migration($datas,$data);

					fclose($handle);
					//redirect('data');

				} else {
					echo 'Format file tidak valid!';
				}
			}
        }

        return redirect('Transaksi/list_migrasi');
	}
}