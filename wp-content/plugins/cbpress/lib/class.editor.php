<?php
if (!defined('ABSPATH')) die();


class CBP_editor {

	function __construct() {

		if(is_admin()){
			global $pagenow;
			$generator_includes_pages = array( 'post.php', 'edit.php', 'post-new.php', 'index.php' );
			if ( in_array( $pagenow, $generator_includes_pages ) ) {
				wp_register_style( 'cbpress-shortcode-generator', CBP_ADMIN_URL . 'sc/generator.css', false, CBP_VERSION, 'all' );
				wp_register_script( 'cbpress-shortcode-generator', CBP_ADMIN_URL . 'sc/generator.js', false, CBP_VERSION, false );
				add_action( 'media_buttons', array( &$this, 'cb_add_generator_button' ), 100 );
				add_action( 'admin_footer', array( &$this, 'cb_generator_popup' ) );
			}
		}
	}

	/**
	 * Add generator button to Upload/Insert buttons
	 */
	function cb_add_generator_button() {
		echo '<a href="#TB_inline?width=640&height=500&inlineId=cb-generator-wrap" class="thickbox" title="' . __( 'CBPRESS Shortcode Helper', 'cbpress' ) . '"><img src="' . CBP_IMG_URL . 'icon.png" alt="" /></a>';
	}
	
	// inline loading
	function cb_generator_popup() {
		?>
		<div id="cb-generator-wrap" style="display:none">
		
			<h3>Shortcode Helper</h3>
			<div id="cb-generator">
				<div id="cb-generator-shell">
					<div id="cb-generator-header">
						<select id="cb-generator-select" size="1">
							<option value=""><?php _e( 'Select shortcode', 'cbpress' ); ?></option>
							<?php
							$thecodes = CBP_data::get('shortcodes');
							foreach ( $thecodes as $name => $shortcode ) {
								?>
								<option value="<?php echo $name; ?>"><?php echo $shortcode['name']; ?></option>
								<?php
							}
							?>
						</select>
						<div id="cb-generator-tools" style="display:none;">
							<a href="<?php echo admin_url( 'admin.php?page=cbpress-settings' ); ?>" target="_blank" title="<?php _e( 'Settings', 'cbpress' ); ?>"><img src="<?php echo CBP_IMG_URL; ?>/settings.png" alt="" /></a>
							<a href="http://support.cbpress.com" target="_blank" title="<?php _e( 'Support', 'cbpress' ); ?>"><img src="<?php echo CBP_IMG_URL; ?>/support.png" alt="" /></a>
						</div>
					</div>
					<div id="cb-generator-settings"></div>
					<input type="hidden" name="cb-generator-url" id="cb-generator-url" value="<?php echo CBP_ADMIN_URL; ?>" />
					<input type="hidden" name="cb-compatibility-mode-prefix" id="cb-compatibility-mode-prefix" value="cbpress " />
				</div>
			</div>
		</div>










		<?php
	}

	
}
