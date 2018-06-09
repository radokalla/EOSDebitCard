<script type="text/javascript" src="<?php echo base_url('js/ajaxfileupload.js');?>"></script>
<script type="text/javascript">
function ajaxFileUpload(pathsetid , elementid, filetype)
{
	var oldImagePath = $('#'+pathsetid).attr('src');
	$('#'+pathsetid).attr('src','<?php echo base_url('images/loader.gif');?>');
	
	var uplaod_url ='<?php echo base_url(); ?>index.php/admin/uploadBannerImage';
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
					alert(data.error);
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
</script>

<div class="memberlogin-wps col-md-12 products_page">
  <h2><?php echo isset($banner['ID']) ? 'Update' : 'Add'; ?> Banner</h2>
  <div class="col-md-12"> <?php echo validation_errors(); ?>
    <form method="post" role="form" id="add-banner-form" class="validate-form">
      <input type="hidden" name="banner_id" value="<?php echo isset($banner['ID']) ? $banner['ID'] : ''; ?>" />
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Tittle<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required alpha" id="title" name="title" value="<?php echo isset($banner['title']) ? $banner['title'] : ''; ?>" placeholder="Enter Tittle" >
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="control-label col-sm-4 control_labeltext">Banner Image<strong class="star">*</strong> : </label>
        <div class="col-md-6">
          <input type="file" name="upload_banner" id="upload-banner"  onchange="ajaxFileUpload('upload-appl-imgPath_img',this.id,'');" class="input-file <?php //echo (isset($banner['ID'])?'':'required'); ?>" placeholder="Banner Image"/>
          <input type="hidden" name="banner" id="upload-banner_h" style="margin:5px 0px 10px 0px" value="<?php echo isset($banner['image']) ? $banner['image'] : '';?>" />
          <a href="<?php echo base_url(isset($banner['image']) ? $banner['image'] : '');?>" id="upload-banner_a" target="_blank"><img alt="" width="125" name="upload-appl-imgPath_img" id="upload-appl-imgPath_img"  style='margin:5px 0px 10px 0px;' src="<?php echo base_url(isset($banner['image']) ? $banner['image'] : '');?>"  /></a> <span id="upload-appl-success-mssg"></span> <span id="upload-appl-error-mssg"></span> </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Banner Url: </label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1" id="banner_url" name="banner_url" value="<?php echo isset($banner['banner_url']) ? $banner['banner_url'] : ''; ?>" placeholder="Enter Url" >
        </div>
      </div>
      <div class="col-md-2"></div>
      <div class="form-group">
        <div class="col-md-4"></div>
        <div class="col-md-6">
          <button type="submit" class="btn btn-primary category_button"><?php echo isset($banner['ID']) ? 'Update' : 'Add'; ?></button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function(e) {
    $("#add-banner-form").submit(function(e) {
        var isValid = true;
		var upload-banner_h = $("#upload-banner_h").val();
		if(upload-banner_h == "" || upload-banner_h == null)
		{
			isValid = false;
		}
		return isValid;
    });
});
</script>