<?php
/*
 * Name: WP Flash Player Plugin URI: http://www.apptha.com/category/extension/Wordpress/HD-FLV-Player-Plugin/ Description: HD FLV Player common function file. Version: 1.3 Author: Apptha Author URI: http://www.apptha.com License: GPL2
 */
@session_start ();
$sessionToken = $_SESSION ['app_wp_token'];
$reqToken = trim ( $_REQUEST ["hdflv_token"] );

if ($sessionToken != $reqToken) {
	die ( "You are not authorized to access this file" );
}
/* Used to import plugin configuration */
require_once (dirname ( __FILE__ ) . '/hdflv-config.php');

global $wpdb;

$pluginDirPath = content_url () . '/plugins/' . dirname ( plugin_basename ( __FILE__ ) ) . '/images';

$updatedisplay = filter_input ( INPUT_GET, 'updatedisplay' );
$changeVideoStatus = filter_input ( INPUT_GET, 'changeVideoStatus' );
$changeplaylistStatus = filter_input ( INPUT_GET, 'changeplaylistStatus' );

if (isset ( $updatedisplay )) {
	
	$IdValue = filter_input ( INPUT_GET, 'IdValue' );
	$setValue = filter_input ( INPUT_GET, 'setValue' );
	update_option ( $IdValue, $setValue );
	exit ();
} else if (isset ( $changeVideoStatus )) {
	$videoId = filter_input ( INPUT_GET, 'videoId' );
	$status = filter_input ( INPUT_GET, 'status' );
	$sql = "UPDATE " . $wpdb->prefix . "hdflv  SET  is_active = $status WHERE vid = $videoId ";
	$wpdb->query ( $sql );
	if ($status) {
		echo "<img  title='deactive' style='cursor:pointer;' onclick='setVideoStatusOff($videoId,0)'  src=$pluginDirPath/hdflv_active.png />";
	} else {
		echo "<img  title='active' style='cursor:pointer;' onclick='setVideoStatusOff($videoId,1)'  src=$pluginDirPath/hdflv_deactive.png />";
	}
	
	exit ();
} else if (isset ( $changeplaylistStatus )) {
	$videoId = filter_input ( INPUT_GET, 'videoId' );
	$status = filter_input ( INPUT_GET, 'status' );
	$sql = "UPDATE " . $wpdb->prefix . "hdflv_playlist  SET  is_pactive = $status WHERE pid = $videoId ";
	$wpdb->query ( $sql );
	if ($status) {
		echo "<img  title='deactive' style='cursor:pointer;' onclick='setVideoStatusOff($videoId,0,1)'  src=$pluginDirPath/hdflv_active.png />";
	} else {
		echo "<img  title='active' style='cursor:pointer;' onclick='setVideoStatusOff($videoId,1,1)'  src=$pluginDirPath/hdflv_deactive.png />";
	}
	exit ();
}

$title = 'hdflv Playlist';
$pid1 = filter_input ( INPUT_GET, 'playid' );
$f_listItem = $_GET ['listItem'];
foreach ( $f_listItem as $position => $item ) :
	$wpdb->query ( "UPDATE $wpdb->prefix" . "hdflv_med2play SET `sorder` = $position WHERE `media_id` = $item and playlist_id=$pid1 " );
endforeach
;

$tables = $wpdb->get_results ( "SELECT vid FROM $wpdb->prefix" . "hdflv LEFT JOIN " . $wpdb->prefix . "hdflv_med2play ON (vid = media_id) WHERE (playlist_id = '$pid1') ORDER BY sorder ASC, vid ASC" );

if ($tables) {
	foreach ( $tables as $table ) {
		$playstore1 .= $table->vid . ",";
	}
}
?>