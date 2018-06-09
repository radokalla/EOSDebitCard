<?php 
// include Config File

include_once './includes/config.inc.php';

// Authenticate user login

$db = db_connect();

$idd=$_POST['iddd'];
$stdate=$_POST['stdate'];
$Enddate=$_POST['Enddate'];
$statuss=$_POST['statuss'];

$selquery="Update adds set start_date='".$stdate."',end_date='".$Enddate."',status='".$statuss."' where aid=".$idd;
mysql_query($selquery);
?>
<script>
window.location='<?php echo ROOT_URL;?>';
</script>

