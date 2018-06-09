<?
include_once './includes/config.inc.php';
auth();
$db = db_connect();
$name='';
$active ='0';

if($_POST)
{
	$name = strip_tags($_POST['mname']);
	$active = $_POST['mstatus'];
	if($_REQUEST["id"])
	{
		mysql_query("UPDATE store_menu set menu_name='$name', active=$active where sm_id=".strip_tags($_REQUEST["id"]));
		$_SESSION['notification'] = array('type'=>'good','msg'=>"Updated successfully");
	}	
	else
	{
		mysql_query("INSERT INTO store_menu(menu_name,active) values ('$name',$active)");
		$_SESSION['notification'] = array('type'=>'good','msg'=>"Added successfully");
	}	
	
}	
if($_REQUEST["id"]){
	$records = mysql_query("select *from store_menu where sm_id=".strip_tags($_REQUEST["id"]));
	$row = mysql_fetch_array($records);
	$name = $row['menu_name'];
	$active = $row['active'];
}
//
?>
<html>
	<head>
		<title><?php echo $lang['STORE_FINDER']; ?> - Store Menu</title>
		<?php include 'header.php'; ?>
	</head>
	<body id="stores">
		<div id="wrapper">
			<div id="header">
				<?php include 'nav.php'; ?>
			</div>
			<div id="main">
				<?php echo notification(); ?>
					<form method="post">
						<fieldset>
							<legend>Edit Menu</legend>
								<label>Name: <span class="required">*</span></label>
								<input type="text" name="mname" id="mname" value="<?=$name?>">
								<select name="mstatus" class="form-select" id="mstatus">
									<option <?if($active=='0') echo "selected";?> value="0">Inactive</option>
									<option <?if($active=='1') echo "selected";?>  value="1">Active</option>
								</select>
								<button type="submit" class="btn" name="save" id="save">Save</button>
						</fieldset>
					</form>
			</div>
		</div>
	</body>
</html>