<?php 
 ini_set('error_reporting', 0);
ini_set('display_errors', 0);
 session_start();
 include_once 'header.php'; 
 include_once './includes/config.inc.php';
include_once './includes/class.database.php';
if(empty($_SESSION["regSuccess"])){ ?>
<script>
	window.location.href= 'addstore.php';
</script>
<?php }
$uname = $_SESSION['regSuccess'];
$udetails = mysql_query("select SubscriptionCategoryId from StoreUsers where Username ='$uname'")or die(mysql_error()) ;
$urow = mysql_fetch_array($udetails);
$userSubsId = $urow['SubscriptionCategoryId'];

//$subscriptions = mysql_query("select * from SubscriptionTypes where InitialAmount != '0' and Status='1' and IsDeleted='0' and SubscriptionTypeId!='' and SubscriptionTypeId!='$userSubsId' order by OrderId ASC");

$subscriptions = mysql_query("select * from SubscriptionTypes where InitialAmount != '0' and Status='1' and IsDeleted='0' and SubscriptionTypeId!='' and SubscriptionCategoryId = '$userSubsId' order by OrderId ASC");
while($row = mysql_fetch_array($subscriptions))
{
	$subscriptions_arr[] = $row['SubscriptionTypeId'];
	$subscriptions_data[] = $row;
}

$date = date("Y-m-d");
$subscribed = mysql_query("select SubscriptionId from StoreUserSubscription where EndDate >='$date' and UserId =".$_SESSION['StoreID']);

while($user_subs = mysql_fetch_array($subscribed))
{
	$data[] = $user_subs['SubscriptionId'];
}

//print_r($_SESSION['StoreID']);die;
 ?>
 <style type="text/css">
 	.plandetails h2 {
    border: 0 none;
    color: #000000;
    font-size: 20px;
    padding-bottom: 20px;
	text-align:center;
}
.plandetails p {
	text-align:center;
	margin:0;
	padding:20px 0;
}
.plandetails p.planname {
	background:#dff9c0;
	font-size:24px;
	font-weight:600;
	color:#000000;
	padding: 20px 15px;
	min-height: 107px;
	word-wrap: break-word;
}
@media (min-width:320px) and (max-width:736px){
	.plandetails p.planname {
		min-height:0;
	}
}
.plandetails p.planstore,
.plandetails p.plan-det-icons {
	background:#cccccc;
	font-size:15px;
}
.plandetails p.plan-det-icons {
	border-top:1px solid #ffffff;
	min-height: 81px;
}
.plandetails p.price {
	background:#7dcd1e;
	font-size:30px;
	color:#ffffff;
}
.plandetails p.sub-btn {
	background:#323232;
	color:#ffffff;
	font-size:18px;
}
 </style>
<div class="row marb-50"> 
    <!--<div class="col-md-12"><h1>Membership Packages</h1></div>-->
	<div class="col-md-12">
    	<div class="plandetails">
        	<h2>Map Icon Subscriptions</h2>
			<?php if(!empty($_SESSION['SubINVALID'])){ ?>
				<div class="alert alert-danger" id="hide" style="text-align:center"><?php echo $_SESSION['SubINVALID']; unset($_SESSION['SubINVALID']); ?></div>
			<?php } ?>
			<?php $i=1; foreach($subscriptions_data as $subs)
			{
				if($i==1 ){
						 ?>
				<div class="row">
				<?php } ?>
					<a href="payment.php?type=<?php echo $subs['SubscriptionTypeId'] ?>" style="text-decoration:none; color:#000">
						<div class="col-md-4 marg-btm20">
							<p class="planname">		
							   <?php echo $subs['Subscription']; ?> 		
							</p>
							<!--<p class="planstore">
								Add Upto  <?php if($subs['Stores_Count']=='0'){ echo '1';} else{ echo $subs['Stores_Count'];}  ?> Store.
							</p>-->
							<p class="planstore">
								Duration: <?php echo $subs['Duration']; ?>
							</p>
							<p class="plan-det-icons">
								<?php if($subs['Icon1']!=''){ ?>
								<img src="<?php echo ROOT_URL.'admin/'.$subs['Icon1']; ?>" style="height:40px;" alt='No Icon' Onerror="$(this).attr('src','img/no_image.png');"/>
								<?php } else{echo "NO ICONS";} ?>
								<?php if($subs['Icon2']!=''){ ?>
								<img src="<?php echo ROOT_URL.'admin/'.$subs['Icon2']; ?>" style="height:40px;" alt='No Icon' Onerror="$(this).attr('src','img/no_image.png');"/>
								<?php } else{echo "NO ICONS";} ?>
								<?php if($subs['Icon3']!=''){ ?>
								<img src="<?php echo ROOT_URL.'admin/'.$subs['Icon3']; ?>" style="height:40px;" alt='No Icon' Onerror="$(this).attr('src','img/no_image.png');"/>
								<?php } else{echo "NO ICONS";} ?>
							</p>
							<p class="price">							
							   <?php echo "$ ".$subs['InitialAmount'] ?>  		
							</p>
							
							<?//var_dump($subs); var_dump($data); die;
							if (in_array($subs['SubscriptionTypeId'], $data))
							{ ?>
								<a href="payment.php?type=<?php echo $subs['SubscriptionTypeId'] ?>" style="text-decoration:none">
									<p class="sub-btn">		
									   Subscribed &nbsp <i class="fa fa-check"></i>
									</p>
								</a>
							<?php }
							else{ ?>
							<a href="payment.php?type=<?php echo $subs['SubscriptionTypeId'] ?>" style="text-decoration:none">
								<p class="sub-btn">		
								   Subscribe	
								</p>
							</a>
							<?}?>
						</div>
					</a>
				<?php if($i  == 3){
					$i=0;?>
				</div>
				<?php } ?>
			<?php $i++; } ?>
		</div>
	</div>
</div>
 <?php include ROOT."themes/footer.inc.php"; ?>