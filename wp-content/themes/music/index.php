<?php
/**
* @package SiteGround
* @subpackage Music_Theme
*/ 

get_header(); ?>

		<div id="primary" <?php if ( !is_active_sidebar(1) ) : ?> class="fullwidth" <?php endif; ?> >
			<div id="content" role="main">

			<?php if ( have_posts() ) : ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', get_post_format() ); ?>

				<?php endwhile; ?>

				<?php musicthemebysg_content_nav( 'nav-below' ); ?>

			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
                    <header class="entry-header">                    	
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'musicthemebysg' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'musicthemebysg' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>