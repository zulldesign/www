<?php

if ( ! isset( $content_width ) ) $content_width = 480;

function newmedia_wp_title( $title ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	$site_description = get_bloginfo( 'description' );

	$filtered_title = $title . get_bloginfo( 'name' );
	$filtered_title .= ( ! empty( $site_description ) && ( is_home() || is_front_page() ) ) ? ' | ' . $site_description: '';
	$filtered_title .= ( 2 <= $paged || 2 <= $page ) ? ' | ' . sprintf( __( 'Page %s', 'newmedia' ), max( $paged, $page ) ) : '';

	return $filtered_title;
}
add_filter( 'wp_title', 'newmedia_wp_title' );

function newmedia_widgets_init() {
	register_sidebar( array(
		'name' => 'Left Sidebar',
		'id' => 'left-sidebar-1',
		'description' => 'Left widget area. Leave blank and main content will automatically adapt.',
		'before_widget' => '',
		'after_widget' => '<br />',
		'before_title' => '<h5 class="sidebarhd">',
		'after_title' => '</h5>',
	) );
	register_sidebar( array(
		'name' => 'Right Sidebar',
		'id' => 'right-sidebar-1',
		'description' => 'Right widget area. Leave blank and main content will automatically adapt.',
		'before_widget' => '',
		'after_widget' => '<br />',
		'before_title' => '<h5 class="sidebarhd">',
		'after_title' => '</h5>',
	) );
	register_sidebar( array(
		'name' => 'Footer widget area',
		'id' => 'footer-sidebar',
		'description' => 'Appears in the footer area. 3 widgets recommended.',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="sidebarhd2">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'newmedia_widgets_init' );

function newmedia_font_url() {
	$font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by this font, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'News Cycle font: on or off', 'newmedia' ) ) {
		$font_url = add_query_arg( 'family', urlencode( 'News Cycle' ), "//fonts.googleapis.com/css" );
	}

	return $font_url;
}

function newmedia_scripts() {

// Add font, used in the main stylesheet.
wp_enqueue_style( 'newmedia-font', newmedia_font_url(), array(), null );

wp_enqueue_style( 'newmedia-style', get_stylesheet_uri() );

if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'newmedia_scripts' );

// Register Theme Features
function newmedia_setup()  {

// Add theme support for Semantic Markup
$markup = array( 'search-form', 'comment-form', 'comment-list', );
add_theme_support( 'html5', $markup );	

add_theme_support( 'automatic-feed-links' );

add_theme_support( 'custom-background' );

$defaults = array(
	'default-color'          => 'cecece',
	'default-image'          => '',
	'wp-head-callback'       => '_custom_background_cb',
	'admin-head-callback'    => '',
	'admin-preview-callback' => ''
);
add_theme_support( 'custom-background', $defaults );

add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 480, 250, true );
add_image_size( 'category-thumb',  480, 250, true );
add_image_size( 'category-thumb2',  180, 120, true );

register_nav_menu( 'header-menu',__( 'Main Menu', 'newmedia' ) );

register_nav_menu( 'top-menu',__( 'Top Menu', 'newmedia' ) );

add_editor_style( array( 'editor-style.css', newmedia_font_url() ) );

}

// Hook into the 'after_setup_theme' action
add_action( 'after_setup_theme', 'newmedia_setup' );

function newmedia_custom_header_setup() {
	$args = array(

		// Set height and width, with a maximum value for the width.
		'height'                 => 200,
		'width'                  => 960,
		'max-width'              => 960,
                'header-text'            => false,

		// Support flexible height and width.
		'flex-height'            => false,
		'flex-width'             => false,

		// Random image rotation off by default.
		'random-default'         => false,

	        'uploads'                => true,
	        'wp-head-callback'       => '',
	        'admin-head-callback'    => '',
	        'admin-preview-callback' => '',
	);

	add_theme_support( 'custom-header', $args );
}
add_action( 'after_setup_theme', 'newmedia_custom_header_setup' );

function newmedia_excerpt_more($more) {
       global $post;
    return '...';
}
add_filter('excerpt_more', 'newmedia_excerpt_more');

function newmedia_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'newmedia_excerpt_length', 999 );

?>