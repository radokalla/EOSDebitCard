<?php

// include Config File
ini_set("memory_limit", "200000000");
include_once './includes/config.inc.php';

//include_once './includes/validate.php';

// Authenticate user login


//echo "<pre>";
//print_r($_POST); exit;

//auth();


//echo "<pre>";print_r($_POST);

//echo $_POST['path1']; exit;

$db = db_connect();


if(isset($_POST['dosubmit']))
{
$name=$_POST['namer'];
$email=$_POST['emailr'];
$phone=$_POST['phone'];
$url=$_POST['url'];
$filer=$_FILES['file']['name'];
$resul=mysql_query("CALL map_proc_ManageAdds('{$filer}','{$url}','{$name}','{$email}','{$phone}')") or die(mysql_error());
$rj=mysql_fetch_assoc($resul);
$rj['MsgOut']; 
if($rj['MsgOut']!=0) 
{

// upload the file
	
	// file needs to be jpg,gif,bmp,x-png and 4 MB max
	if (($_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/pjpeg" || $_FILES["file"]["type"] == "image/gif" || $_FILES["file"]["type"] == "image/x-png") && ($_FILES["file"]["size"] < 4000000))
	{
		
  
		// some settings
		$max_upload_width = 160;
		$max_upload_height = 240;
		  
		// if user chosed properly then scale down the image according to user preferances
		 /*if(isset($_REQUEST['max_width_box']) and $_REQUEST['max_width_box']!='' and $_REQUEST['max_width_box']<=$max_upload_width){
			$max_upload_width = $_REQUEST['max_width_box'];
		}    
		if(isset($_REQUEST['max_height_box']) and $_REQUEST['max_height_box']!='' and $_REQUEST['max_height_box']<=$max_upload_height){
			$max_upload_height = $_REQUEST['max_height_box'];
		}	*/ 

		
		// if uploaded image was JPG/JPEG
		if($_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/pjpeg"){	
			$image_source = imagecreatefromjpeg($_FILES["file"]["tmp_name"]);
		}		
		// if uploaded image was GIF
		if($_FILES["file"]["type"] == "image/gif"){	
			$image_source = imagecreatefromgif($_FILES["file"]["tmp_name"]);
		}	
		// BMP doesn't seem to be supported so remove it form above image type test (reject bmps)	
		// if uploaded image was BMP
		if($_FILES["file"]["type"] == "image/bmp"){	
			$image_source = imagecreatefromwbmp($_FILES["file"]["tmp_name"]);
		}			
		// if uploaded image was PNG
		if($_FILES["file"]["type"] == "image/x-png"){
			$image_source = imagecreatefrompng($_FILES["file"]["tmp_name"]);
		}
		

		$remote_file = getcwd().'/uploads/'.$_FILES["file"]["name"];
		imagejpeg($image_source,$remote_file,100);
		chmod($remote_file,0644);
/*	
			$img1  = new Image(array('filename'=>$remote_file));
			$img1->resize(24,20);
			$img1->save(getcwd().'/uploads/'.$_FILES["file"]["name"]);
*/
		// get width and height of original image
		list($image_width, $image_height) = getimagesize($remote_file);
	
		if($image_width>$max_upload_width || $image_height >$max_upload_height){
			$proportions = $image_width/$image_height;
			
			if($image_width>$image_height){
				$new_width = $max_upload_width;
				$new_height = round($max_upload_width/$proportions);
			}		
			else{
				$new_height = $max_upload_height;
				$new_width = round($max_upload_height*$proportions);
			}		
			
			
			$new_image = imagecreatetruecolor($new_width , $new_height);
			$image_source = imagecreatefromjpeg($remote_file);
			
			imagecopyresampled($new_image, $image_source, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);
			imagejpeg($new_image,$remote_file,100);
			
			imagedestroy($new_image);
		}
		
		imagedestroy($image_source);
		
		$msg="Successfully Submitted";
	}
	else{
		$msg = "Invalid file";
	}
}
else{
$msg = "Already Exist";	
	
}

}

if(isset($_GET['id']))
{
	$getadds="select * from adds where aid=".$_GET['id'];
	$qry_exc=mysql_query($getadds);
	if(!empty($qry_exc))
	{
		$fetchdata=mysql_fetch_assoc($qry_exc);
	}
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
    

			
				<fieldset>

					<legend><?php echo $lang['ADMIN_ADD_ADDS']; ?></legend>
                    <h5 style="color:red"><?php echo $msg; ?></h5>
                    <form class="xform" name="adds" <?php ((isset($fetchdata['aid']))?$fetchdata['aid']:''); if (!empty ($fetchdata['aid'])){ ?> action="<?php echo ROOT_URL; ?>adminadd_update.php" <?php } else { echo "id='addsrr' ";}?> method="post" enctype="multipart/form-data">
					<input type="hidden" value="" name="upid">
                    <section>
                        <div class="row">
                          <div class="col-md-12">
                            <label class="input"> <i class="icon-prepend fa-user"></i>
							<input type="hidden" value="<?php echo ((isset($fetchdata['aid']))?$fetchdata['aid']:''); ?>" name="aid"/>
                            <input type="text" placeholder="Name" value="<?php echo ((isset($fetchdata['Name']))?$fetchdata['Name']:''); ?>" name="namer" id="namer" class="required"/>
                            <!--<label for="firstname" generated="true" class="error" style="display: inline-block; color:red;">Please enter your firstname</label>-->
                            </label>
                          </div>
         
                        </div>
                    </section>
                	<section>
                        <div class="row">
                          <div class="col-md-12">
                            <label class="input"> <i class="icon-prepend fa-envelope"></i>
                              <input type="text" placeholder="Email Id" value="<?php echo ((isset($fetchdata['Email']))?$fetchdata['Email']:''); ?>" name="emailr" id="emailr" class="required"/>
                             <!-- <label for="email" generated="true" class="error" style="display: inline-block; color:red;">Please enter a valid email address</label>-->
                            </label>
                          </div>
                        </div>
              		</section>
					
					    <section>
                        <div class="row">
                          <div class="col-md-12">
                            <label class="input"> <i class="icon-prepend fa-envelope"></i>
                              <input type="text" placeholder="phone" value="<?php echo ((isset($fetchdata['PhoneNumber']))?$fetchdata['PhoneNumber']:''); ?>" name="phone" id="phone" class="required"/>
                             <!-- <label for="email" generated="true" class="error" style="display: inline-block; color:red;">Please enter a valid phone address</label>-->
                            </label>
                          </div>
                        </div>
              		</section>
				       <section>
                        <div class="row">
                          <div class="col-md-12">
                            <label class="input"> <i class="icon-prepend fa-envelope"></i>
                              <input type="text" placeholder="Url" value="<?php echo ((isset($fetchdata['url']))?$fetchdata['url']:''); ?>" name="url" id="url" class="required"/>
                              <!--<label for="email" generated="true" class="error" style="display: inline-block; color:red;">Please enter a valid url address</label>-->
                            </label>
                          </div>
                        </div>
              		</section>
					</section>
					
				       <section>
                        <div class="row">
                       
                          
                              <input type="file"  name="file" id="file" />
							  <br><br>
							  <span style="color:red">Images with higher resolutions will be resized to 160x240 size automatically.</span> 
							  <br><br>
							  <?php echo ((isset($fetchdata['image']))? "<img src='uploads/".$fetchdata['image']."'>":''); ?>
                             <!-- <label for="email" generated="true" class="error" style="display: inline-block; color:red;">Please enter a valid file address</label>-->
                         
          
                        </div>
              		</section>
    

                   
                        <div class="row">
                          <div class="col-md-12">
                                <?php if (!empty ($fetchdata['aid'])){ ?>         
                                <button name="dosubmit" class="btn btn-success" type="submit">Submit</button>
								<?php } else { ?>
								 <button name="dosubmit" class="btn btn-success" type="submit" id="submit">Submit</button>
								<?php } ?>
                                                     <!--<a href="register.php" class="button button-secondary">Register</a>-->
                          </div>
                        </div>
                    


		</form>
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

					
					


				</fieldset>

			</div>

			

		</div>

	</div>

<?php include '../themes/footer.inc.php'; ?>

</body>

</html>