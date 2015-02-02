<?php
/**
* @package SiteGround
* @subpackage Music_Theme
*/ 
?>

</div><!-- #main -->
<div class="clear"></div>

</div><!-- #page -->

<footer id="colophon" role="contentinfo" class="alignleft">
	<div class="foot">
        <div id="site-designer">Designed by <a href="http://www.siteground.com/template-preview/wordpress/Music" target="_blank">SiteGround</a></div>
        <div id="site-generator">
			<?php do_action( 'musicthemebysg_credits' ); ?>
			<a href="http://wordpress.org/" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'musicthemebysg' ); ?>"><?php printf( __( 'Powered by %s', 'musicthemebysg' ), 'WordPress' ); ?></a>
		</div>
    </div>
</footer><!-- #colophon -->
<?php wp_footer(); ?>
</body>
</html>