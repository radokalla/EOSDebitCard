<?php
include_once './includes/config.inc.php';
$_REQUEST["keyword"] = (!isset($_REQUEST["keyword"]) || empty($_REQUEST["keyword"])) ? 0 : $_REQUEST["keyword"];
mysql_query('SET CHARACTER SET utf8');
  $res = mysql_query("call map_proc_getProductsByStore('".$_REQUEST["q"]."','".$_REQUEST["keyword"]."');") or die(mysql_error());
    if ($res === false) {
        echo "HOI ".mysql_errno().': '.mysql_error();
    }
	
	if(mysql_num_rows($res) > 0)
	{
		$products = array();
		$rows = array();
		$category  = array();
		$parentcategory  = array();
		$website = array();
		while ($row = mysql_fetch_array($res)) {
			if(!empty($row['categoryID'])) {
				$category[$row['categoryID']] = $row['categoryName'];
				$website['web'] = $row['website1'];
				$parentcategory[$row['ParentcategoryID']] = array("orderid"=>intval($row['CategoryOrder']),"name"=>$row['ParentcategoryName'],"Catids"=>$row['ParentcategoryID']);
				$rows[] = $row;
			}
		}
		$category = array_unique($category);
		$fullArray = array();
		
		foreach($category as $key=>$catvalue)
		{
			$cat_id = $key;
			$cat_name = $catvalue;
			$prForCat = getProductsbyCat($cat_id,$rows);
			foreach($prForCat as $key=>$value)
			{
				$productName = $value['pname'];
				$price = $value['price'];
			
				$options = getProductData($key,$rows);
				$fullArray[] = array('catid'=>$cat_id, 'catname'=>$cat_name, 'products'=>$prForCat, 'options'=>$options,
					"productID"=>$value['productID']);
			}
		#var_dump($prForCat);die;
		}
		$fullArray = array_map("unserialize", array_unique(array_map("serialize", $fullArray)));
		
		$result_arr['products'] = $fullArray;
		
		usort($parentcategory, 'sortByOrder');
		
		foreach($parentcategory as $key=>$value)
		$parr[$key] = $value;
		$result_arr['website1'] = $website;
		$result_arr['mainCategories'] = $parr;
		//echo '<pre>'; print_r($result_arr);exit;
		echo json_encode($result_arr, JSON_HEX_APOS|JSON_HEX_QUOT);exit;
		//echo json_encode($result_arr);exit;
	}
	else
	{
		echo json_encode(array());exit;
	}
	/*
	if(in_array($row["productID"],$products ))
			continue;
		
			$products[$row["productID"]]= array("ProductID"=>$row["productID"],"productName"=>$row["productName"],
			"price"=>$row["price"],"image"=>$row["image"]);*/
	function sortByOrder($a, $b) {
		return $b['orderid'] - $a['orderid'];
	}
	function getProductsbyCat($cat_id,$res)
	{
		
		$productsForCat = array();
		 foreach($res as $row)
		 { 
			 if($row['categoryID'] == $cat_id)
			 {
				 if(in_array($row['productID'],$productsForCat))
					 continue;
				 $productsForCat[$row['productID']] = array(
						'pname'=>$row['productName'],
						'productDescription' => (isset($row['productDescription']) && !empty($row['productDescription'])) ? $row['productDescription'] : "Description not found.",
						'price'=>$row['price'],
						'image'=>$row['image'],
						"categoryType"=>$row['categoryType'],
						"ParentcategoryID"=>$row['ParentcategoryID'],
						"ParentcategoryName"=>$row['ParentcategoryName'],
						"ProductID"=>$row['productID'],
						"AvailableStock"=>$row['AvailableStock']
					);
			 }
				
		 }
		 #var_dump($productsForCat);die;
		 return ($productsForCat);
	}
	function getProductData($prCat,$res)
	{
		
		$optionsForPro = array();
		
		 foreach($res as $row)
		 {	
 
			 if($row['productID'] == $prCat)
			 {
				  if(in_array($row['optionID'],$optionsForPro))
					 continue;	
				 $value=empty($row['VALUE']) ? $row['value'] : $row['VALUE'];
				 $optionsForPro[$row['optionID']] = array('optionType'=>$row['optionType'],'value'=>$value);
			 }
			 
		 }		
		
			return $optionsForPro;
	}
?>