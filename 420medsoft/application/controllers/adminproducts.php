<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



require_once(APPPATH.'controllers/admin.php');

class Adminproducts extends Admin {

	

	public $data = array();

	

	public function __construct()

	{

		parent::__construct();

		//$this->output->clear_page_cache();

		$this->isLoggedIn();

		$this->data['session'] = $this->session->all_userdata();

	}

	

	public function products()

	{

		$this->load->model('categories_model');		

		

		

		$this->data['categories'] = $this->categories_model->getMainCategories();

		//$this->data['categories'] = $this->categories_model->getSubCategoriesByParentID();

		

		//echo "<pre>"; print_r($this->data['categories']); exit;

		

		$this->load->view('admin/includes/header', $this->data);

		$this->load->view('admin/products/products', $this->data);

		$this->load->view('admin/includes/footer', $this->data);

	}

	
	public function QrCodes($id)
	{
		if($id)
			$ids=explode('_',$id);
		$id=$ids[0];
		$pid=$ids[1];
		$this->load->model('categories_model');		
		$r=$this->categories_model->getSubCategoriesByParentID($pid, false);
		//echo "<pre>"; print_r($r); exit;
		$products=$r[$id]['products'];
		$srno=0;
		$this->load->library('qr');
		foreach($products as $productId=>$product) {
			$text=base_url('index.php/adminorders/samplecart/'.$productId);
			//$imgs[$srno]['$text']=$text;
			$imgs[$srno]['image']=$this->qr->Generate($text,8,1,'L');
			$imgs[$srno]['qr_url']=$this->qr->GetShortUrl($text);
			$imgs[$srno]['productName']=$product['productName'];
			$imgs[$srno]['QBcode']=$product['QBcode'];
			$imgs[$srno]['price']=$product['price'];
			$srno++;
		}
		$data['categoryName']=$r[$id]['categoryName'];
		$data['images']=$imgs;
		$data['currency']=$this->data['currency'];
		$data['sub_cat_text']='CBD '.$r[$id]['options'][2].'% THC '.$r[$id]['options'][3].'% THCA '.$r[$id]['options'][4].'%';
		
		$this->load->view('admin/products/qrcodes',$data);
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

				$this->data['categories'] = $this->categories_model->getSubCategoriesByParentID($category_id, false);
				$this->load->view('admin/products/getallsubcategories', $this->data);

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

	public function getAllSubcategoriesWithSearch()

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
				$this->data['categories'] = $this->categories_model->getSubCategoriesByParentID($category_id, false, $productType, $categoryName);
				$this->data['currentCategory'] = $category_id;
				$this->load->view('admin/products/getallsubcategories', $this->data);

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

	public function updateProducts()

	{

		if($this->isAjaxRequest())

		{

			$this->load->helper('form');

			if($this->input->post())

			{
               
				$post = $this->input->post();
				
				$this->load->model('categories_model');	

				$this->categories_model->updateProducts($post);	

				$category_id = $post['parent_id'];

				$this->data['success'] = 'Product are added/updated successfully.';

				$this->data['options'] = $this->categories_model->getOptions();

				$this->data['categories'] = $this->categories_model->getSubCategoriesByParentID($category_id, false);

				$this->load->view('admin/products/getallsubcategories', $this->data);

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

