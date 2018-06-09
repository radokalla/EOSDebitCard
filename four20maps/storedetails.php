<?
session_start();
ob_start();
$header = 'Stores';
$username = $_SESSION["regSuccess"];
if(empty($username)){ ?>
	<script>
		window.location.href = 'addstore.php';
	</script>
<?php }
include_once 'header.php';

$db = db_connect();
mysql_query("SET NAMES utf8");
if($_POST)
{
	include_once './includes/validate.php';
	$sql = mysql_query("select StoreUserSubscriptionId from stores where StoreUserSubscriptionId =".$_POST['StoreUserSubscriptionId']);
	$subsid = mysql_fetch_array($sql);
	$query = mysql_query("SELECT StoreUserSubscriptionId FROM StoreUserSubscription WHERE StoreUserSubscriptionId =".$_POST['StoreUserSubscriptionId']);
	$substypeid = mysql_fetch_array($query);
	if((empty($subsid)) && (!empty($substypeid)))
	{
		validate_request_add_store();
		if($_SESSION['status'] == '1')
			{  
				unset($_SESSION['status']);
				if($_REQUEST["id"]!='')
					$_SESSION['StoreSuc'] = 'Store Updated Successfully.';
				else
					$_SESSION['StoreSuc'] = 'Store Added Successfully.';?>
				<script>window.location.href = 'stores.php'</script>		
		<?php 	exit;} 
	}
	else
	{
		if(!empty($_REQUEST["id"]))
		{
			validate_request_add_store();
			if($_SESSION['status'] == '1')
				{  
					unset($_SESSION['status']);
					if($_REQUEST["id"]!='')
						$_SESSION['StoreSuc'] = 'Store Updated Successfully.';
					else
						$_SESSION['StoreSuc'] = 'Store Added Successfully.';?>
					<script>window.location.href = 'stores.php'</script>		
			<?php 	exit;} 
		}
		else
			$_SESSION['StoreErr'] = 'Invalid Subscription. Please Try Again..';
	}
}
$storeDetails =array();
if(!empty($_REQUEST["id"])){
$storeDetails = $db->get_row("SELECT * FROM stores WHERE id=".$_REQUEST["id"]);

if(empty($storeDetails))
{ ?>
	<script>window.location.href = 'stores.php'</script>
	
<?php exit;}
$storeSubID = $storeDetails['StoreUserSubscriptionId'];
$det1 = $db->get_row("select SubscriptionId from StoreUserSubscription where StoreUserSubscriptionId = '$storeSubID' ");
$det2 = $db->get_row("select Subscription from SubscriptionTypes where SubscriptionTypeId=".$det1['SubscriptionId']);
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

$cats = $db->get_rows("SELECT categories.* FROM categories WHERE categories.id!='' and cat_free_flag='1' GROUP BY categories.cat_name ORDER BY categories.cat_name ASC");
						
//$store_folder = ROOT.'admin/imgs/stores/'.$_GET['id'];
//$files = get_files($store_folder);
	$date = date("Y:M:D");
	$userid=$_SESSION['StoreID'];
	$subs = mysql_query("select su.*, st.* from StoreUserSubscription su left join SubscriptionTypes st on su.SubscriptionId= st.SubscriptionTypeId where su.status=0 and su.UserId = '$userid' and (su.EndDate=0 or su.EndDate is null or su.EndDate!='' or now() >=su.EndDate)");
if(empty($_REQUEST["id"])){
	$nrows = mysql_num_rows($subs);
 
if($nrows == 0){
	header("location:".ROOT_URL."subs.php");
}
}
?>
        	<div class="row">
            	<?php include ROOT."admin-left.php"; ?>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12" id="add">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 myact-userdet">
					<?php if(!empty($_SESSION['StoreErr'])){ ?>
						<div class="alert alert-danger" role="alert" style="text-align:center" id="alert"><?php echo $_SESSION['StoreErr']; ?></div>
					<?php } ?>
					 <div class="alert alert-warning" role="alert" style="display:none; text-align:center" id="alert"></div>
                        <h2 class="head-text">Store details</h2>
                        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">		<?php $notifications = notification();
										 if(!empty($errors)){ ?>
											<div class="alert alert-success" id="alert2" role="alert" style="text-align:center">Fill all fields</div>
                                        <?php } 
										
										?>
										
                        <form class="form-horizontal" method="post" enctype="multipart/form-data" id="form">
                        <input type="hidden" name='createdby' value='<?php echo $_SESSION["StoreID"]; ?>'/>
                        <input type="hidden" name='id' value='<?php echo isset($storeDetails["id"]) ? $storeDetails["id"] : ''; ?>'/>
                            
						   <div class="form-group">
						   <label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">Subscription<span class="require">*</span></label>
                            <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                  <?php if(!empty($_REQUEST["id"])){ 
								 $SubscriptionTypeId=$det1['SubscriptionId'];
								?>
									<input type="hidden" name="StoreUserSubscriptionId" value="<?php echo isset($storeDetails['StoreUserSubscriptionId']) ? $storeDetails['StoreUserSubscriptionId'] : ''; ?>" id="Subscriptions"/> 
									<input type="text" value="<?php echo $det2['Subscription']; ?>" id="extra" class="form-control" disabled/>
								<?php } else {?>
									<?php if($nrows > 0){  ?>
									  <select name="StoreUserSubscriptionId" id="Subscriptions" class="form-control">
											<?php while($subsciptions = mysql_fetch_array($subs))
											{ $SubscriptionTypeId=$subsciptions["SubscriptionTypeId"];?>
												<option value="<?php echo $subsciptions['StoreUserSubscriptionId']?>" <?php
												if(isset($storeDetails['StoreUserSubscriptionId'])){
													if($storeDetails['StoreUserSubscriptionId']==$subsciptions['StoreUserSubscriptionId']){
														echo "selected"; 
													}} ?>><?php echo $subsciptions['Subscription'] ?></option>
													
											<?php } 
											?>
									  </select>
								<?php } 
									else{ echo "<p style='text-align:center'> No Subsciptions Available. <a href='subs.php'>Click here</a> to buy a New Subsciption.</p>";}
								} ?>
								<?php ?>
                              
                            </div>
							</div>
							
							
							
                            <div class="form-group">
                                <label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">Store Name<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                  <input type="text" placeholder="Name" name='name' id='name' class="form-control" value="<?php echo isset($storeDetails['name']) ? $storeDetails['name'] : ''?>">
                                </div>
                            </div><input type="hidden" name="cat_id" value="<?=$SubscriptionTypeId;?>" />
                            <?php /*?>   <div class="form-group">
                                <label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">Store Type<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                     
                                
                               <select class="form-control" name="cat_id"  id="cat_id" >
                                  		<option value="">No Category</option>
									      <?php if(!empty($cats)): ?>
                                            <?php foreach($cats as $k=>$v): ?>
                                            <option value="<?php echo $v['id']; ?>" <?php  if(isset($storeDetails['cat_id'])) { if($v['id']==$storeDetails['cat_id']) echo 'selected';} ?>><?php echo $v['cat_name']; ?></option>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                  </select>
                                </div>
                            </div><?php */?>
                            <div class="form-group">
                            	<label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">Address<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                  <input class="form-control required alpha" placeholder="Address" name='address' id='map_marker' value="<?php echo isset($storeDetails['address']) ? $storeDetails['address'] : '' ?>">
								  
								  <p style="color:red">The Latitude and Longitude will be automatically detected upon entering address</p>
                                </div>
                            </div>
							<div class="form-group">
                            <label  class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">Zipcode <span class='required'>*</span>:</label>
     						<div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                            <input type='text' class="form-control" name='zipcode' id='zipcode' value='<?php echo isset($storeDetails['zipcode']) ? $storeDetails['zipcode'] : ''; ?>' placeholder="zipcode" required/>
                             </div>
                        </div>
                            <div class="form-group">
                                <label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">Telephone<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                  <input type="text" placeholder="Telephone" class="form-control required alpha"  name='mobile' id='telephone'  value="<?php echo isset($storeDetails['mobile']) ? $storeDetails['mobile'] : '';?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">Email Id<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                  <input type="email" placeholder="Email Id" name='email' id='email' class="form-control required alpha"  value="<?php echo isset($storeDetails['email'])? $storeDetails['email'] : '';?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">Website<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
									<div class="input-group">
									  <span class="input-group-addon" id="basic-addon3">http://</span>
									  <input type="text" name='website' placeholder="Website" class="form-control required alpha" value="<?php echo isset($storeDetails['website']) ? $storeDetails['website'] : '';?>" id="website" aria-describedby="basic-addon3">
									</div>
                                </div>
                            </div>
                            <div class="form-group">
                            	<label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">Description<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
									<textarea name="description" id='description' class="form-control" 	placeholder="Description" onKeyDown="limitText(this.form.description,this.form.countdown,250);" 
									onKeyUp="limitText(this.form.description,this.form.countdown,250);" OnBlur="limitText(this.form.description,this.form.countdown,250);"><?php if(!empty($storeDetails['description'])){echo $storeDetails['description'];}?></textarea>
									<font size="1">(Maximum characters: 250)</font>
                                </div>
                            </div>
                            <div class="form-group">
                               <?php if(empty($_REQUEST["id"])){ ?>
								<label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">Upload Image<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                 <input type="file" name='file' id='file' placeholder="Image" class="required alpha" >
                                <?php } else {?> 
								<label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">Uploaded Image<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                <?php if(isset($storeDetails['image']) && !empty($storeDetails['image'])) {
									if ((!(substr($storeDetails['image'], 0, 7) == 'http://')) && (!(substr($url, 0, 8) == 'https://'))) 
										{ $simg = ROOT_URL .$storeDetails['image']; } 
									else
										{$simg = $storeDetails['image'];}
									$img_path = $simg; ?>
									<img src = '<?php echo $img_path; ?>' />
								<?php } } ?>
								</div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">Latitude<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                  <input type="text" placeholder="Latitude" class="form-control required alpha" name='latitude' id='latiitude' value="<?php echo isset($storeDetails['latitude']) ? $storeDetails['latitude'] : '';?>" readonly required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">Longitude<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                                  <input type="text" placeholder="Longitude" class="form-control required alpha"  name='longitude' id='longitude' value="<?php echo isset($storeDetails['longitude']) ? $storeDetails['longitude'] : '';?>" readonly required>
                                </div>
                            </div>
                          <div id="store_map_canvas" class="newstore_map" style="height:300px;width:100%"></div>
						  
						  <?php 
						  $days = array('mon' => 'MONDAY', 'tue' => 'TUESDAY', 'wed' => 'WEDNESSDAY', 'thu' => 'THURSDAY', 'fri' => 'FRIDAY', 'sat' => 'SATURDAY','sun' => 'SUNDAY');
						  
						  $timings = array('00:00' => '12.00 AM', '00:30' => '12.30 AM', '01:00' => '01.00 AM', '01:30' => '01.30 AM', '02:00' => '02.00 AM', '02:30' => '02.30 AM', '03:00' => '03.00 AM', '03:30' => '03.30 AM', '04:00' => '04.00 AM', '04:30' => '04.30 AM', '05:00' => '05.00 AM', '05:30' => '05.30 AM', '06:00' => '06.00 AM', '06:30' => '06.30 AM', '07:00' => '07.00 AM', '07:30' => '07.30 AM', '08:00' => '08.00 AM', '08:30' => '08.30 AM', '09:00' => '09.00 AM', '09:30' => '09.30 AM', '10:00' => '10.00 AM', '10:30' => '10.30 AM', '11:00' => '11.00 AM', '11:30' => '11.30 AM', '12:00' => '12.00 NOON', '12:30' => '12.30 PM', '13:00' => '01.00 PM', '13:30' => '01.30 PM', '14:00' => '02.00 PM', '14:30' => '02.30 PM',  '15:00' => '03.00 PM', '15:30' => '03.30 PM','16:00' => '04.00 PM', '16:30' => '04.30 PM', '17:00' => '05.00 PM', '17:30' => '05.30 PM', '18:00' => '06.00 PM', '18:30' => '06.30 PM','19:00' => '07.00 PM', '19:30' => '07.30 PM', '20:00' => '08.00 PM', '20:30' => '08.30 PM', '21:00' => '09.00 PM', '21:30' => '09.30 PM', '22:00' => '10.00 PM', '22:30' => '10.30 PM', '23:00' => '11.00 PM', '23:30' => '11.30 PM' );
						  
						  $store_timings = json_decode(json_encode(array()));
						  if(isset($storeDetails['timings'])){
							  $store_timings = json_decode($storeDetails['timings']);
						  }
						  
						  ?>
						  <div class="row" style="color: red">
								<strong>Note : Please select checkbox for Store Close day</strong><br/>						
							</div>
						  <?php foreach($days as $dayKey => $dayDetails){ ?>
						  <?php $day_timings = isset($store_timings->$dayDetails) ? $store_timings->$dayDetails : false; ?>
						  <div class="form-group">
							<div class="col-lg-12 col-md-12 col-sm-12 text-center">
							
							<div class="row">
								<div class="col-sm-1">
								<input type="checkbox" name="timings[<?=$dayDetails;?>][is_closed]" value="1" 
								<?=($day_timings && isset($day_timings->is_closed)) ? ' checked="checked"' : '';?> />
								</div>
								<div class="col-sm-2">
									<label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label"><?=$dayDetails;?></label>
								</div>
								<div class="col-sm-3">
									<div class="input-group bootstrap-timepicker timepicker">
										<select name="timings[<?=$dayDetails;?>][starttime]" class="form-control">
											<option value="">Select Time</option>
											<?php foreach($timings as $timingKey => $timingDetails){ ?>
											<option value="<?=$timingDetails;?>"
											<?=($day_timings && isset($day_timings->starttime) && ($day_timings->starttime == $timingDetails)) ? ' selected="selected"' : '';?> 
											<?=($timingDetails == '09.00 AM') ? 'selected' : "" ?>><?=$timingDetails;?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="input-group bootstrap-timepicker timepicker">
										<select name="timings[<?=$dayDetails;?>][endtime]" class="form-control">
											<option value="">Select Time</option>
											<?php foreach($timings as $timingKey => $timingDetails){ ?>
											<option value="<?=$timingDetails;?>"
											<?=($day_timings && isset($day_timings->endtime) && ($day_timings->endtime == $timingDetails)) ? ' selected="selected"' : '';?> 
											<?=($timingDetails == '05.00 PM') ? 'selected' : "" ?>><?=$timingDetails;?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								
							</div>
								
							</div>
						</div>
						<?php } ?>
						
						
						
                            <div class="form-group">
                            	<label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">First-Time Patients<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
									<textarea name="first_time_patients" id='first_time_patients' class="form-control" 	placeholder="First-Time Patients"><?php if(!empty($storeDetails['first_time_patients'])){echo $storeDetails['first_time_patients'];}?></textarea>
                                </div>
                            </div>
							
                            <div class="form-group">
                            	<label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">Announcement<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
									<textarea name="announcement" id='announcement' class="form-control" placeholder="Announcement"><?php if(!empty($storeDetails['announcement'])){echo $storeDetails['announcement'];}?></textarea>
                                </div>
                            </div>
							
                            <div class="form-group">
                            	<label class="col-lg-2 col-md-2 col-sm-3 col-xs-12 control-label">About Us<span class="require">*</span></label>
                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
									<textarea name="about_us" id='about_us' class="form-control" placeholder="About Us"><?php if(!empty($storeDetails['about_us'])){echo $storeDetails['about_us'];}?></textarea>
                                </div>
                            </div>
                 
                            <div class="form-group">
                                <div class="col-lg-12 col-md-12 col-sm-12 text-center">                        
                                <button class="btn btn-primary" OnClick="FormSubmit(); return false;" name="savestore">Save</button>
                                <a class="btn btn-danger" href="stores.php">Cancel</a>
                                </div>
                    		</div>
							
                        </form></div>
                     </div>
                </div>
            </div>
        </div>
	</div>
</div>
<link href="https://raw.githubusercontent.com/jdewit/bootstrap-timepicker/gh-pages/css/timepicker.less" type="text/css" />
<script type="text/javascript" src="<?=ROOT_URL;?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="https://raw.githubusercontent.com/jdewit/bootstrap-timepicker/gh-pages/js/bootstrap-timepicker.js"></script>
<script>
$(document).ready(function(){
	$('#latiitude').change(function(){
		var value = $('#'+id).val();
		if(value!='')
			$('#'+id).css('border-color','');
	});
	
	
}); 
function val(id)
{
	
}
google.maps.event.addDomListener(window, 'load', initMap);
var lat=37.7391393,lngi=-99.0927344;
var lng=lngi;
var marker_vis=false;
var file_uploaded=false;
	<?if(isset($storeDetails['longitude']) && isset($storeDetails['latitude']))
	{
		echo "lat=".$storeDetails['latitude'].",lngi=".$storeDetails['longitude'].";";
		echo "marker_vis=true;";
	}
	if(isset($_GET['id']))
	echo "file_uploaded=true;";
	?>
	var inizoom=15,map,infoWindow;
	 var marker,autocomplete,geocoder= new google.maps.Geocoder();
	function initMap() {
		var mapCanvas = document.getElementById('store_map_canvas');
		var mapOptions = {
			center: new google.maps.LatLng(lat, lngi),
			zoom: inizoom,
			zoomControl: false,
			mapTypeControl: false,
			streetViewControl: false,
			panControl: false,
			mapTypeId: google.maps.MapTypeId.ROADMAP
			/*styles: [{"featureType":"all","elementType":"labels.text.fill", "stylers":[{"saturation":36},{"color":"#a5a6a8"},{"lightness":2}]},
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
			{"featureType":"water","elementType":"geometry","stylers":[{"color":"#282A2E"},{"lightness":14}]}]*/
		}
		map = new google.maps.Map(mapCanvas, mapOptions);
		marker = new google.maps.Marker({
			map: map,
			position: new google.maps.LatLng(lat, lngi),
			draggable: true,
			animation: google.maps.Animation.DROP
		});
		marker.setVisible(marker_vis)
		google.maps.event.addListener(marker, "dragend", function (e) {
			geocoder.geocode({ 'latLng': marker.getPosition() }, function (results, status) {
			console.log(results)
			console.log(status)
			
			if (status == google.maps.GeocoderStatus.OK) {
				$("#map_marker").val(results[0].formatted_address)
				map.setCenter(results[0].geometry.location);
                //var marker = new google.maps.Marker({map:map, position:results[0].geometry.location});
				$("#latiitude").val(results[0].geometry.location.lat())
				$("#longitude").val(results[0].geometry.location.lng())
				marker.setPosition(results[0].geometry.location);
				
			}
		 });
	})
}
		
	autocomplete = new google.maps.places.Autocomplete(document.getElementById('map_marker'));
	//autocomplete.bindTo('bounds', map);
	autocomplete.addListener('place_changed', function() {
		var place = autocomplete.getPlace();
		//console.log(place.geometry.location)
		map.setCenter(place.geometry.location);
		map.setZoom(14); 
		//marker.setPosition(new google.maps.LatLng(place.geometry.viewport.O.O, place.geometry.viewport.j.O));
		marker.setPosition(place.geometry.location);
		$("#latiitude").val(place.geometry.location.lat());
		$("#longitude").val(place.geometry.location.lng())
		marker.setVisible(true)
		$("#latiitude").trigger("keyup")
		$("#longitude").trigger("keyup")
		
	});
	
$(document).ready(function() {
		$("#form").validate();
		return false;
});
$("#form").find("input,textarea").keyup(function(){
	if($(this).val()=='')
		$(this).css("border","1px solid red");
	else
		$(this).css("border","");
})
function FormSubmit()
{
	var namesPattern = /^[a-zA-Z ]*$/;
	var eMailPattern = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	var phonePattern = /^\d{10}$/;
	var error=0;
	$("#form").find("input,textarea,select").each(function(){
		
		if($(this).val()=='')
		{
			if($(this).attr('name')=="file")
			{
				if(!file_uploaded)
				{
					error=1;
					$(this).css("border","1px solid red")
				}
				else
					$(this).css("border","")
			}	
			if($(this).attr('name')=='id')
			{
				return true;
			}
			else
			{
				error=1;
				$(this).css("border","1px solid red")
			}
		}
		else
		{
			if($(this).attr('name')=="email")
			{
				if(!eMailPattern.test($(this).val())) 
				{
					error=1;
					$(this).css("border","1px solid red")
					$('#alert').html('Enter a valid Email.');
					$('#alert').removeClass('alert alert-success');
					$('#alert').addClass('alert alert-warning');
					$('#alert').fadeIn().fadeOut(5000)
				}
				else
				{
					$(this).css("border","")
					$('#alert').hide()
				}
			}
			else if($(this).attr('name')=="mobile")
			{
				if(!phonePattern.test($(this).val()))
				{
					error=1;
					$(this).css("border","1px solid red")
					$('#alert').html('Enter a Valid Mobile Number');
					$('#alert').removeClass('alert alert-success');
					$('#alert').addClass('alert alert-warning');
					$('#alert').fadeIn().fadeOut(5000)
				}
				else
				{
					$(this).css("border","")
					$('#alert').hide()
				}
			}	
			
		}	
	});
	if(error>0)
	{
		$("html, body").animate({
            scrollTop: 0
        }, 600);
		return false;
	}	
	$('#form').submit();
}
function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) 
	{
		limitField.value = limitField.value.substring(0, limitNum);
	} 
	else
	{
		limitCount.value = limitNum - limitField.value.length;
	}
}
function webVal(url)
{
	if(!isValidURL($("#"+url).val()))
	{
		$('#'+url).css('border-color','red')
		$('#'+url).val('');
	}
	else
	{
		$('#'+url).css('border-color','#eee')
	}
}

function isValidURL(url)
{
		var RegExp = /^(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/;
		if(RegExp.test(url))
			return true;
		else
			return false;
}
<?php  if(!empty($notifications) || !empty($errors) || !empty($_SESSION['StoreErr'])){ ?>
		$(document).ready(function (){
			setTimeout(function (){ $('#alert').hide(); }, 3000);
		});
<?php unset($_SESSION['StoreSuc']); unset($_SESSION['StoreErr']);} ?>
</script>
<?php include ROOT."themes/footer.inc.php"; ?>
</body>
</html>