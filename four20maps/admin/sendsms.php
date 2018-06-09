<?
ob_implicit_flush(true);
ob_start();
$heading='sms';
session_start();
include_once './includes/config.inc.php';
 function logger_print($log, $level='', $label='') {
	$core_config['apps_path']['logs']="logs";
	$core_config['datetime']['format']="Y-m-d";
	$remote = ( trim($_SERVER['REMOTE_ADDR']) ? trim($_SERVER['REMOTE_ADDR']) : '-' );
	$host = ( trim($_SERVER['HTTP_HOST']) ? trim($_SERVER['HTTP_HOST']) : '-' );
	$logfile = 'sms.log' ;
	$username=date("Y-m-d");
	// max log length is 1000
	if (strlen($log) > 1000) {
		$log = substr($log, 0, 1000);
	}

	// default level is 2
	$level = ( (int)$level > 0 ? (int)$level : 2 );

	// label should not have spaces, replace single space with double _
	$label = str_replace(' ', '__', $label);
	$label = ( $label ? $label : '-' );

	$username = ( $user_config['username'] ? $user_config['username'] : '-' );
	//if (logger_get_level() >= $level) {
		$type = 'L'.$level;
		$fn = $core_config['apps_path']['logs'].'/'.$logfile;
		if ($fd = fopen($fn, 'a+')) {
			$dt = date($core_config['datetime']['format'], time());

			// REMOTE_ADDR HTTP_HOST DATE TIME PID USERNAME TYPE LABEL # LOG
			$message = stripslashes($remote." ".$host." ".$dt." "._PID_." ".$username." ".$type." ".$label." # ".$log);
			$message = str_replace("\n", " ", $message);
			$message = str_replace("\r", " ", $message);
			$message .= "\n";

			fputs($fd, $message);
			fclose($fd);
		}
	//}
}
function nexmo_hook_sendsms($smsc, $sms_sender, $sms_footer, $sms_to, $sms_msg,$mesage_unique_id, $uid = '', $gpid = 0, $smslog_id = 0, $sms_type = 'text', $unicode = 0) { 
	
	logger_print("enter smsc:" . $smsc . " smslog_id:" . $smslog_id . " uid:" . $uid . " to:" . $sms_to, 3, "nexmo_hook_sendsms");
	
	// override plugin gateway configuration by smsc configuration
	//$plugin_config = gateway_apply_smsc_config($smsc, $plugin_config);
	$plugin_config['nexmo']['api_key']="3e1997ac";
		$plugin_config['nexmo']['api_secret']="60525d05b4f53e80";
	$sms_sender = stripslashes($sms_sender);
	$plugin_config['nexmo']['url']="https://rest.nexmo.com/sms/json"; 
	
	$sms_footer = stripslashes($sms_footer);
	$sms_msg = stripslashes($sms_msg);
	$ok = false;
	
	if ($sms_footer) {
		$sms_msg = $sms_msg . $sms_footer;
	}
	
	if ($sms_sender && $sms_to && $sms_msg) {
		
		$unicode_query_string = '';
		if ($unicode) {
			if (function_exists('mb_convert_encoding')) {
				// $sms_msg = mb_convert_encoding($sms_msg, "UCS-2BE", "auto");
				// $sms_msg = mb_convert_encoding($sms_msg, "UCS-2", "auto");
				$sms_msg = mb_convert_encoding($sms_msg, "UTF-8", "auto");
				$unicode_query_string = "&type=unicode"; // added at the of query string if unicode
			}
		}
		
		$query_string = "api_key=" . $plugin_config['nexmo']['api_key'] . "&api_secret=" . $plugin_config['nexmo']['api_secret'] . "&to=" . urlencode($sms_to) . "&from=" . urlencode($sms_sender) . "&text=" . urlencode($sms_msg) . $unicode_query_string . "&status-report-req=1&client-ref=" . $smslog_id;
		$url = $plugin_config['nexmo']['url'] . "?" . $query_string;
		
		logger_print("url:[" . $url . "]", 3, "nexmo outgoing");
		
		// fixme anton
		// rate limit to 1 second per submit - nexmo rule
		sleep(1);
		

		// old way
		// $resp = json_decode(file_get_contents($url), true);
		

		// new way
		$opts = array(
			'http' => array(
				'method' => 'POST',
				'header' => "Content-type: application/x-www-form-urlencoded\r\nContent-Length: " . strlen($query_string) . "\r\nConnection: close\r\n",
				'content' => $query_string 
			) 
		);
		$context = stream_context_create($opts);
		$response_msg=file_get_contents($plugin_config['nexmo']['url'], FALSE, $context);
		$resp = json_decode($response_msg, TRUE);
		$result=$resp;
		if ($resp['message-count']) {
			$c_status = $resp['messages'][0]['status'];
			$c_message_id = $resp['messages'][0]['message-id'];
			$c_network = $resp['messages'][0]['network'];
			$c_error_text = $resp['messages'][0]['error-text'];
			logger_print("sent smslog_id:" . $smslog_id . " message_id:" . $c_message_id . " status:" . $c_status . " error:" . $c_error_text, 2, "nexmo outgoing");
		 
		} else {
			// even when the response is not what we expected we still print it out for debug purposes
			$resp = str_replace("\n", " ", $resp);
			$resp = str_replace("\r", " ", $resp);
			logger_print("failed smslog_id:" . $smslog_id . " resp:" . $resp, 2, "nexmo outgoing");
		}
		mysql_query("INSERT INTO `sms`(`sms_from`, `sms_to`, `message`, `server_response`,group_id,mesage_unique_id) VALUES ('".$_REQUEST['from']."','".$sms_to."','".mysql_escape_string($_REQUEST['message'])."','".$response_msg."','".$_REQUEST['group_id']."','".$mesage_unique_id."')");
	}
	if (!$ok) {
		$p_status = 2;
		logger_print($smslog_id, $uid, $p_status);
	}
	return $result;
}


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
		
echo('SMS Started sending...');
		// send to browser
ob_flush();
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
				 
			   		
echo('still going...'.$i);;														
 $resp=nexmo_hook_sendsms('smsc', $_REQUEST['from'],'', $to_number, $_REQUEST['message'],$mesage_unique_id);
 $succ_msg =  "Messages sent succesfully.Balance is now " . $result['messages'][0]['remaining-balance'] . PHP_EOL;
			  }
		
echo('done.');
ob_end_flush(); 
		}}
}
 
?>

 <?php include("header.php"); ?><script  type="text/javascript">
	(function(a){a.fn.maxlength=function(b){var c=a(this);return c.each(function(){b=a.extend({},{counterContainer:!1,text:"%left characters left"},b);var c=a(this),d={options:b,field:c,counter:a('<div class="maxlength"></div>'),maxLength:parseInt(c.attr("maxlength"),10),lastLength:null,updateCounter:function(){var b=this.field.val().length,c=this.options.text.replace(/\B%(length|maxlength|left)\b/g,a.proxy(function(a,c){return"length"==c?b:"maxlength"==c?this.maxLength:this.maxLength-b},this));this.counter.html(c),b!=this.lastLength&&this.updateLength(b)},updateLength:function(a){this.field.trigger("update.maxlength",[this.field,this.lastLength,a,this.maxLength,this.maxLength-a]),this.lastLength=a}};d.maxLength&&(d.field.data("maxlength",d).bind({"keyup change":function(){a(this).data("maxlength").updateCounter()},"cut paste drop":function(){setTimeout(a.proxy(function(){a(this).data("maxlength").updateCounter()},this),1)}}),b.counterContainer?b.counterContainer.append(d.counter):d.field.after(d.counter),d.updateCounter())}),c}})($);
</script>
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
					<textarea id="message" name="message" class="form-control" style="widows: 50%" maxlength="140"></textarea>
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
	$("#message").maxlength();
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