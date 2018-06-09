<div class="memberlogin-wps col-md-12 products_page">
  <h2>Logo details</h2>
  
  <div class="col-md-12"> <?php echo validation_errors(); ?>
  <div id="message"></div>
    <form method="post" action="<?php echo base_url('index.php/backend/footerlogo/'); ?>" role="form" id="add-category-form" class="validate-form" enctype="multipart/form-data">
    <input type="hidden" name="SubscriberID" value="<?php echo $logoss[0]['id']; ?>" />
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Footer Logo<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="file" class="form-control text_input1 required " id="file" name="file" />
          <img src="<?php echo base_url('images/'.$logoss[0]['footerlogo']); ?>">
          <h5><?php echo $logoss[0]['footerlogo']; ?></h5>
        </div>
      </div>
     <?php /*<div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Footer Logo<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="file" class="form-control text_input1 required " id="headerlogo" name="headerlogo[]" value=""/><h5><?php echo $logoss[0]['footerlogo']; ?></h5>
        </div>
      </div> */?>
	    
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