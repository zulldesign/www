<?php
/*
 * Name: WP Flash Player Plugin URI: http://www.apptha.com/category/extension/Wordpress/HD-FLV-Player-Plugin/ Description: HD FLV player config file. Version: 1.3 Author: Apptha Author URI: http://www.apptha.com License: GPL2
 */
$path = '';

if (! defined ( 'WP_LOAD_PATH' )) {
	
	/**
	 * classic root path if wp-content and plugins is below wp-config.php
	 */
	$classic_root = dirname ( dirname ( dirname ( dirname ( __FILE__ ) ) ) ) . '/';
	
	if (file_exists ( $classic_root . 'wp-load.php' ))
		define ( 'WP_LOAD_PATH', $classic_root );
	else if (file_exists ( $path . 'wp-load.php' ))
		define ( 'WP_LOAD_PATH', $path );
	else
		exit ( "Could not find wp-load.php" );
}

// let's load WordPress
require_once (WP_LOAD_PATH . 'wp-load.php');
?>