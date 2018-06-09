<script type="text/javascript" src="<?php echo base_url('js/ajaxfileupload.js');?>"></script>
<script type="text/javascript">
function ajaxFileUpload(pathsetid , elementid, filetype)
{
	var oldImagePath = $('#'+pathsetid).attr('src');
	$('#'+pathsetid).attr('src','<?php echo base_url('images/loader.gif');?>');
	
	var uplaod_url ='<?php echo base_url(); ?>index.php/adminpatients/uploadImage';
	var newelementid = '';
	newelementid = elementid;
	$.ajaxFileUpload
	(
		{
			url:uplaod_url,
			secureuri:false,
			fileElementId:elementid,
			dataType: 'json',
			data:{ name:elementid, showid:pathsetid, filetype:filetype,page:'1'},
			success: function (data)
			{
				console.log(data)
				if(data.error=="")
				{
					$('#'+elementid+'_h').val(data.img_path);
					$('#'+pathsetid).attr('src','<?php echo base_url();?>'+data.img_path);
					$('#'+elementid+'_a').attr('href','<?php echo base_url();?>'+data.img_path);
				}else{
					$('#'+pathsetid).attr('src',oldImagePath);
					//alert(data.error);
				}
			},
			error:function(XMLHttpRequest,textStatus,errorThrown)
		    {
				$('#'+pathsetid).attr('src',oldImagePath);
			   //alert("There was an <strong>"+errorThrown+"</strong> error due to  <strong>"+XMLHttpRequest.responseText+" condition");
		    }   
		}
	);	
}

$(document).ready(function(){
	$("#userName").on('blur', function(){
		$("#userName").removeClass("regster_success").removeClass("regster_wrong").addClass("spinner");
		var userName = $(this).val();
		var dataString = "userName="+userName;
		$.ajax({
			type: "POST",
			url: '<?php echo base_url(); ?>index.php/main/checkusername',
			data: dataString,
			success: function (data) {
				if(data == true)
				{
					$("#userName").removeClass("regster_wrong").removeClass("spinner").addClass("regster_success");
				}
				else
				{
					$("#userName").removeClass("spinner").removeClass("regster_success").addClass("regster_wrong");
					$("#userName").val('');
				}
			}
		});
	})
});
</script>

<div class="memberlogin-wps col-md-12 products_page">
<h2><?php echo isset($patientDetails['patientID']) ? 'Update ' : 'Add '; ?>Patient Details</h2>
    <?php if(isset($profileSuccess)){ ?>
    <div class="success"><?php echo $profileSuccess; ?></div>
    <?php } ?>
    <?php if(isset($profileError)){ ?>
    <div class="error"><?php echo $profileError; ?></div>
    <?php } ?>
    <div></div>
    <?php //echo "<pre>";print_r($patientDetails);?>
    <form method="post" role="form" id="registration-form" class="validate-form form-horizontal">
      <input type="hidden" name="patient_id" value="<?php echo isset($patientDetails['patientID']) ? $patientDetails['patientID'] : ''; ?>" />
      <div class="form-group">
        <label for="userName" class="control-label col-sm-4 control_labeltext">License No (User Name)<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control <?php echo (!isset($patientDetails['patientID']) ? 'required' : ''); ?>" id="userName" name="userName" value="<?php echo isset($patientDetails['userName']) ? $patientDetails['userName'] : ''; ?>" placeholder="License No (User Name)" <?php echo isset($patientDetails['userName']) ? ' readonly="readonly"' : ''; ?>>
        </div>
      </div>
      <div class="form-group">
        <label for="firstName" class="control-label col-sm-4 control_labeltext">First Name<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control required alpha" id="firstName" name="firstName" placeholder="First Name" value="<?php echo isset($patientDetails['firstName']) ? $patientDetails['firstName'] : ''; ?>">
        </div>
      </div>
      
      <div class="form-group">
        <label for="lastName"  class="control-label col-sm-4 control_labeltext">Last Name : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control alpha" id="lastName" name="lastName" value="<?php echo isset($patientDetails['lastName']) ? $patientDetails['lastName'] : ''; ?>" placeholder="Last Name">
        </div>
      </div>
      
      
      <div class="form-group">
        <label for="lastName"  class="control-label col-sm-4 control_labeltext">Doctor Name<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control required alpha" id="doctorName" name="doctorName" value="<?php echo isset($patientDetails['doctorName']) ? $patientDetails['doctorName'] : ''; ?>" placeholder="Doctor Name">
        </div>
      </div>
       <?php if(!isset($patientDetails['patientID'])){ ?>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-sm-4 catogery_name">Password<strong class="star">*</strong> :</label>
        <div class="col-sm-6">
          <input type="password" class="form-control text_input1 required" id="passowrd" name="password" value="" placeholder="Enter Password" >
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-sm-4 catogery_name">Confirm Password<strong class="star">*</strong> :</label>
        <div class="col-sm-6">
          <input type="password" class="form-control text_input1 required" equalto="passowrd" id="cpassowrd" name="cpassowrd" value="" placeholder="Enter Confirm Password"  >
        </div>
      </div>
      <?php } ?>
      
      <div class="form-group">
        <label for="address1" class="control-label col-sm-4 control_labeltext">Street<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control required " id="address1" name="address1" value="<?php echo isset($patientDetails['address1']) ? $patientDetails['address1'] : ''; ?>" placeholder="Address 1">
        </div>
      </div>
      <div class="form-group">
        <label for="address2" class="control-label col-sm-4 control_labeltext">Appartment : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="address2" name="address2" value="<?php echo isset($patientDetails['address2']) ? $patientDetails['address2'] : ''; ?>" placeholder="Address 2">
        </div>
      </div>
      <div class="form-group">
        <label for="city" class="control-label col-sm-4 control_labeltext">City<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control required " id="city" name="city" value="<?php echo isset($patientDetails['city']) ? $patientDetails['city'] : ''; ?>" placeholder="City">
        </div>
      </div>
      <div class="form-group">
        <label for="state" class="control-label col-sm-4 control_labeltext">State/Province<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control required " id="state" name="state" value="<?php echo isset($patientDetails['state']) ? $patientDetails['state'] : ''; ?>" placeholder="State/Province">
        </div>
      </div>
      <!--<div class="form-group">
        <label for="country" class="control-label col-sm-4 control_labeltext">Country<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control required alpha" id="country" name="country" value="<?php //echo isset($patientDetails['country']) ? $patientDetails['country'] : ''; ?>" placeholder="Country">
        </div>
      </div>-->
          <input type="hidden" id="country" name="country" value="<?php echo isset($patientDetails['country']) ? $patientDetails['country'] : ''; ?>">
      <div class="form-group">
        <label for="zip" class="control-label col-sm-4 control_labeltext">Zip/Postal Code<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control required alphanum" id="zip" name="zip" value="<?php echo isset($patientDetails['zip']) ? $patientDetails['zip'] : ''; ?>" placeholder="Zip/Postal Code">
        </div>
      </div>
      <div class="form-group">
        <label for="phone" class="control-label col-sm-4 control_labeltext">Phone<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control required num" id="phone" name="phone" value="<?php echo isset($patientDetails['phone']) ? $this->session->TelephoneNumberFormat($patientDetails['phone']) : ''; ?>" placeholder="Phone">
        </div>
      </div>
      <div class="form-group">
        <label for="email" class="control-label col-sm-4 control_labeltext">E-mail<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control required email" id="email" name="email" value="<?php echo isset($patientDetails['email']) ? $patientDetails['email'] : ''; ?>" placeholder="E-mail" >
        </div>
      </div>
       <?php if(isset($patientCreditCardDetails['cardNumber']) && !empty($patientCreditCardDetails['cardNumber'])){ ?>
      <div class="form-group">
        <label for="nameOnCard" class="control-label col-sm-4 control_labeltext">Name as it appears on Card : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control alpha" id="nameOnCard" name="nameOnCard" value="<?php echo isset($patientCreditCardDetails['nameOnCard']) ? $patientCreditCardDetails['nameOnCard'] : ''; ?>" placeholder="Name as it appears on Card" >
        </div>
      </div>
      <input type="hidden" id="cardType" name="cardType" />
      <?php /*?><div class="form-group">
        <label for="cardType" class="control-label col-sm-4 control_labeltext">Credit Card Type : </label>
        <div class="col-sm-6">
          <select class="form-control " id="cardType" name="cardType">
            <option value="">Please select</option>
            <option value="Visa" <?php if($patientCreditCardDetails['cardType'] == 'Visa'){ ?>selected="selected"<?php } ?>>Visa</option>
            <option value="MasterCard" <?php if($patientCreditCardDetails['cardType'] == 'MasterCard'){ ?>selected="selected"<?php } ?>>MasterCard</option>
            <option value="American Express" <?php if($patientCreditCardDetails['cardType'] == 'American Express'){ ?>selected="selected"<?php } ?>>American Express</option>
          </select>
        </div>
      </div><?php */?>
      <div class="form-group">
        <label for="cardNumber" class="control-label col-sm-4 control_labeltext">Credit Card Number : </label>
        <div class="col-sm-6">
         <?php  $str = ''; for($i=0; $i < strlen($patientCreditCardDetails['cardNumber'])-4 ; $i++){ $str .= 'X'; } ?>
        <?php echo $str . substr($patientCreditCardDetails['cardNumber'], strlen($patientCreditCardDetails['cardNumber'])-4, 4 ) ?>
          <input type="hidden" class="form-control num" id="cardNumber" name="cardNumber" value="<?php echo isset($patientCreditCardDetails['cardNumber']) ? $patientCreditCardDetails['cardNumber'] : ''; ?>" placeholder="Credit Card Number" >
        </div>
      </div>
      <div class="form-group">
        <label for="CVVCode" class="control-label col-sm-4 control_labeltext">CVC Code : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control num" id="CVVCode" name="CVVCode" value="<?php echo isset($patientCreditCardDetails['CVVCode']) ? $patientCreditCardDetails['CVVCode'] : ''; ?>" placeholder="CVC Code">
        </div>
      </div>
      <div class="form-group">
          <label for="expiry" class="control-label col-sm-4 control_labeltext">Expiration Month<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <select id="expiry_month" name="expiry_month" >
            <?php for($year = 1; $year <= 12; $year++){ ?>
            	<option value="<?php echo $year; ?>" <?php echo isset($patientCreditCardDetails['expiry_month']) && ($patientCreditCardDetails['expiry_month'] == $year) ? ' selected="selected"' : ''; ?>><?php echo $year; ?></option>
                <?php } ?>
            </select>
          </div>
        </div>
       <div class="form-group">
          <label for="expiry" class="control-label col-sm-4 control_labeltext">Expiration Year<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <select id="expiry_year" name="expiry_year" >
            <?php for($year = date("Y"); $year <= date("Y")+20; $year++){ ?>
            	<option value="<?php echo $year; ?>" <?php echo isset($patientCreditCardDetails['expiry_year']) && ($patientCreditCardDetails['expiry_year'] == $year) ? ' selected="selected"' : ''; ?>><?php echo $year; ?></option>
                <?php } ?>
            </select>
          </div>
        </div>
      <?php }?>
      <div class="form-group">
        <label for="exampleInputEmail1" class="control-label col-sm-4 control_labeltext">ID Card</label>
        <div class="col-md-6">
          <input type="file" name="upload_idcard" id="upload-idcard"  onchange="ajaxFileUpload('upload-appl-imgPath_img',this.id,'');" class="input-file " placeholder="ID Card"/>
          <?php /*  loadFile1(this,'upload-appl-imgPath_img'); return ajaxFileUpload('idcardimagepath','upload-idcard'); */ ?>
          <input type="hidden" name="idcard" id="upload-idcard_h" style="margin:5px 0px 10px 0px" value="<?php echo isset($patientDetails['idcard']) ? $patientDetails['idcard'] : '';?>"/>
          <a id="upload-idcard_a" href="<?php echo base_url(isset($patientDetails['idcard']) ? $patientDetails['idcard'] : '');?>" target="_blank"><img alt="" width="125" name="upload-appl-imgPath_img" id="upload-appl-imgPath_img"  style='margin:5px 0px 10px 0px;' src="<?php echo base_url(isset($patientDetails['idcard']) ? $patientDetails['idcard'] : '');?>"  /></a>
          <span id="upload-appl-success-mssg"></span> <span id="upload-appl-error-mssg"></span>
        </div>
      </div>
<?php //echo "<pre>";print_r($patientDetails);?>
      <div class="form-group">
        <label for="exampleInputEmail1" class="control-label col-sm-4 control_labeltext">Medical Card</label>
        <div class="col-md-6">
          <input type="file" name="upload_medical" id="upload-medical"   onchange="ajaxFileUpload('upload-appl-imgPath_img1',this.id,'');"  class="input-file " placeholder="Medical Card"/>
          <input type="hidden" name="medical" id="upload-medical_h" style="margin:5px 0px 10px 0px" value="<?php echo isset($patientDetails['medical']) ? $patientDetails['medical'] : '';?>"/>
           <a id="upload-medical_a" href="<?php echo base_url(isset($patientDetails['medical']) ? $patientDetails['medical'] : '');?>" target="_blank"><img alt="" name="upload-appl-imgPath_img" width="125" id="upload-appl-imgPath_img1"  style='margin:5px 0px 10px 0px;' src="<?php echo base_url(isset($patientDetails['medical']) ? $patientDetails['medical'] : '');?>"  /></a>
          <span id="upload-appl-success-mssg"></span> <span id="upload-appl-error-mssg"></span>
        </div>
      </div>
	  
       <div class="form-group">
          <label for="expiryDate" class="control-label col-sm-4 control_labeltext">Expiry Date<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control required" id="expiryDate" name="expiryDate" placeholder="Expiry Date" value="<?php echo isset($patientDetails['expiryDate']) ? $patientDetails['expiryDate'] : ''; ?>">
          </div>
        </div>
        
        
       <div class="form-group">
          <label for="expiryDate" class="control-label col-sm-4 control_labeltext">Short Notes : </label>
          <div class="col-sm-6">
          <textarea class="form-control" id="notes" name="notes" placeholder="Short Notes"><?php echo isset($patientDetails['notes']) ? $patientDetails['notes'] : ''; ?></textarea>
          </div>
        </div>
        
      <div class="form-group">
        <button type="submit" class="btn btn-primary btn-large span6 righister"><?php echo (isset($patientDetails)?'Update':'Add'); ?></button>
      </div>
    </form>
  </div>
<link href="<?php echo base_url('css/datepicker3.css'); ?>" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url('js/bootstrap-datepicker.js'); ?>"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#expiryDate").datepicker({
		 format: "yyyy-mm-dd",
   		 startDate: "+1days"
	});
});
</script>