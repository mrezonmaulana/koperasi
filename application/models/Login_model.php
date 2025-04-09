<?php
class Login_model extends CI_Model {
	public function __construct()
	{
		$this->load->database();
	}
 
	public function login() {
 
		$username = $this->input->POST('username', TRUE);
		$password = md5($this->input->POST('password', TRUE));
		$data = $this->db->query("SELECT a.login_name,a.login_pass,a.tuid,a.is_admin,b.nama_karyawan,a.is_user_approve,a.teid from tbl_user a 
									join tbl_emp b ON (a.teid = b.teid)
									where a.login_name='$username' and a.login_pass='$password' AND COALESCE(a.is_aktif,0) = 1 LIMIT 1 ");
		return $data->row();
	}

	public function setAdmin($tuid,$is_admin) {

		if ($is_admin == 1) {
			$sql = "UPDATE tbl_user SET is_admin=0,modify_id=".$_SESSION['teid'].",modify_time='".date('Y-m-d H:i:s')."' WHERE tuid = ".$tuid;
		}else{
			$sql = "UPDATE tbl_user SET is_admin=1,modify_id=".$_SESSION['teid'].",modify_time='".date('Y-m-d H:i:s')."' WHERE tuid = ".$tuid;
		}

		$this->db->query($sql);
	}

	public function setUserApprove($tuid,$is_user_approve) {

		if (intval($is_user_approve) == 1) {
			$sql = "UPDATE tbl_user SET is_user_approve=0,modify_id=".$_SESSION['teid'].",modify_time='".date('Y-m-d H:i:s')."' WHERE tuid = ".$tuid;
		}else{
			$sql = "UPDATE tbl_user SET is_user_approve=1,modify_id=".$_SESSION['teid'].",modify_time='".date('Y-m-d H:i:s')."' WHERE tuid = ".$tuid;
		}

		$this->db->query($sql);
	}

	public function list_user_row($tuid) {

		$data = $this->db->query("SELECT a.*
								  FROM tbl_user a 
								  WHERE a.tuid = ".$tuid);
		return $data->row();
	}

	public function list_user($tuid) {

		$data = $this->db->query("SELECT a.*,b.nama_karyawan FROM tbl_user a 
							      JOIN tbl_emp b ON (a.teid = b.teid)
								  WHERE a.tuid != ".$tuid." AND a.tuid != 1");
		return $data;
	}

	public function update_user($tuid,$newpass) {
		$this->db->query("UPDATE tbl_user SET login_pass = '".$newpass."',modify_id=".$_SESSION['teid'].",modify_time='".date('Y-m-d H:i:s')."' WHERE tuid = ".$tuid);
	}

	public function update_akses($tuid,$tmndid) {

		$data_menu = serialize($tmndid);

		$this->db->query("UPDATE tbl_user SET list_menu = '".$data_menu."',modify_id=".$_SESSION['teid'].",modify_time='".date('Y-m-d H:i:s')."' WHERE tuid = ".$tuid);
		
	}

	public function  insert_user($login_name,$login_pass,$is_admin,$tmndid,$teid) {

		$cek_user_exist = "SELECT count(*) as jml FROM tbl_user WHERE lower(login_name) = lower('".$login_name."')";
		$get_exist      = $this->db->query($cek_user_exist);
		$get_exist_jml  = $get_exist->row()->jml;

		if ( intval($get_exist_jml) > 0 ) {
			return 'exist';
		} else {

			$data_menu = '';

			if ( count($tmndid) > 0 AND is_array($tmndid) == true ) {
				$data_menu = serialize($tmndid);
			}
			$data = array(
					'login_name' => $login_name,
					'login_pass' => $login_pass,
					'is_admin'   => $is_admin,
					'list_menu'  => $data_menu,
					'teid'		 => $teid,
					'create_id'  => $_SESSION['teid'],
				);

			$this->db->insert('tbl_user',$data);

			return 'berhasil';

		}

	}

	public function hapus_user($tuid,$is_aktif) {
		$this->db->query("UPDATE tbl_user SET is_aktif = '".$is_aktif."' WHERE tuid = ".$tuid);
	}

	public function updateLogin($tuid,$tipe) {
		$this->db->query("UPDATE tbl_user SET is_login = '".$tipe."' WHERE tuid = ".$tuid);
	}

	public function list_allmenu(){

		$rs = $this->db->query("SELECT b.id_li as id_header,a.id_li as id_anak,(CASE WHEN a.tmnid IS NOT NULL AND a.is_sub=1 THEN a.nama_menu ELSE b.nama_menu END) as nama_menu,b.tmnid,b.nama_menu as header_menu,b.url,a.tmndid  FROM tbl_menu b
										    LEFT JOIN tbl_menu_d a ON (a.tmnid = b.tmnid)
										    WHERE 1=1 ORDER BY b.tmnid,a.tmndid");
		return $rs;
	}

	public function cek_po_approve($tuid,$is_user_approve){

		$val_data = 0;

		if ( $is_user_approve  > 0 ) { 

			$get_data = "SELECT count(*) as jml FROM tbl_transaksi_barang WHERE tipe_trans = 2 AND approve_status = 3";

			$rd       = $this->db->query($get_data);
			$jml      = $rd->row()->jml;

			$val_data = intval($jml);
		}

		return json_encode(array("jml_data"=>$val_data));

	}

	public function cek_akses($tuid,$is_admin) {

		$data_akses = json_encode(array());

		if ( $is_admin != '1' ) {
				$get_list = $this->db->query("SELECT list_menu FROM tbl_user WHERE tuid = ".$tuid."");
				$data_menu = $get_list->row()->list_menu;
				$data_menu = unserialize($data_menu);
				$data_menu = implode(',',$data_menu);
				if ( $data_menu != '' ) {
					$rs = $this->db->query("SELECT b.id_li as id_header,a.id_li as id_anak FROM tbl_menu_d a
										    JOIN tbl_menu b ON (a.tmnid = b.tmnid)
										    WHERE a.tmndid IN (".$data_menu.")");
					$data_akses = json_encode($rs->result_array());
				}
		}else{

			$rs = $this->db->query("SELECT b.id_li as id_header,a.id_li as id_anak FROM tbl_menu b
										    LEFT JOIN tbl_menu_d a ON (a.tmnid = b.tmnid)
										    WHERE 1=1");
					$data_akses = json_encode($rs->result_array());
		}


		return $data_akses;
	}

	public function list_karyawan(){
		$sql = "SELECT a.*,b.nama_role FROM tbl_emp a
 				JOIN tbl_role b ON (a.trid = b.trid)
 				LEFT JOIN tbl_user c ON (a.teid = c.teid)
 				WHERE c.teid IS NULL
 				ORDER BY a.teid";
		$data = $this->db->query($sql);
		return $data;
	}

	public function getDataMenu(){
		$sql = "SELECT a.tmnid as tmnidna, a.nama_menu,a.url,a.id_li,a.id_li_a,a.icon ,b.nama_menu as nama_menu_detail
				,b.url as url_menu_detail
				,b.id_li as id_li_detail
				,b.icon as icon_detail
				,(SELECT COUNT(CASE WHEN x.url != '' THEN 1 END) FROM tbl_menu_d x WHERE x.tmnid = a.tmnid) as jml_detail
				FROM tbl_menu a
				LEFT JOIN tbl_menu_d b ON (a.tmnid = b.tmnid)
				WHERE a.is_aktif = '1' AND b.is_aktif='1'
				GROUP BY a.tmnid,a.nama_menu,a.url,a.id_li,a.id_li_a,a.icon,b.nama_menu,b.url,b.id_li,b.icon,b.tmndid
				ORDER BY a.urutan,b.tmndid";
		$rs = $this->db->query($sql);
		$data_menu = json_encode($rs->result_array());

		return $data_menu;
	}
 
}