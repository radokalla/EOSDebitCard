<?php
ini_set('display_errors', false);
$heading = 'stores';
// include Config File

include_once './includes/config.inc.php';

include_once './includes/validate.php';

// Authenticate user login

auth();

$upload_dir = '';

validate_edit_store();

//echo "<pre>";
//print_r($fields);die;

$images = array();

	if(is_dir($upload_dir)) {

		$images = get_files($upload_dir);

		foreach($images as $k=>$v) {

			$images[$k] = ROOT_URL.'imgs/stores/'.$store['id'].'/'.$v;

		}

}
?>
  <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>
	<?$def_loc = explode(',',file_get_contents('http://www.ipaddressapi.com/l/5604d1ed63f42547daffd678ab3e6d5e51cdc1494a65?h='.$_SERVER['REMOTE_ADDR']));
		
		$latitude = substr($def_loc[8], 1, -1);
		$longitude = substr($def_loc[9], 1, -1);
		echo "<script>var lat = ".$latitude.";var lng = ".$longitude."</script>";?>
	  <?php include("header.php"); ?>
      <!-- Left side column. contains the logo and sidebar -->
      <?php include("sidebar.php"); ?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1><?php echo $lang['ADMIN_EDIT_STORE']; ?></h1>
          <ol class="breadcrumb">
            <li><a href="stores.php"><i class="fa fa-dashboard"></i> Store List</a></li>
            <li class="active">Edit Store</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
            	<div class="box">
                <div class="box-body">

			<?php if(isset($errors) && !empty($errors)): ?>

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

			<form method='post' action='./stores_edit.php?id=<?php echo $store['id']; ?>' id='form_new_store' enctype="multipart/form-data">

			<div class="row">
            	<div class="col-lg-6 col-sm-12">
						
						
						<div class="form-group">
                            <label><?php echo $lang['ADMIN_NAME']; ?>: <span class='required'>*</span></label>
    
                            <input type='text' class="form-control" name='name' maxlength="50" id='name' value="<?php echo $fields['name']['value']; ?>" required/>
                        </div>

						 

						<div class="form-group">
                            <label><?php echo $lang['ADMIN_ADDRESS']; ?>: <span class='required'>*</span></label>
    
                           <input type='text' class="form-control" name='address' id='address' value="<?php echo $fields['address']['value']; ?>" required/>

						<span><?php echo $lang['ADMIN_LAT_LANG_AUTO']; ?></span>
                        </div>
<div class="form-group">
                            <label>Zipcode <span class='required'>*</span>:</label>
    
                            <input type='text' class="form-control" name='zipcode' id='zipcode' value='<?php echo $store['zipcode']; ?>' placeholder="zipcode" required/>
                        </div>

						<div class="form-group">
                            <label><?php echo $lang['ADMINISTRATOR_EMAIL']; ?>:</label>
    
                            <input type='text' class="form-control" name='email' id='email' value='<?php echo $fields['email']['value']; ?>' required/>
                        </div>


					<div class='form-group'>

						<label><?php echo html_entity_decode($lang['ADMIN_DESCRIPTION'],ENT_QUOTES) ; ?>:</label>

						<textarea name='description' class="form-control" id='description' rows="5" cols="40" onKeyDown="limitText(this.form.description,this.form.countdown,250);" 
									onKeyUp="limitText(this.form.description,this.form.countdown,250);" OnBlur="limitText(this.form.description,this.form.countdown,250);" required><?php echo htmlentities($fields['description']['value']); ?></textarea>
						<font size="1">(Maximum characters: 250)</font>
					</div>
                   <div class="form-group">
                            <label>Store Open Time:</label>
    
                            <input type='text' class="form-control" name='open_time' id='open_time' value='<?php echo $store['open_time']; ?>' placeholder="10:00 AM" required/>
                        </div>
                   <div class="form-group">
                            <label>Store Close Time:</label>
    
                            <input type='text' class="form-control" name='close_time' id='close_time' value='<?php echo $store['close_time']; ?>' placeholder="9:00 PM" required/>
                        </div>
                        
                        
                    <div class='form-group'>
                    	<label><?php echo $lang['ADMIN_STORE_IMAGE']; ?>:</label>

						<input type="file" name="file" id="file" <?php //if(!empty($images)) {echo 'disabled="disabled" required';} ?> />

						<span><?php echo $lang['ADMIN_THUMB_AUTO']; ?></span>
                        
                        
                        	<?php $img = mysql_query("select image from stores where id =".$_REQUEST['id']);
								 $image = mysql_fetch_array($img);?>

					<div class="input">


						<div class="image">

							<img src="<?php echo $image['image']; ?>" alt="Image" />

						</div>

					</div>
                    </div>
                    
                    

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

						$stores = $db->get_rows("select id,name,DatabaseName FROM stores  where ifnull(DatabaseName,'') <> '' and id = ParentId group by DatabaseName");
						/*$id = $_GET['id'];
						$db->get_rows("select DatabaseName from stores where id='$id' ")
						echo "<pre>"; print_r($stores);die;*/
						
						?>

						
						<div class="form-group">
                            <label><?php echo $lang['SSF_CATEGORY']; ?>: <span class='required'>*</span></label>
    
                            <select name="cat_id" class="form-control" id="cat_id" required><option value=""><?php echo $lang['SSF_CATEGORY_NO_CAT_LISTBOX']; ?></option>
						 
 
							<?php if(!empty($subs)): ?>
    
                                <?php foreach($subs as $k=>$v): ?>
    
                                <option value="<?php echo $v['SubscriptionTypeId']; ?>" <?php if($store['cat_id']==$v['SubscriptionTypeId']){ ?>selected<?php } ?>><?php echo $v['Subscription']; ?></option>
    
                                <?php endforeach; ?>
    
                                <?php endif; ?>
    

						 </select>

                        </div>
                        <div class="form-group">
                            <label><?php echo $lang['ADMIN_TELEPHONE']; ?>:</label>
    
                            <input type='text' class="form-control" name='telephone' OnKeyUp="num(this.id);" id='telephone' maxlength="10" value="<?php if(empty($store['telephone']))echo $store['mobile']; else echo $store['telephone'];?>" required/>
                        </div>
                        <div class='form-group'>

						<label><?php echo $lang['ADMIN_WEBSITE']; ?>:</label>

						<input type='text' class="form-control" name='website' id='website' value='<?php echo $fields['website']['value']; ?>' required/>

					</div>
					<?php if($store['createdby']=='0'){ ?>
                        <div class="form-group">
                            <label><?php echo $lang['DatabaseName']; ?>: <span class='required'>*</span></label>
    
                            <select name="DatabaseName" id="store_id" class="form-control"> 
                        	<option value="">No Database</option>
							
						 <?php if(!empty($stores)): ?> 
							<?php foreach($stores as $k=>$v):  ?>
							<option value="<?php echo $v['DatabaseName']; ?>" <?php if( $store['DatabaseName']==$v['DatabaseName']){ echo "selected"; } ?> data-rel="<?=$v['id']; ?>"><?php echo $v['DatabaseName']; ?></option>
							<?php endforeach; ?>

							<?php endif; ?>

						 </select>
                         </div>
                         <input type="hidden" value = ' ' name="ParentId" id="ParentId" />
					<?php } else {?>
						<input type="hidden" value = ' ' name="ParentId" id="ParentId"/>
					<?php } ?>
                         <div class="form-group input first">

						<label><?php echo $lang['ADMIN_LATITUDE']; ?>:</label>

						<input type='text' name='latitude' id='latitude' class="form-control" value='<?php echo $fields['latitude']['value']; ?>' readonly required/>

					</div>
					<div class='form-group input second'>

						<label><?php echo $lang['ADMIN_LONGITUDE']; ?>:</label>

						<input type='text' name='longitude' id='longitude' class="form-control" value='<?php echo $fields['longitude']['value']; ?>' readonly required/>

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
            <?php include("footer.php"); ?>
<script type="text/javascript" src="http://www.four20maps.com/js/jquery.validate.min.js"></script>
<script>
	$("#store_id").change(function(){ 
		 		
		$("#ParentId").val($('option:selected', this).data("rel"));
	});
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

function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}

function func(id)
{
	var namesPattern = /^[a-zA-Z ]*$/;
	var name = $('#'+id).val();
	if(!namesPattern.test(name))
	{
		$('#'+id).css('border-color','red')
		$('#'+id).val('');
	}
	else
	{
		$('#'+id).css('border-color','#eee')
	}
}

var autocomplete, map, infoWindow, marker ;
function initMap() {
	<?
	if($fields['latitude']['value'] != "")
		echo "lat =".$fields['latitude']['value'].";";
	if($fields['longitude']['value'] != "")
		echo "lng =".$fields['longitude']['value'].";";
	?>
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
	marker = new google.maps.Marker({
			map: map,
			position: {lat: lat, lng: lng}
	});
}
infoWindow = new google.maps.InfoWindow();
	 
 
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
				$("#latitude").val(place.geometry.location.lat())
				$("#longitude").val(place.geometry.location.lng())
				
			});
		
		/*marker = new google.maps.Marker({
			map: map,
			anchorPoint: new google.maps.Point(place.geometry.location)
		  });
		*/
		marker.setPosition(place.geometry.location);
		
	}
	
	


$(document).ready(function() {
		$("#form_new_store").validate();
});
</script>