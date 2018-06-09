<?
$heading='sms';
include_once './includes/config.inc.php';
auth();
 
$db = db_connect();
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
	if($column == "sms_from")
		$cby = "sms_from";
	 
	else if($column == "sms_to")
		$cby = "sms_to";
	  

//
	$sql = "SELECT * from sms_group order by group_id desc
	LIMIT $start , $limit";
	$stores = $db->get_rows($sql);
	#var_dump($sql);die;
	$count_sql = "SELECT * from sms_group order by group_id desc";
	$count_res = mysql_query($count_sql );
	$rows_total = mysql_num_rows($count_res);
	echo json_encode(array('sms_group'=>$stores, "total"=>$rows_total));
	exit;
}
$sms_list = $db->get_rows("SELECT group_id,group_name,group_list_count,COUNT(group_id) AS total from sms_group order by group_id desc");

?>  <?php include("header.php"); ?>
  <script>
	 window.addEventListener("orientationchange", function() {
				window.location.hef='';
			});
function getData(page){
	var order = $("#current_order_ele").attr('order');
	var column = $("#current_order_ele").attr('bind');
	
	var limit = 10;
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
			output = jQuery.parseJSON(output);
			
			var x = 1;
			$("#data-table").find('tbody').empty();
			
				$.each(output.sms_group,function(item){
					var data = output.sms_group;
					if(x%2 == 0)
						var row_class='even';
					else
						var row_class='odd';
					x++; 
					str="<tr class='"+row_class+"'><td>"+data[item].group_name+"</td><td><a href='group_list_numbers.php?gr_group_id="+data[item].group_id+"'>"+data[item].group_list_count+"</a></td></td> </tr>";
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
	getData();
	$('#data-table').find('th').on('click',function(){
		if($(this).find('div').length > 0)
		{
			var order = $(this).find('div').attr('class');
			var column = $(this).find('div').attr('id');
			
			$("#current_order_ele").attr('order',$('#'+column).attr('class'));
			$("#current_order_ele").attr('bind',column);
			getData();
			//getData($('#'+column).attr('class'),column);
			if(order=="icon-circle-arrow-up")
				$('#'+column).attr('class','icon-circle-arrow-down')
			else
				$('#'+column).attr('class','icon-circle-arrow-up')
		
		}
	});
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
 
	 
})
</script>
	
      
      <!-- Left side column. contains the logo and sidebar -->
      <?php include("sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Group List</h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Group List</li>
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
                      <a class="btn btn-success pull-right" href="addgroup.php">Add Group</a>
					   </div>
						</div>
                  <div class="row">
                      <div class="col-lg-12 col-sm-12 col-xs-12">
                          <div class="table-responsive"><table id="data-table" class="table table-bordered table-striped">
                            <thead>
                              <tr>
							<th>Group Name<!--<a><div id='cust_cname' class="icon-circle-arrow-up"></div></a>--></th>
								  <th>Group List Count </th> 
							 
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
       
<input type='hidden' id='current_order_ele' bind='cust_cname' order='icon-circle-arrow-up'>
<!-- <img id='loading_img' align='center' style='display:none; margin-left:43%' src="../img/ajax-loader.gif"> -->
<?php include("footer.php"); ?>
