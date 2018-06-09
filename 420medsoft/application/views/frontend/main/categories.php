
    <div class="row">
      <div class="col-md-12">
        <h1 class="hd-title-light"><?php echo $parentCategory['categoryDescriptionHeading']; ?></h1>
        <p class="col-md-11 p-tx22"><?php echo $parentCategory['categoryDescription']; ?></p>
        
        
       <form method="post" role="form" id="category-search-form">
        <div class="form-group">
        	<div class="row ish-wps">
            	
            	<div class="col-md-2 col-sm-2 ish-box ish-sativa"><a href="<?php echo base_url('index.php/main/categories/'.$currentCategory.'/Sativa');?>">Sativa</a></div>
            	<div class="col-md-2 col-sm-2 ish-box ish-hybrid"><a href="<?php echo base_url('index.php/main/categories/'.$currentCategory.'/Hybrid');?>">Hybrid</a></div>
                <div class="col-md-2 col-sm-2 ish-box ish-indica"><a href="<?php echo base_url('index.php/main/categories/'.$currentCategory.'/Indica');?>">Indica</a></div>
			</div>
            <div class="row margin-bottom-40">
            	<div class="col-md-6 col-sm-6 marginauto">
                	<div class="row margin-bottom-20">
                	<div class="col-md-9 col-sm-9 padding-right-0">
                    	<input type="text" class="form-control text_input1 required" name="categoryName" value="<?php echo isset($category['categoryName']) ? $category['categoryName'] : ''; ?>" placeholder="Enter Product Name" />
					</div>
                    <div class="col-md-3 col-sm-3">
                    	<button type="submit" class="btn btn-primary category_button btn-block">Search</button>
					</div>
                    </div>
                </div>
            </div>
      </div>
      </form>
      
      </div>
      <div class="cate-cntwrps">
      <?php $this->load->view('frontend/includes/sidebar');?>
      <div class="col-md-9 item-wps-container category-wrap">
        <div class="item-wps">
         <?php if($categories){ ?>
         <?php foreach($categories as $categoryID => $categoryDetails){ if(isset($categoryDetails['products'])){ ?>
          <div class="col-md-4 <?php echo ($currentCategory == 65) ? 'splwrap' : ''; ?>">
            <div class="rela-bxwps" > <a href="javascript:">
            <?php $imagepath = isset($categoryDetails['image']) && !empty($categoryDetails['image']) ? $categoryDetails['image'] : "images/no-image.jpg"; 
				if(strpos($imagepath,"product_images") !== false){
					$originalimagepath=str_replace("uploaded/product_images/","uploaded/product_images/original/",$imagepath);
				}
				?>
       		 <div class="rl-imwps" data-toggle="modal" data-target="#myModal">  <img src="<?php echo base_url($imagepath);?>" class="img-responsive img-rounded" data-img="<?php echo base_url($originalimagepath);?>"  data-productname="<?php echo $categoryDetails['categoryName']; ?>" data-option-2="<?php echo $categoryDetails['options']['2'];?>" data-option-3="<?php echo $categoryDetails['options']['3'];?>" data-option-4="<?php echo $categoryDetails['options']['4'];?>" data-productDescription="<?php echo (isset($categoryDetails['productDescription']) && !empty($categoryDetails['productDescription'])) ?$categoryDetails['productDescription'] : ''; ?>"/>
       		 
       		  
                <p class="bx-title"><?php echo $categoryDetails['categoryName']; ?></p>
              </div>
              <div class="relabx-content">
              
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
              
			  <?php /*?><?php if(isset($categoryDetails['options'])){ ?>
                  <?php foreach($options as $optionID => $optionDetails){ ?>
                  <p><?php echo $optionDetails['optionType'] ?>: 
                  <?php switch($optionDetails['type']){
                       case 'radio': echo ($categoryDetails['options'][$optionID] == 1)?'Yes':'No'; break;
                       case 'text': echo ($categoryDetails['options'][$optionID]); break;
                        } ?>
                  </p>
                  <?php } ?>
              <?php } ?><?php */?>
                <div class="clk-wps">
                <?php $product_count = 0; if(isset($categoryDetails['products'])){ ?>
            	<?php foreach($categoryDetails['products'] as $productID => $productDetails){ $product_count++;  ?>
            
                  <div class="col-md-6">
                    <button type="button" class="btn btn-default btn-block product-block" data-maincategory="<?php echo $currentCategory; ?>"  data-category="<?php echo $categoryID; ?>" data-product="<?php echo $productID;?>"><?php echo $productDetails['productName']; ?> <span class="itm-price"><?php echo $currency['symbol']; ?><?php echo $productDetails['price']; ?></span></button>
                  </div>
                  
				<?php } ?>
                <?php } ?>
                </div>
              </div>
               </div>
          </div>
          
    <?php } } ?>
    <?php }else{ ?>
    <div> No Subcategories and products.</div>
    <?php } ?>          
          
        </div>
      </div>
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
			url: '<?php echo base_url('index.php/main/cart'); ?>',
			data: dataString,
			success: function (data) {
				$("#show-cart-items").html(data);
			}
		});
		
	});
	
	
	/*var dataString = "txtCardNum=123456789&btnSubmit=Submit";
	$.ajax({
		type: "POST",
		url: 'http://mmic.cdph.ca.gov/MMIC_Search.aspx',
		data: dataString,
		success: function (data) {
			alert(data);
			console.log(data);
		},
		error: function (data) {
			alert(data);
			console.log(data);
		}
	});*/
});
</script>