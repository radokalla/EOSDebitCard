<?php

ob_start();
session_start();

 $header='SMS';
$username = $_SESSION["regSuccess"];
if(empty($username)){ ?>
	<script>
		window.location.href = 'addstore.php';
	</script>
<?php }
include_once 'header.php';
error_reporting(0);
include_once './includes/config.inc.php';
$db = db_connect();
$sql = mysql_query("SELECT * from StoreUsers where Username ='".$username."'");
$userdetails = mysql_fetch_array($sql);
$userid=$userdetails['UserId'];

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
	$sql = "SELECT * from sms_group where user_id ='".$userid."' order by group_id desc LIMIT $start , $limit" ;
	
	$stores = $db->get_rows($sql);
	
	#var_dump($sql);die;
	$count_sql = "SELECT * from sms_group where user_id ='".$userid."' order by group_id desc ";
	$count_res = mysql_query($count_sql );
	$rows_total = mysql_num_rows($count_res);
	echo json_encode(array('sms_group'=>$stores, "total"=>$rows_total));
	exit;
}
$sms_list = $db->get_rows("SELECT group_id,group_name,user_id,group_list_count,COUNT(group_id) AS total from sms_group where user_id ='".$userid."' order by group_id desc");

?> 
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
			 console.log(output);
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
        	<div class="row">
            	<?php include ROOT."admin-left.php"; ?>

      <!-- Content Wrapper. Contains page content -->
   <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
        <!-- Content Header (Page header) -->
<h2 class="head-text">Group List</h2>

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
                                         <ul class="pagination" id="paginationDiv">
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
</body>
<?php include ROOT."themes/footer.inc.php"; ?>
</html>