
<div class="memberlogin-wps col-md-12 products_page">
  <h2>Products Inventory</h2>
  <div class="col-md-12">
      <div class="form-group">
        <label class="col-md-4" for="exampleInputEmail1">Category Name</label>
<div class="col-md-8">
        <select name="parentID" id="parent_id" class="text_input3 required">
        <option value=""> -- Select -- </option>
        <?php foreach($categories as $mainCategoryID => $mainCategoryDetails){ ?>
        <option value="<?php echo $mainCategoryID; ?>"><?php echo $mainCategoryDetails['categoryName']; ?></option>
        <?php } ?>
        </select>
</div>
      </div>
  </div>
  
  <div id="show_subcategories">
  
  </div>
  
</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#parent_id").change(function(){
		var parent_id = $(this).val();
		var dataString = "parentID="+parent_id;
		$.ajax({
			type: "POST",
			url: '<?php echo base_url('index.php/admininventory/getAllSubcategories'); ?>',
			data: dataString,
			success: function (data) {
				$("#show_subcategories").html(data);
			}
		});
    });
	
});
</script>