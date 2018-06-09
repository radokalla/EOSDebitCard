<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'models/main_model.php');
class Admin_model extends main_model {
	
	private $tablename = '';
	
	public function __construct()
	{
		parent::__construct();
		$this->tablename = 'admin';
	}
	
	
	public function checkAuthentication($array)
	{
			
		$cond_array = array('userName' => $array['username'], 'password' => $array['password'], 'isActive' => '1');	
		//if(isset($array['userType']) && !empty($array['userType']))
			//$cond_array ['userType'] = $array['userType'];		
		$this->db->select('ID,userName,firstName,lastName, userType');
		$this->db->where($cond_array);
		$query = $this->db->get($this->tablename);
			
		if($query->num_rows() == 1)
			return $query->row_array();
		else
			return false;
	}
	
	public function checkUsername($username)
	{
		if(!empty($username))
		{
			$this->db->select('ID');
			$this->db->where('userName', $username);
			$query = $this->db->get($this->tablename);
			if($query->num_rows() == 0)
				return true;
			else
				return false;
		}
		else
			return false;
	}
	
	public function getPartnerDetails($array)
	{
		$array['userType'] = 'partner';
		
		$this->db->select('*');		
		if(isset($array['userName']) && !empty($array['userName']))
			$this->db->like('userName', $array['userName']);			
		$this->db->where('userType', $array['userType']);			
		$this->db->order_by("ID", "DESC");
		
		if( !empty($array['recordsperpage']) && !empty($array['recordsperpage']) )
			$this->db->limit($array['recordsperpage'], $array['limit']);
		$query = $this->db->get($this->tablename);
			
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
			return false;
	}
	
	public function getIp()
	{
		$this->db->select('*');		
		$query = $this->db->get('settings');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
			return false;
	}
	
	public function updateIp($ipadress)
	{
		//echo "<pre>";print_r($ipadress);exit;
		$cond_arr = array('ID' => 1);
		$update_arr = array('ipadress' => $ipadress['ip'], 'quickbooksUser' => $ipadress['user'], 'quickbooksPwd' => $ipadress['pwd'], 'taxPercentage' => $ipadress['taxPercentage'], 'deliveryCharges' => $ipadress['deliveryCharges'], 'facebook' => $ipadress['facebook'], 'twitter' => $ipadress['twitter'], 'instagram' => $ipadress['instagram'], 'linkedin' => $ipadress['linkedin'], 'chat' => $ipadress['chat'], 'qbms_account' => $ipadress['qbms_account'], 'cc_merchant' => $ipadress['cc_merchant'], 'qb_offline' => $ipadress['qb_offline']);
		$this->db->update('settings', $update_arr, $cond_arr);
	}
	
	public function updateEmployeeStatus($employeeID, $status = 0)
	{
		$cond_arr = array('ID' => $employeeID);
		$update_arr = array('isActive' => $status);
		$this->db->update($this->tablename, $update_arr, $cond_arr);
	}
	
	public function getPartnersCount($array)
	{
		$array['userType'] = 'partner';
		
		$this->db->select('ID,userName,firstName,lastName, userType, isActive');		
		if(isset($array['userName']) && !empty($array['userName']))
			$this->db->like('userName', $array['userName']);			
		$this->db->where('userType', $array['userType']);			
		$this->db->order_by("ID", "DESC");
		
		$query = $this->db->get($this->tablename);
			
		return $query->num_rows();
	}
	
	public function getEmployeeDetails($array)
	{
		$array['userType'] = 'employee';		
		$this->db->select('*');		
		if(isset($array['userName']) && !empty($array['userName']))
			$this->db->like('userName', $array['userName']);
		$this->db->where('userType', $array['userType']);			
		$this->db->order_by("ID", "DESC");		
		if( !empty($array['recordsperpage']) && !empty($array['recordsperpage']) )
			$this->db->limit($array['recordsperpage'], $array['limit']);		
		$query = $this->db->get($this->tablename);
			
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
			return false;
	}
	
	
	public function getDetailsByID($userId, $userType = 'employee')
	{
		$this->db->select('*');		
		$this->db->where('userType', $userType);				
		$this->db->where('ID', $userId);			
		$query = $this->db->get($this->tablename);
		if($query->num_rows() == 1)
		{
			return $query->row_array();
		}
		else
			return false;
	}
	
	public function getEmployeeCount($array)
	{
		$array['userType'] = 'employee';		
		$this->db->select('ID,userName,firstName,lastName, userType');		
		if(isset($array['userName']) && !empty($array['userName']))
			$this->db->like('userName', $array['userName']);
		$this->db->where('userType', $array['userType']);			
		$this->db->order_by("ID", "DESC");				
		$query = $this->db->get($this->tablename);
			
		return $query->num_rows();
	}
	
	public function insertPartner($array)
	{
		if(!empty($array['userName']))
		{
			$insert_array = array('userName' => $array['userName'], 'password' => $array['password'], 'firstName' => $array['firstName'], 'lastName' => $array['lastName'], 'address' => $array['address'], 'city' => $array['city'], 'state' => $array['state'], 'zip' => $array['zip'], 'phone' => $array['phone'], 'tax' => $array['tax'],'userType' => 'partner');
			$this->db->insert($this->tablename, $insert_array);
			$categoryID = $this->db->insert_id();
			return $categoryID;
		}
		else
			return false;
	}
	
	public function updatePartner($array)
	{
		if(!empty($array['userName']) && !empty($array['partner_id']))
		{
			$insert_array = array('firstName' => $array['firstName'], 'lastName' => $array['lastName'], 'address' => $array['address'], 'city' => $array['city'], 'state' => $array['state'], 'zip' => $array['zip'], 'phone' => $array['phone'], 'tax' => $array['tax']);
			$cond_array = array('ID' => $array['partner_id'], 'userType' => 'partner');	
			$this->db->update($this->tablename, $insert_array, $cond_array);
			return $array['partner_id'];
		}
		else
			return false;
	}
	
	public function insertEmployee($array)
	{
		if(!empty($array['userName']))
		{
			$insert_array = array('userName' => $array['userName'], 'password' => $array['password'], 'firstName' => $array['firstName'], 'lastName' => $array['lastName'], 'address' => $array['address'], 'city' => $array['city'], 'state' => $array['state'], 'zip' => $array['zip'], 'phone' => $array['phone'], 'tax' => $array['tax'], 'userType' => 'employee', 'idcard' => $array['idcard'], 'medical' => $array['medical'], 'driverid' => $array['driver']);
			$this->db->insert($this->tablename, $insert_array);
			$categoryID = $this->db->insert_id();
			return $categoryID;
		}
		else
			return false;
	}
	
	public function updateEmployee($array)
	{
	   if(!empty($array['userName']) && !empty($array['employee_id']))
		{
			$insert_array = array('firstName' => $array['firstName'], 'lastName' => $array['lastName'], 'address' => $array['address'], 'city' => $array['city'], 'state' => $array['state'], 'zip' => $array['zip'], 'phone' => $array['phone'], 'tax' => $array['tax'], 'idcard' => $array['idcard'], 'medical' => $array['medical'], 'driverid' => $array['driver']);
			$cond_array = array('ID' => $array['employee_id'], 'userType' => 'employee');	
			$this->db->update($this->tablename, $insert_array, $cond_array);
			return $array['employee_id'];
		}
		else
			return false;
	}
	
	public function deleteEmployee($array)
	{
		$cond_array = array('ID' => $array['id']);	
		$this->db->delete($this->tablename, $cond_array);
	}
	
	public function checkEmployeeUsername($userName)
	{
		if(!empty($userName))
		{
			$cond_array = array('userName' => $userName,'userType' => 'employee');		
			$this->db->select('ID');
			$this->db->from($this->tablename);
			$this->db->where($cond_array);
			$query = $this->db->get();
			if($query->num_rows() == 0)
				return true;
			else
				return false;
		}
		else
			return false;
	}
	
	public function checkPartnerUsername($userName)
	{
		if(!empty($userName))
		{
			$cond_array = array('userName' => $userName,'userType' => 'partner');		
			$this->db->select('ID');
			$this->db->from($this->tablename);
			$this->db->where($cond_array);
			$query = $this->db->get();
			if($query->num_rows() == 0)
				return true;
			else
				return false;
		}
		else
			return false;
	}
	
	public function checkUser($id)
	{
		//echo $id;exit;
		$cond_array = array('ID' => $id);		
		$this->db->select('*');		
		$this->db->from($this->tablename);
		$this->db->where($cond_array);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
			return false;
	}
	
	public function ResetPassword()
	{
		$data=$this->input->post();
		$where=array('ID'=>$data['id']);
		$data=array('password'=>$data['password']);
		$this->db->update('admin', $data, $where);
		return	$result=1;
	}
	
	
	public function upatelogo()
	{
			
	if (($_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/png" || $_FILES["file"]["type"] == "image/pjpeg" || $_FILES["file"]["type"] == "image/gif" || $_FILES["file"]["type"] == "image/x-png") && ($_FILES["file"]["size"] < 4000000))
	{

		// some settings
		$max_upload_width = 160;
		$max_upload_height = 240;
		  
		// if user chosed properly then scale down the image according to user preferances
		 /*if(isset($_REQUEST['max_width_box']) and $_REQUEST['max_width_box']!='' and $_REQUEST['max_width_box']<=$max_upload_width){
			$max_upload_width = $_REQUEST['max_width_box'];
		}    
		if(isset($_REQUEST['max_height_box']) and $_REQUEST['max_height_box']!='' and $_REQUEST['max_height_box']<=$max_upload_height){
			$max_upload_height = $_REQUEST['max_height_box'];
		}	*/ 

		
		// if uploaded image was JPG/JPEG
		if($_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/pjpeg"){	
			$image_source = imagecreatefromjpeg($_FILES["file"]["tmp_name"]);
		}		
		// if uploaded image was GIF
		if($_FILES["file"]["type"] == "image/gif"){	
			$image_source = imagecreatefromgif($_FILES["file"]["tmp_name"]);
		}	
		// BMP doesn't seem to be supported so remove it form above image type test (reject bmps)	
		// if uploaded image was BMP
		if($_FILES["file"]["type"] == "image/bmp"){	
			$image_source = imagecreatefromwbmp($_FILES["file"]["tmp_name"]);
		}			
		// if uploaded image was PNG
		if($_FILES["file"]["type"] == "image/x-png" || $_FILES["file"]["type"] == "image/png"){
			$image_source = imagecreatefrompng($_FILES["file"]["tmp_name"]);
		}
		

		 $remote_file = getcwd().'/images/'.$_FILES["file"]["name"];
		imagejpeg($image_source,$remote_file,100);
		chmod($remote_file,0644);
	
	

		// get width and height of original image
		list($image_width, $image_height) = getimagesize($remote_file);
	
		if($image_width>$max_upload_width || $image_height >$max_upload_height){
			
			$proportions = $image_width/$image_height;
			$new_width=136;
			$new_height=38;
			
			/*if($image_width>$image_height){
				echo $new_width = $max_upload_width;
				echo "<br/>";
				echo $new_height = $max_upload_height; exit;
			}		
			else{
				echo $new_height = $max_upload_height;
				echo "<br/>";
				echo $new_width = round($max_upload_height*$proportions); exit;
			}		
			*/
			
			$new_image = imagecreatetruecolor($new_width , $new_height);
			$image_source = imagecreatefromjpeg($remote_file);
			
			imagecopyresampled($new_image, $image_source, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);
			imagejpeg($new_image,$remote_file,100);
			
			imagedestroy($new_image);
		}
		imagedestroy($image_source);
		$this->db->query('update indexText set headerlogo="'.$_FILES['file']['name'].'" where id='.$_POST['SubscriberID']);
		$msg="Successfully Submitted";
	}
	else{
		 $msg = "Invalid file";
	}
	return $msg;
	}
	public function upatefooetrlogo()
	{
		
		
		
	if (($_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/png" || $_FILES["file"]["type"] == "image/pjpeg" || $_FILES["file"]["type"] == "image/gif" || $_FILES["file"]["type"] == "image/x-png") && ($_FILES["file"]["size"] < 4000000))
	{

		// some settings
		$max_upload_width = 160;
		$max_upload_height = 240;
		  
		// if user chosed properly then scale down the image according to user preferances
		 /*if(isset($_REQUEST['max_width_box']) and $_REQUEST['max_width_box']!='' and $_REQUEST['max_width_box']<=$max_upload_width){
			$max_upload_width = $_REQUEST['max_width_box'];
		}    
		if(isset($_REQUEST['max_height_box']) and $_REQUEST['max_height_box']!='' and $_REQUEST['max_height_box']<=$max_upload_height){
			$max_upload_height = $_REQUEST['max_height_box'];
		}	*/ 

		
		// if uploaded image was JPG/JPEG
		if($_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/pjpeg"){	
			$image_source = imagecreatefromjpeg($_FILES["file"]["tmp_name"]);
		}		
		// if uploaded image was GIF
		if($_FILES["file"]["type"] == "image/gif"){	
			$image_source = imagecreatefromgif($_FILES["file"]["tmp_name"]);
		}	
		// BMP doesn't seem to be supported so remove it form above image type test (reject bmps)	
		// if uploaded image was BMP
		if($_FILES["file"]["type"] == "image/bmp"){	
			$image_source = imagecreatefromwbmp($_FILES["file"]["tmp_name"]);
		}			
		// if uploaded image was PNG
		if($_FILES["file"]["type"] == "image/x-png" || $_FILES["file"]["type"] == "image/png"){
			$image_source = imagecreatefrompng($_FILES["file"]["tmp_name"]);
		}
		

			 $remote_file = getcwd().'/images/'.$_FILES["file"]["name"];
			 imagejpeg($image_source,$remote_file,100);
			 chmod($remote_file,0644);
	
	

		// get width and height of original image
		    list($image_width, $image_height) = getimagesize($remote_file);
	
		    if($image_width>$max_upload_width || $image_height >$max_upload_height){
			
			$proportions = $image_width/$image_height;
			$new_width=136;
			$new_height=38;
			
			/*if($image_width>$image_height){
				echo $new_width = $max_upload_width;
				echo "<br/>";
				echo $new_height = $max_upload_height; exit;
			}		
			else{
				echo $new_height = $max_upload_height;
				echo "<br/>";
				echo $new_width = round($max_upload_height*$proportions); exit;
			}		
			*/
			$new_image = imagecreatetruecolor($new_width , $new_height);
			$image_source = imagecreatefromjpeg($remote_file);
			imagecopyresampled($new_image, $image_source, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);
			imagejpeg($new_image,$remote_file,100);
			imagedestroy($new_image);
		  }
			$this->db->query('update indexText set footerlogo="'.$_FILES['file']['name'].'" where id='.$_POST['SubscriberID']);
			imagedestroy($image_source);
			$msg="Successfully Submitted";
		}
		else{ 
			 $msg = "Invalid file";
		 }
		return $msg;
		
	}
	
	public function getBannerCount($array)
	{
		$this->db->select('ID,title,image');		
		$this->db->where('isDelete', '0');			
		$this->db->order_by("ID", "DESC");				
		$query = $this->db->get('banners');
			
		return $query->num_rows();
	}
	
	public function getBannerDetails($array)
	{
		$this->db->select('*');		
		$this->db->where('isDelete', '0');			
		$this->db->order_by("ID", "DESC");		
		if( !empty($array['recordsperpage']) && !empty($array['recordsperpage']) )
			$this->db->limit($array['recordsperpage'], $array['limit']);		
		$query = $this->db->get('banners');
			
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
			return false;
	}
	public function insertBanner($array)
	{
		if(!empty($array['title']))
		{
			$insert_array = array('title' => $array['title'], 'image' => $array['banner'],'banner_url' => $array['banner_url'] );			
			$this->db->insert('banners', $insert_array);
			$categoryID = $this->db->insert_id();
			return $categoryID;
		}
		else
			return false;
	}
	
	public function updateBanner($array)
	{
	   if(!empty($array['title']) && !empty($array['banner_id']))
		{
			$insert_array = array('title' => $array['title'], 'image' => $array['banner'],'banner_url' => $array['banner_url']);
			$cond_array = array('ID' => $array['banner_id'],'isDelete' => '0');	
			$this->db->update('banners', $insert_array, $cond_array);
			return $array['banner_id'];
		}
		else
			return false;
	}
	
	public function deleteBanner($array)
	{
		$cond_array = array('ID' => $array['id']);	
		$this->db->delete('banners', $cond_array);
	}
	
	public function getBannerDetailsByID($Id)
	{
		$this->db->select('*');		
		$this->db->where('ID', $Id);	
		$this->db->where('isDelete', '0');		
		$query = $this->db->get('banners');
		if($query->num_rows() == 1)
		{
			return $query->row_array();
		}
		else
			return false;
	}
	
	public function getiIndexText()
	{
		$this->db->select('*');		
		$query = $this->db->get('indexText');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
			return false;
	}
	
	public function updateText($text)
	{
		//echo "<pre>";print_r($ipadress);exit;
		$cond_arr = array('ID' => 1);
		$update_arr = array('firstTitle' => $text['firstTitle'], 'firstDesc' => $text['firstDesc'], 'secondTitle' => $text['secondTitle']);
		$this->db->update('indexText', $update_arr, $cond_arr);
	}
	
	//////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////
	
	public function getDrivers($userId = '', $userType = 'employee')
	{
		$this->db->select('*');		
		$this->db->where('userType', $userType);
		if(!empty($userId))
			$this->db->where('ID !=', $userId);		
		$this->db->where(array('driverid IS NOT NULL' => NULL));		
		$query = $this->db->get($this->tablename);
		
		if($query->num_rows() > 0)
		{
			$return_array = array();
			$result = $query->result_array();
			foreach($result as $res)
				$return_array[] = $res['driverid'];
			return $return_array;
		}
		else
			return array();
	}
	
	public function getDriverByDriverid($Driverid = '', $userType = 'employee')
	{
		$this->db->select('*');		
		$this->db->where('userType', $userType);
		$this->db->where('driverid', $Driverid);
		$this->db->where(array('driverid IS NOT NULL' => NULL));		
		$query = $this->db->get($this->tablename);
		
		if($query->num_rows() == 1)
		{
			return $query->row_array();
		}
		else
			return array();
	}
	
}
?>