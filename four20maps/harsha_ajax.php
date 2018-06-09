<?php
session_start();
include_once './includes/config.inc.php';
	$farm_pacakages="SELECT SubscriptionTypeId FROM SubscriptionTypes WHERE SubscriptionCategoryId= 4";
		
	$farm_pacakage_result=mysql_query($farm_pacakages);
	 
	$farm_subscription_type_array=array();
	while($farm_store = mysql_fetch_array($farm_pacakage_result))
	{
		$farm_subscription_type_array[]=$farm_store['SubscriptionTypeId'];
	}
  $res = mysql_query("call mp_proc_SearchStorebyKeyword('".$_REQUEST["type"]."', '".$_REQUEST["q"]."', '".$_REQUEST["is_licensed"]."');") or die(mysql_error());
 
    if ($res === false) {
        echo mysql_errno().': '.mysql_error();
    }
	if(mysql_num_rows($res) > 0)
	{
		$answer=array();
		$stores = array();
		$parray=array();
		$child = array();
	
		while ($row = mysql_fetch_array($res)) 
		{
			if(in_array($row["id"], $child))
			continue;
			else
			$child[]=$row["id"];
			$stores[$row["id"]] = array("latitude"=>$row["latitude"],"longitude"=>$row["longitude"]);
			if($_REQUEST["type"]=='Stores')
			{
				     if(empty($_SESSION["regSuccess"])){
						if(!in_array($row["SubscriptionTypeId"],$farm_subscription_type_array)){
							$answer[]=array(
							"name"=>$row["NAME"],
							"label"=>$row["NAME"]." ".$row["address"],
							"value"=>$row["NAME"],
							"address"=>$row["address"],
							'id'=>$row["id"],
							'lat'=> $row["latitude"], 
							'lng' => $row["longitude"],
							'telephone' => $row["telephone"],
							'fax' => $row["fax"],
							'mobile' => $row["mobile"],
							'email' => $row["email"],
							'website' => $row["website"],
							'description' => $row["description"],
							"timings"=>json_decode($row_store["timings"]),
							"first_time_patients"=>$row_store["first_time_patients"],
							"announcement"=>$row_store["announcement"],
							"about_us"=>$row_store["about_us"],
							"created" => date('F dS, Y', strtotime($row_store["created"])),	
							'image' => $row["image"],
							'categoryname' => $row["categoryname"],
							'cat_icon' => "admin/".$row["image"],
							'OrderId' => $row["OrderId"],
							'is_licensed' => $row["is_licensed"]
							);
						}
				   }
				else
				{
					$answer[]=array(
					"name"=>$row["NAME"],
					"label"=>$row["NAME"]." ".$row["address"],
					"value"=>$row["NAME"],
					"address"=>$row["address"],
					'id'=>$row["id"],
					'lat'=> $row["latitude"], 
					'lng' => $row["longitude"],
					'telephone' => $row["telephone"],
					'fax' => $row["fax"],
					'mobile' => $row["mobile"],
					'email' => $row["email"],
					'website' => $row["website"],
					'description' => $row["description"],
						"timings"=>json_decode($row_store["timings"]),
					"first_time_patients"=>$row_store["first_time_patients"],
					"announcement"=>$row_store["announcement"],
					"about_us"=>$row_store["about_us"],
					"created" => date('F dS, Y', strtotime($row_store["created"])),	
					'image' => $row["image"],
					'categoryname' => $row["categoryname"],
					'cat_icon' => "admin/".$row["image"],
					'OrderId' => $row["OrderId"],
					 'is_licensed' => $row["is_licensed"] );
				}

			}
			else
			{		
				if($row["categoryname"]!='')
				{
					if(in_array($row["categoryname"], $parray))
					continue;
					else
					$parray[]=$row["categoryname"];
					$answer[]=array(
					"id"=>$row["id"],
					"label"=>$row["categoryname"],
					"value" =>$row["categoryname"]
					);
				}
				
			}
		}
	}
	else
	{
		$answer[0]=array("text"=>"No Result found","id"=>$row["address"]);
	}
	
echo json_encode($answer);
?>