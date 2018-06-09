<?
include_once './includes/config.inc.php';
auth();
$db = db_connect();
if(intval(strip_tags($_REQUEST['id']))>0)
{
	mysql_query("DELETE FROM store_menu where sm_id =".$_REQUEST['id']);
	$_SESSION['notification'] = array('type'=>'good','msg'=>"Removed successfully");
	header("Location:menu.php");
}
?>
