<?php
class Transaksi_model extends CI_Model {
	public function __construct()
	{
		$this->load->database();
		$this->load->model('masterdata_model');
	}

	public function list_po() {

		$sql = "SELECT a.ttbid,a.reff_code,a.tspid,a.tkgid
					FROM tbl_transaksi_barang a
					JOIN (SELECT a.ttbid,sum(a.harga_total) as harga_total 
								from tbl_transaksi_barang_d a
								left join (select b.reff_id,sum(vol) as vol FROM tbl_transaksi_barang_d b where b.reff_id IS NOT NULL GROUP BY b.reff_id) b ON (b.reff_id = a.ttbdid)
								where a.vol > coalesce(b.vol,0)
						  group by a.ttbid having sum(a.harga_total) > 0) c ON (a.ttbid = c.ttbid)
					JOIN tbl_supplier b ON (a.tspid = b.tspid)
					JOIN tbl_kategori d ON (d.tkgid = a.tkgid)
					JOIN tbl_emp e ON (e.teid = a.create_id)
					WHERE a.tipe_trans = '2' AND COALESCE(a.is_closed,0) = 0 AND COALESCE(a.approve_status,3) = 1 ";
		$rs = $this->db->query($sql);

		return $rs;
	}

	public function data_list($tipe,$id="") {
		
		if ( $tipe == 1 ) {

			$addsql = "";

			if ( $id != "" ) {

				$addsql = " AND a.ttbid = '".$id."' ";
			}

			$sql = "SELECT a.ttbid,a.tgl_trans,b.nama_supplier as nama_media,a.total_amount as total,a.no_faktur,d.nama_kategori,d.kode_kategori,a.reff_code,e.nama_karyawan as user,a.ppn_rp,a.total_diskon,c.ada_pemakaian
					FROM tbl_transaksi_barang a
					JOIN (SELECT a.ttbid,sum(a.harga_total) as harga_total,MAX(CASE WHEN COALESCE(b.vol,0) > 0 THEN 1 ELSE 0 END) as ada_pemakaian
								from tbl_transaksi_barang_d a
								left join (select b.reff_id,sum(vol) as vol FROM tbl_transaksi_barang_d b where b.reff_id IS NOT NULL GROUP BY b.reff_id) b ON (b.reff_id = a.ttbdid)
						  group by a.ttbid having sum(a.harga_total) > 0) c ON (a.ttbid = c.ttbid)
					JOIN tbl_supplier b ON (a.tspid = b.tspid)
					JOIN tbl_kategori d ON (d.tkgid = a.tkgid)
					JOIN tbl_emp e ON (e.teid = a.create_id)
					WHERE a.tipe_trans = '".$tipe."' $addsql";

		} else if ( $tipe == 2 ) {

			$addsql = "";

			if ( $id != "" ) {

				$addsql = " AND a.ttbid = '".$id."' ";
			}

			$sql = "SELECT a.ttbid,a.tgl_trans,b.nama_supplier as nama_media,c.harga_total as total,a.no_faktur,a.alamat,a.kode_pos,a.kelurahan,a.kecamatan,a.kabupaten,d.nama_kategori,d.kode_kategori,a.reff_code,e.nama_karyawan as user,0 as ppn_rp,0 as total_diskon,c.ada_terima,coalesce(a.is_closed,0) as is_closed,a.tkgid,a.tspid,a.keterangan,COALESCE(a.approve_status,3) as status_approve,COALESCE(f.nama_karyawan,'-') as user_approve,a.approve_time,a.ket_approve,b.alamat as alamat_supplier,a.no_sj,COALESCE(b.attn,'-') as attn
					FROM tbl_transaksi_barang a
					JOIN (SELECT a.ttbid,sum(a.harga_total) as harga_total,(CASE 
																				WHEN SUM(a.vol) = SUM(COALESCE(b.vol,0)) THEN 2
																				 WHEN SUM(COALESCE(b.vol,0)) > 0 AND SUM(COALESCE(b.vol,0)) < SUM(a.vol) THEN 1
																				 ELSE 0
																			END) as ada_terima
								from tbl_transaksi_barang_d a
								left join (select b.reff_id,sum(vol) as vol FROM tbl_transaksi_barang_d b where b.reff_id IS NOT NULL GROUP BY b.reff_id) b ON (b.reff_id = a.ttbdid)
						  group by a.ttbid having sum(a.harga_total) > 0) c ON (a.ttbid = c.ttbid)
					JOIN tbl_supplier b ON (a.tspid = b.tspid)
					JOIN tbl_kategori d ON (d.tkgid = a.tkgid)
					JOIN tbl_emp e ON (e.teid = a.create_id)
					LEFT JOIN tbl_emp f ON (f.teid = a.approve_id)
					WHERE a.tipe_trans = '".$tipe."' $addsql";
		} else if ( $tipe == 3 ) {

			$addsql = "";

			if ( $id != "" ) {

				$addsql = " AND a.ttbid = '".$id."' ";
			}

			$sql = "SELECT a.ttbid,a.tgl_trans,d.nama_kategori as nama_media,c.harga_total as total,a.no_faktur,a.alamat,a.kode_pos,a.kelurahan,a.kecamatan,a.kabupaten,d.nama_kategori,d.kode_kategori,a.reff_code,e.nama_karyawan as user,0 as ppn_rp,0 as total_diskon,c.ada_terima,coalesce(a.is_closed,0) as is_closed,a.tkgid,a.tspid,a.keterangan,a.tbmid
					FROM tbl_transaksi_barang a
					JOIN (SELECT a.ttbid,sum(a.harga_total) as harga_total,MAX(CASE WHEN COALESCE(b.vol,0) > 0 THEN 1 ELSE 0 END) as ada_terima
								from tbl_transaksi_barang_d a
								left join (select b.reff_id,sum(vol) as vol FROM tbl_transaksi_barang_d b where b.reff_id IS NOT NULL GROUP BY b.reff_id) b ON (b.reff_id = a.ttbdid)
						  group by a.ttbid having sum(a.harga_total) > 0) c ON (a.ttbid = c.ttbid)
					JOIN tbl_kategori d ON (d.tkgid = a.tkgid)
					JOIN tbl_emp e ON (e.teid = a.create_id)
					WHERE a.tipe_trans = '".$tipe."' $addsql";
		}
	
		$data = $this->db->query($sql);

		if ( $id != "" ) {
			return $data->row();
		} else {
			return $data;
		}
	}

	public function detail_penerimaan($id,$jenisTrans) {


		if ( $jenisTrans == 2 ) {
			$sql = "SELECT a.vol,a.harga_satuan,a.harga_total,b.nama_barang,c.satuan,c.tsid,b.tbrid,d.nama_warna,e.isikecil as konversi_satuan,b.lebar,b.gram,a.handfeel,a.no_match,
					concat(b.nama_barang,' - ',d.nama_warna) as nama_barang_new
					FROM tbl_transaksi_barang_d a
					JOIN tbl_barang b ON (a.tbrid = b.tbrid)
					JOIN tbl_satuan c ON (c.tsid = a.tsid)
					JOIN tbl_warna d ON (b.twid = d.twid)
					JOIN tbl_barang_satuan e ON (e.tbrid = a.tbrid AND e.tsid = a.tsid)
					WHERE a.ttbid = ".$id." ORDER BY a.ttbdid ASC";

			$data = $this->db->query($sql);
		}
		else if ( $jenisTrans == 1) {
			$sql = "SELECT round(a.vol) as vol,a.harga_satuan,a.harga_total,concat(b.nama_barang,' - ',d.nama_warna) as nama_barang,c.satuan,c.tsid,b.tbrid
					FROM tbl_transaksi_barang_d a
					JOIN tbl_barang b ON (a.tbrid = b.tbrid)
					JOIN tbl_satuan c ON (c.tsid = a.tsid)
					JOIN tbl_warna d ON (d.twid = b.twid)
					WHERE a.ttbid = ".$id." ORDER BY a.ttbdid ASC";

			$data = $this->db->query($sql);
		} 
		else {
			$sql = "SELECT a.vol,a.harga_satuan,a.harga_total,b.nama_barang,c.satuan,c.tsid,b.tbrid
					FROM tbl_transaksi_barang_d a
					JOIN tbl_barang b ON (a.tbrid = b.tbrid)
					JOIN tbl_satuan c ON (c.tsid = a.tsid)
					WHERE a.ttbid = ".$id." ORDER BY a.ttbdid ASC";

			$data = $this->db->query($sql);
		}

		return $data;
	}

	public function detail_po($id) {

		$sql = "SELECT a.vol-coalesce(d.vol,0) as vol_sisa,a.harga_satuan,a.harga_total,b.nama_barang,c.satuan,a.tbrid,a.ttbdid,a.tsid
				FROM tbl_transaksi_barang_d a
				JOIN tbl_barang b ON (a.tbrid = b.tbrid)
				JOIN tbl_satuan c ON (c.tsid = a.tsid)
				LEFT JOIN tbl_transaksi_barang_d d ON (d.reff_id = a.ttbdid)

				WHERE a.ttbid = ".$id."
				AND a.vol-coalesce(d.vol,0) > 0
				ORDER BY a.ttbdid ASC";

		$data = $this->db->query($sql);

		return $data;
	}

	public function hapus_transaksi($ttbid) {

		$this->db->query("DELETE FROM tbl_transaksi_barang_d WHERE ttbid = ".$ttbid);
		$this->db->query("DELETE FROM tbl_transaksi_barang WHERE ttbid = ".$ttbid);
	}

	public function close_transaksi($ttbid) {

		$this->db->query("UPDATE tbl_transaksi_barang SET is_closed='1',closed_time='".date('Y-m-d H:i:s')."',closed_id=".$_SESSION['teid']." WHERE ttbid = ".$ttbid);
	}

	public function insertDataStockBill($data,$tipeTrans,$is_edit){

		$count_po = 0;

		if ( isset($data['tbmid']) AND $data['tbmid'] > 0 ) {

			$this->db->query("DELETE FROM tbl_transaksi_barang_d WHERE tbmid = ".$data['tbmid']);
			$this->db->query("DELETE FROM tbl_transaksi_barang WHERE tbmid = ".$data['tbmid']);
			
		}

		if ( $is_edit == '0' ) {

			$query_count = $this->db->query("SELECT COUNT(*) as jml FROM tbl_transaksi_barang 
						WHERE MONTH(tgl_trans) = ".date('m',strtotime($data['tgl_trans']))." AND tipe_trans = ".$tipeTrans."
						AND YEAR(tgl_trans) = ".date('Y',strtotime($data['tgl_trans']))." ");
			$rs_count = $query_count->row();
			$count_po = ($rs_count->jml)+1;

		}

		$data_header = array(
			'tgl_trans' => date('Y-m-d',strtotime($data['tgl_trans'])),
			'tspid'   => ($tipeTrans == 1 OR $tipeTrans == 2) ? $data['tspid'] : 0,
			'no_faktur' => ($tipeTrans == 1) ? $data['no_faktur'] : '-',
			'keterangan' => $data['keterangan'],
			'tkgid'      => $data['tkgid'],
			'tipe_trans'     => $tipeTrans,
			'other_reff_code' => ($tipeTrans==1) ? $data['no_po_real'] : '-',
			'alamat'         => ($tipeTrans == 99) ? $data['alamat'] : '-',
			'kode_pos'         => ($tipeTrans == 99) ? $data['kode_pos'] : '-',
			'kelurahan'         => ($tipeTrans == 99) ? $data['kelurahan'] : '-',
			'kecamatan'         => ($tipeTrans == 99) ? $data['kecamatan'] : '-',
			'kabupaten'         => ($tipeTrans == 99) ? $data['kabupaten'] : '-',
			'total_amount'		=> $data['total'],
			'total_diskon'		=> isset($data['total_diskon']) ? $data['total_diskon'] : 0,
			'ppn_rp'		=> isset($data['ppn_rp']) ? $data['ppn_rp'] : 0,
			'ppn_persen'		=> isset($data['ppn']) ? $data['ppn'] : 0,
			'no_sj'				=> ($tipeTrans == 2) ? $data['no_sj'] : '', 
			'tbmid'				=> $data['tbmid'],
		);

		if ( $is_edit == '0' ){

			$data_header['create_id'] = $_SESSION['teid'];
			$this->db->insert('tbl_transaksi_barang',$data_header);

		}elseif( $is_edit == '1'){

			$data_header['modify_id'] = $_SESSION['teid'];
			$data_header['modify_time'] =date('Y-m-d H:i:s');

			$this->db->where('ttbid',$data['ttbid']);
			$this->db->update('tbl_transaksi_barang',$data_header);
		}

		$data_kategori = $this->masterdata_model->list_kategori_row($data['tkgid']);
		$kode_kategori = $data_kategori->kode_kategori;

		if ( $is_edit == '0' ) {

			$sql_inc = "SHOW TABLE STATUS WHERE name='tbl_transaksi_barang'";
			$row_inc = $this->db->query($sql_inc);
			$data_row   = $row_inc->row();

			$idnya = $data_row->Auto_increment;
			$ttbid = $idnya - 1;

		} else {

			$ttbid = $data['ttbid'];
		}

		if ( $tipeTrans == 2 ) {
			if ( $is_edit == '0') {
				$nomor_pengajuan = str_pad($count_po,5,'0',STR_PAD_LEFT).'/PO/'.$kode_kategori.'/'.date('m',strtotime($data['tgl_trans'])).'/'.date('Y',strtotime($data['tgl_trans']));
				$this->db->query("UPDATE tbl_transaksi_barang SET reff_code = '".$nomor_pengajuan."' WHERE ttbid = ".$ttbid);
			}
		}elseif($tipeTrans == 1){
			if ( $is_edit == '0' ) {
				$nomor_pengajuan = str_pad($count_po,5,'0',STR_PAD_LEFT).'/GRN/'.$kode_kategori.'/'.date('m',strtotime($data['tgl_trans'])).'/'.date('Y',strtotime($data['tgl_trans']));
				$this->db->query("UPDATE tbl_transaksi_barang SET reff_code = '".$nomor_pengajuan."' WHERE ttbid = ".$ttbid);
			}
		}elseif($tipeTrans == 3){
			if ( $is_edit == '0' ) {
				$nomor_pengajuan = str_pad($count_po,5,'0',STR_PAD_LEFT).'/CIU/'.date('m',strtotime($data['tgl_trans'])).'/'.date('Y',strtotime($data['tgl_trans']));
				$this->db->query("UPDATE tbl_transaksi_barang SET reff_code = '".$nomor_pengajuan."' WHERE ttbid = ".$ttbid);
			}
		}

		$tbrid = $_POST['tbrid'];
		$vol   = $_POST['jumlah'];
		$tsid  = $_POST['satuan'];
		$reff_id  = isset($_POST['reff_id']) ? $_POST['reff_id'] : 0;
		$harga_satuan  = $_POST['harga_satuan'];
		$subtot  = $_POST['subtot'];
		$nama_barangs  = $_POST['nama_barang'];

		if ( $tipeTrans == 3 ) {

				foreach ($tbrid as $key => $value) {
					
					$barang_temp = $value;

					$sql_isikecil = $this->db->query("SELECT isikecil FROM tbl_barang_satuan WHERE tsid = '".$data['tsid_stok'][$key]."' AND tbrid = '".$barang_temp."'");
					$rs_isikecil  = $sql_isikecil->row();
					$isikecilnya  = $rs_isikecil->isikecil;

					if ( $tsid[$key] != $data['tsid_stok'][$key] ) {
						$qty_temp = $vol[$key]/$isikecilnya;
						$pembagi[$key] = 1;
						$pengkali[$key] = $isikecilnya;
					}else{
						$qty_temp = $vol[$key]*$isikecilnya;
						$pembagi[$key] = $isikecilnya;
						$pengkali[$key] = 1;
					}

					$rs_stok = $this->masterdata_model->list_barang_stok_bill(" AND a.tbrid = '".$barang_temp."' AND f.tsid = '".$data['tsid_stok'][$key]."'"," AND a.tbrid = '".$barang_temp."'"," AND zz.tbrid = '".$barang_temp."'"," AND a.tbrid = '".$barang_temp."'");
					$stok_now = $rs_stok->row()->stok;
					
					if ( round($stok_now,2) >= round($qty_temp,2) ) {
						
						$rs_new = $this->masterdata_model->list_barang_stok_new(" AND a.tbrid = '".$barang_temp."' AND d.tsid = '".$data['tsid_stok'][$key]."'");

						foreach($rs_new->result_array() as $k){

								if($qty_temp <= 0)
			                    {
			                          break;
			                    }
			                    
								$stok_temps = $qty_temp;
								$qty_temp = $qty_temp - $k['sisa_stok'];

								if ( $qty_temp <= 0 ) {
									$kirim = $stok_temps;
								}else{
									$kirim = $k['sisa_stok'];
								}

								$detail_barang = array (
								'ttbid' => $ttbid,
								'tbrid' => $barang_temp,
								'vol'  => $kirim / $pembagi[$key],
								'tsid' => $tsid[$key],
								'reff_id'		 => $k['ttbdid'],
								'harga_satuan' => $harga_satuan[$key]*$pengkali[$key],
								'harga_total' => ($kirim / $pembagi[$key])*$harga_satuan[$key]*$pengkali[$key],
								'tbmid'				=> $data['tbmid'],
								'tsid_stok'			=> $data['tsid_stok'][$key],
							);

							$this->db->insert('tbl_transaksi_barang_d',$detail_barang);
							
						}

					}else{

						$this->db->query("DELETE FROM tbl_bill_makanan_d WHERE tbmid = ".$data['tbmid']);
						$this->db->query("DELETE FROM tbl_bill_makanan WHERE tbmid = ".$data['tbmid']);
						$this->db->query("DELETE FROM tbl_transaksi_barang_d WHERE tbmid = ".$data['tbmid']);
						$this->db->query("DELETE FROM tbl_transaksi_barang WHERE tbmid = ".$data['tbmid']);

						die("<script languange='javascript'>

										alert('Stok Barang ".$nama_barangs[$key]." Tidak Mencukupi ".$stok_now." - ".$qty_temp."');
										window.location.replace('". base_url('Kasir/list_bill') ."');

							</script>");
					}
				}

		} else {

				foreach( $tbrid as $key => $value ) {

					$detail_barang = array (
						'ttbid' => $ttbid,
						'tbrid' => $value,
						'vol'  => $vol[$key],
						'tsid' => $tsid[$key],
						'reff_id'		 => ($tipeTrans == 1) ? $reff_id[$key] : null,
						'harga_satuan' => $harga_satuan[$key],
						'harga_total' => $subtot[$key],
					);

					$this->db->insert('tbl_transaksi_barang_d',$detail_barang);
				}
		}

	
	}

	public function insertData($data,$tipeTrans) {

		$is_edit = '0';

		$count_po = 0;

		if ( isset($data['ttbid']) AND $data['ttbid'] > 0 ) {

			$this->db->query("DELETE FROM tbl_transaksi_barang_d WHERE ttbid = ".$data['ttbid']);
			//$this->db->query("DELETE FROM tbl_transaksi_barang WHERE ttbid = ".$data['ttbid']);

			$is_edit = '1';
			
		}


		if ( $is_edit == '0' ) {

			$addtsp = "";

			if ( $tipeTrans == 1  ) {
				$addtsp = " AND tspid > 0 ";
			}elseif( $tipeTrans == 2 ) {
				$addtsp = " AND tspid = ".$data['tspid'];
			}

			$query_count = $this->db->query("SELECT MAX(CONVERT(substring(reff_code, 1, 5),DECIMAL)) as jml FROM tbl_transaksi_barang 
						WHERE MONTH(tgl_trans) = ".date('m',strtotime($data['tgl_trans']))." AND tipe_trans = ".$tipeTrans." $addtsp
						AND YEAR(tgl_trans) = ".date('Y',strtotime($data['tgl_trans']))." ");
			$rs_count = $query_count->row();
			$count_po = ($rs_count->jml)+1;

		}

		$id_header_po = 0;

		if ( $tipeTrans == 1 AND isset($data['no_po']) != '') {

			$get_explode_po = explode('_',$data['no_po']);
			$id_header_po = $get_explode_po[0];
		}


		$data_header = array(
			'tgl_trans' => date('Y-m-d',strtotime($data['tgl_trans'])),
			'tspid'   => ($tipeTrans == 1 OR $tipeTrans == 2) ? $data['tspid'] : 0,
			'no_faktur' => ($tipeTrans == 1) ? $data['no_faktur'] : '-',
			'keterangan' => $data['keterangan'],
			'tkgid'      => $data['tkgid'],
			'tipe_trans'     => $tipeTrans,
			'other_reff_code' => ($tipeTrans==1) ? $data['no_po_real'] : '-',
			'alamat'         => ($tipeTrans == 99) ? $data['alamat'] : '-',
			'kode_pos'         => ($tipeTrans == 99) ? $data['kode_pos'] : '-',
			'kelurahan'         => ($tipeTrans == 99) ? $data['kelurahan'] : '-',
			'kecamatan'         => ($tipeTrans == 99) ? $data['kecamatan'] : '-',
			'kabupaten'         => ($tipeTrans == 99) ? $data['kabupaten'] : '-',
			'total_amount'		=> $data['total'],
			'total_diskon'		=> isset($data['total_diskon']) ? $data['total_diskon'] : 0,
			'ppn_rp'		=> isset($data['ppn_rp']) ? $data['ppn_rp'] : 0,
			'ppn_persen'		=> isset($data['ppn']) ? $data['ppn'] : 0,
			'no_sj'				=> ($tipeTrans == 2) ? $data['no_sj'] : '', 
		);

		if ( $is_edit == '0' ){

			$data_header['create_id'] = $_SESSION['teid'];
			$this->db->insert('tbl_transaksi_barang',$data_header);

		}elseif( $is_edit == '1'){

			$data_header['modify_id'] = $_SESSION['teid'];
			$data_header['modify_time'] =date('Y-m-d H:i:s');

			$this->db->where('ttbid',$data['ttbid']);
			$this->db->update('tbl_transaksi_barang',$data_header);
		}

		$data_kategori = $this->masterdata_model->list_kategori_row($data['tkgid']);
		$kode_kategori = $data_kategori->kode_kategori;

		if ( $is_edit == '0' ) {

			$sql_inc = "SHOW TABLE STATUS WHERE name='tbl_transaksi_barang'";
			$row_inc = $this->db->query($sql_inc);
			$data_row   = $row_inc->row();

			$idnya = $data_row->Auto_increment;
			$ttbid = $idnya - 1;

		} else {

			$ttbid = $data['ttbid'];
		}

		if ( $tipeTrans == 2 ) {
			if ( $is_edit == '0') {
				$nomor_pengajuan = str_pad($count_po,5,'0',STR_PAD_LEFT).'/CLP/DNT/'.date('m',strtotime($data['tgl_trans'])).'/'.date('Y',strtotime($data['tgl_trans']));
				$this->db->query("UPDATE tbl_transaksi_barang SET reff_code = '".$nomor_pengajuan."' WHERE ttbid = ".$ttbid);
			}
		}elseif($tipeTrans == 1){
			if ( $is_edit == '0' ) {
				$nomor_pengajuan = str_pad($count_po,5,'0',STR_PAD_LEFT).'/GRN/'.$kode_kategori.'/'.date('m',strtotime($data['tgl_trans'])).'/'.date('Y',strtotime($data['tgl_trans']));
				$this->db->query("UPDATE tbl_transaksi_barang SET reff_code = '".$nomor_pengajuan."' WHERE ttbid = ".$ttbid);
			}
		}elseif($tipeTrans == 3){
			if ( $is_edit == '0' ) {
				$nomor_pengajuan = str_pad($count_po,5,'0',STR_PAD_LEFT).'/CIU/'.date('m',strtotime($data['tgl_trans'])).'/'.date('Y',strtotime($data['tgl_trans']));
				$this->db->query("UPDATE tbl_transaksi_barang SET reff_code = '".$nomor_pengajuan."' WHERE ttbid = ".$ttbid);
			}
		}

		$tbrid = $_POST['tbrid'];
		$vol   = $_POST['jumlah'];
		$tsid  = $_POST['satuan'];
		$reff_id  = isset($_POST['reff_id']) ? $_POST['reff_id'] : 0;
		$harga_satuan  = $_POST['harga_satuan'];
		$subtot  = $_POST['subtot'];
		$nama_barangs  = $_POST['nama_barang'];
		$handfeel = isset($_POST['handfeel']) ? $_POST['handfeel'] : '';
		$no_match = isset($_POST['no_match']) ? $_POST['no_match'] : '';

		if ( $tipeTrans == 3 ) {

				foreach ($tbrid as $key => $value) {
					

					$barang_temp = $value;
					$qty_temp = $vol[$key];
					$rs_stok = $this->masterdata_model->list_barang_stok(" AND a.tbrid = '".$barang_temp."'");
					$stok_now = $rs_stok->row()->stok;
					
					if ( $stok_now >= $qty_temp ) {
						
						$rs_new = $this->masterdata_model->list_barang_stok_new(" AND a.tbrid = '".$barang_temp."' ");


						foreach($rs_new->result_array() as $k){


								if($qty_temp <= 0)
			                    {
			                          break;
			                    }
			                    
								$stok_temps = $qty_temp;
								$qty_temp = $qty_temp - $k['sisa_stok'];

								if ( $qty_temp <= 0 ) {
									$kirim = $stok_temps;
								}else{
									$kirim = $k['sisa_stok'];
								}


								$detail_barang = array (
								'ttbid' => $ttbid,
								'tbrid' => $barang_temp,
								'vol'  => $kirim,
								'tsid' => $tsid[$key],
								'reff_id'		 => $k['ttbdid'],
								'harga_satuan' => $harga_satuan[$key],
								'harga_total' => $kirim*$harga_satuan[$key],
								
							);

							$this->db->insert('tbl_transaksi_barang_d',$detail_barang);
							
							
						}

					}else{

						die("<script languange='javascript'>

										alert('Stok Barang ".$nama_barangs[$key]." Tidak Mencukupi ".$stok_now." - ".$qty_temp."');
										window.location.replace('". base_url('Transaksi/list_pemakaian') ."');

							</script>");
					}
				}

		} else {

				foreach( $tbrid as $key => $value ) {

					$detail_barang = array (
						'ttbid' => $ttbid,
						'tbrid' => $value,
						'vol'  => $vol[$key],
						'tsid' => $tsid[$key],
						'reff_id'		 => ($tipeTrans == 1) ? $reff_id[$key] : null,
						'harga_satuan' => $harga_satuan[$key],
						'harga_total' => $subtot[$key],
						'handfeel' => ($tipeTrans == 2 ) ? $handfeel[$key] : '',
						'no_match' => ($tipeTrans == 2 ) ? $no_match[$key] : '',
					);

					$this->db->insert('tbl_transaksi_barang_d',$detail_barang);
				}
		}

	}

	public function insert_bill($datas) /*{{{*/
	{

		/*if ( $_SESSION['tuid'] == 1 ) {
			var_dump($_POST);
			die();
		}*/


		$is_edit = '0';

		if ( isset($datas['tbmid']) AND $datas['tbmid'] > 0 ) {

			$this->db->query("DELETE FROM tbl_bill_makanan_d WHERE tbmid = ".$datas['tbmid']);
			if ( $datas['sudah_dibayar'] == 0 ) {
				$this->db->query("DELETE FROM tbl_bill_payment WHERE tbmid = ".$datas['tbmid']);
			}
			//$this->db->query("DELETE FROM tbl_transaksi_barang WHERE ttbid = ".$data['ttbid']);

			$is_edit = '1';
			
		}

		$is_final = $datas['is_final'];

		if ( $datas['total_bayar'] > $datas['total_bayar_pasien'] && $datas['is_final'] == 1){
			$is_final = 3;
		}

		$data_header = array(
			'tgl_bill' => date('Y-m-d',strtotime($datas['tgl_trans'])),
			'nama_pelanggan' => $datas['nama_pelanggan'],
			'total_amount'	=> $datas['total'],
			'is_final'      => $is_final,
			'no_meja'		=> $datas['no_meja'],
		);

		if ( $is_edit == '0' ) {
			$data_header['create_id'] = $_SESSION['teid'];
			$this->db->insert('tbl_bill_makanan',$data_header);
		}else{
			$data_header['modify_id'] = $_SESSION['teid'];
			$data_header['modify_time'] = date('Y-m-d H:i:s');

			if ($datas['is_final'] == 1 ) {
			$data_header['total_diskon'] = $_POST['total_diskon'];
			$data_header['ppn_persen'] = $_POST['ppn_persen'];
			$data_header['ppn_rp'] = $_POST['ppn_rp'];
			$data_header['ongkir'] = $_POST['ongkir'];
			$data_header['total_bayar'] = $datas['total'] + $datas['ongkir']; //$_POST['total_bayar'];

			if ( $is_final == 1 ) {

					$data_header['create_time_bayar'] = date('Y-m-d H:i:s');
					$data_header['create_id_bayar'] = $_SESSION['teid'];
					$data_header['cara_bayar'] = $datas['cara_bayar'];

						if ( $data_header['cara_bayar'] == 2 or $data_header['cara_bayar'] == 3 ) {
							$data_header['tedid'] = $datas['tedid'];
							$data_header['no_batch'] = $datas['no_batch'];
							$data_header['no_kartu'] = $datas['no_kartu'];
							if ( $data_header['cara_bayar'] == 3 ) {
								$data_header['atas_nama'] = $datas['atas_nama'];
							}
						}

					$data_header['total_bayar_pasien'] = $datas['sudah_dibayar']+$datas['total_bayar_pasien'];
					$data_header['kembalian'] = $data_header['total_bayar_pasien'] - $data_header['total_bayar'];

				}
			}

			$this->db->where('tbmid',$datas['tbmid']);
			$this->db->update('tbl_bill_makanan',$data_header);
		}

		if ( $is_edit == '0' ) {

			$sql_inc = "SHOW TABLE STATUS WHERE name='tbl_bill_makanan'";
			$row_inc = $this->db->query($sql_inc);
			$data_row   = $row_inc->row();

			$idnya = $data_row->Auto_increment;
			$tbmid = $idnya - 1;
		} else {
			$tbmid = $datas['tbmid'];
		}

		if ( $is_final == 3 ) {

			$nomor_pengajuan = str_pad($tbmid,5,'0',STR_PAD_LEFT).'/BILL/DN/'.date('m',strtotime($datas['tgl_trans'])).'/'.date('Y',strtotime($datas['tgl_trans']));
			$this->db->query("UPDATE tbl_bill_makanan SET no_bill = '".$nomor_pengajuan."' WHERE tbmid = ".$tbmid);

		}else{

			$nomor_pengajuan = str_pad($tbmid,5,'0',STR_PAD_LEFT).'/PAY/DN/'.date('m',strtotime($datas['tgl_trans'])).'/'.date('Y',strtotime($datas['tgl_trans']));
			$this->db->query("UPDATE tbl_bill_makanan SET no_bayar = '".$nomor_pengajuan."' WHERE tbmid = ".$tbmid);
		}



		// S : NEW PEMBAYARAN PARTIAL 
		if ( $datas['is_final'] == 1 ) {

			$data_payment = array();
			$data_payment['tbmid'] = $tbmid;

			$total_dibayarnya = $datas['total_bayar_pasien'];

			if ( $total_dibayarnya > $datas['total_bayar'] ) {

				$total_dibayarnya = $datas['total_bayar'];
			}


			$data_payment['amount']= $total_dibayarnya;
			$data_payment['cara_bayar'] = $datas['cara_bayar'];
			$data_payment['kembalian']  = $data_header['kembalian'];
			$data_payment['create_time'] = date('Y-m-d H:i:s');
			$data_payment['create_id']   = $_SESSION['teid'];
			$data_payment['angunan']	 = $datas['angunan'];
			if ( $datas['cara_bayar'] == 2 or $datas['cara_bayar'] == 3 ) {

				$data_payment['tedid'] = $datas['tedid'];
				$data_payment['no_batch'] = $datas['no_batch'];
				$data_payment['no_kartu'] = $datas['no_kartu'];
				if ( $data_payment['cara_bayar'] == 3 ) {
					$data_payment['atas_nama'] = $datas['atas_nama'];
				}

			}
			if ( $total_dibayarnya > 0 ){
				$this->db->insert('tbl_bill_payment',$data_payment);			
			}
		}

		// E : NEW PEMBAYARAN PARTIAL

		$tbrid = $_POST['tbrid'];
		$jumlah = $_POST['jumlah'];
		$harga_satuan = $_POST['harga_satuan'];
		$subtot = $_POST['subtot'];

		$satuan_barang = $_POST['satuan'];
		$nama_barangs  = $_POST['nama_barang'];
		$satuan_stok   = $_POST['satuan_stok'];
		$diskon        = $_POST['diskon'];
		$harga_satuan_def        = $_POST['harga_satuan_def'];

		// INSERT STOK BARANG NYA
		$dataStok 					= array();
		$dataStok['tbmid'] 			= $tbmid;
		$dataStok['tgl_trans']  	= $datas['tgl_trans'];
		$dataStok['keterangan'] 	= "PENGURANGAN STOK DARI PENJUALAN BARANG [ ".$nomor_pengajuan." ]";
		$dataStok['tkgid']			= 5; // DI HARDCODE KARNA DNN GAPUNYA KATEGORI SEBENENRYA
		$dataStok['total']			= 0;
		$dataStok['total_diskon'] 	= 0;
		$dataStok['ppn_rp'] 		= 0;
		$dataStok['ppn'] 			= 0;

		foreach ($tbrid as $key => $value) {

			$dataStok['tbrid'][$key] = $value;
			$dataStok['jumlah'][$key] = $jumlah[$key];
			$dataStok['satuan'][$key] = $satuan_barang[$key];
			$dataStok['harga_satuan'][$key] = $harga_satuan[$key];
			$dataStok['subtot'][$key] = $subtot[$key];
			$dataStok['nama_barang'][$key] = $nama_barangs[$key];
			$dataStok['tsid_stok'][$key] = $satuan_stok[$key];
			
			$data_detail = array(
				'tbmid' => $tbmid,
				'tbrid' => $value,
				'qty'   => $jumlah[$key],
				'amount'=> $harga_satuan[$key],
				'amount_total' => $subtot[$key],
				'tsid_jual'	   => $satuan_barang[$key],
				'discount_amount' => $diskon[$key],
				'tsid_stok'		=> $satuan_stok[$key],
				'is_manual'		=> ($harga_satuan_def[$key] > 0 ) ? 1 : 0,
			);

			$this->db->insert('tbl_bill_makanan_d',$data_detail);
		}

		$this->transaksi_model->insertDataStockBill($dataStok,3,'0');


		return $tbmid;
		
	} /*}}}*/

	public function cancel_pembayaran($tbmid,$type) {

		if ( $type == 3 ) {

			$sql = "SELECT a.* FROM tbl_bill_makanan a WHERE a.tbmid = ".$tbmid;
			$rs  = $this->db->query($sql)->result_array();

			$data_header = $data_header_new = array();
			foreach ($rs[0] as $key => $value) {
				$data_header[$key] = $value;
			}

			$data_header['cancel_time'] = date('Y-m-d H:i:s');
			$data_header['cancel_id']   = $_SESSION['teid'];
			$this->db->insert('tbl_bill_makanan_backup',$data_header);


			//UPDATE SEMUA KONDISI PAYMENT KE DRAFT
			$data_header_new['total_diskon'] = 0;
			$data_header_new['ppn_persen'] = 0;
			$data_header_new['ppn_rp'] = 0;
			$data_header_new['total_bayar'] = 0;
			$data_header_new['create_time_bayar'] = null;
			$data_header_new['create_id_bayar'] = null;
			$data_header_new['cara_bayar'] = null;
			$data_header_new['tedid'] = null;
			$data_header_new['no_batch'] = null;
			$data_header_new['no_kartu'] = null;
			$data_header_new['total_bayar_pasien'] = null;
			$data_header_new['kembalian'] = null;
			$data_header_new['no_bayar'] = null;
			$data_header_new['ongkir'] = 0;
			$data_header_new['is_final'] = 3;

			$this->db->where('tbmid',$tbmid);
			$this->db->update('tbl_bill_makanan',$data_header_new);
			$this->db->query("DELETE FROM tbl_bill_payment WHERE tbmid = ".$tbmid);




		} else if ( $type == 1 ) {

			$sql = "SELECT a.* FROM tbl_bill_makanan a WHERE a.tbmid = ".$tbmid;
			$rs  = $this->db->query($sql)->result_array();

			$data_header = $data_header_new = $data_detail = array();
			foreach ($rs[0] as $key => $value) {
				$data_header[$key] = $value;
			}

			$data_header['cancel_time'] = date('Y-m-d H:i:s');
			$data_header['cancel_id']   = $_SESSION['teid'];
			$ok = $this->db->insert('tbl_bill_makanan_backup',$data_header);

			$sql_inc = "SHOW TABLE STATUS WHERE name='tbl_bill_makanan_backup'";
			$row_inc = $this->db->query($sql_inc);
			$data_row   = $row_inc->row();

			$idnya = $data_row->Auto_increment;
			$tbmbid = $idnya - 1;

			if ( $ok ) {

				$sql = "SELECT a.* FROM tbl_bill_makanan_d a WHERE a.tbmid = ".$tbmid;
				$rs  = $this->db->query($sql)->result_array();

				foreach ($rs as $key => $value) {
						$data_detail['tbmbid'] = $tbmbid;
						$data_detail['tbmdid'] = $value['tbmdid'];
						$data_detail['tbmid'] = $value['tbmid'];
						$data_detail['tbrid'] = $value['tbrid'];
						$data_detail['qty'] = $value['qty'];
						$data_detail['amount'] = $value['amount'];
						$data_detail['amount_total'] = $value['amount_total'];
						$ok = $this->db->insert('tbl_bill_makanan_d_backup',$data_detail);

				}
			}


			if ( $ok ) { 

				$this->db->query("DELETE FROM tbl_bill_makanan_d WHERE tbmid = ".$tbmid);
				$this->db->query("DELETE FROM tbl_bill_makanan WHERE tbmid = ".$tbmid);

				$this->db->query("DELETE FROM tbl_transaksi_barang_d WHERE tbmid = ".$tbmid);
				$this->db->query("DELETE FROM tbl_transaksi_barang WHERE tbmid = ".$tbmid);

			}

		}
	}

	public function list_bill_detail($addsql=""){


		$sql_d_brg = "SELECT group_concat(a.tbrid) as list_brg FROM tbl_bill_makanan_d a WHERE 1=1 $addsql ";
		$rb        = $this->db->query($sql_d_brg);
		$list_brg_b = $rb->row();
		$list_asli  = $list_brg_b->list_brg;
		$addsql2 = $addsql3 = $addsql4 = $addsql5 = "";
		if ( $list_asli != '' ) {
			$addsql2 = " AND a.tbrid IN (".$list_asli.") ";
			$addsql3 = " AND zz.tbrid IN (".$list_asli.") ";
			$addsql4 = " AND a.tbrid IN (".$list_asli.") ";
		}
		


		$sql = "SELECT DISTINCT f.tbrid,concat(g.nama_barang,' - ',hn.nama_warna) as nama_barang,round(f.qty,2) as qty,f.amount as harga_satuan,f.amount_total,e.tsid,e.tsid_stok,
				xzz.list_satuan as list_satuan,
				g.konversi_satuan,
				zz.satuan as nama_satuan_trans,
				COALESCE(ROUND(xz.stok,2),0) as stok,
				g.satuan_besar,
				g.satuan as satuan_kecil,
				x.satuan as nama_satuan_kecil,
				(CASE WHEN f.tsid_jual = f.tsid_stok THEn f.amount / bs.isikecil ELSE round(f.amount) END) as harga_jual,
				f.discount_amount,
				g.nama_barang as nama_barang_print,
				COALESCE(bs.isikecil,1) as isikeciljual,
				COALESCE(f.is_manual,0) as is_manual
				FROM tbl_bill_makanan a 
				JOIN tbl_bill_makanan_d f ON (a.tbmid = f.tbmid)
				JOIN tbl_barang g ON (g.tbrid = f.tbrid)
				JOIN tbl_emp c ON (a.create_id = c.teid)
				JOIN tbl_transaksi_barang_d e ON (e.tbmid = a.tbmid AND e.tbrid = f.tbrid AND e.tsid = f.tsid_jual AND e.tsid_stok = f.tsid_stok )
				JOIN tbl_satuan x ON (x.tsid = g.satuan)
				LEFT JOIN tbl_satuan z ON (z.tsid = g.satuan_besar)
				JOIN tbl_satuan zz ON (zz.tsid = f.tsid_jual)
				JOIN tbl_warna hn  ON (hn.twid = g.twid)
				LEFT JOIN tbl_emp gg ON (a.create_id_bayar = gg.teid)
				LEFT JOIN tbl_barang_satuan bs ON (bs.tsid = f.tsid_jual AND bs.tbrid = f.tbrid)
				LEFT JOIN (
					SELECT zz.tbrid,group_concat(json_quote(concat(zz.tsid,'_',ROUND(ROUND(zz.isikecil,2)*zzz.hna),'_',xzx.satuan,'_',d.stok,'_',zz.isikecil)) SEPARATOR ':') as list_satuan

					FROM tbl_barang_satuan zz
					JOIN tbl_satuan xzx ON (zz.tsid = xzx.tsid)
					JOIN tbl_barang zzz ON (zzz.tbrid = zz.tbrid)

					JOIN (

					SELECT a.tbrid,
					SUM(CASE WHEN b.tipe_trans = 1 
							 THEN COALESCE(a.vol,0)*c.isikecil
							 ELSE coalesce(COALESCE(a.vol,0)*c.isikecil *-1,0) 
					END) as stok,COALESCE(a.tsid_stok,a.tsid) as tsid

					FROM tbl_transaksi_barang_d a
					JOIN tbl_transaksi_barang b ON (a.ttbid = b.ttbid)
					JOIN tbl_barang_satuan c ON (c.tbrid = a.tbrid AND c.tsid = COALESCE(a.tsid_stok,a.tsid))
					WHERE a.reff_id IS NOT NULL AND b.tipe_trans IN (1,3) $addsql2
					GROUP BY a.tbrid,COALESCE(a.tsid_stok,a.tsid) 

					) d ON (d.tbrid = zz.tbrid AND zz.tsid = d.tsid)
					WHERE 1=1 $addsql3

					GROUP BY zz.tbrid
				) xzz ON xzz.tbrid = f.tbrid

				JOIN (

					SELECT a.tbrid,
					SUM(CASE WHEN b.tipe_trans = 1 
							 THEN COALESCE(a.vol,0)*c.isikecil 
							 ELSE coalesce(COALESCE(a.vol,0)*c.isikecil *-1,0) 
					END) as stok 

					FROM tbl_transaksi_barang_d a
					JOIN tbl_transaksi_barang b ON (a.ttbid = b.ttbid)
					JOIN tbl_barang_satuan c ON (c.tbrid = a.tbrid AND c.tsid = COALESCE(a.tsid_stok,a.tsid))
					WHERE a.reff_id IS NOT NULL AND b.tipe_trans IN (1,3) $addsql4
					GROUP BY a.tbrid

				) xz ON (xz.tbrid = f.tbrid)
				WHERE 1=1 $addsql
				ORDER BY a.tbmid";
		$rs  = $this->db->query($sql);

	/*	if ( $_SESSION['tuid'] == 1) {
			die ("<pre>".$sql."</pre>");
		}
*/
		return $rs;
	}

	public function list_kasir(){

		$sql = "SELECT DISTINCT a.teid,a.nama_karyawan FROM tbl_emp a
				JOIN tbl_bill_makanan b ON ( a.teid = b.create_id OR a.teid = b.create_id_bayar )";
		$rs = $this->db->query($sql);

		return $rs;
	}

	public function list_bill($addsql=""){

		$sql = "SELECT a.no_bill,a.tgl_bill as tgl_trans,a.nama_pelanggan,c.nama_karyawan as user,(CASE WHEN a.is_final = 1 THEN a.total_bayar 
																										ELSE a.total_amount+round(coalesce(a.ongkir,0)) END) as total_amount,a.tbmid,a.is_final,COALESCE(a.no_bayar,'-') as no_bayar,a.create_time_bayar,
				(CASE 
						WHEN a.is_final = 3 THEN 'Draft'
						WHEN a.is_final = 2 THEN 'Checkout'
						WHEN a.is_final = 1 THEN 'Final' 
				END) as status,COALESCE(e.nama_karyawan,'-') as user_bayar,a.no_meja,(CASE WHEN a.cara_bayar=1 THEN 'CASH' 
																						   WHEN a.cara_bayar=2 THEN 'DEBET / CREDIT'
																						   WHEN a.cara_bayar=3 THEN 'TRANSFER'
																						   ELSE '-' END ) as cara_bayar,
																					a.cara_bayar as int_cara_bayar,
																					(CASE WHEN a.cara_bayar IN (2,3) THEN f.nama_edc ELSE null END) as nama_edc,
																					a.no_batch,a.no_kartu,a.ppn_persen,a.ppn_rp,a.total_amount as total_tagihan,a.total_bayar,a.total_diskon,a.create_time as bill_date,a.total_bayar_pasien,COALESCE(a.kembalian,0) as kembalian,
																					g.nama_konsumen,g.alamat as alamat_konsumen,
																					a.nm_mobil,a.nopol_mobil,a.nm_mobil_exs,a.nopol_mobil_exs,a.kpd_yth,a.banyaknya,a.barangnya1,a.barangnya4,a.barangnya5,a.barangnya6,g.no_telp as telp_konsumen,
																					round(COALESCE(h.amount_bayar,0)) as amount_bayar_partial,ROUND(COALESCE(a.ongkir,0)) as ongkir_bill
				FROM tbl_bill_makanan a 
				JOIN tbl_emp c ON (a.create_id = c.teid)
				LEFT JOIN tbl_emp e ON (a.create_id_bayar = e.teid)
				LEFT JOIN tbl_edc f ON (f.tedid = a.tedid)
				JOIN tbl_konsumen g ON (g.tknid = a.nama_pelanggan)
				LEFT JOIN (SELECT SUM(amount) as amount_bayar,tbmid FROM tbl_bill_payment GROUP BY tbmid ) h ON (h.tbmid = a.tbmid)
				WHERE 1=1 $addsql
				ORDER BY a.tbmid DESC";
		$rs  = $this->db->query($sql);

		return $rs;
	}

	public function act_approve_po($data) {

		$data_list = array(
						'approve_time' => date('Y-m-d H:i:s'),
						'approve_id'   => $_SESSION['teid'],
						'approve_status' => $data['status_approve'],
						'ket_approve'    => $data['ket_approve'],
					);

		$this->db->where('ttbid',$data['ttbid']);
		$this->db->update('tbl_transaksi_barang',$data_list);

	}

	public function all_stok($addsql="") {
		$sql = "
				SELECT c.nama_barang,c.gram,c.lebar,c.harga_jual,d.kd_jenis,d.nm_jenis,e.nama_tipe,f.nama_warna,
					   SUM((CASE WHEN g.tipe_trans = 1 THEN a.vol ELSE a.vol*-1 END)*b.isikecil) as jml_stok_kg,
					   SUM((CASE WHEN g.tipe_trans = 1 THEN a.vol ELSE a.vol*-1 END)) as jml_stok_roll,
					   b.tsid,h.satuan as nama_satuan,
					   i.satuan as satuan_kecil
				FROM tbl_transaksi_barang_d a 
				JOIN tbl_barang_satuan b ON (a.tbrid = b.tbrid AND COALESCE(a.tsid_stok,a.tsid) = b.tsid)
				JOIN tbl_barang c ON (b.tbrid = c.tbrid)
				JOIN tbl_jenis_barang d ON (c.tjbid = d.tjbid)
				JOIN tbl_type_barang e ON (c.typbid = e.typbid)
				JOIN tbl_warna f ON (c.twid = f.twid)
				JOIN tbl_transaksi_barang g ON (a.ttbid = g.ttbid)
				JOIN tbl_satuan h ON (h.tsid = b.tsid)
				JOIN tbl_satuan i ON (i.tsid = c.satuan)
				WHERE 1=1 AND g.tipe_trans IN (1,3) $addsql
				GROUP BY c.tbrid,c.nama_barang,c.gram,c.lebar,c.harga_jual,d.kd_jenis,d.nm_jenis,e.nama_tipe,f.nama_warna,b.tsid,h.satuan,i.satuan
				ORDER BY c.tbrid,c.nama_barang ASC

		";

		$rs = $this->db->query($sql);

		return $rs;
	}

	public function all_stok_new_jml($addsql="") {
		$sql = "
				SELECT count(a.tbrid) as jml 
				FROM tbl_transaksi_barang_d a 
				JOIN tbl_barang c ON (a.tbrid = c.tbrid)
				JOIN tbl_transaksi_barang g ON (a.ttbid = g.ttbid)
				JOIN tbl_satuan h ON (h.tsid = COALESCE(a.tsid_stok,a.tsid))
				WHERE 1=1 AND g.tipe_trans IN (1,3) $addsql
				LIMIT 1
		";

		$rs = $this->db->query($sql);

		return $rs;
	}

	public function all_stok_new_excel($addsql="",$addsql2,$tipe_lap) {



		if ( $tipe_lap == 1 ) {
				$sql = "
						SELECT c.tbrid as id_barang,
							   concat(c.nama_barang,' - ',f.nama_warna) as nama_barang,
								0 as id_satuan,
							   'ROLL' as nama_satuan,
							   SUM((CASE WHEN g.tipe_trans = 1 THEN a.vol ELSE a.vol*-1 END)) as jumlah_roll,
							   SUM((CASE WHEN g.tipe_trans = 1 THEN a.vol ELSE a.vol*-1 END)*b.isikecil) as jumlah_kg,
							   '' as stok_fisik_dalam_roll,
							   '' as kuantiti_penyesuaian
						FROM tbl_transaksi_barang_d a 
						JOIN tbl_barang_satuan b ON (a.tbrid = b.tbrid AND COALESCE(a.tsid_stok,a.tsid) = b.tsid)
						JOIN tbl_barang c ON (a.tbrid = c.tbrid $addsql2)
						JOIN tbl_warna f ON (c.twid = f.twid)
						JOIN tbl_transaksi_barang g ON (a.ttbid = g.ttbid)
						WHERE 1=1 AND g.tipe_trans IN (1,3) $addsql
						GROUP BY c.tbrid,concat(c.nama_barang,'-',f.nama_warna)
						HAVING SUM((CASE WHEN g.tipe_trans = 1 THEN a.vol ELSE a.vol*-1 END)) <> 0
						ORDER BY c.nama_barang ASC

				";
		} else {
			$sql = "
						SELECT c.tbrid as id_barang,
							   concat(c.nama_barang,' - ',f.nama_warna) as nama_barang,
							   b.tsid as id_satuan,
						 	   h.satuan as nama_satuan,
							   SUM((CASE WHEN g.tipe_trans = 1 THEN a.vol ELSE a.vol*-1 END)) as jumlah_roll,
							   SUM((CASE WHEN g.tipe_trans = 1 THEN a.vol ELSE a.vol*-1 END)*b.isikecil) as jumlah_kg,
							   '' as stok_fisik_dalam_roll,
							   '' as kuantiti_penyesuaian
						FROM tbl_transaksi_barang_d a 
						JOIN tbl_barang_satuan b ON (a.tbrid = b.tbrid AND COALESCE(a.tsid_stok,a.tsid) = b.tsid)
						JOIN tbl_barang c ON (a.tbrid = c.tbrid $addsql2)
						JOIN tbl_warna f ON (c.twid = f.twid)
						JOIN tbl_transaksi_barang g ON (a.ttbid = g.ttbid)
						JOIN tbl_satuan h ON (h.tsid = b.tsid)
						WHERE 1=1 AND g.tipe_trans IN (1,3) $addsql
						GROUP BY c.tbrid,concat(c.nama_barang,'-',f.nama_warna),b.tsid,h.satuan
						HAVING SUM((CASE WHEN g.tipe_trans = 1 THEN a.vol ELSE a.vol*-1 END)) <> 0
						ORDER BY c.nama_barang ASC

				";
		}
		
		$rs = $this->db->query($sql);

		return $rs;
	}

	public function all_stok_new($addsql="",$addsql2,$tipe_lap) {



		if ( $tipe_lap == 1 ) {
				$sql = "
						SELECT c.nama_barang,f.nama_warna,
								0 as tsid,
							   'ROLL' as nama_sat,
							   SUM((CASE WHEN g.tipe_trans = 1 THEN a.vol ELSE a.vol*-1 END)) as jml_stok_roll,
							   SUM((CASE WHEN g.tipe_trans = 1 THEN a.vol ELSE a.vol*-1 END)*b.isikecil) as jml_stok_kg
						FROM tbl_transaksi_barang_d a 
						JOIN tbl_barang_satuan b ON (a.tbrid = b.tbrid AND COALESCE(a.tsid_stok,a.tsid) = b.tsid)
						JOIN tbl_barang c ON (a.tbrid = c.tbrid $addsql2)
						JOIN tbl_warna f ON (c.twid = f.twid)
						JOIN tbl_transaksi_barang g ON (a.ttbid = g.ttbid)
						WHERE 1=1 AND g.tipe_trans IN (1,3) $addsql
						GROUP BY c.tbrid,c.nama_barang,f.nama_warna
						HAVING SUM((CASE WHEN g.tipe_trans = 1 THEN a.vol ELSE a.vol*-1 END)) <> 0
						ORDER BY c.nama_barang ASC

				";
			
				
		} else {
			$sql = "
						SELECT c.nama_barang,f.nama_warna,
							   b.tsid,
						 	   h.satuan as nama_sat,
							   SUM((CASE WHEN g.tipe_trans = 1 THEN a.vol ELSE a.vol*-1 END)) as jml_stok_roll,
							   SUM((CASE WHEN g.tipe_trans = 1 THEN a.vol ELSE a.vol*-1 END)*b.isikecil) as jml_stok_kg
						FROM tbl_transaksi_barang_d a 
						JOIN tbl_barang_satuan b ON (a.tbrid = b.tbrid AND COALESCE(a.tsid_stok,a.tsid) = b.tsid)
						JOIN tbl_barang c ON (a.tbrid = c.tbrid $addsql2)
						JOIN tbl_warna f ON (c.twid = f.twid)
						JOIN tbl_transaksi_barang g ON (a.ttbid = g.ttbid)
						JOIN tbl_satuan h ON (h.tsid = b.tsid)
						WHERE 1=1 AND g.tipe_trans IN (1,3) $addsql
						GROUP BY c.tbrid,c.nama_barang,f.nama_warna,b.tsid,h.satuan
						HAVING SUM((CASE WHEN g.tipe_trans = 1 THEN a.vol ELSE a.vol*-1 END)) <> 0
						ORDER BY c.nama_barang ASC

				";
		}
		
		$rs = $this->db->query($sql);

		return $rs;
	}

	public function all_stok_detail($addsql="") {
		$sql = "
				SELECT 
					   g.ttbid,
					   g.tgl_trans,
					   c.nama_barang,c.gram,c.lebar,c.harga_jual,d.kd_jenis,d.nm_jenis,e.nama_tipe,f.nama_warna,
					   ((CASE WHEN g.tipe_trans = 1 THEN a.vol ELSE a.vol*-1 END)*b.isikecil) as jml_stok_kg,
					   ((CASE WHEN g.tipe_trans = 1 THEN a.vol ELSE a.vol*-1 END)) as jml_stok_roll,
					   b.tsid,h.satuan as nama_satuan,
					   i.satuan as satuan_kecil,
					   (CASE 
					   			WHEN g.tipe_trans = 1 AND g.tspid > 0 THEN 'Penerimaan Barang'
					   			WHEN g.tipe_trans = 1 AND g.tspid = 0 THEN 'Migrasi Stok'
					   			WHEN g.tipe_trans = 3 AND a.tbmid IS NOT NULL THEN 'Penjualan Barang'
					   			WHEN g.tipe_trans = 3 AND a.tbmid IS NULL THEN 'Pemakaian Barang'
					   	END) as tipe_transaksi,
					   	(CASE 
					   			WHEN g.tipe_trans = 1 THEN g.reff_code
					   			WHEN g.tipe_trans = 3 AND a.tbmid IS NOT NULL THEN j.no_bill
					   			WHEN g.tipe_trans = 3 AND a.tbmid IS NULL THEN g.reff_code
					   	END) as no_transaksi,
					   	(CASE WHEN g.tipe_trans = 1 THEN a.vol END) as jml_masuk,
					   	(CASE WHEN g.tipe_trans = 3 THEN a.vol END) as jml_keluar
				FROM tbl_transaksi_barang_d a 
				JOIN tbl_barang_satuan b ON (a.tbrid = b.tbrid AND COALESCE(a.tsid_stok,a.tsid) = b.tsid)
				JOIN tbl_barang c ON (b.tbrid = c.tbrid)
				JOIN tbl_jenis_barang d ON (c.tjbid = d.tjbid)
				JOIN tbl_type_barang e ON (c.typbid = e.typbid)
				JOIN tbl_warna f ON (c.twid = f.twid)
				JOIN tbl_transaksi_barang g ON (a.ttbid = g.ttbid)
				JOIN tbl_satuan h ON (h.tsid = b.tsid)
				JOIN tbl_satuan i ON (i.tsid = c.satuan)
				LEFT JOIN tbl_bill_makanan j ON (j.tbmid = a.tbmid)
				WHERE 1=1 AND g.tipe_trans IN (1,3) $addsql
				
				ORDER BY g.ttbid,c.tbrid,c.nama_barang ASC

		";

		$rs = $this->db->query($sql);

		return $rs;
	}

	public function update_mobil($data) {

		$rs = $this->db->query("UPDATE tbl_bill_makanan SET nm_mobil = '".$data['nm_mobil']."',nopol_mobil='".$data['nopol_mobil']."' WHERE tbmid = ".$data['tbmid']);
	}

	public function update_mobil_exs($data) {

		$rs = $this->db->query("UPDATE tbl_bill_makanan SET nm_mobil_exs = '".$data['nm_mobil']."',nopol_mobil_exs='".$data['nopol_mobil']."',kpd_yth='".$data['kpd_yth']."',banyaknya='".$data['banyaknya']."',barangnya1='".$data['barangnya1']."',barangnya4='".$data['barangnya4']."',barangnya5='".$data['barangnya5']."',barangnya6='".$data['barangnya6']."' WHERE tbmid = ".$data['tbmid']);
	}


	public function insert_migration($datas,$stokdata) {

		$tipeTrans = 1;
		$is_edit = '0';
		$count_po = 0;


		if ( $is_edit == '0' ) {

			$query_count = $this->db->query("SELECT COUNT(*) as jml FROM tbl_transaksi_barang 
						WHERE MONTH(tgl_trans) = ".date('m',strtotime($datas['tgl_trans']))." AND tipe_trans = ".$tipeTrans." AND tspid = 0
						AND YEAR(tgl_trans) = ".date('Y',strtotime($datas['tgl_trans']))." ");
			$rs_count = $query_count->row();
			$count_po = ($rs_count->jml)+1;

		}

		$id_header_po = 0;

		if ( $tipeTrans == 1 AND isset($datas['no_po']) != '') {

			$get_explode_po = explode('_',$datas['no_po']);
			$id_header_po = $get_explode_po[0];
		}


		$data_header = array(
			'tgl_trans' => date('Y-m-d',strtotime($datas['tgl_trans'])),
			'tspid'   => 0,
			'keterangan' => " MIGRASI STOK - ".$datas['keterangan'],
			'tkgid'      => 5,
			'tipe_trans'     => $tipeTrans,
			'other_reff_code' => '-',
			'total_amount'		=> 0,//$data['total'],
			'total_diskon'		=> 0,//isset($data['total_diskon']) ? $data['total_diskon'] : 0,
			'ppn_rp'		=> 0,//isset($data['ppn_rp']) ? $data['ppn_rp'] : 0,
			'ppn_persen'		=> 0,//isset($data['ppn']) ? $data['ppn'] : 0,
			'no_sj'				=> '',//($tipeTrans == 2) ? $data['no_sj'] : '', 
		);

		if ( $is_edit == '0' ){

			$data_header['create_id'] = $_SESSION['teid'];
			$this->db->insert('tbl_transaksi_barang',$data_header);

		}

		//$data_kategori = $this->masterdata_model->list_kategori_row($data['tkgid']);
		//$kode_kategori = $data_kategori->kode_kategori;

		if ( $is_edit == '0' ) {

			$sql_inc = "SHOW TABLE STATUS WHERE name='tbl_transaksi_barang'";
			$row_inc = $this->db->query($sql_inc);
			$data_row   = $row_inc->row();

			$idnya = $data_row->Auto_increment;
			$ttbid = $idnya - 1;

		} else {

			$ttbid = $data['ttbid'];
		}

		if ( $tipeTrans == 2 ) {
			if ( $is_edit == '0') {
				$nomor_pengajuan = str_pad($count_po,5,'0',STR_PAD_LEFT).'/CLP/DNT/'.date('m',strtotime($data['tgl_trans'])).'/'.date('Y',strtotime($data['tgl_trans']));
				$this->db->query("UPDATE tbl_transaksi_barang SET reff_code = '".$nomor_pengajuan."' WHERE ttbid = ".$ttbid);
			}
		}elseif($tipeTrans == 1){
			if ( $is_edit == '0' ) {
				$nomor_pengajuan = str_pad($count_po,5,'0',STR_PAD_LEFT).'/MGS/DN/'.date('m',strtotime($datas['tgl_trans'])).'/'.date('Y',strtotime($datas['tgl_trans']));
				$this->db->query("UPDATE tbl_transaksi_barang SET reff_code = '".$nomor_pengajuan."' WHERE ttbid = ".$ttbid);
			}
		}elseif($tipeTrans == 3){
			if ( $is_edit == '0' ) {
				$nomor_pengajuan = str_pad($count_po,5,'0',STR_PAD_LEFT).'/CIU/'.date('m',strtotime($data['tgl_trans'])).'/'.date('Y',strtotime($data['tgl_trans']));
				$this->db->query("UPDATE tbl_transaksi_barang SET reff_code = '".$nomor_pengajuan."' WHERE ttbid = ".$ttbid);
			}
		}

				foreach( $stokdata as $key => $value ) {

					$detail_barang = array (
						'ttbid' => $ttbid,
						'tbrid' => $value['tbrid'],
						'vol'  => $value['vol'],
						'tsid' => $value['tsid'],
						'reff_id'		 => 0,
						'harga_satuan' => 0,
						'harga_total' => 0,
					);

					$this->db->insert('tbl_transaksi_barang_d',$detail_barang);
				}
		

	}

	public function list_bill_rekap($addsql="") {

		$sql = "SELECT a.tbrid,SUM(a.qty) as qty, 
				(CASE WHEN a.tsid_jual <> a.tsid_stok THEN 'KG' ELSE 'ROLL' END) as nama_satuan_trans,
				(CASE WHEN a.tsid_jual = a.tsid_stok THEn a.amount / e.isikecil ELSE round(a.amount) END) as harga_jual,
				concat(c.nama_barang,' - ',d.nama_warna) as nama_barang,
				SUM(a.amount*a.qty) as harga_satuan,
				a.discount_amount as discount_amount,
				SUM(a.discount_amount*a.qty*e.isikecil) as discount_total,
				SUM(a.amount_total) as amount_total,
				GROUP_CONCAT(concat(a.qty,':',e.isikecil) ORDER BY a.tbmdid ASC SEPARATOR ',') as satuan_list
				FROM tbl_bill_makanan_d a 
				JOIN tbl_bill_makanan b ON (a.tbmid = b.tbmid)
				JOIN tbl_barang c ON (a.tbrid = c.tbrid)
				JOIN tbl_warna d ON (d.twid = c.twid)
				JOIN tbl_barang_satuan e ON (e.tbrid = a.tbrid AND a.tsid_stok = e.tsid)
				JOIN tbl_satuan f ON (f.tsid = e.tsid)
				WHERE 1=1 $addsql
				GROUP BY a.tbrid,(CASE WHEN a.tsid_jual <> a.tsid_stok THEN 'KG' ELSE 'ROLL' END),c.harga_jual,concat(c.nama_barang,' - ',d.nama_warna),a.discount_amount
				";
		$rs  = $this->db->query($sql);

		return $rs;
	}

	public function all_penjualan($addsql="") {

		$sql = "SELECT a.tbmid,a.tbrid,SUM(a.qty) as qty, 
				(CASE WHEN a.tsid_jual <> a.tsid_stok THEN 'KG' ELSE 'ROLL' END) as nama_satuan_trans,
				c.harga_jual,
				concat(c.nama_barang,' - ',d.nama_warna) as nama_barang,
				SUM(a.amount*a.qty) as harga_satuan,
				SUM(a.discount_amount) as discount_amount,
				SUM(a.discount_amount*a.qty*e.isikecil) as discount_total,
				SUM(a.amount_total) as amount_total,
				GROUP_CONCAT(concat(a.qty,':',e.isikecil) ORDER BY a.tbmdid ASC SEPARATOR ',') as satuan_list,
				b.tgl_bill,b.no_bill,g.nama_karyawan as user_bill,h.nama_konsumen
				FROM tbl_bill_makanan_d a 
				JOIN tbl_bill_makanan b ON (a.tbmid = b.tbmid)
				JOIN tbl_barang c ON (a.tbrid = c.tbrid)
				JOIN tbl_warna d ON (d.twid = c.twid)
				JOIN tbl_barang_satuan e ON (e.tbrid = a.tbrid AND a.tsid_stok = e.tsid)
				JOIN tbl_satuan f ON (f.tsid = e.tsid)
				JOIN tbl_emp g ON (g.teid = b.create_id)
				JOIN tbl_konsumen h ON (h.tknid = b.nama_pelanggan)
				WHERE 1=1 $addsql
				GROUP BY a.tbmid,a.tbrid,(CASE WHEN a.tsid_jual <> a.tsid_stok THEN 'KG' ELSE 'ROLL' END),c.harga_jual,concat(c.nama_barang,' - ',d.nama_warna)
				ORDER BY a.tbmid DESC
				";
		$rs  = $this->db->query($sql);

		return $rs;
	}

	public function all_payment($addsql="") {

		$sql = "SELECT a.tgl_bill,a.no_bill,b.create_time,(CASE WHEN b.cara_bayar = 1 THEN 'CASH' WHEN b.cara_bayar = 2 THEN 'DEBET / CREDIT' ELSE 'TRANSFER' END) as cara_bayar
				,(CASE WHEN b.cara_bayar = 1 THEN c.nama_konsumen ELSE b.atas_nama END) as atas_nama
				,b.amount
				,d.nama_karyawan as nama_user
				,e.nama_edc
				FROM tbl_bill_makanan a 
				JOIN tbl_bill_payment b ON (a.tbmid = b.tbmid)
				JOIN tbl_konsumen c ON (c.tknid = a.nama_pelanggan)
				JOIN tbl_emp d ON (d.teid = b.create_id)
				LEFT JOIN tbl_edc e ON (e.tedid = b.tedid)
				WHERE 1=1 $addsql
				ORDER BY b.create_time DESC";

		$rs  = $this->db->query($sql);

		return $rs;
	}
 
}