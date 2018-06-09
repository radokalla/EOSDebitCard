<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backend extends CI_Controller {
	
	public $data = array();
	private $recordsperpage;
	public function __construct()
	{
		parent::__construct();
		$this->recordsperpage = 50;
		$this->data['session'] = $this->session->all_userdata();
	}
	
	public function index()
	{
		$this->load->helper('form');
		
		$this->load->model('backend_model');		
		$this->load->library('form_validation');
		
		$validation_rules = array( 
								array('field'   => 'username', 'label' => 'Please fill username', 'rules' => 'required'),
								array('field'   => 'password', 'label'  => 'Please fill password', 'rules' => 'required')
							);

		$this->form_validation->set_rules($validation_rules); 
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		if ($this->form_validation->run())
		{
			if($admin = $this->backend_model->checkAuthentication($this->input->post()))
			{
				$session_array = array('BACKEND_ADMIN_ID' => $admin['ID']);
				$this->session->set_userdata($session_array); 
				redirect(base_url('index.php/backend/dashboard')); exit;
			}
			else
			{
				$this->session->set_flashdata('error', 'Wrong username or password.');
			}
		}
		
		$this->load->view('backend/includes/header');
		$this->load->view('backend/index');
		$this->load->view('backend/includes/footer');
	}
	
	public function isLoggedIn()
	{
		if($this->session->userdata('BACKEND_ADMIN_ID')=='')
		{
			redirect(base_url('index.php/backend/index')); exit;
		}
	}
	
	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url("index.php/backend")); exit;
	}

	
	protected function isAjaxRequest()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest';
	}
	
	public function dashboard()
	{
		$this->isLoggedIn();
		
		$this->load->model('backend_model');
		$this->data['subscribers'] = $this->backend_model->getSubscribersCount();
		
		$this->load->view('backend/includes/header', $this->data);
		$this->load->view('backend/dashboard', $this->data);
		$this->load->view('backend/includes/footer', $this->data);
	}
	
	public function subscriberdetails($recordsperpage = 50, $currentPage = 0)
	{
		$this->isLoggedIn();
		$this->recordsperpage = $recordsperpage;
		$condition_array = array();
		if($this->input->post())
		{
			$post = $this->input->post();
						
			if(isset($post['userName']) && !empty($post['userName']))
			$condition_array['userName'] = $post['userName'];	
				
			if(isset($post['recordPerPage']) && !empty($post['recordPerPage']))
				$this->recordsperpage = $post['recordPerPage'];
		}
		
		$condition_array['limit'] = $currentPage;
		$condition_array['recordsperpage'] = $this->recordsperpage;
		//echo "<pre>"; print_r($condition_array); exit;
		
		$this->load->model('backend_model');
		$this->data['subscriberCount'] = $this->backend_model->getSubscriberCount($condition_array);
		$this->data['subscriberDetails'] = $this->backend_model->getSubscriberDetails($condition_array);
		$this->data['recordsperpage'] = $this->recordsperpage;
		
		$this->load->library('pagination');
		$config['uri_segment'] = 4;
		$config['base_url'] = base_url('index.php/backend/subscriberdetails/'.$this->recordsperpage.'/');
		$config['total_rows'] = $this->data['subscriberCount'];
		$config['per_page'] = $this->recordsperpage; 		
		$this->pagination->initialize($config); 		
		//echo "<pre>";print_r($this->data['employeeDetails']);exit;
		$this->data['paginationLinks'] = $this->pagination->create_links();
		
		$this->load->view('backend/includes/header', $this->data);
		$this->load->view('backend/subscriberdetails', $this->data);
		$this->load->view('backend/includes/footer', $this->data);
	}
	
	public function subscriberdetails1($type = "", $recordsperpage = 50, $currentPage = 0)
	{
		$this->isLoggedIn();
		$this->recordsperpage = $recordsperpage;
		$condition_array = array();
		if($this->input->post())
		{
			$post = $this->input->post();
						
			if(isset($post['userName']) && !empty($post['userName']))
			$condition_array['userName'] = $post['userName'];	
				
			if(isset($post['recordPerPage']) && !empty($post['recordPerPage']))
				$this->recordsperpage = $post['recordPerPage'];
		}
		
		switch($type)
		{
			case 'new' 		: $condition_array['IsNewUser'] = 1; break;
			case 'active' 	: $condition_array['status'] = 1;	break;
			case 'inactive' : $condition_array['status'] = 0;	break;
		}
		
		$condition_array['limit'] = $currentPage;
		$condition_array['recordsperpage'] = $this->recordsperpage;
		//echo "<pre>"; print_r($condition_array); exit;
				
		$this->load->model('backend_model');
		$this->data['subscriberCount'] = $this->backend_model->getSubscriberCount($condition_array);
		$this->data['subscriberDetails'] = $this->backend_model->getSubscriberDetails($condition_array);
		$this->data['recordsperpage'] = $this->recordsperpage;
		
		if($type == "new")
			$this->backend_model->updateSubscriberToOldUser();	
		
		$this->load->library('pagination');
		$config['uri_segment'] = 5;
		$config['base_url'] = base_url('index.php/backend/subscriberdetails1/'.$type.'/'.$this->recordsperpage.'/');
		$config['total_rows'] = $this->data['subscriberCount'];
		$config['per_page'] = $this->recordsperpage; 		
		$this->pagination->initialize($config); 		
		//echo "<pre>";print_r($this->data['employeeDetails']);exit;
		$this->data['paginationLinks'] = $this->pagination->create_links();
		
		$this->load->view('backend/includes/header', $this->data);
		$this->load->view('backend/subscriberdetails', $this->data);
		$this->load->view('backend/includes/footer', $this->data);
	}
	
	public function delsubscriber($subscriberID = '')
	{
		$this->load->model('backend_model');
		if(!empty($subscriberID))
			$this->backend_model->updateSubscriberStatus( $subscriberID, 2);
		redirect('backend/subscriberdetails'); 
		exit;
	}
	
	function getsubscriberDetails()
	{
		if($this->isAjaxRequest())
		{
			$this->load->helper('form');
			if($this->input->post('SubscriberID'))
			{
				$SubscriberID = $this->input->post('SubscriberID');	
				$this->load->model('backend_model');
				$this->data['subscriberDetails'] = $this->backend_model->getsubscriberBySubscriberID($SubscriberID);
				//echo "<pre>"; print_r($this->data['subscriberDetails']); echo "</pre>";
				$this->load->view('backend/details', $this->data);
			}
			else
			{
				echo "Sorry ";
			}
		}
		else
		{
			echo "Pichhoda: No details";
		}
	}
	
	function updateSubscriberStatus()
	{
		if($this->isAjaxRequest())
		{
			$this->load->helper('form');
			if($this->input->post())
			{
				$post = $this->input->post();
				if( isset($post['SubscriberID']) && !empty($post['SubscriberID']) )
				{
					$this->load->model('backend_model');		
					$this->backend_model->updateSubscriberStatus($post['SubscriberID'], $post['status']);
					echo true;
				}
				else
					echo false;
			}
			else
			{
				echo false;
			}
		}
		else
		{
			echo false;
		}
	}
	
	public function packagedetails()
	{
		$this->isLoggedIn();
		$this->load->model('main_model');
		$this->load->helper('form');
		if($this->input->post())
		{
			$post = $this->input->post();
			$this->main_model->updatePackages($post);
		}
		
		$this->data['packages'] = $this->main_model->getPackages();
		
		$this->load->view('backend/includes/header', $this->data);
		$this->load->view('backend/packagedetails', $this->data);
		$this->load->view('backend/includes/footer', $this->data);
	}
	
	public function delsubscriberupdate($subscriberID = '')
	{
	
		$this->isLoggedIn();
		$this->load->model('backend_model');
		$this->load->helper('form');
		if($this->input->post())
		{ 
		$this->backend_model->insertsubscrib();
		}
		$this->data['subscpt'] = $this->backend_model->getsubscriberBySubscriberID($subscriberID);
		$this->data['account'] = $this->backend_model->getAccounts($subscriberID);
	
		$this->load->view('backend/includes/header', $this->data);
		$this->load->view('backend/logodetails', $this->data);
		$this->load->view('backend/includes/footer', $this->data);
	}
	
	/*public function indexText()
	{
		$this->isLoggedIn();
		$this->load->helper('form');
		$this->load->model('backend_model');	
		
		if($this->input->post())
		{
			$this->backend_model->updateText($this->input->post());
			redirect('backend/indexText'); 
			exit;
		}
		$this->data['text'] = $this->backend_model->getiIndexText();
		$this->load->view('backend/includes/header', $this->data);
		$this->load->view('backend/addindexText', $this->data);
		$this->load->view('backend/includes/footer', $this->data);
	}
	public function chlogo()
	{
		$this->isLoggedIn();
		$this->load->model('backend_model');
		$this->load->helper('form');
		$this->data['logoss'] = $this->backend_model->getiIndexText();
		if($this->input->post())
		{
				
			$this->backend_model->upatelogo();
			
			
		}

	 $this->load->view('backend/includes/header', $this->data);
		$this->load->view('backend/addlogo', $this->data);
		$this->load->view('backend/includes/footer', $this->data);
	 
	}
	public function footerlogo()
	{
		
		$this->isLoggedIn();
		$this->load->model('backend_model');
		$this->load->helper('form');
		$this->data['logoss'] = $this->backend_model->getiIndexText();
		if($this->input->post())
		{
				
			$this->backend_model->upatefooetrlogo();
			
			
		}
		 $this->load->view('backend/includes/header', $this->data);
		$this->load->view('backend/addfooterlogo', $this->data);
		$this->load->view('backend/includes/footer', $this->data);
		
	}*/
	public function salesreorts()
	{
		$this->isLoggedIn();
		$this->load->model('backend_model');
		$this->load->helper('form');
		$this->data['reports'] = $this->backend_model->salesrp();
		$this->load->view('backend/includes/header', $this->data);
		$this->load->view('backend/salesreports', $this->data);
		$this->load->view('backend/includes/footer', $this->data);
	}
	
	public function salesreport()
	{
	    $this->isLoggedIn();
		$this->load->model('backend_model');
		$this->load->helper('form');
		$this->data['reports'] = $this->backend_model->salesreport();
		$this->load->view('backend/includes/header', $this->data);
		$this->load->view('backend/sales-report', $this->data);
		$this->load->view('backend/includes/footer', $this->data);
	}
	
	
	
	public function checkCreditCardDetails()
	{
		if($this->isAjaxRequest())
		{
			$this->load->helper('form');
			if($this->input->post())
			{
				$post = $this->input->post();	
				$this->load->library('logs');				
				$this->logs->write_log("INFO", $post['SubscriberID'] . " => Checking Creditcard details");				
				require_once FCPATH . 'Quickbookapi/QuickBooks.php';
				$dsn = null;				
				//Testing
				//$application_login = 'bayfrontstaging.bayfrontorganics.com';
				//$connection_ticket = 'SDK-TGT-102-qDnMovo4Rckuk1l695Oxwg';
				
				//Live
				$application_login = 'bayfrontorganics.bayfrontorganics.com';
				$connection_ticket = 'SDK-TGT-57-zRBpWqI2P_4vC$bnv3Fp4Q';				

				$path_to_private_key_and_certificate = null;		

				$MS = new QuickBooks_MerchantService(
					$dsn, 
					$path_to_private_key_and_certificate, 
					$application_login,
					$connection_ticket);
				//Testing
				//$MS->useTestEnvironment(true);
				
				//Live
				$MS->useTestEnvironment(false);

				$MS->useDebugMode(false);
				$this->logs->write_log("INFO", $post['SubscriberID'] . " => Merchant Service connection successful.");
				$name = $post['nameOnCard'];
				$number = $post['cardNumber'];
				$expyear = $post['expiry_year'];
				$expmonth = $post['expiry_month'];
				$address = $post['street_address'];
				$postalcode = $post['zipcode'];
				$cvv = $post['CVVCode'];

				
				// Create the CreditCard object
				$Card = new QuickBooks_MerchantService_CreditCard($name, $number, $expyear, $expmonth, $address, $postalcode, $cvv);
		

				// We're going to authorize $295.00
				$total_price = 295.00;
											

				//if ($Transaction = $MS->authorize($Card, $total_price))
				//{
					
					if ($Transaction = $MS->charge($Card, $total_price))
					{						
						$TransactionDetails = $Transaction->toArray();
						
						$this->load->model('backend_model');
						$this->backend_model->savePayments($post['SubscriberID'], json_encode($TransactionDetails));						

						if($TransactionDetails['PaymentStatus'] == 'Completed')
						{

							

						}
						$this->logs->write_log("INFO", $post['SubscriberID'] . " => Successfull charge for Creditcard : ".$total_price);
						
						$return_arr = array('success' => true, 'message' => "Successfull charge for Creditcard : ".$total_price);
					}
					else
					{
						
						$this->logs->write_log("ERROR", $post['SubscriberID'] . " => Charge  : ".$MS->errorMessage());
						$return_arr = array('success' => false, 'message' => "Charge  : ".$MS->errorMessage());
					}

				// }
				// else
				// {
					// $this->logs->write_log("ERROR", "Credit card authorization : ".$MS->errorMessage());
					// $return_arr = array('success' => false, 'message' => $MS->errorMessage());
				// }
			}
			else
			{
				$this->logs->write_log("ERROR", $post['SubscriberID'] . " => Not Authorized");
				$return_arr = array('success' => false, 'message' => 'Not Authorized');
			}
				
		}
		else
		{
			$this->logs->write_log("ERROR", $post['SubscriberID'] . " => Check the url");
			$return_arr = array('success' => false, 'message' => 'Check the url');	
		}	

		echo json_encode($return_arr);

	}
	
	
}
