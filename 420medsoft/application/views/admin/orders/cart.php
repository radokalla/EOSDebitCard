<?php if(isset($session['adminCart']) && (count($session['adminCart']) > 0)){?>

<div class="cart-row-min-height">

<?php $cart_total_price = 0; foreach($session['adminCart'] as $cart_product_id => $cart_product_details){ ?>

<div class="cart-row">

  <button type="button" class="close" data-product="<?php echo $cart_product_id; ?>"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

  <h3 class="ct-hdtitle col-md-11"><?php echo $cart_product_details['categotyName'] ?> <span class="subtitle"><?php echo $cart_product_details['productName'] ?></span></h3>

  <span class="pull-left">Qty: <?php echo $cart_product_details['quantity'] ?></span> <span class="pull-right">Donations: <?php echo $currency['symbol']; ?><?php echo $cart_product_details['quantity']*$cart_product_details['productPrice'] ?></span> </div>

  <?php $cart_total_price+= ($cart_product_details['quantity']*$cart_product_details['productPrice']);} ?>

</div>

<div class="cart-row">

  

  <h3 class="ct-hdtitle col-md-9">Sales Tax (<?php echo $tax_percentage."%"; ?>)</h3>

  <span class="pull-left"></span> <span class="pull-right"><?php echo $currency['symbol']; ?><?php echo $tax=round((($tax_percentage/100)*$cart_total_price), 2); ?></span> </div>





<?php $cart_total_price+=$tax;?>

  

<?php /*?><div class="cart-row">

  <h3 class="ct-hdtitle col-md-11">Delivery charge<span class="subtitle"><?php echo $delivery_charge; ?></span></h3>

  <span class="pull-left"></span> <span class="pull-right"><?php echo $currency['symbol']; ?><?php echo $delivery_charge; ?></span> </div>

<?php $cart_total_price+=$delivery_charge;?><?php */?>



<div class="ct-total"> <?php echo $currency['symbol']; ?><?php echo $cart_total_price; ?></div>

 <div class="form-group"> <div class="button_class">

<div class="col-md-12" style="display:none;"><div>Delivery Type</div>

<div><input type="radio" name="deliveryType" value="delivery"/> Delivery</div> 

<div><input type="radio" name="deliveryType" value="pickup" checked="checked"  /> Pick up</div></div>



<div class="col-md-12"><div>Payment Type</div>
<?php if($qb_offline == 1){ ?>
<div><input type="radio" name="paymentType" value="creditcard" /> Credit card</div> 
<?php }?>
<div><input type="radio" name="paymentType" value="cash" checked="checked" /> Cash</div></div>


<div class="col-md-12"><a class="btn default btn-primary btn-block btn-lg paynow" data-toggle="modal" href="#responsive" style="display:none;">Pay now</a>

  <button type="button" class="btn default btn-primary btn-block btn-lg paynow paynow-new">Pay now</button>
  <?php /*?><a href="javascript:" class="btn btn-primary btn-block btn-lg paynow">Pay now</a><?php */?>
</div>
<!-- <div class="col-md-12 paynow_button"><button type="submit" class="btn btn-primary btn-block btn-lg paynow">Pay now</button></div>-->

</div></div>





<?php }else{ ?>
<div class="col-md-12 col-sm-12">
Cart is empty.
</div>
<?php } ?>
<!-- Credicard-->
<div id="responsive" class="modal fade" tabindex="-1" data-width="760">
  <form id="checkCreditcardDetails" class="form-horizontal">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
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

	});

	

	$("button.paynow").on('click', function(){

		var patientID = $("#patientID").val();

		if( patientID == '' || patientID == null)

		{

			alert("Please fill patient name.");

			$("#patientID").focus();

			return false;

		}

		else

		{

			var deliveryType = $( "input[name=deliveryType]:checked" ).val();

			var paymentType = $( "input[name=paymentType]:checked" ).val();

			var dataString = "patientID="+patientID+"&deliveryType="+deliveryType+"&paymentType="+paymentType;
			if(paymentType == 'cash')
			{
				$.ajax({
	
					type: "POST",
	
					url: '<?php echo base_url('index.php/adminorders/createorder'); ?>',
	
					data: dataString,
	
					success: function (data) {
	
						//$("#add_cart_products").html(data);
	
						window.location='<?php echo base_url('index.php/adminorders/orders'); ?>';
	
					}
	
				});
			}
			else {
				var dataString = $("form#checkCreditcardDetails").serialize();
			$.ajax({
				type: "POST",
				url: '<?php echo base_url("index.php/adminorders/checkCreditCardDetails") ?>',
				data: dataString,
				success: function (data) {
					var obj = jQuery.parseJSON( data );
					if(obj.success == true)
					{
						var dataString = "patientID="+patientID+"&deliveryType="+deliveryType+"&paymentType="+paymentType;
						$.ajax({
							type: "POST",
							url: '<?php echo base_url('index.php/adminorders/createorder'); ?>',
							data: dataString,
							success: function (data) {
								if(data != 1)
								$("#error-message").html(data);
								else
								//$("#add_cart_products").html(data);
								window.location='<?php echo base_url('index.php/adminorders/orders'); ?>';
							}
						});
					}
					else
					{
						$("#error-message").html(obj.message);
					}
				}
			});
			}
		}

	})
	$('input:radio[name=paymentType]').trigger('change');
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
});

</script>



