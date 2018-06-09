
<div class="col-md-12">
      <div class="form-group">
        <label class="col-md-4" for="exampleInputEmail1">Product Name</label>
<div class="col-md-8">
        <select name="productID" id="product_id" required="required" class="text_input3">
        <option value=""> -- Select -- </option>
        <?php foreach($categories as $categoryID => $categoryDetails){  ?>
        <option value="<?php echo $categoryID; ?>"><?php echo $categoryDetails['categoryName']; ?></option>
        <?php } ?>
        </select>
</div>
      </div>
  </div>
  
    
  <div id="show_ptoducts">
  
  </div>



<script type="text/javascript">
$(document).ready(function(){
	$("#product_id").change(function(){
		var parent_id = $("#parent_id").val();
		var product_id = $(this).val();
		var dataString = "parentID="+parent_id+"&productID="+product_id;
		$.ajax({
			type: "POST",
			url: '<?php echo base_url('index.php/admininventory/getAllProducts'); ?>',
			data: dataString,
			success: function (data) {
				$("#show_ptoducts").html(data);
			}
		});
    });
	
});
</script>
