<?php error_reporting(0);
// include Config File
include_once './includes/config.inc.php';
include_once './includes/validate.php';
// Authenticate user login
auth();



if(isset($_POST['language_set'])){

	if (!is_writable('../includes/config.inc.php')) {
			$errors[] = 'Please ensure that includes/config.inc.php is writable';
	} else {
			
			
		$arr_config = array();
		// frontend config
		$handle = fopen("../includes/config.inc.php", "r");
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
			
			
				if (strpos($line,"define('DEFAULT_LANGUAGE'") !== false) {
				   $line = "define('DEFAULT_LANGUAGE', '".$_POST['language_set']."');\n";
				} else if (strpos($line,"define('DEFAULT_DISTANCE'") !== false) {
				   $line = "define('DEFAULT_DISTANCE', '".$_POST['distance_set']."');\n";
				} else if (strpos($line,"define('INIT_ZOOM'") !== false) {
				    $line = "define('INIT_ZOOM','".$_POST['init_zoom']."');\n";  
				} else if (strpos($line,"define('ZOOMHERE_ZOOM'") !== false) {
				    $line = "define('ZOOMHERE_ZOOM','".$_POST['zoomhere_zoom']."');\n";
				} else if (strpos($line,"define('GEO_SETTINGS'") !== false) {
				    $line = "define('GEO_SETTINGS','".$_POST['geo_settings']."');\n";
				} else if (strpos($line,"define('DEFAULT_LOCATION'") !== false) {
					$line = "define('DEFAULT_LOCATION','".$_POST['default_location']."');\n";
				} else if (strpos($line,"define('STYLE_MAP_COLOR'") !== false) {
					$line = "define('STYLE_MAP_COLOR','".$_POST['style_map_color']."');\n";
				} else if (strpos($line,"define('STYLE_TOP_BAR_BG'") !== false) {
					$line = "define('STYLE_TOP_BAR_BG','".$_POST['style_top_bar_bg']."');\n";
				} else if (strpos($line,"define('STYLE_TOP_BAR_BORDER'") !== false) {
					$line = "define('STYLE_TOP_BAR_BORDER','".$_POST['style_top_bar_border']."');\n";
				} else if (strpos($line,"define('STYLE_TOP_BAR_FONT'") !== false) {
					$line = "define('STYLE_TOP_BAR_FONT','".$_POST['style_top_bar_font']."');\n";
				}  else if (strpos($line,"define('STYLE_RESULTS_BG'") !== false) {
					$line = "define('STYLE_RESULTS_BG','".$_POST['style_results_bg']."');\n";
				}  else if (strpos($line,"define('STYLE_RESULTS_FONT'") !== false) {
					$line = "define('STYLE_RESULTS_FONT','".$_POST['style_results_font']."');\n";
				}  else if (strpos($line,"define('STYLE_RESULTS_HL_BG'") !== false) {
					$line = "define('STYLE_RESULTS_HL_BG','".$_POST['style_results_hl_bg']."');\n";
				}  else if (strpos($line,"define('STYLE_RESULTS_HOVER_BG'") !== false) {
					$line = "define('STYLE_RESULTS_HOVER_BG','".$_POST['style_results_hover_bg']."');\n";
				}  else if (strpos($line,"define('STYLE_RESULTS_DISTANCE_FONT'") !== false) {
					$line = "define('STYLE_RESULTS_DISTANCE_FONT','".$_POST['style_results_distance_font']."');\n";
				}  else if (strpos($line,"define('STYLE_DISTANCE_TOGGLE_BG'") !== false) {
					$line = "define('STYLE_DISTANCE_TOGGLE_BG','".$_POST['style_distance_toggle_bg']."');\n";
				}   else if (strpos($line,"define('STYLE_CONTACT_BUTTON_BG'") !== false) {
					$line = "define('STYLE_CONTACT_BUTTON_BG','".$_POST['style_contact_button_bg']."');\n";
				}   else if (strpos($line,"define('STYLE_CONTACT_BUTTON_FONT'") !== false) {
					$line = "define('STYLE_CONTACT_BUTTON_FONT','".$_POST['style_contact_button_font']."');\n";
				}   else if (strpos($line,"define('STYLE_BUTTON_BG'") !== false) {
					$line = "define('STYLE_BUTTON_BG','".$_POST['style_button_bg']."');\n";
				}   else if (strpos($line,"define('STYLE_BUTTON_FONT'") !== false) {
					$line = "define('STYLE_BUTTON_FONT','".$_POST['style_button_font']."');\n";
				}   else if (strpos($line,"define('STYLE_LIST_NUMBER_BG'") !== false) {
					$line = "define('STYLE_LIST_NUMBER_BG','".$_POST['style_list_number_bg']."');\n";
				}   else if (strpos($line,"define('STYLE_LIST_NUMBER_FONT'") !== false) {
					$line = "define('STYLE_LIST_NUMBER_FONT','".$_POST['style_list_number_font']."');\n";
				}else if (strpos($line,"define('SOCIAL_FB_LINK'") !== false) {					$line = "define('SOCIAL_FB_LINK','".$_POST['SOCIAL_FB_LINK']."');\n";				}else if (strpos($line,"define('SOCIAL_TW_LINK'") !== false) {					$line = "define('SOCIAL_TW_LINK','".$_POST['SOCIAL_TW_LINK']."');\n";				}else if (strpos($line,"define('SOCIAL_INST_LINK'") !== false) {					$line = "define('SOCIAL_INST_LINK','".$_POST['SOCIAL_INST_LINK']."');\n";				}else if (strpos($line,"define('SOCIAL_LIN_LINK'") !== false) {					$line = "define('SOCIAL_LIN_LINK','".$_POST['SOCIAL_LIN_LINK']."');\n";				}

				
					//print_r($line);
				$arr_config[] = $line;
			}

			fclose($handle);
		} else {
			// error opening the file.
			 $errors[] = 'Kindly set includes/config.inc.php to writable';
		}

		$conf_str = "";	
		for($i=0;$i<sizeof($arr_config);$i++){
		$conf_str.=$arr_config[$i];
		}
		
		$fp = fopen('../includes/config.inc.php', 'w');
		fwrite($fp, $conf_str);
		fclose($fp);
		
		$_SESSION['notification'] = array('type'=>'good','msg'=>'Settings successfully saved.');
	}

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title><?php echo $lang['STORE_FINDER']; ?> - <?php echo $lang['ADMIN_ADD_USER']; ?></title>
	<?php include 'header.php'; ?>
	

    <link href="css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
	

    <script src="js/bootstrap-colorpicker.js"></script>
    <script src="js/docs.js"></script>
	<style>
	.container{
	background:none !important;
	}
	</style>
	<script>
	function restoreDefault(){
	
		$('#distance_set').val('mi');
		$('#language_set').val('en_EN');
		
		$('#init_zoom').val('11');
		$('#zoomhere_zoom').val('15');
		$('#geo_settings').val('1');
		$('#default_location').val('New York, US');
		
		$('#style_map_color').val('');
		$('#style_top_bar_bg').val('');
		$('#style_top_bar_border').val('');
		$('#style_top_bar_font').val('');
		
		$('#style_results_bg').val('');
		$('#style_results_hl_bg').val('');
		$('#style_results_hover_bg').val('');
		$('#style_results_font').val('');
		$('#style_results_distance_font').val('');
		
		$('#style_distance_toggle_bg').val('');
		
		$('#style_contact_button_bg').val('');
		$('#style_contact_button_font').val('');
		
		$('#style_button_bg').val('');
		$('#style_button_font').val('');
		$('#style_list_number_bg').val('');
		$('#style_list_number_font').val('');

		
		
		$('#langset').val($('#language_set').val())
	
		document.f.submit();
	}
	</script>
	
</head>
<body id="add">
	<div id="wrapper">
		<div id="header">
			
			<?php include 'nav.php'; ?>
		</div>

		<div id="main">

			
			<?php echo notification(); ?>
			<?php if(isset($errors)): ?>
			<div class="alert alert-block alert-error fade in">
			<ul>
				<?php foreach($errors as $k=>$v): ?>
				<li><?php echo $v; ?></li>
				<?php endforeach; ?>
			</ul>
			</div>
			<?php endif; ?>


			<?php include_once '../includes/config.inc.php'; ?>
			<form name="f" method='post' action='' id='form_settings' enctype="multipart/form-data" onsubmit="$('#langset').val($('#language_set').val())">
			<input type="hidden" id="langset" name="langset" value="en_US" />
				<fieldset>
					<legend>General Settings</legend>
					<div class='input'>
						<label>Default Language: </label>
						<select id="language_set" name="language_set">
						<option value="en_US" <?php if(DEFAULT_LANGUAGE=="en_US") { ?>selected<?php } ?>>English</option>
						<option value="sv_SE" <?php if(DEFAULT_LANGUAGE=="sv_SE") { ?>selected<?php } ?>>Swedish</option>
						<option value="es_ES" <?php if(DEFAULT_LANGUAGE=="es_ES") { ?>selected<?php } ?>>Spanish</option>
						<option value="fr_FR" <?php if(DEFAULT_LANGUAGE=="fr_FR") { ?>selected<?php } ?>>French</option>
						<option value="de_DE" <?php if(DEFAULT_LANGUAGE=="de_DE") { ?>selected<?php } ?>>German</option>
						<option value="cn_CN" <?php if(DEFAULT_LANGUAGE=="cn_CN") { ?>selected<?php } ?>>Chinese</option>
						<option value="kr_KR" <?php if(DEFAULT_LANGUAGE=="kr_KR") { ?>selected<?php } ?>>Korean</option>
						<option value="jp_JP" <?php if(DEFAULT_LANGUAGE=="jp_JP") { ?>selected<?php } ?>>Japanese</option>
						<option value="ar_AR" <?php if(DEFAULT_LANGUAGE=="ar_AR") { ?>selected<?php } ?>>Arabic</option>
						</select>
					</div>
					<div class='input'>
						<label>Default Distance: </label>
							<select id="distance_set" name="distance_set">
							<option value="mi" <?php if(DEFAULT_DISTANCE=="mi") { ?>selected<?php } ?>>Miles (mi)</option>
							<option value="km" <?php if(DEFAULT_DISTANCE=="km") { ?>selected<?php } ?>>Kilometers (km)</option>
							</select>
					</div>
					<div class='input'>
						<label>Init Zoom:</label>
						<select id="init_zoom" name="init_zoom">
							<option value="0" <?php if(INIT_ZOOM=="0") { ?>selected<?php } ?>>0</option>
							<option value="1" <?php if(INIT_ZOOM=="1") { ?>selected<?php } ?>>1</option>
							<option value="2" <?php if(INIT_ZOOM=="2") { ?>selected<?php } ?>>2</option>
							<option value="3" <?php if(INIT_ZOOM=="3") { ?>selected<?php } ?>>3</option>
							<option value="4" <?php if(INIT_ZOOM=="4") { ?>selected<?php } ?>>4</option>
							<option value="5" <?php if(INIT_ZOOM=="5") { ?>selected<?php } ?>>5</option>
							<option value="6" <?php if(INIT_ZOOM=="6") { ?>selected<?php } ?>>6</option>
							<option value="7" <?php if(INIT_ZOOM=="7") { ?>selected<?php } ?>>7</option>
							<option value="8" <?php if(INIT_ZOOM=="8") { ?>selected<?php } ?>>8</option>
							<option value="9" <?php if(INIT_ZOOM=="9") { ?>selected<?php } ?>>9</option>
							<option value="10" <?php if(INIT_ZOOM=="10") { ?>selected<?php } ?>>10</option>
							<option value="11" <?php if(INIT_ZOOM=="11") { ?>selected<?php } ?>>11</option>
							<option value="12" <?php if(INIT_ZOOM=="12") { ?>selected<?php } ?>>12</option>
							<option value="13" <?php if(INIT_ZOOM=="13") { ?>selected<?php } ?>>13</option>
							<option value="14" <?php if(INIT_ZOOM=="14") { ?>selected<?php } ?>>14</option>
							<option value="15" <?php if(INIT_ZOOM=="15") { ?>selected<?php } ?>>15</option>
							<option value="16" <?php if(INIT_ZOOM=="16") { ?>selected<?php } ?>>16</option>
							<option value="17" <?php if(INIT_ZOOM=="17") { ?>selected<?php } ?>>17</option>
							<option value="18" <?php if(INIT_ZOOM=="18") { ?>selected<?php } ?>>18</option>
							<option value="19" <?php if(INIT_ZOOM=="19") { ?>selected<?php } ?>>19</option>
							</select>
					</div>
					<div class='input'>
						<label>Zoom Here:</label>
						<select id="zoomhere_zoom" name="zoomhere_zoom">
							<option value="0" <?php if(ZOOMHERE_ZOOM=="0") { ?>selected<?php } ?>>0</option>
							<option value="1" <?php if(ZOOMHERE_ZOOM=="1") { ?>selected<?php } ?>>1</option>
							<option value="2" <?php if(ZOOMHERE_ZOOM=="2") { ?>selected<?php } ?>>2</option>
							<option value="3" <?php if(ZOOMHERE_ZOOM=="3") { ?>selected<?php } ?>>3</option>
							<option value="4" <?php if(ZOOMHERE_ZOOM=="4") { ?>selected<?php } ?>>4</option>
							<option value="5" <?php if(ZOOMHERE_ZOOM=="5") { ?>selected<?php } ?>>5</option>
							<option value="6" <?php if(ZOOMHERE_ZOOM=="6") { ?>selected<?php } ?>>6</option>
							<option value="7" <?php if(ZOOMHERE_ZOOM=="7") { ?>selected<?php } ?>>7</option>
							<option value="8" <?php if(ZOOMHERE_ZOOM=="8") { ?>selected<?php } ?>>8</option>
							<option value="9" <?php if(ZOOMHERE_ZOOM=="9") { ?>selected<?php } ?>>9</option>
							<option value="10" <?php if(ZOOMHERE_ZOOM=="10") { ?>selected<?php } ?>>10</option>
							<option value="11" <?php if(ZOOMHERE_ZOOM=="11") { ?>selected<?php } ?>>11</option>
							<option value="12" <?php if(ZOOMHERE_ZOOM=="12") { ?>selected<?php } ?>>12</option>
							<option value="13" <?php if(ZOOMHERE_ZOOM=="13") { ?>selected<?php } ?>>13</option>
							<option value="14" <?php if(ZOOMHERE_ZOOM=="14") { ?>selected<?php } ?>>14</option>
							<option value="15" <?php if(ZOOMHERE_ZOOM=="15") { ?>selected<?php } ?>>15</option>
							<option value="16" <?php if(ZOOMHERE_ZOOM=="16") { ?>selected<?php } ?>>16</option>
							<option value="17" <?php if(ZOOMHERE_ZOOM=="17") { ?>selected<?php } ?>>17</option>
							<option value="18" <?php if(ZOOMHERE_ZOOM=="18") { ?>selected<?php } ?>>18</option>
							<option value="19" <?php if(ZOOMHERE_ZOOM=="19") { ?>selected<?php } ?>>19</option>
							</select>
					</div>
					<div class='input'>
						<label>Geo IP Turned On:</label>
						<select id="geo_settings" name="geo_settings">
							<option value="0" <?php if(GEO_SETTINGS=="0") { ?>selected<?php } ?>>No</option>
							<option value="1" <?php if(GEO_SETTINGS=="1") { ?>selected<?php } ?>>Yes</option>
							</select>
					</div>
					<div class='input'>
						<label>Default Initial Location:</label>
						<input type='text' name='default_location' id='default_location' value='<?php echo DEFAULT_LOCATION; ?>' /> Won't be shown on map if Geo IP is turned on.
					</div>
					
					
					
					<legend>Styles Settings </legend>
					<i>* Leave blank for default color.</i>
					<br><br>
					<div class='input'>
						<label>Map Hue Color:</label>
						<input id="style_map_color" name="style_map_color" type="text" class="form-control demo demo-1 demo-auto colorpicker-element" value="<?php echo STYLE_MAP_COLOR; ?>"> 
					</div>
					<div class='input'>
						<label>Top Bar Background:</label>
						<input id="style_top_bar_bg" name="style_top_bar_bg" type="text" class="form-control demo demo-1 demo-auto colorpicker-element" value="<?php echo STYLE_TOP_BAR_BG; ?>"> 
					</div>
					
					<div class='input'>
						<label>Top Bar Font:</label>
						<input id="style_top_bar_font" name="style_top_bar_font" type="text" class="form-control demo demo-1 demo-auto colorpicker-element" value="<?php echo STYLE_TOP_BAR_FONT; ?>"> 
					</div>
					
					
					<div class='input'>
						<label>Top Bar Border:</label>
						<input id="style_top_bar_border" name="style_top_bar_border" type="text" class="form-control demo demo-1 demo-auto colorpicker-element" value="<?php echo STYLE_TOP_BAR_BORDER; ?>"> 
					</div>
					
					<div class='input'>
						<label>Results Background:</label>
						<input id="style_results_bg" name="style_results_bg" type="text" class="form-control demo demo-1 demo-auto colorpicker-element" value="<?php echo STYLE_RESULTS_BG; ?>"> 
					</div>
					
					<div class='input'>
						<label>Results Highlighted Background:</label>
						<input id="style_results_hl_bg" name="style_results_hl_bg" type="text" class="form-control demo demo-1 demo-auto colorpicker-element" value="<?php echo STYLE_RESULTS_HL_BG; ?>"> 
					</div>
					
					<div class='input'>
						<label>Results Hover Background:</label>
						<input id="style_results_hover_bg" name="style_results_hover_bg" type="text" class="form-control demo demo-1 demo-auto colorpicker-element" value="<?php echo STYLE_RESULTS_HOVER_BG; ?>"> 
					</div>
					
					<div class='input'>
						<label>Results Font:</label>
						<input id="style_results_font" name="style_results_font" type="text" class="form-control demo demo-1 demo-auto colorpicker-element" value="<?php echo STYLE_RESULTS_FONT; ?>"> 
					</div>
					
					<div class='input'>
						<label>Results Distance Font:</label>
						<input id="style_results_distance_font" name="style_results_distance_font" type="text" class="form-control demo demo-1 demo-auto colorpicker-element" value="<?php echo STYLE_RESULTS_DISTANCE_FONT; ?>"> 
					</div>
					
					<div class='input'>
						<label>Distance Toggle Background:</label>
						<input id="style_distance_toggle_bg" name="style_distance_toggle_bg" type="text" class="form-control demo demo-1 demo-auto colorpicker-element" value="<?php echo STYLE_DISTANCE_TOGGLE_BG; ?>"> 
					</div>
					
					<div class='input'>
						<label>Contact Button Background:</label>
						<input id="style_contact_button_bg" name="style_contact_button_bg" type="text" class="form-control demo demo-1 demo-auto colorpicker-element" value="<?php echo STYLE_CONTACT_BUTTON_BG; ?>"> 
					</div>
					
					<div class='input'>
						<label>Contact Button Font:</label>
						<input id="style_contact_button_font" name="style_contact_button_font" type="text" class="form-control demo demo-1 demo-auto colorpicker-element" value="<?php echo STYLE_CONTACT_BUTTON_FONT; ?>"> 
					</div>
					
					<div class='input'>
						<label>Buttons Background:</label>
						<input id="style_button_bg" name="style_button_bg" type="text" class="form-control demo demo-1 demo-auto colorpicker-element" value="<?php echo STYLE_BUTTON_BG; ?>"> 
					</div>
					
					<div class='input'>
						<label>Buttons Font:</label>
						<input id="style_button_font" name="style_button_font" type="text" class="form-control demo demo-1 demo-auto colorpicker-element" value="<?php echo STYLE_BUTTON_FONT; ?>"> 
					</div>
					
					<div class='input'>
						<label>Number List Background:</label>
						<input id="style_list_number_bg" name="style_list_number_bg" type="text" class="form-control demo demo-1 demo-auto colorpicker-element" value="<?php echo STYLE_LIST_NUMBER_BG; ?>"> 
					</div>
					
					<div class='input'>
						<label>Number List Font:</label>
						<input id="style_list_number_font" name="style_list_number_font" type="text" class="form-control demo demo-1 demo-auto colorpicker-element" value="<?php echo STYLE_LIST_NUMBER_FONT; ?>"> 
					</div>					<legend>Social Settings </legend>					<div class='input'>						<label>Facebook Page:</label>						<input id="SOCIAL_FB_LINK" name="SOCIAL_FB_LINK" type="text" class="form-control" value="<?php echo SOCIAL_FB_LINK; ?>"> 					</div>											<div class='input'>						<label>Twitter Account Link:</label>						<input id="SOCIAL_TW_LINK" name="SOCIAL_TW_LINK" type="text" class="form-control" value="<?php echo SOCIAL_TW_LINK; ?>">  					</div>											<div class='input'>						<label>Instagram Account Link:</label>						<input id="SOCIAL_INST_LINK" name="SOCIAL_INST_LINK" type="text" class="form-control" value="<?php echo SOCIAL_INST_LINK; ?>">   					</div>					<div class='input'>						<label>LinkedIn Account Link:</label>						<input id="SOCIAL_LIN_LINK" name="SOCIAL_LIN_LINK" type="text" class="form-control" value="<?php echo SOCIAL_LIN_LINK;?>">   					</div>																<div class='input buttons'>
						<button type='button' class="btn" name='default' id='default' onclick="restoreDefault()">Restore Default Settings</button> <button type='submit' class="btn" name='save' id='save'><?php echo $lang['ADMIN_SAVE']; ?></button>
					</div>					
				</fieldset>				
			</form>
		</div>
	</div>
<?php include '../themes/footer.inc.php'; ?>
</body>
</html>