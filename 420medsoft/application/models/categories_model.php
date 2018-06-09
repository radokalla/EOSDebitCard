<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



require_once(APPPATH.'models/main_model.php');

class Categories_model extends main_model {

	

	private $tablename = '';

	private $options_tablename = '';	

	private $category_options_tablename = '';

	private $products_tablename = '';

	private $inventory_tablename = '';

	public function __construct()

	{

		parent::__construct();

		$this->tablename = 'categories';

		$this->options_tablename = 'options';

		$this->category_options_tablename = 'categoryoptions';

		$this->products_tablename = 'products';

		$this->inventory_tablename = 'inventory';

	}

	

	public function getMainCategories($array = array())

	{

		$cond_array = array('parentID' => '0'/*, 'isActive' => '1'*/, 'isDeleted' => '0');	

		$cond_array = array_merge($cond_array, $array);	
		$this->db->select('categoryID,categoryName,categoryType,image,categoryDescriptionHeading,categoryDescription,isActive');
		$this->db->where($cond_array);

		$query = $this->db->get($this->tablename);

			

		if($query->num_rows() > 0)

		{

			$return_categories = array();

			$categories = $query->result_array();

			foreach($categories as $category)

			{
				$return_categories[$category['categoryID']] = array('categoryName' => $category['categoryName'], 'categoryType' => $category['categoryType'], 'image' => $category['image'],'categoryDescriptionHeading' => $category['categoryDescriptionHeading'],'categoryDescription' => $category['categoryDescription'], 'isActive' => $category['isActive']);
			}

			return $return_categories;

		}

		else

			return false;

	}

	

	public function getAllCategoriesCount($array = array())

	{

		$cond_array = array('parentID' => '0', 'isDeleted' => '0');	
		$this->db->select('categoryID,categoryName, categoryType, image,categoryDescriptionHeading,categoryDescription,isActive');
		$this->db->where($cond_array);

		if(isset($array['categoryName']) && !empty($array['categoryName']))

			$this->db->like('categoryName', $array['categoryName']);		

		$query = $this->db->get($this->tablename);		

		return $query->num_rows();

	}

	

	public function getAllCategories($array = array())

	{

		$cond_array = array('parentID' => '0', 'isDeleted' => '0');	
		$this->db->select('categoryID,categoryName, categoryType,image,categoryDescriptionHeading,categoryDescription,isActive,isVisible');
		$this->db->where($cond_array);

		if(isset($array['categoryName']) && !empty($array['categoryName']))

			$this->db->like('categoryName', $array['categoryName']);

			
		if(isset($array['isVisible']) && !empty($array['isVisible']))
			$this->db->where('isVisible', $array['isVisible']);
			
		if(isset($array['isActive']) && !empty($array['isActive']))

			$this->db->where('isActive', $array['isActive']);

			

		$this->db->order_by("categoryOrder", "DESC");

		

		if(isset($cond_array['recordsperpage']) && isset($cond_array['limit']))

		$this->db->limit($array['recordsperpage'], $array['limit']);

		

		$query = $this->db->get($this->tablename);
		 
		if($query->num_rows() > 0)

		{

			$return_categories = array();

			$categories = $query->result_array();
	
			foreach($categories as $category)

			{
				$return_categories[$category['categoryID']] = array('categoryName' => $category['categoryName'], 'image' => $category['image'],'categoryDescriptionHeading' => $category['categoryDescriptionHeading'],'categoryDescription' => $category['categoryDescription'], 'isActive' => $category['isActive'], 'isVisible' => $category['isVisible']);
			}

			return $return_categories;

		}

		else

			return false;

	}

	

	public function getCategoriesByParentID($parent_id)

	{

		$cond_array = array('parentID' => $parent_id, 'isActive' => '1', 'isDeleted' => '0');		
		$this->db->select('categoryID,categoryName, categoryType,image');
		$this->db->where($cond_array);

		$query = $this->db->get($this->tablename);

			

		if($query->num_rows() == 1)

		{

			$return_categories = array();

			$categories = $query->result_array();

			foreach($categories as $category)

			{
				$return_categories[$category['categoryID']] = array('categoryName' => $category['categoryName'],'categoryType' => $category['categoryType'], 'image' => $category['image'],'categoryDescriptionHeading' => $category['categoryDescriptionHeading'],'categoryDescription' => $category['categoryDescription']);
				$categoryOptions = $this->getOptionsByCategoryID($category['categoryID']);

				if($categoryOptions)

					$return_categories[$category['categoryID']]['options'] = $categoryOptions;

			}

			return $return_categories;

		}

		else

			return false;

	}

	

	public function getCategoryByCategoryID($category_id)

	{

		$cond_array = array('categoryID' => $category_id,  'isDeleted' => '0');		
		$this->db->select('categoryID,categoryName,categoryType,image,categoryDescriptionHeading,categoryDescription');
		$this->db->where($cond_array);

		$query = $this->db->get($this->tablename);

			
		if($query->num_rows() == 1)

		{

			$categories = $query->row_array();

			$categoryOptions = $this->getOptionsByCategoryID($category_id);

			if($categoryOptions)

				$categories['options'] = $categoryOptions;

			return $categories;

		}

		else

			return false;

	}

	

	public function getOptionsByCategoryID($category_id)

	{

		$cond_array = array('categoryID' => $category_id);		

		$this->db->select('optionID,value');

		$this->db->where($cond_array);

		$query = $this->db->get($this->category_options_tablename);

			

		if($query->num_rows() > 0)

		{

			$return_options = array();

			$options = $query->result_array();

			foreach($options as $option)

			{

				$return_options[$option['optionID']] = $option['value'];

			}

			return $return_options;

		}

		else

			return false;

	}

	

	

	public function insertCategory($array)

	{

		if(!empty($array['categoryName']))

		{

			$image_path = !empty($array['applimagepath']) ? $array['applimagepath'] : '';

			$parentID = !empty($array['parentID']) ? $array['parentID'] : 0;
			$productDescription = !empty($array['productDescription']) ? $array['productDescription'] : '';
			$categoryDescriptionHeading = !empty($array['categoryDescriptionHeading']) ? $array['categoryDescriptionHeading'] : '';

			$categoryDescription = !empty($array['categoryDescription']) ? $array['categoryDescription'] : '';			
			$insert_array = array('categoryName' => $array['categoryName'], 'categoryType' => $array['categoryType'], 'image' => $image_path, 'parentID' => $parentID, 'categoryDescriptionHeading' => $categoryDescriptionHeading, 'categoryDescription' => $categoryDescription, 'isActive' => '1', 'isDeleted' => '0','productDescription'=>$productDescription );
			$this->db->insert($this->tablename, $insert_array);

			$categoryID = $this->db->insert_id();

			if(isset($array['options']))

			{

				foreach($array['options'] as $optionID => $value)

				{

					$insert_option_array = array('categoryID' => $categoryID, 'optionID' => $optionID, 'value' => $value);

					$this->db->insert($this->category_options_tablename, $insert_option_array);

				}

			}

			return $categoryID;

		}

		else

			return false;

	}

	

	

	public function updateCategory($array)

	{

		if(!empty($array['categoryName']) && !empty($array['category_id']))

		{

			$image_path = !empty($array['applimagepath']) ? $array['applimagepath'] : '';

			$parentID = !empty($array['parentID']) ? $array['parentID'] : 0;
			$productDescription = !empty($array['productDescription']) ? $array['productDescription'] : '';
			$categoryDescriptionHeading = !empty($array['categoryDescriptionHeading']) ? $array['categoryDescriptionHeading'] : '';

			$categoryDescription = !empty($array['categoryDescription']) ? $array['categoryDescription'] : '';				
			$insert_array = array('categoryName' => $array['categoryName'], 'categoryType' => $array['categoryType'], 'image' => $image_path, 'parentID' => $parentID, 'categoryDescriptionHeading' => $categoryDescriptionHeading, 'categoryDescription' => $categoryDescription ,'productDescription'=>$productDescription);
			$cond_array = array('categoryID' => $array['category_id']);

			$this->db->update($this->tablename, $insert_array, $cond_array);

			$categoryID =  $array['category_id'];

			if(isset($array['options']))

			{

				$this->db->delete($this->category_options_tablename, array('categoryID' => $categoryID));

				foreach($array['options'] as $optionID => $value)

				{

					$insert_option_array = array('categoryID' => $categoryID, 'optionID' => $optionID, 'value' => $value);

					$this->db->insert($this->category_options_tablename, $insert_option_array);

				}

			}

			return $categoryID;

		}

		else

			return false;

	}

	

	

	public function deleteCategory($array)

	{

		if(!empty($array['category_id']))

		{

			$insert_array = array('isDeleted' => '1' );

			$cond_array = array('categoryID' => $array['category_id']);

			$this->db->update($this->tablename, $insert_array, $cond_array);

			return $array['category_id'];

		}

		else

			return false;

	}

	

	

	public function getOptions()

	{		

		$this->db->select('optionID,optionType,type');

		$query = $this->db->get($this->options_tablename);

			

		if($query->num_rows() > 0)

		{

			$return_options = array();

			$options = $query->result_array();

			foreach($options as $option)

			{

				$return_options[$option['optionID']] = array('optionType' => $option['optionType'], 'type' => $option['type']);

			}

			return $return_options;

		}

		else

			return false;

	}

	

	public function getSubCategories()

	{

		$cond_array = array('subcategory.parentID !=' => '0', 'subcategory.isDeleted' => '0', 'parentcategory.isDeleted' => '0');		
		$this->db->select('subcategory.categoryID,subcategory.categoryName,subcategory.categoryType,subcategory.image,subcategory.isActive,subcategory.parentID,parentcategory.categoryName as parentName');
		$this->db->from($this->tablename .' as subcategory');

		$this->db->join($this->tablename .' as parentcategory', 'subcategory.parentID = parentcategory.categoryID');

		$this->db->where($cond_array);

		$query = $this->db->get();

		

		if($query->num_rows() > 0)

		{

			$return_categories = array();

			$categories = $query->result_array();

			foreach($categories as $category)

			{
				$return_categories[$category['categoryID']] = array('categoryName' => $category['categoryName'],'categoryType' => $category['categoryType'],'parentID' => $category['parentID'],'parentName' => $category['parentName'], 'image' => $category['image'], 'isActive' => $category['isActive']);
			}

			return $return_categories;

		}

		else

			return false;

	}

	

	

	

	public function getAllSubCategoriesCount($array = array())

	{

		$cond_array = array('subcategory.parentID !=' => '0', 'subcategory.isDeleted' => '0', 'parentcategory.isDeleted' => '0');		
		$this->db->select('subcategory.categoryID,subcategory.categoryName,subcategory.categoryType,subcategory.image,subcategory.isActive,subcategory.parentID,parentcategory.categoryName as parentName');
		$this->db->from($this->tablename .' as subcategory');

		$this->db->join($this->tablename .' as parentcategory', 'subcategory.parentID = parentcategory.categoryID');

		$this->db->where($cond_array);

		

		$this->db->where($cond_array);

		

		/*if(isset($array['categoryName']) && !empty($array['categoryName']))

			$this->db->where('subcategory.parentID', $array['categoryName']);*/

		if(isset($array['categoryName']) && !empty($array['categoryName']))

			$this->db->like('parentcategory.categoryName', $array['categoryName']);

		if(isset($array['productName']) && !empty($array['productName']))

			$this->db->like('subcategory.categoryName', $array['productName']);

		

		$query = $this->db->get();

		

		return $query->num_rows();

	}

	

	public function getAllSubCategories($array = array())

	{

		$cond_array = array('subcategory.parentID !=' => '0', 'subcategory.isDeleted' => '0', 'parentcategory.isDeleted' => '0');		
		$this->db->select('subcategory.categoryID,subcategory.categoryName,subcategory.categoryType,subcategory.image,subcategory.isActive,subcategory.parentID,parentcategory.categoryName as parentName');
		$this->db->from($this->tablename .' as subcategory');

		$this->db->join($this->tablename .' as parentcategory', 'subcategory.parentID = parentcategory.categoryID');

		$this->db->where($cond_array);

		

		$this->db->where($cond_array);

		

		if(isset($array['categoryName']) && !empty($array['categoryName']))

			$this->db->like('parentcategory.categoryName', $array['categoryName']);

		if(isset($array['productName']) && !empty($array['productName']))

			$this->db->like('subcategory.categoryName', $array['productName']);

			

		$this->db->order_by("categoryID", "DESC");

		$this->db->limit($array['recordsperpage'], $array['limit']);

		

		$query = $this->db->get();

		

		if($query->num_rows() > 0)

		{

			$return_categories = array();

			$categories = $query->result_array();

			foreach($categories as $category)

			{
				$return_categories[$category['categoryID']] = array('categoryName' => $category['categoryName'],'categoryType' => $category['categoryType'],'parentID' => $category['parentID'],'parentName' => $category['parentName'], 'image' => $category['image'], 'isActive' => $category['isActive']);
			}

			return $return_categories;

		}

		else

			return false;

	}
	public function getSubCategoriesByParentID($parentID = 0, $isActive = true, $category_type = 'all', $category_name = '')
	{

		$cond_array = array('subcategory.parentID' => $parentID, 'subcategory.isDeleted' => '0');	/*, 'parentcategory.isDeleted' => '0'*/	
		if($isActive)
			$cond_array['subcategory.isActive'] = 1;
		
		
		$this->db->select('subcategory.categoryID, subcategory.categoryName, subcategory.productDescription,subcategory.categoryType, subcategory.image, subcategory.categoryDescriptionHeading, subcategory.categoryDescription, subcategory.isActive, subcategory.parentID, parentcategory.categoryName as parentName');
		$this->db->from($this->tablename .' as subcategory');

		$this->db->join($this->tablename .' as parentcategory', 'subcategory.parentID = parentcategory.categoryID', 'left');

		$this->db->where($cond_array);
		
		if($category_type != 'all' && !empty($category_type))
		{
			$this->db->where("(`subcategory`.categoryType = '$category_type' OR `subcategory`.categoryType = 'Unassigned')");
		}
		
		if(!empty($category_name))
			$this->db->like('subcategory.categoryName', $category_name);
		$query = $this->db->get();
		
		if($query->num_rows() > 0)

		{

			$return_categories = array();

			$categories = $query->result_array();

			foreach($categories as $category)

			{
				$return_categories[$category['categoryID']] = array('categoryName' => $category['categoryName'],'categoryType' => $category['categoryType'],'parentID' => $category['parentID'],'parentName' => $category['parentName'], 'image' => $category['image'], 'categoryDescriptionHeading' => $category['categoryDescriptionHeading'], 'categoryDescription' => $category['categoryDescription'], 'isActive' => $category['isActive'],'productDescription'=> $category['productDescription']);
				$categoryOptions = $this->getOptionsByCategoryID($category['categoryID']);

				if($categoryOptions)

					$return_categories[$category['categoryID']]['options'] = $categoryOptions;

					

				$categoryProducts = $this->getProductsByCategoryID($category['categoryID'],$isActive);

				if($categoryProducts)

					$return_categories[$category['categoryID']]['products'] = $categoryProducts;

				

				$subcategories = $this->getSubCategoriesByParentID($category['categoryID']);

				if($subcategories)

					$return_categories[$category['categoryID']]['subcategories'] = $subcategories;

			}

			return $return_categories;

		}

		else

			return false;

	}

	

	public function getSubCategoriesByCategoryID($category_id = '')

	{

		$cond_array = array('subcategory.parentID !=' => '0', 'subcategory.isDeleted' => '0', 'parentcategory.isDeleted' => '0');	

		if(!empty($category_id))

			$cond_array['subcategory.categoryID'] = $category_id;			
		$this->db->select('subcategory.categoryID,subcategory.categoryName,subcategory.categoryType,subcategory.image,subcategory.isActive,subcategory.parentID,parentcategory.categoryName as parentName,subcategory.productDescription');
		$this->db->from($this->tablename .' as subcategory');

		$this->db->join($this->tablename .' as parentcategory', 'subcategory.parentID = parentcategory.categoryID');

		$this->db->where($cond_array);

		$query = $this->db->get();

		

		if($query->num_rows() == 1)

		{

			$return_categories = array();

			$category = $query->row_array();

		
			$return_categories = array('categoryID' => $category['categoryID'],'categoryName' => $category['categoryName'],'categoryType' => $category['categoryType'],'parentID' => $category['parentID'],'parentName' => $category['parentName'], 'image' => $category['image'], 'isActive' => $category['isActive'],'productDescription'=>$category['productDescription']);
			$categoryOptions = $this->getOptionsByCategoryID($category['categoryID']);

			if($categoryOptions)

				$return_categories['options'] = $categoryOptions;

			$categoryProducts = $this->getProductsByCategoryID($category['categoryID']);

			if($categoryProducts)

				$return_categories['products'] = $categoryProducts;

				

			return $return_categories;

		}

		else

			return false;

	}

	

	

	

	public function getProductsByCategoryID($category_id,$isactive=true)

	{
        if($isactive)
		$cond_array = array('categoryID' => $category_id, 'isActive' => 1, 'isDeleted' => 0);
		else
		 $cond_array = array('categoryID' => $category_id, 'isDeleted' => 0);
		$this->db->select('productID,QBcode,productName,price,isActive');

		$this->db->where($cond_array);

		$query = $this->db->get($this->products_tablename);

		

		if($query->num_rows() > 0)

		{

			$return_products = array();

			$products = $query->result_array();

			foreach($products as $product)

			{

				$return_products[$product['productID']] = array('productName' => $product['productName'], 'QBcode' => $product['QBcode'], 'price' => $product['price'], 'isActive' => $product['isActive']);

			}

			return $return_products;

		}

		else

			return false;

	}

	

	public function getProductByProductID($product_id)

	{

		$cond_array = array('product.productID' => $product_id, 'product.isActive' => 1, 'product.isDeleted' => 0);		

		$this->db->select('product.productID,product.categoryID,category.categoryName,category.parentID,product.productName,product.QBcode,product.price');

		$this->db->from($this->products_tablename .' as product');

		$this->db->join($this->tablename .' as category', 'product.categoryID = category.categoryID', 'inner');

		$this->db->where($cond_array);

		$query = $this->db->get();

		

		if($query->num_rows() == 1)

		{

			$product = $query->row_array();

			return $product;

		}

		else

			return false;

	}

	

	public function updateProducts($post)

	{
 
		foreach($post['products'] as $categoryID => $products)

		{

			foreach($products as $productDetails)

			{

				if(!empty($productDetails['name']) && !empty($productDetails['price']))

				{

					if( isset($productDetails['productID']) && !empty($productDetails['productID']) )

					{
                        $isactive=isset($productDetails['isActive']) ? 1 :0;
						$insert_array = array('productName' => $productDetails['name'], 'QBcode' => $productDetails['QBcode'], 'price' => $productDetails['price'], 'isActive' =>   $isactive);

						$cond_array = array('categoryID' => $categoryID, 'productID' => $productDetails['productID']);

						$this->db->update($this->products_tablename, $insert_array, $cond_array);

					}

					else

					{

						$insert_array = array('categoryID' => $categoryID, 'QBcode' => $productDetails['QBcode'], 'productName' => $productDetails['name'], 'price' => $productDetails['price'], 'isActive' => '1', 'isDeleted' => '0');

						$this->db->insert($this->products_tablename, $insert_array);

					}

				}

			}

		}

		return $categoryID;

	}

	

	

	

	public function updateCategorStatus($categoryID, $status = 0)

	{
		
		$cond_arr = array('categoryID' => $categoryID);

		$update_arr = array('isActive' => $status);

		$this->db->update($this->tablename, $update_arr, $cond_arr);

	}

	
	public function updateCategoryVisibility($categoryID, $status = 0)
	{
		$cond_arr = array('categoryID' => $categoryID);
		$update_arr = array('isVisible' => $status);
		$this->db->update($this->tablename, $update_arr, $cond_arr);
	}
	
	public function insertInventory($array)

	{

		if(!empty($array['inventory']))

		{

			$insert_array = array('productID' => $array['productID'],'inventory' => $array['inventory'] );

			$this->db->insert($this->inventory_tablename, $insert_array);

			return true;

		}

		else

			return false;

	}
	public function updateInventory($array)
	{
		if(!empty($array['inventory']))
		{
			$insert_array = array('inventory' => $array['inventory'] );
			$condition_array = array('inventoryID' => $array['inventoryID'] );
			$this->db->update($this->inventory_tablename, $insert_array, $condition_array);
			return true;
		}
		else
			return false;
	}
	

	public function insertempInventory($array)

	{
		/*
		if(!empty($array['inventory']))

		{

			//echo "<pre>";print_r($array);exit;

			$insert_array = array('productID' => $array['productID'],'inventory' => $array['inventory'],'empID' => $array['employeeID'] );

			$this->db->insert('empinventory', $insert_array);

			return true;

		}

		else

			return false;
		*/
		
		if(isset($array['request']) && isset($array['employeeID']) && !empty($array['employeeID']))
		{
			foreach($array['request'] as $request)
			{
				$insert_array = array('productID' => $request['productID'], 'inventory' => $request['inventory'], 'empID' => $array['employeeID'] );
				$this->db->insert('empinventory', $insert_array);
			}
			return true;
		}
		else
			return false;
	}

	
	public function updateempInventory($array)
	{
		if(!empty($array['inventory']))
		{
			$insert_array = array('inventory' => $array['inventory'] );
			$condition_array = array('inventoryID' => $array['inventoryID'] );
			$this->db->update('empinventory', $insert_array, $condition_array);
			return true;
		}
		else
			return false;
	}
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function getAllInventoryDetailsCount($array = array())
	{
		$this->db->select('invetory.productID, maincategory.categoryName as mainCategoryName, category.categoryName, product.productName, inventory as totalStock');
		$this->db->from($this->products_tablename .' as product');
		$this->db->join($this->inventory_tablename .' as invetory', 'invetory.productID = product.productID', 'inner');
		$this->db->join($this->tablename .' as category', 'product.categoryID = category.categoryID', 'inner');
		$this->db->join($this->tablename .' as maincategory', 'category.parentID = maincategory.categoryID', 'inner');
		
		if(isset($array['mainCategoryName']) && !empty($array['mainCategoryName']))
			$this->db->like('maincategory.categoryName', $array['mainCategoryName']);
		
		if(isset($array['categoryName']) && !empty($array['categoryName']))
			$this->db->like('category.categoryName', $array['categoryName']);
		
		if(isset($array['productName']) && !empty($array['productName']))
			$this->db->like('product.productName', $array['productName']);
		
		if(isset($array['productID']) && !empty($array['productID']))
			$this->db->where('invetory.productID', $array['productID']);
			
		$this->db->order_by("invetory.productID", "DESC");
		
		$query = $this->db->get();
		
		return $query->num_rows();
	}
	
	public function getAllInventoryDetails($array = array())
	{
		$this->db->select('invetory.inventoryID, invetory.productID, maincategory.categoryName as mainCategoryName, category.categoryName, product.productName, inventory as totalStock');
		$this->db->from($this->products_tablename .' as product');
		$this->db->join($this->inventory_tablename .' as invetory', 'invetory.productID = product.productID', 'inner');
		$this->db->join($this->tablename .' as category', 'product.categoryID = category.categoryID', 'inner');
		$this->db->join($this->tablename .' as maincategory', 'category.parentID = maincategory.categoryID', 'inner');
		if(isset($array['inventoryID']) && !empty($array['inventoryID']))
			$this->db->where('invetory.inventoryID', $array['inventoryID']);
		if(isset($array['mainCategoryName']) && !empty($array['mainCategoryName']))
			$this->db->like('maincategory.categoryName', $array['mainCategoryName']);
		if(isset($array['categoryName']) && !empty($array['categoryName']))
			$this->db->like('category.categoryName', $array['categoryName']);
		if(isset($array['productName']) && !empty($array['productName']))
			$this->db->like('product.productName', $array['productName']);
		if(isset($array['productID']) && !empty($array['productID']))
			$this->db->where('invetory.productID', $array['productID']);
		$this->db->order_by("invetory.productID", "DESC");		
		if(isset($array['recordsperpage']) && isset($array['limit']))
		$this->db->limit($array['recordsperpage'], $array['limit']);
		
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$product = $query->result_array();
			return $product;
		}
		else
			return false;
	}
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	public function getInventoryDetailsCount($array = array())

	{

		$this->db->select('invetory.productID, maincategory.categoryName as mainCategoryName, category.categoryName, product.productName, SUM(inventory) as totalStock');

		$this->db->from($this->products_tablename .' as product');

		$this->db->join($this->inventory_tablename .' as invetory', 'invetory.productID = product.productID', 'inner');

		$this->db->join($this->tablename .' as category', 'product.categoryID = category.categoryID', 'inner');

		$this->db->join($this->tablename .' as maincategory', 'category.parentID = maincategory.categoryID', 'inner');

		

		$this->db->group_by("productID");

		

		if(isset($array['mainCategoryName']) && !empty($array['mainCategoryName']))

			$this->db->like('maincategory.categoryName', $array['mainCategoryName']);

		

		if(isset($array['categoryName']) && !empty($array['categoryName']))

			$this->db->like('category.categoryName', $array['categoryName']);

		

		if(isset($array['productName']) && !empty($array['productName']))

			$this->db->like('product.productName', $array['productName']);

		

		if(isset($array['productID']) && !empty($array['productID']))

			$this->db->where('invetory.productID', $array['productID']);

			

		$this->db->order_by("invetory.productID", "DESC");

		

		$query = $this->db->get();

		

		return $query->num_rows();

	}

	
	
	public function getInventoryDetails($array = array())

	{

		$this->db->select('invetory.productID, maincategory.categoryName as mainCategoryName, category.categoryName, product.productName, SUM(inventory) as totalStock');

		$this->db->from($this->products_tablename .' as product');

		$this->db->join($this->inventory_tablename .' as invetory', 'invetory.productID = product.productID', 'inner');
		$this->db->join($this->tablename .' as category', 'product.categoryID = category.categoryID and category.isActive=1', 'inner');
		$this->db->join($this->tablename .' as maincategory', 'category.parentID = maincategory.categoryID', 'inner');

		

		$this->db->group_by("productID");

		

		if(isset($array['mainCategoryName']) && !empty($array['mainCategoryName']))

			$this->db->like('maincategory.categoryName', $array['mainCategoryName']);

		

		if(isset($array['categoryName']) && !empty($array['categoryName']))

			$this->db->like('category.categoryName', $array['categoryName']);

		

		if(isset($array['productName']) && !empty($array['productName']))

			$this->db->like('product.productName', $array['productName']);

		

		if(isset($array['productID']) && !empty($array['productID']))

			$this->db->where('invetory.productID', $array['productID']);

			

		$this->db->order_by("invetory.productID", "DESC");

		

		if(isset($cond_array['recordsperpage']) && isset($cond_array['limit']))

		$this->db->limit($array['recordsperpage'], $array['limit']);

		

		$query = $this->db->get();

		

		if($query->num_rows() > 0)

		{

			$product = $query->result_array();

			return $product;

		}

		else

			return false;

	}

	

	public function getempInventoryDetailsCount($array = array())

	{

		$this->db->select('invetory.productID, maincategory.categoryName as mainCategoryName, category.categoryName, product.productName, SUM(inventory) as totalStock');

		$this->db->from($this->products_tablename .' as product');

		$this->db->join('empinventory as invetory', 'invetory.productID = product.productID', 'inner');
		$this->db->join($this->tablename .' as category', 'product.categoryID = category.categoryID  and category.isActive=1', 'inner');
		$this->db->join($this->tablename .' as maincategory', 'category.parentID = maincategory.categoryID', 'inner');

		

		$this->db->group_by("productID");

		

		if(isset($array['mainCategoryName']) && !empty($array['mainCategoryName']))

			$this->db->like('maincategory.categoryName', $array['mainCategoryName']);

		

		if(isset($array['categoryName']) && !empty($array['categoryName']))

			$this->db->like('category.categoryName', $array['categoryName']);

		

		if(isset($array['productName']) && !empty($array['productName']))

			$this->db->like('product.productName', $array['productName']);

		

		if(isset($array['productID']) && !empty($array['productID']))

			$this->db->where('invetory.productID', $array['productID']);

			

		$this->db->order_by("invetory.productID", "DESC");

		

		$query = $this->db->get();

		

		return $query->num_rows();

	}

	

	public function getempInventoryDetails($array = array())

	{

		//echo "<pre>";print_r($array);exit;

		$this->db->select('invetory.productID,invetory.empID,admin.firstName,admin.lastName, maincategory.categoryName as mainCategoryName, category.categoryName, product.productName, SUM(inventory) as totalStock');

		$this->db->from($this->products_tablename .' as product');

		$this->db->join('empinventory as invetory', 'invetory.productID = product.productID', 'inner');

		$this->db->join('admin as admin', 'admin.ID = invetory.empID', 'inner');

		$this->db->join($this->tablename .' as category', 'product.categoryID = category.categoryID', 'inner');

		$this->db->join($this->tablename .' as maincategory', 'category.parentID = maincategory.categoryID', 'inner');

		

		$this->db->group_by("invetory.empID,invetory.productID");

		

		if(isset($array['mainCategoryName']) && !empty($array['mainCategoryName']))

			$this->db->like('maincategory.categoryName', $array['mainCategoryName']);

		

		if(isset($array['categoryName']) && !empty($array['categoryName']))

			$this->db->like('category.categoryName', $array['categoryName']);

			

		if(isset($array['employeeName']) && !empty($array['employeeName']))

		{
			$this->db->like("CONCAT(admin.firstName, ' ', admin.lastName)", $array['employeeName']);

			//$this->db->or_like(array('admin.firstName'=> $array['employeeName'],'admin.lastName'=> $array['employeeName']));

		}
		if(isset($array['empID']) && !empty($array['empID']))
		{
			 $this->db->where('invetory.empID', $array['empID']);
			//$this->db->or_like(array('admin.firstName'=> $array['employeeName'],'admin.lastName'=> $array['employeeName']));
		}	
		if(isset($array['productName']) && !empty($array['productName']))

			$this->db->like('product.productName', $array['productName']);



		if(isset($array['productID']) && !empty($array['productID']))

			$this->db->where('product.productID', $array['productID']);

			

		$this->db->order_by("invetory.productID", "DESC");

		

		if(isset($cond_array['recordsperpage']) && isset($cond_array['limit']))

		$this->db->limit($array['recordsperpage'], $array['limit']);

		

		$query = $this->db->get();
	 if($query->num_rows() > 0)
		{

			$product = $query->result_array();

			return $product;

		}

		else

			return false;

	}

	
	///////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////
	
	public function getallempInventoryDetailsCount($array = array())
	{
		$this->db->select('invetory.productID, maincategory.categoryName as mainCategoryName, category.categoryName, product.productName, inventory as totalStock');
		$this->db->from($this->products_tablename .' as product');
		$this->db->join('empinventory as invetory', 'invetory.productID = product.productID', 'inner');
		$this->db->join($this->tablename .' as category', 'product.categoryID = category.categoryID', 'inner');
		$this->db->join($this->tablename .' as maincategory', 'category.parentID = maincategory.categoryID', 'inner');
		
		if(isset($array['mainCategoryName']) && !empty($array['mainCategoryName']))
			$this->db->like('maincategory.categoryName', $array['mainCategoryName']);
		
		if(isset($array['categoryName']) && !empty($array['categoryName']))
			$this->db->like('category.categoryName', $array['categoryName']);
		
		if(isset($array['productName']) && !empty($array['productName']))
			$this->db->like('product.productName', $array['productName']);
		if(isset($array['empID']) && !empty($array['empID']))
		{
			 $this->db->where('invetory.empID', $array['empID']);
			//$this->db->or_like(array('admin.firstName'=> $array['employeeName'],'admin.lastName'=> $array['employeeName']));
		}	
		if(isset($array['productID']) && !empty($array['productID']))
			$this->db->where('invetory.productID', $array['productID']);
			
		$this->db->order_by("invetory.productID", "DESC");
		
		$query = $this->db->get();
		
		return $query->num_rows();
	}
	
	public function getallempInventoryDetails($array = array())
	{
		//echo "<pre>";print_r($array);exit;
		$this->db->select('invetory.inventoryID,invetory.productID,invetory.empID,admin.firstName,admin.lastName, maincategory.categoryName as mainCategoryName, category.categoryName, product.productName, inventory as totalStock');
		$this->db->from($this->products_tablename .' as product');
		$this->db->join('empinventory as invetory', 'invetory.productID = product.productID', 'inner');
		$this->db->join('admin as admin', 'admin.ID = invetory.empID', 'inner');
		$this->db->join($this->tablename .' as category', 'product.categoryID = category.categoryID', 'inner');
		$this->db->join($this->tablename .' as maincategory', 'category.parentID = maincategory.categoryID', 'inner');
		
		if(isset($array['inventoryID']) && !empty($array['inventoryID']))
			$this->db->where('invetory.inventoryID', $array['inventoryID']);
			
		if(isset($array['mainCategoryName']) && !empty($array['mainCategoryName']))
			$this->db->like('maincategory.categoryName', $array['mainCategoryName']);
		
		if(isset($array['categoryName']) && !empty($array['categoryName']))
			$this->db->like('category.categoryName', $array['categoryName']);
			
		if(isset($array['employeeName']) && !empty($array['employeeName']))


		{
			$this->db->like("CONCAT(admin.firstName, ' ', admin.lastName)", $array['employeeName']);
			//$this->db->or_like(array('admin.firstName'=> $array['employeeName'],'admin.lastName'=> $array['employeeName']));
		}
			
		if(isset($array['productName']) && !empty($array['productName']))
			$this->db->like('product.productName', $array['productName']);
		if(isset($array['productID']) && !empty($array['productID']))
			$this->db->where('product.productID', $array['productID']);
			
		$this->db->order_by("invetory.productID", "DESC");
		
		if(isset($array['recordsperpage']) && isset($array['limit']))
		$this->db->limit($array['recordsperpage'], $array['limit']);
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{
			$product = $query->result_array();
			return $product;
		}
		else
			return false;
	}
	
	function RemoveInventory()
	{
		$this->db->trans_start();
		
		$insert_option_array = array('name' => 'History');
		$this->db->insert('history_main', $insert_option_array);
		$historyID = $this->db->insert_id();	
				
		$sql = "INSERT INTO history_orderheader SELECT ".$historyID.", orderID, patientID, invoiceNumber, tax_percentage, tax_amount, deliveryCharge, totalPrice, deliveryType, paymentType, CreditCardTransID, status, isDeleted, createdBy, createdType, created FROM orderheader";
		$this->db->query($sql);	
				
		$sql = "INSERT INTO history_orderdetails SELECT ".$historyID.", orderID, categotyID, subCategotyID, subCategotyName, productID, QBcode, productName, productPrice, quantity FROM orderdetails";
		$this->db->query($sql);
				
		$sql = "INSERT INTO history_inventory SELECT ".$historyID.", inventoryID, productID, inventory FROM inventory";
		$this->db->query($sql);
				
		$sql = "INSERT INTO history_empinventory SELECT ".$historyID.", inventoryID, productID, inventory, empID FROM empinventory";
		$this->db->query($sql);
		
		$this->db->truncate('orderheader');
		$this->db->truncate('orderdetails');
		$this->db->truncate('inventory');
		$this->db->truncate('empinventory');
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			return false;
		}
		return true;
	}
	
	/////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////
	
}







?>