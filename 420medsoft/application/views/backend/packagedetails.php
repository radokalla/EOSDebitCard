<div class="memberlogin-wps col-md-12 products_page">
  <h2>Package details</h2>
  
  <div class="col-md-12"> <?php echo validation_errors(); ?>
  <div id="message"></div>
    <form method="post" role="form" id="add-category-form" class="validate-form">
    <input type="hidden" name="PackageID" value="<?php echo isset($packages['PackageID']) ? $packages['PackageID'] : ''; ?>" />
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Package Name<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required " id="PackageName" name="PackageName" value="<?php echo isset($packages['PackageName']) ? $packages['PackageName'] : ''; ?>" placeholder="Enter Package Name"   >
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Description text<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required " id="Description" name="Description" value="<?php echo isset($packages['Description']) ? $packages['Description'] : ''; ?>" placeholder="Enter Description text"   >
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Amount<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required " id="Cost" name="Cost" value="<?php echo isset($packages['Cost']) ? $packages['Cost'] : ''; ?>" placeholder="Enter Amount"   >
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Recurring text<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required" id="RecurringDuration" name="RecurringDuration" value="<?php echo isset($packages['RecurringDuration']) ? $packages['RecurringDuration'] : ''; ?>" placeholder="Enter Recurring Text"   >
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Recurring Amount<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required" id="RecurringCost" name="RecurringCost" value="<?php echo isset($packages['RecurringCost']) ? $packages['RecurringCost'] : ''; ?>" placeholder="Enter Recurring Amount"   >
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Discounts : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1" id="Discounts" name="Discounts" value="<?php echo isset($packages['Discounts']) ? $packages['Discounts'] : ''; ?>" placeholder="Enter Discounts"   >
        </div>
      </div>
      <div class="col-md-2"></div>
      <div class="form-group">
        <div class="col-md-4"></div>
        <div class="col-md-6">
          <button type="submit" class="btn btn-primary category_button">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>
<script type="text/javascript">
function resetinventory()
{
	if(confirm("Are you sure you want to reset your inventory and sales?"))
	{
		var dataString = 'parentID=2'
		$.ajax({
			type: "POST",
			url: '<?php echo base_url('index.php/admininventory/resetInventory'); ?>',
			data: dataString,
			success: function (data) {
				$("#message").html(data);
			}
		});
	}
}
</script>