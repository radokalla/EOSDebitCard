<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'controllers/admin.php');
class Admincategories extends Admin {
	
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
	
	public function maincategories($currentPage = 0)
	{
		$condition_array = array();
		if($this->input->post())
		{
			$post = $this->input->post();
						
			if(isset($post['categoryName']) && !empty($post['categoryName']))
			$condition_array['categoryName'] = $post['categoryName'];	
				
			if(isset($post['recordPerPage']) && !empty($post['recordPerPage']))
				$this->recordsperpage = $post['recordPerPage'];
		}
		
		$condition_array['limit'] = $currentPage;
		$condition_array['recordsperpage'] = $this->recordsperpage;
		//echo "<pre>"; print_r($condition_array); exit;
		
		$this->load->model('categories_model');
		$this->data['categoriesCount'] = $this->categories_model->getAllCategoriesCount($condition_array);
		$this->data['categories'] = $this->categories_model->getAllCategories($condition_array);
		$this->data['recordsperpage'] = $this->recordsperpage;
		
		$this->load->library('pagination');
		$config['base_url'] = base_url('index.php/admincategories/maincategories/');
		$config['total_rows'] = $this->data['categoriesCount'];
		$config['per_page'] = $this->recordsperpage; 		
		$this->pagination->initialize($config); 		
		$this->data['paginationLinks'] = $this->pagination->create_links();
				
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/categories/maincategories', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	}
	
	public function subcategories($currentPage = 0)
	{
		$condition_array = array();
		if($this->input->post())
		{
			$post = $this->input->post();
						
			if(isset($post['categoryName']) && !empty($post['categoryName']))
			$condition_array['categoryName'] = $post['categoryName'];	
			
			if(isset($post['productName']) && !empty($post['productName']))
			$condition_array['productName'] = $post['productName'];	
				
			if(isset($post['recordPerPage']) && !empty($post['recordPerPage']))
				$this->recordsperpage = $post['recordPerPage'];
		}
		
		$condition_array['limit'] = $currentPage;
		$condition_array['recordsperpage'] = $this->recordsperpage;
		//echo "<pre>"; print_r($condition_array); exit;
		
		/*$this->load->model('categories_model');
		$this->data['categoriesCount'] = $this->categories_model->getAllCategoriesCount($condition_array);
		$this->data['categories'] = $this->categories_model->getAllCategories($condition_array);*/
		$this->data['recordsperpage'] = $this->recordsperpage;
		
		
		$this->load->model('categories_model');		
		$this->data['mainCategories'] = $this->categories_model->getMainCategories();
		$this->data['options'] = $this->categories_model->getOptions();
		$this->data['categoriesCount'] = $this->categories_model->getAllSubCategoriesCount($condition_array);
		$this->data['categories'] = $this->categories_model->getAllSubCategories($condition_array);
		
		
		$this->load->library('pagination');
		$config['base_url'] = base_url('index.php/admincategories/subcategories/');
		$config['total_rows'] = $this->data['categoriesCount'];
		$config['per_page'] = $this->recordsperpage; 		
		$this->pagination->initialize($config); 		
		$this->data['paginationLinks'] = $this->pagination->create_links();
		
		
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/categories/subcategories', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	}
	
	
	public function addcategory($category_id = '')
	{
		$this->load->helper('form');
		$this->load->model('categories_model');	
		
		if($this->input->post())
		{
			if($this->input->post('category_id'))
			{
				$this->categories_model->updateCategory($this->input->post());
			}
			else
			{
				$this->categories_model->insertCategory($this->input->post());
			}
			redirect('admincategories/maincategories'); 
			exit;
		}
		
		if(!empty($category_id))
			$this->data['category'] = $this->categories_model->getCategoryByCategoryID($category_id);
		else if($this->input->post())
			$this->data['category'] = $this->input->post();
		else
			$this->data['category'] = array();
			
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/categories/addcategory', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	}
	
	
	public function addsubcategory($category_id = '')
	{
		$this->load->helper('form');
		$this->load->model('categories_model');	
		
		if($this->input->post())
		{
			if($this->input->post('category_id'))
			{
				$this->categories_model->updateCategory($this->input->post());
			}
			else
			{
				$this->categories_model->insertCategory($this->input->post());
			}
			redirect('admincategories/subcategories'); 
			exit;
		}
		
		if(!empty($category_id))
			$this->data['category'] = $this->categories_model->getSubCategoriesByCategoryID($category_id);
		else if($this->input->post())
			$this->data['category'] = $this->input->post();
		else
			$this->data['category'] = array();
		
		$this->data['options'] = $this->categories_model->getOptions();
		$this->data['categories'] = $this->categories_model->getMainCategories(array('isActive'=>1));
			
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/categories/addsubcategory', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
	}
	
	public function deletecategory($category_id = '')
	{
		$this->load->model('categories_model');	
		
		if(!empty($category_id))
			$this->data['category'] = $this->categories_model->deleteCategory(array('category_id' => $category_id));
			
		redirect('admincategories/maincategories'); 
		exit;
	}
	
	public function deletesubcategory($category_id = '')
	{
		$this->load->model('categories_model');	
		
		if(!empty($category_id))
			$this->data['category'] = $this->categories_model->deleteCategory(array('category_id' => $category_id));
			
		redirect('admincategories/subcategories'); 
		exit;
	}
	
	function updateCategorStatus()
	{
		if($this->isAjaxRequest())
		{
			$this->load->helper('form');
			if($this->input->post())
			{
				$post = $this->input->post();
				if( isset($post['categoryID']) && !empty($post['categoryID']) )
				{
					$this->load->model('categories_model');		
					$this->categories_model->updateCategorStatus($post['categoryID'], $post['status']);
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
	
	function updateCategoryVisibility()
	{
		if($this->isAjaxRequest())
		{
			$this->load->helper('form');
			if($this->input->post())
			{
				$post = $this->input->post();
				if( isset($post['categoryID']) && !empty($post['categoryID']) )
				{
					$this->load->model('categories_model');		
					$this->categories_model->updateCategoryVisibility($post['categoryID'], $post['status']);
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
	
	function updateSubCategoryStatus()
	{
		if($this->isAjaxRequest())
		{
			$this->load->helper('form');
			if($this->input->post())
			{
				$post = $this->input->post();
				if( isset($post['categoryID']) && !empty($post['categoryID']) )
				{
					$this->load->model('categories_model');		
					$this->categories_model->updateSubCategoryStatus($post['categoryID'], $post['status']);
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
	
	
	public function uploadImage(){
		
		require_once("SimpleImage.class.php");
		$path = "uploaded/product_images/";
		$allowTypes = array('xls','xlsx','pdf','csv', "bmp");
		$valid_formats = array("jpg", "png", "gif","jpeg","ico");
		list($id,$fieldname)=explode("_",$_REQUEST['page']);
		$str="";$imgpath = '';$error = "";$msg = "";
		
		$fileid		   = $_REQUEST['name'];
		$filename      = str_replace('-','_',$_REQUEST['name']);
		$arr		   = explode('-',$fileid);	
		$imgsetpath	   = $arr[0].'-'.$arr[1].'-imgPath';
			
		$successmssgid = $fileid.'-success-mssg';
		$errormssgid   = $fileid."-error-mssg";
		
		if(!empty($_FILES[$filename]['error']))
		{
			switch($_FILES[$filename]['error'])
			{
		
				case '1':
					$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$error = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$error = 'No file was uploaded.';
					break;
		
				case '6':
					$error = 'Missing a temporary folder';
					break;
				case '7':
					$error =  'Failed to write file to disk';
					break;
				case '8':
					$error = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$error = 'No error code avaiable';
			}
		}elseif(empty($_FILES[$filename]['tmp_name']) || $_FILES[$filename]['tmp_name'] == 'none')
		{
			$error ='No file was uploaded.'.implode(',',$_FILES[$filename]);
		}else 
		{
			$message = '';
			$extension 	 =	 end(explode(".", $_FILES[$filename]["name"]));
			
				if(getimagesize($_FILES[$filename]['tmp_name'])){
			
					$imgname 	 =	 'bayfront-image-'.time();	
						
					$img_path	 =	 $path.$imgname.'.'.$extension;
					$org_img_path =  $path.'original/'.$imgname.'.'.$extension;
					$temp_path	 = 	 $_FILES[$filename]['tmp_name'];
					
					$msg = 'Image Path:'.$img_path;
					
					//code for resizing image	
					$image = new SimpleImage();
			
					$image->load($temp_path);
					$image->save($org_img_path);
					
					/*$image->crop(210,210);
					$image->save($img_path);*/
					
					$image->save($img_path);
						$x ='0';  $height_sts = false;
						$y = '0'; $width_sts  = false;
						
						$imgwidth = 227; $imgheight = 243;
						
						$upImgWidth=$image->getWidth(); $upImgHeight=$image->getHeight();
						//Thumbnail Image
						if(($upImgWidth  > $imgwidth) || ($upImgHeight > $imgheight)){
							if($upImgWidth  > $imgwidth)	{
								$image->resizeToWidth($imgwidth);
								$image->save($img_path);	
								$image->load($img_path);
							} 
							
							$upImgHeight = $image->getHeight();
							if($upImgHeight > $imgheight)	{
								$image->resizeToHeight($imgheight);	
								$image->save($img_path);
							}
							$image->load($img_path);
							$newheight = $image->getHeight(); $newwidth = $image->getWidth();
							$hdiff = $imgheight - $newheight; $wdiff = $imgwidth - $newwidth;
							echo '<script language="JavaScript" type="text/javascript">'."\n";	
							echo 'console.log("Height:'.$hdiff.'****Width:'.$wdiff.' New Height:'.$newheight.'**** New Width:'.$newwidth.'");';
							echo "\n".'</script>';
						}else{
							$image->save($img_path);
							$hdiff = $imgheight-$upImgHeight; $wdiff = $imgwidth-$upImgWidth;
						}
						echo '<script language="JavaScript" type="text/javascript">'."\n";	
						echo 'console.log("Height:'.$hdiff.'****Width:'.$wdiff.'");';
						echo "\n".'</script>';
						$y= $hdiff>0 ? round($hdiff/2) : 0; $x= $wdiff>0 ? round($wdiff/2) : 0;
						$style = 'margin-left : '.$x.'px; margin-top :  '.$y.'px';
					
					//Thumbnail Image
					//$image->resize(150,150);
//			
//					$image->save($thumb_path);
				}else{
					$error = 'Please upload a valid image';
				}
			
		}	
		
		echo '<script language="JavaScript" type="text/javascript">'."\n";
		echo 'var parDoc = window.parent.document;';
		if(!empty($error)){
			echo "parDoc.getElementById('".$errormssgid."').innerHTML = '".$error."';";
		}else{
			
			echo "parDoc.getElementById('".$imgsetpath."').value = '".$img_path."';";
			
			echo  "parDoc.getElementById('".$imgsetpath."_img').src = '".base_url($img_path)."';";
			echo  "parDoc.getElementById('".$imgsetpath."_a').href = '".base_url($img_path)."';";
			
			echo  "parDoc.getElementById('".$imgsetpath."_img').setAttribute('style', '".$style."');";
			
			echo "parDoc.getElementById('".$errormssgid."').innerHTML = '';";
			
			if($_REQUEST['filetype']=='file'){
				//echo "parDoc.getElementById('".$successmssgid."').innerHTML = 'File uploaded successfully';";
			}else{
				//echo "parDoc.getElementById('".$successmssgid."').innerHTML = '<img src=\"".base_url($img_path)."\" width=\"50\" height=\"50\">';";
			}
		}
		echo "\n".'</script>';
		exit(); // do not go futher
	}
	
	
}
