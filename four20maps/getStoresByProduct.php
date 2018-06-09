<?php
include_once './includes/config.inc.php';
if($_REQUEST["q"])
	$query = $_REQUEST["q"];
if($_REQUEST["keyword"])
	$query = $_REQUEST["keyword"];
	
  $res = mysql_query("call mp_proc_SearchStorebyKeyword('products','".$query."');") or die(mysql_error());
    if ($res === false) {
        echo mysql_errno().': '.mysql_error();
    }
	if(mysql_num_rows($res) > 0)
	{
		$answer = array();		
		while ($row = mysql_fetch_array($res)) 
		{
			//var_dump($row);die;
			$cat_img ="";
			if($row['store_cat_id']){
			// cat img
			$cat_upload_dir = '../admin/imgs/categories/'.$row['store_cat_id'].'/';
			$cat_files = get_files($cat_upload_dir);
			//var_dump($cat_files); die; 
			if(is_array($cat_files)) 
				$cat_files = array_values($cat_files);

			if($cat_files !== FALSE && isset($cat_files[0])) 
				$cat_img = 'imgs/categories/'.$row['store_cat_id'].'/'.$cat_files[0];
			}
			
			$upload_dir = 'imgs/stores/'.$row["id"].'/';
			$files = get_files($upload_dir);
			
			if(is_array($files)) 
			$files = array_values($files);
			$img = '';
			if( isset($files[0])) 
			$img = '../admin/imgs/stores/'.$row["id"].'/'.$files[0];
			if($row['image']=='')
				$image = ROOT_URL.'admin/'.$row["cat_icon"];
			else
				$image = ROOT_URL.'admin/imgs/caticons/1440221344Delivery%20Truck%20-%20160%20-%20PREMIER%20-%20SMALL.png';
			if($row['cat_icon'] == '')
				$caticon = ROOT_URL.'admin/imgs/caticons/1440221344Delivery%20Truck%20-%20160%20-%20PREMIER%20-%20SMALL.png';
			else
				$caticon = ROOT_URL.'admin/'.$row['cat_icon'];
			
			$answer[]=array(
			"name"=>$row["NAME"],
			"address"=>$row["address"],
			'id'=>$row["id"],
			'lat'=> $row["latitude"], 
			'lng' => $row["longitude"],
			'latitude'=> $row["latitude"], 
			'longitude' => $row["longitude"],
			'telephone' => $row["telephone"],
			'fax' => $row["fax"],
			'mobile' => $row["mobile"],
			'email' => $row["email"],
			'website' => $row["website"],
			'description' => $row["description"],
			'img' => $img,
			'image' =>  $row['image'],
			'categoryname' => $row["categoryname"],
			'cat_name' => $row["store_cat_name"],
			//'cat_icon' => "admin/".$row["cat_icon"],
			'cat_icon' => $caticon,
			'cat_img' => $cat_img,
			'ctype_icon' =>$row['ctype_icon'],
			'OrderId' => $row['OrderId']
			);
		}
		echo json_encode($answer);
	}

exit;