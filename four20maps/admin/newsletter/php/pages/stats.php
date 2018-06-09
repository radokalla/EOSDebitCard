<?php

$newsletter_id = (int)$_REQUEST['newsletter_id'];
if(!$newsletter_id){
	// basic error checking.
	echo 'Please go back and pick a newsletter';
}
$send_id = (int)$_REQUEST['send_id'];
if(!$send_id){
	// basic error checking.
	echo 'Please go back and pick a newsletter';
}


$newsletter_data = $newsletter->get_newsletter($db,$newsletter_id);
// todo - check this send belongs to this newsletter, oh wel.
$send = $newsletter->get_stats_send($db,$send_id);


// grab the full html content.
if(isset($_REQUEST['iframe'])){
	ob_end_clean();
	
	$template_html = $send['template_html'];
	if(preg_match_all('#<a href=["\'].*ext\.php\?t=lnk&id=(\d+)&#',$template_html,$matches)){
		$processed_links=array();
		foreach($matches[0] as $key => $val){
			$link_id = (int)$matches[1][$key];
			if(isset($processed_links[$link_id]))continue;
			$link = $newsletter->get_link($db,$link_id);
			//open_rates
			$template_html = preg_replace('/' . preg_quote($val,'/') . '/', '<span class="newsletter-click-span">'. count($link['open_rates']) . ' clicks</span>' . $val, $template_html);
			$processed_links[$link_id]=true;
		}
	}
	?>
	<style type="text/css">
	span.newsletter-click-span{
	background-color:#FFFFFF !important;
	border:1px solid #000000 !important;
	color:#000000 !important;
	font-size:10px !important;
	padding:2px !important;
	text-decoration:none !important;
	font-weight:normal !important;
	position:absolute !important;
	margin-left:0px !important;
	filter:alpha(opacity=50);
	-moz-opacity:0.5;
	-khtml-opacity: 0.5;
	opacity: 0.5;

	}
	</style>
	
	<?php
	echo $template_html;
	
	exit;
}


?>

<a href="?p=open&newsletter_id=<?php echo $newsletter_id;?>">&laquo; Back to newsletter</a>

<h2><span>Newsletter Link Clicks:</span></h2>

<iframe src="?p=stats&iframe=true&newsletter_id=<?php echo $newsletter_id;?>&send_id=<?php echo $send_id;?>" frameborder="0" style="border:1px solid #CCCCCC; width:700px; height:600px;"></iframe>


<h2><span>Newsletter Stats:</span></h2>

<div class="box">
	<table cellpadding="5">
		<tr>
			<th>Send Date</th>
			<th>Email Subject</th>
			<th>Sent From</th>
			<th>Sent To</th>
			<th>Opened By</th>
			<th>Unsubscribed</th>
			<th>Bounces</th>
		</tr>
		<tr>
			<td>
				<?php echo date("Y-m-d H:i:s",$send['start_time']);?>
			</td>
			<td>
				<?php echo $newsletter_data['subject'];?>
			</td>
			<td>
				&lt;<?php echo $newsletter_data['from_name'];?>&gt; <?php echo $newsletter_data['from_email'];?> 
			</td>
			<td>
				<?php echo $send['sent_members1'];?> members
			</td>
			<td>
				<?php echo $send['opened_members'];?> members
			</td>
			<td>
				<?php echo $send['unsub_members'];?> members
			</td>
			<td>
				<?php echo $send['bounce_members'];?> members 
			</td>
		</tr>
	</table>
</div>
		

<div class="box">
	<table cellpadding="5">
		<tr>
			<th>Sent To</th>
			<th>Opened</th>
			<th>Unsubscribed</th>
			<th>Bounced</th>
		</tr>
		<?php 
		 
// call the class file
require_once("php/class.pagination.php"); 

// First select.
$sql1 = "SELECT  * FROM `newsletter_member` nm LEFT JOIN `member` m USING (member_id) WHERE nm.send_id = '".mysql_real_escape_string($send_id)."' AND nm.status != 1"; 
$rowsperpage = 30; // 5 records per page. You can change it.
$link=explode('&',$_SERVER[REQUEST_URI]);

$website = "http://$_SERVER[HTTP_HOST]$link[0]&$link[1]&$link[2]"."&id=1"; // other arguments if need it.
$pagination = new CSSPagination($sql1, $rowsperpage, $website); // create instance object
$pagination->setPage($_GET[page]); // dont change it
 
$db = db_connect();
// Second select is similar at the top one, but it follows by limitation.
$sql2 = "SELECT  * FROM `newsletter_member` nm LEFT JOIN `member` m USING (member_id) WHERE nm.send_id = '".mysql_real_escape_string($send_id)."' AND nm.status != 1 LIMIT " . $pagination->getLimit() . ", " . $rowsperpage; 
$result = query($sql2,$db) or die("failed");
while ($rows = mysql_fetch_array($result))
{
$send['sent_members'][]=$rows;
}
 
// Show the pagination index
 foreach($send['sent_members'] as $sent_member){
			$member_data = $newsletter->get_member($db,$sent_member['member_id']);
			?>
			<tr>
				<td>
					<a href="?p=members&edit_member_id=<?php echo $sent_member['member_id'];?>">&lt;<?php echo $member_data['first_name'].' '.$member_data['last_name'];?>&gt; <?php echo $member_data['email'];?></a>
				</td>
				<td>
					<?php if(isset($member_data['opened'][$send_id])){
						echo 'YES: '.date("Y-m-d H:i:s",$member_data['opened'][$send_id]['open_time']);
					}else{
						echo 'NO';
					}
					?>
				</td>
				<td>
					<?php if(isset($member_data['unsubscribe'][$send_id])){
						echo 'YES: '.$member_data['unsubscribe'][$send_id]['unsubscribe_date'];
					}else{
						echo 'NO';
					}
					?>
				</td>
				<td>
					<?php if(isset($member_data['bounces'][$send_id])){
						echo 'YES: '.date("Y-m-d H:i:s",$member_data['bounces'][$send_id]['bounce_time']);
					}else{
						echo 'NO';
					}
					?>
				</td>
			</tr>
			<?
		}
		?><tr>
				<td> <?php echo $pagination->showPage(); ?>
	</table>
</div>
<style type="text/css">
div.pagination {
	padding: 3px;
	margin: 3px;
}

div.pagination a {
	padding: 2px 5px 2px 5px;
	margin: 2px;
	border: 1px solid #AAAADD;
	
	text-decoration: none; /* no underline */
	color: #000099;
}
div.pagination a:hover, div.pagination a:active {
	border: 1px solid #000099;

	color: #000;
}
div.pagination span.current {
	padding: 2px 5px 2px 5px;
	margin: 2px;
		border: 1px solid #000099;
		
		font-weight: bold;
		background-color: #000099;
		color: #FFF;
	}
	div.pagination span.disabled {
		padding: 2px 5px 2px 5px;
		margin: 2px;
		border: 1px solid #EEE;
	
		color: #DDD;
	}
	</style>