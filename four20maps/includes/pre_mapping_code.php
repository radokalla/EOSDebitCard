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
	
	//$sql = "SELECT *, ( 3959 * ACOS( COS( RADIANS(".$_POST['lat'].") ) * COS( RADIANS( latitude ) ) * COS( RADIANS( longitude ) - RADIANS(".$_POST['lng'].") ) + SIN( RADIANS(".$_POST['lat'].") ) * SIN( RADIANS( latitude ) ) ) ) AS distance FROM stores WHERE status=1 AND approved=1 ".$category_filter." HAVING distance <= ".$_POST['distance']." ORDER BY distance ASC LIMIT 0,60";
	/*$sql = "SELECT *,latitude, longitude, SQRT( POW( 69.1 * ( latitude - ".$_POST['lat']." ) , 2 ) + POW( 69.1 * ( ".$_POST['longi']." - longitude ) * COS( latitude / 57.3 ) , 2 ) ) AS distance
	FROM stores
	WHERE status=1 AND approved=1
	HAVING distance <50
	ORDER BY distance
	LIMIT 0 , 30";*/
	/*
	$sql = "SELECT c.cat_icon,s.*,s.latitude,s.DatabaseName, s.longitude, SQRT( POW( 69.1 * ( s.latitude -". $_POST['lat']." ) , 2 ) + POW( 69.1 * ( ".$_POST['longi']." - s.longitude ) * COS( s.latitude / 57.3 ) , 2 ) ) AS distance
	FROM stores s
	LEFT JOIN `categories` c ON c.id = s.cat_id
	WHERE s.status=1 AND s.approved=1
	HAVING distance < 1
	ORDER BY distance
	LIMIT 0 , 30";*/
	$sql = "SELECT c.cat_icon,s.*,s.latitude,s.DatabaseName, s.longitude 
	FROM stores s
	LEFT JOIN `categories` c ON c.id = s.cat_id
	WHERE s.status=1 AND s.approved=1
	and s.id=".$_POST['id']."
	";
	
	echo json_stores_list($sql);
	exit;
}
if(isset($_POST['getStoreRating']) && $_POST['getStoreRating']=='1'){
	$storeid = $_REQUEST['storeid'];
	$records = mysql_query("SELECT u.id as 	userid, IFNULL(u.username,'') AS username,r.store_rating, r.store_review FROM reviews r
    Inner JOIN users u ON u.id= r.customer_id
	 WHERE r.store_id=$storeid  AND r.approved_on!='0000-00-00' order by u.username DESC");
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
if(isset($_POST['setStoreRating']) && $_POST['setStoreRating']=='1'){
	$storeid = $_REQUEST['storeid'];
	$rating = $_REQUEST['rating'];
	$uid = $_REQUEST['uid'];
	$comments = $_REQUEST['comments'];
	$date= date('Y-m-d');
	if($_REQUEST['uid'])
	{
		$x = mysql_query("select count(*) as count from reviews where store_id=$storeid and  customer_id=$uid");
		$xx = mysql_fetch_array($x);
		if($xx['count']=='0')
		$sql= "INSERT INTO reviews (store_id,customer_id, store_rating,store_review,added_on, approved_on) VALUES($storeid, $uid, $rating,'$comments','$date','$date') ";
		else
			$sql= "UPDATE reviews set store_id=$storeid, store_rating=$rating,store_review='$comments' where customer_id=$uid";
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
	 $sql ="SELECT u.id as 	userid, IFNULL(u.username,'') AS username,r.store_rating, r.store_review FROM reviews r
    Inner JOIN users u ON u.id= r.customer_id
	 WHERE r.store_id=$storeid  AND r.approved_on!='0000-00-00' order by u.username DESC";
	$x = mysql_query($sql);
	while($row = mysql_fetch_array($x))
		$y[] = $row;
	if(count($y)>0)
	echo json_encode($y);
	exit;
}
	
$addimages="select aid,image,url,start_date,end_date from adds where status='Active' AND now() >= start_date and now() <= end_date and is_delete=0 order by last_modified DESC LIMIT 5";
$qrryexc=mysql_query($addimages);
while($num_rows=mysql_fetch_assoc($qrryexc))
{
	$images[]=$num_rows;
}
	#var_dump($images);die;
	if(intval(GEO_SETTINGS)>0)
	{
		/*$def_loc = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR']));
		$latitude = $def_loc['geoplugin_latitude'];
		$longitude = $def_loc['geoplugin_longitude'];*/
		$def_loc = explode(',',file_get_contents('http://www.ipaddressapi.com/l/5604d1ed63f42547daffd678ab3e6d5e51cdc1494a65?h='.$_SERVER['REMOTE_ADDR']));
		$latitude = $def_loc[8];
		$longitude = $def_loc[9];
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
		echo "<script>var lat = ".$latitude.";var lng = ".$longitude."</script>";
	}else
		echo "<script>var lat = 32.834337;var lng = -117.148851</script>";
	if(intval(INIT_ZOOM)>0)
	echo "<script>var inizoom=".INIT_ZOOM.";</script>";
	else
	echo "<script>var inizoom=15;</script>";
	
	$stores="SELECT s.*,c.cat_icon,c.cat_name FROM stores s
			LEFT JOIN categories c ON c.id = s.cat_id
			WHERE s.status=1 and approved=1";
	$stores=mysql_query($stores);
	while($row_store = mysql_fetch_array($stores))
	{
		#var_dump($row_store);die;
		$cat_img ="";
		if($row_store['cat_id']>0){
		// cat img
		$cat_upload_dir = ROOT.'admin/imgs/categories/'.$row_store['cat_id'].'/';
		$cat_files = get_files($cat_upload_dir);
		if(is_array($cat_files)) 
			$cat_files = array_values($cat_files);

		if($cat_files !== FALSE && isset($cat_files[0])) 
			$cat_img = ROOT_URL.'admin/imgs/categories/'.$row_store['cat_id'].'/'.$cat_files[0];
		}
		
		$upload_dir = ROOT.'admin/imgs/stores/'.$row_store["id"].'/';
		$files = get_files($upload_dir);
		if(is_array($files))
			$files = array_values($files);
		if($files !== FALSE && isset($files[0])) 
			$img = ROOT_URL.'admin/imgs/stores/'.$row_store['id'].'/'.$files[0];
		
		$store[] = array("id"=>$row_store["id"],"name"=>$row_store["name"],"address"=>$row_store["address"],"telephone"=>$row_store["telephone"],
		"fax"=>$row_store["fax"],"mobile"=>$row_store["mobile"],"email"=>$row_store["email"],"website"=>$row_store["website"],
		"description"=>$row_store["description"],"img"=>$img,/*$row_store["image"],*/"lat"=>$row_store["latitude"],
		"lng"=>$row_store["longitude"], "cat_id"=>$row_store["cat_id"], "cat_img"=>$cat_img ,"cat_icon"=>"admin/".$row_store["cat_icon"],
		"cat_name"=>$row_store["cat_name"]);
	}	
	 #var_dump($store); die;
	 echo "<script>var default_stores=".json_encode($store).";</script>";
	
	if(isset($_SESSION['userid']))
	{
		$qry_reg="select * from users where id=".$_SESSION['userid'];
		$qrex=mysql_query($qry_reg);
		$regfetch=mysql_fetch_assoc($qrex);
	}
	
	mysql_query("UPDATE adds SET STATUS='InActive' WHERE CURDATE() >=end_date");
	$menu_items = mysql_query("select * from store_menu where active=1");
	while($row = mysql_fetch_array($menu_items))
		$menu[]=$row["menu_name"];
		