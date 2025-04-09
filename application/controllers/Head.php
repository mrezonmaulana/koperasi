<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Head extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper("url");
		
	}

	public function header()
	{
		$this->load->view('view_proj/Head');
	}

	public function leftmenu()
	{	
		$this->load->view('view_proj/aside');
	}
}
