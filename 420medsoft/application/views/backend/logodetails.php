<script type="text/javascript" src="<?php echo base_url('js/ajaxfileupload.js');?>"></script>
<script type="text/javascript">
function ajaxFileUpload(pathsetid , elementid, filetype)
{
	var oldImagePath = $('#'+pathsetid).attr('src');
	$('#'+pathsetid).attr('src','<?php echo base_url('images/loader.gif');?>');
	
	var uplaod_url ='<?php echo base_url(); ?>index.php/main/uploadImage';
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
<div class="memberlogin-wps col-md-12 products_page">
  <h2>Package details</h2>
  
  <div class="col-md-12"> <?php echo validation_errors(); ?>
  <div id="message"></div>
 
   <!-- <form method="post" action="<?php echo base_url('index.php/backend/delsubscriberupdate/'.$subscpt['SubscriberID']); ?>" role="form" id="add-category-form" class="validate-form" enctype="multipart/form-data">-->
     <form  class="validate-form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="SubscriberID" value="<?php echo isset($subscpt['SubscriberID']) ? $subscpt['SubscriberID'] : ''; ?>" />
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Name<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required " id="Name" name="name" value="<?php echo isset($subscpt['username']) ? $subscpt['username'] : ''; ?>" placeholder="Name"   >
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Email<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required email" id="email" name="email" value="<?php echo isset($subscpt['email']) ? $subscpt['email'] : ''; ?>" placeholder="Email"  <?php echo isset($subscpt['email']) ? 'readonly' : ''; ?>/>
        </div>
      </div>
	  <?php if(!isset($subscpt['email'])) {?>
	  <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Password<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="password" class="form-control text_input1 required " id="password" name="password" value="" placeholder="Password"  <?php echo isset($subscpt['email']) ? 'readonly' : ''; ?>/>
        </div>
      </div>
	  <?php }?>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Address<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <textarea type="text" class="form-control text_input1 required " id="address" name="address" cols="5" rows="3" value="<?php echo isset($subscpt['address']) ? $subscpt['address'] : ''; ?>" ><?php echo isset($subscpt['address']) ? $subscpt['address'] : ''; ?></textarea>
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Phone Number<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required num" maxlength="10" id="PhoneNumber" name="PhoneNumber" value="<?php echo isset($subscpt['PhoneNumber']) ? $subscpt['PhoneNumber'] : ''; ?>" placeholder="Phone Number"   >
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Domain Name<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required url" id="DomainName" name="DomainName" value="<?php echo isset($subscpt['DomainName']) ? $subscpt['DomainName'] : ''; ?>" placeholder="Domain Name"   >
        </div>
      </div>
      <div class="form-group" id="outer">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Color : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 color" id="color" name="ColorID" value="<?php echo isset($subscpt['ColorID']) ? $subscpt['ColorID'] : '66ff00'; ?>" placeholder="Color"   >
		  <em id='basic-log'></em>
        </div>
      </div>
	     <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Package Name : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1" id="PackageName" name="PackageName" value="<?php echo isset($subscpt['PackageName']) ? $subscpt['PackageName'] : ''; ?>" placeholder="Package Name"   >
        </div>
      </div>
	   <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Quickbooks : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1" id="Quickbooksid" name="Quickbooksid" value="<?php echo isset($subscpt['Quickbooks']) ? $subscpt['Quickbooks'] : ''; ?>" placeholder="Quickbooks Id"   >
        </div>
      </div>
          
       <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">QB online User Name: </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1" id="QBUserName" name="QBUserName" value="<?php echo isset($account['QBUserName']) ? $account['QBUserName']:'';?>" placeholder="QBUserName"   >
        </div>
      </div>
       <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">QB online Password : </label>
        <div class="col-md-6">
          <input type="password" class="form-control text_input1" id="QBPassword" name="QBPassword" value="<?php echo isset($account['QBUserName']) ? $account['QBUserName']:'';?>" placeholder="QBPassword"   >
        </div>
      </div>
      
       <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">QB Merchant User Name:</label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1" id="MerUserName" name="MerUserName" value="<?php echo isset($account['MerUserName']) ? $account['MerUserName']:'';?>" placeholder="MerUserName"   >
        </div>
      </div>
        
        
       <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">QB Merchant Password: </label>
        <div class="col-md-6">
          <input type="password" class="form-control text_input1" id="MerPassword" name="MerPassword" value="<?php echo isset($account['MerPassword']) ? $account['MerPassword']:'';?>" placeholder="MerPassword"   >
        </div>
      </div>
       <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">On fleets User Name:</label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1" id="FleetsUserName" name="FleetsUserName" value="<?php echo isset($account['FleetsUserName']) ? $account['FleetsUserName']:'';?>" placeholder="FleetsUserName"   >
        </div>
      </div>
       <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">On fleets Password: </label>
        <div class="col-md-6">
          <input type="password" class="form-control text_input1" id="FleetsPassword" name="FleetsPassword" value="<?php echo isset($account['FleetsPassword']) ? $account['FleetsPassword']:'';?>" placeholder="FleetsPassword"   >
        </div>
      </div>
	   <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Tracking Software: </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1" id="Trackingsoftware" name="Trackingsoftware" value="<?php echo isset($subscpt['Trackingsoftware']) ? $subscpt['Trackingsoftware'] : ''; ?>" placeholder="Tracking Software"   >
        </div>
      </div>
        
        
      
      <div class="form-group">
          <label for="expiryDate" class="col-md-4 catogery_name">Expiry Date<strong class="star">*</strong> : </label>
          <div class="col-md-6">
            <input type="text" class="form-control text_input1" readonly id="expiryDate" name="expiryDate" placeholder="Expiry Date" value="<?php echo isset($subscpt['expiryDate']) ? $subscpt['expiryDate'] : ''; ?>">
          </div>
        </div>
        
        
         <input type="hidden" name="CompanyLogo" id="upload-idcard_h" style="margin:5px 0px 10px 0px" value="<?php echo isset($subscpt['CompanyLogo']) ? $subscpt['CompanyLogo'] : '';?>"/>
	  	 <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Subscriber Logo : </label>
        <div class="col-md-6">
          <input type="file" enctype="multipart/form-data" class="filefield input-file" name="upload_idcard"  id="upload-idcard"  onchange="ajaxFileUpload('upload-appl-imgPath_img',this.id,'');"/>
          <img alt="" width="125" name="upload-appl-imgPath_img" id="upload-appl-imgPath_img"  style='margin:5px 0px 10px 0px;' src="<?php echo base_url(isset($subscpt['CompanyLogo']) ? $subscpt['CompanyLogo'] : '');?>"  />
       <span id="upload-appl-success-mssg"></span> <span id="upload-appl-error-mssg"></span>
          
        </div>
      </div>	
      
          
      <div class="col-md-2"></div>
        
         
      <div class="form-group">
        <div class="col-md-4"></div>
        <div class="col-md-6">
		<?php if(isset($subscpt['email'])) 
		{	
		?>
          <button type="submit" class="btn btn-primary category_button">Update</button>
		<?php }
		else
		{
		?>
		<button type="submit" class="btn btn-primary category_button">Submit</button>
		<?php } ?>
        </div>
      </div>
    </form>
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
});
</script>


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
</script>