<?php 
include_once './includes/config.inc.php';
include_once './includes/validate.php';
$db = db_connect();
if($_POST)
{
	if(($_POST['type'])==1)
	{
			$SubscriptionCategoryId = $_POST['SubscriptionCategoryId'];
			$Subscription = $_POST['Subscription'];
			$Description = addslashes($_POST['Description']);
			$duration = $_POST['Duration'];
			$duration_type = " ".$_POST['duration_type'];
			$totalduration = $duration.$duration_type;
			$IntialAmount = $_POST['IntialAmount'];
			$RenualAmount = $_POST['RenualAmount'];
			$stores = $_POST['stores'];
			$Status = $_POST['Status'];
			/*echo "<pre>";
			print_r($_POST);die;*/
			
			$target_file1 = '';
			$target_file2 = '';
			$target_file3 = '';
			
			if($_FILES["Icon1"]!='')
			{
				$f1 = time().$_FILES["Icon1"]["name"];
				$target_dir1 = "subscription_icons/";
				$target_file1 = $target_dir1.$f1;
				move_uploaded_file($_FILES["Icon1"]["tmp_name"], $target_file1);
				 
				$f2 = time().$_FILES["Icon2"]["name"];
				$target_dir2 = "subscription_icons/";
				$target_file2 = $target_dir2.$f2;
				move_uploaded_file($_FILES["Icon2"]["tmp_name"], $target_file2) ;
				
				$f3 = time().$_FILES["Icon3"]["name"];
				$target_dir3 = "subscription_icons/";
				$target_file3 = $target_dir3.$f3;
				move_uploaded_file($_FILES["Icon3"]["tmp_name"], $target_file3);
				 
				
				$result = mysql_query("Insert into SubscriptionTypes (Subscription,SubscriptionCategoryId, Description, Duration, InitialAmount, 						RenualAmount, Stores_Count, Icon1, Icon2, Icon3, Status) 
										VALUES ('$Subscription','$SubscriptionCategoryId','$Description','$totalduration','$IntialAmount','$RenualAmount','$stores','$target_file1','$target_file2','$target_file3','$Status')") or die(mysql_error());
				$last_id = mysql_insert_id();
				$result = mysql_query("UPDATE SubscriptionTypes SET OrderId = '$last_id' where SubscriptionTypeId = '$last_id' ");
				if($result==1)
					{
						$_SESSION['message'] = 'Subscription added successfully';
						echo "<script>window.location.href = 'subscription.php' </script>";
					}
					else
					{
						$_SESSION['message'] = 'Error Adding Subscription ! Please try later';
						echo "<script>window.location.href = 'addsubs.php' </script>";
					}
			}
			else
			{
				$_SESSION['message'] = 'Please upload files..';
				echo "<script>window.location.href = 'addsubs.php' </script>";
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
		
		if(isset($_FILES["Icon1"]["name"]) && !empty($_FILES["Icon1"]["name"])){
			$img  = new Image(array('filename'=>$_FILES['Icon1']['tmp_name']));

		if($img !== FALSE && $img->resize_to_width("64")) {             
			$f1 = time().$_FILES["Icon1"]["name"];
			$target_dir1 = "subscription_icons/";
			$target_file1 = $target_dir1.$f1;
			$img->save($target_file1);
		}
		}
		else
		{
			$target_file1=$_POST["temp_Icon1"];
		}
        if(isset($_FILES["Icon2"]["name"]) && !empty($_FILES["Icon2"]["name"])){
		$f2 = time().$_FILES["Icon2"]["name"];
		$target_dir2 = "subscription_icons/";
		$target_file2 = $target_dir2.$f2;
		move_uploaded_file($_FILES["Icon2"]["tmp_name"], $target_file2) ;
		}
		else
		{
			$target_file2=$_POST["temp_Icon2"];
		}
        if(isset($_FILES["Icon3"]["name"]) && !empty($_FILES["Icon3"]["name"])){
		$f3 = time().$_FILES["Icon3"]["name"];
		$target_dir3 = "subscription_icons/";
		$target_file3 = $target_dir3.$f3;
		move_uploaded_file($_FILES["Icon3"]["tmp_name"], $target_file3);
		}
		else
		{
			$target_file3=$_POST["temp_Icon3"];
		}
		
		
		
		$SubscriptionCategoryId = $_POST['SubscriptionCategoryId'];
		$Subscription = $_POST['Subscription'];
		$Description = $_POST['Description'];
		$Durat = $_POST['Duration'];
		$IntialAmount = $_POST['IntialAmount'];
		$RenualAmount = $_POST['RenualAmount'];
		$Status = $_POST['Status'];
		$SubId = $_POST['SubId'];
		$DurationType = $_POST['durationType'];
		$Duration = $Durat." ".$DurationType;
		 
			$return = mysql_query("UPDATE SubscriptionTypes  SET SubscriptionCategoryId = '$SubscriptionCategoryId',Subscription='$Subscription',
																	Description = '$Description',
																	Duration = '$Duration',
																	InitialAmount = '$IntialAmount',
																	RenualAmount = '$RenualAmount',
																	Icon1= '$target_file1', Icon2= '$target_file2',Icon3= '$target_file3',
																	Status = '$Status' where SubscriptionTypeId = '$SubId' ") or die(mysql_error());
		
		$_SESSION['message'] = 'Subscription updated successfully';
		header("location:".ROOT_URL."subscription.php");
	 exit;
		//echo $return;
			/*$order = $_POST['order'];
			if($order!='abcd')
			{
				$ordr = $_POST['orid'];
				$det = mysql_query("select MAX(OrderId) from SubscriptionTypes ");
				$var = mysql_fetch_array($det);
				for($i=$ordr; $i<=$var[0]; $i++)
				{
					$ids[] = $i;
				}
				foreach($ids as $row)
					$d[] = implode(',',$ids);
				//print_r($d[0]);die;
				mysql_query("UPDATE SubscriptionTypes SET OrderId = OrderId +1 where SubscriptionTypeId IN($d[0]) and SubscriptionTypeId!='$ordr' and OrderId < $ordr ")or die(mysql_error());
			}*/
	}
	
	if($_REQUEST['type']==4)
	{
		$SubscriptionTypeId = $_REQUEST['subId'];
			mysql_query("Update SubscriptionTypes SET IsDeleted='1' where SubscriptionTypeId = '$SubscriptionTypeId' ") or die(mysql_error());
		echo "1";
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
		updatejson();
						echo $return;	
	}
	
	if($_POST['type']==7)
	{
		$transID = $_POST['trans'];
		$return = mysql_query("UPDATE Transactions SET Status = '1' where Sno = '$transID'")or die(mysql_error());
		if($return==1)
		{
			echo $return;
		}
	}
	
	if($_POST['type']==8)
	{
		$id = $_POST['id'];
		$sql = mysql_query("select * from ProductCategory where categoryID = '$id' and parentID='0'");
		$data = mysql_fetch_array($sql);
		echo json_encode($data);
		
	}
	
	if($_POST['type']==9)
	{
		$id = $_POST['id'];
		$sql = mysql_query("SELECT p.*,p1.categoryName as ParentCatName FROM `ProductCategory` p
		left join ProductCategory p1 on p.parentID= p1.categoryID
		WHERE p.parentId!=0 and p.categoryID='$id' ");
		$data = mysql_fetch_array($sql);
		$UserId = $_POST['UserId'];
		$cat = mysql_query("select categoryID,categoryName from ProductCategory where UserId = '$UserId' and parentID='0' and isDeleted='0' and isActive='1' ");
		while($ucats = mysql_fetch_array($cat))
			$cats[] = $ucats;
		echo json_encode(array('data'=>$data,'cats'=>$cats));
		
	}
	
	if($_POST['type']==10)
	{
		$id = $_POST['current_cid'];
		if(!empty($id))
		{
			$name = $_POST['catedit_name'];
			$heading = addslashes($_POST['Category_heading']);
			$description = addslashes($_POST['Category_description']);
			$status = $_POST['cat_Status'];
			$return = mysql_query("update ProductCategory SET categoryName='$name', categoryDescriptionHeading = '$heading', categoryDescription = '$description', isActive = '$status' where categoryID = '$id' ");
			if($return == 1)
				echo "1";
			else
				echo "2";
		}
		else
			echo "3";
		
	}
	
	if($_POST['type']==11)
	{
		$id = $_POST['current_pid'];
		if(!empty($id))
		{
			$pname = $_POST['product_name'];
			$ctype = $_POST['categorytype'];
			$pstat = $_POST['pro_Status'];
			$cname = $_POST['category_name'];
			$return = mysql_query("update ProductCategory SET categoryName='$pname', parentID = '$cname', categoryType = '$ctype', isActive = '$pstat' where categoryID = '$id' ");
			if($return == 1)
				echo "1";
			else
				echo "2";
		}
		else
			echo "3";
	}
	
	if($_POST['type']==12)
	{
		$cat_id = $_POST['catid'];
		$return = mysql_query("delete from ProductCategory where categoryID = '$cat_id' and parentID!='0' ");
		if($return == 1)
			echo "1";
		else
			echo "2";
	}
	
	if($_POST['type']==13)
	{
		$cat_id = $_POST['catid'];
		$return = mysql_query("delete from ProductCategory where categoryID = '$cat_id' and parentID ='0' ");
		$prodel = mysql_query("delete from ProductCategory where ParentID = '$cat_id' ");
		if(($return == 1) && ($prodel==1))
			echo "1";
		else
			echo "2";
	}
	
	if($_POST['type']==14)
	{
		$uid = $_POST['uid'];
		if($uid!='')
		{
			$stores = mysql_query("UPDATE stores SET status='0' where createdby='$uid'");
			$catpro = mysql_query("DELETE from  ProductCategory  where UserId='$uid'");
			$user = mysql_query("DELETE from StoreUsers where UserId='$uid'");
			$user = mysql_query("DELETE from StoreUserSubscription where UserId='$uid'");
			//if(($stores=='1') && ($catpro=='1') && ($user=='1'))
			if($user=='1')
				echo "1";
			else
				echo "2";
		}
	}
}
?>