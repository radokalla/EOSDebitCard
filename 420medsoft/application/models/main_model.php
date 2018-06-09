<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main_model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
		$this->db_subscription = $this->load->database('subscription', TRUE);
		//echo "<pre>"; print_r($this->db_subscription); exit;
		//$this->load->database();

	}
	
	public function getValuesbyTable($tablename, $cond_array)
	{
		$result_arr = array();
		$query = $this->db->get_where($tablename, $cond_array);
		foreach ($query->result_array() as $row)
		{
		   $result_arr[] = $row;
		}
		return $result_arr;

	}
	
	
	public function insertvalues($username='',$emailid ='',$password ='',$PhoneNumber ='',$from='',$to='')
	{
	 
	   $qry_res = $this->db_subscription->query("CALL md_proc_RegisterUser('{$username}', '{$password}', '{$emailid}','{$PhoneNumber}');");
	   return $qry_res->row_array();	  
	}
/*public function login1($username='',$password ='')
 {
	   $qry_result = $this->db_subscription->query("CALL md_proc_UserLogin('{$username}', '{$password}');");
	   $res = array_shift($qry_result->result_array());
	   
	   
	   if($res['MsgOut']){
		   return $res['MsgOut'];
	   }else{
		   return 0;
	   }

}*/
public function editprofile1($SubscriberID='', $firstname='',$lastname='', $address='', $PhoneNumber=''){
	
	

$qry_resu = $this->db_subscription->query("CALL md_proc_UpdateProfile('{$SubscriberID}', '{$firstname}','{$lastname}','{$address}',  '{$PhoneNumber}');");
// $this->session->set_userdata('MsgOut',$qry_resu);
//echo ($qry_resu) ? "updated":"failed";exit;
echo $this->db_subscription->last_query();
//echo "<pre>";print_r($qry_resu);
//$session_id = $this->session->userdata('$res');
$report = array();
    $report['error'] = $this->db->_error_number();
    $report['message'] = $this->db->_error_message();
    return $report;
}

public function forgotpassword($post)
 {
        $username= $post['username'];
	    $cond_array = array('email' => $username, 'status !=' => 2);		
		$this->db_subscription->where($cond_array);
		$query = $this->db_subscription->get('md_tb_Subscriber');
	 // echo $this->db_subscription->last_query(); exit;
	   
        if($query->num_rows > 0)
		 {
            $res =  $query->result_array();
			$password= substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,5);;
			$this->db_subscription->where('username', $res[0]['username']);
            $this->db_subscription->update('md_tb_Subscriber',array('password'=>md5($password)));
			return $password;
         }
	  else return false;
 }


public function changpwd($oldpassword,$newpassword){
	
	
$query = $this->db_subscription->query("select * from md_tb_Subscriber where SubscriberID ='".$this->session->userdata('id')."' and password='".$oldpassword."'");
if($query->num_rows()>0){
$qry_resu = $this->db_subscription->query("CALL md_proc_ChangePwd('{$this->session->userdata('id')}','{$newpassword}');");	
return 1;
}else{
	return 0;
 }

}
public function getcolor(){
	
	$qry_resu = $this->db_subscription->query("SELECT ColorID,ColorName FROM md_tb_Colors WHERE IsActive =1 and IsDeleted=0");
	//echo $this->db_subscription->last_query();
	
	$report=$qry_resu->result_array();
   
    return $report;

	}
	
	
	////md_proc_UpdateSitedetails
	
	
	public function UpdateSite($ColorID ='',$DomainName='',$CompanyName='',$CompanyLogo ='')
 {
 
   $qry_res = $this->db_subscription->query("CALL md_proc_UpdateSitedetails('{$this->session->userdata('id')}','{$ColorID}','{$DomainName}', '{$CompanyName}', '{$CompanyLogo}');");
 // echo $this->db_subscription->last_query();
          
  //return $qry_res->result_array();
 // exit;
 //NOTE FOR JOINS::::  SELECT sb.* ,c.ColorName FROM md_tb_SubscriptionDetails sb left join md_tb_Colors  c on  sb.ColorID=c.ColorID WHERE SubscriberID=22
  
}
public function sendmails($to,$from,$message,$subject,$fromname)
{
	// echo $to."----".$from."----".$message."----".$subject."----".$fromname;
	
	


	$this->load->library('email');
	$config['mailtype'] = 'html';
	$config['charset']  = 'utf-8';
	$this->email->initialize($config);
	//for admin
	$this->email->from($from, $fromname);
	//$this->email->to(/*$result[0]*/['email']);  
	$this->email->to($to);
	$this->email->cc('rado@bayfrontorganics.com'); 
	$this->email->bcc('nagaraju.bandi@inducosolutions.com');   
	$this->email->subject($subject);
	$this->email->message($message); 
	$this->email->set_mailtype("html");
	$result=$this->email->send();
	return $result;
}


	////////////////////////////////////////////
	////// CODE BY NAGS ////////////////////////
	////////////////////////////////////////////
	
	function getPackages()
	{
		$this->db_subscription->select('PackageID, PackageName, Description, Cost, Currency, CurrencySymbol, Duration, RecurringCost, RecurringDuration, LicenseDescription, Discounts, CreatedDate');
    	$this->db_subscription->limit(1, 0);
		$query = $this->db_subscription->get('md_tb_Packages');
		return $query->row_array();
	}	
	
	function updatePackages($post)
	{
		$cond_arr = array('PackageID' => $post['PackageID']);
		$update_arr = array('PackageName' => $post['PackageName'], 'Description' => $post['Description'], 'Cost' => $post['Cost'], 'RecurringDuration' => $post['RecurringDuration'],'RecurringCost' => $post['RecurringCost'], 'Discounts' => $post['Discounts']);
		$this->db_subscription->update('md_tb_Packages', $update_arr, $cond_arr);
	}
	
	function subscriptionSuccessful($SubscriberID, $PackageID, $Amount, $CreditCardTransID)
	{
		$sql = "CALL md_proc_confirmSubscription('{$SubscriberID}','{$PackageID}','{$Amount}', '{$CreditCardTransID}');";
		$qry_res = $this->db_subscription->query($sql);
		return true;
	}
	function updateSubscriptionExpiryDate($SubscriberID,$expiryDate)
	{
		$data = array(
               'expiryDate' => $expiryDate 
            );
        $this->db_subscription->where('SubscriberID', $SubscriberID);
		$this->db_subscription->update('md_tb_Subscriber', $data); 
	}
	
	function updateSubscriptionRecurringCost($SubscriberID,$RecurringCost)
	{
		$data = array(
               'RecurringCost' => $RecurringCost 
            );
        $this->db_subscription->where('DomainName', $SubscriberID);
		$this->db_subscription->update('md_tb_SubscriptionDetails', $data); 
	}
	function checkSubscriberAuthentication($username, $password)
	{
		$this->db_subscription->select('*');
		$this->db_subscription->where(array('email' => $username, 'password' => $password, 'status' => 1, 'expiryDate >' => date('Y-m-d')));
		$query = $this->db_subscription->get('md_tb_Subscriber');
		//echo $this->db_subscription->last_query(); exit;
		return $query->row_array();		
		
		//$qry_result = $this->db_subscription->query("CALL md_proc_UserLogin('{$username}', '{$password}');");
		//$res = $qry_result->row_array();
		//return $res;
	}
	
	function getSubscriberDetails($SubscriberID)
	{
		$this->db_subscription->select('SubscriberID, username, address, PhoneNumber, email');
		$this->db_subscription->where(array('SubscriberID' => $SubscriberID));
		$query = $this->db_subscription->get('md_tb_Subscriber');
		return $query->row_array();
	}	
	function getQuick($SubscriberID)
	{
		$this->db_subscription->select('Quickbooksmail, Quickbookspwd');
		$this->db_subscription->where(array('SubscriberID' => $SubscriberID));
		$query = $this->db_subscription->get('md_tb_SubscriptionDetails');
		return $query->row_array();
	}	
	function updateSubscriberDetails($SubscriberID, $post)
	{
		$this->db_subscription->query("CALL md_proc_UpdateProfile('{$SubscriberID}', '".$post['username']."', '".$post['address']."', '".$post['PhoneNumber']."');");
	}	
	
	function getSiteDetails($SubscriberID)
	{
		$this->db_subscription->select('SubscriptionID, ColorID, DomainName, CompanyName, CompanyLogo');
		$this->db_subscription->where(array('SubscriberID' => $SubscriberID));
		$query = $this->db_subscription->get('md_tb_SubscriptionDetails');
		return $query->row_array();
	}		
	
	function updateSiteDetails($SubscriberID, $post, $path = "")
	{
		//echo $path;exit;
		$this->db_subscription->query("CALL md_proc_UpdateSitedetails('{$SubscriberID}', '".$post['color']."', '".$post['DomainName']."', '".$path."');");
	}	
		
	
	function getAccountTypes()
	{
		$this->db_subscription->select('AccountTypeID, AccountType');
		$query = $this->db_subscription->get('md_tb_AccountTypes');
		return $query->result_array();
	}
	
	function getAccounts($SubscriberID)
	{
		$qry_result = $this->db_subscription->query("CALL md_proc_getSettingDetails('{$SubscriberID}');");
		$res = $qry_result->row_array();
		return $res;
	}
	
	function updateAccounts($SubscriberID, $post)
	{
		$qry_result = $this->db_subscription->query("CALL md_proc_UpdateSettings('{$SubscriberID}', '".$post['QBUserName']."', '".$post['QBPassword']."', '".$post['MerUserName']."', '".$post['MerPassword']."', '".$post['FleetsUserName']."', '".$post['FleetsPassword']."', '".$post['is_qb']."');");
		$res = $qry_result->row_array();
		return $res;
	}
	
	function changeSubscriberPassword($SubscriberID, $post)
	{
		$this->db_subscription->select('SubscriberID, username, address, PhoneNumber, email');
		$this->db_subscription->where(array('SubscriberID' => $SubscriberID, 'password' => md5($post['oldpassword'])));
		$query = $this->db_subscription->get('md_tb_Subscriber');
		$details = $query->row_array();
		if(isset($details['SubscriberID']))
		{
			$this->db_subscription->query("CALL md_proc_ChangePwd('{$SubscriberID}','".md5($post['confirmpassword'])."');");
			return true;
		}
		return false;
	}		
	
	
	
	function getSubscriberDetailsByDomain($domain)
	{
		/*$this->db_subscription->select('SubscriberID, username, address, PhoneNumber, email');
		$this->db_subscription->where(array('DomainName' => $domain));
		$query = $this->db_subscription->get('md_tb_Subscriber');
		return $query->row_array();*/
		
		$this->db_subscription->select('md_tb_Subscriber.SubscriberID, md_tb_Subscriber.username, md_tb_Subscriber.address, md_tb_Subscriber.PhoneNumber, md_tb_Subscriber.email, md_tb_Subscriber.expiryDate,,md_tb_SubscriptionDetails.RecurringCost,md_tb_SubscriptionDetails.DomainName');
		$this->db_subscription->from('md_tb_Subscriber');
		$this->db_subscription->join('md_tb_SubscriptionDetails', 'md_tb_SubscriptionDetails.SubscriberID = md_tb_Subscriber.SubscriberID'); 
		$this->db_subscription->where(array('DomainName' => $domain));
		$query = $this->db_subscription->get();
		return $query->row_array();
	}	
	////////////////////////////////////////////
	////// END OF CODE BY NAGS /////////////////
	////////////////////////////////////////////

}
?>