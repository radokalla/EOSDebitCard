<script type="text/javascript" src="<?php echo base_url('js/ajaxfileupload.js');?>"></script>
<script type="text/javascript">
function ajaxFileUpload(pathsetid , elementid, filetype)
{
	var fileInput = $("#add-category-form").find("input[type=file]")[0],
        file = fileInput.files && fileInput.files[0];

	var img = new Image();

	img.src = window.URL.createObjectURL( file );

	img.onload = function() {
		var width = img.naturalWidth,
			height = img.naturalHeight;

		window.URL.revokeObjectURL( img.src );

		if( width >= 227 && height >= 243 )
		{
			var uplaod_url = $("#base_url").val()+'index.php/admincategories/uploadImage';
			console.log(uplaod_url+'---'+elementid);
			var newelementid = '';
			newelementid = elementid;
			$.ajaxFileUpload
			(
				{
					url:uplaod_url,
					secureuri:false,
					fileElementId:elementid,
					dataType: 'json',
					data:{ name:elementid, showid:pathsetid, filetype:filetype},
					success: function (data)
					{
						if(typeof(data.error) != 'undefined')
						{
							if(data.error != '')
							{
								alert(data.error);
							}
						}else{
							alert(data);
						}
					},
					error:function(XMLHttpRequest,textStatus,errorThrown)
					{
					   //alert("There was an <strong>"+errorThrown+"</strong> error due to  <strong>"+textStatus+" condition");
					}   
				}
			);
		}
		else {
			alert('The minimum image dimensions should be 227 X 243.');
			return false;
		}
	};
}
</script>

<div class="memberlogin-wps col-md-12 products_page">
  <h2><?php echo isset($category['categoryID']) ? 'Update' : 'Add'; ?> Product</h2>
  <div class="col-md-12"> <?php echo validation_errors(); ?>
    <form method="post" role="form" id="add-category-form" class="validate-form">
    	<input type="hidden" id="base_url" value="<?php echo base_url(); ?>" />
      <input type="hidden" name="category_id" value="<?php echo isset($category['categoryID']) ? $category['categoryID'] : ''; ?>" />
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Category Name</label>
        <div class="col-md-6">
          <select name="parentID" id="parent_id" class="form-control text_input1 required ">
            <option value=""> -- Select -- </option>
            <?php foreach($categories as $mainCategoryID => $mainCategoryDetails){ ?>
            <option value="<?php echo $mainCategoryID; ?>" <?php echo (isset($category['parentID']) && ($mainCategoryID == $category['parentID'])) ? ' selected="selected"' : ''; ?>><?php echo $mainCategoryDetails['categoryName']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Product Name</label>
        <div class="col-md-6">
          <input type="text" class="form-control text_input1 required" name="categoryName" value="<?php echo isset($category['categoryName']) ? $category['categoryName'] : ''; ?>" placeholder="Enter Product Name" />
        </div>
      </div>
      
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Product Type</label>
        <div class="col-md-6">
          <select class="form-control text_input1 required" name="categoryType">
          <?php /*?><option value=""> -- Select -- </option><?php */?>
          <option value="Unassigned" <?php echo (isset($category['categoryType']) && ($category['categoryType'] == 'Unassigned')) ? ' selected="selected"' : ''; ?>>Unassigned</option>
          <option value="Indica" <?php echo (isset($category['categoryType']) && ($category['categoryType'] == 'Indica')) ? ' selected="selected"' : ''; ?>>Indica</option>
          <option value="Sativa" <?php echo (isset($category['categoryType']) && ($category['categoryType'] == 'Sativa')) ? ' selected="selected"' : ''; ?>>Sativa</option>
          <option value="Hybrid" <?php echo (isset($category['categoryType']) && ($category['categoryType'] == 'Hybrid')) ? ' selected="selected"' : ''; ?>>Hybrid</option>
          </select>
        </div>
      </div>
      
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name">Product Image</label>
        <div class="col-md-6">
          <input type="file" name="upload_appl" id="upload-appl" onchange="return ajaxFileUpload('applimagepath','upload-appl');" class="input-file" />
		  <span style="color:red">Images with higher resolutions will be resized to 227 X 243 size automatically.</span>
          <input type="hidden" name="applimagepath" id="upload-appl-imgPath" style="margin:5px 0px 10px 0px" value="<?php echo isset($category['image']) ? $category['image'] : '';?>"/>
          <a href="<?php echo base_url(isset($category['image']) ? $category['image'] : '');?>" id="upload-appl-imgPath_a" target="_blank"><img name="upload-appl-imgPath_img" id="upload-appl-imgPath_img"  style='margin:5px 0px 10px 0px;' src="<?php echo base_url(isset($category['image']) ? $category['image'] : '');?>"  /></a>
          <span id="upload-appl-success-mssg"></span> <span id="upload-appl-error-mssg"></span>
        </div>
      </div>
         <div class="form-group">
					<label class="col-md-4 catogery_name">Description</label>
					 <div class="col-md-6">
					<textarea name="productDescription"  id="productDescription" class="form-control text_input1 required" ><?php if(isset($category['productDescription'])){ ?> <?php echo $category['productDescription'];} ?></textarea>
					</div>
				</div>            
      
      <?php 

	  foreach($options as $optionID => $optionDetails){ 

	  if(!empty($optionDetails['type'])){

	  ?>
      <div class="form-group">
        <label for="exampleInputEmail1" class="col-md-4 catogery_name"><?php echo $optionDetails['optionType']; ?></label>
        <div class="col-md-6">
        <?php if($optionDetails['type'] == 'text'){ ?>
        <input type="text" id="option-<?php echo $optionID; ?>" class="form-control text_input1 required " name="options[<?php echo $optionID; ?>]" value="<?php echo isset($category['options'][$optionID]) ? $category['options'][$optionID] : ''; ?>" placeholder="Enter <?php echo $optionDetails['optionType']; ?>" />
        <?php }else if($optionDetails['type'] == 'textshow'){ ?>
        
        <input type="text" class="form-control text_input1 required " name="options[<?php echo $optionID; ?>]" value="<?php echo isset($category['options'][$optionID]) ? $category['options'][$optionID] : ''; ?>" maxlength="25" placeholder="Enter <?php echo $optionDetails['optionType']; ?>" />
        
        <?php }else if($optionDetails['type'] == 'radio'){ ?>
        <input type="radio" name="options[<?php echo $optionID; ?>]" value="1" checked="checked" />
        Yes
        <input type="radio" name="options[<?php echo $optionID; ?>]" value="0" <?php echo (!isset($category['options'][$optionID]) || ($category['options'][$optionID] != 1)) ? ' checked="checked"' : ''; ?>/>
        No
        
        <?php } ?>
        </div>
       
      </div>
      <?php } } ?>
      <div class="form-group">
      <div class="col-md-4"></div>
 
       <div class="col-md-2">
       <button type="button" class="btn btn-info category_button" data-toggle="modal" data-target="#myModal" id="preview">Preview</button></div>
       <div class="col-md-6"> <button type="submit" class="btn btn-primary category_button"><?php echo isset($category['categoryID']) ? 'Update' : 'Add'; ?></button></div>      
       </div>
    </form>
  </div>
</div>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="product_title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p><img id="thumbnil" src="" alt="image"/></p>
        <div class="row"> 
        
			<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12" id="product_description"></div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><div class="product-itemwps-bx prditmsbxwdful"><div class="product-itemwps prdctitem_optiontype"><div class="prd-optsbx-txt mbwdfl"></div><div class="prd-optsbx-txt"><span class="prd-optsbx-txt-optype">CBD % :</span> <span class="prd-optsbx-txt-sb" id="cbd_per">0.00</span></div><div class="prd-optsbx-txt"><span class="prd-optsbx-txt-optype">THC % :</span> <span class="prd-optsbx-txt-sb" id="thc_per">0.00</span></div><div class="prd-optsbx-txt"><span class="prd-optsbx-txt-optype">THCA % :</span> <span class="prd-optsbx-txt-sb" id="thca_per">0.00</span></div></div></div></div></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<script type="text/javascript">
<?php if(isset($category['image'])){ ?>	
 $("#thumbnil").attr("src","<?php echo base_url($category['image']);?>");
	<?php } ?>
	
	
	function showMyImage(fileInput) {
        var files = fileInput.files;
        for (var i = 0; i < files.length; i++) {           
            var file = files[i];
            var imageType = /image.*/;     
            if (!file.type.match(imageType)) {
                continue;
            }           
            var img=document.getElementById("thumbnil");            
            img.file = file;    
            var reader = new FileReader();
            reader.onload = (function(aImg) { 
                return function(e) { 
                    aImg.src = e.target.result; 
                }; 
            })(img);
            reader.readAsDataURL(file);
        }    
    }
	$(document).ready(function () {
     
    $("#preview").click(function(){ 
		$("#thumbnil").attr("src",$("#upload-appl-imgPath_img").attr("src"));
		$("#product_title").html($("#product_name").val());
       $("#cbd_per").html($("#option-2").val());
		$("#thc_per").html($("#option-3").val());
		$("#thca_per").html($("#option-4").val());
		$("#product_description").html($("#productDescription").val());
		
    });
});
</script>