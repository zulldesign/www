<?php
/*
Plugin Name: Japkin
Plugin URI: http://www.japkin.com/
Description: Japkin allows you to receive video and voice messages from your website visitors. Once installed, visitors can click on the Japkin widget placed on your website and choose to leave you a video or audio message straight from their desktop and mobile devices. Install Japkin today and start receiving messages from your visitors instantly!
Version: 1.0.1406
Company: Japkin, LLC.
Author: Japkin, LLC.
Author URI: http://www.japkin.com/
License: Proprietary Software
Copyright: (c) 2014. All rights reserved.
*/

/**
 * The Japkin class.
 */

class JapkinWP
{
	private $options;
	private $version = '1.0.1406';
	
	public function __construct() {
		if ( is_admin() ) {
			// User is in the WordPress Admin Page
			add_action( 'admin_menu', array( $this , 'CreateAdminMenu') );
			add_action( 'admin_enqueue_scripts', array( $this, 'LoadAdminAssets' ) );
			
			// AJAX Handler
			add_action( 'wp_ajax_japkin_options', array( $this, 'SaveOptions' ) );			
		} else {
			// Get Plugin Options
			$options = get_option( 'japkin_wp' );
			
			if ( !$options || $options == '' ) {
				// Plugin is not yet configured.
			} else {
				$this->options = unserialize( $options );
				
				if ( !isset( $this->options['widget'] ) || $this->options['widget'] == 'enabled' ) {
					// Plugin is being called from the front-end.
					wp_enqueue_script( 'japkin-wp', "//www.japkin.com/src/loader.php?apikey={$this->options['key']}", array(), $this->version );
				}

				if ( !isset( $this->options['embed'] ) || $this->options['embed'] == 'enabled' ) {
					// Support for Embed Buttons
					wp_enqueue_script( 'japkin-embed-v1', '//www.japkin.com/script/japkin-embed-v1.js', array(), $this->version );
					add_shortcode( 'japkin-btn-embed', array( $this, 'CreateEmbed' ) );
				}
			}
		}
	}
	
	/**
	 * Adds menu to the WP Admin panel.
	 */
	public function CreateAdminMenu() {
		// Creates a menu named "Japkin for WP".
		add_menu_page( 'Japkin for WP', 'Japkin for WP', 'manage_options', 'japkin_wp', array( $this, 'ShowSettings' ), plugins_url( 'assets/images/favicon.ico', __FILE__ ) );
	}
	
	/**
	 * Loads assets for the WP Admin panel.
	 */
	public function LoadAdminAssets() {
		// Includes styles and scripts for the plugin administration page. Note
		// that version number is added to inhibit caching.
		wp_enqueue_style( 'japkin_wp_style', plugins_url( 'assets/css/style.css', __FILE__ ), array(), $this->version );
		wp_enqueue_script( 'japkin_wp_script', plugins_url( 'assets/js/script.js', __FILE__ ), 'jquery', $this->version );
	}
	
	/**
	 * Handles the Settings page in the WP admin panel.
	 */
	public function ShowSettings() {
		$options = unserialize( get_option( 'japkin_wp' ) );
		$logo = plugins_url( 'assets/images/logo.png', __FILE__ );

		if ( !isset( $options['id'] ) || !isset( $options['email'] ) || !isset( $options['key'] ) ) {
			require_once( 'views/form-login.php' );
		} else {
			require_once( 'views/page-settings.php' );
		}
	}
	
	/**
	 * Handles saving of plugin related options.
	 */
	public function SaveOptions() {
		if ( isset( $_REQUEST['method'] ) && $_REQUEST['method'] == 'clear' ) {
			// If the user logs out, or the method is to clear the options,
			// database entries are deleted.
			delete_option( 'japkin_wp' );
		} else if ( isset( $_REQUEST['id'] ) && isset( $_REQUEST['email'] ) && isset( $_REQUEST['key'] ) ) {
			// Else, plugin required data are set and options will be saved.
			$options = array(
				'id' => $_REQUEST['id'],
				'email' => $_REQUEST['email'],
				'key' => $_REQUEST['key'],
				'widget' => isset( $_REQUEST['widget'] ) ? $_REQUEST['widget'] : 'enabled',
				'embed' => isset( $_REQUEST['embed'] ) ? $_REQUEST['embed'] : 'enabled'
			);
			update_option( 'japkin_wp', serialize( $options ) );
			
			// Returns JSON formatted string to be handled by javascript.
			die( json_encode( array(
				'Status' => 200,
				'Message' => isset( $_REQUEST['no_reload'] ) ? $_REQUEST['no_reload'] : 'OK'
			)));
		}
	}
	
	/**
	 * Creates an embed button for use on front-end pages.
	 * 
	 * @param array $attr Contains data from the plugin options.
	 * 
	 * @return string $embedObj HTML DIV formatted element.
	 */
	public function CreateEmbed( $attr ) {
		return "<div data-japkinembed='true' data-opt='{$attr['opt']}' data-apikey='{$this->options['key']}' class='japkinEmbedBtn'></div>";
	}
}

$J = new JapkinWP();