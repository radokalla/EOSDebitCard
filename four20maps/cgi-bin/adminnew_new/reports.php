<?
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
<link rel="stylesheet" type="text/css" href="css/style.css" media="all" />
<link rel="stylesheet" type="text/css" href="../css/bootstrap.css" media="all" />
<link rel="stylesheet" type="text/css" href="../css/bootstrap-responsive.css" media="all" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" media="all">
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
<style>
.modal-body{
	text-align:center
}
</style>
<body id="stores">
	<div id="wrapper">
		<div id="header">
			<?php include 'nav.php'; ?>
		</div>
		<div id="main">
			Start Date<input id='start_date'>
			End Date <input id='end_date'>
			<button id="filter">Filter</button>
			<br><br>
			<img id="loading" src='../img/ajax-loader.gif' style='display:none'>
			 <div id="registered_user_buy_clicks_chart"></div>
			 <br><br>
			 <div id="stores_clicks_chart"></div>
		</div>
	</div>
	
</body>