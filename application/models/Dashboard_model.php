<?php
class Dashboard_model extends CI_Model {
	public function __construct()
	{
		$this->load->database();
	}

	public function list_masterdata($tbl){
		$addsql ="";

		if ( $tbl == 'emp') {
			$addsql = " WHERE teid > 1 ";
		}
		$data = $this->db->query("SELECT count(*) as jml FROM tbl_".$tbl." ".$addsql);
		return $data->row();
	}

	public function list_sales(){

		$sql = "SELECT SUM(x.amount) as total_amount,
				DATE(x.create_time) as tgl_transaksi,
				(CASE WHEN DATE(x.create_time) >= tgl_awal AND DATE(x.create_time) < tgl_tengah THEN 'last_week' ELSE 'curr_week' END) as status_week,
				DAYOFWEEK(DATE(x.create_time))-1 as status_hari
				FROM tbl_bill_payment x
			    JOIN (
				SELECT DATE_ADD(DATE_ADD(DATE(CURDATE()), INTERVAL(1-DAYOFWEEK(DATE(CURDATE()))) DAY) ,INTERVAL -7 DAY) as tgl_awal,DATE_ADD(DATE(CURDATE()), INTERVAL(1-DAYOFWEEK(DATE(CURDATE()))) DAY) as tgl_tengah, DATE_ADD(DATE_ADD(DATE(CURDATE()), INTERVAL(1-DAYOFWEEK(DATE(CURDATE()))) DAY) ,INTERVAL +7 DAY) as tgl_akhir
				) b ON (DATE(x.create_time) BETWEEN b.tgl_awal AND b.tgl_akhir)
				AND DATE(x.create_time) BETWEEN b.tgl_awal AND b.tgl_akhir
				GROUP BY DATE(x.create_time),(CASE WHEN DATE(x.create_time) >= tgl_awal AND DATE(x.create_time) < tgl_tengah THEN 'last_week' ELSE 'curr_week' END),DAYOFWEEK(DATE(x.create_time))-1
				ORDER BY DATE(x.create_time)";
		$rs  = $this->db->query($sql);

		return $rs;
	}

	public function list_qty_makanan(){
		$sql = "SELECT x.tbrid,concat(x.nama_barang,' - ',xx.nama_warna) as nama_barang,COALESCE(y.qty,0) as terjual FROM tbl_barang x
				JOIN tbl_kategori z ON (x.tkgid = z.tkgid)
			    LEFT JOIN (SELECT SUM(a.qty) as qty,a.tbrid FROM tbl_bill_makanan_d a 
								  JOIN tbl_bill_makanan b ON (a.tbmid = b.tbmid)
								   GROUP BY a.tbrid
			              ) y ON (x.tbrid = y.tbrid)
			    JOIN tbl_warna xx ON (xx.twid = x.twid)
			    WHERE 1=1 AND COALESCE(y.qty,0) > 0
			    ORDER BY COALESCE(y.qty,0) DESC,concat(x.nama_barang,' - ',xx.nama_warna) ASC LIMIT 10";
			$rs = $this->db->query($sql);

			return $rs;
	}

	public function list_sales_month () {

		$sql = "SELECT SUM(a.amount) as total_amount,
				DATE_FORMAT(DATE(a.create_time),'%m') as bulan,
				DATE_FORMAT(DATE(a.create_time),'%Y') as tahun,
				(CASE WHEN DATE_FORMAT(DATE(a.create_time),'%Y') = DATE_FORMAT(DATE(CURDATE()),'%Y') THEN 'curr_year' ELSE 'last_year' END) as status_year FROM tbl_bill_payment a
				LEFT JOIN (SELECT DATE_FORMAT(DATE_ADD(DATE(CURDATE()),INTERVAL -1 YEAR),'%Y') as bulan_lalu,DATE_FORMAT(DATE(CURDATE()),'%Y') as bulan_sekarang) b ON (DATE_FORMAT(DATE(a.create_time),'%Y')BETWEEN bulan_lalu AND bulan_sekarang )
				WHERE DATE_FORMAT(DATE(a.create_time),'%Y')BETWEEN bulan_lalu AND bulan_sekarang
				GROUP BY DATE_FORMAT(DATE(a.create_time),'%m'),DATE_FORMAT(DATE(a.create_time),'%Y'),(CASE WHEN DATE_FORMAT(DATE(a.create_time),'%Y') = DATE_FORMAT(DATE(CURDATE()),'%Y') THEN 'curr_year' ELSE 'last_year' END)";
		$rs  = $this->db->query($sql);

		return $rs;

	}

	public function list_sales_all(){

		$sql = "SELECT SUM(amount) as jml FROM tbl_bill_payment";
		$rs  = $this->db->query($sql)->row()->jml;

		return $rs;
	}

}