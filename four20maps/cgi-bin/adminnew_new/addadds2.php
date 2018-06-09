<?php

// include Config File

include_once './includes/config.inc.php';

include_once './includes/validate.php';

// Authenticate user login




auth();

validate_cat_add();

if(isset($_POST['save']))
{
	//echo "INDUCO";exit;
	$img=$_FILES['file']['name'];
	$url=$_POST['path1'];
	$isdelete=0;
$quey="insert into adds(image,url,is_delete) values(".$img.",".$url.",".$isdelete.")";
mysql_query($quey);	
}

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>

<head>

	<title><?php echo $lang['STORE_FINDER']; ?> - <?php echo $lang['ADMIN_ADD_ADDS']; ?></title>

	<?php include 'header.php'; ?>

</head>

<body id="add_edit_body">

	<div id="wrapper">

		<div id="header">

			

			<?php include 'nav.php'; ?>

		</div>



		<div id="main">



			

			<?php echo notification(); ?>

			<?php if(isset($errors)): ?>

			<div class="alert alert-block alert-error fade in">

			<ul>

				<?php foreach($errors as $k=>$v): ?>

				<li><?php echo $v; ?></li>

				<?php endforeach; ?>

			</ul>

			</div>

			<?php endif; ?>




<style>
input[type="file"]{
	float:left; 
}
.margin-right-10{
	margin-right:10px !important;
}
</style>


			<div style="display:block; clear:both">

			<form method='post' action='' id='form_new_store' enctype="multipart/form-data" class="form-horizontal">

				<fieldset>

					<legend><?php echo $lang['ADMIN_ADD_ADDS']; ?></legend>
                    
                    <div class="control-group">
                    	<label for="exampleInputFile" class="control-label">1) Icon</label>
                        <div class="controls">
                        	<input type="file" id="file" name="file">
                            <label for="exampleInputFile" class="control-label margin-right-10" >Url </label>
                            <input type='text' id="path1" name="path1" value='<?php echo $fields['cat_name']['value'];  ?>' />
                        </div>
                        <button type='submit' class="btn" name='save' id='save'><?php //echo $lang['ADMIN_SAVE']; ?>Update</button>
                    </div>
                    <!--<div class="control-group">
                    	<label for="exampleInputFile" class="control-label">2) Icon</label>
                        <div class="controls">
                        	<input type="file" id="add2" name="id="add2"">
                            <label for="exampleInputFile" class="control-label margin-right-10">Url </label>
                            <input type='text' name='cat_name' id='cat_name' value='<?php //echo $fields['cat_name']['value']; ?>' />
                        </div>
                    </div>
                    <div class="control-group">
                    	<label for="exampleInputFile" class="control-label">3) Icon</label>
                        <div class="controls">
                        	<input type="file" id="exampleInputFile">
                            <label for="exampleInputFile" class="control-label margin-right-10">Url </label>
                            <input type='text' name='cat_name' id='cat_name' value='<?php //echo $fields['cat_name']['value']; ?>' />
                        </div>
                    </div>
                    <div class="control-group">
                    	<label for="exampleInputFile" class="control-label">4) Icon</label>
                        <div class="controls">
                        	<input type="file" id="exampleInputFile">
                            <label for="exampleInputFile" class="control-label margin-right-10">Url </label>
                            <input type='text' name='cat_name' id='cat_name' value='<?php // echo $fields['cat_name']['value']; ?>' />
                        </div>
                    </div>
                    <div class="control-group">
                    	<label for="exampleInputFile" class="control-label">5) Icon</label>
                        <div class="controls">
                        	<input type="file" id="exampleInputFile">
                            <label for="exampleInputFile" class="control-label margin-right-10">Url </label>
                            <input type='text' name='cat_name' id='cat_name' value='<?php //echo $fields['cat_name']['value']; ?>' />
                        </div>
                    </div>
                    <div class="control-group">
                    	<label for="exampleInputFile" class="control-label">6) Icon</label>
                        <div class="controls">
                        	<input type="file" id="exampleInputFile">
                            <label for="exampleInputFile" class="control-label margin-right-10">Url </label>
                            <input type='text' name='cat_name' id='cat_name' value='<?php //echo $fields['cat_name']['value']; ?>' />
                        </div>
                    </div>-->
                    
                    
                    
                    

						

					

					

						<?php /*?><span><?php echo $lang['ADMIN_THUMB_AUTO']; ?></span><?php */?>

					

					

					<?php if(!empty($images)): ?>

					<div class="input">

						<?php foreach($images as $k=>$v): ?>

						<div class="image">

							<img src="<?php echo $v; ?>" alt="Image" />

							<button type="submit" name="delete_image[<?php echo basename($v); ?>]" id="delete_image" class="btn btn-danger" value="<?php echo basename($v); ?>"><?php echo $lang['ADMIN_DELETE']; ?></button>

						</div>

						<?php endforeach; ?>

					</div>

					<?php endif; ?>



					<p></p>

					

					<div class='input buttons'>

						<button type='submit' class="btn" name='save' id='save'><?php echo $lang['ADMIN_SAVE']; ?></button>

						<!--<button type='button' class="btn" onclick="document.location.href='categories.php'"><?php //echo $lang['ADMIN_CANCEL']; ?></button>-->

					</div>

				</fieldset>

			</form>

			</div>

			

		</div>

	</div>

<?php include '../themes/footer.inc.php'; ?>

</body>

</html>