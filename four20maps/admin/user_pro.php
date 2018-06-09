<?php
$heading='store';
// include Config File
include_once './includes/config.inc.php';
include_once './includes/class.database.php';
// Authenticate user login
auth();
$db = db_connect();
function create_links($total_rows=0, $per_page=0, $cur_page=0,$num_links=10)
	{
		
		if ($total_rows == 0 OR $per_page == 0)
		return '';
		
		$num_pages = (int) ceil($total_rows / $per_page);
	
		if ($num_pages === 1)
		return '';

		
		if($cur_page > $num_pages)
			$cur_page = $num_pages;		

		if($cur_page<$num_links)
		{
			$start	= 1;
			if($num_links > $num_pages) 
				$end = $num_pages;	
			else
				$end = $num_links;
		}
		else
		{
			$start	= $cur_page;
			if(($cur_page + $num_links) > $num_pages) 
				$end = $num_pages;	
			else
				$end = $num_links;
		}
		
		$output = '';
		// Render the "First" link.
		if ($cur_page > 1)
		$output .= '<li page="1"><a aria-label="First" href="#"> <span aria-hidden="true">First</span></a></li>';

       
      
		// Render the "Previous" link.
		if ($cur_page !== 1 && $cur_page>1)
		$output .= '<li page="'.($cur_page-1).'"><a href="#">Previous</li></a>';

		for ($loop = $start; $loop <= $end; $loop++)
		{
			if (intval($cur_page) == intval($loop))
				$output .= "<li page='$loop' class='active'><a href='#'>".$loop."</a></li>";
			else
				$output .= "<li page='$loop'><a href='#'>".$loop."</a></li>";
		}	
		

		if($cur_page < $num_pages-1)
		$output .= "<li page='$num_pages'><a href='#'>Last</a></li>";

		return $output;
	}
if($_POST)
{
	$page = $_POST['page'];
	$catname = $_POST['catname'];
	$lstart = (intval($page)-1)*10;
	$lend = 10;
	$sql = "SELECT su.Username,p.*,p1.categoryName AS ParentCatName FROM ProductCategory p
		LEFT JOIN ProductCategory p1 ON p.parentID= p1.categoryID
		LEFT JOIN StoreUsers su ON su.`UserId`= p.UserId
		WHERE p.parentId!=0";
	
	if($catname!='')
		$sql.= "  AND p.categoryName like '$catname' ";	
	$vsql = $sql;
	$to = mysql_query($vsql);
	while($t = mysql_fetch_assoc($to))
	{
		$tot[] = $t; 
	}
	$sql.= " GROUP BY p.categoryID order by p.categoryID DESC LIMIT $lstart, $lend ";
	$total = mysql_num_rows($to);
	$users = mysql_query($sql);
	while($details = mysql_fetch_assoc($users))
	{
		$data[] = $details;
	}
	$cats = array("total"=>$total,"cat"=> $data);
	$per_page=10; $num_links=10;
	$output = create_links($total, $per_page, $page=0,$num_links=10);
	
		echo json_encode(array("cat"=>$cats['cat'],"pagination"=>$output));
		die;
}
?>
	  <?php include("header.php"); ?>
      <!-- Left side column. contains the logo and sidebar -->
      
      

      <?php include("sidebar.php"); ?>
<script>
var cpage=1;
	$(function(){
	all_search_cat(cpage);
})

		function all_search_cat(page)
		{
			$.ajax({
				url: "",
				type:"POST",
				ataType: "json",
				data:
				{
					catname: $("#cat_name").val(),
					page: page
				},
				/*beforeSend:function(){
					loading.show();
				},
				complete:function(){
					loading.hide();
				},*/
				success:function(data)
				{
					data = $.parseJSON(data);
					$("#cat_table").find('tbody').empty();
					var html='';
					if(data.cat!=null)
					{
						$.each(data.cat, function(i){
							var item = data.cat[i];
							var status = '';
							if((item.isActive)==1)
								status = 'Active';
							else
								status = 'In - Active';
							html+="<tr><td>"+item.Username+"</td><td>"+item.categoryName+"</td>"+"<td>"+item.ParentCatName+"</td><td><img src='http://www.four20maps.com/"+item.image+"' style='height:30px'/></td><td>"+item.categoryType+"</td><td>"+status+"</td><td style='text-align:center'><a href='#' data-toggle='modal' data-target='#cat_modal' OnClick='editcat("+item.categoryID+","+item.UserId+")' title='Edit' ><i class='fa fa-pencil'></i></a>&nbsp<a  href='#' OnClick='del1("+item.categoryID+")' title='Delete'><i class='fa fa-trash-o'></i></a></td></tr>";
						}) 
						$("#cat_table").find('tbody').html(html);
						$("#pagination").html(data.pagination);
						$(document).find(".pagination li").on("click",function(){
							cpage=$(this).attr('page');
							all_search_cat($(this).attr('page'));
						});
						$('#loadingDiv').hide();
					}
					else
					{
						var html= 'No Results Found..';
						$("#cat_table").find('tbody').html(html);
						$("#pagination").html('');
						$(document).find(".pagination li").on("click",function(){
							cpage=$(this).attr('page');
							all_search_cat($(this).attr('page'));
						});
						$('#loadingDiv').hide();
					}
				}
			});
		}
</script>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>User Products</h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">User Products</li>
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
					<div class="alert alert-success" style="text-align:center; display:none" id="editalert"></div>
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            <div id="example1_filter">
								<form method="post" Onsubmit="return false;">
									<input type="text" id="cat_name" class="form-control" placeholder="Search By Product Name" />
									<button class="btn btn-primary" name="submit" Onclick="all_search_cat(1);"> Search</button>
								</form>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            <div class="dataTables_length" id="example1_length">
                            <?php //	<a style="float:right" href="addsubs.php" class="btn btn-primary">Add New Subscription</a> ?>
                            </div>
                        </div>
                    </div>
                  <div class="row">
                      <div class="col-lg-12 col-sm-12 col-xs-12">
                          <div class="table-responsive">
                          <table class="table table-bordered table-striped" id="cat_table">
                            <thead>
                                <tr>
								<th>User Name</th>
                                <th>Product Name</th>
                                <th>Category Name</th>
                                <th>Image</th>
                                <th>Product Type</th>
								<th>Status</th>
                                <th class="text-center">Action</th>
                                </tr>
							</thead>
							<div class="col-md-12" align="center" id="loadingDiv">
								<img src="../img/loading.gif" style="height:35px;" class="img-responsive"/>
							</div>
							<tbody>
							</tbody>
						</table>
						</div>
						<div class="col-md-12 text-right">
								<ul id="pagination" class="pagination"></ul>
							</div>
                      </div>
                  </div></div>
                </div><!-- /.box-body -->
              </div>
            </div>

          </div><!-- /.row -->

        </section><!-- /.content -->
	</div><!-- /.content-wrapper -->
	<div class="modal fade" id="cat_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title LRhd" id="myModalLabel">Edit Product</h4>
				</div>
				<div class="modal-body">
					<div class="col-md-12" align="center" id="loadingDiv1">
						<img src="../img/loading.gif" style="height:35px;" class="img-responsive"/>
					</div>
					<div class="alert alert-danger" id="editerror" style="text-align:center; display:none"></div>
					<form method="post"  Onsubmit="return false;" id="editform"  class="xform">
						<div class="form-group">
							<label><b>Product Name:</b></label>
							<input type="text" name="product_name" Onblur="names(this.id);" id="product_name" placeholder="Product Name" value="" class="required form-control">
						</div>
						<div class="form-group">
							<label><b>Category Name:</b></label>
							<select class="form-control required" id="category_name" name="category_name"></select>
						</div>		
						<div class="form-group">					
							<label><b>Image:</b></label>
							<img id="image" style="height:50px"/>
						</div>
						<div class="form-group">
							<label><b>Product Type:</b></label>	
							<select name="categorytype" id="categorytype" class="form-control">
							<option value="Indica">Indica</option>
							<option value="Sativa">Sativa</option>
							<option value="Hybrid">Hybrid</option>
							</select>
						</div>
						<div class="form-group">
							<label><b>Status:</b></label>
							<select id="pro_Status" name="pro_Status" class="form-control">
							<option value='1' id="one">Approved</option>
							<option value='0' id="two">Not Approved</option>
							</select>
						</div>
						<input type="hidden" value="11" name="type" id="type"/>
						<div class="form-group">
							<button type="button" class="btn btn-primary" Onclick="EditSubmit();" class="Update">Save</button>
								<button class="btn btn-danger" Onclick="$('#cat_modal').modal('hide'); return false;">Cancel</button>
						</div>
						<input type='hidden' id='current_pid' name="current_pid" value=''>
						<div class="col-md-12" id="Loading_div"></div>
					</form>
				</div>
			</div>
		</div>
	</div>	
	  <?php include("footer.php"); ?>	 
<script>
function  editcat(id,UserId)
{
	var type = $('#type').val();
	 $.ajax({
    url: "storesdb.php",
    type: "POST",
    data: {'id':id, 'UserId':UserId, 'type':9},
	  beforeSend: function(){
			$("#loadingDiv1").show();
		},
		complete: function(){
			$("#loadingDiv1").hide();
		},
	success: function(data)
    {
		//console.log(data); return;
		data = $.parseJSON(data.trim())
		$('#current_pid').val(data.data.categoryID);
		$('#product_name').val(data.data.categoryName);
		$('#image').attr('src','http://www.four20maps.com/'+data.data.image);
		$('#categorytype').val(data.data.categoryType);
		$('#pro_Status').val(data.data.isActive);
		var html = '';
		$.each(data.cats, function(i){
			var item = data.cats[i];
			//console.log(item.categoryID);
			html+= "<option value='"+item.categoryID+"'>"+item.categoryName+"</option>";
		});
		$('#category_name').html(html);
		$('#category_name').val(data.data.parentID);
	}
	 });
}

function names(name)
{
	var namesPattern = /^[a-zA-Z ]*$/;
	if(!namesPattern.test($('#'+name).val()))
	{
		$('#'+name).css('border-color','red');
		$('#'+name).val('');
	}
	else
		$('#'+name).css('border-color','');
}

function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) 
	{
		limitField.value = limitField.value.substring(0, limitNum);
	} 
	else
	{
		limitCount.value = limitNum - limitField.value.length;
	}
}

function EditSubmit()
{
	var current_pid = $('#current_pid').val();
	if(current_pid!='')
	{
		var pname = $.trim($('#product_name').val());
		var ctype = $.trim($('#categorytype').val());
		var pstat = $('#pro_Status').val();
		var cname = $('#category_name').val();
		if((pname!='') && (ctype!='') && (pstat!='') && (cname!=''))
		{
			var formdata = $('#editform').serializeArray();
			$.ajax({
				url: "storesdb.php",
				type: "POST",
				data: formdata,
				success: function(data)
				{
					if(data == 1)
					{
						document.getElementById('editalert').innerHTML = 'Updated Successfully';
						$('#cat_modal').modal('hide');
						$('#editalert').removeClass('alert-danger');
						$('#editalert').addClass('alert-success');
						$('#editalert').show();
						setTimeout(function (){$('#editalert').hide(); }, 4000);
						all_search_cat(cpage);
					}
					else
					{
						document.getElementById('editalert').innerHTML = 'Unable to Update.. Please try again';
						$('#cat_modal').modal('hide');
						$('#editalert').removeClass('alert-success');
						$('#editalert').addClass('alert-danger');
						$('#editalert').show();
						setTimeout(function (){$('#editalert').hide(); }, 4000);
					}
				}
			});
		}
		else
		{
			document.getElementById('editerror').innerHTML = 'Please Fill all the details';
			$('#editerror').show();
			setTimeout(function (){$('#editerror').hide(); }, 4000);
		}
	}
	else
	{
		document.getElementById('editalert').innerHTML = 'Invalid Update Or Invalid Category. Please try again';
		$('#cat_modal').modal('hide');
		$('#editalert').removeClass('alert-success');
		$('#editalert').addClass('alert-danger');
		$('#editalert').show();
		setTimeout(function (){$('#editalert').hide(); }, 4000);
	}
}

function del1(catid)
{ 
	if (confirm("Dou You really want to delete ?"))
	{
		$.ajax({
		url: "storesdb.php",
		type: "POST",
		data: {'catid':catid, 'type':12},
		  beforeSend: function()
			{
				$("#loadingDiv").show()
			},
			complete: function()
			{
				$("#loadingDiv").hide();
			},
			success: function(data)
			{
				if(data==1)
				{
					document.getElementById('editalert').innerHTML = 'Deleted Successfully';
					$('#editalert').removeClass('alert-danger');
					$('#editalert').addClass('alert-success');
					$('#editalert').show();
					setTimeout(function (){$('#editalert').hide(); }, 4000);
					all_search_cat(cpage);
				}
				else
				{
					document.getElementById('editalert').innerHTML = 'Please try again';
					$('#cat_modal').modal('hide');
					$('#editalert').removeClass('alert-success');
					$('#editalert').addClass('alert-danger');
					$('#editalert').show();
					setTimeout(function (){$('#editalert').hide(); }, 4000);
				}
			}
		});
	}
}
</script>