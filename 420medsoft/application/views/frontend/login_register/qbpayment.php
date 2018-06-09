<script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
<link href="http://420medsoft.com/css/bootstrap.min.css" rel="stylesheet">
<link href="http://420medsoft.com/css/bootstrap.css" rel="stylesheet">

<div class="col-md-6">
	<div class="general-page text-center clearfix">
		<style type="text/css">
			 
			.col-md-5 {
				text-align: left;
			}

						.col-md-6.sdsread {
				text-align: left;
			}
			.error-message{
				color: red;
			}
			.hdtitle-h3{
				background-color: #00ff58;
			}
			
		</style>
		<form method="post" id="payment_form" name="payment_form" class="xform" action="http://420medsoft.com/index.php/main/subscribeQbPayment/<?php echo $subscriberDetails['DomainName'];?>">
			<div class="row">
				<div class="col-md-12">
					<div class="general-page">
						<div class="col-md-12" style="background-color:#00ff58;">
							<h3 class="hdtitle-h3" style="background-color:#00ff58;">Payment Details</h3>
						</div>
						<div class="row pdleftright15">
							<?php if(isset($errorMessage) && !empty($errorMessage)){?>
							<div class="col-md-12 error-message">
								<?php echo $errorMessage;?>
							</div>
							<?php } ?>

							  <div class="col-md-12">
          <div class="field-wps-rd">
            <div class="col-md-5">
              <label class="label-spws"> User ID </label>
            </div>
            <div class="col-md-6 sdsread" ><?php echo $subscriberDetails['email']; ?></div>
          </div>
          
          <div class="field-wps-rd">
            <div class="col-md-5">
              <label class="label-spws">Total </label>
            </div>
            <div class="col-md-6 sdsread"  ><?php echo $packages['CurrencySymbol'].number_format("200",2); ?></div>
          </div>

								<div class="field-wps-rd">
									<div class="col-md-5">
										<label class="label-spws"> Name on Card<strong class="star">*</strong></label>
									</div>
									<div class="col-md-6 sdsread"><input type="text" class="form-control" id="nameOnCard" name="nameOnCard" placeholder=" Name on Card" value="<?php echo isset($patientCreditCardDetails['nameOnCard']) ? $patientCreditCardDetails['nameOnCard'] : ''; ?>" required="required">
									</div>
								</div>



								<div class="field-wps-rd">
									<div class="col-md-5">
										<label class="label-spws">Credit Card Number<strong class="star">*</strong></label>
									</div>
									<div class="col-md-6 sdsread"> <input type="text" class="form-control" id="cardNumber" name="cardNumber" value="<?php echo isset($patientCreditCardDetails['cardNumber']) ? $patientCreditCardDetails['cardNumber'] : ''; ?>" placeholder="Credit Card Number">
									</div>
								</div>


								<div class="field-wps-rd" style="width: 100%">
									<div class="col-md-5">
										<label class="label-spws">CVC Code<strong class="star">*</strong></label>
									</div>
									<div class="col-md-6 sdsread"> <input type="text" class="form-control" id="CVVCode" name="CVVCode" value="<?php echo isset($patientCreditCardDetails['CVVCode']) ? $patientCreditCardDetails['CVVCode'] : ''; ?>" placeholder="CVC Code">
									</div>
								</div>


								<div class="field-wps-rd">
									<div class="col-md-5">
										<label class="label-spws">Expiration Month<strong class="star">*</strong></label>
									</div>
									<div class="col-md-6 sdsread">
										<select id="expiry_month" name="expiry_month">
											<?php for($year = 1; $year <= 12; $year++){ ?>
											<option value="<?php echo $year; ?>" <?php echo isset($patientCreditCardDetails[ 'expiry_month']) && ($patientCreditCardDetails[ 'expiry_month']==$year) ? ' selected="selected"' : ''; ?>>
												<?php echo $year; ?>
											</option>
											<?php } ?>
										</select>
									</div>
								</div>


								<div class="field-wps-rd">
									<div class="col-md-5">
										<label class="label-spws">Expiration Year<strong class="star">*</strong></label>
									</div>
									<div class="col-md-6 sdsread">
										<select id="expiry_year" name="expiry_year">
											<?php for($year = date("Y"); $year <= date("Y")+20; $year++){ ?>
											<option value="<?php echo $year; ?>" <?php echo isset($patientCreditCardDetails[ 'expiry_year']) && ($patientCreditCardDetails[ 'expiry_year']==$year) ? ' selected="selected"' : ''; ?>>
												<?php echo $year; ?>
											</option>
											<?php } ?>
										</select>
									</div>
								</div>

								<div class="field-wps-rd rmv-borbottm">
									<?php /*?>
									<div class="col-md-3">
										<label class="label-spws">Pay With</label>
									</div>
									<div class="col-md-6">
										<label class="radio">
                <input type="radio" value="paypal">
                PayPal</label>
									
									</div>
									<?php */?>
									<div class="col-md-12"  style="width: 100%">
										<button type="submit" name="dosubmit1" class="button" onclick="return reg()">Submit</button>
										<!-- <a href="#" class="sub-btn" class="button" onclick="return fn_payment()">Submit</a>-->
									</div>
								</div>


							</div>
						</div>
					</div>
				</div>
			</div>
	</div>
</div>