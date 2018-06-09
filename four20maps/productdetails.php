<?php
session_start();
$header='Products';
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
if($_POST)
{
	$searchattr= $_POST['productName'];
	if(!empty($searchattr))
	{
		$query = "SELECT p.*,p1.categoryName as ParentCatName FROM `ProductCategory` p
			inner join ProductCategory p1 on p.parentID= p1.categoryID
			WHERE p.parentId!=0 AND p.isDeleted=0 and p.categoryName like '%$searchattr%' and p.UserId=".$_SESSION['StoreID'];
			
	}
	else
	{
		$query = "SELECT p.*,p1.categoryName as ParentCatName FROM `ProductCategory` p
		inner join ProductCategory p1 on p.parentID= p1.categoryID
		WHERE p.parentId!=0 AND p.isDeleted=0 and p.UserId=".$_SESSION['StoreID'];
	}
}
else
{
	$query = "SELECT p.*,p1.categoryName as ParentCatName FROM `ProductCategory` p
	inner join ProductCategory p1 on p.parentID= p1.categoryID
	WHERE p.parentId!=0 AND p.isDeleted=0 and p.UserId=".$_SESSION['StoreID'];
}
$catproducts = mysql_query($query);
if($catproducts=='')
	$count ='0';
else
$count = mysql_num_rows($catproducts);
?>

        	<div class="row">
            	<?php include ROOT."admin-left.php"; ?>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 myact-userdet">
                        <h2 class="head-text">Product List <a href="addproduct.php" class="pull-right btn btn-primary addprdbtn btn-sm">Add Product</a></h2>
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
                                                <th>Category Name</th>
                                                <th align="center">Status</th>
                                                <th align="center">Actions</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                            <?php
											if($count>0){
											$i=0;
											if(!empty($catproducts)){
											$count = mysql_num_rows($catproducts);
											if($count>0) {
                                            while($catproduct = mysql_fetch_assoc($catproducts)){ $i++;
											?>
                                              <tr>
                                                <th scope="row"><?php echo $i;?></th>
                                                <td><?php echo $catproduct['categoryName'];?></td>
                                                <td><?php echo $catproduct['ParentCatName'];?></td>
                                                <td>
													<?php if($catproduct['isDeleted']==0){ if($catproduct['isActive']==1){?> <a href="#" class="btn btn-success">Active</a> <?php } else {?><a href="#" class="btn btn-danger">In-Active</a><?php } } ?>
												</td>
                                                <td align="center">
                                                <a href="<?php echo '/addproduct.php?prodid='.$catproduct['categoryID'];?>" ><i class="fa fa-pencil"></i></a> 
                                                <a href="#" OnClick="del('<?php echo $catproduct['categoryID'];?>');" id="delete-<?php echo $catproduct['categoryID'];?>" class="deleteproduct"><i class="fa fa-trash"></i></a>
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
			window.location.href="/deleterecord.php?prodid="+id;
		}
		else
			return false;
	}
</script>

<?php include ROOT."themes/footer.inc.php"; ?>

</body>
</html>