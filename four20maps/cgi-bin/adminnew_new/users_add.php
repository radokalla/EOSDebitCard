<?php
// include Config File
include_once './includes/config.inc.php';
include_once './includes/validate.php';
// Authenticate user login
auth();
validate_user_add();
?>
<html>
<head>	<title><?php echo $lang['STORE_FINDER']; ?> - <?php echo $lang['ADMIN_ADD_USER']; ?></title>	<?php include 'header.php'; ?>
</head>
<body id="add">
	<div id="wrapper">
		<div id="header">
			<?php include 'nav.php'; ?>
		</div>		<div id="main">			<?php echo notification(); ?>			<?php if(isset($errors)): ?>
			<div class="alert alert-block alert-error fade in">				<ul>					<?php foreach($errors as $k=>$v): ?>					<li><?php echo $v; ?></li>					<?php endforeach; ?>				</ul>			</div>			<?php endif; ?>			<form method='post' action='' id='form_new_store' enctype="multipart/form-data">
				<fieldset>
					<legend><?php echo $lang['ADMIN_ADD_USER']; ?></legend>
					<div class='input'>
						<label><?php echo $lang['ADMIN_USERNAME']; ?>: <span class='required'>*</span></label>
						<input type='text' name='username' id='username' value='<?php echo $fields['username']['value']; ?>' />
					</div>
					<div class='input'>
						<label><?php echo $lang['ADMIN_PASSWORD']; ?>: <span class='required'>*</span></label>
						<input type='password' name='password' id='password' value='<?php echo $fields['password']['value']; ?>' />					</div>
					<div class='input'>
						<label><?php echo $lang['ADMIN_CONFIRM_PASSWORD']; ?>:</label>
						<input type='password' name='cpassword' id='cpassword' value='<?php echo $fields['cpassword']['value']; ?>' />
					</div>					<div class='input'>
						<label>E-mail:*</label>
						<input type='text' name='email' id='email' value='<?php echo $fields['email']['value']; ?>' />
					</div>					<div class='input buttons'>
						<button type='submit' class="btn" name='save' id='save'><?php echo $lang['ADMIN_SAVE']; ?></button>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
<?php include '../themes/footer.inc.php'; ?>
</body>
</html>