<?
$heading='sms';
include_once './includes/config.inc.php';
auth();
$db = db_connect();
if(isset($_REQUEST['action']) && $_REQUEST['action']=="insert"){
	 mysql_query("INSERT INTO `sms_group_list`(`group_id`, `sms_number`) VALUES ('".$_REQUEST['gr_group_id']."','+1".$_REQUEST['sms_number']."')");
	mysql_query("UPDATE `sms_group` SET  `group_list_count`=group_list_count+1 WHERE group_id='".$_REQUEST['gr_group_id']."'");
}
if($_REQUEST['getData'])
{
	$order = $_REQUEST['order'];
	$page = $_REQUEST['page'];
	$limit = $_REQUEST['limit'];
	$start = ($page-1)*$limit;
	
	if("icon-circle-arrow-up"==$order)
		$or = 'ASC';
	else
		$or = 'DESC';
	$column = $_REQUEST['column'];
	if($column == "sms_number")
		$cby = "sms_number";
	 
	
	if(intval($_REQUEST['gr_group_id'])!='')
	$store =' Where group_id='.$_REQUEST['gr_group_id'];
	else
	$store ='';
	
	$key ='';
	if($_REQUEST['keyword']!='')
	{	
		$key.=' AND sms_number like "%'.$_REQUEST['keyword'].'%" ';
	}

//
	      $sql = "SELECT `sms_group_list_id`,`group_id`,`sms_number` FROM `sms_group_list`  $store $key 
	order by  sms_group_list_id DESC
	LIMIT $start , $limit";
	$stores = $db->get_rows($sql);
	#var_dump($sql);die;
	$count_sql = "SELECT `sms_group_list_id`,`group_id`,`sms_number` FROM `sms_group_list`
	  $store $key";
	$count_res = mysql_query($count_sql );
	$rows_total = mysql_num_rows($count_res);
	echo json_encode(array('sms_group_list'=>$stores, "total"=>$rows_total));
	exit;
}
if($_REQUEST['deleteReview'])
{
	$sms_group_list_id = $_REQUEST['sms_group_list_id'];
	mysql_query("Delete from sms_group_list where sms_group_list_id=$sms_group_list_id");
	mysql_query("UPDATE `sms_group` SET  `group_list_count`=group_list_count-1 WHERE group_id='".$_REQUEST['gr_group_id']."'");
	echo mysql_affected_rows();
	exit;
}

  

$stores_list = $db->get_rows("SELECT `sms_group_list_id`,`group_id`,`sms_number` FROM `sms_group_list`");

?>

	  <?php include("header.php"); ?>
      <script>
	 window.addEventListener("orientationchange", function() {
				window.location.hef='';
			});getData();
function getData(page){
	var order = $("#current_order_ele").attr('order');
	var column = $("#current_order_ele").attr('bind');
	
	var limit = 50;
	if(!page)
		page = 1;
	
	$.ajax({
		url: location.href,
		data: { 
			getData:1,
			order:order,
			column: column,
			store: $('#filter_store').val(),
			keyword: $('#keyword_search').val(),
			page:page,
			limit:limit
		},
		error: function(){alert('Errorr')},
		beforeSend: function(){
			$("#loading_img").show()
		},
		complete: function(){
			$("#loading_img").hide()
		},
		datatype:'json',
		success: function(output){
			var str='';
			output = jQuery.parseJSON(output)
			var x = 1;
			$("#data-table").find('tbody').empty();
			
				$.each(output.sms_group_list,function(item){
					var data = output.sms_group_list;
					if(x%2 == 0)
						var row_class='even';
					else
						var row_class='odd';
					x++;
				 
					 str="<tr class='"+row_class+"'><td>"+data[item].sms_number+"</td>"+
				"<td class='actions text-center'><a sms_group_list_id='"+data[item].sms_group_list_id+"' class='confirm_delete' href='#'><i class='fa fa-trash' id='trash'></i></a>"+
				"</td>";
				"</td></tr>";
					$("#data-table").find('tbody').append(str);
					
				})
				var totalrows = output.total;
				var no_links = Math.ceil(totalrows/limit);
				var act = '';
				$("#paginationDiv").empty();
				for(var i =1;i<=no_links;i++)
				{
					if(page == i)
						act = 'active';
					else
						act = '';
					$("#paginationDiv").append("<li class='paginate_button " + act+"' page='"+i+"'><a href='#'>"+i+"</a></li>");
				}
			
		}
	});
}
$(document).ready(function(){
 
	$("#search_button").click(function(){
		//getData('icon-circle-arrow-up','cust_cname');
		getData();
	});
	
	$(document).on("click", ".paginate_button", function(){
		
		getData($(this).attr('page'));
	});
	$(document).on("touchstart", ".pagination_li", function(){
		
		getData($(this).attr('page'));
	});
	
	  
	$(document).on("click", "#trash", function(){
		if(confirm("Are you sure to delete?"))
		{
			var sms_group_list_id = $(this).parent().attr('sms_group_list_id');
			$.ajax({
			url: location.href,
			data: { 
				deleteReview: 1,
				sms_group_list_id: sms_group_list_id
			},
			beforeSend: function(){},
			complete: function(){},
			success: function(data){
				if(data.trim()=='1')
					alert("Success");
				//getData('icon-circle-arrow-up','cust_cname');
				getData();
			}
			});
		}
		
		return false;
	});
})
</script>
      <!-- Left side column. contains the logo and sidebar -->
      <?php include("sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Group Number List</h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Group Number List</li>
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
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                      <div class="row">
                        <div class="col-lg-12 col-sm-12 col-xs-12">
                            <div id="example1_filter" class="res-marg rev-width-alg">
                            	<div class="row">
                               	<form action="group_list_numbers.php?gr_group_id=<?=$_REQUEST['gr_group_id'];?>" method="post">
                               	<input type="hidden" name="action" value="insert" />
                                	<div class="col-lg-3 col-sm-4 col-xs-12">
                                    	<input type="text" class="form-control required" placeholder="Enter Number"  value="" name="sms_number" id="" >
                                    </div>
                                    
									<div class="col-lg-2 col-sm-2 col-xs-12">
                                    	<button type="submit" id='btnsubmit' class="btn btn-primary">Add Number</button>
                                    </div>
									</form>
                				</div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-xs-12">
                            <div id="example1_filter" class="res-marg rev-width-alg">
                            	<div class="row">
                                	<div class="col-lg-3 col-sm-4 col-xs-12">
                                    	<input type="text" class="form-control" placeholder="Enter Number"  value="" name="search" id="keyword_search">
                                    </div>
                                    
									<div class="col-lg-2 col-sm-2 col-xs-12">
                                    	<button id='search_button' class="btn btn-primary">Search</button>
                                    </div>
                				</div>
                            </div>
                        </div>
                        
                    </div>
                  <div class="row">
                      <div class="col-lg-12 col-sm-12 col-xs-12">
                          <div class="table-responsive"><table id="data-table" class="table table-bordered table-striped">
                            <thead>
                              <tr>
							<th>Numbers 
							<th class="actions text-center">Delete</th>
						</tr>
                            </thead>
							<img id='loading_img' align='center' style='display:none;margin-left:43%' src="../img/ajax-loader.gif">
                            <tbody>
                             
                            </tbody>
                          </table></div>
                          <div class="row">
                             <div class="col-lg-12 col-sm-12 col-xs-12">
                                 <div class="dataTables_paginate paging_simple_numbers">
                                     <div>
                                         <ul class="pagination" id="paginationDiv">ggdfggf
                                         </ul>
                                     </div>
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
       
<input type='hidden' id='current_order_ele' bind='sms_number' order='icon-circle-arrow-up'>
<!-- <img id='loading_img' align='center' style='display:none; margin-left:43%' src="../img/ajax-loader.gif"> -->
<?php include("footer.php"); ?>
