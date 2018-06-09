<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?=TITLE;?></title>
<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url("images/favicon.ico");?>">

<!-- Bootstrap -->
<link href="<?php echo base_url("css/bootstrap.min.css");?>" rel="stylesheet">
<link href="<?php echo base_url("css/bootstrap.css");?>" rel="stylesheet">
<link href="<?php echo base_url("css/style-color.css");?>" rel="stylesheet">
<link href="<?php echo base_url("css/style-new.css");?>" rel="stylesheet">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<style>
label.error{
	background:none;
	padding:0px;
	border:none;
}
</style>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="<?php echo base_url("js/jquery.min.js");?>"></script> 
<script src="<?php echo base_url("js/common.js");?>"></script> 

<script type="text/javascript">
$(document).ready(function(){
	$("input[type='text'], input[type='password']").each(function(){
		$(this).attr("autocapitalize", "off");
	});
});
</script>

<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="<?php echo base_url("js/bootstrap.min.js");?>"></script>
<div class="header-wps">
  <div class="container">
        <div class="collapse navbar-collapse accts-btns-marg">
<?php if(!isset($session['SUBSCRIBER_ID'])){ ?>
          <ul class="nav navbar-nav accts-btns pull-right"> 
			<li><a href="<?php echo base_url("index.php/main/login"); ?>">Login</a></li>
            <li><a href="<?php echo base_url("index.php/main/packages"); ?>">Register</a></li>
          </ul> <?php } ?>   
        </div>
  
    <nav class="navbar navbar-inverse" role="navigation">
      <div class="container-fluid"> 
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menubar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
          <a class="navbar-brand logo-wps" href="<?php echo base_url("index.php/main/index"); ?>"><img src="<?php echo base_url('images'); echo "/"; echo $logos[0]['headerlogo']; ?>"></a> </div>
        
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="menubar">
          <ul class="nav navbar-nav"> 
		  <?php $controller = $this->router->fetch_class(); $method = $this->router->fetch_method(); $segment3 = $this->uri->segment(3); ?>
          <?php $Category_menu_list = 0; foreach($mainCategories as $mainCategoryID => $mainCategory){  $Category_menu_list++; if($Category_menu_list<=6){ ?>
          <?php $menuactive = (($controller == 'main') && ($method == 'categories') && ($segment3 == $mainCategoryID)) ? 'active' : '';  ?>
            <li class="<?php echo $menuactive; ?>"><a href="<?php echo base_url("index.php/main/categories/".$mainCategoryID); ?>"><?php echo $mainCategory['categoryName']; ?></a></li>
            <?php } } ?>
            <?php if($Category_menu_list > 6){ ?>
          <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">More <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
              <?php $Category_menu_list = 0; foreach($mainCategories as $mainCategoryID => $mainCategory){  $Category_menu_list++; if($Category_menu_list>6){ ?>
                <li><a href="<?php echo base_url("index.php/main/categories/".$mainCategoryID); ?>"><?php echo $mainCategory['categoryName']; ?></a></li>
                <?php } } ?>
              </ul>
			  <?php if(isset($session['SUBSCRIBER_ID'])){ ?>
			<li><a href="http://support.420medsoft.com/ ">Support</a></li>
			<?php  } ?>
			  
            </li>
            <?php } ?>
          </ul>
        </div>
        <!-- /.navbar-collapse --> 
      </div>
      <!-- /.container-fluid --> 
    </nav>
  </div>
</div>
<style>
.carousel-inner{
	height:518;
	overflow:hidden;
}
</style>
<?php if($this->router->fetch_class() == 'main' && $this->router->fetch_method() == 'index') { ?>
<div class="slide-wps">
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel"> 
      <!-- Indicators -->
      <ol class="carousel-indicators">
      <?php foreach($banner as $kd=>$bannerd){ ?>
        <li data-target="#carousel-example-generic" data-slide-to="<?php echo $kd;?>" class="<?php  echo ($kd==0)?'active':''; ?>?>"></li>
      <?php }?>
        <!--<li data-target="#carousel-example-generic" data-slide-to="1"></li>
        <li data-target="#carousel-example-generic" data-slide-to="2"></li>-->
      </ol>
      
      <!-- Wrapper for slides -->
      <div class="carousel-inner">
      	<?php foreach($banner as $k=>$banner){ ?>
        <div class="item <?php echo ($k==0)?'active':''; ?>"> <img src="<?php echo base_url().str_replace('uploaded/frontendMyaccount/','uploaded/frontendMyaccount/original/',$banner['image']);?>" alt="">
          <div class="carousel-caption"></div>
        </div>
        <?php } ?>
      </div>
      
      <!-- Controls --> 
      <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left"></span> </a> <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span> </a> </div>
</div>

<div class="feature-wps">
  <div class="container">
    
    <div class="row">
      <?php $this->load->view('frontend/includes/sidebar');?>
      <div class="col-md-9 relate-wps">
<div class="col-md-12 welcome-txt">
  <h1 class="title-welcm"><?php echo isset($text[0]['firstTitle']) ? nl2br($text[0]['firstTitle']) : ''; ?></h1>
  <p><?php echo isset($text[0]['firstDesc']) ? nl2br($text[0]['firstDesc']) : ''; ?></p>
</div>
    
        
        <?php /*?>
		<div class="col-md-4">
          <div class="rela-bxwps"> <img src="<?php echo base_url("images/redcongo-img.jpg");?>" class="img-responsive img-rounded"/>
            <p class="bx-title">Red Congo</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="rela-bxwps"> <img src="<?php echo base_url("images/stellabluecbd-img.jpg");?>" class="img-responsive img-rounded"/>
            <p class="bx-title">Stella Blue CBD</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="rela-bxwps"> <img src="<?php echo base_url("images/sourpineapple-img.jpg");?>" class="img-responsive img-rounded"/>
            <p class="bx-title">Sour Pineapple</p>
          </div>
        </div>
		
		<?php */?>
        
        
        <?php if($categories){ ?>
        <div class="col-md-12 item-wps-container sploffer">
        <h1 class="title-sec-welc"><?php echo isset($text[0]['secondTitle']) ? $text[0]['secondTitle'] : ''; ?></h1>
        <div class="item-wps">
         
         <?php foreach($categories as $categoryID => $categoryDetails){ if(isset($categoryDetails['products'])){ ?>
          <div class="col-md-4 splwrap">
            <div class="rela-bxwps"> <a href="javascript:">
            <?php $imagepath = isset($categoryDetails['image']) && !empty($categoryDetails['image']) ? $categoryDetails['image'] : "images/no-image.jpg"; 
				if(strpos($imagepath,"product_images") !== false){
					$originalimagepath=str_replace("uploaded/product_images/","uploaded/product_images/original/",$imagepath);
				}?>
       		 <div class="rl-imwps" data-toggle="modal" data-target="#myModal"><?php /*?> <img src="<?php echo base_url($imagepath);?>" class="img-responsive img-rounded"/><?php */?>
        <img src="<?php echo base_url($imagepath);?>" class="img-responsive img-rounded" data-img="<?php echo base_url($originalimagepath);?>"  data-productname="<?php echo $categoryDetails['categoryName']; ?>" data-option-2="<?php echo $categoryDetails['options']['2'];?>" data-option-3="<?php echo $categoryDetails['options']['3'];?>" data-option-4="<?php echo $categoryDetails['options']['4'];?>"  data-productDescription="<?php echo (isset($categoryDetails['productDescription']) && !empty($categoryDetails['productDescription'])) ?$categoryDetails['productDescription'] : ''; ?>"/>
       		 
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
            
                  <div class="col-md-12 clkpd2">
                    <button type="button" class="btn btn-default btn-block product-block" data-maincategory="<?php echo $currentCategory; ?>"  data-category="<?php echo $categoryID; ?>" data-product="<?php echo $productID;?>"><?php echo $productDetails['productName']; ?> <span class="itm-price"><?php echo $currency['symbol']; ?><?php echo $productDetails['price']; ?></span></button>
                  </div>
                  
				<?php } ?>
                <?php } ?>
                </div>
              </div>
               </div>
               </a>
          </div>
          
    <?php } } ?>
    <?php /*?><?php }else{ ?>
    <div> No Subcategories and products.</div><?php */?>
          
        </div>
    <?php } ?>          
      </div>
      
        
        
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
	
});
</script>

<div class="martp60less"></div>
<?php } ?>

<div class="main-content martp60">
<div class="container">
