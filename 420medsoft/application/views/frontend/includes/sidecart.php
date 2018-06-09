<?php $number_products = 0; if(isset($session['cart']) && (count($session['cart']) > 0)){?>
<?php foreach($session['cart'] as $cart_product_id => $cart_product_details){ ?>
<?php $number_products +=  $cart_product_details['quantity'] ?>
<?php } ?>
<?php } ?>

    <h2 class="htlte-org" id="carth2" ><i class="fa fa-shopping-cart"></i> Cart <?php echo $number_products; ?></h2>

    <div class="col-md-12 cr-sdcontent" id="show-inner-cart-items">
	<?php if(isset($session['cart']) && (count($session['cart']) > 0)){?>
<?php if(!isset($deliveryType)) { $deliveryType = 'delivery'; }  ?>
<div class="cart-row-min-height">
<?php $cart_total_price = 0; foreach($session['cart'] as $cart_product_id => $cart_product_details){ ?>

<div class="cart-row">
  <button type="button" class="close removeproduct" data-product="<?php echo $cart_product_id; ?>"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <h3 class="ct-hdtitle col-md-11"><?php echo $cart_product_details['categotyName'] ?> <span class="subtitle"><?php echo $cart_product_details['productName'] ?></span></h3>
  <span class="pull-left">Qty: <?php echo $cart_product_details['quantity'] ?></span> <span class="pull-right">Donations: <?php echo $currency['symbol']; ?><?php echo $cart_product_details['quantity']*$cart_product_details['productPrice'] ?></span> </div>
<?php $cart_total_price += $cart_product_details['quantity']*$cart_product_details['productPrice']; ?>
<?php } ?>
</div>


<div class="cart-row">
  <h3 class="ct-hdtitle col-md-9">Sales Tax (<?php echo $tax_percentage."%"; ?>)</h3>
  <span class="pull-left"></span> <span class="pull-right"><?php echo $currency['symbol']; ?><?php echo $tax=round((($tax_percentage/100)*$cart_total_price), 2); ?></span> </div>
  
  
<?php $cart_total_price+=$tax;?>

<?php if( (isset($session['deliveryType']) && ($session['deliveryType'] == 'pickup')) ){ ?>
<?php }else{ ?>
<div class="cart-row">
  <h3 class="ct-hdtitle col-md-9">Delivery charge<?php /*?><span class="subtitle"><?php echo $delivery_charge; ?></span><?php */?></h3>
  <span class="pull-left"></span> <span class="pull-right"><?php echo $currency['symbol']; ?><?php echo round($delivery_charge, 2); ?></span> </div>
<?php $cart_total_price+=$delivery_charge;?>
<?php } ?>
<div class="cart-row" style="display:none;">

  <label><strong>Delivery Type</strong></label>
  </br>
  <label for="deliveryType-delivery">
  <input type="radio" checked="checked" value="delivery" name="deliveryType" id="deliveryType-delivery">
  Delivery</label>
  <?php /*?><label for="deliveryType-pickup">
  <input type="radio" value="pickup" name="deliveryType" id="deliveryType-pickup" <?php echo (isset($session['deliveryType']) && ($session['deliveryType'] == 'pickup')) ? ' checked="checked"' : ''; ?>>
  Pick up</label><?php */?> </div>
 


<div class="cart-row">
  <label><strong>Payment Type</strong></label>
  </br>
 <?php if($qb_offline == 1){ ?>
  <label for="paymentType-ccpaid">
  <input type="radio"   value="creditcard" name="paymentType" id="paymentType-ccpaid">
  Credit card</label>
  <?php } ?>
  <label for="paymentType-cash">
<!--  <input type="radio" value="cash" name="paymentType" id="paymentType-cash" <?php echo (isset($session['paymentType']) && ($session['paymentType'] == 'cash')) ? ' checked="checked"' : ''; ?>>-->
 <input type="radio" value="cash" name="paymentType" id="paymentType-cash" checked />
  Cash</label> </div>
  
<div class="ct-total">Total <?php echo $currency['symbol']; ?><?php echo $cart_total_price; ?></div>
<?php if(isset($session['PATIENT_ID']) && !empty($session['PATIENT_ID'])){ ?>
<div class="col-md-12"><a class="btn default btn-primary btn-block btn-lg paynow" data-toggle="modal" href="#responsive" <?php echo (isset($session['paymentType']) && ($session['paymentType'] == 'cash')) ? ' style="display:none;"' : ''; ?>>Pay now</a>

  <button type="button" class="btn default btn-primary btn-block btn-lg paynow paynow-new"  <?php echo (isset($session['paymentType']) && ($session['paymentType'] == 'cash')) ? '' : ' style="display:none;"'; ?>>Pay now</button>
  <?php /*?><a href="javascript:" class="btn btn-primary btn-block btn-lg paynow">Pay now</a><?php */?>
</div>
<?php }else{ ?>
<div class="col-md-12"><a onclick="checkLogin();" class="btn btn-primary btn-block btn-lg">Pay now</a></div>
<?php } ?>
<?php }else{ ?>
<div class="cart-row">
  <h3 class="ct-hdtitle col-md-12">Cart is empty</h3>
</div>
<?php } ?>
<div id="responsive" class="modal fade" tabindex="-1" data-width="760">
  <form id="checkCreditcardDetails" class="form-horizontal">
    <div class="modal-header">
      <button type="button" class="close removeproduct" data-dismiss="modal" aria-hidden="true"></button>
      <h4 class="modal-title">Credit card details</h4>
    </div>
    <div class="modal-body">
      <div class="row">
      
      	<div class="main-loader" style="display:none;">
        	<img src="<?php echo base_url('images/input-spinner.gif'); ?>" />  <span>Credit Card is processing</span>
        </div>
        
        <div class="main-creditdetails">
        
        <div class="form-group">
          <div class="col-md-12" id="error-message"> </div>
        </div>
        <div class="form-group">
          <label for="nameOnCard" class="control-label col-sm-4 control_labeltext">Name as it appears on Card<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="nameOnCard" name="nameOnCard" placeholder="Name as it appears on Card" value="<?php echo isset($patientCreditCardDetails['nameOnCard']) ? $patientCreditCardDetails['nameOnCard'] : ''; ?>" required="required">
          </div>
        </div>
        <?php /*?><div class="form-group">
          <label for="cardType" class="control-label col-sm-4 control_labeltext">Credit Card Type<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <select class="form-control" id="cardType" name="cardType" required="required">
              <option value="">Please select</option>
              <option value="Visa">Visa</option>
              <option value="MasterCard">MasterCard</option>
              <option value="American Express">American Express</option>
            </select>
          </div>
        </div><?php */?>
        <div class="form-group">
          <label for="cardNumber" class="control-label col-sm-4 control_labeltext">Credit Card Number<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="cardNumber" name="cardNumber" value="<?php echo isset($patientCreditCardDetails['cardNumber']) ? $patientCreditCardDetails['cardNumber'] : ''; ?>" placeholder="Credit Card Number" >
          </div>
        </div>
        <div class="form-group">
          <label for="CVVCode" class="control-label col-sm-4 control_labeltext">CVC Code<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="CVVCode" name="CVVCode" value="<?php echo isset($patientCreditCardDetails['CVVCode']) ? $patientCreditCardDetails['CVVCode'] : ''; ?>" placeholder="CVC Code" >
          </div>
        </div>
        <div class="form-group">
          <label for="expiry" class="control-label col-sm-4 control_labeltext">Expiration Month<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <select id="expiry_month" name="expiry_month" >
            <?php for($year = 1; $year <= 12; $year++){ ?>
            	<option value="<?php echo $year; ?>" <?php echo isset($patientCreditCardDetails['expiry_month']) && ($patientCreditCardDetails['expiry_month'] == $year) ? ' selected="selected"' : ''; ?>><?php echo $year; ?></option>
                <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="expiry" class="control-label col-sm-4 control_labeltext">Expiration Year<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <select id="expiry_year" name="expiry_year" >
            <?php for($year = date("Y"); $year <= date("Y")+20; $year++){ ?>
            	<option value="<?php echo $year; ?>" <?php echo isset($patientCreditCardDetails['expiry_year']) && ($patientCreditCardDetails['expiry_year'] == $year) ? ' selected="selected"' : ''; ?>><?php echo $year; ?></option>
                <?php } ?>
            </select>
          </div>
        </div>
        <?php if(!isset($patientCreditCardDetails['cardNumber']) || empty($patientCreditCardDetails['cardNumber'])){ ?>
        <div class="form-group">
          <label for="expiry" class="control-label col-sm-4 control_labeltext">Save Creditcard details : </label>
          <div class="col-sm-6"> 
            <input type="checkbox" name="save_details" value="Yes" />
          </div>
        </div>
        <?php }else{ ?>
        <input type="hidden" name="save_details" value="Yes" />
        <?php } ?>
      </div>
      
      </div>
      
      
    </div>
    <div class="modal-footer">
      <button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
      <button type="button" class="btn blue paynow">Pay</button>
    </div>
  </form>
</div>
<link href="<?php echo base_url("js/bootstrap-modal/css/bootstrap-modal-bs3patch.css");?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url("js/bootstrap-modal/css/bootstrap-modal.css");?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("js/bootstrap-modal/js/bootstrap-modalmanager.js");?>" type="text/javascript" ></script> 
<script src="<?php echo base_url("js/bootstrap-modal/js/bootstrap-modal.js");?>" type="text/javascript" ></script> 
<script src="<?php echo base_url("js/ui-extended-modals.js");?>"></script> 
<script type="text/javascript">

function checkLogin()
{
	alert("Please log in or register.");
}

$(document).ready(function(){
	$("button.removeproduct").on('click', function(){
		if(confirm("Do you want to remove product?"))
		{
			var product = $(this).data("product");
			var dataString = "product="+product;
			$.ajax({
				type: "POST",
				url: '<?php echo base_url('index.php/main/removecart'); ?>',
				data: dataString,
				success: function (data) {
					$("#show-cart-items").html(data);
				}
			});
		}
	});	var paymentType = $('input:radio[name=paymentType]:checked').val();
	if(paymentType == 'cash')
		{
			$("button.paynow-new").show();
			$("a.paynow").hide();
		}
		else
		{
			$("button.paynow-new").hide();
			$("a.paynow").show();
		}
	$('input:radio[name=deliveryType]').change(function(){
		var deliveryType = $('input:radio[name=deliveryType]:checked').val();
		var dataString = "deliveryType="+deliveryType;
		$.ajax({
			type: "POST",
			url: '<?php echo base_url('index.php/main/addDeliveryType'); ?>',
			data: dataString,
			success: function (data) {
				$("#show-cart-items").html(data);
			}
		});		
	});
	
	$('input:radio[name=paymentType]').change(function(){
		var paymentType = $('input:radio[name=paymentType]:checked').val();
		var dataString = "paymentType="+paymentType;
		$.ajax({
			type: "POST",
			url: '<?php echo base_url('index.php/main/addPaymentType'); ?>',
			data: dataString,
			success: function (data) {
				$("#show-cart-items").html(data);
			}
		});		
	});
	//$('input:radio[name=paymentType]').trigger('change');
	$('input:radio[name=paymentType]').change(function(){
		var paymentType = $('input:radio[name=paymentType]:checked').val();
		if(paymentType == 'cash')
		{
			$("button.paynow-new").show();
			$("a.paynow").hide();
		}
		else
		{
			$("button.paynow-new").hide();
			$("a.paynow").show();
		}
	});
	
	$("button.paynow").on('click', function(){
		
		$(".main-creditdetails").hide();
		$(".main-loader").show();
		
		var paymentType = $('input:radio[name=paymentType]:checked').val();
		
		if(paymentType == 'cash')
		{
			var deliveryType = $('input:radio[name=deliveryType]:checked').val();
			location.href = '<?php echo base_url("index.php/checkout/addorder") ?>/'+deliveryType+'/'+paymentType;
		}
		else
		{
			var dataString = $("form#checkCreditcardDetails").serialize();
			$.ajax({
				type: "POST",
				url: '<?php echo base_url("index.php/checkout/checkCreditCardDetails") ?>',
				data: dataString,
				success: function (data) {
					var obj = jQuery.parseJSON( data );
					if(obj.success == true)
					{
						var deliveryType = $('input:radio[name=deliveryType]:checked').val();
						location.href = '<?php echo base_url("index.php/checkout/addorder") ?>/'+deliveryType+'/'+paymentType;
					}
					else
					{
						$("#error-message").html(obj.message);
						$(".main-creditdetails").show();
						$(".main-loader").hide();
					}
				}
			});
		}
		
	});
	
	<?php /*?>$("a.paynow").on('click', function(){
		var deliveryType = $('input:radio[name=deliveryType]:checked').val();
		location.href = '<?php echo base_url("index.php/checkout/addorder") ?>/'+deliveryType;
	});<?php */?>
	
	$('#CartDivmob h2').click(function(e) { 
		$( "#show-inner-cart-items" ).toggle( "slow", function() {
		});
	});
	
});
</script>
</div>