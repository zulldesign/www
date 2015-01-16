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

<div class="archivetek"><?php printf( __( 'Category Archives: %s', 'newmedia' ), single_cat_title( '', false ) ); ?></div>

<div class="breaker"></div>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<div class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></div>
<div class="post-date"><?php _e(' By ', 'newmedia'); ?><?php the_author_posts_link(); ?><?php _e(' On ', 'newmedia'); ?><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_time(get_option('date_format')) ?></a></div>

<?php if ( has_post_thumbnail()) : ?>
   <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
   <?php the_post_thumbnail('category-thumb2', array('class' => 'alignleft')); ?>
   </a>
 <?php endif; ?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<?php the_excerpt(''); ?><div class="more-link"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php _e('Read More', 'newmedia'); ?></a></div>

</div>

<div class="linebreaker"></div>

<?php endwhile; else: ?>

<p><?php _e('Sorry, no posts matched your criteria.', 'newmedia'); ?></p><?php endif; ?>

<?php wp_link_pages(array('next_or_number'=>'next', 'previouspagelink' => '&#8592;', 'nextpagelink'=>'&#8594;')); ?>

<?php comments_template(); ?>

<h4 class="pagi">
<?php posts_nav_link(' &#183 ', 'Previous Page', 'Next Page'); ?>
</h4>

</div>

<div id="sidebar-right">
<?php if(is_active_sidebar('right-sidebar-1')){ dynamic_sidebar('right-sidebar-1'); } ?>
</div>

</div>

<div class="delimiter"></div>

<?php get_footer(); ?>