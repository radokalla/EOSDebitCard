<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



require_once(APPPATH.'models/main_model.php');

class Patient_model extends main_model {

	

	private $tablename = '';

	private $patientcarddetails_tablename = '';

	private $patientdetails_tablename = '';

	private $options_tablename = '';	

	private $category_options_tablename = '';

	

	public function __construct()

	{

		parent::__construct();

		$this->tablename = 'patient';

		$this->patientcarddetails_tablename = 'patientcarddetails';

		$this->patientdetails_tablename = 'patientdetails';

		$this->categories_tablename = 'categories';

		$this->category_options_tablename = 'categoryoptions';

	}

	

	public function checkPatientUsername($userName)

	{

		if(!empty($userName))

		{

			$cond_array = array('userName' => $userName, 'isDeleted' => 0);		

			$this->db->select('patientID');

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

	

	public function patientRegistration($post)

	{

		if($this->checkPatientUsername($post['userName']))

		{

			$insert_patient_array = array('userName' => $post['userName'], 'password' => sha1($post['password']), 'userType' => 'patient', 'isActive' => '1', 'isDeleted' => '0', 'isRead' => '0' );

			$this->db->insert($this->tablename, $insert_patient_array);

			$patientID = $this->db->insert_id();

			if($patientID)

			{

				$insert_patientdetails_array = array('patientID' => $patientID, 'QBCodeID' => $post['QBCodeID'], 'firstName' => $post['firstName'], 'lastName' => $post['lastName'], 'doctorName' => $post['doctorName'], 'address1' => $post['address1'], 'address2' => $post['address2'], 'city' => $post['city'], 'state' => $post['state'], 'country' => $post['country'], 'zip' => $post['zip'], 'phone' => $post['phone'], 'email' => $post['email'], 'idcard' => $post['idcard'], 'medical' => $post['medical'], 'expiryDate' => $post['expiryDate'] );

				$this->db->insert($this->patientdetails_tablename, $insert_patientdetails_array);

				 

				//$insert_patientcarddetails_array = array('patientID' => $patientID, 'nameOnCard' => $post['nameOnCard'], 'cardType' => $post['cardType'], 'cardNumber' => $post['cardNumber'], 'CVVCode' => $post['CVVCode'], 'expiry' => $post['expiry']);

				//$this->db->insert($this->patientcarddetails_tablename, $insert_patientcarddetails_array);

				

				return array('patientID' => $patientID, 'firstName' => $post['firstName'], 'lastName' => $post['lastName']);

			}

			else

				return false;

		}

		else

			return false;

	}	

	

	public function addPatientCreditCardDetails($patientID, $post)

	{

		

		if($this->getPatientCreditCardDetails())

		{

			$insert_patientcarddetails_array = array('nameOnCard' => $post['nameOnCard'], 'cardType' => '', 'cardNumber' => $post['cardNumber'], 'CVVCode' => $post['CVVCode'], 'expiry_year' => $post['expiry_year'], 'expiry_month' => $post['expiry_month']);

		

			$this->db->update($this->patientcarddetails_tablename, $insert_patientcarddetails_array, array('patientID' => $patientID) );

		}

		else

		{

			$insert_patientcarddetails_array = array('patientID' => $patientID, 'nameOnCard' => $post['nameOnCard'], 'cardType' => '', 'cardNumber' => $post['cardNumber'], 'CVVCode' => $post['CVVCode'], 'expiry_year' => $post['expiry_year'], 'expiry_month' => $post['expiry_month']);

		

			$this->db->insert($this->patientcarddetails_tablename, $insert_patientcarddetails_array);

		}

		return true;

	}

	public function getUnreadPatients()
	{
		$cond_array = array('isRead' => '0', 'isDeleted' => '0');		

		$this->db->select('count(patientID)');

		$this->db->from($this->tablename .' as patient');

		$this->db->where($cond_array);

		$query = $this->db->get();
		$res = $query->result_array();
		return $res;
	}
	

	public function updatePatientDetails($post)

	{

		//echo "<pre>";print_r($post);exit;

		$patientID = $this->session->userdata('PATIENT_ID');

		if(!empty($patientID))

		{

			$cond_array = array('patientID' => $patientID);

			$update_patientdetails_array = array( 'firstName' => $post['firstName'], 'lastName' => $post['lastName'], 'doctorName' => $post['doctorName'], 'address1' => $post['address1'], 'address2' => $post['address2'], 'city' => $post['city'], 'state' => $post['state'], 'country' => $post['country'], 'zip' => $post['zip'], 'phone' => $post['phone'], 'email' => $post['email'], 'idcard'=>$post['idcard'], 'medical'=>$post['medical'] );

			$this->db->update($this->patientdetails_tablename, $update_patientdetails_array, $cond_array);


			$update_patientcarddetails_array = array('nameOnCard' => $post['nameOnCard'], 'cardType' => $post['cardType'], 'cardNumber' => $post['cardNumber'], 'CVVCode' => $post['CVVCode'], 'expiry_year' => $post['expiry_year'], 'expiry_month' => $post['expiry_month']);

			$this->db->update($this->patientcarddetails_tablename, $update_patientcarddetails_array, $cond_array);

			return true;

		}

		else

			return false;

	}

	

	public function updateAdminPatientDetails($post, $patientID = '')

	{

		//echo "<pre>";print_r($post);

		if(!empty($patientID))

		{

			$cond_array = array('patientID' => $patientID);

			$update_patientdetails_array = array( 'firstName' => $post['firstName'], 'lastName' => $post['lastName'], 'doctorName' => $post['doctorName'], 'address1' => $post['address1'], 'address2' => $post['address2'], 'city' => $post['city'], 'state' => $post['state'], 'country' => $post['country'], 'zip' => $post['zip'], 'phone' => $post['phone'], 'email' => $post['email'], 'idcard'=>$post['idcard'], 'medical'=>$post['medical'], 'expiryDate'=>$post['expiryDate'], 'notes'=>$post['notes'] );

			$this->db->update($this->patientdetails_tablename, $update_patientdetails_array, $cond_array);

			

			if(isset($post['nameOnCard']))

			{

				$update_patientcarddetails_array = array('nameOnCard' => $post['nameOnCard'], 'cardType' => $post['cardType'], 'cardNumber' => $post['cardNumber'], 'CVVCode' => $post['CVVCode'], 'expiry_year' => $post['expiry_year'], 'expiry_month' => $post['expiry_month']);

				$this->db->update($this->patientcarddetails_tablename, $update_patientcarddetails_array, $cond_array);

			}
			
			
			$update_patient_array = array('isRead' => 1);
			
			$this->db->update($this->tablename,$update_patient_array,$cond_array);

			return true;

		}

		else 

		{

			$patient=array('userName'=>$post['userName'], 'password'=>md5($post['password']), 'userType'=>'patient');

			$this->db->insert('patient', $patient);

			$patientID=$this->db->insert_id();

			$update_patientdetails_array = array('patientID'=>$patientID, 'QBCodeID' => $post['QBCodeID'], 'firstName' => $post['firstName'], 'lastName' => $post['lastName'], 'doctorName' => $post['doctorName'], 'address1' => $post['address1'], 'address2' => $post['address2'], 'city' => $post['city'], 'state' => $post['state'], 'country' => $post['country'], 'zip' => $post['zip'], 'phone' => $post['phone'], 'email' => $post['email'], 'idcard'=>$post['idcard'], 'medical'=>$post['medical'], 'expiryDate'=>$post['expiryDate'], 'notes'=>$post['notes'] );

			$this->db->insert($this->patientdetails_tablename, $update_patientdetails_array);

			

			$this->db->select('email');

			$this->db->from('admin');

			$this->db->where(array('userType'=>'admin'));

			$admin=$this->db->get();

			$admin=$admin->result_array();

			$from=$admin[0]['email'];

			$to=$post['email'];

			$this->email($from,$to,'Patient Registration Mail','User Name is:  '.$post['userName'].' .</br> Password is:  '.$post['password'].' .','AUTOMATED ADMIN');

/*			 

			$update_patientcarddetails_array = array('nameOnCard' => $post['nameOnCard'], 'cardType' => $post['cardType'], 'cardNumber' => $post['cardNumber'], 'CVVCode' => $post['CVVCode'], 'expiry' => $post['expiry']);

			$this->db->insert($this->patientcarddetails_tablename, $update_patientcarddetails_array);

			*/

			return true;

		}

	}

	

	public function getPatientDetails()

	{

		$patientID = $this->session->userdata('PATIENT_ID');

		if(!empty($patientID))

		{

			$cond_array = array('patient.patientID' => $patientID, 'patient.userType' => 'patient', 'patient.isDeleted' => '0', 'patient.isActive' => '1');		

			$this->db->select('patientdetails.patientID, patientdetails.QBCodeID, patientdetails.firstName, patientdetails.lastName, patientdetails.doctorName, patientdetails.address1, patientdetails.address2, patientdetails.city, patientdetails.state, patientdetails.country, patientdetails.zip, patientdetails.phone, patientdetails.email, patient.userName,patientdetails.idcard,patientdetails.medical,patientdetails.expiryDate,patientdetails.notes');

			$this->db->from($this->tablename .' as patient');

			$this->db->join($this->patientdetails_tablename .' as patientdetails', 'patient.patientID = patientdetails.patientID', 'inner');

			$this->db->where($cond_array);

			$query = $this->db->get();

			if($query->num_rows() == 1)

				return $query->row_array();

			else

				return false;

		}

		else

			return false;

	}

	

	public function getPatientCreditCardDetails()

	{

		$patientID = $this->session->userdata('PATIENT_ID');

		if(!empty($patientID))

		{

			$cond_array = array('patient.patientID' => $patientID, 'patient.userType' => 'patient', 'patient.isDeleted' => '0', 'patient.isActive' => '1');		

			$this->db->select('patientcarddetails.patientID, patientcarddetails.nameOnCard, patientcarddetails.cardType, patientcarddetails.cardNumber, patientcarddetails.CVVCode, patientcarddetails.expiry_year, patientcarddetails.expiry_month'); 

			$this->db->from($this->tablename .' as patient');

			$this->db->join($this->patientcarddetails_tablename .' as patientcarddetails', 'patient.patientID = patientcarddetails.patientID', 'inner');

			$this->db->where($cond_array);

			$query = $this->db->get();

			if($query->num_rows() == 1)

				return $query->row_array();

			else

				return false;

		}

		else

			return false;

	}

	

	public function getAdminPatientDetails($patientID = '')

	{

		if(!empty($patientID))

		{

			$cond_array = array('patient.patientID' => $patientID, 'patient.userType' => 'patient', 'patient.isDeleted' => '0');		

			$this->db->select('patientdetails.patientID, patientdetails.QBCodeID, patientdetails.firstName, patientdetails.lastName, patientdetails.doctorName, patientdetails.address1, patientdetails.address2, patientdetails.city, patientdetails.state, patientdetails.country, patientdetails.zip, patientdetails.phone, patientdetails.email, patient.userName,patientdetails.idcard,patientdetails.medical,patientdetails.expiryDate,patientdetails.notes');

			$this->db->from($this->tablename .' as patient');

			$this->db->join($this->patientdetails_tablename .' as patientdetails', 'patient.patientID = patientdetails.patientID', 'inner');

			$this->db->where($cond_array);

			$query = $this->db->get();

			if($query->num_rows() == 1)

				return $query->row_array();

			else

				return false;

		}

		else

			return false;

	}

	

	public function getAdminPatientCreditCardDetails($patientID = '')

	{

		if(!empty($patientID))

		{

			$cond_array = array('patient.patientID' => $patientID, 'patient.userType' => 'patient', 'patient.isDeleted' => '0', 'patient.isActive' => '1');		

			$this->db->select('patientcarddetails.patientID, patientcarddetails.nameOnCard, patientcarddetails.cardType, patientcarddetails.cardNumber, patientcarddetails.CVVCode, patientcarddetails.expiry_month, patientcarddetails.expiry_year');

			$this->db->from($this->tablename .' as patient');

			$this->db->join($this->patientcarddetails_tablename .' as patientcarddetails', 'patient.patientID = patientcarddetails.patientID', 'inner');

			$this->db->where($cond_array);

			$query = $this->db->get();

			if($query->num_rows() == 1)

				return $query->row_array();

			else

				return false;

		}

		else

			return false;

	}

	

	

	public function checkLogin($post)

	{

		if($post)

		{

			$cond_array = array('patient.userName' => $post['userName'], 'patient.password' => sha1($post['password']), 'patient.userType' => 'patient', 'patient.isDeleted' => '0', 'patient.isActive' => '1');		

			$this->db->select('patientdetails.patientID, patientdetails.firstName, patientdetails.lastName');

			$this->db->from($this->tablename .' as patient');

			$this->db->join($this->patientdetails_tablename .' as patientdetails', 'patient.patientID = patientdetails.patientID', 'inner');

			$this->db->where($cond_array);

			$query = $this->db->get();

			if($query->num_rows() == 1)

				return $query->row_array();

			else

				return false;

		}

		else

			return false;

	}

	

	public function getAllPatientDetails($cond_array = array(),$unid="")

	{

		$this->db->select('patientdetails.patientID, patientdetails.firstName, patientdetails.lastName, patientdetails.doctorName, patientdetails.address1, patientdetails.address2, patientdetails.city, patientdetails.state, patientdetails.country, patientdetails.zip, patientdetails.phone, patientdetails.email, patient.userName, patient.isActive,patientdetails.idcard,patientdetails.medical,patientdetails.notes,patientdetails.expiryDate');

		$this->db->from($this->tablename .' as patient');

		$this->db->join($this->patientdetails_tablename .' as patientdetails', 'patient.patientID = patientdetails.patientID', 'inner');

		$this->db->where('patient.userType', 'patient');

		$this->db->where('patient.isDeleted', '0');
		
		if($unid)
			$this->db->where('patient.isRead', '0');

		if(isset($cond_array['patientName']) && !empty($cond_array['patientName']))

			$this->db->like('CONCAT(patientdetails.firstName, " ", patientdetails.lastName)', $cond_array['patientName']);

		

		if(isset($cond_array['userName']) && !empty($cond_array['userName']))

			$this->db->like('patient.userName', $cond_array['userName']);

		

		if(isset($cond_array['emailID']) && !empty($cond_array['emailID']))

			$this->db->like('patientdetails.email', $cond_array['emailID']);

			

		if(isset($cond_array['phone']) && !empty($cond_array['phone']))

			$this->db->like('patientdetails.phone', $cond_array['phone']);

			

		if(isset($cond_array['Status']) && (!empty($cond_array['Status']) || ($cond_array['Status'] == 0)))

			$this->db->where('patient.isActive', $cond_array['Status']);			

		

		$this->db->order_by("patient.patientID", "DESC");

		if(isset($cond_array['recordsperpage']) && isset($cond_array['limit']))

		$this->db->limit($cond_array['recordsperpage'], $cond_array['limit']);

			

		$query = $this->db->get();

		//echo $this->db->last_query(); exit;

		if($query->num_rows() > 0)

			return $query->result_array();

		else

			return false;

	}

	

	public function getAllPatientDetailsCount($cond_array = array(),$unid="")

	{

		$this->db->select('patientdetails.patientID, patientdetails.firstName, patientdetails.lastName, patientdetails.address1, patientdetails.address2, patientdetails.city, patientdetails.state, patientdetails.country, patientdetails.zip, patientdetails.phone, patientdetails.email, patient.userName, patient.isActive,patientdetails.idcard,patientdetails.medical');

		$this->db->from($this->tablename .' as patient');

		$this->db->join($this->patientdetails_tablename .' as patientdetails', 'patient.patientID = patientdetails.patientID', 'inner');

		$this->db->where('patient.userType', 'patient');

		$this->db->where('patient.isDeleted', '0');
		
		
		if($unid)
			$this->db->where('patient.isRead', '0');

		if(isset($cond_array['patientName']) && !empty($cond_array['patientName']))

			$this->db->like('CONCAT(patientdetails.firstName, " ", patientdetails.lastName)', $cond_array['patientName']);

		

		if(isset($cond_array['userName']) && !empty($cond_array['userName']))

			$this->db->like('patient.userName', $cond_array['userName']);

		

		if(isset($cond_array['emailID']) && !empty($cond_array['emailID']))

			$this->db->like('patientdetails.email', $cond_array['emailID']);

			

		if(isset($cond_array['phone']) && !empty($cond_array['phone']))

			$this->db->like('patientdetails.phone', $cond_array['phone']);

			

		if(isset($cond_array['Status']) && (!empty($cond_array['Status']) || ($cond_array['Status'] == 0)))

			$this->db->where('patient.isActive', $cond_array['Status']);			

			

		$query = $this->db->get();
		//echo $query->num_rows();exit;
		return $query->num_rows();

	}

	

	public function UpdatePatientStatus($patientID, $status = 0)

	{

		$cond_arr = array('patientID' => $patientID);

		$update_arr = array('isActive' => $status);

		$this->db->update($this->tablename, $update_arr, $cond_arr);

	}

	

	public function ForgotPassword()

	{

		$data=$this->input->post();

		//echo "<pre>";print_r($data);exit;

		$this->db->select('patientID,email');

		$this->db->from('patientdetails');

		$this->db->where(array('email'=>$data['emailid']));

		$id1=$this->db->get();

		$id1=$id1->result_array();

		$id=$id1[0]['patientID'];

		$email=$id1[0]['email'];

		//$result=array('error'=>1,'msg'=>"Invalid Email Id");

		$result=0;

		if($id){

			$this->db->select('email');

			$this->db->from('admin');

			$this->db->where(array('userType'=>'admin'));

			$admin=$this->db->get();

			$admin=$admin->result_array();

			$from=$admin[0]['email'];

			$to=$email;

			$where=array('patientID'=>$id);

			/*$letters=range("a","z");

			$cletters=range('A','Z');

			$num=range(0,9);

			$randomnum=$letters[array_rand($letters)].$num[array_rand($num)].$cletters[array_rand($letters)].$num[array_rand($num)].$cletters[array_rand($letters)].$num[array_rand($num)].$letters[array_rand($letters)].$cletters[array_rand($num)];

			$randomnum=trim($randomnum);

			$data=array('password'=>sha1($randomnum));

			$this->db->update('patient', $data, $where);*/

			$this->email($from,$to,'Password reset link','To reset your password Please click on this link:  '.base_url("index.php/members/resetpwd?setval=").base64_encode($id).' .','AUTOMATED ADMIN');

			$result=1;

		}

		return $result;

	}

	

	public function ResetPassword()

	{

		$data=$this->input->post();

		$where=array('patientID'=>$data['id']);

		$data=array('password'=>sha1($data['password']));

		$this->db->update('patient', $data, $where);

		return	$result=1;

	}

	

	public function email($from,$to,$subject,$message,$name)

	{

		//echo $from.'<br>'.$to.'<br>'; 

		$headers  = 'MIME-Version: 1.0' . "\r\n";

		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

		$headers .= 'From: '.$name.' <'.$from.'>' . "\r\n";

		$ans=mail($to,$subject,$message,$headers);

		//echo (int)$ans; exit;

	}

	

	public function deletepatient($array)

	{

		if(!empty($array))

		{

			$insert_array = array('isDeleted' => '1' );

			$cond_array = array('patientID' => $array['patientID']);

			$this->db->update($this->tablename, $insert_array, $cond_array);

			return $array['patientID'];

		}

		else

			return false;

	}

	

	public function getBanner()

	{

		$this->db->select('*');	

		$this->db->where('isDelete', '0');		

		$query = $this->db->get('banners');

		if($query->num_rows() > 0)

		{

			return $query->result_array();

		}

		else

			return false;

	}

	

	public function getText()

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
	public function getlogos()
	{
		//$this->db_bayfrontdb->query('select * from indexText1 where id=1');
		//$rows = $query->result_array();
		$this->db->select('*');	

		$this->db->where('id', '1');		

		$query = $this->db->get('indexText');
		return $result = $query->result_array();
	}

	

}







?>