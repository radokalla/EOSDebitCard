<div class="memberlogin-wps col-md-12 products_pagess">
  <h2>Create New Order</h2>
   <?php echo validation_errors(); ?>
  <div class="col-md-12">
    <form class="" method="post" action="<?php echo base_url();?>/index.php/adminorders/createorder" onsubmit="">
        
      <div class="form-group">
        <label class="col-md-4 patient_id" for="exampleInputEmail1">Patient Name</label>
        <div class="col-md-8 catogory_name">
         <input type="text" id="patientName"   class="text_input3 required">
		<input type="hidden" name="patientID" id="patientID" class="text_input3">
        </div>
      </div>
      
      <div id="show_subcategories"> </div>
      <div class="col-md-12" id="add_qr_cart_products">
       <?php if(isset($session['adminCart']) && (count($session['adminCart']) > 0)){?>
       
    <table class="table table-hover table-striped table_hd">
    <thead class="table_heading">
      <tr>
  
        <th width="65%">Product Name</th>
        <th>Quantity</th>
        <th>Unit Price</th>
    
        <th width="8%"></th>
      </tr>
    </thead>   
    <tbody><?php $cart_total_price = 0; foreach($session['adminCart'] as $cart_product_id => $cart_product_details){ ?>
    

      <tr>
       <td class="title_class"><?php echo $cart_product_details['categotyName'] ?> </br><span class="subtitle subtitle_color"><strong><?php echo $cart_product_details['productName'] ?></strong></span></td>
       
        <td class="aligncenter"><?php echo $cart_product_details['quantity'] ?></td>
        <td class="alignright"><?php echo $currency['symbol']; ?><?php echo $cart_product_details['quantity']*$cart_product_details['productPrice'] ?></td>
          <?php $cart_total_price += $cart_product_details['quantity']*$cart_product_details['productPrice']; ?>
        <td class="aligncenter"> <button type="button" data-toggle="tooltip" data-placement="top" title="Delete" class="glyphicon glyphicon-trash close close_buttons" data-product="<?php echo $cart_product_id; ?>"></button></td>
      </tr>
        <?php } ?>
        
        <tr>
       <td class="title_class" colspan="2">Sales Tax (<?php echo $tax_percentage."%"; ?>)</td>
       
       
        <td class="alignright"><?php echo $currency['symbol']; ?><?php echo $tax=($tax_percentage/100)*$cart_total_price; ?></td>
          <?php $cart_total_price+=$tax;?>
        <td class="aligncenter"> </td>
      </tr>
      
      <?php /*?><tr>
       <td class="title_class" colspan="2">Delivery charge</td>
       
       
        <td class="alignright"><?php echo $currency['symbol']; ?><?php echo $delivery_charge; ?></td>
          <?php $cart_total_price+=$delivery_charge;?>
        <td class="aligncenter"> </td>
      </tr><?php */?>
      
      
    </tbody>
 

  </table>
  

<div class="ct-total"> <?php echo $currency['symbol']; ?><?php echo $cart_total_price; ?></div>



     <?php /*?> <div class="ct-total total_amountprice"><?php echo $currency['symbol']; ?><?php echo $cart_total_price; ?></div><?php */?>
        <div class="form-group">
        <div class="button_class">
        
<div class="col-md-9" style="display:none;"><label>Delivery Type</label> <input type="radio" name="deliveryType" value="delivery" /> Delivery <input type="radio" name="deliveryType" value="pickup" checked="checked" /> Pick up</div>


<div class="col-md-9"><label>Payment Type</label> <input type="radio" name="paymentType" value="creditcard" checked="checked" /> CC-Paid <input type="radio" name="paymentType" value="cash" /> Cash</div>

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
							window.location.href = '<?php echo base_url('index.php/adminorders/samplecart'); ?>';
							//$(remove_tr).remove();
							//$("#add_qr_cart_products").html(data);
						}
					});
				}
			})
		});
		</script> 
      </div>
    </form>
  </div>
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
							value: item.patientID
						}
					}));
				}
			});
		},
		autoFocus: true,
		minLength: 0 ,
		selectFirst: true,
		focus: function( event, ui ) {
			$( "#patientName" ).val( ui.item.label );
			$( "#patientID" ).val( ui.item.value );	
			return false;
		},
		select: function( event, ui ) {
			$( "#patientName" ).val( ui.item.label );
			$( "#patientID" ).val( ui.item.value );			
			return false;
		}    	
	});
	
	
	
});
</script>

