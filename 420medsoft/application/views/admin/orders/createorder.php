
<div class="memberlogin-wps col-md-12 products_pagess">
  <h2>Create New Order</h2>
  <div class="col-md-12">
    <form class="validate-form" method="post">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group" id="patientMessage"> </div>
        </div>
      </div>
      <?php if(!isset($orderDetails['patientID']) || !isset($orderDetails['orderID'])){ ?>
	  <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label class="col-md-4 patient_id" for="exampleInputEmail1">Patient Name</label>
            <div class="col-md-8 catogory_name">
              <input type="text" id="patientName" required="required" class="text_input3">
              <input type="hidden" name="patientID" id="patientID" class="text_input3">
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group" style="display:none;">
            <label class="col-md-4 patient_id" for="exampleInputEmail1">Patient Notes</label>
            <div class="col-md-8 catogory_name" id="patientNotes"></div>
          </div>
        </div>
      </div>
	  <?php }else{ ?>
      <input type="hidden" name="patientID" id="patientID" value="<?php echo $orderDetails['patientID']; ?>" class="text_input3">
      <input type="hidden" name="orderID" id="orderID" value="<?php echo $orderDetails['orderID']; ?>" class="text_input3">
      <?php } ?>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label class="col-md-4 patient_id" for="exampleInputEmail1">Category Name</label>
            <div class="col-md-8 catogory_name">
              <?php /*?>
         <input type="text" id="categoryName" class="text_input3">
		<input type="hidden" name="parentID" id="categoryID" class="text_input3"><?php */?>
              <select name="parentID" id="categoryID" class="text_input3">
                <option value="all"> -- Select -- </option>
                <?php foreach($categories as $mainCategoryID => $mainCategoryDetails){ ?>
                <option value="<?php echo $mainCategoryID; ?>"><?php echo $mainCategoryDetails['categoryName']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label class="col-md-4 patient_id" for="exampleInputEmail1">Product Type</label>
            <div class="col-md-8 catogory_name">
              <select name="productType" id="productType" class="text_input3">
                <option value=""> -- Select -- </option>
                <option value="Indica">Indica</option>
                <option value="Sativa">Sativa</option>
                <option value="Hybrid">Hybrid</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label class="col-md-4 patient_id" for="exampleInputEmail1">Product Name</label>
            <div class="col-md-8 catogory_name">
              <input type="text" id="categoryName" class="text_input3">
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <div class="col-md-10"></div>
            <div class="col-md-2 catogory_name">
              <button type="button" class="btn btn-primary category_button" id="search-cat">Search</button>
            </div>
          </div>
        </div>
      </div>
      <div id="show_subcategories"> </div>
      <?php /*?><div class="col-md-12" id="add_cart_products">
       <?php if(isset($session['adminCart']) && (count($session['adminCart']) > 0)){?>
       
    <table class="table table-hover table-striped table_hd">
    <thead class="table_heading">
      <tr>
  
        <th width="65%">Product Name</th>
        <th>Quantity</th>
        <th>Unit Price</th>
    
        <th width="8%"></th>
      </tr>
    </thead>   <?php $cart_total_price = 0; foreach($session['adminCart'] as $cart_product_id => $cart_product_details){ ?>
    <tbody>

      <tr>
       <td class="title_class"><?php echo $cart_product_details['categotyName'] ?> </br><span class="subtitle subtitle_color"><strong><?php echo $cart_product_details['productName'] ?></strong></span></td>
       
        <td class="aligncenter"><?php echo $cart_product_details['quantity'] ?></td>
        <td class="alignright"><?php echo $currency['symbol']; ?><?php echo $cart_product_details['quantity']*$cart_product_details['productPrice'] ?></td>
          <?php $cart_total_price += $cart_product_details['quantity']*$cart_product_details['productPrice']; ?>
        <td class="aligncenter"> <button type="button" data-toggle="tooltip" data-placement="top" title="Delete" class="glyphicon glyphicon-trash close close_buttons" data-product="<?php echo $cart_product_id; ?>"></button></td>
      </tr>
    </tbody>
        <?php } ?>
 

  </table>
  
      

  
      <div class="ct-total total_amountprice"><?php echo $currency['symbol']; ?><?php echo $cart_total_price; ?></div>
        <div class="form-group">
        <div class="button_class">
<div class="col-md-9"><label>Delivery Type</label> <input type="radio" name="deliveryType" value="delivery" checked="checked" /> Delivery <input type="radio" name="deliveryType" value="pickup" /> Pick up</div>
          <div class="col-md-3 paynow_button">
            <button type="submit" class="btn btn-primary btn-block btn-lg">Pay now</button>
          </div></div>
       
        <?php } ?> </div>
        <script type="text/javascript">
		$(document).ready(function(){
			$("button.close").on('click', function(){
				if(confirm("Do you want to remove product?"))
				{
					var product = $(this).data("product");
					var dataString = "product="+product;
					$.ajax({
						type: "POST",
						url: '<?php echo base_url('index.php/adminorders/removecart'); ?>',
						data: dataString,
						success: function (data) {
							$("#add_cart_products").html(data);
						}
					});
				}
			})
		});
		</script> 
      </div><?php */?>
    </form>
  </div>
  <script type="text/javascript">
	$(document).ready(function(){
		$("#categoryID").change(function(){
			var parent_id = $(this).val();
			var dataString = "parentID="+parent_id;
			$.ajax({
				type: "POST",
				url: '<?php echo base_url('index.php/adminorders/getAllSubcategories'); ?>',
				data: dataString,
				success: function (data) {
					$("#show_subcategories").html(data);
				
				}
			});
		});
	});
	</script> 
  <script type="text/javascript">
	$(document).ready(function(){
		$("#search-cat").click(function(){
			var parent_id = $("#categoryID").val();
			var categoryName = $("#categoryName").val();
			var productType = $("#productType").val();
			var dataString = "parentID="+parent_id+"&categoryName="+categoryName+"&productType="+productType;
			$.ajax({
				type: "POST",
				url: '<?php echo base_url('index.php/adminorders/getAllSubcategoriesWithSearch'); ?>',
				data: dataString,
				success: function (data) {
					$("#show_subcategories").html(data);
				
				}
			});
		});
	});
	</script> 
</div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script> 
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">
<script>
$(function() {
	
	$('#patientName').autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : '<?php echo base_url('index.php/adminorders/getPatientDetails'); ?>',
				dataType: "json",
				data: {
				   name_startsWith: request.term
				},
				 success: function( data ) {
					 response( $.map( data, function( item ) {
						return {
							label: item.patientName,
							notes: item.patientNotes,
							value: item.patientID,
							message: item.message
						}
					}));
				}
			});
		},
		autoFocus: true,
		minLength: 0 ,
		/*selectFirst: true,
		focus: function( event, ui ) {
			$( "#patientName" ).val( ui.item.label );
			$( "#patientID" ).val( ui.item.value );	
			return false;
		},*/
		select: function( event, ui ) {
			$( "#patientName" ).val( ui.item.label );
			$( "#patientNotes" ).html( ui.item.notes );
			$( "#patientMessage" ).html( ui.item.message );
			$( "#patientID" ).val( ui.item.value );	
			$( "#patientNotes" ).closest(".form-group").show();		
			return false;
		}    	
	});
	
	
	<?php /*?>$('#categoryName').autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : '<?php echo base_url('index.php/adminorders/getCategoryDetails'); ?>',
				dataType: "json",
				data: {
				   name_startsWith: request.term
				},
				 success: function( data ) {
					 response( $.map( data, function( item ) {
						return {
							label: item.categoryName,
							value: item.categoryID
						}
					}));
				}
			});
		},
		autoFocus: false,
		minLength: 0 ,
		selectFirst: false,
		focus: function( event, ui ) {
			$( "#categoryName" ).val( ui.item.label );
			$( "#categoryID" ).val( ui.item.value );		
			
			var parent_id = $("#categoryID").val();
			var dataString = "parentID="+parent_id;
			$.ajax({
				type: "POST",
				url: '<?php echo base_url('index.php/adminorders/getAllSubcategories'); ?>',
				data: dataString,
				success: function (data) {
					$("#show_subcategories").html(data);
				}
			});
			return false;
		},
		select: function( event, ui ) {
			$( "#categoryName" ).val( ui.item.label );
			$( "#categoryID" ).val( ui.item.value );	
			
			var parent_id = $("#categoryID").val();
			var dataString = "parentID="+parent_id;
			$.ajax({
				type: "POST",
				url: '<?php echo base_url('index.php/adminorders/getAllSubcategories'); ?>',
				data: dataString,
				success: function (data) {
					$("#show_subcategories").html(data);
				}
			});
					
			return false;
		}    	
	});<?php */?>
	
});
</script>