<?php
// include Config File
include_once './includes/config.inc.php';
include_once './includes/validate.php';
// Authenticate user login
//auth();
validate_user_add();
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
                  <h3 class="box-title"><?php echo $lang['ADMIN_ADD_USER']; ?></h3>
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
                            <label><?php echo $lang['ADMIN_USERNAME']; ?>: <span class='required'>*</span></label>
							<input type='text' class="form-control" name='username' id='username' value='<?php echo $fields['username']['value']; ?>' />
                        </div>
						<div class="form-group">
                            <label><?php echo $lang['ADMIN_PASSWORD']; ?>: <span class='required'>*</span></label>
						<input type='password' class="form-control" name='password' id='password' value='<?php echo $fields['password']['value']; ?>' />
                        </div>
						<div class="form-group">
                            <label><?php echo $lang['ADMIN_CONFIRM_PASSWORD']; ?>:</label>
							<input type='password' class="form-control" name='cpassword' id='cpassword' value='<?php echo $fields['cpassword']['value']; ?>' />
                        </div>
						<div class="form-group">
                            <label>E-mail:*</label>
							<input type='text' class="form-control" name='email' id='email' value='<?php echo $fields['email']['value']; ?>' />
                        </div>
					</div>

                    
                    <div class="col-lg-12 col-sm-12">
                        <div class='input buttons'>
                            <button type='submit' class="btn btn-primary" name='save' id='save'><?php echo $lang['ADMIN_SAVE']; ?></button>
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
