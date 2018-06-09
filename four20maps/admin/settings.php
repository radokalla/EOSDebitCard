<?php  
$heading='store';
// include Config File
include_once './includes/config.inc.php';
include_once './includes/validate.php';
// Authenticate user login
auth();
 
$db = db_connect();
if(isset($_POST['fee']))
{
	
	mysql_query("UPDATE `settings` SET  `fee`='".$_POST['fee']."',`term`='".$_POST['terms']."' WHERE id=1");
	 
}
 	$settings  ="";
	$sql = "select * from settings";	 
	$settingsData = mysql_query($sql);
	while($settingsArray = mysql_fetch_assoc($settingsData))
	{
		$settings  = $settingsArray;
	}
 
?>
	  <?php include("header.php"); ?>
      <!-- Left side column. contains the logo and sidebar -->
      <?php include("sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Settings</h1>
          <ol class="breadcrumb">
            <li><a href="settings.php"><i class="fa fa-dashboard"></i>Settings</a></li> 
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
            	<div class="box">
                <div class="box-header">
                  <?php /*?><h3 class="box-title"><?php echo $lang['ADMIN_ADD_SUBSCRIPTION']; ?></h3><?php */?>
                </div><!-- /.box-header -->
                <div class="box-body">

			<div class="alert alert-success" id="msg" style="display:none; text-align:center"></div>
			<form method="post" action='' id="SubsForm" enctype="multipart/form-data">
           <input type="hidden" name="SubId" id="SubId" value="<?=$_GET["id"]?>" />
			<div class="row">
            	<div class="col-lg-6 col-sm-12">

					<div class="form-group">
							<label class="input">Fee</label>
							 <input type='text' class="form-control"   name="fee"   class="form-control" id="fee" required aria-required="true" value="<?=(isset($settings["fee"])) ?$settings["fee"] : '' ;?>"/>
						</div>
						<div class="form-group">
                            <label>Terms: <span class='required'>*</span></label>
     <textarea name="terms"  class="form-control" id="terms" required><?=(isset($settings["term"])) ? $settings["term"] : '' ;?></textarea>
                             
                        </div>

						 
                    </div>
                    <div class="col-lg-12 col-sm-12">
                        <div class='input buttons'>
                            <input type="submit" class="btn btn-primary" value="Submit">
							<input type="hidden" value="3" name="type" />
							<button type="reset" class="btn btn-danger">Reset</button>
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
            <?php include("footer.php"); ?> 
<script type="text/javascript" src="http://www.four20maps.com/js/jquery.validate.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
		$("#SubsForm").validate();
	});
	</script>