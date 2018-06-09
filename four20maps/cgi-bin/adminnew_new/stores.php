<?php
// include Config File
include_once './includes/config.inc.php';
// Authenticate user login
auth();
$pg='';
if (isset($_REQUEST["page"])) { $page  = $_REQUEST["page"]; $pg='&page='.$page ; } else { $page=1;  };  
if (isset($_REQUEST["sort"])) {  $st='&sort='.$_REQUEST["sort"] ; } else { $st='';  };  
if (isset($_REQUEST["filter"])) { $fl='&filter='.$_REQUEST["filter"] ; } else { $fl='';  };  

if(isset($_GET['action']) && $_GET['action']=='approve') {
	if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['ADMIN_INVALID_ID']);
		redirect(ROOT_URL.'stores.php?search='.$_REQUEST['search'].$pg.$st.$fl);
	}
	$db = db_connect();
	if($db->update('stores',array('approved'=>1),$_GET['id'])) {
		$_SESSION['notification'] = array('type'=>'good','msg'=>$lang['ADMIN_STORE_APPROVED']);
	} else {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['ADMIN_APPROVE_FAILED']);
	}
	redirect(ROOT_URL.'stores.php?search='.$_REQUEST['search'].$pg.$st.$fl);
}
if(isset($_GET['action']) && $_GET['action']=='disapprove') {
	if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['ADMIN_INVALID_ID']);
		redirect(ROOT_URL.'stores.php?search='.$_REQUEST['search'].$pg.$st.$fl);
	}
	$db = db_connect();
	if($db->update('stores',array('approved'=>0),$_GET['id'])) {
		$_SESSION['notification'] = array('type'=>'good','msg'=>"Record Updated Successfully");
	} else {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>"Something went wrong..!! Error while updating records");
	}
	redirect(ROOT_URL.'stores.php?search='.$_REQUEST['search'].$pg.$st.$fl);
}
if(isset($_GET['action']) && $_GET['action']=='delete') {
	if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['ADMIN_INVALID_ID']);
		redirect(ROOT_URL.'stores.php?search='.$_REQUEST['search'].$pg.$st.$fl);
	}
	$db = db_connect();
	if($db->delete('stores',$_GET['id'])) {
		$id=$_GET['id'];
		$query="update adds set is_delete='1' where id=$id";
		//echo "<pre>";echo $query;exit;
		$_SESSION['notification'] = array('type'=>'good','msg'=>$lang['ADMIN_STORE_DELETED']);
	} else {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['ADMIN_DELETE_STORE_FAILED']);
	}
	redirect(ROOT_URL.'stores.php?search='.$_REQUEST['search'].$pg.$st.$fl);
}

$db = db_connect();
$limit = 20; 
$stype = "ASC";
$str = "";
if(!isset($_REQUEST['stype1'])){
	$stype1 = "ASC";
} else {
	$stype1 = $_REQUEST['stype1'];
	$stype = $stype1;
	$str = "&stype1=$stype1";
}
if(!isset($_REQUEST['stype2'])){
	$stype2 = "ASC";
} else {
	$stype2 = $_REQUEST['stype2'];
	$stype = $stype2;
	$str = "&stype2=$stype2";
}
if(!isset($_REQUEST['stype3'])){
	$stype3 = "ASC";
} else {
	$stype3 = $_REQUEST['stype3'];
	$stype = $stype3;
	$str = "&stype3=$stype3";
}
$start_from = ($page-1) * $limit;  
$storefilter = "";
if(isset($_REQUEST['search'])){
	if(isset($_REQUEST['filter'])){
		$filter = $_REQUEST['filter'];
	}
	else {
		$filter = '';
	}
	if($filter=="fname"){
		$storefilter = " AND (stores.name LIKE '%".$_REQUEST['search']."%')";
	} else if($filter=="faddress"){
		$storefilter = " AND (stores.address LIKE '%".$_REQUEST['search']."%')";
	} else if($filter=="ftel"){
		$storefilter = " AND (stores.telephone LIKE '%".$_REQUEST['search']."%')";
	} else if($filter=="femail"){
		$storefilter = " AND (stores.email LIKE '%".$_REQUEST['search']."%')";
	} else if($filter=="fwebsite"){
		$storefilter = " AND (stores.website LIKE '%".$_REQUEST['search']."%')";
	}

  //$storefilter = " AND (stores.name LIKE '%".$_REQUEST['search']."%' OR stores.address LIKE '%".$_REQUEST['search']."%' OR stores.website LIKE '%".$_REQUEST['search']."%' OR stores.name LIKE '%".$_REQUEST['search']."%' OR stores.telephone LIKE '%".$_REQUEST['search']."%')";

}
$sort = "name";
if(isset($_REQUEST['sort'])){
 $sort = $_REQUEST['sort'];
}
 $storefilter .= "ORDER BY ".$sort." $stype";

mysql_query("SET NAMES utf8"); 
$stores = $db->get_rows("SELECT stores.* FROM stores WHERE stores.id!=0 $storefilter LIMIT $start_from, $limit");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title><?php echo $lang['STORE_FINDER']; ?> - <?php echo $lang['ADMIN_STORE_LIST']; ?></title>
	<?php include 'header.php'; ?>
</head>
<body id="stores">
	<div id="wrapper">

		<div id="header">

			

			<?php include 'nav.php'; ?>

		</div>

		<div id="main">

		

			<h2><?php echo $lang['ADMIN_STORE_LIST']; ?></h2>

			<?php echo notification(); ?>



			<div class="searchbar">

<form method="POST" action="stores.php">

<input type=text placeholder="Search"  value="<?php if(isset($_REQUEST['search'])) { echo $_REQUEST['search']; } ?>" name="search" id="search">

<select id=filter name=filter>

<option value="fname" <?php if(isset($_REQUEST['filter'])){ if($_REQUEST['filter']=="fname"){ ?>selected<?php } } ?>>Store Name</option>

<option value="faddress" <?php if(isset($_REQUEST['filter'])){ if($_REQUEST['filter']=="faddress"){ ?>selected<?php } } ?>>Address</option>

<option value="ftel" <?php if(isset($_REQUEST['filter'])){ if($_REQUEST['filter']=="ftel"){ ?>selected<?php } } ?>>Telephone</option>

<option value="femail" <?php if(isset($_REQUEST['filter'])){ if($_REQUEST['filter']=="femail"){ ?>selected<?php } } ?>>Email</option>

<option value="fwebsite" <?php if(isset($_REQUEST['filter'])){ if($_REQUEST['filter']=="fwebsite"){ ?>selected<?php } } ?>>Website</option>

</select>

 <!--<div class="icon-search"></div>--><input type=submit class="btn btn-primary" value="<?php echo $lang['FIND_STORE']; ?>" style="margin-bottom:10px;" />

</form>





<?php



$sql = "SELECT COUNT(id) FROM stores WHERE status=1 $storefilter";  

			$rs_result = mysql_query($sql);  

			$row = mysql_fetch_row($rs_result);  

			$total_records = $row[0];  

			$total_pages = ceil($total_records / $limit);  

			$active = "";



			$pagLink = "<div class='pagination'><ul>";  

			for ($i=1; $i<=$total_pages; $i++) { 

					if(isset($_GET['page'])){

						if($i==$_GET["page"]){

						  $active="class='active'";

						} else {

						   $active="";

						}

					}

						$rf = "";

						if(isset($_REQUEST['search'])) { $rf = "&search=".$_REQUEST['search']."&filter=".$filter; } 

						 $pagLink .= "<li ".$active."><a href='stores.php?sort=$sort".$str."&page=".$i.$rf."'>".$i."</a></li>";   

			};  

?>



</div>
<a style="float:right" href="./stores_add.php"><?php echo $lang['ADMIN_ADD_STORE']; ?></a>
			<table class="table table-bordered table-striped">

				<thead>

				<tr>

					<th><?php echo $lang['ADMIN_NAME']; ?> 



<?php if($stype1=="ASC"){ ?>

<a href="stores.php?&search=<?php if(isset($_REQUEST['search'])){ echo $_REQUEST['search']; } ?>&sort=name&stype1=DESC&page=<?php echo $page.$rf; ?>"><div class="icon-circle-arrow-up">

<? } else { ?>

<a href="stores.php?&search=<?php if(isset($_REQUEST['search'])){ echo $_REQUEST['search']; } ?>&sort=name&stype1=ASC&page=<?php echo $page.$rf; ?>"><div class="icon-circle-arrow-down">

<?php } ?>



</div></a></th>

<th><?php echo $lang['ADMIN_ADDRESS']; ?>





<?php if($stype2=="ASC"){ ?>

<a href="stores.php?&search=<?php if(isset($_REQUEST['search'])){ echo $_REQUEST['search']; } ?>&sort=address&stype2=DESC&page=<?php echo $page.$rf; ?>"><div class="icon-circle-arrow-up">

<? } else { ?>

<a href="stores.php?&search=<?php if(isset($_REQUEST['search'])){ echo $_REQUEST['search']; } ?>&sort=address&stype2=ASC&page=<?php echo $page.$rf; ?>"><div class="icon-circle-arrow-down">

<?php } ?>

</th>

<th><?php echo $lang['ADMIN_TELEPHONE']; ?></th>

<th><?php echo $lang['ADMINISTRATOR_EMAIL']; ?></th>

<th><?php echo $lang['ADMIN_WEBSITE']; ?>



<?php if($stype3=="ASC"){ ?>

<a href="stores.php?&search=<?php if(isset($_REQUEST['search'])){ echo $_REQUEST['search']; } ?>&sort=website&stype3=DESC&page=<?php echo $page.$rf; ?>"><div class="icon-circle-arrow-up">

<? } else { ?>

<a href="stores.php?&search=<?php if(isset($_REQUEST['search'])){ echo $_REQUEST['search']; } ?>&sort=website&stype3=ASC&page=<?php echo $page.$rf; ?>"><div class="icon-circle-arrow-down">

<?php } ?>



</th>

<th class="acenter"><?php echo $lang['ADMIN_APPROVED']; ?></th>

<th class="actions"><?php echo $lang['ADMIN_ACTION']; ?></th>

				</tr>

				</thead>

				<tbody>

				<?php if(!empty($stores)): ?>

					<?php foreach($stores as $k=>$v): ?>

					<tr class='<?php echo ($k%2==0) ? 'odd':'even'; ?>'>

						<td><?php echo $v['name']; ?></td>

						<td><?php echo $v['address']; ?></td>

						

						<td><?php echo $v['telephone']; ?></td>

						<td><?php echo $v['email']; ?></td>

						<td><?php echo $v['website']; ?></td>

						<td class="acenter"><?php echo ($v['approved']) ? 'Yes' : 'No' ; ?></td>

						<td class="actions">

							<a href='./stores_edit.php?id=<?php echo $v['id']; ?>'><i class="icon-pencil"></i></a>

							<a href='javascript:delItem(<?php echo $v['id']; ?>)' class="confirm_delete"><i class="icon-trash"></i></a>

							<?php if(!$v['approved']) {?>

							<a href='?action=approve&amp;&id=<?php echo $v['id']; ?>&search=<?php if(isset($_REQUEST['search'])) { echo $_REQUEST['search']; } ?><?php if(isset($_REQUEST['page'])) { echo "&page=".$_REQUEST['page']; } ?><?php if(isset($_REQUEST['sort'])) { echo "&sort=".$_REQUEST['sort']; } ?><?php if(isset($_REQUEST['filter'])) { echo "&filter=".$_REQUEST['filter']; } ?>'><?php echo $lang['ADMIN_APPROVE']; ?></a>

							<?php }
							else{?>
							
								<a href='?action=disapprove&amp;&id=<?php echo $v['id']; ?>&search=<?php if(isset($_REQUEST['search'])) { echo $_REQUEST['search']; } ?><?php if(isset($_REQUEST['page'])) { echo "&page=".$_REQUEST['page']; } ?><?php if(isset($_REQUEST['sort'])) { echo "&sort=".$_REQUEST['sort']; } ?><?php if(isset($_REQUEST['filter'])) { echo "&filter=".$_REQUEST['filter']; } ?>'>DisApprove</a>
							<?php }?>

						</td>

					</tr>

					<?php endforeach; ?>

				<?php else: ?>

					<tr>

						<td colspan="7"><?php echo $lang['ADMIN_NO_STORES']; ?></td>

					</tr>

				<?php endif; ?>

				</tbody>

			</table>



			

		<?php  

			

			echo $pagLink . "</ul></div>";  

		?>  

	

		</div>

	</div>

	

	<script>

	function delItem(id){



	var a = confirm("<?php echo $lang['ADMIN_DELETE_CONFIRM']; ?>");

		if(a){

		document.location.href='?action=delete&id='+id+'&search=<?php if(isset($_REQUEST['search'])) { echo $_REQUEST['search']; } ?><?php if(isset($_REQUEST['page'])) { echo "&page=".$_REQUEST['page']; } ?><?php if(isset($_REQUEST['sort'])) { echo "&sort=".$_REQUEST['sort']; } ?><?php if(isset($_REQUEST['filter'])) { echo "&filter=".$_REQUEST['filter']; } ?>';

		}

	}

	</script>

	<?php include '../themes/footer.inc.php'; ?>

</body>

</html>