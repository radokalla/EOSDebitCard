<?php
$heading ='admins';
// include Config File
include_once './includes/config.inc.php';
include_once './includes/validate.php';
// Authenticate user login
auth();
validate_user_add();
?>
	  <?php include("header.php"); ?>
      <!-- Left side column. contains the logo and sidebar -->
      <?php include("sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Admin Users</h1>
          <ol class="breadcrumb">
            <li><a href="adminusers.php"><i class="fa fa-dashboard"></i>Admin Users list</a></li>
            <li class="active">Add Admin</li>
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
					<?php if(isset($errors)): ?>

			<div class="alert alert-block alert-error fade in">				
                <ul>
					<?php foreach($errors as $k=>$v): ?>
                    <li><?php echo $v; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
			<?php endif; ?>
			<div class="alert alert-danger" id="alert" style="text-align:center; display:none"></div>
			<form method='post' action='' id='form_new_store' enctype="multipart/form-data">

			<div class="row">
            	<div class="col-lg-6 col-sm-12">

						<div class="form-group">
                            <label><?php echo $lang['ADMIN_USERNAME']; ?>: <span class='required'>*</span></label>
							<input type='text' class="form-control" name='username' id='username' value='<?php echo $fields['username']['value']; ?>' OnBlur="func(this.id)" required/>
                        </div>
						<div class="form-group">
                            <label><?php echo $lang['ADMIN_PASSWORD']; ?>: <span class='required'>*</span></label>
						<input type='password' class="form-control" name='password' id='password' value='<?php echo $fields['password']['value']; ?>' />
						<p style="color:red">Password Must be Alpha - Numeric</p>
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
                            <a class="btn btn-primary" OnClick="required();" name='save' id='save'><?php echo $lang['ADMIN_SAVE']; ?></a>
							<a href="adminusers.php" class="btn btn-danger">Cancel</a>
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
<script>
function required()
{
	var username = $.trim($('#username').val());
	var password = $.trim($('#password').val());
	var cpassword = $.trim($('#cpassword').val());
	var email = $.trim($('#email').val());
	if( (username!='') && (password!='') && (cpassword!='') && (email!=''))
	{
		$('#form_new_store').submit();
	}
	else
	{
		$('#alert').html('Please fill all the details !!');
		$('#alert').show();
		setTimeout(function (){$('#alert').hide(); }, 4000);
	}
}

function func(id)
{
	var namesPattern = /^[a-zA-Z ]*$/;
	var name = $('#'+id).val();
		if(!namesPattern.test(name))
		{
			$('#'+id).css('border-color','red')
			$('#'+id).val('');
		}
		else
		{
			$('#'+id).css('border-color','#eee')
		}
}
</script>
            <?php include("footer.php"); ?>