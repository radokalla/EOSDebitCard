<?php
ob_start();
if(empty($_SESSION))
 {
	session_start();
 }
include_once './includes/config.inc.php';
include_once './includes/class.database.php';
include_once './includes/validate.php';
	$type = $_REQUEST['type'];
		if($type==1)
		{
			$email = $_REQUEST['email'];
			$table1 = mysql_query("select * from StoreUsers where Email='$email'") or die(mysql_error());
			$data1 = mysql_fetch_array($table1);
			if(empty($data1))
			{
				$table2 = mysql_query("select * from users where email='$email'")or die(mysql_error());
				$data2 = mysql_fetch_array($table2);
				if(empty($data2))
				{
					echo "1";
					die;
				}
				else
				{
					echo "Email Already Exsist's";
					die;
				}
			}
			else
			{
				echo "Email Already Exsist's";
				die;
			}
		}
		elseif($type==2)
		{
			$username = $_REQUEST['username'];
			$table1 = mysql_query("select * from StoreUsers where Username='$username'") or die(mysql_error());
			$data1 = mysql_fetch_array($table1);
			if(empty($data1))
			{
				$table2 = mysql_query("select * from StoreUsers where Username='$username'") or die(mysql_error());
				$data2 = mysql_fetch_array($table2);
				if(empty($data2))
				{
					echo "1";
					die;
				}
				else
				{
					echo "User Name already Exists";
					die;
				}
			}
			else
			{
				echo "User Name already Exists";
				die;
			}
		}
		else 
		{
			if($_POST)
			{
				if(!empty($_POST['SubscriptionCategoryId']))
				{
					$SubscriptionTypes = mysql_query("select * from SubscriptionTypes where SubscriptionCategoryId='".$_POST['SubscriptionCategoryId']."' and InitialAmount = '0' and Status='1'");
					$SubscriptionTypesdata = mysql_fetch_array($SubscriptionTypes);
					$subscription = $SubscriptionTypesdata['SubscriptionTypeId'];
					$username = $_POST['Username'];
					$fname  = $_POST['Firstname'];
					$lname  = $_POST['Lastname'];
					$email  = $_POST['Email'];
					$password  = md5($_POST['Password']);
					$mobile  = $_POST['Mobile'];
					$address  = $_POST['Address'];
					$status = '1';
					$date = date('Y-m-d H:i:s');
					$re = mysql_query("INSERT INTO StoreUsers (Username, Firstname, Lastname, Email, Password, Mobile, Address, SubscriptionTypeId,SubscriptionCategoryId, Status)
					VALUES ('$username', '$fname', '$lname', '$email', '$password', '$mobile', '$address', '$subscription','".$_POST['SubscriptionCategoryId']."', '$status')") or die(mysql_error());
					$uid = mysql_insert_id() ;
					mysql_query("INSERT INTO ProductCategory
							SELECT NULL,'',$uid,
							categoryOrder,categoryName,parentID,image,
							categoryDescriptionHeading,categoryDescription,NULL,isActive,1,0 FROM MasterCategory where isActive=1")or die(mysql_error());
					$re2 = mysql_query("Insert into StoreUserSubscription (UserId, SubscriptionId, CreatedDate)VALUES('$uid','$subscription','$date')")or die(mysql_error());
					$_SESSION["SubscriptionCategoryId"] = $_POST['SubscriptionCategoryId'];
					 if($re==1 || $re2==1)
					 {
						/* ------------------- Email to User starts here.. ------------------- */
						 	$to = $_POST['Email'];
						 $username = $_POST['Username'];
								$subject = "Four20maps Confirmation";
						$txt="<html><head><title>Four20maps Confirmation</title></head><body><table cellpadding='5' cellspacing='5'> <tbody> <tr><td align='center'><img src='".ROOT_URL."/img/logo.png'/></td> </tr><tr><td><b>Hello ".$_POST['Firstname']." ".$_POST['Lastname']."</b></td> </tr> <tr> <td>Thank you for registering with four20maps.com where you can find legal dispensaries with delivery services. Please send us any comments you may have and please don't forget to rate the the dispensaries in our network.</td></tr> <tr> <td>Please use this login User ID ".$username."</td> </tr><tr><td>Please <a href='".ROOT_URL."'>Click Here</a> to redirect.</td></tr><tr><td>Sincerely,</td></tr><tr><td>four20maps.com Team</td></tr></tbody></table></body></html>";
						// Always set content-type when sending HTML email
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                        $headers .= 'From: <support@four20maps.com>' . "\r\n";  
							mail($to,$subject,$txt,$headers); 
							
						/* ------------------- Email To admin about Registered User's Starts here.. ------------------- */
							$header= "MIME-Version: 1.0\r\n";
							$header.="Content-Type: text/html; charset=ISO-8859-1\r\n";
							$header.="From:".$_POST['Email'];
							$to = 'admin@four20maps.com';
							$subject = "Four20maps User Registration Alert";
							$txt="<table style='background-color:#eee'>";
							$txt.="<tr style='background-color:#7dcd1e'><td align='center'><img src='".ROOT_URL."/img/logo.png'/></td></tr>";
							$txt.="<tr><b>A new user has registered with <a href='".ROOT_URL."'>four20maps.com</a> </b></tr>";
							$txt.="<tr><b>Name:</b> ".$fname." ".$lname." </tr>";
							$txt.="<tr><b>User Email:</b> ".$email." </tr>";
							$txt.="<tr><b>Login UserName:</b> ".$username."</tr>";
							$txt.="<tr><b>Address:</b> ".$address." </tr>";
							$txt.="<tr><b>Click <a href='".ROOT_URL."/admin'>here</a> to login to admin panel. </b></tr>";
							$txt.="</table>";
							mail($to,$subject,$txt,$header);
							echo "1";
							
						/*----------------------------------------- EmAiL ENdS HeRe ------------------------------------*/
						/* ------------------------ Inserting default categories to a user ------------------------*/
							
							
						
						/*-----------------------------------------------------------------------------------------*/
						 $_SESSION["regSuccess"] = $username;
						 $userDetails = $db->get_row("SELECT * FROM StoreUsers WHERE Username='$username'");
						 $_SESSION['StoreID'] = $uid; 
						 $_SESSION["NOPAYMENT"] = "0";
						// echo $re;
					 }
				}
				else if($_POST['type']==3)
				{
					if(!empty($_POST['cat_id']))
					{
						$catid = $_POST['cat_id'];
						$UserId = $_SESSION['StoreID'];
						$result=mysql_query("select categoryID,categoryName from ProductCategory where UserId='$UserId' and parentID ='0' and isDeleted=0 and isActive=1") or die(mysql_error());
						echo "<option selected value=''>Select Category</option>";
							while($data = mysql_fetch_array($result))
							{ 
								if($catid==$data['categoryID']){$active='selected';} else {$active='';}
								echo "<option " .$active." value=".$data['categoryID'].">".$data['categoryName']."</option>";
							}
					}
					else
					{
						$UserId = $_SESSION['StoreID'];
						$result=mysql_query("select categoryID,categoryName from ProductCategory where UserId='$UserId' and parentID ='0' and isDeleted=0 and isActive=1") or die(mysql_error());
						echo "<option selected value=''>Select Category</option>";
							while($data = mysql_fetch_array($result))
							{ 
								echo "<option value=".$data['categoryID'].">".$data['categoryName']."</option>";
							}
					}
					
				}
				else if($_POST['type']==4)
				{
					$CategoryId = $_POST['CategoryId'];
					$result=mysql_query("select * from ProductCategory where parentID='$CategoryId'  and UserId=".$_SESSION['StoreID']) or die(mysql_error());
						$output = "<option selected disabled>Select Product</option>";
						while($data = mysql_fetch_array($result))
						{
							$output .= "<option value=".$data['categoryID'].">".$data['categoryName']."</option>";
						}
					
					echo $output;
				}
				else if($_POST['type']==5)
				{
					$productID = $_POST['ProductId'];
					$result=mysql_query("select * from Products where categoryID ='$productID' ") or die(mysql_error());
					$data = mysql_fetch_array($result);
					if(!empty($data))
					{
						echo json_encode($data); 
					}
					else
						echo "1";
					
				}
				else if($_POST['type']==6)
				{
					$UserId = $_SESSION['StoreID'];
					if(!empty($_POST['PcategoryID']))
					{
						$qbcode = $_POST['QBcode'];						
						$productName = $_POST['productName'];
						$price = $_POST['price'];
						$isActive = $_POST['isActive'];
						$categoryID = $_POST['PcategoryID'];
						$productID = $_POST['productID'];
						$result = mysql_query("UPDATE Products SET QBcode = '$qbcode', productName = '$productName', price = '$price', isActive = '$isActive' where categoryID = '$productID' ") or die(mysql_error());
						if($result==1)
						{
							echo "1";
						}
						else
							echo "error. Try Again Later..";
					}
					else
					{
						$qbcode = $_POST['QBcode'];						
						$productName = $_POST['productName'];
						$price = $_POST['price'];
						$isActive = $_POST['isActive'];
						$categoryID = $_POST['ProductId'];
						$storeid = $_POST['StoreId'];
						$result1 = mysql_query("Insert INTO Products (QBcode,productName,price,isActive,categoryID) VALUES('$qbcode','$productName','$price','$isActive','$categoryID')")or die(mysql_error());
						if($result1==1)
						{
							echo "1";
						}
						else
							echo "error. Try Again Later..";
					}
				}
				else if($_POST['type']==7)
				{
					
					$result = mysql_query("delete from StoreUserSubscription where StoreUserSubscriptionId=".$_POST['StoreUserSubscriptionId']);
					$result = mysql_query("delete from stores where id=".$_POST['id']);
					updatejson();
					if($result==1)
						echo "1";
					else
						echo "2";
				}
				else
					echo "post error";
			}
			else
				echo "In post error";
		}
 ?>