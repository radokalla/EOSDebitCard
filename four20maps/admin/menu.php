<?
$heading='menu';
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
	  <?php include("header.php"); ?>
      <!-- Left side column. contains the logo and sidebar -->
      
      

      <?php include("sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Users List</h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Users</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
            	<div class="box">
                <div class="box-header">
                  <h3 class="box-title">Users List</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                    <?php echo notification(); ?>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div id="example1_filter">
                            	
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <a style="float:right" href="menu_edit.php" class="btn btn-primary">Add new</a>
                        </div>
                    </div>
                  <div class="row">
                      <div class="col-lg-12 col-sm-12 col-xs-12">
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
                  </div></div>
                </div><!-- /.box-body -->
              </div>
            </div>

          </div><!-- /.row -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      	
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
					str="<tr><td>"+i+"</td><td>"+item.menu_name+"</td><td>"+status+"</td><td class='actions'><a href='./menu_edit.php?id="+item.sm_id+"'><i class='fa fa-pencil'></i></a> <a href='./menu_del.php?id="+item.sm_id+"'><i class='fa fa-trash'></i></a></td></tr>";
					$("#menulist").find('tbody').append(str);
					i++;
				});
			}
			
		}
	})
}
</script>
<?php include("footer.php"); ?>
