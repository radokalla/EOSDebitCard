<?php
/* This is where you would inject your sql into the database 
   but we're just going to format it and send it back
*/

foreach ($_GET['listItem'] as $position => $item) :
	$sql[] = "UPDATE `SubscriptionTypes` SET `OrderId` = $position WHERE `SubscriptionTypeId` = $item";
endforeach;

echo json_encode($sql);

?>