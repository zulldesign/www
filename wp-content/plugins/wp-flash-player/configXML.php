<?php
/*
 * Name: WP Flash Player Plugin URI: http://www.apptha.com/category/extension/Wordpress/HD-FLV-Player-Plugin/ Description: Video configxml file. Version: 1.3 Author: Apptha Author URI: http://www.apptha.com License: GPL2
 */
header ( "content-type:text/xml;charset=utf-8" );
require_once (dirname ( __FILE__ ) . '/hdflv-config.php');
global $wpdb;
global $site_url;
// $site_url = get_option('siteurl');
$settingsData = $wpdb->get_row ( "SELECT logopath,player_colors,player_values,player_icons FROM " . $wpdb->prefix . "hdflv_settings" );
$player_colors = unserialize ( $settingsData->player_colors );
$player_values = unserialize ( $settingsData->player_values );
$player_icons = unserialize ( $settingsData->player_icons );
$site_url = content_url ();

$skinPath = APPTHA_HDFLV_BASEURL . 'hdflvplayer' . DS . 'skin/skin_hdflv_white.swf';
$logoPath = $site_url . '/plugins/' . dirname ( plugin_basename ( __FILE__ ) ) . '/hdflvplayer/css/images/';

$playXml = get_site_url () . '/wp-admin/admin-ajax.php?action=playermyextractXML';
$midrollXML = get_site_url () . '/wp-admin/admin-ajax.php?action=playermymidrollXML';
$imaAdsXML = get_site_url () . '/wp-admin/admin-ajax.php?action=playermyimaadsXML';
$langXML = get_site_url () . '/wp-admin/admin-ajax.php?action=playerlanguageXML';
$emailPath = get_site_url () . '/wp-admin/admin-ajax.php?action=playeremail';
$downloadPath = '';
$AdsXML = get_site_url () . '/wp-admin/admin-ajax.php?action=playermyadsXML';

// $playXml = APPTHA_HDFLV_BASEURL . 'myextractXML.php';
// $imaAdsXML = APPTHA_HDFLV_BASEURL . 'myimaadsXML.php';
// $midrollXML = APPTHA_HDFLV_BASEURL . 'mymidrollXML.php';
// $AdsXML = APPTHA_HDFLV_BASEURL . 'myadsXML.php';
// $langXML = APPTHA_HDFLV_BASEURL . '/hdflvplayer/xml/language/language.xml';
// $emailPath = APPTHA_HDFLV_BASEURL . 'email.php';
// $downloadPath = APPTHA_HDFLV_BASEURL . 'download.php';

$playerTimer = $player_icons ['timer'] == 1 ? 'true' : 'false';
$adsSkip = $player_icons ['adsSkip'] == 1 ? 'true' : 'false';
$showTag = $player_icons ['showTag'] == 1 ? 'true' : 'false';
$imageDefault = $player_icons ['imageDefault'] == 1 ? 'true' : 'false';
$progressControl = $player_icons ['progressbar'] == 1 ? 'true' : 'false';
$volumecontrol = $player_icons ['volumevisible'] == 1 ? 'true' : 'false';
$shareIcon = $player_icons ['shareURL'] == 1 ? 'true' : 'false';
$imaAds = $player_icons ['ima_ads'] == 1 ? 'true' : 'false';
$playerZoom = $player_icons ['zoom'] == 1 ? 'true' : 'false';
$playerEmail = $player_icons ['email'] ? 'true' : 'false';
$prerollAds = ($player_icons ['preroll'] == 1) ? 'true' : 'false';
$postrollAds = ($player_icons ['postroll'] == 1) ? 'true' : 'false';
$midroll_ads = ($player_icons ['midroll_ads'] == 1) ? 'true' : 'false';
$playerFullscreen = $player_icons ['fullscreen'] == 1 ? 'true' : 'false';
$playerAutoplay = ($player_icons ['autoplay'] == 1) ? 'true' : 'false';
$playlistAuto = ($player_icons ['playlistauto'] == 1) ? 'true' : 'false';
$hdDefault = ($player_icons ['HD_default'] == 1) ? 'true' : 'false';
$playerDownload = ($player_icons ['download'] == 1) ? 'true' : 'false';
$skinAutohide = ($player_icons ['skin_autohide'] == 1) ? 'true' : 'false';
$embedVisible = ($player_icons ['embed_visible'] == 1) ? 'true' : 'false';
$showPlaylist = ($player_icons ['playlist_visible'] == 1) ? 'true' : 'false';
$playlist_open = ($player_icons ['playlist_open'] == 1) ? 'true' : 'false';
$logotarget = $player_values ['logotarget'];
if (! preg_match ( "~^(?:f|ht)tps?://~i", $logotarget )) {
	$logotarget = "http://" . $logotarget;
}

/* Configuration Start */
echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<config>
        <stagecolor>' . $player_values ['stagecolor'] . '</stagecolor>
        <autoplay>' . $playerAutoplay . '</autoplay>
        <buffer>' . $player_values ['buffer'] . '</buffer>
        <volume>' . $player_values ['volume'] . '</volume>
        <normalscale>' . $player_values ['normalscale'] . '</normalscale>
        <fullscreenscale>' . $player_values ['fullscreenscale'] . '</fullscreenscale>
        <license>' . $player_values ['license'] . '</license>
        <logopath>' . $logoPath . $settingsData->logopath . '</logopath>
        <logoalpha>' . $player_values ['logoalpha'] . '</logoalpha>
        <logoalign>' . $player_values ['logoalign'] . '</logoalign>
        <logo_target>' . $logotarget . '</logo_target>
        <sharepanel_up_BgColor>' . $player_colors ['sharepanel_up_BgColor'] . '</sharepanel_up_BgColor>
        <sharepanel_down_BgColor>' . $player_colors ['sharepanel_down_BgColor'] . '</sharepanel_down_BgColor>
        <sharepaneltextColor>' . $player_colors ['sharepaneltextColor'] . '</sharepaneltextColor>
        <sendButtonColor>' . $player_colors ['sendButtonColor'] . '</sendButtonColor>
        <sendButtonTextColor>' . $player_colors ['sendButtonTextColor'] . '</sendButtonTextColor>
        <textColor>' . $player_colors ['textColor'] . '</textColor>
        <skinBgColor>' . $player_colors ['skinBgColor'] . '</skinBgColor>
        <seek_barColor>' . $player_colors ['seek_barColor'] . '</seek_barColor>
        <buffer_barColor>' . $player_colors ['buffer_barColor'] . '</buffer_barColor>
        <skinIconColor>' . $player_colors ['skinIconColor'] . '</skinIconColor>
        <pro_BgColor>' . $player_colors ['pro_BgColor'] . '</pro_BgColor>
        <playButtonColor>' . $player_colors ['playButtonColor'] . '</playButtonColor>
        <playButtonBgColor>' . $player_colors ['playButtonBgColor'] . '</playButtonBgColor>
        <playerButtonColor>' . $player_colors ['playerButtonColor'] . '</playerButtonColor>
        <playerButtonBgColor>' . $player_colors ['playerButtonBgColor'] . '</playerButtonBgColor>
        <relatedVideoBgColor>' . $player_colors ['relatedVideoBgColor'] . '</relatedVideoBgColor>
        <scroll_barColor>' . $player_colors ['scroll_barColor'] . '</scroll_barColor>
        <scroll_BgColor>' . $player_colors ['scroll_BgColor'] . '</scroll_BgColor>
        <skin>' . $skinPath . '</skin>
        <skin_autohide>' . $skinAutohide . '</skin_autohide>
        <languageXML>' . $langXML . '</languageXML>
        <playlistXML>' . $playXml . '</playlistXML>
        <playlist_open>' . $playlist_open . '</playlist_open>
        <showPlaylist>' . $showPlaylist . '</showPlaylist>
        <HD_default>' . $hdDefault . '</HD_default>
        <shareURL>' . $emailPath . '</shareURL>
        <embed_visible>' . $embedVisible . '</embed_visible>
        <Download>' . $playerDownload . '</Download>
        <downloadUrl>' . $downloadPath . '</downloadUrl>
        <adsSkip>' . $adsSkip . '</adsSkip>
        <adsSkipDuration>' . $player_values ['adsSkipDuration'] . '</adsSkipDuration>
        <relatedVideoView>' . $player_values ['relatedVideoView'] . '</relatedVideoView>
        <imaAds>' . $imaAds . '</imaAds>
        <imaAdsXML>' . $imaAdsXML . '</imaAdsXML>
        <midrollXML>' . $midrollXML . '</midrollXML>
			<preroll_ads>' . $prerollAds . '</preroll_ads>
        <postroll_ads>' . $postrollAds . '</postroll_ads>
			<midroll_ads>' . $midroll_ads . '</midroll_ads>
        <adXML>' . $AdsXML . '</adXML>
        <trackCode>' . $player_values ['google_tracker'] . '</trackCode>
        <showTag>' . $showTag . '</showTag>
        <timer>' . $playerTimer . '</timer>
        <zoomIcon>' . $playerZoom . '</zoomIcon>
        <email>' . $playerEmail . '</email>
        <shareIcon>' . $shareIcon . '</shareIcon>
        <fullscreen>' . $playerFullscreen . '</fullscreen>
        <volumecontrol>' . $volumecontrol . '</volumecontrol>
        <playlist_auto>' . $playlistAuto . '</playlist_auto>
        <progressControl>' . $progressControl . '</progressControl>
        <imageDefault>' . $imageDefault . '</imageDefault>
    </config>';
// Configuration ends
exit ();
?>