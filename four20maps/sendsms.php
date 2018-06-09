<?
ob_start();
session_start();
$header = 'Categories';
$username = $_SESSION["regSuccess"];
if(empty($username)){ ?>
	<script>
		window.location.href = 'addstore.php';
	</script>
<?php }
include_once './includes/config.inc.php';
include_once './includes/functions.php';
include ('header.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$error = $succ_msg = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	 if($error =='') {
	$url='https://rest.nexmo.com/sms/json'; 
$fields = array(
 
	'api_key' => urlencode('3e1997ac'),
	'api_secret' => urlencode('60525d05b4f53e80'),
	'to' => urlencode('16198300710'),
	'from' => urlencode('12015799529'),
	'text' => urlencode('Hello from Nexmo') 
);

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');
		 
	$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

//execute post
$result = curl_exec($ch);   
         
$info  = curl_getinfo($ch); 
$error = curl_errno($ch);  
curl_close($ch);  	 
 
echo "<pre>";
		 print_r($message);exit;
//array access provides response data
echo "Sent message to " . $message['to'] . ". Balance is now " . $message['remaining-balance'] . PHP_EOL;

		$succ_msg = 'Message sentsuccessfully';
		header('Location: /categories.php');
		exit;
	}
}
 
?>
	<div class="row">
        <?php include ROOT."admin-left.php"; ?>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 myact-userdet">
            <h2 class="head-text">SMS</h2>
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
                <label class="col-lg-3 col-md-3 col-sm-5 col-xs-12 control-label">From<span class="require">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                        <input type="text" id="from"  name="from"   class="form-control required  " style="widows: 50%" placeholder="Enter Name to be appear">
                      </div>
              </div> 
					
                    <div class="form-group">
                <label class="col-lg-3 col-md-3 col-sm-5 col-xs-12 control-label">To<span class="require">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                        <input type="text" id="to"  name="to"   class="form-control required  " style="widows: 50%">
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
              </div>
      </div>
          </div>
  </div>
      </div>
<?php include ROOT."themes/footer.inc.php"; ?>
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