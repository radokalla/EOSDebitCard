<?php

// include config file

include_once './includes/config.inc.php';

//echo $_SESSION['userid']; exit;

// list of available distances


$distances = array(

	100=>'100 Miles',

    50=>'50 Miles',

	10=>'10 Miles',

);

error_reporting(0);

$location = file_get_contents('http://freegeoip.net/json/'.$_SERVER['REMOTE_ADDR']);


$mr = explode(',',$location);



$city=explode(':',$mr[5]);
$cities=str_replace('"',"",$city[1]);

$latitude=explode(':',$mr[8]);
$latituder = str_replace('"',"",$latitude[1]);

/*
$cities
$latituder
$longituder

*/


$longitude=explode(':',$mr[9]);
$longituder = str_replace('"',"",$longitude[1]);

$region=explode(':',$mr[4]);
 $regionr=str_replace('"',"",$region[1]);


if(isset($_POST['search']))
{
	
	
	$serch=$_POST['addressid'];
	$myquery="select latitude,longitude from stores where ".$_POST['addressid'];
	$query_exc=mysql_query($myquery);
	$ferchlat=mysql_fetch_assoc($query_exc);
	$latitude=$ferchlat['latitude'];
	$longitude=$ferchlat['longitude'];
	$category_filter='';
	$distance=100;
	
	$sqlg = "SELECT *, ( 3959 * ACOS( COS( RADIANS(".$latitude.") ) * COS( RADIANS( latitude ) ) * COS( RADIANS( longitude ) - RADIANS(".$longitude.") ) + SIN( RADIANS(".$latitude.") ) * SIN( RADIANS( latitude ) ) ) ) AS distance FROM stores WHERE status=1 AND approved=1 ".$category_filter." HAVING distance <= ".$distance." ORDER BY distance ASC LIMIT 0,60";

$result_query=mysql_query($sqlg);
while($resfetch=mysql_fetch_assoc($result_query))
{
	$stores[]=$resfetch;
}

}




$addimages="select aid,image,url from adds where status='Active' order by aid DESC LIMIT 8";
$qrryexc=mysql_query($addimages);
while($num_rows=mysql_fetch_assoc($qrryexc))
{
	$images[]=$num_rows;
}
/*echo "<pre>";
print_r($images);
*/

/*$addimagessec="select aid,image,url from adds order by aid DESC LIMIT 3, 6";
$qrrysec=mysql_query($addimagessec);
while($num_rowssec=mysql_fetch_assoc($qrrysec))
{
	$imagessec[]=$num_rowssec;
}*/


if($_GET['msg'])
{
	
	 $msg=$_GET['msg']; 
}



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
}

$errors = array();



if($_POST) {

	if(isset($_POST['address']) && empty($_POST['address'])) {

		$errors[] = 'Please enter your address';

	} else {


/*$addr = $_POST['address'];

$resul=mysql_query("CALL mp_proc_SearchStorebyKeyword('{$addr}')") or die(mysql_error());
while($rj=mysql_fetch_assoc($resul))
{
	$stores=$rj;
}*/


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

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>
  <head>
  <title><?php echo $lang['STORE_FINDER']; ?>- Google Maps Store Locator with Google Street View, Google Direction, Admin Area, Category Icons, Store Thumbnail, Custom Markers, Google Maps API v3</title>
  <meta name="keywords" content="street view, google direction, ajax, bootstrap, embed, geo ip, geolocation, gmap, google maps, jquery, json, map, responsive, store admin, store finder, store locator" />
  <meta name="description" content="Super Store Finder &amp;#8211; Easy to use Google Maps API Store Finder Super Store Finder is a multi-language fully featured PHP Application integrated with Google Maps API v3 that allows customers to..." />
  <link rel="shortcut icon" href="img/favicon.ico" />
  <!--    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.2/jquery-ui.js"></script>-->

  <?php /*?><script src="js/jquery.min.js"></script><?php */?>
  <?php /*?><script src="js/jquery.validate.js"></script><?php */?>
  <?php include ROOT."settings.php"; ?>
  <?php include ROOT."themes/meta_mobile.php"; ?>
  <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
  <script type="text/javascript" src="<?php echo ROOT_URL; ?>js/common.js"></script>
  <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
  <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="http://malsup.github.com/jquery.form.js"></script>-->

  <script>
	function changeLang(v){

	document.location.href="?langset="+v;

	}



	
 var kmethod;
  $(function() {
  function split( val ) {
  return val.split( /,\s*/ );
  }
  function extractLast( term ) {
  return split( term ).pop();
  }
  $( ".multi" )
  // don't navigate away from the field on tab when selecting an item
  .bind( "keydown", function( event ) {
   kmethod=$(this).attr('data-url');
   if ( event.keyCode === $.ui.keyCode.TAB &&
    $( this ).data( "ui-autocomplete" ).menu.active ) {
     event.preventDefault();
   }
  })
  .autocomplete({
   source: function( request, response ) {
    $.getJSON(  '<?php echo ROOT_URL; ?>autocomplete.php', {
    term: extractLast( request.term )
   }, response );
  },
  search: function() {
  // custom minLength
  var term = extractLast( this.value );
  if ( term.length < 2 ) {
  return false;
  }
  },
  focus: function() {
  // prevent value inserted on focus
  return false;
  },
  select: function( event, ui ) {
  id=this.id;
  idv=$('#'+id+'1').val();
  var terms = split( this.value );
  var termid=split(idv);
  // remove the current input
  terms.pop();
  termid.pop();
  // add the selected item
  terms.push( ui.item.value );
  termid.push(ui.item.id);
  // add placeholder to get the comma-and-space at the end
  terms.push( "" );
  termid.push("");
  this.value = terms.join( ", " );
  id=this.id;
  $('#'+id+'1').val(termid.join(","));
  return false;
  }
  });
 });
 
 function filldata(data)
 {
  for(i in data){
   var ele=document.getElementById('af'+i);
   if(ele!=null)
    ele.value=data[i];
  }
 }
 
 function split( val ) {
  return val.split( /,\s*/ );
 }
 
 function extractLast( term ) {
  return split( term ).pop();
 }
 
 function autoComplete(element,method,value,funct)
 {
  value=(value)|| ""; 
  value=(value.length!=0)?"/"+value:"";
  var valu=element.value;
  var sid=element.id;
  var tid=sid+'1';
  var url='<?php echo ROOT_URL; ?>autocomplete.php';
  if(valu.length>0){
   $("#"+sid).autocomplete({
    source: url+value,
     minLength: 1,
    select: function(event, ui) {
     var val=ui.item.value;
	
     var id=ui.item.id;
	 
	 var latitude=ui.item.latitude;
	 var longitude=ui.item.longitude;
	 
	 
     $("#"+sid).html(val);
     $("#"+tid).val(id);
	 //var obj = JSON.parse(val);
	$("#addressid").val(id);
     //alert(json.m);
	var lst = $("#latitude").val(latitude);
	alert(lst);
	var lon = $("#longitude").val(longitude);
	alert(lon);
     if(funct!=""){
      var fn = window[funct];
      if (typeof fn === "function") fn(ui.item);
     }
     },
      html: true, 
     open: function(event, ui) {
     $(".ui-autocomplete").css("z-index", 1000);
    },
   });
  }
 }
 var kmethod;

</script>
  <script type="text/javascript">
            // When the window has finished loading create our google map below
            google.maps.event.addDomListener(window, 'load', init);
        
            function init() {
                // Basic options for a simple Google Map
                // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
                var mapOptions = {
                    // How zoomed in you want the map to start at (always required)
                    zoom: 11,

                    // The latitude and longitude to center the map (always required)
                    center: new google.maps.LatLng(40.6700, -73.9400), // New York

                    // How you would like to style the map. 
                    // This is where you would paste any style found on Snazzy Maps.
                    styles: [{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":21}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":17}]}]
                };

                // Get the HTML DOM element that will contain your map 
                // We are using a div with id="map" seen below in the <body>
                var mapElement = document.getElementById('map');

                // Create the Google Map using our element and options defined above
                var map = new google.maps.Map(mapElement, mapOptions);

                // Let's also add a marker while we're at it
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(40.6700, -73.9400),
                    map: map,
                    title: 'Snazzy!'
                });
            }
        </script>
  <style>
.search-finderwps .form-item {
	float: left;
	width: auto;
}
.search-finderwps .form-item input.form-text {
	margin-top: 3px;
	margin-left: 10px;
}
.login-resiwps {
	margin: 5px 0px 5px 10px;
	padding: 0px;
	list-style: none;
	float: left;
	width: auto;
}
.login-resiwps li {
	float: left;
	width: auto;
	margin-right: 10px;
}
.login-resiwps li a {
	color: #ffffff;
	border-right: 1px solid #fff;
	padding-right: 10px;
}
.login-resiwps li:last-child a {
	border-right: 0px;
}
.search-finderwps #edit-submit {
	float: left;
	margin-top: 3px;
	padding-bottom: 2px;
	padding-top: 5px;
}
.search-finderwps #edit-products-wrapper {
	float: left;
	width: auto !important;
}
</style>
  <link rel="stylesheet" type="text/css" href="<?=ROOT_URL;?>css/jquery-ui-1.10.3.custom.min.css"/>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
  <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="http://code.jquery.com/resources/demos/style.css">
  <style>
.ui-autocomplete-category {
	font-weight: bold;
	padding: .2em .4em;
	margin: .8em 0 .2em;
	line-height: 1.5;
}
</style>
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
		  li.attr( "aria-val", item.id );
        }
      });
    }
  });
  </script>
  <script>
  $(function() {
    $( "#addressr" ).catcomplete({
      delay: 500,
	  minLength: 3,
	  /*source: 'autocomplete.php?keyword='+$( "#addressr" ).val()*/
      source: function( request, response ) {
				$.ajax({
				  url: 'autocomplete.php',
				  async:true,
				  dataType: "json",
				  data: { keyword: request.term },
				  success: function( data ) {
					  //console.log(data);
					  response(data);
				  }
				});
      		},
	 select: function (event, ui) {
		 	//console.log(ui.item);
			var coord = ui.item.longlat.split('|');
            $("#latitude").val(coord[0]); 
			$("#longitude").val(coord[1]);
        }		
    });
  });
  </script>
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
        <form method="post" action="./index.php" accept-charset="UTF-8" method="post" id="clinic-finder-form" class="clear-block" class="clear-block">
        <div class="search-finderwps">
            <div class="form-item" id="edit-gmap-address-wrapper">
            <?php /*?><label for="edit-gmap-address"><?php echo $lang['PLEASE_ENTER_YOUR_LOCATION']; ?>: </label><?php */?>
            <input type="text" maxlength="128" name="address" id="addressr" size="60" value="" class="form-text" <?php /*?> onKeyUp="autoComplete(this,'getCustomers','','filldata');"<?php */?> />
            <input type="hidden" id="addressval" value="" />
          </div>
            <?php 
				// support unicode
				mysql_query("SET NAMES utf8");
				$cats = $db->get_rows("SELECT categories.* FROM categories WHERE categories.id!='' ORDER BY categories.cat_name ASC");

				?>
            <div class="form-item" id="edit-products-wrapper">
            <?php /*?><label for="edit-products"><?php echo $lang['SSF_CHOOSE_A_CATEGORY']; ?>: </label><?php */?>
            <select name="products" class="form-select" id="edit-products" >
                <option value=""><?php echo $lang['SSF_ALL_CATEGORY']; ?></option>
                <?php if(!empty($cats)): ?>
                <?php foreach($cats as $k=>$v): ?>
                <option value="<?php echo $v['id']; ?>"><?php echo $v['cat_name']; ?></option>
                <?php endforeach; ?>
                <?php endif; ?>
              </select>
          </div>
            <input type="hidden" name="jslatitude" id="jslatitude" value="<?php echo $latituder; ?>" />
            <input type="hidden" name="jlongituder" id="jlongituder" value="<?php echo $longituder; ?>" />
            <input type="hidden" name="jregionr" id="jregionr" value="<?php echo $cities; ?>" />
            <input type="hidden" name="latitude" id="latitude" value="" />
            <input type="hidden" name="longitude" id="longitude" value=""/>
            <input type="hidden" id="distance" name="distance" value="200">
            <input type="submit" name="op" id="edit-submit" value="<?php echo $lang['FIND_STORE']; ?>" class="btn btn-large btn-primary" />
            <input type="hidden" name="form_build_id" id="form-0168068fce35cf80f346d6c1dbd7344e" value="form-0168068fce35cf80f346d6c1dbd7344e"  />
            <input type="hidden" name="form_id" id="edit-clinic-finder-form" value="clinic_finder_form"  />
            <ul class="login-resiwps">
            <?php if(isset($_SESSION['userid'])){
				?>
            <li><a href="register.php?reg=<?php echo $_SESSION['userid']; ?>">My account</a></li>
            <li><a href="logout.php">Logout</a></li>
            <?php }
				else {
				?>
            <li><a href="#"  data-toggle="modal" data-target="#myModal1" id="login_id">Login</a></li>
            <li><a href="#"  data-toggle="modal" data-target="#myModal2" id="register_id">Register</a></li>
            <?php } ?>
          </ul>
          </div>
        </form>
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
    <div class="feature-wps home-content">
    <div class="leftbar-ads">
    	<a href="<?php  echo ((isset($images[0]['url']))?$images[0]['url']:'');?>"><img style="padding-bottom:1px; height:297px;" src="<?php echo ROOT_URL ?>admin/uploads/<?php echo ((isset($images[0]['image']))?$images[0]['image']:'image.jpg'); ?>"/></a>
        <a id="adds" data-target="#myModaladds" data-toggle="modal" href="#"><img style="padding-bottom:1px" src="img/ads-enquiry.jpg"  id="addenquiry"/></a>
        
         <a href="<?php  echo ((isset($images[7]['url']))?$images[7]['url']:''); ?>"><img src="<?php echo ROOT_URL ?>admin/uploads/<?php echo ((isset($images[7]['image']))?$images[7]['image']:'Koala.jpg');?>"/></a>
	</div>
    <div class="container">
        <div class="right-mapwps row">
        <div class="col-md-12 col-sm-12 col-xs-12 padALL">
            <div id="map-container">
            <div id="clinic-finder" class="clear-block">
                <div class="links"></div>
                <h5 style="color:#F40F13; margin:0;"><?php echo $msg; ?></h5>
                <form method="post" action="./index.php" accept-charset="UTF-8" method="post" id="clinic-finder-form" class="clear-block" class="clear-block">
                
                <div id="map_canvas"><?php echo $lang['JAVASCRIPT_ENABLED']; ?></div>
                <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marginauto text-center">
                    <div class="col-md-12 col-sm-12 col-xs-12 margin-top-40">
                    <div class="info-logoimg margin-top-20"><img src="img/footer-logo.jpg"/ class="img-responsive"></div>
                  </div>
                    <div class="socia-media col-lg-12 col-md-12 col-sm-12 col-xs-12 padLT margin-bottom-40"> <a href="#" class="scl-icn facebook"></a> <a href="#" class="scl-icn twitter"></a> <a href="#" class="scl-icn instram"></a> <a href="#" class="scl-icn linkdin"></a> </div>
                  </div>
              </div>
              </div>
          </div>
          </div>
      </div>
        
      </div>
      <div class="rightbar-ads">
      	<a href="<?php echo ((isset($images[1]['url']))?$images[1]['url']:'');?>"><img src="<?php echo ROOT_URL ?>admin/uploads/<?php echo ((isset($images[1]['image']))?$images[1]['image']:'Koala.jpg');?>"  class="rightbaradsPadd"/></a>
        <a href="<?php echo ((isset($images[2]['url']))?$images[2]['url']:'');?>"><img src="<?php echo ROOT_URL ?>admin/uploads/<?php echo ((isset($images[2]['image']))?$images[2]['image']:'Koala.jpg');?>" class="rightbaradsPadd" /></a>
        <a href="<?php echo ((isset($images[6]['url']))?$images[6]['url']:'');?>"><img src="<?php echo ROOT_URL ?>admin/uploads/<?php echo ((isset($images[6]['image']))?$images[6]['image']:'Koala.jpg');?>"  class="rightbaradsPadd"/></a>
         </div>
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

				$('#addressr').val(geoip_city()+", "+geoip_country_name());
				console.log(val(geoip_city()+", "+geoip_country_name()));

		} else {
			
			default_location = jQuery('#jregionr').val();

			jQuery('#addressr') = default_location;
			cosole.log(jQuery('#addressr'));
		}
		
		

	</script>

  </div>
  </div>
<!-- Container 12 END-->
<style type="text/css">
    .label {width:100px;text-align:right;float:left;padding-right:10px;font-weight:bold;}
    #register-form label.error, .output {color:#FB3A3A;font-weight:bold;}
  </style>

<!-- Modal -->
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
                <div class="col-md-12">
                <label class="input"> <i class="icon-prepend fa fa-user"></i>
                    <input type="text" placeholder="Username" name="username"  id="usernamer" class="required"  />
                  </label>
              </div>
              </div>
          </section>
            <section>
            <div class="row">
                <div class="col-md-12">
                <label class="input"> <i class="icon-prepend fa fa-lock"></i>
                    <input type="password" placeholder="Password" name="password" id="passwordr"  class="required"/>
                  </label>
              </div>
              </div>
          </section>
            <div class="row">
            <div class="col-md-12">
                <input type="checkbox">
                Remember me <a href="#">Forgot password</a></div>
            <div class="col-md-12 login-btn-mar">
                <button class="btn btn-success" name="dosubmit" onclick="jslogin();" type="submit" id="submit_login">Login</button>
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
        <form class="xform" name="login_form"  action="<?php echo ROOT_URL; ?>reg.php" method="post" id="registerform">
            <input type="hidden" value="" name="upid">
            <section>
            <div class="row">
                <div class="col-md-6">
                <label class="input"> <i class="icon-prepend fa-user"></i>
                    <input type="text" placeholder="First Name" value="" name="firstname" class="required"/>
                    <!--<label for="firstname" generated="true" class="error" style="display: inline-block; color:red;">Please enter your firstname</label>--> 
                  </label>
              </div>
                <div class="col-md-6">
                <label class="input"> <i class="icon-prepend fa-user"></i>
                    <input type="text" placeholder="Last Name" value="" name="lastname" class="required"/>
                    <!--<label for="lastname" generated="true" class="error" style="display: inline-block; color:red;">Please enter your lastname</label>--> 
                  </label>
              </div>
              </div>
          </section>
            <section>
            <div class="row">
                <div class="col-md-12">
                <label class="input"> <i class="icon-prepend fa-envelope"></i>
                    <input type="text" placeholder="Email Id" value="" name="required email" class="required email"/>
                    <!--<label for="email" generated="true" class="error" style="display: inline-block; color:red;">Please enter a valid email address</label>--> 
                  </label>
              </div>
              </div>
          </section>
            <section>
            <div class="row">
                <div class="col-md-12">
                <label class="input"> <i class="icon-prepend fa-user"></i>
                    <input type="text" placeholder="User Name" value="" name="username" class="required"/>
                    <!--<label for="username" generated="true" class="error" style="display: inline-block; color:red;">Please enter a valid username</label>--> 
                  </label>
              </div>
              </div>
          </section>
            <section>
            <div class="row">
                <div class="col-md-12">
                <label class="input"> <i class="icon-prepend fa fa-lock"></i>
                    <input type="password" placeholder="Password" name="password" class="required"/>
                    <!--<label for="password" generated="true" class="error" style="display: inline-block; color:red;">Please provide a password</label>--> 
                  </label>
              </div>
              </div>
          </section>
            <section>
            <div class="row">
                <div class="col-md-12">
                <label class="textarea">
                    <textarea rows="5" name="address" placeholder="address" class="required"/>
                    </textarea>
                    <!--<label for="address" generated="true" class="error" style="display: inline-block; color:red;">This field is required.</label>--> 
                  </label>
              </div>
              </div>
          </section>
            <div class="row">
            <div class="col-md-12">
                <button name="dosubmit" class="btn btn-success" type="submit" id="submit">Register</button>
                <!--<a href="register.php" class="button button-secondary">Register</a>--> 
              </div>
          </div>
          </form>
      </div>
      </div>
  </div>
  </div>

<script>
$(document).ready(function(){

	var mr = $('#address').val();
	var latrr = $('#jslatitude').val();
	
	var longt = $('#jlongituder').val();

	var longt = $('#jregionr').val();


	
	$('#file').on("click",function() {
		$(this).next("label").html('');
	});
$('#file').on("change",function() {
var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
	
$('#error').text("Only '.jpeg','.jpg', '.png', '.gif', '.bmp' formats are allowed.");
}
else
{
	$('#error').text("");
}
});
});
</script>
<div class="modal fade" id="myModaladds" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title LRhd" id="myModalLabel">Enquire with us</h4>
      </div>
        <div class="modal-body">
        <form class="xform" name="adds" action="<?php echo ROOT_URL; ?>upload.php" method="post" id="addsrr" enctype='multipart/form-data'>
            <input type="hidden" value="" name="upid">
            <section>
            <div class="row">
                <div class="col-md-12">
                <label class="input"> <i class="icon-prepend fa-user"></i>
                    <input type="text" placeholder="Name" value="" name="name" id="name" class="required"/>
                    <!--<label for="name" generated="true" class="error" style="color:red;">Please enter your firstname</label>--> 
                    <!--<label for="firstname" generated="true" class="error" style="display: inline-block; color:red;">Please enter your firstname</label>--> 
                  </label>
              </div>
              </div>
          </section>
            <section>
            <div class="row">
                <div class="col-md-12">
                <label class="input"> <i class="icon-prepend fa-envelope"></i>
                    <input type="text" placeholder="Email Id" value="" name="emailr" id="emailr" class="required email"/>
                    <!--<label for="emailr" generated="true" class="error" style="color:red;">Please enter a valid email address</label>--> 
                    <!-- <label for="email" generated="true" class="error" style="display: inline-block; color:red;">Please enter a valid email address</label>--> 
                  </label>
              </div>
              </div>
          </section>
            <section>
            <div class="row">
                <div class="col-md-12">
                <label class="input"> <i class="icon-prepend fa-envelope"></i>
                    <input type="text" placeholder="phone" value="" name="phone" id="phone" maxlength="10" minlength="10" class="required num"/>
                    <!--<label for="phone" generated="true" class="error" style="display: inline-block; color:red;">Please enter a valid Mobile number</label>--> 
                    <!-- <label for="email" generated="true" class="error" style="display: inline-block; color:red;">Please enter a valid phone address</label>--> 
                  </label>
              </div>
              </div>
          </section>
            <section>
            <div class="row">
                <div class="col-md-12">
                <label class="input"> <i class="icon-prepend fa-envelope"></i>
                    <input type="text" placeholder="Url" value="" name="url" id="url" class="required"/>
                    <!--<label for="url" generated="true" class="error" style="display: inline-block; color:red;">Please enter a valid url</label>--> 
                    
                    <!--<label for="email" generated="true" class="error" style="display: inline-block; color:red;">Please enter a valid url address</label>--> 
                  </label>
              </div>
              </div>
          </section>
            </section>
            <section>
            <div class="row">
                <input type="file"  name="file" id="file"/>
                <!--<label class="error" for="file" generated="true" style="display: inline-block; color:red;">This field is required.</label>-->
                <div id="error" style="color:#F00"></div>
                <!-- <label for="email" generated="true" class="error" style="display: inline-block; color:red;">Please enter a valid file address</label>--> 
                
              </div>
          </section>
            <div class="row">
            <div class="col-md-12">
                <input type="submit" name="addenquirysubmit" value="Submit" class="btn btn-success"/>
                <!--<button name="dosubmit" class="btn btn-success" type="submit" id="submit">Submit</button>--> 
                <!--<a href="register.php" class="button button-secondary">Register</a>--> 
              </div>
          </div>
          </form>
      </div>
      </div>
  </div>
  </div>
<div id="map"></div>
<?php include ROOT."themes/footer.inc.php"; ?>
</body>
</html>
