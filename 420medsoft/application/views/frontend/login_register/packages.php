<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript">
function Submit(){
 var emailRegex = /^[A-Za-z0-9._]*\@[A-Za-z]*\.[A-Za-z]{2,5}$/;
 var fname = document.form.Name.value,
  lname = document.form.LastName.value,
  femail = document.form.Email.value,
  freemail = document.form.enterEmail.value,
  fpassword = document.form.Password.value;
}
</script>

<div class="plans-page">
  <div class="row marb-50"> 
    <!--<div class="col-md-12"><h1>Membership Packages</h1></div>-->
    <div class="col-md-7">
      <div class="planpack">
        <h2>
          <center>
            Patient & Admin Website
          </center>
        </h2>
        <div class="row">
          <div class="col-md-6 paddingr0">
            <p class="setfee"><?php echo $packages['Description']; ?><br />
              <?php echo $packages['CurrencySymbol'].number_format($packages['Cost'],2); ?></p>
          </div>
          <div class="col-md-6 paddingl0">
            <p class="price"><?php echo $packages['RecurringDuration']; ?><br />
              <?php echo $packages['CurrencySymbol'].number_format($packages['RecurringCost'],2); ?></p>
          </div>
        </div>
      </div>
      <div class="purchase-btn" onClick="$('#register-form').toggle();"> <a href="javascript:">
        <p class="purchase">Purchase Membership <i class="icon-prepend glyphicon glyphicon-user"></i></p>
        </a> </div>
       
      <div class="reg-fields-form" id="register-form" <?php if(!isset($errorMessage) || empty($errorMessage)){ ?> style="display:none;"<?php } ?>>
      <?php if(isset($errorMessage) && !empty($errorMessage)){?><div class="error-message"><?php echo $errorMessage;?></div><?php } ?>
        <form method="post" id="reg_form"  onsubmit="return validateForm()" name="reg_form" class="xform" action="">
          <div class="row">
          <div class="col-md-1"></div>
          <div class="col-md-9">
            <section>
              <div class="row">
                <div class="col-md-12">
                  <header>Register <span>(New user)</span></header>
                  <label class="input"> <i class="icon-prepend glyphicon glyphicon-user"></i>
                    <input  type="text" name="username"  id="uname" placeholder="Name" class="required">
                  </label>
                </div>
              </div>
            </section>
            <section>
              <div class="row">
                <div class="col-md-12">
                  <label class="input"> <i class="icon-prepend glyphicon glyphicon-envelope"></i>
                    <input  type="text" name="email"  id="email" placeholder="Email Id" class="required">
                  </label>
                </div>
              </div>
            </section>
            <section>
            <div class="row">
              <div class="col-md-12">
                <label class="input"> <i class="icon-prepend glyphicon glyphicon-lock"></i>
                  <input type="password" name="password" id="pswrd" placeholder="Password" class="required">
                </label>
              </div>
              </section>
              <!--  <section>
                                  <div class="row">
                                  <div class="col-md-12">
                                    <label class="input"> <i class="icon-prepend glyphicon glyphicon-lock"></i>
                                      <input type="password" name="Confirmpassword" id="cpswrd" placeholder="ConfirmPassword" class="required">
                                    </label>
                                  </div>
                                </div>
                            </section>-->
              <section>
                <div class="row">
                  <div class="col-md-12">
                    <label class="input"> <i class="icon-prepend glyphicon glyphicon-lock"></i>
                      <input type="text" name="PhoneNumber" id="phno" placeholder="PhoneNumber" class="required">
                    </label>
                  </div>
                </div>
              </section>
              <footer>
                <div class="row">
                  <div class="col-md-12"> 
                    <!--<a href="" class="button button-secondary" onclick="return reg()">Register</a>-->
                    
                    <button type="submit" name="dosubmit1" class="button" onclick="return sitedetails()">Register</button>
                  </div>
                </div>
              </footer>
              <input name="doLogin" type="hidden" value="1" />
            </div>
            <div class="col-md-2"></div>
          </div>
        </form>
      </div>
    </div>
    <div class="col-md-5">
      <div class="planpack-details">
        <h2>Set up Process</h2>
        <ul>
          <li>Please register with <?php /*?><a href="http://quickbooks.intuit.com/signup"><?php */?>Quickbooks Online<?php /*?></a><?php */?></li>
          <li> Register for <?php /*?><a href="https://onfleet.com/createOrganization" target="_blank/" class="button"><?php */?>Delivery Tracking Software <?php /*?></a><?php */?></li>
          <!-- https://onfleet.com/features-->
          <li> Provide Domain Name</li>
          <li>Provide Logo <?php /*?>(give specific size 180x240)<?php */?></li>
          <li>Choose your Website Color Scheme </li>
        </ul>
      </div>
    </div>
  </div>
</div>
