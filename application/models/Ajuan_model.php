<?php
class Ajuan_model extends CI_Model {
	public function __construct()
	{
		$this->load->database();
	}

	public function data_list($tbl,$field_order) {
		
		if ( $tbl == 'tbl_project_ajuan' ) {

			$sql = "SELECT a.*,b.nama_karyawan as pengaju FROM tbl_project_ajuan a
					JOIN tbl_emp b ON (a.create_id = b.teid)
					ORDER BY a.tpaid";

		}  else {
			if ($tbl == 'tbl_ajuan_kasbon' ) {

				$sql = "SELECT a.*,b.nama_karyawan as nama_pengaju FROM tbl_ajuan_kasbon a 
						JOIN tbl_emp b ON (a.teid_pengaju = b.teid)";

			} else {

				$sql = "SELECT * FROM ".$tbl." ORDER BY ".$field_order;

			}

		}

	
		$data = $this->db->query($sql);

		return $data;
	}

	public function ajuankasbon_row($takid) {

		$sql = "SELECT a.*,b.nama_karyawan as nama_pengaju FROM tbl_ajuan_kasbon a 
						JOIN tbl_emp b ON (a.teid_pengaju = b.teid)";
		 $data = $this->db->query($sql);

		 return $data->row();
	}

	public function get_data_ajuan($tpaid) {
		$sql = "SELECT * FROM tbl_project_ajuan WHERE tpaid = ".$tpaid;
		$data = $this->db->query($sql);
		return $data->row();
	}

	public function hapus_ajuankas($takid) {
		$this->db->query("DELETE FROM tbl_ajuan_kasbon WHERE takid = ".$takid);
	}

	public function hapus_ajuanadm($tpaaid) {
		$this->db->query("DELETE FROM tbl_project_ajuan_adm WHERE tpaaid = ".$tpaaid);	
	}

	public function hapus_ajuan($tpaid) {

		$sql_ajuan_tpid = "SELECT * FROM tbl_project_ajuan_tpid WHERE tpaid = ".$tpaid;
		$rs             = $this->db->query($sql_ajuan_tpid);
		foreach ($rs->result_array() as $key) {

			$this->db->query("UPDATE tbl_project SET sisa_penggunaan_pagu = sisa_penggunaan_pagu + ".$key['sisa_setelah_ajuan_project']." WHERE tpid = ".$key['tpid']);
		}


		$this->db->query("DELETE FROM tbl_project_ajuan_kas WHERE tpaid = ".$tpaid);
		$this->db->query("DELETE FROM tbl_project_ajuan_tpid WHERE tpaid = ".$tpaid);
		$this->db->query("DELETE FROM tbl_project_ajuan WHERE tpaid = ".$tpaid);
	}

	public function list_ajuan_row($tpaid) {

		$sql = "SELECT a.*,b.nama_karyawan as pengaju FROM tbl_project_ajuan a
			    JOIN tbl_emp b ON (a.create_id = b.teid)
				WHERE a.tpaid = ".$tpaid;
		$rs = $this->db->query($sql);

		return $rs->row();
	}

	public function list_ajuan_kas_row($tpaid) {
		$sql = "SELECT * FROM tbl_project_ajuan_kas WHERE tpaid = ".$tpaid;
		$rs = $this->db->query($sql);

		return $rs;
	}

	public function list_ajuan_project_row($tpaid) {
		$sql = "SELECT a.nama_project,b.sisa_setelah_ajuan_project,b.sisa_sebelum_ajuan_project FROM tbl_project_ajuan_tpid b 
				JOIN tbl_project a ON (b.tpid = a.tpid)
				WHERE b.tpaid = ".$tpaid;
		$rs = $this->db->query($sql);

		return $rs;
	}

	public function insertAjuanKasbon($data) {

		 $data_row = array(
		 	'tanggal_ajuan' => date('Y-m-d',strtotime($data['tgl_ajuan'])),
		 	'teid_pengaju'  => $data['teid_pengaju'],
		 	'jumlah'            => $data['jumlah'],
		 	'create_id'         => $_SESSION['teid'],
		 	'keterangan'         => $data['keperluan'],
		 );

		 $this->db->insert('tbl_ajuan_kasbon',$data_row);
	}

	public function insertAjuan($data) {

		$data_header = array(
			'tanggal_ajuan' => date('Y-m-d',strtotime($data['tgl_ajuan'])),
			'total_ajuan'   => $data['total_ajuan_proj'],
			//'sisa_setelah_ajuan' => $data['sisa_biaya_proj'],
			'create_id'      => $_SESSION['teid'],
			'keterangan'	 => $data['keterangan'],
			'is_out'         => $data['is_out'],
			//'teid'   => $data['teid_pengaju'],
		);

		$this->db->insert('tbl_project_ajuan',$data_header);

		$sql_inc = "SHOW TABLE STATUS WHERE name='tbl_project_ajuan'";
		$row_inc = $this->db->query($sql_inc);
		$data_row   = $row_inc->row();

		$idnya = $data_row->Auto_increment;
		$tpaid = $idnya - 1;

		$persentase_pembagian = $_POST['total_ajuan_proj'] / $_POST['sisa_sbm_ajuan'];

		$data_tpid = json_decode($_POST['arrtpid']);

		$data_ajuan = json_decode($_POST['arrajuan']);

		/*foreach( $data_tpid as $key => $value ) {

			$data_project = array (
				'tpaid' => $tpaid,
				'tpid'  => $key,
				'sisa_setelah_ajuan_project' => round($persentase_pembagian*$value),
				'sisa_sebelum_ajuan_project' => $value,
			);

			$this->db->query("UPDATE tbl_project SET sisa_penggunaan_pagu = sisa_penggunaan_pagu - ".$data_project['sisa_setelah_ajuan_project']." WHERE tpid = ".$key);

			$this->db->insert('tbl_project_ajuan_tpid',$data_project);
		}*/

		foreach ( $data_ajuan as $k => $v) {

			 $list_ajuan = explode(':',$v);
		 	 $data_ajuannya = array (
		 	 	'tpaid' => $tpaid,
		 	 	'description' => $list_ajuan[0],
		 	 	'nominal'     => $list_ajuan[1],
		 	 );

		 	 $this->db->insert('tbl_project_ajuan_kas',$data_ajuannya);
		 } 

	}

	function insertadm($data) {

		$data['create_id'] = $_SESSION['teid'];
		$this->db->insert('tbl_project_ajuan_adm',$data);
	}
 
}