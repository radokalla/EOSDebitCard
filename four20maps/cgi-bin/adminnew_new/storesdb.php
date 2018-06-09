<?php 
include_once './includes/config.inc.php';
$db = db_connect();
if($_POST)
{
	if(($_POST['type'])==1)
	{
		$Subscription = $_POST['Subscription'];
		$Description = $_POST['Description'];
		$data['Years'] = $_POST['years'];
		$data['Months'] = $_POST['months'];
		$data['Days'] = $_POST['days'];
		$Duration = implode("/",$data);
		$IntialAmount = $_POST['IntialAmount'];
		$RenualAmount = $_POST['RenualAmount'];
		$Status = $_POST['Status'];
			$result = mysql_query("Insert into SubscriptionTypes (Subscription, Description, Duration, InitialAmount, RenualAmount, Status) 
									VALUES ('$Subscription','$Description','$Duration','$IntialAmount','$RenualAmount','$Status')") or die(mysql_error());
				if($result==1)
				{
					echo "Success";
				}
				else
				{
					echo "Un-Sucess";
				}
	}
	if($_REQUEST['type']==2)
	{
		$id = $_REQUEST['id'];
		$result = mysql_query("select * from SubscriptionTypes where SubscriptionTypeId='$id'");
		$data = mysql_fetch_array($result);
		echo json_encode($data);
	}
	
	if($_POST['type']==3)
	{
		$Subscription = $_POST['Subscription'];
		$Description = $_POST['Description'];
		$Duration = $_POST['Duration'];
		$IntialAmount = $_POST['IntialAmount'];
		$RenualAmount = $_POST['RenualAmount'];
		$Status = $_POST['Status'];
		$SubId = $_POST['SubId'];
			$return = mysql_query("UPDATE SubscriptionTypes  SET Subscription='$Subscription',
																	Description = '$Description',
																	Duration = '$Duration',
																	InitialAmount = '$IntialAmount',
																	RenualAmount = '$RenualAmount',
																	Status = '$Status' where SubscriptionTypeId = '$SubId' ") or die(mysql_error());
						echo $return;
	}
	
	if($_REQUEST['type']==4)
	{
		$SubscriptionTypeId = $_REQUEST['subId'];
			mysql_query("DELETE from SubscriptionTypes where SubscriptionTypeId = '$SubscriptionTypeId' ") or die(mysql_error());
	}
	
	if($_REQUEST['type']==5)
	{
		$uid = $_REQUEST['uid'];
			$result = mysql_query("select * from StoreUsers where UserId = '$uid' ") or die(mysql_error());
			$data = mysql_fetch_array($result);
			echo json_encode($data);
	}
	
	if($_POST['type']==6)
	{
		$uid = $_POST['UserId'];
		$Firstname = $_POST['FirstName'];
		$Lastname = $_POST['LastName'];
		$Mobile = $_POST['Mobile'];
		$Status = $_POST['Status'];
		$Address = $_POST['Address'];
			$return = mysql_query("UPDATE StoreUsers  SET Firstname='$Firstname',
																	Mobile = '$Mobile',
																	Lastname = '$Lastname',
																	Status = '$Status',
																	Address = '$Address',
																	Status = '$Status' where UserId = '$uid' ") or die(mysql_error());
						echo $return;	
	}
}
?>