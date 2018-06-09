<?php 
session_start(); 
$heading!='payment';
include_once './includes/config.inc.php';
$subscriptions = mysql_query("select * from SubscriptionTypes where SubscriptionTypeId='".$_REQUEST['type']."'");
while($row = mysql_fetch_array($subscriptions))
{
	 $subscriptions_data[] = $row;
}
$date = date("Y-m-d");
 if(empty($_SESSION['regSuccess']))
{ ?>
	 <script>window.location.href= 'addstore.php';</script>
<?php }
	$username = $_SESSION['regSuccess'];
	if(!empty($_REQUEST['type']))
	{
		$sid = $_REQUEST['type'];
		$Inimount = mysql_query("SELECT InitialAmount FROM SubscriptionTypes WHERE SubscriptionTypeId ='$sid' "); 
		$InitialAmount =  mysql_fetch_array($Inimount);
	if(empty($Inimount) || ($InitialAmount['InitialAmount']==0))
	{  ?>
<script>
		window.location.href = 'dashboard.php';
</script>
<?php 
	} 
	if(isset($_POST)&&($_POST['nameoncard']!=''))
	{
		if($_POST['total_price']>0)
		{
			
			$_SESSION['session']['nameOnCard'] = $_POST['nameoncard'];
			$_SESSION['session']['cardNumber'] = $_POST['cardnumber'];
			$_SESSION['session']['expiry_year'] = $_POST['year'];
			$_SESSION['session']['expiry_month'] = $_POST['month'];
			$_SESSION['session']['CVVCode'] = $_POST['securitycode'];
			$_SESSION['session']['total_price'] = $_POST['total_price'];
			$_SESSION['session']['subs_type']= $_POST['SubscriptionType'];
		//$this->data['patientDetails']['QBCodeID']
		//$this->data['tax_percentage']
		
		?>
		<script>
			window.location.href="payment_gate.php";
		</script>
		<?php
		die;
	?>
	<script>
		window.location.href = 'payment_gate.php';
	</script>
	<?php }
	else {?>
	<script>
		window.location.href = 'storelogout.php';
	</script>
	<?php }
		
	} 
	?> 
	<?php include_once 'header.php'; ?>
	<script>
		var lat=0, lng=0, inizoom=0;
	</script>
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
<div class="row"> 
	<div class="col-md-12">
	
		<form action="" class="xform" name="reg_form" method="post">
			<div class="col-md-4 marg-btm20">
			 <div class="plandetails">
					<p class="planname">
						<?php echo $subscriptions_data[0]['Subscription']; ?> 	
					</p>
					 	<p class="planstore">
						Duration: <?php echo $subscriptions_data[0]['Duration']; ?> </p>
					<p class="plan-det-icons">
								<?php if($subscriptions_data[0]['Icon1']!=''){ ?>
								<img src="<?php echo ROOT_URL.'admin/'.$subscriptions_data[0]['Icon1']; ?>" style="height:40px;" alt='No Icon' Onerror="$(this).attr('src','img/no_image.png');"/>
								<?php } else{echo "NO ICONS";} ?>
								<?php if($subscriptions_data[0]['Icon2']!=''){ ?>
								<img src="<?php echo ROOT_URL.'admin/'.$subscriptions_data[0]['Icon2']; ?>" style="height:40px;" alt='No Icon' Onerror="$(this).attr('src','img/no_image.png');"/>
								<?php } else{echo "NO ICONS";} ?>
								<?php if($subscriptions_data[0]['Icon3']!=''){ ?>
								<img src="<?php echo 'http://www.four20maps.com/admin/'.$subscriptions_data[0]['Icon3']; ?>" style="height:40px;" alt='No Icon' Onerror="$(this).attr('src','img/no_image.png');"/>
								<?php } else{echo "NO ICONS";} ?>
							</p>
					<p class="price">
						<?php echo "$ ".$subscriptions_data[0]['InitialAmount'] ?> 
					</p>
 
				</div> 
			</div>
			<div class="col-md-6">
			 <h1>Payment</h1>
	<?php if($_SESSION["NOPAYMENT"] == "1") { ?>
		<div class="alert alert-success" style="text-align:center"><h4>Please pay the pending amount for Your Subscription ($ <?php echo $InitialAmount['InitialAmount']; ?>)</h4></div>
	<?php } ?>
	<?php if(!empty($_SESSION['CardError'])){ ?>
		<div class="alert alert-danger" style="text-align:center"><h4><?php echo $_SESSION['CardError']; unset($_SESSION['CardError']); ?></h4></div>
	<?php } ?>
				<section>
					<div class="row">
						<div class="col-md-12">
							<p>To pay by credit card, please fill out the fields below.</p>
						</div>
					</div>
				</section>
				 
				<section>
					<div class="row">
						<div class="col-md-12">
							<label class="input"> <i class="icon-prepend fa fa-credit-card"></i>
								<input type="text" OnKeyup="func();" autocomplete="off" placeholder="Name on card" name="nameoncard" id="nameoncard" required>
							</label>
						</div>
					</div>
				</section>
				<section>
					<div class="row">
						<div class="col-md-12">
							<label class="input"> <i class="icon-prepend fa fa-credit-card"></i>
								<input type="text" class="required" autocomplete="off" placeholder="Card Number" name="cardnumber" required>
							</label>
						</div>
					</div>
				</section>
				<section>
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								 
								<div class="col-md-4 col-sm-4 col-xs-12 res-marb20">
									<label class="select">
										<select name="month" required>
											<option value='' disabled selected>Select Month</option>
											<option value="01">01</option>
											<option value="02">02</option>
											<option value="03">03</option>
											<option value="04">04</option>
											<option value="05">05</option>
											<option value="06">06</option>
											<option value="07">07</option>
											<option value="08">08</option>
											<option value="09">09</option>
											<option value="10">10</option>
											<option value="11">11</option>
											<option value="12">12</option>
										</select>
									</label>
								</div>
								<div class="col-md-4 col-sm-4 col-xs-12">
									<label class="select">
										<select name="year" required>
											<option value='' disabled selected>Select Year</option>
											<option value="2014">2014</option>
											<option value="2015">2015</option>
											<option value="2016">2016</option>
											<option value="2017">2017</option>
											<option value="2018">2018</option>
											<option value="2019">2019</option>
											<option value="2020">2020</option>
											<option value="2021">2021</option>
											<option value="2022">2022</option>
											<option value="2023">2023</option>
											<option value="2024">2024</option>
											<option value="2025">2026</option>
											<option value="2027">2027</option>
											<option value="2028">2028</option>
											<option value="2029">2029</option>
											<option value="2030">2030</option>
										</select>
									</label>
								</div>
							</div>
						</div>
					</div>
				</section>
				<section>
					<div class="row">
						<div class="col-md-12">
							<label class="input"> <i class="icon-prepend fa fa-unlock"></i>
								<input type="text" class="required" placeholder="Security code (3 on back, Amex:4 on front)" autocomplete="off" maxlength="4" name="securitycode" required>
							</label>
						</div>
					</div>
				</section>
				<footer>
					<input type="hidden" name="total_price" value="<?php echo $InitialAmount['InitialAmount']; ?>" />
					<input type="hidden" name="SubscriptionType" value="<?php echo $_REQUEST['type']; ?>" />
					<div class="row">
						<div class="col-md-12"> 
							<!--<a href="" class="button button-secondary" onclick="return reg()">Register</a>-->
							<button onclick="" class="button" name="dosubmit1" type="submit">Continue</button>
						</div>
					</div>
				</footer>
			</div>
		</form>
	</div> 
</div>
<script>
$('[name=cardnumber],[name=securitycode]').keyup(function(e){
	if (/\D/g.test(this.value))
	this.value = this.value.replace(/\D/g, '');
});
function func()
{
	var namesPattern = /^[a-zA-Z ]*$/;
	var name = $('#nameoncard').val();
	if(!namesPattern.test(name))
	{
		$('#nameoncard').css('border-color','red')
		$('#nameoncard').val('');
	}
	else
	{
		$('#nameoncard').css('border-color','#eee')
	}
}
</script>
 <?php  }
 else{ ?> 
 <script> 
	window.location.href = 'addstore.php' 
</script> <?php 
 }  ?>