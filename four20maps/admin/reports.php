<?
$heading='reports';
include_once './includes/config.inc.php';
auth();
$db = db_connect();
if($_REQUEST['getCategoryHits'])
{
	$start = $_REQUEST['start'];
	$end = $_REQUEST['end'];
	$allrows = array();		
	$userrows = array();		
	$visitorrows = array();		
	$output = array();
	$output[] = array('Day', 'Total Clicks', 'User Clicks', 'Visitor Clicks');		
	$sql="SELECT DATE(`b_datetime`) as day,COUNT(*) as recordCount FROM analytics_buy 
		WHERE DATE(`b_datetime`) BETWEEN '$start' AND '$end' GROUP BY DATE(`b_datetime`)";
	$results = mysql_query($sql );
	while($row = mysql_fetch_array($results))
	$allrows[$row['day']] = intval($row['recordCount']);
	
	$sql="SELECT DATE(`b_datetime`) as day,COUNT(*) as recordCount FROM analytics_buy 
		WHERE uid=0 AND DATE(`b_datetime`) BETWEEN '$start' AND '$end' GROUP BY DATE(`b_datetime`)";
	$results = mysql_query($sql );
	while($row = mysql_fetch_array($results))
	$visitorrows[$row['day']] = intval($row['recordCount']);
	
	$sql="SELECT DATE(`b_datetime`) as day,COUNT(*) as recordCount FROM analytics_buy 
		WHERE uid!=0 AND DATE(`b_datetime`) BETWEEN '$start' AND '$end' GROUP BY DATE(`b_datetime`)";
	$results = mysql_query($sql );
	while($row = mysql_fetch_array($results))
	$userrows[$row['day']] = intval($row['recordCount']);
	
	foreach($allrows as $key=>$value){
		$user = ($userrows[$key])?$userrows[$key]:0;
		$vis = ($visitorrows[$key])?$visitorrows[$key]:0;
		$output[] = array($key, $allrows[$key],$user,$vis);
	}
	if(count($output)==1)
		$output[]= array("No Data",0,0,0);
	#var_dump($output); die;
	echo json_encode($output);
	exit;
	
}
if($_REQUEST['getStoreHits'])
{
	$start = $_REQUEST['start'];
	$end = $_REQUEST['end'];
	$sql="SELECT site, COUNT(*) AS recordCount FROM analytics_buy 
	WHERE DATE(`b_datetime`) BETWEEN '$start' AND '$end' GROUP BY site";
	$results = mysql_query($sql );
	$result[]=array("Store","Hits");
	while($row = mysql_fetch_array($results))
	$result[] = array($row['site'],$row['recordCount']);
	if(count($result)==1)
		$result[]= array("No Data",0);
	echo json_encode($result);
	exit;
}

?>
<?php include("header.php"); ?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script>
	google.load("visualization", "1.1", {packages:["bar"]});
	
	google.setOnLoadCallback(drawChart);
		function drawChart() {
			
			
			var d = new Date();
			var today = d.getFullYear()+"-"+(d.getMonth() +1)+"-"+d.getDate();
			d.setDate(d.getDate() - 7);
			var firstday = d.getFullYear()+"-"+(d.getMonth() +1)+"-"+d.getDate();
			loadChart1(today,firstday)
			//loadChart2(today,firstday)
			
		}
		$(document).ready(function(e) {
	$( "#start_date" ).datepicker({
      changeMonth: true,
      changeYear: true,
	  dateFormat:"yy-mm-dd"
    });
	$( "#end_date" ).datepicker({
      changeMonth: true,
      changeYear: true,
	  dateFormat:"yy-mm-dd"
    });
	$("#filter").click(function(){
		
		loadChart1($( "#end_date" ).val(),$( "#start_date" ).val())
		
	});
});
function loadChart1(today,firstday)
{
	var all=[];
	$.ajax({
			url: 'reports.php?getCategoryHits=1&start='+firstday+'&end='+today,
			beforeSend:function(){
				$("#loading").show();
			},
			complete:function(){
				loadChart2(today,firstday)
			},
			success: function(output){
				
				output = $.parseJSON(output)
				for(var i in output)
					all.push(output[i])
				
				var fulldata = google.visualization.arrayToDataTable(all);
				var barchart = new google.charts.Bar(document.getElementById('registered_user_buy_clicks_chart'));
				var boptions = {
						  chart: {
							title: 'No of BUY Clicks',
							subtitle: 'All, User, and Visitor:'+firstday+"-"+today
						  }
						};
				barchart.draw(fulldata, boptions);
			}
		});
}
function loadChart2(today,firstday)
{
	var stores=[];
	$.ajax({
		url: 'reports.php?getStoreHits=1&start='+firstday+'&end='+today,
		beforeSend:function(){
			$("#loading").show();
		},
		complete:function(){
				$("#loading").hide();
			},
		success: function(output){
			output = $.parseJSON(output)
			for(var i in output)
				stores.push(output[i])
			
			var data = google.visualization.arrayToDataTable(stores);

			var options = {
			  title: 'Store Performance'+firstday+"-"+today
			};

			var chart1 = new google.charts.Bar(document.getElementById('stores_clicks_chart'));
			chart1.draw(data, options);
			
		}
	});
}
</script>
<!-- Left side column. contains the logo and sidebar -->
<?php include("sidebar.php"); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"> 
 <!-- Content Header (Page header) -->
 <section class="content-header">
  <h1>Reports</h1>
  <ol class="breadcrumb">
   <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
   <li class="active">Reports</li>
  </ol>
 </section>
 
 <!-- Main content -->
 <section class="content"> 
  <!-- Small boxes (Stat box) -->
  <div class="row">
   <div class="col-lg-12 col-sm-12 col-xs-12">
    <div class="box">
     <div class="box-header"> </div>
     <!-- /.box-header -->
     <div class="box-body">
      <div class="dataTables_wrapper form-inline dt-bootstrap">
       <div class="row">
        <div id="example1_filter" class="res-marg res-report-frm">
         <div class="col-lg-3 col-md-3 col-sm-5 col-xs-12">
          <label>Start Date </label>
          <input id='start_date' class="form-control res-frm-cnt">
         </div>
         <div class="col-lg-3 col-md-3 col-sm-5 col-xs-12">
          <label>End Date </label>
          <input id='end_date' class="form-control res-frm-cnt">
         </div>
         <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
          <button id="filter" class="btn btn-primary">Filter</button>
         </div>
        </div>
       </div>
       <div class="row">
       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
        <div class="ani-loadingwrps"><img id="loading" src='../img/ajax-loader.gif' style='display:none'></div>
       </div>
       </div>
       
       <div class="row">
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 graph-block">
         <div id="registered_user_buy_clicks_chart"></div>
         <div id="stores_clicks_chart"></div>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
       </div>
      </div>
     </div>
     <!-- /.box-body --> 
    </div>
   </div>
  </div>
  <!-- /.row --> 
  
 </section>
 <!-- /.content --> 
</div>
<!-- /.content-wrapper -->
<div class="modal fade" id="editreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true" style="display:none">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title LRhd" id="myModalLabel">Edit Rating</h4>
   </div>
   <div class="modal-body">
    <form method="post"  action="" id="review_edit_form" name="" class="xform">
     <section>
      <div class="col-md-6">
       <label class="input">
       <i class="icon-prepend fa-user"></i>
       <label name="cust_name" id="cust_name" ></label>
       </label>
      </div>
      <div class="col-md-6">
       <label class="input">
       <i class="icon-prepend fa-user"></i>
       <label name="store_name" id="store_name"></label>
       </label>
      </div>
      <div class="col-md-6">
       <label class="input"> <i class="icon-prepend fa-user"></i> Rating
        <select name="user_store_rating" id="user_store_rating" placeholder="Rating" value="" class="required">
         <option value='1'>1</option>
         <option value='2'>2</option>
         <option value='3'>3</option>
         <option value='4'>4</option>
         <option value='5'>5</option>
        </select>
       </label>
      </div>
      <div class="col-md-6">
       <label class="input"> <i class="icon-prepend fa-user"></i> Comments
        <input  type="text" name="rating_desc" id="rating_desc" placeholder="Description" value="" class="required">
       </label>
      </div>
      <div class="col-md-12"> Status
       <select id="rating_approved" name="rating_approved">
        <option value='1'>Approved</option>
        <option value='0'>Not Approved</option>
       </select>
      </div>
      <div class="col-md-12">
       <button type="button" class="button button-secondary" id="update_rev" id="addEnquiry_button" class="Update">
       Save
       </button>
       <input type='hidden' id='current_revid'>
      </div>
      <div class="col-md-12" id="Loading_div"> </div>
     </section>
    </form>
   </div>
  </div>
 </div>
</div>
<input type='hidden' id='current_order_ele' bind='cust_cname' order='icon-circle-arrow-up'>
<img id='loading_img' align='center' style='display:none;margin-left:43%' src="../img/ajax-loader.gif">
<?php include("footer.php"); ?>
