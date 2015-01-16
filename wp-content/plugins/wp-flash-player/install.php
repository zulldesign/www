<?php
/*
  Name: WP Flash Player
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/HD-FLV-Player-Plugin/
  Description: Installation file.
  Version: 1.3
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */

/* * ************************************************************* */
/* Install routine for hdflvplayer
  /*************************************************************** */

## Function to alter table while upgrade plugin
function upadteColumnIfNotExists($errorMsg, $table, $column, $attributes = "INT( 11 ) NOT NULL DEFAULT '0'") {
    global $wpdb;
    $columnExists           = false;
    $upgra                  = 'upgrade';
    $query                  = 'SHOW COLUMNS FROM ' . $table;


    if (!$result = $wpdb->query($query)) {
        return false;
    }
    $columnData             = $wpdb->get_results($query);
    foreach ($columnData as $valueColumn) {
        if ($valueColumn->Field == $column) {
            $columnExists   = true;
            break;
        }
    }
    ## Alter table if column not exist
    if (!$columnExists) {
        $query              = "ALTER TABLE `$table` ADD `$column` $attributes";
        if (!$wpdb->query($query)) { 
            return false;
        } else {
            if($column=='player_icons'){
        $query1 = 'SELECT `autoplay`,`embed_visible`,`playlistauto`,`skin_autohide`,`fullscreen`,`zoom`,`shareURL`,`timer`, `HD_default`,`email`,`download` FROM `'.$table.'`';
            $wpdb->query($query1);
            $settingsResult = $wpdb->get_row($query1);
             $player_icons                   = array(
            'autoplay'                  => $settingsResult->autoplay,
            'playlistauto'              => $settingsResult->playlistauto,
            'playlist_open'             => 1,
            'skin_autohide'             => $settingsResult->skin_autohide,
            'fullscreen'                => $settingsResult->fullscreen,
            'zoom'                      => $settingsResult->zoom,
            'timer'                     => $settingsResult->timer,
            'shareURL'                  => $settingsResult->shareURL,
            'email'                     => $settingsResult->email,
            'volumevisible'             => 1,
            'progressbar'               => 1,
            'HD_default'                => $settingsResult->HD_default,
            'imageDefault'              => 1,
            'download'                  => $settingsResult->download,
            'ima_ads'                   => 0,
            'adsSkip'                   => 1,
            'showTag'                   => 0,
            'embed_visible'             => $settingsResult->embed_visible,
            'playlist_visible'          => 1
        );
        $player_icons = serialize($player_icons);
        $query = 'UPDATE '.$table.' SET player_icons=\'' .$player_icons . '\'';
        $wpdb->query($query);
            } else if($column=='player_values'){
        $query1 = 'SELECT `buffer`,`width`,`height`,`normalscale`, `fullscreenscale`,`volume`,`stagecolor`,`license`,`logoalpha`,`logoalign`,`logo_target`, `ima_ads_xml`,`google_tracker` FROM `'.$table.'`';
            $wpdb->query($query1);
            $settingsResult = $wpdb->get_row($query1);
             $player_values                   = array(
            'buffer'                        => $settingsResult->buffer,
            'width'                         => $settingsResult->width,
            'height'                        => $settingsResult->height,
            'normalscale'                   => $settingsResult->normalscale,
            'fullscreenscale'               => $settingsResult->fullscreenscale,
            'volume'                        => $settingsResult->volume,
            'stagecolor'                    => $settingsResult->stagecolor,
            'license'                       => $settingsResult->license,
            'logoalpha'                     => $settingsResult->logoalpha,
             'logoalign'                    => $settingsResult->logoalign,
             'logotarget'                   => $settingsResult->logo_target,
             'ima_ads_xml'                  => $settingsResult->ima_ads_xml,
             'relatedVideoView'             => 'center',
             'adsSkipDuration'              => '',
            'google_tracker'                => $settingsResult->google_tracker
        );
        $player_values = serialize($player_values);
        $query = 'UPDATE '.$table.' SET player_values=\'' .$player_values . '\'';
        $wpdb->query($query);
            }
        }
        $errorMsg           = 'notexistcreated';
    }
    return true;
}
function contusHdInstalling() {
    global $wpdb;

    // set tablename
    $tablehdflv = $wpdb->prefix . 'hdflv';
    $tablePlaylist = $wpdb->prefix . 'hdflv_playlist';
    $tableMed2play = $wpdb->prefix . 'hdflv_med2play';
    $tableSettings = $wpdb->prefix . 'hdflv_settings';
    $tableads = $wpdb->prefix . 'hdflv_vgads';

    update_option('youtubelogoshow', 1);

    // add charset & collate like wp core
    $charset_collate = '';

    if (version_compare(mysql_get_server_info(), '4.1.0', '>=')) {
        if (!empty($wpdb->charset))
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";
    }
    $sqlTableHdflv = "CREATE TABLE IF NOT EXISTS " . $tablehdflv . " (
                vid MEDIUMINT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    name MEDIUMTEXT NULL,
                    file MEDIUMTEXT NULL,
                    hdfile MEDIUMTEXT NULL,
                    image MEDIUMTEXT NULL,
                    opimage MEDIUMTEXT NULL,
                    link MEDIUMTEXT NULL ,
                    streamer_path MEDIUMTEXT NULL ,
                    islive INT(1) NOT NULL DEFAULT '0',
                    `is_active` INT(1) NOT NULL DEFAULT '1'
                    ) $charset_collate;";

    $sqlPlayList = "CREATE TABLE IF NOT EXISTS " . $tablePlaylist . " (
                pid INT(2) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                playlist_name VARCHAR(200) NOT NULL ,
                playlist_desc VARCHAR(200) NULL,
                playlist_order VARCHAR(50) NOT NULL DEFAULT 'ASC',
               `is_pactive` INT(1) NOT NULL DEFAULT '1'
                ) $charset_collate;";

       // create table
        $wpdb->query($sqlTableHdflv);
         // create table
        $wpdb->query($sqlPlayList);

    $sql = "CREATE TABLE IF NOT EXISTS " . $tableMed2play . " (
                rel_id BIGINT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                media_id BIGINT(10) NOT NULL DEFAULT '0',
                playlist_id BIGINT(10) NOT NULL DEFAULT '0',
                porder MEDIUMINT(10) NOT NULL DEFAULT '0',
                sorder INT(3) NOT NULL DEFAULT '0'
                ) $charset_collate;";

    $wpdb->query($sql);

    $sql = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."hdflv_googlead` (
`id` int(2) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`code` text NOT NULL,
`showoption` tinyint(1) NOT NULL,
`closeadd` int(6) NOT NULL,
`reopenadd` tinytext NOT NULL,
`publish` int(1) NOT NULL,
`ropen` int(6) NOT NULL,
`showaddc` tinyint(1) NOT NULL DEFAULT '0',
`showaddm` tinyint(4) NOT NULL DEFAULT '0',
`showaddp` tinyint(4) NOT NULL DEFAULT '0'
) $charset_collate;";

    $wpdb->query($sql);

    $sql = "CREATE TABLE  IF NOT EXISTS " . $tableads . " (
                `ads_id` bigint(10) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                    `file_path` varchar(200) NOT NULL,
                    `title` varchar(200) NOT NULL,
                    `description` text NOT NULL,
                    `targeturl` text NOT NULL,
                    `clickurl` text NOT NULL,
                    `adtype` text NOT NULL,
                    `admethod` text NOT NULL,
                    `imaadwidth` INT(11) NOT NULL,
                    `imaadheight` INT(11) NOT NULL,
                    `imaadpath` text NOT NULL,
                    `publisherId` text NOT NULL,
                    `contentId` text NOT NULL,
                    `imaadType` INT( 11 ) NOT NULL,
                    `channels` varchar(200) NOT NULL,
                    `impressionurl` text NOT NULL,
                    `publish` INT( 10 ) NOT NULL
                ) $charset_collate;";

    $wpdb->query($sql);

    $sql = "CREATE TABLE  IF NOT EXISTS " . $tableSettings . " (
                settings_id BIGINT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                logopath VARCHAR(200) NOT NULL DEFAULT '0',
                player_colors LONGTEXT NOT NULL,
                player_values LONGTEXT NOT NULL,
                player_icons LONGTEXT NOT NULL
                ) $charset_collate;";

    $wpdb->query($sql);
    $playr_colors = 'a:18:{s:21:"sharepanel_up_BgColor";s:0:"";s:23:"sharepanel_down_BgColor";s:0:"";s:19:"sharepaneltextColor";s:0:"";s:15:"sendButtonColor";s:0:"";s:19:"sendButtonTextColor";s:0:"";s:9:"textColor";s:0:"";s:11:"skinBgColor";s:0:"";s:13:"seek_barColor";s:0:"";s:15:"buffer_barColor";s:0:"";s:13:"skinIconColor";s:0:"";s:11:"pro_BgColor";s:0:"";s:15:"playButtonColor";s:0:"";s:17:"playButtonBgColor";s:0:"";s:17:"playerButtonColor";s:0:"";s:19:"playerButtonBgColor";s:0:"";s:19:"relatedVideoBgColor";s:0:"";s:15:"scroll_barColor";s:0:"";s:14:"scroll_BgColor";s:0:"";}';
    $player_values = 'a:15:{s:6:"buffer";s:1:"3";s:5:"width";s:3:"700";s:6:"height";s:3:"500";s:11:"normalscale";s:1:"0";s:15:"fullscreenscale";s:1:"0";s:6:"volume";s:3:"100";s:10:"stagecolor";s:0:"";s:7:"license";s:0:"";s:9:"logoalpha";s:3:"100";s:9:"logoalign";s:2:"BL";s:10:"logotarget";s:0:"";s:11:"ima_ads_xml";s:0:"";s:15:"adsSkipDuration";s:1:"5";s:14:"google_tracker";s:0:"";s:16:"relatedVideoView";s:6:"center";}';
    $player_icons = 'a:19:{s:8:"autoplay";s:1:"1";s:12:"playlistauto";s:1:"1";s:13:"playlist_open";i:0;s:13:"skin_autohide";s:1:"1";s:10:"fullscreen";s:1:"1";s:4:"zoom";s:1:"1";s:5:"timer";s:1:"1";s:8:"shareURL";s:1:"1";s:5:"email";s:1:"1";s:13:"volumevisible";s:1:"1";s:11:"progressbar";s:1:"1";s:10:"HD_default";s:1:"1";s:12:"imageDefault";s:1:"1";s:8:"download";s:1:"1";s:7:"ima_ads";s:1:"1";s:7:"adsSkip";s:1:"1";s:7:"showTag";s:1:"1";s:13:"embed_visible";s:1:"1";s:16:"playlist_visible";s:1:"1";}';
    
    $wpdb->query(" INSERT INTO " . $tableSettings . " (`logopath` ,`player_colors` ,`player_values` ,`player_icons`) VALUES (0,'$playr_colors','$player_values','$player_icons')");




    $videotable_data = $wpdb->get_results("SELECT * FROM " . $tablehdflv);
	if (empty($videotable_data)) {
$wpdb->query("INSERT INTO ".$tablehdflv." (`vid`, `name`, `file`, `hdfile`, `image`, `opimage`, `link`,`streamer_path`,`islive`, `is_active`) VALUES
('', 'Fast And Furious 5 (Official Trailer) HD', 'www.youtube.com/watch?v=4PspF_GA-9U', '', 'http://img.youtube.com/vi/4PspF_GA-9U/1.jpg', 'http://img.youtube.com/vi/4PspF_GA-9U/0.jpg', 'http://www.youtube.com/watch?v=4PspF_GA-9U', '','0','1')
");
}

        $playlisttable_data = $wpdb->get_results("SELECT * FROM " . $tablePlaylist);
	if (empty($playlisttable_data)) {
$wpdb->query("INSERT INTO " . $tablePlaylist . "(`pid`, `playlist_name`, `playlist_desc`, `playlist_order`,`is_pactive`)
        VALUES
        (1, 'Movie Trailer', '', 'ASC',1)");
	}

        //------------Media to play -----------------

	$media2Play_data = $wpdb->get_results("SELECT * FROM " . $tableMed2play);
	if (empty($media2Play_data)) {
$wpdb->query("INSERT INTO ".$tableMed2play." (`rel_id`, `media_id`, `playlist_id`, `porder`, `sorder`) VALUES
(7, 1, 1, 0, 0)");
	}

}

function hdflvDropTables() {
    global $wpdb;
    $tablehdflv = $wpdb->prefix . 'hdflv';
    $tablePlaylist = $wpdb->prefix . 'hdflv_playlist';
    $tableMed2play = $wpdb->prefix . 'hdflv_med2play';
    $tableSettings = $wpdb->prefix . 'hdflv_settings';
    $sql = "DROP TABLE $tablehdflv , $tablePlaylist , $tableMed2play ,  $tableSettings ";
    $wpdb->query($sql);
    //drop table start
    delete_option('SkinContentHide');
    delete_option('displayContentHide');
    delete_option('GeneralContentHide');
    delete_option('PlaylistContentHide');
    delete_option('LogoContentHide');
    delete_option('VideoContentHide');
    delete_option('LicenseContentHide');
}
?>