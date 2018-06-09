<?
ob_start();
session_start();
include_once './includes/config.inc.php';
 
$db = db_connect();
$error = $succ_msg = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
 set_time_limit(0);
	$mesage_unique_id=uniqid();
	$url='https://rest.nexmo.com/sms/json';
	 
	 if($_REQUEST['bulksms'] == 0)
	 {
	   $fields = array(
			'api_key' => urlencode('3e1997ac'),
			'api_secret' => urlencode('60525d05b4f53e80'),
			'to' => urlencode("+1".$_REQUEST['to']),
			'from' => urlencode($_REQUEST['from']),
			'text' => urlencode($_REQUEST['message']) 
		);
		 
		//url-ify the data for the POST
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');		 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
		//execute post
		$result = curl_exec($ch);         
		$info  = curl_getinfo($ch); 
		$error = curl_errno($ch);  
		curl_close($ch);  	
	 mysql_query("INSERT INTO `sms`(`sms_from`, `sms_to`, `message`, `server_response`,mesage_unique_id) VALUES ('".$_REQUEST['from']."','".$_REQUEST['to']."','".mysql_escape_string($_REQUEST['message'])."','".$result."','".$mesage_unique_id."')");
		 $result=json_decode($result,true);
		 if($result['messages']['status']==0)
			$succ_msg =  "Messages sent succesfully.Balance is now " . $resultt['messages'][0]['remaining-balance'] . PHP_EOL;
		 else
			$succ_msg =  "Problem in sending sms.Error: " . $result['messages']['error-text'] . PHP_EOL; 
 	}
	else{	
		$url='https://rest.nexmo.com/sms/json';
	$resulr_array = mysql_query("SELECT * from sms_group_list  where group_id=".$_REQUEST['group_id']);
	$count = mysql_num_rows($resulr_array); if($count>0){
		$j=1;$i=1;
		 while($row = mysql_fetch_assoc($resulr_array)){ $i++;$j++;
	 if (strpos($row['sms_number'], '+1') !== false) {
    		$to_number=$row['sms_number'];
	 	}
		else{
			$to_number="+1".$row['sms_number'];
		}			
				 if($j==14){
					 $j=0;
					 sleep(1);
				 }
	    			$fields = array(
						'api_key' => urlencode('3e1997ac'),
						'api_secret' => urlencode('60525d05b4f53e80'),
						'to' => urlencode($to_number),
						'from' => urlencode($_REQUEST['from']),
						'text' => urlencode($_REQUEST['message']) 
					);
					//url-ify the data for the POST
					foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
					rtrim($fields_string, '&');		 
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url); 
					curl_setopt($ch,CURLOPT_POST, count($fields));
					curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
			        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
					//execute post
					$result = curl_exec($ch);         
					$info  = curl_getinfo($ch); 
					$error = curl_errno($ch);  
					curl_close($ch);  	 
			   		mysql_query("INSERT INTO `sms`(`sms_from`, `sms_to`, `message`, `server_response`,group_id,mesage_unique_id) VALUES ('".$_REQUEST['from']."','".$to_number."','".mysql_escape_string($_REQUEST['message'])."','".$result."','".$_REQUEST['group_id']."','".$mesage_unique_id."')");
			   $result=json_decode($result,true);
			   if($result['messages'][0]['status']==0)
					$succ_msg =  "Messages sent succesfully.Balance is now " . $result['messages'][0]['remaining-balance'] . PHP_EOL;
			   	    else
					$succ_msg =  "Problem in sending sms.Error: " . $result['messages'][0]['error-text'] . PHP_EOL; 
			   ob_start();
			   print "Pending SMS : ". $count-$i;
			  ob_end_clean();
				  }
		
		}}
}
 
?>
 <?php include("header.php"); ?>
      <!-- Left side column. contains the logo and sidebar -->
      <?php include("sidebar.php"); ?>
   <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Send Sms</h1>
          <ol class="breadcrumb">
            <li><a href="sendsms.php"><i class="fa fa-dashboard"></i>Send Sms</a></li> 
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
                   <div class="form-group" >
                <label class="col-lg-3 col-md-3 col-sm-5 col-xs-12 control-label">Do you want to send bulk sms<span class="require">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                        <input type="radio" id="bulk"  name="bulksms"  class="bulkclass" value="0" checked>No
                        <input type="radio" id="bulk"  name="bulksms"  class="bulkclass" value="1">Yes
                </div>
              </div> 
              
              
                 <div class="form-group" style="display: none"  id="bulk_to">
                <label class="col-lg-3 col-md-3 col-sm-5 col-xs-12 control-label">Import Bulk List<span class="require">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                   <select name="group_id" class="form-control">
                         	<?php $sms_group_array = mysql_query("SELECT * from sms_group");
								$count = mysql_num_rows($sms_group_array); if($count>0){
                         while($row = mysql_fetch_assoc($sms_group_array)){ $i++;?>
							 <option value="<?=$row['group_id'];?>"><?=$row['group_name'];?></option>
                         <?php }}?></select></div>
              </div>  
					
                    <div class="form-group"  id="single_to">
                <label class="col-lg-3 col-md-3 col-sm-5 col-xs-12 control-label">To<span class="require">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                        <input type="text" id="to"  name="to"   class="form-control required  " style="widows: 50%">
                      </div>
              </div>
             		
             		 <div class="form-group" style="display: none">
                <label class="col-lg-3 col-md-3 col-sm-5 col-xs-12 control-label">From <span class="require">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                        <input type="text" id="from"  name="from" value="12015799529"  class="form-control required  " style="widows: 50%" placeholder="Enter from number">
                      </div>
              </div>
              		
                    <div class="form-group">
                <label class="col-lg-3 col-md-3 col-sm-5 col-xs-12 control-label">Message<span class="require">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
					<textarea id="message" name="message" class="form-control" style="widows: 50%"></textarea>
                      </div>
              </div>
               
				<div class="form-group">
					<div class="col-lg-8 col-md-8 col-sm-8 col-sm-offset-4 col-md-offset-4">
						<button class="btn btn-primary" id="add_product" name="add_product" type="submit">Send Message
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
$('.bulkclass').change(function(){
	
	if($(this).val()==1){
		$('#bulk_to').show();
		$('#single_to').hide();
	}
	else{
			$('#bulk_to').hide();
			$('#single_to').show();
		}
}) 
</script>
</body>
</html>