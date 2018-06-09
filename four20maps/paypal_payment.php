<?
if(isset($_POST['store_id'])){
session_start();
include_once './includes/config.inc.php';
$userId = $_SESSION['StoreID'];
$owner_id=$_POST['owner_id'];
$category_id=$_POST['category_id'];
$product_id=$_POST['product_id'];
$store_id=$_POST['store_id'];
$qty=$_POST['qty'];
$total_price = $_POST['total_price'];
mysql_query("INSERT INTO `order_header`(`user_id`, `total_amount`, `CreditCardTransID`,status) VALUES ('".$userId."','".$total_price."','',0)");
							$order_id=mysql_insert_id();				
							
							 mysql_query("INSERT INTO `order_detail`( `order_id`, `user_id`, `owner_id`, `category_id`, `product_id`, `store_id`, `price`,qty) VALUES ('".$order_id."','".$userId."','".$owner_id."','".$category_id."','".$product_id."','".$store_id."','".$total_price."','".$qty."')");

?>
 <HTML>
<HEAD>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<?php /*?><script type="text/javascript" src="http://formvalidation.io/vendor/formvalidation/js/formValidation.min.js"></script>
<script type="text/javascript" src="http://formvalidation.io/vendor/formvalidation/js/framework/bootstrap.min.js"></script>
<script type="text/javascript" src="http://formvalidation.io/vendor/jquery.steps/js/jquery.steps.min.js"></script><?php */?>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css" rel="stylesheet" />
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet" />
<?php /*?><link href="http://formvalidation.io/vendor/jquery.steps/css/jquery.steps.css" rel="stylesheet" />
<link href="http://formvalidation.io/vendor/formvalidation/css/formValidation.min.css" rel="stylesheet" /><?php */?>
<?php /*?><script type="text/javascript" src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/aes.js"></script><?php */?>
<meta charset="utf-8" />
<title>Payment Service Provider | Merchant Accounts</title>
<style>
.has-success .form-control, .has-success .control-label, .has-success .radio, .has-success .checkbox, .has-success .radio-inline, .has-success .checkbox-inline {
	color: #1cb78c !important;
}
.has-success .help-block {
	color: #1cb78c !important;
	border-color: #1cb78c !important;
	box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 6px #1cb78c;
}
.has-error .form-control, .has-error .help-block, .has-error .control-label, .has-error .radio, .has-error .checkbox, .has-error .radio-inline, .has-error .checkbox-inline {
	color: #f0334d;
	border-color: #f0334d;
	box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 6px #f0334d;
}
table {
	color: #333; /* Lighten up font color */
	font-family: 'Raleway', Helvetica, Arial, sans-serif;
	font-weight: bold;
	width: 640px;
	border-collapse: collapse;
	border-spacing: 0;
}
td, th {
	border: 1px solid #CCC;
	height: 30px;
} /* Make cells a bit taller */
th {
	background: #F3F3F3; /* Light grey background */
	font-weight: bold; /* Make sure they're bold */
	font-color: #1cb78c !important;
}
td {
	background: #FAFAFA; /* Lighter grey background */
	text-align: left;
	padding: 2px;/* Center our text */
}
label {
	font-weight: normal;
	display: block;
}
</style>
</HEAD>
<BODY>
   <div class="container cs-border-light-blue"> 
    
    <!-- first line -->
    <div class="row pad-top"></div>
    <!-- end first line -->
    
    <div class="equalheight row" style="padding-top: 10px;">
      <div id="cs-main-body" class="cs-text-size-default pad-bottom">
        <div class="col-sm-9  equalheight-col pad-top">
          <div style="padding-bottom: 50px;">
            <h1>Initiating your payment process</h1>
            <div class="row">
              <div class="col-sm-12">
                <legend>Your payment is being processed, Please wait for a moment.</legend>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <form name="paypal_form" action="https://www.paypal.com/cgi-bin/webscr" method="post"   id="paypal_payment_form">
				<input type="hidden" name="business" value="radokalla@sbcglobal.net" />
				<input type="hidden" name="notify_url" value="http://www.four20maps.com/paypalnotify.php" />
				<input type="hidden" name="cancel_return" value="http://www.four20maps.com/payment_thankyou.php" />
				<input type="hidden" name="return" value="http://www.four20maps.com/payment_cancel.php" />
				<input type="hidden" name="currency_code" value="USD" />
				<input type="hidden" name="page_style" value="paypal" />
				<input type="hidden" name="charset" value="utf-8" />
				<input type="hidden" name="item_name" value="<?=$_POST["productname"];?>" />
				<input type="hidden" name="cbt" value="<?=$order_id;?>" />
				<input type="hidden" name="qty" value="<?=$qty;?>" />
				<input type="hidden" value="_xclick" name="cmd"/>
				<input type="hidden" name="amount" value="<?=$total_price;?>" />
 
 </form>
</BODY>
</HTML>
<script type="text/javascript">
$(document).ready(function(e) {
   $("#paypal_payment_form").submit();
});
</script>
<?php }?>