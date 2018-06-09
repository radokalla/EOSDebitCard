<?php error_reporting(0); 
unset($_SESSION['language']);
session_start();
include 'admin/includes/class.database.php'; 
include 'admin/includes/library.php'; 

define('SALT', 'Ku23ao+(f%bxh|k?4ee4<+?%B$-<2_#%IpwU4]+o2l+xmXGHL0_h}+1m$QnL.pIu');


$install_step = 1;

if(isset($_POST['admin_email'])){

$install_step = 2;

if($_POST['admin_password']=="" || $_POST['admin_password2']=="" || $_POST['admin_email']==""){
   $error = 'Please fill up admin password and email below';
} else {

$pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';





	if($_POST['admin_password']!=$_POST['admin_password2']){
		$error = 'Password does not match.';
	} else if (preg_match($pattern, $_POST['admin_email']) !== 1) {
		$error = 'Please enter a valid email address.';
	} else {
	
	
	// frontend config
	$arr_config = array();
	$handle = fopen("includes/config.inc.php", "r");

		if ($handle) {
			while (($line = fgets($handle)) !== false) {
			
			
				if (strpos($line,"define('DEFAULT_LANGUAGE'") !== false) {
				   $line = "define('DEFAULT_LANGUAGE', '".$_POST['language_set']."');\n";
				} else if (strpos($line,"define('DEFAULT_DISTANCE'") !== false) {
				    $line = "define('DEFAULT_DISTANCE','".$_POST['distance_set']."');\n";

				} else if (strpos($line,"define('ADMINISTRATOR_EMAIL'") !== false) {
				    $line = "define('ADMINISTRATOR_EMAIL','".$_POST['admin_email']."');\n";
					$setpwemail = "UPDATE users SET password='".md5($_POST['admin_password'].SALT)."' WHERE username='admin'";
					// stop on db fail
					$connect = mysql_connect($_SESSION['hostname'],$_SESSION['username'],$_SESSION['password']) or $error = 'Unable to connect to database hostname, kindly check your database details and retry.';
					$db = mysql_select_db($_SESSION['dbname']) or $error = 'Unable to connect to database '.$_POST['dbname'];

					mysql_query($setpwemail) or die(mysql_error());
					
				} 
				

				$arr_config[] = $line;
			}

			fclose($handle);
		} else {
			// error opening the file.
			 $error = 'Kindly set includes/config.inc.php and admin/includes/config.inc.php to writable';
		} 
		

		$conf_str = "";	
		for($i=0;$i<sizeof($arr_config);$i++){
		$conf_str.=$arr_config[$i];
		}
		
		
		

		
		$fp = fopen('includes/config.inc.php', 'w');
		fwrite($fp, $conf_str);
		fclose($fp);
		
		//admin config
		$arr_config = array();
		$handle = fopen("admin/includes/config.inc.php", "r");

		if ($handle) {
			while (($line = fgets($handle)) !== false) {
			
			
				if (strpos($line,"define('DEFAULT_LANGUAGE'") !== false) {
				   $line = "define('DEFAULT_LANGUAGE', '".$_POST['language_set']."');\n";
				} else if (strpos($line,"define('ADMINISTRATOR_EMAIL'") !== false) {
				    $line = "define('ADMINISTRATOR_EMAIL','".$_POST['admin_email']."');\n";
					
					
				} else if (strpos($line,'$default_language =') !== false) {
				$line = '$default_language = "'.$_POST['language_set'].'";'."\n";
				$_SESSION['language'] = $_POST['language_set'];
				}
				

				$arr_config[] = $line;
			}

			fclose($handle);
		} else {
			// error opening the file.
			 $error = 'Kindly set includes/config.inc.php and admin/includes/config.inc.php to writable';
		} 
		

		$conf_str = "";	
		for($i=0;$i<sizeof($arr_config);$i++){
		$conf_str.=$arr_config[$i];
		}
		
		$fp = fopen('admin/includes/config.inc.php', 'w');
		fwrite($fp, $conf_str);
		fclose($fp);
		
		$_SESSION['notification'] = array('type'=>'good','msg'=>'Installation completed successfully.');
		$install_step = 3;
	}

}



}

if(isset($_POST['hostname'])){

$_SESSION['hostname'] = $_POST['hostname'];
$_SESSION['dbname'] = $_POST['dbname'];
$_SESSION['username'] =  $_POST['username'];
$_SESSION['password'] =  $_POST['password'];

	if($_POST['hostname']=="" || $_POST['dbname']=="" || $_POST['username']==""){
		$error = 'Please fill up all your database info below';
	} if (!is_writable('includes/config.inc.php') || !is_writable('admin/includes/config.inc.php')) {
		$error = 'Please ensure that includes/config.inc.php and admin/includes/config.inc.php is writable';
	} else {
	


	// stop on db fail
	$connect = mysql_connect($_POST['hostname'],$_POST['username'],$_POST['password']) or $error = 'Unable to connect to database hostname, kindly check your database details and retry.';
	
	if(!empty($connect)){
		$db = mysql_select_db($_POST['dbname']) or $error = 'Unable to connect to database '.$_POST['dbname'];
		
		if(!empty($db)){
			mysql_query("CREATE TABLE IF NOT EXISTS `stores` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) character set utf8 collate utf8_bin NOT NULL,
  `address` varchar(255) character set utf8 collate utf8_bin NOT NULL,
  `telephone` varchar(25) NOT NULL default '',
  `fax` varchar(25) NOT NULL default '',
  `mobile` varchar(25) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `website` varchar(255) NOT NULL default '',
  `description` text character set utf8 collate utf8_bin NOT NULL,
  `approved` tinyint(1) NOT NULL default '0',
  `latitude` float NOT NULL default '0',
  `longitude` float NOT NULL default '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `status` tinyint(1) NOT NULL default '1',
  `cat_id` int(9) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
);
");

mysql_query("CREATE TABLE `categories` (
`id` int(11) NOT NULL auto_increment,
`cat_name` varchar(100) character set utf8 collate utf8_bin default NULL,
`cat_icon` varchar(255) default NULL,
`cat_parent_id` int(11) default NULL,
`cat_free_flag` int(1) default NULL,
PRIMARY KEY (id)
);
");


mysql_query("CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL, 
  `firstname` varchar(255) NOT NULL, 
  `lastname` varchar(255) NOT NULL, 
  `facebook_id` varchar(255) NOT NULL, 
  `address` varchar(255) NOT NULL, 
  `email` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
);");


mysql_query("delete from `users` where `username`='admin';");

mysql_query("insert  into `users`(`username`,`password`) values ('admin','e64a4f78be2256a38de080744dd5b117');");
		$arr_config = array();
		// frontend config
		$handle = fopen("includes/config.inc.php", "r");
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
			
			
				if (strpos($line,"define('ROOT'") !== false) {
				   $line = "define('ROOT', '');\n";
				} else if (strpos($line,"define('ROOT_URL'") !== false) {
				   $line = "define('ROOT_URL', 'http://".$_SERVER['HTTP_HOST']."".dirname($_SERVER["REQUEST_URI"])."/');\n";
				} else if (strpos($line,"define('HOSTNAME'") !== false) {
				    $line = "define('HOSTNAME','".$_POST['hostname']."');\n";  
				} else if (strpos($line,"define('DB_USERNAME'") !== false) {
				    $line = "define('DB_USERNAME','".$_POST['username']."');\n";
				} else if (strpos($line,"define('DB_PASSWORD'") !== false) {
				    $line = "define('DB_PASSWORD','".$_POST['password']."');\n";
				} else if (strpos($line,"define('DB_NAME'") !== false) {
					$line = "define('DB_NAME','".$_POST['dbname']."');\n";
				}
				
				
				
				
					//print_r($line);
				$arr_config[] = $line;
			}

			fclose($handle);
		} else {
			// error opening the file.
			 $error = 'Kindly set includes/config.inc.php and admin/includes/config.inc.php to writable';
		}

	$conf_str = "";	
	for($i=0;$i<sizeof($arr_config);$i++){
	$conf_str.=$arr_config[$i];
	}
	
	$fp = fopen('includes/config.inc.php', 'w');
	fwrite($fp, $conf_str);
	fclose($fp);
	
	//admin config
	$arr_config = array();
	$handle = fopen("admin/includes/config.inc.php", "r");
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
			
			
				if (strpos($line,"define('ROOT'") !== false) {
				  $line = "define('ROOT', '');\n";
				} else if (strpos($line,"define('ROOT_URL'") !== false) {
				   $line = "define('ROOT_URL', 'http://".$_SERVER['HTTP_HOST']."".dirname($_SERVER["REQUEST_URI"])."/admin/');\n";
				} else if (strpos($line,"define('HOSTNAME'") !== false) {
				    $line = "define('HOSTNAME','".$_POST['hostname']."');\n";  
				} else if (strpos($line,"define('DB_USERNAME'") !== false) {
				    $line = "define('DB_USERNAME','".$_POST['username']."');\n";
				} else if (strpos($line,"define('DB_PASSWORD'") !== false) {
				    $line = "define('DB_PASSWORD','".$_POST['password']."');\n";
				} else if (strpos($line,"define('DB_NAME'") !== false) {
					$line = "define('DB_NAME','".$_POST['dbname']."');\n";
				}
				
				
				
				
					//print_r($line);
				$arr_config[] = $line;
			}

			fclose($handle);
		} else {
			// error opening the file.
			 $error = 'Kindly set includes/config.inc.php and admin/includes/config.inc.php to writable';
		}

	$conf_str = "";	
	for($i=0;$i<sizeof($arr_config);$i++){
	$conf_str.=$arr_config[$i];
	}
	
	$fp = fopen('admin/includes/config.inc.php', 'w');
	fwrite($fp, $conf_str);
	fclose($fp);



$_SESSION['notification'] = array('type'=>'good','msg'=>'Database successfully created.');
$install_step = 2;
		}
	}
	 
	}


}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>Super Store Finder - Installation</title>
	<link rel="stylesheet" type="text/css" href="admin/css/style.css" media="all" />
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css" media="all" />
	<link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.css" media="all" />
	
</head>
<body id="login">
	<div id="wrapper">
	
	
	
	<form method="post" action="" id="form_install">
	<div class="hero-unit">
              <h1>Super Store Finder - Installation</h1>
              <p>Let's get started, follow the simple steps below.</p>

            </div>
          
	
		 
        <div id="main">
	<?php echo notification(); ?>
	<?php if(isset($error)): ?>
	<p class="alert alert-block alert-error fade in"><?php echo $error; ?></p>
	<?php endif; ?>
	<?php if($install_step==1){ ?>
		<fieldset>
			<legend>Installation Step 1/2 (watch <a href="https://www.youtube.com/watch?v=ut54uUZp63M" target="new">video tutorial</a>)</legend>
			<label>1. Create your database via cPanel </span></label>
			<label>2. Change config files mode to writeable</span></label>
			<label>3. Fill up database info below and click Install</span></label>
			<br>
				<label>Database Hostname: <span class='required'>*</span></label>
				<input type="text" name='hostname'id=' hostname' value='<?php if(isset($_POST['hostname'])){ echo $_POST['hostname']; } ?>' />

				<label>Database Name: <span class='required'>*</span></label>
				<input type="text" name='dbname'id='dbname' value='<?php if(isset($_POST['dbname'])){ echo $_POST['dbname']; } ?>' />
				
				<label>Database Username: <span class='required'>*</span></label>
				<input type="text" name='username'id='username' value='<?php if(isset($_POST['username'])){ echo $_POST['username']; } ?>' />
				
				<label>Database Password: <span class='required'>*</span></label>
				<input type="password" name='password' id='password' value='<?php if(isset($_POST['password'])){ echo $_POST['password']; } ?>' />

			<div class='input buttons'>
				<button type="submit" name='btn_login' class="btn btn-primary" id='btn_login'>Install</button>
			</div>
		</fieldset>
		
		<?php } else if($install_step==2){ ?>
		<fieldset>
			<legend>Installation Step 2 / 2 (watch <a href="https://www.youtube.com/watch?v=ut54uUZp63M" target="new">video tutorial</a>)</legend>
			<label>1. Enter Your Admin Password and Email</span></label>
			
						<br>
						
						<label>Username:  <strong>admin</strong></label>
						<br>
				<label>Password: <span class='required'>*</span></label>
				<input type="password" name='admin_password' id='admin_password' value='<?php if(isset($_POST['admin_password'])){ echo $_POST['admin_password']; } ?>' />

				<label>Confirm Password: <span class='required'>*</span></label>
				<input type="password" name='admin_password2' id='admin_password2' value='<?php if(isset($_POST['admin_password2'])){ echo $_POST['admin_password2']; } ?>' />
				
				<label>Email: <span class='required'>*</span></label>
				<input type="text" name='admin_email' id='admin_email' value='<?php if(isset($_POST['admin_email'])){ echo $_POST['admin_email']; } ?>' />


				
				<br><br>
			
				
				<label>2. Language Selection</span></label>

			<br>
				<select name="language_set">
<option value="en_US" <?php if(!isset($_POST['language_set']) || $_POST['language_set']=="en_US") { ?>selected<?php } ?>>English</option>
<option value="sv_SE" <?php if(isset($_POST['language_set']) && $_POST['language_set']=="sv_SE") { ?>selected<?php } ?>>Swedish</option>
<option value="es_ES" <?php if(isset($_POST['language_set']) && $_POST['language_set']=="es_ES") { ?>selected<?php } ?>>Spanish</option>
<option value="fr_FR" <?php if(isset($_POST['language_set']) && $_POST['language_set']=="fr_FR") { ?>selected<?php } ?>>French</option>
<option value="de_DE" <?php if(isset($_POST['language_set']) && $_POST['language_set']=="de_DE") { ?>selected<?php } ?>>German</option>
<option value="cn_CN" <?php if(isset($_POST['language_set']) && $_POST['language_set']=="cn_CN") { ?>selected<?php } ?>>Chinese</option>
<option value="kr_KR" <?php if(isset($_POST['language_set']) && $_POST['language_set']=="kr_KR") { ?>selected<?php } ?>>Korean</option>
<option value="jp_JP" <?php if(isset($_POST['language_set']) && $_POST['language_set']=="jp_JP") { ?>selected<?php } ?>>Japanese</option>
<option value="ar_AR" <?php if(isset($_POST['language_set']) && $_POST['language_set']=="ar_AR") { ?>selected<?php } ?>>Arabic</option>
</select>
				<br />
				<br />
				
				<label>3. Default Distance</span></label>

			<br>
				<select name="distance_set">
<option value="mi" <?php if(!isset($_POST['distance_set']) || $_POST['distance_set']=="mi") { ?>selected<?php } ?>>Miles (mi)</option>
<option value="km" <?php if(isset($_POST['distance_set']) && $_POST['distance_set']=="km") { ?>selected<?php } ?>>Kilometers (km)</option>
</select>
				<br />
				
				
			<div class='input buttons'>
				<button type="submit" name='btn_login' class="btn btn-primary" id='btn_login'>Complete Installation</button>
			</div>
		</fieldset>
		
		<?php } else if($install_step==3){ ?>
		
		<fieldset>
			<legend>Installation Completed</legend>
			<label><button type="button" onclick="document.location.href='index.php'" name='btn_login' class="btn btn-primary" id='btn_login'>View Your Store Finder</button>
			or <button type="button" onclick="document.location.href='admin/index.php'" name='btn_login' class="btn btn-primary" id='btn_login'>Administrator Area</button></label>
			
			</fieldset>
		<?php } ?>
		</div>
	</form>
     
	</div>
<?php include 'themes/footer.inc.php'; ?>	
</body>
</html>