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
		$max_upload_width = 600;
		$max_upload_height = 600;
		  
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
	  <?php include("header.php"); ?>
      <!-- Left side column. contains the logo and sidebar -->
      <?php include("sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Add Adds</h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li class="active">Add Adds</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
            	<div class="box">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['ADMIN_ADD_ADDS']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">

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
			<form class="xform" name="adds" <?php ((isset($fetchdata['aid']))?$fetchdata['aid']:''); if (!empty ($fetchdata['aid'])){ ?> action="<?php echo ROOT_URL; ?>adminadd_update.php" <?php } else { echo "id='addsrr' ";}?> method="POST" enctype="multipart/form-data">
            <input type="hidden" value="" name="upid">

			<div class="row">
            <h5 style="color:red"><?php echo $msg; ?></h5>
            	<div class="col-lg-6 col-sm-12">

						<div class="form-group">
                            <label>Name</label>
							<input type="hidden" value="<?php echo ((isset($fetchdata['aid']))?$fetchdata['aid']:''); ?>" name="aid"/>
                            <input type="text" placeholder="Name" value="<?php echo ((isset($fetchdata['Name']))?$fetchdata['Name']:''); ?>" name="namer" id="namer" class="required form-control" required/>
                           
                           
                        </div>
						
						<div class="form-group">
                             <label>Email Id</label>
                              <input type="text" placeholder="Email Id" value="<?php echo ((isset($fetchdata['Email']))?$fetchdata['Email']:''); ?>" name="emailr" id="emailr" class="required form-control" required/>      
                        </div>

						<div class="form-group">
                            <label>phone</label>
                              <input type="text" placeholder="phone" value="<?php echo ((isset($fetchdata['PhoneNumber']))?$fetchdata['PhoneNumber']:''); ?>" name="phone" id="phone" class="required form-control" required/>                          
                        </div>

						<div class="form-group">
                           <label>Url</label>
                              <input type="text" placeholder="Url" value="<?php echo ((isset($fetchdata['url']))?$fetchdata['url']:''); ?>" name="url" id="url" class="required form-control" required/>      
                        </div>
                       
                        <div class="form-group">
                            <input type="file"  name="file" id="file" Onchange="fileup();"/>
							  <span style="color:red">Images with higher resolutions will be resized to 600X600 size automatically.</span> 
							  <br><br>
							  <?php echo ((isset($fetchdata['image']))? "<img src='uploads/".$fetchdata['image']."'>":''); ?>
                        </div>
					
					</div>
                    <div class="col-lg-12 col-sm-12">
                        <div class='input buttons'>
                          <?php if (!empty ($fetchdata['aid'])){ ?>         
                                <input class="btn btn-primary" type="submit" value="Submit" />
								<a href="addslist.php" class="btn btn-danger">Cancel</a>
								<?php } else { ?>
								 <button name="dosubmit" class="btn btn-primary" type="submit" id="submit">Submit</button>
								 <a href="addslist.php" class="btn btn-danger">Cancel</a>
								<?php } ?>
                        </div>
                    </div>
				</div>
			</form>

                </div><!-- /.box-body -->
              </div>
            </div>

          </div><!-- /.row -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
	  <script>
		function fileup()
		{
			var image = $('#file').val()
			if(image!='')
			{
				var valid_extensions = /(\.jpg|\.jpeg|\.gif|\.png)$/i;   
				if(valid_extensions.test(image))
				{
					
				}
				else
				{
					alert('Invalid File type');
					$('#file').val('');
				}
				//if(ext == filename)
			}
		}
	  </script>
            <?php include("footer.php"); ?>
