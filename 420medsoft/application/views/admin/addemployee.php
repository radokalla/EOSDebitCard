<script type="text/javascript" src="<?php echo base_url('js/ajaxfileupload.js');?>"></script>
<script type="text/javascript">
function ajaxFileUpload(pathsetid , elementid, filetype)
{
	var uplaod_url ='<?php echo base_url(); ?>index.php/admin/uploadImage';
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
					alert(data.error);
				}
			},
			error:function(XMLHttpRequest,textStatus,errorThrown)
		    {
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
			url: '<?php echo base_url(); ?>index.php/admin/checkEmp',
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
  <h2><?php echo isset($employee['ID']) ? 'Edit' : 'Add'; ?> Employee</h2>
  <div class="col-md-12"> <?php echo validation_errors(); ?>
    <form method="post" role="form" id="add-category-form" class="validate-form">
      <input type="hidden" name="employee_id" value="<?php echo isset($employee['ID']) ? $employee['ID'] : ''; ?>" />
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">User Name<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required alpha" id="userName" name="userName" value="<?php echo isset($employee['userName']) ? $employee['userName'] : ''; ?>" placeholder="Enter User Name"   <?php echo isset($employee['userName']) ? ' readonly="readonly"' : ''; ?>>
        </div>
      </div>
      <?php if(!isset($employee['ID'])){ ?>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Password<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="password" class="form-control text_input1 required" id="password" name="password" value="" placeholder="Enter Password" >
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Confirm Password<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="password" class="form-control text_input1 required" equalto="password" id="cpassword" name="cpassword" value="" placeholder="Enter Confirm Password">
        </div>
      </div>
      <?php } ?>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">First Name<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required alpha" id="firstName" name="firstName" value="<?php echo isset($employee['firstName']) ? $employee['firstName'] : ''; ?>" placeholder="Enter First Name" >
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Last Name<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required alpha" id="lastName" name="lastName" value="<?php echo isset($employee['lastName']) ? $employee['lastName'] : ''; ?>" placeholder="Enter Last Name" >
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">address<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required " id="address" name="address" value="<?php echo isset($employee['address']) ? $employee['address'] : ''; ?>" placeholder="Enter Address">
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Phone Number<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required num" id="phone" name="phone" value="<?php echo isset($employee['phone']) ? $employee['phone'] : ''; ?>" placeholder="Enter Phone Number" >
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Tax id<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required alphanum" id="tax" name="tax" value="<?php echo isset($employee['tax']) ? $employee['tax'] : ''; ?>" placeholder="Enter Tax Id" >
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">City<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required " id="city" name="city" value="<?php echo isset($employee['city']) ? $employee['city'] : ''; ?>" placeholder="Enter City">
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">State<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required " id="state" name="state" value="<?php echo isset($employee['state']) ? $employee['state'] : ''; ?>" placeholder="Enter State">
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Zip Code<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required num" id="zip" name="zip" value="<?php echo isset($employee['zip']) ? $employee['zip'] : ''; ?>" placeholder="Enter Zip Code">
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Driver<strong class="star">*</strong> : </label>
        <div class="col-md-6">
                <select name="driver" id="driver" class="text_input2">
                 <option value="0">Not a Driver</option>
            <?php foreach($drivers['workers'] as $driver){ if( !in_array($driver['id']."_". $drivers['id'], $existeddrivers)){  ?>
            <option value="<?php echo $driver['id']."_". $drivers['id'];?>" <?php echo isset($employee['driverid'])&&($employee['driverid'] == $driver['id']."_". $drivers['id']) ? "selected" : ""; ?>><?php echo $driver['name'];?></option>
            <?php } } ?>
          </select>
        </div>
      </div>
      
      <div class="form-group">
        <label for="exampleInputEmail1" class="control-label col-sm-4 control_labeltext">ID Card : </label>
        <div class="col-md-6">
          <input type="file" name="upload_idcard" id="upload-idcard"  onchange="ajaxFileUpload('upload-appl-imgPath_img',this.id,'');" class="input-file " placeholder="ID Card"/>
          <?php /*  loadFile1(this,'upload-appl-imgPath_img'); return ajaxFileUpload('idcardimagepath','upload-idcard'); */ ?>
          <input type="hidden" name="idcard" id="upload-idcard_h" style="margin:5px 0px 10px 0px" value="<?php echo isset($employee['idcard']) ? $employee['idcard'] : '';?>"/>
          <img alt="" width="125" name="upload-appl-imgPath_img" id="upload-appl-imgPath_img"  style='margin:5px 0px 10px 0px;' src="<?php echo base_url(isset($employee['idcard']) ? $employee['idcard'] : '');?>"  /> <span id="upload-appl-success-mssg"></span> <span id="upload-appl-error-mssg"></span> </div>
      </div>
      <?php //echo "<pre>";print_r($patientDetails);?>
      <div class="form-group">
        <label for="exampleInputEmail1" class="control-label col-sm-4 control_labeltext">Medical Card : </label>
        <div class="col-md-6">
          <input type="file" name="upload_medical" id="upload-medical"   onchange="ajaxFileUpload('upload-appl-imgPath_img1',this.id,'');"  class="input-file " placeholder="Medical Card"/>
          <input type="hidden" name="medical" id="upload-medical_h" style="margin:5px 0px 10px 0px" value="<?php echo isset($employee['medical']) ? $employee['medical'] : '';?>"/>
          <img alt="" name="upload-appl-imgPath_img" width="125" id="upload-appl-imgPath_img1"  style='margin:5px 0px 10px 0px;' src="<?php echo base_url(isset($employee['medical']) ? $employee['medical'] : '');?>"  /> <span id="upload-appl-success-mssg"></span> <span id="upload-appl-error-mssg"></span> </div>
      </div>
      <div class="col-md-2"></div>
      <div class="form-group">
        <div class="col-md-4"></div>
        <div class="col-md-6">
          <button type="submit" class="btn btn-primary category_button"><?php echo isset($employee['ID']) ? 'Update' : 'Add'; ?></button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>
