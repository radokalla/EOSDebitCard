<?php 
// include Config File

include_once './includes/config.inc.php';

// Authenticate user login

$db = db_connect();
$idd=$_POST['idd'];

$selquery="Select aid,start_date,end_date,status from adds where aid=".$idd;
$mysql_querr = mysql_query($selquery);
$mysql_fetc = mysql_fetch_assoc($mysql_querr);
echo json_encode($mysql_fetc);
?>