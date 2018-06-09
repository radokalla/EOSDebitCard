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
	   $sql = "SELECT * from sms where mesage_unique_id='".$_REQUEST['mesage_unique_id']."' order by sms_id desc
	LIMIT $start , $limit";
	$stores = $db->get_rows($sql);
	#var_dump($sql);die;
	$count_sql = "SELECT * from sms where mesage_unique_id='".$_REQUEST['mesage_unique_id']."'";
	$count_res = mysql_query($count_sql );
	$rows_total = mysql_num_rows($count_res);
	echo json_encode(array('reviews'=>$stores, "total"=>$rows_total));
	exit;
}
$sms_list = $db->get_rows("SELECT sms_id,sms_from,sms_to,created_date,message,server_response,COUNT(1) AS total from sms where mesage_unique_id='".$_REQUEST['mesage_unique_id']."' order by sms_id desc");

?>  <?php include("header.php"); ?>
  <script>
	 window.addEventListener("orientationchange", function() {
				window.location.hef='';
			});
function getData(page){
	var order = $("#current_order_ele").attr('order');
	var column = $("#current_order_ele").attr('bind');
	
	var limit = 250;
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
			
				$.each(output.reviews,function(item){
					var data = output.reviews;
					if(x%2 == 0)
						var row_class='even';
					else
						var row_class='odd';
					x++; 
				  var response=jQuery.parseJSON(data[item].server_response);
					str="<tr class='"+row_class+"'><td>"+data[item].sms_from+"</td><td>"+data[item].sms_to+"</td><td>"+
					 data[item].created_date.toString()+"</td><td>"+((response.messages[0].status == "0") ? "Success" : "Fail")+"</td> </tr>";
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
      <?php 
	$url='https://rest.nexmo.com/account/get-balance/?api_key=3e1997ac&api_secret=60525d05b4f53e80';
  	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
	//execute post
	$result = curl_exec($ch);         
	$info  = curl_getinfo($ch); 
	$error = curl_errno($ch);  
	curl_close($ch);  
	$remaining_array=json_decode($result,true); 
//$result_remaining=  mysql_fetch_assoc(mysql_query("SELECT * FROM `sms` WHERE `server_response` like '%remaining-balance%' order by sms_id DESC limit 0,1"));

//$remaining_array=json_decode($result_remaining['server_response'],true);
 
include("sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>SMS List</h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">SMS List</li>
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
                      <a class="btn btn-success pull-right" href="sendsms.php">Send Sms</a><br/>
                      <h3 class="pull-right">Balance Remaining :<?= round($remaining_array['value']/0.0057);?></h3>
                       
					   </div>
						</div>
                  <div class="row">
                      <div class="col-lg-12 col-sm-12 col-xs-12">
                          <div class="table-responsive"><table id="data-table" class="table table-bordered table-striped">
                            <thead>
                              <tr>
							<th>From<!--<a><div id='cust_cname' class="icon-circle-arrow-up"></div></a>--></th>
							<th>To <!--<a><div id='store_cname' class="icon-circle-arrow-up"></div></a>--></th>
							<th>Date</th>
							<th>Message Status</th>
							 
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
