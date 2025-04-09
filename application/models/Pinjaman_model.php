<?php
class Pinjaman_model extends CI_Model {
	public function __construct()
	{
		$this->load->database();
	}

	## -------------------------------------------------------------------------------------------- ##
	/* S: KARYAWAN */
	## -------------------------------------------------------------------------------------------- ##

	public function list_karyawan($addsql="") {
 		
 		$sql = "SELECT a.*,b.nama_role 
 				FROM tbl_emp a
 				JOIN tbl_role b ON (a.trid = b.trid)
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
	public function is_base64_encoded($str) {
        $cek_match = "data:image/png;base64";
        if(strpos($str, $cek_match) !== false){
            return true;
        } else{
            return false;
        }
    }
    public function is_base64_encoded_jpg($str) {
        $cek_match = "data:image/jpg;base64";
        if(strpos($str, $cek_match) !== false){
            return true;
        } else{
        	$cek_match2 ="data:image/jpeg;base64";
        	if(strpos($str, $cek_match2) !== false){
        		return true;
        	}else{
        		$cek_match3 ="data:image/webp;base64";
        		if(strpos($str, $cek_match3) !== false){
        			return true;
        		}else{
            		return false;
        		}
        	}
        }
    }

    public function UniqueRandomNumbersWithinRange($min, $max, $quantity) {
            $numbers = range($min, $max);
            shuffle($numbers);
            return array_slice($numbers, 0, $quantity);
        }

    public function convert_base64_to_jpeg($base64_string, $ext)
        {

            $source_dir = "./assets/foto_anggota/";
            //$get_rand_number = UniqueRandomNumbersWithinRange(0,25,5);
            $get_rand_number = $this->UniqueRandomNumbersWithinRange(0,50,3);
            $row_rand_number = implode('',$get_rand_number);
            $dir = $source_dir;

            if(!is_dir($dir)) //Cek apakah direktorinya sudah ada, kalau belum ada buat direktori baru
            {
                mkdir($dir,0777);
            }

            $file_name = $source_dir."foto_anggota_".$row_rand_number.".".$ext;

            $ifp = fopen( $file_name, 'wb' );
            $data = explode( ',', $base64_string );
            fwrite( $ifp, base64_decode( $data[ 1 ] ) );
            fclose( $ifp );

            return $file_name;
        }

	public function saveData($datas,$tbl)
	{
		if ( $tbl == 'tbl_emp' ) 
		{

			$extnya = "";
			$cek_match_folder = "foto_anggota";
			if(strpos($datas['data_image'], $cek_match_folder) === false) { 

				$cek_image_is_base64 = $this->is_base64_encoded($datas['data_image']);
				if ( !$cek_image_is_base64 ) {
					$cek_image_is_base64_jpg = $this->is_base64_encoded_jpg($datas['data_image']);
					if ( !$cek_image_is_base64_jpg )  {
						$datas['data_image'] = null;
					}else{
						$extnya = "jpg";
					}
				}else{
					$extnya = "png";
				}
			}

			if ( $datas['data_image'] != null && $datas['data_image'] != '' && $extnya != "" ) {
				$datas['data_image'] = $this->convert_base64_to_jpeg($datas['data_image'], $extnya);
			}

			if ( $datas['teid'] == 0 ) 
			{

				$data = array(
					'nama_karyawan' => $datas['nama_anggota'],
					'trid'			=> $datas['jenis_anggota'],
					'no_telp'		=> $datas['no_hp'],
					'jenis_kelamin' => 'm',
					'alamat'		=> $datas['alamat_ktp'],
					'nik'		=> (isset($datas['nik'])) ? $datas['nik'] : null,
					'is_aktif'  => (isset($datas['status_anggota'])) ? $datas['status_anggota'] : '0',
					'alamat_domisili'		=> $datas['alamat_domisili'],
					'foto'		=> (isset($datas['data_image'])) ? $datas['data_image'] : null,
					'no_kk'		=> $datas['no_kk'],
					'pasangan'		=> $datas['pasangan'],
					'create_id'     => $_SESSION['teid'],
				);

				$ok = $this->db->insert($tbl,$data);
				$msg = "Registrasi Anggota Berhasil";

			} else {

				$data = array(
					'nama_karyawan' => $datas['nama_anggota'],
					'trid'			=> $datas['jenis_anggota'],
					'no_telp'		=> $datas['no_hp'],
					'jenis_kelamin' => 'm',
					'alamat'		=> $datas['alamat_ktp'],
					'nik'		=> (isset($datas['nik'])) ? $datas['nik'] : null,
					'is_aktif'  => (isset($datas['status_anggota'])) ? $datas['status_anggota'] : '0',
					'alamat_domisili'		=> $datas['alamat_domisili'],
					'foto'		=> (isset($datas['data_image'])) ? $datas['data_image'] : null,
					'no_kk'		=> $datas['no_kk'],
					'pasangan'		=> $datas['pasangan'],
					'modify_id'     => $_SESSION['teid'],
					'modify_time'   => date('Y-m-d H:i:s'),
				);

				$this->db->where('teid',$datas['teid']);
				$ok = $this->db->update($tbl,$data);
				$msg = "Edit Anggota Berhasil";

			}

			if ( $ok ) {
				$data_menu = json_encode(array('status'=>'200','msg'=>$msg));
			} else {
				$msg_gagal = ( $datas['teid'] > 0 ) ? "Edit Anggota Gagal" : "Registrasi Anggota Gagal";
				$data_menu = json_encode(array('status'=>'201','msg'=>$msg_gagal));
			}
			return $data_menu;

		}elseif( $tbl == 'tbl_pinjaman' ) {

			if ( $datas['tpid'] == '0' ) {

				$data = array(
					'teid' => $datas['teid'],
					'tanggal' => date('Y-m-d',strtotime($datas['tanggal_pinjam'])),
					'jumlah_pinjaman' => $datas['nominal_pinjam'],
					'tenor' => $datas['tenor'],
					'provisi' => $datas['provisi'],
					'total_real_pinjaman' => $datas['real_pinjam'],
					'simpanan_wajib' => $datas['simpanan_wajib'],
					'jasa_pinjaman' => $datas['jasa_pinjam'],
					'total_piutang' => $datas['total_piutang'],
					'jumlah_cicilan' => $datas['jumlah_cicilan'],
					'create_id'     => $_SESSION['teid'],
				);

				$ok = $this->db->insert($tbl,$data);
				if ( $ok ) {
					$msg = "Data Pinjaman Berhasil Disimpan";
				}else{
					$msg = "Data Pinjaman Gagal Disimpan";
				}

			} else {

			}

			if ( $ok ) {
				$data_menu = json_encode(array('status'=>'200','msg'=>$msg));
			} else {
				$msg_gagal = ( $datas['tpid'] > 0 ) ? "Edit Pinjaman Gagal" : "Data Pinjaman Gagal Disimpan";
				$data_menu = json_encode(array('status'=>'201','msg'=>$msg_gagal));
			}
			return $data_menu;
		}else if ( $tbl == 'tbl_cicilan' ) {

			$rs_pinjaman = $this->db->query("SELECT * FROM tbl_pinjaman WHERE tpid = ".$datas['tpid']);

			$jml_piutang    = $rs_pinjaman->result_array()[0]['jumlah_pinjaman'] / $rs_pinjaman->result_array()[0]['tenor'];
			$simpanan_wajib = $rs_pinjaman->result_array()[0]['simpanan_wajib'] / $rs_pinjaman->result_array()[0]['tenor'];
			$jasa_pinjaman = $rs_pinjaman->result_array()[0]['jasa_pinjaman'] / $rs_pinjaman->result_array()[0]['tenor'];
			$total_bayar = $jml_piutang + $simpanan_wajib + $jasa_pinjaman;
			//$data_file = ($tbl == 'tbl_emp' ) ? $rs_old->result_array()[0]['foto'] : "";

			$data = array(
					'tpid' => $datas['tpid'],
					'tanggal' => date('Y-m-d',strtotime($datas['tanggal_bayar'])),
					'jml_piutang' => $jml_piutang * $datas['jml_cicilan'],
					'simpanan_wajib' => $simpanan_wajib * $datas['jml_cicilan'],
					'jasa_pinjaman' => $jasa_pinjaman * $datas['jml_cicilan'],
					'total_bayar' => $total_bayar * $datas['jml_cicilan'],
					'jml_cicilan' => $datas['jml_cicilan'],
					'create_id'     => $_SESSION['teid'],
			);

			$ok = $this->db->insert($tbl,$data);
			if ( $ok ) {
				$msg = "Data Pembayaran Berhasil Disimpan";
			}else{
				$msg = "Data Pembayaran Gagal Disimpan";
			}

			if ( $ok ) {
				$data_menu = json_encode(array('status'=>'200','msg'=>$msg));
			} else {
				$msg_gagal = ( $datas['tpid'] > 0 ) ? "Edit Pembayaran Gagal" : "Data Pembayaran Gagal Disimpan";
				$data_menu = json_encode(array('status'=>'201','msg'=>$msg_gagal));
			}
			return $data_menu;

		}else if ( $tbl == 'tbl_sukarela' ) {


			if ( $datas['tsid'] == '0' ) {

				$data = array(
					'teid' => $datas['teid'],
					'tanggal' => date('Y-m-d',strtotime($datas['tanggal_pinjam'])),
					'nominal' => $datas['nominal_pinjam'],
					'keterangan' => $datas['keterangan'],
					'create_id'     => $_SESSION['teid'],
				);

				$ok = $this->db->insert($tbl,$data);
				if ( $ok ) {
					$msg = "Data Simpanan Sukarela Berhasil Disimpan";
				}else{
					$msg = "Data Simpanan Sukarela Gagal Disimpan";
				}

			} else {

			}

			if ( $ok ) {
				$data_menu = json_encode(array('status'=>'200','msg'=>$msg));
			} else {
				$msg_gagal = ( $datas['tpid'] > 0 ) ? "Edit Simpanan Sukarela Gagal" : "Data Simpanan Sukarela Gagal Disimpan";
				$data_menu = json_encode(array('status'=>'201','msg'=>$msg_gagal));
			}
			return $data_menu;
		
		}else if ( $tbl == 'tbl_pengambilan_sukarela' ) {


			if ( $datas['tpsid'] == '0' ) {

				$data = array(
					'teid' => $datas['teid'],
					'tanggal' => date('Y-m-d',strtotime($datas['tanggal_pinjam'])),
					'nominal' => $datas['nominal_pinjam'],
					'nominal_awal' => $datas['sisa_saldo_nom'],
					'keterangan' => $datas['keterangan'],
					'create_id'     => $_SESSION['teid'],
				);

				$ok = $this->db->insert($tbl,$data);
				if ( $ok ) {
					$msg = "Data Pengambilan Sukarela Berhasil Disimpan";
				}else{
					$msg = "Data Pengambilan Sukarela Gagal Disimpan";
				}

			} else {

			}

			if ( $ok ) {
				$data_menu = json_encode(array('status'=>'200','msg'=>$msg));
			} else {
				$msg_gagal = ( $datas['tpid'] > 0 ) ? "Edit Pengambilan Sukarela Gagal" : "Data Pengambilan Sukarela Gagal Disimpan";
				$data_menu = json_encode(array('status'=>'201','msg'=>$msg_gagal));
			}
			return $data_menu;
		
		}
	}

	public function delData($datas,$tbl,$id){

		$rs_old = $this->db->query("SELECT * FROM $tbl WHERE ".$id." = ".$datas[$id]);
		$data_file = ($tbl == 'tbl_emp' ) ? $rs_old->result_array()[0]['foto'] : "";
		
		$sql = "DELETE FROM $tbl WHERE ".$id." = ".$datas[$id];
		$rs  = $this->db->query($sql);

		if ( $rs ) {
			if ( $data_file != '' ) {;
				unlink($data_file);
			}
			$data_menu = json_encode(array('status'=>'200'));
		} else {
			$data_menu = json_encode(array('status'=>'201'));
		}

		return $data_menu;
	}

	public function getData($datas,$tbl,$id){
		$sql = "SELECT * FROM ".$tbl." WHERE ".$id." = ".$datas['teid'];
		$rs  = $this->db->query($sql);
		$data_menu = json_encode($rs->result_array());
		return $data_menu;
	}

	public function list_pinjaman($addsql=""){
		$sql = "SELECT CONCAT('PJM',LPAD(b.tpid,8,'0')) as no_reff,b.tpid,b.tanggal,a.nama_karyawan,a.no_telp,c.nama_karyawan as pengurus,b.jumlah_pinjaman as nominal_pinjam,b.tenor
				FROM tbl_pinjaman b
				JOIN tbl_emp a ON (b.teid = a.teid)
				JOIN tbl_emp c ON (c.teid = b.create_id)
				WHERE 1=1 $addsql
 				ORDER BY b.tanggal";
		$data = $this->db->query($sql);
		return $data;
	}

	public function list_cicilan($addsql=""){
		$sql = "SELECT CONCAT('CPJ',LPAD(b.tcid,8,'0')) as no_reff,b.tcid,b.tanggal,a.nama_karyawan,a.no_telp,c.nama_karyawan as pengurus,b.total_bayar as nominal_pinjam,
				CONCAT('PJM',LPAD(b.tpid,8,'0')) as no_reff_pinjaman,b.jml_piutang as nominal_net_cicilan
				FROM tbl_cicilan b
				JOiN tbl_pinjaman x ON (x.tpid = b.tpid)
				JOIN tbl_emp a ON (x.teid = a.teid)
				JOIN tbl_emp c ON (c.teid = b.create_id)
				WHERE 1=1 $addsql
 				ORDER BY b.tanggal";
		$data = $this->db->query($sql);
		return $data;
	}

	public function list_sukarela($addsql=""){
		$sql = "SELECT CONCAT('SKRL',LPAD(b.tsid,8,'0')) as no_reff,b.tsid,b.tanggal,a.nama_karyawan,a.no_telp,c.nama_karyawan as pengurus,b.nominal as nominal_pinjam
				FROM tbl_sukarela b
				JOIN tbl_emp a ON (b.teid = a.teid)
				JOIN tbl_emp c ON (c.teid = b.create_id)
				WHERE 1=1 $addsql
 				ORDER BY b.tanggal";
		$data = $this->db->query($sql);
		return $data;
	}

	public function list_pengambilan($addsql=""){
		$sql = "SELECT CONCAT('ABL',LPAD(b.tpsid,8,'0')) as no_reff,b.tpsid,b.tanggal,a.nama_karyawan,a.no_telp,c.nama_karyawan as pengurus,b.nominal as nominal_pinjam
				FROM tbl_pengambilan_sukarela b
				JOIN tbl_emp a ON (b.teid = a.teid)
				JOIN tbl_emp c ON (c.teid = b.create_id)
				WHERE 1=1 $addsql
 				ORDER BY b.tanggal";
		$data = $this->db->query($sql);
		return $data;
	}

	public function list_aging_pinjaman($addsql=""){

		$get_coaid_piutang = $this->db->query('SELECT coaid FROM tbl_coa WHERE tcmid = 5')->row();
		if ($get_coaid_piutang) {
		    $get_coaid_piutang = $get_coaid_piutang->coaid; 
		}

		$sql = " 
				 SELECT CONCAT('PJM',LPAD(data_utama.id_pinjam,8,'0')) as no_reff_pinjaman,
				 te.nama_karyawan,
				 te.no_telp,
				 data_utama.tanggal_pinjam,
				 data_utama.sisa_saldo,
				 dp.jumlah_pinjaman,
				 dp.tenor,
				 data_utama.jml_cicilan
				 FROM (
				 SELECT 
				 (CASE WHEN a.ttjid = 2 THEN a.reff_id ELSE a.other_id END) as id_pinjam,
				 MAX(CASE WHEN a.ttjid = 2 THEN a.other_id END) as id_anggota,
				 MAX(CASE WHEN a.ttjid = 2 THEN DATE(a.jurnal_date) END) as tanggal_pinjam,
				 SUM(b.debet-b.credit) as sisa_saldo,
				 SUM(COALESCE(tc.jml_cicilan,0)) as jml_cicilan
				 FROM tbl_jurnal a
				 JOIN tbl_jurnal_d b ON (a.tjid = b.tjid)
				 LEFT JOIN tbl_cicilan tc ON (tc.tcid = a.reff_id AND a.ttjid = 3)
				 WHERE 1=1
				 AND b.coaid = ".$get_coaid_piutang."
				 $addsql
				 GROUP BY (CASE WHEN a.ttjid = 2 THEN a.reff_id ELSE a.other_id END)
				 HAVING SUM(b.debet-b.credit) <> 0
				 ) data_utama
				 JOIN tbl_pinjaman dp ON (dp.tpid = data_utama.id_pinjam)
				 JOIN tbl_emp te ON (te.teid = data_utama.id_anggota)
				 ORDER BY data_utama.tanggal_pinjam
				 ";
		$data = $this->db->query($sql);
		return $data;
	}

	public function getPiutangPinjaman($datas){
		$sql = "SELECT 
				CONCAT('PJM',LPAD(a.tpid,8,'0')) as no_reff,
				a.tanggal as tanggal_pinjam,
				a.jumlah_pinjaman,
				ROUND(a.jumlah_pinjaman / a.tenor) as cicilan_tenor,
				ROUND(a.simpanan_wajib / a.tenor) as cicilan_simpanan_wajib,
				ROUND(a.jasa_pinjaman / a.tenor) as cicilan_jasa_pinjaman,
				a.tenor,
				a.total_piutang-COALESCE(SUM(b.total_bayar),0) as sisa_piutang,
				a.total_piutang,
				a.jumlah_cicilan,
				SUM(b.jml_cicilan) as jml_cicilan_bayar
				FROM 
				tbl_pinjaman a 
				LEFT JOIN tbl_cicilan b ON (a.tpid = b.tpid)
				WHERE a.tpid = ".$datas['tpid']."
				GROUP BY CONCAT('PJM',LPAD(a.tpid,8,'0')),a.tanggal,a.jumlah_pinjaman,ROUND(a.jumlah_pinjaman / a.tenor) 
				,ROUND(a.simpanan_wajib / a.tenor),ROUND(a.jasa_pinjaman / a.tenor),a.tenor,a.total_piutang,a.jumlah_cicilan
				";

		$rs  = $this->db->query($sql);
		$data_menu = json_encode($rs->result_array());
		return $data_menu;

	}

	public function list_karyawan_sukarela($addsql="") {
 		
 		$sql = "SELECT a.teid,a.nik,a.nama_karyawan,SUM(c.credit-c.debet) as saldo
 				FROM tbl_emp a
 				JOIN tbl_role b ON (a.trid = b.trid)
 				LEFT JOIN tbl_jurnal_d c ON (a.teid = c.reff_id AND c.ttjid IN (4,5) AND c.coaid = (SELECT x.coaid FROM tbl_coa x WHERE x.tcmid = 3))
 				WHERE 1=1 $addsql
 				GROUP BY a.teid,a.nik,a.nama_karyawan
 				HAVING SUM(c.credit-c.debet) <> 0
 				ORDER BY a.teid";
		$data = $this->db->query($sql);
		return $data;
	}

}