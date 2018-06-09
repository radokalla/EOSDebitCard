<?php

$newsletter_id = (int)$_REQUEST['newsletter_id'];
if(!$newsletter_id){
	// basic error checking.
	echo 'Please go back and pick a newsletter';
}

if(isset($_REQUEST['delete'])){
	if(_DEMO_MODE){
		echo "Sorry, cant delete newsletters in demo mode... ";
		exit;
	}
	$newsletter->delete_newsletter($db,$newsletter_id);
	ob_end_clean();
	header("Location: index.php?p=past");
	exit;
}

$errors = array();
if(isset($_REQUEST['save']) && $_REQUEST['save']){
	
	// save the newsletter 
	// check required fields.
	
	$fields = array(
		//"template" => $_REQUEST['template'],
		"subject" => $_REQUEST['subject'],
		"from_name" => $_REQUEST['from_name'],
		//"content" => $_REQUEST['newsletter_content'],
		"from_email" => $_REQUEST['from_email'],
		"bounce_email" => $_REQUEST['bounce_email'],
	);
	
	// basic error checking, nothing fancy
	foreach($fields as $key=>$val){
		if(!trim($val)){
			$errors [] = 'Required field missing: '.ucwords(str_replace('_', ' ',$key));
		}
	}
	
	if(!$errors){
		
		$newsletter_id = $newsletter->save($db,$newsletter_id,$fields);
		if($newsletter_id){
			if($_REQUEST['send']){
				// user wants to send this newsletter!! create a send a start away..
				
				if(isset($_REQUEST['dont_send_duplicate']) && $_REQUEST['dont_send_duplicate']){
					$dont_sent_duplicates = true;
				}else{
					$dont_sent_duplicates = false;
				}
				if(is_array($_REQUEST['group_id'])){
					$send_groups = $_REQUEST['group_id'];
				}else{
					$errors [] = "Please select a group to send to";
				}
				
				if(!$errors){
					
					$send_id = $newsletter->create_send($db,$newsletter_id,$send_groups,$dont_sent_duplicates,$_REQUEST['send_later']);
					
					if(!$send_id){
						$errors[] = "No members found to send to";
					}else{
						ob_end_clean();
						header("Location: index.php?p=send&send_id=$send_id");
						exit;
					}
				}
			}else{
				ob_end_clean();
				header("Location: index.php?p=open&newsletter_id=$newsletter_id");
				exit;
			}
		}else{
			$errors [] = 'Failed to create newsletter in database';
		}
	}
	
	
	foreach($errors as $error){
		echo '<div style="font-weight:bold; color:#FF0000; font-size:20px;">'.$error . '</div>';
	}
	
	
}


$newsletter_data = $newsletter->get_newsletter($db,$newsletter_id);

$sends = $newsletter->get_newsletter_sends($db,$newsletter_id);
?>

<h1>Newsletter</h1>

<form action="?p=open&save=true" method="post" id="create_form">

<input type="hidden" name="newsletter_id" value="<?php echo $newsletter_id;?>">

<a href="#" onclick="$('#other_settings').slideToggle(); return false;">show settings / edit newsletter again</a>

<div id="other_settings" style="display:none;">
<h2><span>Subject: <?php echo $newsletter_data['subject'];?></span></h2>

<div class="box">
	<table cellpadding="5">
		<tr>
			<td>
				Email Subject:
			</td>
			<td>
				<input type="text" class="input" name="subject" value="<?php echo $newsletter_data['subject'];?>">
			</td>
		</tr>
		<tr>
			<td>
				From Name:
			</td>
			<td>
				<input type="text" class="input" name="from_name" value="<?php echo $newsletter_data['from_name'];?>">
			</td>
		</tr>
		<tr>
			<td>
				From Email:
			</td>
			<td>
				<input type="text" class="input" name="from_email" value="<?php echo $newsletter_data['from_email'];?>">
			</td>
		</tr>
		<tr>
			<td>
				Bounce Email:
			</td>
			<td>
				<input type="text" class="input" name="bounce_email" value="<?php echo $newsletter_data['bounce_email'];?>"> (bounced newsletters get sent to this address)
			</td>
		</tr>
		<tr>
			<td>
				Advanced Editing:
			</td>
			<td>
				Your HTML newsletter is saved here: <a href="<?php echo _NEWSLETTERS_DIR;?>newsletter-<?php echo $newsletter_data['newsletter_id'];?>.html" target="_blank"><?php echo _NEWSLETTERS_DIR;?>newsletter-<?php echo $newsletter_data['newsletter_id'];?>.html</a> and here <a href="<?php echo _NEWSLETTERS_DIR;?>newsletter-<?php echo $newsletter_data['newsletter_id'];?>-full.html" target="_blank"><?php echo _NEWSLETTERS_DIR;?>newsletter-<?php echo $newsletter_data['newsletter_id'];?>-full.html</a> <br>
				You can download these files with FTP, make your advanced changes, and then re-upload it before clicking send below. <br>
				You can also go <a href="?p=create&newsletter_id=<?php echo $newsletter_data['newsletter_id'];?>">back</a> to the edit screen to change this newsletter.
			</td>
		</tr>
		<tr>
			<td>
				
			</td>
			<td>
				<input type="submit" name="save" value="Save">
			</td>
		</tr>
	</table>
</div>


<h2><span>Preview (optional)</span></h2>

<div class="box">
	<table cellpadding="5">
		<tr>
			<td>
				Preview in Browser
			</td>
			<td>
				<input type="submit" name="preview1" value="Open Preview" onclick="this.form.action='preview.php'; popupwin=window.open('about:blank','popupwin','width=700,height=800,scrollbars=1,resizeable=1'); if(!popupwin){alert('Please disable popup blocker'); return false;} this.form.target='popupwin';">
			</td>
		</tr>
		<tr>
			<td>
				Preview in Email
			</td>
			<td>
				 <input type="text" name="preview_email" id="preview_email" value="<?php echo $newsletter_data['from_email'];?>"><input type="submit" name="preview2" value="Send Preview" onclick="this.form.action='preview.php?email=true'; popupwin=window.open('about:blank','popupwin','width=500,height=400,scrollbars=1,resizeable=1'); if(!popupwin){alert('Please disable popup blocker'); return false;} this.form.target='popupwin';"> 
			</td>
		</tr>
	</table>
	
</div>

</div>

<h2><span>Send <?php echo (count($sends))?' newsletter out again':'';?></span></h2>

<div class="box">
	<table cellpadding="5">
		<tr>
			<td>
				Tick which groups you would like to send to:
			</td>
			<td>
				<input type="checkbox" name="group_id[]" value="ALL"> <b>All Members</b><br>
				<?php
				$groups = $newsletter->get_groups($db);
				foreach($groups as $group){ ?>
				<input type="checkbox" name="group_id[]" value="<?php echo $group['group_id'];?>"> <?php echo $group['group_name'];?> <br>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td>
				Don't send to people who have already received this newsletter:
			</td>
			<td>
				 <input type="checkbox" name="dont_send_duplicate" value="true" checked>
			</td>
		</tr>
		<tr>
			<td>
				Schedule send for a later date:
			</td>
			<td>
				 <input type="text" name="send_later" value="" size="10"> (date format: YYYY-MM-DD)
			</td>
		</tr>
		<tr>
			<td>
				
			</td>
			<td>
				 <input type="submit" name="send" value="Send<?php echo (count($sends))?' again':'';?>!">
			</td>
		</tr>
	</table>
</div>


<?php
// see if pending sends exist:
$pending = $newsletter->get_pending_sends($db,$newsletter_id);
if($pending){
	?>
	
	

		<h2><span>Pending Sends for this newsletter:</span></h2>
		
		<div class="box">
			<table cellpadding="5">
				<tr>
					<td>Newsletter</td>
					<td>Start Send</td>
					<td>Progress</td>
					<td>Action</td>
				</tr>
				<?php
				foreach($pending as $send){
					?>
					<tr>
						<td><?php echo $send['subject'];?></td>
						<td><?php echo $send['start_date'];?></td>
						<td><?php echo $send['progress'];?></td>
						<td><a href="?p=send&send_id=<?php echo $send['send_id'];?>">Continue Sending</a></td>
					</tr>
					<?php
				}
				?>
			</table>
				
		</div>
	<?
}


// see if previous sends exist
if($sends){
	?>
	
<h2><span>Previous Sends of this Newsletter</span></h2>
	
<div class="box">
	<table cellpadding="5">
		<tr>
			<td>Sent Date</td>
			<td>Sent To</td>
			<td>Opened By</td>
			<td>Unsubscribed</td>
			<td>Bounces</td>
			<td>Link Clicks</td>
			<td></td>
		</tr>
		<?php
		foreach($sends as $send){ 
			if(!$db)$db = db_connect();
			$sql = "SELECT * FROM `"._DB_PREFIX."send` WHERE `send_id` = '".$send['send_id']."'";
			$res_r = mysql_query($sql, $db);
			$send= mysql_fetch_assoc($res_r);
			//$num_sentMembers = mysql_num_rows($res_sentMembers);	
			
			//$send = $newsletter->get_send($db,$send['send_id']);
			?>
		<tr>
			<td>
				<?php echo date("Y-m-d",$send['start_time']);?>
			</td>
			<td>
				<?
           			if(!$db)$db = db_connect();
					$sql = "SELECT * FROM `"._DB_PREFIX."newsletter_member` nm LEFT JOIN `"._DB_PREFIX."member` m USING (member_id) WHERE nm.send_id = '".$send['send_id']."' AND nm.status != 1";
					$res_r = mysql_query($sql, $db);
					//$send= mysql_fetch_assoc($res_r);
					$num_sentMembers = mysql_num_rows($res_r);	

				?>
				<?php echo $num_sentMembers;?> members
			</td>
			<td>
				<?
           			if(!$db)$db = db_connect();
					$sql = "SELECT * FROM `"._DB_PREFIX."newsletter_member` nm LEFT JOIN `"._DB_PREFIX."member` m USING (member_id) WHERE nm.send_id = '".mysql_real_escape_string($send['send_id'])."' AND nm.open_time > 0";
					$res_r = mysql_query($sql, $db);
					//$send= mysql_fetch_assoc($res_r);
					$opened_members = mysql_num_rows($res_r);	

				?>
				<?php echo $opened_members;?> members
			</td>
			<td>
				<?
           			if(!$db)$db = db_connect();
					$sql = "SELECT * FROM `"._DB_PREFIX."newsletter_member` nm LEFT JOIN `"._DB_PREFIX."member` m USING (member_id) WHERE nm.send_id = '".mysql_real_escape_string($send['send_id'])."' AND m.unsubscribe_date != '0000-00-00' AND m.unsubscribe_send_id = '".mysql_real_escape_string($send['send_id'])."'";
					$res_r = mysql_query($sql, $db);
					//$send= mysql_fetch_assoc($res_r);
					$unsub_members = mysql_num_rows($res_r);	

				?>
				<?php echo $unsub_members;?> members
			</td>
			<td>
				<?
           			if(!$db)$db = db_connect();
					$sql = "SELECT * FROM `"._DB_PREFIX."newsletter_member` nm LEFT JOIN `"._DB_PREFIX."member` m USING (member_id) WHERE nm.send_id = '".mysql_real_escape_string($send['send_id'])."' AND nm.bounce_time > 0";
					$res_r = mysql_query($sql, $db);
					//$send= mysql_fetch_assoc($res_r);
					$bounce_members = mysql_num_rows($res_r);	

				?>
				<?php echo $bounce_members;?> members
			</td>
			<td>
				<a href="?p=stats&newsletter_id=<?php echo $newsletter_id;?>&send_id=<?php echo $send['send_id'];?>">View Stats</a>
			</td>
		</tr>
		<?php } ?>
		
	</table>
</div>

	<?php
}
?>

<h2><span>Other actions</span></h2>
	
<div class="box">
	<a href="#" onclick="if(confirm('Really delete this newsletter and all newsletter history? Cannot undo!')){ window.location.href='?p=open&newsletter_id=<?php echo $newsletter_id;?>&delete=true'; } return false;">Delete Newsletter</a>
</div>


</form>