<?php
$heading='store';
// include Config File
include_once './includes/config.inc.php';
include_once './includes/class.database.php';
include_once './includes/validate.php';
// Authenticate user login
auth();

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
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Map Icon User's</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
            	<div class="box">
					<div class="alert alert-success" id="msg" style="text-align:center; display:none"></div>
                <div class="box-header">
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
						<div class="row">
							<div class="col-lg-12 col-sm-12 col-xs-12">
								<div id="example1_filter" class="res-marg rev-width-alg">
									<div class="row">
									<form action="" method="post">
										<div class="col-lg-3 col-sm-4 col-xs-12">
											<input type="text" class="form-control" placeholder="Enter email or user name" name="search" id="search" <?php if($_POST)
																			{?> value="<?php echo $_POST['search']?>" <?php } ?>>
										</div>
										<div class="col-lg-3 col-sm-4 col-xs-12">
											<select name="type" class="form-control" required>
												<option selected disabled value='' class="form-control">Select type</option>
												<option value="email">Email</option>
												<option value="username">User Name</option>
											</select>
										</div>
										<div class="col-lg-2 col-sm-2 col-xs-12">
											<button class="btn btn-primary" type="submit">Search</button>
										</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
						  <div class="col-lg-12 col-sm-12 col-xs-12">
							  <div class="table-responsive">
								  <table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Name</th>
											<th>User Name</th>
											<th>Email</th>
											<th>Mobile</th>
											<th>Address</th>
											<th>Status</th>
											<th class="text-center">Actions</th>
										</tr>
									</thead>
								<tbody>
						<?php  $db = db_connect();
								$query = '';
								if($_POST)
								{
								 $skey = $_POST['search'];
								 if($_POST['type']=='email')
									$query .= " u.Email like '%$skey%' ";
								 if($_POST['type']=='username')
									$query .= " u.Username like '%$skey%' ";
								}
								$sql = "select u.*, st.Subscription, st.SubscriptionTypeId from StoreUsers as u LEFT JOIN SubscriptionTypes as st ON u.SubscriptionTypeId = st.SubscriptionTypeId where u.Status!='2'";
								if( $query != '')
								 $sql.=" and ".$query;
								$result=mysql_query($sql) or die(mysql_error());
								$count = mysql_num_rows($result);
								?>
								
							<?php if($count>0){ while($v=mysql_fetch_array($result)) { $name = $v['Firstname'].$v['Lastname']; ?>

								<td><?php echo $name ?></td>

								<td><?php echo $v['Username']; ?></td>

								<td><?php echo $v['Email']; ?></td>

								<td><?php echo $v['Mobile']; ?></td>
								
								<td><?php echo $v['Address']; ?></td>

								<td><?php $str = $v['Status']; if(($str)==1){echo"Active";} else{echo"In-Active";}  ?></td>

								<td class="actions text-center">
								<a href="assignStore.php?id=<?=$v['UserId'];?>&sub_id=<?=$v["SubscriptionCategoryId"];?>&user_name=<?=$v['Username'];?>"><i class="fa fa-tasks"></i></a>
									<a href="#" data-toggle="modal" data-target="#eDIT" OnClick="Editdata('<?php echo $v['UserId'];  ?>');">
									<i class="fa fa-pencil"></i></a>
									&nbsp&nbsp <a href="#" OnClick="Deluser('<?php echo $v['UserId'];?>');"><i class="fa fa-trash"></i></a>
								</td>
								
							</tr>

							<?php } }else{?>
								<tr><td style="background-color:#fff"><h5>No results found..</h5></td></tr>
							<?php } ?>
						</tbody>

						</table>
						</div>
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
      
          <div class="modal fade" id="eDIT" tabindex="-1" role="dialog" aria-labelledby="eDIT" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title LRhd" id="myModalLabel">Edit User</h4>
				</div>
				<div class="modal-body">
					<div class="alert alert-danger" id="modalerror" style="text-align:center; display:none"></div>
					<form method="post" class="form" OnSubmit="return false;"  id="Users_edit_form" class="xform">
							<div class="form-group">
								<label class="input">First Name</label>
								<input  type="text" name="FirstName" id="FirstName" placeholder="First Name" value="" class="required form-control">
							</div>
							<div class="form-group">
								<label class="input">Last Name</label>
								<input  type="text" name="LastName" id="LastName" placeholder="Last Name" value="" class="required form-control">
							</div>
							<div class="form-group">
								<label class="input">User Name</label>
								<input  type="text" id="Username" placeholder="User Name" value="" class="required form-control" readonly>
							</div>
							<div class="form-group">
								<label class="input">Email</label>
								<input  type="text" id="Email" placeholder="Email" value="" class="required form-control" readonly>
							</div>
							<div class="form-group">
								<label class="input">Mobile</label>
								<input  type="text" name="Mobile" id="Mobile" placeholder="Mobile Number" value="" class="required form-control">
							</div>
							<div class="form-group">
								<label class="input">Address</label>
								<textarea name="Address" id="Address" placeholder="Address" class="form-control" ></textarea>
							</div>
							<div class="form-group">
								<label>Status</label>
								<select id="Status" name="Status" class="form-control">
										<option value='1' id="option1">Active</option>
										<option value='0' id="option2">In-Active</option>
									</select>
							</div>
							<div class="form-group">
								<button type="button" class="btn btn-primary" OnClick="EditSubmit();">Save</button>
								<button class="btn btn-danger" Onclick="$('#eDIT').modal('hide'); return false;">Cancel</button>
							</div>
								<input type="hidden" name="type" value="6"/>
								<input type="hidden" name="UserId" value="" id="UserId"/>
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
	var FirstName = $.trim($('#FirstName').val());
	var LastName = $.trim($('#LastName').val());
	var Email = $.trim($('#Email').val());
	var Mobile = $.trim($('#Mobile').val());
	var Address = $.trim($('#Address').val());
	var status = $.trim($('#Status').val());
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
	else
	{
		document.getElementById('modalerror').innerHTML = 'Please fill all the details';
		$('#modalerror').show();
		setTimeout(function (){ $('#modalerror').hide(); }, 3000);
	}
}

function Deluser(uid)
{
	var type = '14';
	if(confirm("Deleting a user deletes stores, categories & products created by them. \n And cannot be registered again \n Do you really want to continue ?"))
		$.ajax({
				type: "POST",
				url: "storesdb.php",
				data : {'type':type, 'uid':uid},
				success: function(data)
				{
					if(data==1)
					{
						alert("DELETED Successfully");
						window.location.href='';
					}
					else
						alert("Delete failed.. \n Please try Again");
				}
			});
}
 
</script>
<?php include("footer.php"); ?>
