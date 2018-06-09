<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	
	public $data = array();
	private $recordsperpage;
	public function __construct()
	{
		parent::__construct();
		$purl=$this->session->userdata('purl');
		if($purl)
			$this->session->set_userdata(array('ppurl'=>$purl));
		$this->session->set_userdata(array('purl'=>(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'')));
		//$this->output->clear_page_cache();
		$this->data['currency'] = array('symbol'=>'$', 'text' => 'USD');
		$this->data['session'] = $this->session->all_userdata();
		$this->recordsperpage = 50;
		$this->load->model('admin_model');	
		$settings = $this->admin_model->getIp();
		$this->data['tax_percentage'] = $settings[0]['taxPercentage'];
		$this->data['delivery_charge'] = $settings[0]['deliveryCharges'];
	}
	
	public function index()
	{
		$purl=$this->session->userdata('purl');
		if($purl)
			$this->session->set_userdata(array('ppurl'=>$purl));
		$this->session->set_userdata(array('purl'=>(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'')));
		//echo "mounika111";exit;
		$this->load->helper('form');
		
		$this->load->model('admin_model');		
		$this->load->library('form_validation');
		
		$validation_rules = array( 
								array('field'   => 'username', 'label' => 'Please fill username', 'rules' => 'required'),
								array('field'   => 'password', 'label'  => 'Please fill password', 'rules' => 'required')
							);

		$this->form_validation->set_rules($validation_rules); 
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		if ($this->form_validation->run())
		{
			if($admin = $this->admin_model->checkAuthentication($this->input->post()))
			{
				
			 	 	if($admin['userType'] == 'admin')
					$LOGIN_TYPE='ADMIN';
					else if($admin['userType'] == 'partner')
					$LOGIN_TYPE='PARTNER';
					if($admin['userType'] == 'employee')
					$LOGIN_TYPE='EMPLOYEE';
					//$session_array = array('ADMIN_ID' => $admin['ID'],'ADMIN_NAME' => $admin['firstName'].' '.$admin['lastName'],'LOGIN_TYPE' =>$LOGIN_TYPE);
					$session_array = array('ADMIN_ID' => $admin['ID'],'ADMIN_NAME' => $admin['firstName'],'LOGIN_TYPE' =>$LOGIN_TYPE);
				//echo "<pre>";print_r($admin);exit;
				//$session_array = array('ADMIN_ID' => $admin['ID'],'ADMIN_NAME' => $admin['firstName'].' '.$admin['lastName'],'LOGIN_TYPE' => $admin['userType']);
				$this->session->set_userdata($session_array); 
				$back=$this->session->userdata('ppurl');
			 
				/*if($back) 
				{
					$back=str_replace(base_url('index.php'),'',$back);
					if($back=='admin/index' || $back=='/admin')
						$back="admin/dashboard";
				}
				else*/
					$back="admin/dashboard";
				redirect($back);
			}
			else
			{
				$this->session->set_flashdata('error', 'Wrong username or password.');
			}
		}
		
		$this->load->view('admin/includes/header');
		$this->load->view('admin/index');
		$this->load->view('admin/includes/footer');
	}
	
	/*public function login()
	{
		$this->load->helper('form');
		
		$this->load->model('admin_model');		
		$this->load->library('form_validation');
		
		$validation_rules = array( 
								array('field'   => 'username', 'label' => 'Please fill username', 'rules' => 'required'),
								array('field'   => 'password', 'label'  => 'Please fill password', 'rules' => 'required')
							);

		$this->form_validation->set_rules($validation_rules); 
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		if ($this->form_validation->run())
		{
			if($admin = $this->admin_model->checkAuthentication($this->input->post()))
			{
				$session_array = array('PARTNER_ID' => $admin['ID'],'PARTNER_NAME' => $admin['firstName'].' '.$admin['lastName'],'LOGIN_TYPE' => ($admin['userType'] = 'partner') ? 'PARTNER' : 'EMPLOYEE');
				$this->session->set_userdata($session_array); 
				redirect('admin/dashboard');
				
			}
			else
			{
				$this->session->set_flashdata('error', 'Wrong username or password. or Account inactivated.');
			}
		}
		
		$this->load->view('admin/includes/header');
		$this->load->view('admin/login');
		$this->load->view('admin/includes/footer');
	}*/
	
	public function isLoggedIn()
	{
		if($this->session->userdata('ADMIN_ID')=='' || $this->session->userdata('ADMIN_NAME')=='' )
		{
			if($this->router->fetch_method() == 'samplecart' && $this->router->fetch_class() == 'adminorders')		
			{	
				redirect('http://www.four20maps.com/');
			}
			else
				redirect(base_url('index.php/admin/index'));
		}
	}
	
	protected function isAjaxRequest()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest';
	}
	
	public function dashboard()
	{
		$this->isLoggedIn();
		
		$this->load->model('orders_model');
		$this->load->model('patient_model');
		$this->data['ordersDetails'] = $this->orders_model->getAllordersCount();
		$this->data['ordersStatus'] = $this->orders_model->getStatus();
		$this->data['unreadPatients'] = $this->patient_model->getUnreadPatients();
		//echo "<pre>";print_r($this->data['unreadPatients']);exit;
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/dashboard', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	}
	
	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url("index.php/admin")); exit;
	}

	public function employeedetails($recordsperpage = 50, $currentPage = 0)
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
		
		$this->load->model('admin_model');
		$this->data['employeeCount'] = $this->admin_model->getEmployeeCount($condition_array);
		$this->data['employeeDetails'] = $this->admin_model->getEmployeeDetails($condition_array);
		$this->data['recordsperpage'] = $this->recordsperpage;
		
		$this->load->library('pagination');
		$config['uri_segment'] = 4;
		$config['base_url'] = base_url('index.php/admin/employeedetails/'.$this->recordsperpage.'/');
		$config['total_rows'] = $this->data['employeeCount'];
		$config['per_page'] = $this->recordsperpage; 		
		$this->pagination->initialize($config); 		
		//echo "<pre>";print_r($this->data['employeeDetails']);exit;
		$this->data['paginationLinks'] = $this->pagination->create_links();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/employeedetails', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	}
	
	public function partnerdetails($recordsperpage = 50, $currentPage = 0)
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
		
		$this->load->model('admin_model');
		$this->data['employeeCount'] = $this->admin_model->getPartnersCount($condition_array);
		$this->data['employeeDetails'] = $this->admin_model->getPartnerDetails($condition_array);
		//echo "<pre>";print_r($this->data['employeeDetails']);exit;
		$this->data['recordsperpage'] = $this->recordsperpage;
		
		$this->load->library('pagination');
		$config['base_url'] = base_url('index.php/admin/partnerdetails/'.$this->recordsperpage.'/');
		$config['uri_segment'] = 4;
		$config['total_rows'] = $this->data['employeeCount'];
		$config['per_page'] = $this->recordsperpage; 		
		$this->pagination->initialize($config); 		
		$this->data['paginationLinks'] = $this->pagination->create_links();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/partnerdetails', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	}
	
	
	function getQrCode($id)
	{
		$this->load->library('qr');
		$text=base_url('index.php/admin/checkUser/'.$id);
		//echo $text;exit; 
		
		$data['employee'] = $this->admin_model->getDetailsByID($id, 'partner');
		if($data['employee'])
		{
			$data['title']="Partner Autologin Qr Code";
			$data['filename']="Partner";
		}
		else
		{
			$data['employee'] = $this->admin_model->getDetailsByID($id, 'employee');
			if($data['employee'])
			{
				$data['title']="Employee Autologin Qr Code";
				$data['filename']="Employee";
			}
			else
			{
				redirect(base_url('index.php/admin/employeedetails')); exit;
			}
		}
		
		$data['image']=$this->qr->Generate($text,4,1);
		$data['qr_url']=$this->qr->GetShortUrl($text);
		$this->load->view('admin/qrcode',$data);
	}
	
	
	function getPatientQrCode($patientID)
	{
		$this->load->library('qr');
		$text=base_url('index.php/patientqueue/queue/'.$patientID);
		
		$this->load->model("patient_model");
		$data['patientDetails'] = $this->patient_model->getAdminPatientDetails($patientID);
		
		if(isset($data['patientDetails']['patientID']) && !empty($data['patientDetails']['patientID']))
		{
			$data['title']="Patient Add queue Qr Code";
			$data['filename']="Patient";
		}
		else
		{
			redirect(base_url('index.php/adminpatients/patients')); exit;
		}
		
		$data['image']=$this->qr->Generate($text,4,1);
		$data['qr_url']=$this->qr->GetShortUrl($text);
		$this->load->view('admin/patientqrcode',$data);
	}
	
	function updatePartnerStatus()
	{
		if($this->isAjaxRequest())
		{
			$this->load->helper('form');
			if($this->input->post())
			{
				$post = $this->input->post();
				if( isset($post['employeeID']) && !empty($post['employeeID']) )
				{
					$this->load->model('admin_model');		
					$this->admin_model->updateEmployeeStatus($post['employeeID'], $post['status']);
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
	
	function updateEmployeeStatus()
	{
		if($this->isAjaxRequest())
		{
			$this->load->helper('form');
			if($this->input->post())
			{
				$post = $this->input->post();
				if( isset($post['employeeID']) && !empty($post['employeeID']) )
				{
					$this->load->model('admin_model');		
					$this->admin_model->updateEmployeeStatus($post['employeeID'], $post['status']);
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
	
	
	public function addpartner($employee_id = '')
	{
		$this->isLoggedIn();
		$this->load->helper('form');
		$this->load->model('admin_model');	
		
		if($this->input->post())
		{
			if($this->input->post('partner_id'))
			{
				$this->admin_model->updatePartner($this->input->post());
			}
			else
			{
				$this->admin_model->insertPartner($this->input->post());
			}
			redirect('admin/partnerdetails'); 
			exit;
		}
		
		if(!empty($employee_id))
			$this->data['employee'] = $this->admin_model->getDetailsByID($employee_id, 'partner');
		else if($this->input->post())
			$this->data['employee'] = $this->input->post();
		else
			$this->data['employee'] = array();
			
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/addpartner', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	}
	
	public function addemployee($employee_id = '')
	{
		$this->load->helper('form');
		$this->load->model('admin_model');	
		
		if($this->input->post())
		{
			if($this->input->post('employee_id'))
			{
				$this->admin_model->updateEmployee($this->input->post());
			}
			else
			{
				$this->admin_model->insertEmployee($this->input->post());
			}
			redirect('admin/employeedetails'); 
			exit;
		}
		
		if(!empty($employee_id))
			$this->data['employee'] = $this->admin_model->getDetailsByID($employee_id, 'employee');
		else if($this->input->post())
			$this->data['employee'] = $this->input->post();
		else
			$this->data['employee'] = array();
		
		$this->data['drivers']=$this->CallAPI("GET", "https://onfleet.com/api/v2/teams/".ONFLEETTEAMID, "");
		foreach($this->data['drivers']['workers'] as  $result)
		{
			//echo $result ."<br/>";
			$this->data['drivers']['workersdetails'][]=$this->CallAPI("GET", "https://onfleet.com/api/v2/workers/".$result, "");
		} 
		//echo "<pre>";
		//print_r($this->data['drivers']);
		if(!empty($employee_id))
			$this->data['existeddrivers'] = $this->admin_model->getDrivers($employee_id, 'employee');
		else
			$this->data['existeddrivers'] = $this->admin_model->getDrivers('', 'employee');
		//echo "<pre>"; print_r($this->data['drivers']); exit;
		 
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/addemployee', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	}
	
	public function deletepartner($empid = '')
	{
		$this->load->model('admin_model');	
		
		if(!empty($empid))
			$this->data['order'] = $this->admin_model->deleteEmployee(array('id' => $empid));
			
		redirect('admin/partnerdetails'); 
		exit;
	}
	
	public function deleteemployee($empid = '')
	{
		$this->load->model('admin_model');	
		
		if(!empty($empid))
			$this->data['order'] = $this->admin_model->deleteEmployee(array('id' => $empid));
			
		redirect('admin/employeedetails'); 
		exit;
	}
	
	public function uploadImage()
	{
		//
		$path = "uploaded/frontendMyaccount/";
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
					//echo $org_img_path; exit;
					$image->load($temp_path);
					//resizing orginal image
					$image->resize(1600,518);	
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
		exit(); // do not go futher
	}

	public function checkPartner()
	{
		if($this->isAjaxRequest())
		{
			$this->load->helper('form');
			if($this->input->post())
			{
				$post = $this->input->post();
				if( isset($post['userName']) && !empty($post['userName']) )
				{
					$this->load->model('admin_model');		
					if($this->admin_model->checkPartnerUsername($post['userName']))
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

	public function checkEmp()
	{
		if($this->isAjaxRequest())
		{
			$this->load->helper('form');
			if($this->input->post())
			{
				$post = $this->input->post();
				if( isset($post['userName']) && !empty($post['userName']) )
				{
					$this->load->model('admin_model');		
					if($this->admin_model->checkEmployeeUsername($post['userName']))
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
	
	public function ipdetails()
	{
		$this->load->helper('form');
		$this->load->model('admin_model');	
		
		if($this->input->post())
		{
			$this->admin_model->updateIp($this->input->post());
			redirect('admin/ipdetails'); 
			exit;
		}
		$this->data['ip'] = $this->admin_model->getIp();
		//echo "<pre>";print_r($this->data['ip']);exit;
			
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/addip', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	}
	
	public function changepwd($id="")
	{
		//$data=array();
		$this->load->helper('form');
		$this->load->model('admin_model');	
		if(empty($id))
			$data['id']=$this->session->userdata('ADMIN_ID');
		else
			$data['id']=$id;
		if($this->input->post())
		{
			$data=$this->input->post();
			$result=$this->admin_model->ResetPassword($this->input->post());
			$session_array = array('FORGOT_SUCESS' => 'Password changed sucessfully.');
			$this->session->set_userdata($session_array);
			//echo "<pre>";print_r($session_array);exit;
			if(empty($id))
				redirect('admin/changepwd');
			else
			{
				redirect($this->session->userdata('ppurl'));
			}
		}
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('frontend/members/forgetpwd', $data);
		$this->load->view('admin/includes/footer', $this->data);
	}

	public function checkUser($id)
	{
		if($id)
		{
			$this->load->model('admin_model');	
			$data=$this->admin_model->checkUser($id);
			if($data[0]['userType'] == 'admin')
			$LOGIN_TYPE='ADMIN';
			else if($data[0]['userType'] == 'partner')
			$LOGIN_TYPE='PARTNER';
			if($data[0]['userType'] == 'employee')
			$LOGIN_TYPE='EMPLOYEE';
			$session_array = array('ADMIN_ID' => $data[0]['ID'],'ADMIN_NAME' => $data[0]['firstName'].' '.$data[0]['lastName'],'LOGIN_TYPE' =>$LOGIN_TYPE);
			$this->session->set_userdata($session_array); 
			redirect('admin/dashboard');
		}
		else
		{
			redirect('admin/index');
		}
	}

	public function bannerdetails($recordsperpage = 50, $currentPage = 0)
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
		
		$this->load->model('admin_model');
		$this->data['bannerCount'] = $this->admin_model->getBannerCount($condition_array);
		$this->data['bannerDetails'] = $this->admin_model->getBannerDetails($condition_array);
		$this->data['recordsperpage'] = $this->recordsperpage;
		
		$this->load->library('pagination');
		$config['uri_segment'] = 4;
		$config['base_url'] = base_url('index.php/admin/bannerdetails/'.$this->recordsperpage.'/');
		$config['total_rows'] = $this->data['bannerCount'];
		$config['per_page'] = $this->recordsperpage; 		
		$this->pagination->initialize($config); 		
		//echo "<pre>";print_r($this->data['employeeDetails']);exit;
		$this->data['paginationLinks'] = $this->pagination->create_links();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/bannerdetails', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	}
	
	public function addbanner($banner_id = '')
	{
		$this->load->helper('form');
		$this->load->model('admin_model');	
		
		if($this->input->post())
		{
			//print_r($this->input->post());exit;
			if($this->input->post('banner_id'))
			{
				$this->admin_model->updateBanner($this->input->post());
			}
			else
			{
				$this->admin_model->insertBanner($this->input->post());
			}
			redirect('admin/bannerdetails'); 
			exit;
		}
		
		if(!empty($banner_id))
			$this->data['banner'] = $this->admin_model->getBannerDetailsByID($banner_id, 'banner');
		else if($this->input->post())
			$this->data['banner'] = $this->input->post();
		else
			$this->data['banner'] = array();
			
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/addbanner', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	}
	
	public function deletebanner($bannerid = '')
	{
		$this->load->model('admin_model');	
		
		if(!empty($bannerid))
			$this->data['order'] = $this->admin_model->deleteBanner(array('id' => $bannerid));
			
		redirect('admin/bannerdetails'); 
		exit;
	}
	
	
		public function chlogo()
	   {
		$this->load->model('admin_model');
		$this->load->helper('form');
		$this->data['logoss'] = $this->admin_model->getiIndexText();
		if($this->input->post())
		{
			$this->admin_model->upatelogo();
			redirect('admin/chlogo'); 
		}
	    $this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/addlogo', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	 
	}
	public function footerlogo()
	{
		$this->load->model('admin_model');
		$this->load->helper('form');
		$this->data['logoss'] = $this->admin_model->getiIndexText();
		if($this->input->post())
		{
			$this->admin_model->upatefooetrlogo();
			redirect('admin/footerlogo'); 
		}
		 $this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/addfooterlogo', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	}
	
	
	public function indexText()
	{
		$this->load->helper('form');
		$this->load->model('admin_model');	
		
		if($this->input->post())
		{
			$this->admin_model->updateText($this->input->post());
			redirect('admin/indexText'); 
			exit;
		}
		$this->data['text'] = $this->admin_model->getiIndexText();
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/addindexText', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	}
	
	
	public function getitemcode()
	{
		$this->load->helper('form');
		$this->load->model('admin_model');	
		
		if($this->input->post())
		{
			$this->load->library('quickbooks');			
			$this->data['itemCodes'] = $this->quickbooks->getItemCode($this->input->post('name'));
			
		}			
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/itemcodes', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	}
	
	
	function TelephoneNumberFormat($val)
	{
		$val = $this->unformatTelephoneNumber($val);
		$numberlenth = strlen($val);
		$res = "";
		if($numberlenth >= 7)
		{
			$fir = "(".substr($val,0,3).") ";
			$mid = substr($val,3,3);
			$las = substr($val,6,4);
			$res = $fir.$mid.'-'.$las;
		}				
		else if($numberlenth > 3) 
		{
			$fir = "(".substr($val,0,3).") ";
			$las = substr($val,3);
			$res = $fir.$las;
		}
		return $res;
	}


	function unformatTelephoneNumber($val)
	{
		$val	= str_replace(array('(',')',' ', '-'),"",$val);
		return str_replace(" ","",$val);
	}
	
	
	
	/////////////////////////////////////
	
	public function uploadBannerImage()
	{
		//
		$path = "uploaded/frontendMyaccount/";
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
					//echo $org_img_path; exit;
					$image->load($temp_path);
					//resizing orginal image
					$image->save($org_img_path);
					
					if($image->getWidth()>=1600 && $image->getHeight() >= 518)
					{
						$imgwidth = 1600; $imgheight = 520;
						
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
								$image->crop($imgwidth,$imgheight,'not scale');	
								$image->save($img_path);
							}
							$image->load($img_path);
							$newheight = $image->getHeight(); $newwidth = $image->getWidth();
							$hdiff = $imgheight - $newheight; $wdiff = $imgwidth - $newwidth;
						}else{
							$image->save($img_path);
							$hdiff = $imgheight-$upImgHeight; $wdiff = $imgwidth-$upImgWidth;
						}
					}else{
						$error = 'Image dimensions are too small. Minimum width is 1600px And Minimum height is 518px';
					}
						
				}else{

					$error = 'Please upload a valid image';
				}
			
		}	
		$result=array('error'=>$error,'img_path'=>$img_path);
		echo json_encode($result);
		exit(); // do not go futher
	}
	////////////////////////////////////////////////////////////
	
	
	public function CallAPI($method, $url, $data = false)
	{
		$curl = curl_init();
		switch ($method)
		{
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);
				  $data = json_encode($data);
				if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				 break;
			case "PUT":
				curl_setopt($curl, CURLOPT_PUT, 1);
				break;
			default:
				if ($data)
					$url = sprintf("%s?%s", $url, http_build_query($data));
		}
	
		// Optional Authentication:
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, ONFLEETAPPID);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0); 
		$result = curl_exec($curl);
		curl_close($curl);
		$phpObj = json_decode($result,true);  // Convert to PHP Object
		return $phpObj;  // Convert to PHP Array
	}
	
}
