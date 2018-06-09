<?
session_start();
include_once($_SERVER['DOCUMENT_ROOT']."/includes/config.inc.php");
//include_once './includes/config.inc.php';
error_reporting(0);

?>
<html>
	  <head>
	  <title><?php echo 'Four20maps'; ?></title>
	  <meta name="keywords" content="street view, google direction, ajax, bootstrap, embed, geo ip, geolocation, gmap, google maps, jquery, json, map, responsive, store admin, store finder, store locator" />
	  <meta name="description" content="Super Store Finder &amp;#8211; Easy to use Google Maps API Store Finder Super Store Finder is a multi-language fully featured PHP Application integrated with Google Maps API v3 that allows customers to..." />
	  <link rel="shortcut icon" href="img/favicon.ico" />
	  <?php include ROOT."settings.php"; ?>
	  <?php include ROOT."themes/meta_mobile.php"; ?>
	  <link rel="stylesheet" type="text/css" href="http://four20maps.com/css/style-four20.css"/>
	  <link rel="stylesheet" type="text/css" href="http://four20maps.com/css/jquery.mCustomScrollbar.min.css"/>
	  <link rel="stylesheet" type="text/css" href="http://four20maps.com/css/jquery-ui-1.10.3.custom.min.css"/>
	  <link rel="stylesheet" href="http://four20maps.com/css/jquery-ui.css">
	  <script src="http://four20maps.com/js/jquery-1.10.2.js"></script>
	  <script src="http://four20maps.com/js/jquery-ui.js"></script>
	  <script src="http://four20maps.com/js/jquery.validate.js"></script>
	  <script src="http://four20maps.com/js/jquery.maskedinput.js"></script>
	  <script src="http://four20maps.com/pagescript.js"></script>
	  <script>
				$.widget( "custom.catcomplete", $.ui.autocomplete, {
			_create: function() {
			  this._super();
			  this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );
			},
			_renderMenu: function( ul, items ) {
			  var that = this,
				currentCategory = "";
			  $.each( items, function( index, item ) {
				var li;
				if ( item.category != currentCategory ) {
				  ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
				  currentCategory = item.category;
				}
				li = that._renderItemData( ul, item );
				if ( item.category ) {
				  li.attr( "aria-label", item.category + " : " + item.label );
				}
			  });
			}
		  }); 
	  </script>
	  </head>

	  <body id="super-store-finder">
<div class="header-wps">
        <div class="container">
    <nav class="navbar navbar-inverse">
            <div class="container-fluid"> 
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                <a class="navbar-brand logo-wps" href="index.php"><img src="<?php echo ROOT_URL; ?>img/logo.png"></a> </div>
        
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="search-finderwps collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <div class="form-item" id="edit-gmap-address-wrapper">
				<div class="input-schbx">
					<input type="text" name="address" id="search_box" class="form-text form-control" placeholder="Search Strain Delivery or Dispensary"/>
					<button class="icn-closebtn" type="button">x</button>
				</div>
            <input type="button" name="op" id="edit-submit" value="<?php echo $lang['FIND_STORE']; ?>" class="btn btn-large btn-primary" />
          </div>
                <ul class="login-resiwps nav navbar-nav">
            <?php if(isset($_SESSION['userid'])){?>
            <li><a href="#" data-toggle="modal" data-target="#myModal3" id="myaccount_view">My account</a></li>
            <li><a href="logout.php">Logout</a></li>
            <?php }else {?>
            <li><a href="#"  data-toggle="modal" data-target="#myModal1" id="login_id">Login</a></li>
            <li><a href="#"  data-toggle="modal" data-target="#myModal2" id="register_id">Register</a></li>
            <li><a href="addstore.php">Add Store</a></li>
            <?php } ?>
          </ul>
              </div>
        <!-- /.navbar-collapse -->
        
        <?php /*?><div class="search-finderwps collapse navbar-collapse" id="menubar">
            <div class="form-item" id="edit-gmap-address-wrapper">
                    <input type="text" maxlength="128" name="address" id="search_box" size="60" style="float:left" class="form-text" placeholder="Search Strain Delivery or Dispensary"/>
                    <input type="button" name="op" id="edit-submit" value="<?php echo $lang['FIND_STORE']; ?>" class="btn btn-large btn-primary" />
                  </div>
            <ul class="login-resiwps nav navbar-nav">
                    <?php if(isset($_SESSION['userid'])){?>
                    <li><a href="#" data-toggle="modal" data-target="#myModal3" id="myaccount_view">My account</a></li>
                    <li><a href="logout.php">Logout</a></li>
                    <?php }else {?>
                    <li><a href="#"  data-toggle="modal" data-target="#myModal1" id="login_id">Login</a></li>
                    <li><a href="#"  data-toggle="modal" data-target="#myModal2" id="register_id">Register</a></li>
                    <?php } ?>
                  </ul>
          </div><?php */?>
      </div>
            <!-- /.container-fluid --> 
          </nav>
    <?php /*?><nav class="navbar navbar-inverse" role="navigation" style="float:left; width:100%; margin-bottom:0px;">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                
            
                
      </div>
          </nav><?php */?>
  </div>
      </div>
<?php if(!empty($_SESSION['message'])) { ?>
<h4 style="text-align:center" class="alert alert-success" role="alert"><?php echo $_SESSION['message'] ?></h4>
<?php  unset($_SESSION['message']); } ?>
<div class="clear"></div>
<div id="main_content" class="container_12">
        <div class="feature-wps home-content">
    		<div class="container">
            	<div class="row marb-50"> 
    <!--<div class="col-md-12"><h1>Membership Packages</h1></div>-->
    <div class="col-md-7">
      <div class="planpack">
        <h2>
          <center>
            Map Icon Subscription
          </center>
        </h2>
        <div class="row">
          <div class="col-md-6 paddingr0">
            <p class="setfee">Delivery<br>
             Dispensary FREE Listing</p>
          </div>
          <div class="col-md-6 paddingl0">
            <p class="price">Bronze Delivery<br>
             Dispensary Listing $100.00</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 paddingr0">
            <p class="price">Silver Delivery<br>
             Dispensary Listing $200.00 </p>
          </div>
          <div class="col-md-6 paddingl0">
            <p class="setfee">Gold Delivery<br>
             Dispensary Listing $500.00</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 paddingr0">
            <p class="setfee">Premium Delivery<br>
             Dispensary Listing $1000.00</p>
          </div>
          <div class="col-md-6 paddingl0">
          </div>
        </div>
      </div>
      <div onclick="$('#register-form').toggle();" class="purchase-btn"> <a href="javascript:">
        <p class="purchase">Buy Now <i class="fa fa-cart-plus"></i></p>
        </a> </div>
       
      <div style="display:none;" id="register-form" class="reg-fields-form">
              <form action="" class="xform" name="reg_form" onsubmit="return validateForm()" method="post">
          <div class="row">
          <div class="col-md-1"></div>
          <div class="col-md-9">
            <section>
              <div class="row">
                <div class="col-md-12">
                  <header>Register <span>(New user)</span></header>
                  <label class="input"> <i class="icon-prepend fa fa-user"></i>
                    <input type="text" class="required" placeholder="Name" id="uname" name="username">
                  </label>
                </div>
              </div>
            </section>
            <section>
              <div class="row">
                <div class="col-md-12">
                  <label class="input"> <i class="icon-prepend fa fa-envelope"></i>
                    <input type="text" class="required" placeholder="Email Id" id="email" name="email">
                  </label>
                </div>
              </div>
            </section>
            <section>
            <div class="row">
              <div class="col-md-12">
                <label class="input"> <i class="icon-prepend fa fa-unlock"></i>
                  <input type="password" class="required" placeholder="Password" id="pswrd" name="password">
                </label>
              </div>
              </div>
              </section>
              <section>
                <div class="row">
                  <div class="col-md-12">
                    <label class="input"> <i class="icon-prepend fa fa-phone"></i>
                      <input type="text" class="required" placeholder="PhoneNumber" id="phno" name="PhoneNumber">
                    </label>
                  </div>
                </div>
              </section>
              <footer>
                <div class="row">
                  <div class="col-md-12"> 
                    <!--<a href="" class="button button-secondary" onclick="return reg()">Register</a>-->
                    
                    <button class="button" onclick="window.location.href='payment.php';return false;">Register</button>
                  </div>
                </div>
              </footer>
            </div>
            <div class="col-md-2"></div>
          </div>
        </form>
      </div>
    </div>
    <div class="col-md-5">
      <div class="planpack-details">
        <h2>Free Map Listing</h2>
        <ul>
          <li>List your Delivery / Dispensary for FREE</li>
          <li>Upgrade your Delivery / Dispensary Listing to Bronze</li>
          <li>Upgrade your Delivery / Dispensary Listing to Silver</li>
          <li>Upgrade your Delivery / Dispensary Listing to Gold</li>
          <li>Upgrade your Delivery / Dispensary Listing to Premium</li>
          <li>List your menu for FREE</li>
          <li>Upgrade your menu for online ordering with <a href="www.420MedSoft.com" target="_blank">www.420MedSoft.com package</a></li>
          <li>420MedSoft package includes patient Website and Admin Site</li>
        </ul>
      </div>
    </div>
  </div>
			</div>
    <div class="leftbar-ads">
            <? 
					if(count($images)>0){
						if(count($images)==1)
							$end=0;
						else if(count($images)>1)
							$end = 1;
						else 
							$end = count($images);
						#echo $end; die;
						for($i=0 ; $i<=$end;$i++)
						{
							$web = $images[$i]['url'];
							if (strpos($web,'http://') === false)
							$web = 'http://'.$web;

							echo '<a onClick="window.open(\''.$web.'\')" target="_blank"><img src="'.ROOT_URL.'admin/uploads/'.$images[$i]['image'].'"  class="rightbaradsPadd"/></a>';
						}
						
					}
					if(count($images)<2)
						for($i=1 ; $i<=2- count($images);$i++)
							echo '<img data-toggle="modal" data-target="#myModal4" src="img/add-banner160x240.jpg"  class="rightbaradsPadd"/>';
					?>
            <img src="img/ads-enquiry.jpg"  id="addenquiry" data-toggle="modal" data-target="#myModal4" style="padding:7px 0px;" /> </div>
    <div class="rightbar-ads">
            <?if(count($images)>1){
						for($i=2 ; $i<count($images);$i++)
						{
							$web = $images[$i]['url'];
							if (strpos($web,'http://') === false)
							$web = 'http://'.$web;
							echo '<a onClick="window.open(\''.$web.'\')" target="_blank"><img src="'.ROOT_URL.'admin/uploads/'.$images[$i]['image'].'"  class="rightbaradsPadd"/></a>';
						}
					}
					
					if(count($images)<5)
					{
						for($i=0 ; $i< 4-count($images);$i++)
						echo '<img data-toggle="modal" data-target="#myModal4" src="img/add-banner160x240.jpg"  class="rightbaradsPadd"/>';
					}
					?>
          </div>
  </div>
      </div>
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog">
    <div class="modal-content">
            <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title LRhd" id="myModalLabel">Login</h4>
      </div>
            <div class="modal-body">
        <form class="xform" name="login_form" action="<?php echo ROOT_URL; ?>ajlogin.php" method="post" id="loginForm">
                <section>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label class="input"> <i class="icon-prepend fa fa-user"></i>
                        <input type="text" placeholder="Username" name="username" class="form-control" id="usernamer" required="" />
                      </label>
              </div>
                  </div>
          </section>
                <section>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label class="input"> <i class="icon-prepend fa fa-lock"></i>
                        <input type="password" placeholder="Password" class="form-control" name="password" id="passwordr"  required="" />
                      </label>
              </div>
                  </div>
          </section>
                <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <input type="checkbox">
                    Remember me <a href="#" onClick="fp()" data-toggle="modal" data-target="#forgot_pass">Forgot password</a> &nbsp &nbsp <a href="#forgotEmail"  data-toggle="modal" data-dismiss="modal">Forgot Username</a></div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 login-btn-mar">
                    <button class="btn btn-success" name="dosubmit" onClick="jslogin();return false;" type="submit" id="submit_login">Login</button>
                  </div>
          </div>
                <input type="hidden" value="1" name="doLogin">
              </form>
      </div>
          </div>
  </div>
      </div>
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
        <div class="modal-dialog">
    <div class="modal-content">
            <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title LRhd" id="myModalLabel">Register (New user)</h4>
      </div>
            <div class="modal-body">
        <form class="xform" name="login_form">
                <input type="hidden" value="" name="upid">
                <section>
            <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-mb20-resp">
                <label class="input"> <i class="icon-prepend fa-user"></i>
                        <input type="text" id="regfname" placeholder="First Name" class="form-control" value="" name="firstname" required aria-required="true"/>
                      </label>
              </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label class="input"> <i class="icon-prepend fa-user"></i>
                        <input type="text" id="reglname" placeholder="Last Name" class="form-control" value="" name="lastname" required aria-required="true"/>
                      </label>
              </div>
                  </div>
          </section>
                <section>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label class="input"> <i class="icon-prepend fa-envelope"></i>
                        <input type="email" id="regemail" placeholder="Email Id" class="form-control" value="" name="email" required aria-required="true"/>
                      </label>
              </div>
                  </div>
          </section>
                <section>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label class="input"> <i class="icon-prepend fa-user"></i>
                        <input type="text" id="reguser" placeholder="User Name" class="form-control" value="" name="username" required aria-required="true"/>
                      </label>
              </div>
                  </div>
          </section>
                <section>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label class="input"> <i class="icon-prepend fa fa-lock"></i>
                        <input type="password" id="regpassword" placeholder="Password" class="form-control" name="password" required aria-required="true"/>
                      </label>
              </div>
                  </div>
          </section>
                <section>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label class="textarea">
                        <textarea rows="5" name="address" id="regaddress" placeholder="Address" class="form-control" required aria-required="true"/>
                        </textarea>
                      </label>
              </div>
                  </div>
          </section>
                <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button name="dosubmit" class="btn btn-success" onClick="userlogin();return false;">Register</button>
                    <!--<a href="register.php" class="button button-secondary">Register</a>--> 
                  </div>
          </div>
              </form>
      </div>
          </div>
  </div>
      </div>
      
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
        <div class="modal-dialog">
    <div class="modal-content">
            <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title LRhd" id="myModalLabel">Update Your Profile</h4>
      </div>
            <div class="modal-body">
        <form method="post"  id="update_form" name="update_form" class="xform">
                <input type="hidden" name="idd" id="idd" value="<?php echo $_SESSION['userid']; ?>" >
                <section>
            <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-mb20-resp">
                <label class="input"> <i class="icon-prepend fa-user"></i>
                        <input  type="text" name="username" id="username" class="form-control" placeholder="Username" value="<?php echo $regfetch['username']; ?>" disabled>
                      </label>
              </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label class="input"> <i class="icon-prepend fa-user"></i>
                        <input  type="text" name="email" id="email" class="form-control" placeholder="Email" value="<?php echo $regfetch['email']; ?>" disabled>
                      </label>
              </div>
                  </div>
          </section>
                <section>
            <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-mb20-resp">
                <label class="input"> <i class="icon-prepend fa-user"></i>
                        <input  type="text" name="firstname" id="firstname" placeholder="First Name" value="<?php echo $regfetch['firstname']; ?>" class="required form-control">
                      </label>
              </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label class="input"> <i class="icon-prepend fa-user"></i>
                        <input  type="text" name="lastname" id="lastname" placeholder="Last Name" value="<?php echo $regfetch['lastname']; ?>" class="required form-control">
                      </label>
              </div>
                  </div>
          </section>
                <section>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label class="textarea">
                        <textarea name="addressm" id="addressm" rows="5" value="<?php echo $regfetch['address']; ?>" class="required form-control"><?php echo $regfetch['address']; ?></textarea>
                      </label>
              </div>
                  </div>
          </section>
                <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button type="button" class="button button-secondary Update" name="update" onclick="updatereg();">
                    Update
                    </button>
                    <!--<a href="register.php" class="button button-secondary">Register</a>--> 
                  </div>
          </div>
              </form>
      </div>
          </div>
  </div>
      </div>
<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
        <div class="modal-dialog">
    <div class="modal-content">
            <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title LRhd" id="myModalLabel">Add Enquiry</h4>
      </div>
            <div class="modal-body">
        <form method="post"  action="" id="add_form" name="" class="xform">
                <section>
            <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-mb20-resp">
                <label class="input"> <i class="icon-prepend fa-user"></i>
                        <input  type="text" name="adduser_name" id="adduser_name" placeholder="Full Name" value="" class="required form-control">
                      </label>
              </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-mb20-resp">
                <label class="input"> <i class="icon-prepend fa-envelope"></i>
                        <input  type="text" name="adduser_email" id="adduser_email" placeholder="Email" value="" class="required form-control">
                      </label>
              </div>
                  </div>
                  </section>
                  <section>
            <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-mb20-resp">
                <label class="input"> <i class="icon-prepend fa-phone"></i>
                        <input  type="text" name="adduser_phone" id="adduser_phone" placeholder="Phone" value="" class="required form-control">
                      </label>
              </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 input-mb20-resp">
                <label class="input"> <i class="icon-prepend fa-link"></i>
                        <input  type="text" name="adduser_url" id="adduser_url" placeholder="URL" value="" class="required form-control">
                      </label>
              </div>
                  </div>
                  </section>
                  <section>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type='file' name="adduser_image" id="adduser_image" class="form-control" placeholder="Image" accept="image/*">
              </div>
			 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  <p style="color:red;margin-top:5px"> Please upload 160x240 or higher resolution images 		</p>     </div>
		  </div>
          </section>
                <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button type="button" class="button button-secondary Update" name="update" id="addEnquiry_button">
                    Enquire
                    </button>
                    <!--<a href="register.php" class="button button-secondary">Register</a>--> 
                  </div>
          </div>
              </form>
      </div>
          </div>
  </div>
      </div>
<div class="modal fade" id="myModal5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
        <div class="modal-dialog">
    <div class="modal-content">
            <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title LRhd" id="myModalLabel">Rate Us</h4>
      </div>
            <div class="modal-body">
        <section>
                <form id='rating_form'>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="" style="display:block" name='rate_us_store'>
                        <fieldset class="rating">
                    <input type="radio" value="5" name="rate" id="star5">
                    <label for="star5" class="full"></label>
                    <input type="radio" value="4" name="rate" id="star4">
                    <label for="star4" class="full"></label>
                    <input type="radio" value="3" name="rate" id="star3">
                    <label for="star3" class="full"></label>
                    <input type="radio" value="2" name="rate" id="star2">
                    <label for="star2" class="full"></label>
                    <input type="radio" value="1" name="rate" id="star1">
                    <label for="star1" class="full"></label>
                  </fieldset>
                      </div>
              </div>
                  </div>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <textarea id="rating_comments" name="rating_comments" <? if(!isset($_SESSION['userid'])) echo "style='display:none'"?> placeholder="Comments" class="form-control"></textarea>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-8 col-xs-8"></div>
                    <div class="col-lg-4 col-md-4 col-sm-8 col-xs-8">
						<div align="center">
								<br><button class="btn btn-primary" onClick="saveRating('<?=$_SESSION['userid']?>');return false;">Submit Rating</button>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-8 col-xs-8"></div>
             </div>
          </form>
              </section>
      </div>
          </div>
  </div>
      </div>
<div class="modal fade" id="forgotEmail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
        <div class="modal-dialog">
    <div class="modal-content">
            <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title LRhd" id="myModalLabel">Forgot User Name</h4>
      </div>
            <div class="modal-body">
        <section>
                <form method="post">
            <div class="row">
                    <div class="col-lg-12">
						<input type="email" id="frgtemailid" class="form-control" name="email" placeholder="Please Enter Your Registered Email ID" aria-required="true" required="" style="background-color:#fff"/>
					</div> 
					<div class="col-lg-12"><br><br></div>
					<div class="col-lg-12">
						<div class="col-lg-5"></div>
						<div class="col-lg-4">
							<button  onclick="frgtuser();return false;" class="btn btn-success">Submit</button>
						</div>
					</div>
             </div>
          </form>
              </section>
      </div>
          </div>
  </div>
      </div>
<div class="modal fade" id="forgot_pass" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog">
    <div class="modal-content">
            <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title LRhd" id="myModalLabel">Forgot Password</h4>
      </div>
            <div class="modal-body">
        <form class="xform" name="login_form" action="#" method="post" id="loginForm">
                <section>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label class="input"> <i class="icon-prepend fa fa-user"></i>
                        <input type="text" placeholder="Email" class="form-control" name="Email_for"  id="Email_for" required="" aria-required="true"  />
                      </label>
              </div>
                  </div>
          </section>
                <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 login-btn-mar">
                    <button class="btn btn-success" name="doFPsubmit" onClick="forgotpass();return false;" type="submit" id="submit_login">Submit</button>
                  </div>
          </div>
              </form>
      </div>
          </div>
  </div>
      </div>
<div class="modal fade" id="myModal6" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
        <div class="modal-dialog">
    <div class="modal-content">
            <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title LRhd" id="myModalLabel">Reviews</h4>
      </div>
            <div class="modal-body">
				<?php if(!($_SESSION['userid'])) { ?> <h4 style="text-align:center; color:#000">To leave a review please <a href="#myModal2"  data-toggle="modal" data-dismiss="modal"  id="register_id"><u style="color:5dc2ed">Register</u></a> and <a href="#myModal1"  data-toggle="modal" data-dismiss="modal" id="login_id"><u style="color:5dc2ed">Login</u></a> <br></h4> <?php } ?>
        <section>
                <form id=''>
					<div class="row">
						<div class="col-md-9" id='review_form'> </div>
					</div>
				</form>
              </section>
      </div>
          </div>
  </div>
      </div>
<?php include ROOT."themes/footer.inc.php"; ?>
<script>
$("#adduser_image").change(function (e) {
    var file, img;
	if(this.files[0].size <= 102400)
	{
		alert("Select images with atleast 100KB in Size")
		$("#adduser_image").clearInputs()
		$("#adduser_image").css('border','1px solid red');
		return false;
	}
    
});
$.fn.clearFields = $.fn.clearInputs = function(includeHidden) {
    var re = /^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i; // 'hidden' is not in this list
    return this.each(function() {
        var t = this.type, tag = this.tagName.toLowerCase();
        if (re.test(t) || tag == 'textarea') {
            this.value = '';
        }
        else if (t == 'checkbox' || t == 'radio') {
            this.checked = false;
        }
        else if (tag == 'select') {
            this.selectedIndex = -1;
        }
        else if (t == "file") {
            if (/MSIE/.test(navigator.userAgent)) {
                $(this).replaceWith($(this).clone(true));
            } else {
                $(this).val('');
            }
        }
        else if (includeHidden) {
            // includeHidden can be the value true, or it can be a selector string
            // indicating a special test; for example:
            //  $('#myForm').clearForm('.special:hidden')
            // the above would clean hidden inputs that have the class of 'special'
            if ( (includeHidden === true && /hidden/.test(t)) ||
                 (typeof includeHidden == 'string' && $(this).is(includeHidden)) )
                this.value = '';
        }
    });
};
</script>
	  <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
</body>
</html>