<?php
// include Config File
include_once './includes/config.inc.php';
include_once './includes/validate.php';
// Authenticate user login
auth();
validate_cat_add();
$db = db_connect();
if($_POST)
{
	$data = $_POST;
	print_r($data);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title><?php echo $lang['STORE_FINDER']; ?> - <?php echo $lang['SSF_ADD_CATEGORY']; ?></title>
		<?php include 'header.php'; ?>
	</head>
	<body id="add_edit_body">
		<div id="wrapper">
			<div id="header">
				
				<?php include 'nav.php'; ?>
			</div>

			<div id="main">
				<section>
					<h2>Add New Subscription</h2>
					<form method="post" OnSubmit="return false;" id="SubsForm">
						<div class="row-fluid">
							<div class="span12">
								<label>Subscription Name: <span class='required'>*</span></label>
								<input type="text" name="Subscription" class="form-control" id="subscription" required/>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span12">
								<label>Description: <span class='required'>*</span></label>
								<textarea name="Description" class="form-control" id="description" required></textarea>
							</div>
						</div>
						<div class="row-fluid">
							<label>Duration: <span class='required'>*</span></label>
							<div class="span3">
								<label>Years: <span class='required'>*</span></label>
								<input type="number" name="years" class="form-control" id="years" required />
							</div>
							<div class="span3">
								<label>Months: <span class='required'>*</span></label>
								<input type="number" name="months" class="form-control" id="months" required />
							</div>
							<div class="span3">
								<label>Days: <span class='required'>*</span></label>
								<input type="number"  name= "days" class="form-control" id="days" required />
							</div>
						</div>
						<div class="row-fluid">
							<div class="span6">
								<label>Initial Amount: <span class='required'>*</span></label>
								<input type="number" name="IntialAmount" class="form-control" id="IntialAmount" required />
							</div>
						</div>
						<div class="row-fluid">
							<div class="span6">
								<label>Renual Amount: <span class='required'>*</span></label>
								<input type="number" name="RenualAmount" class="form-control" id="RenualAmount" required />
							</div>
						</div>
						<div class="row-fluid">
							<div class="span6">
								<select name="Status" class="form-control" id="status" required>
									<option selected disabled>Select Status</option>
									<option value="1">Avtive</option>
									<option value="2">In-Active</option>
								</select>
							</div>
						</div>
						<input type="hidden" name="type" value="1">
						<div class="row-fluid">
							<div class="span3">
								<button class="btn btn-primary" OnClick="Addsubs();">Submit</button>
								<button type="reset" class="btn btn-danger">Reset</button>
							</div>
						</div>
					</form>
				</section>
			</div>
		</div>
	</body>
	<script>
		function Addsubs()
		{
			var formdata = $('#SubsForm').serializeArray();
			var subscription = $('#subscription').val();
			var description = $('#description').val();
			var years = $('#years').val();
			var months = $('#months').val();
			var days = $('#days').val();
			var IntialAmount = $('#IntialAmount').val();
			var RenualAmountv = $('#RenualAmount').val();
			var status = $('#status').val();
			if((subscription!='') && (description!='') && (years!='') && (months!='') && (days!='') && (IntialAmount!='') && (RenualAmountv!='') && (status!=''))
			{
				$.ajax({
						type: "POST",
						url: "storesdb.php",
						data : formdata,
						success: function(data)
						{
							document.getElementById('msg').innerHTML = data;
							$('#msg').show(); 
							setTimeout(function (){window.location.href = "subscriptions.php"; }, 5000);
						}
					});
			}
			else
			{
				document.getElementById('msg').innerHTML = 'Please Fill all the Required Fields..';
				$('#msg').show(); 
			}
		}
	</script>
</html>
	