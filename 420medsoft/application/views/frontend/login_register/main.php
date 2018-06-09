<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	 public function __construct()
	 {
		parent::__construct();
		//$this->output->clear_page_cache();		
		$this->data['currency'] = array('symbol'=>'$', 'text' => 'USD');
		$this->data['session'] = $this->session->all_userdata();
		$this->session->unset_userdata('LOGIN_ERROR');		
		$this->load->model('categories_model');	
		$this->load->model('patient_model');			
		$this->data['mainCategories'] = $this->categories_model->getAllCategories(array('isActive' => 1));
		
		$this->load->model('admin_model');	
		$settings = $this->admin_model->getIp();
		$this->data['tax_percentage'] = $settings[0]['taxPercentage'];
		$this->data['delivery_charge'] = $settings[0]['deliveryCharges'];		
		if($this->session->userdata('PATIENT_ID'))
		{
			$this->data['patientCreditCardDetails'] = $this->patient_model->getPatientCreditCardDetails();
		}
	 }
	
	protected function isAjaxRequest()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest';
	}
	
	protected function checkPatientAuthentication()
	{
		if($this->session->userdata('PATIENT_ID')=='' || $this->session->userdata('PATIENT_NAME')=='' )
		{
			redirect(base_url('index.php/main/index'));
		}
	}
	
	public function index()
	{
		$category_id = 65;
		$this->data['currentCategory'] = $category_id;
		if($this->categories_model->getMainCategories(array('categoryID' => $category_id, 'isActive' => '1')))
		{
			$this->data['categories'] = $this->categories_model->getSubCategoriesByParentID($category_id);
			$this->data['options'] = $this->categories_model->getOptions();
		}
		else
		{
			$this->data['categories'] = false;
		}
		$this->load->model('patient_model');
		$this->data['banner'] =$this->patient_model->getBanner();
		$this->data['text']=$this->patient_model->getText();
		$this->load->view('frontend/includes/header', $this->data);
		$this->load->view('frontend/main/index', $this->data);
		//$this->load->view('frontend/login_register/packages', $this->data);
	//	$this->load->view('frontend/main/login_dummy', $this->data);
		$this->load->view('frontend/includes/footer', $this->data);	
	}
	
	public function packages(){
	$this->load->view('frontend/includes/header', $this->data);	
	$this->load->view('frontend/login_register/packages');
	$this->load->view('frontend/includes/footer', $this->data);	
//	$this->load->view('frontend/main/index', $this->data);
	//$this->load->view('frontend/main/index', $this->data);
		
	}
	public function login(){
	$this->load->view('frontend/includes/header', $this->data);	
	$this->load->view('frontend/login_register/login');
	$this->load->view('frontend/includes/footer', $this->data);	
		
	}
	
	
	public function thankyou($msg = '')
	{
		if(!empty($msg))
			$this->data['message'] = $msg;
		$this->load->view('frontend/includes/header', $this->data);
		$this->load->view('frontend/main/thankyou', $this->data);
	//	$this->load->view('frontend/includes/footer', $this->data);
	}
	
	public function categories($category_id = '')
	{
		if(empty($category_id))
		{
			redirect('main/index');
		}
		
		$this->data['currentCategory'] = $category_id;
		$this->data['options'] = $this->categories_model->getOptions();
		$this->data['categories'] = $this->categories_model->getSubCategoriesByParentID($category_id, true);
		$this->data['parentCategory'] = $this->categories_model->getCategoryByCategoryID($category_id);
		
		$this->load->view('frontend/includes/header', $this->data);
		$this->load->view('frontend/main/categories', $this->data);
		$this->load->view('frontend/includes/footer', $this->data);
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
					if(isset($this->data['session']['cart'][$productKey]))
					{
						$this->data['session']['cart'][$productKey]['quantity'] = $this->data['session']['cart'][$productKey]['quantity'] + $quantity;
					}
					else
					{
						$cart_product = $this->categories_model->getProductByProductID($post['product']);
						if($cart_product)
						{
							$product_details = array('categotyName' => $cart_product['categoryName'], 'productName' => $cart_product['productName'], 'QBcode' => $cart_product['QBcode'], 'quantity' => $quantity, 'productPrice' => ($cart_product['price']/$quantity));
							$this->data['session']['cart'][$productKey] = $product_details;
						}
					}
					$this->session->set_userdata($this->data['session']);
				}
				$this->load->view('frontend/includes/sidecart', $this->data);
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
	
	public function addDeliveryType()
	{
		if($this->isAjaxRequest())
		{
			$this->load->helper('form');
			if($this->input->post())
			{
				$post = $this->input->post();
				if( isset($post['deliveryType']))
				{
					$this->data['session']['deliveryType'] = $post['deliveryType'];
					$this->session->set_userdata($this->data['session']);
				}
				$this->load->view('frontend/includes/sidecart', $this->data);
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
	
	public function addPaymentType()
	{
		if($this->isAjaxRequest())
		{
			$this->load->helper('form');
			if($this->input->post())
			{
				$post = $this->input->post();
				if( isset($post['paymentType']))
				{
					$this->data['session']['paymentType'] = $post['paymentType'];
					$this->session->set_userdata($this->data['session']);
				}
				$this->load->view('frontend/includes/sidecart', $this->data);
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
	/*public function samplecart($id)
	{
		$this->db->select('categoryID');
		$this->db->from('products');
		$this->db->where('productID',$id);
		$r=$this->db->get();
		$r=$r->result_array();
		$categoryID=$r[0]['categoryID'];

		$this->db->select('parentID');
		$this->db->from('categories');
		$this->db->where('categoryID',$categoryID);
		$r=$this->db->get();
		$r=$r->result_array();
		$maincategory=$r[0]['parentID'];
		
		$post = array('maincategory'=>$maincategory,'category'=>$categoryID,'product'=>$id);
		//echo "<pre>";print_r($post);exit;
		if( isset($post['maincategory']) && !empty($post['maincategory']) && isset($post['category']) && !empty($post['category']) && isset($post['product']) && !empty($post['product']))
		{
			$productKey = $post['maincategory'].':'.$post['category'].':'.$post['product'];
			if(isset($this->data['session']['cart'][$productKey]))
			{
				$this->data['session']['cart'][$productKey]['quantity'] = $this->data['session']['cart'][$productKey]['quantity'] + 1;
			}
			else
			{
				$cart_product = $this->categories_model->getProductByProductID($post['product']);
				if($cart_product)
				{
					$product_details = array('categotyName' => $cart_product['categoryName'], 'productName' => $cart_product['productName'], 'QBcode' => $cart_product['QBcode'], 'quantity' => 1, 'productPrice' => $cart_product['price']);
					$this->data['session']['cart'][$productKey] = $product_details;
				}
			}
			$this->session->set_userdata($this->data['session']);
		}
		else
		{
			echo "Pichhoda: No details";
		}
		redirect(base_url());
	}*/
	
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
					if(isset($this->data['session']['cart'][$productKey]))
					{
						unset($this->data['session']['cart'][$productKey]);
					}
					$this->session->set_userdata($this->data['session']);
				}
				$this->load->view('frontend/includes/sidecart', $this->data);
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
		$this->session->unset_userdata('cart');
		$this->session->unset_userdata('creditData');
		$this->data['session'] = $this->session->all_userdata();		
	}
	
	public function checkusername()
	{
		if($this->isAjaxRequest())
		{
			$this->load->helper('form');
			if($this->input->post())
			{
				$post = $this->input->post();
				if( isset($post['userName']) && !empty($post['userName']) )
				{
					$this->load->model('patient_model');		
					if($this->patient_model->checkPatientUsername($post['userName']))
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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */