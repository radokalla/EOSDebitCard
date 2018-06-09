<?php
$heading='store';
// include Config File
include_once './includes/config.inc.php';
include_once './includes/class.database.php';
include_once './includes/validate.php';
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
	$subname = $_POST['subname'];
	$lstart = (intval($page)-1)*10;
	$lend = 10;
	$sql = "select * from SubscriptionTypes where IsDeleted='0' ";
	if($subname!='')
		$sql.= "  and Subscription like '$subname' ";	
	$vsql = $sql;
	$to = mysql_query($vsql);
	while($t = mysql_fetch_assoc($to))
	{
		$tot[] = $t; 
	}
	$sql.= " GROUP BY SubscriptionTypeId order by OrderId DESC LIMIT $lstart, $lend ";
	$total = mysql_num_rows($to);
	$users = mysql_query($sql);
	while($details = mysql_fetch_assoc($users))
	{
		$data[] = $details;
	}
	$sub = array("total"=>$total,"sub"=> $data);
	$per_page=10; $num_links=10;
	$output = create_links($total, $per_page, $page=0,$num_links=10);
		echo json_encode(array("subs"=>$sub['sub'],"pagination"=>$output));
		die;
}
$sql = "select * from SubscriptionCategory where  `Status` =1 and `IsDeleted`=0";	 
	$subscriptionCategory = mysql_query($sql);
	while($subscriptionCategoryDetails = mysql_fetch_assoc($subscriptionCategory))
	{
		$subscriptionCategoryData[] = $subscriptionCategoryDetails;
	}
?>
	  <?php include("header.php"); ?>
      <!-- Left side column. contains the logo and sidebar -->
      
      

      <?php include("sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Store Subscriptions</h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Store Subscriptions</li>
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
						<div class="alert alert-success" id="msg" style="text-align:center; display:none"></div>
						<div class="dataTables_wrapper form-inline dt-bootstrap">
						<div class="row">
							<?php echo notification(); ?>
							<?php if(!empty($_SESSION['message']))
								{ ?>
									<div class="alert alert-success fade in" id="message" style="text-align:center"><?php echo $_SESSION['message']; unset($_SESSION['message']);  ?> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </div>
							<?php } ?>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div id="example1_filter">
									<form method="post" Onsubmit="return false;">
         <div class="row">
         	<div class="col-lg-10 col-md-9 col-sm-8 col-xs-8">
										<input type="text" id="subname" name="subname" class="form-control" placeholder="Enter Subscription Name" style="width:100%;" /></div>
         	<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4" style="padding-left:0px;">
										<button class="btn btn-primary btn-block" name="submit" Onclick="subscriptions(1);">Search</button></div>
         </div>
									</form>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div class="dataTables_length text-right" id="example1_length">
									<a href="suborder.php" class="btn btn-primary">Sort </a>
									<a href="addsubs.php" class="btn btn-primary">Add New</a>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-sm-12 col-xs-12">
								<div class="table-responsive">
									<table class="table table-bordered table-striped" id="subs_table">
										<thead>
											<tr>
												<th>Subscription Name</th>
												<th>Description</th>
												<th>Duration</th>
												<th>Initial Amount</th>
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
					</div>
				</div>
            </div>

          </div><!-- /.row -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      	
        <div class="modal fade" id="eDIT" tabindex="-1" role="dialog" aria-labelledby="eDIT" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title LRhd" id="myModalLabel">Edit Subscription</h4>
			  </div>
			  <div class="modal-body">
			   <form method="post"  action="" id="Subs_edit_form" name="" class="xform">
			   
			   <div class="form-group">
							<label class="input">Subscription Category</label>
							 <select class="form-control" id="SubscriptionCategory" name="SubscriptionCategoryId">
							 	<?php foreach($subscriptionCategoryData as $subscriptionCategory) { ?>
							 	 		<option value="<?=$subscriptionCategory["SubscriptionCategoryId"];?>"><?=$subscriptionCategory["Subscription"];?></option>
							 	<?php } ?>
							 </select>
						</div>
						<div class="form-group">
							<label class="input">Subscription</label>
							<input  type="text" name="Subscription" OnBlur="func(this.id);" id="Subscription" placeholder="Subscription Name" value="" class="required form-control">
						</div>
						<div class="row">
								<div class="col-lg-6 col-sm-12">
									<div class="form-group">
                                    	<label class="input">Duration</label>
                                    	<input  type="text" name="Duration" id="Duration" placeholder="Enter Only numbers" value="" OnBlur="nums(this.id);" class="required form-control">
                                    </div>
								</div>
								<div class="col-lg-6 col-sm-12">
                                	<div class="form-group">
                                        <label>&nbsp;</label>
                                        <select class="form-control" id="durationType" name="durationType" required>
                                            <option value='' disabled>Select Duration type</option>
                                            <option value="days" id="opt1">days</option>
                                            <option value="months" id="opt2">months</option>
                                            <option value="years" id="opt3">years</option>
                                        </select>
                                    </div>
								</div>
						</div>
                        <p style="color:red">Change in Duration doesn't effects Present Subscriber's.</p>
						<div class="form-group">
							<label class="input">Description</label>
							<textarea name="Description" id="Description" onKeyDown="limitText(this.form.Description,this.form.countdown,25);" onKeyUp="limitText(this.form.Description,this.form.countdown,25);" OnBlur="limitText(this.form.Description,this.form.countdown,25);"
							placeholder="Description" class="form-control" ></textarea>
						</div>
						<div class="form-group">
							<label class="input">Initial Amount</label>
							<input  type="text" name="IntialAmount" id="InitialAmount" OnBlur="nums(this.id);" placeholder="Intial Amount" value="" class="required form-control">
						</div>
						<div class="form-group">
							<label class="input">Renewal Amount</label>
							<input  type="text" name="RenualAmount" id="RenualAmount" OnBlur="nums(this.id);" placeholder="Renual Amount" value="" class="required form-control">
						</div>
						<!--<div class="form-group">
							<label>Order</label>
							<select id="order" name="order" class="form-control">
									<option value='abcd' selected>No Change</option>
								<?php for($i=1; $i<=10; $i++){ ?>
									<option value='<?php echo $i; ?>'><?php echo $i; ?></option>
								<?php } ?>
							</select>
						</div>-->
						<div class="form-group">
							<label>Status</label>
							<select id="Status" name="Status" class="form-control">
								<option value='1' id="option1">Active</option>
								<option value='0' id="option2">In-Active</option>
							</select>
						</div>
							<div class="form-group">
								<button type="button" class="btn btn-primary" OnClick="EditSubmit();">Save</button>
								<button class="btn btn-danger" Onclick="$('#eDIT').modal('hide'); return false;">Cancel</button>
							</div>
							<input type="hidden" name="orid" value="" id ="orid"/>
							<input type="hidden" name="type" value="3"/>
							<input type="hidden" name="SubId" value="" id="SubId"/>
					</form>
				</div>
		   
			</div>
		</div>
	</div>
<script>
var cpage=1;
	$(function(){
	subscriptions(cpage);
})

function subscriptions(page)
{
	$.ajax({
		url: "",
		type:"POST",
		ataType: "json",
		data:
		{
			subname: $("#subname").val(),
			page: page
		},
		beforeSend:function(){
			$("#loadingDiv").show();
		},
		complete:function(){
			$("#loadingDiv").hide();
		},
		success:function(data)
		{
			data = $.parseJSON(data);
			$("#subs_table").find('tbody').empty();
			var html='';
			if(data.subs!=null)
			{
				$.each(data.subs, function(i){
					var item = data.subs[i];
					var status = '';
					if((item.Status)==1)
						status = 'Active';
					else
						status = 'In - Active';
					html+="<tr><td>"+item.Subscription+"</td><td>"+item.Description+"</td>"+
						"<td>"+item.Duration+"</td><td>"+item.InitialAmount+"</td><td>"+status+"</td><td style='text-align:center'><a href='editsubs.php?id="+item.SubscriptionTypeId+"'  ><i class='fa fa-pencil'></i></a>&nbsp<a  href='#' OnClick='DelSub("+item.SubscriptionTypeId+");' title='Delete' ><i class='fa fa-trash-o'></i></a></td></tr>";
				})
				$("#subs_table").find('tbody').html(html);
				$("#pagination").html(data.pagination);
				$(document).find(".pagination li").on("click",function(){
					cpage=$(this).attr('page');
					subscriptions($(this).attr('page'));
				});
				$('#loadingDiv').hide();
			}
			else
			{
				var html= 'No Results Found..';
				$("#subs_table").find('tbody').html(html);
				$("#pagination").html('');
				$(document).find(".pagination li").on("click",function(){
					cpage=$(this).attr('page');
					subscriptions($(this).attr('page'));
				});
				$('#loadingDiv').hide();
			}
		}
		
		
	})
}
		
function editdata(id)
{
	var id = id;
	var type = 2;
		$.ajax({
				type: "POST",
				url: "storesdb.php",
				data : {'type':type, 'id':id},
				success: function(data)
				{
					 var obj = JSON.parse(data);
					 $('#SubscriptionCategory').val(obj.SubscriptionCategoryId);
					 $('#Subscription').val(obj.Subscription);
					 var duration = obj.Duration;
					 var dur = duration.split(" ");
					 $('#Duration').val(dur[0]);
					 $('#durationType').val(dur[1]);
					 $('#Description').val(obj.Description);
					 $('#InitialAmount').val(obj.InitialAmount);
					 $('#RenualAmount').val(obj.RenualAmount);
					 $('#SubId').val(obj.SubscriptionTypeId);
					 $('#orid').val(obj.OrderId);
					 var status = obj.Status;
					 if(status == "1")
					 {
						 $('#option1').attr("selected","selected");
					 }
					 else
					 {
						 $('#option2').attr("selected","selected");
					 }
					
				}
			});
}

function EditSubmit()
{
	var formdata = $('#Subs_edit_form').serializeArray();
	var Subscription = $('#Subscription').val();
	var Duration = $('#Duration').val();
	var durationType = $('#durationType').val();
	var Description = $('#Description').val();
	var InitialAmount = $('#InitialAmount').val();
	var RenualAmount = $('#RenualAmount').val();
	var Status = $('#Status').val();
	var SubId = $('#SubId').val();
	if((Subscription!='') && (Duration!='') && (durationType!=null) && (Description!='') && (InitialAmount!='') && (RenualAmount!='') && (Status!='')&& (SubId!=''))
	{
		$.ajax({
				type: "POST",
				url: "storesdb.php",
				data : formdata,
				success: function(data)
				{
					if(data==1)
					{
						$('#eDIT').modal('hide');
						document.getElementById('msg').innerHTML = 'Update Successful..';
						$('#msg').show();
						setTimeout(function (){window.location.href = ""; }, 3000);
					}
				}
				});
	}
	else
	{
		alert('Fill all the details');
	}
}

function DelSub(SuBid)
{
	var type = '4';
	if(confirm("Are you sure you want to delete ??"))
	{
		$.ajax({
				type: "POST",
				url: "storesdb.php",
				data : {'type':type, 'subId':SuBid},
				success: function(data)
				{
					document.getElementById('msg').innerHTML = 'Deleted Successfully';
					$('#msg').removeClass('alert-success');
					$('#msg').addClass('alert-warning');
					$('#msg').show();
					setTimeout(function (){$('#msg').hide(); }, 3000);
					subscriptions(cpage);
				}
			});
	}
}

	$(document).ready (function(){
            $("#message").hide();
                $("#message").alert();
                $("#message").fadeTo(2000, 500).slideUp(500, function(){
               $("#message").alert('close');   
            });
 });
 
function nums(num)
{
	var numPat = /^[0-9]+$/;
	var number = $('#'+num).val();
	if(!numPat.test(number))
	{
		$('#'+num).css('border-color','red')
		$('#'+num).val('');
	}
	else
	{
		$('#'+num).css('border-color','')
	}
}

function limitText(limitField, limitCount, limitNum) 
{
	if (limitField.value.length > limitNum) 
	{
		limitField.value = limitField.value.substring(0, limitNum);
	} 
	else
	{
		limitCount.value = limitNum - limitField.value.length;
	}
}

function func(id)
{
	var namesPattern = /^[a-zA-Z ]*$/;
	var name = $('#'+id).val();
	if(!namesPattern.test(name))
	{
		$('#'+id).css('border-color','red')
		$('#'+id).val('');
	}
	else
	{
		$('#'+id).css('border-color','#eee')
	}
}
</script>
<?php include("footer.php"); ?>
