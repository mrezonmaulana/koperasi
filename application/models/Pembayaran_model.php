<?php
class Pembayaran_model extends CI_Model {
	public function __construct()
	{
		$this->load->database();
	}

	public function insertPembayaran($data) {
		$data_insert = array(
			'tpid' => $data['tpid_orig'],
			'tgl_bayar' => date('Y-m-d',strtotime($data['tgl_ajuan'])),
			'jumlah'    => $data['jumlah_orig'],
			'keterangan' => $data['keperluan'],
			'create_id'  => $_SESSION['teid'],
		);
		$this->db->insert('tbl_pembayaran_project',$data_insert);
	} 

	public function list_pembayaran($tipe) {

		$sql = "SELECT a.*,b.nama_project FROM tbl_pembayaran_project a
			    JOIN tbl_project b ON (a.tpid = b.tpid)
			    ORDER BY a.tgl_bayar DESC";

		$rs  = $this->db->query($sql);

		return $rs;
	}

	public function hapus_pembayaran($tppid) {

		$this->db->query("DELETE FROM tbl_pembayaran_project WHERE tppid = ".$tppid);
	}
	
}