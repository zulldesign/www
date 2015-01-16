<?php
/*
 * Name: WP Flash Player Plugin URI: http://www.apptha.com/category/extension/Wordpress/HD-FLV-Player-Plugin/ Description: Video IMA AD xml file. Version: 1.3 Author: Apptha Author URI: http://www.apptha.com License: GPL2
 */

/* Used to import plugin configuration */
require_once (dirname ( __FILE__ ) . '/hdflv-config.php');
global $wpdb;

$settingsData = $wpdb->get_row ( "SELECT player_values,player_icons FROM " . $wpdb->prefix . "hdflv_settings" );
$player_icons = unserialize ( $settingsData->player_icons );
$player_values = unserialize ( $settingsData->player_values );

$imaAds = $player_icons ['ima_ads'];
// Create XML output of playlist

ob_clean ();
header ( "content-type: text/xml" );
echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<ima>';
if ($imaAds == 1) {
	if (! empty ( $player_values ['ima_ads_xml'] )) {
		// video ads
		echo '<adTagUrl>' . $player_values ['ima_ads_xml'] . '</adTagUrl>';
	} else {
		// video ads
		echo '<adTagUrl>http://pubads.g.doubleclick.net/gampad/ads?sz=2x2&iu=/5474/oms_video_testsite/radiovhr&ciu_szs=300x250,728x90&impl=s&gdfp_req=1&env=vp&output=xml_vast2&unviewed_position_start=1&url=[referrer_url]&correlator=[timestamp]&cust_params=nielsen%3D3b%26cue%3Dpre%26owner%3Drv505radiovhr%26Category%3Dtest%26source%3Drv505radiovhr%26ttID%3D[VideoID]%26[AMP_WLRCMD]</adTagUrl>';
	}
}
echo '</ima>';
?>