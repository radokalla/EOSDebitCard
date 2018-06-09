<?
session_start();
$header='Subscriptions';
$username = $_SESSION["regSuccess"];
if(empty($username)){ ?>
	<script>
		window.location.href = '<?=ROOT_URL;?>addstore.php';
	</script>
<?php }
include_once 'header.php';
//include_once './includes/config.inc.php';
error_reporting(0);
$db = db_connect();
mysql_query("SET NAMES utf8");
$user_id = $_SESSION['StoreID'];
//$Subscriptions = mysql_query("select * from StoreUserSubscription where UserId ='$user_id' ");


$Subscriptions = mysql_query("select * from StoreUserSubscription,SubscriptionTypes where StoreUserSubscription.SubscriptionId=SubscriptionTypes.SubscriptionTypeId and UserId='$user_id' ");

//echo $_SESSION["StoreID"].'<pre>';print_r($Subscription);exit;
?>
<?


?>
        	<div class="row">
            	<?php include ROOT."admin-left.php"; ?>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 myact-userdet">
                        <h2 class="head-text">Subscriptions</h2>
							<div> &nbsp </div>
                        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <form class="form-horizontal">
                                    <div class="table-responsive">
                                          <table class="table table-bordered">
                                            <thead>
                                              <tr>
                                                <th>Subscription</th>
                                                <th>Expires on</th>
                                                <th align="center">Status</th>
                                                 <th align="center">420medsoft Subscription</th>
                                              </tr>
                                            </thead>
                                            <tbody>
											<?php while($Subscription = mysql_fetch_array($Subscriptions)){ ?>
											
                                              <tr>
                                                <td><?php  echo $Subscription['Subscription'];?></td>
                                                <td><?php if($Subscription['InitialAmount']==0){echo"No Expiry Date";} else{ echo date('Y-m-d',strtotime($Subscription['EndDate']));}?></td>
                                                <td><?php if($Subscription['InitialAmount']==0){echo "<a class='btn btn btn-success btn-sm' href='#'>Free</a>";?>
                                                <a href="subs.php" class="btn btn-primary  btn-sm">
									Upgrade
								</a>
                                             <?php } else{ if(($Subscription['EndDate'])<(date("Y-m-d"))) {?><a href="#" class="btn btn btn-danger btn-sm">Expired</a><?php } else { ?><a href="#" class="btn btn btn-success btn-sm">ACTIVE</a><?php } ?> </td>
                                           
											<?php }} ?>
                                             <td><a href="http://420medsoft.com/index.php/main/packages" class="btn btn btn-danger btn-sm">420medsoft Subscription</a></td>
                                              </tr>
                                            </tbody>
                                          </table>
                                    </div>
                                </form>
                        	</div>
                     </div>
                </div>
            </div>
        </div>
	</div>
</div>

<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel11" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title LRhd" id="myModalLabel">Current plan</h4>
            </div>
            <div class="modal-body">
                        <section>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="input"> Subscription
                                    	<input readonly type="text"  class="form-control" value="<?php  echo $Subscription['Subscription'];?>" name="website" />
                                    </label>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="input"> Duration (in days)
                                    	<input readonly type="text" class="form-control" value="<?php  echo $Subscription['Duration'];?> " name="website" />
                                    </label>
                                </div>
                            </div>
                        </section>
      <section>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="input"> Price
                                    	<input readonly type="text"  class="form-control" value="<?php  echo $Subscription['InitialAmount'];?>" name="website" />
                                    </label>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="input"> Expires on
                                    	<input readonly type="text" class="form-control" value="<?php  echo $Subscription['subscribed_at'];?>" name="website" />
                                    </label>
                                </div>
                            </div>
                        </section>
                
				
			</div>
		</div>
	</div>
</div>
<?php include ROOT."themes/footer.inc.php"; ?>

</body>
</html>