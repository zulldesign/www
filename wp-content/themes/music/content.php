<?php
/**
* @package SiteGround
* @subpackage Music_Theme
*/ 
?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="note"></div>
        <header class="entry-header">
        	<?php 
        		$post_title = get_the_title();
        		if( empty( $post_title ) ) {
        			$post_title = get_the_ID();
        		}
        	?>
			<?php if ( is_sticky() ) : ?>
				<hgroup>
					<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'musicthemebysg' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php echo $post_title; ?></a></h2>
					<h3 class="entry-format"><?php _e( 'Featured', 'musicthemebysg' ); ?></h3>
				</hgroup>
			<?php else : ?>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'musicthemebysg' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php echo $post_title; ?></a> 
				<?php if ( 'post' == get_post_type() ) : ?>
				<span class="date">			
					<div class="entry-meta">
						<?php the_time('j') ?><br />
		                <?php the_time('M') ?>
					</div><!-- .entry-meta -->
				</span>
				<?php endif; ?>
			</h2>

			<?php endif; ?>
		</header><!-- .entry-header -->

		<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'musicthemebysg' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'musicthemebysg' ) . '</span>', 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
		<?php endif; ?>

		<footer class="entry-meta">
			<?php if ( comments_open() && ! post_password_required() ) : ?>
			<div class="alignleft">
				<?php comments_popup_link( 'No comments', '1 comment', '% comments', 'comments-link', 'Comments are off for this post' ); ?>
			</div>
			<span class="sep"> | </span>
			<?php endif; ?>
			
			<?php $show_sep = false; ?>
			<?php if ( is_object_in_taxonomy( get_post_type(), 'category' ) ) : // Hide category text when not supported ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'musicthemebysg' ) );
				if ( $categories_list ):
			?>
			<span class="cat-links">
				<?php printf( __( '<span class="%1$s">Posted in</span> %2$s', 'musicthemebysg' ), 'entry-utility-prep entry-utility-prep-cat-links', $categories_list );
				$show_sep = true; ?>
			</span>
			<?php endif; // End if categories ?>
			<?php endif; // End if is_object_in_taxonomy( get_post_type(), 'category' ) ?>
			<?php if ( is_object_in_taxonomy( get_post_type(), 'post_tag' ) ) : // Hide tag text when not supported ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'musicthemebysg' ) );
				if ( $tags_list ):
				if ( $show_sep ) : ?>
			<span class="sep"> | </span>
				<?php endif; // End if $show_sep ?>
			<span class="tag-links">
				<?php printf( __( '<span class="%1$s">Tagged</span> %2$s', 'musicthemebysg' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list );
				$show_sep = true; ?>
			</span>
			<?php endif; // End if $tags_list ?>
			<?php endif; // End if is_object_in_taxonomy( get_post_type(), 'post_tag' ) ?>

			<?php if ( comments_open() ) : ?>
			
			
			<?php endif; // End if comments_open() ?>
            
            

			<?php edit_post_link( __( 'Edit', 'musicthemebysg' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post-<?php the_ID(); ?> -->
