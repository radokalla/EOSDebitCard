<?
session_start();
$header = 'Categories';
$username = $_SESSION["regSuccess"];

if(empty($username)){ ?>
	<script>
		window.location.href = 'addstore.php';
	</script>
<?php }

include_once './includes/config.inc.php';
error_reporting(E_ALL);
if(isset($_REQUEST['status']) )
{
	$query = "UPDATE `ProductCategory` SET `isActive`=".$_REQUEST['status']."  WHERE categoryID=".$_REQUEST['id'];
	mysql_query($query);
	$_SESSION['catUp']="Status updated successfully";
}
if(isset($_POST['categoryName'])) 
{
	$searchkey = $_POST['categoryName'];
	$query = "SELECT * FROM `ProductCategory` WHERE parentId=0 AND isDeleted=0 and categoryName like '%$searchkey%' and UserId=".$_SESSION['StoreID'];
}
else
{
	$query = "SELECT * FROM `ProductCategory` WHERE parentId=0 AND isDeleted=0 AND UserId=".$_SESSION['StoreID'];
}
$categories = mysql_query($query);


include("header.php");
?>
        	<div class="row">
            	<?php include ROOT."admin-left.php"; ?>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 myact-userdet">
                        <h2 class="head-text">Category List <a href="addcategory.php" class="pull-right btn btn-primary addprdbtn btn-sm">Add Category</a></h2>
						<?php if(!empty($_SESSION['catUp'])){ ?>
								<div class="alert alert-success" role="alert" style="text-align:center" id="Catup"><?php echo $_SESSION['catUp']; ?></div>
						<?php } ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<?php $count = mysql_num_rows($categories); if($count>0){ ?>
                                <form class="form-horizontal" name="searchform" id="searchform" method="post" action="">
                                    <div class="table-responsive">
                                          <table class="table table-bordered">
                                            <thead>
                                            </thead>
                                            <tbody>
                                              <tr>
                                                <td><input type="text" name="categoryName" id="categoryName" value="<?php echo ($searchkey!='')? $searchkey :''?>" placeholder="Search By category name" class="form-control"></td>
                                                <td><button type="submit" class="btn btn-primary">Search</button>
                                              </tr>
                                            </tbody>
                                          </table>
                                    </div>
                                </form>
							<?php } ?>
                            </div>
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
                                                <th>Category Name</th>
                                                <th>Status</th>
                                                <th align="center">Actions</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                            <?php
											$i=0;
											if($count>0) {
                                            while($category = mysql_fetch_assoc($categories)){ $i++;
											?>
                                              <tr>
                                                <th scope="row"><?php echo $i;?></th>
                                                <td><?php echo $category['categoryName'];?></td>
                                                <td align="center">
													<?php if($category['isDeleted']==0){ if($category['isActive']==1){?> <a href="categories.php?id=<?=$category['categoryID'];?>&status=0" class="btn btn-success">Active</a> 
													<?php } else {
													?>
													<a href="categories.php?id=<?=$category['categoryID'];?>&status=1" class="btn btn-danger">In-Active</a>
													<?php 
											} } ?>
												</td>
                                                <td align="center">
                                                <a href="<?php echo '/addcategory.php?catid='.$category['categoryID'];?>" ><i class="fa fa-pencil"></i></a> 
                                                <a href="#" OnClick="del('<?php echo $category['categoryID'] ?>');" id="delete-<?php echo $category['categoryID'];?>" class="deleteproduct"><i class="fa fa-trash"></i></a>
                                                </td>
                                              </tr>
                                             <?php
											}
											} else {
												echo '<tr><td colspan="2">No Categories Found!!</td></tr>';
											}
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


<?php include ROOT."themes/footer.inc.php"; ?>

<script>
$(document).ready(function(e) {
    $('#resetform').click(function(){
		$('#categoryName').val('');
		$('#searchform').submit();
	});
});

function del(id)
{
	if(confirm("All the products related to this category \n will be deleted \n do you want to continue ?"))
	{
		window.location.href='/deleterecord.php?catid='+id;
	}
}
<?php if(!empty($_SESSION['catUp'])){ unset($_SESSION['catUp']); ?>
	setTimeout(function (){ $('#Catup').hide(); }, 4000);
<?php } ?>
</script>
</body>
</html>