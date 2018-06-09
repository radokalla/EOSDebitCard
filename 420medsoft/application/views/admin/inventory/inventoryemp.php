<style type="text/css">
.div_categories_block {
    border-top: 1px solid #dedede;
    clear: both;
    padding-bottom: 10px;
    padding-top: 20px;
    position: relative;
    width: 100%;
}
</style>
<form class="validate-form" method="post">
  <div class="memberlogin-wps col-md-12 products_page">
    <h2>Products Inventory  </h2>
    <div class="col-md-12">
      <div class="form-group">
        <label class="col-md-4 patient_id" for="exampleInputEmail1">Employee Name</label>
        <div class="col-md-8 catogory_name"> 
          <!--<input type="text" id="employeeName" required="required" class="text_input3">-->
          <select name="employeeName" id="employeeName" class="text_input3 required">
            <option value="">Select Employee</option>
            <?php foreach($employeeDetails as $employeeDetail){ ?>
            <option value="<?php echo $employeeDetail['ID']; ?>"><?php echo $employeeDetail['firstName'].$employeeDetail['lastName']; ?></option>
            <?php } ?>
          </select>
          <input type="hidden" name="employeeID" id="employeeID" class="text_input3">
    <a href="javascript:" class="btn btn-primary  pull-right" onClick="addCategory();">Add more</a>
        </div>
      </div>
    </div>
    
    <input type="hidden" id="total_products" value="0" />
    
    
    <div class="all_categories">
    
    <div class="div_categories_block" id="div_categories_0">
    <div class="col-md-12">
      <div class="form-group">
        <label class="col-md-4" for="exampleInputEmail1">Category Name</label>
        <div class="col-md-8">
          <select name="request[0][parentID]" id="parent_id_0" rel="0" class="text_input3 required">
            <option value=""> -- Select -- </option>
            <?php foreach($categories as $mainCategoryID => $mainCategoryDetails){ ?>
            <option value="<?php echo $mainCategoryID; ?>"><?php echo $mainCategoryDetails['categoryName']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
    </div>
    <div id="show_subcategories_0"> </div>
    <hr>
   </div>
   
   
   
   </div>
    
   <div class="form-group">
    <div class="col-md-8">
      <button type="submit" class="btn btn-primary category_button pull-right">Save Inventory</button>
    </div>
  </div>
    
  </div>
</form>
<script type="text/javascript">



$(document).ready(function(){
	$("#employeeName").change(function(){
		$("#employeeID").val($(this).val());
	});
	
	$( document ).on("change", '[id^=parent_id_]', function(){
		var parent_id = $(this).val();
		var rel = $(this).attr("rel");
		var dataString = "parentID="+parent_id+"&rel="+rel;
		$.ajax({
			type: "POST",
			url: '<?php echo base_url('index.php/adminempinventory/getAllSubcategories'); ?>',
			data: dataString,
			success: function (data) {
				$("#show_subcategories_"+rel).html(data);
			}
		});
    });
	
	$( document ).on("change", '[id^=iproduct_id_]', function(){
		var productID = $(this).val();
		var rel = $(this).attr("rel");
		var dataString = "productID="+productID;
		$.ajax({
			type: "POST",
			url: '<?php echo base_url('index.php/admininventory/getRemaining'); ?>',
			data: dataString,
			success: function (data) {
				$('#exampleInputRemaining_'+rel).html(data);
				$('#inventory_'+rel).attr('data-max',data.trim());
			}
		});
	});

	/*$("#product_id").on('change',function(){
		var productID = $(this).val();
		var dataString = "productID="+productID;
		$.ajax({
			type: "POST",
			url: '<?php echo base_url('index.php/admininventory/getRemaining'); ?>',
			data: dataString,
			success: function (data) {
				alert(JSON.stringify(data));
			}
		});
    });*/
	
});
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script> 
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">
<script>
$(function() {
	
	// $('#employeeName').autocomplete({
		// source: function( request, response ) {
			// $.ajax({
				// url : '<?php echo base_url('index.php/adminempinventory/getEmployeeDetails'); ?>',
				// dataType: "json",
				// data: {
				   // name_startsWith: request.term
				// },
				 // success: function( data ) {
					 // response( $.map( data, function( item ) {
						// return {
							// label: item.employeeName,
							// value: item.employeeID
						// }
					// }));
				// }
			// });
		// },
		// autoFocus: true,
		// minLength: 0 ,
		// selectFirst: true,
		// focus: function( event, ui ) {
			// $( "#employeeName" ).val( ui.item.label );
			// $( "#employeeID" ).val( ui.item.value );	
			// return false;
		// },
		// select: function( event, ui ) {
			// $( "#employeeName" ).val( ui.item.label );
			// $( "#employeeID" ).val( ui.item.value );			
			// return false;
		// }    	
	// });
	 
		 
		 
		 
	
});

function addCategory()
{
	var total_products = $("#total_products").val();
	total_products++;
	
	var html = '';
	html += '<div class="div_categories_block" id="div_categories_'+total_products+'">';
	html += '<div class="col-md-12">';
	html += '<div class="form-group">';
	html += '<label class="col-md-4" for="exampleInputEmail1">Category Name</label>';
	html += '<div class="col-md-8">';
	html += '<select name="request['+total_products+'][parentID]" id="parent_id_'+total_products+'" rel="'+total_products+'" class="text_input3 required">';
	html += '<option value=""> -- Select -- </option>';
	<?php foreach($categories as $mainCategoryID => $mainCategoryDetails){ ?>
	html += '<option value="<?php echo $mainCategoryID; ?>"><?php echo $mainCategoryDetails['categoryName']; ?></option>';
	<?php } ?>
	html += '</select>';
	html += '</div>';
	html += '</div>';
	html += '</div>';
	html += '<div id="show_subcategories_'+total_products+'"> </div><hr>';
	html += '</div>';
	
	$(".all_categories").append(html);
	$("#total_products").val(total_products);
}
</script>