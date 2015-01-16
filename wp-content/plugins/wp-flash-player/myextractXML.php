<?php
/*
 * Name: WP Flash Player Plugin URI: http://www.apptha.com/category/extension/Wordpress/HD-FLV-Player-Plugin/ Description: Video playlist xml file. Version: 1.3 Author: Apptha Author URI: http://www.apptha.com License: GPL2
 */

/* Used to import plugin configuration */
require_once (dirname ( __FILE__ ) . '/hdflv-config.php');
global $wpdb, $i;
$title = 'hdflv Playlist';
$themediafiles = array ();

// Fetching videos of the selected playlist
$playlist_id = filter_input ( INPUT_GET, 'pid', FILTER_SANITIZE_STRING );
$playlist_id = intval ( $playlist_id );
$video_id = filter_input ( INPUT_GET, 'vid', FILTER_SANITIZE_STRING );
$video_id = intval ( $video_id );

if ($playlist_id != '' && $video_id != '') { // Condition if both playlist id && video id were set
	$playlist = $wpdb->get_row ( "SELECT * FROM " . $wpdb->prefix . "hdflv_playlist WHERE pid = '$playlist_id' AND is_pactive = 1 " );
	
	if (count ( $playlist ) > 0) {
		$selectVideo = " (SELECT * FROM " . $wpdb->prefix . "hdflv w WHERE w.vid = " . $video_id . "  AND is_active = 1)";
		$videoFiles = $wpdb->get_results ( $wpdb->prepare ( $selectVideo, NULL ) );
		$selectPlaylist = " (SELECT * FROM " . $wpdb->prefix . "hdflv w";
		$selectPlaylist .= " INNER JOIN " . $wpdb->prefix . "hdflv_med2play m";
		$selectPlaylist .= " WHERE (m.playlist_id = '$playlist_id'";
		$selectPlaylist .= " AND m.media_id = w.vid AND w.vid != $video_id)  AND w.is_active = 1 GROUP BY w.vid ";
		$selectPlaylist .= " ORDER BY m.sorder ASC , m.porder " . $playlist->playlist_order . " ,w.vid " . $playlist->playlist_order . ")";
		$playFiles = $wpdb->get_results ( $wpdb->prepare ( $selectPlaylist, NULL ) );
		$themediafiles = array_merge ( $videoFiles, $playFiles );
		
		$title = $playlist->playlist_name;
	}
} elseif ($playlist_id != '') { // Condition if the playlist id set
	$playlist = $wpdb->get_row ( "SELECT * FROM " . $wpdb->prefix . "hdflv_playlist WHERE pid = '$playlist_id' AND is_pactive = 1" );
	if ($playlist) {
		$select = " SELECT * FROM " . $wpdb->prefix . "hdflv w";
		$select .= " INNER JOIN " . $wpdb->prefix . "hdflv_med2play m";
		$select .= " WHERE (m.playlist_id = '$playlist_id'";
		$select .= " AND m.media_id = w.vid)  AND w.is_active = 1 GROUP BY w.vid ";
		$select .= " ORDER BY m.sorder ASC , m.porder " . $playlist->playlist_order . " ,w.vid " . $playlist->playlist_order;
		$themediafiles = $wpdb->get_results ( $wpdb->prepare ( $select, NULL ) );
		$title = $playlist->playlist_name;
	}
} else { // Condition if both video id is set
	$selectVideo = " (SELECT * FROM " . $wpdb->prefix . "hdflv w WHERE w.vid = " . $video_id . " AND is_active = 1)";
	$videoFiles = $wpdb->get_results ( $wpdb->prepare ( $selectVideo, NULL ) );
	$themediafiles = $videoFiles;
}

$settingsdata = $wpdb->get_var ( "SELECT player_icons FROM " . $wpdb->prefix . "hdflv_settings" );
$settingsRecord = unserialize ( $settingsdata );

$imaAds = $settingsRecord ['ima_ads'];
($settingsRecord ['playlistauto'] == 1) ? $ap = 'true' : $ap = 'false';

// Get ima ad detail
$imaad = ' allow_ima = "false"';
if ($imaAds != 0) {
	$imaad = ' allow_ima = "true"';
}

// Get preroll ad detail
if ($settingsRecord ['preroll'] == 1) {
	$preroll = ' allow_preroll = "true"';
	$preroll_id = ' preroll_id = ""';
} else {
	$preroll = ' allow_preroll = "false"';
	$preroll_id = ' preroll_id = "0"';
}

// Get postroll ad detail
if ($settingsRecord ['postroll'] == 1) {
	$postroll = ' allow_postroll = "true"';
	$postroll_id = ' postroll_id = ""';
} else {
	$postroll = ' allow_postroll = "false"';
	$postroll_id = ' postroll_id = "0"';
}

// Get postroll ad detail
if ($settingsRecord ['midroll_ads'] == 1) {
	$midroll = ' allow_midroll = "true"';
} else {
	$midroll = ' allow_midroll = "false"';
}

// Create XML output of playlist

header ( "content-type:text/xml;charset = utf-8" ); // mime type
echo '<?xml version = "1.0" encoding = "utf-8"?>';
echo "<playlist autoplay = '$ap' random = 'false'>";
$defaultVideoImg = content_url () . '/plugins/' . dirname ( plugin_basename ( __FILE__ ) ) . '/images/hdflv.jpg';

if (count ( $themediafiles )) { // if min(1) playlist or video is selected then it's play .
	
	foreach ( $themediafiles as $media ) {
		
		if ($media->image == '') {
			$image = $defaultVideoImg;
		} else {
			$image = $media->image;
		}
		if ($media->hdfile != '') {
			$hd = 'true';
		} else {
			$hd = 'false';
		}
		$streamer_path = '';
		$isLive = $media->islive;
		if ($isLive != 0) {
			$is_Live = 'true';
		} else {
			$is_Live = 'false';
		}
		$wp_upload = wp_upload_dir ();
		$wp_urlpath = $wp_upload ['url'] . '/';
		$download = ($settingsRecord ['download'] == 1) ? 'true' : 'false';
		$streamer_path = $media->streamer_path;
		if (preg_match ( '/www\.youtube\.com\/watch\?v=[^&]+/', $media->file, $vresult )) {
			$download = "false";
		} else if (strstr ( $media->file, $wp_urlpath ) == '') {
			$download = "false";
		}
		echo '<mainvideo video_url = "' . htmlspecialchars ( $media->file ) . '"
             video_hdpath   = "' . $media->hdfile . '"
             video_id = "' . htmlspecialchars ( $media->vid ) . '"
             thumb_image = "' . htmlspecialchars ( $image ) . '"
             preview_image = "' . htmlspecialchars ( $media->opimage ) . '"
             ' . $imaad . '    
             ' . $midroll . '    
             ' . $postroll . '    
             ' . $preroll . '    
             ' . $postroll_id . '    
             ' . $preroll_id . '    
             allow_download ="' . $download . '"
             streamer_path="' . $streamer_path . '" 
             video_isLive="' . $is_Live . '" >';
		echo '<title><![CDATA[' . htmlspecialchars ( $media->name ) . ']]></title> ';
		echo '<tagline targeturl=""><![CDATA[]]></tagline>';
		echo '</mainvideo>';
	} // for loop end hear
} // if end hear
else { // IF NO VIDEO IS FOUND THEN I PLAY DEFAULT VIDEO
	echo '<mainvideo video_url="http://www.hdflvplayer.net/hdflvplayer/videos/300.mp4"
             video_hdpath="http://www.hdflvplayer.net/hdflvplayer/videos/300.mp4"
             video_id="100"
             thumb_image="http://hdflvplayer.net/hdflvplayer/images/300_p.jpg"                                                                                
             preview_image="" 
             ' . $imaad . '   
             preroll="true" 
             midroll="true" 
             postroll="true" 
             allow_download="true"
             streamer_path=""
             video_isLive="false" > 
             <title><![CDATA[Welcome]]></title> 
             <!--Optional--> 
             <tagline targeturl=""><![CDATA[<span class="heading">Tagline - </span> <b>Your short description  goes here for Videos.</b> ]]></tagline>        
  </mainvideo>';
}
echo '</playlist>';
?>