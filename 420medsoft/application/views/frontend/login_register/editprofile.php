<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>

<div class="row">
  <div class="col-md-3">
    <?php $this->load->view('frontend/includes/dashboardsidebar');?>
  </div>
  <div class="col-md-9">
    <div class="general-page col-md-12">
      <form method="post" id="reg_form" name="reg_form" class="xform" action="">
        <div class="col-md-9">
        <?php if(isset($success_message)){ ?><div class="success"><?=$success_message;?></div><?php } ?>
          <section>
            <div class="row">
              <div class="col-md-12">
                <header>Edit Profile</span></header>
                <label class="input"> <i class="icon-prepend glyphicon glyphicon-user"></i>
                  <input  type="text" name="username" id="uname" placeholder="Name" value="<?php echo $Subscriber['username']; ?>" class="required">
                </label>
              </div>
            </div>
          </section>
          <section>
            <div class="row">
              <div class="col-md-12">
                <label class="input"> <i class="icon-prepend glyphicon glyphicon-envelope"></i>
                  <input  type="text" name="email" id="email" placeholder="Email Id" value="<?php echo $Subscriber['email']; ?>" class="required" readonly>
                </label>
              </div>
            </div>
          </section>
          <section>
            <div class="row">
              <div class="col-md-12">
                <label class="textarea"> <i class="icon-prepend glyphicon glyphicon-map-marker"></i>
                  <textarea name="address" placeholder="Address" rows="4" class="required"><?php echo $Subscriber['address']; ?></textarea>
                </label>
              </div>
            </div>
          </section>
          <section>
            <div class="row">
              <div class="col-md-12">
                <label class="input"> <i class="icon-prepend glyphicon glyphicon-lock"></i>
                  <input type="text" name="PhoneNumber" id="phno" maxlength="10" value="<?php echo $Subscriber['PhoneNumber']; ?>" placeholder="PhoneNumber" class="required num">
                </label>
              </div>
            </div>
          </section>
          <footer>
            <div class="row">
              <div class="col-md-12">
                <button type="submit" name="dosubmit1" class="button" onclick="">Update Profile</button>
              </div>
            </div>
          </footer>
        </div>
        <div class="col-md-3">
          <a target="_blank" href="http://support.420medsoft.com/" class="support"></a>
        </div>
      </form>
    </div>
  </div>
</div>
