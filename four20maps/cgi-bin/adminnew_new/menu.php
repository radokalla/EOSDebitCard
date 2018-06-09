<?
include_once './includes/config.inc.php';
auth();
$db = db_connect();
if($_REQUEST["getMenu"]){
	$records = mysql_query("select *from store_menu");
	while($row = mysql_fetch_array($records))
	{
		$rows[] = $row;
	}
	echo json_encode($rows);
	exit;
}
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
					<a style="float:right" href="./menu_edit.php">Add new</a>
					<table class="table table-bordered table-striped" id='menulist'>
						<thead>
							<tr>
								<th>Sl No</th>
								<th>Menu</th>
								<th>Status</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
			</div>
		</div>
	</body>
</html>
<script>
$(document).ready(function(){
	getMenu();
})
function getMenu()
{
	$.ajax({
		url: location.href,
		data: { getMenu:1 },
		success: function(data){
			data = $.parseJSON(data);
			if(data)
			{
				$("#menulist").find('tbody').empty()
				var str='';
				var i=1;
				$.map(data,function(item){
					if(item.active=='1' || item.active==1)
					var status = "Active";
					else
					var status = "InActive";
					str="<tr><td>"+i+"</td><td>"+item.menu_name+"</td><td>"+status+"</td><td class='actions'><a href='./menu_edit.php?id="+item.sm_id+"'><i class='icon-pencil'></i></a><a href='./menu_del.php?id="+item.sm_id+"'><i class='icon-trash'></i></a></td></tr>";
					$("#menulist").find('tbody').append(str);
					i++;
				});
			}
			
		}
	})
}
</script>