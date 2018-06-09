<?php
$heading='adds';
ob_start();
include_once './includes/config.inc.php';

// Authenticate user login

auth();
/*echo "<pre>";
print_r($_POST);exit;*/
error_reporting(0);
$db = db_connect();
	if(isset($_POST['save']))
	{
		/*if($_GET['status']=='InActive')
		{*/
			$stdate=$_POST['stdate'];
			$Enddate = $_POST['Enddate'];
			$timestamp = strtotime($stdate);
			 $stdate = date("Y-m-d", $timestamp);
			 
			 $timestamp = strtotime($Enddate);
			 $Enddate = date("Y-m-d", $timestamp);
			$statuss= $_POST['statuss'];
			$idd = $_POST['iddd'];
			$last_modified = date('Y-m-d H:i:s',time());
		/*$sratrtdate = date("Y-m-d");
		
		$enddate = date("Y-m-d",strtotime("+7 day"));
			
		$aid=$_GET['aid'];
		*/
		$selquery="Update adds set start_date='".$stdate."',end_date='".$Enddate."', last_modified='$last_modified',status='".$statuss."' where aid=".$idd;
		mysql_query($selquery);
		
				/*$m=$db->dateRange($sratrtdate,$enddate);
		echo "<pre>";
		print_r($m); exit;*/
		
		$msg =  "Successfully ".$statuss;
		/* }
		else{
		$aid=$_GET['aid'];
		$qry ="UPDATE adds SET status = 'InActive' WHERE aid=".$aid;
		mysql_query($qry);
		$msg = "Successfully InActivated";			
		}*/
		
	
		
	}
	if($_REQUEST['deleteAll'])
	{
		mysql_query("Truncate table adds");
		die;
	}
$todays_date = date("Y-m-d");
$expire_statusquery="select aid,status,start_date,end_date from adds where status='Active' and is_delete=0";
$qr_exc=mysql_query($expire_statusquery);

while($fetchr=mysql_fetch_assoc($qr_exc))
{
	$dateperioud[]=$fetchr;
}
$todays_date = date("Y-m-d");
foreach($dateperioud as $cnt)
{
	$aidm=$cnt['aid'];
	$end_ate=$cnt['end_date'];
		
	
	$today= strtotime($todays_date);
$expiration_date = strtotime($end_ate);

if($expiration_date < $today) {
	
	$qry ="UPDATE adds SET status = 'InActive' WHERE aid=".$aidm;

}	
}



if(isset($_GET['action']) && $_GET['action']=='delete') {


	//if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		if(isset($_GET['id'])) {
		
		$id=$_GET['id'];
		$query="update adds set is_delete='1' where aid=".$id;
		mysql_query($query);
		
	
		

		$_SESSION['notification'] = array('type'=>'good','msg'=>"Deleted Successfully");

		redirect(ROOT_URL.'addslist.php');

	}





	$db = db_connect();
	

	if($db->delete('adds', $_GET['aid'])) {
		/*$id=$_GET['aid'];
		$query="update adds set is_delete='1' where aid=$id";
		echo "<pre>";echo $query;exit;
		*/
		

		$_SESSION['notification'] = array('type'=>'good','msg'=>$lang['ADD_USER_DELETED']);

	} else {

		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['ADMIN_DELETE_USER_FAILED']);

	}

redirect(ROOT_URL.'addslist.php');

}





$db = db_connect();


if(intval($_GET['perpage'])>0)
$limit = intval($_GET['perpage']); 
else
$limit = 10; 



if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  

$start_from = ($page-1) * $limit;  


$useradd = $db->get_rows("SELECT adds.* FROM adds where is_delete=0 ORDER BY aid desc LIMIT $start_from, $limit");
?>

	  <?php include("header.php"); ?>
      <!-- Left side column. contains the logo and sidebar -->
      <?php include("sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1><?php echo $lang['ADMIN_ADD_ADDS']; ?></h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><?php echo $lang['ADMIN_ADD_ADDS']; ?></li>
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
					<?php $alert = notification(); if(!empty($alert)){ ?>
								<div class="alert alert-success" style="text-align:center" id="alert"><h6><?php echo $alert; ?></h6></div>
					<?php } ?>
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div id="example1_filter">
                        		<a id="deleteAllAds" class="btn btn-danger">Delete All</a>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="dataTables_length text-right" id="example1_length">
                            	<a href="#" data-toggle="modal" data-target="#myModal4" class="btn btn-primary"><?php echo $lang['USERADD_ADDNEW']; ?></a>
							<span id='imgloading'></span>
                            </div>
                        </div>
						
                    </div>
                  <div class="row">
                      <div class="col-lg-12 col-sm-12 col-xs-12">
                          <div class="table-responsive"><table id="example1" class="table table-bordered table-striped">
                            <thead>
                              <tr>

					<th><?php echo $lang['USERADD_NAME']; ?></th><th><?php echo $lang['USERADD_EMAIL']; ?></th><th><?php echo $lang['USERADD_PHONENUMBER']; ?></th><th><?php echo $lang['USERADD_IMAGE']; ?></th><th><?php echo $lang['USERADD_URL']; ?></th><th><?php echo $lang['USERADD_STATUS']; ?></th><th class="actions text-center"><?php echo $lang['ADMIN_ACTION']; ?></th>

				</tr>
                            </thead>
                            <tbody>
                          <?php if(!empty($useradd)): ?>

					<?php foreach($useradd as $k=>$v): ?>
                              <tr class='<?php echo ($k%2==0) ? 'odd':'even'; ?>'>

						<td><?php echo $v['Name']; ?></td>

						<td><?php echo $v['Email']; ?></td>
						<td><?php echo $v['PhoneNumber']; ?></td>
						<td><img src="../admin/uploads/<?php echo $v['image']; ?>" style="height:100px;width:115px"></td>
						<td><?php echo $v['url']; ?></td>
						<td>
						
						<?php /*<a href="./addslist.php?status=<?php echo $v['status']; ?>&aid=<?php echo $v['aid'];?>" data-toggle="modal" data-target="#statusmd"><?php echo $v['status']; ?></a> */?>
						<a href='javascript:delItem(<?php echo $v['aid']; ?>)' onclick="ajstatus(<?php echo $v['aid']; ?>);" data-toggle="modal" data-target="#exampleModal"><?php echo $v['status']; ?></a>
						</td>

						<td class="actions text-center">

							<a href='./addadds.php?id=<?php echo $v['aid']; ?>'><i class="fa fa-pencil"></i></a>

							<a href='javascript:delItem(<?php echo $v['aid']; ?>)' class="confirm_delete"><i class="fa fa-trash"></i></a>

							

						</td>

					</tr>
                             <?php endforeach; ?>

				<?php else: ?>

					<tr>

						<td colspan="7">No advertisements</td>

					</tr>

				<?php endif; ?>
                            </tbody>
                          </table></div>
                          <div class="row">
                          <div class="col-lg-6 col-sm-6 col-xs-12">
                          </div>
                              <div class="col-lg-6 col-sm-6 col-xs-12">
                              <div class="dataTables_paginate paging_simple_numbers">
							 <?php  

			$sql = "SELECT COUNT(aid) FROM adds";  

			$rs_result = mysql_query($sql);  

			$row = mysql_fetch_row($rs_result);  

			$total_records = $row[0];  

			$total_pages = ceil($total_records / $limit);  

			$active = "";


			
			$pagLink = "<div><ul class='pagination'>";  

			for ($i=1; $i<=$total_pages; $i++) { 

					if(isset($_GET['page'])){

						if($i==$_GET["page"]){

						  $active="active";

						} else {

						   $active="";

						}

					}

						 $pagLink .= "<li class='paginate_button $active'><a href='addslist.php?page=".$i."&perpage=$limit'>".$i."</a></li>";  

			};  
			$l10='';
			$l20='';
			$l50='';
			if($limit==10)
				$l10='selected';
			else if($limit==20)	
				$l20='selected';
			else if($limit==50)	
				$l50='selected';
			$pp ="<select onChange='changePP(this.value)'><option $l10>10</option><option $l20>20</option><option $l50>50</option></select>";
			if($total_records<10){echo $pagLink;} "</ul></div>";   

		?>  
			
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
<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title LRhd" id="myModalLabel3">Add Adds</h4>
			  </div>
			  <div class="modal-body">
			   <form method="post"  action="" id="add_form" name="" class="xform">
							<div class="form-group">
								<label>Name: *</label>
								<input type="text" name="adduser_name" id="adduser_name" placeholder="Enter your full Name" value="" class="required form-control">
							</div>
                            <div class="form-group">
								<label>Email: *</label>
								<input type="text" name="adduser_email" id="adduser_email" placeholder="Email" value="" class="required form-control">	
							</div>
							<div class="form-group">
								<label>Phone: *</label>
								<input  type="text" name="adduser_phone" id="adduser_phone" placeholder="Phone" value="" class="required form-control">
							</div>
                            <div class="form-group">	
								<label>URL: *</label>
								<input type="text" name="adduser_url" id="adduser_url" placeholder="URL" value="" class="required form-control">
								
							</div>
							<div class="form-group">
								<input type='file' name="adduser_image" id="adduser_image" placeholder="Image" accept="image/*">
							</div>
						 	<div class="form-group">
							<button type="button" class="btn btn-primary" name="update" Onclick="addEnquiry();" class="Update">Submit</button>
					   <!--<a href="register.php" class="button button-secondary">Register</a>-->
					  		</div>
					</form>
				</div>
		   
			</div>
		</div>
	</div>
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Assign Dates</h4>
      </div>
	  <form action="" name="addform" method="POST" id="addform" />   
      <div class="modal-body">     
		<fieldset>

				<div class="form-group">
				<label>Start Date: <span class='required'>*</span></label>

				<input type="date" name='stdate' value='' id="stdate" class="form-control" required/>
				<input type="hidden" name="iddd" id="iddd" value="" />
                </div>
                <div class="form-group">
				<label>End Date: <span class='required' >*</span></label>

				<input type="date" name='Enddate' value='' id="Enddate" class="form-control " required/>
                </div>
                <div class="form-group">
				<label>Status: <span class='required'>*</span></label>

				<select name="statuss" id="statuss" class="form-control" required>
				<option value="InActive">InActive</option>
				<option value="Active">Active</option>
				</select>
                </div>

		</fieldset>
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" name="save" class="btn btn-prim" value="Save changes" />
      </div>
	  </form>
    </div>
  </div>
</div>
      <script>

	

	function delItem(id){
	var a = confirm("<?php echo $lang['ADMIN_DELETE_CONFIRM']; ?>");

		if(a){

		document.location.href='?action=delete&id='+id+'&search=<?php if(isset($_REQUEST['search'])) { echo $_REQUEST['search']; } ?><?php if(isset($_REQUEST['page'])) { echo "&page=".$_REQUEST['page']; } ?><?php if(isset($_REQUEST['sort'])) { echo "&sort=".$_REQUEST['sort']; } ?><?php if(isset($_REQUEST['filter'])) { echo "&filter=".$_REQUEST['filter']; } ?>';

		}

	}
	
	function ajstatus(id)
	{
		   	$.ajax({
			type: 'POST',
			url: '<?php echo ROOT_URL; ?>ajaxstdate.php',
			data: {'idd':id},
			dataType: "json",
			success: function(data)
			{
			$('#stdate').val(data['start_date']);
			$('#Enddate').val(data['end_date']);
			$('#iddd').val(data['aid']);
			selectByValue('statuss',data['status']);
			}
			});

	
	}
	
	function selectByValue(id,val)
	{
			if(val==null)
			return true;
	 element=document.getElementById(id)
			if(element==null)
			return true;
	 var sind=0;
	 for (x=0;x<element.length;x++) {
	  if (element[x].value==val)
	   sind=x;
	 }
	 element.selectedIndex=sind;
	}

	function addEnquiry(event)
	   {
			if($("#adduser_name").val()=='')
			{
				$("#adduser_name").css('border','1px solid red');
				return false;
			}
			else
				$("#adduser_name").css('border','');
			
			if($("#adduser_phone").val()=='')
			{
				 $("#adduser_phone").css('border','1px solid red');
				 return false;
			}
			else
			{
				var pattern = /^\d{10}$/;
				if (pattern.test($("#adduser_phone").val()))
					$("#adduser_phone").css('border',''); 
				else
				{
					$("#adduser_phone").css('border','1px solid red');
					return false;
				}
			}	
			
			if($("#adduser_url").val()=='')
			{
				$("#adduser_url").css('border','1px solid red');
				return false;
			}
			else
			{
				if(isValidURL($("#adduser_url").val()))
				$("#adduser_url").css('border','');
				else
				{
					$("#adduser_url").css('border','1px solid red');
					return false;
				}
			}
				
			
			if($("#adduser_email").val()=='')
			{
				$("#adduser_email").css('border','1px solid red');
				return false;
			}
			else
			{
				if( !isValidEmailAddress( $("#adduser_email").val() ) )
				{
					$("#adduser_email").css('border','1px solid red');
					return false;
				}
				else
				$("#adduser_email").css('border','');
			}
			
			if($("#adduser_image").val()=='')
			{
				$("#adduser_image").css('border','1px solid red');
				return false;
			}
			else
			$('#adduser_image').css('border','');
			
			
			
			var data = new FormData();
			  data.append('name', $("#adduser_name").val());
			  data.append('email', $("#adduser_email").val());
			  data.append('phone', $("#adduser_phone").val());
			  data.append('url', $("#adduser_url").val());
			  data.append('file', $("#adduser_image")[0].files[0]);
			   $.ajax({
					url: '../addss.php',
					type: 'POST',
			   data: data,
					cache: false,
					dataType: 'json',
					processData: false, // Don't process the files
					contentType: false,
					enctype: 'multipart/form-data',
					beforeSend: function(){
						$('#addEnquiry_button').parent().append("<img src='../img/ajax-loader.gif'>")
					},
					complete: function(){
						$('#addEnquiry_button').parent().find('img').remove()
					},
					success: function(data, textStatus, jqXHR)
					{
						if(data=='1')
							alert("Success")
						else if(data == '3')
							alert("Invalid file Type. Only PNG, JPEG or GIF are allowed");
						else
							alert("Error in sending enquiry!!")
						$("#myModal4").find('.close').trigger('click');
						$("#add_form").trigger('reset');
						location.reload();
					}
			   });
			
	   }
	   
   function isValidEmailAddress(emailAddress) {
		var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
		return pattern.test(emailAddress);
	}
	
	function isValidURL(url){
		var RegExp = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
		if(RegExp.test(url))
			return true;
		else
			return false;
    } 
	
	$("#deleteAllAds").click(function(){
		if(confirm("Are you sure to delete All adds Completely ?"))
		{
			$.ajax({
			url: location.href,
			data: {
				deleteAll:1
			},
			beforeSend: function(){
				$("#imgloading").html("<img style='float:right' src='../img/ajax-loader.gif'>")
			},
			complete: function(){
				$("#imgloading").empty();
			},
			success:function(data){
				alert("Success")
				location.reload()
			}
		})
		}
	})

		
if( navigator.userAgent.match(/iPhone|iPad|iPod/i) ) {

$('.modal').on('show.bs.modal', function() {

    // Position modal absolute and bump it down to the scrollPosition
    $(this)
    .css({
        position: 'absolute',
        marginTop: $(window).scrollTop() + 'px',
        bottom: 'auto'
    });

    // Position backdrop absolute and make it span the entire page
    //
    // Also dirty, but we need to tap into the backdrop after Boostrap 
    // positions it but before transitions finish.
    //
    setTimeout( function() {
    $('.modal-backdrop').css({
        position: 'absolute', 
        top: 0, 
        left: 0,
        width: '100%',
        height: Math.max(
        document.body.scrollHeight, document.documentElement.scrollHeight,
        document.body.offsetHeight, document.documentElement.offsetHeight,
        document.body.clientHeight, document.documentElement.clientHeight
        ) + 'px'
    });
    }, 0);
});
}

	function changePP(val)
	{
		
	window.location.search = '?perpage='+val;
	}
	
	<?php if(!empty($alert)){ ?>
		$(document).ready(function(){
			setTimeout(function (){ $('#alert').hide(); }, 3000);
	});
	<?php } ?>
	<?php if(!empty($msgs)){ ?>
		$(document).ready(function(){
			setTimeout(function (){ $('#msgs').hide(); }, 3000);
	});
	<?php } ?>
	</script>
	
      <?php include("footer.php"); ?>
