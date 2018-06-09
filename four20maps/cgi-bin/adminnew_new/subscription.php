<?php
// include Config File
include_once './includes/config.inc.php';
include_once './includes/class.database.php';
include_once './includes/validate.php';
// Authenticate user login
auth();

?>
<html>
<head>
	<title>Subscription Types</title>
	<?php include 'header.php'; ?>
</head>
<body id="stores">
	<div id="wrapper">

		<div id="header">

			

			<?php include 'nav.php'; ?>

		</div>

		<div id="main">

		

			<h2>Store Subscriptions </h2>

<a style="float:right" href="addsubs.php">Add New Subscription</a>
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
							<a href="#" data-toggle="modal" data-target="#eDIT" OnClick="editdata('<?php echo $v['SubscriptionTypeId']?>');"><i class="fa fa-pencil"></i></a>
							&nbsp&nbsp <a href="#" OnClick="DelSub('<?php echo $v['SubscriptionTypeId'];?>');"><i class="fa fa-trash"></i></a>
						</td>
						
					</tr>

					<?php } ?>

				</tbody>

			</table>



			

		<?php  

			

			echo $pagLink . "</ul></div>";  

		?>  

	

		</div>

	</div>

	<?php include '../themes/footer.inc.php'; ?>

</body>
	<div class="modal fade" id="eDIT" tabindex="-1" role="dialog" aria-labelledby="eDIT" aria-hidden="true" style="top:25%">
		<div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title LRhd" id="myModalLabel">Edit User</h4>
			  </div>
			  <div class="modal-body">
			   <form method="post"  action="" id="Subs_edit_form" name="" class="xform">
					<section>
						<div class="row-fluid">
							<div class="span4">
								<label class="input">Subscription</label>
							</div>
							<div class="span6">
								<input  type="text" name="Subscription" id="Subscription" placeholder="Subscription Name" value="" class="required">
							</div>
						</div>
						<div class="row-fluid">
							<div class="span4">
								<label class="input">Duration</label>
							</div>
							<div class="span6">
								<input  type="text" name="Duration" id="Duration" placeholder="YYYY / MM / DD" value="" class="required">
							</div>
						</div>
						<div class="row-fluid">
							<div class="span4">
								<label class="input">Description</label>
							</div>
							<div class="span6">
								<textarea name="Description" id="Description" placeholder="Description" ></textarea>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span4">
								<label class="input">Intial Amount</label>
							</div>
							<div class="span6">
								<input  type="text" name="IntialAmount" id="InitialAmount" placeholder="Intial Amount" value="" requiredq>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span4">
								<label class="input">Renual Amount</label>
							</div>
							<div class="span6">
								<input  type="text" name="RenualAmount" id="RenualAmount" placeholder="Renual Amount" value="" requiredq>
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
							<input type="hidden" name="type" value="3"/>
							<input type="hidden" name="SubId" value="" id="SubId"/>
					</section>
					</form>
				</div>
		   
			</div>
		</div>
	</div>

</html>
<script>
function editdata(id)
{
	var id = id;
	var type = 2;
		$.ajax({
				type: "POST",
				url: "storesdb.php",
				data : {'type':type, 'id':id},
				success: function(data)
				{
					 var obj = JSON.parse(data);
					 $('#Subscription').val(obj.Subscription);
					 $('#Duration').val(obj.Duration);
					 $('#Description').val(obj.Description);
					 $('#InitialAmount').val(obj.InitialAmount);
					 $('#RenualAmount').val(obj.RenualAmount);
					 $('#SubId').val(obj.SubscriptionTypeId);
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
	var formdata = $('#Subs_edit_form').serializeArray();
	var Subscription = $('#Subscription').val();
	var Duration = $('#Duration').val();
	var Description = $('#Description').val();
	var InitialAmount = $('#InitialAmount').val();
	var RenualAmount = $('#RenualAmount').val();
	var Status = $('#Status').val();
	var SubId = $('#SubId').val();
	if((Subscription!='') && (Duration!='') && (Description!='') && (InitialAmount!='') && (RenualAmount!='') && (Status!='')&& (SubId!=''))
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

function DelSub(SuBid)
{
	var type = '4';
		$.ajax({
				type: "POST",
				url: "storesdb.php",
				data : {'type':type, 'subId':SuBid},
				success: function(data)
				{
					
				}
			});
}
</script>