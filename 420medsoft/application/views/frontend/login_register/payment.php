<script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>

<div class="row">
<div class="general-page text-center clearfix">
<div class="col-md-12">
<form method="post" id="payment_form" name="payment_form" class="xform" action="">
<div class="row">
  <div class="col-md-12">
    <div class="general-page">
      <div class="col-md-12">
        <h3 class="hdtitle-h3">Payment Details</h3>
      </div>
      <div class="row pdleftright15">
      <?php if(isset($errorMessage) && !empty($errorMessage)){?><div class="col-md-12 error-message"><?php echo $errorMessage;?></div><?php } ?>
      
        <div class="col-md-12">
          <div class="field-wps-rd">
            <div class="col-md-5">
              <label class="label-spws"> User ID </label>
            </div>
            <div class="col-md-6 sdsread" style="text-align:left;"><?php echo $subscriberDetails['email']; ?></div>
          </div>
          <div class="field-wps-rd">
            <div class="col-md-5">
              <label class="label-spws"> Subscription      Patient & Admin Websites </label>
            </div>
            <div class="col-md-6 sdsread"  style="text-align:left;"><?php echo $packages['PackageName']; ?></div>
          </div>
          <?php /*?>
          <div class="field-wps-rd">
            <div class="col-md-5">
              <label class="label-spws">Price</label>
            </div>
             <div class="col-md-6 sdsread"><?php secho $packages['CurrencySymbol'].number_format($packages['Cost'],2); ?></div>
          </div>
         <div class="field-wps-rd">
            <div class="col-md-3">
              <label class="label-spws">Discounts/Coupons</label>
            </div>
            <div class="col-md-6 sdsread"><?php echo $packages['CurrencySymbol'].number_format(0,2); ?></div>
          </div>
          <div class="field-wps-rd">
            <div class="col-md-3">
              <label class="label-spws">Vat/Tax</label>
            </div>
            <div class="col-md-6 sdsread"  style="text-align:left;"><?php echo $packages['CurrencySymbol'].number_format(0,2); ?></div>
          </div>
          <?php */?>
          <div class="field-wps-rd">
            <div class="col-md-5">
              <label class="label-spws">Total </label>
            </div>
            <div class="col-md-6 sdsread"  style="text-align:left;"><?php echo $packages['CurrencySymbol'].number_format($packages['Cost'],2); ?></div>
          </div>
          
          <div class="field-wps-rd">
            <div class="col-md-5">
              <label class="label-spws"> Name on Card<strong class="star">*</strong></label>
            </div>
            <div class="col-md-6 sdsread"  style="text-align:left;"><input type="text" class="form-control" id="nameOnCard" name="nameOnCard" placeholder=" Name on Card" value="<?php echo isset($patientCreditCardDetails['nameOnCard']) ? $patientCreditCardDetails['nameOnCard'] : ''; ?>" required="required"></div>
          </div>
          
          
          
          <div class="field-wps-rd">
            <div class="col-md-5">
              <label class="label-spws">Credit Card Number<strong class="star">*</strong></label>
            </div>
            <div class="col-md-6 sdsread"> <input type="text" class="form-control" id="cardNumber" name="cardNumber" value="<?php echo isset($patientCreditCardDetails['cardNumber']) ? $patientCreditCardDetails['cardNumber'] : ''; ?>" placeholder="Credit Card Number" ></div>
          </div>
          
          
          <div class="field-wps-rd">
            <div class="col-md-5">
              <label class="label-spws">CVC Code<strong class="star">*</strong></label>
            </div>
            <div class="col-md-6 sdsread"> <input type="text" class="form-control" id="CVVCode" name="CVVCode" value="<?php echo isset($patientCreditCardDetails['CVVCode']) ? $patientCreditCardDetails['CVVCode'] : ''; ?>" placeholder="CVC Code" ></div>
          </div>
          
          
          <div class="field-wps-rd">
            <div class="col-md-5">
              <label class="label-spws">Expiration Month<strong class="star">*</strong></label>
            </div>
            <div class="col-md-6 sdsread" style="text-align:left;"><select id="expiry_month" name="expiry_month" >
            <?php for($year = 1; $year <= 12; $year++){ ?>
            	<option value="<?php echo $year; ?>" <?php echo isset($patientCreditCardDetails['expiry_month']) && ($patientCreditCardDetails['expiry_month'] == $year) ? ' selected="selected"' : ''; ?>><?php echo $year; ?></option>
                <?php } ?>
            </select></div>
          </div>
          
          
          <div class="field-wps-rd">
            <div class="col-md-5">
              <label class="label-spws">Expiration Year<strong class="star">*</strong></label>
            </div>
            <div class="col-md-6 sdsread"  style="text-align:left;"><select id="expiry_year" name="expiry_year" >
            <?php for($year = date("Y"); $year <= date("Y")+20; $year++){ ?>
            	<option value="<?php echo $year; ?>" <?php echo isset($patientCreditCardDetails['expiry_year']) && ($patientCreditCardDetails['expiry_year'] == $year) ? ' selected="selected"' : ''; ?>><?php echo $year; ?></option>
                <?php } ?>
            </select></div>
          </div>
        
        <div class="field-wps-rd rmv-borbottm">
            <?php /*?><div class="col-md-3">
              <label class="label-spws">Pay With</label>
            </div>
            <div class="col-md-6">
              <label class="radio">
                <input type="radio" value="paypal">
                PayPal</label>
            </div><?php */?>
            <div class="col-md-12">
              <button type="submit" name="dosubmit1" class="button" onclick="return reg()">Submit</button>
              <!-- <a href="#" class="sub-btn" class="button" onclick="return fn_payment()">Submit</a>--> 
            </div>
          </div>
          
        
        </div>
      </div>
    </div>
  </div>
</div>
