<?php
// include Config File
include_once './includes/config.inc.php';
// Authenticate user login
//auth();
if(isset($_GET['action']) && $_GET['action']=='approve') {
	if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['ADMIN_INVALID_ID']);
		redirect(ROOT_URL.'categories.php');
	}
	$db = db_connect();
	if($db->update('categories',array('approved'=>1),$_GET['id'])) {
		$_SESSION['notification'] = array('type'=>'good','msg'=>$lang['ADMIN_STORE_APPROVED']);
	} else {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['ADMIN_APPROVE_FAILED']);
	}
redirect(ROOT_URL.'categories.php');
}


if(isset($_GET['action']) && $_GET['action']=='delete') {
	
	if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['ADMIN_INVALID_ID']);
		redirect(ROOT_URL.'categories.php');
	}

	
	$db = db_connect();
	if($db->delete('categories', $_GET['id'])) {
		$_SESSION['notification'] = array('type'=>'good','msg'=>$lang['SSF_ADMIN_CAT_DELETED']);
	} else {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['SSF_ADMIN_CAT_DELETE_FAILED']);
	}
redirect(ROOT_URL.'categories.php');
}


$db = db_connect();

$limit = 20; 

if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
$start_from = ($page-1) * $limit;  

mysql_query("SET NAMES utf8"); 
$cats = $db->get_rows("SELECT categories.* FROM categories WHERE categories.id!='' ORDER BY categories.cat_name ASC LIMIT $start_from, $limit");
?>
	  <?php include("header.php"); ?>
      <!-- Left side column. contains the logo and sidebar -->
      <?php include("sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1><?php echo $lang['SSF_CATEGORY_LIST']; ?></h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Store List</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
        <?php echo notification(); ?>
          <!-- Small boxes (Stat box) -->
          <div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
            	<div class="box">
                <div class="box-header">
                  <h3 class="box-title">Store List</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                           
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="dataTables_length" id="example1_length">
                            	<a href="category_add.php" style="float:right" class="btn btn-primary"><?php echo $lang['SSF_ADD_CATEGORY']; ?></a>
                            </div>
                        </div>
                    </div>
                  <div class="row">
                      <div class="col-lg-12 col-sm-12 col-xs-12">
                          <table id="example1" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th><?php echo $lang['SSF_CATEGORY_NAME']; ?></th>
                                <th title="Icon you usually found on the map">Map Icon</th>
								<th title="Icon you usually found on the map icon info window"><?php echo $lang['SSF_CATEGORY_ICON']; ?></th>
								<th class="actions"><?php echo $lang['ADMIN_ACTION']; ?></th>
                              </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($cats)): ?>
					<?php foreach($cats as $k=>$v): ?>
                              <tr class='<?php echo ($k%2==0) ? 'odd':'even'; ?>'>

						<td><?php echo $v['cat_name']; ?></td>
						<td><img src="<?php echo $v['cat_icon']; ?>" style="max-height:30px;max-width:30px; height:26px"></td>						
						<td>
					<?php $upload_dir = ROOT.'imgs/categories/'.$v['id'].'/';

							    $images = array();
								if(is_dir($upload_dir)) {

									$images = get_files($upload_dir);
									foreach($images as $a=>$b) {
										$images[$a] = ROOT_URL.'imgs/categories/'.$v['id'].'/'.$b;
									}
							    }
							
					if(!empty($images)): ?>
					<div class="input">
						<?php foreach($images as $c=>$d): ?>
						<div class="image">
							<img src="<?php echo $d; ?>" alt="Image" style="max-width:30px; max-height:30px;"/>
						</div>
						<?php endforeach; ?>
					</div>
					<?php else: ?>
					<?php echo $lang['SSF_CATEGORY_NO_ICON']; ?>
					<?php endif; ?>
					</td>
					<td class="actions">
							<a href='./category_edit.php?id=<?php echo $v['id']; ?>'><i class="fa fa-pencil"></i></a>
							<a href='javascript:delItem(<?php echo $v['id']; ?>)' class="confirm_delete"><i class="fa fa-trash"></i></a>	
					</td>
					</tr>
                             <?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="4"><?php echo $lang['SSF_CATEGORY_NO_CAT']; ?></td>
					</tr>
					<?php endif; ?>
                            </tbody>
                          </table>
                          <div class="row">
                              <div class="col-lg-12 col-sm-12 col-xs-12">
                              <div class="dataTables_paginate paging_simple_numbers">
							  <?php  
			$sql = "SELECT COUNT(id) FROM categories";  
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
						 $pagLink .= "<li class='paginate_button $active'><a href='categories.php?page=".$i."'>".$i."</a></li>";  
			};  
			echo $pagLink . "</ul></div>";  
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
		document.location.href='?action=delete&id='+id;
		}
	
	}
	</script>
      <?php include("footer.php"); ?>
