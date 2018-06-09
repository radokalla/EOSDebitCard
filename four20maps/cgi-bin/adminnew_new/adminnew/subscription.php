<?php
// include Config File
include_once './includes/config.inc.php';
include_once './includes/class.database.php';
include_once './includes/validate.php';
// Authenticate user login
//auth();

?>
	  <?php include("header.php"); ?>
      <!-- Left side column. contains the logo and sidebar -->
      
      

      <?php include("sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Store Subscriptions</h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Store Subscriptions</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
            	<div class="box">
                <div class="box-header">
                  <h3 class="box-title">Store Subscriptions</h3>
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
                            <div class="dataTables_length" id="example1_length">
                            	<a style="float:right" href="addsubs.php" class="btn btn-primary">Add New Subscription</a>
                            </div>
                        </div>
                    </div>
                  <div class="row">
                      <div class="col-lg-12 col-sm-12 col-xs-12">
                          <table class="table table-bordered table-striped">

				<thead>

				<tr>
</div></a></th>

<th>Subscription Name</th>

<th>Description</th>

<th>Duration</th>

<th>Intial Amount</th>
<th>Renual Amount</th>
<th>Status</th>
<th>Action</th>

				</tr>

				</thead>

				<tbody>
				<?php  $db = db_connect();
						$result=mysql_query("select * from SubscriptionTypes") or die(mysql_error()); ?>

					<?php while($v=mysql_fetch_array($result)) { ?>

						<td><?php echo $v['Subscription']; ?></td>

						<td><?php echo $v['Description']; ?></td>

						

						<td><?php echo $v['Duration']; ?></td>

						<td><?php echo $v['InitialAmount']; ?></td>

						<td><?php echo $v['RenualAmount']; ?></td>

						<td class="acenter"><?php $stat = $v['Status']; if($stat==1){echo "Active";} else {echo "In-Active";} ?></td>

						<td class="actions">
							<a href="#" data-toggle="modal" data-target="#eDIT" OnClick="editdata('<?php echo $v['SubscriptionTypeId'];  ?>');"><i class="fa fa-pencil"></i></a>
							&nbsp&nbsp <a href="#" OnClick="DelSub('<?php echo $v['SubscriptionTypeId'];?>');"><i class="fa fa-trash"></i></a>
						</td>
						
					</tr>

					<?php } ?>

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
