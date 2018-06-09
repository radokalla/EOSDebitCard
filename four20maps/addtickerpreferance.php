<?
ob_start();
session_start();
//echo "<pre>";
//print_r($_SESSION);
$header='Products';
$username = $_SESSION["regSuccess"];
if(empty($username)){ ?>
	<script>
		window.location.href = 'addstore.php';
	</script>
<?php }
include_once './includes/config.inc.php';
include_once './includes/functions.php';
error_reporting(E_ALL);
	
//code for the add product 
$categories = mysql_query("select * from ProductCategory where parentID = 0 order by categoryID desc");

$return_options = array();
$options = mysql_query('select * from options order by optionID');
while($option = mysql_fetch_assoc($options))
{
	$return_options[$option['optionID']] = array('optionType' => $option['optionType'], 'type' => $option['type']);
}
$productTypes = array('Un-assigned','Indica','Sativa','Hybrid');

$productcatDetails = array();
echo "<script>var category='';</script>";
if(isset($_GET['prodid'])) {
	$prodcatDet = getProductCategory($_GET['prodid']);
	$cst = mysql_query("select price,qty,productDescription from Products where categoryID =".$_GET['prodid']);
	$cost = mysql_fetch_array($cst);
	if($prodcatDet['category']!='')
	echo "<script>category=".$prodcatDet['category'].";</script>";
	//var_dump($prodcatDet);die;
}
//echo '<pre>';print_r($prodcatDet['Storeid']);exit;
$error = $succ_msg = '';
if($_POST) {
	$categoryID = $_POST['categoryID'];
	$productName = $_POST['product_name'];
	$product_type = $_POST['categoryType'];


	if(($categoryID =='')||($productName =='')||($product_type =='')) {
		$error .= 'Please Fill all the fields';
	}
	
	
	if($error =='') {
		
		$_POST['applimagepath'] = uploadImage();
		//print_r($_POST);exit;
		$res = insertCategoryProduct($_POST);
		
		$succ_msg = 'Product '.(isset($_GET['prodid'])?'Updated':'Added').' successfully';
		header('Location: /productdetails.php');
	}
}
	include("header.php");
?>
            <div class="row">
        <?php include ROOT."admin-left.php"; ?>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 myact-userdet">
            <h2 class="head-text"><?php echo isset($_GET['prodid'])?'Update':'Add';?> Product</h2>
            <?php
            if($error!='') 
			{
				echo '<div class="alert alert-warning" role="alert" style="text-align:center">'.$error.'</div>';
				break ;
			}
			 if($succ_msg!='') 
			{
				echo '<div class="alert alert-warning" role="alert" style="text-align:center">'.$succ_msg.'</div>';
				break ;
			}
			?>
			<?php if(!empty($_SESSION['proUp'])){ ?>
				<div class="alert alert-warning" role="alert" style="text-align:center"><?php echo $_SESSION['proUp']; unset($_SESSION['proUp']); ?></div>
			<?php } ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <form class="form-horizontal" id="addPorudct_form" name="addPorudct_form" method="post" onSubmit="return validateProd()" enctype="multipart/form-data">
            <input type="hidden" name="productid" id="productid" value="<?php echo isset($_GET['prodid'])?$_GET['prodid']:'0';?>">

				  <div class="form-group">
					<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Category Name<span class="require">*</span></label>
					<div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
						<select class="form-control required" id="category" name="categoryID" title="Category Name"></select>
                    </div>
              </div>
                    <div class="form-group">
                <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Product Name<span class="require">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                        <input type="text" id="product_name" name="product_name" onKeyDown="limitText(this.form.product_name,this.form.countdown,100);" 
									onKeyUp="limitText(this.form.product_name,this.form.countdown,100);" OnBlur="limitText(this.form.product_name,this.form.countdown,100);"
						value="<?php echo isset($prodcatDet['product_name'])?$prodcatDet['product_name']:'';?>" placeholder="Product Name" 
						class="form-control required alpha">
                      </div>
              </div>
                    <div class="form-group">
                <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Product Type<span class="require">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
				<?php
					
					//print_r( $prodcatDet['product_type']);
						?>
				
                        <select class="form-control required" id="product_type" name="categoryType" title="Product Type">
                    <option value="" disabled selected>Select one</option>
                    <?php
					foreach($productTypes as $prodtype){ 
					?>
                    <option value="<?php echo $prodtype;?>" <?php if(isset($prodcatDet['product_type']) && $prodcatDet['product_type']==$prodtype){ echo 'selected="selected"';}?>><?php echo $prodtype;?></option>
                    <?php } ?>
                  </select>
                      </div>
              </div>
                    <div class="form-group">
                <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Product Image<span class="require">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                        <input type="file" name="image" id="image" placeholder="Image" class="<?php echo isset($_GET['prodid'])?'':'required';?>" accept="image/*"  onchange="showMyImage(this)">
                     
                  <?php if(isset($_GET['prodid'])){?>
                   <br><img src="<?php echo $prodcatDet['image'];?>" height="200" width="200">
                   <?php }?>
              </div>
               </div> 
               
				<div class="form-group">
					<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Description  <span class="require"> *</span></label>
					<div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
					<textarea name="productDescription"  id="productDescription"  class="form-control required" ><?php if(isset($_GET['prodid'])){ ?> <?php echo $cost['productDescription'];} ?></textarea>
					</div>
				</div>             
              <?php 
			 foreach($return_options as $optionID=>$optionDetails) {?>
              <div class="form-group">
                <label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label"><?php echo $optionDetails['optionType']; ?><span class="require">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                 <?php if($optionDetails['type'] == 'text' || $optionDetails['type'] == 'textshow'){ ?>
                 <input type="text" id="option-<?php echo $optionID; ?>" name="options[<?php echo $optionID; ?>]" placeholder="Enter <?php echo $optionDetails['optionType']; ?>" class="form-control required " value="<?php echo isset($prodcatDet['options'][$optionID])?$prodcatDet['options'][$optionID]:'';?>">
                <?php }else if($optionDetails['type'] == 'radio'){ ?>
                <input type="radio" id="option-<?php echo $optionID; ?>_1" name="options[<?php echo $optionID; ?>]" value="1" checked="checked" /> Yes
       			 <input type="radio" id="option-<?php echo $optionID; ?>_0"  name="options[<?php echo $optionID; ?>]" value="0" />  No
        
        		<?php } ?>
                      </div>
              </div>
              <?php 
			 }
			  ?>
				<input type="hidden" name="isActive" value="1" />
				
			   <div class="form-group">
					<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Price  <span class="require"> *</span></label>
					<div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
						<input type="text" class="form-control required" name="cost" <?php if(isset($_GET['prodid'])){ ?> value="<?php echo $cost['price'];} ?>" placeholder="Enter the price of the product" id="cost" Onblur="cost_valid(this.id)">
					</div>
				</div>
                   <div class="form-group">
					<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Quantity  <span class="require"> *</span></label>
					<div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
						<input type="text" class="form-control required" name="qty" <?php if(isset($_GET['prodid'])){ ?> value="<?php echo $cost['qty'];} ?>" placeholder="Enter the Quantity of the product" id="qty">
					</div>
				</div>  
                    <div class="form-group">
                <div class="col-lg-8 col-md-8 col-sm-8 col-sm-offset-4 col-md-offset-4">
                     <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal" id="preview">Preview</button>
                        <button class="btn btn-primary" id="add_product" name="add_product"><?php echo isset($_GET['prodid'])?'Update':'Add';?></button>
                        <button class="btn btn-danger" OnClick="redirect();">Cancel</button>
                </div>
              </div>
                  </form></div>
          </div>
              </div>
      </div>
          </div>
  </div>
      </div>
	  <div class="col-lg-12">
		<br><br>
	  </div><!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="product_title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p><img id="thumbnil" src="" alt="image"/></p>
        <div class="row"> 
        
			<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12" id="product_description"></div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><div class="product-itemwps-bx prditmsbxwdful"><div class="product-itemwps prdctitem_optiontype"><div class="prd-optsbx-txt mbwdfl"></div><div class="prd-optsbx-txt"><span class="prd-optsbx-txt-optype">CBD % :</span> <span class="prd-optsbx-txt-sb" id="cbd_per">0.00</span></div><div class="prd-optsbx-txt"><span class="prd-optsbx-txt-optype">THC % :</span> <span class="prd-optsbx-txt-sb" id="thc_per">0.00</span></div><div class="prd-optsbx-txt"><span class="prd-optsbx-txt-optype">THCA % :</span> <span class="prd-optsbx-txt-sb" id="thca_per">0.00</span></div></div></div></div></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<script>
	$(document).ready(function () {
      
    $("#myBtn").click(function(){
         $('#myModal').modal('show');
    });
});
<?php if(isset($prodcatDet['image'])){ ?>	
 $("#thumbnil").attr("src","<?php echo $prodcatDet['image'];?>");
	<?php } ?>
	
	
	function showMyImage(fileInput) {
        var files = fileInput.files;
        for (var i = 0; i < files.length; i++) {           
            var file = files[i];
            var imageType = /image.*/;     
            if (!file.type.match(imageType)) {
                continue;
            }           
            var img=document.getElementById("thumbnil");            
            img.file = file;    
            var reader = new FileReader();
            reader.onload = (function(aImg) { 
                return function(e) { 
                    aImg.src = e.target.result; 
                }; 
            })(img);
            reader.readAsDataURL(file);
        }    
    }
function validateProd()
{
	var error=0;
	$("#addPorudct_form").find("input,select").each(function(){
		if($(this).hasClass("required"))
		{
			if($(this).val()=='')
			{
				error = 1;
				$(this).css("border","1px solid red")
			}
			else
				$(this).css("border","")
		}
		
	});
	
	if(error>0)
		return false;
	else
		return true;
	}
	


$("#category").change(function(){
	$(this).css("border","")
})
/*$('#option-2,#option-3,#option-4').keypress(function(event) { 
  if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
    event.preventDefault();
  }
});*/
function submit()
{
		$('#image').val();
        var file = this.files[0];
        console.log(file['type']);
		var mystr = file['type'];
		var myarr = mystr.split("/");
		console.log(myarr[1]);
		var filetype = myarr[1];
		if((filetype== 'gif') || (filetype=='jpg') || (filetype=='jpeg') || (filetype=='png') || (filetype=='PNG'))
		{
			console.log(filetype);
			$('#addPorudct_form').submit();
		}
		else
			alert('File Type Should be Only JPEG, JPG, PNG or GIF');
		//var myvar = myarr[1] + ":" + myarr[2];
}
function redirect()
{
	window.location.href='productdetails.php';
}

$(document).ready(function() {
		$.ajax({
			type: "POST",
			url: "checkEmail.php",
		<?php if(isset($_GET['prodid'])){ ?>
			data : {type:'3', cat_id:"<?php echo $prodcatDet['category']?>"},
		<?php } else{ ?>
			data : {type:'3'},
		<?php } ?>
			success: function(data)
			{
				if(data!='')
				{
					$('#category').append(data);
					$('#StoreId').trigger('change');
				}
			}
		});
});
function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) 
	{
		limitField.value = limitField.value.substring(0, limitNum);
	} 
	else
	{
		limitCount.value = limitNum - limitField.value.length;
	}
}
function cost_valid(id)
{
var numPattern =/^[0-9]*\.?[0-9]*$/;
	var name = $('#'+id).val();
	if(!numPattern.test(name))
	{
		 $('#'+id).css('border-color','red');
		 $('#'+id).val('');
	}
	else	
	{
		if(name <= 0)
		{
			$('#'+id).css('border','red');
			$('#'+id).val();
		}
		else
			$('#'+id).css('border','');
	}
}
</script>
<?php include ROOT."themes/footer.inc.php"; ?>
<script type="text/javascript">
	$(document).ready(function () {
     
    $("#preview").click(function(){ 
		$("#product_title").html($("#product_name").val());
       $("#cbd_per").html($("#option-2").val());
		$("#thc_per").html($("#option-3").val());
		$("#thca_per").html($("#option-4").val());
		$("#product_description").html($("#productDescription").val());
		
    });
});</script>
</body>
</html>