<?php session_start();  
	include_once 'header.php'; 
	$username = $_SESSION["regSuccess"];
/*	$result=mysql_query("select Payment from StoreUsers where Username='$username'") or die(mysql_error());
	$data=mysql_fetch_array($result);	*/
			if(empty($username)) { ?>
<script>
	window.location.href = 'addstore.php';
</script>	
<?php } else { 
$stores_result = mysql_query("select s.id,s.name from stores as s inner join StoreUserSubscription as sub on sub.StoreUserSubscriptionId=s.StoreUserSubscriptionId where UserId = '".$_SESSION['StoreID']."' order by name asc");
				


?>
        	<div class="row">
            	<?php include ROOT."admin-left.php"; ?>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 myact-userdet">
                        <h2 class="head-text">Dashboard</h2>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        	<p class="dash-opt"><a href="storedetails.php"><i class="fa fa-home fa-3x"></i><br />Add Store</a></p>
                        </div>
                        <?php if(mysql_num_rows($stores_result) > 0)
						{?>
							 <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<p class="dash-opt"><a href="addproduct.php"><i class="fa fa-cubes fa-3x"></i><br />Add Products</a></p>
								</div>
						<?php } ?>
                       
                         <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        	<p class="dash-opt"><a href="subs.php"><i class="fa  fa-usd fa-3x"></i><br />Upgrade Subscription</a></p>
                        </div>
                     </div>
                </div>
            </div>
        </div>
	</div>
</div>
<?php include ROOT."themes/footer.inc.php";  }   /* if(($data['Payment'])==0) { echo $data['payment'] ;} */  ?> 
</body>
</html>