
    <div class="row">
      <div class="col-md-12">
        <h1 class="hd-title-light">Checkout</h1>
        <div class="panel-group" id="accordion">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#loginRegister"> Step-1 Login/register </a> </h4>
            </div>
            <div id="loginRegister" class="panel-collapse collapse in">
              <div class="panel-body">
              <?php if($patientDetails && isset($session['PATIENT_ID']) && !empty($session['PATIENT_ID'])){ ?>
              <h2>Patient Details</h2>
              
              <?php }else{ ?>
              
                <div class="col-md-6">
                  <h2>New Patient</h2>
                 <?php /*?> <p>Checkout Options:</p><?php */?>
                  <form role="form">
                    <?php /*?><div class="radio">
                      <label>
                        <input type="radio" name="checkoutopt" id="registeraccount" value="option1" checked> Register Account</label>
                    </div>
                    <div class="radio">
                      <label>
                        <input type="radio" name="checkoutopt" id="guestcheckout" value="option2"> Guest Checkout </label>
                    </div><?php */?>
                    <p>By creating an account you will be able to shop faster, be up to date on an order's status, and keep track of the orders you have previously made.</p>
                    <a href="<?php echo base_url("index.php/members/register"); ?>"><button type="button" class="btn btn-primary">Register</button></a>
                  </form>
                </div>
                <div class="col-md-6">
                  <h2>Login</h2>
                  <p>I am a returning customer.</p>
                  <form role="form">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Email address</label>
                      <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email" required="required" >
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Password</label>
                      <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" required="required" >
                    </div>
                    <div class="form-group"> <a href="#">Forgotten Password?</a> </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                  </form>
                </div>
                
              <?php } ?>
                
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#paymentmethod"> Step-2 Payment Method </a> </h4>
            </div>
            <div id="paymentmethod" class="panel-collapse collapse">
              <div class="panel-body">
              	<div class="col-md-12">
              		Please select the preferred payment method to use on this order.
                    <form role="form">
                    
                    <div class="radio">
                      <label>
                        <input type="radio" name="cashondelivery" id="cashondelivery" value="option2"> Cash On Delivery  </label>
                    </div>
                    <div class="form-group">
                     	<label for="addcommentorder">Add Comments About Your Order</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                    <div class="checkbox">
    <label>
      <input type="checkbox">I have read and agree to the Terms & Conditions</label>
  </div>
  <button type="submit" class="btn btn-primary">Continue</button>
  
                    
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#confirmorder"> Step-3 confirm order </a> </h4>
            </div>
            <div id="confirmorder" class="panel-collapse collapse">
              <div class="panel-body">
              	<div class="col-md-12">
                <?php if(isset($session['cart']) && (count($session['cart']) > 0)){?>
                	<table class="table table-hover table-striped">
                    	<thead>
                    	<tr>
                        	<th>Image</th>
                        	<th>Description</th>
                        	<!--<th>Model</th>-->
                        	<th class="aligncenter">Quantity</th>
                        	<th class="alignright">Price</th>
                            <th class="alignright">Total</th>
                        </tr>
                        </thead>
                        <tbody>
						<?php $cart_total_price = 0; foreach($session['cart'] as $cart_product_id => $cart_product_details){ ?>
                        <tr>
                        	<td><img src="<?php echo base_url('images/chem-walker-img.jpg'); ?>" class="img-responsive img-thumbnail" width="100px"/></td>
                        	<td><?php echo $cart_product_details['categotyName'] ?> <span class="td-subtitle"><?php echo $cart_product_details['productName'] ?></span></td>
                        	<!--<td>RES.193</td>-->
                        	<td class="aligncenter"><?php echo $cart_product_details['quantity'] ?></td>
                        	<td class="alignright"><?php echo $currency['symbol']; ?><?php echo $cart_product_details['productPrice'] ?></td>
                        	<td class="alignright"><?php echo $currency['symbol']; ?><?php echo $cart_product_details['quantity']*$cart_product_details['productPrice'] ?></td>
                        </tr>
						<?php $cart_total_price += $cart_product_details['quantity']*$cart_product_details['productPrice']; ?>
                        <?php } ?>                        
                        </tbody>
                    </table>
                    <table class="pull-right table table-condensed col-md-4 checkout-total">
                    	<!--<tr>
                        	<td>Sub total</td>
                            <td>$47</td>
                        </tr>
                    	<tr>
                        	<td>Shipping cost</td>
                            <td>$3</td>
                        </tr>
                    	<tr>
                        	<td>Eco Tax (-2.00)</td>
                            <td>$3</td>
                        </tr>
                    	<tr>
                        	<td>Vat (17.5%)</td>
                            <td>$3</td>
                        </tr>-->
                    	<tr>
                        	<td style="font-size:20px;"><strong>Total</strong></td>
                            <td style="font-size:20px;"><strong><?php echo $currency['symbol']; ?><?php echo $cart_total_price; ?></strong></td>
                        </tr>
                        <tr>
                        	<td colspan="2"><a href="#" class="btn btn-danger">Cancel</a> <a href="#" class="btn btn-primary">Confirm Order</a></td>
                        </tr>
                    </table>
                    <?php }else{ ?>
                    
                    <table class="pull-right table table-condensed col-md-4 checkout-total">
                    	<tr>
                        	<td><a href="<?php echo base_url('index.php/main'); ?>" class="btn btn-primary">Continue shopping</a></td>
                        </tr>
                    </table>
                    <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>