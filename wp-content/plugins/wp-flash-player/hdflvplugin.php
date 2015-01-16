<?php
/*
 * Plugin Name: WP Flash Player
 * Plugin URI: http://www.apptha.com/category/extension/Wordpress/HD-FLV-Player-Plugin/
 * Description: WP Flash Player simplifies the process of adding high definition videos to the Wordpress blog. The plugin efficiently plays your Videos with high quality video and audio output.
 * Version: 1.3
 * Author: Apptha
 * Author URI: http://www.apptha.com
 * License: GPL2
 */
$videoid = 0;
$site_url = get_option ( 'siteurl' );
$siteUrl = content_url ();
define ( 'APPTHA_HDFLV_BASEURL', plugin_dir_url ( __FILE__ ) );
// Define Constants
if (! defined ( 'DS' )) {
	define ( 'DS', '/' );
}
$dir = dirname ( plugin_basename ( __FILE__ ) );
$dirExp = explode ( '/', $dir );
$dirPage = $dirExp [0];
function HDFLV_Parse($content) {
	$content = preg_replace_callback ( '/\[hdpla ([^]]*)\]/i', 'hdflvPlayerReader', $content );
	return $content;
}

// Configxml function
add_action ( 'wp_ajax_playerconfigXML', 'configXML_player_function' );
add_action ( 'wp_ajax_nopriv_playerconfigXML', 'configXML_player_function' );
function configXML_player_function() {
	require_once (dirname ( __FILE__ ) . '/configXML.php');
	die ();
}

// myextractXML function
add_action ( 'wp_ajax_playermyextractXML', 'myextractXML_player_function' );
add_action ( 'wp_ajax_nopriv_playermyextractXML', 'myextractXML_player_function' );
function myextractXML_player_function() {
	require_once (dirname ( __FILE__ ) . '/myextractXML.php');
	die ();
}

// mymidrollXML function
add_action ( 'wp_ajax_playermymidrollXML', 'mymidrollXML_player_function' );
add_action ( 'wp_ajax_nopriv_playermymidrollXML', 'mymidrollXML_player_function' );
function mymidrollXML_player_function() {
	require_once (dirname ( __FILE__ ) . '/mymidrollXML.php');
	die ();
}

// myimaadsXML function
add_action ( 'wp_ajax_playermyimaadsXML', 'myimaadsXML_player_function' );
add_action ( 'wp_ajax_nopriv_playermyimaadsXML', 'myimaadsXML_player_function' );
function myimaadsXML_player_function() {
	require_once (dirname ( __FILE__ ) . '/myimaadsXML.php');
	die ();
}

// languageXML function
add_action ( 'wp_ajax_playerlanguageXML', 'languageXML_player_function' );
add_action ( 'wp_ajax_nopriv_playerlanguageXML', 'languageXML_player_function' );
function languageXML_player_function() {
	require_once (dirname ( __FILE__ ) . '/languageXML.php');
	die ();
}

// email function
add_action ( 'wp_ajax_playeremail', 'email_player_function' );
add_action ( 'wp_ajax_nopriv_playeremail', 'email_player_function' );
function email_player_function() {
	require_once (dirname ( __FILE__ ) . '/email.php');
	die ();
}

// download function
add_action ( 'wp_ajax_playerdownload', 'download_player_function' );
add_action ( 'wp_ajax_nopriv_playerdownload', 'download_player_function' );
function download_player_function() {
	require_once (dirname ( __FILE__ ) . '/download.php');
	die ();
}

// myadsXML function
add_action ( 'wp_ajax_playermyadsXML', 'myadsXML_player_function' );
add_action ( 'wp_ajax_nopriv_playermyadsXML', 'myadsXML_player_function' );
function myadsXML_player_function() {
	require_once (dirname ( __FILE__ ) . '/myadsXML.php');
	die ();
}


// Mobile platform check
function detect_mobile() {
	$_SERVER ['ALL_HTTP'] = isset ( $_SERVER ['ALL_HTTP'] ) ? $_SERVER ['ALL_HTTP'] : '';
	
	$mobile_browser = '0';
	
	$agent = strtolower ( $_SERVER ['HTTP_USER_AGENT'] );
	
	if (preg_match ( '/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', $agent ))
		$mobile_browser ++;
	
	if ((isset ( $_SERVER ['HTTP_ACCEPT'] )) and (strpos ( strtolower ( $_SERVER ['HTTP_ACCEPT'] ), 'application/vnd.wap.xhtml+xml' ) !== false))
		$mobile_browser ++;
	
	if (isset ( $_SERVER ['HTTP_X_WAP_PROFILE'] ))
		$mobile_browser ++;
	
	if (isset ( $_SERVER ['HTTP_PROFILE'] ))
		$mobile_browser ++;
	
	$mobile_ua = substr ( $agent, 0, 4 );
	$mobile_agents = array (
			'w3c ',
			'acs-',
			'alav',
			'alca',
			'amoi',
			'audi',
			'avan',
			'benq',
			'bird',
			'blac',
			'blaz',
			'brew',
			'cell',
			'cldc',
			'cmd-',
			'dang',
			'doco',
			'eric',
			'hipt',
			'inno',
			'ipaq',
			'java',
			'jigs',
			'kddi',
			'keji',
			'leno',
			'lg-c',
			'lg-d',
			'lg-g',
			'lge-',
			'maui',
			'maxo',
			'midp',
			'mits',
			'mmef',
			'mobi',
			'mot-',
			'moto',
			'mwbp',
			'nec-',
			'newt',
			'noki',
			'oper',
			'palm',
			'pana',
			'pant',
			'phil',
			'play',
			'port',
			'prox',
			'qwap',
			'sage',
			'sams',
			'sany',
			'sch-',
			'sec-',
			'send',
			'seri',
			'sgh-',
			'shar',
			'sie-',
			'siem',
			'smal',
			'smar',
			'sony',
			'sph-',
			'symb',
			't-mo',
			'teli',
			'tim-',
			'tosh',
			'tsm-',
			'upg1',
			'upsi',
			'vk-v',
			'voda',
			'wap-',
			'wapa',
			'wapi',
			'wapp',
			'wapr',
			'webc',
			'winw',
			'xda',
			'xda-' 
	);
	
	if (in_array ( $mobile_ua, $mobile_agents ))
		$mobile_browser ++;
	
	if (strpos ( strtolower ( $_SERVER ['ALL_HTTP'] ), 'operamini' ) !== false)
		$mobile_browser ++;
		
		// Pre-final check to reset everything if the user is on Windows
	if (strpos ( $agent, 'windows' ) !== false)
		$mobile_browser = 0;
		
		// But WP7 is also Windows, with a slightly different characteristic
	if (strpos ( $agent, 'windows phone' ) !== false)
		$mobile_browser ++;
	
	if ($mobile_browser > 0)
		return true;
	else
		return false;
}

// Used for Rendering player with the configured informations and save configurations from admin
function hdflvPlayerReader($arguments = array()) {
	global $wpdb, $videoid, $siteUrl, $dirPage, $site_url;
	$output = $videoName = $file = $imagefile = '';
	$playlist_id = $videoid1 = 0;
	$configXML = unserialize ( $wpdb->get_var ( "SELECT player_values FROM " . $wpdb->prefix . "hdflv_settings" ) );
	if (isset ( $arguments ['width'] ) && $arguments ['width'] != '') {
		$width = $arguments ['width'];
	} else {
		$width = $configXML ['width'];
	}
	if (isset ( $arguments ['height'] ) && $arguments ['height'] != '') {
		$height = $arguments ['height'];
	} else {
		$height = $configXML ['height'];
	}
	
	if (isset ( $arguments ['id'] )) {
		$videoid1 = $arguments ['id'];
		$videofiles = $wpdb->get_row ( "SELECT vid,file,hdfile,image,name FROM " . $wpdb->prefix . "hdflv where vid = " . intval ( $arguments ['id'] ) . " AND is_active = 1" );
		if (! empty ( $videofiles )) {
			$file = $videofiles->file;
			$videofile = $videofiles->hdfile;
			$imagefile = $videofiles->image;
			$videoName = $videofiles->name;
		}
	} elseif (isset ( $arguments ['playlistid'] )) {
		$playlist_id = intval ( $arguments ['playlistid'] );
		$playlist = $wpdb->get_row ( "SELECT w.* FROM " . $wpdb->prefix . "hdflv w  INNER JOIN " . $wpdb->prefix . "hdflv_med2play m  WHERE (m.playlist_id = '$playlist_id') AND m.media_id = w.vid AND w.is_active = 1" );
		if ($playlist) {
			$select = " SELECT w.* FROM " . $wpdb->prefix . "hdflv w";
			$select .= " INNER JOIN " . $wpdb->prefix . "hdflv_med2play m";
			$select .= " WHERE (m.playlist_id = '$playlist_id'";
			$select .= " AND m.media_id = w.vid) AND w.is_active = 1 GROUP BY w.vid ";
			$select .= " ORDER BY w.vid " . $playlist->playlist_order . " limit 0,1";
			$videofiles = $wpdb->get_row ( $wpdb->prepare ( $select, NULL ) );
			$playlistName = $wpdb->get_row ( "SELECT w . name,w.vid FROM wp_hdflv w INNER JOIN wp_hdflv_med2play m WHERE (m.playlist_id = '$playlist_id')  AND m.media_id = w.vid AND w.is_active = 1 GROUP BY w.vid ORDER BY w.vid ASC " );
			$playName = $playlistName->name;
			$videoid1 = $playlistName->vid;
			$file = $videofiles->file;
			$videofile = $videofiles->hdfile;
			$imagefile = $videofiles->image;
			// $videoId = $videofiles->vid;
		}
	}
	$output .= '<div style=" position: relative; ">';
	if (! isset ( $arguments ['id'] )) {
		$output .= '<h3 id="default_title' . $videoid . $videoid1 . $playlist_id . '">' . $playName . '</h3>';
	} else {
		$output .= '<h3 id="default_title' . $videoid . $videoid1 . $playlist_id . '">' . $videoName . '</h3>';
	}
	?>

<script type="text/javascript">
        function current_video_<?php echo $videoid.$videoid1.$playlist_id;?>(video_id,d_title){ 
            if(d_title == undefined)
            {
                document.getElementById("default_title<?php echo $videoid.$videoid1.$playlist_id; ?>").innerHTML='';
             }
            else { 
                document.getElementById("default_title<?php echo $videoid.$videoid1.$playlist_id; ?>").innerHTML=d_title;
            }
        }
    </script>
<?php
	
	$play_url = get_option ( 'siteurl' );
	if (isset ( $arguments ['playlistid'] ) && isset ( $arguments ['id'] )) {
		$play_url .= "&amp;pid=" . $arguments ['playlistid'];
		$play_url .= "&amp;vid=" . $arguments ['id'];
	} elseif (isset ( $arguments ['playlistid'] )) {
		$play_url .= "&amp;pid=" . $arguments ['playlistid'];
	} else {
		$play_url .= "&amp;vid=" . $arguments ['id'];
	}
	if (isset ( $arguments ['flashvars'] )) {
		$play_url .= "&amp;" . $arguments ['flashvars'];
	}
	$playerpath = $siteUrl . '/plugins/' . dirname ( plugin_basename ( __FILE__ ) ) . '/hdflvplayer/hdplayer.swf';
	$mobile = detect_mobile ();
	if ($mobile === true) {
		
		if (strpos ( $file, 'youtube' ) > 0) {
			$url = $file;
			$query_string = array ();
			parse_str ( parse_url ( $url, PHP_URL_QUERY ), $query_string );
			$id = $query_string ["v"];
			$videourl = trim ( $id );
			$output .= '<iframe type="text/html" src="http://www.youtube.com/embed/' . $videourl . '">
            </iframe>';
		} else {
			$output .= '<video id="video" src="' . $file . '" poster="' . $imagefile . '" autobuffer controls onerror="failed(event)">
     Html5 Not support This video Format.</video>';
		}
	} else {
		$output .= '<embed id="n' . $videoid . $videoid1 . $playlist_id . $videoid . $videoid1 . $playlist_id . '" wmode="opaque" src="' . $playerpath . '"
               type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true"
               flashvars="baserefWP=' . $play_url . '&amp;player=0
                   &amp;videodata=current_video_' . $videoid . $videoid1 . $playlist_id . '"
               width="' . $width . '" height="' . $height . '"/>  ';
		$details1 = $wpdb->get_row ( "SELECT * FROM " . $wpdb->prefix . "hdflv_googlead WHERE id =1" );
		
		// Display Google Adsense
		if (isset ( $details1->publish ) == '1' && isset ( $details1->showaddc ) == '1') {
			?>
<div>
	<?php
			if ($width > 468) {
				$adstyle = "margin-left: -234px;";
			} else {
				$margin_left = ($width - 100) / 2;
				$adwidth = $width - 100;
				$adstyle = "width:" . $adwidth . "px;margin-left: -" . $margin_left . "px;";
			}
			$output .= '<div id="lightm"  style="' . $adstyle . 'height:76px;position:absolute;display:none;
	 background:none !important;background-position: initial initial !important;
	 background-repeat: initial initial !important;bottom: 65px;left: 50%;">
	<span id="divimgm" ><img alt="close" id="closeimgm" src="' . $siteUrl . '/plugins/' . dirname ( plugin_basename ( __FILE__ ) ) . '/images/close.png"
							 style="z-index: 10000000;width:48px;height:12px;cursor:pointer;top:-12px;"
							 onclick="googleclose();"  /> </span>
	<iframe  height="60" width="' . ($width - 100) . '" scrolling="no"
			 align="middle" id="IFrameName" src="" name="IFrameName" marginheight="0" marginwidth="0"
			 class="iframe_frameborder" ></iframe>
</div>
</div>
	<script type="text/javascript">
		var folder_path = "' . APPTHA_HDFLV_BASEURL . 'googleadsense.php' . '";
		</script>
<script src="' . $siteUrl . '/plugins/' . dirname ( plugin_basename ( __FILE__ ) ) . '/js/googlead.js"
type="text/javascript"></script>';
		}
	}
	$output .= '</div>';
	
	$videoid ++;
	return $output;
}

add_shortcode ( 'hdplay', 'hdflvPlayerReader' ); // Shortcode tag[hdplay]] to be searched in post content

/* Adding page & options */
function hdflvMenuCreate() {
	add_menu_page ( __ ( 'hdflv', 'hdflv' ), __ ( 'Video Player', 'hdflv' ), 'edit_pages', "hdflv", "showMenu", content_url () . "/plugins/" . dirname ( plugin_basename ( __FILE__ ) ) . "/images/apptha.png" );
	add_submenu_page ( "hdflv", __ ( 'HDFLV Videos', 'hdflv' ), __ ( 'Videos', 'hdflv' ), 'edit_pages', "hdflv", "showMenu" );
	add_submenu_page ( "hdflv", "HDFLV Options", "Categories", 'manage_options', "hdflvplaylist", "showMenu" );
	add_submenu_page ( "hdflv", "HDFLV Options", "Video Ads", 'manage_options', "hdflvvideoads", "showMenu" );
	add_submenu_page ( "hdflv", "HDFLV Options", "Google Adsense", 'manage_options', "hdflvadsense", "showMenu" );
	add_submenu_page ( "hdflv", "HDFLV Options", "Settings", 'manage_options', "hdflvplugin.php", "FlashOptions" );
}
function showMenu() { // HDFLV Videos submenu coding in manage.php file
	$page = filter_input ( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
	switch ($page) {
		case 'hdflv' :
			include_once (dirname ( __FILE__ ) . '/functions.php'); // admin functions support to manage.php
			include_once (dirname ( __FILE__ ) . '/manage.php');
			$MediaCenter = new HDFLVManage ();
			break;
		case 'hdflvplaylist' :
			include_once (dirname ( __FILE__ ) . '/functions.php'); // admin functions support to manage.php
			include_once (dirname ( __FILE__ ) . '/manage.php');
			$MediaCenter = new HDFLVManage ();
			$MediaCenter->mode = 'playlist';
			break;
		case 'hdflvvideoads' :
			include_once (dirname ( __FILE__ ) . '/functions.php'); // admin functions support to manage.php
			include_once (dirname ( __FILE__ ) . '/manage.php');
			$MediaCenter = new HDFLVManage ();
			$MediaCenter->mode = 'managevideoads';
			break;
		case 'hdflvadsense' :
			include_once (dirname ( __FILE__ ) . '/functions.php'); // admin functions support to manage.php
			include_once (dirname ( __FILE__ ) . '/manage.php');
			$MediaCenter = new HDFLVManage ();
			$MediaCenter->mode = 'hdflvadsense';
			break;
	}
}

/* function use to set div is hide or show in settings tab */
function getDisplayValueOfDiv($divId) {
	if (! get_option ( $divId )) {
		$display = 'block';
		$viewStymolId = 'ui-icon ui-icon-minusthick';
	} else {
		$display = 'none';
		$viewStymolId = 'ui-icon ui-icon-plusthick';
	}
	return $display;
}

// function end hear

/* Function used to Edit player settings and generate settings form elements */
function FlashOptions() {
	global $wpdb;
	global $siteUrl;
	$message = '';
	$g = array (
			0 => 'Properties' 
	);
	
	$youtubelogshow = filter_input ( INPUT_POST, 'logostatus', FILTER_SANITIZE_STRING );
	if (isset ( $youtubelogshow )) {
		
		update_option ( 'youtubelogoshow', $youtubelogshow );
	}
	
	$options = get_option ( 'HDFLVSettings' );
	
	if ($_POST) {
		$settings = $wpdb->get_col ( "SELECT * FROM " . $wpdb->prefix . "hdflv_settings" );
		$logoUpload = '';
		$settingsResult = $_POST;
		$logopath = $settingsResult ['logopathvalue'];
		$settingsData = array ();
		$playlist_open = $showTag = $autoplay = $skin_autohide = $ima_ads = $embed_visible = $download = $imageDefault = $playlist_visible = $progressbar = $email = $volumevisible = $shareURL = $timer = $fullscreen = $zoom = $HD_default = $playlistauto = 0;
		if (count ( $settings ) > 0) {
			// Get Player values and serialize data
			$player_values = array (
					'buffer' => $settingsResult ['buffer'],
					'width' => $settingsResult ['width'],
					'height' => $settingsResult ['height'],
					'normalscale' => $settingsResult ['normalscale'],
					'fullscreenscale' => $settingsResult ['fullscreenscale'],
					'volume' => $settingsResult ['volume'],
					'stagecolor' => $settingsResult ['stagecolor'],
					'license' => $settingsResult ['license'],
					'logoalpha' => $settingsResult ['logoalpha'],
					'logoalign' => $settingsResult ['logoalign'],
					'logotarget' => $settingsResult ['logotarget'],
					'ima_ads_xml' => $settingsResult ['ima_ads_xml'],
					'adsSkipDuration' => $settingsResult ['adsSkipDuration'],
					'google_tracker' => $settingsResult ['google_tracker'],
					'relatedVideoView' => $settingsResult ['relatedVideoView'] 
			);
			$settingsData ['player_values'] = serialize ( $player_values );
			
			if (isset ( $settingsResult ['playlist_open'] )) {
				$playlist_open = $settingsResult ['playlist_open'];
			}
			// if(isset($settingsResult['showTag'])){
			// $showTag = $settingsResult['showTag'];
			// }
			if (isset ( $settingsResult ['imageDefault'] )) {
				$imageDefault = $settingsResult ['imageDefault'];
			}
			if (isset ( $settingsResult ['playlist_visible'] )) {
				$playlist_visible = $settingsResult ['playlist_visible'];
			}
			if (isset ( $settingsResult ['HD_default'] )) {
				$HD_default = $settingsResult ['HD_default'];
			}
			if (isset ( $settingsResult ['playlistauto'] )) {
				$playlistauto = $settingsResult ['playlistauto'];
			}
			if (isset ( $settingsResult ['autoplay'] )) {
				$autoplay = $settingsResult ['autoplay'];
			}
			if (isset ( $settingsResult ['adsSkip'] )) {
				$adsSkip = $settingsResult ['adsSkip'];
			}
			if (isset ( $settingsResult ['download'] )) {
				$download = $settingsResult ['download'];
			}
			if (isset ( $settingsResult ['embed_visible'] )) {
				$embed_visible = $settingsResult ['embed_visible'];
			}
			if (isset ( $settingsResult ['ima_ads'] )) {
				$ima_ads = $settingsResult ['ima_ads'];
			}
			if (isset ( $settingsResult ['preroll'] )) {
				$preroll = $settingsResult ['preroll'];
			}
			if (isset ( $settingsResult ['postroll'] )) {
				$postroll = $settingsResult ['postroll'];
			}
			if (isset ( $settingsResult ['midroll_ads'] )) {
				$midroll_ads = $settingsResult ['midroll_ads'];
			}
			if (isset ( $settingsResult ['skin_autohide'] )) {
				$skin_autohide = $settingsResult ['skin_autohide'];
			}
			if (isset ( $settingsResult ['fullscreen'] )) {
				$fullscreen = $settingsResult ['fullscreen'];
			}
			if (isset ( $settingsResult ['zoom'] )) {
				$zoom = $settingsResult ['zoom'];
			}
			if (isset ( $settingsResult ['timer'] )) {
				$timer = $settingsResult ['timer'];
			}
			if (isset ( $settingsResult ['shareURL'] )) {
				$shareURL = $settingsResult ['shareURL'];
			}
			if (isset ( $settingsResult ['email'] )) {
				$email = $settingsResult ['email'];
			}
			if (isset ( $settingsResult ['volumevisible'] )) {
				$volumevisible = $settingsResult ['volumevisible'];
			}
			if (isset ( $settingsResult ['progressbar'] )) {
				$progressbar = $settingsResult ['progressbar'];
			}
			
			// Get player icon options and serialize data
			$player_icons = array (
					'autoplay' => $autoplay,
					'adsSkip' => $adsSkip,
					'playlistauto' => $playlistauto,
					'playlist_open' => $playlist_open,
					'skin_autohide' => $skin_autohide,
					'fullscreen' => $fullscreen,
					'zoom' => $zoom,
					'timer' => $timer,
					'shareURL' => $shareURL,
					'email' => $email,
					'volumevisible' => $volumevisible,
					'progressbar' => $progressbar,
					'HD_default' => $HD_default,
					'imageDefault' => $imageDefault,
					'download' => $download,
					'ima_ads' => $ima_ads,
					'preroll' => $preroll,
					'postroll' => $postroll,
					'midroll_ads' => $midroll_ads,
					'showTag' => 0,
					'embed_visible' => $embed_visible,
					'playlist_visible' => $playlist_visible 
			);
			$settingsData ['player_icons'] = serialize ( $player_icons );
			
			// Get Player colors and serialize data
			$player_color = array (
					'sharepanel_up_BgColor' => $settingsResult ['sharepanel_up_BgColor'],
					'sharepanel_down_BgColor' => $settingsResult ['sharepanel_down_BgColor'],
					'sharepaneltextColor' => $settingsResult ['sharepaneltextColor'],
					'sendButtonColor' => $settingsResult ['sendButtonColor'],
					'sendButtonTextColor' => $settingsResult ['sendButtonTextColor'],
					'textColor' => $settingsResult ['textColor'],
					'skinBgColor' => $settingsResult ['skinBgColor'],
					'seek_barColor' => $settingsResult ['seek_barColor'],
					'buffer_barColor' => $settingsResult ['buffer_barColor'],
					'skinIconColor' => $settingsResult ['skinIconColor'],
					'pro_BgColor' => $settingsResult ['pro_BgColor'],
					'playButtonColor' => $settingsResult ['playButtonColor'],
					'playButtonBgColor' => $settingsResult ['playButtonBgColor'],
					'playerButtonColor' => $settingsResult ['playerButtonColor'],
					'playerButtonBgColor' => $settingsResult ['playerButtonBgColor'],
					'relatedVideoBgColor' => $settingsResult ['relatedVideoBgColor'],
					'scroll_barColor' => $settingsResult ['scroll_barColor'],
					'scroll_BgColor' => $settingsResult ['scroll_BgColor'] 
			);
			$settingsData ['player_colors'] = serialize ( $player_color );
			
			if ($_FILES ['logopath'] ["name"] != '') {
				$allowedExtensions = array (
						'jpg',
						'jpeg',
						'png',
						'gif' 
				);
				$logoImage = strtolower ( $_FILES ['logopath'] ["name"] );
				if (in_array ( end ( explode ( ".", $logoImage ) ), $allowedExtensions )) {
					$logoUpload = true;
					$settingsData ['logopath'] = $_FILES ['logopath'] ["name"];
					move_uploaded_file ( $_FILES ["logopath"] ["tmp_name"], dirname ( __FILE__ ) . "/hdflvplayer/css/images/" . $_FILES ["logopath"] ["name"] );
				} else {
					$settingsData ['logopath'] = $logopath;
				}
			} else {
				$settingsData ['logopath'] = $logopath;
			}
			$settingsdataformat = array (
					'%s',
					'%s',
					'%s',
					'%s' 
			);
			$wpdb->update ( $wpdb->prefix . "hdflv_settings", $settingsData, array (
					'settings_id' => 1 
			), $settingsdataformat );
		}
	}
	
	echo $message;
	$site_url = site_url ();
	$content_name = str_replace ( $site_url . '/', '', $siteUrl );
	$ski = str_replace ( 'wp-admin', $content_name, dirname ( $_SERVER ['SCRIPT_FILENAME'] ) ) . '/plugins/' . dirname ( plugin_basename ( __FILE__ ) ) . '/hdflvplayer/skin';
	
	$skins = array ();
	
	// Pull the directories listed in the skins folder to generate the dropdown list with valid skin files
	chdir ( $ski );
	if ($handle = opendir ( $ski )) {
		while ( false !== ($file = readdir ( $handle )) ) {
			if ($file != "." && $file != "..") {
				if (is_dir ( $file )) {
					$skins [] = $file;
				}
			}
		}
		closedir ( $handle );
	}
	$contus = dirname ( plugin_basename ( __FILE__ ) );
	$fetchSettings = $wpdb->get_row ( "SELECT player_icons,player_values,player_colors,logopath FROM " . $wpdb->prefix . "hdflv_settings" );
	$player_colors = unserialize ( $fetchSettings->player_colors );
	$player_icons = unserialize ( $fetchSettings->player_icons );
	$player_values = unserialize ( $fetchSettings->player_values );
	?>
				<script type="text/javascript">
				var content_name = '<?php echo str_replace(site_url().'/', '',content_url()); ?>';
				</script>
	<!--HTML design for admin settings -->
	<link rel="stylesheet"
		href="<?php echo $siteUrl ?>/plugins/<?php echo dirname(plugin_basename(__FILE__)) ?>/hdflvplayer/css/jquery.ui.all.css">
	<script type="text/javascript"
		src="<?php echo $siteUrl ?>/plugins/<?php echo $contus ?>/js/hdflvscript.js"></script>


	<h2 style="margin-bottom: 1%;" class="nav-tab-wrapper">
		<a id="hdflv" href="?page=hdflv" class="nav-tab "> Manage Videos</a> <a
			id="video" href="?page=hdflv&mode=video" class="nav-tab"> Add Video</a>
		<a id="playlist" href="?page=hdflvplaylist" class="nav-tab">Categories</a>
		<a id="hdflvvideoads" href="?page=hdflvvideoads&mode=managevideoads"
			class="nav-tab">Video Ads</a> <a id="videoads"
			href="?page=hdflvvideoads&mode=videoads" class="nav-tab">Add Video
			Ads</a> <a id="videoads" href="?page=hdflvadsense&mode=hdflvadsense"
			class="nav-tab">Google Adsense</a> <a id="settings"
			href="?page=hdflvplugin.php" class="nav-tab">Settings</a>
	</h2>
	<script type="text/javascript">
        document.getElementById("settings").className = 'nav-tab nav-tab-active';
        function enablerelateditems(val) {
                        if(val == 'side') {
                                document.getElementById('related_scroll_barColor').style.display = '';
                                document.getElementById('related_scroll_barbgColor').style.display = '';
                                document.getElementById('related_bgColor').style.display = '';
                                document.getElementById('related_playlist_open').style.display = '';
                        } else{
                                document.getElementById('related_scroll_barColor').style.display = 'none';
                                document.getElementById('related_scroll_barbgColor').style.display = 'none';
                                document.getElementById('related_bgColor').style.display = 'none';
                                document.getElementById('related_playlist_open').style.display = 'none';
                        }
                }
     </script>
	<div class="wrap">
		<h2>HD FLV Player Options</h2>

		<form method="post" enctype="multipart/form-data"
			action="admin.php?page=hdflvplugin.php">

			<input type="hidden" name="app_wp_token" id="app_wp_token"
				value="<?php echo $_SESSION['app_wp_token']; ?>" /> <input
				type="hidden" name="plugin_name" id="plugin_name"
				value="<?php echo $contus; ?>" />
			<p class='submit'>
				<input class='button-primary' type='submit' value='Update Options'>
			</p>
			<div class="column column1" style="float: left;">
            <?php
	$showOrHide = getDisplayValueOfDiv ( 'LicenseContentHide' );
	if ($showOrHide == 'block') {
		$className = 'ui-icon ui-icon-minusthick';
	} else {
		$className = 'ui-icon ui-icon-plusthick';
	}
	?>
            <div
					class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
					<div class="portlet-header ui-widget-header ui-corner-all">
						<span id='LicenseSpan'
							onclick="hideContentDives('LicenseContentHide','LicenseSpan')"
							class="<?php echo $className; ?>"></span> License Configuration
					</div>
					<div style="display: <?php echo $showOrHide; ?>;" class="portlet-content" id='LicenseContentHide' >
						<table class="form-table">
							<tr>
								<th scope='row'>License Key</th>
								<td><input type='text' name="license"
									value="<?php echo $player_values['license']; ?>" size=45 />
                                <?php if ($player_values['license'] == '' || $player_values['license'] == '0') {?>
                                   <a
									href="http://www.apptha.com/shop/checkout/cart/add/product/20"
									target="_blank"
									style="margin-top: 10px; display: inline-block;"> <img
										src="<?php  echo APPTHA_HDFLV_BASEURL; ?>/images/buy.gif"
										alt="Buy" />
								</a>
                                <?php } ?>
                           </td>
							</tr>
						</table>
					</div>
				</div>
            <?php
	$showOrHide = getDisplayValueOfDiv ( 'LogoContentHide' );
	if ($showOrHide == 'block') {
		$className = 'ui-icon ui-icon-minusthick';
	} else {
		$className = 'ui-icon ui-icon-plusthick';
	}
	?>
                                   <div
					class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
					<div class="portlet-header ui-widget-header ui-corner-all">
						<span id='LogoSpan'
							onclick="hideContentDives('LogoContentHide','LogoSpan')"
							class="<?php echo $className; ?>"></span>Logo Configuration
						(Applicable Only For Licensed Player)

					</div>
					<div style="display: <?php echo $showOrHide; ?>;" class="portlet-content" id='LogoContentHide' >


						<table class="form-table">
							<tr>
								<th scope='row'>Logo Path</th>
								<td><input type='file' name="logopath" value="" size=35 /> <?php echo $fetchSettings->logopath?>
                                               <input type='hidden'
									name="logopathvalue"
									value="<?php echo $fetchSettings->logopath ?>" /></td>
							</tr>
							<tr>
								<th scope='row'>Logo Target</th>
								<td><input type='text' name="logotarget"
									value="<?php echo $player_values['logotarget']; ?>" size=45 /></td>
							</tr>
							<tr>
								<th scope='row'>Logo Align</th>
								<td><select name="logoalign" style="width: 150px;">
										<option <?php if ($player_values['logoalign'] == 'TL') { ?>
											selected="selected" <?php } ?> value="TL">Top Left</option>
										<option <?php if ($$player_values['logoalign'] == 'TR') { ?>
											selected="selected" <?php } ?> value="TR">Top Right</option>
										<option <?php if ($player_values['logoalign'] == 'BL') { ?>
											selected="selected" <?php } ?> value="BL">Bottom Left</option>
										<option <?php if ($player_values['logoalign'] == 'BR') { ?>
											selected="selected" <?php } ?> value="BR">Bottom Right</option>
								</select></td>
							</tr>
							<tr>
								<th scope='row'>Logo Alpha</th>
								<td><input type='text' name="logoalpha"
									value="<?php echo $player_values['logoalpha']; ?>" size=45 /></td>
							</tr>
						</table>
					</div>
				</div>

<?php
	$showOrHide = getDisplayValueOfDiv ( 'displayContentHide' );
	if ($showOrHide == 'block') {
		$className = 'ui-icon ui-icon-minusthick';
	} else {
		$className = 'ui-icon ui-icon-plusthick';
	}
	?>


            <div
					class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
					<div class="portlet-header ui-widget-header ui-corner-all">
						<span id='displaySpan'
							onclick="hideContentDives('displayContentHide','displaySpan')"
							class="<?php echo $className; ?>"></span>Display Configuration
					</div>

					<div style="display:<?php echo $showOrHide; ?> ;" class="portlet-content" id='displayContentHide' >
						<table class="form-table">
							<tr>
								<th scope='row'>Normal Scale</th>
								<td><select name="normalscale" style="width: 150px;">
										<option value="0"
											<?php if ($player_values['normalscale'] == 0) { ?>
											selected="selected" <?php } ?>>Aspect Ratio</option>
										<option value="1"
											<?php if ($player_values['normalscale'] == 1) { ?>
											selected="selected" <?php } ?>>Original Screen</option>
										<option value="2"
											<?php if ($player_values['normalscale'] == 2) { ?>
											selected="selected" <?php } ?>>Fit To Screen</option>
								</select></td>
							</tr>
							<tr>
								<th scope='row'>Full Screen Scale</th>
								<td><select name="fullscreenscale" style="width: 150px;">
										<option value="0"
											<?php if ($player_values['fullscreenscale'] == 0) { ?>
											selected="selected" <?php } ?>>Aspect Ratio</option>
										<option value="1"
											<?php if ($player_values['fullscreenscale'] == 1) { ?>
											selected="selected" <?php } ?>>Original Screen</option>
										<option value="2"
											<?php if ($player_values['fullscreenscale'] == 2) { ?>
											selected="selected" <?php } ?>>Fit To Screen</option>
								</select></td>
							</tr>
							<tr>
								<th scope='row'>Player Width</th>
								<td><input type='text' name="width"
									value="<?php echo $player_values['width'] ?>" size=45 /><span
									style="font-size: 10px; padding-left: 5px; display: block;">Note:
										Recommended width is 400. If you want use player width less
										than 400, please disable few buttons in "Skin Configuration"</span></td>
							</tr>
							<tr>
								<th scope='row'>Player Height</th>
								<td><input type='text' name="height"
									value="<?php echo $player_values['height'] ?>" size=45 /></td>
							</tr>
							<tr>
								<th scope='row'>Stage Color</th>
								<td><input type='text' name="stagecolor"
									value="<?php echo $player_values['stagecolor']; ?>" size=45 />
									<span
									style="font-size: 9px; padding-left: 5px; display: block;">Ex :
										0xFFFFFF </span></td>
							</tr>
						</table>
					</div>
				</div>

                <?php
	$showOrHide = getDisplayValueOfDiv ( 'ColorContentHide' );
	if ($showOrHide == 'block') {
		$className = 'ui-icon ui-icon-minusthick';
	} else {
		$className = 'ui-icon ui-icon-plusthick';
	}
	?>
                                       <div
					class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
					<div class="portlet-header ui-widget-header ui-corner-all">
						<span id='ColorSpan'
							onclick="hideContentDives('ColorContentHide','ColorSpan')"
							class="<?php echo $className; ?>"></span>Player Color
						Configuration Ex : 0xdddddd
					</div>
					<div style="display: <?php echo $showOrHide; ?>;" class="portlet-content" id='ColorContentHide' >
						<table class="form-table">
							<!-- Share Popup Header color -->
							<tr>
								<th scope='row'><?php _e("Share Popup Header Color", "video_gallery"); ?></th>
								<td><input type='text' name="sharepanel_up_BgColor"
									value="<?php echo $player_colors['sharepanel_up_BgColor']; ?>"
									size=45 /></td>
							</tr>
							<!-- Share Popup Background color -->
							<tr>
								<th scope='row'><?php _e("Share Popup Background Color", "video_gallery"); ?></th>
								<td><input type='text' name="sharepanel_down_BgColor"
									value="<?php echo $player_colors['sharepanel_down_BgColor']; ?>"
									size=45 /></td>
							</tr>
							<!-- Share Popup Text color -->
							<tr>
								<th scope='row'><?php _e("Share Popup Text Color", "video_gallery"); ?></th>
								<td><input type='text' name="sharepaneltextColor"
									value="<?php echo $player_colors['sharepaneltextColor']; ?>"
									size=45 /></td>
							</tr>
							<!-- Send Button Color -->
							<tr>
								<th scope='row'><?php _e("Send Button Color", "video_gallery"); ?></th>
								<td><input type='text' name="sendButtonColor"
									value="<?php echo $player_colors['sendButtonColor']; ?>"
									size=45 /></td>
							</tr>
							<!-- Send Button Text Color -->
							<tr>
								<th scope='row'><?php _e("Send Button Text Color", "video_gallery"); ?></th>
								<td><input type='text' name="sendButtonTextColor"
									value="<?php echo $player_colors['sendButtonTextColor']; ?>"
									size=45 /></td>
							</tr>
							<!-- Player Text Color -->
							<tr>
								<th scope='row'><?php _e("Player Text Color", "video_gallery"); ?></th>
								<td><input type='text' name="textColor"
									value="<?php echo $player_colors['textColor']; ?>" size=45 /></td>
							</tr>
							<!-- Skin Background Color -->
							<tr>
								<th scope='row'><?php _e("Skin Background Color", "video_gallery"); ?></th>
								<td><input type='text' name="skinBgColor"
									value="<?php echo $player_colors['skinBgColor']; ?>" size=45 />
								</td>
							</tr>
							<!-- Seek Bar Color -->
							<tr>
								<th scope='row'><?php _e("Seek Bar Color", "video_gallery"); ?></th>
								<td><input type='text' name="seek_barColor"
									value="<?php echo $player_colors['seek_barColor']; ?>" size=45 />
								</td>
							</tr>
							<!-- Buffer Bar Color -->
							<tr>
								<th scope='row'><?php _e("Buffer Bar Color", "video_gallery"); ?></th>
								<td><input type='text' name="buffer_barColor"
									value="<?php echo $player_colors['buffer_barColor']; ?>"
									size=45 /></td>
							</tr>
							<!-- Skin Icons Color -->
							<tr>
								<th scope='row'><?php _e("Skin Icons Color", "video_gallery"); ?></th>
								<td><input type='text' name="skinIconColor"
									value="<?php echo $player_colors['skinIconColor']; ?>" size=45 />
								</td>
							</tr>
							<!-- Progress Bar Background Color -->
							<tr>
								<th scope='row'><?php _e("Progress Bar Background Color", "video_gallery"); ?></th>
								<td><input type='text' name="pro_BgColor"
									value="<?php echo $player_colors['pro_BgColor']; ?>" size=45 />
								</td>
							</tr>
							<!-- Play Button Color -->
							<tr>
								<th scope='row'><?php _e("Play Button Color", "video_gallery"); ?></th>
								<td><input type='text' name="playButtonColor"
									value="<?php echo $player_colors['playButtonColor']; ?>"
									size=45 /></td>
							</tr>
							<!-- Play Button Background Color -->
							<tr>
								<th scope='row'><?php _e("Play Button Background Color", "video_gallery"); ?></th>
								<td><input type='text' name="playButtonBgColor"
									value="<?php echo $player_colors['playButtonBgColor']; ?>"
									size=45 /></td>
							</tr>
							<!-- Player Buttons Color -->
							<tr>
								<th scope='row'><?php _e("Player Buttons Color", "video_gallery"); ?></th>
								<td><input type='text' name="playerButtonColor"
									value="<?php echo $player_colors['playerButtonColor']; ?>"
									size=45 /></td>
							</tr>
							<!-- Player Buttons Background Color -->
							<tr>
								<th scope='row'><?php _e("Player Buttons Background Color", "video_gallery"); ?></th>
								<td><input type='text' name="playerButtonBgColor"
									value="<?php echo $player_colors['playerButtonBgColor']; ?>"
									size=45 /></td>
							</tr>
							<!-- Related Videos Background Color -->
							<tr id="related_bgColor" style="display: none;">
								<th scope='row'><?php _e("Related Videos Background Color", "video_gallery"); ?></th>
								<td><input type='text' name="relatedVideoBgColor"
									value="<?php echo $player_colors['relatedVideoBgColor']; ?>"
									size=45 /></td>
							</tr>
							<!-- Related Videos Scroll Bar Color -->
							<tr id="related_scroll_barColor" style="display: none;">
								<th scope='row'><?php _e("Related Videos Scroll Bar Color", "video_gallery"); ?></th>
								<td><input type='text' name="scroll_barColor"
									value="<?php echo $player_colors['scroll_barColor']; ?>"
									size=45 /></td>
							</tr>
							<!-- Related Videos Scroll Bar Background Color -->
							<tr id="related_scroll_barbgColor" style="display: none;">
								<th scope='row'><?php _e("Related Videos Scroll Bar Background Color", "video_gallery"); ?></th>
								<td><input type='text' name="scroll_BgColor"
									value="<?php echo $player_colors['scroll_BgColor']; ?>" size=45 />
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<!-- <div class="column" >lelf side div is end -->
			<div class="column column2">

 <?php
	$showOrHide = getDisplayValueOfDiv ( 'PlaylistContentHide' );
	if ($showOrHide == 'block') {
		$className = 'ui-icon ui-icon-minusthick';
	} else {
		$className = 'ui-icon ui-icon-plusthick';
	}
	?>
                                       <div
					class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
					<div class="portlet-header ui-widget-header ui-corner-all">
						<span id='PlaylistSpan'
							onclick="hideContentDives('PlaylistContentHide','PlaylistSpan')"
							class="<?php echo $className; ?>"></span>Playlist Configuration
					</div>
					<div style="display: <?php echo $showOrHide; ?>;" class="portlet-content" id='PlaylistContentHide' >
						<table class="form-table">
							<tr>
								<th scope='row'>Playlist Display</th>
								<td><input type='checkbox' class='check' name="playlist_visible"
									<?php if ($player_icons['playlist_visible'] == 1) { ?> checked
									<?php } ?> value="1" size=45 /></td>
							</tr>
							<tr id="related_playlist_open" style="display: none;">
								<th scope='row'><?php _e("Playlist Open", "video_gallery"); ?></th>
								<td><input type='checkbox' class='check' name="playlist_open"
									<?php if (isset($player_icons['playlist_open']) && $player_icons['playlist_open'] == 1) { ?>
									checked <?php } ?> value="1" /></td>

							</tr>
							<tr>
								<th scope='row'>HD Default</th>
								<td><input type='checkbox' class='check' name="HD_default"
									<?php if ($player_icons['HD_default'] == 1) { ?> checked
									<?php } ?> value="1" size=45 /></td>
							</tr>
							<tr>
								<th scope='row'>Playlist Autoplay</th>
								<td><input type='checkbox' class='check'
									<?php if ($player_icons['playlistauto'] == 1) { ?> checked
									<?php } ?> name="playlistauto" value="1" size=45 /></td>
							</tr>
							<tr>
								<th scope='row'><?php _e("Related Video View", "video_gallery"); ?></th>
								<td><select name="relatedVideoView"
									onchange="enablerelateditems(this.value)">
										<option value="side"
											<?php
	
if ($player_values ['relatedVideoView'] == 'side')
		echo "selected=selected";
	?>>side</option>
										<option value="center"
											<?php
	
if ($player_values ['relatedVideoView'] == 'center')
		echo "selected=selected";
	?>>center</option>
								</select></td>

							</tr>
						</table>
					</div>
				</div>



<?php
	$showOrHide = getDisplayValueOfDiv ( 'VideoContentHide' );
	if ($showOrHide == 'block') {
		$className = 'ui-icon ui-icon-minusthick';
	} else {
		$className = 'ui-icon ui-icon-plusthick';
	}
	?>
                                       <div
					class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
					<div class="portlet-header ui-widget-header ui-corner-all">
						<span id='VideoSpan'
							onclick="hideContentDives('VideoContentHide','VideoSpan')"
							class="<?php echo $className; ?>"></span>Video Configuration
					</div>
					<div style="display: <?php echo $showOrHide; ?>;" class="portlet-content" id='VideoContentHide' >


						<table class="form-table">
							<tr>
								<th scope='row'>Auto Play</th>
								<td><input type='checkbox' class='check' name="autoplay"
									<?php if ($player_icons['autoplay'] == 1) { ?> checked
									<?php } ?> value="1" size=45 /></td>
							</tr>
							<tr>
								<th scope='row'>Download</th>
								<td><input type='checkbox' class='check' name="download"
									<?php
	
if ($player_icons ['download'] == 1) {
		?>
									checked <?php } ?> value="1" size=45 />&nbsp Note: Not
									supported for Custom URL and YouTube videos</td>
							</tr>
							<tr>
								<th scope='row'>Buffer</th>
								<td><input type='text' name="buffer"
									value="<?php echo $player_values['buffer'] ?>" size=45 /></td>
							</tr>
							<tr>
								<th scope='row'>Volume</th>
								<td><input type='text' name="volume"
									value="<?php echo $player_values['volume']; ?>" size=45 /></td>
							</tr>
							<tr>
								<th scope='row'>Embed Visible</th>
								<td><input type='checkbox' class='check' name="embed_visible"
									<?php if ($player_icons['embed_visible'] == 1) { ?> checked
									<?php } ?> value="1" size=45 /></td>
							</tr>

						</table>
					</div>
				</div>




                                <?php
	$showOrHide = getDisplayValueOfDiv ( 'GeneralContentHide' );
	if ($showOrHide == 'block') {
		$className = 'ui-icon ui-icon-minusthick';
	} else {
		$className = 'ui-icon ui-icon-plusthick';
	}
	?>
                                       <div
					class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
					<div class="portlet-header ui-widget-header ui-corner-all">
						<span id='GeneralSpan'
							onclick="hideContentDives('GeneralContentHide','GeneralSpan')"
							class="<?php echo $className; ?>"></span>General Configuration
					</div>
					<div style="display: <?php echo $showOrHide; ?>;" class="portlet-content" id='GeneralContentHide' >

						<table class="form-table">

							<!-- Ad Skip -->
							<tr>
								<th scope='row'><?php _e("Ad Skip", "video_gallery"); ?></th>
								<td><input name="adsSkip" id="adsSkip" type='radio' value="1"
									<?php
	
if ($player_icons ['adsSkip'] == 1)
		echo 'checked';
	?> /><label><?php _e("Enable", "video_gallery"); ?></label>
									<input name="adsSkip" id="adsSkip" type='radio' value="0"
									<?php
	
if ($player_icons ['adsSkip'] == 0)
		echo 'checked';
	?> /><label><?php _e("Disable", "video_gallery"); ?></label>
								</td>
							</tr>
							<!--Ad Skip Duration-->
							<tr>
								<th scope='row'><?php _e("Ad Skip Duration", "video_gallery"); ?></th>
								<td><input type='text' name="adsSkipDuration"
									value="<?php echo $player_values['adsSkipDuration'] ?>" size=45 />
								</td>
							</tr>
							<tr>
								<th scope='row'>Preroll Ads</th>
								<td><input type='checkbox' class='check'
									<?php
	
if ($player_icons ['preroll'] == 1) {
		?> checked
									<?php } ?> name="preroll" value="1" size=45 /></td>
							</tr>
							<tr>
								<th scope='row'>Postroll Ads</th>
								<td><input type='checkbox' class='check'
									<?php
	
if ($player_icons ['postroll'] == 1) {
		?> checked
									<?php } ?> name="postroll" value="1" size=45 /></td>
							</tr>
							<tr>
								<th scope='row'>Midroll Ads</th>
								<td><input type='checkbox' class='check'
									<?php
	
if ($player_icons ['midroll_ads'] == 1) {
		?> checked
									<?php } ?> name="midroll_ads" value="1" size=45 /></td>
							</tr>
							<tr>
								<th scope='row'>IMA Ads</th>
								<td><input type='checkbox' class='check'
									<?php
	
if ($player_icons ['ima_ads'] == 1) {
		?> checked
									<?php } ?> name="ima_ads" value="1" size=45 /></td>
							</tr>

							<!--
                        <tr>
                            <th scope='row'>IMA Ads XML</th>
                            <td><input type='textbox' value="<?php echo $player_values['ima_ads_xml']; ?>" name="ima_ads_xml"  /></td>
                        </tr>-->

							<tr>
								<th scope='row'>Google Tracker</th>
								<td><input type='textbox'
									value="<?php echo $player_values['google_tracker']; ?>"
									name="google_tracker" /></td>
							</tr>
						</table>
					</div>
				</div>


<?php
	$showOrHide = getDisplayValueOfDiv ( 'SkinContentHide' );
	if ($showOrHide == 'block') {
		$className = 'ui-icon ui-icon-minusthick';
	} else {
		$className = 'ui-icon ui-icon-plusthick';
	}
	?>
            <div
					class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
					<div class="portlet-header ui-widget-header ui-corner-all">
						<span id='SkinSpan'
							onclick="hideContentDives('SkinContentHide','SkinSpan')"
							class="<?php echo $className; ?>"></span>Skin Configuration
					</div>
					<div style="display: <?php echo $showOrHide; ?>;" class="portlet-content" id='SkinContentHide' >
						<table class="form-table">
							<tr>
								<th scope='row'>Display Timer</th>
								<td><input type='checkbox' class='check'
									<?php if ($player_icons['timer'] == 1) { ?> checked <?php } ?>
									name="timer" value="1" size=45 /></td>
							</tr>
							<tr>
								<th scope='row'>Display Zoom</th>
								<td><input type='checkbox' class='check'
									<?php if ($player_icons['zoom'] == 1) { ?> checked <?php } ?>
									name="zoom" value="1" size=45 /></td>
							</tr>
							<!-- Display Email Icon-->
							<tr>
								<th scope='row'><?php _e("Display Email", "video_gallery"); ?></th>
								<td><input type='checkbox' class='check' name="email"
									<?php if ($player_icons['email'] == 1) { ?> checked <?php } ?>
									value="1" /></td>
							</tr>
							<!-- Display Share Icon-->
							<tr>
								<th scope='row'>Display Share</th>
								<td><input type='checkbox' class='check'
									<?php if ($player_icons['shareURL'] == 1) { ?> checked
									<?php } ?> name="shareURL" value="1" size=45 /></td>
							</tr>
							<!-- Display Volume Icon-->
							<tr>
								<th scope='row'><?php _e("Display Volume", "video_gallery"); ?></th>
								<td><input type='checkbox' class='check' name="volumevisible"
									<?php if ($player_icons['volumevisible'] == 1) { ?> checked
									<?php } ?> value="1" /></td>
							</tr>
							<!-- Display Progress Bar-->
							<tr>
								<th scope='row'><?php _e("Display Progress Bar", "video_gallery"); ?></th>
								<td><input type='checkbox' class='check' name="progressbar"
									<?php if ($player_icons['progressbar'] == 1) { ?> checked
									<?php } ?> value="1" /></td>
							</tr>
							<tr>
								<th scope='row'>Display Full Screen</th>
								<td><input type='checkbox' class='check'
									<?php if ($player_icons['fullscreen'] == 1) { ?> checked
									<?php } ?> name="fullscreen" value="1" size=45 /></td>
							</tr>
							<tr>
								<th scope='row'>Skin Autohide</th>
								<td><input type='checkbox' class='check'
									<?php
	
if ($player_icons ['skin_autohide'] == 1) {
		?> checked
									<?php } ?> name="skin_autohide" value="1" size=45 /></td>
							</tr>
							<!--  Display Description on the player-->
							<!--                        <tr>
                           <th scope='row'><?php _e("Show Description", "video_gallery"); ?></th>
                           <td><input type='checkbox' class='check' name="showTag" <?php if ($player_icons['showTag'] == 1) { ?> checked <?php } ?> value="1" size=45  /></td>
                       </tr>-->
							<!--  Display Default Image-->
							<tr>
								<th scope='row'><?php _e("Display Default Image", "video_gallery"); ?></th>
								<td><input type='checkbox' class='check' name="imageDefault"
									<?php if ($player_icons['imageDefault'] == 1) { ?> checked
									<?php } ?> value="1" size=45 /></td>
							</tr>
						</table>
					</div>
				</div>

			</div>
			<div class="clear"></div>
			<p class='submit'>
				<input class='button-primary' type='submit' value='Update Options'>
			</p>

		</form>
		<script type="text/javascript">
        <?php
	
if (isset ( $player_values ['relatedVideoView'] ) && $player_values ['relatedVideoView'] == 'side') {
		?>
        enablerelateditems('side');
<?php
	} elseif (isset ( $player_values ['relatedVideoView'] ) && $player_values ['relatedVideoView'] == 'center') {
		?>
        enablerelateditems('center');
<?php
	}
	?>
    </script>
	</div>

	<!-- End of settings design-->
<?php
}
function HDFLV_head() {
	global $siteUrl;
}

add_action ( 'wp_head', 'HDFLV_head' );

/* Loading default settings of player */
                                   /* Function to uninstall player plugin */

                                   function contusHdDeinstall() {
	global $wpdb, $wp_version;
	
	hdflvDropTables ();
}

/* Function to deactivate player plugin */
function contusHdDeactive() {
	delete_option ( 'HDFLVSettings' );
}
function hdflv_cssJs() { // function for adding css and javascript files starts
	wp_register_style ( 'hdflv_css', plugins_url ( 'css/hdflvsettings.css', __FILE__ ) );
	wp_enqueue_style ( 'hdflv_css' );
}

add_action ( 'admin_init', 'hdflv_cssJs' );
require_once (dirname ( __FILE__ ) . '/install.php');
register_activation_hook ( __FILE__, 'contusHdInstalling' ); // activation
$plugin_main_file = $dirPage . "/hdflvplugin.php";
if (isset ( $_GET ['action'] ) && $_GET ['action'] == "activate-plugin" && $_GET ['plugin'] == $plugin_main_file) {
	// define default collation for database
	if (version_compare ( mysql_get_server_info (), '4.1.0', '>=' )) {
		if (! empty ( $wpdb->charset ))
			$charset_collate = "CHARACTER SET $wpdb->charset";
		if (! empty ( $wpdb->collate ))
			$charset_collate .= " COLLATE $wpdb->collate";
	}
	$player_colors = $player_values = $player_icons = $errorMsg = '';
	$tableSettings = $wpdb->prefix . 'hdflv_settings';
	$player_colors = upadteColumnIfNotExists ( $errorMsg, "$tableSettings", "player_colors", "longtext $charset_collate NOT NULL" );
	$player_values = upadteColumnIfNotExists ( $errorMsg, "$tableSettings", "player_values", "longtext $charset_collate NOT NULL" );
	$player_icons = upadteColumnIfNotExists ( $errorMsg, "$tableSettings", "player_icons", "longtext $charset_collate NOT NULL" );
}
register_uninstall_hook ( __FILE__, 'contusHdDeinstall' ); // delete plugin .
register_deactivation_hook ( __FILE__, 'contusHdDeactive' ); // deactivation plugin
                                                          // CONTENT FILTER
add_filter ( 'the_content', 'HDFLV_Parse' );

// OPTIONS MENU
add_action ( 'admin_menu', 'hdflvMenuCreate' );
?>