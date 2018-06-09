<?php
session_start();
$header='TickerPreferance';
include_once './includes/config.inc.php';
include_once './includes/functions.php';
include_once 'header.php';
error_reporting(E_ALL);

if(!isset($_SESSION['regSuccess']) && empty($_SESSION['regSuccess']))
{ 
?> 
<script>
window.location.href = 'addstore.php';
</script> 
<?php 
}
$query = "SELECT * from ticker_preference WHERE p.UserId=".$_SESSION['StoreID'];
if($_POST)
{
	$searchattr= $_POST['productName'];
	if(!empty($searchattr))
	{
		$query = "SELECT * from ticker_preference WHERE product_name like '%$searchattr%' and p.UserId=".$_SESSION['StoreID'];
			
	}
}
$tickerPreferanceProducts = mysql_query($query);
if($tickerPreferanceProducts=='')
	$count ='0';
else
$count = mysql_num_rows($tickerPreferanceProducts);
?>

        	<div class="row">
            	<?php include ROOT."admin-left.php"; ?>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 myact-userdet">
                        <h2 class="head-text">Ticker Preferance <a href="addtickerpreferance.php" class="pull-right btn btn-primary addprdbtn btn-sm">Add Ticker Preferance</a></h2>
						<?php if($count>0){ ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <form class="form-horizontal" name="searchform" id="searchform" method="post" action="">
                                    <div class="table-responsive">
                                          <table class="table table-bordered">
                                            <thead>
                                              <tr>
                                                <th>Product Name</th>
                                                <th></th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                              <tr>
                                                <td><input type="text" class="form-control" name="productName" id="productName" placeholder="Search By Product"></td>
                                                <td><button type="submit" class="btn btn-primary">Search</button>
                                              </tr>
                                            </tbody>
                                          </table>
                                    </div>
                                </form>
                            </div>
						<?php } ?>
                        	<!--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            	<form>
                                	<div class="form-group pull-right">
                                        <select class="form-control mart-10">
                                            <option>10 Records</option>
                                            <option>20 Records</option>
                                            <option>30 Records</option>
                                        </select>
                                    </div>
                                </form>
                            </div>-->
                        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <form class="form-horizontal">
                                    <div class="table-responsive">
                                          <table class="table table-bordered">
                                            <thead>
                                              <tr>
                                                <th>S. No</th>
                                                <th>Product Name</th>
                                                <th>Ticker Price</th> 
                                                <th align="center">Actions</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                            <?php
											if($count>0){
											$i=0;
											if(!empty($tickerPreferanceProducts)){
											$count = mysql_num_rows($tickerPreferanceProducts);
											if($count>0) {
                                            while($tickerPreferanceProduct = mysql_fetch_assoc($tickerPreferanceProducts)){ $i++;
											?>
                                              <tr>
                                                <th scope="row"><?php echo $i;?></th>
                                                <td><?php echo $tickerPreferanceProduct['product_name'];?></td> 
                                                <td><?php echo $tickerPreferanceProduct['ticker_price'];?></td>                                       
                                                <td align="center">
                                                <a href="<?php echo ROOT_URL.'addtickerpreferance.php?id='.$tickerPreferanceProduct['id'];?>" ><i class="fa fa-pencil"></i></a> 
                                                <a href="#" OnClick="del('<?php echo $tickerPreferanceProduct['id'];?>');" id="delete-<?php echo $tickerPreferanceProduct['id'];?>" class="deleteproduct"><i class="fa fa-trash"></i></a>
                                                </td>
                                              </tr>
											<?php 
											}
											}} else {
												echo '<tr><td colspan="5">No records !!</td></tr>';
											}} else { echo "<tr><td>No Products Found..</td></tr>";}
											 ?>
                                              
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
<script>
	function del(id)
	{
		var proid = confirm("Are you really want to delete ?");
		if(proid)
		{
			window.location.href="/deletetickerrecord.php?id="+id;
		}
		else
			return false;
	}
</script>

<?php include ROOT."themes/footer.inc.php"; ?>

</body>
</html>