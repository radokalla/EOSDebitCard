<?php

// include config file

include_once './includes/config.inc.php';


if(isset($_POST['doLogin'])) {
	$login=array('username'=>$_POST['username'],'password'=>$_POST['password']);
	$db->Loginr('users',$login);
}
// list of available distances

/*$distances = array(

	100=>'100 Miles',

    50=>'50 Miles',

	10=>'10 Miles',

);





if(isset($_POST['ajax'])) {

	

	if(isset($_POST['action']) && $_POST['action']=='get_nearby_stores') {

		

		if(!isset($_POST['lat']) || !isset($_POST['lng'])) {

			

			echo json_encode(array('success'=>0,'msg'=>'Coordinate not found'));

		exit;

		}

		

		// support unicode

		mysql_query("SET NAMES utf8");



		// category filter

		if(!isset($_POST['products']) || $_POST['products']==""){

			$category_filter = "";

		} else {

			$category_filter = " AND cat_id='".$_POST['products']."'";

		}

		

		$sql = "SELECT *, ( 3959 * ACOS( COS( RADIANS(".$_POST['lat'].") ) * COS( RADIANS( latitude ) ) * COS( RADIANS( longitude ) - RADIANS(".$_POST['lng'].") ) + SIN( RADIANS(".$_POST['lat'].") ) * SIN( RADIANS( latitude ) ) ) ) AS distance FROM stores WHERE status=1 AND approved=1 ".$category_filter." HAVING distance <= ".$_POST['distance']." ORDER BY distance ASC LIMIT 0,60";

	

		

		

		echo json_stores_list($sql);

	}

exit;

}*/





/*$errors = array();



if($_POST) {

	if(isset($_POST['address']) && empty($_POST['address'])) {

		$errors[] = 'Please enter your address';

	} else {



			

		$google_api_key = '';



		$region = 'us';



		

		

		$xml = convertXMLtoArray($tmp);

		

		if($xml['Response']['Status']['code']=='200') {

			

			$coords = explode(',', $xml['Response']['Placemark']['Point']['coordinates']);

			

			if(isset($coords[0]) && isset($coords[1])) {

				

				$data = array(

					'name'=>$v['name'],

					'address'=>$v['address'],

					'latitude'=>$coords[1],

					'longitude'=>$coords[0]

				);



				

				$sql = "SELECT *, ( 3959 * ACOS( COS( RADIANS(".$coords[1].") ) * COS( RADIANS( latitude ) ) * COS( RADIANS( longitude ) - RADIANS(".$coords[0].") ) + SIN( RADIANS(".$coords[1].") ) * SIN( RADIANS( latitude ) ) ) ) AS distance FROM stores WHERE status=1 HAVING distance <= ".$db->escape($_POST['distance'])." ORDER BY distance ASC  LIMIT 0,60";

				

				$stores = $db->get_rows($sql);



				

				if(empty($stores)) {

					$errors[] = 'Stores with address '.$_POST['address'].' not found.';

				}

			} else {

				$errors[] = 'Address not valid';

			}

		} else {

			$errors[] = 'Entered address'.$_POST['address'].' not found.';

		}

	}

}*/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>
	<head>
	<title><?php echo $lang['STORE_FINDER']; ?>- Google Maps Store Locator with Google Street View, Google Direction, Admin Area, Category Icons, Store Thumbnail, Custom Markers, Google Maps API v3</title>
	<meta name="keywords" content="street view, google direction, ajax, bootstrap, embed, geo ip, geolocation, gmap, google maps, jquery, json, map, responsive, store admin, store finder, store locator" />
	<meta name="description" content="Super Store Finder &amp;#8211; Easy to use Google Maps API Store Finder Super Store Finder is a multi-language fully featured PHP Application integrated with Google Maps API v3 that allows customers to..." />
	<link rel="shortcut icon" href="img/favicon.ico" />
	<script>



	function changeLang(v){

	document.location.href="?langset="+v;

	}

	</script>
	<?php include ROOT."settings.php"; ?>
	<?php include ROOT."themes/meta_mobile.php"; ?>
	</head>

	<body id="super-store-finder">

<!-- Start Head Container -->
<div class="header-wps">
      <div class="container">
    <nav class="navbar navbar-inverse" role="navigation" style="float:left; width:100%; margin-bottom:0px;">
          <div class="container-fluid">
        <div class="row"> 
              <!-- Brand and toggle get grouped for better mobile display -->
              <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menubar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            <a class="navbar-brand logo-wps" href="index.php"><img src="<?php echo ROOT_URL; ?>img/logo.png"></a> </div>
              
              <!-- Collect the nav links, forms, and other content for toggling -->
              <div class="collapse navbar-collapse" id="menubar">
            <ul class="nav navbar-nav pull-right">
                  <!--<li class="active"><a href="flowers.php">Flowers</a></li>
            <li><a href="#">Concentrates</a></li>
            <li><a href="#">Edibles</a></li>
            <li><a href="#">Topicals</a></li>
            <li><a href="#">Seeds</a></li>
            <li><a href="#">Accessories</a></li>--> 
                  
                  <!--<li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li><a href="#">Separated link</a></li>
                <li class="divider"></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>-->
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
                  
                </ul>
          </div>
              <!-- /.navbar-collapse --> 
            </div>
      </div>
          <!-- /.container-fluid --> 
        </nav>
  </div>
    </div>
<?php /*?><div class="container_12 margin"> 
      
      <!-- Logo -->
      
      <h1 class="grid_4 logo"><a href="http://superstorefinder.net" class='ie6fix'>Super Store Finder</a></h1>
    </div><?php */?>

<!-- Head Container END --> 

<!-- CLEAR --> 

<!-- Start Header Break Line --> 

<!-- Header Break Line END --> 

<!-- CLEAR --> 

<!-- Start Teaser --> 

<!-- Teaser END -->

<div class="clear"></div>

<!-- Start Container 12 -->

<div id="main_content" class="container_12">
      <div class="feature-wps">
    <div class="container">

              <?php /*?><div class="row">
            <div style="padding:10px;">Language:
                  <select onChange="changeLang(this.value)">
                <option value="en_US" <?php if(!isset($_SESSION['language']) || $_SESSION['language']=="en_US") { ?>selected<?php } ?>>English</option>
                <option value="sv_SE" <?php if(isset($_SESSION['language']) && $_SESSION['language']=="sv_SE") { ?>selected<?php } ?>>Swedish</option>
                <option value="es_ES" <?php if(isset($_SESSION['language']) && $_SESSION['language']=="es_ES") { ?>selected<?php } ?>>Spanish</option>
                <option value="fr_FR" <?php if(isset($_SESSION['language']) && $_SESSION['language']=="fr_FR") { ?>selected<?php } ?>>French</option>
                <option value="de_DE" <?php if(isset($_SESSION['language']) && $_SESSION['language']=="de_DE") { ?>selected<?php } ?>>German</option>
                <option value="cn_CN" <?php if(isset($_SESSION['language']) && $_SESSION['language']=="cn_CN") { ?>selected<?php } ?>>Chinese</option>
                <option value="kr_KR" <?php if(isset($_SESSION['language']) && $_SESSION['language']=="kr_KR") { ?>selected<?php } ?>>Korean</option>
                <option value="jp_JP" <?php if(isset($_SESSION['language']) && $_SESSION['language']=="jp_JP") { ?>selected<?php } ?>>Japanese</option>
                <option value="ar_AR" <?php if(isset($_SESSION['language']) && $_SESSION['language']=="ar_AR") { ?>selected<?php } ?>>Arabic</option>
              </select>
                </div>
          </div><?php */?>
              <div class="row">
              		<div class="col-md-2"></div>
              		<div class="col-md-9">
        <form method="post" name="login_form" class="xform">


                    <section>
                        <div class="row">
                            <div class="col-md-12">
                                <h2>Login</h2>
                                <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input  type="text" name="username" placeholder="Username">
                                </label>
                            </div>
                        </div>
                    </section>
              		<section>
                		<div class="row">
                  			<div class="col-md-12">
                                <label class="input"> <i class="icon-prepend fa fa-lock"></i>
                                  <input type="password"  name="password" placeholder="Password">
                                </label>
                  			</div>
                		</div>
              		</section>
                		<div class="row">
                            <div class="col-md-12"><input type="checkbox"> Remember me <a href="#">Forgot password</a></div>
                            <div class="col-md-12 login-btn-mar">
                            <button type="submit" name="dosubmit" class="button">Login</button>
                            </div>
                		</div>
              <input name="doLogin" type="hidden" value="1" />
 

        </form>
	</div>
                
          </div>
        </div>
  </div>
      <?php /*?><div id="main">
    <div class="width-container">
          <div id="container-sidebar">
        <div class="content-boxed">
              <div class="clearfix"></div>
            </div>
      </div>
          <!-- close #container-sidebar -->
          
          <div class="clearfix"></div>
        </div>
    <!-- close .width-container --> 
    
  </div><?php */?>
      <!-- close #main -->
      
      <?php /*?><center>
      <div class="clear"></div>
      <!-- CLEAR --> 
      
      <br>
      <br>
      <h4><?php echo $lang['EMBED']; ?>:</h4>
      <textarea id="embed" style="width:650px;"><iframe src="<?php echo ROOT_URL; ?>embed.php" width="100%" height="1180px" scrolling=no frameborder=no allowtransparency="true"></iframe>
</textarea>
      <br>
      <br>
      <div class="grid_12">
    <h1 class="heading">LIKE THE PRODUCT? SHARE THE LOVE..</h1>
    <a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
    <div class="fb-like" data-href="http://superstorefinder.net/products/superstorefinder" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false"></div>
    <br />
    <br />
    <a class="button main_btn eager block" href="http://codecanyon.net/item/super-store-finder/3630922">BUY THIS ITEM AT CODECANYON FOR ONLY $11</a> <br>
    <br>
    <style>

		



			.eager {

				background-color:#8BAA20;

				color: #FFF;

				font-size: 22px;

				padding:18px 18px;

				border: 1px solid #7E9920;

				text-shadow: 1px 1px 0 #7E9920;

			}

			.eager:hover {

				background-color:#7E9920;

				color: #fff;

				border: 1px solid #768E1C;

			}

			

			a.button, input[type="submit"], input[type="button"] {

				border: 1px solid rgba(0, 0, 0, 0.1);

				-webkit-box-shadow: 0 1px 1px rgba(200, 200, 200, 0.4) inset, 1px 1px 3px rgba(0, 0, 0, 0.2);

				-moz-box-shadow: 0 1px 1px rgba(200, 200, 200, 0.4) inset, 1px 1px 3px rgba(0, 0, 0, 0.2);

				box-shadow: 0 1px 1px rgba(200, 200, 200, 0.4) inset, 1px 1px 3px rgba(0, 0, 0, 0.2);

				cursor: pointer;

				display: inline-block;

				font-family: Arial, Helvetica, Sans-Serif;

				font-weight: 700;

				line-height: normal !important; 

				text-align: center;

				text-decoration:none;

				-webkit-transition: border-color .218s;

				-webkit-border-radius: 2px;

				-moz-border-radius: 2px;

				border-radius: 2px;

			}


			input::-moz-focus-inner,

			button::-moz-focus-inner { padding:0; border:0; }



			a.button:active, input[type="submit"]:active {

				-webkit-box-shadow: inset 0 0 5px #555;

				-moz-box-shadow: inset 0 0 5px #555;

				box-shadow: inset 0 0 5px #555;

			}





		</style>
    </center><?php */?>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
    <div id="fb-root"></div>
    <script>(function(d, s, id) {

	  var js, fjs = d.getElementsByTagName(s)[0];

	  if (d.getElementById(id)) return;

	  js = d.createElement(s); js.id = id;

	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=250642888282319";

	  fjs.parentNode.insertBefore(js, fjs);

	}(document, 'script', 'facebook-jssdk'));

	</script> 
    <script>

		if(geo_settings==1){

				$('#address').val(geoip_city()+", "+geoip_country_name());

		} else {

				$('#address').val(default_location);

		}

	</script>
    
    <?php /*?><div class="clear"></div>
	<center>
          <br>
          <br>
          <a href="index.php" rel="nofollow" ><img src="http://superstorefinder.net/img/store.jpg" alt="Super Store Finder Demo"></a> <a href="admin" rel="nofollow" ><img src="http://superstorefinder.net/img/admin.jpg" alt="Super Store Finder Admin Demo"></a> <br>
          <a href="http://superstorefinder.net/products/superstorefinder/index_geoip.php" rel="nofollow" ><img src="http://superstorefinder.net/img/geoip.jpg" alt="Super Store Finder with Geo IP"></a> <a href="http://superstorefinder.net/clients/responsive" rel="nofollow" ><img src="http://superstorefinder.net/img/responsive.jpg" alt="Super Store Finder Responsive Demo"></a>
        </center><?php */?>
  </div>
    </div>
<!-- Container 12 END-->

<?php include ROOT."themes/footer.inc.php"; ?>
</body>
</html>