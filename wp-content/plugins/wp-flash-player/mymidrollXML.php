<?php
/*
 * Name: Wordpress Video Gallery Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery Description: AdsXML file for player. Version: 2.5 Author: Apptha Author URI: http://www.apptha.com License: GPL2
 */
/* Used to import plugin configuration */
require_once (dirname ( __FILE__ ) . '/hdflv-config.php');
// get the path url from querystring
global $wpdb;
$selectPlaylist = "SELECT * FROM " . $wpdb->prefix . "hdflv_vgads WHERE publish=1";
$themediafiles = $wpdb->get_results ( $selectPlaylist );

ob_clean ();
header ( "content-type: text/xml" );
echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<midrollad begin="5" adinterval="6" adrotate="false" random="false">';
if (count ( $themediafiles ) > 0) {
	foreach ( $themediafiles as $rows ) {
		$admethod = $rows->admethod;
		if ($admethod == 'midroll') { // Allow only if ad is a midroll ad
			$targeturl = $rows->targeturl;
			$clickurl = $rows->clickurl;
			$impressionurl = $rows->impressionurl;
			$description = $rows->description;
			$title = $rows->title;
			
			echo '
                <midroll targeturl="' . $targeturl . '" clickurl="' . $clickurl . '" impressionurl="' . $impressionurl . '">
                <![CDATA[' . $title . '<br>' . $description . '<br>' . $targeturl . ']]>
                </midroll>
                ';
		}
	}
} else {
	echo '
        <midroll targeturl="http://grouponclone.contussupport.com/" clickurl="http://grouponclone.contussupport.com/" impressionurl="http://grouponclone.contussupport.com/">
        <![CDATA[<b><u><font class="heading"  size="15" color="#FF3300">Best Groupon Clone Script</font></u></b><br><font class="midroll" color="#FFFF00">Start your own group buying site like <b> Groupon or Living Social.</b></font><br><font class="webaddress" color="#FFFFFF">http://grouponclone.contussupport.com/</font>]]>
        </midroll>
        ';
}
echo '</midrollad>';
?>