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

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<div class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></div>

<?php if ( has_post_thumbnail()) : ?>
   <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
   <?php the_post_thumbnail('category-thumb', array('class' => 'alignnone')); ?>
   </a>
 <?php endif; ?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<?php the_content('Read More...'); ?>

</div>

<div class="commentstext"><?php
  comments_popup_link( 'No comments yet', '1 comment', '% comments', 'comments-link', '');
?></div>

<div class="breaker"></div>

<?php endwhile; else: ?>

<p><?php _e('Sorry, no posts matched your criteria.', 'newmedia'); ?></p><?php endif; ?>

<?php wp_link_pages(array('next_or_number'=>'next', 'previouspagelink' => '&#8592;', 'nextpagelink'=>'&#8594;')); ?>

<?php comments_template(); ?> 

</div>

<div id="sidebar-right">
<?php if(is_active_sidebar('right-sidebar-1')){ dynamic_sidebar('right-sidebar-1'); } ?>
</div>

</div>

<div class="delimiter"></div>

<?php get_footer(); ?>