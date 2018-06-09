<?php
$heading='store';
// include Config File
include_once './includes/config.inc.php';
include_once './includes/class.database.php';
include_once './includes/validate.php';
// Authenticate user login
auth();
 $db = db_connect();

$subscriptions = mysql_query("select * from SubscriptionTypes where  Status='1' and IsDeleted='0' and SubscriptionTypeId!='' and SubscriptionCategoryId = '".$_GET["sub_id"]."' order by OrderId ASC");
while($row = mysql_fetch_array($subscriptions))
{
	$subscriptions_arr[] = $row['SubscriptionTypeId'];
	$subscriptions_data[] = $row;
}
if(isset($_POST["SubscriptionCategoryId"]) )
{
	 
	mysql_query("INSERT INTO `StoreUserSubscription`(`UserId`, `SubscriptionId`,`CreatedDate`, `Status`) VALUES ('".$_POST['UserId']."','".$_POST['SubscriptionTypeId']."','".date("Y-m-d h:m:s")."',0);");
	$StoreUserSubscriptionId=mysql_insert_id();
	mysql_query("UPDATE `stores` SET `StoreUserSubscriptionId`=".$StoreUserSubscriptionId." WHERE  id=".$_POST['store_id']);
	header('location:store_users.php');
}
 
?>
	  <?php include("header.php"); ?>
      <!-- Left side column. contains the logo and sidebar -->
      
 <link rel="stylesheet" href="http://www.four20maps.com/css/jquery-ui.css">     

      <?php include("sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Assign Store for <?=$_GET["user_name"];?></h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Assign Store</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
         <?php if(!empty($error_msg)){ ?>
				<div class="alert alert-danger" id="hide" style="text-align:center"><?php $error_msg; ?></div>
			<?php } ?>
          <!-- Small boxes (Stat box) -->
		<div class="row">
			<form method="post" class="form" id="assign_store_form">
				<div class="col-lg-4 col-sm-4 col-xs-4">
					<div class="form-group">
						<input type="hidden" id="store_id" name="store_id"/>
						<input type="hidden" id="SubscriptionTypeId" name="SubscriptionTypeId"/>
						<input type="hidden" id="UserId" name="UserId" value="<?=$_GET["id"]?>"/>
						<input type="hidden" id="SubscriptionCategoryId" name="SubscriptionCategoryId" value="<?=$_GET["sub_id"]?>"/>
						<label><b>Store Name:</b></label>
						<input type="text" name="address" id="search_box" class="form-text form-control" placeholder="Search Store"/>
						<span class="srch-loadingicon" id="load1" style="display:none"><i class="fa fa-spinner fa-spin"></i></span>
					</div>
				</div>
			 
				 
			</form>
         </div><!-- /.row -->
          <div class="row">
         <div class="col-md-12">
    	<div class="plandetails">
        	<h2>Map Icon Subscriptions</h2>
			 
			<?php $i=1; foreach($subscriptions_data as $subs)
			{
				if($i==1 ){
						 ?>
				<div class="row">
				<?php } ?>
					 
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
								<img src="<?php echo 'http://www.four20maps.com/admin/'.$subs['Icon1']; ?>" style="height:40px;" alt='No Icon' Onerror="$(this).attr('src','img/no_image.png');"/>
								<?php } else{echo "NO ICONS";} ?>
								<?php if($subs['Icon2']!=''){ ?>
								<img src="<?php echo 'http://www.four20maps.com/admin/'.$subs['Icon2']; ?>" style="height:40px;" alt='No Icon' Onerror="$(this).attr('src','img/no_image.png');"/>
								<?php } else{echo "NO ICONS";} ?>
								<?php if($subs['Icon3']!=''){ ?>
								<img src="<?php echo 'http://www.four20maps.com/admin/'.$subs['Icon3']; ?>" style="height:40px;" alt='No Icon' Onerror="$(this).attr('src','img/no_image.png');"/>
								<?php } else{echo "NO ICONS";} ?>
							</p>
							<p class="price">							
							   <?php echo "$ ".$subs['InitialAmount'] ?>  		
							</p>
							
							
							<a href="javascript:void(0);" style="text-decoration:none">
								<p class="sub-btn" data-SubscriptionTypeId="<?=$subs['SubscriptionTypeId'];?>">		
								   Select	
								</p>
							</a>
							
						</div>
					 
				<?php if($i  == 3){
					$i=0;?>
				</div>
				<?php } ?>
			<?php $i++; } ?>
			<div class="col-lg-12 col-sm-12">
                        <div class='input buttons'>
                            <a class="btn btn-primary" OnClick="submitAssignStore();">Submit</a>
							<input type="hidden" value="1" name="type" />
							<button type="reset" class="btn btn-danger">Reset</button>
                        </div>
                    </div>
		</div>
	</div>
 </div>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
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
.subscribe_active{
background:blueviolet !important;
color:#00000 !important;
font-size:18px;  
}
 </style>     
      	
<script>
	function  submitAssignStore(){
		$("#assign_store_form").submit();	
	}
$(".sub-btn").click(function(){
	$("#SubscriptionTypeId").val($(this).data('SubscriptionTypeId'));
	$(".subscribe_active").removeClass("subscribe_active");
	$(this).addClass("subscribe_active"); 
	$("#SubscriptionTypeId").val($(this).data('subscriptiontypeid'));   
})
$( "#search_box" ).autocomplete({
        source: function( request, response ) {
        $.ajax({
			url: "http://www.four20maps.com/admin/admin_ajax.php",
			data: 
			{
				type: 'Stores',
				q: $("#search_box").val()
			},
			dataType: "json",
			type: "POST",
			beforeSend:function(){
				$('#load1').show();
			},
			complete:function(){
				$('#load1').hide();
			},
			success: function( data ) {
				response(data);
			}
        })
      },
		minLength:2,
		select: function( event, ui ){
			var id = ui.item.id;
			$("#store_id").val(id);
		}
		 
	});
</script>
<?php include("footer.php"); ?>
