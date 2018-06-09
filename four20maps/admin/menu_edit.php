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
                  <h3 class="box-title">Edit Menu</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                    <?php echo notification(); ?>
                      <div class="row">
                          <div class="col-lg-12 col-sm-12 col-xs-12">
                             <div class="form-group">
                                <label>Name: <span class="required">*</span></label>
                                <input type='text' class="form-control" name="mname" id="mname" value="<?=$name?>" />
                                <select name="mstatus" class="form-select form-control" id="mstatus">
									<option <?if($active=='0') echo "selected";?> value="0">Inactive</option>
									<option <?if($active=='1') echo "selected";?>  value="1">Active</option>
								</select>
                                <button type="submit" class="btn btn-primary" name="save" id="save">Save</button>
                        	</div>
                         </div>
                      </div>
                  </div>
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
