<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>

<div class="row">
  <div class="general-page text-center clearfix">
  <?php if(isset($errorMessage) && !empty($errorMessage)){?><div class="error-message"><?php echo $errorMessage;?></div><?php } ?>
    <div class="col-md-12">
      <form method="post" id="login_form" name="login_form" class="xform" action="">
        <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-7">
            <section>
              <div class="row">
                <div class="col-md-12">
                  <header>Login</header>
                  <label class="input"> <i class="icon-prepend glyphicon glyphicon-user"></i>
                    <input  type="text" name="username"  id="username" placeholder="Username" value="<?php echo $coockie_username; ?>" class="required">
                  </label>
                </div>
              </div>
            </section>
            <section>
              <div class="row">
                <div class="col-md-12">
                  <label class="input"> <i class="icon-prepend glyphicon glyphicon-lock"></i>
                    <input type="password"  name="password"  id="password" placeholder="Password" class="required">
                  </label>
                </div>
              </div>
            </section>
            <footer>
              <div class="row">
                <div class="col-md-12">
                  <input type="checkbox" name="remember_me">
                  Remember me <a href="<?php echo base_url('index.php/main/forgotpassword');?>">Forgot password</a></div>
                <div class="col-md-12 login-btn-mar">
                  <button type="submit" name="dosubmit" class="button" onclick="return login1()">Login</button>
                </div>
              </div>
            </footer>
            <input name="doLogin" type="hidden" value="1" />
          </div>
          <div class="col-md-1"></div>
        </div>
      </form>
    </div>
  </div>
</div>
