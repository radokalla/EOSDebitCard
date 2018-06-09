<div class="products_design"><?php if(isset($session['adminCart']) && (count($session['adminCart']) > 0)){?>
<?php $cart_total_price = 0; foreach($session['adminCart'] as $cart_product_id => $cart_product_details){ ?>
<div class="cart-row  border-bottom col-md-12">
  <button type="button" class="glyphicon glyphicon-trash close" data-product="<?php echo $cart_product_id; ?>"></button>
  <h3 class="ct-hdtitle col-md-11"><?php echo $cart_product_details['categotyName'] ?> <span class="subtitle"><strong><?php echo $cart_product_details['productName'] ?></strong></span></h3>
  <span class="pull-left">Qty:<strong> <?php echo $cart_product_details['quantity'] ?></strong></span> <span class="pull-right">Price:<strong> <?php echo $currency['symbol']; ?><?php echo $cart_product_details['quantity']*$cart_product_details['productPrice'] ?></strong></span> </div>
  <?php $cart_total_price += $cart_product_details['quantity']*$cart_product_details['productPrice']; ?>
<?php } ?>

<div class="ct-total">Total <?php echo $currency['symbol']; ?><?php echo $cart_total_price; ?></div>
 <div class="form-group">
<div class="col-md-9"><label>Delivery Type</label> <input type="radio" name="deliveryType" value="delivery" checked="checked" /> Delivery <input type="radio" name="deliveryType" value="pickup" /> Pick up</div>
 <div class="col-md-3 paynow_button"><button type="submit" class="btn btn-primary btn-block btn-lg">Pay now</button></div>

<?php } ?>
</div>
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

