<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		$this->load->helper("url");
		$this->load->model('dashboard_model');
	}

	public function index(){

		if (empty($_SESSION['tuid'])) {

			return redirect('');
		}

		$day=date('Y-m-d');

// add 7 days to the date above
		$NewDate = date('Y-m-d', strtotime($day . " -14 days"));
		$first_of_weeks_day = date('l',strtotime($NewDate));


		$data_sales = $this->dashboard_model->list_sales()->result_array();
		$data_sales_month = $this->dashboard_model->list_sales_month()->result_array();
		$data_sales_all = $this->dashboard_model->list_sales_all();
		$nama_hari = array('Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu');

		$list_qty_makanan = $this->dashboard_model->list_qty_makanan()->result_array();

		$data_makanan['jml_terjual'] = $header_makanan['nama_makanan'] =  $all_makanan['all_makanan'] = array();

		foreach ($list_qty_makanan as $aa => $bb) {
			$data_makanan['jml_terjual'][$aa] = $bb['terjual'];
			$header_makanan['nama_makanan'][$aa] = "'".$bb['nama_barang']."'";
			$all_makanan['all_makanan'][$aa] = array('nama_makanan' => $bb['nama_barang'],'total'=> $bb['terjual']);

		}

		$makanan_juara = "";

		if ( count($all_makanan['all_makanan']) > 0) {
			$max_terjual = max(array_column($all_makanan['all_makanan'], 'total'));
			if ( $max_terjual > 0 ) {
				foreach ($all_makanan['all_makanan'] as $key => $value) {
					if ( $value['total'] == $max_terjual ) {
						$makanan_juara .= $value['nama_makanan'];
					}
				}
			}
		}else{
			$max_terjual = 0;
		}
		



		$new_makanan = implode(",",$header_makanan['nama_makanan']);
		$terjual_new_makanan = implode(",",$data_makanan['jml_terjual']);



		$nama_bulan = array(
						'01' => 'Jan',
						'02' => 'Feb',
						'03' => 'Mar',
						'04' => 'Apr',
						'05' => 'Mei',
						'06' => 'Jun',
						'07' => 'Jul',
						'08' => 'Agt',
						'09' => 'Sept',
						'10' => 'Okt',
						'11' => 'Nov',
						'12' => 'Des',
						);

		$array_week = array('last_week','curr_week');
		$array_year = array('last_year','curr_year');

		$array_sales = $selisih_sales = $array_sales_month  = $selisih_sales_month = array();
		$data_hari=array();

		for($week=0;$week<=1;$week++){
			for ($day=0; $day <7 ; $day++) { 
				$array_sales[$array_week[$week]."_".$day] = 0;
				$data_hari['hari_'.$day] = $nama_hari[$day];
			}
			$selisih_sales[$array_week[$week]] = 0;
		}

		for($years=0;$years<=1;$years++){
			for($months=1;$months<=12;$months++){
				$new_months = $months;
				if ( $months < 10 ) {
					$new_months = "0".$months;
				}
				$array_sales_month[$array_year[$years]."_".$new_months] = 0;
			}
		}

		$date_cur = date('Y-m-d');
		$month_curr = date('m',strtotime($date_cur));
		$month_before_curr = date('m',strtotime($date_cur. " -  1 Month"));
		
		$selisih_sales_month['curr_year_'.$month_curr] = 0;
		$selisih_sales_month['curr_year_'.$month_before_curr] = 0;

		
		foreach ($data_sales_month as $key => $value) {
			$array_sales_month[$value['status_year']."_".$value['bulan']] = $value['total_amount'];
			if ( $value['tahun'] == date('Y')){
				if ( $value['bulan'] == $month_curr ) {
					$selisih_sales_month[$value['status_year']."_".$value['bulan']] = $value['total_amount'];
				}elseif($value['bulan'] == $month_before_curr) {
					$selisih_sales_month[$value['status_year']."_".$value['bulan']] = $value['total_amount'];
				}		
			}
		}



		foreach ($data_sales as $key => $value) {
			$array_sales[$value['status_week'].'_'.$value['status_hari']] = $value['total_amount'];
			$selisih_sales[$value['status_week']] += $value['total_amount'];

			$new_hari = $value['status_hari'];
			$data_hari['hari_'.$new_hari] = $nama_hari[$new_hari];

		}



		$selisih_nom_sales = $selisih_sales['curr_week'] - $selisih_sales['last_week'];
		$selisih_nom_sales_month = $selisih_sales_month['curr_year_'.$month_curr] - $selisih_sales_month['curr_year_'.$month_before_curr];

		if ( $selisih_sales['last_week'] <> 0 ) {
			$selisih_nom_sales_persen = ($selisih_nom_sales/$selisih_sales['last_week'])*100;
		}else{
			$selisih_nom_sales_persen = 0;
		}

		if ( $selisih_sales_month['curr_year_'.$month_before_curr] <> 0 ) {

			$selisih_nom_sales_persen_month = ($selisih_nom_sales_month/$selisih_sales_month['curr_year_'.$month_before_curr])*100;
		}else{
			$selisih_nom_sales_persen_month = 0;
		}

		

		if ( $selisih_nom_sales_persen < 0 ) {
			 $data['list']['text_persen'] = 'text-danger';
			 $data['list']['text_persen_panah'] = 'fa-arrow-down';
		}else{
			 if ( $selisih_nom_sales_persen == 0 ) {

			 	$data['list']['text_persen'] = 'text-secondary';
				 $data['list']['text_persen_panah'] = 'fa-dot-circle';

			 } else {

				 $data['list']['text_persen'] = 'text-success';
				 $data['list']['text_persen_panah'] = 'fa-arrow-up';

			 }
		}

		if ( $selisih_nom_sales_persen_month < 0 ) {
			 $data['list']['text_persen_month'] = 'text-danger';
			 $data['list']['text_persen_month_panah'] = 'fa-arrow-down';
		}else{
			 if ( $selisih_nom_sales_persen_month ==  0 ) {
			 $data['list']['text_persen_month'] = 'text-secondary';
			 $data['list']['text_persen_month_panah'] = 'fa-dot-circle';
			 }else{
			 $data['list']['text_persen_month'] = 'text-success';
			 $data['list']['text_persen_month_panah'] = 'fa-arrow-up';

			 }
		}



		$data['list']['project'] = $this->dashboard_model->list_masterdata('bill_makanan');
		$data['list']['karyawan'] = $this->dashboard_model->list_masterdata('emp');
		$data['list']['bidang'] = $this->dashboard_model->list_masterdata('kategori');
		$data['list']['barang'] = $this->dashboard_model->list_masterdata('barang');
		$data['list']['kendaraan'] = $this->dashboard_model->list_masterdata('edc');
		$data['list']['supplier'] = $this->dashboard_model->list_masterdata('supplier');

		$data['list']['project_ajuan'] = $this->dashboard_model->list_masterdata('project_ajuan');
		$data['list']['ajuan_kasbon'] = $this->dashboard_model->list_masterdata('ajuan_kasbon');
		$data['list']['project_ajuan_adm'] = $this->dashboard_model->list_masterdata('project_ajuan_adm');
		$data['list']['selisih_persen'] = round($selisih_nom_sales_persen,2);
		$data['list']['selisih_month_persen'] = round($selisih_nom_sales_persen_month,2);
		$data['list']['all_sales'] = number_format($data_sales_all);
		$data['list']['new_makanan'] = $new_makanan;
		$data['list']['terjual_new_makanan'] = $terjual_new_makanan;
		$data['list']['makanan_juara'] = $makanan_juara;

		for($week=0;$week<=1;$week++){
			for ($day=0; $day <7 ; $day++) { 
			 $data['list'][$array_week[$week]."_".$day] = $array_sales[$array_week[$week]."_".$day] ;
			 $data['list']['hari_'.$day] = $data_hari['hari_'.$day];
			}
		}

		for($years=0;$years<=1;$years++){
			for($months=1;$months<=12;$months++){
				$new_months = $months;
				if ( $months < 10 ) {
					$new_months = "0".$months;
				}
				$data['list'][$array_year[$years]."_".$new_months] = $array_sales_month[$array_year[$years]."_".$new_months];
			}
		}
		




		require ('Head.php');
		$test = new Head();
		$test->header();
		$this->load->view('view_proj/index',$data);
	}

	public function home_default(){


		if (empty($_SESSION['tuid'])) {

			return redirect('');
		}


		$data['list']['project'] = $this->dashboard_model->list_masterdata('project');
		$data['list']['karyawan'] = $this->dashboard_model->list_masterdata('emp');
		$data['list']['bidang'] = $this->dashboard_model->list_masterdata('bidang');
		$data['list']['barang'] = $this->dashboard_model->list_masterdata('barang');
		$data['list']['kendaraan'] = $this->dashboard_model->list_masterdata('kendaraan');
		$data['list']['supplier'] = $this->dashboard_model->list_masterdata('supplier');

		$data['list']['project_ajuan'] = $this->dashboard_model->list_masterdata('project_ajuan');
		$data['list']['ajuan_kasbon'] = $this->dashboard_model->list_masterdata('ajuan_kasbon');
		$data['list']['project_ajuan_adm'] = $this->dashboard_model->list_masterdata('project_ajuan_adm');


		require ('Head.php');
		$test = new Head();
		$test->header();
		$this->load->view('view_proj/home_default',$data);
	}


}