
<div class="row">
	<div class="col-md-3"><?php $this->load->view('frontend/includes/dashboardsidebar');?></div>
    <div class="col-md-9">
        <div class="col-md-12 general-page">
                <form method="post" id="reg_form" name="reg_form" class="xform" action=""> 
                        <div class="col-md-10">
                        <?php 
						//print_r($Quick);exit;
						if(isset($success_message)){ ?><div class="success"><?=$success_message;?></div><?php } ?>
                            <section>
                                <div class="row"> <div class="col-md-9"><header>Settings</header></div>
                                  </div>
                            </section>
                               <section>
                                <div class="row">
                                
                                <p class="col-md-12"><strong>Do you have Quickbooks</strong></p>
                                  <div class="col-md-6">
                                    <label class="">  
                                      <input  type="radio" name="is_qb" id="is_qb" value="1"  class="required is_qb" <?php echo ($account['is_qb']) ? "checked" : ''; ?> >Yes
                                    </label>
                                  </div>
                                  <div class="col-md-6">
                                    <label class="">  
                                      <input  type="radio" name="is_qb" id="is_qb" value="0"  class="required is_qb" <?php echo (!$account['is_qb']) ? "checked" : ''; ?> >No
                                    </label>
                                  </div>
                                </div>
                            </section>
                               <section>
                                <div class="row qbdiv"   style="<?php echo (!$account['is_qb']) ? "display:none" : ''; ?>">
                                
                                <p class="col-md-12"><strong>QB online credentials</strong></p>
                                  <div class="col-md-6">
                                    <label class="input"> <i class="icon-prepend glyphicon glyphicon-user"></i>
                                      <input  type="text" name="QBUserName" id="QBUserName" value="<?php echo isset($account['QBUserName']) ? $account['QBUserName'] : ''; ?>" placeholder="Username" class="required">
                                    </label>
                                  </div>
                                  <div class="col-md-6">
                                    <label class="input"> <i class="icon-prepend glyphicon glyphicon-lock"></i>
                                      <input  type="password" name="QBPassword" id="QBPassword" value="<?php echo isset($account['QBPassword']) ? $account['QBPassword'] : ''; ?>" placeholder="Password" class="required">
                                    </label>
                                  </div>
                                </div>
                            </section>
                            <section>
                                <div class="row qbdiv" style="<?php echo (!$account['is_qb']) ? "display:none" : ''; ?>">
                                <p class="col-md-12"><strong>Quick book Merchant Account</strong></p>
                                  <div class="col-md-6">
                                    <label class="input"> <i class="icon-prepend glyphicon glyphicon-user"></i>
                                      <input  type="text" name="MerUserName" id="MerUserName" value="<?php echo isset($account['MerUserName']) ? $account['MerUserName'] : ''; ?>" placeholder="Username" class="required">
                                    </label>
                                  </div>
                                  <div class="col-md-6">
                                    <label class="input"> <i class="icon-prepend glyphicon glyphicon-lock"></i>
                                      <input  type="password" name="MerPassword" id="MerPassword" value="<?php echo isset($account['MerPassword']) ? $account['MerPassword'] : ''; ?>" placeholder="Password" class="required">
                                    </label>
                                  </div>
                                </div>
                            </section>
                            <section>
                                <div class="row">
                                <p class="col-md-12"><strong>On fleets Details</strong></p>
                                  <div class="col-md-6">
                                    <label class="input"> <i class="icon-prepend glyphicon glyphicon-user"></i>
                                      <input  type="text" name="FleetsUserName" id="FleetsUserName" value="<?php echo isset($account['FleetsUserName']) ? $account['FleetsUserName'] : ''; ?>" placeholder="Username" class="required">
                                    </label>
                                  </div>
                                  <div class="col-md-6">
                                    <label class="input"> <i class="icon-prepend glyphicon glyphicon-lock"></i>
                                      <input  type="password" name="FleetsPassword" id="FleetsPassword" value="<?php echo isset($account['FleetsPassword']) ? $account['FleetsPassword'] : ''; ?>" placeholder="Password" class="required">
                                    </label>
                                  </div>
                                </div>
                            </section>
                            <footer>
                                <div class="row">
                                  <div class="col-md-12">
                                  		<button type="submit" name="dosubmit1" class="button" onclick="">Submit</button>
                                  </div>
                                </div>
                            </footer>
                            <input name="doLogin" type="hidden" value="1" />
                        </div>
                        <div class="col-md-2">
                        	<a target="_blank" href="http://support.420medsoft.com/" class="support"></a>
                        </div>
                </form>
        </div>
    </div>
</div>
<script type="text/javascript">
$(".is_qb").change(function(){
if($(this).val() == "1")
    $(".qbdiv").show();
else
	$(".qbdiv").hide();
});</script>
