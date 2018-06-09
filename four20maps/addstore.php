<?php 
 ini_set('error_reporting', 0);
ini_set('display_errors', 0);
 session_start();
 if(!empty($_SESSION["regSuccess"])){ ?>
<script>window.location.href=<?php echo ROOT_URL; ?>'subs.php'</script>
 <?php }
 include_once 'header.php'; 
 //include_once './includes/config.inc.php';
include_once './includes/class.database.php'; 
	//$sql = mysql_query("select * from SubscriptionTypes where InitialAmount = '0' and Status='1'");
$sql = "select * from SubscriptionCategory where  `Status` =1 and `IsDeleted`=0";	 
	$subscriptionCategory = mysql_query($sql);
	while($subscriptionCategoryDetails = mysql_fetch_assoc($subscriptionCategory))
	{
		$subscriptionCategoryData[] = $subscriptionCategoryDetails;
	}
//$sql_result=mysql_query($sql);
$addimages="select aid,image,url,start_date,end_date from adds where status='Active' AND now() >= start_date and now() <= end_date and is_delete=0 order by last_modified DESC LIMIT 5";
$qrryexc=mysql_query($addimages);
while($num_rows=mysql_fetch_assoc($qrryexc))
{
	$images[]=$num_rows;
}
 ?>

<div class="custom-container">
<div class="row mar-t30"> 
    <!--<div class="col-md-12"><h1>Membership Packages</h1></div>-->
    <div class="col-md-7">
      <div class="planpack">
      <h1 class="h1_headtitlepg">Map Icon Subscription</h1>
			<?php  $i=1;
		   foreach($subscriptionCategoryData as $sub) {  
				if($i % 2 != 0 || $i == 1){
		  $SubscriptionCategoryId=$sub['SubscriptionCategoryId'];
		  ?>
			<div class="row"> <?php } ?><div class="col-md-5 " style="margin:10px;background:#6bc005 none repeat scroll 0 0">
					<p class="subhieght"><?php echo $sub['Subscription']; ?>
						<br><?php echo $sub['Description']; ?><br>
						
					</p><p class="btndown"><?php if(!empty($_SESSION["regSuccess"])){ ?>
						<a href="<?php echo ROOT_URL.'payment.php?type='.$sub['SubscriptionCategoryId']; ?>" data-toggle="tooltip" title="Free Listing on Map. You can add One store." class="btn btn-primary sub-buy">Subscribe</a> 
						<?php } else { ?>
						<a OnClick="reg('<?php echo $sub['SubscriptionCategoryId']; ?>','<?php echo $sub['Subscription']; ?>');" data-toggle="modal" data-target="#register-form" title="Free Listing on Map. You can add One store." data-placement="left" class="btn btn-primary sub-buy">Subscribe</a>   
						<?php } ?></p>
				</div><?php if($i % 2 == 0){ ?></div><?php } ?>
			<?php 
			
			$i++;
			} ?>
		</div>
      </div>
       
     <div class="col-md-5">
      <div class="planpack-details">





        <h2>Free Map Listing</h2>
		<ul>		
			<li>List your Delivery, Dispensary, Brands Vendor and Farm for FREE</li>
			<li>Upgrade your Listing to Bronze</li>
			<li>Upgrade your Listing to Silver</li>
			<li>Upgrade your Listing to Gold</li>
			<li>Upgrade your Listing to Premium</li>
			<li>List your menu for FREE</li>
			<li>Upgrade your menu for online ordering with <a href="http://420medsoft.com/" target="_blank">www.420MedSoft.com package</a></li>
			<li>420MedSoft package includes patient Website Admin Site and Newsletter e-mail System</li>
		</ul>
      </div>
    </div>
</div>
	<div class="modal fade" id="register-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title LRhd" id="myModalLabel2">Register (Map User's)</h4>
				</div>
				<div class="modal-body">
					<h4 id="alert" style="display:none; text-align:center; color:red"></h4>
					<form class="xform" id="FormReg" OnSubmit="return false;">
						<input type="hidden" value="" id="SubscriptionCategoryId" name="SubscriptionCategoryId">
					 	
						 		<div class="form-group">
									<label class="input"> <i class="icon-prepend fa fa-user"></i>
										<input type="text" id="regfname" placeholder="First Name" class="form-control" value="" name="Firstname" required />
									</label>
								</div>
								<div class="form-group">
									<label class="input"> <i class="icon-prepend fa fa-user"></i>
										<input type="text" id="reglname" placeholder="Last Name" class="form-control" value="" name="Lastname" required />
									</label>
								</div>
							
								<div class="form-group">
									<label class="input"> <i class="icon-prepend fa fa-envelope"></i>
										<input type="email" id="email" placeholder="Email Id" class="form-control"  name="Email" required />
									</label>
								</div>
						
								<div class="form-group">
									<label class="input"> <i class="icon-prepend fa fa-user"></i>
									<input type="text" id="uname" placeholder="User Name" class="form-control" name="Username" required />
								</label>
								</div>
							
								<div class="form-group">
									<label class="input"> <i class="icon-prepend fa fa-lock"></i>
										<input type="password" id="pswrd" placeholder="Password" class="form-control" name="Password" required />
									</label>
								</div>
							
									<div class="form-group">
									<label class="input"> <i class="icon-prepend fa fa-lock"></i>
										<input type="password" id="cpswrd" placeholder="Confirm Password" class="form-control" required />
									</label>
								</div>
							
								<div class="form-group">
									<label class="input"> <i class="icon-prepend fa fa-phone"></i>
										<input type="text" id="phno" placeholder="Mobile Number" name="Mobile" class="form-control" required />
									</label>
								</div>
						
							<div class="form-group">
                                <div class="row"><div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                    <button class="btn btn-success" id="regbtn" onClick="FormSubmit();">Register</button>
									<img src="img/loading.gif" style="height:25px; width:25px; display:none" id="processing">
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
									<a href="#"  data-toggle="modal" data-dismiss="modal" data-target="#myModal1" id="login_id"> Already a Member ?</a>
								</div></div>
							</div>
					</form>
						</div>
				</div>
			</div>
		</div>


<script type="text/javascript">
  
 $(document).ready(function() {
   var maxHeight = -1;

   $('.subhieght').each(function() {
     maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();
   });

   $('.subhieght').each(function() {
     $(this).height(maxHeight);
   });
 });
function reg(id,title)
{
	$("#myModalLabel2").html(title);
	$('#SubscriptionCategoryId').val(id);
	$('#register-form').modal('show');
	
	//$('#subs_type').val(id);
}
function FormSubmit()
{
	$('#alert').hide();
	var formdata = $('#FormReg').serializeArray();
	var username = $('#uname').val();
	var email = $('#email').val();
	var password = $('#pswrd').val();
	var cpassword = $('#cpswrd').val();
	var phoneno = $('#phno').val();
	var address = $('#address').val();
	var regfname = $('#regfname').val();
	var reglname = $('#reglname').val();
	var eMailPattern = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	var phonePattern = /^\d{10}$/;
	var namesPattern = /^[a-zA-Z ]*$/;
	if((username!='') && (email!='') && (password!='') && (cpassword!='') && (phoneno!='') && (regfname) && (reglname))
	{
		if(!namesPattern.test(regfname))
		{
			document.getElementById('alert').innerHTML = 'Name Should contain only Alphabets.';
			$('#alert').css('text-color','red');
			$('#alert').show();
		}
		else if(!namesPattern.test(reglname))
		{
			document.getElementById('alert').innerHTML = 'Name Should contain only Alphabets.';
			$('#alert').css('text-color','red');
			$('#alert').show();
		}
		else if(!eMailPattern.test(email))
		{
			document.getElementById('alert').innerHTML = 'Enter a Valid Email ID';
			$('#alert').css('text-color','red');
			$('#alert').show();
		}
		else if(!phonePattern.test(phoneno))
		{
			document.getElementById('alert').innerHTML = 'Enter a Valid Mobile Number';
			$('#alert').css('text-color','red');
			$('#alert').show();
		}
		else if((password)!=(cpassword))
		{
			document.getElementById('alert').innerHTML = 'Password & Confirm Password <br> Mis - Match.';
			$('#alert').css('text-color','red');
			$('#alert').show();
		}
		else
		{
			$('#processing').show();
			$('#regbtn').hide();
			$.ajax({
					type: "POST",
					url: "checkEmail.php",
					data : {'email':email, type:'1'},
					success: function(data)
					{
						if(data==1)
						{
							$.ajax({
								type: "POST",
								url: "checkEmail.php",
								data : {'username':username, type:'2'},
								success: function(data)
								{
									if(data==1) 
									{
										$.ajax({
											type: "POST",
											url: "checkEmail.php",
											data : formdata,
											success: function(data)
											{
												if(data==1)
												{
													$('#FormReg').hide();
													$('#alert').css('color','green');
													document.getElementById('alert').innerHTML = 'Registration Successful <br> Please wait <br> While you are redirected..';
													$('#alert').css('text-color','green');
													$('#alert').show();
													setTimeout(function (){window.location.href = "storedetails.php"; }, 5000);
												}
												else
												{
													$('#processing').hide();
													document.getElementById('alert').innerHTML = data;
													$('#regbtn').show();
													$('#alert').show();
												}
											}
										});
									}
									else
									{
										$('#processing').hide();
										$('#regbtn').show();
										document.getElementById('alert').innerHTML = data;
										$('#alert').show();
									}
								}
							});
						}
						else
						{
							$('#processing').hide();
							$('#regbtn').show();
							document.getElementById('alert').innerHTML = 'Email Already Exist';
							$('#alert').show();
						}
					}
				});
		}
	}
	else
	{
		document.getElementById('alert').innerHTML = 'Please Fill all the Details';
		$('#alert').show();
	}
	
	
		
}
</script>

<style>
@media (min-width: 768px) and (max-width: 991px) {
.custom-container {
	width:500px;	
	margin:0px auto;
}
	
}

@media (min-width: 992px) and (max-width: 1199px) {
.custom-container {
	width:650px;	
	margin:0px auto;
}
	
}


</style>

 <?php include_once 'sidebar.php';  ?>	