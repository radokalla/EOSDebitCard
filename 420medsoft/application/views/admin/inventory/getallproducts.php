<form method="post" role="form" id="add-category-form" class="validate-form">
<div class="col-md-12">
  <div class="form-group">
    <label class="col-md-4" for="exampleInputEmail1">Inner Product Name</label>
    <div class="col-md-8">
      <select name="productID" id="iproduct_id"  required="required" onchange="getRemain();" class="text_input3">
        <option value=""> -- Select -- </option>
        <?php foreach($categories[$productID]['products'] as $productID => $productDetails){  ?>
        <option value="<?php echo $productID; ?>"><?php echo $productDetails['productName']; ?></option>
        <?php } ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-md-4" for="exampleInputRemaining">Remaining Products</label>
    <div class="col-md-8">
    <label id="exampleInputRemaining">&nbsp;0</label>
    </div>
  </div>
  <div class="form-group">
    <label class="col-md-4" for="exampleInputEmail1">Inventory</label>
    <div class="col-md-8">
      <input type="text"  class="form-control num text_input1" name="inventory" id="inventory" value="" placeholder="Inventory" required="required"  />
    </div>
  </div>
  <div class="form-group">
    <div class="col-md-8">
      <button type="submit" class="btn btn-primary category_button">Add</button>
    </div>
  </div>
</div>
</form>
<script>
	function getRemain()
	{
		var productID = $('#iproduct_id').val();
		var dataString = "productID="+productID;
		$.ajax({
			type: "POST",
			url: '<?php echo base_url('index.php/admininventory/getRemaining'); ?>',
			data: dataString,
			success: function (data) {
				$('#exampleInputRemaining').html(data);
				//$('#inventory').attr('data-max',data.trim());
			}
		});
	}


</script>