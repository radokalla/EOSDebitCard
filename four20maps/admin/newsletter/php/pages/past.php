<?php



?>

<h1>Past Newsletters</h1>


<h2><span>List of All Newsletters</span></h2>


<div class="box">
	<table cellpadding="5">
		<tr>
			<th>Email Subject</th>
			<th>Sent From</th>
			<th>Sent To</th>
			<th>Opened By</th>
			<th>Unsubscribed</th>
			<th>Bounces</th>
			<th>Action</th>
		</tr>
		<?php

		$newsletters = $newsletter->get_newsletters($db);
		
		foreach($newsletters as $n){ 
			$n = $newsletter->get_newsletter($db,$n['newsletter_id']);
			//print_r ($newsletters);
			//exit;
			$sends = $newsletter->get_newsletter_sends($db,$n['newsletter_id']);
			//$sends = $newsletter->get_newsletter_sends($db,11);
			//print_r ($sends[0]['send_id']);
			//exit;
			?>
		<tr>
			<td>
				<?php echo $n['subject'];?>
			</td>
			<td>
				&lt;<?php echo $n['from_name'];?>&gt; <?php echo $n['from_email'];?> 
			</td>
			<td>
				<?php
				
				foreach($sends as $send){ 
					if(!$db)$db = db_connect();
					$sql_sentMembers = "SELECT * FROM `"._DB_PREFIX."newsletter_member` nm LEFT JOIN `"._DB_PREFIX."member` m USING (member_id) WHERE nm.send_id = '".mysql_real_escape_string($send['send_id'])."' AND nm.status != 1";
					$res_sentMembers = mysql_query($sql_sentMembers, $db);
					
					//$res_sentMembers_r = mysql_fetch_assoc($res_sentMembers);
					$num_sentMembers = mysql_num_rows($res_sentMembers);	
					//$send = $newsletter->get_send($db,$send['send_id']);
					?>
					
					<?php echo $num_sentMembers;?> members on <?php echo date("Y-m-d",$send['start_time']);?> <br>
					
				<?php } //db_close($db); ?>
			</td>
			<td>
				<?php
				foreach($sends as $send){ 
					//$send = $newsletter->get_send($db,$send['send_id']);
					if(!$db)$db = db_connect();
					$sql_openMembers = "SELECT * FROM `"._DB_PREFIX."newsletter_member` nm LEFT JOIN `"._DB_PREFIX."member` m USING (member_id) WHERE nm.send_id = '".mysql_real_escape_string($send['send_id'])."' AND nm.open_time > 0";
					$res_openMembers = mysql_query($sql_openMembers, $db);
					//$res_sentMembers_r = mysql_fetch_assoc($res_sentMembers);
					$num_openMembers = mysql_num_rows($res_openMembers);

					?>
					
					<?php echo $num_openMembers;?> members <br>
					
				<?php }  //db_close($db); ?>
			</td>
			<td>
				<?php
				foreach($sends as $send){ 
					//$send = $newsletter->get_send($db,$send['send_id']);
					if(!$db)$db = db_connect();
					$sql_unsubMembers = "SELECT * FROM `"._DB_PREFIX."newsletter_member` nm LEFT JOIN `"._DB_PREFIX."member` m USING (member_id) WHERE nm.send_id = '".mysql_real_escape_string($send['send_id'])."' AND m.unsubscribe_date != '0000-00-00' AND m.unsubscribe_send_id = '".mysql_real_escape_string($send['send_id'])."'";
					$res_unsubMembers = mysql_query($sql_unsubMembers, $db);
					//$res_sentMembers_r = mysql_fetch_assoc($res_sentMembers);
					$num_unsubMembers = mysql_num_rows($res_unsubMembers);
					
					?>
					
					<?php echo $num_unsubMembers;?> members <br>
					
				<?php } //db_close($db); ?>
			</td>
			<td>
				<?php
				foreach($sends as $send){ 
					//$send = $newsletter->get_send($db,$send['send_id']);
					if(!$db)$db = db_connect();
					$sql_bounceMembers = "SELECT * FROM `"._DB_PREFIX."newsletter_member` nm LEFT JOIN `"._DB_PREFIX."member` m USING (member_id) WHERE nm.send_id = '".mysql_real_escape_string($send['send_id'])."' AND nm.bounce_time > 0";
					$res_bounceMembers = mysql_query($sql_bounceMembers, $db);
					//$res_sentMembers_r = mysql_fetch_assoc($res_sentMembers);
					$num_bounceMembers = mysql_num_rows($res_bounceMembers);

					?>
					
					<?php echo $num_bounceMembers;?> members <br>
					
				<?php }?>
			</td>
			<td>
                <a href="?p=open&newsletter_id=<?php echo $n['newsletter_id'];?>">Stats/Send</a>
				<a href="?p=create&newsletter_id=<?php echo $n['newsletter_id'];?>">Edit</a>
			</td>
		</tr>
		<?php } ?>
		
	</table>
</div>


