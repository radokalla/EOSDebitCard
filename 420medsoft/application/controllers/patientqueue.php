<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'controllers/admin.php');
class Patientqueue extends Admin {
	
	private $recordsperpage;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->data['session'] = $this->session->all_userdata();
	}
	
	public function queue($patientID)
	{
		if( !empty($patientID) && $this->session->userdata('ADMIN_ID')!='' && $this->session->userdata('ADMIN_NAME')!='' )
		{
			$this->load->model('patient_model');
			$data['patientDetails'] = $this->patient_model->getAdminPatientDetails($patientID);
			if(isset($data['patientDetails']['patientID']) && !empty($data['patientDetails']['patientID']))
			{
				$this->load->model('orders_model');		
				$this->orders_model->addPatientInQueue($patientID, $this->data['session']['ADMIN_ID'], $this->data['session']['LOGIN_TYPE']);
				$this->data['patientName'] = ucwords(strtolower($data['patientDetails']['firstName'] . " " . substr($data['patientDetails']['lastName'],0,1)));
				//$this->load->view('admin/includes/header', $this->data);
				$this->load->view('admin/orders/thankyou', $this->data);
				//$this->load->view('admin/includes/footer', $this->data);
				exit;
			}
		}
		redirect(base_url('index.php/patientqueue/error')); exit;
	}
	
	public function thankyou()
	{
		//$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/orders/thankyou', $this->data);
		//$this->load->view('admin/includes/footer', $this->data);
	}
	
	public function error()
	{
		//$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/orders/error', $this->data);
		//$this->load->view('admin/includes/footer', $this->data);
	}
  
}
