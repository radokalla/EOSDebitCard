<?php /*?><div class="leftbar-ads">
            <? 
					if(count($images)>0){
						if(count($images)==1)
							$end=0;
						else if(count($images)>1)
							$end = 1;
						else 
							$end = count($images);
						#echo $end; die;
						for($i=0 ; $i<=$end;$i++)
						{
							$web = $images[$i]['url'];
							if (strpos($web,'http://') === false)
							$web = 'http://'.$web;

							echo '<a onClick="window.open(\''.$web.'\')" target="_blank"><img src="'.ROOT_URL.'admin/uploads/'.$images[$i]['image'].'"  class="rightbaradsPadd"/></a>';
						}
						
					}
					if(count($images)<2)
						for($i=1 ; $i<=2- count($images);$i++)
							echo '<img data-toggle="modal" data-target="#myModal4" src="img/add-banner160x240.jpg"  class="rightbaradsPadd"/>';
					?>
            <img src="img/ads-enquiry.jpg"  id="addenquiry" data-toggle="modal" data-target="#myModal4" style="padding:7px 0px;" /> 
</div>
 <div class="rightbar-ads">
            <?if(count($images)>1){
						for($i=2 ; $i<count($images);$i++)
						{
							$web = $images[$i]['url'];
							if (strpos($web,'http://') === false)
							$web = 'http://'.$web;
							echo '<a onClick="window.open(\''.$web.'\')" target="_blank"><img src="'.ROOT_URL.'admin/uploads/'.$images[$i]['image'].'"  class="rightbaradsPadd"/></a>';
						}
					}
					
					if(count($images)<5)
					{
						for($i=0 ; $i< 4-count($images);$i++)
						echo '<img data-toggle="modal" data-target="#myModal4" src="img/add-banner160x240.jpg"  class="rightbaradsPadd"/>';
					}
					?>
</div><?php */?>
  
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog">
    <div class="modal-content">
            <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title LRhd" id="myModalLabel">Login</h4>
      </div>
            <div class="modal-body">
			<h4 id="msg" style="display:none; text-align:center;"></h4>
        <form class="xform res-log-alg" name="login_form" method="post" OnSubmit="return false;" id="loginForm">
                <section>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label class="input"> <i class="icon-prepend fa fa-user"></i>
                        <input type="text" Onfocus="hide();" placeholder="Username" name="username" class="form-control" id="usernamer" required="" />
                      </label>
              </div>
                  </div>
          </section>
                <section>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label class="input"> <i class="icon-prepend fa fa-lock"></i>
                        <input type="password" Onfocus="hide();" placeholder="Password" class="form-control" name="password" id="passwordr"  required="" />
                      </label>
              </div>
                  </div>
          </section>
                <div class="row">
            		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><input type="checkbox"> Remember me</div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right res-text-alg-left">
                   <a href="#" onClick="fp()" data-toggle="modal" data-target="#forgot_pass" data-dismiss="modal">Forgot password</a> &nbsp &nbsp <a href="#forgotEmail"  data-toggle="modal" data-dismiss="modal">Forgot Username</a></div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 login-btn-mar">
                   <!-- <button class="btn btn-success">Login as User</button> -->
					<button class="btn btn-success" OnClick="storelog();">Login</button>
                  </div>
          </div>
              </form>
      </div>
          </div>
  </div>
      </div>
<script> 

	function hide()
	{
		$('#msg').hide();
	}
	
	function storelog()
	{
		var username = $('#usernamer').val();
		var password = $('#passwordr').val();
		var formdata = $('#loginForm').serializeArray();
		if((username!='') && (password!=''))
		{
			$.ajax({
					type: "POST",
					url: "storelogin.php",
					data : formdata,
					success: function(data)
					{
						if(data==8)
						{
							document.getElementById('msg').innerHTML = 'Login successfull';
							$('#msg').css('color','green');
							$('#msg').show();
							window.location.href="index.php";
						}
						if(data==9)
						{
							document.getElementById('msg').innerHTML = 'Your Account has been deleted';
							$('#msg').css('color','red');
							$('#msg').show();
							return false;
						}
						else if(data=='a')
						{
							window.location.href = "dashboard.php";
							return false;
						}
						else if((data!='a') && (data!=0))
						{
							window.location.href = "stores.php";
							return false;
						}
						else if(data==0)
						{
							document.getElementById('msg').innerHTML = 'Invalid Details';
							$('#msg').css('color','red');
							$('#msg').show();
							return false;
						}
					}
				});
		}
	}
</script>
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
        <div class="modal-dialog">
    <div class="modal-content">
            <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title LRhd" id="myModalLabel">Register (New user)</h4>
      </div>
            <div class="modal-body">
        <form class="xform" name="login_form" method="post" OnSubmit="return false;">
                <input type="hidden" value="" name="upid">
                <section>
            <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-mb20-resp">
                <label class="input"> <i class="icon-prepend fa-user"></i>
                        <input type="text" id="regfname" maxlength="15" placeholder="First Name" class="form-control" value="" name="firstname" required aria-required="true"/>
                      </label>
              </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label class="input"> <i class="icon-prepend fa-user"></i>
                        <input type="text" id="reglname" maxlength="15" placeholder="Last Name" class="form-control" value="" name="lastname" required aria-required="true"/>
                      </label>
              </div>
                  </div>
          </section>
                <section>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label class="input"> <i class="icon-prepend fa-envelope"></i>
                        <input type="email" id="regemail" maxlength="30" placeholder="Email Id" class="form-control" value="" name="email" required aria-required="true"/>
                      </label>
              </div>
                  </div>
          </section>
                <section>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label class="input"> <i class="icon-prepend fa-user"></i>
                        <input type="text" id="reguser" placeholder="User Name" maxlength="15" class="form-control" value="" name="username" required aria-required="true"/>
                      </label>
              </div>
                  </div>
          </section>
                <section>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label class="input"> <i class="icon-prepend fa fa-lock"></i>
                        <input type="password" id="regpassword" maxlength="20" placeholder="Password" class="form-control" name="password" required aria-required="true"/>
                      </label>
              </div>
                  </div>
          </section>
                <section>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label class="textarea">
                        <textarea rows="5" name="address" maxlength="70" id="regaddress" placeholder="Address" class="form-control" required aria-required="true"/>
                        </textarea>
                      </label>
              </div>
                  </div>
          </section>
                <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button name="dosubmit" class="btn btn-success" onClick="userlogin();return false;">Register</button>
                    <!--<a href="register.php" class="button button-secondary">Register</a>--> 
                  </div>
          </div>
              </form>
      </div>
          </div>
  </div>
      </div>
      
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
        <div class="modal-dialog">
    <div class="modal-content">
            <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title LRhd" id="myModalLabel">Update Your Profile</h4>
      </div>
            <div class="modal-body">
        <form method="post"  id="update_form" name="update_form" class="xform">
                <input type="hidden" name="idd" id="idd" value="<?php echo $_SESSION['userid']; ?>" >
                <section>
            <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-mb20-resp">
                <label class="input"> <i class="icon-prepend fa-user"></i>
                        <input  type="text" name="username" id="username" class="form-control" placeholder="Username" value="<?php echo $regfetch['username']; ?>" disabled>
                      </label>
              </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label class="input"> <i class="icon-prepend fa-user"></i>
                        <input  type="text" name="email" id="email" class="form-control" placeholder="Email" value="<?php echo $regfetch['email']; ?>" disabled>
                      </label>
              </div>
                  </div>
          </section>
                <section>
            <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-mb20-resp">
                <label class="input"> <i class="icon-prepend fa-user"></i>
                        <input  type="text" name="firstname" id="firstname" placeholder="First Name" value="<?php echo $regfetch['firstname']; ?>" class="required form-control">
                      </label>
              </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label class="input"> <i class="icon-prepend fa-user"></i>
                        <input  type="text" name="lastname" id="lastname" placeholder="Last Name" value="<?php echo $regfetch['lastname']; ?>" class="required form-control">
                      </label>
              </div>
                  </div>
          </section>
                <section>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label class="textarea">
                        <textarea name="addressm" id="addressm" rows="5" value="<?php echo $regfetch['address']; ?>" class="required form-control"><?php echo $regfetch['address']; ?></textarea>
                      </label>
              </div>
                  </div>
          </section>
                <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button type="button" class="button button-secondary Update" name="update" onclick="updatereg();">
                    Update
                    </button>
                    <!--<a href="register.php" class="button button-secondary">Register</a>--> 
                  </div>
          </div>
              </form>
      </div>
          </div>
  </div>
      </div>
<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
        <div class="modal-dialog">
    <div class="modal-content">
            <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title LRhd" id="myModalLabel">Add Enquiry</h4>
      </div>
            <div class="modal-body">
        <form method="post"  action="" id="add_form" name="" class="xform">
                <section>
            <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-mb20-resp">
                <label class="input"> <i class="icon-prepend fa-user"></i>
                        <input  type="text" maxlength="20" name="adduser_name" id="adduser_name" placeholder="Full Name" value="" class="required form-control">
                      </label>
              </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-mb20-resp">
                <label class="input"> <i class="icon-prepend fa-envelope"></i>
                        <input  type="text" maxlength="25" name="adduser_email" id="adduser_email" placeholder="Email" value="" class="required form-control">
                      </label>
              </div>
                  </div>
                  </section>
                  <section>
            <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-mb20-resp">
                <label class="input"> <i class="icon-prepend fa-phone"></i>
                        <input  type="text" maxlength="10" name="adduser_phone" id="adduser_phone" placeholder="Phone" value="" class="required form-control">
                      </label>
              </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-mb20-resp">
                <label class="input"> <i class="icon-prepend fa-link"></i>
                        <input  type="text" name="adduser_url" id="adduser_url" placeholder="URL" value="" class="required form-control">
                      </label>
              </div>
                  </div>
                  </section>
                  <section>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type='file' name="adduser_image" id="adduser_image" placeholder="Image" accept="image/*">
              </div>
			 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  <p style="color:red;margin-top:5px"> Please upload 160x240 or higher resolution images 		</p>     </div>
		  </div>
          </section>
                <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button type="button" class="button button-secondary Update" name="update" id="addEnquiry_button">
                    Enquire
                    </button>
                    <!--<a href="register.php" class="button button-secondary">Register</a>--> 
                  </div>
          </div>
              </form>
      </div>
          </div>
  </div>
      </div>
<div class="modal fade" id="myModal5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
        <div class="modal-dialog">
    <div class="modal-content">
            <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title LRhd" id="myModalLabel">Rate Us</h4>
      </div>
            <div class="modal-body">
        <section>
                <form id='rating_form'>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="" style="display:block" name='rate_us_store'>
                        <fieldset class="rating">
                    <input type="radio" value="5" name="rate" id="star5">
                    <label for="star5" class="full"></label>
                    <input type="radio" value="4" name="rate" id="star4">
                    <label for="star4" class="full"></label>
                    <input type="radio" value="3" name="rate" id="star3">
                    <label for="star3" class="full"></label>
                    <input type="radio" value="2" name="rate" id="star2">
                    <label for="star2" class="full"></label>
                    <input type="radio" value="1" name="rate" id="star1">
                    <label for="star1" class="full"></label>
                  </fieldset>
                      </div>
              </div>
                  </div>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <textarea id="rating_comments" name="rating_comments" <? if(!isset($_SESSION['userid'])) echo "style='display:none'"?> placeholder="Comments" class="form-control"></textarea>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-8 col-xs-8"></div>
                    <div class="col-lg-4 col-md-4 col-sm-8 col-xs-8">
						<div align="center">
								<br><button class="btn btn-primary" onClick="saveRating('<?=$_SESSION['userid']?>');return false;">Submit Rating</button>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-8 col-xs-8"></div>
             </div>
          </form>
              </section>
      </div>
          </div>
  </div>
      </div>
<div class="modal fade" id="forgotEmail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
        <div class="modal-dialog">
    <div class="modal-content">
            <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title LRhd" id="myModalLabel">Forgot User Name</h4>
      </div>
            <div class="modal-body">
			<div class="alert alert-success" id="frgtUalert" style="text-align:center; display:none"></div>
        
                <form method="post" class="xform">
            <section>
            	<div class="row">
                    <div class="col-lg-12">
                    <label class="input"> <i class="icon-prepend fa fa-envelope"></i>
                        <input type="email" id="frgtemailid" class="form-control" name="email" placeholder="Please Enter Your Registered Email ID" aria-required="true" required=""/>
                      </label>
					</div> 
					<input type="hidden" value="2" name="type" id="type" />
             	</div>
             </section>
             <div class="row"><div class="col-lg-12"><button  onclick="frgtuser();return false;" class="btn btn-success">Submit</button></div></div>
          </form>
              
      </div>
          </div>
  </div>
      </div>
<div class="modal fade" id="forgot_pass" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog">
    <div class="modal-content">
            <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title LRhd" id="myModalLabel">Forgot Password</h4>
      </div>
            <div class="modal-body">
			<div class="alert alert-success" id="frgtmsg" style="display:none; text-align:center"></div>
        <form class="xform" name="login_form" OnSubmit="return false;" method="post" id="loginForm">
                <section>
            <div class="row">
					<input type="hidden" value="1" id="typeforgot"/>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label class="input"> <i class="icon-prepend fa fa-envelope"></i>
                        <input type="text" placeholder="Email" class="form-control" name="Email_for"  id="Email_for" required="" aria-required="true"  />
                      </label>
              </div>
                  </div>
          </section>
                <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button class="btn btn-success" name="doFPsubmit" onClick="forgotpass();" type="submit" id="submit_login">Submit</button>
					<img src="img/loading.gif" id="passimg" style="height:25px; display:none" />
                  </div>
          </div>
              </form>
      </div>
          </div>
  </div>
      </div>
<div class="modal fade" id="myModal6" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
        <div class="modal-dialog">
    <div class="modal-content">
            <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title LRhd" id="myModalLabel">Reviews</h4>
      </div>
            <div class="modal-body">
				<?php if(!($_SESSION['userid'])) { ?> <h4 style="text-align:center; color:#000">To leave a review please <a href="#myModal2"  data-toggle="modal" data-dismiss="modal"  id="register_id"><u style="color:5dc2ed">Register</u></a> and <a href="#myModal1"  data-toggle="modal" data-dismiss="modal" id="login_id"><u style="color:5dc2ed">Login</u></a> <br></h4> <?php } ?>
        <section>
                <form id=''>
					<div class="row">
						<div class="col-md-9" id='review_form'> </div>
					</div>
				</form>
              </section>
      </div>
          </div>
  </div>
      </div>
<?php include ROOT."themes/footer.inc.php"; ?>
<script>
$("#adduser_image").change(function (e) {
    var file, img;
	if(this.files[0].size <= 102400)
	{
		alert("Select images with atleast 100KB in Size")
		$("#adduser_image").clearInputs()
		$("#adduser_image").css('border','1px solid red');
		return false;
	}
    
});
$.fn.clearFields = $.fn.clearInputs = function(includeHidden) {
    var re = /^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i; // 'hidden' is not in this list
    return this.each(function() {
        var t = this.type, tag = this.tagName.toLowerCase();
        if (re.test(t) || tag == 'textarea') {
            this.value = '';
        }
        else if (t == 'checkbox' || t == 'radio') {
            this.checked = false;
        }
        else if (tag == 'select') {
            this.selectedIndex = -1;
        }
        else if (t == "file") {
            if (/MSIE/.test(navigator.userAgent)) {
                $(this).replaceWith($(this).clone(true));
            } else {
                $(this).val('');
            }
        }
        else if (includeHidden) {
            // includeHidden can be the value true, or it can be a selector string
            // indicating a special test; for example:
            //  $('#myForm').clearForm('.special:hidden')
            // the above would clean hidden inputs that have the class of 'special'
            if ( (includeHidden === true && /hidden/.test(t)) ||
                 (typeof includeHidden == 'string' && $(this).is(includeHidden)) )
                this.value = '';
        }
    });
};
</script>
	  <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>