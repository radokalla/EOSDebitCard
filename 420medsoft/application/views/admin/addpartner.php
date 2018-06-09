<?php ?>
<div class="memberlogin-wps col-md-12 products_page">
  <h2><?php echo isset($employee['ID']) ? 'Edit' : 'Add'; ?> Partner</h2>
  <div class="col-md-12"> <?php echo validation_errors(); ?>
    <form method="post" role="form" id="add-category-form1" name="partnerform" class="">
      <input type="hidden" name="partner_id" value="<?php echo isset($employee['ID']) ? $employee['ID'] : ''; ?>" />
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">User Name</label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required alpha" id="userName" name="userName" value="<?php echo isset($employee['userName']) ? $employee['userName'] : ''; ?>" placeholder="Enter User Name"   <?php echo isset($employee['userName']) ? ' readonly="readonly"' : ''; ?>>
        </div>
      </div>
       <?php if(!isset($employee['ID'])){ ?>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Password</label>
        <div class="col-md-6">
          <input type="password" class="form-control text_input1 required" id="passowrd" name="password" value="" placeholder="Enter Password" >
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Confirm Password</label>
        <div class="col-md-6">
          <input type="password" class="form-control text_input1 required" equalto="passowrd" id="cpassowrd" name="cpassowrd" value="" placeholder="Enter Confirm Password"  >
        </div>
      </div>
      <?php } ?>
      
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">First Name</label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required alpha" id="firstName" name="firstName" value="<?php echo isset($employee['firstName']) ? $employee['firstName'] : ''; ?>" placeholder="Enter First Name"  >
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Last Name</label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required alpha" id="lastName" name="lastName" value="<?php echo isset($employee['lastName']) ? $employee['lastName'] : ''; ?>" placeholder="Enter Last Name"  >
        </div>
      </div>
       <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">address</label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required" id="address" name="address" value="<?php echo isset($employee['address']) ? $employee['address'] : ''; ?>" placeholder="Enter Address"  >
        </div>
      </div>
       <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Phone Number</label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required num" id="phone" name="phone" value="<?php echo isset($employee['phone']) ? $employee['phone'] : ''; ?>" placeholder="Enter Phone Number"  >
        </div>
      </div>
       <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Tax id</label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required alphanum" id="tax" name="tax" value="<?php echo isset($employee['tax']) ? $employee['tax'] : ''; ?>" placeholder="Enter Tax Id"  >
        </div>
      </div>
       <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">City</label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required " id="city" name="city" value="<?php echo isset($employee['city']) ? $employee['city'] : ''; ?>" placeholder="Enter City"  >
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">State</label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required" id="state" name="state" value="<?php echo isset($employee['state']) ? $employee['state'] : ''; ?>" placeholder="Enter State"  >
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Zip Code</label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required num" id="zip" name="zip" value="<?php echo isset($employee['zip']) ? $employee['zip'] : ''; ?>" placeholder="Enter Zip Code"  >
        </div>
      </div>
        <div class="col-md-2"></div>
      </div>
      <div class="form-group">
        <div class="col-md-4"></div>
        <div class="col-md-6">
          <button type="submit" class="btn btn-primary category_button"><?php echo isset($employee['ID']) ? 'Update' : 'Add'; ?></button>
        </div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#userName").on('blur', function(){
		$("#userName").removeClass("regster_success").removeClass("regster_wrong").addClass("spinner");
		var userName = $(this).val();
		var dataString = "userName="+userName;
		$.ajax({
			type: "POST",
			url: '<?php echo base_url('index.php/admin/checkPartner'); ?>',
			data: dataString,
			success: function (data) {
				if(data == true)
				{
					$("#userName").removeClass("regster_wrong").removeClass("spinner").addClass("regster_success");
				}
				else
				{
					$("#userName").removeClass("spinner").removeClass("regster_success").addClass("regster_wrong");
					$("#userName").val('');
				}
			}
		});
	})
});
</script>
