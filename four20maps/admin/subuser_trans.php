<?php
$heading='store';
// include Config File
include_once './includes/config.inc.php';
include_once './includes/class.database.php';
include_once './includes/validate.php';
// Authenticate user login
auth();

?>
	  <?php include("header.php"); ?>
	    <?php include("sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Store Users Transactions</h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Store Users Transactions</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
            	<div class="box">
                <div class="box-header">
                </div><!-- /.box-header -->
                <div class="box-body">
					<div class="alert alert-success" id="alert" style="text-align:center; display:none">Deleted Successfully</div>
                    <div class="dataTables_wrapper form-inline dt-bootstrap">					
                  <div class="row">
                      <div class="col-lg-12 col-sm-12 col-xs-12">
                          <div class="table-responsive">
                          <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
									<th>Username</th>
									<th>Subscription</th>
									<th>Amount Paid</th>
									<th>Transaction ID</th>
									<th>Authorization Time</th>
									<th>Action</th>
                                </tr>
							</thead>
							<tbody>
				<?php  $db = db_connect();
						$result=mysql_query("SELECT * FROM Transactions,SubscriptionTypes WHERE Transactions.SubscriptionTypeid = SubscriptionTypes.SubscriptionTypeid ORDER BY Transactions.Sno DESC limit 20") or die(mysql_error()); ?> 

					<?php while($v=mysql_fetch_array($result)) { ?>

						<td><?php echo $v['Username']; ?></td>
						<td><?php echo $v['Subscription']; ?></td>
						<td><?php echo $v['AmountPaid']; ?></td>
						<td><?php echo $v['CreditCardTransID']; ?></td>
						<td><?php echo $v['TxnAuthorizationTime']; ?></td>
						<td class="actions text-center">
							<a href="#" OnClick="DelTran('<?php echo $v['TransactionSno'];?>');"><i class="fa fa-trash"></i></a>
						</td>
						
					</tr>

					<?php } ?>

				</tbody>

			</table>
            </div>
                      </div>
                  </div></div>
                </div><!-- /.box-body -->
              </div>
            </div>

          </div><!-- /.row -->

        </section><!-- /.content -->
      </div>
	<script>
		function DelTran(trans)
		{
			var type = '7';
			if(confirm("Are you sure ? you want to delete the Transaction ??"))
			{
				$.ajax({
					type: "POST",
					url: "storesdb.php",
					data : {'type':type, 'trans':trans},
					success: function(data)
					{
						$('#alert').show();
						setTimeout(function (){ window.location.href = ''; }, 3000);
						
					}
				});
			}
		}
	</script>
<?php include("footer.php"); ?>