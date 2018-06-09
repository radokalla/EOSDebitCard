<style>
	.active
	{ 
		background-color: #3E3E3E
	}
</style>
<?php if(empty($header)){$header = null;} 

$stores_result = mysql_query("select s.id,s.name from stores as s inner join StoreUserSubscription as sub on sub.StoreUserSubscriptionId=s.StoreUserSubscriptionId where UserId = '".$_SESSION['StoreID']."' order by name asc");


?>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 myact-user">
                	<h3>My Account</h3>
                    <ul>
                    	<li <?php if($header=='Profile'){echo "class='active'";} ?>><a href="accountprofile.php">User Profile</a></li>
                        <li <?php if($header=='Password'){echo "class='active'";} ?>><a href="changepass.php" >Change Password</a></li>
                        <li <?php if($header=='Stores'){echo "class='active'";} ?>><a href="stores.php" >Stores</a></li>
                        <li <?php if($header=='Categories'){echo "class='active'";} ?>><a href="categories.php" >Categories</a></li>
                          <?php if(mysql_num_rows($stores_result) > 0)
						{?>
							<li <?php if($header=='Products'){echo "class='active'";} ?>><a href="productdetails.php" >Products</a></li>
						<?php } ?>
                        <!--
                        <li <?php if($header=='TickerPreferance'){echo "class='active'";} ?>><a href="tickerprefernce.php" >Ticker Preference</a>--></li>
					<!--<li <?php if($header=='cProducts'){echo "class='active'";} ?>><a href="confirm_pro.php" >Confirm Products</a></li>-->
                        <li <?php if($header=='Subscriptions'){echo "class='active'";} ?>><a href="orders.php" >Subscriptions</a></li>
                    </ul>
                </div>