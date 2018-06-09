<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	 public function __construct()
	 {
		parent::__construct();
		//$this->output->clear_page_cache();	
		$method = $this->router->fetch_method();
	//if($method != 'registers'){
		$this->data['currency'] = array('symbol'=>'$', 'text' => 'USD');
		$this->data['session'] = $this->session->all_userdata();
		$this->session->unset_userdata('LOGIN_ERROR');		
		$this->load->model('categories_model');	
		$this->load->model('patient_model');			
		$this->data['mainCategories'] = $this->categories_model->getAllCategories(array('isActive' => 1,'isVisible' => 1));
		$this->load->model('main_model');	
		$this->load->model('admin_model');	
		$settings = $this->admin_model->getIp();
		$this->data['admin_settings'] = $settings[0];
		$this->data['tax_percentage'] = $settings[0]['taxPercentage'];
		$this->data['delivery_charge'] = $settings[0]['deliveryCharges'];	
		 $this->data['logos'] =$this->patient_model->getlogos();		
	
		if($this->session->userdata('PATIENT_ID'))
		{
			$this->data['patientCreditCardDetails'] = $this->patient_model->getPatientCreditCardDetails();
		}
		if(empty($this->data['session']['paymentType']))
		{
			$this->data['session']['paymentType'] = "cash";
		}
		$this->qb_offline=$settings[0]['qb_offline'];
		$this->data['qb_offline']=$settings[0]['qb_offline'];
	 }
	
	protected function isAjaxRequest()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest';
	}
	
	protected function checkPatientAuthentication()
	{
		if($this->session->userdata('PATIENT_ID')=='' || $this->session->userdata('PATIENT_NAME')=='' )
		{
			redirect(base_url('index.php/main/index'));
		}
	}
	
	
	public function subscribePayment($domain)
	{
		if($domain =='' && empty($domain))
		{
			redirect(base_url('index.php/main/index'));
		}
		else
		{
		    $this->data['subscriberDetails'] = $this->main_model->getSubscriberDetailsByDomain($domain);
			$this->data['packages'] = $this->main_model->getPackages();
			$this->data['session']['SUBSCRIPTION_USER_ID']= $this->data['subscriberDetails']['SubscriberID'];
			//echo "<pre>"; print_r($this->data['subscriberDetails'] ); exit;
		if($this->input->post())
		{
			$this->load->library('quickbooks');
			$this->load->library('logs');
						
			$this->logs->write_log("INFO", "Checking Creditcard details");			
			require_once FCPATH . 'Quickbookapi/QuickBooks.php';
			$post = $this->input->post();
			//echo "<pre>"; print_r($post); exit;
			$dsn = null;
				
			//Testing
			//$application_login = 'bayfrontstaging.bayfrontorganics.com';
			//$connection_ticket = 'SDK-TGT-102-qDnMovo4Rckuk1l695Oxwg';
			
			//Live
		//$application_login = 'bayfrontorganics.bayfrontorganics.com';
		//	$connection_ticket = 'SDK-TGT-57-zRBpWqI2P_4vC$bnv3Fp4Q';
			
			$application_login = 'cali-oil-qbms-production.www.cali-oil.com';
			$connection_ticket = 'SDK-TGT-70-YF8vpuuyzMAaDihya4SYmg';
			$path_to_private_key_and_certificate = null;
			
			$MS = new QuickBooks_MerchantService($dsn, $path_to_private_key_and_certificate, $application_login, $connection_ticket);
			//Testing
			$MS->useTestEnvironment(false);
			
			//Live
			//$MS->useTestEnvironment(false);
			
			$MS->useDebugMode(false);
			
			$this->logs->write_log("INFO", "Merchant Service connection successful.");
			
			$name = $post['nameOnCard'];			
			$number = $post['cardNumber'];			
			$expyear = $post['expiry_year'];			
			$expmonth = $post['expiry_month'];			
			$address = '';			
			$postalcode = '';			
			$cvv = $post['CVVCode'];

			$total_price =  $this->data['subscriberDetails']['RecurringCost'];
			
			// Create the CreditCard object
			$Card = new QuickBooks_MerchantService_CreditCard($name, $number, $expyear, $expmonth, $address, $postalcode, $cvv);
			/*if ($Transaction = $MS->authorize($Card, $total_price))
			{
				$this->logs->write_log("INFO", "Credit card authorization successful.");
				*/
				if ($Transaction = $MS->charge($Card, $total_price))
				{
					$this->logs->write_log("INFO", "Successfull charge for Creditcard : ".$total_price);	
							
					$TransactionDetails = $Transaction->toArray();
					if($TransactionDetails['PaymentStatus'] == 'Completed')
					{
						$SubscriberID = $this->data['session']['SUBSCRIPTION_USER_ID'];
						$PackageID = $this->data['packages']['PackageID'];
						$Amount = $total_price;
						$CreditCardTransID = $TransactionDetails['CreditCardTransID'];
						
						$this->main_model->subscriptionSuccessful($SubscriberID, $PackageID, $Amount, $CreditCardTransID);
						$date=$this->data['subscriberDetails']['expiryDate'];
						$expiryDate=date("Y-m-d",$this->calculate_next_month(strtotime($date)));
						$this->main_model->updateSubscriptionExpiryDate($SubscriberID,$expiryDate);
						$session_array = array('SUBSCRIPTION_USER_ID', 'SUBSCRIPTION_USER_NAME');
						$this->session->unset_userdata($session_array);
						$this->data['errorMessage'] = $MS->errorMessage();
						redirect('main/renewthanks/'.$domain); exit;
					}
				}
				else
				{
					$this->logs->write_log("Error", "Charge  : ".$MS->errorMessage());
					//echo "<br>"."Charge  : ".$MS->errorMessage();
					$this->data['errorMessage'] = $MS->errorMessage();
				}
				
			/*}
			else
			{
				$this->logs->write_log("Error", "Authorization error : ".$MS->errorMessage());
				echo "<br>"."Authorization error  : ".$MS->errorMessage();
				$this->data['errorMessage'] = $MS->errorMessage();
			}*/
		}
		
		// $this->load->view('frontend/includes/header', $this->data);	
		$this->load->view('frontend/login_register/ccpayment', $this->data);
		// $this->load->view('frontend/includes/footer', $this->data);	
		}
	}
	
	
	public function subscribeQbPayment($domain)
	{
		if($domain =='' && empty($domain))
		{
			redirect(base_url('index.php/main/index'));
		}
		else
		{
		    $this->data['subscriberDetails'] = $this->main_model->getSubscriberDetailsByDomain($domain);
			$this->data['packages'] = $this->main_model->getPackages();
			$this->data['session']['SUBSCRIPTION_USER_ID']= $this->data['subscriberDetails']['SubscriberID'];
			//echo "<pre>"; print_r($this->data['subscriberDetails'] ); exit;
		if($this->input->post())
		{
			$this->load->library('quickbooks');
			$this->load->library('logs');
						
			$this->logs->write_log("INFO", "Checking Creditcard details");			
			require_once FCPATH . 'Quickbookapi/QuickBooks.php';
			$post = $this->input->post();
			//echo "<pre>"; print_r($post); exit;
			$dsn = null;
				
			//Testing
			//$application_login = 'bayfrontstaging.bayfrontorganics.com';
			//$connection_ticket = 'SDK-TGT-102-qDnMovo4Rckuk1l695Oxwg';
			
			//Live
		//$application_login = 'bayfrontorganics.bayfrontorganics.com';
		//	$connection_ticket = 'SDK-TGT-57-zRBpWqI2P_4vC$bnv3Fp4Q';
			
			$application_login = 'cali-oil-qbms-production.www.cali-oil.com';
			$connection_ticket = 'SDK-TGT-70-YF8vpuuyzMAaDihya4SYmg';
			$path_to_private_key_and_certificate = null;
			
			$MS = new QuickBooks_MerchantService($dsn, $path_to_private_key_and_certificate, $application_login, $connection_ticket);
			//Testing
			$MS->useTestEnvironment(false);
			
			//Live
			//$MS->useTestEnvironment(false);
			
			$MS->useDebugMode(false);
			
			$this->logs->write_log("INFO", "Merchant Service connection successful.");
			
			$name = $post['nameOnCard'];			
			$number = $post['cardNumber'];			
			$expyear = $post['expiry_year'];			
			$expmonth = $post['expiry_month'];			
			$address = '';			
			$postalcode = '';			
			$cvv = $post['CVVCode'];

			$total_price =  $this->data['subscriberDetails']['RecurringCost'];
			
			// Create the CreditCard object
			$Card = new QuickBooks_MerchantService_CreditCard($name, $number, $expyear, $expmonth, $address, $postalcode, $cvv);
			/*if ($Transaction = $MS->authorize($Card, $total_price))
			{
				$this->logs->write_log("INFO", "Credit card authorization successful.");
				*/
				if ($Transaction = $MS->charge($Card, $total_price))

				{
					$this->logs->write_log("INFO", "Successfull charge for Creditcard : ".$total_price);	
							
					$TransactionDetails = $Transaction->toArray();
					if($TransactionDetails['PaymentStatus'] == 'Completed')
					{
						$SubscriberID = $this->data['session']['SUBSCRIPTION_USER_ID'];
						$PackageID = $this->data['packages']['PackageID'];
						$Amount = $total_price;
						$CreditCardTransID = $TransactionDetails['CreditCardTransID'];
						
						$this->main_model->subscriptionSuccessful($SubscriberID, $PackageID, $Amount, $CreditCardTransID);
						//$date=$this->data['subscriberDetails']['expiryDate'];
						//$expiryDate=date("Y-m-d",$this->calculate_next_month(strtotime($date)));
						//$this->main_model->updateSubscriptionExpiryDate($SubscriberID,$expiryDate);
						$this->main_model->updateSubscriptionRecurringCost($domain,"399.00");
						$session_array = array('SUBSCRIPTION_USER_ID', 'SUBSCRIPTION_USER_NAME');
						$this->session->unset_userdata($session_array);
						$this->data['errorMessage'] = $MS->errorMessage();
						redirect('main/renewthanks/'.$domain); exit;
					}
				}
				else
				{
					$this->logs->write_log("Error", "Charge  : ".$MS->errorMessage());
					//echo "<br>"."Charge  : ".$MS->errorMessage();
					$this->data['errorMessage'] = $MS->errorMessage();
				}
				
			/*}
			else
			{
				$this->logs->write_log("Error", "Authorization error : ".$MS->errorMessage());
				echo "<br>"."Authorization error  : ".$MS->errorMessage();
				$this->data['errorMessage'] = $MS->errorMessage();
			}*/
		}
		
		// $this->load->view('frontend/includes/header', $this->data);	
		$this->load->view('frontend/login_register/qbpayment', $this->data);
		// $this->load->view('frontend/includes/footer', $this->data);	
		}
	}
	
	
	public function calculate_next_month($start_date = FALSE) {
		  if ($start_date) {
			$now = $start_date; // Use supplied start date.
		  } else {
			$now = time(); // Use current time.
		  }

		  // Get the current month (as integer).
		  $current_month = date('n', $now);

		  // If the we're in Dec (12), set current month to Jan (1), add 1 to year.
		  if ($current_month == 12) {
			$next_month = 1;
			$plus_one_month = mktime(0, 0, 0, 1, date('d', $now), date('Y', $now) + 1);
		  }
		  // Otherwise, add a month to the next month and calculate the date.
		  else {
			$next_month = $current_month + 1;
			$plus_one_month = mktime(0, 0, 0, date('m', $now) + 1, date('d', $now), date('Y', $now));
		  }

		  $i = 1;
		  // Go back a day at a time until we get the last day next month.
		  while (date('n', $plus_one_month) != $next_month) {
			$plus_one_month = mktime(0, 0, 0, date('m', $now) + 1, date('d', $now) - $i, date('Y', $now));
			$i++;
		  }

		  return $plus_one_month;
}
	
	public function index()
	{
		$category_id = SPECIALCATEGORYID;
		$this->data['currentCategory'] = $category_id;
		if($this->categories_model->getMainCategories(array('categoryID' => $category_id, 'isActive' => '1')))
		{
			$this->data['categories'] = $this->categories_model->getSubCategoriesByParentID($category_id);
			$this->data['options'] = $this->categories_model->getOptions();
		}
		else
		{
			$this->data['categories'] = false;
		}
		$this->load->model('patient_model');
		//$this->data['logos'] =$this->patient_model->getlogos();
		$this->data['banner'] =$this->patient_model->getBanner();
		$this->data['text']=$this->patient_model->getText();
		$this->load->view('frontend/includes/header', $this->data);
		$this->load->view('frontend/main/index', $this->data);
		//$this->load->view('frontend/login_register/packages', $this->data);
	//	$this->load->view('frontend/main/login_dummy', $this->data);
		$this->load->view('frontend/includes/footer', $this->data);	
	}
	
	public function packages()
	{
		$this->data['packages'] = $this->main_model->getPackages();
		//$this->data['logos'] =$this->patient_model->getlogos();
		if($this->input->post())
		{
			$result = $this->main_model->insertvalues($this->input->post('username'),$this->input->post('email'),md5($this->input->post('password')),$this->input->post('PhoneNumber'));
			if(isset($result['SubscriberID']) && !empty($result['SubscriberID']))
			{
				
				$message  = '<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
</head>

<body>
<table width="700" border="0" cellspacing="0" cellpadding="0" bgcolor="#2190d7" style="font-family: \'Open Sans\', sans-serif; font-size:16px; margin:0  auto" >
  <tbody>
    <tr >
      <td align="center" height="30" ><br>
        <img src="http://420medsoft.com/images/logo.png" alt=""/></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><div style="border-radius: 12px;  -moz-border-radius: 12px;  -webkit-border-radius: 12px; background:#fff; margin:0 30px; padding:14px;"> Hi <strong>'.$this->input->post('username').'</strong>,
          <div style=" border-bottom: dashed 1px #ccc; border-top:dashed 1px #ccc; margin:20px 0px; padding:10px 0; "> Welcome to 420MedSoft your complete software solution for your MMCC venture.
            You are succefully registered member and your credentials are below 
            Here is your login credentials<br>
            Log In : <strong>'.$this->input->post('email').'</strong><br>
            Password : <strong>'.$this->input->post('password').'</strong> </div>
          Please log in and provide your Quickbooks Online Log In and Password so we can link your 420MedSoft system with your accounting for your automated transactions transfer into Quickbooks Online. If you need to register with Quickbooks please do so during your set up instructions. After your Quickbooks registration is completed, provide your log in and password in your settings at 420MedSoft.<br>
          <br>
          Register for your driver tracking software, after you registration is completed, provide your log in and password in your settings at 420MedSoft.<br>
          <br>
          If you already have a merchant account then simply add your merchant details in your settings at 420MedSoft. If you do not have a merchant account and would like to accept credit cards.<br>
          <br>
          Please call 619 972-5280 for details and pricing. </div></td>
    </tr>
    <tr>
      <td><div style="background:#fff; padding:10px; margin:20px 0px;">Welcome to 420MedSoft.com and thank you for your subscription your system will be set up with in 72 hrs.</div></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
</body>
</html>';

				/*$message  = "Hi ".$this->input->post('username');
				$message .= "<br/> Welcome to 420MedSoft your complete software solution for your MMCC venture.";
				$message .= "<br/> You are succefully registered member and your credentials are below ";
				$message .= "<br/> Here is your user Log In : " .$this->input->post('email'). " and Password : ".$this->input->post('password');
				$message .= "<br/> Please log in and provide your Quickbooks Online Log In and Password so we can link your 420MedSoft system with your accounting for your automated transactions transfer into Quickbooks Online. If you need to register with Quickbooks click here. After your Quickbooks registration is completed, provide your log in and password in your settings at 420MedSoft. ";
				$message .= "<br/> For your driver tracking software, please register here. After you registration is completed, provide your log in and password in your settings at 420MedSoft.";
				$message .= "<br/> If you already have a merchant account then simply add your merchant details in your settings at 420MedSoft. If you don't have a merchant account and would like to accept credit cards, please call 619 972-6280 for details and pricing.";
				$message .= '<br/> Welcome to <a href="http://420medsoft.com/">420MedSoft.com</a> and thank you for your subscription your system will be set up with in 72 hrs.';*/
				$subject  = "Succefully registration";
				$fromname = "420Medsoft";
				
				$this->main_model->sendmails($this->input->post('email'),'no-reply@420medsoft.com',$message,$subject,$fromname);
				
				$session_array = array('SUBSCRIPTION_USER_ID' => $result['SubscriberID'], 'SUBSCRIPTION_USER_NAME' => $result['UserName']);
				$this->session->set_userdata($session_array); 
				redirect('main/payment');
				exit;
			}
			else
			{
				$this->data['errorMessage'] = $result['OptMsg'];
			}
		}
		
		$this->load->view('frontend/includes/header', $this->data);	
		$this->load->view('frontend/login_register/packages', $this->data);
		$this->load->view('frontend/includes/footer', $this->data);			
	}
	
	
	public function payment()
	{
		$this->data['packages'] = $this->main_model->getPackages();
		if(!isset($this->data['session']['SUBSCRIPTION_USER_ID']) || empty($this->data['session']['SUBSCRIPTION_USER_ID']))
		{
			redirect('main/packages');
			exit;
		}
		$this->data['subscriberDetails'] = $this->main_model->getSubscriberDetails($this->data['session']['SUBSCRIPTION_USER_ID']);
		
		if($this->input->post())
		{
			$this->load->library('quickbooks');
			$this->load->library('logs');
						
			$this->logs->write_log("INFO", "Checking Creditcard details");			
			require_once FCPATH . 'Quickbookapi/QuickBooks.php';
			$post = $this->input->post();
			//echo "<pre>"; print_r($post); exit;
			$dsn = null;
				
			//Testing
		//	$application_login = 'bayfrontstaging.bayfrontorganics.com';
			//$connection_ticket = 'SDK-TGT-102-qDnMovo4Rckuk1l695Oxwg';
			
			//Live
		$application_login = 'bayfrontorganics.bayfrontorganics.com';
			$connection_ticket = 'SDK-TGT-57-zRBpWqI2P_4vC$bnv3Fp4Q';
			
			
			$path_to_private_key_and_certificate = null;
			
			$MS = new QuickBooks_MerchantService($dsn, $path_to_private_key_and_certificate, $application_login, $connection_ticket);
			//Testing
			$MS->useTestEnvironment(FALSE);
			
			//Live
			//$MS->useTestEnvironment(false);
			
			$MS->useDebugMode(false);
			
			$this->logs->write_log("INFO", "Merchant Service connection successful.");
			
			$name = $post['nameOnCard'];			
			$number = $post['cardNumber'];			
			$expyear = $post['expiry_year'];			
			$expmonth = $post['expiry_month'];			
			$address = '';			
			$postalcode = '';			
			$cvv = $post['CVVCode'];

			$total_price = $this->data['packages']['Cost'];
			
			// Create the CreditCard object
			$Card = new QuickBooks_MerchantService_CreditCard($name, $number, $expyear, $expmonth, $address, $postalcode, $cvv);
			if ($Transaction = $MS->authorize($Card, $total_price))
			{
				$this->logs->write_log("INFO", "Credit card authorization successful.");
				
				if ($Transaction = $MS->charge($Card, $total_price))
				{
					$this->logs->write_log("INFO", "Successfull charge for Creditcard : ".$total_price);	
							
					$TransactionDetails = $Transaction->toArray();
					if($TransactionDetails['PaymentStatus'] == 'Completed')
					{
						$SubscriberID = $this->data['session']['SUBSCRIPTION_USER_ID'];
						$PackageID = $this->data['packages']['PackageID'];
						$Amount = $total_price;
						$CreditCardTransID = $TransactionDetails['CreditCardTransID'];
						
						$this->main_model->subscriptionSuccessful($SubscriberID, $PackageID, $Amount, $CreditCardTransID);
						
						$session_array = array('SUBSCRIPTION_USER_ID', 'SUBSCRIPTION_USER_NAME');
						$this->session->unset_userdata($session_array);
				
						redirect('main/thanks'); exit;
					}
				}
				else
				{
					$this->logs->write_log("Error", "Charge  : ".$MS->errorMessage());
					echo "<br>"."Charge  : ".$MS->errorMessage();
					$this->data['errorMessage'] = $MS->errorMessage();
				}
				
			}
			else
			{
				$this->logs->write_log("Error", "Authorization error : ".$MS->errorMessage());
				echo "<br>"."Authorization error  : ".$MS->errorMessage();
				$this->data['errorMessage'] = $MS->errorMessage();
			}
		}
		
		$this->load->view('frontend/includes/header', $this->data);	
		$this->load->view('frontend/login_register/payment', $this->data);
		$this->load->view('frontend/includes/footer', $this->data);	
	}
	
	public function thanks()
	{
		$this->load->view('frontend/includes/header', $this->data);	
		$this->load->view('frontend/login_register/thanks', $this->data);
		$this->load->view('frontend/includes/footer', $this->data);	
	}
	public function ccthanks()
	{
		//$this->load->view('frontend/includes/header', $this->data);	
		$this->load->view('frontend/login_register/ccthanks', $this->data);
		//$this->load->view('frontend/includes/footer', $this->data);	
	}
	public function renewthanks($domain)
	{
		$this->data['domain']=$domain;
		//$this->load->view('frontend/includes/header', $this->data);	
		$this->load->view('frontend/login_register/renew_thanks', $this->data);
		//$this->load->view('frontend/includes/footer', $this->data);	
	}
	public function login()
	{
		if($this->input->post())
		{
			$res=$this->load->model('main_model');
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			if($this->input->post('remember_me'))
			{
				setcookie('MEDSOFT_USERNAME',$this->input->post('username'), time()+2595000);
			} 
			$Subscriber = $this->main_model->checkSubscriberAuthentication($username,md5($password));
			if(isset($Subscriber['SubscriberID']) && !empty($Subscriber['SubscriberID']))
			{
				$session_array = array('SUBSCRIBER_ID' => $Subscriber['SubscriberID'], 'SUBSCRIBER_NAME' => $Subscriber['username']);
				$this->session->set_userdata($session_array);
				redirect(base_url('index.php/main/dashboard')); exit;
			}
			else
			{
				$this->data['errorMessage'] = "Invalid username or password. or Account deactivated.";
			}
		}
		$this->data['coockie_username'] = $this->input->cookie('MEDSOFT_USERNAME', TRUE);
				
		$this->load->view('frontend/includes/header', $this->data);	
		$this->load->view('frontend/login_register/login', $this->data);	
		$this->load->view('frontend/includes/footer', $this->data);
	}
	
	public function isSubscriberLoggedIn()
	{
		if($this->session->userdata('SUBSCRIBER_ID')=='' || $this->session->userdata('SUBSCRIBER_NAME')=='' )
		{
			redirect(base_url('index.php/main/login'));
		}
	}
	
	public function dashboard()
	{
		$this->isSubscriberLoggedIn();		
		$this->load->view('frontend/includes/header', $this->data);	
		$this->load->view('frontend/login_register/dashboard', $this->data);
		$this->load->view('frontend/includes/footer', $this->data);	
	}
	
	public function editprofile()
	{
		$this->isSubscriberLoggedIn();
		if($this->input->post())
		{
			$post = $this->input->post();
			$this->main_model->updateSubscriberDetails($this->data['session']['SUBSCRIBER_ID'], $post);
			$this->data['success_message'] = "Profile updated successfully.";
		}
		
		$this->data['Subscriber'] = $this->main_model->getSubscriberDetails($this->data['session']['SUBSCRIBER_ID']);
		
		$this->load->view('frontend/includes/header', $this->data);	
		$this->load->view('frontend/login_register/editprofile', $this->data);	
		$this->load->view('frontend/includes/footer', $this->data);
	}
	
	public function sitedetails()
	{
		$this->isSubscriberLoggedIn();
		if($this->input->post())
		{
			/*$config['upload_path'] = './uploaded/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']	= '100';
			$config['max_width']  = '1024';
			$config['max_height']  = '768';
	
			$this->load->library('upload', $config);
	
			if ( ! $this->upload->do_upload())
			{
				$error = array('error' => $this->upload->display_errors());
				echo "<pre>"; print_r($error); exit;
			}
			else
			{
				echo "<pre>"; print_r($this->upload->data()); exit;
				$data = array('upload_data' => $this->upload->data());
			}*/
			
			$this->load->model('main_model');			
			$post = $this->input->post();
			//print_r($post);exit;
			$this->main_model->updateSiteDetails($this->data['session']['SUBSCRIBER_ID'], $post, $post['idcard']);
			$this->data['success_message'] = "Patient website set up updated successfully.";
		}
		
		$this->data['SiteDetails'] = $this->main_model->getSiteDetails($this->data['session']['SUBSCRIBER_ID']);
		
		$this->load->view('frontend/includes/header', $this->data);
		$this->load->view('frontend/login_register/sitedetails', $this->data);
		$this->load->view('frontend/includes/footer', $this->data);	
	}
	
	public function settings()
	{
		$this->isSubscriberLoggedIn();
		if($this->input->post())
		{
			$post = $this->input->post();
			$this->main_model->updateAccounts($this->data['session']['SUBSCRIBER_ID'], $post);
			$this->data['success_message'] = "Settings updated successfully.";
		}
		
		$this->data['account'] = $this->main_model->getAccounts($this->data['session']['SUBSCRIBER_ID']);
		//$this->data['Quick'] = $this->main_model->getQuick($this->data['session']['SUBSCRIBER_ID']);
		//print_r($this->data['Quick']);exit;
		$this->load->view('frontend/includes/header', $this->data);	
		$this->load->view('frontend/login_register/settings');
		$this->load->view('frontend/includes/footer', $this->data);	
	}
	
	public function changepassword()
	{
		$this->isSubscriberLoggedIn();
	
		if($this->input->post())
		{
			$post 	= $this->input->post();
			$result = $this->main_model->changeSubscriberPassword($this->data['session']['SUBSCRIBER_ID'], $post); 		 
			if($result)
				$this->data['successMessage'] = "Password changed successfully.";
			else
				$this->data['errorMessage'] = "Old password incorrect.";
		}
		$this->load->view('frontend/includes/header', $this->data);	
		$this->load->view('frontend/login_register/changepassword', $this->data);	
		$this->load->view('frontend/includes/footer', $this->data);	
	}
	
	public function forgotpassword()
	{
	$this->load->helper('url');
	//$this->load->model('main_model');
	if($this->input->post())
	{ 
		$username= $_POST['username'];
		$result=$this->main_model->forgotpassword($this->input->post());
		if(!$result)
		{
			$this->data['error_message'] = "Invalid E-mail. Please try again with valid E-mail.";
		}
		else
		{
			
			$message  = '<!doctype html>
			<html>
			<head>
			<meta charset="utf-8">
			<title>Untitled Document</title>
			<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
			</head>
			
			<body>
			<table width="700" border="0" cellspacing="0" cellpadding="0" bgcolor="#2190d7" style="font-family: \'Open Sans\', sans-serif; font-size:16px; margin:0  auto" >
			  <tbody>
				<tr >
				  <td align="center" height="30" ><br>
					<img src="http://420medsoft.com/images/logo.png" alt=""/></td>
				</tr>
				<tr>
				  <td>&nbsp;</td>
				</tr>
				<tr>
				  <td><div style="border-radius: 12px;  -moz-border-radius: 12px;  -webkit-border-radius: 12px; background:#fff; margin:0 30px; padding:14px;"> Hi,
					  <div style=" border-bottom: dashed 1px #ccc; border-top:dashed 1px #ccc; margin:20px 0px; padding:10px 0; "> 
					  	You have requested for the new password.
						Here is your new login credentials<br>
						Log In : <strong>'.$username.'</strong><br>
						Password : <strong>'.$result.'</strong> </div>
					  </div></td>
				</tr>
				<tr>
				  <td>&nbsp;</td>
				</tr>
			  </tbody>
			</table>
			</body>
			</html>';

			$this->load->library('email');
			$config['mailtype'] = 'html';
			$config['charset']  = 'utf-8';
			$this->email->initialize($config);
			$this->email->from('noreply@420medsoft.com', '420Medsoft');
			$this->email->to($username);  
			$this->email->to('nagaraju.bandi@inducosolutions.com');  
			$this->email->subject('Password reset');
			$this->email->message($message); 
			$this->email->send();
			$this->data['success_message'] = "New password is generated and send it to your E-mail.";
		}
	}
    $this->load->view('frontend/includes/header', $this->data);	
	$this->load->view('frontend/login_register/forgotpassword', $this->data);	
	$this->load->view('frontend/includes/footer', $this->data);	
  
 }
	
	public function forgdotpassword(){
		$this->load->model('main_model');  
		if($this->input->post()){ 
			$this->main_model->forgotpassword($this->input->post());
		}
		
	$this->load->view('frontend/includes/header', $this->data);	
	
	$this->load->view('frontend/login_register/forgotpassword');
	$this->load->view('frontend/includes/footer', $this->data);	
	}
	
	
	
	
	
	
	
	
	
	
	
	public function thankyou($msg = '')
	{
		if(!empty($msg))
			$this->data['message'] = $msg;
		$this->load->view('frontend/includes/header', $this->data);
		$this->load->view('frontend/main/thankyou', $this->data);
		$this->load->view('frontend/includes/footer', $this->data);
	}
	
	public function categories($category_id = '', $category_type = '')
	{
		if(empty($category_id))
		{
			redirect('main/index');
		}
		
		$this->data['currentCategory'] = $category_id;
		$this->data['options'] = $this->categories_model->getOptions();
		$this->load->helper('form');
		if($this->input->post())
		{
			$post = $this->input->post();
			$category_name = $post['categoryName'];
			$this->data['categories'] = $this->categories_model->getSubCategoriesByParentID($category_id, true, $category_type, $category_name);
		}
		else
		{
			$this->data['categories'] = $this->categories_model->getSubCategoriesByParentID($category_id, true, $category_type);
		}
		$this->data['parentCategory'] = $this->categories_model->getCategoryByCategoryID($category_id);
		
		$this->load->view('frontend/includes/header', $this->data);
		$this->load->view('frontend/main/categories', $this->data);
		$this->load->view('frontend/includes/footer', $this->data);
	}
	
	public function cart()
	{
		if($this->isAjaxRequest())
		{
			$this->load->helper('form');
			if($this->input->post())
			{
				$post = $this->input->post();
				if( isset($post['maincategory']) && !empty($post['maincategory']) && isset($post['category']) && !empty($post['category']) && isset($post['product']) && !empty($post['product']))
				{
					$quantity = ($post['maincategory'] == BOGOCATEGORYID) ? 2 : 1;
					
					$productKey = $post['maincategory'].':'.$post['category'].':'.$post['product'];
					if(isset($this->data['session']['cart'][$productKey]))
					{
						$this->data['session']['cart'][$productKey]['quantity'] = $this->data['session']['cart'][$productKey]['quantity'] + $quantity;
					}
					else
					{
						$cart_product = $this->categories_model->getProductByProductID($post['product']);
						if($cart_product)
						{
							$product_details = array('categotyName' => $cart_product['categoryName'], 'productName' => $cart_product['productName'], 'QBcode' => $cart_product['QBcode'], 'quantity' => $quantity, 'productPrice' => ($cart_product['price']/$quantity));
							$this->data['session']['cart'][$productKey] = $product_details;
						}
					}
					$this->session->set_userdata($this->data['session']);
				}
				$this->load->view('frontend/includes/sidecart', $this->data);
			}
			else
			{
				echo "Sorry";
			}
		}
		else
		{
			echo "Pichhoda: No details";
		}
	}
	
	public function addDeliveryType()
	{
		if($this->isAjaxRequest())
		{
			$this->load->helper('form');
			if($this->input->post())
			{
				$post = $this->input->post();
				if( isset($post['deliveryType']))
				{
					$this->data['session']['deliveryType'] = $post['deliveryType'];
					$this->session->set_userdata($this->data['session']);
				}
				$this->load->view('frontend/includes/sidecart', $this->data);
			}
			else
			{
				echo "Sorry";
			}
		}
		else
		{
			echo "Pichhoda: No details";
		}
	}
	
	public function addPaymentType()
	{
		if($this->isAjaxRequest())
		{
			$this->load->helper('form');
			if($this->input->post())
			{
				$post = $this->input->post();
				if( isset($post['paymentType']))
				{
					$this->data['session']['paymentType'] = $post['paymentType'];
					$this->session->set_userdata($this->data['session']);
				}
				$this->load->view('frontend/includes/sidecart', $this->data);
			}
			else
			{
				echo "Sorry";
			}
		}
		else
		{
			echo "Pichhoda: No details";
		}
	}
	/*public function samplecart($id)
	{
		$this->db->select('categoryID');
		$this->db->from('products');
		$this->db->where('productID',$id);
		$r=$this->db->get();
		$r=$r->result_array();
		$categoryID=$r[0]['categoryID'];

		$this->db->select('parentID');
		$this->db->from('categories');
		$this->db->where('categoryID',$categoryID);
		$r=$this->db->get();
		$r=$r->result_array();
		$maincategory=$r[0]['parentID'];
		
		$post = array('maincategory'=>$maincategory,'category'=>$categoryID,'product'=>$id);
		//echo "<pre>";print_r($post);exit;
		if( isset($post['maincategory']) && !empty($post['maincategory']) && isset($post['category']) && !empty($post['category']) && isset($post['product']) && !empty($post['product']))
		{
			$productKey = $post['maincategory'].':'.$post['category'].':'.$post['product'];
			if(isset($this->data['session']['cart'][$productKey]))
			{
				$this->data['session']['cart'][$productKey]['quantity'] = $this->data['session']['cart'][$productKey]['quantity'] + 1;
			}
			else
			{
				$cart_product = $this->categories_model->getProductByProductID($post['product']);
				if($cart_product)
				{
					$product_details = array('categotyName' => $cart_product['categoryName'], 'productName' => $cart_product['productName'], 'QBcode' => $cart_product['QBcode'], 'quantity' => 1, 'productPrice' => $cart_product['price']);
					$this->data['session']['cart'][$productKey] = $product_details;
				}
			}
			$this->session->set_userdata($this->data['session']);
		}
		else
		{
			echo "Pichhoda: No details";
		}
		redirect(base_url());
	}*/
	
	public function removecart()
	{
		if($this->isAjaxRequest())
		{
			$this->load->helper('form');
			if($this->input->post())
			{
				$post = $this->input->post();
				if( isset($post['product']) && !empty($post['product']) )
				{
					$productKey = $post['product'];
					if(isset($this->data['session']['cart'][$productKey]))
					{
						unset($this->data['session']['cart'][$productKey]);
					}
					$this->session->set_userdata($this->data['session']);
				}
				$this->load->view('frontend/includes/sidecart', $this->data);
			}
			else
			{
				echo "Sorry";
			}
		}
		else
		{
			echo "Pichhoda: No details";
		}
	}
	
	public function emptycart()
	{
		$this->session->unset_userdata('cart');
		$this->session->unset_userdata('creditData');
		$this->data['session'] = $this->session->all_userdata();		
	}
	
	public function checkusername()
	{
		if($this->isAjaxRequest())
		{
			$this->load->helper('form');
			if($this->input->post())
			{
				$post = $this->input->post();
				if( isset($post['userName']) && !empty($post['userName']) )
				{
					$this->load->model('patient_model');		
					if($this->patient_model->checkPatientUsername($post['userName']))
					{
						echo true;
					}
					else
						echo false;
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
	
	
	
	
	public function registers(){
		$this->load->model('main_model');
		//$this->load->model('main_model');
		//print_r($this->input->post());exit;
		 $this->main_model->insertvalues($this->input->post('username'),$this->input->post('email'),$this->input->post('password'),$this->input->post('PhoneNumber'));
   redirect('main/payment');
 }

 

 
 

			public function editprofile1(){
	$this->load->model('main_model');
	
	$firstname = $this->input->post('username');
         $address = $this->input->post('address');
		$PhoneNumber = $this->input->post('PhoneNumber'); 
	 
	$error = $this->main_model->editprofile1($this->session->userdata('id'), $firstname,'', $address,$PhoneNumber);
	
	
if (!$error['error']) {
 redirect('main/login');
} else {
  //echo "tyfyf";
}
			
 }
 
 public function changpwd(){
	 
	$oldpassword = $this->input->post('oldpassword');
         $newpassword = $this->input->post('newpassword');
	 $confirmpassword = $this->input->post('confirmpassword');
	 }
	 
	 
	  function logout()
    {
        $this->session->unset_userdata('logged_in');
        session_destroy();
		
		
        redirect('main/index');
    }
	/*function sendMail()
{
          $this->load->library('email');
		  
		  
		  for user
		  $this->email->from('noreply@420medsoft.com', '420medsoft');
		  $this->email->to($result[0]['email']);  
		  $this->email->to('lakshmi.priyanka@inducosolutions.com');  
		  $this->email->subject('Registered Successfully');
		  $this->email->message('You have requested the new password, Here is you new password:'. $result[0]['password']); 
		 $this->email->message('Welcome to 420medsoft'); 
		  $this->email->send(); 
		  
		  for admin
		  $this->email->from('noreply@420medsoft.com', '420medsoft');
		  $this->email->to($result[0]['email']);  
		  $this->email->to('lakshmi.priyanka@inducosolutions.com');  
		  $this->email->subject('New User');
		  $this->email->message('New User'  ); 
		  $this->email->send(); 
		  
    if($this->email->send())
    {
        echo "Mail send successfully!";
    }
    else
    {
        show_error($this->email->print_debugger());
    }
}*/
public function  sendMails(){
		$this->load->model('main_model');  
		if($this->input->post()){ 
		  $this->load->library('email');
		  
		  		  
   /* if($this->email->send())
    {
        echo "Mail send successfully!";
    }
    else
    {
        show_error($this->email->print_debugger());
    }*/

 }
}


	
	public function announcement()
	{
		$this->load->model('orders_model');
		$this->data['announcement'] = $this->orders_model->getAnnouncementCounterPatientDetails();
		if(isset($this->data['announcement']['queueID']) && !empty($this->data['announcement']['queueID']))
		{
			$this->orders_model->updateAnnouncementCounterPatientDetails($this->data['announcement']['queueID']);
		}
		$this->load->view('frontend/main/announcement', $this->data);
	}
	
	
	
	/////////////////////////////////////////////////////
	/////////////////////////////////////////////////////
	
	public function uploadImage()
	{
		//
		$path = "uploaded/websiteLogos/";
		$allowTypes = array('xls','xlsx','pdf','csv', "bmp");
		$valid_formats = array("jpg", "png", "gif","jpeg","ico");
		
		$error="";
		$filename      = str_replace('-','_',$_REQUEST['name']);

		if(!empty($_FILES[$filename]['error']))
		{
			switch($_FILES[$filename]['error'])
			{
		
				case '1':
					$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$error = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$error = 'No file was uploaded.';
					break;
		
				case '6':
					$error = 'Missing a temporary folder';
					break;
				case '7':
					$error =  'Failed to write file to disk';
					break;
				case '8':
					$error = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$error = 'No error code avaiable';
			}
		}elseif(empty($_FILES[$filename]['tmp_name']) || $_FILES[$filename]['tmp_name'] == 'none')
		{
			$error ='No file was uploaded.'.implode(',',$_FILES[$filename]);
		}else 
		{
			$message = '';
			$extension 	 =	 end(explode(".", $_FILES[$filename]["name"]));
			
				if(getimagesize($_FILES[$filename]['tmp_name'])){
			
					$imgname 	 =	 'bayfront-image-'.time();	
						
					$img_path	 =	 $path.$imgname.'.'.$extension;
					$org_img_path =  $path.'original/'.$imgname.'.'.$extension;
					$temp_path	 = 	 $_FILES[$filename]['tmp_name'];
					
					$msg = 'Image Path:'.$img_path;

					require_once("SimpleImage.class.php");
					//code for resizing image	
					$image = new SimpleImage();
					$image->load($temp_path);
					$image->save($org_img_path);
					

					
					$image->save($img_path);
						$x ='0';  $height_sts = false;
						$y = '0'; $width_sts  = false;
						
						$imgwidth = 136; $imgheight = 38;
						
						$upImgWidth=$image->getWidth(); $upImgHeight=$image->getHeight();
						//Thumbnail Image
						if(($upImgWidth  > $imgwidth) || ($upImgHeight > $imgheight)){
							if($upImgWidth  > $imgwidth)	{
								$image->resizeToWidth($imgwidth);
								$image->save($img_path);	
								$image->load($img_path);
							} 
							
							$upImgHeight = $image->getHeight();
							if($upImgHeight > $imgheight)	{
								$image->resizeToHeight($imgheight);	
								$image->save($img_path);
							}
							$image->load($img_path);
							$newheight = $image->getHeight(); $newwidth = $image->getWidth();
							$hdiff = $imgheight - $newheight; $wdiff = $imgwidth - $newwidth;
						
						}else{
							$image->save($img_path);
							$hdiff = $imgheight-$upImgHeight; $wdiff = $imgwidth-$upImgWidth;
						}
						$y= $hdiff>0 ? round($hdiff/2) : 0; $x= $wdiff>0 ? round($wdiff/2) : 0;
						$style = 'margin-left : '.$x.'px; margin-top :  '.$y.'px';
					
				}else{
					$error = 'Please upload a valid image';
				}
			
		}	
		$result=array('error'=>$error,'img_path'=>$img_path);
		echo json_encode($result);
		exit(); // do not go futher
	}
	
	
	
	
}
