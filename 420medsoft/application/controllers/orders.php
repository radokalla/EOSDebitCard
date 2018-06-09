<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'controllers/main.php');
class Orders extends Main {

	public function __construct()
	{
		parent::__construct();
		//$this->output->clear_page_cache();
	}
	
	public function index()
	{
		$this->load->model('orders_model');
		$this->data['ordersDetails'] = $this->orders_model->getOrders($this->data['session']['PATIENT_ID']);
		$this->data['ordersStatus'] = $this->orders_model->getStatus();
		
		$this->load->view('frontend/includes/header', $this->data);
		$this->load->view('frontend/orders/index', $this->data);
		$this->load->view('frontend/includes/footer', $this->data);
	}
	
	
	public function details($orderID = '')
	{
		if(empty($orderID))
		{
			redirect(base_url('index.php/orders')); exit;
		}
		
		$this->load->model('orders_model');
		$this->data['ordersDetails'] = $this->orders_model->getOrders($this->data['session']['PATIENT_ID'], $orderID);
		if(!$this->data['ordersDetails'])
		{
			redirect(base_url('index.php/orders')); exit;
		}
		else
			$this->data['ordersDetails'] = $this->data['ordersDetails'][$orderID];
		$this->data['ordersStatus'] = $this->orders_model->getStatus();
		
		$this->load->view('frontend/includes/header', $this->data);
		$this->load->view('frontend/orders/details', $this->data);
		$this->load->view('frontend/includes/footer', $this->data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */