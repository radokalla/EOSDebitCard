<?
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
	if($column == "cust_cname")
		$cby = "u.firstname";
	else if($column == "store_approved")
		$cby = "r.approved_on";
	else if($column == "store_cname")
		$cby = "s.name";
	
	if(intval($_REQUEST['store'])!='')
	$store ='AND s.id='.$_REQUEST['store'];
	else
	$store ='';
	
	$key ='';
	if($_REQUEST['keyword']!='')
	{	
		$key.=' AND (u.firstname like "%'.$_REQUEST['keyword'].'%" or u.lastname like "%'.$_REQUEST['keyword'].'%") ';
	}

//
	$sql = "SELECT u.firstname, u.lastname, s.name, r . * 
	FROM reviews r
	LEFT JOIN users u ON u.id = r.customer_id
	LEFT JOIN stores s ON s.id = r.store_id
	WHERE r.customer_id >=0  $store $key 
	order by $cby $or
	LIMIT $start , $limit";
	$stores = $db->get_rows($sql);
	#var_dump($sql);die;
	$count_sql = "SELECT u.firstname, u.lastname, s.name, r . * 
	FROM reviews r
	LEFT JOIN users u ON u.id = r.customer_id
	LEFT JOIN stores s ON s.id = r.store_id
	WHERE 1=1  $store $key";
	$count_res = mysql_query($count_sql );
	$rows_total = mysql_num_rows($count_res);
	echo json_encode(array('reviews'=>$stores, "total"=>$rows_total));
	exit;
}
if($_REQUEST['deleteReview'])
{
	$revid = $_REQUEST['revid'];
	mysql_query("Delete from reviews where rev_id=$revid");
	echo mysql_affected_rows();
	exit;
}
if($_REQUEST['getReview'])
{
	$revid = $_REQUEST['revid'];
	$stores = $db->get_rows("SELECT u.firstname, u.lastname, s.name, r . * 
	FROM reviews r
	LEFT JOIN users u ON u.id = r.customer_id
	LEFT JOIN stores s ON s.id = r.store_id
	where r.rev_id=$revid");
	echo json_encode($stores);
	exit;
}
if($_REQUEST['saveReview'])
{
	$revid = $_REQUEST['revid'];
	$rating = $_REQUEST['rating'];
	$comments = $_REQUEST['comments'];
	$approved = $_REQUEST['approved'];
	if($approved=='1')
		$approved = date("Y-m-d");
	else
		$approved = "0000-00-00";
	$sql ="UPDATE reviews set store_rating = $rating, store_review='$comments',approved_on='$approved'
	where rev_id=$revid ";
	#var_dump($sql);die;
	mysql_query($sql);
	echo mysql_affected_rows();
	exit;
}


$stores_list = $db->get_rows("SELECT s.id,s.name,COUNT(r.rev_id) AS total FROM stores s
LEFT JOIN reviews r ON s.id = r.store_id
GROUP BY s.id");

?>
<link rel="stylesheet" type="text/css" href="css/style.css" media="all" />
<link rel="stylesheet" type="text/css" href="../css/bootstrap.css" media="all" />
<link rel="stylesheet" type="text/css" href="../css/bootstrap-responsive.css" media="all" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.js"></script>
<script>
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
			output = jQuery.parseJSON(output)
			var x = 1;
			$("#data-table").find('tbody').empty();
			
				$.each(output.reviews,function(item){
					var data = output.reviews;
					if(x%2 == 0)
						var row_class='even';
					else
						var row_class='odd';
					x++;
					if("0000-00-00" == data[item].approved_on)
						var app= "Not Approved";
					else
						var app= "Approved";
					if(data[item].firstname ==null)
						data[item].firstname = '';
					if(data[item].lastname ==null)
						data[item].lastname = '';
					
					var cname = data[item].firstname + data[item].lastname;
					str="<tr class='"+row_class+"'><td>"+cname+"</td><td>"+data[item].name+"</td><td>"+
					+data[item].store_rating+"</td><td>"+data[item].store_review+"</td>"+"<td>"+app+"</td>"+
				"<td class='actions'><a revid='"+data[item].rev_id+"' href='#' data-toggle='modal' data-target='#editreview'>"+
				"<i class='icon-pencil'></i></a>"+
				"<a revid='"+data[item].rev_id+"' class='confirm_delete' href=''><i class='icon-trash'></i></a>"+
				"</td></tr>";
					$("#data-table").find('tbody').append(str);
					
				})
				var totalrows = output.total;
				var no_links = Math.ceil(totalrows/limit);
				$(".pagination ul").empty();
				for(var i =1;i<=no_links;i++)
					$(".pagination ul").append("<li class='pagination_li' page='"+i+"'><a>"+i+"</a><li>")
			
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
	
	$(document).on("click", ".pagination_li", function(){
		
		getData($(this).attr('page'));
	});
	$(document).on("touchstart", ".pagination_li", function(){
		
		getData($(this).attr('page'));
	});
	
	$(document).on("click", ".icon-pencil", function(){
		
		var revid = $(this).parent().attr('revid');
		$.ajax({
			url: location.href,
			data: { 
				getReview: 1,
				revid: revid
			},
			beforeSend: function(){
				$('#Loading_div').html("<img style='margin-top:10px;' src='../img/ajax-loader.gif'>");
			},
			complete: function(){
				$('#Loading_div').html("");
			},
			success: function(data){
				data = $.parseJSON(data);
				
				var custname = data[0].firstname + " " + data[0].lastname;
				var storename = data[0].name;
				var rev_id = data[0].rev_id;
				var rating = data[0].store_rating;
				var comments = data[0].store_review;
				var added_on = data[0].added_on;
				var approved_on = data[0].approved_on;
				$("#review_edit_form").trigger("reset");
				$("#cust_name").html("<b>Customer Name</b> : "+custname);
				$("#store_name").html("<b>Store Name</b> : "+storename);
				$("#user_store_rating").val(rating);
				$("#rating_desc").val(comments);
				if(approved_on=="0000-00-00")
				$("#rating_approved").val(0);
				else
				$("#rating_approved").val(1);
				$("#current_revid").val(rev_id)
			}
		});
	});
	
	$("#update_rev").click(function(){
		var revid = $("#current_revid").val();
		if(parseInt(revid)==0)
		{
			alert("Can't save")
			return false;
		}
		$.ajax({
			url: location.href,
			data: { 
				saveReview: 1,
				revid: revid,
				rating: $("#user_store_rating").val(),
				comments: $("#rating_desc").val(),
				approved: $("#rating_approved").val()
			},
			beforeSend: function(){
				$('#Loading_div').html("<img style='margin-top:10px;' src='../img/ajax-loader.gif'>");
			},
			complete: function(){
				$('#Loading_div').html("");
			},
			success: function(data){
				if(data.trim()=='1')
					alert("Success")
				else
					alert("Can't save at this moment")
				//getData('icon-circle-arrow-up','cust_cname');
				getData();
			}
		});
	})
	$(document).on("click", ".icon-trash", function(){
		if(confirm("Are you sure to delete?"))
		{
			var revid = $(this).parent().attr('revid');
			$.ajax({
			url: location.href,
			data: { 
				deleteReview: 1,
				revid: revid
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
			<div class="searchbar">
				<input type=text placeholder="Customer Name"  value="" name="search" id="keyword_search">
				<select id="filter_store" name="filter">
				<option value="">All</option>	
				<?
					foreach($stores_list as $store)
					echo '<option value="'.$store['id'].'">'.$store['name'].'('.$store['total'].')'.'</option>	';
				?>
				</select>
				<button id='search_button'>Search</button>
			</div>
			<p style="float:right">** No Customer name indicates "Visitor"</p>
				<table style="width:100%;" id='data-table' class="table table-bordered">
					<thead>
						<tr>
							<th>Customer Name <a><div id='cust_cname' class="icon-circle-arrow-up"></div></a></th>
							<th>Store<a><div id='store_cname' class="icon-circle-arrow-up"></div></a></th>
							<th>Rating</th>
							<th>Description</th>
							<th class="acenter">Approved<a><div id='store_approved' class="icon-circle-arrow-up"></div></a></th>
							<th class="actions">Actions</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
				<div class="pagination">
					<ul>
					</ul>
				</div>
		</div>
	</div>
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
								<label class="input"> <i class="icon-prepend fa-user"></i>
									<label name="cust_name" id="cust_name" ></label>
								</label>
							</div>
							<div class="col-md-6">
								<label class="input"> <i class="icon-prepend fa-user"></i>
									<label name="store_name" id="store_name"></label>
								</label>
							</div>
						
						
							<div class="col-md-6">
								<label class="input"> <i class="icon-prepend fa-user"></i>
								Rating	<select name="user_store_rating" id="user_store_rating" placeholder="Rating" value="" class="required">
								<option value='1'>1</option>
								<option value='2'>2</option>
								<option value='3'>3</option>
								<option value='4'>4</option>
								<option value='5'>5</option>
								</select>
								</label>
							</div>
							<div class="col-md-6">
								<label class="input"> <i class="icon-prepend fa-user"></i>
								Comments	<input  type="text" name="rating_desc" id="rating_desc" placeholder="Description" value="" class="required">
								</label>
							</div>
						
						
							<div class="col-md-12">
								Status<select id="rating_approved" name="rating_approved">
								<option value='1'>Approved</option>
								<option value='0'>Not Approved</option>
								</select>
							</div>
							<div class="col-md-12">
								<button type="button" class="button button-secondary" id="update_rev" id="addEnquiry_button" class="Update">Save</button>
								<input type='hidden' id='current_revid'>
							</div>
							<div class="col-md-12" id="Loading_div">
								
							</div>
					</section>

					
					
					 
					
					</form>
				</div>
		   
			</div>
		</div>
	</div>
	<input type='hidden' id='current_order_ele' bind='cust_cname' order='icon-circle-arrow-up'>
	<img id='loading_img' align='center' style='display:none;margin-left:43%' src="../img/ajax-loader.gif">
</body>