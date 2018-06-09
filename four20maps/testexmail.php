<?php
$_POST['Firstname']="Rajesh";
$_POST['Lastname']="Rajesh";
$_POST['Username']="Rajesh";
 
						 	$to = "rajeshm@inducosolutions.com";
							$subject = "Four20maps Confirmation";
						$txt="<html><head><title>Four20maps Confirmation</title></head><body><table cellpadding='5' cellspacing='5'> <tbody> <tr><td align='center'><img src='http://www.four20maps.com/img/logo.png'/></td> </tr><tr><td><b>Hello ".$_POST['Firstname']." ".$_POST['Lastname']."</b></td> </tr> <tr> <td>Thank you for registering with four20maps.com where you can find legal dispensaries with delivery services. Please send us any comments you may have and please don't forget to rate the the dispensaries in our network.</td></tr> <tr> <td>Please use this login User ID ".$username = $_POST['Username']."</td> </tr><tr><td>Please <a href='http://www.four20maps.com'>Click Here</a> to redirect.</td></tr><tr><td>Sincerely,</td></tr><tr><td>four20maps.com Team</td></tr></tbody></table></body></html>";
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <support@four20maps.com>' . "\r\n";  
							mail($to,$subject,$txt,$headers); 
?>