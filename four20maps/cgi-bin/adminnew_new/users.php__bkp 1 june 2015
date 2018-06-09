<?php
// include Config File
include_once './includes/config.inc.php';
// Authenticate user login
auth();




if(isset($_GET['action']) && $_GET['action']=='delete') {

	if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['ADMIN_INVALID_ID']);
		redirect(ROOT_URL.'users.php');
	}


	$db = db_connect();
	if($db->delete('users', $_GET['id'])) {
		$_SESSION['notification'] = array('type'=>'good','msg'=>$lang['ADMIN_USER_DELETED']);
	} else {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['ADMIN_DELETE_USER_FAILED']);
	}
redirect(ROOT_URL.'users.php');
}


$db = db_connect();

$limit = 20; 

if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
$start_from = ($page-1) * $limit;  

$users = $db->get_rows("SELECT users.* FROM users WHERE users.username!='admin' ORDER BY users.username ASC LIMIT $start_from, $limit");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title><?php echo $lang['STORE_FINDER']; ?> - <?php echo $lang['ADMIN_USER_LIST']; ?></title>
	<?php include 'header.php'; ?>
</head>
<body id="users">
	<div id="wrapper">
		<div id="header">
			
			<?php include 'nav.php'; ?>
		</div>
		<div id="main">
		
			<h2><?php echo $lang['ADMIN_USER_LIST']; ?></h2>
			<?php echo notification(); ?>

			<table class="table table-bordered" style="width:100%;">
				<thead>
				<tr>
					<th><?php echo $lang['ADMIN_USERNAME']; ?></th><th><?php echo $lang['ADMIN_LAST_LOGIN']; ?></th><th class="actions"><?php echo $lang['ADMIN_ACTION']; ?></th>
				</tr>
				</thead>
				<tbody>
				<?php if(!empty($users)): ?>
					<?php foreach($users as $k=>$v): ?>
					<tr class='<?php echo ($k%2==0) ? 'odd':'even'; ?>'>
						<td><?php echo $v['username']; ?></td>
						<td><?php echo $v['modified']; ?></td>
						
						
						<td class="actions">
							<a href='./users_edit.php?id=<?php echo $v['id']; ?>'><i class="icon-pencil"></i></a>
							<a href='javascript:delItem(<?php echo $v['id']; ?>)' class="confirm_delete"><i class="icon-trash"></i></a>
							
						</td>
					</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="7"><?php echo $lang['ADMIN_NO_USERS']; ?></td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
			
				
		<?php  
			$sql = "SELECT COUNT(id) FROM users";  
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
						 $pagLink .= "<li ".$active."><a href='users.php?page=".$i."'>".$i."</a></li>";  
			};  
			echo $pagLink . "</ul></div>";  
		?>  

		</div>
	</div>
	
	<script>
	function delItem(id){
	
	var a = confirm("<?php echo $lang['ADMIN_DELETE_CONFIRM']; ?>");
		if(a){
		document.location.href='?action=delete&id='+id;
		}
	
	}
	</script>
	<?php include '../themes/footer.inc.php'; ?>
</body>
</html>