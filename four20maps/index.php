<?
session_start();
include_once './includes/config.inc.php';
error_reporting(0);
date_default_timezone_set('America/Los_Angeles');
$time_start = microtime(true);
 $settings  ="";
	$sql = "select * from settings";	 
	$settingsData = mysql_query($sql);
	while($settingsArray = mysql_fetch_assoc($settingsData))
	{
		$settings  = $settingsArray;
	}
 
if(isset($_POST['action']) && $_POST['action']=='get_nearby_stores') {
		
	if(!isset($_POST['lat']) || !isset($_POST['longi'])) {
		
		echo json_encode(array('success'=>0,'msg'=>'Coordinate not found'));
	exit;
	}
	
	// support unicode 
	mysql_query("SET NAMES utf8");

	// category filter
	if(!isset($_POST['products']) || $_POST['products']==""){
		$category_filter = "";
	} else {
		$category_filter = " AND cat_id='".$_POST['products']."'";
	}
	
	/*------------------------------------ New procedure for above code ---------------------------- */
	//$sql = mysql_query("CALL map_proc_GetRelatedStoresById(".$_POST['id'].")");
		$sql = mysql_query("CALL mp_proc_SearchStorebyKeyword('Stores','".$_POST['id']."','".$_POST['is_licensed']."')");
	while($result = mysql_fetch_assoc($sql))
	{
		//$result['cat_icon'] =  $result['image'];
		 
		$data[] = $result;
	}
	echo json_encode(array("stores"=>$data));
	die;
}
if(isset($_POST['action']) && $_POST['action']=='get_nearby_stores_shorturl') {
		
	if(!isset($_POST['lat']) || !isset($_POST['longi'])) {
		
		echo json_encode(array('success'=>0,'msg'=>'Coordinate not found'));
	exit;
	}
	
	// support unicode 
	mysql_query("SET NAMES utf8");

	// category filter
	if(!isset($_POST['products']) || $_POST['products']==""){
		$category_filter = "";
	} else {
		$category_filter = " AND cat_id='".$_POST['products']."'";
	}
	
	 $stores = "SELECT s.*,st.icon1 as cat_icon,sc.SubscriptionCategoryAliasName as cat_name,st.SubscriptionTypeId,
				st.Icon1, st.Icon2, st.Icon3 ,IFNULL(IFNULL(st.OrderId,c.`OrderId`),0) AS OrderId
				FROM stores s 
				LEFT JOIN categories c ON c.id = s.cat_id 
				AND IFNULL(s.`createdby`,0) =0
				LEFT JOIN StoreUserSubscription suc
				ON suc.StoreUserSubscriptionId = s.StoreUserSubscriptionId 
				AND IFNULL(s.`createdby`,0) > 0
				LEFT JOIN SubscriptionTypes st
				ON st.SubscriptionTypeId = s.cat_id 
				LEFT JOIN SubscriptionCategory sc
				ON sc.SubscriptionCategoryId = st.SubscriptionCategoryId
				WHERE s.id=".$_POST['id']." 
				ORDER BY OrderId DESC,s.store_views DESC";
    $stores = mysql_query($stores);
    $farm_pacakages = "SELECT SubscriptionTypeId FROM `SubscriptionTypes` WHERE `SubscriptionCategoryId`= 4";
    $farm_pacakage_result = mysql_query($farm_pacakages);
    $farm_subscription_type_array = array();
    while ($farm_store = mysql_fetch_assoc($farm_pacakage_result))
    {
        $farm_subscription_type_array[] = $farm_store['SubscriptionTypeId'];
    }
    while ($row_store = mysql_fetch_assoc($stores))
    {
        #var_dump($row_store);die;
        $cat_img = "";
        if ($row_store['cat_id'] > 0)
        {
            // cat img
            $cat_upload_dir = 'http://www.four20maps.com/admin/imgs/categories/' . $row_store['cat_id'] . '/';
            $cat_files = get_files($cat_upload_dir);
            if (is_array($cat_files)) $cat_files = array_values($cat_files);

            if ($cat_files !== false && isset($cat_files[0])) $cat_img = 'http://www.four20maps.com/admin/imgs/categories/' . $row_store['cat_id'] . '/' . $cat_files[0];

        }

        $upload_dir = 'http://www.four20maps.com/admin/imgs/stores/' . $row_store["id"] . '/';
        $files = get_files($upload_dir);
        if (is_array($files)) $files = array_values($files);
        if ($files !== false && isset($files[0]))
        {
            $img = 'http://www.four20maps.com/admin/imgs/stores/' . $row_store['id'] . '/' . $files[0];
        }
        if ($row_store["Icon1"] != '') $img = "http://www.four20maps.com/admin/" . $row_store["Icon1"];
        else $img = "http://www.four20maps.com/admin/" . $row_store["cat_icon"];

        if ((!(substr($row_store["image"], 0, 7) == 'http://')) && (!(substr($url, 0, 8) == 'https://')))
        {
            $simg = 'http://www.four20maps.com/' . $row_store["image"];
        }
        else
        {
            $simg = $row_store["image"];
        }
        if ($row_store["cat_name"] == 'Delivery') $cat_typeImg = $row_store['Icon2'];
        else $cat_typeImg = $row_store['Icon3'];
        if ($cat_typeImg == '') $cat_typeImg = $img;
        $store[] = array(
            "id" => $row_store["id"],
            "name" => $row_store["name"],
            "address" => $row_store["address"],
            "telephone" => $row_store["telephone"],
            "fax" => $row_store["fax"],
            "mobile" => $row_store["mobile"],
            "email" => $row_store["email"],
            "website" => $row_store["website"],
            "description" => $row_store["description"],
			 "timings"=>json_decode($row_store["timings"]),
			"first_time_patients"=>$row_store["first_time_patients"],
			"announcement"=>$row_store["announcement"],
			"about_us"=>$row_store["about_us"],
			"created" => date('F dS, Y', strtotime($row_store["created"])),
            "img" => $img,
            "latitude" => $row_store["latitude"],
            "longitude" => $row_store["longitude"],
            "cat_id" => $row_store["cat_id"],
            "cat_img" => $cat_img,
            "cat_icon" => $img,
            "image" => $simg,
            "cat_name" => $row_store["cat_name"],
            "ctype_icon" => $cat_typeImg,
            "OrderId" => $row_store["OrderId"]
        );
   

    }
    echo json_encode(array("stores"=>$store));
	die;
}

if(isset($_POST['getStoreRating']) && $_POST['getStoreRating']=='1'){
	$storeid = $_REQUEST['storeid'];
	$records = mysql_query("SELECT  IFNULL(u.id,ifnull(su.userid,'')) as 	userid, IFNULL(u.username,ifnull(su.UserName,'')) AS username,r.store_rating, r.store_review FROM reviews r
    left JOIN users u ON u.id= r.customer_id
left join StoreUsers su on su.userid = r.customer_id
	 WHERE r.store_id= '$storeid' and approved_on!= '0000-00-00' order by u.username DESC");
	 $total_votes = mysql_num_rows($records);
	$total_score = 0;
	while($record = mysql_fetch_array($records))
	$total_score+= $record['store_rating'];
	if($total_votes == 0)
	$avg = 0;
	else
	$avg = $total_score / $total_votes;
	echo json_encode(array("avg"=>ceil($avg),"total"=>$total_votes)); exit;
	
}


if(isset($_POST['getStoreViews']) && $_POST['getStoreViews']=='1'){
	$storeid = $_REQUEST['storeid'];
	$records = mysql_query("SELECT store_views FROM stores WHERE id='" . $storeid . "' LIMIT 0,1");
	$record  = mysql_fetch_assoc($records);
	echo (isset($record['store_views']) ? $record['store_views'] : 0);
	exit;
}


if(isset($_POST['addViewForStore']) && $_POST['addViewForStore']=='1'){
	$storeid = $_REQUEST['storeid'];
	$uid=0;
	if($_SESSION['userid'])
		$uid = $_SESSION['userid'];
	$ip_addr = $_SERVER['REMOTE_ADDR'];
	 $sql ="insert into store_views(uid, storeid, ip_addr) values($uid, '$storeid','$ip_addr')";
	mysql_query($sql);
	
	$update_sql ="update stores SET store_views=store_views+1 WHERE id='" . $storeid . "'";
	mysql_query($update_sql);
	
	echo 1; exit;
}


if(isset($_POST['setStoreRating']) && $_POST['setStoreRating']=='1'){
	$storeid = $_REQUEST['storeid'];
	$rating = $_REQUEST['rating'];
	$uid = $_REQUEST['uid'];
	$comments = $_REQUEST['comments'];
	$date= date('Y-m-d');
	if($_REQUEST['uid'])
	{
		//$x = mysql_query("select count(*) as count from reviews where store_id=$storeid and  customer_id=$uid");
		//$xx = mysql_fetch_array($x);
		//if($xx['count']=='0')
		$sql= "INSERT INTO reviews (store_id,customer_id, store_rating,store_review,added_on, approved_on) VALUES($storeid, $uid, $rating,'$comments','$date','$date') ";
		//else
		//	$sql= "UPDATE reviews set store_rating=$rating,store_review='$comments' where store_id=$storeid and customer_id=$uid";
	}
	else
		$sql= "INSERT INTO reviews (store_id,customer_id, store_rating,store_review,added_on, approved_on) VALUES($storeid, 0, $rating,'$comments','$date','$date') ";
	#var_dump($sql);die;
	mysql_query($sql);
	exit;
}
if(isset($_REQUEST['recordBuy']) && $_REQUEST['recordBuy']=='1'){
	$pname = $_REQUEST['pname'];
	$site = $_REQUEST['site'];
	$uid=0;
	if($_SESSION['userid'])
		$uid = $_SESSION['userid'];
	$ip_addr = $_SERVER['REMOTE_ADDR'];
	 $sql ="insert into analytics_buy(uid, catid, site,ip_addr) values($uid, $pname, '$site','$ip_addr')";
	mysql_query($sql);
	exit;
}
if(isset($_REQUEST['getStoreReviews']))
{
	$storeid = $_REQUEST['storeid'];
	 $sql ="SELECT  IFNULL(u.id,ifnull(su.userid,'')) as 	userid, IFNULL(u.username,ifnull(su.UserName,'')) AS username,r.store_rating, r.store_review FROM reviews r
    left JOIN users u ON u.id= r.customer_id
left join StoreUsers su on su.userid = r.customer_id
	 WHERE r.store_id= '$storeid' and approved_on!= '0000-00-00' order by u.username DESC";
	$x = mysql_query($sql);
	while($row = mysql_fetch_array($x))
		$y[] = $row;
	if(count($y)>0)
	echo json_encode($y);
	exit;
}
	
$addimages="select aid,image,url,start_date,end_date from adds where status='Active' AND now() >= start_date and now() <= end_date and is_delete=0 order by last_modified DESC";
$qrryexc=mysql_query($addimages);
while($num_rows=mysql_fetch_assoc($qrryexc))
{
	$images[]=$num_rows;
}
	#var_dump($images);die;
	if(intval(GEO_SETTINGS)>0)
	{
	$geocode = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR']));
		 
		$latitude = $geocode['geoplugin_latitude'];
		$longitude = $geocode['geoplugin_longitude'];
		/*$def_loc = explode(',',file_get_contents('http://www.ipaddressapi.com/l/5604d1ed63f42547daffd678ab3e6d5e51cdc1494a65?h='.$_SERVER['REMOTE_ADDR']));
		$latitude = $def_loc[8];
		$longitude = $def_loc[9];*/
		echo "<script>var lat = ".$latitude.";var lng = ".$longitude."</script>";
	}
	else if(DEFAULT_LOCATION)
	{
		$address = DEFAULT_LOCATION; // Google HQ
        $prepAddr = str_replace(' ','+',$address);
        $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
        $output= json_decode($geocode);
        $latitude = $output->results[0]->geometry->location->lat;
        $longitude = $output->results[0]->geometry->location->lng;
		echo "<script>var lat = ".$latitude.";var lng = ".$longitude.";
		 </script>";
	}else
		echo "<script>var lat = 32.834337;var lng = -117.148851</script>";
	if(intval(INIT_ZOOM)>0)
	echo "<script>var inizoom=".INIT_ZOOM.";</script>";
	else
	echo "<script>var inizoom=15;</script>";
						
			 

	/* ------- Code written by Nags ------- */

	$store_reviews_sql= "SELECT `store_id`, COUNT(1) AS total_reviews, SUM(`store_rating`)/COUNT(1) AS avg_rating FROM `reviews` GROUP BY `store_id`";
$store_reviews = array();
	$store_reviews_details=mysql_query($store_reviews_sql);
	while($store_reviews_result = mysql_fetch_assoc($store_reviews_details))
	{
		$store_reviews[$store_reviews_result['store_id']] = array(
			'total_reviews' => $store_reviews_result['total_reviews'],
			'avg_rating' => $store_reviews_result['avg_rating'],
		);
	}
	if(isset($_SESSION["StoreID"]) && !empty($_SESSION["StoreID"])){
		 $ticker_sql="SELECT DISTINCT categoryAliasName,s.name,`categoryName`, spm.product_id,spm.`user_id` as owner_id,spm.`store_id`,pc.`categoryID`,pc.`UserId`,pc.`image`,`price`,ten_price,hundered_price,`qty`,productDescription,spm.store_product_map_id,s.description,s.telephone,weight,s.email,s.address,s.website,s.mobile,s.image as store_logo
FROM `ProductCategory` as pc inner join 
Products as p on p.categoryID=pc.`categoryID`
inner join store_product_map as spm on spm.product_id=p.productID
inner join StoreUsers as su on su.UserId=spm.user_id
inner join stores as s on s.id=spm.store_id WHERE `SubscriptionCategoryId`='4' and p.`qty`>0 and p.`isActive` = 1 and p.`isDeleted`=0  and pc.parentID !=0 order by spm.store_product_map_id desc";
		$ticker_result=mysql_query($ticker_sql);
	    
	}
    else
	{
	$return_popup_stores = array(); 
		$stores= "SELECT * FROM (
	SELECT s.*,st.Icon1  as cat_icon,c.cat_name,(((ACOS(SIN(($latitude*PI()/180)) *               SIN((`latitude`*PI()/180))+COS(($latitude*PI()/180))
		* COS((`latitude`*PI()/180)) * COS((($longitude - `longitude`)*PI()/180))))*180/PI())*60*1.1515) AS `distance`,
	st.Icon1, st.Icon2, st.Icon3 FROM stores s LEFT JOIN categories c ON c.id = s.cat_id LEFT JOIN StoreUserSubscription suc
	ON suc.StoreUserSubscriptionId = s.StoreUserSubscriptionId LEFT JOIN SubscriptionTypes st
	ON st.SubscriptionTypeId = suc.SubscriptionId WHERE s.status=1 AND approved=1 AND featured=1  limit 0,5
	
	UNION
	
	SELECT s.*,st.Icon1  as cat_icon,c.cat_name,(((ACOS(SIN(($latitude*PI()/180)) *               SIN((`latitude`*PI()/180))+COS(($latitude*PI()/180))
		* COS((`latitude`*PI()/180)) * COS((($longitude - `longitude`)*PI()/180))))*180/PI())*60*1.1515) AS `distance`,
	st.Icon1, st.Icon2, st.Icon3 FROM stores s LEFT JOIN categories c ON c.id = s.cat_id LEFT JOIN StoreUserSubscription suc
	ON suc.StoreUserSubscriptionId = s.StoreUserSubscriptionId LEFT JOIN SubscriptionTypes st
	ON st.SubscriptionTypeId = suc.SubscriptionId WHERE s.status=1 AND approved=1 HAVING `distance` <= 200) AS temp_store  ORDER BY  featured DESC, store_views DESC, `distance` ASC limit 0,30";
	$stores=mysql_query($stores);
	while($row_store = mysql_fetch_assoc($stores))
	{
		#var_dump($row_store);die;
		$cat_img ="";
		if($row_store['cat_id']>0){
		 $cat_upload_dir = ROOT_URL.'admin/imgs/categories/'.$row_store['cat_id'].'/';
		$cat_files = get_files($cat_upload_dir);
		if(is_array($cat_files)) 
			$cat_files = array_values($cat_files);

		if($cat_files !== FALSE && isset($cat_files[0])) 
			$cat_img = ROOT_URL.'admin/imgs/categories/'.$row_store['cat_id'].'/'.$cat_files[0];
		
		}
		
		$upload_dir = ROOT_URL.'admin/imgs/stores/'.$row_store["id"].'/';
		$files = get_files($upload_dir);
		if(is_array($files))
			$files = array_values($files);
		if($files !== FALSE && isset($files[0])) 
		{
			$img =ROOT_URL.'admin/imgs/stores/'.$row_store['id'].'/'.$files[0];
		}
		if($row_store["Icon1"]!='')
			$img = ROOT_URL."admin/".$row_store["Icon1"];
		else
			$img = ROOT_URL."admin/".$row_store["cat_icon"];
		
		if ((!(substr($row_store["image"], 0, 7) == 'http://')) && (!(substr($url, 0, 8) == 'https://'))) 
			{ $simg = ROOT_URL .$row_store["image"]; } 
		else
			{$simg = $row_store["image"];}
		if($row_store["cat_name"] == 'Delivery')
			$cat_typeImg = $row_store['Icon2'];
		else
			$cat_typeImg = $row_store['Icon3'];
		if($cat_typeImg == '')
			$cat_typeImg = $img;
		
		
		$cat_img = ROOT_URL.'admin/imgs/categories/' . $row_store['cat_id'] . '/image.jpg';
		
		$return_popup_stores[$row_store["id"]] = array(
			"id"=>$row_store["id"],"name"=>$row_store["name"],"address"=>$row_store["address"],"telephone"=>$row_store["telephone"],
		"fax"=>$row_store["fax"],"mobile"=>$row_store["mobile"],"email"=>$row_store["email"],"website"=>$row_store["website"],
		"description"=>$row_store["description"],
		"timings"=>json_decode($row_store["timings"]),
		"first_time_patients"=>$row_store["first_time_patients"],
		"announcement"=>$row_store["announcement"],
		"about_us"=>$row_store["about_us"],
		"created" => date('F dS, Y', strtotime($row_store["created"])),
		
		"img"=>$img,"latitude"=>$row_store["latitude"],
		"longitude"=>$row_store["longitude"], "cat_id"=>$row_store["cat_id"], "cat_img"=>$cat_img ,
		"cat_icon"=>$img,
		"image"=>$simg,			   
		'open_time' => $row_store["open_time"],
		'close_time' => $row_store["close_time"],
		'distance' => $row_store["distance"]*1.68,
		"cat_name"=>$row_store["cat_name"],
		"ctype_icon"=>$cat_typeImg,
		"OrderId"=>$row_store["OrderId"],
		"store_views"=>$row_store["store_views"],
		"status"=>isBetween($row_store["open_time"],$row_store["close_time"],date("h:m A")),
		"total_reviews"=>isset($store_reviews[$row_store["id"]]) ? $store_reviews[$row_store["id"]]["total_reviews"] : 0,
		"avg_rating"=>isset($store_reviews[$row_store["id"]]) ? $store_reviews[$row_store["id"]]["avg_rating"] : 0
			);
		
	}
	}
function isBetween($from, $till, $input) {
	$from1  = date("g:i a", strtotime($from));
	$till1  = date("g:i a", strtotime($till));
	$input1  = date("g:i a", strtotime($input));
//	/echo "<br/>".$from."---".$till."---".$input;
	if((!empty($from1)) && !empty($till1)){
    $fromTime = strtotime($from1);
    $toTime = strtotime($till1);
    $current = strtotime($input1);
//echo "<br/>".$fromTime."---".$toTime."---".$inputTime."---".(($inputTime >= $fromTime and $inputTime <= $toTime) ? "open" : "closed");
    //return (($inputTime >= $fromTime and $inputTime <= $toTime) ? "Open" : "Closed");
		if(($current>= $fromTime) && ($current<= $toTime))
		{
			//echo "open";
			return "Open ".$from." to ".$till;
		}
		else
		{
			return "<span class='mapsdfxblk_openclosed'  style='color: red'>Closed </span>".$from." to ".$till;
		}
	}
	else 
	{
		return "Not Specfied";
	}
}
//echo "<pre>"; print_r($return_popup_stores); exit();
echo "<script>var return_popup_stores=".json_encode($return_popup_stores).";</script>";
//echo "<pre>"; print_r($return_popup_stores); exit();
/* ------- End Code written by Nags ------- */

///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////
	
	 
	
	if($_SESSION['userid'])
	{
		$qry_reg="select * from users where id=".$_SESSION['userid'];
		$qrex=mysql_query($qry_reg);
		$regfetch=mysql_fetch_assoc($qrex);
	}
	
	mysql_query("UPDATE adds SET STATUS='InActive' WHERE CURDATE() >=end_date");
	$menu_items = mysql_query("select * from store_menu where active=1");
	while($row = mysql_fetch_array($menu_items))
		$menu[]=$row["menu_name"];

?>
<html>
<head>
<title><?php echo 'Four20maps'; ?></title>
<meta name="keywords" content="street view, google direction, ajax, bootstrap, embed, geo ip, geolocation, gmap, google maps, jquery, json, map, responsive, store admin, store finder, store locator" />
<meta name="description" content="Super Store Finder &amp;#8211; Easy to use Google Maps API Store Finder Super Store Finder is a multi-language fully featured PHP Application integrated with Google Maps API v3 that allows customers to..." />
<link rel="shortcut icon" href="img/favicon.ico" />
 
<?php include ROOT."settings.php"; ?>
<?php include ROOT."themes/meta_mobile.php"; ?>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_URL; ?>css/style-four20.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_URL; ?>css/jquery.mCustomScrollbar.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_URL; ?>css/jquery-ui-1.10.3.custom.min.css"/>
<link rel="stylesheet" href="<?php echo ROOT_URL; ?>css/rating.css">
<link rel="stylesheet" type="text/css" href="https://0.s3.envato.com/files/192839596/modal/css/plugin.min.css" / >
<style type="text/css">
	.bxslider-wrap { visibility: hidden; }
	
	</style>
</head>

<body id="super-store-finder">
<div class="header-wps">
  <div class="container">
    <div class="header_tpwrps">
      <nav class="navbar navbar-inverse">
   <input type="hidden" value="<?php echo (isset($_GET['store'])) ? '1' : '0';?>" id="isStoreSearch" />
    <input type="hidden" value="<?php echo $latitude;?>" id="default_lat_store" />
     <input type="hidden" value="<?php echo $longitude;?>" id="default_long_store" />
    <!-- Brand and toggle get grouped for better mobile display -->    
    <div class="navbar-header col-lg-7 col-md-6 col-sm-12 col-xs-12">
    
     <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbarmenu_collapse" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
     <a class="navbar-brand logo-wps" href="<?=ROOT_URL;?>"><img src="<?php echo ROOT_URL; ?>img/logo.png"></a> 
     
     <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12 pull-right">
  	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="display: none;">
        	<select class="form-control" name="search_type" id="search_type">
          <option value="Stores" selected>Stores</option>
          <option value="Products">Products</option>
          <option value="Categories">Categories</option>
         </select>
        </div>
         
     <div class="header_findstore">
        	<div class="srcinpnew-wrps" style="width:40%">
       	<i class="fa fa-search srcinp-fa"></i>
        <input type="text" name="address" id="search_box" class="form-text form-control" placeholder="Search Delivery or Dispensary"/>
        <button class="icn-closebtn" type="button">x</button>
		<span class="srch-loadingicon" id="load1" style="display:none"><i class="fa fa-spinner fa-spin"></i></span>
       
       </div>
       
       <div class="lic_chk_wrap" style="width: 60%; float: right;">
    <!--   	<label class="checkbox" for="is_licensed" style="color: #ffffff;"><input name="is_licensed" id="is_licensed" class="form-text" type="checkbox"> Licensed Dispensary </label>-->
        <input type="hidden" name="is_licensed" id="is_licensed" />
        <a id="btn_is_licensed" class="btnlicense btn btn-primary"> Licensed  </a>
       
       
       <div class="legal_countdown"><p id="demo" style="margin: 0 0 3px 0"></p>
Legal Countdown</div>
</div>
<script>
// Set the date we're counting down to
var countDownDate = new Date("Jan 1, 2018 00:00:00").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

    // Get todays date and time
    var now = new Date().getTime();
    
    // Find the distance between now an the count down date
    var distance = countDownDate - now;
    
    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    // Output the result in an element with id="demo"
    document.getElementById("demo").innerHTML = days + "d " + hours + "h "
    + minutes + "m " + seconds + "s ";
    
    // If the count down is over, write some text 
    if (distance < 0) {
        clearInterval(x);
        document.getElementById("demo").innerHTML = "EXPIRED";
    }
}, 1000);
</script>
       <!--
       <div style="float:left;color: #ffffff;padding:10px 10px 0"></div>
     <div style="float:left;color: #ffffff;"> </div>-->
       
        
        </div></div>
     </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    
    
    
    
    <div class="col-lg-5 col-md-6 col-sm-5 col-xs-12"><div class="collapse navbar-collapse" id="navbarmenu_collapse">
    
     <?php if(!empty($_SESSION['regSuccess'])){ ?>
     <ul class="login-resiwps nav navbar-nav">
      <li><a href="<?=ROOT_URL;?>dashboard.php">Dashboard</a></li>
      <li><a href="<?=ROOT_URL;?>logout.php">Logout</a></li>
     </ul>
     <?php } else { ?>
     <ul class="login-resiwps nav navbar-nav">
      <?php if(isset($_SESSION['userid'])){?>
      <li><a href="#" data-toggle="modal" data-target="#myModal3" id="myaccount_view">My account</a></li>
      <li><a href="<?=ROOT_URL;?>logout.php">Logout</a></li>
      <?php }else {?>
      <li><a href="javascript:"  data-toggle="modal" data-target="#myModal1" id="login_id">Login</a></li>
      <li><a href="javascript:"  data-toggle="modal" data-target="#myModal2" onClick="reset();" id="register_id">Register</a></li>
      <li><a href="addstore.php">Register Dispensary</a></li>
      <?php } ?>
     </ul>
     <?php } ?>
     <ul class="social-wrps pull-right">
      <li><a target="_blank" href="<?php echo SOCIAL_TW_LINK;?>"><i class="fa fa-twitter"></i></a></li>
      <li><a target="_blank" href="<?php echo SOCIAL_FB_LINK;?>"><i class="fa fa-facebook"></i></a></li>
      <li><a target="_blank" href="<?php echo SOCIAL_INST_LINK;?>"><i class="fa fa-instagram"></i></a></li>
      <li><a target="_blank" href="<?php echo SOCIAL_LIN_LINK;?>"><i class="fa fa-linkedin"></i></a></li>
     </ul>
     
     
     </div></div>
    <!-- /.navbar-collapse -->
     </nav>
    </div>
     
 </div>
</div>
<script>
		var everyChild = document.querySelectorAll("#rating div");
		for (var i = 0; i<everyChild.length; i++) 
		{
			everyChild[i].classList.css('color','');
		}
	  </script>
<?php if(!empty($_SESSION['message'])) { ?>
<h4 style="text-align:center" class="alert alert-success" role="alert"><?php echo $_SESSION['message'] ?></h4>
<?php  unset($_SESSION['message']); } ?>
<div class="clear"></div>
<div id="main_content" class="container_12">
<div class="map_wrps">
	<div class="container-fluid pad-l0 pad-r0">
   <div class="right-mapwps">
     <div id="map-container">
      <div id="clinic-finder" class="clear-block">
       <div class="links"></div>
       <h5 style="color:#F40F13; margin:0;"><?php echo $msg; ?></h5>
       <div id="map-canvas" style="background-color:#43474E;width: 100%; height: 510px"></div>
       <div class="mapsidefx_wrps mapside_open" >
       	<span class="mapsidefx_toggle" data-toggle="tooltip" data-placement="right" title="Details"><i class="fa fa-angle-right"></i></span>
        <div class="mapsidefx_cntwrps mCustomScrollbar" data-mcs-theme="dark">
        <? if(!isset($_SESSION["StoreID"]) && empty($_SESSION["StoreID"])){ ?>
        <ul class="utilities" >
        <?php foreach($return_popup_stores as $return_popup_store_id => $return_popup_store){ 
			 if(isset($_GET['store'])) {
			 	?>  
         <li class="feature_store" id="store_map_block_<?=$return_popup_store_id;?>" onClick='search_by_shortlink(<?=$return_popup_store_id;?>,<?=$return_popup_store['latitude'];?>,<?=$return_popup_store['longitude'];?>);'> 
         <?php }else {?>
          <li class="feature_store" id="store_map_block_<?=$return_popup_store_id;?>" onClick='storedelivery(<?=$return_popup_store_id;?>,<?=$return_popup_store['latitude'];?>,<?=$return_popup_store['longitude'];?>);'> 
        <?php }?>
          <div class="mapsdfx_block" rel="<?=$return_popup_store_id;?>" style="cursor: pointer" data-lat="<?=$return_popup_store['latitude'];?>" data-long="<?=$return_popup_store['longitude'];?>">
          <div class="maps_img_tag">
          <?php /*?>	<img src="<?=$return_popup_store['cat_img'];?>" class="imb-responsive" onError="this.src='http://four20maps.com/admin/imgs/categories/41/image.jpg'"/>   <?php */?>        
          </div>
          <div class="mapsdfxblk_prfimg"><a href="javascript:"><img src="<?=$return_popup_store['image'];?>" onError="this.src='<?php echo ROOT_URL; ?>img/no_image.png'" class="imb-responsive"/></a></div>           
           <div class="mapsdfxblk_desc">
           <h3 class="mapsdfxblk_title"><a href="javascript:"><?=$return_popup_store['name'];?></a></h3>
											<?php $avg_rating_per = round($return_popup_store['avg_rating']/5*100); ?>
             <div class="mapsdfxblk_reviews">
              <div class="rating_wrap"><div class="rating_div">
             <div class="rating_images"></div>
             <div class="activerating_imgs" style="width:<?=$avg_rating_per;?>%;"></div>
            </div></div>
              <span class="mapsdfxblk_reviewstxt"><?=number_format($return_popup_store['avg_rating'], 1);?> by <?=$return_popup_store['total_reviews'];?> Reviews</span>
             </div>
             <span class="mapsdfxblk_place"><?=$return_popup_store['address'];?></span>
             <?php if(empty($return_popup_store['open_time']) || empty($return_popup_store['close_time'])){ ?>
             <span class="mapsdfxblk_openclosed" style="color: red"> Closed </span>
             <?php }else if(strpos($return_popup_store['status'],"Closed") === false){ ?>
             <?php /*?><span class="mapsdfxblk_openclosed">Timings : <?=$return_popup_store['open_time'];?> to <?=$return_popup_store['close_time'];?></span><?php */?>
              <span class="mapsdfxblk_openclosed"> <?=$return_popup_store['status'];?> </span>   
             <?php } else {?><?=$return_popup_store['status'];?>  
             <?php } ?>
             <span class="mapsdfxblk_distance">Distance : <?=number_format($return_popup_store['distance'],2);?> Miles</span>
             <div class="circle map_block_hits"><?=$return_popup_store['store_views']?> Hits</div>
            </div>
          </div>
         </li>
         
         <?php } ?>
         
          
        </ul>
        <?php } else if(isset($_SESSION["StoreID"]) && !empty($_SESSION["StoreID"])){
 
			
			?>
        
        <div class="table_view_wrapper" >
<h4>Marijuana Exchange</h4>
        <table class="table table-striped table-hover">
        <thead>
           <th> </th>
        	<th>Product </th>
        	<th> Store </th>
        	<th>Donation</th>
        	<th>Qty</th>
        	<th>  <span class="mapsidefx_toggle" data-toggle="tooltip" data-placement="right" title="Details" style="background: #F47A15;color: #ffffff;"><i class="fa">Back</i></span> </th>
        </thead>
  <tbody>
   
  <?php while($ticker_farm_array = mysql_fetch_array($ticker_result)){
	  if((int)($ticker_farm_array["price"]) != 0) {
	 $return_options = array();
$options = mysql_query('select * from categoryoptions where `categoryID`='.$ticker_farm_array["categoryID"]);
while($option = mysql_fetch_assoc($options))
{
	 
	if(in_array($option['optionID'],array(2,3,4)))
	$return_options[] = $option['value'];
}
	   
	  ?>
     <tr>
      <td width="50"><div class="tableblk_prfimg"><a style="border: none;" href="javascript:"  data-toggle="modal" data-target="#ticker_popup" class="rl-imwps"><img src="http://www.four20maps.com/<?=$ticker_farm_array["image"];?>" onerror="this.src='http://www.four20maps.com/img/no_image.png'" class="imb-responsive mCS_img_loaded"  data-img="<?php echo ROOT_URL.$ticker_farm_array["image"];?>"  data-productname="<?php echo $ticker_farm_array['categoryName']; ?>" data-productDescription="<?php echo $ticker_farm_array['productDescription']; ?>" data-cbd="<?=$return_options[0];?>" data-thc="<?=$return_options[1];?>" data-thcas="<?=$return_options[2];?>"></a></div></td>
      <td><?=$ticker_farm_array["categoryAliasName"];?></td> 
      <td><?=$ticker_farm_array["name"];?></td>       
      <td><?=$ticker_farm_array["price"];?></td>
      <td><?=$ticker_farm_array["qty"];?></td>
    
      <td><a href="javascript:" data-toggle="modal" class="btn btn-sm btn-primary modaldonatenow" data-target="#responsive" data-weight="<?=$ticker_farm_array["weight"];?>" data-total_price="<?=$ticker_farm_array["price"];?>" data-product_ten_price="<?=$ticker_farm_array["ten_price"];?>" data-product_hundered_price="<?=$ticker_farm_array["hundered_price"];?>"data-owner_id="<?=$ticker_farm_array["owner_id"];?>" data-category_id="<?=$ticker_farm_array["categoryID"];?>" data-product_id="<?=$ticker_farm_array["product_id"];?>" data-store_id="<?=$ticker_farm_array["store_id"];?>" data-store_description="<?=$ticker_farm_array["description"];?>" data-store_name="<?=$ticker_farm_array["name"];?>"  data-product_name="<?=$ticker_farm_array['categoryName'];?>" data-product_image="http://www.four20maps.com/<?=$ticker_farm_array["image"];?>" data-store_email="<?=$ticker_farm_array["email"];?>" data-store_phone="<?=!empty($ticker_farm_array["telephone"])? $ticker_farm_array["telephone"] : $ticker_farm_array["mobile"];?>" data-store_address="<?=$ticker_farm_array["address"];?>" data-store_website="<?=$ticker_farm_array["website"];?>" data-product_productDescription="<?=$ticker_farm_array["productDescription"];?>" data-logo="<?=$ticker_farm_array["store_logo"];?>">Donate</a></td>
    </tr>
    <?php }} ?>
	 
  </tbody>
</table>

<div id="ticker_popup" class="modal fade" role="dialog" style="background: #ffffff;">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="product_title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p><img id="thumbnil" src="" alt="image" class="img-responsive"/></p>
        <div class="row"> 
      <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12" id="product_description"></div>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><div class="product-itemwps-bx prditmsbxwdful"><div class="product-itemwps prdctitem_optiontype"><div class="prd-optsbx-txt mbwdfl"></div><div class="prd-optsbx-txt"><span class="prd-optsbx-txt-optype">CBD % :</span> <span class="prd-optsbx-txt-sb" id="cbd_per">0.00</span></div><div class="prd-optsbx-txt"><span class="prd-optsbx-txt-optype">THC % :</span> <span class="prd-optsbx-txt-sb" id="thc_per">0.00</span></div><div class="prd-optsbx-txt"><span class="prd-optsbx-txt-optype">THCA % :</span> <span class="prd-optsbx-txt-sb" id="thca_per">0.00</span></div></div></div></div> </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="terms" class="modal fade " role="dialog" style="background: #ffffff;z-index: 99999;">
  <div class="modal-dialog modal-dialog1">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="product_title">Terms & Conditions</h4>
      </div>
      <div class="modal-body">
       <p><?=$settings["term"];?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>



<div id="responsive" class="modal fade" role="dialog">
  <div class="modal-dialog">
 <div class="modal-content">
            <div class="modal-header modal-header1">
                <a href="#" data-dismiss="modal" class="class pull-right"><span class="fa fa-close  mar-right10"></span></a>
                <h3 class="mar-left10 modal-title" id="temp_store_name" style="font-size: 20px;"></h3><img src="http://www.four20maps.com/admin/imgs/stores/8141/humboldt_standard_single.png" id="temp_logo" class="mCS_img_loaded" style="float:right;margin-top: -20px;margin-right: 40px;" width="64px">
               <p style="background: #f47a15;color:#ffffff;padding: 10px"> 
			   <i class="fa fa-map-marker mar-lf10" aria-hidden="true"></i> <span class="mar-right10" id="temp_address"></span>
                <i class="fa fa-envelope-o mar-lf10" aria-hidden="true"></i><span class="mar-right10" id="temp_email"></span>
               <i class="fa fa-phone mar-lf10" aria-hidden="true"></i> <span class="mar-right10" id="temp_phone"></span>
                <i class="fa fa-home mar-lf10" aria-hidden="true"></i><span class="mar-right10" id="temp_website"></span></p>
                
            </div>
            <div class="modal-body">
                <div class="row">
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 product_img">
	<img src="http://www.four20maps.com/uploaded/product_images//1507443615.png" class="img-responsive" id="temp_product_image">
	 
</div>
<input type="hidden" name="weight" id="weight" value="0"/>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 product_content" style="flo">
	<!--				<div class="row"> 
		 
                       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> <h2 style="text-align:left;margin-top: 10px;border-bottom:none;"> Description :</h2><br/> <span id="temp_storedescription"></span> </div> 
	</div>-->
                      
                      
                      
	                     <div class="row">
		                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label">Product Name :</div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-value"><strong><span id="temp_product_name"></span></strong></div></div> 
                      <div class="clearfix"></div><div class="bot-border"></div>
                      <div class="row">
                       <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label">Product price 1 lbs :</div>
                       <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-value"><strong>$<span id="temp_product_price"></span></strong></div></div> 
                        <div class="clearfix"></div><div class="bot-border"></div> <div class="row">
                       <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label">Price 10+ units :</div>
                       <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-value"><strong>$<span id="temp_product_ten_price"></span></strong></div></div> 
                       <div class="clearfix"></div><div class="bot-border"></div>
                         <div class="row">
                       <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label">Price 100+ units:</div>
                       <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-value"><strong>$<span id="temp_product_hundred_price"></span></strong></div></div> 
                       
                       <div class="clearfix"></div><div class="bot-border"></div>
                       
                       
                       
                       <div class="row">
                       <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label">Quantity :</div><div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-value" style="padding: 0px;"> <input name="temp_qty" id="temp_qty" class="form-control" type="number" autofocus></div></div>
                       <div class="clearfix"></div><div class="bot-border"></div>
                         <div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label">Fee Per lbs:</div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-value"><strong>$<span id="temp_fee">25</span></strong></div></div> 
                        <div class="clearfix"></div><div class="bot-border"></div>
                        <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label" >Total Fee :</div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-value"><strong>$<span id="temp_total_fee">0</span></strong></div></div>  
                       <div class="clearfix"></div><div class="bot-border"></div> <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label">Total Price :</div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-value"><strong>$<span id="temp_total_price">0</span></strong></div></div>  
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-value"> <a href="#terms" data-toggle="modal"  data-target="#terms">Terms & Conditions</a></div></div>  
                        
                        <div class="space-ten"></div>
                        <div class="btn-ground">
                             <div class="row bt10">
        <div class="col-sm-12">
          
            <input type="button" class="btn btn-sm btn-primary cc_process btn-payment" value="Credit Card" />
             <input type="button" class="btn btn-sm btn-primary paypal_process btn-payment" value="Paypal" />
             <!--<input type="button" class="btn btn-sm btn-primary ach_process btn-payment" value="ACH" /> 
             
              <input type="button" class="btn btn-sm btn-primary bitcoin_process btn-payment" value="BitCoin" /> -->
          </div></div>
                        </div>
                    </div>
    <div class="row">            
                
  <div class="credit_card_div" style="display: none;margin-left: 20px;" >
        <form id="checkCreditcardDetails" class="form-horizontal" >
        <input type="hidden" value="" name="total_price" id="total_price" />
        <input type="hidden" value="" name="owner_id" id="owner_id" />
        <input type="hidden" value="" name="category_id" id="category_id" />
        <input type="hidden" value="" name="product_id" id="product_id" />
        <input type="hidden" value="" name="store_id" id="store_id" /> 
         <input type="hidden" value="" name="qty" id="qty" /> 
         <input type="hidden" value="" name="payment_type" id="payment_type" /> 
         <input type="hidden" value="100" name="fee" id="fee" /> 
      <div class="row">
      
      	<div class="main-loader" style="display:none;">
        	   <span>Credit Card is processing</span>
        </div>
        
        <div class="main-creditdetails">
        
        <div class="form-group">
          <div class="col-md-12" id="error-message"> </div>
        </div>
        <div class="form-group">
          <label for="nameOnCard" class="control-label col-sm-4 control_label">Name as it appears on Card<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="nameOnCard" name="nameOnCard" placeholder="Name as it appears on Card" value="" required="required">
          </div>
        </div>
        <div class="clearfix"></div><div class="bot-border"></div>
        <div class="form-group">
          <label for="cardNumber" class="control-label col-sm-4 control_label">Credit Card Number<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="cardNumber" name="cardNumber" value="" placeholder="Credit Card Number" >
          </div>
        </div>
         <div class="clearfix"></div><div class="bot-border"></div>
        <div class="form-group">
          <label for="CVVCode" class="control-label col-sm-4 control_label">CVC Code<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="CVVCode" name="CVVCode" value="" placeholder="CVC Code" >
          </div>
        </div>
        <div class="clearfix"></div><div class="bot-border"></div>
        <div class="form-group">
          <label for="expiry" class="control-label col-sm-4 control_label">Expiration Month<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <select id="expiry_month" name="expiry_month" class="form-control" >
            <?php for($year = 1; $year <= 12; $year++){ ?>
            	<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                <?php } ?>
            </select>
          </div>
        </div>
        <div class="clearfix"></div><div class="bot-border"></div>
        <div class="form-group">
          <label for="expiry" class="control-label col-sm-4 control_labeltext">Expiration Year<strong class="star">*</strong> : </label>
          <div class="col-sm-6">
            <select id="expiry_year" name="expiry_year" class="form-control" >
            <?php for($year = date("Y"); $year <= date("Y")+20; $year++){ ?>
            	<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                <?php } ?>
            </select>
          </div>
        </div>
        <div class="clearfix"></div><div class="bot-border"></div>
      </div>
      
      </div>
      
       <div class="modal-footer"  style="display: none">
      <button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
      <button type="button" class="btn btn-sm btn-primary donatenow">Pay</button>
    </div></form>
    </div>
    </div>
    <div class="row">
	  <div class="ach_div" style="display: none"><h1>ACH PROCESS</h1>

	  
	  
	  
	  
	  
	  </div></div>
	  
	  <div class="row">
	   <div class="paypal_div" style="display: none" ><h1>PAYPAL PROCESS</h1><br/>
 	    
	 	   <form id="paypal_form" class="form-horizontal" action="paypal_payment.php" target="_blank" method="post">
	   <input type="hidden" value="" name="total_price" id="paypal_total_price" />
        <input type="hidden" value="" name="owner_id" id="paypal_owner_id" />
        <input type="hidden" value="" name="category_id" id="paypal_category_id" />
        <input type="hidden" value="" name="product_id" id="paypal_product_id" />
        <input type="hidden" value="" name="store_id" id="paypal_store_id" /> 
         <input type="hidden" value="" name="qty" id="paypal_qty" /> 
         <input type="hidden" value="" name="payment_type" id="paypal_payment_type" /> 
          <input type="hidden" value="" name="productname" id="paypal_productname" /> 
		  <div style="text-align: center"> <button type="submit" class="btn btn-lg btn-primary" style="margin-right:50%">Paypal Donation</button></div>
      
     
      </form>
	   
	   
	   
	   
	   
	   </div></div><div class="row">
	   <div class="bitcoin_div" style="display: none" ><h1>BITCON PROCESS</h1></div> </div> 
  
     	</div>
                
            </div>
        </div>
     
       
    </div>

  </div>
 
 <?php } ?>
        </div>
       </div>
       <div class="adsfeature_wrps">
       <div class="container">
       
  
   <? 
					if(count($images)>0){
						?>
						<div class="bxslider-wrap">
						<ul class="bxslider" style="100%">
						<?php
						if(count($images)==1)
							$end=0;
						else if(count($images)>1)
							$end = count($images);
						else 
							$end = count($images);
						#echo $end; die;
						for($i=0 ; $i<$end;$i++)
						{
							$web = $images[$i]['url'];
							if (strpos($web,'http://') === false)
							$web = 'http://'.$web;

							echo '<li><div class="adsfeatblock"><a onClick="window.open(\''.$web.'\')" target="_blank"><img src="'.ROOT_URL.'admin/uploads/'.$images[$i]['image'].'"  class="rightbaradsPadd"/></a></div></li>';
						}
						?>
		   </ul>
		   </div>
		   <?php
					}
					 /*if(count($images)<4)
						for($i=1 ; $i<=4- count($images);$i++)
							echo '<div class="adsfeatblock"><img data-toggle="modal" data-target="#myModal4" src="img/add-banner160x240.jpg" class="rightbaradsPadd"/></div>'; */
					?>
   <!--<div class="adsfeatblock"><img src="img/add-banner160x240.jpg"  id="addenquiry" data-toggle="modal" data-target="#myModal4"  class="rightbaradsPadd"/></div>-->
   </div>
   </div>
       <div id="store_information" class="storeinfo_hmbxwrps">
       <div class="container-fluid">
          <div class="row" id="storeinformation">
           <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 pad-l0 pad-r0"><div class="storeinfohmbx_left">
             <div class="storeinfohmbx_img"> <img class="store_image" id="store_image"/> </div>
             <div class="storeinfohmbx_infodesc">
              <div class="bdy store_namelo" name='store_name'></div>
              <div class="bdy"  name='store_addr'></div>
              <div class="bdy"  name='store_deli'></div>
              <div class="view-tx"  name='store_telephone'></div>
              <div class="bdy" name="store_rating" style="display:none"></div>
              <div class="bdy"  name='store_id' style="display:none"></div>
             </div>
           </div></div>
           <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 pad-l0 pad-r0"><div class="storeinfohmbx_right">
            <div class="bdy"  name='store_email' style="display:none"></div>
            <div class="bdy"  name='store_website' style="display:none"></div>
            <div class="bdy" name='store_desc'></div>
            <?if($_SESSION['userid']){?>
            <?}?>
            <div class="row" style="display:none;">
             <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="rateuswps"><a OnClick="removeCHCKD();" id="rateus" class="rating" style="display:none" data-toggle="modal" data-target="#myModal5" href="#">Rate Us</a></div>
              <div class="review-wps"><a id="storecomments" class="" style="display:none" data-toggle="modal" data-target="#myModal6" href="#">Reviews</a></div>
             </div>
            </div>
           </div></div>
          </div>
        </div>
       </div>
       <div id="results" class="resultsfbx_wrps">
       <div class="srchfilter_wrps">
       	<div class="container">
       	
         <!--	<div class="srchflr_label"><label><i class="fa fa-filter"></i> Search Filter</label></div>-->
        	<div class="srchflr_inputtext">
         	<div class="searchindi search-productlist">
            <input type="text"  id="store_search" class="search-fild form-control" placeholder="Search Filter"/>
           <!--<div class="searIcon"><img src="img/searchicon.png" id="store_search-btn"></div>--> 
          </div>
         </div>
         <div class="srchflr_flrsicon" id="filter_search_res" style="display:none">
         <div class="all-icon clearfix text-center"> 
           <!--<div class="s-icon">Sativa</div>
                            <div class="h-icon">Hydrid</div>
                            <div class="i-icon">indica</div>-->
           <?
							$class='s-icon';
							foreach($menu as $m)
							{
								if($m=="Indica")
								$class='i-icon';
								else if($m=="Hybrid")
								$class='h-icon';
								echo '<div id="sicon" class="'.$class.'" name="'.$m.'">'.$m.'</div>';
							}
							
							?>
          </div>
        </div>
        <div class="srchflr_siconmenu">
        	<div class="btn-group" id="category-menu-div" style="display:none;">
           <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Categories <span class="caret"></span> </button>
           <ul class="dropdown-menu" id="category-menu">
           </ul>
          </div>
        </div>
        </div>
       </div>
       <div class="store_productswrps">
       	<div class="container">       	
        <div class="tabsftm_wrps">
         <!-- Nav tabs -->
         <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#tabftmstp_01" aria-controls="" role="tab" data-toggle="tab">Menu</a></li>
          <li role="presentation"><a href="#tabftmstp_02" aria-controls="" role="tab" data-toggle="tab">Details</a></li>
          <li role="presentation"><a href="#tabftmstp_03" aria-controls="" role="tab" data-toggle="tab">Reviews</a></li>
        <!--  <li role="presentation"><a href="#tabftmstp_04" aria-controls="" role="tab" data-toggle="tab">Deals</a></li>-->
         </ul>
         <!-- Tab panes -->
         <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="tabftmstp_01"><div id='store_products'></div></div>
          <div role="tabpanel" class="tab-pane" id="tabftmstp_02">
          <h3 class="h3_headtitle"></h3>
			<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<div id="js_first_time_patients" class="panel panel-default">
	<div class="panel-body">
		<h3>First-Time Patients</h3>
		<p>No data found.</p>
	</div>
</div>
<div id="js_announcement" class="panel panel-default">
	<div class="panel-body">
		<h3>Announcement</h3>
		<p>No data found.</p>
	</div>
</div>
<div id="js_about_us" class="panel panel-default">
	<div class="panel-body">
		<h3>About Us</h3>
		<p>No data found.</p>
	</div>
</div>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="sdbrrht_wrps">
		<div class="panel panel-default">
	<div class="panel-body">
		<h3>Store Hours</h3>
		<ul id="js_timings" class="ulsdlist_wrps">
			<li><span class="ullist_lbl">No data found.</li>
		</ul>
		<ul class="ulsdlist_wrps">
			<li><span class="ullist_lbl">Phone</span> <span id="js-review-phone"></span></li>
			<li><span class="ullist_lbl">Email</span> <a id="js-review-email" href="mailto:cali.oil619@gmail.com"></a></li>
			<li><span class="ullist_lbl">Website</span> <a id="js-review-website" href="www.cali-oil.com"></a></li>
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
          <div role="tabpanel" class="tab-pane" id="tabftmstp_03">
          	<div class="review_custwrps" id="review_customermh">
            <h1 class="h1_headtitle">Customer Reviews</h1>
			
             <div class="row">
              <div class="col-lg-9 col-md-9 col-sm-10 col-xs-12 marginauto" id="js-load-store-reviews">
               <div class="reviewcust_block">
                 <div class="reviewcustblk_quote">
                 "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur."
                 </div>
                 <h3 class="reviewcustblk_name">- Theressa Lam</h3>
                 <div class="store-rating-starwrps rating">
                  <i class="star-1 fa fa-star"></i>
                  <i class="star-2 fa fa-star"></i>
                  <i class="star-3 fa fa-star"></i>
                  <i class="star-4 fa fa-star"></i>
                  <i class="star-5 fa fa-star"></i>
                 </div>
                </div>
              </div>
             </div>
			 
			 <div class="row">
              <div class="col-lg-9 col-md-9 col-sm-10 col-xs-12 marginauto">
			  
			  
			 <?php $uid = $_SESSION['userid']; if(empty($uid)){$uid = $_SESSION['StoreID'];} ?>
			<?php if($uid == '') { ?>
			<h4 style="text-align:center; color:#000">To leave a review please <a href="#myModal2"  data-toggle="modal" data-dismiss="modal"  id="register_id"><u style="color:5dc2ed">Register</u></a> OR <a href="#myModal1"  data-toggle="modal" data-dismiss="modal" id="login_id"><u style="color:5dc2ed">Login</u></a> <br>
			</h4>
			<?php }else{ ?>
			
			<form id='rating_form'>
			  <div class="row">
				<div class="col-lg-10 col-md-10 col-sm-10 col-xs-9">
				<div class="ratcontwrps">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<h3 id="RatingStoreName"></h3>
							<div class="rating" data-rate="1" id="rchange">
								<fieldset class="rating">
									<input type="radio" value="1" name="rate" id="star1">
									<i class="star-1 lirate fa fa-star" value="1"></i>
									<input type="radio" value="2" name="rate" id="star2">
									<i class="star-2 lirate fa fa-star" value="2"></i>
									<input type="radio" value="3" name="rate" id="star3">
									<i class="star-3 lirate fa fa-star" value="3"></i>
									<input type="radio" value="4" name="rate" id="star4">
									<i class="star-4 lirate fa fa-star" value="4"></i>
									<input type="radio" value="5" name="rate" id="star5">
									<i class="star-5 lirate fa fa-star" value="5"></i>
									<span id="SpanText" class="rating-text">Select your rating.</span>
								</fieldset>
							</div>
						</div>
					</div>
				 <div class="row">
				  <?php $uid = $_SESSION['userid']; if(empty($uid)){$uid = $_SESSION['StoreID'];} ?>
				  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-bottom-10">
				   <textarea id="rating_comments" name="rating_comments" <? if($uid == ''){ echo "style='display:none'";}?> placeholder="write your review" class="form-control"></textarea>
				  </div>
				 </div>
				 <div class="row">
				  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
				  
				   <button class="btn btn-primary" onClick="saveRating('<?php echo $uid; ?>');return false;">Submit</button>
				  </div>
				 </div>
				</div>
			   </div>
			  </div>
			 </form>
			<?php } ?>
			 
              </div>
             </div>
			 
			 
           </div>
          </div>
         <!-- <div role="tabpanel" class="tab-pane" id="tabftmstp_04" style="display: ">
			<div class="">
   <h3 class="h3_headtitle">Cannabis Deals</h3>
			<div class="panel panel-default">
			<div class="panel-body">
			<h3>First-Time Patients</h3>
			<p>$5 Off all 1/8 & Presidential Cone</p>
			<p>Have your rec and ID ready when you call in or text it to 619 830-0710</p>
			<p>CA prop 215, SB 420</p>
			<p>Now accepting credit cards</p>
			<p>1/2 oz Deals Daily</p>
			<p>P.R. $190</p>
			<p>Vape $40 0.5g</p>
			<p>$5 Off Cali-Oil Products</p>

			</div>
			</div>
		  </div>
		  </div>-->
         </div>
        </div>
       </div>
       </div>
      </div>
      
     </div>
    <button style="display:none" data-toggle="modal" data-target="#loadingDiv" id="ToggleMe"></button>
    </div>
    </div>
</div>

 <?php /*?><div class="feature-wps home-content">
  <div class="container custom-container">
   
   <div class="row">
    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12"> <img src="img/footer-logo.jpg"/ class="img-responsive"> </div>
    
   </div>   
  </div>
  <div class="leftbar-ads">
   <? 
					if(count($images)>0){
						if(count($images)==1)
							$end=0;
						else if(count($images)>1)
							$end = 1;
						else 
							$end = count($images);
						#echo $end; die;
						for($i=0 ; $i<=$end;$i++)
						{
							$web = $images[$i]['url'];
							if (strpos($web,'http://') === false)
							$web = 'http://'.$web;

							echo '<a onClick="window.open(\''.$web.'\')" target="_blank"><img src="'.ROOT_URL.'admin/uploads/'.$images[$i]['image'].'"  class="rightbaradsPadd"/></a>';
						}
						
					}
					if(count($images)<2)
						for($i=1 ; $i<=2- count($images);$i++)
							echo '<img data-toggle="modal" data-target="#myModal4" src="img/add-banner160x240.jpg"  class="rightbaradsPadd"/>';
					?>
   <img src="img/ads-enquiry.jpg"  id="addenquiry" data-toggle="modal" data-target="#myModal4" style="padding:7px 0px;" />
   </div>
   
  <div class="rightbar-ads">
   <?if(count($images)>1){
						for($i=2 ; $i<count($images);$i++)
						{
							$web = $images[$i]['url'];
							if (strpos($web,'http://') === false)
							$web = 'http://'.$web;
							echo '<a onClick="window.open(\''.$web.'\')" target="_blank"><img src="'.ROOT_URL.'admin/uploads/'.$images[$i]['image'].'"  class="rightbaradsPadd"/></a>';
						}
					}
					
					if(count($images)<5)
					{
						for($i=0 ; $i< 4-count($images);$i++)
						echo '<img data-toggle="modal" data-target="#myModal4" src="img/add-banner160x240.jpg"  class="rightbaradsPadd"/>';
					}
					?>
  </div>
 </div><?php */?>
</div>
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title LRhd" id="myModalLabel">Login</h4>
   </div>
   <div class="modal-body">
    <div class="alert alert-success" style="display:none; text-align:center" id="loginmsg"></div>
    <form class="xform res-log-alg" name="login_form" OnSubmit="return false;" method="post" id="loginForm">
     <section>
      <div class="row">
       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label class="input"> <i class="icon-prepend fa fa-user"></i>
         <input type="text" placeholder="Username" name="username" class="form-control" id="loginuser" required="" />
        </label>
       </div>
      </div>
     </section>
     <section>
      <div class="row">
       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label class="input"> <i class="icon-prepend fa fa-lock"></i>
         <input type="password" placeholder="Password" class="form-control" name="password" id="loginpass"  required="" />
        </label>
       </div>
      </div>
     </section>
     <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
       <input type="checkbox">
       Remember me</div>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right res-text-alg-left"><a href="#" onClick="fp()" data-toggle="modal" data-target="#forgot_pass">Forgot password</a> &nbsp &nbsp <a href="#forgotEmail"  data-toggle="modal" data-dismiss="modal">Forgot Username</a></div>
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 login-btn-mar">
       <button class="btn btn-success" onClick="jslogin();" type="submit" id="submit_login">Login</button>
      </div>
     </div>
     <input type="hidden" value="1" name="doLogin">
    </form>
   </div>
  </div>
 </div>
</div>
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title LRhd" id="myModalLabel">Register (New user)</h4>
   </div>
   <div class="modal-body">
    <form class="xform" OnSubmit="return false;" name="login_form" id="register_form">
     <input type="hidden" value="" name="upid">
     <section>
      <div class="row">
       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-mb20-resp">
        <label class="input"> <i class="icon-prepend fa-user"></i>
         <input type="text" id="regfname" maxlength="10" placeholder="First Name" class="form-control" value="" OnBlur="func(this.id);" name="firstname" required aria-required="true"/>
        </label>
       </div>
       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <label class="input"> <i class="icon-prepend fa-user"></i>
         <input type="text" id="reglname" maxlength="10" placeholder="Last Name" OnBlur="func(this.id);" class="form-control" value="" name="lastname" required aria-required="true"/>
        </label>
       </div>
      </div>
     </section>
     <section>
      <div class="row">
       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label class="input"> <i class="icon-prepend fa-envelope"></i>
         <input type="email" id="regemail" maxlength="30" placeholder="Email Id" class="form-control" value="" name="email" required aria-required="true"/>
        </label>
       </div>
      </div>
     </section>
     <section>
      <div class="row">
       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label class="input"> <i class="icon-prepend fa-user"></i>
         <input type="text" id="reguser" maxlength="10" placeholder="User Name" class="form-control" value="" name="username" required aria-required="true"/>
        </label>
       </div>
      </div>
     </section>
     <section>
      <div class="row">
       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label class="input"> <i class="icon-prepend fa fa-lock"></i>
         <input type="password" id="regpassword" maxlength="20" placeholder="Password" class="form-control" name="password" required aria-required="true"/>
        </label>
       </div>
      </div>
     </section>
     <section>
      <div class="row">
       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label class="textarea">
          <input class="form-control required alpha" placeholder="Address" name='address' id='regaddress' value="<?php echo isset($storeDetails['address']) ? $storeDetails['address'] : '' ?>">
         <?php /*?><textarea rows="5" name="address" maxlength="100"  id="regaddress" placeholder="Address" class="form-control" required aria-required="true"><?=$geocode['geoplugin_city'].", ".$geocode['geoplugin_region'];?></textarea><?php */?>
         </textarea>
        </label>
       </div>
      </div>
     </section>
     <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
       <button name="dosubmit" class="btn btn-success" onClick="userlogin();">Register</button>
       <!--<a href="register.php" class="button button-secondary">Register</a>--> 
      </div>
     </div>
    </form>
   </div>
  </div>
 </div>
</div>
<script type="text/javascript"  src="<?=ROOT_URL;?>js/jquery.validate.min.js"></script> 
<style>
    .pac-container {
        z-index: 10000 !important;
    }
</style>
<script type="text/javascript">
	autocomplete = new google.maps.places.Autocomplete(document.getElementById('regaddress'));
		$(function() {
			function chechfooter(){
				var winH = $(window).height();
				 var conH = $('.container').height() + 60;
				 console.log(winH + ' ' + conH); 
				 if(conH < winH){
					 $('#footer').addClass('fixed-footer');
				 }
				 else{
					 $('#footer').removeClass('fixed-footer');
				 }
			}
			chechfooter();
			$(window).resize(function(){
				chechfooter();
			});
			
		});
$(document).ready(function() {
		$("#register_form").validate();
	});
	</script> 
<!-- Add store -->
<div class="modal fade" id="myModal11" tabindex="-1" role="dialog" aria-labelledby="myModalLabel11" aria-hidden="true">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title LRhd" id="myModalLabel">Add Store</h4>
   </div>
   <div class="modal-body">
    <form class="xform" name="addstore_form" onSubmit="jsaddstore();return false;">
     <input type="hidden" value="" name="upid">
     <section>
      <div class="row">
       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-mb20-resp">
        <label class="input"> <i class="icon-prepend fa-user"></i>
         <input type="text" id="regname" placeholder="Name" class="form-control" value="" name="name" />
        </label>
       </div>
       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <label class="select"> <i class="icon-prepend fa-user"></i> 
         <!--<input type="text" id="regcategory" placeholder="Category" class="form-control" value="" name="category" />-->
         <select name='category' class="form-control" id="regcategory" >
          <option value=""> Select Category </option>
          <?php
                                       // mysql_connect("localhost","root","");
                                        mysql_select_db("four20ma_storefinder");
                                        $list=mysql_query("SELECT  cat_name FROM categories");
                                        while($row_list=mysql_fetch_array($list)){
                                        ?>
          <option value="<?php echo $row_list['cat_name']; ?>"> <? echo $row_list['cat_name'];  ?> </option>
          <?php
                                        }
                                        ?>
         </select>
        </label>
       </div>
      </div>
     </section>
     <section>
      <div class="row">
       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <label class="input"> <i class="icon-prepend fa-phone"></i>
         <input type="text" id="regtelephone" placeholder="Telephone" class="form-control" value="" name="telephone" pattern="[/^(\+?([0-9]{2})-?([0-9]{3})-?([0-9]{6,7})$/]"s />
        </label>
       </div>
       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <label class="input"> <i class="icon-prepend fa-envelope"></i>
         <input type="email" id="regemail1" placeholder="Email" class="form-control" value="" name="email" />
        </label>
       </div>
      </div>
     </section>
     <section>
      <div class="row">
       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <label class="textarea"><i class="icon-prepend fa-map-marker"></i>
         <textarea rows="5" name="address" id="regaddress1" placeholder="Address" class="form-control" >
         </textarea>
        </label>
       </div>
       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <label class="textarea"><i class="icon-prepend fa-file-text-o"></i>
         <textarea rows="5" name="description" id="regdescription" placeholder="Description" class="form-control" />
         </textarea>
        </label>
       </div>
      </div>
     </section>
     <section>
      <div class="row">
       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <label class="input"> <i class="icon-prepend fa-globe"></i>
         <input type="text" id="regwebsite" placeholder="Website" class="form-control" value="" name="website" />
        </label>
       </div>
       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <input type='file' name="addstore_image" id="regimage" class="form-control" placeholder="Image" accept="image/*">
        <p style="color:red; margin-top:5px; margin-bottom:0;"> Please upload 160x240 or higher resolution images</p>
       </div>
      </div>
     </section>
     <section>
      <div class="row">
       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <label class="input"> <i class="icon-prepend fa-user"></i>
         <input type="text" id="reglatitude" placeholder="Latitude" class="form-control" value="" name="latitude" />
        </label>
       </div>
       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <label class="input"> <i class="icon-prepend fa-user"></i>
         <input type="text" id="reglongitude" placeholder="Longitude" class="form-control" value="" name="longitude" />
        </label>
       </div>
      </div>
     </section>
     <section>
      <div class="row">
       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <button class="btn btn-success" name="dosubmit" onClick="" type="submit" id="submit_addstore">Save</button>
        <!--<a href="register.php" class="button button-secondary">Register</a>--> 
       </div>
      </div>
     </section>
    </form>
   </div>
  </div>
 </div>
</div>

<!-- End Add store -->

<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title LRhd" id="myModalLabel">Update Your Profile</h4>
   </div>
   <div class="modal-body">
    <form method="post"  id="update_form" name="update_form" class="xform">
     <input type="hidden" name="idd" id="idd" value="<?php echo $_SESSION['userid']; ?>" >
     <section>
      <div class="row">
       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-mb20-resp">
        <label class="input"> <i class="icon-prepend fa-user"></i>
         <input  type="text" name="username" id="username" class="form-control" placeholder="Username" value="<?php echo $regfetch['username']; ?>" disabled>
        </label>
       </div>
       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <label class="input"> <i class="icon-prepend fa-user"></i>
         <input  type="text" name="email" id="email" class="form-control" placeholder="Email" value="<?php echo $regfetch['email']; ?>" disabled>
        </label>
       </div>
      </div>
     </section>
     <section>
      <div class="row">
       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-mb20-resp">
        <label class="input"> <i class="icon-prepend fa-user"></i>
         <input  type="text" name="firstname" id="firstname" Onblur="func(this.id)"; placeholder="First Name" value="<?php echo $regfetch['firstname']; ?>" class="required form-control">
        </label>
       </div>
       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <label class="input"> <i class="icon-prepend fa-user"></i>
         <input  type="text" name="lastname" id="lastname" Onblur="func(this.id)"; placeholder="Last Name" value="<?php echo $regfetch['lastname']; ?>" class="required form-control">
        </label>
       </div>
      </div>
     </section>
     <section>
      <div class="row">
       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label class="textarea">
         <textarea name="addressm" id="addressm" rows="5" value="<?php echo $regfetch['address']; ?>" class="required form-control"><?php echo $regfetch['address']; ?></textarea>
        </label>
       </div>
      </div>
     </section>
     <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
       <button type="button" class="button button-secondary Update" name="update" onclick="updatereg();"> Update </button>
       <!--<a href="register.php" class="button button-secondary">Register</a>--> 
      </div>
     </div>
    </form>
   </div>
  </div>
 </div>
</div>
<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title LRhd" id="myModalLabel">Add Enquiry</h4>
   </div>
   <div class="modal-body">
    <form method="post"  action="" id="add_form" name="" class="xform">
     <section>
      <div class="row">
       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-mb20-resp">
        <label class="input"> <i class="icon-prepend fa-user"></i>
         <input  type="text" maxlength="10" name="adduser_name" OnBlur="func(this.id)" id="adduser_name" placeholder="Full Name" value="" class="required form-control">
        </label>
       </div>
       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-mb20-resp">
        <label class="input"> <i class="icon-prepend fa-envelope"></i>
         <input  type="email" maxlength="30" name="adduser_email" id="adduser_email" OnBlur="emailVal(this.id);" placeholder="Email" value="" class="required form-control">
        </label>
       </div>
      </div>
     </section>
     <section>
      <div class="row">
       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-mb20-resp">
        <label class="input"> <i class="icon-prepend fa-phone"></i>
         <input  type="text" maxlength="10" name="adduser_phone" id="adduser_phone" placeholder="Phone" value="" class="required form-control">
        </label>
       </div>
       <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-mb20-resp">
        <label class="input"> <i class="icon-prepend fa-link"></i>
         <input  type="text" name="adduser_url" id="adduser_url" placeholder="URL" Onblur="url(this.id);" class="required form-control">
        </label>
       </div>
      </div>
     </section>
     <section>
      <div class="row">
       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <input type='file' name="adduser_image" OnFocus="$(this).css('border-color','');" id="adduser_image" placeholder="Image" accept="image/*">
       </div>
       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p style="color:red;margin-top:5px"> Please upload 160x240 or higher resolution images </p>
       </div>
      </div>
     </section>
     <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
       <button type="button" class="button button-secondary Update" name="update" id="addEnquiry_button"> Enquire </button>
       <!--<a href="register.php" class="button button-secondary">Register</a>--> 
      </div>
     </div>
    </form>
   </div>
  </div>
 </div>
</div>
<div class="modal fade" id="myModal5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title LRhd" id="myModalLabel">Rate Us</h4>
   </div>
   <div class="modal-body">
    <section>
     <form id='rating_form'>
      <div class="row">
       <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
        <div class="rating-store-images"><img id="StoreRatingImg" src="<?=ROOT_URL;?>store.png" class="img-responsive"/></div>
       </div>
		<div class="col-lg-10 col-md-10 col-sm-10 col-xs-9">
		<div class="ratcontwrps">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<h3 id="RatingStoreName"></h3>
					<div class="rating" data-rate="1" id="rchange">
						<fieldset class="rating">
							<input type="radio" value="1" name="rate" id="star1">
							<i class="star-1 lirate fa fa-star" value="1"></i>
							<input type="radio" value="2" name="rate" id="star2">
							<i class="star-2 lirate fa fa-star" value="2"></i>
							<input type="radio" value="3" name="rate" id="star3">
							<i class="star-3 lirate fa fa-star" value="3"></i>
							<input type="radio" value="4" name="rate" id="star4">
							<i class="star-4 lirate fa fa-star" value="4"></i>
							<input type="radio" value="5" name="rate" id="star5">
							<i class="star-5 lirate fa fa-star" value="5"></i>
							<span id="SpanText" class="rating-text">Select your rating.</span>
						</fieldset>
					</div>
				</div>
			</div>
         <div class="row">
		  <?php $uid = $_SESSION['userid']; if(empty($uid)){$uid = $_SESSION['StoreID'];} ?>
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-bottom-10">
           <textarea id="rating_comments" name="rating_comments" <? if($uid == ''){ echo "style='display:none'";}?> placeholder="write your review" class="form-control"></textarea>
          </div>
         </div>
         <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
          
           <button class="btn btn-primary" onClick="saveRating('<?php echo $uid; ?>');return false;">Submit</button>
          </div>
         </div>
        </div>
       </div>
      </div>
     </form>
    </section>
   </div>
  </div>
 </div>
</div>
<div class="modal fade" id="forgotEmail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title LRhd" id="myModalLabel">Forgot User Name</h4>
   </div>
   <div class="modal-body">
    <div class="alert alert-success" id="frgtUalert" style="text-align:center; display:none"></div>
    <form method="post" OnSubmit="return false;" class="xform">
     <section>
      <div class="row">
       <div class="col-lg-12">
        <label class="input"> <i class="icon-prepend fa fa-envelope"></i>
         <input type="email" id="frgtemailid" class="form-control" name="email" placeholder="Please Enter Your Registered Email ID" aria-required="true" required="" />
        </label>
       </div>
      </div>
     </section>
     <input type="hidden" name="type" value="1" id="type" />
     <div class="row">
      <div class="col-lg-12">
       <button  onclick="frgtuser();" class="btn btn-success">Submit</button>
      </div>
     </div>
    </form>
   </div>
  </div>
 </div>
</div>
<div class="modal fade" id="forgot_pass" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title LRhd" id="myModalLabel">Forgot Password</h4>
   </div>
   <div class="modal-body">
    <div class="alert alert-success" id="frgtmsg" style="display:none; text-align:center"></div>
    <form class="xform" name="login_form" OnSubmit="return false;" method="post" id="loginForm">
     <section>
      <div class="row">
       <input type="hidden" value="2" id="typeforgot"/>
       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label class="input"> <i class="icon-prepend fa fa-envelope"></i>
         <input type="text" placeholder="Email" class="form-control" name="Email_for"  id="Email_for" required="" aria-required="true"  />
        </label>
       </div>
      </div>
     </section>
     <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
       <button class="btn btn-success" name="doFPsubmit" onClick="forgotpass();" type="submit" id="submit_login">Submit</button>
       <img src="<?php echo ROOT_URL; ?>img/loading.gif" id="passimg" style="height:25px; display:none" /> </div>
     </div>
    </form>
   </div>
  </div>
 </div>
</div>
<div class="modal fade" id="myModal6" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title LRhd" id="myModalLabel">Reviews</h4>
   </div>
   <div class="modal-body">
    <?php $uid = $_SESSION['userid']; if(empty($uid)){$uid = $_SESSION['StoreID'];} ?>
    <?php if($uid == '') { ?>
    <h4 style="text-align:center; color:#000">To leave a review please <a href="#myModal2"  data-toggle="modal" data-dismiss="modal"  id="register_id"><u style="color:5dc2ed">Register</u></a> OR <a href="#myModal1"  data-toggle="modal" data-dismiss="modal" id="login_id"><u style="color:5dc2ed">Login</u></a> <br>
    </h4>
    <?php } ?>
    <section>
     <form id=''>
      <div class="row">
       <div class="col-md-9">
        <div id='review_form'></div>
       </div>
      </div>
     </form>
    </section>
   </div>
  </div>
 </div>
</div>

<!-- *********************************Loading Div************************************* -->
<div class="modal fade" id="loadingDiv" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true" style="padding-top: 17em;">
 <div class="modal-dialog">
  <div align="center"><img src="<?php echo ROOT_URL; ?>img/progress.gif" style="height:50px"/></div>
 </div>
</div>


<div id="productDescriptionModal" class="modal fade" role="dialog">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">&times;</button>
<h4 class="modal-title" id="product_title">Modal Header</h4>
 </div>
<div class="modal-body">
<p><img id="thumbnil" src="" alt="image"/></p>
<div class="row"> 
<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12" id="product_description"></div>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="product_options"></div>'
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
 </div>
</div>
</div>
</div>
</div>


<!-- *********************************Loading Div************************************* -->
<?php include ROOT."themes/footer.inc.php"; ?>
<script>
function reset()
{
	$('#register_form')[0].reset();
}
var x;
$(document).ready(function(){
$(':file').change(function()
{
		var file = this.files[0];
		var name = file.name;
		var size = file.size;
		var type = file.type;
		var error=0;
		if(file.name.length < 1) 
		{
			alert("Invalid File name")
			$(this).val('');
			error=1;
		}
		else if(file.size > 2000000) 
		{
			alert("File is too big, Max allowed size: 2MB");
			$(this).val('');
			error=1;
		}
		else if(file.type != 'image/png' && file.type != 'image/jpg' && !file.type != 'image/gif' && file.type != 'image/jpeg' ) 
		{
			alert("File doesnt match png, jpg or gif");
			$(this).val('');
			error=1;
		}
		else
		{
			var img = new Image();
			var _URL = window.URL || window.webkitURL;
			img.src = _URL.createObjectURL(file);
			img.onload = function () 
			{
				if(this.height <120|| this.width<120)
				{
					error=1;
					alert("Please upload Images with atleast 120 x 120 resolution");
					$(this).val('');
					return false;
				}
			};
        }
    });
});
$.fn.clearFields = $.fn.clearInputs = function(includeHidden) {
    var re = /^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i; // 'hidden' is not in this list
    return this.each(function() {
        var t = this.type, tag = this.tagName.toLowerCase();
        if (re.test(t) || tag == 'textarea') {
            this.value = '';
        }
        else if (t == 'checkbox' || t == 'radio') {
            this.checked = false;
        }
        else if (tag == 'select') {
            this.selectedIndex = -1;
        }
        else if (t == "file") {
            if (/MSIE/.test(navigator.userAgent)) {
                $(this).replaceWith($(this).clone(true));
            } else {
                $(this).val('');
            }
        }
        else if (includeHidden) {
            // includeHidden can be the value true, or it can be a selector string
            // indicating a special test; for example:
            //  $('#myForm').clearForm('.special:hidden')
            // the above would clean hidden inputs that have the class of 'special'
            if ( (includeHidden === true && /hidden/.test(t)) ||
                 (typeof includeHidden == 'string' && $(this).is(includeHidden)) )
                this.value = '';
        }
    });
};
</script> 
<script async src="<?php echo ROOT_URL; ?>/js/jquery.mCustomScrollbar.concat.min.js"></script>
<?php// echo 'Total execution time in seconds: ' . (microtime(true) - $time_start);?>
<script type="text/javascript">
// set current position
var cPosition = false;

$('.modal').on('show.bs.modal', function(){

    cPosition = $(window).scrollTop();
})
.on('shown.bs.modal', function(){

    $('body').css({
        position:'fixed'
    });

})
.on('hide.bs.modal', function(){

    $('body').css({
        position:'relative'
    });

    window.scrollTo(0, cPosition);

});    
function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) 
	{
		limitField.value = limitField.value.substring(0, limitNum);
	} 
	else
	{
		limitCount.value = limitNum - limitField.value.length;
	}
}

function func(id)
{
	var namesPattern = /^[a-zA-Z ]*$/;
	var name = $('#'+id).val();
	if(!namesPattern.test(name))
	{
		$('#'+id).css('border-color','red')
		$('#'+id).val('');
	}
	else
	{
		$('#'+id).css('border-color','#eee')
	}
}

function url(url)
{
	if(!isValidURL($("#"+url).val()))
	{
		$('#'+url).css('border-color','red')
		$('#'+url).val('');
	}
	else
	{
		$('#'+url).css('border-color','#eee')
	}
}
 
function isValidURL(url)
{
		var RegExp = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
		if(RegExp.test(url))
			return true;
		else
			return false;
}

function emailVal(email)
{
	if(!isValidEmailAddress(email))
	{
		$('#'+email).css('border','red');
		$('#'+email).val();
		return false;
	}
	else
	{
		$('#'+email).css('border','');
	}
}
 
	function getMobileOperatingSystem() {
  var userAgent = navigator.userAgent || navigator.vendor || window.opera;

  if( userAgent.match( /iPad/i ) || userAgent.match( /iPhone/i ) || userAgent.match( /iPod/i ) )
  {
    return 'iOS';

  }
  else if( userAgent.match( /Android/i ) )
  {

    return 'Android';
  }
  else
  {
    return 'unknown';
  }
}
 
 
$('.modal').on('show.bs.modal', function () {
  
  $(".mCustomScrollbar").mCustomScrollbar({
                scrollInertia: 0,
                live: true
            });
 
});
 
   
  </script>
<style>
 .mapsidefx_toggle.btn.btn-sm.btn-primary {
    background: none !important;
}	 
.rating i{
	cursor:pointer;
}
.rating-text{
	 margin-left: 10px;
}

@media (min-width: 768px) and (max-width: 991px) {
.custom-container {
	width:500px;	
	margin:0px auto;
}
	
}

@media (min-width: 992px) and (max-width: 1199px) {
.custom-container {
	width:650px;	
	margin:0px auto;
}	
}


</style>


<?php 
//echo "<script>var default_stores=".json_encode($store).";</script>";
//	 echo "<script>var return_popup_stores=".json_encode($return_popup_stores).";</script>";

if(isset($_GET['store'])) {// echo "rajesh";
$_GET['store']= str_replace("_"," ",$_GET['store']);
   $searchqry="SELECT  `id`, `latitude`, `longitude`,is_licensed FROM `stores` WHERE LOWER(`name`) = LOWER('".$_GET['store']."') and LOWER(`zipcode`)='".$_GET['zip']."'";
$searchresult=mysql_query($searchqry);
	$store_array=array();
while($row_store=mysql_fetch_assoc($searchresult)){
	$store_id=$row_store['id'];
	$is_licensed=$row_store['is_licensed'];
	//$store_array=$row_store;
} 
?><script type='text/javascript'>$(document).ready(function(){
		
		$("#is_licensed").val('<?=$is_licensed;?>');
		search_by_shortlink(<?php echo $store_id;?>,lat,lng);})</script><?php }?>
 <?php   if(isset($_SESSION["StoreID"]) && !empty($_SESSION["StoreID"])){ ?>
 
 <script type='text/javascript'>
	 
	 
	 
	 $(document).ready(function(){
	function executeQuery() {
		$.ajax({
    		url: '<?=ROOT_URL?>ticker.php',
    		success: function(data) {
				if(data){
				  var rows = $(data);
				  rows.hide();
				   $('tr:first-child').before(rows);
				  	rows.fadeIn("slow");
    			}
			}
		});
	}
  		setTimeout(executeQuery, 5000); 
	 });
	 $(".rl-imwps").click(function(){ 		 
		$("#thumbnil").attr("src",$(this).find("img").attr("data-img"));
		$("#product_title").html($(this).find("img").attr("data-productname"));
         $("#product_description").html($(this).find("img").attr("data-productDescription"));
		  $("#cbd_per").html($(this).find("img").attr("data-cbd"));
		  $("#thc_per").html($(this).find("img").attr("data-thc"));
		  $("#thca_per").html($(this).find("img").attr("data-thca"));
		 
		 $('#ticker_popup').modal('show');
    });
	 
	  
	  $(".ach_process").click(function(){
	  	  $(".bitcoin_div").hide(); 
		  $(".credit_card_div").hide();
		  $(".payment_method").hide();
		  $(".ach_div").show();
		  $(".paypal_div").hide();
	  });
	  $(".paypal_process").click(function(){ 
		   if($("#temp_qty").val() == "" || $("#temp_qty").val() == "0"){
			 alert("please enter Quantity");
			 return false;
		 }
		
		 $("#paypal_qty").val($("#temp_qty").val());
		 $("#paypal_fee").val($("#temp_fee").html());	
		 $("#paypal_total_price").val($("#temp_total_price").html());		 
		 $("#payment_title").html('Paypal Process');
		 $("#paypal_payment_type").val('paypal');
		  $("#paypal_productname").val( $("#temp_product_name").html());
		 $("#paypal_owner_id").val($("#owner_id").val());
		 $("#paypal_product_id").val($("#product_id").val());
		 $("#paypal_category_id").val($("#category_id").val());
		 $("#paypal_store_id").val($("#store_id").val()); 
		 $(".bitcoin_div").hide(); 
		 $(".credit_card_div").hide();
		 $(".payment_method").hide();
		 $(".ach_div").hide();
		 $(".paypal_div").show();
	  });
	  $(".bitcoin_process").click(function(){
	      $(".bitcoin_div").show(); 
		  $(".credit_card_div").hide();
		  $(".payment_method").hide();
		  $(".ach_div").hide();
		  $(".paypal_div").hide();
		
	   
	  });
	 $(".cc_process").click(function(){ 
		 if($("#temp_qty").val() == "" || $("#temp_qty").val() == "0"){
			 alert("please enter Quantity");
			 return false;
		 }
		 $("#qty").val($("#temp_qty").val());
		  $("#fee").val($("#temp_fee").html());	
		 $("#total_price").val($("#temp_total_price").html());		 
		 $("#payment_title").html('Credit Card Process')
		 $("#payment_type").val('cc');
		 $(".payment_method").hide();
		 $(".credit_card_div").show();
		 $(".modal-footer").show();
		  $(".paypal_div").hide();
	 });
 $("#temp_qty").bind('keyup change', function() {
	 var fee=<?=$settings['fee'];?>;
	  $("#temp_total_fee").html($(this).val()*fee*$("#weight").val()); 
	 if($(this).val() <10){	
		 var totalprice=($(this).val()*fee*$("#weight").val())+ ($("#temp_product_price").html()*$(this).val());
	 }
	 else if($(this).val()>=10 && $(this).val()<100)  {
		 var totalprice=($(this).val()*fee*$("#weight").val())+ ($("#temp_product_ten_price").html()*$(this).val());	 
	 }
	 else if($(this).val()>=100){
		  var totalprice=($(this).val()*fee*$("#weight").val())+ ($("#temp_product_hundred_price").html()*$(this).val());
	 }
	$("#temp_total_price").html(totalprice); 
 })
	 $(".modaldonatenow").click(function(){ 
		
		 $("#weight").val($(this).data("weight"));
		 //$("#temp_qty").val('1');
		/* $("#temp_total_fee").html(25*$("#weight").val()); 
		 $("#temp_product_price").html($(this).data("total_price"));
		 var totalprice=(25*$("#weight").val())+($("#temp_product_price").html());
		$("#temp_total_price").html(totalprice);*/
		 $('#temp_qty').val('');
		  $("#temp_total_fee").html(0);
		 $("#temp_product_price").html($(this).data("total_price"));
		 var totalprice=0;
		 $("#temp_total_price").html(totalprice);
		 
		 
		 
		 $("#payment_title").html('Select Payment Method')
		 $("#payment_type").val('cc');
		 $(".payment_method").show();
		 $(".credit_card_div").hide();
		 $(".modal-footer").hide();
		 $("#temp_store_name").html($(this).data("store_name"));
		 $("#temp_product_name").html($(this).data("product_name"));
		 $("#temp_product_image").attr('src',$(this).data("product_image"));
		  
		 
		 $("#temp_product_ten_price").html($(this).data("product_ten_price"));
		 $("#temp_product_hundred_price").html($(this).data("product_hundered_price"));
		 $("#temp_logo").attr('src',$(this).data("logo")); 
		 
		 $("#total_price").html($(this).data("total_price"))
         $("#owner_id").val($(this).data("owner_id"));
         $("#category_id").val($(this).data("category_id"));
         $("#product_id").val($(this).data("product_id"));
         $("#store_id").val($(this).data("store_id"));
		 
		 $("#temp_address").html($(this).data("store_address"));
		 $("#temp_phone").html($(this).data("store_phone"));
		 $("#temp_email").html($(this).data("store_email"));
		 //$("#temp_storedescription").html($(this).data("product_productDescription"));
		 $("#temp_website").html($(this).data("store_website"));setTimeout(function (){
		  $('#temp_qty').focus();
    }, 1000); 
		 $('#responsive').modal('show');
		 
    });
	 
	 
	 
	  $("button.donatenow").on('click', function(){
	  var dataString = $("form#checkCreditcardDetails").serialize(); 
			$.ajax({
				type: "POST",
				url: '<?=ROOT_URL?>ticker_payment.php',
				data: dataString,
				success: function (data) {
					var obj = jQuery.parseJSON( data );
					if(obj.success == true)
					{
						//var deliveryType = $('input:radio[name=deliveryType]:checked').val();
						 location.href = '<?=ROOT_URL?>/ticker_thankyou.php';
						 //$('#ticker_popup').modal('hide');
					}
					else
					{
						$("#error-message").html(obj.message);
						$(".main-creditdetails").show();
						$(".main-loader").hide();
					}
				}
			}); 
	  });
	</script>
	<style type="text/css">
.mt20 {
	margin-top: 8px;
}
#ticker_popup{
	background: none!important;
}
.bt10{
	margin-bottom: 10px;
}
.btn-payment {
	margin-right: 20px;
}
.modal-dialog {
	max-width: 800px;
	width: 100% ;
}
.modal-dialog1 {
	max-width: 500px;
	width: 100% ;
}		
		
.pre-cost {
	text-decoration: line - through;
	color: #a5a5a5;}
		.space-ten {
			padding: 10px 0;
		}
.control-label {
	border-bottom: solid 1 px #F47A15;
	min-height: 36px;
	padding-top: 10px;
	background: #F47A15;
	color: #ffffff;
	width: 35%;
	text-align: right;

}
.form-value {
	border: solid 1px #eee;
	min-height: 36px;
	padding-top: 10px;
	width: 65%;
	
}
.bot-border {
	margin-bottom: 2px;
}
	</style>
 <?php } else{?>
 
 <script type="text/javascript">if(getMobileOperatingSystem()== "iOS" || getMobileOperatingSystem() == "Android"){
$(document).on("click", '.feature_store', function(){
	  $(".mapsidefx_cntwrps").toggle();
        $(".mapsidefx_wrps").toggleClass('mapside_open');
        $(".mapsidefx_toggle i").toggleClass('fa-rotate-180');
}); 
	}</script><?php } ?>
	
</body>
</html>