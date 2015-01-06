jQuery(document).ready(function($) {
	// Select shortcode
	$('#cb-generator-select').live( "change", function() {
		var queried_shortcode = $('#cb-generator-select').find(':selected').val();		
		$('#cb-generator-settings').addClass('cb-loading-animation');
		$('#cb-generator-settings').load($('#cb-generator-url').val() + 'sc/generator.php?shortcode=' + queried_shortcode, function() {			
			$('#cb-generator-settings').removeClass('cb-loading-animation');
		});
	});

	// Insert shortcode
	$('#cb-generator-insert').live('click', function() {
		var queried_shortcode = $('#cb-generator-select').find(':selected').val();
		var cb_compatibility_mode_prefix = $('#cb-compatibility-mode-prefix').val();
		$('#cb-generator-result').val('[' + cb_compatibility_mode_prefix);
		
		$('#cb-generator-settings .cb-generator-attr').each(function() {
			if ( $(this).val() !== '' ) {
				$('#cb-generator-result').val( $('#cb-generator-result').val() + ' ' + $(this).attr('name') + '="' + $(this).val() + '"' );
			}
		});

		v = $('#cb-generator-result').val().trim();
		$('#cb-generator-result').val(v + ']');

		// wrap shortcode
		if ( $('#cb-generator-content').val() != 'false' ) {
			$('#cb-generator-result').val($('#cb-generator-result').val() + $('#cb-generator-content').val() + '[/' + cb_compatibility_mode_prefix + queried_shortcode + ']');
		}
		window.send_to_editor($('#cb-generator-result').val());
		return false;
	});

});