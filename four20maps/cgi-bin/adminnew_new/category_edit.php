<?php
// include Config File
include_once './includes/config.inc.php';
include_once './includes/validate.php';
// Authenticate user login
auth();
$upload_dir = '';
validate_edit_cats();
$images = array();
	if(is_dir($upload_dir)) {
		$images = get_files($upload_dir);
		foreach($images as $k=>$v) {
			$images[$k] = ROOT_URL.'imgs/categories/'.$cats['id'].'/'.$v;
		}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title><?php echo $lang['STORE_FINDER']; ?> - <?php echo $lang['SSF_EDIT_CATEGORY']; ?></title>
	<?php include 'header.php'; ?>
</head>
<body id="add_edit_body">
	<div id="wrapper">
		<div id="header">
			
			<?php include 'nav.php'; ?>
		</div>
		<div id="main">

			<?php echo notification(); ?>
			<?php if(isset($errors) && !empty($errors)): ?>
			<div class="alert alert-block alert-error fade in">
			<ul>
				<?php foreach($errors as $k=>$v): ?>
				<li><?php echo $v; ?></li>
				<?php endforeach; ?>
			</ul>
			</div>
			<?php endif; ?>

			<div style="display:block; clear:both">
			<form method='post' action='./category_edit.php?id=<?php echo $cats['id']; ?>' id='form_new_store' enctype="multipart/form-data">
				<fieldset>
					<legend><?php echo $lang['SSF_EDIT_CATEGORY']; ?></legend>
					
						<label><?php echo $lang['SSF_CATEGORY_NAME']; ?>: <span class='required'>*</span></label>
						<input type='text' name='cat_name' id='cat_name' value="<?php echo $fields['cat_name']['value']; ?>" />
					
						<label>Map Icon:</label>
						<input type="file" name="map_icon" id="map_icon" />
						<?if($cats['cat_icon']!=''){?>
							<br><span><img src="<?php echo $cats['cat_icon']; ?>"></span>
						<?}?>
						<label><?php echo $lang['SSF_CATEGORY_ICON']; ?>:</label>
						<input type="file" name="file" id="file" <?php if(!empty($images)) {echo 'disabled="disabled"';} ?>/>
						<span><?php echo $lang['ADMIN_THUMB_AUTO']; ?></span>
					
						
						
					
					<?php if(!empty($images)): ?>
					<div class="input">
						<?php foreach($images as $k=>$v): ?>
						<div class="image">
							<img src="<?php echo $v; ?>" alt="Image" />
							<button type="submit" class="btn btn-danger" name="delete_image[<?php echo basename($v); ?>]" id="delete_image" value="<?php echo basename($v); ?>">Delete</button>
						</div>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
					
					<p></p>
					
					<div class='input buttons'>
						<button type='submit' class="btn" name='save' id='save'><?php echo $lang['ADMIN_SAVE']; ?></button>
						<button type='button' class="btn" onclick="document.location.href='categories.php'"><?php echo $lang['ADMIN_CANCEL']; ?></button>
					</div>
				</fieldset
				</div>

		</div>
	</div>
	<?php include '../themes/footer.inc.php'; ?>
</body>
</html>