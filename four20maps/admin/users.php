<?
$heading='users';
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
	if($column == "f_cname")
		$cby = "firstname";
	else if($column == "l_cname")
		$cby = "lastname";
	else if($column == "u_status")
		$cby = "status";
	
	
	$key ='';
	if($_REQUEST['keyword']!='')
	$key.=' AND (firstname like "%'.$_REQUEST['keyword'].'%" or lastname like "%'.$_REQUEST['keyword'].'%")';
	

//
	$sql = "SELECT *from users WHERE users.is_admin=0 $key
	order by $cby $or
    LIMIT $start, $limit";
	$stores = $db->get_rows($sql);
	#var_dump($sql);die;
	$count_sql = "SELECT *from users WHERE users.is_admin=0 $key";
	$count_res = mysql_query($count_sql );
	$rows_total = mysql_num_rows($count_res);
	echo json_encode(array('reviews'=>$stores, "total"=>$rows_total));
	exit;
}
if($_REQUEST['deleteUser'])
{
	$uid = $_REQUEST['uid'];
	mysql_query("Delete from users where id=$uid");
	mysql_query("Delete from reviews where customer_id=$uid");
	echo mysql_affected_rows();
	exit;
}
if($_REQUEST['getUser'])
{
	$uid = $_REQUEST['uid'];
	$stores = $db->get_rows("SELECT id,firstname,lastname,address,email,status from users where id=$uid");
	echo json_encode($stores);
	exit;
}
if($_REQUEST['saveUser'])
{
	$uid = $_REQUEST['uid'];
	$userf_name = $_REQUEST['userf_name'];
	$userl_name = $_REQUEST['userl_name'];
	$user_address = $_REQUEST['user_address'];
	$user_approved = $_REQUEST['user_approved'];
	$date = date('Y-m-d');
	$sql ="UPDATE users set firstname = '$userf_name', lastname='$userl_name',address='$user_address', modified='$date', status=$user_approved 
	where id=$uid ";
	#var_dump($sql);die;
	mysql_query($sql);
	echo mysql_affected_rows();
	exit;
}


?>
	  <?php include("header.php"); ?>
      <!-- Left side column. contains the logo and sidebar -->
        
<script>
	function getData(page){
	var order = $("#current_order_ele").attr('order');
	var column = $("#current_order_ele").attr('bind');
	
	var limit = 10;
	if(!page)
		page = $("#current_page").val();
	
	$.ajax({
		url: location.href,
		data: { 
			getData:1,
			order:order,
			column: column,
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
				if(output.reviews=='')
				{
					$("#data-table").find('tbody').html('No results found..');
					return false;
				}
				var x = 1;
				$("#data-table").find('tbody').empty();
				$.each(output.reviews,function(item){
					var data = output.reviews;
					if(x%2 == 0)
						var row_class='even';
					else
						var row_class='odd';
					x++;
					if("0" == data[item].status)
						var app= "Not Active";
					else
						var app= "Active";
					str +="<tr class='"+row_class+"'><td>"+data[item].firstname +"</td><td>"+data[item].lastname+"</td><td>"+
					data[item].address+"</td><td>"+data[item].email+"</td>"+"<td>"+app+"</td>"+
				"<td class='actions text-center'><a revid='"+data[item].id+"' href='#' data-toggle='modal' data-target='#editreview'>"+
				"<i class='fa fa-pencil'></i></a> "+
				"<a revid='"+data[item].id+"' class='confirm_delete' href='#'><i class='fa fa-trash'></i></a>"+
				"</td></tr>";
					
				})
				$("#data-table").find('tbody').html(str);
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
			$("#current_order_ele").attr('bind',column);			$("#current_page").val('1')
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
		$("#current_page").val($(this).attr('page'))
		getData($(this).attr('page'));
	});
	$(document).on("click", ".fa-pencil", function(){
		var uid = $(this).parent().attr('revid');
		$.ajax({
			url: location.href,
			data: { 
				getUser: 1,
				uid: uid
			},
			beforeSend: function(){
				$('#Loading_div').html("<img style='margin-top:10px;' src='../img/ajax-loader.gif'>");
			},
			complete: function(){
				$('#Loading_div').html("");
			},
			success: function(data){
				data = $.parseJSON(data);
				var firstname = data[0].firstname;
				var lastname = data[0].lastname;
				var address = data[0].address;
				var email = data[0].email;
				var status = data[0].status;
				$("#review_edit_form").trigger("reset");
				$("#userf_name").val(firstname);
				$("#userl_name").val(lastname);
				$("#user_address").val(address);
				$("#user_email").val(email);
				if(status=="0")
				$("#user_approved").val(0);
				else
				$("#user_approved").val(1);
				$("#current_uid").val(data[0].id)
			}
		});
	});
	
	$("#update_rev").click(function(){
		var uid = $("#current_uid").val();
		
		if(parseInt(uid)==0)
		{
			alert("Can't save")
			return false;
		}
		var firstname = $("#userf_name").val();
		var lastname = $("#userl_name").val();
		var address = $("#user_address").val();
		var email= $("#user_email").val();
		var approve = $("#user_approved").val();
		var cuid = $("#current_uid").val()
		if( (firstname!='') && (lastname!='') && (address!='') && (email!='') && (approve!='') && (cuid!=''))
		{
			$.ajax({
				url: location.href,
				data: { 
					saveUser: 1,
					uid: uid,
					userf_name : $("#userf_name").val(),
					userl_name : $("#userl_name").val(),
					user_address : $("#user_address").val(),
					user_approved : $("#user_approved").val()
					
				},
				beforeSend: function(){
					$('#Loading_div').html("<img style='margin-top:10px;' src='../img/ajax-loader.gif'>");
				},
				complete: function(){
					$('#Loading_div').html("");
				},
				success: function(data){
					if(data.trim()=='1')
					{
						alert("Success");
						window.location.href='users.php';
					}
					else
						alert("Can't save at this moment");
					//getData('icon-circle-arrow-up','cust_cname');
				}
			});
		}
		else
		{
			document.getElementById('modalerror').innerHTML = 'Please fill all the details';
			$('#modalerror').show();
			setTimeout(function (){ $('#modalerror').hide(); }, 3000);
		}
	})
	$(document).on("click", ".fa-trash", function(){
		if(confirm("Are you sure to delete?"))
		{
			var uid = $(this).parent().attr('revid');
			$.ajax({
				url: location.href,
				data: { 
					deleteUser: 1,
					uid: uid
				},
				beforeSend: function(){},
				complete: function(){},
				success: function(data)
				{
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
      <?php include("sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Users List</h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Users</li>
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
                        <div class="col-lg-6 col-sm-9 col-xs-12">
                            <div id="example1_filter">
                            	<form Onsubmit="return false;">
                                	<input type="text" class="form-control" placeholder="Search" value="" name="search" id="keyword_search">
                                    <button id="search_button" class="btn btn-primary">Search</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            
                        </div>
                    </div>
                  <div class="row">
                      <div class="col-lg-12 col-sm-12 col-xs-12">
                          <div class="table-responsive"><table id='data-table' class="table table-bordered table-striped">
                            <thead>
                              <tr>
							<th>First Name <a><div id='f_cname' class="icon-circle-arrow-up"></div></a></th>
							<th>Last Name<a><div id='l_cname' class="icon-circle-arrow-up"></div></a></th>
							<th>Address</th>
							<th>Email</th>
							<th class="acenter">Status<a><div id='u_status' class="icon-circle-arrow-up"></div></a></th>
							<th class="actions text-center">Actions</th>
						</tr>
                            </thead>
                            <tbody>
                            
                            </tbody>
                          </table></div>
                          <div class="row">
                              <div class="col-lg-12 col-sm-12 col-xs-12">
                              <div>
                                <ul class="pagination" id="paginationDiv" style="float:right">
                                </ul>
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
      	<div class="modal fade" id="editreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title LRhd" id="myModalLabel">Edit User</h4>
			  </div>
			  <div class="modal-body">
				<div class="alert alert-danger" id="modalerror" style="text-align:center; display:none"></div>
			   <form method="post"  action="" id="review_edit_form" name="" class="xform">
					<div class="form-group">
							<label><b>First Name:</b></label>
							<input type="text" name="userf_name" id="userf_name" placeholder="First Name" value="" class="required form-control">
					</div>
					<div class="form-group">
							<label><b>Last Name:</b></label>
							<input type="text" name="userl_name" id="userl_name" placeholder="Last Name" value="" class="required form-control">
					</div>					
					<div class="form-group">
							<label><b>Address:</b></label>	
							<!--<textarea id="user_address" name="user_address" placeholder="Address" class="form-control required"></textarea>-->
							<textarea class="form-control required" id="user_address" name="user_address" placeholder="Address"></textarea>
					</div>
					<div class="form-group">
							<label><b>Email:</b></label>
							<input type="text" name="user_email" id="user_email" placeholder="e-mail" class="form-control" value="" readonly>
					</div>
					<div class="form-group">
							<label><b>Status:</b></label>
							<select id="user_approved" name="user_approved" class="form-control">
								<option value='1'>Approved</option>
								<option value='0'>Not Approved</option>
							</select>
					</div>
                    <div class="form-group">
						<button type="button" class="btn btn-primary" id="update_rev" class="Update">Save</button>
                        </div>
						<input type='hidden' id='current_uid'>
						<div class="col-md-12" id="Loading_div"></div>
					</form>
				</div>
		   
			</div>
		</div>
	</div>
    <input type='hidden' id='current_order_ele' bind='f_cname' order='icon-circle-arrow-up'>
	<input type='hidden' id='current_page' value='1'>
	<img id='loading_img' align='center' style='display:none;margin-left:43%' src="../img/ajax-loader.gif">
      <?php include("footer.php"); ?>
