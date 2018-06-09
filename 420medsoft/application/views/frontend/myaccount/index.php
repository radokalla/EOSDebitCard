<script type="text/javascript" src="<?php echo base_url('js/ajaxfileupload.js');?>"></script>
<script type="text/javascript">
function ajaxFileUpload(pathsetid , elementid, filetype)
{
	var oldImagePath = $('#'+pathsetid).attr('src');
	$('#'+pathsetid).attr('src','<?php echo base_url('images/loader.gif');?>');
	
	var uplaod_url ='<?php echo base_url(); ?>index.php/myaccount/uploadImage';
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
					$('#'+elementid+'_a').attr('href','<?php echo base_url();?>'+data.img_path);
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
    <h1 class="hd-title-light">My account</h1>
  </div>
  <?php $this->load->view('frontend/includes/sidebar');?>
  <div class="col-md-9">
    <?php if(isset($profileSuccess)){ ?>
    <div class="success"><?php echo $profileSuccess; ?></div>
    <?php } ?>
    <?php if(isset($profileError)){ ?>
    <div class="error"><?php echo $profileError; ?></div>
    <?php } ?>
    <div></div>
    <form method="post" name="test1" role="form" id="registration-form" class="validate-form form-horizontal">
      <div class="form-group">
        <label for="userName" class="control-label col-sm-4 control_labeltext">License No (User Name)<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="userName" name="userName" value="<?php echo $patientDetails['userName'] ?>" placeholder="License No (User Name)" readonly="readonly">
        </div>
      </div>
      <div class="form-group">
        <label for="firstName" class="control-label col-sm-4 control_labeltext">First Name<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" value="<?php echo $patientDetails['firstName'] ?>" required="required">
        </div>
      </div>
      <div class="form-group">
        <label for="lastName"  class="control-label col-sm-4 control_labeltext">Last Name : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo $patientDetails['lastName'] ?>" placeholder="Last Name">
        </div>
      </div>
      
      
      <div class="form-group">
        <label for="lastName"  class="control-label col-sm-4 control_labeltext">Doctor Name<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control alpha" id="doctorName" name="doctorName" value="<?php echo isset($patientDetails['doctorName']) ? $patientDetails['doctorName'] : ''; ?>" placeholder="Doctor Name"  required="required">
        </div>
      </div>
      
      <div class="form-group">
        <label for="address1" class="control-label col-sm-4 control_labeltext">Address 1<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="address1" name="address1" value="<?php echo $patientDetails['address1'] ?>" placeholder="Address 1" required="required">
        </div>
      </div>
      <div class="form-group">
        <label for="address2" class="control-label col-sm-4 control_labeltext">Address 2 : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="address2" name="address2" value="<?php echo $patientDetails['address2'] ?>" placeholder="Address 2">
        </div>
      </div>
      <div class="form-group">
        <label for="city" class="control-label col-sm-4 control_labeltext">City<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="city" name="city" value="<?php echo $patientDetails['city'] ?>" placeholder="City" required="required">
        </div>
      </div>
      <div class="form-group">
        <label for="state" class="control-label col-sm-4 control_labeltext">State/Province<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="state" name="state" value="<?php echo $patientDetails['state'] ?>" placeholder="State/Province" required="required">
        </div>
      </div>
      <!--<div class="form-group">
        <label for="country" class="control-label col-sm-4 control_labeltext">Country<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
        <label class="form-control">California</label>
          <input type="text" class="form-control" id="country" name="country" value="<?php //echo $patientDetails['country'] ?>" placeholder="Country" required="required">
        </div>
      </div>-->
        <input type="hidden" value="California" id="country" name="country" />
      <div class="form-group">
        <label for="zip" class="control-label col-sm-4 control_labeltext">Zip/Postal Code<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="zip" name="zip" value="<?php echo $patientDetails['zip'] ?>" placeholder="Zip/Postal Code" required="required">
        </div>
      </div>
      <div class="form-group">
        <label for="phone" class="control-label col-sm-4 control_labeltext">Phone<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $this->session->TelephoneNumberFormat($patientDetails['phone']); ?>" placeholder="Phone" required="required">
        </div>
      </div>
      <div class="form-group">
        <label for="email" class="control-label col-sm-4 control_labeltext">E-mail<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="email" class="form-control" id="email" name="email" value="<?php echo $patientDetails['email'] ?>" placeholder="E-mail" required="required">
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="control-label col-sm-4 control_labeltext">ID Card</label>
        <div class="col-md-6">
          <input type="file" name="upload_idcard" id="upload-idcard"  onchange="ajaxFileUpload('upload-appl-imgPath_img',this.id,'');" class="input-file"/>
          <?php /*  loadFile1(this,'upload-appl-imgPath_img'); return ajaxFileUpload('idcardimagepath','upload-idcard'); */ ?>
          <input type="hidden" name="idcard" id="upload-idcard_h" style="margin:5px 0px 10px 0px" value="<?php echo isset($patientDetails['idcard']) ? $patientDetails['idcard'] : '';?>"/>
          <a href="<?php echo base_url(isset($patientDetails['idcard']) ? $patientDetails['idcard'] : '');?>" id="upload-idcard_a" target="_blank"><img width="125" name="upload-appl-imgPath_img" id="upload-appl-imgPath_img"  style='margin:5px 0px 10px 0px;' src="<?php echo base_url(isset($patientDetails['idcard']) ? $patientDetails['idcard'] : '');?>"  /></a>
          <span id="upload-appl-success-mssg"></span> <span id="upload-appl-error-mssg"></span>
        </div>
      </div>

<?php //echo "<pre>";print_r($patientDetails);?>
      <div class="form-group">
        <label for="exampleInputEmail1" class="control-label col-sm-4 control_labeltext">Medical Card</label>
        <div class="col-md-6">
          <input type="file" name="upload_medical" id="upload-medical"   onchange="ajaxFileUpload('upload-appl-imgPath_img1',this.id,'');"  class="input-file"/>
          <input type="hidden" name="medical" id="upload-medical_h" style="margin:5px 0px 10px 0px" value="<?php echo isset($patientDetails['medical']) ? $patientDetails['medical'] : '';?>"/>
          <a href="<?php echo base_url(isset($patientDetails['medical']) ? $patientDetails['medical'] : '');?>" id="upload-medical_a" target="_blank"><img name="upload-appl-imgPath_img" width="125" id="upload-appl-imgPath_img1"  style='margin:5px 0px 10px 0px;' src="<?php echo base_url(isset($patientDetails['medical']) ? $patientDetails['medical'] : '');?>"  /></a>
          <span id="upload-appl-success-mssg"></span> <span id="upload-appl-error-mssg"></span>
        </div>
      </div>
      <input type="hidden" id="cardType" name="cardType" value="<?php echo $patientCreditCardDetails['cardType']; ?>">
      <?php if(empty($patientCreditCardDetails['cardNumber'])) { ?>
      <input type="hidden" id="nameOnCard" name="nameOnCard" value="<?php echo $patientCreditCardDetails['nameOnCard']; ?>">
      <input type="hidden" id="cardNumber" name="cardNumber" value="<?php echo $patientCreditCardDetails['cardNumber'] ?>">
      <input type="hidden" id="CVVCode" name="CVVCode" value="<?php echo $patientCreditCardDetails['CVVCode'] ?>">
      <input type="hidden" id="expiry" name="expiry_month" value="<?php echo $patientCreditCardDetails['expiry_month'] ?>">
      <input type="hidden" id="expiry" name="expiry_year" value="<?php echo $patientCreditCardDetails['expiry_year'] ?>">
      <?php }else{ ?>
      
      <div class="form-group">
        <label for="nameOnCard" class="control-label col-sm-4 control_labeltext">Name as it appears on Card<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="nameOnCard" name="nameOnCard" value="<?php echo $patientCreditCardDetails['nameOnCard'] ?>" placeholder="Name as it appears on Card" required="required" >
        </div>
      </div>
      
      <div class="form-group">
        <label for="cardNumber" class="control-label col-sm-4 control_labeltext">Credit Card Number<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
        <?php  //$str = ''; for($i=0; $i < strlen($patientCreditCardDetails['cardNumber'])-4 ; $i++){ $str .= 'X'; } ?>
        <?php //echo $str . substr($patientCreditCardDetails['cardNumber'], strlen($patientCreditCardDetails['cardNumber'])-4, 4 ) ?>
          <input type="text" class="form-control" id="cardNumber" name="cardNumber" value="<?php echo $patientCreditCardDetails['cardNumber'] ?>" placeholder="Credit Card Number" required="required" >
        </div>
      </div>
      <div class="form-group">
        <label for="CVVCode" class="control-label col-sm-4 control_labeltext">CVC Code<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="CVVCode" name="CVVCode" value="<?php echo $patientCreditCardDetails['CVVCode'] ?>" placeholder="CVC Code"  required="required">
        </div>
      </div>
      <div class="form-group">
        <label for="expiry" class="control-label col-sm-4 control_labeltext">Expiration Month<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="expiry_month" name="expiry_month" value="<?php echo $patientCreditCardDetails['expiry_month'] ?>" placeholder="Expiration Month"  required="required">
        </div>
      </div>
      <div class="form-group">
        <label for="expiry" class="control-label col-sm-4 control_labeltext">Expiration Year<strong class="star">*</strong> : </label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="expiry_year" name="expiry_year" value="<?php echo $patientCreditCardDetails['expiry_year'] ?>" placeholder="Expiration Year"  required="required">
        </div>
      </div>
      
      <?php } ?>
      <div class="form-group">
        <button type="submit" class="btn btn-primary btn-large span6 righister">Update</button>
      </div>
    </form>
  </div>
</div>
</div>
