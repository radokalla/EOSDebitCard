<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'models/main_model.php');
class Backend_model extends main_model {
	
	private $tablename = '';
	
	public function __construct()
	{
		parent::__construct();
		$this->db_subscription = $this->load->database('subscription', TRUE);
	}
	
	
	public function checkAuthentication($array)
	{
			
		$cond_array = array('UserName' => $array['username'], 'Password' => md5($array['password']));	
		$this->db_subscription->select('ID,UserName');
		$this->db_subscription->where($cond_array);
		$query = $this->db_subscription->get('md_tb_Admin');
					
		if($query->num_rows() == 1)
			return $query->row_array();
		else
			return false;
	}
	
	public function getSubscriberCount($array)
	{
		$this->db_subscription->select('SubscriberID, username,address,PhoneNumber,email,created');		
		if(isset($array['userName']) && !empty($array['userName']))
			$this->db_subscription->like('username', $array['userName']);
		if(isset($array['IsNewUser']) && !empty($array['IsNewUser']))
			$this->db_subscription->where('IsNewUser', $array['IsNewUser']);
		if(isset($array['status']))
			$this->db_subscription->where('status', $array['status']);
		
		$this->db_subscription->where('status !=', 2);
		$this->db_subscription->order_by("SubscriberID", "DESC");				
		$query = $this->db_subscription->get('md_tb_Subscriber');
			
		return $query->num_rows();
	}
	
	public function getSubscriberDetails($array)
	{
		$this->db_subscription->select('SubscriberID, username, address, PhoneNumber, email, status, IsApproved, created');		
		if(isset($array['userName']) && !empty($array['userName']))
			$this->db_subscription->like('username', $array['userName']);
		if(isset($array['IsNewUser']) && !empty($array['IsNewUser']))
			$this->db_subscription->where('IsNewUser', $array['IsNewUser']);
		if(isset($array['status']))
			$this->db_subscription->where('status', $array['status']);
		
		$this->db_subscription->where('status !=', 2);
		$this->db_subscription->order_by("SubscriberID", "DESC");		
		if( !empty($array['recordsperpage']) && !empty($array['recordsperpage']) )
			$this->db_subscription->limit($array['recordsperpage'], $array['limit']);		
		$query = $this->db_subscription->get('md_tb_Subscriber');
			
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
			return false;
	}
	
	public function getsubscriberBySubscriberID($SubscriberID)
	{
		$sql = "SELECT 
sub.SubscriberID, sub.username, sub.address, sub.PhoneNumber, sub.email, sub.expiryDate, sub.status, sub.IsApproved, subdetails.ColorID, subdetails.PackageID, subdetails.DomainName, subdetails.CompanyName, subdetails.CompanyLogo, subdetails.PackageName,subdetails.Quickbooks,subdetails.Trackingsoftware, subdetails.Description, subdetails.Cost, subdetails.Currency, subdetails.CurrencySymbol, subdetails.Duration, subdetails.RecurringCost, subdetails.RecurringDuration, subdetails.LicenseDescription, subdetails.Discounts
FROM md_tb_Subscriber AS sub
LEFT JOIN md_tb_SubscriptionDetails AS subdetails ON sub.SubscriberID = subdetails.SubscriberID

 WHERE sub.status != 2 AND sub.SubscriberID = '".$SubscriberID."'";	//LEFT JOIN md_tb_Packages AS pkg ON pkg.PackageID = subdetails.PackageID
		$query = $this->db_subscription->query($sql);
			
		if($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
			return false;
	}
	
	
	public function updateSubscriberStatus($SubscriberID, $status = 0)
	{
		$cond_arr = array('SubscriberID' => $SubscriberID);
		$update_arr = array('status' => $status);
		$this->db_subscription->update('md_tb_Subscriber', $update_arr, $cond_arr);
	}
	
	public function updateSubscriberToOldUser()
	{
		$cond_arr = array('IsNewUser' => 1);
		$update_arr = array('IsNewUser' => 0);
		$this->db_subscription->update('md_tb_Subscriber', $update_arr, $cond_arr);
	}
	
	public function getSubscribersCount()
	{
		$sql  = " SELECT 'newuser' AS userType, count(*) as userscount FROM `md_tb_Subscriber` WHERE `IsNewUser` = 1 ";
		$sql .= " UNION ALL ";
		$sql .= " SELECT 'activeuser', count(*) as userscount FROM `md_tb_Subscriber` WHERE `status` = 1 ";
		$sql .= " UNION ALL ";
		$sql .= " SELECT 'inactiveuser', count(*) as userscount FROM `md_tb_Subscriber` WHERE `status` = 0 ";	
		$query = $this->db_subscription->query($sql);
			
		if($query->num_rows() > 0)
		{
			$return_users = array();
			$users = $query->result_array();
			foreach($users as $user)
			{
				$return_users[$user['userType']] = $user['userscount'];
			}
			return $return_users;
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
		$this->db_subscription->update('indexText', $update_arr, $cond_arr);
	}
		public function getlogos()
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
	public function insertsubscrib()
	{
		$post = $this->input->post();
		$id=$post['SubscriberID'];
		$currentdate=date('Y-m-d H:i:s');
		if($id)
		{
			$where = array('SubscriberID'=>$id); 
			$firstsub=array('username'=>$post['name'],'email'=>$post['email'],'address'=>$post['address'],'PhoneNumber'=>$post['PhoneNumber'],'expiryDate'=>$post['expiryDate']);
			
			$this->db_subscription->update('md_tb_Subscriber', $firstsub, $where);
			$whrese = array('SubscriberID'=>$id);
			$addsub=array('DomainName'=>$post['DomainName'],'ColorID'=>$post['ColorID'],'Quickbooks'=>$post['Quickbooks'],'Trackingsoftware'=>$post['Trackingsoftware'],'PackageName'=>$post['PackageName'],'DomainName'=>$post['DomainName'],'CompanyLogo'=>$post['CompanyLogo'],'ModifiedDate'=>$currentdate);
			$this->db_subscription->update('md_tb_SubscriptionDetails', $addsub, $whrese);
	
		}
		else{
			$pasword=md5($post['password']);
			$firstsubr = array('username'=>$post['name'],'email'=>$post['email'],'password'=>$pasword,'address'=>$post['address'],'PhoneNumber'=>$post['PhoneNumber'],'status'=>1);
			$this->db_subscription->insert('md_tb_Subscriber', $firstsubr);
			$insert_id = $this->db_subscription->insert_id();
			$addsubins=array('SubscriberID'=>$insert_id,'DomainName'=>$post['DomainName'],'ColorID'=>$post['ColorID'],'Quickbooks'=>$post['Quickbooks'],'Trackingsoftware'=>$post['Trackingsoftware'],'PackageName'=>$post['PackageName'],'DomainName'=>$post['DomainName'],'CompanyLogo'=>$_FILES['file']['name'],'CreatedDate'=>$currentdate);
			$this->db_subscription->insert('md_tb_SubscriptionDetails',$addsubins);
		}


		if (($_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/pjpeg" || $_FILES["file"]["type"] == "image/gif" || $_FILES["file"]["type"] == "image/x-png") && ($_FILES["file"]["size"] < 4000000))
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
		if($_FILES["file"]["type"] == "image/x-png"){
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
		
		$msg="Successfully Submitted";
	}
	else{
		 $msg = "Invalid file";
	}
		
		
		
		
	}
	
	public function getAccounts($SubscriberID)
	{
		$qry_result = $this->db_subscription->query("CALL md_proc_getSettingDetails('{$SubscriberID}');");
		$res = $qry_result->row_array();
		return $res;
	}
	
	
	
	public function salesrp()
	{	
		$query=$this->db_subscription->query('SELECT CreatedDate AS date,count(CreatedDate) as noofpersons,IFNULL(SUM(Cost),0) AS total_sales FROM md_tb_SubscriptionDetails WHERE CreatedDate BETWEEN (SELECT MIN(DATE(CreatedDate)) FROM md_tb_SubscriptionDetails) AND (SELECT MAX(DATE(CreatedDate)) FROM md_tb_SubscriptionDetails) GROUP BY Month(date)');
		$resultmon = $query->result_array();
		
		$query=$this->db_subscription->query('SELECT CreatedDate AS date,count(CreatedDate) as noofpersons,IFNULL(SUM(Cost),0) AS total_sales FROM md_tb_SubscriptionDetails WHERE CreatedDate BETWEEN (SELECT MIN(DATE(CreatedDate)) FROM md_tb_SubscriptionDetails) AND (SELECT MAX(DATE(CreatedDate)) FROM md_tb_SubscriptionDetails) GROUP BY WEEK(date)');
		$result = $query->result_array();
		$query=$this->db_subscription->query('SELECT CreatedDate AS date,count(CreatedDate) as noofpersons,IFNULL(SUM(Cost),0) AS total_sales FROM md_tb_SubscriptionDetails WHERE CreatedDate BETWEEN (SELECT MIN(DATE(CreatedDate)) FROM md_tb_SubscriptionDetails) AND (SELECT MAX(DATE(CreatedDate)) FROM md_tb_SubscriptionDetails) GROUP BY YEAR(date)');
		$results = $query->result_array();
		$arr=$results[0];
		$arr['month']=$resultmon[0];
		$arr['month']['week']=$result;
		return $arr; 


	}
	
		public function salesreport()
	{	
		$query=$this->db_subscription->query('SELECT ms.username as name,msd.PackageName,msd.CreatedDate AS date,msd.Cost,ms.status FROM md_tb_Subscriber as ms  INNER JOIN  md_tb_SubscriptionDetails as msd  on ms.SubscriberID=msd.SubscriberID');
		$resultmon = $query->result_array();
		return $resultmon; 


	}
		
	public function savePayments($subscriber_id, $data)
	{
		$this->db->insert('md_tb_payments', array('subscriber_id' => $subscriber_id, 'data' => $data));
	}
		
}
?>