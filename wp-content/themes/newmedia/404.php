<?php get_header(); ?>

<div id="main">

<?php get_sidebar(); ?>

<?php if ( is_active_sidebar('left-sidebar-1') && is_active_sidebar('right-sidebar-1') ) { ?>
  <div id="content">
<?php } elseif ( is_active_sidebar('left-sidebar-1') ) { ?>
  <div id="content-wide">
<?php } elseif ( is_active_sidebar('right-sidebar-1') ) { ?>
  <div id="content-wide">
<?php } else { ?>
  <div id="content-widest">
<?php }; ?>

<h1><?php _e('404 Error', 'newmedia'); ?></h1>

	<p><?php _e('We cannot seem to find what you were looking for.', 'newmedia'); ?></p>
	<p><?php _e('Maybe we can still help you.', 'newmedia'); ?></p>

	<ul>
		<li><?php _e('You can search our site using the form provided below.', 'newmedia'); ?>

<p><?php get_search_form(); ?></p>

</li>
	</ul>

<?php _e('Click', 'newmedia'); ?> <a href="<?php echo esc_url(home_url('/')); ?>"><?php _e('here', 'newmedia'); ?></a> <?php _e('to return to the main page.', 'newmedia'); ?>

</div>

<div id="sidebar-right">
<?php if(is_active_sidebar('right-sidebar-1')){ dynamic_sidebar('right-sidebar-1'); } ?>
</div>

</div>

<div class="breaker"></div>

<?php get_footer(); ?>