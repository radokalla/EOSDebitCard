<?php
//functions list here
function your_filter($value) { 
    $newVal = mysql_real_escape_string($newVal);
    return $newVal;
}
function insertCategoryProduct($array)
{
if(!empty($array['product_name']))
	{
			$store_id = !empty($array['StoreId']) ? $array['StoreId'] : '';
			
			$product_name = !empty($array['product_name']) ? $array['product_name'] : '';
		    $categoryAliasName = !empty($array['categoryAliasName']) ? $array['categoryAliasName'] : '';
		   
			$product_type = !empty($array['product_type']) ? $array['product_type'] : '';
			if($product_type=='')
			{
				$product_type = (isset($array['categoryType'])) ? $array['categoryType'] : "" ;
			}
				
			
			$image_path = !empty($array['applimagepath']) ? $array['applimagepath'] : '';

			$parentID = !empty($array['category']) ? $array['category'] : 0;
			
			$categoryDescriptionHeading = !empty($array['cat_desc_heading']) ? mysql_real_escape_string($array['cat_desc_heading']) : '';

			$categoryDescription = !empty($array['cat_description']) ? $array['cat_description'] : '';		
			$qty = $_POST['qty'];
			$weight = $_POST['weight'];
			$productDescription  =mysql_real_escape_string($_POST['productDescription']);
			if(empty($array['productid']))
			{
				$userid = $_SESSION['StoreID'];
				$parentID = $_POST['categoryID'];
				if(($parentID==0) || ($parentID==''))
					$isActive =$_POST['isActive'];
				else
					$isActive ='1';
				  $sql = "INSERT INTO ProductCategory ".
					   "(categoryAliasName,categoryName,categoryType, UserId, image, parentID, categoryDescriptionHeading, categoryDescription, isActive, isDeleted) ".
					   "VALUES ".
					   "('$categoryAliasName','$product_name','$product_type','$userid','$image_path','$parentID','$categoryDescriptionHeading','$categoryDescription','$isActive','0')";
			 
				$retval = mysql_query( $sql);
				$categoryID = mysql_insert_id();
				$qbcode = '';
				$productName = '';
				$isActive = '1';
				$price = $_POST['cost'];
				$qty = $_POST['qty'];
				$ten_price = $_POST['ten_price'];
				$hundered_price = $_POST['hundered_price'];
				$weight = $_POST['weight'];
				$productDescription  = mysql_real_escape_string($_POST['productDescription']);
				mysql_query("Insert INTO Products (QBcode,productName,productDescription,price,ten_price,hundered_price,qty,weight,isActive,categoryID) VALUES('$qbcode','$productName','$productDescription','$price','$ten_price','$hundered_price','$qty','$weight','$isActive','$categoryID')")or die(mysql_error());
				$product_id=mysql_insert_id();
				if(count($array["store_name"]) >0){
					 foreach($array["store_name"] as $store_id){
					     mysql_query("INSERT INTO `store_product_map`(`store_id`, `product_id`, `user_id`) VALUES ('".$store_id."','".$product_id."','".$_SESSION['StoreID']."')");
					}
				}
			} 
		else {
				$categoryID = $array['productid'];
				$UserId = $_SESSION["StoreID"];
				$isActive = '1';
				if(!empty($image_path))
				{
					if(($parentID==0) || ($parentID==''))
						$isActive =$_POST['isActive'];
					else
						$isActive ='1';
					$parentID = $_POST['categoryID'];
					  $sql = "update ProductCategory  set categoryAliasName= '$categoryAliasName',categoryName = '$product_name', UserId = '$UserId', categoryType = '$product_type' , image = '$image_path' , parentID = '$parentID' , categoryDescriptionHeading = '$categoryDescriptionHeading' , categoryDescription = '$categoryDescription' where  categoryID = '".$categoryID."'"; 
					$result = mysql_query($sql);
					if($result==1)
					{
						$qbcode = '';
						$productName = '';
						$isActive = '1';
						$price = $_POST['cost'];
						$pid = $_POST['pid'];
						$ten_price = $_POST['ten_price'];
				$hundered_price = $_POST['hundered_price'];	 
						$result = mysql_query("Update Products SET  productDescription='$productDescription',price='$price',ten_price='$ten_price',hundered_price='$hundered_price',qty=$qty,weight =$weight where          categoryID = '$categoryID' ")or die(mysql_error());
						if($result==1)
							$_SESSION['catUp']='Updated Successfully';
						  	if(count($array["store_name"]) >0){
					        mysql_query("DELETE FROM `store_product_map` WHERE `user_id`='".$_SESSION['StoreID']."' and product_id=".$_POST['pid']);
							foreach($array["store_name"] as $store_id){
								 
								 mysql_query("INSERT INTO `store_product_map`(`store_id`, `product_id`, `user_id`) VALUES ('".$store_id."','".$_POST['pid']."','".$_SESSION['StoreID']."')");
							}
						}
					}
				}
				else
				{
					if(empty($image_path))
					{
						if(($parentID==0) || ($parentID==''))
							$isActive =$_POST['isActive'];
						else
							$isActive ='1';
						$parentID = $_POST['categoryID'];
						  $sql = "update ProductCategory  set    categoryAliasName= '$categoryAliasName',categoryName = '$product_name', UserId = '$UserId', categoryType = '$product_type' , isActive = '$isActive',  parentID = '$parentID' , categoryDescriptionHeading = '$categoryDescriptionHeading' , categoryDescription = '$categoryDescription' where  categoryID = '".$categoryID."'"; 
						$result = mysql_query( $sql);
						if($result==1)
						{
							$qbcode = '';
							$productName = '';
							$isActive = '1';
							$price = $_POST['cost'];
							$ten_price = $_POST['ten_price'];
							$hundered_price = $_POST['hundered_price'];
						    if(isset($qty)){
							$updatestr= ($_SESSION["SubscriptionCategoryId"]== "4")?",ten_price='$ten_price',hundered_price='$hundered_price',weight =$weight":'';
							$result = mysql_query("Update Products SET productDescription='$productDescription',price='$price',qty=$qty.$updatestr where categoryID = '$categoryID' ")or die(mysql_error());
							if($result==1)
								$_SESSION['catUp']='Updated Successfully';
							if(count($array["store_name"]) >0){
							 
					        mysql_query("DELETE FROM `store_product_map` WHERE `user_id`='".$_SESSION['StoreID']."' and product_id=".$_POST['pid']);
							foreach($array["store_name"] as $store_id){
								 mysql_query("INSERT INTO `store_product_map`(`store_id`, `product_id`, `user_id`) VALUES ('".$store_id."','".$_POST['pid']."','".$_SESSION['StoreID']."')");
							}
							}
						}
						}
						if($result==1)
						{
							if($_POST['isActive'])
								$_SESSION['catUp']='Updated Successfully';
							else
								$_SESSION['proUp']='Updated Successfully';
						}
					}
				}
				
					$retval = mysql_query( $sql);
			}
			
			if(!empty($array['options']))
			{
				
				$productid = $_POST['productid'];
				//var_dump($parentID);
				//var_dump($array);die; 
				//die;
				if(!empty($productid))
				{
					mysql_query("delete from categoryoptions where categoryID = '".$productid ."'");
					$categoryID = $_POST['productid'];
				}
				$userid = $_SESSION["StoreID"];
				$stores = mysql_query("select id from stores where createdby='$userid' ");
				$storeid = mysql_fetch_array($stores);
				$sid = $storeid['id'];
				foreach($array['options'] as $optionID => $value)
				{
					mysql_query("INSERT INTO categoryoptions (categoryID, optionID, value) values ('$categoryID','$optionID','$value')");
				}
			}
			
			return $categoryID;
		}
		else
		return false;
}
function uploadImage()
{
	if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
			
			$baseDir = 'uploaded/product_images/';
			 $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
			  $time=time();
			  $destination = $baseDir."/".$time.'.'.$extension;

			 if(move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
				  return $destination;              
			 } else {
				 return false;
			 }
			  
		}
}

function getProductCategory($catid='')
{
	//getting category products information
	$where = '';
	if($catid!='') {
		$where .= " and categoryID = '".$catid."'";
	}
	$query = "select * from ProductCategory where isActive != '2' and isDeleted='0' ".$where." order by categoryID desc";
	$catproducts = mysql_query($query);
	
	if (mysql_num_rows($catproducts) == 1) {
		while ($row = mysql_fetch_assoc($catproducts)) {
			$result = array('categoryAliasName'=>$row['categoryAliasName'],'product_name'=>$row['categoryName'],'product_type'=>$row['categoryType'],'image'=>$row['image'],'category'=>$row['parentID'],'active'=>$row['isActive'],'cat_desc_heading'=>$row['categoryDescriptionHeading'],'cat_description'=>$row['categoryDescription']);
		}
		
		//getting product options
	
		$query2 = "select * from categoryoptions where 1=1 ".$where;
		$catproductsoptions = mysql_query($query2);
		
		while ($row = mysql_fetch_assoc($catproductsoptions)) {
			$result['options'][$row['optionID']] = $row['value'];
		}
			
			
	} else {
		while ($row = mysql_fetch_assoc($catproducts)) {
			$result[$row['categoryID']] = array('product_name'=>$row['categoryName'],'product_type'=>$row['categoryType'],'image'=>$row['image'],'category'=>$row['parentID'],'active'=>$row['isActive'],'cat_desc_heading'=>$row['categoryDescriptionHeading'],'cat_description'=>$row['categoryDescription']);
		}
		
		//getting product options
	
		$query2 = "select * from categoryoptions where 1=1 ".$where;
		$catproductsoptions = mysql_query($query2);
		
		while ($row = mysql_fetch_assoc($catproductsoptions)) {
			$result[$row['categoryID']]['options'][$row['optionID']] = $row['value'];
		}
	}
	
	
	return $result;
}
?>