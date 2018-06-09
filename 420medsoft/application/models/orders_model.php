<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'models/main_model.php');
class Orders_model extends main_model {
	
	private $tablename = '';
	private $order_details_tablename = '';	
	private $order_status_tablename = '';	
	private $patient_details_tablename = '';	
	public function __construct()
	{
		parent::__construct();
		$this->tablename = 'orderheader';
		$this->order_details_tablename = 'orderdetails';
		$this->order_status_tablename = 'orderstatus';
		$this->patient_details_tablename = 'patientdetails';
		$this->patient_tablename = 'patient';
	}
	
	public function addOrder($orderProductDetails, $patientID, $totalPrice, $deliveryType = 'delivery', $CreditCardTransID = '', $tax_percentage = 0, $tax_amount = 0, $createdType = '', $createdBy = '', $paymentType = 'cash', $delivery_charge = 0, $invoice_number = 0, $orderID = 0)
	{
		if(empty($createdBy))
		{
			$createdType = 'patient';
			$createdBy = $patientID;
		}
		
		if($createdType == 'patient')
		{
			$status = ($deliveryType == 'delivery') ? 3 : 2;
		}
		else
		{
			$status = 4;
		}
		
		$orderHeader = array('patientID' => $patientID,'totalPrice' => $totalPrice,'tax_percentage' => $tax_percentage,'tax_amount' => $tax_amount,'deliveryType' => $deliveryType,'status' => 0, 'CreditCardTransID' => $CreditCardTransID, 'createdType' => $createdType, 'createdBy' => $createdBy, 'paymentType' => $paymentType, 'status' => $status, 'deliveryCharge' => $delivery_charge, 'invoiceNumber' =>  $invoice_number, 'created' => date('Y-m-d H:i:s'));
		if(!empty($orderID))
		{
			$cond_array = array('patientID' => $patientID, 'orderID' => $orderID);
			unset($orderHeader['created']);
			$this->db->update($this->tablename, $orderHeader, $cond_array);
			$this->db->delete($this->order_details_tablename, array('orderID' => $orderID));
		}
		else
		{
			$this->db->insert($this->tablename, $orderHeader);
			$orderID = $this->db->insert_id();
		}
		
		
		if($orderID)
		{
			foreach($orderProductDetails as $ProductDetail)
			{
				$ProductDetail['orderID'] = $orderID;
				$this->db->insert($this->order_details_tablename, $ProductDetail);
			}
		}
		else
			return false;
		return true;
	}
	
	public function getStatus()
	{
		$this->db->select('orderstatus.*');
		$this->db->from($this->order_status_tablename);
		$this->db->order_by("orderStatusOrder", "ASC");
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$return_order_status = array();
			$order_status = $query->result_array();
			foreach($order_status as $order_status_details)
			{
				$return_order_status[$order_status_details['orderStatusID']] = $order_status_details['orderStatusText'];
			}
			return $return_order_status;
		}
		else
			return false;
	}
	
	public function getOrders($patientID = '', $orderID = '')
	{
		if(!empty($patientID))
		{
			$cond_array = array('orderheader.patientID' => $patientID,'orderheader.isDeleted'=>0);	
			if(!empty($orderID))
				$cond_array['orderheader.orderID'] =  $orderID;		
			$this->db->select('orderheader.*,orderdetails.*');
			$this->db->from($this->tablename .' as orderheader');
			$this->db->join($this->order_details_tablename .' as orderdetails', 'orderheader.orderID = orderdetails.orderID', 'inner');
			$this->db->where($cond_array);
			$this->db->order_by("orderheader.created", "DESC");
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				$return_orders = array();
				$orders = $query->result_array();
				foreach($orders as $orderDetails)
				{
					$return_orders[$orderDetails['orderID']]['totalPrice'] = $orderDetails['totalPrice'];
					$return_orders[$orderDetails['orderID']]['tax_percentage'] = $orderDetails['tax_percentage'];
					$return_orders[$orderDetails['orderID']]['tax_amount'] = $orderDetails['tax_amount'];
					$return_orders[$orderDetails['orderID']]['status'] = $orderDetails['status'];
					$return_orders[$orderDetails['orderID']]['deliveryType'] = $orderDetails['deliveryType'];
					$return_orders[$orderDetails['orderID']]['deliveryCharge'] = $orderDetails['deliveryCharge'];
					$return_orders[$orderDetails['orderID']]['paymentType'] = $orderDetails['paymentType'];
					$return_orders[$orderDetails['orderID']]['createdBy'] = $orderDetails['createdBy'];
					$return_orders[$orderDetails['orderID']]['createdType'] = $orderDetails['createdType'];
					$return_orders[$orderDetails['orderID']]['created'] = $orderDetails['created'];
					$return_orders[$orderDetails['orderID']]['productDetails'][] = array('categotyID' => $orderDetails['categotyID'], 'subCategotyID' => $orderDetails['subCategotyID'], 'subCategotyName' => $orderDetails['subCategotyName'], 'productID' => $orderDetails['productID'], 'productName' => $orderDetails['productName'], 'productPrice' => $orderDetails['productPrice'], 'quantity' => $orderDetails['quantity']);
				}
				return $return_orders;
			}
			else
				return false;
		}
		else
			return false;
	}
	
	public function getOrdersByOrderID($orderID = '')
	{
		if(!empty($orderID))
		{
			/*$cond_array['orderheader.orderID'] =  $orderID;		
			$cond_array['orderheader.isDeleted'] = 0;
			$this->db->select('orderheader.*,orderdetails.*, patientdetails.firstName, patientdetails.lastName, patientdetails.doctorName,  patientdetails.address1, patientdetails.address2, patientdetails.city, patientdetails.state, patientdetails.zip, patientdetails.phone, patientdetails.email,CONCAT(vw_ProductDetails.productName," ",vw_ProductDetails.categoryName," ",vw_ProductDetails.productLable)  as product',false);
			$this->db->from($this->tablename .' as orderheader');
			$this->db->join($this->order_details_tablename .' as orderdetails', 'orderheader.orderID = orderdetails.orderID', 'inner');
			$this->db->join($this->patient_details_tablename .' as patientdetails', 'patientdetails.patientID = orderheader.patientID', 'inner');
			$this->db->join('vw_ProductDetails' .' as vw_ProductDetails', 'vw_ProductDetails.categoryID = orderdetails.subCategotyID and vw_ProductDetails.productID=orderdetails.productID', 'inner');
			$this->db->where($cond_array);
			$this->db->order_by("orderheader.created", "DESC");*/
			$query = $this->db->query("call bay_proc_getOrderDetailsbyOrderId(".$orderID.")");
			//$query = $this->db->get();
			
			
			if($query->num_rows() > 0)
			{
				$return_orders = array();
				$orders = $query->result_array();
			 $query->next_result();
$query->free_result();
			 	foreach($orders as $orderDetails)
				{
					$return_orders[$orderDetails['orderID']]['orderID'] = $orderDetails['orderID'];
					$return_orders[$orderDetails['orderID']]['totalPrice'] = $orderDetails['totalPrice'];
 					$return_orders[$orderDetails['orderID']]['invoiceNumber'] = $orderDetails['invoiceNumber'];
					$return_orders[$orderDetails['orderID']]['tax_percentage'] = $orderDetails['tax_percentage'];
					$return_orders[$orderDetails['orderID']]['tax_amount'] = $orderDetails['tax_amount'];
					$return_orders[$orderDetails['orderID']]['status'] = $orderDetails['status'];
					$return_orders[$orderDetails['orderID']]['deliveryType'] = $orderDetails['deliveryType'];
					$return_orders[$orderDetails['orderID']]['deliveryCharge'] = $orderDetails['deliveryCharge'];
					$return_orders[$orderDetails['orderID']]['paymentType'] = $orderDetails['paymentType'];
					$return_orders[$orderDetails['orderID']]['created'] = $orderDetails['created'];
					$return_orders[$orderDetails['orderID']]['createdBy'] = $orderDetails['createdBy'];
					$return_orders[$orderDetails['orderID']]['createdType'] = $orderDetails['createdType'];
					$return_orders[$orderDetails['orderID']]['productDetails'][] = array('categotyID' => $orderDetails['categotyID'], 'subCategotyID' => $orderDetails['subCategotyID'], 'subCategotyName' => $orderDetails['subCategotyName'], 'productID' => $orderDetails['productID'], 'productName' => $orderDetails['productName'], 'productPrice' => $orderDetails['productPrice'], 'quantity' => $orderDetails['quantity'], 'QBcode' => $orderDetails['QBcode']);
				$return_orders[$orderDetails['orderID']]['patientDetails'] = array('firstName' => $orderDetails['firstName'], 'lastName' => $orderDetails['lastName'], 'doctorName' => $orderDetails['doctorName'],  'address1' => $orderDetails['address1'], 'address2' => $orderDetails['address2'], 'city' => $orderDetails['city'], 'state' => $orderDetails['state'], 'zip' => $orderDetails['zip'], 'phone' => $orderDetails['phone'], 'email' => $orderDetails['email']);
				}
				return $return_orders;
			}
			else
				return false;
		}
		else
			return false;
	}
	
	public function getOrdersByOrderID_store($orderID = '')
	{
		if(!empty($orderID))
		{
			/*$cond_array['orderheader.orderID'] =  $orderID;		
			$cond_array['orderheader.isDeleted'] = 0;
			$this->db->select('orderheader.*,orderdetails.*, patientdetails.firstName, patientdetails.lastName, patientdetails.doctorName,  patientdetails.address1, patientdetails.address2, patientdetails.city, patientdetails.state, patientdetails.zip, patientdetails.phone, patientdetails.email,CONCAT(vw_ProductDetails.productName," ",vw_ProductDetails.categoryName," ",vw_ProductDetails.productLable)  as product',false);
			$this->db->from($this->tablename .' as orderheader');
			$this->db->join($this->order_details_tablename .' as orderdetails', 'orderheader.orderID = orderdetails.orderID', 'inner');
			$this->db->join($this->patient_details_tablename .' as patientdetails', 'patientdetails.patientID = orderheader.patientID', 'inner');
			$this->db->join('vw_ProductDetails' .' as vw_ProductDetails', 'vw_ProductDetails.categoryID = orderdetails.subCategotyID and vw_ProductDetails.productID=orderdetails.productID', 'inner');
			$this->db->where($cond_array);
			$this->db->order_by("orderheader.created", "DESC");*/
			$query = $this->db->query("call bay_proc_getOrderDetailsbyOrderId('".$orderID."')");
			//$query = $this->db->get();
			
			
			if($query->num_rows() > 0)
			{
				$return_orders = array();
				$orders = $query->result_array();
			 	$query->next_result();
				$query->free_result();
			 	foreach($orders as $orderDetails)
				{
					$return_orders[$orderDetails['orderID']]['orderID'] = $orderDetails['orderID'];
					$return_orders[$orderDetails['orderID']]['totalPrice'] = $orderDetails['totalPrice'];
 					$return_orders[$orderDetails['orderID']]['invoiceNumber'] = $orderDetails['invoiceNumber'];
					$return_orders[$orderDetails['orderID']]['tax_percentage'] = $orderDetails['tax_percentage'];
					$return_orders[$orderDetails['orderID']]['tax_amount'] = $orderDetails['tax_amount'];
					$return_orders[$orderDetails['orderID']]['status'] = $orderDetails['status'];
					$return_orders[$orderDetails['orderID']]['deliveryType'] = $orderDetails['deliveryType'];
					$return_orders[$orderDetails['orderID']]['deliveryCharge'] = $orderDetails['deliveryCharge'];
					$return_orders[$orderDetails['orderID']]['paymentType'] = $orderDetails['paymentType'];
					$return_orders[$orderDetails['orderID']]['created'] = $orderDetails['created'];
					$return_orders[$orderDetails['orderID']]['createdBy'] = $orderDetails['createdBy'];
					$return_orders[$orderDetails['orderID']]['createdType'] = $orderDetails['createdType'];
					$return_orders[$orderDetails['orderID']]['productDetails'][] = array('categotyID' => $orderDetails['categotyID'], 'subCategotyID' => $orderDetails['subCategotyID'], 'subCategotyName' => $orderDetails['subCategotyName'], 'productID' => $orderDetails['productID'], 'productName' => $orderDetails['productName'], 'productPrice' => $orderDetails['productPrice'], 'quantity' => $orderDetails['quantity'], 'QBcode' => $orderDetails['QBcode'], 'product' => $orderDetails['product']);
					
				$return_orders[$orderDetails['orderID']]['patientDetails'] = array('firstName' => $orderDetails['firstName'], 'lastName' => $orderDetails['lastName'], 'doctorName' => $orderDetails['doctorName'],  'address1' => $orderDetails['address1'], 'address2' => $orderDetails['address2'], 'city' => $orderDetails['city'], 'state' => $orderDetails['state'], 'zip' => $orderDetails['zip'], 'phone' => $orderDetails['phone'], 'email' => $orderDetails['email']);
				}
				return $return_orders;
			}
			else
				return false;
		}
		else
			return false;
	}	
	
	
	public function getAllOrders( $orderID = '')
	{
		$cond_array = array();
		if(!empty($orderID))
			$cond_array['orderheader.orderID'] =  $orderID;		
		$this->db->select('orderheader.*,orderdetails.*,patientdetails.firstName,patientdetails.lastName');
		$this->db->from($this->tablename .' as orderheader');
		$this->db->join($this->order_details_tablename .' as orderdetails', 'orderheader.orderID = orderdetails.orderID', 'inner');
		$this->db->join($this->patient_details_tablename .' as patientdetails', 'patientdetails.patientID = orderheader.patientID', 'inner');
		$cond_array['orderheader.isDeleted1'] = 0;
		$this->db->where($cond_array);
		$this->db->order_by("orderheader.created", "DESC");
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$return_orders = array();
			$orders = $query->result_array();
			foreach($orders as $orderDetails)
			{
				$return_orders[$orderDetails['orderID']]['patientName'] = $orderDetails['firstName']. ' ' .$orderDetails['lastName'];
				$return_orders[$orderDetails['orderID']]['totalPrice'] = $orderDetails['totalPrice'];
				$return_orders[$orderDetails['orderID']]['tax_amount'] = $orderDetails['tax_amount'];
				$return_orders[$orderDetails['orderID']]['deliveryType'] = $orderDetails['deliveryType'];
				$return_orders[$orderDetails['orderID']]['deliveryCharge'] = $orderDetails['deliveryCharge'];
				$return_orders[$orderDetails['orderID']]['paymentType'] = $orderDetails['paymentType'];
				$return_orders[$orderDetails['orderID']]['status'] = $orderDetails['status'];
				$return_orders[$orderDetails['orderID']]['created'] = $orderDetails['created'];
				$return_orders[$orderDetails['orderID']]['productDetails'][] = array('categotyID' => $orderDetails['categotyID'], 'subCategotyID' => $orderDetails['subCategotyID'], 'subCategotyName' => $orderDetails['subCategotyName'], 'productID' => $orderDetails['productID'], 'productName' => $orderDetails['productName'], 'productPrice' => $orderDetails['productPrice'], 'quantity' => $orderDetails['quantity']);
			}
			return $return_orders;
		}
		else
			return false;
	}
	
	public function getAllordersCount()
	{
		$this->db->select('orderheader.*, COUNT(*) AS orderCount');
		$this->db->from($this->tablename .' as orderheader');
		$this->db->where('orderheader.isDeleted','0');
		$this->db->group_by("orderheader.status");
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$return_orders = array();
			$orders = $query->result_array();
			foreach($orders as $orderDetails)
			{
				$return_orders[$orderDetails['status']] = $orderDetails['orderCount'];
			}
			return $return_orders;
		}
		else
			return false;
	}
	
	public function UpdateOrderStatus($orderID, $status = 0)
	{
		$cond_arr = array('orderID' => $orderID);
		$update_arr = array('status' => $status);
		$this->db->update($this->tablename, $update_arr, $cond_arr);
	}
	
	
	/*public function getAllSearchOrders( $cond_array = array(), $is_store_call = false)
	{
		$this->db->select('orderheader.*,patientdetails.firstName,patientdetails.lastName, patientdetails.doctorName, patientdetails.address1,patientdetails.city, patientdetails.state,patientdetails.zip,patientdetails.phone,patient.userName ');//orderdetails.*,//, GROUP_CONCAT(DISTINCT orderdetails.subCategotyName ORDER BY orderdetails.orderID  DESC SEPARATOR "#") AS productdetails
		$this->db->from($this->tablename .' as orderheader');
	//	$this->db->join($this->order_details_tablename .' as orderdetails', 'orderheader.orderID = orderdetails.orderID', 'inner');
		$this->db->join($this->patient_details_tablename .' as patientdetails', 'patientdetails.patientID = orderheader.patientID', 'inner');
		$this->db->join($this->patient_tablename .' as patient', 'patientdetails.patientID = patient.patientID', 'inner');
		$this->db->join('admin', 'admin.ID = orderheader.createdBy', 'left');
		
		if(isset($cond_array['orderID']) && !empty($cond_array['orderID']))
			$this->db->where('orderheader.orderID', $cond_array['orderID']);
		
		if(isset($cond_array['patientName']) && !empty($cond_array['patientName']))
			$this->db->like('CONCAT(patientdetails.firstName, " ", patientdetails.lastName)', $cond_array['patientName']);
		
		if(isset($cond_array['orderBy']) && !empty($cond_array['orderBy']))
			$this->db->like('CONCAT(admin.firstName, " ", admin.lastName)', $cond_array['orderBy']);
		
		if(isset($cond_array['orderStatus']) && !empty($cond_array['orderStatus']))
		{
			$this->db->where(array('orderheader.status'=> $cond_array['orderStatus']));
		}
		else if(isset($cond_array['orderStatus']) && ($cond_array['orderStatus'] === '0'))
		{
			$this->db->where(array('orderheader.status'=> $cond_array['orderStatus']));
		}
		
		if(isset($cond_array['deliveryType']) && !empty($cond_array['deliveryType']))
		{
			$this->db->like('orderheader.deliveryType', $cond_array['deliveryType']);
			$this->db->or_like('orderheader.paymentType', $cond_array['deliveryType']);
		}
		
		$this->db->where('orderheader.isDeleted','0');
		
		if(isset($cond_array['orderDate']) && !empty($cond_array['orderDate']))
		{
			$this->db->like('orderheader.created', $cond_array['orderDate'] );
		}
			
		//$this->db->group_by("orderheader.orderID");
		$this->db->order_by("orderheader.created", "DESC");
		if(isset($cond_array['recordsperpage']) && isset($cond_array['limit']))
			$this->db->limit($cond_array['recordsperpage'], $cond_array['limit']);
		
		$query = $this->db->get();
		
		//echo $this->db->last_query(); //exit;
		
		if($query->num_rows() > 0)
		{
			$return_orders = array();
			$orders = $query->result_array();
			
			$orderids_arr=array();
			foreach($orders as $orderDetails)
			{
				if($orderDetails['createdType'] != 'patient')
				{
					$dettails = $this->getDetailsByID($orderDetails['createdBy']);
					$createdBy = ($dettails) ? $dettails['firstName']. ' ' .$dettails['lastName'] : '';
				}
				else
				{
					$createdBy = $orderDetails['firstName']. ' ' .$orderDetails['lastName'];
				}
				$orderids_arr[]=$orderDetails['orderID'];
				$return_orders[$orderDetails['orderID']]['patientName'] = $orderDetails['firstName']. ' ' .$orderDetails['lastName'];
				$return_orders[$orderDetails['orderID']]['doctorName'] = $orderDetails['doctorName'];
				$return_orders[$orderDetails['orderID']]['patientID'] = $orderDetails['patientID'];
				$return_orders[$orderDetails['orderID']]['orderID'] = $orderDetails['orderID'];
				$return_orders[$orderDetails['orderID']]['invoiceNumber'] = $orderDetails['invoiceNumber'];
				
				$return_orders[$orderDetails['orderID']]['patientDetails'] = $orderDetails['address1']. ', ' .$orderDetails['city']. ', ' .$orderDetails['state']. ', ' .$orderDetails['zip'];
				$return_orders[$orderDetails['orderID']]['phone'] = $orderDetails['phone'];
				$return_orders[$orderDetails['orderID']]['userName'] = $orderDetails['userName'];
				$return_orders[$orderDetails['orderID']]['totalPrice'] = $orderDetails['totalPrice'];
				
				$productdetails = $this->getOrdersByOrderID($orderDetails['orderID']);
				//echo "<pre>"; print_r($productdetails); exit;
				$OrderedProducts = array();
				foreach($productdetails[$orderDetails['orderID']]['productDetails'] as $productdetail)
				{
					$OrderedProducts[] = $productdetail['subCategotyName'].' - '.$productdetail['productName'];//.' - '.$productdetail['QBcode'];
				}
				$return_orders[$orderDetails['orderID']]['productdetails'] = $OrderedProducts;
				$return_orders[$orderDetails['orderID']]['productdetailsconcat'] = $OrderedProductconcat;
				
				$return_orders[$orderDetails['orderID']]['tax_amount'] = $orderDetails['tax_amount'];
				$return_orders[$orderDetails['orderID']]['deliveryType'] = $orderDetails['deliveryType'];
				$return_orders[$orderDetails['orderID']]['deliveryCharge'] = $orderDetails['deliveryCharge'];
				$return_orders[$orderDetails['orderID']]['paymentType'] = $orderDetails['paymentType'];
				$return_orders[$orderDetails['orderID']]['status'] = $orderDetails['status'];
				$return_orders[$orderDetails['orderID']]['createdBy'] = $createdBy;
				$return_orders[$orderDetails['orderID']]['createdType'] = $orderDetails['createdType'];
				$return_orders[$orderDetails['orderID']]['created'] = $orderDetails['created'];
				//$return_orders[$orderDetails['orderID']]['productDetails'][] = array('categotyID' => $orderDetails['categotyID'], 'subCategotyID' => $orderDetails['subCategotyID'], 'subCategotyName' => $orderDetails['subCategotyName'], 'productID' => $orderDetails['productID'], 'productName' => $orderDetails['productName'], 'productPrice' => $orderDetails['productPrice'], 'quantity' => $orderDetails['quantity']);
			}
			if($is_store_call)
			{
				$orderids=implode(",",$orderids_arr);
				$productdetails = $this->getOrdersByOrderID_store($orderids);
				foreach($orders as $orderDetails)
				{
					$OrderedProducts = array();
					$OrderedProductconcat = array();
					foreach($productdetails[$orderDetails['orderID']]['productDetails'] as $productdetail)
					{
						$OrderedProducts[] = $productdetail['subCategotyName'].' - '.$productdetail['productName'];//.' - '.$productdetail['QBcode'];
						$OrderedProductconcat[] = $productdetail['product'];//.' - '.$productdetail['QBcode'];
					}
					$return_orders[$orderDetails['orderID']]['productdetails'] = $OrderedProducts;
					$return_orders[$orderDetails['orderID']]['productdetailsconcat'] = $OrderedProductconcat;
				}
			}
			return $return_orders;
		}
		else
			return false;
	}*/
	
	public function getAllSearchOrders( $cond_array = array(), $is_store_call = false)
	{
		$this->db->select('orderheader.*,patientdetails.firstName,patientdetails.lastName, patientdetails.doctorName, patientdetails.address1,patientdetails.city, patientdetails.state,patientdetails.zip,patientdetails.phone,patient.userName ');//orderdetails.*,//, GROUP_CONCAT(DISTINCT orderdetails.subCategotyName ORDER BY orderdetails.orderID  DESC SEPARATOR "#") AS productdetails
		$this->db->from($this->tablename .' as orderheader');
	//	$this->db->join($this->order_details_tablename .' as orderdetails', 'orderheader.orderID = orderdetails.orderID', 'inner');
		$this->db->join($this->patient_details_tablename .' as patientdetails', 'patientdetails.patientID = orderheader.patientID', 'inner');
		$this->db->join($this->patient_tablename .' as patient', 'patientdetails.patientID = patient.patientID', 'inner');
		$this->db->join('admin', 'admin.ID = orderheader.createdBy', 'left');
		
		if(isset($cond_array['orderID']) && !empty($cond_array['orderID']))
			$this->db->where('orderheader.orderID', $cond_array['orderID']);
		
		if(isset($cond_array['patientName']) && !empty($cond_array['patientName']))
			$this->db->like('CONCAT(patientdetails.firstName, " ", patientdetails.lastName)', $cond_array['patientName']);
		
		if(isset($cond_array['orderBy']) && !empty($cond_array['orderBy']))
			$this->db->like('CONCAT(admin.firstName, " ", admin.lastName)', $cond_array['orderBy']);
		
		if(isset($cond_array['orderStatus']) && !empty($cond_array['orderStatus']))
		{
			$this->db->where(array('orderheader.status'=> $cond_array['orderStatus']));
		}
		else if(isset($cond_array['orderStatus']) && ($cond_array['orderStatus'] === '0'))
		{
			$this->db->where(array('orderheader.status'=> $cond_array['orderStatus']));
		}
		
		if(isset($cond_array['deliveryType']) && !empty($cond_array['deliveryType']))
		{
			$this->db->like('orderheader.deliveryType', $cond_array['deliveryType']);
			$this->db->or_like('orderheader.paymentType', $cond_array['deliveryType']);
		}
		
		$this->db->where('orderheader.isDeleted','0');
		
		if(isset($cond_array['from_orderDate']) && !empty($cond_array['from_orderDate']))
		{
			
		    $this->db->where('orderheader.created >=', $cond_array['from_orderDate']);
			$this->db->where('orderheader.created <=',  $cond_array['to_orderDate']);
			 
		}
			
		//$this->db->group_by("orderheader.orderID");
		$this->db->order_by("orderheader.created", "DESC");
		if(isset($cond_array['recordsperpage']) && isset($cond_array['limit']))
			$this->db->limit($cond_array['recordsperpage'], $cond_array['limit']);
		
		$query = $this->db->get();
		
		//echo $this->db->last_query(); //exit;
		
		if($query->num_rows() > 0)
		{
			$return_orders = array();
			$orders = $query->result_array();
			
			$orderids_arr=array();
			foreach($orders as $orderDetails)
			{
				if($orderDetails['createdType'] != 'patient')
				{
					$dettails = $this->getDetailsByID($orderDetails['createdBy']);
					$createdBy = ($dettails) ? $dettails['firstName']. ' ' .$dettails['lastName'] : '';
				}
				else
				{
					$createdBy = $orderDetails['firstName']. ' ' .$orderDetails['lastName'];
				}
				$orderids_arr[]=$orderDetails['orderID'];
				$return_orders[$orderDetails['orderID']]['patientName'] = $orderDetails['firstName']. ' ' .$orderDetails['lastName'];
				$return_orders[$orderDetails['orderID']]['doctorName'] = $orderDetails['doctorName'];
				$return_orders[$orderDetails['orderID']]['patientID'] = $orderDetails['patientID'];
				$return_orders[$orderDetails['orderID']]['orderID'] = $orderDetails['orderID'];
				$return_orders[$orderDetails['orderID']]['invoiceNumber'] = $orderDetails['invoiceNumber'];
				
				$return_orders[$orderDetails['orderID']]['patientDetails'] = $orderDetails['address1']. ', ' .$orderDetails['city']. ', ' .$orderDetails['state']. ', ' .$orderDetails['zip'];
				$return_orders[$orderDetails['orderID']]['phone'] = $orderDetails['phone'];
				$return_orders[$orderDetails['orderID']]['userName'] = $orderDetails['userName'];
				$return_orders[$orderDetails['orderID']]['totalPrice'] = $orderDetails['totalPrice'];
				
				
				
				$return_orders[$orderDetails['orderID']]['productdetails'] = $OrderedProducts;
				$return_orders[$orderDetails['orderID']]['productdetailsconcat'] = $OrderedProductconcat;
				
				$return_orders[$orderDetails['orderID']]['tax_amount'] = $orderDetails['tax_amount'];
				$return_orders[$orderDetails['orderID']]['deliveryType'] = $orderDetails['deliveryType'];
				$return_orders[$orderDetails['orderID']]['deliveryCharge'] = $orderDetails['deliveryCharge'];
				$return_orders[$orderDetails['orderID']]['paymentType'] = $orderDetails['paymentType'];
				$return_orders[$orderDetails['orderID']]['status'] = $orderDetails['status'];
				$return_orders[$orderDetails['orderID']]['createdBy'] = $createdBy;
				$return_orders[$orderDetails['orderID']]['createdType'] = $orderDetails['createdType'];
				$return_orders[$orderDetails['orderID']]['created'] = $orderDetails['created'];
				//$return_orders[$orderDetails['orderID']]['productDetails'][] = array('categotyID' => $orderDetails['categotyID'], 'subCategotyID' => $orderDetails['subCategotyID'], 'subCategotyName' => $orderDetails['subCategotyName'], 'productID' => $orderDetails['productID'], 'productName' => $orderDetails['productName'], 'productPrice' => $orderDetails['productPrice'], 'quantity' => $orderDetails['quantity']);
			}
			if($is_store_call)
			{
				$orderids=implode(",",$orderids_arr);
				$productdetails = $this->getOrdersByOrderID_store($orderids);
				foreach($orders as $orderDetails)
				{
					$OrderedProducts = array();
					$OrderedProductconcat = array();
					foreach($productdetails[$orderDetails['orderID']]['productDetails'] as $productdetail)
					{
						$OrderedProducts[] = $productdetail['subCategotyName'].' - '.$productdetail['productName'];//.' - '.$productdetail['QBcode'];
						$OrderedProductconcat[] = $productdetail['product'];//.' - '.$productdetail['QBcode'];
					}
					$return_orders[$orderDetails['orderID']]['productdetails'] = $OrderedProducts;
					$return_orders[$orderDetails['orderID']]['productdetailsconcat'] = $OrderedProductconcat;
				}
			}
			return $return_orders;
		}
		else
			return false;
	}
	
	public function getDetailsByID($userId)
	{
		$this->db->select('firstName, lastName');						
		$this->db->where('ID', $userId);			
		$query = $this->db->get('admin');
		if($query->num_rows() == 1)
		{
			$resultarray= $query->row_array();
			$query->next_result();
			$query->free_result();
			return $resultarray;
 		}
		else
			return false;
	}
	
	public function getAllSearchOrdersCount( $cond_array = array())
	{
		
		$this->db->select('orderheader.*,patientdetails.firstName,patientdetails.lastName');//orderdetails.*,
		$this->db->from($this->tablename .' as orderheader');
		//$this->db->join($this->order_details_tablename .' as orderdetails', 'orderheader.orderID = orderdetails.orderID', 'inner');
		$this->db->join($this->patient_details_tablename .' as patientdetails', 'patientdetails.patientID = orderheader.patientID', 'inner');
		$this->db->where('orderheader.isDeleted','0');
		
		//echo "<pre>";print_r($cond_array);exit;
		if(isset($cond_array['orderID']) && !empty($cond_array['orderID'])) {
			$this->db->where(array('orderheader.orderID'=> $cond_array['orderID'],'orderheader.isDeleted'=>'0'));
		}
		
		if(isset($cond_array['patientName']) && !empty($cond_array['patientName']))
		{
			$this->db->like('CONCAT(patientdetails.firstName, " ", patientdetails.lastName)', $cond_array['patientName']);
		}
		
		if(isset($cond_array['orderStatus']) && !empty($cond_array['orderStatus']))
		{
			$this->db->where(array('orderheader.status'=> $cond_array['orderStatus']));
		}
		else if(isset($cond_array['orderStatus']) && ($cond_array['orderStatus'] === '0'))
		{
			$this->db->where(array('orderheader.status'=> $cond_array['orderStatus']));
		}
		
		if(isset($cond_array['deliveryType']) && !empty($cond_array['deliveryType']))
		{
			$this->db->like('orderheader.deliveryType', $cond_array['deliveryType']);
			$this->db->or_like('orderheader.paymentType', $cond_array['deliveryType']);
		}		
		
		$this->db->where('orderheader.isDeleted','0');
		
		if(isset($cond_array['from_orderDate']) && !empty($cond_array['from_orderDate']))
		{
			
			 //echo "<pre>";
			//print_r($date_arr);
		 	//$this->db->like('orderheader.created', $cond_array['orderDate'] );
			$this->db->where('orderheader.created >=', $cond_array['from_orderDate']);
			$this->db->where('orderheader.created <=',  $cond_array['to_orderDate']);
			//$this->db->like('orderheader.created', $cond_array['orderDate'] );
		}
		
		$this->db->group_by("orderheader.orderID", "DESC");	
		$this->db->order_by("orderheader.created", "DESC");
		
		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		return $query->num_rows();
	}
	
	
	
	public function getSalesDetails()
	{
       // $cond_array=array();
		$this->db->select('orderdetails.productID, SUM(orderdetails.quantity) as totalSales');
		//$this->db->from($this->order_details_tablename .' as orderheader');
		$this->db->from($this->tablename .' as orderheader');
		$this->db->join($this->order_details_tablename .' as orderdetails', 'orderheader.orderID = orderdetails.orderID', 'inner');
		//$cond_array['orderheader.created'] = '>2014-12-01';
		//$cond_array['orderheader.isDeleted'] = 0;
		//$this->db->where($cond_array);
		$this->db->where('orderheader.isDeleted','0');
		$this->db->group_by("productID");
         
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$orders = $query->result_array();
			foreach($orders as $orderDetails)
			{
				$return_orders[$orderDetails['productID']] = $orderDetails['totalSales'];
			}
			return $return_orders;
		}
		else
			return false;
	}
	
	public function getSalesDetailsByEmployee()
	{
		$this->db->select('orderheader.createdBy, orderdetails.productID, SUM(orderdetails.quantity) as totalSales');
		$this->db->from($this->order_details_tablename.' as orderdetails');
		$this->db->join($this->tablename .' as orderheader', 'orderdetails.orderID = orderheader.orderID', 'inner');
		$this->db->where(array('orderheader.createdType'=> 'EMPLOYEE'));
		$this->db->group_by("orderdetails.productID, orderheader.createdBy");
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$orders = $query->result_array();
			foreach($orders as $orderDetails)
			{
				$return_orders[$orderDetails['createdBy']][$orderDetails['productID']] = $orderDetails['totalSales'];
			}
			return $return_orders;
		}
		else
			return false;
	}
	
	public function deleteOrder($array)
	{
		if(!empty($array['orderID']))
		{
			$insert_array = array('isDeleted' => '1' );
			$cond_array = array('orderID' => $array['orderID']);
			$this->db->update($this->tablename, $insert_array, $cond_array);
			return $array['orderID'];
		}
		else
			return false;
	}
	
	
	public function getCounters()
	{
		$this->db->select('counterID,counterName');
		$this->db->from('ordercounter');
		$this->db->where(array('isActive'=> 1, 'isDeleted'=> 0));
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
			return false;
	}
	
	public function addPatientInQueue($patientID, $createdBy, $createdType)
	{
		$this->db->insert('orderqueue', array("patientID" => $patientID, "createdBy" => $createdBy, "createdType" => $createdType));
		return true;
	}
	
	public function getOrdersQueue($cond_array)
	{
		$this->db->select('orderheader.*,patientdetails.firstName,patientdetails.lastName, patientdetails.doctorName, patientdetails.address1,patientdetails.city, patientdetails.state,patientdetails.zip,patientdetails.phone,patient.userName ');
		$this->db->from('orderqueue as orderheader');
		$this->db->join($this->patient_details_tablename .' as patientdetails', 'patientdetails.patientID = orderheader.patientID', 'inner');
		$this->db->join($this->patient_tablename .' as patient', 'patientdetails.patientID = patient.patientID', 'inner');
		
		if(isset($cond_array['queueID']) && !empty($cond_array['queueID']))
			$this->db->where('orderheader.queueID', $cond_array['queueID']);
		
		if(isset($cond_array['patientName']) && !empty($cond_array['patientName']))
			$this->db->like('CONCAT(patientdetails.firstName, " ", patientdetails.lastName)', $cond_array['patientName']);
				
		if(isset($cond_array['orderDate']) && !empty($cond_array['orderDate']))
			$this->db->like('orderheader.created', $cond_array['orderDate'] );
				
		if(isset($cond_array['counterID']))
			$this->db->where('orderheader.counterID IS NULL' );
			
		$this->db->order_by("orderheader.created", "DESC");
		
		
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$return_orders = array();
			$orders = $query->result_array();
			foreach($orders as $orderDetails)
			{
				if($orderDetails['createdType'] != 'patient')
				{
					$dettails = $this->getDetailsByID($orderDetails['createdBy']);
					$createdBy = ($dettails) ? $dettails['firstName']. ' ' .$dettails['lastName'] : '';
				}
				else
				{
					$createdBy = $orderDetails['firstName']. ' ' .$orderDetails['lastName'];
				}
				
				$return_orders[$orderDetails['queueID']]['patientID'] = $orderDetails['patientID'];								
				$return_orders[$orderDetails['queueID']]['patientName'] = $orderDetails['firstName']. ' ' .$orderDetails['lastName'];								
				$return_orders[$orderDetails['queueID']]['patientDetails'] = $orderDetails['address1']. ', ' .$orderDetails['city']. ', ' .$orderDetails['state']. ', ' .$orderDetails['zip'];				
				$return_orders[$orderDetails['queueID']]['userName'] = $orderDetails['userName'];
				$return_orders[$orderDetails['queueID']]['createdBy'] = $createdBy;
				$return_orders[$orderDetails['queueID']]['createdByID'] = $orderDetails['createdBy'];
				$return_orders[$orderDetails['queueID']]['createdType'] = $orderDetails['createdType'];
				$return_orders[$orderDetails['queueID']]['created'] = $orderDetails['created'];
				
			}
			return $return_orders;
		}
		return false;
	}
	
	public function assignCounter($queueID, $counterID)
	{
		$queue = $this->getOrdersQueue(array("queueID" => $queueID));
		if(isset($queue[$queueID]['patientID']) && !empty($queue[$queueID]['patientID']))
		{
			$orderHeader = array('patientID' => $queue[$queueID]['patientID'], 'status' => 5, 'createdType' => $queue[$queueID]['createdType'], 'createdBy' => $queue[$queueID]['createdByID'], 'created' => date('Y-m-d H:i:s'));
			$this->db->insert($this->tablename, $orderHeader);
			$orderID = $this->db->insert_id();
			$this->db->update('orderqueue', array("counterID" => $counterID, "orderID" => $orderID), array("queueID" => $queueID));
		}
		return true;
	}
	
	function getCounterPatientDetails()
	{
		$sql  = "SELECT oq.`queueID`, oq.`patientID`, oq.`counterID`, oq.`orderID`, oq.`createdBy`, oq.`createdType`, oq.`created`, CONCAT(pd.firstName, ' ', pd.lastName) as patientName, oc.counterName FROM `orderqueue` as oq";
		$sql .= " INNER JOIN `orderheader` as oh ON oh.`orderID` = oq.`orderID` AND oh.`status` = 5";
		$sql .= " INNER JOIN `patientdetails` as pd ON pd.patientID = oq.`patientID`";
		$sql .= " INNER JOIN `ordercounter` as oc ON oc.counterID = oq.`counterID`";
		$sql .= " ORDER BY oq.`queueID` ASC";
		$query  = $this->db->query($sql);
		$orders = $query->result_array();
		
		return $orders;
		
	}
	
	function getAnnouncementCounterPatientDetails()
	{
		$sql  = "SELECT oq.`queueID`, oq.`patientID`, oq.`counterID`, oq.`orderID`, oq.`createdBy`, oq.`createdType`, oq.`created`, CONCAT(pd.firstName, ' ', LEFT(pd.lastName,1)) as patientName, oc.counterName, oc.counterNumber FROM `orderqueue` as oq";
		$sql .= " INNER JOIN `orderheader` as oh ON oh.`orderID` = oq.`orderID` AND oh.`status` = 5";
		$sql .= " INNER JOIN `patientdetails` as pd ON pd.patientID = oq.`patientID`";
		$sql .= " INNER JOIN `ordercounter` as oc ON oc.counterID = oq.`counterID`";
		$sql .= " WHERE oq.`isAnnounced` = 0";
		$sql .= " ORDER BY oq.`queueID` ASC";
		$query  = $this->db->query($sql);
		$orders = $query->row_array();
		
		if(!isset($orders['queueID']))
		{
			$sql  = "SELECT oq.`queueID`, oq.`patientID`, oq.`counterID`, oq.`orderID`, oq.`createdBy`, oq.`createdType`, oq.`created`, CONCAT(pd.firstName, ' ', LEFT(pd.lastName,1)) as patientName, oc.counterName, oc.counterNumber FROM `orderqueue` as oq";
			$sql .= " INNER JOIN `orderheader` as oh ON oh.`orderID` = oq.`orderID` AND oh.`status` = 5";
			$sql .= " INNER JOIN `patientdetails` as pd ON pd.patientID = oq.`patientID`";
			$sql .= " INNER JOIN `ordercounter` as oc ON oc.counterID = oq.`counterID`";
			$sql .= " WHERE oq.`isAnnounced` = 1";
			$sql .= " ORDER BY oq.`queueID` DESC";
			$query  = $this->db->query($sql);
			$orders = $query->row_array();
		}
		
		return $orders;
	}
	
	function updateAnnouncementCounterPatientDetails($queueID)
	{
		$this->db->update('orderqueue', array('isAnnounced' => 1), array('queueID' => $queueID));
	}
	
	public function UpdateOrderDriver($orderID, $details = array())
	{
		$cond_arr = array('orderID' => $orderID);
		$this->db->update($this->tablename, $details, $cond_arr);
	}
}
?>