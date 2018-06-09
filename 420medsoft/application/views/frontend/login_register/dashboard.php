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
}
</script>
<div class="row">
    	<div class="col-md-3"><?php $this->load->view('frontend/includes/dashboardsidebar');?></div>
        <div class="col-md-9">
 			<div class="general-page col-md-12">
               <!-- <form method="post" id="reg_form" name="reg_form" class="xform" action="<?php echo base_url("index.php/main/editprofile1"); ?>"> 
                      
                        <div class="col-md-9">  -->
                            <section>
                                <div class="row">
                                  <div class="col-md-12">
                                   <div class="col-md-12">
            <div class="planpack-details">
            <h2>Set up Process</h2>
            <ul>
                <li>Please register with <a href="http://quickbooks.intuit.com/signup" target="_blank">Quickbooks Online</a></li>
                <li>Register for <a href="https://onfleet.com/createOrganization" target="_blank/" class="button">Delivery Tracking Software</a></li>
                <li>Provide Domain Name</li>
                <li>Provide Logo<?php /*?> (give specific size 180x240)<?php */?></li>
                <li>Choose your Website Color Scheme</li>
                <li>Add your store or delivery address in your <a href="<?php echo base_url("index.php/main/editprofile"); ?>">Profile</a></li>
                <li>Set up your <a href="<?php echo base_url("index.php/main/settings"); ?>">Settings</a></li>
            </ul>
            </div> 
        </div>
                       <!--<div class="col-md-3">-->
   </div>                        </div>
                        </section>

                </form>
          	</div>
        </div>
</div>
