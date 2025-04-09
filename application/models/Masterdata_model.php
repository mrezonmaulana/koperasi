<?php
class Masterdata_model extends CI_Model {
	public function __construct()
	{
		$this->load->database();
	}

 	## -------------------------------------------------------------------------------------------- ##
	/* S: BIDANG */
	## -------------------------------------------------------------------------------------------- ##

	public function list_bidang() {
 
		$data = $this->db->query("SELECT * FROM tbl_bidang ORDER BY tbid");
		return $data;
	}

	public function list_bidang_row($tbid) {
 
		$data = $this->db->query("SELECT * FROM tbl_bidang WHERE tbid = ".$tbid);
		return $data->row();
	}

	public function add_bidang($nama_bidang) {

		$data = array('nama_bidang' => $nama_bidang);
		$this->db->insert('tbl_bidang',$data);
	}

	public function hapus_bidang($tbid) {

		$this->db->query("DELETE FROM tbl_bidang WHERE tbid = ".$tbid);

	}

	public function edit_bidang($tbid,$nama_bidang) {

		$this->db->query("UPDATE tbl_bidang SET nama_bidang = '".$nama_bidang."' WHERE tbid = ".$tbid);

	}

	
	## -------------------------------------------------------------------------------------------- ##
	/* E: BIDANG */
	## -------------------------------------------------------------------------------------------- ##


	## -------------------------------------------------------------------------------------------- ##
	/* S: BARANG */
	## -------------------------------------------------------------------------------------------- ##

	public function list_barang_new_satuan($addsql="",$no_po=""){

		$addjoin = "";
		$addfield = ",0 as volume_po,0 as ttbdid_po";

		if ( $no_po != "0_0_0" AND $no_po != "") {

				$detail_no_po = explode("_",$no_po);

				$addjoin = " JOIN tbl_transaksi_barang_d ttbd ON (ttbd.tbrid = a.tbrid) 
							 JOIN tbl_transaksi_barang ttb ON (ttbd.ttbid = ttb.ttbid AND ttb.tipe_trans = 2 AND ttb.ttbid = ".$detail_no_po[0].")
							 LEFT JOIN tbl_transaksi_barang_d ttbdt ON (ttbdt.reff_id = ttbd.ttbdid AND ttbdt.tbrid = ttbd.tbrid)";

				$addfield = ",ttbd.vol-COALESCE(ttbdt.vol,0) as volume_po,ttbd.ttbdid as ttbdid_po ";

				$addsql .= " AND ttbd.vol-COALESCE(ttbdt.vol,0) > 0 ";
			
		}

		$sql = "SELECT a.*,
				b.nama_kategori,c.satuan as nama_satuan,(CASE WHEN (coalesce(d.jml_makanan,0) + coalesce(e.jml_transaksi,0)) > 0 THEN 1 ELSE 0 END) as ada_pakai, 
				f.kd_jenis,f.nm_jenis,g.nama_tipe,h.nama_warna,i.satuan as nama_satuan_besar,
				j.nama_karyawan as user_create,
				COALESCE(k.nama_karyawan,'-') as user_edit,
				a.konversi_satuan*a.hna as new_hna,
				group_concat(json_quote(concat(zz.tsid,'_',xzx.satuan,'_',ROUND(zz.isikecil,2))) ORDER BY zz.tbsid ASC SEPARATOR ':') as list_satuan,
				concat(a.nama_barang,' - ',h.nama_warna) as nama_barang_new
				$addfield
				FROM tbl_barang a
				JOIN tbl_kategori b ON (a.tkgid = b.tkgid)
				JOIN tbl_satuan c ON (c.tsid = a.satuan)
				LEFT JOIN (
					SELECT a.tbrid,COUNT(a.tbrid) as jml_makanan FROM tbl_bill_makanan_d a
					GROUP BY a.tbrid
				) d ON (a.tbrid = d.tbrid)
				LEFT JOIN (
					SELECT a.tbrid,COUNT(a.tbrid) as jml_transaksi FROM tbl_transaksi_barang_d a
					GROUP BY a.tbrid
				) e ON (a.tbrid = e.tbrid)
				JOIN tbl_jenis_barang f ON (f.tjbid = a.tjbid)
				JOIN tbl_type_barang g ON (g.typbid = a.typbid)
				JOIN tbl_warna h ON (h.twid = a.twid)
				LEFT JOIN tbl_satuan i ON (i.tsid = a.satuan_besar)
				JOIN tbl_emp j ON (j.teid = a.create_id)
				LEFT JOIN tbl_emp k ON (k.teid = a.modify_id)
				JOIN tbl_barang_satuan zz ON (zz.tbrid = a.tbrid)
				JOIN tbl_satuan xzx ON (zz.tsid = xzx.tsid)
				$addjoin
				WHERE 1=1 $addsql
				GROUP BY a.tbrid,a.nama_barang,a.satuan,a.tkgid,a.hna,a.harga_jual,a.create_id,a.create_time,a.modify_id,a.modify_time,a.is_aktif,a.tjbid,a.typbid,a.twid,a.gram,a.lebar,a.satuan_besar,a.konversi_satuan,b.nama_kategori,c.satuan,(CASE WHEN (coalesce(d.jml_makanan,0) + coalesce(e.jml_transaksi,0)) > 0 THEN 1 ELSE 0 END),
				f.kd_jenis,f.nm_jenis,g.nama_tipe,h.nama_warna,i.satuan,j.nama_karyawan,COALESCE(k.nama_karyawan,'-'),a.konversi_satuan*a.hna
				ORDER BY a.tbrid";
				$data = $this->db->query($sql);
				return $data;

	}

	public function list_barang_search() {

		$sql = "SELECT a.tbrid,a.nama_barang,b.nama_warna FROM tbl_barang a
				JOIN tbl_warna b ON (a.twid = b.twid)
				ORDER BY a.tbrid
				";
		$rs = $this->db->query($sql);

		return $rs;
	}

	public function list_barang($addsql="") {


		$sql = "SELECT a.*,
				b.nama_kategori,c.satuan as nama_satuan,(CASE WHEN (coalesce(d.jml_makanan,0) + coalesce(e.jml_transaksi,0)) > 0 THEN 1 ELSE 0 END) as ada_pakai, 
				f.kd_jenis,f.nm_jenis,g.nama_tipe,h.nama_warna,i.satuan as nama_satuan_besar,
				j.nama_karyawan as user_create,
				COALESCE(k.nama_karyawan,'-') as user_edit,
				a.konversi_satuan*a.hna as new_hna,
				xzx.satuan as nama_satuan_po,
				zz.tsid as satuan_po,
				zz.isikecil*a.hna as new_hna_po,
				concat(a.nama_barang,' - ',h.nama_warna) as nama_barang_new
				FROM tbl_barang a
				JOIN tbl_kategori b ON (a.tkgid = b.tkgid)
				JOIN tbl_satuan c ON (c.tsid = a.satuan)
				LEFT JOIN (
					SELECT a.tbrid,COUNT(a.tbrid) as jml_makanan FROM tbl_bill_makanan_d a
					GROUP BY a.tbrid
				) d ON (a.tbrid = d.tbrid)
				LEFT JOIN (
					SELECT a.tbrid,COUNT(a.tbrid) as jml_transaksi FROM tbl_transaksi_barang_d a
					GROUP BY a.tbrid
				) e ON (a.tbrid = e.tbrid)
				JOIN tbl_jenis_barang f ON (f.tjbid = a.tjbid)
				JOIN tbl_type_barang g ON (g.typbid = a.typbid)
				JOIN tbl_warna h ON (h.twid = a.twid)
				LEFT JOIN tbl_satuan i ON (i.tsid = a.satuan_besar)
				JOIN tbl_emp j ON (j.teid = a.create_id)
				LEFT JOIN tbl_emp k ON (k.teid = a.modify_id)
				JOIN tbl_barang_satuan zz ON (zz.tbrid = a.tbrid AND zz.is_po = 1)
				JOIN tbl_satuan xzx ON (zz.tsid = xzx.tsid)
				WHERE 1=1 $addsql
				ORDER BY a.tbrid";
		$data = $this->db->query($sql);
		
	
		return $data;
	}

	public function detail_satuan($addsql="") {

		$sql = "SELECT b.satuan,b.tsid,c.isikecil,COALESCE(d.jml,0) as jml_pakai,c.is_po,c.tbsid FROM tbl_barang_satuan c 
				JOIN tbl_satuan b ON (b.tsid = c.tsid)
				JOIN tbl_barang a ON (a.tbrid = c.tbrid)
				LEFT JOIN (
					SELECT count(a.tbrid) as jml,a.tbrid,coalesce(a.tsid_stok,a.tsid) as tsid
					FROM tbl_transaksi_barang_d a
					GROUP BY a.tbrid,coalesce(a.tsid_stok,a.tsid)
				) d ON (d.tbrid = a.tbrid AND d.tsid = c.tsid)
				WHERE 1=1 $addsql 
				ORDER BY c.tbsid ";
		$rs  = $this->db->query($sql);

		return $rs;
	}

	public function list_barang_stok_new($addsql=""){


		$sql = "SELECT a.ttbdid,(a.vol*d.isikecil) - coalesce(COALESCE(c.vol_kurang,0)*d.isikecil,0) as sisa_stok
				FROM tbl_transaksi_barang_d a
				JOIN tbl_transaksi_barang b ON (a.ttbid = b.ttbid)
				LEFT JOIN (
					SELECT SUM(x.vol) as vol_kurang,x.reff_id,COALESCE(x.tsid_stok,x.tsid) as tsid FROM tbl_transaksi_barang_d x
				    JOIN tbl_transaksi_barang z on (x.ttbid = z.ttbid)
				    WHERE reff_id IS NOT NULL AND z.tipe_trans = 3
				    GROUP BY reff_id,COALESCE(x.tsid_stok,x.tsid)
				) c ON (c.reff_id = a.ttbdid AND c.tsid = COALESCE(a.tsid_stok,a.tsid))
				JOIN tbl_barang_satuan d ON (a.tbrid = d.tbrid AND COALESCE(a.tsid_stok,a.tsid) = d.tsid)
				WHERE b.tipe_trans = 1 $addsql
				AND a.vol - coalesce(c.vol_kurang,0) > 0 ORDER BY b.ttbid ASC";
		$rs = $this->db->query($sql);

		return $rs;

	}

	public function list_barang_stok($addsql="",$addsql2="",$addsql3="",$addsql4="",$addsql5="") {


		$sql = "SELECT a.*,b.nama_kategori,c.satuan as nama_satuan,COALESCE(ROUND(f.stok,2),0) as stok,z.satuan as nama_satuan_besar,
				a.harga_jual*a.konversi_satuan as harga_jual_new,
				e.list_satuan as list_satuan,
				concat(a.nama_barang,' - ',wrn.nama_warna) as nama_barang_new,
				COALESCE(jns.is_manual,0) as is_manual
				FROM tbl_barang a
				JOIN tbl_kategori b ON (a.tkgid = b.tkgid)
				JOIN tbl_satuan c ON (c.tsid = a.satuan)
				JOIN tbl_warna wrn ON (wrn.twid = a.twid)
				JOIN tbl_jenis_barang jns ON (jns.tjbid = a.tjbid)
				LEFT JOIN tbl_satuan z ON (z.tsid = a.satuan_besar)
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
					WHERE COALESCE(d.stok,0) > 0 $addsql3

					GROUP BY zz.tbrid
				) e ON e.tbrid = a.tbrid

				JOIN (

					SELECT a.tbrid,
					SUM(CASE WHEN b.tipe_trans = 1 
							 THEN COALESCE(a.vol,0)*c.isikecil 
							 ELSE coalesce(COALESCE(a.vol,0)*c.isikecil*-1,0) 
					END) as stok

					FROM tbl_transaksi_barang_d a
					JOIN tbl_transaksi_barang b ON (a.ttbid = b.ttbid)
					JOIN tbl_barang_satuan c ON (c.tbrid = a.tbrid AND c.tsid = COALESCE(a.tsid_stok,a.tsid))
					WHERE a.reff_id IS NOT NULL AND b.tipe_trans IN (1,3) 
					$addsql4
					GROUP BY a.tbrid

					) f ON (f.tbrid = a.tbrid)

				WHERE 1=1  $addsql
				ORDER BY a.tbrid";
		$data = $this->db->query($sql);
		return $data;
	}

	public function list_barang_stok_bill($addsql="",$addsql2="",$addsql3="",$addsql4="",$addsql5="") {


		$sql = "SELECT a.*,b.nama_kategori,c.satuan as nama_satuan,COALESCE(f.stok,0) as stok,z.satuan as nama_satuan_besar,
				a.harga_jual*a.konversi_satuan as harga_jual_new,
				e.list_satuan as list_satuan
				FROM tbl_barang a
				JOIN tbl_kategori b ON (a.tkgid = b.tkgid)
				JOIN tbl_satuan c ON (c.tsid = a.satuan)
				LEFT JOIN tbl_satuan z ON (z.tsid = a.satuan_besar)
				LEFT JOIN (
					SELECT zz.tbrid,group_concat(json_quote(concat(zz.tsid,'_',ROUND(zz.isikecil,2)*zzz.hna,'_',xzx.satuan,'_',d.stok)) SEPARATOR ':') as list_satuan

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
					WHERE COALESCE(d.stok,0) > 0 $addsql3

					GROUP BY zz.tbrid
				) e ON e.tbrid = a.tbrid

				JOIN (

					SELECT a.tbrid,
					SUM(CASE WHEN b.tipe_trans = 1 
							 THEN COALESCE(a.vol,0)*c.isikecil
							 ELSE coalesce(COALESCE(a.vol,0)*c.isikecil *-1,0) 
					END) as stok,COALESCE(a.tsid_stok,a.tsid) as tsid

					FROM tbl_transaksi_barang_d a
					JOIN tbl_transaksi_barang b ON (a.ttbid = b.ttbid)
					JOIN tbl_barang_satuan c ON (c.tbrid = a.tbrid AND c.tsid = COALESCE(a.tsid_stok,a.tsid))
					WHERE a.reff_id IS NOT NULL AND b.tipe_trans IN (1,3) $addsql4
					GROUP BY a.tbrid,COALESCE(a.tsid_stok,a.tsid)

					) f ON (f.tbrid = a.tbrid)

				WHERE 1=1  $addsql
				ORDER BY a.tbrid";
		$data = $this->db->query($sql);
		return $data;
	}

	public function add_barang($datas) {

		if ( isset($datas['tbrid']) AND $datas['tbrid'] > 0 ) {

			//$this->db->query("DELETE FROM tbl_barang_satuan WHERE tbrid = ".$datas['tbrid']);

			$data = array(
				'nama_barang' => $datas['nama_barang'],
				'satuan'      => $datas['satuan'],
				'tkgid'		  => $datas['tkgid'],
				'tjbid'		  => $datas['tjbid'],
				'typbid'	  => $datas['typbid'],
				'twid'		  => $datas['twid'],
				'gram'		  => $datas['gram'],
				'lebar'	      => $datas['lebar'],
				/*'satuan_besar' => $datas['satuan_besar'],
				'konversi_satuan' => $datas['konversi_satuan'],*/
				'hna'		  => (isset($datas['harga_jual']) AND is_numeric($datas['harga_jual'])) ? $datas['harga_jual'] : 0,
				'harga_jual'		  => (isset($datas['harga_jual']) AND is_numeric($datas['harga_jual'])) ? $datas['harga_jual'] : 0,
				'is_aktif'		=> isset($datas['is_aktif']) ? $datas['is_aktif'] : '0',
				'modify_id' => $_SESSION['teid'],
				'modify_time' => date('Y-m-d H:i:s'),

			);
			$this->db->where('tbrid',$datas['tbrid']);
			$this->db->update('tbl_barang',$data);

			$id = $datas['tbrid'];

		} else {

			$data = array(
				'nama_barang' => $datas['nama_barang'],
				'satuan'      => $datas['satuan'],
				'tkgid'		  => $datas['tkgid'],
				'tjbid'		  => $datas['tjbid'],
				'typbid'	  => $datas['typbid'],
				'twid'		  => $datas['twid'],
				'gram'		  => $datas['gram'],
				'lebar'	      => $datas['lebar'],
				/*'satuan_besar' => $datas['satuan_besar'],
				'konversi_satuan' => $datas['konversi_satuan'],*/
				'hna'		  => (isset($datas['harga_jual']) AND is_numeric($datas['harga_jual'])) ? $datas['harga_jual'] : 0,
				'harga_jual'		  => (isset($datas['harga_jual']) AND is_numeric($datas['harga_jual'])) ? $datas['harga_jual'] : 0,
				'is_aktif'		=> isset($datas['is_aktif']) ? $datas['is_aktif'] : '0',
				'create_id' => $_SESSION['teid'],

			);
			$this->db->insert('tbl_barang',$data);

				$sql_inc = "SHOW TABLE STATUS WHERE name='tbl_barang'";
				$row_inc = $this->db->query($sql_inc);
				$data_row   = $row_inc->row();

				$idnya = $data_row->Auto_increment;
				$id = $idnya - 1;
		}

		$tsid_detail = isset($datas['tsid_detail']) ? $datas['tsid_detail'] : array();
		$konversi_detail = isset($datas['konversi_detail']) ? $datas['konversi_detail'] : array();
		$is_po = isset($datas['is_po']) ? $datas['is_po'] : array();

		if ( count($tsid_detail) > 0 ) {

			foreach ($tsid_detail as $key => $value) {

				$cek_ada = $this->db->query("SELECT COUNT(tbsid) as jml FROM tbl_barang_satuan WHERE tbrid = ".$id." AND tsid = ".$value);
				$data_satuan   = $cek_ada->row();
				$count = $data_satuan->jml;

				if ( intval($count) > 0 ) {
					continue;
				}
				
				if ( $is_po[$key] == '1' ) {
				    
				    $cek_ada_sat_po = $this->db->query("SELECT MIN(tbsid) as jml FROM tbl_barang_satuan WHERE tbrid = ".$id." AND is_po = '1'");
				    $tbsid   = $cek_ada_sat_po->row();
				    $tbsid_new = $tbsid->jml;
				    
				    if ( $tbsid_new > 0 ) {
				        $this->db->query("UPDATE tbl_barang_satuan SET is_po = '0' WHERE tbrid = ".$id." AND tbsid = ".$tbsid_new);
				    }
				}
				
				$detail_satuan = array(
					'tbrid' => $id,
					'tsid'  => $value,
					'isikecil' => $konversi_detail[$key],
					'is_po'		=> $is_po[$key] == '1' ? '1' : '0',
				);

				$this->db->insert('tbl_barang_satuan',$detail_satuan);
			}
		}

		
	}

	public function list_barang_row($tbrid) {
 		
		$data = $this->db->query("SELECT * FROM tbl_barang WHERE tbrid = ".$tbrid);
		return $data->row();
	}

	public function edit_barang($tbrid,$nama_barang,$satuan) {

		$this->db->query("UPDATE tbl_barang SET nama_barang = '".$nama_barang."'WHERE tbrid = ".$tbrid);

	}

	public function hapus_barang($tbrid) {

		$this->db->query("DELETE FROM tbl_barang_satuan WHERE tbrid = ".$tbrid);
		$this->db->query("DELETE FROM tbl_barang WHERE tbrid = ".$tbrid);

	}

	## -------------------------------------------------------------------------------------------- ##
	/* E: BARANG */
	## -------------------------------------------------------------------------------------------- ##

	## -------------------------------------------------------------------------------------------- ##
	/* S: KENDARAAN */
	## -------------------------------------------------------------------------------------------- ##

	public function list_kendaraan() {

		$sql = "SELECT a.* FROM tbl_kendaraan a ORDER BY a.tkid";
		$data = $this->db->query($sql);
		return $data;
	}

	public function add_kendaraan($nama_kendaraan,$no_polisi) {

		$data = array(
				'nama_kendaraan' => $nama_kendaraan,
				'no_polisi'      => $no_polisi

		);
		$this->db->insert('tbl_kendaraan',$data);
	}

	public function list_kendaraan_row($tkid) {
 
		$data = $this->db->query("SELECT * FROM tbl_kendaraan WHERE tkid = ".$tkid);
		return $data->row();
	}

	public function edit_kendaraan($tkid,$nama_kendaraan,$no_polisi) {

		$this->db->query("UPDATE tbl_kendaraan SET nama_kendaraan = '".$nama_kendaraan."',no_polisi='".$no_polisi."' WHERE tkid = ".$tkid);

	}

	public function hapus_kendaraan($tkid) {

		$this->db->query("DELETE FROM tbl_kendaraan WHERE tkid = ".$tkid);

	}

	## -------------------------------------------------------------------------------------------- ##
	/* E: KENDARAAN */
	## -------------------------------------------------------------------------------------------- ##


	## -------------------------------------------------------------------------------------------- ##
	/* S: SUPPLIER */
	## -------------------------------------------------------------------------------------------- ##


	public function list_supplier($addsql= "") {
 
		$data = $this->db->query("SELECT a.*,(CASE WHEN COALESCE(b.jml_pakai,0) > 0 THEN 1 ELSE 0 END) as ada_pakai FROM tbl_supplier a 
									LEFT JOIN (
										SELECT a.tspid,COUNT(a.tspid) as jml_pakai FROM tbl_transaksi_barang a
										WHERE a.tipe_trans IN (1,2)
										GROUP BY a.tspid
									) b ON (a.tspid = b.tspid)
									WHERE 1=1 $addsql
									ORDER BY a.tspid");
		return $data;
	}

	public function list_supplier_row($tspid) {
 
		$data = $this->db->query("SELECT * FROM tbl_supplier WHERE tspid = ".$tspid);
		return $data->row();
	}

	public function add_supplier($datas) {

		if ( isset($datas['tspid']) AND $datas['tspid'] > 0 ) {

			$data = array('
								nama_supplier' => $datas['nama_supplier'],
								'no_telp'	   => $datas['no_telp'],
								'alamat'	   => $datas['alamat'],
								'is_aktif'	   => isset($datas['is_aktif']) ? $datas['is_aktif'] : '0',
								'modify_id'    => $_SESSION['teid'],
								'attn'			=> $datas['attn'],
								'modify_time'  => date('Y-m-d H:i:s'),
							);
				$this->db->where('tspid',$datas['tspid']);
				$this->db->update('tbl_supplier',$data);

		} else {

				$data = array('
								nama_supplier' => $datas['nama_supplier'],
								'no_telp'	   => $datas['no_telp'],
								'alamat'	   => $datas['alamat'],
								'is_aktif'	   => isset($datas['is_aktif']) ? $datas['is_aktif'] : '0',
								'create_id'    => $_SESSION['teid'],
								'attn'			=> $datas['attn'],
							);
				$this->db->insert('tbl_supplier',$data);
		}

	}

	public function hapus_supplier($tspid) {

		$this->db->query("DELETE FROM tbl_supplier WHERE tspid = ".$tspid);

	}

	public function edit_supplier($tspid,$nama_supplier) {

		$this->db->query("UPDATE tbl_supplier SET nama_supplier = '".$nama_supplier."' WHERE tspid = ".$tspid);

	}

	## -------------------------------------------------------------------------------------------- ##
	/* E: SUPPLIER */
	## -------------------------------------------------------------------------------------------- ##


	## -------------------------------------------------------------------------------------------- ##
	/* S: KARYAWAN */
	## -------------------------------------------------------------------------------------------- ##

	public function list_karyawan($addsql="") {
 		
 		$sql = "SELECT a.*,b.nama_role,(CASE WHEN (COALESCE(c.jml_kasbon,0) + COALESCE(d.jml_user,0)) > 0 THEN 1 ELSE 0 END) as ada_pakai FROM tbl_emp a
 				JOIN tbl_role b ON (a.trid = b.trid)
 				LEFT JOIN (
 					SELECT a.teid_pengaju,count(a.teid_pengaju) as jml_kasbon FROM tbl_ajuan_kasbon a
 					GROUP BY a.teid_pengaju
 				) c ON (a.teid = c.teid_pengaju)
 				LEFT JOIN (
 					SELECT a.teid,count(a.teid) as jml_user FROM tbl_user a
 					GROUP BY a.teid
 				) d ON (a.teid = d.teid)
 				WHERE 1=1 $addsql
 				ORDER BY a.teid";
		$data = $this->db->query($sql);
		return $data;
	}

	public function list_pelaksana($tipe) {
		$addsql = " WHERE b.trid = ".$tipe;
 		if ( $tipe == '' )
 		{
 			$addsql="";
 		}
 		$sql = "SELECT a.*,b.nama_role FROM tbl_emp a
 				JOIN tbl_role b ON (a.trid = b.trid)
 				$addsql
 				ORDER BY a.teid";
		$data = $this->db->query($sql);
		return $data;
	}

	public function list_karyawan_row($teid) {
 
		$data = $this->db->query("SELECT * FROM tbl_emp WHERE teid = ".$teid);
		return $data->row();
	}

	public function add_karyawan($datas) {

		if ( (isset($datas['teid']) AND $datas['teid'] > 0 )) {

				$data = array(
					'nama_karyawan' => $datas['nama_karyawan'],
					'trid'			=> $datas['trid'],
					'no_telp'		=> $datas['no_telp'],
					'jenis_kelamin' => $datas['jenis_kelamin'],
					'alamat'		=> $datas['alamat'],
					'tgl_lahir'		=> (isset($datas['tgl_lahir'])) ? date('Y-m-d',strtotime($datas['tgl_lahir'])) : null,
					'tempat_lahir'		=> (isset($datas['tempat_lahir'])) ? $datas['tempat_lahir'] : null,
					'nik'		=> (isset($datas['nik'])) ? $datas['nik'] : null,
					'is_aktif'  => (isset($datas['is_aktif'])) ? $datas['is_aktif'] : '0',
					'modify_id'     => $_SESSION['teid'],
					'modify_time'   => date('Y-m-d H:i:s'),
			);
			$this->db->where('teid',$datas['teid']);
			$this->db->update('tbl_emp',$data);

		} else {

			$data = array(
					'nama_karyawan' => $datas['nama_karyawan'],
					'trid'			=> $datas['trid'],
					'no_telp'		=> $datas['no_telp'],
					'jenis_kelamin' => $datas['jenis_kelamin'],
					'alamat'		=> $datas['alamat'],
					'tgl_lahir'		=> (isset($datas['tgl_lahir'])) ? date('Y-m-d',strtotime($datas['tgl_lahir'])) : null,
					'tempat_lahir'		=> (isset($datas['tempat_lahir'])) ? $datas['tempat_lahir'] : null,
					'nik'		=> (isset($datas['nik'])) ? $datas['nik'] : null,
					'is_aktif'  => (isset($datas['is_aktif'])) ? $datas['is_aktif'] : '0',
					'create_id'     => $_SESSION['teid'],
			);
			$this->db->insert('tbl_emp',$data);

		}

		
	}

	public function hapus_karyawan($teid) {

		$this->db->query("DELETE FROM tbl_emp WHERE teid = ".$teid);

	}

	public function edit_karyawan($teid,$nama_karyawan,$trid) {

		$this->db->query("UPDATE tbl_emp SET nama_karyawan = '".$nama_karyawan."',trid=".$trid.",modify_time='".date('Y-m-d H:i:s')."',modify_id=".$_SESSION['teid']." WHERE teid = ".$teid);

	}

	public function list_role() {

		$data = $this->db->query("SELECT * FROM tbl_role ORDER BY trid");
		return $data;
	}

	
	## -------------------------------------------------------------------------------------------- ##
	/* E: KARYAWAN */
	## -------------------------------------------------------------------------------------------- ##

	## -------------------------------------------------------------------------------------------- ##
	/* S: PERUSAHAAN */
	## -------------------------------------------------------------------------------------------- ##

	public function list_perusahaan() {
 
		$data = $this->db->query("SELECT * FROM tbl_perusahaan ORDER BY tphid");
		return $data;
	}

	public function list_perusahaan_row($tphid) {
 
		$data = $this->db->query("SELECT a.*,b.nama_karyawan as nama_kepala FROM tbl_perusahaan a 
									JOIN tbl_emp b ON (a.teid = b.teid)
					WHERE a.tphid = ".$tphid);
		return $data->row();
	}

	public function add_perusahaan($data) {

		$data_ins = array(
			'nama_perusahaan' => $data['nama_perusahaan'],
			'teid'            => $data['teid_kepala'],
		);

		$this->db->insert('tbl_perusahaan',$data_ins);
	}

	public function hapus_perusahaan($tphid) {

		$this->db->query("DELETE FROM tbl_perusahaan WHERE tphid = ".$tphid);

	}

	public function edit_perusahaan($data) {


		$this->db->query("UPDATE tbl_perusahaan SET nama_perusahaan = '".$data['nama_perusahaan']."',teid=".$data['teid_kepala']." WHERE tphid = ".$data['tphid']);

	}

	
	## -------------------------------------------------------------------------------------------- ##
	/* E: PERUSAHAAN */
	## -------------------------------------------------------------------------------------------- ##


	## -------------------------------------------------------------------------------------------- ##
	/* S: BIDANG */
	## -------------------------------------------------------------------------------------------- ##

	public function list_satuan($addsql="") {
 
		$data = $this->db->query("SELECT a.*,(CASE WHEN COALESCE(b.jml_pakai,0) + COALESCE(c.jml_pakai,0) > 0 THEN 1 ELSE 0 END) as ada_pakai FROM tbl_satuan a
								 LEFT JOIN (
								 	SELECT a.satuan,COUNT(a.satuan) as jml_pakai
								 	FROM tbl_barang a
								 	GROUP BY a.satuan
								 ) b ON (a.tsid = b.satuan)
								 LEFT JOIN (
								 	SELECT a.tsid,COUNT(a.tsid) as jml_pakai
								 	FROM tbl_barang_satuan a
								 	GROUP BY a.tsid
								 ) c ON (a.tsid = c.tsid)
								 WHERE 1=1 $addsql
									 ORDER BY a.tsid");
		return $data;
	}

	public function list_satuan_row($tsid) {
 
		$data = $this->db->query("SELECT a.* FROM tbl_satuan a WHERE a.tsid = ".$tsid);
		return $data->row();
	}

	public function add_satuan($datas) {

		if ( isset($datas['tsid']) AND $datas['tsid'] > 0 ) {

			$data = array(
							'satuan' => $datas['nama_satuan'],
							'is_aktif' => isset($datas['is_aktif']) ? $datas['is_aktif'] : '0',
							'modify_id' => $_SESSION['teid'],
							'modify_time' => date('Y-m-d H:i:s'),

							);
				$this->db->where('tsid',$datas['tsid']);
				$this->db->update('tbl_satuan',$data);

		}  else {
				$data = array(
							'satuan' => $datas['nama_satuan'],
							'is_aktif' => isset($datas['is_aktif']) ? $datas['is_aktif'] : '0',
							'create_id' => $_SESSION['teid'],

							);
				$this->db->insert('tbl_satuan',$data);

		}

	}

	public function hapus_satuan($tsid) {

		$this->db->query("DELETE FROM tbl_satuan WHERE tsid = ".$tsid);

	}

	public function edit_satuan($tsid,$nama_satuan) {

		$this->db->query("UPDATE tbl_satuan SET satuan = '".$nama_satuan."' WHERE tsid = ".$tsid);

	}

	
	## -------------------------------------------------------------------------------------------- ##
	/* E: BIDANG */
	## -------------------------------------------------------------------------------------------- ##

	## -------------------------------------------------------------------------------------------- ##
	/* S: KATEGORI */
	## -------------------------------------------------------------------------------------------- ##


	public function list_kategori($addsql="") {
 
		$data = $this->db->query("SELECT a.*,(CASE WHEN COALESCE(b.jml,0) > 0 THEN 1 ELSE 0 END) as ada_pakai FROM tbl_kategori a 
								  LEFT JOIN (
								  			SELECT a.tkgid,COUNT(a.tkgid) as jml FROM tbl_barang a
								  			GROUP BY a.tkgid
								  		) b ON (a.tkgid = b.tkgid)
								  		WHERE 1=1 $addsql
								  ORDER BY a.tkgid");
		return $data;
	}

	public function list_kategori_row($tkgid) {
 
		$data = $this->db->query("SELECT * FROM tbl_kategori WHERE tkgid = ".$tkgid);
		return $data->row();
	}

	public function add_kategori($nama_kategori,$is_aktif,$is_makanan,$kode_kategori) {

		$data = array(
				'nama_kategori' => $nama_kategori,
				'is_aktif' => $is_aktif,
				'is_makanan' => $is_makanan,
				'create_id' => $_SESSION['teid'],
				'kode_kategori' => $kode_kategori,
			);
		$this->db->insert('tbl_kategori',$data);
	}

	public function hapus_kategori($tkgid) {

		$this->db->query("DELETE FROM tbl_kategori WHERE tkgid = ".$tkgid);

	}

	public function edit_kategori($tkgid,$nama_kategori,$is_aktif,$is_makanan,$kode_kategori) {

		$this->db->query("UPDATE tbl_kategori SET nama_kategori = '".$nama_kategori."',is_aktif='".$is_aktif."',is_makanan='".$is_makanan."',kode_kategori='".$kode_kategori."', modify_time='".date('Y-m-d H:i:s')."',modify_id=".$_SESSION['teid']." WHERE tkgid = ".$tkgid);

	}

	## -------------------------------------------------------------------------------------------- ##
	/* E: KATEGORI */
	## -------------------------------------------------------------------------------------------- ##


	## -------------------------------------------------------------------------------------------- ##
	/* S: EDC */
	## -------------------------------------------------------------------------------------------- ##


	public function list_edc($addsql="") {
 
		$data = $this->db->query("SELECT * FROM tbl_edc WHERE 1=1 $addsql ORDER BY tedid");
		return $data;
	}

	public function list_edc_row($tedid) {
 
		$data = $this->db->query("SELECT * FROM tbl_edc WHERE tedid = ".$tedid);
		return $data->row();
	}

	public function add_edc($nama_edc,$is_aktif,$is_trans) {

		$data = array(
				'nama_edc' => $nama_edc,
				'is_aktif' => $is_aktif,
				'is_trans' => $is_trans,
				'create_id' => $_SESSION['teid']
			);
		$this->db->insert('tbl_edc',$data);
	}

	public function hapus_edc($tedid) {

		$this->db->query("DELETE FROM tbl_edc WHERE tedid = ".$tedid);

	}

	public function edit_edc($tedid,$nama_edc,$is_aktif,$is_trans) {

		$this->db->query("UPDATE tbl_edc SET nama_edc = '".$nama_edc."',is_aktif='".$is_aktif."',is_trans='".$is_trans."',modify_time='".date('Y-m-d H:i:s')."',modify_id=".$_SESSION['teid']." WHERE tedid = ".$tedid);

	}

	## -------------------------------------------------------------------------------------------- ##
	/* E: EDC */
	## -------------------------------------------------------------------------------------------- ##


	## -------------------------------------------------------------------------------------------- ##
	/* S: JENIS */
	## -------------------------------------------------------------------------------------------- ##


	public function list_jenis($addsql="") {
 
		$data = $this->db->query("SELECT a.*,(CASE WHEN COALESCE(b.jml,0) > 0 THEN 1 ELSE 0 END) as ada_pakai FROM tbl_jenis_barang a 
								  LEFT JOIN (
								  			SELECT a.tjbid,COUNT(a.tjbid) as jml FROM tbl_barang a
								  			GROUP BY a.tjbid
								  		) b ON (a.tjbid = b.tjbid)
								  		WHERE 1=1 $addsql
								  ORDER BY a.tjbid");
		return $data;
	}

	public function list_jenis_row($tjbid) {
 
		$data = $this->db->query("SELECT * FROM tbl_jenis_barang WHERE tjbid = ".$tjbid);
		return $data->row();
	}

	public function add_jenis($nama_jenis,$is_aktif,$kode_jenis,$is_manual) {

		$data = array(
				'nm_jenis' => $nama_jenis,
				'is_aktif' => $is_aktif,
				'create_id' => $_SESSION['teid'],
				'is_manual' => $is_manual,
				'kd_jenis' => $kode_jenis,
			);
		$this->db->insert('tbl_jenis_barang',$data);
	}

	public function hapus_jenis($tjbid) {

		$this->db->query("DELETE FROM tbl_jenis_barang WHERE tjbid = ".$tjbid);

	}

	public function edit_jenis($tjbid,$nama_jenis,$is_aktif,$kode_jenis,$is_manual) {

		$this->db->query("UPDATE tbl_jenis_barang SET nm_jenis = '".$nama_jenis."',is_aktif='".$is_aktif."',kd_jenis='".$kode_jenis."',is_manual='".$is_manual."',modify_time='".date('Y-m-d H:i:s')."',modify_id=".$_SESSION['teid']." WHERE tjbid = ".$tjbid);

	}

	## -------------------------------------------------------------------------------------------- ##
	/* E: JENIS */
	## -------------------------------------------------------------------------------------------- ##

	## -------------------------------------------------------------------------------------------- ##
	/* S: TIPE */
	## -------------------------------------------------------------------------------------------- ##


	public function list_tipe($addsql="") {
 
		$data = $this->db->query("SELECT a.*,(CASE WHEN COALESCE(b.jml,0) > 0 THEN 1 ELSE 0 END) as ada_pakai FROM tbl_type_barang a 
								  LEFT JOIN (
								  			SELECT a.typbid,COUNT(a.typbid) as jml FROM tbl_barang a
								  			GROUP BY a.typbid
								  		) b ON (a.typbid = b.typbid)
								  		WHERE 1=1 $addsql
								  ORDER BY a.typbid");
		return $data;
	}

	public function list_tipe_row($typbid) {
 
		$data = $this->db->query("SELECT * FROM tbl_type_barang WHERE typbid = ".$typbid);
		return $data->row();
	}

	public function add_tipe($nama_tipe,$is_aktif) {

		$data = array(
				'nama_tipe' => $nama_tipe,
				'is_aktif' => $is_aktif,
				'create_id' => $_SESSION['teid']
			);
		$this->db->insert('tbl_type_barang',$data);
	}

	public function hapus_tipe($typbid) {

		$this->db->query("DELETE FROM tbl_type_barang WHERE typbid = ".$typbid);

	}

	public function edit_tipe($typbid,$nama_tipe,$is_aktif) {

		$this->db->query("UPDATE tbl_type_barang SET nama_tipe = '".$nama_tipe."',is_aktif='".$is_aktif."', modify_time='".date('Y-m-d H:i:s')."',modify_id=".$_SESSION['teid']." WHERE typbid = ".$typbid);

	}

	## -------------------------------------------------------------------------------------------- ##
	/* E: TIPE */
	## -------------------------------------------------------------------------------------------- ##

	## -------------------------------------------------------------------------------------------- ##
	/* S: KONSUMEN */
	## -------------------------------------------------------------------------------------------- ##


	public function list_konsumen($addsql="") {
 
		$data = $this->db->query("SELECT a.*,(CASE WHEN COALESCE(b.jml_pakai,0) > 0 THEN 1 ELSE 0 END) as ada_pakai FROM tbl_konsumen a 
									LEFT JOIN (
										SELECT a.nama_pelanggan,COUNT(a.nama_pelanggan) as jml_pakai FROM tbl_bill_makanan a
										GROUP BY a.nama_pelanggan
									) b ON (a.tknid = b.nama_pelanggan)
									WHERE 1=1 $addsql
									ORDER BY a.tknid");
		return $data;
	}

	public function list_konsumen_row($tknid) {
 
		$data = $this->db->query("SELECT * FROM tbl_konsumen WHERE tknid = ".$tknid);
		return $data->row();
	}

	public function add_konsumen($datas) {

		if ( isset($datas['tknid']) AND $datas['tknid'] > 0 ) {

			$data = array('
								nama_konsumen' => $datas['nama_konsumen'],
								'no_telp'	   => $datas['no_telp'],
								'alamat'	   => $datas['alamat'],
								'is_aktif'	   => isset($datas['is_aktif']) ? $datas['is_aktif'] : '0',
								'modify_id'    => $_SESSION['teid'],
								'modify_time'  => date('Y-m-d H:i:s'),
							);
				$this->db->where('tknid',$datas['tknid']);
				$this->db->update('tbl_konsumen',$data);

		} else {

				$data = array('
								nama_konsumen' => $datas['nama_konsumen'],
								'no_telp'	   => $datas['no_telp'],
								'alamat'	   => $datas['alamat'],
								'is_aktif'	   => isset($datas['is_aktif']) ? $datas['is_aktif'] : '0',
								'create_id'    => $_SESSION['teid'],
							);
				$this->db->insert('tbl_konsumen',$data);
		}

	}

	public function hapus_konsumen($tknid) {

		$this->db->query("DELETE FROM tbl_konsumen WHERE tknid = ".$tknid);

	}

	public function edit_konsumen($tknid,$nama_konsumen) {

		$this->db->query("UPDATE tbl_konsumen SET nama_konsumen = '".$nama_konsumen."' WHERE tknid = ".$tknid);

	}

	## -------------------------------------------------------------------------------------------- ##
	/* E: KONSUMEN */
	## -------------------------------------------------------------------------------------------- ##

	## -------------------------------------------------------------------------------------------- ##
	/* S: WARNA */
	## -------------------------------------------------------------------------------------------- ##


	public function list_warna($addsql="") {
 
		$data = $this->db->query("SELECT a.*,(CASE WHEN COALESCE(b.jml,0) > 0 THEN 1 ELSE 0 END) as ada_pakai FROM tbl_warna a 
								  LEFT JOIN (
								  			SELECT a.twid,COUNT(a.twid) as jml FROM tbl_barang a
								  			GROUP BY a.twid
								  		) b ON (a.twid = b.twid)
								  		WHERE 1=1 $addsql
								  ORDER BY a.twid");
		return $data;
	}

	public function list_warna_row($twid) {
 
		$data = $this->db->query("SELECT * FROM tbl_warna WHERE twid = ".$twid);
		return $data->row();
	}

	public function add_warna($nama_warna,$is_aktif,$tkwid) {

		$data = array(
				'nama_warna' => $nama_warna,
				'is_aktif' => $is_aktif,
				'create_id' => $_SESSION['teid'],
				'tkwid'		=> $tkwid,
			);
		$this->db->insert('tbl_warna',$data);
	}

	public function hapus_warna($twid) {

		$this->db->query("DELETE FROM tbl_warna WHERE twid = ".$twid);

	}

	public function edit_warna($twid,$nama_warna,$is_aktif,$tkwid) {

		$this->db->query("UPDATE tbl_warna SET nama_warna = '".$nama_warna."',is_aktif='".$is_aktif."', tkwid='".$tkwid."',modify_time='".date('Y-m-d H:i:s')."',modify_id=".$_SESSION['teid']." WHERE twid = ".$twid);

	}

	public function kategori_warna(){

		$sql = "SELECT * FROM tbl_kategori_warna ORDER BY tkwid";
		$rs  = $this->db->query($sql);

		return $rs;
	}

	public function list_barang_template($addsql=""){

		$sql = "SELECT a.tbrid as id_barang,CONCAT(a.nama_barang,' - ',b.nama_warna) as nama_barang,c.tsid as id_satuan,d.satuan as nama_satuan,'' as qty
				FROM tbl_barang a
				JOIN tbl_warna b ON (a.twid = b.twid)
				JOIN tbl_barang_satuan c ON (a.tbrid = c.tbrid)
				JOIN tbl_satuan d ON (c.tsid = d.tsid)
				JOIN tbl_jenis_barang e ON (e.tjbid = a.tjbid)
				WHERE 1=1 $addsql
				ORDER BY a.tbrid,CONCAT(a.nama_barang,' - ',b.nama_warna),c.tbsid";
		$rs  = $this->db->query($sql);

		return $rs;
	}

	## -------------------------------------------------------------------------------------------- ##
	/* E: WARNA */
	## -------------------------------------------------------------------------------------------- ##

	public function hapus_satuan_barang_detail($tbsid){

		$this->db->query("DELETE FROM tbl_barang_satuan WHERE tbsid = ".$tbsid);
	}
 	

 	public function getMaster($key,$value) {

 		if ( $key == 'pelaksana' || $key == 'kepala_tukang' || $key == 'pengaju' || $key == 'pengirim_karyawan' || $key == 'id_kasir') {

 			$data = $this->db->query("SELECT nama_karyawan FROM tbl_emp WHERE teid = ".$value);
 			return $data->row()->nama_karyawan;

 		} else if ( $key == 'pengirim' ) {

 			$data = $this->db->query("SELECT concat(nama_kendaraan,' ( ',no_polisi,' )') as nama_kendaraan FROM tbl_kendaraan WHERE tkid = ".$value);
 			return $data->row()->nama_kendaraan;
 		} else if ( $key == 'bidang' ) {

 			$data = $this->db->query("SELECT nama_bidang FROM tbl_bidang WHERE tbid = ".$value);
 			return $data->row()->nama_bidang;
 		} else if ( $key == 'supplier' ) {
 			$data = $this->db->query("SELECT nama_supplier FROM tbl_supplier WHERE tspid = ".$value);
 			return $data->row()->nama_supplier;
 		} 
 	}

 	public function list_konfig($sql_filter) {
 
		$data = $this->db->query("SELECT a.* FROM tbl_config a WHERE 1=1 ".$sql_filter." ORDER BY a.tcid");
		return $data;
	}

	public function list_coa($sql_filter,$ajax='f') {
 
		$data = $this->db->query("SELECT a.* FROM tbl_coa a WHERE 1=1 ".$sql_filter." ORDER BY a.coatid,a.coacode");

		if ( $ajax == 't' ){
			$data = json_encode($data->result_array());
		}

		return $data;
	}

	public function list_mandatory_coa() {
 
		$data = $this->db->query("SELECT a.* FROM tbl_coa_mandatory a WHERE 1=1");
		return $data;
	}

	public function getData($datas,$tbl){
		$sql = "SELECT * FROM ".$tbl." WHERE tcid = ".$datas['tcid'];
		$rs  = $this->db->query($sql);
		$data_menu = json_encode($rs->result_array());
		return $data_menu;
	}

	public function saveData($datas,$tbl)
	{
		if ( $tbl == 'tbl_config' ) 
		{
			
			$data = array(
				'data' => $datas['data_konfig'],
			);

			$this->db->where('tcid',$datas['tcid']);
			$ok = $this->db->update($tbl,$data);
			$msg = "Edit Konfigurasi Berhasil";

			if ( $ok ) {
				$data_menu = json_encode(array('status'=>'200','msg'=>$msg));
			} else {
				$msg_gagal = ( $datas['tcid'] > 0 ) ? "Edit Konfigurasi Gagal" : "Tambah Konfigurasi Gagal";
				$data_menu = json_encode(array('status'=>'201','msg'=>$msg_gagal));
			}
			return $data_menu;

		}elseif( $tbl == 'tbl_coa' ) {

			if ( $datas['coaid'] == 0 ) 
			{

				$data = array(
					'coacode' => $datas['coacode'],
					'coaname' => $datas['coaname'],
					'coatid' => $datas['coatid'],
					'tcmid' => $datas['tcmid'],
					'is_aktif' => $datas['is_aktif'],
				);

				$ok = $this->db->insert($tbl,$data);
				$msg = "Tambah COA Berhasil";

			} else {

				$data = array(
					'coacode' => $datas['coacode'],
					'coaname' => $datas['coaname'],
					'coatid' => $datas['coatid'],
					'tcmid' => $datas['tcmid'],
					'is_aktif' => $datas['is_aktif'],
				);

				$this->db->where('coaid',$datas['coaid']);
				$ok = $this->db->update($tbl,$data);
				$msg = "Edit COA Berhasil";

			}

			if ( $ok ) {
				$data_menu = json_encode(array('status'=>'200','msg'=>$msg));
			} else {
				$msg_gagal = ( $datas['coaid'] > 0 ) ? "Edit COA Gagal" : "Tambah COA Gagal";
				$data_menu = json_encode(array('status'=>'201','msg'=>$msg_gagal));
			}
			return $data_menu;
		}
	}
}