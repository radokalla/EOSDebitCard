<?php
$heading='stores';
// include Config File

include_once './includes/config.inc.php';

include_once './includes/validate.php';

// Authenticate user login

auth();

validate_store_add();
?>
	  <?php include("header.php"); ?>
	  <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>
	<?$def_loc = explode(',',file_get_contents('http://www.ipaddressapi.com/l/5604d1ed63f42547daffd678ab3e6d5e51cdc1494a65?h='.$_SERVER['REMOTE_ADDR']));
		
		$latitude = substr($def_loc[8], 1, -1);
		$longitude = substr($def_loc[9], 1, -1);
		echo "<script>var lat = ".$latitude.";var lng = ".$longitude."</script>";?>
      <!-- Left side column. contains the logo and sidebar -->
      <?php include("sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1><?php echo $lang['ADMIN_ADD_STORE']; ?></h1>
          <ol class="breadcrumb">
            <li><a href="stores.php"><i class="fa fa-dashboard"></i> Store List</a></li>
            <li class="active">Add a Store</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
            	<div class="box">
                <div class="box-header">
                </div><!-- /.box-header -->
                <div class="box-body">
					<?php $notification = notification(); if(!empty($notification)) { ?>
						<div class="alert alert-success" style="text-align:center" id="hide"><?php echo $notification ?></div>
					<?php } ?>
			<?php if(isset($errors)): ?>

			<div class="alert alert-block alert-error fade in">

			<ul>

				<?php foreach($errors as $k=>$v): ?>

				<li><?php echo $v; ?></li>

				<?php endforeach; ?>

			</ul>

			</div>

			<?php endif; ?>
            
            <div id="map_canvas" class="newstore_map"></div>

			<div id="ajax_msg"></div>
            
            <div style="display:block; clear:both">

			<form method='post' action='' id='form_new_store' enctype="multipart/form-data" >

			<div class="row">
            	<div class="col-lg-6 col-sm-12">

						<div class="form-group">
                            <label><?php echo $lang['ADMIN_NAME']; ?>: <span class='required'>*</span></label>
							<!--OnKeyup="val(this.id);" -->
                            <input type='text' class="form-control required" name='name' maxlength="50" id='name' value='<?php echo $fields['name']['value']; ?>' required/>
                        </div>

						
						<div class="form-group">
                            <label><?php echo $lang['ADMIN_ADDRESS']; ?>: <span class='required'>*</span></label>
    
                            <input type='text' class="form-control required" name='address' id='address' value='<?php echo $fields['address']['value']; ?>' required/>
    
                            <span><?php echo $lang['ADMIN_LAT_LANG_AUTO']; ?></span>
                        </div>
						<div class="form-group">
                            <label>Zipcode <span class='required'>*</span>:</label>
    
                            <input type='text' class="form-control required" name='zipcode' id='zipcode' value='<?php echo $fields['zipcode']['value']; ?>' placeholder="zipcode" required/>
                        </div>

						<div class="form-group">
                            <label><?php echo $lang['ADMINISTRATOR_EMAIL']; ?>:*</label>
    
                            <input type='email' class="form-control required" name='email' id='email' value='<?php echo $fields['email']['value']; ?>'  required/>
                        </div>

						

						<div class="form-group">
                            <label><?php echo $lang['ADMIN_DESCRIPTION']; ?>:*</label>
    
                            <textarea name='description' class="form-control required" id='description' rows="5" cols="40"  onKeyDown="limitText(this.form.description,this.form.countdown,250);" 
									onKeyUp="limitText(this.form.description,this.form.countdown,250);" OnBlur="limitText(this.form.description,this.form.countdown,250);" required><?php echo $fields['description']['value']; ?></textarea>
									<font size="1">(Maximum characters: 250)</font>
                        </div>
                        <div class="form-group">
                            <label>Store Open Time:</label>
    
                            <input type='text' class="form-control" name='open_time' id='open_time' value='' placeholder="10:00 AM" required/>
                        </div>
                   <div class="form-group">
                            <label>Store Close Time:</label>
    
                            <input type='text' class="form-control" name='close_time' id='close_time' value='' placeholder="9:00 PM" required/>
                        </div>

						
                         <!-- added by mallesh-->
                        
                        
                         
                         <!-- added by mallesh-->

						<div class="form-group">
                            <label><?php echo $lang['ADMIN_STORE_IMAGE']; ?>:*</label>
    
                            <input type="file" name="file" id="file" required/>
    
                            <span style="color:red"><?php echo $lang['ADMIN_THUMB_AUTO']; ?></span>
                        </div>

					<?php if(!empty($images)): ?>

					<div class="input">

						<?php foreach($images as $k=>$v): ?>

						<div class="image">

							<img src="<?php echo $v; ?>" alt="Image" required/>

							<button type="submit" name="delete_image[<?php echo basename($v); ?>]" id="delete_image" class="btn btn-danger" value="<?php echo basename($v); ?>"><?php echo $lang['ADMIN_DELETE']; ?></button>

						</div>

						<?php endforeach; ?>

					</div>

					<?php endif; ?>


					

					

					
					</div>
                    <div class="col-lg-6 col-sm-12">
                    	<?php 

						$db = db_connect();

						mysql_query("SET NAMES utf8");

						$subs = $db->get_rows("SELECT  * FROM SubscriptionTypes ORDER BY Subscription  ASC");



						?>
                        
                        <?php 

						$db = db_connect();

						mysql_query("SET NAMES utf8");

						$stores = $db->get_rows("select '' as id, 'New Store' as name, '' as DatabaseName union all SELECT id,name,DatabaseName FROM stores  where ifnull(DatabaseName,'') <> '' and id = ParentId group by DatabaseName");



						?>


						<div class="form-group">
                            <label><?php echo $lang['SSF_CATEGORY']; ?>: <span class='required'>*</span></label>
    
                            <select name="cat_id" class="form-select form-control required" id="cat_id" required><option value=""><?php echo $lang['SSF_CATEGORY_NO_CAT_LISTBOX']; ?></option>
    
                             <?php if(!empty($subs)): ?>
    
                                <?php foreach($subs as $k=>$v): ?>
    
                                <option value="<?php echo $v['SubscriptionTypeId']; ?>"><?php echo $v['Subscription']; ?></option>
    
                                <?php endforeach; ?>
    
                                <?php endif; ?>
    
                             </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo $lang['ADMIN_TELEPHONE']; ?>:*</label>
                            <input type='text' class="form-control required" name='telephone' id='telephone' maxlength="10" value='<?php echo $fields['telephone']['value']; ?>' OnKeyup="num(this.id);" required/>
                        </div>
                        <div class="form-group">
                            <label><?php echo $lang['ADMIN_WEBSITE']; ?>:*</label>
    
                            <input type='url' class="form-control required" name='website' id='website' value='<?php echo $fields['website']['value']; ?>' required/>
                        </div>
<!--                        <div class="form-group">
                            <label><?php echo $lang['DatabaseName']; ?>: <span class='required'>*</span></label>
    
                            <select name="ParentId" class="form-select form-control required" id="store_id" required>
                            
    
                             <?php if(!empty($stores)): ?>
    
                                <?php foreach($stores as $k=>$v):  ?>
    
                                <option value="<?php echo $v['id']; ?>" <?php if( $store[ParentId]==$v['id']){ ?>selected<?php } ?>><?php echo $v['name']; ?></option>
    
                                <?php endforeach; ?>
    
                                <?php endif; ?>
    
                             </select>
                         </div>-->
                         <div class='form-group input first'>

						<label><?php echo $lang['ADMIN_LATITUDE']; ?>:</label>

						<input type='text' class="form-control required" name='latitude' id='latitude' readonly value='<?php echo $fields['latitude']['value']; ?>' required/>

					</div>
					<div class='form-group input second'>

						<label><?php echo $lang['ADMIN_LONGITUDE']; ?>:</label>

						<input type='text' class="form-control required" name='longitude' id='longitude' readonly value='<?php echo $fields['longitude']['value']; ?>' required/>

					</div>
                    </div>
					<div class="col-lg-12 col-sm-12">
						<div id="map" style="height:500px;width:100%;float:left"></div>
					</div>
                    <div class="col-lg-12 col-sm-12">
                        <div class='input buttons'>
                            <button type='submit' class="btn btn-primary" name='save' id='save'><?php echo $lang['ADMIN_SAVE']; ?></button>
                            <button type='button' class="btn btn-danger" onclick="document.location.href='stores.php'"><?php echo $lang['ADMIN_CANCEL']; ?></button>
                        </div>
                    </div>
				</div>
			</form>

			</div>
                </div><!-- /.box-body -->
              </div>
            </div>

          </div><!-- /.row -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
<script type="text/javascript" src="http://www.four20maps.com/js/jquery.validate.min.js"></script>
<script type="text/javascript">
function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}
var autocomplete, map, infoWindow, marker ;
function initMap() {
	map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: lat, lng: lng},
    zoom: 10,
	 styles: [{"featureType":"all","elementType":"labels.text.fill", "stylers":[{"saturation":36},{"color":"#a5a6a8"},{"lightness":2}]},
										{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#45484D"},{"lightness":0}]},
										{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},
										{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#45484D"},{"lightness":2}]},
										{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#45484D"},{"lightness":1},{"weight":1.2}]},
										{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#4F5660"},{"lightness":1}]},
										{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#4F5660"},{"lightness":2}]},
										{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#45484D"},{"lightness":6}]},
										{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#45484D"},{"lightness":6},{"weight":0.2}]},
										{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#45484D"},{"lightness":6}]},
										{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#45484D"},{"lightness":6}]},
										{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#45484D"},{"lightness":19}]},
										{"featureType":"water","elementType":"geometry","stylers":[{"color":"#282A2E"},{"lightness":14}]}]
  });
}
//infoWindow = new google.maps.InfoWindow();
$(document).ready(function() {
		$("#form_new_store").validate();
		autocomplete = new google.maps.places.Autocomplete(
					(document.getElementById('address')),
					{ types: ['geocode'] }
				);
		//autocomplete.bindTo('bounds', map);
		google.maps.event.addListener(autocomplete, 'place_changed', function() {
			fillInAddress();
			
		});
		initMap()
	});
	function fillInAddress()
	{
		var place = autocomplete.getPlace();
		var request = {
			placeId:  place.place_id
		};
		var service = new google.maps.places.PlacesService(document.createElement('div'));
			service.getDetails(request, function(place, status) {
				
					$("#latitude").val(place.geometry.location.lat()) // place.geometry.viewport.Pa.I //place.geometry.location.H
					$("#longitude").val(place.geometry.location.lng()) // place.geometry.viewport.La.j //place.geometry.location.L
			});
		
		marker = new google.maps.Marker({
		map: map,
		anchorPoint: new google.maps.Point(place.geometry.location.lat(), place.geometry.location.lng())
	  });
		marker.setVisible(false);
		if (place.geometry.viewport) {
			map.fitBounds(place.geometry.viewport);
		} else {
			map.setCenter(place.geometry.location);
			map.setZoom(10);  // Why 10? Because it looks good.
		}
		marker.setPosition(place.geometry.location);
		marker.setVisible(true);
		
	}
	
	
	$('#description').on('keypress', function (e) {
		var ingnore_key_codes = [34, 39];
		if ($.inArray(e.which, ingnore_key_codes) >= 0) 
        e.preventDefault();
	});
	
function val(val)
{
	var namesPattern = /^[a-zA-Z]*$/;
	var name = $('#'+val).val();
	if(!namesPattern.test(name))
	{
		$('#'+val).css('border-color','red')
		$('#'+val).val('');
	}
	else
	{
		$('#'+val).css('border-color','#eee')
	}
}

function num(id)
{
	var mnum = /^[0-9]{1,50}$/;
	var num = $('#'+id).val();
	if(!mnum.test(num))
	{
		$('#'+id).css('border-color','red')
		$('#'+id).val('');
	}
	else
	{
		$('#'+id).css('border-color','#eee')
	}
}
	</script>
    
            <?php include("footer.php"); ?>
<script>
	<?php if(!empty($notification)){ ?>
		$(document).ready(function(){
			setTimeout(function (){ $('#hide').hide(); }, 3000);
	});
	<?php } ?>
</script>