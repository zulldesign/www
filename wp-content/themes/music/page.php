<?php
/**
* @package SiteGround
* @subpackage Music_Theme
*/ 

get_header(); ?>

		<div id="primary" <?php if ( !is_active_sidebar(1) ) : ?> class="fullwidth" <?php endif; ?> >
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'page' ); ?>

					<?php comments_template( '', true ); ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>