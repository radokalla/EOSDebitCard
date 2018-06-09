<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript">

//login


function login1(){ 

	if($(".username").val() == ''){
	   $(".username").insertAfter('<span>Username Required</span>');
	}
	
	if($(".password").val() == ''){
	   $(".password").insertAfter('<span>Password Required</span>');
	}
/*var err =0;
	if($("#username").val() == ''){
	   $("#username").after('<span>Username Required</span>');
	   err++;
	}*/
	
	/*if($("#password").val() == ''){
	   $("#password").after('<span>Password Required</span>');
	    err++;
/*	}*/
   //if(err!=0) return false;
  // else return true;*/
}

//register
function reg(){
if($(".uname").val() == ''){
	   $(".uname").insertAfter('<span>Username Required</span>');
	}
	
	if($(".email").val() == ''){
	   $(".email").insertAfter('<span>Email Required</span>');
	}
	if($(".pswd").val() == ''){
	   $(".pswd").insertAfter('<span>Password Required</span>');
	}
	
}
</script>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-12 general-page">
            <div class="col-md-6">
                <form method="post" name="" class="xform" action="">
                <?php if(isset($error_message)){ ?><div class="error"><?=$error_message;?></div><?php } ?>
                <?php if(isset($success_message)){ ?><div class="success"><?=$success_message;?></div><?php } ?>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-10">
                            <section>
                                <div class="row">
                                    <div class="col-md-12">
                                        <header>Forgot Password</header>
                                        <label class="input"> <i class="icon-prepend glyphicon glyphicon-user"></i>
                                        <input  type="text" name="username"  id="username" placeholder="Username" class="required">
                                        </label>
                                    </div>
                                </div>
                            </section>
                            <footer>
                                <div class="row">
                                    <div class="col-md-12">
                                    <button type="submit" name="dosubmit" class="button" onclick="">Submit</button>
                                    </div>
                                </div>
                          </footer>
                      <input name="doLogin" type="hidden" value="1" />
                      </div>
                      
                 </div>
                </form>
            </div>
        </div>
    </div>
</div>
