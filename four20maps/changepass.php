<?
ob_start();
session_start();
$header = 'Password';
$username = $_SESSION["regSuccess"];
if(empty($username)){ ?>
	<script>
		window.location.href = 'addstore.php';
	</script>
<?php }
include_once 'header.php';
//include_once './includes/config.inc.php';
error_reporting(0);
$db = db_connect();
mysql_query("SET NAMES utf8");
//auth();
$_SESSION['msg']='';
//$userDetails = $db->get_row("SELECT StoreUsers.* FROM StoreUsers WHERE StoreUsers.UserId=".$_SESSION["StoreID"]);

if(isset($_POST['changepwd']))
{
   if($_POST['NPassword']==$_POST['CnewPassword'])
   {
   $userDetails = $db->get_row("SELECT StoreUsers.* FROM StoreUsers WHERE StoreUsers.Username='$username' AND Password ='".md5($_POST['Password'])."'");
	if(!empty($userDetails))
	{
		$password = md5($_POST['NPassword']);
	mysql_query("UPDATE StoreUsers SET Password = '$password' where Username = '$username' ");
	$_SESSION['passup']='Password Updated Successfully';
	}
	else{
		FlashMessage::add('Old password is incorrect.');
	}
   }else{
	  FlashMessage::add('Password & confirm passwords should be same.'); 
   }
	//header('location:changepass.php');
}
//echo $_SESSION["StoreID"].'<pre>';print_r($Subscription);exit;
?>
        	<div class="row">
            	<?php include ROOT."admin-left.php"; ?>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 myact-userdet">
                        <h2 class="head-text">Change Password</h2>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php $message = FlashMessage::render(); if(!empty($message)){ ?>
							  <div class="alert alert-danger" role="alert" id="msg" style="text-align:center"><?php  echo $message; ?></div>
							  <?php } ?>
							 <?php  if(!empty($_SESSION['passup'])){ ?>
							   <div class="alert alert-success" role="alert" id="msg" style="text-align:center"><?php  echo $_SESSION['passup']; ?></div>
							<?php } ?>
                        <form class="form-horizontal" id="change_pass" method="post" onSubmit="return validate()" autocomplete="off">
                            <div class="form-group">
                                <label class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label">Old Password<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                  <input type="password" placeholder="Old Password" class="form-control required alpha" name='Password' id='Password'>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label">New Password<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                  <input type="password" placeholder="New Password" class="form-control required alpha" name='NPassword' id='NewPassword'>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label">Confirm Password<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                  <input type="password" placeholder="Confirm Password" class="form-control required alpha" name='CnewPassword' id='CnewPassword'>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-8 col-md-8 col-sm-8 col-sm-offset-4 col-md-offset-4">                        
                                <button class="btn btn-primary" type="submit" name="changepwd">Update Password</button>
                                <a href="dashboard.php"><button class="btn btn-danger" type="button">Cancel</button></a>
                                </div>
                    		</div>
                        </form></div>
                     </div>
                </div>
            </div>
        </div>
	</div>
</div>



<script>
function validate()
{
	var error=0;
	$("#change_pass").find("input").each(function(){
		if($.trim($(this).val())=='')
		{
			$(this).css("border","1px solid red")
			error =1;
		}
		else
			$(this).css("border","")
	})
	if(error>0)
		return false
}
<?php if((!empty($message)) || (!empty($_SESSION['passup']))){ ?>
	$(document).ready(function (){
			setTimeout(function (){ $('#msg').hide(); }, 3000);
		});
<?php unset($_SESSION['passup']); } ?>
</script>
</body>
<?php include ROOT."themes/footer.inc.php"; ?>
</html>