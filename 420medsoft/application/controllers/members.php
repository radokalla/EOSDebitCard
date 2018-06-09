<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'controllers/main.php');
class Members extends Main {

	public function __construct()
	{
		parent::__construct();
		//$this->output->clear_page_cache();
	}
	
	public function index()
	{
		$this->load->view('frontend/includes/header', $this->data);
		$this->load->view('frontend/members/index', $this->data);
		$this->load->view('frontend/includes/footer', $this->data);
	}
	
	public function register()
	{
		$this->load->model('patient_model');
		$this->load->helper('form');		
		$this->load->library('form_validation');		
		$validation_rules = array( 
								array('field'   => 'firstName', 'label' => 'Please fill First Name', 'rules' => 'required'),
								array('field'   => 'userName', 'label' => 'Please fill Username', 'rules' => 'required'),
								array('field'   => 'password', 'label'  => 'Please fill Password', 'rules' => 'required')
							);

		$this->form_validation->set_rules($validation_rules); 
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		if ($this->form_validation->run())
		{
			if($this->input->post())
			{
				$post = $this->input->post();
				if($this->patient_model->checkPatientUsername($post['userName']))
				{
					try
					{
						$Customer = array(	'GivenName' => $post['firstName'],
										'MiddleName' => '',
										'FamilyName' => $post['lastName'],
										'DisplayName' => $post['userName'],
										'PrimaryPhone' => $post['phone'],
										'Mobile' => $post['phone'],
										'Line1' => $post['address1'],
										'Line2' => $post['address2'],
										'City' => $post['city'].' '.$post['state'],
										'CountrySubDivisionCode' => $post['country'],
										'PostalCode' => $post['zip'],
										'PrimaryEmailAddr' => $post['email'],
									 );
						$this->load->library('quickbooks');				
						$customer_details = $this->quickbooks->addCustomer($Customer);
						if($customer_details)
						{
							$post['QBCodeID'] = $customer_details;
						
							$patientDetails = $this->patient_model->patientRegistration($post);
							$patientName = $patientDetails['firstName'];
							$patientName .= !empty($patientDetails['lastName']) ? ' '.$patientDetails['lastName'] : '';
							$session_array = array('PATIENT_ID' => $patientDetails['patientID'],'PATIENT_NAME' => $patientName);
							$this->session->set_userdata($session_array); 
							redirect(base_url("index.php/main/index")); exit;
						}
					}
					catch (Exception $e)
					{
						echo "Caught exception: Exception in adding Patient\n";
					}
				}
			}			
		}
		
		$this->load->view('frontend/includes/header', $this->data);
		$this->load->view('frontend/members/register', $this->data);
		$this->load->view('frontend/includes/footer', $this->data);
	}
	
	public function login()
	{
		$this->load->model('patient_model');
		$this->load->helper('form');			
		if($this->input->post())
		{
			$patientDetails = $this->patient_model->checkLogin($this->input->post());
			if($patientDetails)
			{
				$patientName = $patientDetails['firstName'];
				$patientName .= !empty($patientDetails['lastName']) ? ' '.$patientDetails['lastName'] : '';
				$session_array = array('PATIENT_ID' => $patientDetails['patientID'],'PATIENT_NAME' => $patientName);
				$this->session->set_userdata($session_array); 
			}
			else
			{
				$session_array = array('LOGIN_ERROR' => 'Incorrect username or password or Inactivated');
				$this->session->set_userdata($session_array); 
			}
		}
		else
		{
			$session_array = array('LOGIN_ERROR' => 'Incorrect username or password');
			$this->session->set_userdata($session_array); 
		}
		redirect(base_url("index.php/main/index")); exit;
	}
	
	public function forgot()
	{
		//echo "<pre>"; print_r($this->input->post());exit;
		$this->load->model('patient_model');
		if($this->input->post()) 
		{
			$result=$this->patient_model->ForgotPassword();
			if($result)
				$session_array = array('FORGOT_SUCESS' => 'Password was sent, please check registered e-mail.');
			else
				$session_array = array('LOGIN_ERROR' => 'Email not found in record');
			$this->session->set_userdata($session_array); 
		}
		redirect(base_url("index.php/main/index")); exit;
	}
	
	public function resetpwd()
	{
		//$data=array();
		$data['id']=base64_decode($_REQUEST['setval']);
		if($this->input->post())
		{
			$data=$this->input->post();
			$result=$this->patient_model->ResetPassword($this->input->post());
			$session_array = array('FORGOT_SUCESS' => 'Password changed sucessfully please login.');
			redirect(base_url("index.php/main/index")); exit;
		}
		$this->load->view('frontend/includes/header', $this->data);
		$this->load->view('frontend/members/forgetpwd', $data);
		$this->load->view('frontend/includes/footer', $this->data);
	}
	
	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url("index.php/main/index")); exit;
	}
	
	public function QbCustomerAdd()
	{ 
		$this->load->config('quickbooks');
		$this->qbwc();
		echo APPPATH;exit;	
	}
	
	public function qbwc()
	{
		$user = $this->config->item('quickbooks_user');
		$pass = $this->config->item('quickbooks_pass');
		
		// Memory limit
		ini_set('memory_limit', $this->config->item('quickbooks_memorylimit'));
		
		// We need to make sure the correct timezone is set, or some PHP installations will complain
		if (function_exists('date_default_timezone_set'))
		{
			// * MAKE SURE YOU SET THIS TO THE CORRECT TIMEZONE! *
			// List of valid timezones is here: http://us3.php.net/manual/en/timezones.php
			date_default_timezone_set($this->config->item('quickbooks_tz'));
		}
				
		// Map QuickBooks actions to handler functions
		$map = array(
			QUICKBOOKS_ADD_CUSTOMER => array( array( $this, '_addCustomerRequest' ), array( $this, '_addCustomerResponse' ) ),
			);
		
		// Catch all errors that QuickBooks throws with this function 
		$errmap = array(
			'*' => array( $this, '_catchallErrors' ),
			);
		
		// Call this method whenever the Web Connector connects
		$hooks = array(
			//QuickBooks_WebConnector_Handlers::HOOK_LOGINSUCCESS => array( array( $this, '_loginSuccess' ) ), 	// Run this function whenever a successful login occurs
			);
		
		// An array of callback options
		$callback_options = array();
		
		// Logging level
		$log_level = $this->config->item('quickbooks_loglevel');
		
		// What SOAP server you're using 
		//$soapserver = QUICKBOOKS_SOAPSERVER_PHP;			// The PHP SOAP extension, see: www.php.net/soap
		$soapserver = QUICKBOOKS_SOAPSERVER_BUILTIN;		// A pure-PHP SOAP server (no PHP ext/soap extension required, also makes debugging easier)
		
		$soap_options = array(		// See http://www.php.net/soap
			);
		
		$handler_options = array(
			'deny_concurrent_logins' => false, 
			);		// See the comments in the QuickBooks/Server/Handlers.php file
		
		$driver_options = array(		// See the comments in the QuickBooks/Driver/<YOUR DRIVER HERE>.php file ( i.e. 'Mysql.php', etc. )
			'max_log_history' => 32000,	// Limit the number of quickbooks_log entries to 1024
			'max_queue_history' => 1024, 	// Limit the number of *successfully processed* quickbooks_queue entries to 64
			);
		
		// Build the database connection string
		$dsn = 'mysql://' . $this->db->username . ':' . $this->db->password . '@' . $this->db->hostname . '/' . $this->db->database;
		
		// Check to make sure our database is set up 
		if (!QuickBooks_Utilities::initialized($dsn))
		{
			// Initialize creates the neccessary database schema for queueing up requests and logging
			QuickBooks_Utilities::initialize($dsn);
			
			// This creates a username and password which is used by the Web Connector to authenticate
			QuickBooks_Utilities::createUser($dsn, $user, $pass);
		}
		
		// Set up our queue singleton
		QuickBooks_WebConnector_Queue_Singleton::initialize($dsn);
		
		// Create a new server and tell it to handle the requests
		// __construct($dsn_or_conn, $map, $errmap = array(), $hooks = array(), $log_level = QUICKBOOKS_LOG_NORMAL, $soap = QUICKBOOKS_SOAPSERVER_PHP, $wsdl = QUICKBOOKS_WSDL, $soap_options = array(), $handler_options = array(), $driver_options = array(), $callback_options = array()
		$Server = new QuickBooks_WebConnector_Server($dsn, $map, $errmap, $hooks, $log_level, $soapserver, QUICKBOOKS_WSDL, $soap_options, $handler_options, $driver_options, $callback_options);
		$response = $Server->handle(true, true);
	}
	
	/**
	 * Issue a request to QuickBooks to add a customer
	 */
	public function _addCustomerRequest($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
	{
		// Do something here to load data using your model
		//$data = $this->yourmodel->getCustomerData($ID);
		
		// Build the qbXML request from $data
		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="2.0"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<CustomerAddRq requestID="' . $requestID . '">
					<CustomerAdd>
						<Name>ConsoliBYTE, LLC (' . mt_rand() . ')</Name>
						<CompanyName>ConsoliBYTE, LLC</CompanyName>
						<FirstName>Keith</FirstName>
						<LastName>Palmer</LastName>
						<BillAddress>
							<Addr1>ConsoliBYTE, LLC</Addr1>
							<Addr2>134 Stonemill Road</Addr2>
							<City>Mansfield</City>
							<State>CT</State>
							<PostalCode>06268</PostalCode>
							<Country>United States</Country>
						</BillAddress>
						<Phone>860-634-1602</Phone>
						<AltPhone>860-429-0021</AltPhone>
						<Fax>860-429-5183</Fax>
						<Email>Keith@ConsoliBYTE.com</Email>
						<Contact>Keith Palmer</Contact>
					</CustomerAdd>
				</CustomerAddRq>
			</QBXMLMsgsRq>
		</QBXML>';
	
		return $xml;
	}

	/**
	 * Handle a response from QuickBooks indicating a new customer has been added
	 */	
	public function _addCustomerResponse($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
	{
		// Do something here to record that the data was added to QuickBooks successfully 
		
		return true; 
	}
	
	/**
	 * Catch and handle errors from QuickBooks
	 */		
	public function _catchallErrors($requestID, $user, $action, $ID, $extra, &$err, $xml, $errnum, $errmsg)
	{
		return false;
	}
	
	/**
	 * Whenever the Web Connector connects, do something (e.g. queue some stuff up if you want to)
	 */
	public function _loginSuccess($requestID, $user, $hook, &$err, $hook_data, $callback_config)
	{
		return true;
	}
	
	public function uploadImage()
	{
		//
		$path = "uploaded/frontendMyaccount/";
		$allowTypes = array('xls','xlsx','pdf','csv', "bmp");
		$valid_formats = array("jpg", "png", "gif","jpeg","ico");
		/*
		list($id,$fieldname)=explode("_",$_REQUEST['page']);
		$str="";$imgpath = '';$error = "";$msg = "";
		
		$fileid		   = $_REQUEST['name'];
		$filename      = str_replace('-','_',$_REQUEST['name']);
		$arr		   = explode('-',$fileid);	
		$imgsetpath	   = $arr[0].'-'.$arr[1].'-imgPath';
			
		$successmssgid = $fileid.'-success-mssg';
		$errormssgid   = $fileid."-error-mssg";
		*/
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
					//echo $org_img_path; exit;
					$image->load($temp_path);
					$image->save($org_img_path);
					
					/*$image->crop(210,210);
					$image->save($img_path);*/
					
					$image->save($img_path);
						$x ='0';  $height_sts = false;
						$y = '0'; $width_sts  = false;
						
						$imgwidth = 227; $imgheight = 243;
						
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
							/*
							echo '<script language="JavaScript" type="text/javascript">'."\n";	
							echo 'console.log("Height:'.$hdiff.'****Width:'.$wdiff.' New Height:'.$newheight.'**** New Width:'.$newwidth.'");';
							echo "\n".'</script>';
							*/
						}else{
							$image->save($img_path);
							$hdiff = $imgheight-$upImgHeight; $wdiff = $imgwidth-$upImgWidth;
						}
						/*
						echo '<script language="JavaScript" type="text/javascript">'."\n";	
						echo 'console.log("Height:'.$hdiff.'****Width:'.$wdiff.'");';
						echo "\n".'</script>';
						*/
						$y= $hdiff>0 ? round($hdiff/2) : 0; $x= $wdiff>0 ? round($wdiff/2) : 0;
						$style = 'margin-left : '.$x.'px; margin-top :  '.$y.'px';
					
					//Thumbnail Image
					//$image->resize(150,150);
//			
//					$image->save($thumb_path);
				}else{
					$error = 'Please upload a valid image';
				}
			
		}	
		$result=array('error'=>$error,'img_path'=>$img_path);
		echo json_encode($result);
		/*
		echo '<script language="JavaScript" type="text/javascript">'."\n";
		echo 'var parDoc = window.parent.document;';
		if(!empty($error)){
			echo "parDoc.getElementById('".$errormssgid."').innerHTML = '".$error."';";
		}else{
			
			echo "parDoc.getElementById('".$imgsetpath."').value = '".$img_path."';";
			
			echo  "parDoc.getElementById('".$imgsetpath."_img').src = '".base_url($img_path)."';";
			
			echo  "parDoc.getElementById('".$imgsetpath."_img').setAttribute('style', '".$style."');";
			
			echo "parDoc.getElementById('".$errormssgid."').innerHTML = '';";
			
			if($_REQUEST['filetype']=='file'){
				//echo "parDoc.getElementById('".$successmssgid."').innerHTML = 'File uploaded successfully';";
			}else{
				//echo "parDoc.getElementById('".$successmssgid."').innerHTML = '<img src=\"".base_url($img_path)."\" width=\"50\" height=\"50\">';";
			}
		}
		echo "\n".'</script>';
		*/
		exit(); // do not go futher
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */