
<div class="memberlogin-wps col-md-12 products_page">
  <h2>Product Pricing List</h2>
  <div class="col-md-12">
    <div class="form-group">
      <label class="col-md-4" for="exampleInputEmail1">Category Name</label>
      <div class="col-md-8">
        <select name="parentID" id="parent_id" required class="text_input5">
          <option value=""> -- Select -- </option>
          <?php foreach($categories as $mainCategoryID => $mainCategoryDetails){ ?>
          <option value="<?php echo $mainCategoryID; ?>"><?php echo $mainCategoryDetails['categoryName']; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
  </div>
  
  
  
    <div class="col-md-12">
      <div class="form-group">
        <label class="col-md-4 patient_id" for="exampleInputEmail1">Product Type</label>
        <div class="col-md-8">
          <select name="productType" id="productType" class="text_input5">
            <option value="all"> -- Select -- </option>
            <option value="Indica">Indica</option>
            <option value="Sativa">Sativa</option>
            <option value="Hybrid">Hybrid</option>
          </select>
        </div>
      </div>
    </div>
  
    <div class="col-md-12">
      <div class="form-group">
        <label class="col-md-4 patient_id" for="exampleInputEmail1">Product Name</label>
        <div class="col-md-8">
          <input type="text" id="categoryName" class="text_input5">
        </div>
      </div>
    </div>
  
    <div class="col-md-12">
      <div class="form-group">
        <div class="col-md-10"></div>
        <div class="col-md-2">
          <button type="button" class="btn btn-primary category_button" id="search-cat">Search</button>
        </div>
      </div>
    </div>
    
  <div class="col-md-12" id="show_subcategories"> </div>
</div>
<script type="text/javascript">

$(document).ready(function(){

	$("#search-cat").click(function(){
			var parent_id = $("#parent_id").val();
			var categoryName = $("#categoryName").val();
			var productType = $("#productType").val();
			var dataString = "parentID="+parent_id+"&categoryName="+categoryName+"&productType="+productType;
			$.ajax({
				type: "POST",
				url: '<?php echo base_url('index.php/adminproducts/getAllSubcategoriesWithSearch'); ?>',
				data: dataString,
				success: function (data) {
					$("#show_subcategories").html(data);
				
				}
			});
		});
		
	$("#parent_id").change(function(){

		var parent_id = $(this).val();

		var dataString = "parentID="+parent_id;

		$.ajax({

			type: "POST",

			url: '<?php echo base_url('index.php/adminproducts/getAllSubcategories'); ?>',

			data: dataString,

			success: function (data) {

				$("#show_subcategories").html(data);

			}

		});

    });

	

});

function getQrCode(id)
{
	pid=$('#parent_id').val();
	window.open('<?php echo base_url('index.php/adminproducts/QrCodes');?>/'+id+'_'+pid);
}

</script>