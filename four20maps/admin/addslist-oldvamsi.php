<?php
ob_start();
include_once './includes/config.inc.php';

// Authenticate user login

//auth();
/*echo "<pre>";
print_r($_POST);exit;*/
error_reporting(0);
$db = db_connect();
	if(isset($_POST['save']))
	{
		/*if($_GET['status']=='InActive')
		{*/
			$stdate=$_POST['stdate'];
			$Enddate = $_POST['Enddate'];
			$timestamp = strtotime($stdate);
			 $stdate = date("Y-m-d", $timestamp);
			 
			 $timestamp = strtotime($Enddate);
			 $Enddate = date("Y-m-d", $timestamp);
			$statuss= $_POST['statuss'];
			$idd = $_POST['iddd'];
			$last_modified = date('Y-m-d H:i:s',time());
		/*$sratrtdate = date("Y-m-d");
		
		$enddate = date("Y-m-d",strtotime("+7 day"));
			
		$aid=$_GET['aid'];
		*/
		$selquery="Update adds set start_date='".$stdate."',end_date='".$Enddate."', last_modified='$last_modified',status='".$statuss."' where aid=".$idd;
		mysql_query($selquery);
		
				/*$m=$db->dateRange($sratrtdate,$enddate);
		echo "<pre>";
		print_r($m); exit;*/
		
		$msg =  "Successfully ".$statuss;
		/* }
		else{
		$aid=$_GET['aid'];
		$qry ="UPDATE adds SET status = 'InActive' WHERE aid=".$aid;
		mysql_query($qry);
		$msg = "Successfully InActivated";			
		}*/
		
	
		
	}
	if($_REQUEST['deleteAll'])
	{
		mysql_query("Truncate table adds");
		die;
	}
$todays_date = date("Y-m-d");
$expire_statusquery="select aid,status,start_date,end_date from adds where status='Active' and is_delete=0";
$qr_exc=mysql_query($expire_statusquery);

while($fetchr=mysql_fetch_assoc($qr_exc))
{
	$dateperioud[]=$fetchr;
}
$todays_date = date("Y-m-d");
foreach($dateperioud as $cnt)
{
	$aidm=$cnt['aid'];
	$end_ate=$cnt['end_date'];
		
	
	$today= strtotime($todays_date);
$expiration_date = strtotime($end_ate);

if($expiration_date < $today) {
	
	$qry ="UPDATE adds SET status = 'InActive' WHERE aid=".$aidm;

}	
}



if(isset($_GET['action']) && $_GET['action']=='delete') {


	//if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		if(isset($_GET['id'])) {
		
		$id=$_GET['id'];
		$query="update adds set is_delete='1' where aid=".$id;
		mysql_query($query);
		
	
		

		$_SESSION['notification'] = array('type'=>'good','msg'=>"Success");

		redirect(ROOT_URL.'addslist.php');

	}





	$db = db_connect();
	

	if($db->delete('adds', $_GET['aid'])) {
		/*$id=$_GET['aid'];
		$query="update adds set is_delete='1' where aid=$id";
		echo "<pre>";echo $query;exit;
		*/
		

		$_SESSION['notification'] = array('type'=>'good','msg'=>$lang['ADD_USER_DELETED']);

	} else {

		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['ADMIN_DELETE_USER_FAILED']);

	}

redirect(ROOT_URL.'addslist.php');

}





$db = db_connect();


if(intval($_GET['perpage'])>0)
$limit = intval($_GET['perpage']); 
else
$limit = 10; 



if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  

$start_from = ($page-1) * $limit;  


$useradd = $db->get_rows("SELECT adds.* FROM adds where is_delete=0 ORDER BY aid desc LIMIT $start_from, $limit");
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
            <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Store List</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
        <h5><?php echo $msg; ?></h5>
          <!-- Small boxes (Stat box) -->
          <div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
            	<div class="box">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['ADMIN_ADD_ADDS']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div id="example1_filter">
                        <a href="#" data-toggle="modal" data-target="#myModal4" class="btn btn-primary"><?php echo $lang['USERADD_ADDNEW']; ?></a>
			<span id='imgloading'></span>



                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="dataTables_length" id="example1_length">
                            <a style="float:right" id="deleteAllAds" class="btn btn-primary">Delete All</a>
                            	
                            </div>
                        </div>
                        <?php echo notification(); ?>
                    </div>
                  <div class="row">
                      <div class="col-lg-12 col-sm-12 col-xs-12">
                          <table id="example1" class="table table-bordered table-striped">
                            <thead>
                              <tr>

					<th><?php echo $lang['USERADD_NAME']; ?></th><th><?php echo $lang['USERADD_EMAIL']; ?></th><th><?php echo $lang['USERADD_PHONENUMBER']; ?></th><th><?php echo $lang['USERADD_IMAGE']; ?></th><th><?php echo $lang['USERADD_URL']; ?></th><th><?php echo $lang['USERADD_STATUS']; ?></th><th class="actions"><?php echo $lang['ADMIN_ACTION']; ?></th>

				</tr>
                            </thead>
                            <tbody>
                          <?php if(!empty($useradd)): ?>

					<?php foreach($useradd as $k=>$v): ?>
                              <tr class='<?php echo ($k%2==0) ? 'odd':'even'; ?>'>

						<td><?php echo $v['Name']; ?></td>

						<td><?php echo $v['Email']; ?></td>
						<td><?php echo $v['PhoneNumber']; ?></td>
						<td><img src="../admin/uploads/<?php echo $v['image']; ?>" style="height:100px;width:115px"></td>
						<td><?php echo $v['url']; ?></td>
						<td>
						
						<?php /*<a href="./addslist.php?status=<?php echo $v['status']; ?>&aid=<?php echo $v['aid'];?>" data-toggle="modal" data-target="#statusmd"><?php echo $v['status']; ?></a> */?>
						<a href='javascript:delItem(<?php echo $v['aid']; ?>)' onclick="ajstatus(<?php echo $v['aid']; ?>);" data-toggle="modal" data-target="#exampleModal"><?php echo $v['status']; ?></a>
						</td>

						<td class="actions">

							<a href='./addadds.php?id=<?php echo $v['aid']; ?>'><i class="fa fa-pencil"></i></a>

							<a href='javascript:delItem(<?php echo $v['aid']; ?>)' class="confirm_delete"><i class="fa fa-trash"></i></a>

							

						</td>

					</tr>
                             <?php endforeach; ?>

				<?php else: ?>

					<tr>

						<td colspan="7">No advertisements</td>

					</tr>

				<?php endif; ?>
                            </tbody>
                          </table>
                          <div class="row">
                          <div class="col-lg-6 col-sm-6 col-xs-12">
                          </div>
                              <div class="col-lg-6 col-sm-6 col-xs-12">
                              <div class="dataTables_paginate paging_simple_numbers">
							 <?php  

			$sql = "SELECT COUNT(aid) FROM adds";  

			$rs_result = mysql_query($sql);  

			$row = mysql_fetch_row($rs_result);  

			$total_records = $row[0];  

			$total_pages = ceil($total_records / $limit);  

			$active = "";


			
			$pagLink = "<div><ul class='pagination'>";  

			for ($i=1; $i<=$total_pages; $i++) { 

					if(isset($_GET['page'])){

						if($i==$_GET["page"]){

						  $active="active";

						} else {

						   $active="";

						}

					}

						 $pagLink .= "<li class='paginate_button $active'><a href='addslist.php?page=".$i."&perpage=$limit'>".$i."</a></li>";  

			};  
			$l10='';
			$l20='';
			$l50='';
			if($limit==10)
				$l10='selected';
			else if($limit==20)	
				$l20='selected';
			else if($limit==50)	
				$l50='selected';
			$pp ="<select onChange='changePP(this.value)'><option $l10>10</option><option $l20>20</option><option $l50>50</option></select>";
			echo "<span>No of Records Per Page".$pp."</span>".$pagLink . "</ul></div>";  

		?>  
			
                             </div>
                             </div>
        				</div>
                      </div>
                  </div></div>
                </div><!-- /.box-body -->
              </div>
            </div>

          </div><!-- /.row -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <script>

	function delItem(id){



	var a = confirm("<?php echo $lang['ADMIN_DELETE_CONFIRM']; ?>");

		if(a){

		document.location.href='?action=delete&id='+id+'&search=<?php if(isset($_REQUEST['search'])) { echo $_REQUEST['search']; } ?><?php if(isset($_REQUEST['page'])) { echo "&page=".$_REQUEST['page']; } ?><?php if(isset($_REQUEST['sort'])) { echo "&sort=".$_REQUEST['sort']; } ?><?php if(isset($_REQUEST['filter'])) { echo "&filter=".$_REQUEST['filter']; } ?>';

		}

	}

	</script>
      <?php include("footer.php"); ?>
