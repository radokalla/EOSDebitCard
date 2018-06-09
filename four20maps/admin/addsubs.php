<?php
$heading='store';
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
	//print_r($data);
}
$sql = "select * from SubscriptionCategory where  `Status` =1 and `IsDeleted`=0";	 
	$subscriptionCategory = mysql_query($sql);
	while($subscriptionCategoryDetails = mysql_fetch_assoc($subscriptionCategory))
	{
		$subscriptionCategoryData[] = $subscriptionCategoryDetails;
	}
?>
	  <?php include("header.php"); ?>
      <!-- Left side column. contains the logo and sidebar -->
      <?php include("sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Add New Subscription</h1>
          <ol class="breadcrumb">
            <li><a href="subscription.php"><i class="fa fa-dashboard"></i>Store Subscription</a></li>
            <li class="active">Add New Subscription</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
            	<div class="box">
                <div class="box-header">
                  <?php /*?><h3 class="box-title"><?php echo $lang['ADMIN_ADD_SUBSCRIPTION']; ?></h3><?php */?>
                </div><!-- /.box-header -->
                <div class="box-body">

			<div class="alert alert-success" id="msg" style="display:none; text-align:center"></div>
			<form method="post" action='storesdb.php' id="SubsForm" enctype="multipart/form-data">

			<div class="row">
            	<div class="col-lg-6 col-sm-12">

					<div class="form-group">
							<label class="input">Subscription Category</label>
							 <select class="form-control" id="SubscriptionCategory" name="SubscriptionCategoryId">
							 	<?php foreach($subscriptionCategoryData as $subscriptionCategory) { ?>
							 	 		<option value="<?=$subscriptionCategory["SubscriptionCategoryId"];?>">
							 	 		<?=$subscriptionCategory["Subscription"];?></option>
							 	<?php } ?>
							 </select>
						</div>
						<div class="form-group">
                            <label>Subscription Name: <span class='required'>*</span></label>
    
                            <input type='text' class="form-control" OnBlur="func(this.id);" name="Subscription" maxlength="25" class="form-control" id="subscription" required aria-required="true" />
                        </div>

						
						
						<div class="form-group">
                            <label>Description: <span class='required'>*</span></label>
    
                            <textarea name="Description" maxlength="25" class="form-control" id="description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Initial Amount: <span class='required'>*</span></label>
                            <input type="number" name="IntialAmount" OnBlur="nums(this.id);" class="form-control" id="IntialAmount" required />
                        </div>
                        <div class="form-group">
                            <label>Renewal Amount: <span class='required'>*</span></label>
                            <input type="number" name="RenualAmount" OnBlur="nums(this.id);" class="form-control" id="RenualAmount" required />
                        </div>
                        
					</div>
					<div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                            <label>Number Of Stores <span class='required'>*</span></label>
                            <input type='number' class="form-control" OnBlur="nums(this.id);" name="stores" class="form-control" id="stores" required />
                        </div>
                    
                        <div class="form-group">
                            <label>Status: <span class='required'>*</span></label>
                            <select name="Status" class="form-control" id="status" required>
									<option selected disabled value=''>Select Status</option>
									<option value="1">Active</option>
									<option value="2">In-Active</option>
								</select>
                        </div>
						<div class="form-group">
                            <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                <label>Duration: <span class='required'>*</span></label>
                                <input type="number" name="Duration" OnBlur="nums(this.id);" class="form-control" id="Duration" required />
                            </div>
                            <div class="col-lg-6 col-sm-6">
                            <label>&nbsp;</label>
                                <select class="form-control" name="duration_type" id="duration_type" required>
									<option value="" selected diabled> Select one</option>
                                    <option value="days">Days</option>
                                    <option value="months">Months</option>
                                    <option value="years">Years</option>
                                </select>
                            </div>
                            </div>
                        </div>
                        
                        
						
                        
					</div>
                    <div class="col-lg-10 col-sm-12">	
                    	<div class="row">
                            <div class="form-group col-lg-4 col-sm-12">
                                <label>Map Icon :<span class='required'></span></label>
                                <input type="file" name="Icon1" id="Icon1" />
                            </div>
                            <div class="form-group col-lg-4 col-sm-12">
                                <label>Delivery Icon :<span class='required'></span></label>
                                <input type="file" name="Icon2" id="Icon2" /> 
                            </div>
                            <div class="form-group col-lg-4 col-sm-12">
                                <label>Dispensery Icon :<span class='required'></span></label>
                                <input type="file" name="Icon3" id="Icon3" />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12">
                        <div class='input buttons'>
                            <a class="btn btn-primary" OnClick="Addsubs();">Submit</a>
							<input type="hidden" value="1" name="type" />
							<button type="reset" class="btn btn-danger">Reset</button>
                        </div>
                    </div>
				</div>
			</form>

                </div><!-- /.box-body -->
              </div>
            </div>

          </div><!-- /.row -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
            <?php include("footer.php"); ?>
<script>
		function Addsubs()
		{
			var formdata = $('#SubsForm').serializeArray();
			var subscription = $('#subscription').val();
			var description = $('#description').val();
			var Duration = $('#Duration').val();
			var duration_type = $('#duration_type').val();
			var IntialAmount = $('#IntialAmount').val();
			var RenualAmountv = $('#RenualAmount').val();
			var stores = $('#stores').val();
			var image1 = $('#Icon1').val();
			var image2 = $('#Icon2').val();
			var image3 = $('#Icon3').val();			
			var status = $('#status').val();
			if((subscription !=null) && (description !=null) && (IntialAmount !=null) && (RenualAmountv !=null) && (image1 !=null) && (image2 !=null) && (image3 !=null) && ((Duration !=null) ||(Duration !='')) && ((duration_type !=null)||(duration_type !='') && (stores !=null) && (status!=null)))
			{
				if((IntialAmount >= 0) && (RenualAmountv >= 0) && (stores >= 0) && (Duration >= 0))
				{
					$('#SubsForm').submit();
				}
				else
				{
					document.getElementById('msg').innerHTML = 'Initial, Renewal Amounts and stores, Duration Cannot be Negative.';
					$('#msg').removeClass('alert-success')
					$('#msg').addClass('alert-warning');
					$('#msg').show();
					setTimeout(function (){ $('#msg').hide(); }, 3000);
				}
			}
			else
			{
				document.getElementById('msg').innerHTML = 'Please Fill all the Required Fields..';
				$('#msg').removeClass('alert-success')
				$('#msg').addClass('alert-warning');
				$('#msg').show(); 
				setTimeout(function (){ $('#msg').hide(); }, 3000);
			}
		}
		
function func(id)
{
	var namesPattern = /^[a-zA-Z ]*$/;
	var name = $('#'+id).val();
	if(!namesPattern.test(name))
	{
		$('#'+id).css('border-color','red')
		$('#'+id).val('');
	}
	else
	{
		$('#'+id).css('border-color','#eee')
	}
}

function nums(num)
{
	var numPat = /^[0-9]+$/;
	var number = $('#'+num).val();
	if(!numPat.test(number))
	{
		$('#'+num).css('border-color','red')
		$('#'+num).val('');
	}
	else
	{
		$('#'+num).css('border-color','')
	}
}
</script>
<script type="text/javascript" src="http://www.four20maps.com/js/jquery.validate.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
		$("#SubsForm").validate();
	});
	</script>