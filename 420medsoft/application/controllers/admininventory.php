<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'controllers/admin.php');
class Admininventory extends Admin {
	
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
		
		if($this->input->post())
		{
			$this->categories_model->insertInventory($this->input->post());
			redirect('admininventory/inventorydetails'); 
			exit;
		}
		
		$this->data['categories'] = $this->categories_model->getMainCategories();
		//$this->data['categories'] = $this->categories_model->getSubCategoriesByParentID();
		
		//echo "<pre>"; print_r($this->data['categories']); exit;
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/inventory/inventory', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	}
	
	////////////////////////////////////////////////////////////
	
	public function editinventory($inventoryID)
	{
		$this->load->helper('form');
		$this->load->model('categories_model');	
		
		if($this->input->post())
		{
			$this->categories_model->updateInventory($this->input->post());
			redirect('admininventory/getallinventorydetails'); 
			exit;
		}
		
		$condition_array = array('inventoryID' => $inventoryID);
				
		$this->data['inventoryStock'] = $this->categories_model->getAllInventoryDetails($condition_array);
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/inventory/editinventory', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	}
	
	public function getallinventorydetails($currentPage = 0)
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
			
			if(isset($post['productName']) && !empty($post['productName']))
			$condition_array['productName'] = $post['productName'];	
				
			if(isset($post['recordPerPage']) && !empty($post['recordPerPage']))
				$this->recordsperpage = $post['recordPerPage'];
		}
		
		$condition_array['limit'] = $currentPage;
		$condition_array['recordsperpage'] = $this->recordsperpage;
		
		$this->data['inventoryStock'] = $this->categories_model->getAllInventoryDetails($condition_array);
		$this->data['inventoryCount'] = $this->categories_model->getAllInventoryDetailsCount($condition_array);
		
		$this->data['recordsperpage'] = $this->recordsperpage;
		
		$this->load->library('pagination');
		$config['base_url'] = base_url('index.php/admininventory/getallinventorydetails/');
		$config['total_rows'] = $this->data['inventoryCount'];
		$config['per_page'] = $this->recordsperpage; 		
		$this->pagination->initialize($config); 		
		$this->data['paginationLinks'] = $this->pagination->create_links();
		
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/inventory/getallinventorydetails', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
		
	}
	////////////////////////////////////////////////////////////
	
	
	public function inventorydetails($currentPage = 0)
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
			
			if(isset($post['productName']) && !empty($post['productName']))
			$condition_array['productName'] = $post['productName'];	
				
			if(isset($post['recordPerPage']) && !empty($post['recordPerPage']))
				$this->recordsperpage = $post['recordPerPage'];
		}
		
		$condition_array['limit'] = $currentPage;
		$condition_array['recordsperpage'] = $this->recordsperpage;
				
		$this->data['inventoryStock'] = $this->categories_model->getInventoryDetails($condition_array);
		$this->data['empinventoryStock'] = $this->categories_model->getempInventoryDetails($condition_array);
		$this->data['inventoryCount'] = $this->categories_model->getInventoryDetailsCount($condition_array);
		$inventorySales = $this->orders_model->getSalesDetails();
		
		/*$inventoryStockEmployeeProduct = array();
		if($this->data['empinventoryStock'])
		foreach($this->data['empinventoryStock'] as $key => $empinventoryStock)
		{
			if(!isset($inventoryStockEmployeeProduct[$empinventoryStock['productID']]))
				$inventoryStockEmployeeProduct[$empinventoryStock['productID']] = 0;
			$inventoryStockEmployeeProduct[$empinventoryStock['productID']] += $empinventoryStock['totalStock'];
		}*/
		
		$empInventorySales = $this->orders_model->getSalesDetailsByEmployee();
		
		$inventoryStockEmployeeProduct = array();
		if($this->data['empinventoryStock'])
		foreach($this->data['empinventoryStock'] as $key => $empinventoryStock)
		{
			if(isset($empInventorySales[$empinventoryStock['empID']][$empinventoryStock['productID']]) && !empty($empInventorySales[$empinventoryStock['empID']][$empinventoryStock['productID']]))
				$this->data['empinventoryStock'][$key]['totalSales'] = $empInventorySales[$empinventoryStock['empID']][$empinventoryStock['productID']];
			else
				$this->data['empinventoryStock'][$key]['totalSales'] = 0;
			
			if(!isset($inventoryStockEmployeeProduct[$empinventoryStock['productID']]))
				$inventoryStockEmployeeProduct[$empinventoryStock['productID']] = 0;
			$inventoryStockEmployeeProduct[$empinventoryStock['productID']] += $empinventoryStock['totalStock']- $this->data['empinventoryStock'][$key]['totalSales'];
		}
		
		if($this->data['inventoryStock'])
		foreach($this->data['inventoryStock'] as $key => $inventoryStock)
		{
			if(isset($inventorySales[$inventoryStock['productID']]) && !empty($inventorySales[$inventoryStock['productID']]))
				$this->data['inventoryStock'][$key]['totalSales'] = $inventorySales[$inventoryStock['productID']];
			else
				$this->data['inventoryStock'][$key]['totalSales'] = 0;
				
			if(isset($inventoryStockEmployeeProduct[$inventoryStock['productID']]) && !empty($inventoryStockEmployeeProduct[$inventoryStock['productID']]))
				$this->data['inventoryStock'][$key]['totalStockEmployee'] = $inventoryStockEmployeeProduct[$inventoryStock['productID']];
			else
				$this->data['inventoryStock'][$key]['totalStockEmployee'] = 0;
			
			$this->data['inventoryStock'][$key]['totalRemaining'] = $inventoryStock['totalStock'] - $this->data['inventoryStock'][$key]['totalSales'];//$this->data['inventoryStock'][$key]['totalStockEmployee'];
		}
		
		//$this->data['categories'] = $this->categories_model->getSubCategoriesByParentID();
		
	    //echo "<pre>"; print_r($this->data['inventoryStock']); exit;
		$this->data['recordsperpage'] = $this->recordsperpage;
		
		$this->load->library('pagination');
		$config['base_url'] = base_url('index.php/admininventory/inventorydetails/');
		$config['total_rows'] = $this->data['inventoryCount'];
		$config['per_page'] = $this->recordsperpage; 		
		$this->pagination->initialize($config); 		
		$this->data['paginationLinks'] = $this->pagination->create_links();
		
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/inventory/inventorydetails', $this->data);
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
				$this->load->model('categories_model');		
				$this->data['options'] = $this->categories_model->getOptions();
				$this->data['categories'] = $this->categories_model->getSubCategoriesByParentID($category_id);
				$this->load->view('admin/inventory/getallsubcategories', $this->data);
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
				$this->load->model('categories_model');	
				$this->data['productID'] = 	$productID;
				$this->data['options'] = $this->categories_model->getOptions();
				$this->data['categories'] = $this->categories_model->getSubCategoriesByParentID($category_id);
				$this->load->view('admin/inventory/getallproducts', $this->data);
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
	
	public function getRemaining()
	{
		if($this->input->post('productID')) {
			$productID=$this->input->post('productID');
			$this->load->model('categories_model');	
			$this->load->model('orders_model');
			$condition_array=array('limit'=>0,'recordsperpage'=>1,'productID'=>$productID);
			//$this->data['inventoryEmpStock'] = $this->categories_model->getempInventoryDetails($condition_array);
			
			$this->data['empinventoryStock'] = $this->categories_model->getempInventoryDetails($condition_array);
			//echo $this->db->last_query(); exit;
			$inventoryStockEmployeeProduct = array();
			if($this->data['empinventoryStock'])
			foreach($this->data['empinventoryStock'] as $key => $empinventoryStock)
			{
				if(!isset($inventoryStockEmployeeProduct[$empinventoryStock['productID']]))
					$inventoryStockEmployeeProduct[$empinventoryStock['productID']] = 0;
				$inventoryStockEmployeeProduct[$empinventoryStock['productID']] += $empinventoryStock['totalStock'];
			}
		
			$this->data['inventoryStock'] = $this->categories_model->getInventoryDetails($condition_array);
			//$this->data['inventoryCount'] = $this->categories_model->getempInventoryDetailsCount($condition_array);
			$inventorySales = $this->orders_model->getSalesDetails();
			//echo "<pre>"; print_r($this->data['empinventoryStock']); 
			//echo "<pre>"; print_r($this->data['inventoryStock']); exit;
			if($this->data['inventoryStock']) {
				foreach($this->data['inventoryStock'] as $key => $inventoryStock)
				{
					if(isset($inventorySales[$inventoryStock['productID']]) && !empty($inventorySales[$inventoryStock['productID']]))
						$this->data['inventoryStock'][$key]['totalSales'] = $inventorySales[$inventoryStock['productID']];
					else
						$this->data['inventoryStock'][$key]['totalSales'] = 0;
					
					if(isset($inventoryStockEmployeeProduct[$inventoryStock['productID']]) && !empty($inventoryStockEmployeeProduct[$inventoryStock['productID']]))
						$this->data['inventoryStock'][$key]['totalStockEmployee'] = $inventoryStockEmployeeProduct[$inventoryStock['productID']];
					else
						$this->data['inventoryStock'][$key]['totalStockEmployee'] = 0;
					
					//$this->data['inventoryStock'][$key]['totalRemaining'] = $inventoryStock['totalStock'] - $this->data['inventoryStock'][$key]['totalStockEmployee'];
					$this->data['inventoryStock'][$key]['totalRemaining'] = $inventoryStock['totalStock'] - $this->data['inventoryStock'][$key]['totalSales'];
				}
			}
		}
		echo ((isset($this->data['inventoryStock'][0]['totalRemaining']) && !empty($this->data['inventoryStock'][0]['totalRemaining']))?$this->data['inventoryStock'][0]['totalRemaining']:0);
	}
	
	
	public function resetInventory()
	{
		if($this->isAjaxRequest())
		{
			$this->load->helper('form');
			if($this->input->post('parentID'))
			{
				$this->load->model('categories_model');	
				$status = $this->categories_model->RemoveInventory();
				if($status)
					echo "Successfully resetted your inventory.";
				else
					echo "Problem occured while resetting inventory Please try again.";
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
	
}
