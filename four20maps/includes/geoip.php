<?php header('Content-Type: application/javascript');
include("geoipcity.inc");
include("geoipregionvars.php");
$user_ip = $_SERVER['REMOTE_ADDR'];

if(!is_localhost()){

$gi = geoip_open("GeoLiteCity.dat", GEOIP_STANDARD);
//print_r( geoip_db_get_all_info() );
$record = geoip_record_by_addr($gi, $user_ip);

geoip_close($gi);

}
/*
print $record->country_code . " " . $record->country_code3 . " " . $record->country_name . "<br /><br />";
print $record->region . " " . $GEOIP_REGION_NAME[$record->country_code][$record->region] . "<br /><br />";
print $record->city . "<br /><br />";
print $record->postal_code . "<br /><br />";
print $record->latitude . "<br /><br />";
print $record->longitude . "<br /><br />";
print $record->metro_code . "<br /><br />";
print $record->area_code . "<br /><br />";
print $record->continent_code . "<br /><br />";
*/

function is_localhost() {
    $whitelist = array( '127.0.0.1', '::1' );
    if( in_array( $_SERVER['REMOTE_ADDR'], $whitelist) )
        return true;
}

?>

function geoip_city(){
  return '<?php if(is_localhost()){ echo "Unable to detect city/country at localhost"; } else { echo $record->city; } ?>';
}

function geoip_country_name(){
 return '<?php if(is_localhost()){ echo "please upload at live server."; } else { echo $record->country_name; }  ?>';
}

