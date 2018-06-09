<?php
// include config file

include_once './includes/config.inc.php';
include_once './includes/class.database.php';
$storid=$_POST['stid'];
$resul=mysql_query("CALL map_proc_getProductsByStore('{$storid}')") or die(mysql_error());
$prd = array();
while($rj=mysql_fetch_assoc($resul))
{
	if(!isset($prd[$rj['categoryID']])){
		$prd[$rj['categoryID']] = array('categoryName'=>$rj['categoryName'],'ParentcategoryName'=>$rj['ParentcategoryName'],'image'=>$rj['image']);
	}
	if(!isset($prd[$rj['categoryID']]['products'][$rj['productID']])){
		$prd[$rj['categoryID']]['products'][$rj['productID']] = array('categoryName'=>$rj['categoryName'],'ParentcategoryName'=>$rj['ParentcategoryName'],'image'=>$rj['image'],'QBcode'=>$rj['QBcode'],'productName'=>$rj['productName'],'price'=>$rj['price'],'optionID'=>$rj['optionID'],'type'=>$rj['type']);
	}
	$prd[$rj['categoryID']]['products'][$rj['productID']]['optionType'][] = array('optionType'=>$rj['optionType'],'value'=>$rj['value'],'price'=>$rj['price']);
	
}
echo json_encode($prd);

?>
