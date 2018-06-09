<?php
// include Config File
include_once './includes/config.inc.php';
include_once './includes/validate.php';
// Authenticate user login
//auth();
validate_cat_add();
$db = db_connect();
if($_POST)
{
	$data = $_POST;
	print_r($data);
}
?>
	  <?php include("header.php"); ?>
      <!-- Left side column. contains the logo and sidebar -->
      <?php include("sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Add New Subscription</h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i>Add New Subscription</a></li>
            <li class="active">Add New Subscription</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
            	<div class="box">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['ADMIN_ADD_STORE']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">


			<form method="post" OnSubmit="return false;" id="SubsForm">

			<div class="row">
            	<div class="col-lg-6 col-sm-12">

						<div class="form-group">
                            <label>Subscription Name: <span class='required'>*</span></label>
    
                            <input type='text' class="form-control" name="Subscription" class="form-control" id="subscription" required />
                        </div>

						
						<div class="form-group">
                            <label>Description: <span class='required'>*</span></label>
    
                            <textarea name="Description" class="form-control" id="description" required></textarea>
                        </div>
						

						<div class="form-group">
                            <label>Duration: <span class='required'>*</span></label>
    
                            <label>Years: <span class='required'>*</span></label>
								<input type="number" name="years" class="form-control" id="years" required />
                                <label>Months: <span class='required'>*</span></label>
								<input type="number" name="months" class="form-control" id="months" required />
                                <label>Days: <span class='required'>*</span></label>
								<input type="number"  name= "days" class="form-control" id="days" required />
                        </div>

						<div class="form-group">
                            <label>Initial Amount: <span class='required'>*</span></label>
                            <input type="number" name="IntialAmount" class="form-control" id="IntialAmount" required />
                        </div>
                        <div class="form-group">
                            <label>Renewal Amount: <span class='required'>*</span></label>
                            <input type="number" name="RenualAmount" class="form-control" id="RenualAmount" required />
                        </div>
                        <div class="form-group">
                            <label>Status: <span class='required'>*</span></label>
                            <select name="Status" class="form-control" id="status" required>
									<option selected disabled>Select Status</option>
									<option value="1">Avtive</option>
									<option value="2">In-Active</option>
								</select>
                        </div>
                        <input type="hidden" name="type" value="1">
                       
                     
                     
					

					
					</div>
                    <div class="col-lg-12 col-sm-12">
                        <div class='input buttons'>
                            <button class="btn btn-primary" OnClick="Addsubs();">Submit</button>
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
