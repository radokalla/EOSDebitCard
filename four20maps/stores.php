<?
session_start();
$header = 'Stores';
$username = $_SESSION["regSuccess"];

	if(empty($username)) { ?>
	<script>
		window.location.href = 'addstore.php';
	</script>	
	<?php } else {
include_once 'header.php';
if(isset($_REQUEST["generate_url"]) && $_REQUEST["generate_url"]=="yes"){
	
		
		require_once('admin/includes/Googl.class.php');

$googl = new Googl('AIzaSyA1kV7OTLBQNr517kIPzhR1GhodYn6WtGc');

// Shorten URL
$short_url=$googl->shorten(ROOT_URL.'store/'.$_GET['store_name'].'/zip/'.$_GET[ 'zipcode']);

 
unset($googl);
	$db = db_connect();
	$db->update( 'stores', array( 'short_url' => $short_url ), $_GET[ 'store_id']);
	header("location:stores.php");
}
$db = db_connect();
	if($_POST['search'])
	{
		$keyword = $_POST['search'];
		$stores = mysql_query("select * from vw_SubscriptionStoreDetails where Status = '1' and name like '%$keyword%' and UserId=".$_SESSION['StoreID']);
	}
	else
	{
	$stores = mysql_query("select * from vw_SubscriptionStoreDetails where Status = '1' and UserId=".$_SESSION['StoreID']);
	$count = mysql_num_rows($stores);
	}
	//$store_details = mysql_fetch_array($stores);
	$Cat_array=array("1"=>"Add Dispesary","2"=>"Add Delivery","3"=>"Add Brand Vendor","4"=>"Add Farm");
 
?>
        	<div class="row">
            	<?php include ROOT."admin-left.php"; ?>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12" id="add">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 myact-userdet">
					 <div class="alert alert-warning" role="alert" style="display:none; text-align:center" id="alert"></div>
										<?php if(!empty($_SESSION['StoreSuc'])) { ?>
											 <div class="alert alert-success" id="alert1" role="alert" style=				"text-align:center"><?php echo $_SESSION['StoreSuc'] ?>
											 </div>
										<?php } ?>
                        <h2 class="head-text">Store details<a href="storedetails.php" class='pull-right btn btn-primary addprdbtn btn-sm'><?=$Cat_array[$_SESSION["SubscriptionCategoryId"]];?></a></h2>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<?php if($count>0){ ?>
									<form action="" method="post">
										<div class="row marg-btm10"><div class="col-xs-8 col-sm-9 col-md-5 col-lg-5">
											<input type="text" class="form-control" placeholder="Search by store name" name="search" />
										</div>
										<div class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
											<button type="submit" class="btn btn-primary">Search</button>
										</div></div>
									</form>
									<?php } ?>
									
								<div class="table-responsive">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th align="center">Store Name</th>
												<th align="center">Subscription</th>
												<th align="center">Short Url</th>
												<th align="center">Status</th>
												<th align="center">Actions</th>
											</tr>
										</thead>
										<tbody>
										
											<?php if(($count<=0) || ($count=='')){echo "<tr><td>No Stores Found..<td></tr>";} else{
													while($store_details = mysql_fetch_array($stores)) 
												{ 
													$result = mysql_query("SELECT `StoreUserSubscriptionId` FROM `stores` WHERE `id` ='".$store_details['StoreId']."'");
													$row = mysql_fetch_array($result); ?>
											<tr>
												<td scope="row"><?php echo $store_details['name']; ?></td>
												<td><?php echo $store_details['Subscription']; ?></td>
												    <td class="acenter"><?php if(isset($store_details['short_url']) && !empty($store_details['short_url'])) { 
														?>
														<a href="<?=$store_details['short_url']?>"><?=$store_details['short_url']?></a>
														<?php 
							 }
							else if(!empty($store_details['zipcode'])) {
							$store_name=str_replace(" ","_",strtolower($store_details['name'])); ?>
                        <a href="stores.php?generate_url=yes&store_id=<?php echo $store_details['StoreId'];?>&store_name=<?php echo $store_name;?>&zipcode=<?php echo $store_details['zipcode'];?>">Generate Url</a>  <?php }else{?>
                        ---
                        <?php }?>
                        </td>  
												<td align="center"><?php if($store_details['Status']==1){echo "Active";}elseif($store_details['SubscriptionEndDate'] < $date){echo"Expied";} else{echo"In-Active";} ?></td>
												<td align="center">
													<a href="<?php echo "storedetails.php?id=".$store_details['StoreId']; ?>"><i class="fa fa-pencil"></i></a>
													<a href="#" OnClick="del('<?php echo $store_details['StoreId']; ?>','<?php echo $row["StoreUserSubscriptionId"];?>');"><i class="fa fa-trash"></i></a>
												</td>
											</tr>
											<?php }} ?>
										</tbody>
									</table>
								</div>
							</div>
					</div>
				</div>
			</div>
</div>
</div>
<script>
	 <?php if(!empty($_SESSION['StoreSuc'])) { ?>
		$(document).ready(function (){
			setTimeout(function (){ $('#alert1').hide(); }, 3000);
		});
	 <?php unset($_SESSION['StoreSuc']); } ?>
	 
	 function del(id,StoreUserSubscriptionId)
	 {
		if(confirm("Do you really want to delete this store ?"))
		{
			$.ajax({
					url: "checkEmail.php",
					data: 
					{'type':7, 'id':id,'StoreUserSubscriptionId':StoreUserSubscriptionId},
					type: "POST",
					success: function(data)
					{
						if(data==1)
						{
							$('#alert').removeClass('alert-danger');
							$('#alert').addClass('alert-success');
							document.getElementById('alert').innerHTML = "Deleted Successfully";
							$('#alert').show();
							setTimeout(function (){window.location.reload(); }, 2000);
						}
						if(data==2)
						{
							$('#alert').removeClass('alert-success');
							$('#alert').addClass('alert-danger');
							document.getElementById('alert').innerHTML = "Cannot delete at this moment. Please try later";
							$('#alert').show();
							setTimeout(function (){$('#alert').hide(); }, 4000);
						}
					}
			});
		}
	 }
</script>
	<?php  include ROOT."themes/footer.inc.php";} ?>