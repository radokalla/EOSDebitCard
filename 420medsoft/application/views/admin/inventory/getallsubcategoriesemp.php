
<div class="col-md-12">
  <div class="form-group">
    <label class="col-md-4" for="exampleInputEmail1">Product Name</label>
    <div class="col-md-8">
      <select name="request[<?=$rel;?>][productID]" id="product_id_<?=$rel;?>" required class="text_input3">
        <option value=""> -- Select -- </option>
        <?php foreach($categories as $categoryID => $categoryDetails){  ?>
        <option value="<?php echo $categoryID; ?>"><?php echo $categoryDetails['categoryName']; ?></option>
        <?php } ?>
      </select>
    </div>
  </div>
</div>
<div id="show_ptoducts_<?=$rel;?>"> </div>
<script type="text/javascript">
$(document).ready(function(){
	$("#product_id_<?=$rel;?>").change(function(){
		var parent_id = $("#parent_id_<?=$rel;?>").val();
		var product_id = $(this).val();
		var dataString = "parentID="+parent_id+"&productID="+product_id+"&rel="+<?=$rel;?>;
		$.ajax({
			type: "POST",
			url: '<?php echo base_url('index.php/adminempinventory/getAllProducts'); ?>',
			data: dataString,
			success: function (data) {
				$("#show_ptoducts_<?=$rel;?>").html(data);
			}
		});
    });
	
});
</script> 
