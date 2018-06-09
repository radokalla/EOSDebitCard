<?
$heading = 'reviews';
include_once './includes/config.inc.php';
auth();
$db = db_connect();
session_start();
function array_column(array $input, $columnKey, $indexKey = null)
{
    $array = array();
    foreach ($input as $value) {
        if (!array_key_exists($columnKey, $value)) {
            trigger_error("Key \"$columnKey\" does not exist in array");
            return false;
        }
        if (is_null($indexKey)) {
            $array[] = $value[$columnKey];
        } else {
            if (!array_key_exists($indexKey, $value)) {
                trigger_error("Key \"$indexKey\" does not exist in array");
                return false;
            }
            if (!is_scalar($value[$indexKey])) {
                trigger_error("Key \"$indexKey\" does not contain scalar value");
                return false;
            }
            $array[$value[$indexKey]] = $value[$columnKey];
        }
    }
    return $array;
}
$stores_list = $db->get_rows("SELECT s.id,s.name FROM stores as s inner join StoreUserSubscription as sus on sus.StoreUserSubscriptionId = s.StoreUserSubscriptionId");
$menu_data   = array();
$message     = "";

if (isset($_GET['insertsearch']) && !empty($_GET['insertsearch']) && !empty($_SESSION['weedmap_menu'])) {
    // echo "<pre>";
    $flowers_array = array(
        'Indica',
        'Sativa',
        'Hybrid'
    );
    $storeuserdata = $db->get_rows("SELECT s.id,sus.UserId FROM stores as s inner join StoreUserSubscription as sus on sus.StoreUserSubscriptionId = s.StoreUserSubscriptionId where s.id=" . $_GET['storeid']);
    if (isset($storeuserdata) && count($storeuserdata) > 0) {
       $categoryid   = "";
        $categoryData = $db->get_rows("SELECT `categoryID`,`categoryName`  from ProductCategory where  UserId=" . $storeuserdata[0]['UserId']);
        $flowerid     = $categoryData[array_search('Flowers', array_column($categoryData, 'categoryName'))]['categoryID'];
        foreach ($_SESSION['weedmap_menu']['categories'] as $storedetails) {
            if (in_array($storedetails['title'], $flowers_array)) {
                $categoryid = $flowerid;
                //echo "<br/>flowers id: ".$categoryid."---".$storedetails['title'];
            } else if (array_search($storedetails['title'], array_column($categoryData, 'categoryName'))) {
                //insert id
                $categoryid = $categoryData['categoryID'];
                //echo "<br/>category id: ".$categoryid."---".$storedetails['title'];
            } else {
                // echo "<br/>insert category : ".$storedetails['title'];
                $insert_productarray = array(
                    'categoryAliasName' => '',
                    'UserId' => $storeuserdata[0]['UserId'],
                    'categoryOrder' => 0,
                    'categoryName' => $storedetails['title'],
                    'parentID' => 0,
                    'image' => '',
                    'categoryDescriptionHeading' => '',
                    'categoryDescription' => '',
                    'categoryType' => 'Un-assigned'
                );
                $categoryid          = $db->cat_insert("ProductCategory", $insert_productarray);
                
                
            }
            foreach ($storedetails['items'] as $items) {
                // echo "<br/>SELECT `categoryID`,`categoryName` from ProductCategory where categoryName='".mysql_escape_string($items['name'])."' and parentID ='".$categoryid."' and UserId ='".$storeuserdata[0]['UserId']."'";
                $product_data = $db->get_rows("SELECT `categoryID`,`categoryName` from ProductCategory where categoryName='" . mysql_escape_string($items['name']) . "' and parentID ='" . $categoryid . "' and UserId ='" . $storeuserdata[0]['UserId'] . "'");
                if (isset($product_data) && count($product_data) > 0) {
                    //  echo "dont do anything";
                } else {
                    //echo "<br/>insert details in productCategory and products table and store_product_map".$items['name'];
                    
                    $insert_productCatArray = array(
                        'UserId' => $storeuserdata[0]['UserId'],
                        'categoryOrder' => 0,
                        'categoryName' => mysql_escape_string($items['name']),
                        'parentID' => $categoryid,
                        'image' => $items['image_url'],
                        'categoryType' => 'Un-assigned'
                    );
                    $catid                  = $db->cat_insert("ProductCategory", $insert_productCatArray);
                    $item_price             = 0;
                    foreach ($items['prices'] as $key=>$price) {
                        if (!empty($price)) {
                            $item_price = $price;
							$insert_product_array       = array(
								'categoryID' => $catid,
								'qty' => 10,
								'isActive' => 1,
								'productName' => $key,
								'price' => $item_price,
								'isDeleted' => 0,
								'is_ticker' => 0
							);
							$product_id   = $db->cat_insert("Products", $insert_product_array);
							  $insert_store_product_array = array(
								'store_id' => $_GET['storeid'],
								'product_id' => $product_id,
								'user_id' => $storeuserdata[0]['UserId']
							);
							$db->cat_insert("store_product_map", $insert_store_product_array);
                        }
                    }
                    
                   
                  
                }
            }
        }
        $_SESSION['weedmap_menu'] = '';
        $message                  = "Successfully imported.";
        
    } else {
        $message = "No User found for the store selected.";
    }
    //exit;
}

if (isset($_GET['keyword_search']) && !empty($_GET['keyword_search']) && !empty($_GET['fetchweedmap'])) {
    $current_url = base64_encode($url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    $headers     = array(
        'Content-Type: application/json'
    );
    $url         = 'https://weedmaps.com/api/web/v1/listings/' . $_GET['keyword_search'] . '/menu?show_unpublished=false&type=delivery';
    $ch          = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    $menu_data = json_decode($result, true);
    if (count($menu_data['categories']) > 0) {
        //    echo "rajesh";
        $_SESSION['weedmap_menu'] = $menu_data;
    } else {
        $_SESSION['weedmap_menu'] = '';
        $message                  = "No Menu found with the keyword";
    }
} else {
    $_SESSION['weedmap_menu'] = '';
    //$message="check keyword";
}

?>
<?php include("header.php"); ?>
<?php include("sidebar.php"); ?>
 
<style>
.media-heading{font-weight:700;}
.menu-item-prices{font-weight:700;}
.media-body p{font-size:0.8em}
</style>
 <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Fetch WeedMap List</h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Weedmap Menu</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Small boxes (Stat box) -->
<div class="container"> 
     
    <div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
           	 
           	<?php if(!empty($message)) {?>
           	<div class="alert alert-success">
		  <strong>Success!</strong><?php echo $message; ?>
		</div>
           	
           	<?php } ?>
			 
            	<div class="box">
                <div class="box-header"> 
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                       <form method="get" action="">
                        <div class="col-lg-12 col-sm-12 col-xs-12">
                            <div id="example1_filter" class="res-marg rev-width-alg">
                            	<div class="row">
                                	<div class="col-lg-3 col-sm-4 col-xs-12">
                                    	<input type="text" class="form-control" placeholder="WeedMap Keyword"  value="<?=(isset($_GET['keyword_search']))?$_GET['keyword_search']: ''?>" name="keyword_search" id="keyword_search" >
                                    </div>
                                    <div class="col-lg-3 col-sm-5 col-xs-12">
                                        <select id="storeid" name="storeid" class="form-control">
                                        <option value="">Select Store</option>	
                                        <?
                                            foreach($stores_list as $store){
											$selected=(isset($_GET['storeid']) && ($store['id'] == $_GET["storeid"])) ? 'selected': '';
                                            echo '<option value="'.$store['id'].'" '.$selected.'>'.$store['name'].'</option>	';
												}
                                        ?>
                                        </select>
                                    </div>
									<div class="col-lg-2 col-sm-2 col-xs-12">
                                    	<a id='search_button' class="btn btn-primary" onclick="fetchdata();">Fetch WeedMap Data</a>
                                    </div>
                                    <?php if(isset($_GET['storeid'])) {?>
                                     
                                    <div class="col-lg-2 col-sm-2 col-xs-12">
                                    	<a id='search_button' class="btn btn-primary" onclick="insertdata();">Save WeedMap Data</a></div>
                                    <?php }?>
                                    
                				</div>
                            </div>
                        </div>
                       </form>
                    </div>
                  <div class="store_productswrps">
       	<div class="container">  
       	<?php if(isset($menu_data['categories'])){  ?>   	
        <div class="tabsftm_wrps">
         <!-- Nav tabs -->
         <ul class="nav nav-tabs">
          <li role="presentation" class="active"><a onClick="showtab('tabftmstp_01','tabftmstp_02')">Menu</a></li>
          <li role="presentation"><a onClick="showtab('tabftmstp_02','tabftmstp_01')">Details</a></li> 
        <!--  <li role="presentation"><a href="#tabftmstp_04" aria-controls="" role="tab" data-toggle="tab">Deals</a></li>-->
         </ul>
         <!-- Tab panes -->
         <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="tabftmstp_01"><div id='store_products'>
   <?php       	
	foreach($menu_data['categories'] as $storedetails){
			  foreach($storedetails['items'] as $items){
			  ?>
<div class="product-rowwps" name="product_row" cat_type="Hybrid" parentcatid="<?=$items['id'];?>" cat_name="<?=$items['name'];?>" style="display: block;">
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 productDescriptionModalPreview" data-id="164" data-toggle="modal" data-target="#productDescriptionModal"><img class="vimage" id="js_product_image_164" src="<?=$items['thumb_image_url'];?>">
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
					<div id="js_product_name_164" class="res_catheaditle"><?=$items['name'];?></div>
				</div>
				<div id="js_product_descrition_164" style="display:none;"> </div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="js_product_options_164">
					<div class="product-itemwps-bx prditmsbxwdful">
						<div class="product-itemwps prdctitem_optiontype">
							<div class="prd-optsbx-txt mbwdfl"><?=$storedetails['title'];?></div>
							<div class="prd-optsbx-txt"><span class="prd-optsbx-txt-optype">CBD % :</span> <span class="prd-optsbx-txt-sb"></span>
							</div>
							<div class="prd-optsbx-txt"><span class="prd-optsbx-txt-optype">THC % :</span> <span class="prd-optsbx-txt-sb"></span>
							</div>
							<div class="prd-optsbx-txt"><span class="prd-optsbx-txt-optype">THCA % :</span> <span class="prd-optsbx-txt-sb"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<div class="row">
				<div class="product-itemwps-bx prdctitemtypeby col-lg-2 col-md-2 col-sm-2 col-xs-4" name="product">
					
					<? foreach($items['prices']  as $price) {if(!empty($price)){
 ?>				  <div class="product-itemwps">
				  <p class="pritem-name" name=""></p><span class="pritem-price">$ <?=number_format($price,2);?></span>Donation Not Available</div>
				  
			 <?php }} ?>
					 
				</div>
			</div>
		</div>
	</div>
</div>
          	<?php }}?>
          </div></div>
          <div role="tabpanel" class="tab-pane" id="tabftmstp_02" style="display:none;">
          <h3 class="h3_headtitle"></h3>
			<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<div id="js_first_time_patients" class="panel panel-default">
	<div class="panel-body">
		<h3>First-Time Patients</h3>
		<p><?=$menu_data['listing']['customer_display_word'];?></p>
	</div>
</div>
<div id="js_announcement" class="panel panel-default">
	<div class="panel-body">
		<h3>Announcement</h3>
		<p><?=$menu_data['listing']['announcement'];?></p>
	</div>
</div>
<div id="js_about_us" class="panel panel-default">
	<div class="panel-body">
		<h3>About Us</h3>
		<p><?=$menu_data['listing']['intro_body'];?></p>
	</div>
</div>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="sdbrrht_wrps">
		<div class="panel panel-default">
	<div class="panel-body">
		<h3>Store Hours</h3>
		<ul id="js_timings" class="ulsdlist_wrps">
			<? foreach($menu_data['listing']['hours_of_operation'] as $key =>$timings){?>
			<li><span class="ullist_lbl"><?=$key;?> <?=$timings;?></span></li>
			<?php }	
				 ?>
		</ul>
		<ul class="ulsdlist_wrps">
			<li><span class="ullist_lbl">Phone</span> <span id="js-review-phone"><?=$menu_data['listing']['phone_number'];?></span></li>
			<li><span class="ullist_lbl">Email</span> <a id="js-review-email" href="mailto:"><?=$menu_data['listing']['email'];?></a></li>
			<li><span class="ullist_lbl">Website</span> <a id="js-review-website" href=""></a></li>
		</ul>
		<ul class="ulsdlist_wrps">
			<li><span class="ullist_lbl">Member Since</span> <span id="js-member-since"></span></li>
		</ul>
		</div>
		</div>
		</div>
	</div>
</div>
		  </div>
          
        
         <div></div>
         </div>
        </div><?php } ?>
       </div>
       </div></div>
                </div><!-- /.box-body -->
              </div>
            </div>

          </div>
			</div>
		  </section>
	</div>	 
	<script type="text/ecmascript">
function showtab(showid,hideid){
	$("#"+hideid).hide();
$("#"+showid).show();
}
function fetchdata() {
	if($("#keyword_search").val() == "" || $("#storeid").val() == ""){
			alert("please select store and keyword");
		return false;
	}
	window.location="http://www.four20maps.com/admin/weedmaps.php?keyword_search="+$("#keyword_search").val()+"&fetchweedmap=1&storeid="+$("#storeid").val();
 }
function insertdata(){
	if($("#keyword_search").val() == "" || $("#storeid").val() == ""){
			alert("please select store and keyword")
	}
	window.location="http://www.four20maps.com/admin/weedmaps.php?keyword_search="+$("#keyword_search").val()+"&insertsearch=1&storeid="+$("#storeid").val();
}
</script> 