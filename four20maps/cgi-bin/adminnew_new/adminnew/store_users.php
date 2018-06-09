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
          <h1>Map Icon User's</h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Map Icon User's</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
            	<div class="box">
                <div class="box-header">
                  <h3 class="box-title">Map Icon User's</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                  <div class="row">
                      <div class="col-lg-12 col-sm-12 col-xs-12">
                          <table class="table table-bordered table-striped">

				<thead>

				<tr>
</th>

<th>Name</th>

<th>User Name</th>

<th>Email</th>

<th>Mobile</th>
<th>Subscription Type</th>
<th>Payment</th>
<th>Address</th>
<th>Status</th>
<th>Actions</th>

				</tr>

				</thead>

				<tbody>
				<?php  $db = db_connect();
						$result=mysql_query("select u.*, st.Subscription, st.SubscriptionTypeId from StoreUsers as u LEFT JOIN SubscriptionTypes as st ON u.SubscriptionTypeId = st.SubscriptionTypeId") or die(mysql_error()); ?>

					<?php while($v=mysql_fetch_array($result)) { $name = $v['Firstname'].$v['Lastname']; ?>

						<td><?php echo $name ?></td>

						<td><?php echo $v['Username']; ?></td>

						<td><?php echo $v['Email']; ?></td>

						<td><?php echo $v['Mobile']; ?></td>

						<td><?php echo $v['Subscription']; ?></td>
						
						<td><?php if($v['Payment']==1){echo "Paid";}else {echo "Un-Paid";} ?></td>
						
						<td><?php echo $v['Address']; ?></td>

						<td><?php $str = $v['Status']; if(($str)==1){echo"Active";} else{echo"In-Active";}  ?></td>

						<td class="actions">
							<a href="#" data-toggle="modal" data-target="#eDIT" OnClick="Editdata('<?php echo $v['UserId'];  ?>');">
							<i class="fa fa-pencil"></i></a>
							&nbsp&nbsp <a href="#" OnClick="Deluser('<?php echo $v['UserId'];?>');"><i class="fa fa-trash"></i></a>
						</td>
						
					</tr>

					<?php } ?>

				</tbody>

			</table>
                      </div>
                  </div>
                  <div class="row">
                  	<div class="col-lg-12 col-sm-12 col-xs-12">
                    	<?php  

			

			echo $pagLink . "</ul></div>";  

		?>  
                    </div>
                  </div>
                  </div>
                </div><!-- /.box-body -->
              </div>
            </div>

          </div><!-- /.row -->
          
          <div class="modal fade" id="eDIT" tabindex="-1" role="dialog" aria-labelledby="eDIT" aria-hidden="true" style="top:25%">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title LRhd" id="myModalLabel">Edit User</h4>
				</div>
				<div class="modal-body">
					<form method="post"  action="" id="Users_edit_form" class="xform">
						<section>
							<div class="row-fluid">
								<div class="span4">
									<label class="input">First Name</label>
								</div>
								<div class="span6">
									<input  type="text" name="FirstName" id="FirstName" placeholder="First Name" value="" class="required">
								</div>
							</div>
							<div class="row-fluid">
								<div class="span4">
									<label class="input">Last Name</label>
								</div>
								<div class="span6">
									<input  type="text" name="LastName" id="LastName" placeholder="Last Name" value="" class="required">
								</div>
							</div>
							<div class="row-fluid">
								<div class="span4">
									<label class="input">User Name</label>
								</div>
								<div class="span6">
									<input  type="text" id="Username" placeholder="User Name" value="" class="required" readonly>
								</div>
							</div>
							<div class="row-fluid">
								<div class="span4">
									<label class="input">Email</label>
								</div>
								<div class="span6">
									<input  type="text" id="Email" placeholder="Email" value="" class="required" readonly>
								</div>
							</div>
							<div class="row-fluid">
								<div class="span4">
									<label class="input">Mobile</label>
								</div>
								<div class="span6">
									<input  type="text" name="Mobile" id="Mobile" placeholder="Mobile Number" value="" class="required">
								</div>
							</div>
							<div class="row-fluid">
								<div class="span4">
									<label class="input">Address</label>
								</div>
								<div class="span6">
									<textarea name="Address" id="Address" placeholder="Address" ></textarea>
								</div>
							</div>
							<div class="row-fluid">
								<div class="span4">
									<label>Status</label>
								</div>
								<div class="span6">
									<select id="Status" name="Status">
										<option value='1' id="option1">Active</option>
										<option value='0' id="option2">In-Active</option>
									</select>
								</div>
							</div>
								<div class="col-md-12">
									<button type="button" class="button button-secondary" OnClick="EditSubmit();">Save</button>
								</div>
								<input type="hidden" name="type" value="6"/>
								<input type="hidden" name="UserId" value="" id="UserId"/>
						</section>
					</form>
				</div>
			</div>
		</div>
	</div>

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      
      	
<script>
function Editdata(uid)
{
	var type = 5;
		$.ajax({
				type: "POST",
				url: "storesdb.php",
				data : {'type':type, 'uid':uid},
				success: function(data)
				{
					var obj = JSON.parse(data);
					$('#FirstName').val(obj.Firstname);
					$('#LastName').val(obj.Lastname);
					$('#Email').val(obj.Email);
					$('#Mobile').val(obj.Mobile);
					$('#Address').val(obj.Address);
					$('#UserId').val(obj.UserId);
					$('#Username').val(obj.Username);
					var status = obj.Status;
					 if(status == "1")
					 {
						 $('#option1').attr("selected","selected");
					 }
					 else
					 {
						 $('#option2').attr("selected","selected");
					 }
				}
			});
}

function EditSubmit()
{
	var formdata = $('#Users_edit_form').serializeArray();
	var FirstName = $('#FirstName').val();
	var LastName = $('#LastName').val();
	var Email = $('#Email').val();
	var Mobile = $('#Mobile').val();
	var Address = $('#Address').val();
	var status = $('#Status').val();
	if((FirstName!='') && (LastName!='') && (Email!='') && (Mobile!='') && (Address!='') && (Status!=''))
	{
		$.ajax({
				type: "POST",
				url: "storesdb.php",
				data : formdata,
				success: function(data)
				{
					if(data==1)
					{
						$('#eDIT').modal('hide');
						document.getElementById('msg').innerHTML = 'Update Successful..';
						$('#msg').show();
						setTimeout(function (){window.location.href = ""; }, 3000);
					}
				}
				});
	}
}

function Deluser(uid)
{
	var type = '7';
		$.ajax({
				type: "POST",
				url: "storesdb.php",
				data : {'type':type, 'uid':uid},
				success: function(data)
				{
					
				}
			});
}
</script>
<?php include("footer.php"); ?>
