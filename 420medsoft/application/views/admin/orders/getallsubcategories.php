
<div class="item-wps-container">
  <?php if(isset($success)){ ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="item-wps">
    <?php if($categories){ ?>
    <?php foreach($categories as $categoryID => $categoryDetails){ if(isset($categoryDetails['products']) && (count($categoryDetails['products']) > 0)){ ?>
    <div class="col-md-4">
      <div class="new_image rela-bxwps"> <a>
      <?php $imagepath = isset($categoryDetails['image']) && !empty($categoryDetails['image']) ? $categoryDetails['image'] : "images/no-image.jpg"; ?>
        <div class="rl-imwps"> <img src="<?php echo base_url($imagepath);?>" class="img-responsive img-rounded"/>
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
              <button type="button" class="btn btn-default btn-block product-block newbook" data-maincategory="<?php echo $currentCategory; ?>" data-category="<?php echo $categoryID; ?>" data-product="<?php echo $productID;?>"><?php echo $productDetails['productName']; ?> <span class="itm-price newbook1"><?php echo $currency['symbol']; ?><?php echo $productDetails['price']; ?></span></button>
            </div>
            <?php } ?>
            <?php } ?>
          </div>
        </div>
        </a> </div>
    </div>
    <?php } } ?>
    <?php }else{ ?>
    <div> No Subcategories found. Please insert first.</div>
    <?php } ?>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$(".product-block").click(function(){
		
		var maincategory 	= $(this).data("maincategory");
		var category 		= $(this).data("category");
		var product 		= $(this).data("product");
		
		var dataString = "maincategory="+maincategory+"&category="+category+"&product="+product;
		$.ajax({
			type: "POST",
			url: '<?php echo base_url('index.php/adminorders/cart'); ?>',
			data: dataString,
			success: function (data) {
				$("#add_cart_products").html(data);
			}
		});
		
	});
});
</script>