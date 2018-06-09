<?php
	 session_start();   
	 if(empty($_SESSION['time']) || empty($_SESSION['regSuccess'])){
?>
	<script> window.location.href = 'addstore.php' </script>
	 
<?php } 
	include_once './includes/config.inc.php'; 
	include_once 'header.php';
?>
	
	
	<div class="row"> 
		<div class="col-md-12">
			<h1>Payment & Transaction Details</h1>
			<section>
				<div class="row">
					<div class="col-lg-12">
					</div>
				</div>
			</section>
			<section>
				<div class="row">
					<div class="col-lg-12 col-sm-12">
						<div class="col-lg-4"><h3></h3></div><div class="col-lg-4"></div>
						<div class="col-lg-4"><h3></h3></div><div class="col-lg-4"></div>
						<div class="col-lg-4"><h3></h3></div><div class="col-lg-4"></div>
						<div class="col-lg-4"><h3></h3></div><div class="col-lg-4"></div>
						<div class="col-lg-4"><h3></h3></div><div class="col-lg-4"></div>
						<div class="table-responsive">
							<table id="example1" class="table table-bordered table-striped">
								<thead>
								  <tr>
									<th>Card Number</th>
									<th>Amount Paid</th>
									<th>Transaction ID</th>
									<th>Authorization Time</th>
									<th>Status</th>
								  </tr>
								</thead>
								<tbody>
								  <tr>
									<td><?php echo $_SESSION["Cardno"]; ?></td>
									<td><?php echo $_SESSION["price"]; ?></td>
									<td><?php echo $_SESSION["CreditCardTransID"]; ?></td>
									<td><?php echo $_SESSION["TxnAuthorizationTime"]; ?></td>
									<td><?php echo $_SESSION["Status"]; ?></td>
								  </tr>
								</tbody>
							</table>
							<h4 style="text-align:center">
								<br>
								Please note this details for your future reference.. <br><br>
								<a href="orders.php" style="color:#2a6496">Click here to view all your subsciptions </a><br><br>
								<a href="stores.php" style="color:#2a6496">Click here to view / add Store's</a>
							</h4>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
	<?php  unset($_SESSION['time']); ?>