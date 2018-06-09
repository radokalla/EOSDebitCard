<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'controllers/admin.php');
class Adminempinventory extends Admin {
	
	public $data = array();
	private $recordsperpage;
	
	public function __construct()
	{
		parent::__construct();
		//$this->output->clear_page_cache();
		$this->isLoggedIn();
		$this->data['session'] = $this->session->all_userdata();
		$this->recordsperpage = 50;
	}
	
	
	public function inventory()
	{
		$this->load->helper('form');
		$this->load->model('categories_model');			
		$this->load->model('admin_model');	
		if($this->input->post())
		{
			//echo "<pre>"; print_r($this->input->post()); exit;
			$this->categories_model->insertempInventory($this->input->post());
			redirect('adminempinventory/inventoryempdetails'); 
			exit;
		}
		$this->data['employeeDetails']= $this->admin_model->getEmployeeDetails(array('userName' =>''));
		
		 
		$this->data['categories'] = $this->categories_model->getMainCategories();
		//$this->data['categories'] = $this->categories_model->getSubCategoriesByParentID();
		
		//echo "<pre>"; print_r($this->data['categories']); exit;
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/inventory/inventoryemp', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	} 
	
	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
	
	
	public function editempinventory($inventoryID)
	{
		$this->load->helper('form');
		$this->load->model('categories_model');			
		if($this->input->post())
		{
			$this->categories_model->updateempInventory($this->input->post());
			redirect('adminempinventory/getallinventoryempdetails'); 
			exit;
		}
		
		$condition_array = array('inventoryID' => $inventoryID);
				
		$this->data['inventoryStock'] = $this->categories_model->getallempInventoryDetails($condition_array);
		//$this->data['categories'] = $this->categories_model->getSubCategoriesByParentID();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/inventory/editempinventory', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	} 
	
	public function getallinventoryempdetails($currentPage = 0)
	{
		$this->load->helper('form');
		$this->load->model('categories_model');	
		$this->load->model('orders_model');
		
		if($this->input->post())
		{
			$post = $this->input->post();						
			if(isset($post['mainCategoryName']) && !empty($post['mainCategoryName']))
			$condition_array['mainCategoryName'] = $post['mainCategoryName'];
				
			if(isset($post['categoryName']) && !empty($post['categoryName']))
			$condition_array['categoryName'] = $post['categoryName'];	
			
			if(isset($post['employeeName']) && !empty($post['employeeName']))
			$condition_array['employeeName'] = $post['employeeName'];	
			
			if(isset($post['productName']) && !empty($post['productName']))
			$condition_array['productName'] = $post['productName'];	
				
			if(isset($post['recordPerPage']) && !empty($post['recordPerPage']))
				$this->recordsperpage = $post['recordPerPage'];
		}
		
		$condition_array['limit'] = $currentPage;
		$condition_array['recordsperpage'] = $this->recordsperpage;
				
		$this->data['inventoryStock'] = $this->categories_model->getallempInventoryDetails($condition_array);
		$this->data['inventoryCount'] = $this->categories_model->getallempInventoryDetailsCount($condition_array);
		
		$this->data['recordsperpage'] = $this->recordsperpage;
		
		$this->load->library('pagination');
		$config['base_url'] = base_url('index.php/adminempinventory/getallinventoryempdetails/');
		$config['total_rows'] = $this->data['inventoryCount'];
		$config['per_page'] = $this->recordsperpage; 		
		$this->pagination->initialize($config); 		
		$this->data['paginationLinks'] = $this->pagination->create_links();
				
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/inventory/getallinventoryempdetails', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	}
	
	
	
	////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////
	
	public function inventoryempdetails($currentPage = 0)
	{
		$this->load->helper('form');
		$this->load->model('categories_model');	
		$this->load->model('orders_model');
		
		if($this->input->post())
		{
			$post = $this->input->post();
						
			if(isset($post['mainCategoryName']) && !empty($post['mainCategoryName']))
			$condition_array['mainCategoryName'] = $post['mainCategoryName'];
				
			if(isset($post['categoryName']) && !empty($post['categoryName']))
			$condition_array['categoryName'] = $post['categoryName'];	
			
			if(isset($post['employeeName']) && !empty($post['employeeName']))
			$condition_array['employeeName'] = $post['employeeName'];	
			
			if(isset($post['productName']) && !empty($post['productName']))
			$condition_array['productName'] = $post['productName'];	
				
			if(isset($post['recordPerPage']) && !empty($post['recordPerPage']))
				$this->recordsperpage = $post['recordPerPage'];
		}
		//echo "<pre>";
		//print_r($_SESSION);
		if($_SESSION['LOGIN_TYPE'] == 'EMPLOYEE'){
		$condition_array['empID']=$_SESSION['ADMIN_ID'];
		}
		$condition_array['limit'] = $currentPage;
		$condition_array['recordsperpage'] = $this->recordsperpage;
		//echo "<pre>";
		 //print_r($condition_array);		
		$this->data['inventoryStock'] = $this->categories_model->getempInventoryDetails($condition_array);
		$this->data['inventoryCount'] = $this->categories_model->getempInventoryDetailsCount($condition_array);
		$inventorySales = $this->orders_model->getSalesDetailsByEmployee();
		 //echo "<pre>";print_r($inventorySales);exit;
		//echo "<pre>";print_r($this->data['inventoryStock']);exit;
		if(!empty($this->data['inventoryStock']))
		{
			foreach($this->data['inventoryStock'] as $key => $inventoryStock)
			{
				if(isset($inventorySales[$inventoryStock['empID']][$inventoryStock['productID']]) && !empty($inventorySales[$inventoryStock['empID']][$inventoryStock['productID']]))
					$this->data['inventoryStock'][$key]['totalSales'] = $inventorySales[$inventoryStock['empID']][$inventoryStock['productID']];
				else
					$this->data['inventoryStock'][$key]['totalSales'] = 0;
				
				$this->data['inventoryStock'][$key]['totalRemaining'] = $inventoryStock['totalStock'] - $this->data['inventoryStock'][$key]['totalSales'];
				
				if($this->data['inventoryStock'][$key]['totalRemaining'] <= 0)
					unset($this->data['inventoryStock'][$key]);
			}
		}
		//$this->data['categories'] = $this->categories_model->getSubCategoriesByParentID();
		
	    //echo "<pre>"; print_r($this->data['inventoryStock']); exit;
		$this->data['recordsperpage'] = $this->recordsperpage;
		
		$this->load->library('pagination');
		$config['base_url'] = base_url('index.php/adminempinventory/inventoryempdetails/');
		$config['total_rows'] = $this->data['inventoryCount'];
		$config['per_page'] = $this->recordsperpage; 		
		$this->pagination->initialize($config); 		
		$this->data['paginationLinks'] = $this->pagination->create_links();
		
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/inventory/inventoryempdetails', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	}
	
	public function getAllSubcategories()
	{
		if($this->isAjaxRequest())
		{
			$this->load->helper('form');
			if($this->input->post('parentID'))
			{
				$category_id = $this->input->post('parentID');
				$this->data['rel'] = $this->input->post('rel');
				$this->load->model('categories_model');		
				$this->data['options'] = $this->categories_model->getOptions();
				$this->data['categories'] = $this->categories_model->getSubCategoriesByParentID($category_id);
				$this->load->view('admin/inventory/getallsubcategoriesemp', $this->data);
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
	
	public function getAllProducts()
	{
		if($this->isAjaxRequest())
		{
			$this->load->helper('form');
			if($this->input->post('parentID'))
			{
				$category_id = $this->input->post('parentID');
				$productID = $this->input->post('productID');
				$this->data['rel'] = $this->input->post('rel');
				$this->load->model('categories_model');	
				$this->data['productID'] = 	$productID;
				$this->data['options'] = $this->categories_model->getOptions();
				$this->data['categories'] = $this->categories_model->getSubCategoriesByParentID($category_id);
				$this->load->view('admin/inventory/getallproductsemp', $this->data);
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
	
	public function getEmployeeDetails()
	{
		if($this->isAjaxRequest())
		{
			$this->load->model('admin_model');		
			$employeeDetails = $this->admin_model->getEmployeeDetails(array('userName' => $_REQUEST['name_startsWith']));
			$currentEmployeeDetails = array();
			foreach($employeeDetails as $employee)
			{
				array_push($currentEmployeeDetails, array('employeeID' => $employee['ID'], 'employeeName' => $employee['firstName']. ' '. $employee['lastName'], ));
			}
			echo json_encode($currentEmployeeDetails);
		}
	}
}
