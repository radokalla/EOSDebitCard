<script type="text/javascript" src="<?php echo base_url('js/ajaxfileupload.js');?>"></script>
<script type="text/javascript">
function ajaxFileUpload(pathsetid , elementid, filetype)
{
	var oldImagePath = $('#'+pathsetid).attr('src');
	$('#'+pathsetid).attr('src','<?php echo base_url('images/loader.gif');?>');
	
	var uplaod_url ='<?php echo base_url(); ?>index.php/members/uploadImage';
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
				if(data.error=="")
				{
					$('#'+elementid+'_h').val(data.img_path);
					$('#'+pathsetid).attr('src','<?php echo base_url();?>'+data.img_path);
				}else{					
					$('#'+pathsetid).attr('src',oldImagePath);
					alert(data.error);
				}
			},
			error:function(XMLHttpRequest,textStatus,errorThrown)
		    {
				$('#'+pathsetid).attr('src',oldImagePath);
			   //alert("There was an <strong>"+errorThrown+"</strong> error due to  <strong>"+textStatus+" condition");
		    }   
		}
	);	
}

</script>
<div class="row">
  <div class="col-md-12">
    <div class="panel_bodybg" style="overflow:hidden;">
      <h2 class="register_header">Patient Registration</h2>
      <?php echo validation_errors(); ?>
      <div class="col-md-12 col-sm-12"><form method="post" role="form" id="registration-form" class="validate-form form-horizontal">
        <p class="registration" style="padding:0px;">Thank you for taking the time to register with 420MedSoft Registration.<?php /*?> is FREE. Once you register you will gain access to:</br>
          Special pricing on products and services</br>
          A range of ordering and shipping tools that will save you time and money<?php */?></br>
          </br>
          Registration is fast and easy. Your privacy is our number one priority and we share your information with no one.</br>
        </p>
        <div class="form-group">
          <label for="firstName" class="control-label col-sm-4 control_labeltext">First Name<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control required" id="firstName" name="firstName" placeholder="First Name">
          </div>
        </div>
        <div class="form-group">
          <label for="lastName"  class="control-label col-sm-4 control_labeltext">Last Name : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name">
          </div>
          <div class="col-sm-1"></div>
        </div>
        
        
      <div class="form-group">
        <label for="lastName"  class="control-label col-sm-4 control_labeltext">Doctor Name<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control required" id="doctorName" name="doctorName" value="" placeholder="Doctor Name">
        </div>
      </div>
      
        <div class="form-group">
          <label for="address1" class="control-label col-sm-4 control_labeltext">Address 1<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control required" id="address1" name="address1" placeholder="Address 1">
          </div>
        </div>
        <div class="form-group">
          <label for="address2" class="control-label col-sm-4 control_labeltext">Address 2 : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="address2" name="address2" placeholder="Address 2">
          </div>
        </div>
        <div class="form-group">
          <label for="city" class="control-label col-sm-4 control_labeltext">City<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control required" id="city" name="city" placeholder="City">
          </div>
        </div>
        <div class="form-group">
          <label for="state" class="control-label col-sm-4 control_labeltext">State/Province<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control required" id="state" name="state" placeholder="State/Province">
          </div>
        </div>
        <!--<div class="form-group">
          <label for="country" class="control-label col-sm-4 control_labeltext">Country<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
         <label  class="control-label col-sm-4 control_labeltext"  style="text-align:left;">  California</label>
          </div>
        </div>-->
            <input type="hidden" class="form-control" id="country" name="country" placeholder="Country" value="California">
        <div class="form-group">
          <label for="zip" class="control-label col-sm-4 control_labeltext">Zip/Postal Code<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control required num" id="zip" name="zip" placeholder="Zip/Postal Code">
          </div>
        </div>
        <div class="form-group">
          <label for="userName" class="control-label col-sm-4 control_labeltext">License No (User Name)<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control required" id="userName" name="userName" placeholder="License No (User Name)">
          </div>
        </div>
        <div class="form-group">
          <label for="password" class="control-label col-sm-4 control_labeltext">Password<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="password" class="form-control required" id="password" name="password" placeholder="Password">
          </div>
        </div>
        <div class="form-group">
          <label for="confirmPassword" class="control-label col-sm-4 control_labeltext">Confirm Password<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="password" class="form-control required" equalto="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password">
          </div>
        </div>
        <div class="form-group">
          <label for="phone" class="control-label col-sm-4 control_labeltext">Phone<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control required num" id="phone" name="phone" placeholder="Phone">
          </div>
        </div>
        <div class="form-group">
          <label for="email" class="control-label col-sm-4 control_labeltext">E-mail<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control required email" id="email" name="email" placeholder="E-mail">
          </div>
        </div>
       <!-- <div class="form-group">
          <label for="nameOnCard" class="control-label col-sm-4 control_labeltext">Name as it appears on Card<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="nameOnCard" name="nameOnCard" placeholder="Name as it appears on Card" >
          </div>
        </div>
        <div class="form-group">
          <label for="cardType" class="control-label col-sm-4 control_labeltext">Credit Card Type<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <select class="form-control" id="cardType" name="cardType" required="required">
              <option value="">Please select</option>
              <option value="Visa">Visa</option>
              <option value="MasterCard">MasterCard</option>
              <option value="American Express">American Express</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="cardNumber" class="control-label col-sm-4 control_labeltext">Credit Card Number<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="cardNumber" name="cardNumber" placeholder="Credit Card Number" >
          </div>
        </div>
        <div class="form-group">
          <label for="CVVCode" class="control-label col-sm-4 control_labeltext">CVC Code<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="CVVCode" name="CVVCode" placeholder="CVC Code" >
          </div>
        </div>
        <div class="form-group">
          <label for="expiry" class="control-label col-sm-4 control_labeltext">Expiration Date<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="expiry" name="expiry" placeholder="Expiration Date" >
          </div>
        </div>-->
        
      <div class="form-group">
        <label for="exampleInputEmail1" class="control-label col-sm-4 control_labeltext">ID Card</label>
        <div class="col-md-6 col-sm-6">
          <input type="file" name="upload_idcard" id="upload-idcard"  onchange="ajaxFileUpload('upload-appl-imgPath_img',this.id,'');" class="input-file"/>
          <?php /*  loadFile1(this,'upload-appl-imgPath_img'); return ajaxFileUpload('idcardimagepath','upload-idcard'); */ ?>
          <input type="hidden" name="idcard" id="upload-idcard_h" style="margin:5px 0px 10px 0px" value="<?php echo isset($patientDetails['idcard']) ? $patientDetails['idcard'] : '';?>"/>
          <img alt="" width="125" name="upload-appl-imgPath_img" id="upload-appl-imgPath_img"  style='margin:5px 0px 10px 0px;' src="<?php echo base_url(isset($patientDetails['idcard']) ? $patientDetails['idcard'] : '');?>"  />
          <span id="upload-appl-success-mssg"></span> <span id="upload-appl-error-mssg"></span>
        </div>
      </div>

<?php //echo "<pre>";print_r($patientDetails);?>
      <div class="form-group">
        <label for="exampleInputEmail1" class="control-label col-sm-4 control_labeltext">Medical Card</label>
        <div class="col-md-6 col-sm-6">
          <input type="file" name="upload_medical" id="upload-medical"   onchange="ajaxFileUpload('upload-appl-imgPath_img1',this.id,'');"  class="input-file"/>
          <input type="hidden" name="medical" id="upload-medical_h" style="margin:5px 0px 10px 0px" value="<?php echo isset($patientDetails['medical']) ? $patientDetails['medical'] : '';?>"/>
          <img alt="" name="upload-appl-imgPath_img" width="125" id="upload-appl-imgPath_img1"  style='margin:5px 0px 10px 0px;' src="<?php echo base_url(isset($patientDetails['medical']) ? $patientDetails['medical'] : '');?>"  />
          <span id="upload-appl-success-mssg"></span> <span id="upload-appl-error-mssg"></span>
        </div>
      </div>
      
      <div class="form-group">
          <label for="expiryDate" class="control-label col-sm-4 control_labeltext">Expiry Date<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control required" readonly id="expiryDate" name="expiryDate" placeholder="Expiry Date">
          </div>
        </div>
        
        <div class="form-group">
        
        <div class="col-md-6 col-md-offset-4 col-sm-6 col-sm-offset-4">
          <button type="submit" class="btn btn-primary btn-lg col-md-12 col-sm-12">Register</button>
        </div>
        
        </div>
        
      </form></div>
    </div>
  </div>
</div>
<link href="<?php echo base_url('css/datepicker3.css'); ?>" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url('js/bootstrap-datepicker.js'); ?>"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#expiryDate").datepicker({
		 format: "yyyy-mm-dd",
   		 startDate: "+1days"
	});

	$("#userName").on('blur', function(){
		$("#userName").removeClass("regster_success").removeClass("regster_wrong").addClass("spinner");
		var userName = $(this).val();
		var dataString = "userName="+userName;
		$.ajax({
			type: "POST",
			url: '<?php echo base_url('index.php/main/checkusername'); ?>',
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
