
<div class="col-md-12">
  <div class="form-group">
    <label class="col-md-4" for="exampleInputEmail1">Inner Product Name</label>
    <div class="col-md-8">
      <select name="request[<?=$rel;?>][productID]" id="iproduct_id_<?=$rel;?>" rel="<?=$rel;?>" required <?php /*?>onchange="getRemain();"<?php */?> class="text_input3">
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
    <label id="exampleInputRemaining_<?=$rel;?>">&nbsp;0</label>
    </div>
  </div>
  <div class="form-group">
    <label class="col-md-4" for="exampleInputEmail1">Inventory</label>
    <div class="col-md-8">
      <input type="text"  class="form-control num text_input1" data-max="0" name="request[<?=$rel;?>][inventory]" id="inventory_<?=$rel;?>" value="" placeholder="Inventory" required="required"  />
    </div>
  </div>
  <?php /*?><div class="form-group">
    <div class="col-md-8">
      <button type="submit" class="btn btn-primary category_button">Add</button>
    </div>
  </div><?php */?>
</div>

<?php /*?><script>
function getRemain()
{
	var productID = $('#iproduct_id_<?=$rel;?>').val();
	var dataString = "productID="+productID+"&rel="+<?=$rel;?>;
	$.ajax({
		type: "POST",
		url: '<?php echo base_url('index.php/admininventory/getRemaining'); ?>',
		data: dataString,
		success: function (data) {
			$('#exampleInputRemaining_<?=$rel;?>').html(data);
			$('#inventory_<?=$rel;?>').attr('data-max',data.trim());
		}
	});
}
</script><?php */?>