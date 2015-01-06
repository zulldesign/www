<?php

/*
Plugin Name: CB PRESS
Plugin URI: http://cbpress.com/
Description: The Official ClickBank Marketplace Plugin for WordPress
Author: cbpress.com
Version: 1.2.5
Text Domain: cbpress
Author URI: http://cbpress.com/
*/


if(!defined('ABSPATH')) exit;

if ( !class_exists( 'Cbpress' ) && !defined('CBPRESS_BOOT_STRAP_PAGE_CHECK')) {


	define('CBP_BASEFILE', __FILE__);

	$cbp_self_update = 0;
	if($cbp_self_update == 1){ include_once("lib/upgrademe.php"); }	


	function _cbpress_class($f){
		include_once("lib/class.$f.php");
		return $f;
	}


	function cbpress_plugin_url() {
		return plugins_url( basename( __FILE__, '.php' ), dirname( __FILE__ ) );
	}

	include_once("constants.php");

	function cbpress_wp_activation() {
		CBP_constants::init();
		include_once("lib/class.install.php"); 
		CBP_install::install();
		return true;
	}


	function cbpress_wp_deactivation() {
		// clear update checks
		global $wpdb, $cbp_self_update;	

		if($cbp_self_update == 1){
			$opt_tbl = $wpdb->prefix."options";
			$wpdb->query("DELETE FROM $opt_tbl WHERE option_name = '_site_transient_update_plugins'");
		}
	}


	register_activation_hook(CBP_BASEFILE, 'cbpress_wp_activation');
	register_deactivation_hook(CBP_BASEFILE, 'cbpress_wp_deactivation');			


	function _cbpress_plugin_start() {

		CBP_constants::init();

		include_once("lib/class.install.php");
		include_once("lib/class.fn.php");
		include_once("lib/class.dump.php");
		include_once("lib/class.cbp.php");
		include_once("lib/class.api.php");
		include_once("lib/class.data.php");
		include_once("lib/class.cbpress.php");
		CBP_constants::class_loader('lib/');

		// $incs = array_map('_cbpress_class', explode(',','install,fn,dump,cbp,api,base,data'));

		// unset($incs);

		// CBP_constants::class_loader('lib/');

		global $cbpress;
		$cbpress = Cbpress::getter();

	}

	add_action( 'plugins_loaded', '_cbpress_plugin_start' );

}