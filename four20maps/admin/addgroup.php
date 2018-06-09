<?
ob_start();
session_start();
include_once './includes/config.inc.php';
 
$db = db_connect();
$error = $succ_msg = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
  require_once 'excel_reader2.php';
		
		$extension_arr 	= explode(".", $_FILES['bulk_file_to']["name"]);
			$extension 		= end($extension_arr);
			 
				$imgname 		= 'document-excel-'.time();
				$pdf_path	 	= "uploads/".$imgname.'.'.$extension;
				$temp_path	 	= $_FILES['bulk_file_to']['tmp_name'];
				$pdf_name=$imgname.'.'.$extension;;
			     move_uploaded_file($temp_path, $pdf_path);
	 	$data = new Spreadsheet_Excel_Reader( $pdf_path);
	mysql_query("INSERT INTO `sms_group`(`group_name`,file_path) VALUES ('".$_REQUEST['group_name']."','".$pdf_path."')");
	$group_id=mysql_insert_id();
	$i=1;
		foreach($data->sheets['0']['cells'] as $number)
		{
		   if($number[1] != "Numbers"){
			   $i++;
			   $to_number="+1".$number[1];
			   mysql_query("INSERT INTO `sms_group_list`(`group_id`, `sms_number`) VALUES ('".$group_id."','".$to_number."')");
			  }
		}
		mysql_query("UPDATE `sms_group` SET  `group_list_count`='".$i."' WHERE group_id='".$group_id."'"); 
	header("location:groups.php");
}
 
?>
 <?php include("header.php"); ?>
      <!-- Left side column. contains the logo and sidebar -->
      <?php include("sidebar.php"); ?>
   <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Add Group</h1>
          <ol class="breadcrumb">
            <li><a href="sendsms.php"><i class="fa fa-dashboard"></i>Add group</a></li> 
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Small boxes (Stat box) -->    
      
	<div class="row">
        
            <?php
            if($error!='') {
				echo ' <p>'.$error.'</p>';
			}
			 if($succ_msg!='') {
				echo ' <p>'.$succ_msg.'</p>';
			}
			?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <form class="form-horizontal" onSubmit="return sendsms()"  id="sendsms_frm" name="sendsms_frm" method="post" action="" enctype="multipart/form-data">
              
              	 <div class="form-group">
                <label class="col-lg-3 col-md-3 col-sm-5 col-xs-12 control-label">Group Name <span class="require">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                        <input type="text" id="group_name"  name="group_name"  class="form-control required  " style="widows: 50%" placeholder="Enter Group Name">
                      </div>
              </div>
              
                 <div class="form-group">
                <label class="col-lg-3 col-md-3 col-sm-5 col-xs-12 control-label">Import Bulk List<span class="require">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                        <input type="file" id="bulk_file_to"  name="bulk_file_to">
                        <br/>
                        Note : Please upload excel file(xls only) of numbers.Please download sample file for the format.<a href="bulk_sample.xlsx" download>Click here</a>
                      </div>
              </div>  
					
                   
               
				<div class="form-group">
					<div class="col-lg-8 col-md-8 col-sm-8 col-sm-offset-4 col-md-offset-4">
						<button class="btn btn-primary" id="add_product" name="add_product" type="submit">Save
						</button>
						<a class="btn btn-danger" href="sms.php">Cancel</a>
					</div>
				</div>
                  </form></div>
          </div>
         </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
            <?php include("footer.php"); ?> 
<script>
function validatesms()
{
	var error=0;
	$("#sendsms_frm").find("select,input").each(function(){
		if($(this).val()=='')
		{
			error=1;
			$(this).css("border","1px solid red")
		}
		else
			$(this).css("border","")
	})
	if(error>0)
		return false
}
 
</script>
</body>
</html>