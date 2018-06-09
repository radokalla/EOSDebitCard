<?php
// include Config File
include_once './includes/config.inc.php';
include_once './includes/validate.php';
// Authenticate user login
auth();
$upload_dir = '';
validate_edit_store();

$images = array();
	if(is_dir($upload_dir)) {
		$images = get_files($upload_dir);
		foreach($images as $k=>$v) {
			$images[$k] = ROOT_URL.'imgs/stores/'.$store['id'].'/'.$v;
		}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title><?php echo $lang['STORE_FINDER']; ?> - <?php echo $lang['ADMIN_EDIT_STORE']; ?></title>
	<?php include 'header.php'; ?>
</head>
<body id="add_edit_body">
	<div id="wrapper">
		<div id="header">
			
			<?php include 'nav.php'; ?>
		</div>
		<div id="main">
			<h2><?php echo $lang['ADMIN_EDIT_STORE']; ?></h2>
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


			<div id="map_canvas" class="newstore_map"></div>
			<div id="ajax_msg"></div>

			<div style="display:block; clear:both">
			<form method='post' action='./stores_edit.php?id=<?php echo $store['id']; ?>' id='form_new_store' enctype="multipart/form-data">
				<fieldset>
					<legend><?php echo $lang['ADMIN_EDIT_STORE']; ?></legend>
					
						<label><?php echo $lang['ADMIN_NAME']; ?>: <span class='required'>*</span></label>
						<input type='text' name='name' id='name' value="<?php echo $fields['name']['value']; ?>" />
						
						<?php 
						$db = db_connect();
						mysql_query("SET NAMES utf8");
						$cats = $db->get_rows("SELECT categories.* FROM categories WHERE categories.id!='' ORDER BY categories.cat_name ASC");

						?>
						
						<label><?php echo $lang['SSF_CATEGORY']; ?>: <span class='required'>*</span></label>
						<select name="cat_id" class="form-select" id="cat_id" ><option value="0"><?php echo $lang['SSF_CATEGORY_NO_CAT_LISTBOX']; ?></option>
						 <?php if(!empty($cats)): ?>
							<?php foreach($cats as $k=>$v): ?>
							<option value="<?php echo $v['id']; ?>" <?php if($store['cat_id']==$v['id']){ ?>selected<?php } ?>><?php echo $v['cat_name']; ?></option>
							<?php endforeach; ?>
							<?php endif; ?>
						 </select>
					
						<label><?php echo $lang['ADMIN_ADDRESS']; ?>: <span class='required'>*</span></label>
						<input type='text' name='address' id='address' value="<?php echo $fields['address']['value']; ?>" />
						<span><?php echo $lang['ADMIN_LAT_LANG_AUTO']; ?></span>
					
						<label><?php echo $lang['ADMIN_TELEPHONE']; ?>:</label>
						<input type='text' name='telephone' id='telephone' value='<?php echo $fields['telephone']['value']; ?>' />
					
						<label><?php echo $lang['ADMINISTRATOR_EMAIL']; ?>:</label>
						<input type='text' name='email' id='email' value='<?php echo $fields['email']['value']; ?>' />
					
						<label><?php echo $lang['ADMIN_WEBSITE']; ?>:</label>
						<input type='text' name='website' id='website' value='<?php echo $fields['website']['value']; ?>' />
					
						<label><?php echo $lang['ADMIN_DESCRIPTION']; ?>:</label>
						<textarea name='description' id='description' rows="5" cols="40"><?php echo htmlentities($fields['description']['value']); ?></textarea>
					
						<label><?php echo $lang['ADMIN_STORE_IMAGE']; ?>:</label>
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
					
					
					<div class='input first'>
						<label><?php echo $lang['ADMIN_LATITUDE']; ?>:</label>
						<input type='text' name='latitude' id='latitude' value='<?php echo $fields['latitude']['value']; ?>' />
					</div>
					<div class='input second'>
						<label><?php echo $lang['ADMIN_LONGITUDE']; ?>:</label>
						<input type='text' name='longitude' id='longitude' value='<?php echo $fields['longitude']['value']; ?>' />
					</div>
				

					<div class='input buttons'>
						<button type='submit' class="btn" name='save' id='save'><?php echo $lang['ADMIN_SAVE']; ?></button>
						<button type='button' class="btn" onclick="document.location.href='stores.php'"><?php echo $lang['ADMIN_CANCEL']; ?></button>
					</div>
				</fieldset
				</div>

		</div>
	</div>
	<?php include '../themes/footer.inc.php'; ?>
</body>
</html>