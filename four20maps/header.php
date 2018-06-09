<?
include_once './includes/config.inc.php';
if(!empty($_SESSION["NOPAYMENT"]))
{
if($_SESSION["NOPAYMENT"] != "0") ?>
	<script>window.location.href = 'payment.php?type='+<?php echo $_SESSION["payAmount"]?></script>
<?php }
error_reporting(0);
$date = date("Y:m:d");
?>
<html>
	  <head>
	  <title><?php echo 'Four20Maps'; ?></title>
	  <meta name="keywords" content="street view, google direction, ajax, bootstrap, embed, geo ip, geolocation, gmap, google maps, jquery, json, map, responsive, store admin, store finder, store locator" />
	  <meta name="description" content="Super Store Finder &amp;#8211; Easy to use Google Maps API Store Finder Super Store Finder is a multi-language fully featured PHP Application integrated with Google Maps API v3 that allows customers to..." />
	  <link rel="shortcut icon" href="img/favicon.ico" />
	  <?php include ROOT."settings.php"; ?>
	  <?php  include ROOT."themes/meta_mobile.php"; ?>
	  <link rel="stylesheet" type="text/css" href="<?=ROOT_URL;?>css/style-four20.css"/>
	  <link rel="stylesheet" type="text/css" href="<?=ROOT_URL;?>css/jquery.mCustomScrollbar.min.css"/>
	  <link rel="stylesheet" type="text/css" href="<?=ROOT_URL;?>css/jquery-ui-1.10.3.custom.min.css"/>
 

	  <script type="text/javascript">
		$(function() {
			function chechfooter(){
				var winH = $(window).height();
				 var conH = $('.container').height() + 60;
				 console.log(winH + ' ' + conH);
				 if(conH < winH){
					 $('#footer').addClass('fixed-footer');
				 }
				 else{
					 $('#footer').removeClass('fixed-footer');
				 }
			}
			chechfooter();
			$(window).resize(function(){
				chechfooter();
			});
			
		});
	</script>
		
	  
	  <style>
		.error
		{
			color:red;
		}
	</style>
	  </head>
	  <body id="super-store-finder">
<div class="header-wps">
	<div class="container">
		<nav class="navbar navbar-inverse">
			<div class="container-fluid"> 
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
					<a class="navbar-brand logo-wps" href="<?=ROOT_URL;?>index.php"><img src="<?php echo ROOT_URL; ?>img/logo.png"></a> 
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav pull-right">
						<li class="pull-right">
							<?php if(!isset($_SESSION['regSuccess']) && empty($_SESSION['regSuccess'])){ ?>
								<li><a href="#"  data-toggle="modal" data-target="#myModal1" id="login_id">Login</a></li>
							<?php } else { ?>
								<li><a href="<?=ROOT_URL;?>dashboard.php">Dashboard</a></li>
								<li><a href="<?=ROOT_URL;?>storelogout.php">Logout</a></li>
							<?php } ?>
						</li>
					</ul>
				</div>
			</div>
		</nav>
	</div>
</div>
<div class="clear"></div>
<div id="main_content" class="container_12">
        <div class="feature-wps <?php if(empty($_SESSION['regSuccess'])){ echo "home-content"; } ?>">
    		<div class="container">