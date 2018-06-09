
<div class="col-md-12 item-wps-container">
  <?php if(isset($success)){ ?><div class="success"><?php echo $success; ?></div><?php } ?>
  <div class="item-wps">
  <?php if($categories){ ?>
   <form  role="form" id="add-product-form">
  <?php foreach($categories as $categoryID => $categoryDetails){   ?>
<div class="col-md-4">
      <div class="new_image rela-bxwps"> <a>
      <?php /*?><input type="hidden"  id="parent_id" name="parent_id" value="<?php echo $categoryDetails['parentID']; ?>"   /><?php */?>
      <?php $imagepath = isset($categoryDetails['image']) && !empty($categoryDetails['image']) ? $categoryDetails['image'] : "images/no-image.jpg";  
       		if(strpos($imagepath,"product_images") !== false){
					$originalimagepath=str_replace("uploaded/product_images/","uploaded/product_images/original/",$imagepath);
				}?>
        <div class="rl-imwps" data-toggle="modal" data-target="#myModal"> <?php /*?><img src="<?php echo base_url($imagepath);?>" class="img-responsive img-rounded"/><?php */?>
       <img src="<?php echo base_url($imagepath);?>"  class="img-responsive img-rounded" data-img="<?php echo base_url($originalimagepath);?>"  data-productname="<?php echo $categoryDetails['categoryName']; ?>" data-option-2="<?php echo $categoryDetails['options']['2'];?>" data-option-3="<?php echo $categoryDetails['options']['3'];?>" data-option-4="<?php echo $categoryDetails['options']['4'];?>" data-productDescription="<?php echo $categoryDetails['productDescription']; ?>"/>
         
          <p class="bx-title"><?php echo $categoryDetails['categoryName']; ?></p>
        </div>
        <div class="relabx-content new_comtemt">
          <?php if(isset($categoryDetails['options'])){ ?>
			  <?php foreach($options as $optionID => $optionDetails){ ?>
              <?php if($optionDetails['type'] == 'textshow') { ?>
              <p><?php echo $categoryDetails['options'][$optionID] ?></p>
              <?php }else{ ?>
              
              <p><?php echo $optionDetails['optionType'] ?>: 
              <?php switch($optionDetails['type']){
                   case 'radio': echo ($categoryDetails['options'][$optionID] == 1)?'Yes':'No'; break;
                   case 'text': echo ($categoryDetails['options'][$optionID]); break;
                    } ?>
              </p>
               <?php } ?>
              <?php } ?>
          <?php } ?>
          <div class="clk-wps">
          <?php $product_count = 0; if(isset($categoryDetails['products'])){ ?>
            <?php foreach($categoryDetails['products'] as $productID => $productDetails){ $product_count++;  ?>
            <div class="col-md-6">
            <input type="hidden" name="products[<?php echo $categoryID;?>][<?php echo $productID;?>][productID]" value="<?php echo $productID; ?>" />
               <input type="checkbox" class="text_focus" name="products[<?php echo $categoryID;?>][<?php echo $productID;?>][isActive]"  <?php echo (isset($productDetails['isActive']) && ($productDetails['isActive'] == 1)) ? 'checked="checked"' : ''; ?> value="<?php echo isset($productDetails['isActive']) ? $productDetails['isActive'] : '0'; ?>" /> 
               <input type="text" class="text_focus" name="products[<?php echo $categoryID;?>][<?php echo $productID;?>][QBcode]" value="<?php echo isset($productDetails['QBcode']) ? $productDetails['QBcode'] : ''; ?>" placeholder="QBcode" /> 
               
              <input type="text" class="text_focus" name="products[<?php echo $categoryID;?>][<?php echo $productID;?>][name]" value="<?php echo $productDetails['productName']; ?>" placeholder="Name" /> 
              <span class="itm-price"><input type="text" class="text_focus" name="products[<?php echo $categoryID;?>][<?php echo $productID;?>][price]" value="<?php echo $productDetails['price']; ?>" placeholder="Price" /></span>
             
            </div>
            <?php } ?>
           <?php } ?>
           
           <?php for($product_count; $product_count<6; $product_count++){ ?>
           <div class="col-md-6">
           <input type="checkbox" class="text_focus" name="products[<?php echo $categoryID;?>][<?php echo 'new'.$product_count;?>][isActive]" value="0"  /> 
             <input type="text" class="text_focus" name="products[<?php echo $categoryID;?>][<?php echo 'new'.$product_count;?>][QBcode]" value="" placeholder="QBcode" /> 
              <input type="text" class="text_focus" name="products[<?php echo $categoryID;?>][<?php echo 'new'.$product_count;?>][name]" value="" placeholder="Name" /> 
              <span class="itm-price"><input type="text" class="text_focus" name="products[<?php echo $categoryID;?>][<?php echo 'new'.$product_count;?>][price]" value="" placeholder="Price"  /></span>
              
            </div>
           <?php } ?>
          </div>
        </div>
        </a>
         </div>
       <?php if($session['LOGIN_TYPE'] != 'EMPLOYEE'){ ?>
       <a onclick="getQrCode(<?php echo $categoryID; ?>);" style="margin-bottom:10px;" class="btn btn-sm btn-success">QrCode</a>
       <?php } ?>

    </div>
    <?php } ?>
    
     
       <div class="col-md-12"> <a href="#" class="add-product-submit" onclick="submitform();">Add/Update product</a>
      </div>
    
      </form>
      
    <?php }else{ ?>
    <div> No Subcategories found. Please insert first.</div>
    <?php } ?>
  </div>
</div>   <div id="myModal" class="modal fade" role="dialog"  style="z-index: 9999">
  <div class="modal-dialog" >

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="product_title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p><img id="thumbnil" src="" alt="image" class="img-responsive" style="height:300px"/></p>
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
function submitform()
{
	var dataString = $("#add-product-form").serialize()+'&parent_id=' + $("#parent_id").val();
	$.ajax({
		type: "POST",
		url: '<?php echo base_url('index.php/adminproducts/updateProducts'); ?>',
		data: dataString,
		success: function (data) {
			$("#show_subcategories").html(data);
		}
	});
}
	 $(".rl-imwps").click(function(){ 
		 
		$("#thumbnil").attr("src",$(this).find("img").attr("data-img"));
		$("#product_title").html($(this).find("img").attr("data-productname"));
       $("#cbd_per").html($(this).find("img").attr("data-option-2"));
		$("#thc_per").html($(this).find("img").attr("data-option-3"));
		$("#thca_per").html($(this).find("img").attr("data-option-4"));
		$("#product_description").html($(this).find("img").attr("data-productDescription"));
		//$('#myModalq').modal('show');
    });
</script>
