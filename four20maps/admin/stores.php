<?php

$heading='stores';
include_once './includes/config.inc.php';
include_once './includes/validate.php';
auth();
$pg='';

if(isset($_REQUEST["generate_url"]) && $_REQUEST["generate_url"]=="yes"){	
		
		require_once('includes/Googl.class.php');

$googl = new Googl('AIzaSyA1kV7OTLBQNr517kIPzhR1GhodYn6WtGc');

// Shorten URL
$short_url=$googl->shorten('http://www.four20maps.com/store/'.$_GET['store_name'].'/zip/'.$_GET[ 'zipcode']);

 
unset($googl);
	$db = db_connect();
	$db->update( 'stores', array( 'short_url' => $short_url ), $_GET[ 'store_id']);
	header("location:stores.php");
}
if (isset($_REQUEST["page"])) { $page  = $_REQUEST["page"]; $pg='&page='.$page ; } else { $page=1;  };  
if (isset($_REQUEST["sort"])) {  $st='&sort='.$_REQUEST["sort"] ; } else { $st='';  };  
if (isset($_REQUEST["filter"])) { $fl='&filter='.$_REQUEST["filter"] ; } else { $fl='';  };  
if(isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == 'deleteAll')
{
	$db = db_connect();
	 
	if(empty($_GET["all_id"]) && empty($_GET["searchkey"]))
	$db->updateAll( 'stores', array( 'approved' => 0 ));
	else if(!empty($_GET["all_id"]) && empty($_GET["searchkey"]))
	$db->update( 'stores', array( 'approved' => 0 ),$_GET["all_id"]);
	else  if(empty($_GET["all_id"]) && !empty($_GET["searchkey"]))
	$db->updateAllByKeyword( 'stores', array( 'approved' => 0 ),$_GET["searchkey"]);	 
	updatejson();	
	 redirect( ROOT_URL . 'stores.php' );
}
if(isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == 'activeAll')
{
	$db = db_connect();
	
	if(empty($_GET["all_id"]) && empty($_GET["searchkey"]))
	{
		$db->udpdateByCondtion( 'stores','approved',1,'is_licensed',1);
	}
	
	else if(!empty($_GET["all_id"]) && empty($_GET["searchkey"]))
	$db->update( 'stores', array( 'approved' => 1 ),$_GET["all_id"]);
	else  if(empty($_GET["all_id"]) && !empty($_GET["searchkey"]))
	$db->updateAllByKeyword( 'stores', array( 'approved' => 1 ),$_GET["searchkey"]);
	updatejson();	
	 
	 redirect( ROOT_URL . 'stores.php' );
}

if(isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == 'deActiveLicenseAll')
{
	$db = db_connect();
	 
	if(empty($_GET["all_id"]) && empty($_GET["searchkey"]))
	$db->updateAll( 'stores', array( 'is_licensed' => 0 ));
	else if(!empty($_GET["all_id"]))
	$db->update( 'stores', array( 'is_licensed' => 0 ),$_GET["all_id"]);
	else  if(empty($_GET["all_id"]) && !empty($_GET["searchkey"]))
	$db->updateAllByKeyword( 'stores', array( 'is_licensed' => 0 ),$_GET["searchkey"]);	 
	updatejson();	
	 redirect( ROOT_URL . 'stores.php' );
}
if(isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == 'activeLicenseAll')
{
	$db = db_connect();
	
	/*if(empty($_GET["all_id"]) && empty($_GET["searchkey"]))
	//$db->updateAll( 'stores', array( 'is_licensed' => 1 ));
	else */
	if(!empty($_GET["all_id"]))
	$db->update( 'stores', array( 'is_licensed' => 1 ),$_GET["all_id"]);
	else  if(empty($_GET["all_id"]) && !empty($_GET["searchkey"]))
	$db->updateAllByKeyword( 'stores', array( 'is_licensed' => 1 ),$_GET["searchkey"]);
	else 
	alert("Please select stores to turn on license");
	updatejson();	
	 
	 redirect( ROOT_URL . 'stores.php' );
}





if ( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == 'featured' ) {
	if ( !isset( $_GET[ 'id' ] ) || !is_numeric( $_GET[ 'id' ] ) ) {
		$_SESSION[ 'notification' ] = array( 'type' => 'bad', 'msg' => $lang[ 'ADMIN_INVALID_ID' ] );
		redirect( ROOT_URL . 'stores.php?search=' . $_REQUEST[ 'search' ] . $pg . $st . $fl );
	}
	$db = db_connect();
	if ( $db->update( 'stores', array( 'featured' => 1 ), $_GET[ 'id' ] ) ) {
		$_SESSION[ 'notification' ] = array( 'type' => 'good', 'msg' => 'Store Successfully Featured' );
	} else {
		$_SESSION[ 'notification' ] = array( 'type' => 'bad', 'msg' => $lang[ 'ADMIN_APPROVE_FAILED' ] );
	}
	redirect( ROOT_URL . 'stores.php?search=' . $_REQUEST[ 'search' ] . $pg . $st . $fl );
}

if ( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == 'unfeatured' ) {
	if ( !isset( $_GET[ 'id' ] ) || !is_numeric( $_GET[ 'id' ] ) ) {
		$_SESSION[ 'notification' ] = array( 'type' => 'bad', 'msg' => $lang[ 'ADMIN_INVALID_ID' ] );
		redirect( ROOT_URL . 'stores.php?search=' . $_REQUEST[ 'search' ] . $pg . $st . $fl );
	}
	$db = db_connect();
	if ( $db->update( 'stores', array( 'featured' => 0 ), $_GET[ 'id' ] ) ) {
		$_SESSION[ 'notification' ] = array( 'type' => 'good', 'msg' => 'Store Successfully Unfeatured' );
	} else {
		$_SESSION[ 'notification' ] = array( 'type' => 'bad', 'msg' => $lang[ 'ADMIN_APPROVE_FAILED' ] );
	}
	redirect( ROOT_URL . 'stores.php?search=' . $_REQUEST[ 'search' ] . $pg . $st . $fl );
}


if(isset($_GET['action']) && $_GET['action']=='approve') {
	if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['ADMIN_INVALID_ID']);
		redirect(ROOT_URL.'stores.php?search='.$_REQUEST['search'].$pg.$st.$fl);
	}
	$db = db_connect();
	if($db->update('stores',array('approved'=>1),$_GET['id'])) {
		updatejson();	
		$_SESSION['notification'] = array('type'=>'good','msg'=>'Store Successfully Approved');
	} else {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['ADMIN_APPROVE_FAILED']);
	}
	redirect(ROOT_URL.'stores.php?search='.$_REQUEST['search'].$pg.$st.$fl);
}
if(isset($_GET['action']) && $_GET['action']=='disapprove') {
	if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['ADMIN_INVALID_ID']);
		redirect(ROOT_URL.'stores.php?search='.$_REQUEST['search'].$pg.$st.$fl);
	}
	$db = db_connect();
	if($db->update('stores',array('approved'=>0),$_GET['id'])) {
		$_SESSION['notification'] = array('type'=>'good','msg'=>"Record Updated Successfully");
	} else {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>"Something went wrong..!! Error while updating records");
	}
	redirect(ROOT_URL.'stores.php?search='.$_REQUEST['search'].$pg.$st.$fl);
}
if(isset($_GET['action']) && $_GET['action']=='delete') {
	if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['ADMIN_INVALID_ID']);
		redirect(ROOT_URL.'stores.php?search='.$_REQUEST['search'].$pg.$st.$fl);
	}
	$db = db_connect();
	$storeDetails = $db->get_row("SELECT * FROM stores WHERE id=".$_GET['id']);

	if($db->delete('stores',$_GET['id'])) {
		mysql_query('update StoreUserSubscription set status=0 where StoreUserSubscriptionId='.$storeDetails['StoreUserSubscriptionId']);
	    $db->delete('StoreUserSubscription',$storeDetails['StoreUserSubscriptionId'],'StoreUserSubscriptionId');
		updatejson();
		$id=$_GET['id'];
		$query="update adds set is_delete='1' where id=$id";
		//echo "<pre>";echo $query;exit;
		$_SESSION['msg'] = "Store Deleted Successfully";
	} else {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['ADMIN_DELETE_STORE_FAILED']);
	}
	
	redirect(ROOT_URL.'stores.php?search='.$_REQUEST['search'].$pg.$st.$fl);
}

$db = db_connect();
$limit = 20; 
$stype = "ASC";
$str = "";
if(!isset($_REQUEST['stype1'])){
	$stype1 = "ASC";
} else {
	$stype1 = $_REQUEST['stype1'];
	$stype = $stype1;
	$str = "&stype1=$stype1";
}
if(!isset($_REQUEST['stype2'])){
	$stype2 = "ASC";
} else {
	$stype2 = $_REQUEST['stype2'];
	$stype = $stype2;
	$str = "&stype2=$stype2";
}
if(!isset($_REQUEST['stype3'])){
	$stype3 = "ASC";
} else {
	$stype3 = $_REQUEST['stype3'];
	$stype = $stype3;
	$str = "&stype3=$stype3";
}
$start_from = ($page-1) * $limit; 
if(isset($_REQUEST['filter']))
{
	$_SESSION['filter']=$_REQUEST['filter'];
}
if(isset($_REQUEST['search']))
{
	$_SESSION['search']=$_REQUEST['search'];
}
$storefilter = "";
if(isset($_SESSION['search'])){
	if(isset($_SESSION['filter'])){
		$filter = $_SESSION['filter'];
	}
	else {
		$filter = '';
	}
	if($filter=="fname"){
		$storefilter = " AND (Lower(stores.name) LIKE '%".strtolower($_SESSION['search'])."%')";
	} else if($filter=="faddress"){
		$storefilter = " AND (Lower(stores.address) LIKE '%".strtolower($_SESSION['search'])."%')";
	} else if($filter=="ftel"){
		$storefilter = " AND (Lower(stores.telephone) LIKE '".strtolower($_SESSION['search'])."%')";
	} else if($filter=="femail"){
		$storefilter = " AND (Lower(stores.email) LIKE '".strtolower($_SESSION['search'])."%')";
	} else if($filter=="fwebsite"){
		$storefilter = " AND (Lower(stores.website) LIKE '".strtolower($_SESSION['search'])."%')";
	}

  //$storefilter = " AND (stores.name LIKE '%".$_REQUEST['search']."%' OR stores.address LIKE '%".$_REQUEST['search']."%' OR stores.website LIKE '%".$_REQUEST['search']."%' OR stores.name LIKE '%".$_REQUEST['search']."%' OR stores.telephone LIKE '%".$_REQUEST['search']."%')";

}
$sort = "name";
if(isset($_REQUEST['sort'])){
 $sort = $_REQUEST['sort'];
}
 $storefilter .= "ORDER BY stores.id DESC" ;

mysql_query("SET NAMES utf8"); 
//echo "SELECT stores.* FROM stores WHERE stores.id!=0 $storefilter LIMIT $start_from, $limit";
$stores = $db->get_rows("SELECT stores.* FROM stores WHERE stores.id!=0 $storefilter LIMIT $start_from, $limit");
?>
	  <?php include("header.php"); ?>
      <!-- Left side column. contains the logo and sidebar -->
      <?php include("sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Store List</h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Store List</li>
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
					<?php $notifications =  notification(); if(!empty($notifications)){ ?>
						<div class="alert alert-success" style="text-align:center;padding:0 important" id="alert" > <?php echo $notifications; ?> </div>
					<?php } ?>
					<?php if(!empty($_SESSION['msg'])){ ?>
						<div class="alert alert-warning" id="alert" style="text-align:center" ><?php echo $_SESSION['msg'];  ?></div> 
					<?php } ?>
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <div id="example1_filter" class="str-wdt-alg">
                            	<form method="POST" action="stores.php">

<input type="text" class="form-control" placeholder="Search" value="<?php if(isset($_SESSION['search'])) { echo $_SESSION['search']; } ?>" name="search" id="search">

<select id="filter" name="filter" class="form-control">

<option value="fname" <?php if(isset($_SESSION['filter'])){ if($_SESSION['filter']=="fname"){ ?>selected<?php } } ?>>Store Name</option>

<option value="faddress" <?php if(isset($_SESSION['filter'])){ if($_SESSION['filter']=="faddress"){ ?>selected<?php } } ?>>Address</option>

<option value="ftel" <?php if(isset($_SESSION['filter'])){ if($_SESSION['filter']=="ftel"){ ?>selected<?php } } ?>>Telephone</option>

<option value="femail" <?php if(isset($_SESSION['filter'])){ if($_REQUEST['filter']=="femail"){ ?>selected<?php } } ?>>Email</option>

<option value="fwebsite" <?php if(isset($_SESSION['filter'])){ if($_SESSION['filter']=="fwebsite"){ ?>selected<?php } } ?>>Website</option>

</select>

 <!--<div class="icon-search"></div>--><input type=submit class="btn btn-primary" value="<?php echo $lang['FIND_STORE']; ?>" />

</form>


<?php



$sql = "SELECT COUNT(id) FROM stores WHERE status=1 $storefilter";  

			$rs_result = mysql_query($sql);  

			$row = mysql_fetch_row($rs_result);  

			$total_records = $row[0];  

			$total_pages = ceil($total_records / $limit);  

			$active = "";
			$pvactive = 0;
			$dotvalue = "";
			 



			$pagLink = "<div><ul class='pagination'>";  
			if(isset($_REQUEST['search'])) 
				$rf = "&search=".$_REQUEST['search']."&filter=".$filter; 
			
			if($_GET["page"])
					$page = $_GET["page"];
			else
				$page=1;
			
			if($total_pages>1)
				$pagLink .= "<li class='paginate_button $active'><a href='stores.php?sort=$sort".$str."&page=1".$rf."'>First</a></li> ";
			if($total_pages>1 && $page>1)
				$pagLink .= "<li class='paginate_button $active'><a href='stores.php?sort=$sort".$str."&page=".($page-1).$rf."'>Prev</a></li> ";
			
			
			if($page<=10)
			{
				$start =1;
				if($total_pages<=10)
					$end = $total_pages;
				else
					$end=10;
			}
			else
			{
				$start = $page - 5;
				if($total_pages<= ($page + 5))
					$end = $total_pages;
				else
					$end=$page + 5;
			}
			for ($i=$start; $i<=$end; $i++) 
			{
				if($i==$page)
				  $active="active";
				else
					$active='';
				 
				$pagLink .= "<li class='paginate_button $active'><a href='stores.php?sort=$sort".$str."&page=".$i.$rf."'>".$i."</a></li> ".$dotvalue ; 
			}
			if($total_pages>1 && $total_pages>$end)
				$pagLink .= "<li class='paginate_button'><a href='stores.php?sort=$sort".$str."&page=".($page+1).$rf."'>Next</a></li> ";
			if($total_pages>1)
				$pagLink .= "<li class='paginate_button'><a href='stores.php?sort=$sort".$str."&page=".$total_pages.$rf."'>Last</a></li> ";
?>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <div class="dataTables_length" id="example1_length">
                            	<a style="float:right" href="stores_add.php" class="btn btn-primary"><?php echo $lang['ADMIN_ADD_STORE']; ?></a>
                            </div>
                            </div>
                           <div class="col-lg-12 col-sm-12 col-xs-12">    
                           	 <div  class="dataTables_length" id="example1_length">
                            	<a style="float:right;margin-right: 10px" href="javascript:void(0);" class="btn btn-success" onClick="ActiveLicenseStores();">Turn on License</a>
                            </div>
                             <div class="dataTables_length" id="example1_length">
                            	<a style="float:right;margin-right: 10px" href="javascript:void(0);" class="btn btn-danger" onClick="DeActiveLicenseStores();">Turn off License</a>
                            </div>
                      
                              <div class="dataTables_length" id="example1_length">
                            	<a style="float:right;margin-right: 10px" href="javascript:void(0);" class="btn btn-danger" onClick="inActiveStores();">TURN OFF ALL STORES</a>
                            </div>
                             <div class="dataTables_length" id="example1_length">
                            	<a style="float:right;margin-right: 10px" href="javascript:void(0);" class="btn btn-success" onClick="ActiveStores();">TURN ON ALL LICENSE STORES</a>
                            </div>
                        </div>
                    </div>
                  <div class="row">
                      <div class="col-lg-12 col-sm-12 col-xs-12">
                          <div class="table-responsive"><table id="example1" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                               <th><input type="checkbox" id="checkall" name="checkall" value="CheckAll"></th>
                               <th class="actions"><?php echo $lang['ADMIN_ACTION']; ?></th>
                                <th><?php echo $lang['ADMIN_NAME']; ?> 



<?php if($stype1=="ASC"){ ?>

<a href="stores.php?&search=<?php if(isset($_REQUEST['search'])){ echo $_REQUEST['search']; } ?>&sort=name&stype1=DESC&page=<?php echo $page.$rf; ?>"><div class="icon-circle-arrow-up"></div></a>

<? } else { ?>

<a href="stores.php?&search=<?php if(isset($_REQUEST['search'])){ echo $_REQUEST['search']; } ?>&sort=name&stype1=ASC&page=<?php echo $page.$rf; ?>"><div class="icon-circle-arrow-down"></div></a>

<?php } ?>



</th>
                                <th><?php echo $lang['ADMIN_ADDRESS']; ?>





<?php if($stype2=="ASC"){ ?>

<a href="stores.php?&search=<?php if(isset($_REQUEST['search'])){ echo $_REQUEST['search']; } ?>&sort=address&stype2=DESC&page=<?php echo $page.$rf; ?>"><div class="icon-circle-arrow-up">

<? } else { ?>

<a href="stores.php?&search=<?php if(isset($_REQUEST['search'])){ echo $_REQUEST['search']; } ?>&sort=address&stype2=ASC&page=<?php echo $page.$rf; ?>"><div class="icon-circle-arrow-down">

<?php } ?></th>
                               <th>Zipcode</th>
                                <th><?php echo $lang['ADMIN_TELEPHONE']; ?></th>

<th><?php echo $lang['ADMINISTRATOR_EMAIL']; ?></th>

<th><?php echo $lang['ADMIN_WEBSITE']; ?>



<?php if($stype3=="ASC"){ ?>

<a href="stores.php?&search=<?php if(isset($_REQUEST['search'])){ echo $_REQUEST['search']; } ?>&sort=website&stype3=DESC&page=<?php echo $page.$rf; ?>"><div class="icon-circle-arrow-up">

<? } else { ?>

<a href="stores.php?&search=<?php if(isset($_REQUEST['search'])){ echo $_REQUEST['search']; } ?>&sort=website&stype3=ASC&page=<?php echo $page.$rf; ?>"><div class="icon-circle-arrow-down">

<?php } ?>



</th>
	

	
<th class="acenter">Short Url</th>
<th class="acenter">License</th>
<th class="acenter">Approve</th>
<th class="acenter">Featured</th>

                              </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($stores)): ?>

					<?php foreach($stores as $k=>$v): ?>
                              <tr class='<?php echo ($k%2==0) ? 'odd':'even'; ?>'>
                        <td><input type="checkbox" id="" name="checkall[]" value="<?=$v['id'];?>" class="checkAllClass"></td>
                        <td class="actions">

							<a href='./stores_edit.php?id=<?php echo $v['id']; ?>'><i class="fa fa-pencil"></i></a>

							<a href='javascript:delItem(<?php echo $v['id']; ?>)' class="confirm_delete"><i class="fa fa-trash"></i></a>

							<?php if(!$v['approved']) {?>

							<a href='?action=approve&amp;&id=<?php echo $v['id']; ?>&search=<?php if(isset($_REQUEST['search'])) { echo $_REQUEST['search']; } ?><?php if(isset($_REQUEST['page'])) { echo "&page=".$_REQUEST['page']; } ?><?php if(isset($_REQUEST['sort'])) { echo "&sort=".$_REQUEST['sort']; } ?><?php if(isset($_REQUEST['filter'])) { echo "&filter=".$_REQUEST['filter']; } ?>'><i class="fa fa-check"></i></a>

							<?php }
							else{?>
							
								<a href='?action=disapprove&amp;&id=<?php echo $v['id']; ?>&search=<?php if(isset($_REQUEST['search'])) { echo $_REQUEST['search']; } ?><?php if(isset($_REQUEST['page'])) { echo "&page=".$_REQUEST['page']; } ?><?php if(isset($_REQUEST['sort'])) { echo "&sort=".$_REQUEST['sort']; } ?><?php if(isset($_REQUEST['filter'])) { echo "&filter=".$_REQUEST['filter']; } ?>'><i class="fa fa-times"></i></a>
							<?php }?>
							
							<?php if(!$v['featured']) {?>

							<a href='?action=featured&amp;&id=<?php echo $v['id']; ?>&search=<?php if(isset($_REQUEST['search'])) { echo $_REQUEST['search']; } ?><?php if(isset($_REQUEST['page'])) { echo "&page=".$_REQUEST['page']; } ?><?php if(isset($_REQUEST['sort'])) { echo "&sort=".$_REQUEST['sort']; } ?><?php if(isset($_REQUEST['filter'])) { echo "&filter=".$_REQUEST['filter']; } ?>'><i class="fa fa-check"></i></a>

							<?php }
							else{?>
							
								<a href='?action=unfeatured&amp;&id=<?php echo $v['id']; ?>&search=<?php if(isset($_REQUEST['search'])) { echo $_REQUEST['search']; } ?><?php if(isset($_REQUEST['page'])) { echo "&page=".$_REQUEST['page']; } ?><?php if(isset($_REQUEST['sort'])) { echo "&sort=".$_REQUEST['sort']; } ?><?php if(isset($_REQUEST['filter'])) { echo "&filter=".$_REQUEST['filter']; } ?>'><i class="fa fa-times"></i></a>
							<?php }?>

						</td>
						<td><?php echo $v['name']; ?></td>

						<td><?php echo $v['address']; ?></td>

						<td><?php echo $v['zipcode']; ?></td>

						<td><?php if(empty($v['telephone'])){ echo $v['mobile'];} else { echo $v['telephone']; }  ?></td>

						<td><?php echo $v['email']; ?></td>

						<td><?php echo $v['website']; ?></td>
                        <td class="acenter"><?php if(isset($v['short_url']) && !empty($v['short_url'])) { 
?> <a href="<?php echo $v['short_url']; ?>" target="_blank">
							<?php echo $v['short_url']; ?></a> <?php }
							else if(!empty($v['zipcode'])) {
							$store_name=str_replace(" ","_",strtolower($v['name'])); ?>
                        <a href="stores.php?generate_url=yes&store_id=<?php echo $v['id'];?>&store_name=<?php echo $store_name;?>&zipcode=<?php echo $v['zipcode'];?>">Generate Url</a>  <?php }else{?>
                        ---
                        <?php }?>
                        </td>  
                        <td class="acenter"><?php echo ($v['is_licensed']) ? 'Yes' : 'No' ; ?></td>

						<td class="acenter"><?php echo ($v['approved']) ? 'Yes' : 'No' ; ?></td>

						<td class="acenter"><?php echo ($v['featured']) ? 'Yes' : 'No' ; ?></td>
						

					</tr>
                              <?php endforeach; ?>

				<?php else: ?>

					<tr>

						<td colspan="7"><?php echo $lang['ADMIN_NO_STORES']; ?></td>

					</tr>

				<?php endif; ?>
                            </tbody>
                          </table></div>
                          <div class="row">
                              <div class="col-lg-12 col-sm-12 col-xs-12">
                              <div class="dataTables_paginate paging_simple_numbers">
							  <?php  
							if($total_records > 10)
							{						  
                				echo $pagLink . "</ul></div>";  
							}	
            				?> 				
                             </div>
                             </div>
        				</div>
                      </div>
                  </div></div>
                </div><!-- /.box-body -->
              </div>
            </div>
<input type="hidden" value="" name="allcheckval" id="allcheckval" />
          </div><!-- /.row -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <script>
$("#checkall").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});

$('input:checkbox').change(function(){
var output = jQuery.map($(':checkbox[name=checkall\\[\\]]:checked'), function (n, i) {
    return n.value;
}).join(',');
$("#allcheckval").val(output);
})


function ActiveLicenseStores(){
var a = confirm("Its turn On Licenses for all selected stores");
	if(a)
	{
		document.location.href='?action=activeLicenseAll&all_id='+$("#allcheckval").val()+'&searchkey='+$("#search").val();
	}
}

function DeActiveLicenseStores(){
var a = confirm("Its turn off Licenses for all selected stores");
	if(a)
	{
		document.location.href='?action=deActiveLicenseAll&all_id='+$("#allcheckval").val()+'&searchkey='+$("#search").val();
	}
}
		  
		  
function ActiveStores(){
var a = confirm("Its turn on all stores in map");
	if(a)
	{
		document.location.href='?action=activeAll&all_id='+$("#allcheckval").val()+'&searchkey='+$("#search").val();
	}
}

function inActiveStores(){
var a = confirm("Its turn off all stores in map");
	if(a)
	{
		document.location.href='?action=deleteAll&all_id='+$("#allcheckval").val()+'&searchkey='+$("#search").val();
	}
}
	function delItem(id){



	var a = confirm("<?php echo $lang['ADMIN_DELETE_CONFIRM']; ?>");

		if(a){

		document.location.href='?action=delete&id='+id+'&search=<?php if(isset($_REQUEST['search'])) { echo $_REQUEST['search']; } ?><?php if(isset($_REQUEST['page'])) { echo "&page=".$_REQUEST['page']; } ?><?php if(isset($_REQUEST['sort'])) { echo "&sort=".$_REQUEST['sort']; } ?><?php if(isset($_REQUEST['filter'])) { echo "&filter=".$_REQUEST['filter']; } ?>';

		}

	}
	<?php  if(!empty($notifications) || !empty($_SESSION['msg'])){ ?>
		$(document).ready(function (){
			<?php if(!empty($notifications)) ?>
			$('#alert').css('padding','0');
			setTimeout(function (){ $('#alert').hide(); }, 4000);
		});
	<?php unset($_SESSION['msg']); } ?>

	</script>
      <?php include("footer.php"); ?>
