<?php
class Project_model extends CI_Model {
	public function __construct()
	{
		$this->load->database();
	}

	public function cekDetailProject($tpid){
		$sql = "SELECT count(*) as jml FROM tbl_project_barang WHERE tpid = ".$tpid;
		$count = $this->db->query($sql);
		$jml   = $count->row()->jml;

		if ( $jml > 0 ) {

			$sql_delete = "DELETE FROM tbl_project_barang WHERE tpid = ".$tpid;
			$this->db->query($sql_delete);

		}
		
	}

	public function insertData($data,$tbl) {
		$this->db->insert($tbl,$data);
	}

	public function update_tgl($data){

		$this->db->query("UPDATE tbl_project SET tgl_selesai = '".date('Y-m-d',strtotime($data['tgl']))."' WHERE tpid = ".$data['tpid']);
	}

	public function editData($data,$tbl,$field,$id,$sisanya_pagu){
		

		$query_sisa_pagu = $this->db->query("SELECT sisa_penggunaan_pagu FROM tbl_project WHERE tpid = ".$id);
		$data_sisa = $query_sisa_pagu->row();

		$sisa_pagu = $data_sisa->sisa_penggunaan_pagu;

		$potongan_other_amount = (($data['pot_other_persen'] / 100) * $data['pagu']);
		$buat_sisa_pagu = $data['pot_other_amount'] - $potongan_other_amount;

		/*die("<pre>
				sisa pagu awal = ".$sisa_pagu." <br>
				sisanya_pagu   = ".$sisanya_pagu." <br>
				potongan awal  = ".$data['pot_other_amount']." <br>
				potongan baru  = ".$potongan_other_amount." <br>
				selisih_potongan = ".$buat_sisa_pagu."
			</pre>");*/

		$data['sisa_penggunaan_pagu'] = $sisa_pagu + ($sisanya_pagu) + $buat_sisa_pagu;
		$data['pot_other_amount']     = $potongan_other_amount;

		$this->db->where($field,$id);
		$this->db->update($tbl,$data);

	}

	public function list_penerimaan($data){
		
		$addsql = "";

		if ( $data['tipe_trans'] == 1 ) { 

			if ( $data['supplier'] != '' ) {

			$addsql .= " AND a.tspid = ".$data['supplier'];

			}

			if ( $data['nam_barang'] != '' ) {

			$addsql .= " AND lower(b.nama_barang) like lower('%".$data['nam_barang']."%')";

			}


			if ( $data['kd_terima'] != '' ) {

				$addsql .= " AND a.reff_code='".$data['kd_terima']."' ";
			}

			if ( $data['kd_ajuan'] != '' ) {

				$addsql .= " AND a.other_reff_code='".$data['kd_ajuan']."' ";
			}

			$sql = "SELECT a.ttbid,a.tgl_trans,concat(b.nama_barang,' - ',f.nama_warna) as nama_barang,c.nama_supplier,a.no_faktur,a.keterangan,d.satuan,x.harga_satuan,x.harga_total as total,x.vol,a.other_reff_code as po,a.reff_code as grn,
				e.nama_karyawan as user_terima,
				COALESCE(a.ppn_rp,0) as ppn_rp,
				COALESCE(a.total_diskon,0) as total_diskon,
				COALESCE(a.total_amount,0) as total_amount

				FROM tbl_transaksi_barang a
				JOIN tbl_transaksi_barang_d x ON (a.ttbid = x.ttbid)
				JOIN tbl_barang b ON (x.tbrid = b.tbrid)
				JOIN tbl_supplier c ON (c.tspid = a.tspid)
				JOIN tbl_satuan d ON (d.tsid = x.tsid)
				JOIN tbl_emp e on (e.teid = a.create_id)
				JOIN tbl_warna f ON (f.twid = b.twid)

				WHERE a.tipe_trans = 1 $addsql
				AND DATE(a.tgl_trans) BETWEEN '".date('Y-m-d',strtotime($data['tgl1']))."' AND '".date('Y-m-d',strtotime($data['tgl2']))."'
				ORDER BY a.ttbid
				";

		} else { 

			if ( $data['supplier'] != '' ) {

			$addsql .= " AND a.tspid = ".$data['supplier'];

			}

			if ( $data['nam_barang'] != '' ) {

			$addsql .= " AND lower(b.nama_barang) like lower('%".$data['nam_barang']."%')";

			}

			if ( $data['kode_ajuan']!='') {
				$addsql .= " AND a.reff_code = '".$data['kode_ajuan']."'";
			}

			$sql = "SELECT a.ttbid,a.tgl_trans,b.nama_barang,c.nama_karyawan,a.no_faktur,a.keterangan,d.satuan,x.harga_satuan,x.harga_total as total,x.vol,a.alamat,a.reff_code,e.nama_supplier
				FROM tbl_transaksi_barang a
				JOIN tbl_transaksi_barang_d x ON (a.ttbid = x.ttbid)
				JOIN tbl_barang b ON (x.tbrid = b.tbrid)
				JOIN tbl_emp c ON (c.teid = a.create_id)
				LEFT JOIN tbl_supplier e ON (e.tspid = a.tspid)
				JOIN tbl_satuan d ON (d.tsid = x.tsid)

				WHERE a.tipe_trans = ".$data['tipe_trans']." $addsql
				AND DATE(a.tgl_trans) BETWEEN '".date('Y-m-d',strtotime($data['tgl1']))."' AND '".date('Y-m-d',strtotime($data['tgl2']))."'
				ORDER BY a.ttbid
				";

		}

		
		$data = $this->db->query($sql);

		return $data;
	}

	public function list_detail($data){

		$addsql = "";

		if ( $data['pelaksana'] != '' ) {

			$addsql .= " AND g.teid_pelaksana = '".$data['pelaksana']."'";
		}

		if ( $data['pengirim'] != '' ) {

			$addsql .= " AND a.tkid = '".$data['pengirim']."'";
		}

		if ( $data['bidang'] != '' ) {

			$addsql .= " AND g.tbid = '".$data['bidang']."'";
		}

		if ( $data['nam_project'] != '' ) {

			$addsql .= " AND lower(g.nama_project) like lower('%".$data['nam_project']."%')";
		}

		$sql = "
			SELECT g.tgl_mulai,g.tgl_selesai,b.nama_karyawan as pelaksana,c.nama_bidang,g.nama_project,
			concat(g.kelurahan,',',g.kecamatan,',',g.kabupaten) as alamat,
			d.no_polisi as nama_kendaraan,e.nama_barang,a.vol,f.satuan,a.harga_satuan,a.total,a.tpid,a.no_faktur,a.tgl_kirim

			FROM tbl_project_barang a
			JOIN tbl_project g ON (g.tpid = a.tpid)
			JOIN tbl_emp b ON (g.teid_pelaksana = b.teid)
			JOIN tbl_bidang c ON (g.tbid = c.tbid)
			JOIN tbl_kendaraan d ON (a.tkid = d.tkid)
			JOIN tbl_barang e ON (a.tbrid = e.tbrid)
			JOIN tbl_satuan f ON (a.satuan = f.tsid)
			WHERE DATE(g.tgl_mulai) BETWEEN '".date('Y-m-d',strtotime($data['tgl1']))."' AND '".date('Y-m-d',strtotime($data['tgl2']))."'
			$addsql
			ORDER BY g.tpid ASC
		";
		
		$data = $this->db->query($sql);

		return $data;
	}

	public function list_ajuan_before($data){

		$addsql = "";

		if ( $data['pelaksana'] != '' ) {

			$addsql .= " AND a.teid = '".$data['pelaksana']."'";
		}

		$sql = "
			SELECT 
			SUM(CASE WHEN a.is_out = 1 THEN b.nominal*-1 ELSE b.nominal END) as total
			FROM tbl_project_ajuan a
			JOIN tbl_project_ajuan_kas b ON (a.tpaid = b.tpaid)
			JOIN tbl_emp c ON (a.create_id = c.teid)
			WHERE DATE(a.tanggal_ajuan) < '".date('Y-m-d',strtotime($data['tgl1']))."'
			$addsql
		";		
		$data = $this->db->query($sql);
		
		return $data;	
	}

	public function list_ajuan($data){

		$addsql = "";

		if ( $data['pelaksana'] != '' ) {

			$addsql .= " AND a.teid = '".$data['pelaksana']."'";
		}

		$sql = "
			SELECT 
			a.tpaid,
			a.tanggal_ajuan,
			c.nama_karyawan as pengaju,
			b.description as rincian,
			b.nominal,
			'' as project,
			a.keterangan,
			a.is_out,
			(CASE WHEN a.is_out = 1 THEN b.nominal*-1 ELSE b.nominal END) as new_nominal
			FROM tbl_project_ajuan a
			JOIN tbl_project_ajuan_kas b ON (a.tpaid = b.tpaid)
			JOIN tbl_emp c ON (a.create_id = c.teid)
			WHERE DATE(a.tanggal_ajuan) BETWEEN '".date('Y-m-d',strtotime($data['tgl1']))."' AND '".date('Y-m-d',strtotime($data['tgl2']))."'
			$addsql
			ORDER BY a.tanggal_ajuan ASC
		";		
		$data = $this->db->query($sql);
		
		return $data;	
	}

	public function list_ajuan_kasbon($data){

		$addsql = "";

		if ( $data['pengaju'] != '' ) {

			$addsql .= " AND a.teid_pengaju = '".$data['pengaju']."'";
		}

		if ( $data['kepala_tukang'] != '' ) {

			$addsql .= " AND a.teid_kepalatukang = '".$data['kepala_tukang']."'";
		}

		$sql = "
			SELECT a.tanggal_ajuan,b.nama_karyawan as pengaju,
			c.nama_karyawan as kepala_tukang,
			a.jumlah,a.keterangan
			FROM tbl_ajuan_kasbon a
			JOIN tbl_emp b ON (a.teid_pengaju = b.teid)
			LEFT JOIN tbl_emp c ON (a.teid_kepalatukang = c.teid)
			WHERE DATE(a.tanggal_ajuan) BETWEEN '".date('Y-m-d',strtotime($data['tgl1']))."' AND '".date('Y-m-d',strtotime($data['tgl2']))."'
			$addsql
			ORDER BY a.tanggal_ajuan ASC,a.teid_pengaju
		";		
		$data = $this->db->query($sql);
		
		return $data;	
	}


	public function list_rekap($data){

		$addsql = "";

		if ( $data['pelaksana'] != '' ) {

			$addsql .= " AND a.teid_pelaksana = '".$data['pelaksana']."'";
		}

		$sql = "
			SELECT 
				   a.tpid,
				   concat(a.nama_project,' (',f.nama_bidang,')') as nama_project,
				   b.nama_karyawan as pelaksana,
				   SUM(a.anggaran) as total_nilai_kontrak,
				   SUM(a.pagu) as real_cost,
				   SUM(COALESCE(c.total_barang,0)) as total_barang,
				   SUM(COALESCE(d.total_ajuan,0)) as total_ajuan,
				   SUM(COALESCE(e.backup,0) + COALESCE(e.kontrak_ajuan,0) + COALESCE(e.pho,0) + COALESCE(e.keuangan,0) + COALESCE(e.pmi,0) + COALESCE(e.basp,0) + COALESCE(e.bkd,0) + COALESCE(e.ajuan_lain,0)) as total_adm
			FROM tbl_project a
			JOIN tbl_emp b ON (a.teid_pelaksana = b.teid)
			LEFT JOIN (
						SELECT SUM(total) as total_barang,tpid FROM tbl_project_barang
						GROUP BY tpid
					) c ON (c.tpid = a.tpid)
			LEFT JOIN (
					SELECT a.tpid,SUM(a.sisa_setelah_ajuan_project) as total_ajuan
					FROM tbl_project_ajuan_tpid a
					GROUP BY a.tpid
			) d ON (a.tpid = d.tpid)
			LEFT JOIN tbl_project_ajuan_adm e ON (e.tpid = a.tpid)
			INNER JOIN tbl_bidang f ON (f.tbid = a.tbid)
			WHERE DATE(a.tgl_mulai) BETWEEN '".date('Y-m-d',strtotime($data['tgl1']))."' AND '".date('Y-m-d',strtotime($data['tgl2']))."'
			$addsql
			GROUP BY b.nama_karyawan,a.tpid,a.nama_project
			ORDER BY b.nama_karyawan ASC
		";		
		$data = $this->db->query($sql);

		return $data;
	}

	public function getAlamat($term){

		$sql = "select tbl_kelurahan.kode_pos, concat(tbl_kelurahan.kode_pos,' - ',tbl_kelurahan.kelurahan,',',tbl_kecamatan.kecamatan,',',tbl_kabupaten.kabupaten) as kelurahan
                                from tbl_kelurahan
                                        left join tbl_kecamatan on tbl_kecamatan.kec_id=tbl_kelurahan.kec_id and tbl_kelurahan.kab_id=tbl_kecamatan.kab_id and tbl_kelurahan.prov_id=tbl_kecamatan.prov_id
                                        left join tbl_kabupaten on tbl_kabupaten.kab_id=tbl_kelurahan.kab_id and tbl_kelurahan.prov_id=tbl_kabupaten.prov_id
                                        left join tbl_provinsi on tbl_provinsi.prov_id=tbl_kelurahan.prov_id
                                where (lower(tbl_kelurahan.kode_pos) like lower('%".$term."%') OR lower(tbl_kelurahan.kelurahan) like lower('%".$term."%'))
                                AND tbl_kelurahan.prov_id = 9
                                ";
		$data = $this->db->query($sql);
		return $data;
	}

	public function data_list_teid($teid_pelaksana) {
		$sql = "SELECT a.*,concat('Kel.',' ',a.kelurahan,', Kec. ',a.kecamatan,', Kab. ',a.kabupaten) as alamat2, c.nama_karyawan as pelaksana,d.nama_karyawan as kepala_tukang,b.nama_bidang
				    ,COALESCE(e.jml_barang,0) as jml_barang
				    ,COALESCE(e.total_barang,0) as total_barang
				    FROM tbl_project a
					JOIN tbl_bidang b ON (a.tbid = b.tbid) 
					JOIN tbl_emp c ON (a.teid_pelaksana = c.teid)
					JOIN tbl_emp d ON (a.teid_kepalatukang = d.teid)
					LEFT JOIN (
						SELECT count(tpid) as jml_barang,SUM(total) as total_barang,tpid FROM tbl_project_barang
						GROUP BY tpid
					) e ON (e.tpid = a.tpid)
					LEFT JOIN tbl_pembayaran_project xx ON (xx.tpid = a.tpid)
					WHERE a.teid_pelaksana = '".$teid_pelaksana."'
					AND xx.tpid IS NULL
					ORDER BY a.tpid";

		$data = $this->db->query($sql);

		return $data;
	}

	public function data_list($tbl,$field_order,$addsql='') {
		if ( $tbl == 'tbl_project' ) {

			$sql = "SELECT a.*,concat('Kel.',' ',a.kelurahan,', Kec. ',a.kecamatan,', Kab. ',a.kabupaten) as alamat2, c.nama_karyawan as pelaksana,d.nama_karyawan as kepala_tukang,b.nama_bidang
				    ,COALESCE(e.jml_barang,0) as jml_barang,COALESCE(xx.tpid,0) as tpid_bayar,xx.tgl_bayar,zz.backup,COALESCE(zz.kontrak_ajuan,0) as kontrak_ajuan,zz.pho,zz.keuangan,zz.pmi,zz.basp,zz.bkd,COALESCE(cc.nama_perusahaan,'-') as nama_perusahaan,b.nama_bidang,COALESCE(zz.ajuan_lain,0) as ajuan_lain,coalesce(e.total_barang,0) as totnom_barang,coalesce(f.pengeluaran_kas,0) as totnom_kas,COALESCE(xx.jumlah,0) as nom_bayar
				    FROM tbl_project a
					JOIN tbl_bidang b ON (a.tbid = b.tbid) 
					JOIN tbl_emp c ON (a.teid_pelaksana = c.teid)
					JOIN tbl_emp d ON (a.teid_kepalatukang = d.teid)
					JOIN tbl_perusahaan cc ON (a.tphid = cc.tphid)
					LEFT JOIN (
						SELECT count(tpid) as jml_barang,tpid,sum(total) as total_barang FROM tbl_project_barang
						GROUP BY tpid
					) e ON (e.tpid = a.tpid)
					LEFT JOIN (
						SELECT tpid,sum(sisa_setelah_ajuan_project) as pengeluaran_kas FROM tbl_project_ajuan_tpid
						GROUP BY tpid
					) f ON (f.tpid = a.tpid)
					LEFT JOIN tbl_pembayaran_project xx ON (xx.tpid = a.tpid)
					LEFT JOIN tbl_project_ajuan_adm zz ON (zz.tpid = a.tpid)
					WHERE 1=1 $addsql
					ORDER BY a.tpid";

		} else {

			$sql = "SELECT * FROM ".$tbl." ORDER BY ".$field_order;
	
		}

		$data = $this->db->query($sql);

		return $data;
	}

	public function updateProject($tpid,$sisa) {

		$this->db->query("UPDATE tbl_project SET sisa_penggunaan_pagu = '".$sisa."' WHERE tpid = ".$tpid);

	}

	public function verifBon($data) {

		$data_faktur = $data['faktur'];
		$data_faktur = explode(":",$data_faktur);
		$real_faktur = $data_faktur[0];
		$tgl_faktur_awal = explode("-",$data_faktur[1]);
		$tgl_faktur2 = $tgl_faktur_awal[1]."-".$tgl_faktur_awal[0]."-".$tgl_faktur_awal[2];
		$real_faktur_new = date('Y-m-d',strtotime($tgl_faktur2));

		$this->db->query("UPDATE tbl_project_barang SET type_bon = '".$data['tipe']."' WHERE tpid = ".$data['tpid']." AND no_faktur = '".$real_faktur."' AND tgl_kirim='".$real_faktur_new."'");
	}

	public function list_project_row($tpid) {
 		$sql = "SELECT a.*,c.nama_karyawan as pelaksana,d.nama_karyawan as kepala_tukang,b.nama_bidang,COALESCE(e.total_barang,0) as biaya_total_barang,COALESCE(zz.tpid,0) as ada_ajuan
 					,concat(a.nama_project,' ( ',b.nama_bidang,' )') as nama_project_new,f.nama_karyawan as nama_kepala,COALESCE(xx.tpid,0) as tpid_bayar
 					FROM tbl_project a
					JOIN tbl_bidang b ON (a.tbid = b.tbid) 
					JOIN tbl_emp c ON (a.teid_pelaksana = c.teid)
					JOIN tbl_emp d ON (a.teid_kepalatukang = d.teid)
					JOIN tbl_emp f ON (f.teid = a.teid_kepala)
					LEFT JOIN (
						SELECT SUM(total) as total_barang,tpid FROM tbl_project_barang
						GROUP BY tpid
					) e ON (e.tpid = a.tpid)
					LEFT JOIN tbl_pembayaran_project xx ON (xx.tpid = a.tpid)
					LEFT JOIN (
							SELECT a.tpid,SUM(a.sisa_setelah_ajuan_project) as total_ajuan
							FROM tbl_project_ajuan_tpid a
							GROUP BY a.tpid
							) zz ON (zz.tpid = a.tpid)
					WHERE a.tpid = ".$tpid."
					ORDER BY a.tpid";
		$data = $this->db->query($sql);
		return $data->row();
	}

	public function list_project_barang($tpid) {

		
		$new_tpid = $tpid;

		$row_baru = explode('_',$tpid);
		$addsql = "";

		if ( count($row_baru) > 1 ) {

			$new_tpid = $row_baru[0];
			$no_faktur = $row_baru[1];
			if ( $no_faktur != '0') {

				$faktur_detail = explode(':',$no_faktur);
				$tglnya = explode("-",$faktur_detail[1]);
				$tglbaru = $tglnya[1]."-".$tglnya[0]."-".$tglnya[2];
				$tglnyaa = date('Y-m-d',strtotime($tglbaru));

				$addsql = " AND a.no_faktur = '".$faktur_detail[0]."' AND a.tgl_kirim = '".$tglnyaa."'";
			}
		}

		$sql = "SELECT a.tpbid,a.tgl_kirim,a.no_faktur,a.tbrid,b.nama_barang,a.tkid,a.vol,a.satuan,a.harga_satuan,a.total,c.nama_project,a.type_bon FROM tbl_project_barang a 
				JOIN tbl_barang b ON (a.tbrid = b.tbrid) 
				JOIN tbl_project c ON (c.tpid = a.tpid)
				WHERE a.tpid = ".$new_tpid." $addsql ";
		$data = $this->db->query($sql);

		return $data;
	}



	public function hapus_project($tpid) {
		$this->db->query("DELETE FROM tbl_project_barang WHERE tpid = ".$tpid);
		$this->db->query("DELETE FROM tbl_project WHERE tpid = ".$tpid);
	}
 
	
}