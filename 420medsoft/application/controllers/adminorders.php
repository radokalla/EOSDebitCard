<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



require_once(APPPATH.'controllers/admin.php');

class Adminorders extends Admin {

	

	private $recordsperpage;

	

	public function __construct()

	{

		parent::__construct();

		$this->isLoggedIn();

		$this->recordsperpage = 50;

		$this->data['session'] = $this->session->all_userdata();
$this->load->model('admin_model');	
		$settings = $this->admin_model->getIp();	
		$this->qb_offline=$settings[0]['qb_offline'];
		$this->data['qb_offline']=$settings[0]['qb_offline'];
	}

	

	public function orders($recordsperpage = 50, $currentPage=0)

	{
		$this->load->library('mobiledetect');
		$this->data['deviceType'] = ($this->mobiledetect->isMobile() ? ($this->mobiledetect->isTablet() ? 'tablet' : 'phone') : 'computer');
		
		$this->recordsperpage = $recordsperpage;

		$this->load->helper('form');

		$condition_array = array();

		if($this->input->post())

		{

			$post = $this->input->post();

			if(isset($post['orderID']) && !empty($post['orderID']))

				$condition_array['orderID'] = $post['orderID'];

			

			if(isset($post['patientName']) && !empty($post['patientName']))

				$condition_array['patientName'] = $post['patientName'];		

			

			if(isset($post['orderStatus']) && (!empty($post['orderStatus']) || ($post['orderStatus'] == 0)))

				$condition_array['orderStatus'] = $post['orderStatus'];	

			

			if(isset($post['deliveryType']) && !empty($post['deliveryType']))

				$condition_array['deliveryType'] = $post['deliveryType'];			

			
			
			if(isset($post['orderBy']) && !empty($post['orderBy']))
				$condition_array['orderBy'] = $post['orderBy'];			
			
			if(isset($post['from_orderDate']) && !empty($post['from_orderDate']))
				$condition_array['from_orderDate'] = $post['from_orderDate'];
			
			if(isset($post['to_orderDate']) && !empty($post['to_orderDate']))
				$condition_array['to_orderDate'] = $post['to_orderDate'];

				

			if(isset($post['recordPerPage']) && !empty($post['recordPerPage']))

				$this->recordsperpage = $post['recordPerPage'];

		}

		

		$condition_array['limit'] = $currentPage;

		$condition_array['recordsperpage'] = $this->recordsperpage;



		$this->load->model('orders_model');
		$this->data['ordersStatus'] = $this->orders_model->getStatus();
		$this->data['ordersCount'] = $this->orders_model->getAllSearchOrdersCount($condition_array);
		$this->data['ordersDetails'] = $this->orders_model->getAllSearchOrders($condition_array, true);
		$this->data['recordsperpage'] = $this->recordsperpage;

		

		$this->load->library('pagination');

		$config['uri_segment'] = 4;

		$config['base_url'] = base_url('index.php/adminorders/orders/'.$this->recordsperpage.'/');

		$config['total_rows'] = $this->data['ordersCount'];

		$config['per_page'] = $this->recordsperpage; 

		

		$this->pagination->initialize($config); 		

		$this->data['paginationLinks'] = $this->pagination->create_links();

		

		$this->load->view('admin/includes/header', $this->data);

		$this->load->view('admin/orders/orders', $this->data);

		$this->load->view('admin/includes/footer', $this->data);

	}

	
	function getOrderDetails()
	{
		if($this->isAjaxRequest())
		{//ini_set("display_errors",1);
			$this->load->helper('form');
			if($this->input->post('orderID'))
			{
				$orderID = $this->input->post('orderID');	
				$this->load->model('orders_model');
				$this->data['ordersDetails'] = $this->orders_model->getOrdersByOrderID($orderID);
 				$this->data['ordersDetails'] = $this->data['ordersDetails'][$orderID];
 				$this->data['ordersStatus'] = $this->orders_model->getStatus();
				$onfleet=$this->input->post('onfleet');
				//echo "https://onfleet.com/api/v2/teams/".ONFLEETTEAMID;
				if($onfleet)
				{
					$this->data['onfleet']['flag']=1;
					$this->data['onfleet']['driverdetail']=$this->CallAPI("GET", "https://onfleet.com/api/v2/teams/".ONFLEETTEAMID, "");
					//$this->data['onfleet']['driverdetail']=$this->CallAPI("GET", "https://onfleet.com/api/v2/workers/".ONFLEETTEAMID, "");
			 foreach($this->data['onfleet']['driverdetail']['workers'] as  $result)
					{
						//echo $result ."<br/>";
						$this->data['onfleet']['driverdetail']['details'][]=$this->CallAPI("GET", "https://onfleet.com/api/v2/workers/".$result, "");
					} 
				}
				   
				 //echo "<pre>";
				// print_r($this->data['onfleet']['driverdetail']['details']);exit;
				$this->load->view('admin/orders/details', $this->data);
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
	
	public function details($orderID = '')

	{

		if(empty($orderID))

		{

			redirect(base_url('index.php/orders')); exit;

		}

		

		$this->load->model('orders_model');

		$this->data['ordersDetails'] = $this->orders_model->getOrdersByOrderID($orderID);

		

		

		

		if(!$this->data['ordersDetails'])

		{

			redirect(base_url('index.php/adminorders/orders')); exit;

		}

		else

			$this->data['ordersDetails'] = $this->data['ordersDetails'][$orderID];

		

		//echo "<pre>"; print_r($this->data['ordersDetails']); exit;

		$this->data['ordersStatus'] = $this->orders_model->getStatus();

		

		$this->load->view('admin/includes/header', $this->data);

		$this->load->view('admin/orders/details', $this->data);

		$this->load->view('admin/includes/footer', $this->data);

	}

	

	function updateOrderStaus()

	{

		if($this->isAjaxRequest())

		{

			$this->load->helper('form');

			if($this->input->post())

			{

				$post = $this->input->post();

				if( isset($post['orderID']) && !empty($post['orderID']) )

				{

					$this->load->model('orders_model');		

					$this->orders_model->UpdateOrderStatus($post['orderID'], $post['status']);

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

	
	
	public function setorder($orderID = "")
	{
		if(isset($orderID) && !empty($orderID))
		{
			$this->load->model('orders_model');		
			$orderDetails = $this->orders_model->getOrdersByOrderID($orderID);
			if(isset($orderDetails[$orderID]['productDetails']))
			{
				$this->emptycart();
				$this->session->unset_userdata('ADMIN_ORDER_ID');
				$this->data['session']['ADMIN_ORDER_ID'] = $orderID;
				foreach($orderDetails[$orderID]['productDetails'] as $productDetails)
				{
					$this->load->model('categories_model');	
					$cart_product = $this->categories_model->getProductByProductID($productDetails['productID']);
					$quantity = ($cart_product['parentID'] == BOGOCATEGORYID) ? 2 : 1;
					$productKey = $cart_product['parentID'].':'.$cart_product['categoryID'].':'.$cart_product['productID'];
					if($cart_product)
					{
						$product_details = array('categotyName' => $cart_product['categoryName'], 'productName' => $cart_product['productName'], 'QBcode' => $cart_product['QBcode'], 'quantity' => $productDetails['quantity'], 'productPrice' => ($cart_product['price']/$quantity));
						$this->data['session']['adminCart'][$productKey] = $product_details;
					}
				}
				$this->session->set_userdata($this->data['session']);
			}
		}
		redirect(base_url('index.php/adminorders/createorder')); exit;
	}
	
	
	function createorder($orderID = "")
	{

		$this->load->helper('form');

		$this->load->model('patient_model');
		$this->load->model('orders_model');
		//echo  $ISQB;exit;
		if($this->input->post('patientID'))
		{
			
			$total_price = 0;

			$deliveryType = $this->input->post('deliveryType');

			$paymentType = $this->input->post('paymentType');

			$paymentType = ($paymentType != 'cash' ) ?  'creditcard' : 'cash';

			foreach($this->data['session']['adminCart'] as $cart_key => $product)

			{

				$cart_item_ids = explode(":",$cart_key);	

				$orderProductDetails[] = array("categotyID" => $cart_item_ids[0], "subCategotyID" => $cart_item_ids[1], "productID" => $cart_item_ids[2], "subCategotyName" => $product['categotyName'], "productName" => $product['productName'], "QBcode" => $product['QBcode'], "quantity" => $product['quantity'], "productPrice" => $product['productPrice']);

				$total_price += ($product['quantity']*$product['productPrice']);

			}

			$tax_amount=$total_price*($this->data['tax_percentage']/100);

			$total_price += $tax_amount;

			$delivery_charge = 0;

			if($deliveryType != 'pickup' )

				 $delivery_charge = $this->data['delivery_charge'];

			$total_price += $delivery_charge;

			$this->data['patientDetails'] = $this->patient_model->getAdminPatientDetails($this->input->post('patientID'));

			
			$this->load->library('quickbooks');	
			if(isset($this->data['session']['ADMIN_ORDER_ID']))
			{
				$orderDetails = $this->orders_model->getAllSearchOrders( array('orderID' => $this->data['session']['ADMIN_ORDER_ID']));
				$this->data['orderDetails'] = $orderDetails[$this->data['session']['ADMIN_ORDER_ID']];
				$order_details = true;
			    if($this->qb_offline)
				{
				$order_details = $this->quickbooks->invoiceGeneration($orderProductDetails, $this->data['patientDetails']['QBCodeID'], $total_price, $deliveryType, $this->data['tax_percentage'], $tax_amount, $delivery_charge, ($paymentType != 'cash' ) ? true : false, $this->data['orderDetails']['invoiceNumber']);
				}
			}
			else
			{
				$order_details = $this->quickbooks->invoiceGeneration($orderProductDetails, $this->data['patientDetails']['QBCodeID'], $total_price, $deliveryType, $this->data['tax_percentage'], $tax_amount, $delivery_charge, ($paymentType != 'cash' ) ? true : false, '');
			}
			
			if($order_details)

			{
				
				$this->orders_model->addOrder($orderProductDetails, $this->input->post('patientID'), $total_price, $deliveryType, '', $this->data['tax_percentage'], $tax_amount, $this->data['session']['LOGIN_TYPE'], $this->data['session']['ADMIN_ID'], $paymentType,  $delivery_charge, $order_details, $this->data['session']['ADMIN_ORDER_ID']);
					

				$this->emptycart();
				$this->session->unset_userdata('ADMIN_ORDER_ID');
				$this->data['session'] = $this->session->all_userdata();
				echo 1;exit;
				//redirect(base_url('index.php/adminorders/orders')); exit;
			}
			else
			{
				echo 0;exit;
			}

		}
		
		if(isset($orderID) && !empty($orderID))
		{
			$orderDetails = $this->orders_model->getAllSearchOrders( array('orderID' => $orderID,'orderStatus' => 5));
			if(isset($orderDetails[$orderID]))
			{
				$this->session->set_userdata(array('ADMIN_ORDER_ID' =>$orderID ));
				$this->data['session'] = $this->session->all_userdata();
			}
		}
		
		if(isset($this->data['session']['ADMIN_ORDER_ID']))
		{
			$orderDetails = $this->orders_model->getAllSearchOrders( array('orderID' => $this->data['session']['ADMIN_ORDER_ID']));
			$this->data['orderDetails'] = $orderDetails[$this->data['session']['ADMIN_ORDER_ID']];	
		}
		
		$this->load->model('patient_model');		

		$patientDetails = $this->patient_model->getAllPatientDetails();

		$currentPatientDetails = array();

		foreach($patientDetails as $patient)

		{

			$currentPatientDetails[] = array('patientID' => $patient['patientID'], 'firstName' => $patient['firstName']. ' '. $patient['lastName']);

		}

		$this->data['patientDetails'] = $currentPatientDetails;

		$this->load->model('categories_model');		

		$this->data['categories'] = $this->categories_model->getAllCategories(array('isActive' => 1));//getMainCategories();

		

		$this->load->view('admin/includes/header', $this->data);

		$this->load->view('admin/orders/createorder', $this->data);

		$this->load->view('admin/includes/footer', $this->data);

	}

	

	function getAllSubcategories()

	{

		if($this->isAjaxRequest())

		{

			$this->load->helper('form');

			if($this->input->post('parentID'))

			{

				$category_id = $this->input->post('parentID');

				$this->load->model('categories_model');		

				$this->data['options'] = $this->categories_model->getOptions();

				$this->data['categories'] = $this->categories_model->getSubCategoriesByParentID($category_id);

				$this->data['currentCategory'] = $category_id;

				$this->load->view('admin/orders/getallsubcategories', $this->data);

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

	
	
	function getAllSubcategoriesWithSearch()
	{
		if($this->isAjaxRequest())
		{
			$this->load->helper('form');
			if($this->input->post('parentID'))
			{
				$category_id = $this->input->post('parentID');
				$categoryName = $this->input->post('categoryName');
				$productType = $this->input->post('productType');
				$this->load->model('categories_model');		
				$this->data['options'] = $this->categories_model->getOptions();
				$this->data['categories'] = $this->categories_model->getSubCategoriesByParentID($category_id, true, $productType, $categoryName);
				$this->data['currentCategory'] = $category_id;
				$this->load->view('admin/orders/getallsubcategories', $this->data);
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
					$quantity = ($post['maincategory'] == 96) ? 2 : 1;
					$productKey = $post['maincategory'].':'.$post['category'].':'.$post['product'];

					if(isset($this->data['session']['adminCart'][$productKey]))

					{

						$this->data['session']['adminCart'][$productKey]['quantity'] = $this->data['session']['adminCart'][$productKey]['quantity'] + $quantity;

					}

					else

					{

						$this->load->model('categories_model');		

						$cart_product = $this->categories_model->getProductByProductID($post['product']);

						if($cart_product)

						{

							$product_details = array('categotyName' => $cart_product['categoryName'], 'productName' => $cart_product['productName'], 'QBcode' => $cart_product['QBcode'], 'quantity' => $quantity, 'productPrice' => ($cart_product['price']/$quantity));

							$this->data['session']['adminCart'][$productKey] = $product_details;

						}

					}

					$this->session->set_userdata($this->data['session']);

				}

				$this->load->view('admin/orders/cart', $this->data);

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

					if(isset($this->data['session']['adminCart'][$productKey]))

					{

						unset($this->data['session']['adminCart'][$productKey]);

					}

					$this->session->set_userdata($this->data['session']);

				}

				$this->load->view('admin/orders/cart', $this->data);

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

		$this->session->unset_userdata('adminCart');

		$this->data['session'] = $this->session->all_userdata();		

	}

	

	public function getPatientDetails()

	{

		if($this->isAjaxRequest())

		{

			$this->load->model('patient_model');		

			$patientDetails = $this->patient_model->getAllPatientDetails(array('patientName' => $_REQUEST['name_startsWith']));

			$currentPatientDetails = array();

			foreach($patientDetails as $patient)

			{
				
				 $now = time(); // or your date as well
				$your_date = strtotime($patient['expiryDate']);
				$datediff = $your_date - $now;
				$days = floor($datediff/(60*60*24));
				$html = '';
				if($days <= 30 && $days > 0){ 
					$html = '<div class="error">Medical license will expired in '.$days.' day(s).</div>';
				}else if($days <= 0){ 
					$html = '<div class="error">Medical license expired.</div>';
				} 
     
     
				array_push($currentPatientDetails, array('patientID' => $patient['patientID'], 'patientName' => $patient['firstName']. ' '. $patient['lastName'] . ', '. $patient['address1'] . ' '. $patient['address2'] . ' '. $patient['city'] . ' '. $patient['state'] . ' '. $patient['zip'], 'patientNotes' => $patient['notes'], 'message' => $html));
			}

			echo json_encode($currentPatientDetails);

		}

	}

	

	public function getCategoryDetails()

	{

		if($this->isAjaxRequest())

		{

			$this->load->model('categories_model');		

			$patientDetails = $this->categories_model->getAllCategories(array('categoryName' => $_REQUEST['name_startsWith']));

			$currentPatientDetails = array();

			foreach($patientDetails as $categoryID => $patient)

			{

				array_push($currentPatientDetails, array('categoryID' => $categoryID, 'categoryName' => $patient['categoryName']));

			}

			echo json_encode($currentPatientDetails);

		}

	}

	public function samplecart($id)

	{

		$this->load->helper('form');

		$this->load->model('categories_model');

		$this->load->model('categories_model');		

		$cart_product = $this->categories_model->getProductByProductID($id);
		
		$quantity = ($cart_product['parentID'] == BOGOCATEGORYID) ? 2 : 1;
		
		$productKey = $cart_product['parentID'].':'.$cart_product['categoryID'].':'.$cart_product['productID'];

		if(isset($this->data['session']['adminCart'][$productKey]))

		{

			$this->data['session']['adminCart'][$productKey]['quantity'] = $this->data['session']['adminCart'][$productKey]['quantity'] + $quantity;

		}

		else if($cart_product)

		{

			$product_details = array('categotyName' => $cart_product['categoryName'], 'productName' => $cart_product['productName'], 'QBcode' => $cart_product['QBcode'], 'quantity' => $quantity, 'productPrice' => ($cart_product['price']/$quantity));

			$this->data['session']['adminCart'][$productKey] = $product_details;

		}

		$this->session->set_userdata($this->data['session']);

		$this->load->view('admin/includes/header', $this->data);

		$this->load->view('admin/orders/createqrorder', $this->data);

		$this->load->view('admin/includes/footer', $this->data);

	}

	

	public function deleteorder($orderID = '')

	{

		$this->load->model('orders_model');	

		

		if(!empty($orderID))

			$this->data['order'] = $this->orders_model->deleteOrder(array('orderID' => $orderID));

			

		redirect('adminorders/orders'); 

		exit;

	}
	  
  
  
  /////////////////////////////////////////////////////////////////////
  /////////////////////////////////////////////////////////////////////
  
  
  public function orderqueue()
  {
		$this->load->library('mobiledetect');
		$this->data['deviceType'] = ($this->mobiledetect->isMobile() ? ($this->mobiledetect->isTablet() ? 'tablet' : 'phone') : 'computer');
		
		
		$this->load->helper('form');
		$condition_array = array();
		if($this->input->post())
		{
			$post = $this->input->post();
			if(isset($post['orderID']) && !empty($post['orderID']))
				$condition_array['orderID'] = $post['orderID'];
			
			if(isset($post['patientName']) && !empty($post['patientName']))
				$condition_array['patientName'] = $post['patientName'];		
			
			if(isset($post['orderDate']) && !empty($post['orderDate']))
				$condition_array['orderDate'] = $post['orderDate'];	
				
			if(isset($post['recordPerPage']) && !empty($post['recordPerPage']))
				$this->recordsperpage = $post['recordPerPage'];
		}
		$condition_array['counterID'] = "";
		$this->load->model('orders_model');
		$this->data['queueCounters'] = $this->orders_model->getCounters();
		$this->data['ordersDetails'] = $this->orders_model->getOrdersQueue($condition_array);
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/orders/ordersqueue', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	}
	
	/*public function addPatientInQueue($patientID)
	{
		if(!empty($patientID))
		{
			$this->load->model('patient_model');
			$data['patientDetails'] = $this->patient_model->getAdminPatientDetails($patientID);
			if(isset($data['patientDetails']['patientID']) && !empty($data['patientDetails']['patientID']))
			{
				$this->load->model('orders_model');		
				$this->orders_model->addPatientInQueue($patientID, $this->data['session']['ADMIN_ID'], $this->data['session']['LOGIN_TYPE']);
				redirect(base_url('index.php/adminorders/thankyou')); exit;
			}
		}
		redirect(base_url('index.php/admin/index')); exit;
	}*/
	
	public function addorderqueue()
	{
		
		if($this->input->post('patientID'))
		{
			$patientID = $this->input->post('patientID');
			$this->load->model('orders_model');			
			$this->orders_model->addPatientInQueue($patientID, $this->data['session']['ADMIN_ID'], $this->data['session']['LOGIN_TYPE']);
			redirect(base_url('index.php/adminorders/orderqueue')); exit;
		}
		
		$this->load->model('patient_model');		
		$patientDetails = $this->patient_model->getAllPatientDetails();
		$currentPatientDetails = array();
		foreach($patientDetails as $patient)
		{
			$currentPatientDetails[] = array('patientID' => $patient['patientID'], 'firstName' => $patient['firstName']. ' '. $patient['lastName']);
		}
		$this->data['patientDetails'] = $currentPatientDetails;
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/orders/addorderqueue', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	}
	
	public function assignCounter()
	{
		if($this->input->post('queueID'))
		{
			$queueID = $this->input->post('queueID');
			$counterID = $this->input->post('counterID');
			$this->load->model('orders_model');			
			$this->orders_model->assignCounter($queueID, $counterID);
		}
		redirect(base_url('index.php/adminorders/orderqueue')); exit;
	}
	
	public function counterdetails()
    {
		$this->load->helper('form');
		$condition_array = array();
		if($this->input->post())
		{
			$post = $this->input->post();
			if(isset($post['orderID']) && !empty($post['orderID']))
				$condition_array['orderID'] = $post['orderID'];
			
			if(isset($post['patientName']) && !empty($post['patientName']))
				$condition_array['patientName'] = $post['patientName'];		
			
			if(isset($post['orderDate']) && !empty($post['orderDate']))
				$condition_array['orderDate'] = $post['orderDate'];	
				
			if(isset($post['recordPerPage']) && !empty($post['recordPerPage']))
				$this->recordsperpage = $post['recordPerPage'];
		}
		$this->load->model('orders_model');
		$this->data['counterDetails'] = $this->orders_model->getCounterPatientDetails($condition_array);
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/orders/counterdetails', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	 }
	//////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////
  
  
  
  	public function taskAssign()
	{
		
		$post = $this->input->post();
		
		$this->load->library('logs');
		$destinationresult=array();
		$recipients=array();
		$taskarray=array();
		$name=$_POST['name'];
		$phone=str_replace(array('-','(',')',' ','+'),'',$_POST['phone']);
 		//$phone="$number[0]$number[1]$number[2]-$number[3]$number[4]$number[5]-$number[6]$number[7]$number[8]$number[9]";
		$recipients= $this->CallAPI("GET", "https://onfleet.com/api/v2/recipients/phone/".$phone, "");
		if(isset($recipients['id']))
		{
 			$recipientid=$recipients['id'];
		}
		else
		{
			//adding recipient
 			$recipientarray=array("name"=>$_POST['name'],"phone"=>$phone,"notes"=>"Adding Recipient $name");
			$recipients=$this->addRecipient($recipientarray);
			
		}
 		// adding desitination
	    if(isset($recipients['id']))
		{$recipientid=$recipients['id'];
		$address=(!empty($_POST['address']) &&  ($_POST['address'] != "none")) ?  $_POST["address"]: '1';
		$destinationarray=array("address"=>array("number"=>$address,"street"=>$_POST['street'],"city"=>$_POST['city'],"postalCode"=>$_POST['postalCode'],"country"=>'US'),"notes"=>"adding desitination");
		//echo "<pre>"; print_r($destinationarray);
		 $destinationresult=$this->addDestination($destinationarray);
		 }
		 else 
		 {
 			$this->logs->write_log("recipients", $this->convert_multi_array($recipients));
 			 echo json_encode($recipients['message']['message']);
			 exit;
		 }
		 
 
 		//Adding Task;
		if(isset($destinationresult['id']))
		{
			$worker=explode("_",$_POST['worker']);
		$workerid=$worker[0];
			$destinationid=$destinationresult['id'];
		   $taskearray=array("merchant"=>$_POST['merchant'],"executor"=>$_POST['merchant'],"destination"=>$destinationid,"recipients"=>array($recipientid),"notes"=>$_POST['notes'],'worker'=>$workerid);
		$taskresult= $this->addTask($taskearray);
		
		}
		 else 
		 {
			 $this->logs->write_log("destinationresult",  $this->convert_multi_array($destinationarray));
 			 $this->logs->write_log("destinationresult",  $this->convert_multi_array($destinationresult));
			
 			echo json_encode($destinationresult['message']['message']);
		 	exit;
		 }
		 
		/*if(isset($taskresult['id']))
		{$taskid=$taskresult['id'];
		$worker=explode("_",$_POST['worker']);
		$workerid=$worker[0];
		//$assigntaskarray=array("name"=>$_POST['workername'],"teams"=>array($worker[1]),"tasks"=>array($taskid));
		$assigntaskarray=array("worker"=>$workerid);
		//$this->updateworkertotask($workerid,$assigntaskarray);
		$this->updateworkertotask($taskid,$assigntaskarray);
		}
		else 
		 {
 			$this->logs->write_log("destinationresult", $this->convert_multi_array($taskresult));
 		    echo json_encode($taskresult['message']['message']);exit;
		 }*/
		 
		//removing invetory from Driver
		$this->load->model('orders_model');
		$this->load->model('admin_model');
		$this->data['orderDetails'] = $this->orders_model->getOrdersByOrderID($post['orderID']);
		if($this->data['orderDetails'])
		{
			$this->data['orderDetails'] = array_shift($this->data['orderDetails']);
			$driverDetails = $this->admin_model->getDriverByDriverid($post['worker']);
			
			if($driverDetails)
			{
				$details = array('createdBy' => $driverDetails['ID'], 'createdType' => 'EMPLOYEE', 'createdByOld' => $this->data['orderDetails']['createdBy'], 'createdTypeOld' => $this->data['orderDetails']['createdType']);
				$this->orders_model->UpdateOrderDriver($post['orderID'], $details);
			}
			else
			{
				$details = array('createdBy' => 69, 'createdType' => 'PARTNER', 'createdByOld' => $this->data['orderDetails']['createdBy'], 'createdTypeOld' => $this->data['orderDetails']['createdType']);
				$this->orders_model->UpdateOrderDriver($post['orderID'], $details);
			}
		}
		 
		echo 1; 
		exit;
  	}
	public function addRecipient($data)
	{
		$results= $this->CallAPI("POST", "https://onfleet.com/api/v2/recipients", $data);
		/*echo "<pre><br/>addRecipient";
		print_r($data);
		print_r($results);*/
		return $results;
 	}
	public function addDestination($data)
	{
 		 	$results=$this->CallAPI("POST", "https://onfleet.com/api/v2/destinations", $data);
		/* echo "<pre><br/>addDestination";
		print_r($data);
		print_r($results);*/
		return $results;
 	}
	public function addTask($data)
	{
	    	$results= $this->CallAPI("POST", "https://onfleet.com/api/v2/tasks", $data);
	 /* echo "<pre><br/>addTask";
		print_r($data);
		print_r($results);*/
		return $results;
	}
	public function updateworkertotask($workerid,$data)
	{
		 $results= $this->CallAPI("put", "https://onfleet.com/api/v2/tasks/".$workerid, $data);
	/*	echo "<pre><br/>updateworkertotask";
		print_r($data);
		print_r($results);*/
		return $results;
	}
	
	public function convert_multi_array($array)
	{
	 	$str="";
		foreach($array as $k=>$i)
		{
			if(is_array($i))
			{
				$str.=$this->convert_multi_array($i);
			}
			else
			{
				$str.=$k."=>".$i;
			}
		}
		return $str;
	}
	
	public function getTeams($data)
	{
		$results= $this->CallAPI("GET", "https://onfleet.com/api/v2/teams");
		echo "<br/>getTeams<pre>";
		print_r($results); exit;
		return $results;
 	}
  
	
	public function thankyou()
	{
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/orders/counterdetails', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	}
  
}

