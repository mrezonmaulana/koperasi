<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {


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

		require ('Head.php');
		$test = new Head();
		$test->header();
		$this->load->view('view_proj/home_default');
	}


}