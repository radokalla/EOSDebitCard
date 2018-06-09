<?php



$send_id = (int)$_REQUEST['send_id'];

if(!$send_id){

	// basic error checking.

	echo 'Please go back and pick a newsletter';

}





if($_REQUEST['pause']){

	$newsletter->pause_send($db,$send_id);

}

if($_REQUEST['unpause']){

	$newsletter->un_pause_send($db,$send_id);

}



if(!$db)$db = db_connect();



$sql = "SELECT * FROM `"._DB_PREFIX."send` WHERE `send_id` = '".mysql_real_escape_string($send_id)."'";

$res = mysql_query($sql, $db);

$res_r = mysql_fetch_assoc($res);





$sql_sentMembers = "SELECT * FROM `"._DB_PREFIX."newsletter_member` nm LEFT JOIN `"._DB_PREFIX."member` m USING (member_id) WHERE nm.send_id = '".mysql_real_escape_string($send_id)."' AND nm.status != 1";

$res_sentMembers = mysql_query($sql_sentMembers, $db);

//$res_sentMembers_r = mysql_fetch_assoc($res_sentMembers);

$num_sentMembers = mysql_num_rows($res_sentMembers);



$sql_unsentMembers = "SELECT * FROM `"._DB_PREFIX."newsletter_member` nm LEFT JOIN `"._DB_PREFIX."member` m USING (member_id) WHERE nm.send_id = $send_id AND nm.status = 1 AND m.unsubscribe_date = 0000-00-00";

$res_unsentMembers = mysql_query($sql_unsentMembers, $db);

//$res_unsentMembers_r = mysql_fetch_assoc($res_unsentMembers);

$num_unsentMembers = mysql_num_rows($res_unsentMembers);



$sql_unsentMembers_loop = "SELECT * FROM `"._DB_PREFIX."newsletter_member` nm LEFT JOIN `"._DB_PREFIX."member` m USING (member_id) WHERE nm.send_id = $send_id AND nm.status = 1 AND m.unsubscribe_date = 0000-00-00 LIMIT 10";

$res_unsentMembers_loop = mysql_query($sql_unsentMembers_loop, $db);

//$res_unsentMembers_r = mysql_fetch_assoc($res_unsentMembers);

$num_unsentMembers_loop = mysql_num_rows($res_unsentMembers_loop);



//$send_data = $newsletter->get_send($db,$send_id);

//$newsletter_id = $send_data['newsletter_id'];

$newsletter_id = $res_r['newsletter_id'];

$newsletter_data = $newsletter->get_newsletter($db,$newsletter_id);




if($_REQUEST['process']){

	

	ob_end_clean();

	?>

	<html>

	<head>

	<title>Sending</title>

	<script language="javascript" src="layout/js/jquery.js"></script>

	</head>

	<body>

	<?php

	@set_time_limit(0);

	

	if($res_r['start_time'] > time()){

		?>

		<script language="javascript">

    	$('#sent_to',window.parent.document).html('This newsletter has been scheduled for sending on <?php echo date('d/m/Y',$res_r['start_time']);?>');

    	</script>

		<?php

		exit;

	}

	

	$batch_limit = (int)$newsletter->settings['burst_count'];

	if(!$batch_limit)$batch_limit = 10; // default 10.

	

		$result = array();

		$result['status'] = true;

		//$sent_to = count($send_data['sent_members']);

		$sent_to = $num_sentMembers;

		

		$batch_count = 0;

		

		while($unsent_member = mysql_fetch_assoc($res_unsentMembers_loop) ){ 

			

			

			/*print ($send_id."<br />");

			print ($unsent_member['member_id']."<br />");

			print ($newsletter_id."<br />");

			print_r ($newsletter_data);

			exit;*/



			

			

			$result = $newsletter->send_out_newsletter($db,$send_id,$unsent_member['member_id'], $newsletter_id, $newsletter_data);

			

			

			

			if($result['status']){

				$batch_count++;

				$sent_to++;

			}else{

				$sent_to = $result['message'];

			}

			

			

			?>

			<script language="javascript">

	    	$('#sent_to',window.parent.document).html('<?php echo $sent_to;?>');

	    	</script>

	    	

	    	<?php 

	    	ob_flush();

	    	flush();

	    	

	    	if(!$result['status']){

	    		// break on fail to send

	    		break;

	    	}

			

	    	if($batch_count >= $batch_limit){

	    		if(_DEMO_MODE)sleep(4);

				break;

			}

		

		} 

		

		if($result['status']){

		?>

	    

	    <script language="javascript">

	    

	    <?php

	    /*if(!$db)$db = db_connect();

		$sql_unsentMembers_next = "SELECT * FROM `"._DB_PREFIX."newsletter_member` nm LEFT JOIN `"._DB_PREFIX."member` m USING (member_id) WHERE nm.send_id = $send_id AND nm.status = 1 AND m.unsubscribe_date = 0000-00-00";

		$res_unsentMembers_next = mysql_query($sql_unsentMembers_next, $db);

		//$res_unsentMembers_r = mysql_fetch_assoc($res_unsentMembers);

		$num_unsentMembers_next = mysql_num_rows($res_unsentMembers_next);*/

		

		

		

	    				//$send_data = $newsletter->get_send($db,$send_id);

		//print ("number of unset members ".$num_unsentMembers_next);

		//exit;

	    				//if(!count($send_data['unsent_members']) ){

	    	//echo "STOP";

						//$newsletter->send_complete($db,$send_id);

	    	?>

	    // if complete.

	    				//window.parent.location.href='index.php?p=send&send_id=<?php echo $send_id;?>';

	    <?php 			//}else{ ?>

		    

			setTimeout(function(){window.location.href='index.php?p=send&send_id=<?php echo $send_id;?>&process=true';},1000);

	    <?php 			//} ?>

		</script>

	

		<?php

		}

	?>

	</body>

	</html>

	<?php

		exit;

}else{



?>



<h1>Send</h1>





<h2><span>Sending Newsletter: <?php echo $newsletter_data['subject'];?></span></h2>



<p>Please don't close the window until it says "COMPLETE" below.</p>



<?php



if($res_r['start_time'] > time()){

	?>



	<div class="box">

	<div style="font-size:20px; padding:20px;"> 

	This newsletter has been scheduled for sending on <?php echo date('d/m/Y',$res_r['start_time']);?>

	</div>

	</div>

	<?php

		

}else{ ?>

		

	<div class="box">

		

		<?php if($res_r['status'] == '3'){$newsletter->send_complete($db,$send_id); // quick hack to fix a half completed send.?>

			<div style="font-size:20px; padding:20px;"> Complete!</div>

		<?php }else{ ?>

					<?php if($res_r['status'] == '6'){ ?>

						<div style="font-size:20px; padding:20px;"> Paused...</div>

					<?php }else{ ?>

						<div style="font-size:20px; padding:20px;"> Sending...</div>

					<?php } ?>

		<?php } ?>



		<div style="font-size:20px; padding:20px;"> Sent to <span id="sent_to"><?php echo $num_sentMembers;?></span> out of <span id="sent_total"><?php echo $num_unsentMembers + $num_sentMembers;?></span> members</div>

		

		<div style="padding:20px;">

			<?php if($res_r['status'] == '3'){ ?>

			<?php }else{ ?>

				<?php if($res_r['status'] == '6'){ ?>

				<a href="?p=send&send_id=<?php echo $send_id;?>&unpause=true">Continue Sending</a>

				<?php }else{ ?>

				<a href="?p=send&send_id=<?php echo $send_id;?>&pause=true">Pause Send</a>

				<?php } ?>

			<?php } ?>

		</div>

	</div>

	

	<?php if($res_r['status'] == '1'){ ?>

	<iframe src="about:blank" id="send_iframe" name="send_iframe" width="300" height="300" style="display:block;"></iframe>

	<script language="javascript">

	

	function send_mailout(){

		$('#send_iframe').attr('src','index.php?p=send&send_id=<?php echo $send_id;?>&process=true');

		

	}

	$(window).ready(function(){

		setTimeout(send_mailout,3000);

		//setTimeout(function(){window.location.href='index.php?p=send&send_id=<?php //echo $send_id;?>&process=true';},3000);

	});

	</script>



<?php } ?>

<?php } ?>

<?php } ?>