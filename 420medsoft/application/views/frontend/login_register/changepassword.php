<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript">

//login


function login1(){ 

	if($("#oldpassword").val() == ''){
	   $("#oldpassword").insertAfter('<span>Old Password Required</span>');
	}
	
	if($("#newpassword").val() == ''){
	   $("#newpassword").insertAfter('<span>New Password Required</span>');
	}
	if($("#confirmpassword").val() == ''){
	   $("#confirmpassword").insertAfter('<span>Confirm Password Required</span>');
	}
	
	if($("#newpassword").val() != '' && $("#confirmpassword").val()!=''){
		if($("#newpassword").val() == $("#confirmpassword").val())
		{
	      $("#newpassword").insertAfter('<span>New Passwords not matched</span>');
		}
	}
}
</script>

<div class="row">
    	<div class="col-md-3"><?php $this->load->view('frontend/includes/dashboardsidebar');?></div>
        <div class="col-md-9">
 			<div class="general-page col-md-12">
                <form method="post" id="reg_form" name="reg_form" class="xform" action=""> 
                       
                        <div class="col-md-9">
        <?php if(isset($errorMessage) && !empty($errorMessage)){?><div class="error"><?php echo $errorMessage;?></div><?php } ?>
        <?php if(isset($successMessage) && !empty($successMessage)){?><div class="success"><?php echo $successMessage;?></div><?php } ?>
                            <section>
                                <div class="row">
                                  <div class="col-md-12">
                                    <header>Change Password</span></header>
                                    <label class="input"> <i class="icon-prepend glyphicon glyphicon-lock"></i>
                                       <input  type="password" name="oldpassword" placeholder="Old Password" class="required">
                                    </label>
                                  </div>
                                </div>
                            </section>
                            <section>
                                <div class="row">
                                      <div class="col-md-12">
                                        <label class="input"> <i class="icon-prepend glyphicon glyphicon-lock"></i>
                                          <input  type="password" name="newpassword" placeholder="New Password" class="required">
                                        </label>
                                      </div>
                                </div>
                            </section>
                            <section>
                                <div class="row">
                                      <div class="col-md-12">
                                        <label class="input"> <i class="icon-prepend glyphicon glyphicon-lock"></i>
                                          <input  type="password" name="confirmpassword" placeholder="Confirm Password" class="required">
                                        </label>
                                      </div>
                                </div>
                            </section>    
                            <footer>
                                <div class="row">
                                  <div class="col-md-12">
                                  		<button type="submit" name="dosubmit1" class="button" onclick="login1">Submit</button>
                                  </div>
                                </div>
                            </footer>
                            <input name="doLogin" type="hidden" value="1" />
                        </div>
                        <div class="col-md-3">
                        	<a target="_blank" href="http://support.420medsoft.com/" class="support"></a>
                        </div>

                </form>
          	</div>
        </div>
</div>
