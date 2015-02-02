<!DOCTYPE html>
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php 
	wp_head();
?>
</head>
<body <?php body_class(); ?>>
<div id="page" class="hfeed">
	<div id="header">
    	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="alignleft" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
    	<?php $header_image = get_header_image();
		if ( ! $header_image ) : ?>
    		<img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" class="logo" alt="logo" />
    	 <?php else: ?>
    		 <img src="<?php header_image(); ?>" class="logo" alt="logo" />
    	 <?php endif; ?>
    	</a> 
    	<h1 class="alignleft"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
    	<h2 id="site-description" class="alignleft"><?php bloginfo( 'description' ); ?></h2>
        <div class="clr"></div>
		<?php
        wp_nav_menu( array(
			'theme_location' 	=> 'primary',
			'container'			=> 'div',
			'container_class' 	=> 'menu',
			'items_wrap'      	=> '<ul id="%1$s" class="%2$s">%3$s</ul>',
		));
		?>
	</div>
			<!-- #access -->

	<div id="main">
