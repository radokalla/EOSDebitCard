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

<link href="<?php echo base_url('css/bootstrap-colorpicker.min.css'); ?>" rel="stylesheet">
<div class="row">
  <div class="col-md-3">
    <?php $this->load->view('frontend/includes/dashboardsidebar');?>
  </div>
  <div class="col-md-9">
    <div class="col-md-12 general-page">
      <form method="post" id="reg_form" name="reg_form" class="xform" enctype="multipart/form-data" action="<?php echo base_url("index.php/main/sitedetails"); ?>">
        <div class="col-md-9">
        <?php if(isset($success_message)){ ?><div class="success"><?=$success_message;?></div><?php } ?>
          <section>
            <div class="row">
              <div class="col-md-12">
                <header>Patient Website Set Up</span></header>
                <label class="input"> <i class="icon-prepend glyphicon glyphicon-globe"></i>
                  <input  type="text" name="DomainName" id="" placeholder="Domain Name" value="<?php echo $SiteDetails['DomainName']; ?>" class="required url">
                </label>
              </div>
            </div>
          </section>
          
          <section>
            <div class="row">
              <div class="col-md-12">
                <?php /*?><div class="wojo form">
                  <div class="field"><?php */?>
                    <label class="input">
                    
                    
                    <div class="fileinput"><i  class="icon-prepend glyphicon glyphicon-picture"></i>
                      <input type="file" enctype="multipart/form-data" class="filefield input-file" name="upload_idcard"  id="upload-idcard"  onchange="ajaxFileUpload('upload-appl-imgPath_img',this.id,'');" style="position: absolute; clip: rect(0px, 0px, 0px, 0px);" tabindex="-1">
                      <?php /*?><a class="group-span-filestyle input-group-btn" tabindex="0">
                      <label class="btn btn-default " for="avatar"><i class="icon-prepend fa fa-folder-open"></i> Choose file</label>
                      </a><?php */?></div>
                    </label>
                    
                     <img alt="" width="125" name="upload-appl-imgPath_img" id="upload-appl-imgPath_img"  style='margin:5px 0px 10px 0px;' src="<?php echo base_url(isset($SiteDetails['CompanyLogo']) ? $SiteDetails['CompanyLogo'] : '');?>"  />
          <span id="upload-appl-success-mssg"></span> <span id="upload-appl-error-mssg"></span>
          
                  </div>
                  <input type="hidden" name="idcard" id="upload-idcard_h" style="margin:5px 0px 10px 0px" value="<?php echo isset($SiteDetails['CompanyLogo']) ? $SiteDetails['CompanyLogo'] : '';?>"/>
                <?php /*?></div>
              </div><?php */?>
            </div>
          </section>
          
          
          <section>
            <div class="row">
              <div class="col-md-12">
                <label class="select">
                <i class="icon-prepend glyphicon glyphicon-pencil"></i>
                <div class="input-group demo2">
                
                 <input type="text" name="color" value="<?php echo $SiteDetails['ColorID']; ?>" class="form-control" />
                  <span class="input-group-addon"><i></i></span> </div>
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
        <div class="col-md-3">
          <a target="_blank" href="http://support.420medsoft.com/" class="support"></a>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="<?php echo base_url('js/bootstrap-colorpicker.js'); ?>"></script>
<script>
    $(function(){
        $('.demo2').colorpicker();
    });
</script>