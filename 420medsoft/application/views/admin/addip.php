<div class="memberlogin-wps col-md-12 products_page">
  <h2>Settings <a href="javascript:" class="category_add" onClick="resetinventory()">Reset Invetory and Sales</a></h2>
  
  <div class="col-md-12"> <?php echo validation_errors(); ?>
  <div id="message"></div>
    <form method="post" role="form" id="add-category-form" class="validate-form">
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">IP<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required " id="ip" name="ip" value="<?php echo isset($ip[0]['ipadress']) ? $ip[0]['ipadress'] : ''; ?>" placeholder="Enter Ip Adress"   >
        </div>
      </div>
      <div class="form-group" <?php isset($ip[0]['qb_offline']) && ($ip[0]['qb_offline'] == '0') ? ' style="display: none;"' : ''; ?>> 
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Quickbooks User<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required " id="user" name="user" value="<?php echo isset($ip[0]['quickbooksUser']) ? $ip[0]['quickbooksUser'] : ''; ?>" placeholder="Enter Quickbooks User"   >
        </div>
      </div>
      <div class="form-group" <?php isset($ip[0]['qb_offline']) && ($ip[0]['qb_offline'] == '0') ? ' style="display: none;"' : ''; ?>>
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Quickbooks Password<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="password" class="form-control text_input1 required " id="pwd" name="pwd" value="<?php echo isset($ip[0]['quickbooksPwd']) ? $ip[0]['quickbooksPwd'] : ''; ?>" placeholder="Enter Quickbooks Password"   >
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Tax Percentage<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required percent" id="taxPercentage" name="taxPercentage" value="<?php echo isset($ip[0]['taxPercentage']) ? $ip[0]['taxPercentage'] : ''; ?>" placeholder="Enter Tax Percentage"   >
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Delivery Charges<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required currency" id="deliveryCharges" name="deliveryCharges" value="<?php echo isset($ip[0]['deliveryCharges']) ? $ip[0]['deliveryCharges'] : ''; ?>" placeholder="Enter Delivery Charges"   >
        </div>
      </div>
      
      
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Facebook : </label>
        <div class="col-md-3">https://www.facebook.com/</div>
        <div class="col-md-3">
          <input type="text" class="form-control text_input1" id="facebook" name="facebook" value="<?php echo isset($ip[0]['facebook']) ? $ip[0]['facebook'] : ''; ?>"    >
        </div>
      </div>
      
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Twitter : </label>
        <div class="col-md-3">https://twitter.com/</div>
        <div class="col-md-3">
          <input type="text" class="form-control text_input1" id="twitter" name="twitter" value="<?php echo isset($ip[0]['twitter']) ? $ip[0]['twitter'] : ''; ?>" >
        </div>
      </div>
      
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Instagram : </label>
        <div class="col-md-3">http://instagram.com/</div>
        <div class="col-md-3">
          <input type="text" class="form-control text_input1" id="instagram" name="instagram" value="<?php echo isset($ip[0]['instagram']) ? $ip[0]['instagram'] : ''; ?>"  >
        </div>
      </div>
      
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Linked in : </label>
        <div class="col-md-3">https://www.linkedin.com/</div>
        <div class="col-md-3">
          <input type="text" class="form-control text_input1" id="linkedin" name="linkedin" value="<?php echo isset($ip[0]['linkedin']) ? $ip[0]['linkedin'] : ''; ?>"    >
        </div>
      </div>
      
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Chat : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1" id="chat" name="chat" value="<?php echo isset($ip[0]['chat']) ? $ip[0]['chat'] : ''; ?>"    >
        </div>
      </div>
      
      
      <input type="hidden" class="form-control text_input1" id="cc_merchant" name="cc_merchant" value="<?php echo isset($ip[0]['cc_merchant']) ? $ip[0]['cc_merchant'] : ''; ?>"    >
      
      
      
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Merchant Account : </label>
        <div class="col-md-6">
          <select class="form-control text_input1" id="qbms_account" name="qbms_account">
			  <option value="cdllab">cdllab</option>
			  <option value="cdllab@cdllab.com" <?php echo isset($ip[0]['qbms_account']) && ($ip[0]['qbms_account'] == 'cdllab@cdllab.com') ? ' selected="selected"' : ''; ?>>cdllab@cdllab.com</option>
			</select>
        </div>
      </div>
      
      
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name" >QB Online : </label>
        <div class="col-md-6">
          <select class="form-control text_input1" id="qb_offline" name="qb_offline" onChange="showModal(this.value);">
			  <option value="0" <?php echo isset($ip[0]['qb_offline']) && ($ip[0]['qb_offline'] == '0') ? ' selected="selected"' : ''; ?>>OFF</option>
			  <option value="1" <?php echo isset($ip[0]['qb_offline']) && ($ip[0]['qb_offline'] == '1') ? ' selected="selected"' : ''; ?>>ON</option>
			</select>
        </div>
      </div>
      
      <div class="col-md-2"></div>
      <div class="form-group">
        <div class="col-md-4"></div>
        <div class="col-md-6">
          <button type="submit" class="btn btn-primary category_button">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>

 
  
  <!-- Modal -->
<div class="modal fade" id="qbmodal" role="dialog" style="z-index: 9999;">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
 <div id="qb_message"></div>
                <div class="container">

                    <form>
                        <div id="account_status">
                            <p>Is your QuickBooks Online Account active</p>
                            <label class="radio-inline">
                                <input type="radio" name="optradio" value="1" class="rbtnaccount">Yes
                            </label>

                            <label class="radio-inline">
                                <input type="radio" name="optradio" value="0" class="rbtnaccount">No
                            </label>

                        </div>
                        <div id="qbdetails_div" style="display: none">
                            <p>Is your QB Online Log-In and Password set in settings</p>
                            <label class="radio-inline">
                                <input type="radio" name="optradio" value="1" class="rbtnsettings">Yes
                            </label>

                            <label class="radio-inline">
                                <input type="radio" name="optradio" value="0" class="rbtnsettings">No
                            </label>

                        </div>

                        <div style="display: none;" id="qb_settings">
                           <div class="row">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="col-md-3 catogery_name">Quickbooks User<strong class="star">*</strong> : </label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control text_input1 required " id="user" name="user" value="<?php echo isset($ip[0]['quickbooksUser']) ? $ip[0]['quickbooksUser'] : ''; ?>" placeholder="Enter Quickbooks User">
                                </div>
                            </div></div>
                            <div class="row">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="col-md-3 catogery_name">Quickbooks Password<strong class="star">*</strong> : </label>
                                <div class="col-md-3">
                                    <input type="password" class="form-control text_input1 required " id="pwd" name="pwd" value="<?php echo isset($ip[0]['quickbooksPwd']) ? $ip[0]['quickbooksPwd'] : ''; ?>" placeholder="Enter Quickbooks Password">
                                </div>
                            </div>
 							</div>
                          <div class="row">
                              <div class="form-groupp col-md-6">
                             <input type="button" class="btn success pull-right" value="Submit" id="btnsaveqb"/></div>
 							</div>
                         <input  type="hidden" value="<?php echo isset($ip[0]['quickbooksUser']) ? '1' : '0'; ?>"  id="checkqbuser" />
                        </div>

                    </form>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default danger" data-dismiss="modal" id="modal_close">Close</button>
            </div>
        </div>

    </div>
</div>

 
 
   <div class="modal fade" id="QbPayment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9999;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Subscription</h4> 
      </div>
      <div class="modal-body">
              
            <iframe style="zoom:0.60" width="99.6%" height="650" src="http://420medsoft.com/index.php/main/subscribeQbPayment/www.cali-oil.com" frameborder="0" allowfullscreen id="subscription" ></iframe>
         
</div>  
      </div>
      
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  
<script type="text/javascript">
function resetinventory()
{
	if(confirm("Are you sure you want to reset your inventory and sales?"))
	{
		var dataString = 'parentID=2'
		$.ajax({
			type: "POST",
			url: '<?php echo base_url('index.php/admininventory/resetInventory'); ?>',
			data: dataString,
			success: function (data) {
				$("#message").html(data);
			}
		});
	}
}
	
	
$("#modal_close").click(function(){
	$("#qb_offline").val("0");
})
$("#btnsaveqb").click(function(){
	
//	var dataString= 'user='+$("#user").val()+'&pwd='+$("#pwd").val();
//	$.ajax({
//			type: "POST",
//			url: '<?php echo base_url('index.php/admin/saveqb'); ?>',
//			data: dataString,
//			success: function (data) {
	$("#qbmodal").modal('hide');
			$("#QbPayment").modal('show');
//			}
//		}); 
})
function showModal(val)
{
	if(val == 1)
	{
		 $("#qbmodal").modal();
	}
}
	$(".rbtnaccount").change(function(){
		 
		if($(this).val() == 1){
			$("#account_status").hide();
			$("#qbdetails_div").show();
		}
		else
			{   
				$("#qb_offline").val("0");
				$("#account_status").show();
				$("#qbdetails_div").hide();
				$("#qb_settings").hide();
				$("#qbmodal").modal('hide');
			}
	})
		$(".rbtnsettings").change(function(){
		if($(this).val() == 0){
			$("#account_status").hide();
			$("#qbdetails_div").hide();
			$("#qb_settings").show();
		}
		else
			{
				$("#qb_offline").val("1");
				$("#account_status").show();
				$("#qbdetails_div").hide();
				$("#qb_settings").hide();
				$("#qbmodal").modal('hide');
			}
	})
	$('#qbmodal').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
})
</script>