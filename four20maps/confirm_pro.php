<?php 
session_start();
$header='cProducts';
$username = $_SESSION["regSuccess"];
if(empty($username)){ ?>
	<script>
		window.location.href = 'addstore.php';
	</script>
<?php }
include_once './includes/config.inc.php';
error_reporting(E_ALL);

$user = mysql_query("select UserId from StoreUsers where Username = '$username'");
$userdet=mysql_fetch_array($user);
$userid = $userdet['UserId'];
$store = mysql_query("select name,StoreId from vw_SubscriptionStoreDetails where UserId=".$_SESSION['StoreID']);
include('header.php');
 ?>
<div class="row">
	<?php include ROOT."admin-left.php"; ?>
		<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 myact-userdet">
				<h2 class="head-text">
					Confirm Products for Listing 
				</h2>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="alert alert-warning" role="alert" id="textmsg" style="text-align:center; display:none"></div>
					<form class="form-horizontal" id="Confirm_Products" method="post" OnSubmit="return false;">
						<div class="form-group" id="Category_div">
							<label class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label">
							Select Category <span class="require">*</span>
							</label>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
								<select name="CategoryId" id="CategoryId" OnChange="GetProducts();" class="form-control required" required>
									<option selected disabled>Select Category</option>
								</select>
							</div>
						</div>
						<div class="form-group" id="Products_div">
							<label class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label">
							Select Product <span class="require">*</span>
							</label>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
								<select name="ProductId" id="ProductId" OnChange="GetProduct();" class="form-control required" required>
									<option selected disabled>Select Product</option>
								</select>
							</div>
						</div>
						<div class="form-group" id="Product_div" style="display:none">
							<div class="form-group">
								<label class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label">
								Enter QBCODE <span class="require">*</span>
								</label>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
									<input type="text" name="QBcode" id="QBcode" class="form-control required" required />
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label">
								Enter Product Name <span class="require">*</span>
								</label>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
									<input type="text" name="productName" id="productName" class="form-control required" required />
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label">
								Enter Price <span class="require">*</span>
								</label>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
									<input type="text" name="price" OnKeyup="func(this.id);" id="price" class="form-control required" required />
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label">
								Select Status <span class="require">*</span>
								</label>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
									<select type="text" name="isActive" id="isActive" class="form-control required" required >
										<option value="1" id="option1">Active</option>
										<option value="1" id="option2">In-Active</option>
									</select>
								<br><button class="btn btn-primary" OnClick="SubmitForm();">Submit</button>
								</div>
								<input type="hidden" name="PcategoryID" id="PcategoryID" value="" />
								<input type="hidden" name="productID" id="PproductID" value="" />
								<input type="hidden" name="type" value="6" />
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
</div>
        </div>
	</div>
</div>
<script>
$(document).ready(function(){
	$.ajax({
			type: "POST",
			url: "checkEmail.php",
			data : {type:'3'},
			success: function(data)
			{
				if(data!='')
				{
					$('#CategoryId').empty().append(data);
					//$('#CategoryId').append(data);
				}
			}
		});
});

function func(id)
{
	var namesPattern = /^[0-9]*$/;
	var name = $('#'+id).val();
	if(!namesPattern.test(name))
	{
		 $('#'+id).css('border-color','red');
		 $('#'+id).val('');
	}
	else	
	{
		$('#'+id).css('border-color','');
	}
}

function GetProducts()
{
	$('#Product_div').hide();
	$('#textmsg').hide();
	var CategoryId = $('#CategoryId').val();
	if((CategoryId!=''))
	{
		$.ajax({
					type: "POST",
					url: "checkEmail.php",
					data : {'CategoryId':CategoryId, type:'4'},
					success: function(data)
					{
						if(data==1)
						{
							$('#ProductId').empty();
							document.getElementById('textmsg').innerHTML = 'No Products found..';
							$('#textmsg').removeClass('alert alert-success');
							$('#textmsg').addClass('alert alert-danger');
							$('#textmsg').show();
							setTimeout(function (){$('#textmsg').hide(); }, 4000);
						}
						else
						{
							$('#ProductId').empty().append(data);
							//$('#ProductId').append(data);
						}
					}
			});
	}
}

function GetProduct()
{
	$('#Product_div').hide();
	$('#textmsg').hide();
	var StoreId = $('#StoreId').val();
	var ProductId = $('#ProductId').val();
	if(ProductId!='')
	{
		$.ajax({
					type: "POST",
					url: "checkEmail.php",
					data : {'ProductId':ProductId,'StoreId':StoreId, type:'5'},
					success: function(data)
					{
						if(data!='1')
						{
							var obj = JSON.parse(data);
							$('#QBcode').val(obj.QBcode);
							$('#productName').val(obj.productName);
							$('#price').val(obj.price);
							$('#PcategoryID').val(obj.categoryID);							
							var Active = obj.isActive;
								if(Active==1)
									$('#option1').attr('selected', 'selected');
								else
									$('option2').attr('selected', 'selected');
							$('#PproductID').val(ProductId);
							$('#pStoreId').val(obj.StoreId);
							$('#Product_div').show();
						}
						else
						{
							$('#PproductID').val('');
						}
					}
		});
	}
}

function SubmitForm()
{
	$('#textmsg').hide();
	var formdata = $('#Confirm_Products').serializeArray();
	var QBcode = $('#QBcode').val();
	var productName = $('#productName').val();
	var price = $('#price').val();
	var PcategoryID = $('#PcategoryID').val();							
	var PproductID = $('#PproductID').val();
	var isActive = $('#isActive').val();
	if((QBcode!='') && (productName!='') && (price!='') && (isActive!=''))
	{
		$.ajax({
					type: "POST",
					url: "checkEmail.php",
					data : formdata,
					success: function(data)
					{
						$('#Product_div').hide();
						if(data==1)
						{
							document.getElementById('textmsg').innerHTML = "Update Successful..";
							$('#textmsg').removeClass('alert alert-warning');
							$('#textmsg').addClass('alert alert-success');
							$('#textmsg').show();
							setTimeout(function (){window.location.href = 'productdetails.php' }, 3000);
						}
						else
						{
							document.getElementById('textmsg').innerHTML = data;
							$('#textmsg').removeClass('alert alert-success');
							$('#textmsg').addClass('alert alert-warning');
							$('#textmsg').show();
							setTimeout(function (){$('#textmsg').hide(); }, 4000);
						}
					}
		});
	}
	else
	{
		document.getElementById('textmsg').innerHTML = 'Fill all the fields..';
		$('#textmsg').removeClass('alert alert-success');
		$('#textmsg').addClass('alert alert-warning');
		$('#textmsg').show();
		setTimeout(function (){$('#textmsg').hide(); }, 4000);
	}
	
}
</script>
<?php include ROOT."themes/footer.inc.php"; ?>