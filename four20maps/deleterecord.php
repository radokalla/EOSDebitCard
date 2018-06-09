<?php
ob_start();
session_start();
include_once './includes/config.inc.php';
include_once './includes/functions.php';
error_reporting(E_ALL);

//deleting category 
if(isset($_GET['catid']) && $_GET['catid']!='') {
	mysql_query("update ProductCategory set isDeleted = '1' where categoryID='".$_GET['catid']."'");
	mysql_query("update ProductCategory set isDeleted = '1' where parentID='".$_GET['catid']."'");
	$_SESSION['catUp'] = 'Deleted Successfully';
	header('Location: /categories.php');
}

//deleting product category
if(isset($_GET['prodid']) && $_GET['prodid']!='') {

	/*$Products=mysql_query("select  productID from Products where categoryID='".$_GET['prodid']."'"); 
	while($product = mysql_fetch_assoc($Products)){
			
		mysql_query("DELETE FROM `store_product_map` where product_id ='".$product['productID']."'");
		mysql_query("DELETE FROM `Products` where categoryID='".$_GET['prodid']."'");
	    mysql_query("DELETE FROM `ProductCategory` where categoryID='".$_GET['prodid']."'");
		
		
	}*/
	$Products=mysql_query("select  productID from Products where categoryID='".$_GET['prodid']."'"); 
	 mysql_query("DELETE FROM `ProductCategory` where categoryID='".$_GET['prodid']."'");
	while($product = mysql_fetch_assoc($Products)){
			
		mysql_query("DELETE FROM `store_product_map` where product_id ='".$product['productID']."'");
		mysql_query("DELETE FROM `Products` where categoryID='".$_GET['prodid']."'");
	   
		
		
	}
	
	header('Location: /productdetails.php');
}
?>