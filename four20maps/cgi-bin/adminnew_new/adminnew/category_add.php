<?php
// include Config File
include_once './includes/config.inc.php';
include_once './includes/validate.php';
// Authenticate user login
//auth();
validate_cat_add();

?>
	  <?php include("header.php"); ?>
      <!-- Left side column. contains the logo and sidebar -->
      <?php include("sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Add a Store</h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Store List</a></li>
            <li class="active">Add a Store</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
            	<div class="box">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['SSF_ADD_CATEGORY']; ?></h3>
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


			<form method='post' action='' id='form_new_store' enctype="multipart/form-data">

			<div class="row">
            	<div class="col-lg-6 col-sm-12">

						<div class="form-group">
                            <label><?php echo $lang['SSF_CATEGORY_NAME']; ?>: <span class='required'>*</span></label>
							<input type='text' class="form-control" name='cat_name' id='cat_name' value='<?php echo $fields['cat_name']['value']; ?>' />
                        </div>
						<div class="form-group">
                            <label>Map Icon:</label>
						<input type="file" name="map_icon" id="map_icon" />
                        </div>
						<div class="form-group">
                            <label><?php echo $lang['SSF_CATEGORY_ICON']; ?>:</label>
							<input type="file" name="file" id="file" />
                            <span><?php echo $lang['ADMIN_THUMB_AUTO']; ?></span>
                        </div>
                        <?php if(!empty($images)): ?>
						<div class="form-group">
                        <?php foreach($images as $k=>$v): ?>
                            <div class="image">
							<img src="<?php echo $v; ?>" alt="Image" />
							<button type="submit" name="delete_image[<?php echo basename($v); ?>]" id="delete_image" class="btn btn-danger" value="<?php echo basename($v); ?>"><?php echo $lang['ADMIN_DELETE']; ?></button>
						</div>
						<?php endforeach; ?>
                        </div>
                        <?php endif; ?>
					</div>

                    
                    <div class="col-lg-12 col-sm-12">
                        <div class='input buttons'>
                            <button type='submit' class="btn btn-primary" name='save' id='save'><?php echo $lang['ADMIN_SAVE']; ?></button>
						    <button type='button' class="btn btn-primary" onclick="document.location.href='categories.php'"><?php echo $lang['ADMIN_CANCEL']; ?></button>
                        </div>
                    </div>
				</div>
			</form>

			</div>
                </div><!-- /.box-body -->
              </div>
            </div>

          </div><!-- /.row -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
            <?php include("footer.php"); ?>
