<?
ob_start();
session_start();
$header = 'Categories';
$username = $_SESSION["regSuccess"];
if(empty($username)){ ?>
	<script>
		window.location.href = 'addstore.php';
	</script>
<?php }
include_once './includes/config.inc.php';
include_once './includes/functions.php';
include ('header.php');
 
	
	/*if(isset($_SESSION['userid']) && $_SESSION['userid'])
	{
		$qry_reg="select * from users where id=".$_SESSION['userid'];
		$qrex=mysql_query($qry_reg);
		$regfetch=mysql_fetch_assoc($qrex);
	}*/
//category details here
$catDet = array();
$catDet['Storeid'] = '';

if(isset($_GET['catid'])) {
	$catDet = getProductCategory($_GET['catid']);
}
//echo '<pre>';print_r($catDet);exit;
$error = $succ_msg = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	$product_name = $_POST['product_name'];
	$cat_desc_heading = $_POST['cat_desc_heading'];
	$cat_description = $_POST['cat_description'];


	if($product_name =='') {
		$error .= 'Enter Category Name <br>';
	}
	if($cat_desc_heading =='') {
		$error .= 'Enter Category Description Heading<br>';
	}
	if($cat_description =='') {
		$error .= 'Enter Category Description<br>';
	}

	
	if($error =='') {
		//print_r($_POST);exit;
		$res = insertCategoryProduct($_POST);
		$succ_msg = 'Category '.(isset($_GET['catid'])?'Updated':'Added').' successfully';
		header('Location: /categories.php');
		exit;
	}
}

/*$user = mysql_query("select UserId from StoreUsers where Username = '$username'");
$userdet=mysql_fetch_array($user);
$userid = $userdet['UserId'];

$user_stores = mysql_query("select name,StoreId from vw_SubscriptionStoreDetails where UserId=".$_SESSION['StoreID']);*/
?>
	<div class="row">
        <?php include ROOT."admin-left.php"; ?>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 myact-userdet">
            <h2 class="head-text"><?php echo isset($_GET['catid'])?'Update':'Add';?> Category</h2>
            <?php
            if($error!='') {
				echo ' <p>'.$error.'</p>';
			}
			 if($succ_msg!='') {
				echo ' <p>'.$succ_msg.'</p>';
			}
			?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><form class="form-horizontal" onSubmit="return validateCat()" id="addPorudct_form" name="addPorudct_form" method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="productid" id="productid" value="<?php echo isset($_GET['catid'])?$_GET['catid']:'0';?>">
                
					
                    <div class="form-group">
                <label class="col-lg-3 col-md-3 col-sm-5 col-xs-12 control-label">Category Name<span class="require">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                        <input type="text" id="product_name" Onblur="func(this.id);" name="product_name" value="<?php echo isset($catDet['product_name'])?$catDet['product_name']:'';?>" onKeyDown="limitText(this.form.product_name,this.form.countdown,25);" 
						onKeyUp="limitText(this.form.product_name,this.form.countdown,25);" OnBlur="limitText(this.form.product_name,this.form.countdown,25);" placeholder="Category Name" class="form-control required alpha">
                      </div>
              </div>
              		
                    <div class="form-group">
                <label class="col-lg-3 col-md-3 col-sm-5 col-xs-12 control-label">Category Description Heading<span class="require">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                        <input type="text" id="cat_desc_heading" name="cat_desc_heading" value="<?php echo isset($catDet['cat_desc_heading'])?$catDet['cat_desc_heading']:'';?>" onKeyDown="limitText(this.form.cat_desc_heading,this.form.countdown,25);" 
									onKeyUp="limitText(this.form.cat_desc_heading,this.form.countdown,25);" OnBlur="limitText(this.form.cat_desc_heading,this.form.countdown,25);" placeholder="Category Description Heading" class="form-control required alpha">
                      </div>
              </div>
              
              		<div class="form-group">
                <label class="col-lg-3 col-md-3 col-sm-5 col-xs-12 control-label">Category Description<span class="require">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                        <input type="text" id="cat_description" name="cat_description" value="<?php echo isset($catDet['cat_description'])?$catDet['cat_description']:'';?>" onKeyDown="limitText(this.form.cat_description,this.form.countdown,25);" 
									onKeyUp="limitText(this.form.cat_description,this.form.countdown,25);" OnBlur="limitText(this.form.cat_description,this.form.countdown,25);"  placeholder="Category Description" class="form-control required alpha">
                </div>
              </div>
				<div class="form-group">
				<label class="col-lg-3 col-md-3 col-sm-5 col-xs-12 control-label">Status
					<span class="require">*</span>
				</label>
				<div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
					<select name="isActive" id="isActive" class="form-control" required>
						<option value='' <?php if($catDet['active']==0){echo "selected";}?> disabled>Select Status</option>
						<option value="1" <?php if($catDet['active']==1){echo "selected";} ?>>Active</option>
						<option value="0" <?php if($catDet['active']==0){echo "selected";} ?>>In-Active</option>
					</select>
				</div>
				</div>
				<div class="form-group">
					<div class="col-lg-8 col-md-8 col-sm-8 col-sm-offset-4 col-md-offset-4">
						<button class="btn btn-primary" id="add_product" name="add_product" type="submit"><?php echo isset($_GET['catid'])?		'Update':'Add';?>
						</button>
						<a class="btn btn-danger" href="categories.php">Cancel</a>
					</div>
				</div>
                  </form></div>
          </div>
              </div>
      </div>
          </div>
  </div>
      </div>
<?php include ROOT."themes/footer.inc.php"; ?>
<script>
function validateCat()
{
	var error=0;
	$("#addPorudct_form").find("select,input").each(function(){
		if($(this).val()=='')
		{
			error=1;
			$(this).css("border","1px solid red")
		}
		else
			$(this).css("border","")
	})
	if(error>0)
		return false
}

function limitText(limitField, limitCount, limitNum) 
{
	if (limitField.value.length > limitNum) 
	{
		limitField.value = limitField.value.substring(0, limitNum);
	} 
	else
	{
		limitCount.value = limitNum - limitField.value.length;
	}
}
function redirect()
{
	window.location.href='categories.php';
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
</body>
</html>