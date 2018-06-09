<?php
session_start();
 
// include common file
include_once "./includes/config.inc.php";

/* To create a new user in the database, add a username example called newuser, if you want to use 123456 as password all you need to do is run this php script

echo md5( '123456'.SALT );

The output should be f0971de887e9150eb74dbd4786d0f80d

Now you can login with 

Username: newuser
Password: 123456

*/

// form has been submitted
$error='';
if($_POST) {
	// check user is valid
	if(!check_user($_POST['username'],$_POST['password'])) {
		echo "2";
		die;
	} else {
		
		echo "1";
		die;
	}
	die;
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title><?php echo $lang['ADMIN_TITLE']; ?></title>
	<?php // include 'header.php'; ?>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	 <!-- Bootstrap 3.3.5 -->
	 <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
	 <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="dist/css/font-awesome.min.css">
</head>
<body id="login">
<div class="bg-fulimage-blur"></div>
	<div id="wrapper">
	

	<div class="col-lg-4 col-sm-8 col-xs-12 marginauto">
    <div class="admin-loginwps"><h1><img src="<?php echo ROOT_URL ?>images/logo-admin.png"/> <?php /*?><?php echo $lang['STORE_FINDER']; ?><?php */?></h1>
    <?php /*?><p><?php echo $lang['ADMIN_LOGIN_TAGLINE']; ?></p><?php */?>
	<div class="alert alert-warning" id="alert" style="display:none; text-align:center"></div>
    <form method="post" Onsubmit="return false;" id="form_login">
	
          
	<?php echo notification(); ?>
	<?php if(isset($error)): ?>
	<p class="alert alert-block alert-error fade in"><?php echo $error; ?></p>
	<?php endif; ?>
		  
        <div id="main">
		<fieldset>
			<legend><?php echo $lang['ADMIN_LOGIN']; ?></legend>

				<div class="form-group"><label><?php echo $lang['ADMIN_USERNAME']; ?>: <span class='required'>*</span></label>
				<input type="text" class="form-control" name='username' id='username' placeholder="Enter username" />
				</div>

				<div class="form-group"><label><?php echo $lang['ADMIN_PASSWORD']; ?>: <span class='required'>*</span></label>
				<input type="password" class="form-control" name='password' id='password' placeholder="Enter Password" /></div>

			<div class='input buttons'>
            	<button type="submit" name='btn_login' class="btn btn-primary btn-lg btn-block" Onclick="login();" id='btn_login'><?php echo $lang['ADMIN_LOGIN']; ?></button>
				 
				
			</div>
   <div class='backfront-btn'>
   	<button type="button" onclick="document.location.href='../index.php'" name='btn_login' class="btn btn-danger btn-sm" id='btn_login'>&laquo; <?php echo $lang['ADMIN_BACK_TO_FRONTEND']; ?></button>
   <a href="#" data-toggle="modal" data-target="#editreview" class="frgtpaswd-link btn btn-warning btn-sm">Forgot Password?</a>
   </div>
		</fieldset>
		</div>
	</form></div>
    </div>
	
          
	</div>
<footer class="footer">
  <div class="footer-wps">
    <div style="display:none;" class="sec-footer">
      <div class="container">
        <div class="row">
          <div class="col-md-3 ">
            <div class="socia-media padALL col-md-12"> <a class="scl-icn facebook" href="#"></a> <a class="scl-icn twitter" href="#"></a> <a class="scl-icn instram" href="#"></a> <a class="scl-icn linkdin" href="#"></a>  </div>
          </div>
          <div class="col-md-9 info-wps">
            <div style="display:none;" class="col-md-5">
              <h3>7 days a week 7am-9pm</h3>
              <p>7625 Carroll Road,<br>
                San Diego CA 92145</p>
            </div>
            <div style="display:none;" class="col-md-7">
              <div class="info-map pull-right">
                <iframe width="390" height="200" frameborder="0" style="border:0" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3350.529536001857!2d-117.15822000000003!3d32.884164999999996!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80dbf8b31a2500d9%3A0x52fb140261cc5777!2s7625+Carroll+Rd%2C+San+Diego%2C+CA+92121%2C+USA!5e0!3m2!1sen!2sin!4v1409576904750"></iframe>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
	<div class="container">
		<div class="copyright text-center">
       Copyright &copy; 2015 Four20maps. All Rights Reserved. </div>
    </div>
  </div>
  <div class="container"> 
    
    <!--<p><a href="http://superstorefinder.net">Super Store Finder</a> is designed and built by Joe Iz from <a href="http://highwardenhuntsman.blogspot.com">Highwarden Huntsman</a>.</p>

	<p>Code licensed under <a href="http://codecanyon.net/licenses/regular_extended" target="_blank">Regular & Extended License</a>. Purchasable exclusively only at <a href="http://codecanyon.net/item/super-store-finder/3630922" target="new">Codecanyon</a></p>-->
    
    <ul class="footer-links">
    </ul>
  </div>
</footer>	
</body>
	<div class="modal fade" id="editreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title LRhd" id="myModalLabel">Admin Forgot Password</h4>
				</div>
				<div class="modal-body">
					<div class="alert alert-success" id="msg" style="text-align:center; display:none"></div>
					<form OnSubmit="return false" method="post" id="AdminForgot_Form" class="xform">
						<div class="form-group">
							<label>Email ID</label>
							<input name="email" id="EmAiL" class="form-control" OnKeyup="Onkeyup();" required/>
						</div>
						<div class="form-group">
							<button type="button" class="btn btn-primary" OnClick="AdminForgotEmail();">Submit</button>
						</div>
						<div class="col-md-12" id="Loading_div">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<script>
		function AdminForgotEmail()
		{
			var email = $('#EmAiL').val();
			var formdata = $('#AdminForgot_Form').serializeArray();
			if(email!='')
			{
				$.ajax({
					url: 'AdminForgotPass.php',
					data: { 'Email':email},
					beforeSend: function()
					{
						$('#Loading_div').html("<img style='margin-top:10px;' src='../img/ajax-loader.gif'>");
					},
					complete: function()
					{
						$('#Loading_div').html("");
					},
					success: function(data)
					{
						if(data==1)
						{
							document.getElementById('msg').innerHTML = 'A Email has been sent <br> to your Email ID';
							$('#msg').show();
							setTimeout(function (){ $('#editreview').modal('hide'); }, 4000);
						}
						else if(data==2)
						{
							document.getElementById('msg').innerHTML = 'Invalid Email ID';
							$('#msg').removeClass('alert-success');
							$('#msg').addClass('alert-warning');
							$('#msg').show();
							setTimeout(function (){ $('#msg').hide(); }, 4000);
						}
						else if(data==3)
						{
							document.getElementById('msg').innerHTML = 'Illegal Access';
							$('#msg').removeClass('alert-success');
							$('#msg').addClass('alert-danger');
							$('#msg').show();
							setTimeout(function (){ $('#msg').hide(); }, 4000);
						}
					}
				});
			}
			else
			{
				$('#EmAiL').css('border-color','red');
				$('#EmAiL').focus();
			}
		}
		
		function Onkeyup()
		{
			$('#EmAiL').css('border-color','#2bb0ba');
		}
		
		function login()
		{
			var formdata = $('#form_login').serializeArray();
			$.ajax({
					type: "POST",
					url: 'index.php',
					data: formdata,
					success: function(data)
					{
						if(data==1)
						{
							window.location.href='stores.php';
						}
						else
						{
							document.getElementById('alert').innerHTML = 'Invalid Login details';
							$('#alert').show();
							setTimeout(function (){ $('#alert').hide(); }, 3000);
						}
					}
				});
		}
	</script>
</html>