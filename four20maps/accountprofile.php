<?
ob_start();
session_start();
 $header='Profile';
$username = $_SESSION["regSuccess"];
if(empty($username)){ ?>
	<script>
		window.location.href = 'addstore.php';
	</script>
<?php }
include_once 'header.php';
error_reporting(0);
$db = db_connect();
if($_POST)
{
	$db->update('StoreUsers',$_POST,array('UserId' => $_POST['UserId']));
	exit;
}
$query = "select * from StoreUsers where UserId =".$_SESSION['StoreID'] or die(mysql_error());
$user = mysql_query($query);
$userDetails = mysql_fetch_array($user);
?>
        	<div class="row"> 
            	<?php include ROOT."admin-left.php"; ?>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 myact-userdet">
                        <h2 class="head-text">Account Profile</h2>
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						  <?php echo FlashMessage::render();?>
						  <div class="alert alert-warning" role="alert" style="display:none; text-align:center" id="alert"></div>
                        <form class="form-horizontal" method="post"   id="form"> 
                         <input type="hidden" name='UserId' id='UserId' class="form-control required alpha" value="<?php echo $_SESSION["StoreID"]?>">
                            <div class="form-group">
                                <label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">First Name<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                  <input type="text" placeholder="First Name" name='Firstname' id='Firstname' value="<?php echo $userDetails["Firstname"]?>" class="form-control required alpha">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">Last Name<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                  <input type="text" placeholder="Last Name" name='Lastname' id='Lastname' value="<?php echo $userDetails["Lastname"]?>" class="form-control required alpha">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">Email Id<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                  <input type="email" placeholder="Email Id" name='Email' id='Email' value="<?php echo $userDetails["Email"]?>" class="form-control required alpha" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">Phone<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                  <input type="text" placeholder="Phone Number" name='Mobile' id='Mobile' value="<?php echo $userDetails["Mobile"]?>" class="form-control required alpha">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">User Name<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                  <input type="text" placeholder="User Name" raedonly name='Username' id='Username' value="<?php echo $userDetails["Username"]?>" class="form-control required alpha" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">Address<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                  <textarea class="form-control required alpha" name='Address' id='Address' placeholder="Address" required><?php echo $userDetails["Address"]?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-8 col-md-8 col-sm-8 col-sm-offset-4 col-md-offset-4">                        
                                <button class="btn btn-primary" OnClick="FormSubmit(); return false" name="saveprofile">Update account</button>
                                <a href="dashboard.php"><button class="btn btn-danger" type="button">Cancel</button></a>
                                </div>
                    		</div>
                        </form>
                        </div>
                     </div>
                </div>
            </div>
        </div>
	</div>
</div>
<script>
function FormSubmit()
{
	var formdata = $('#form').serializeArray();
	var namesPattern = /^[a-zA-Z ]*$/;
	var eMailPattern = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	var phonePattern = /^\d{10}$/;
	var Firstname = $('#Firstname').val();
	var Lastname = $('#Lastname').val();
	var Mobile = $('#Mobile').val();
	var Address = $('#Address').val();
	if((Firstname!='') && (Lastname!='') && (Mobile!='') && (Address!=''))
	{
		if(!namesPattern.test(Firstname))
		{
			document.getElementById('alert').innerHTML = 'Name Should contain only Alphabets.';
			$('#alert').show();
		}
		else if(!namesPattern.test(Lastname))
		{
			document.getElementById('alert').innerHTML = 'Name Should contain only Alphabets.';
			$('#alert').show();
		}
		else if(!phonePattern.test(Mobile))
		{
			document.getElementById('alert').innerHTML = 'Enter a Valid Mobile Number';
			$('#alert').show();
		}
		else
		{
			$.ajax({
					type: "POST",
					url: "",
					data : formdata,
					success: function(data)
					{
						document.getElementById('alert').innerHTML = 'Update Successful';
						$('#alert').removeClass('alert alert-warning');
						$('#alert').addClass('alert alert-success');
						$('#alert').show();
						setTimeout(function (){$('#alert').hide(); }, 4000);
					}
			});
		}
	}
	else
	{
		document.getElementById('alert').innerHTML = 'Fill all the Details';
		$('#alert').removeClass('alert alert-success');
		$('#alert').addClass('alert alert-warning');
		$('#alert').show();
		setTimeout(function (){$('#alert').hide(); }, 4000);
	}
	
}
</script>

<?php include ROOT."themes/footer.inc.php"; ?>

</body>
</html>