<?php

if(isset($_REQUEST['logout'])){
	unset($_SESSION['_newsletter_loggedin']);
	$newsletter->logout();
	header("Location: index.php");
	exit;
}

$login_status = (isset($_SESSION['_newsletter_loggedin']) && $_SESSION['_newsletter_loggedin']);

if(isset($_REQUEST['username']) && isset($_REQUEST['password'])){
	$login_status = $newsletter->login($db,$_REQUEST['username'],$_REQUEST['password']);
}

if($login_status){
	// support for multiple logins at one time.
	$_SESSION['_newsletter_loggedin'] = $login_status;
}


if(!$login_status){
//print ("ERROR........");
	?>
	<html>
	<head>
		<title>Login</title>
		<link rel="stylesheet" href="layout/css/styles.css" type="text/css" />
	</head>

	<body>
	<div id="wrapper" style="margin:40px auto; width:300px;">
	<h1>Newsletter Dashboard (<?php echo _NEWSLETTER_VERSION;?>)</h1>
	<form action="" method="post">
		<table cellpadding="5">
			<tr>
				<td>Username:</td>
				<td><input type="text" name="username" value="<?php echo (_DEMO_MODE)?$newsletter->settings['username']:'';?>"></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><input type="password" name="password" value="<?php echo (_DEMO_MODE)?$newsletter->settings['password']:'';?>"></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" name="login_button" value="Login to Dashboard"></td>
			</tr>
			<tr>
				<td></td>
				<td>
				* Firefox recommended.
				</td>
			</tr>
		</table>
		
	</form>
	
		<?php if(_DEMO_MODE){ ?>
		
		<div style="padding:20px;">
			Welcome to the Newsletter Demo. Please login above. <br><br>
			For more information about this newsletter system, please <a href="http://codecanyon.net/item/newsletter-system/52667?ref=dtbaker">click here</a>.
		</div>
		
		<?php } ?>
	</div>
	</body>
	</html>
	<?php 
	exit;
}

