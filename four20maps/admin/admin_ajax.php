<?php
include_once './includes/config.inc.php';
$db = db_connect();
//$res = mysql_query("call mp_proc_SearchStorebyKeyword('".$_REQUEST["type"]."', '".$_REQUEST["q"]."');") or die(mysql_error());
//$_REQUEST["type"]='Stores';
  $res = mysql_query("SELECT `id`,`name` FROM `stores`  WHERE `StoreUserSubscriptionId` IS NULL and Lower(name) like  '".strtolower($_REQUEST["q"])."%' limit 0,10") or die(mysql_error());
    if ($res === false) {
        echo mysql_errno().': '.mysql_error();
    }
	if(mysql_num_rows($res) > 0)
	{
		 
		while ($row = mysql_fetch_array($res)) 
		{
			 
					$answer[]=array(
					"id"=>$row["id"],
					"label"=>$row["name"],
					"value" =>$row["name"]
					);
	    }
				
	 
	}
	else
	{
		$answer[0]=array("text"=>"No Result found","id"=>$row["address"]);
	}
	
echo json_encode($answer);
?>