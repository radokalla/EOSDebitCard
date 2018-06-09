<?php error_reporting(0); 
ini_set('max_execution_time', 0);header('Content-Type: text/csv; charset=utf-8');header("Content-Disposition: attachment; filename=superstorefinder_stores_".$date.".csv");header("Pragma: no-cache");header("Expires: 0");
include_once './includes/config.inc.php';
include_once './includes/validate.php';
// Authenticate user login
auth();
$db = db_connect();
$header  = "";
mysql_query("SET NAMES utf8"); 
$stores = $db->get_rows("SELECT stores.* FROM stores ORDER BY stores.name ASC");
$data = '"id","name","address","telephone","fax","mobile","email","website","description","approved","latitude","longitude","created","modified","status","cat_id"';
$data .= "\n";
if(!empty($stores)):
	foreach($stores as $k=>$v):
		$data .= '"'.$v['id'].'","'.$v['name'].'","'.$v['address'].'","'.$v['telephone'].'","'.$v['fax'].'","'.$v['mobile'].'","'.$v['email'].'","'.$v['website'].'","'.$v['description'].'","'.$v['approved'].'","'.$v['latitude'].'","'.$v['longitude'].'","'.$v['created'].'","'.$v['modified'].'","'.$v['status'].'","'.$v['cat_id'].'"'; 
	$data .= "\n";
	endforeach; 
else: 
	echo "";
endif;
$data = str_replace( "\r" , "" , $data );
if ( $data == "" )
{
    $data = "\n(0) Records Found!\n";                        
}
$date = date("Y-m-d");
print "$header".$data;
?>