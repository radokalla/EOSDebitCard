<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'controllers/main.php');
class Profile extends Main {

	public function __construct()
	{
		parent::__construct();
		//$this->output->clear_page_cache();
	}
	
	public function index()
	{
		$this->load->view('frontend/includes/header', $this->data);
		$this->load->view('frontend/profile/index', $this->data);
		$this->load->view('frontend/includes/footer', $this->data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */