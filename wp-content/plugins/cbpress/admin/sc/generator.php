<?php
// Start WordPress
function get_cbpress_sc_gen_tool_wpload_path() {
	   $d = 0;
	   while (!file_exists(str_repeat('../', $d).'wp-load.php'))
	       if (++$d > 16) exit;
	   $wpconfig = str_repeat('../', $d).'wp-load.php';
	   return $wpconfig;
}
$wpath = get_cbpress_sc_gen_tool_wpload_path();
require_once($wpath);


// $cbpress_args = CBP_query::getSearchParams();

function cbpress_gen_ddo( $items, $default = '' ) {
	$out = '';
	if ( count( $items ) > 0 ) {
		foreach ( $items AS $key => $value ) {
			if ( is_array( $value ) )	{
				$out .= '<optgroup label="'.esc_attr( $key ).'">';
				foreach ( $value AS $sub => $subvalue ) {
					$out .=  '<option value="'.esc_attr( $sub ).'"'.( $sub == $default ? ' selected="selected"' : '' ).'>'.esc_html( $subvalue ).'</option>';
				}
				$out .=  '</optgroup>';
			}
			else {
				
				$out .=  '<option value="'.esc_attr( $key ).'"'.( $key == $default ? ' selected="selected"' : '' ).'>'.esc_html( $value ).'</option>';
			}
		}
	}
	return $out;
}
function cbpress_gen_attr($attr_name,$value='') {
	// global $cbpress_args;	
	$id = 'cb-generator-attr-' . $attr_name;
	$out = '';
	$selstart = '<select name="' . $attr_name . '" id="'.$id.'" class="cb-generator-attr">';
	switch ($attr_name) {
		case 'root':
			$catopts = CBP_cats::category_dropdown_tree_db(0,$value);
			$out .= $selstart;
			$out .= $catopts;
			$out .= '</select>';
			break;
		case 'category':
			$catopts = CBP_cats::category_dropdown_tree_db(0,$value);
			$out .= $selstart;
			$out .= '<option value="" selected="selected">(optional)</option>';
			$out .= $catopts;
			$out .= '</select>';
			break;
		case 'list':
			$lists = CBP_lists::get_for_select();			
			$out .= $selstart;
			$out .= cbpress_gen_ddo( $lists, $value );
			$out .= '</select>';
			break;
		case 'vendor':
			$out .= '<input type="text" name="' . $attr_name . '" id="'.$id.'" value="' . $value . '" class="cb-generator-attr" />';
			break;
		case 'showdesc':		
			$out .= $selstart;
			$out .= '<option value="">Yes</option>';
			$out .= '<option value="0">No</option>';
			$out .= '</select>';
			break;
		case 'sort':
			$out .= $selstart;
			$out .= '<option value=""></option>';
			$out .= cbpress_gen_ddo( CBP_Meta::getSortByList(), $value  );
			$out .= '</select>';
			break;	
		case 'order':
			$out .= $selstart;
			$out .= '<option value=""></option>';
			$out .= cbpress_gen_ddo( CBP_Meta::getOrderByList(), $value  );
			$out .= '</select>';
			break;			
		default:
			$out .= '<input type="text" name="' . $attr_name . '" id="'.$id.'" value="' . $value . '" class="cb-generator-attr" />';				
			break;
	}
	return $out;
}
			
function cbpress_gen_load() {	

	
	global $wpdb;
	
	$out = '';

	if (!current_user_can('edit_posts') || !current_user_can('edit_pages')){ die('Access denied'); }
	if (empty($_GET['shortcode'])){ die('Shortcode not specified'); }




	$scc = $_GET['shortcode'];
	
	$theargs = CBP_data::get('shortcodes_args');
	$thecodes = CBP_data::get('shortcodes');

	$shortcode = $thecodes[$scc];
	
	//	abort(get_defined_vars());
	// Shortcode has atts
	
	/// if ( count( $shortcode['atts'] ) && $shortcode['atts'] ) {
		
	if ( count( $shortcode['atts'] )  ) {
			
		
		foreach ( $shortcode['atts'] as $attr_name => $attr_info ) {

			$out .= '<p>';
			$out .= '<label for="cb-generator-attr-' . $attr_name . '">' . $attr_info['desc'] . '</label><br>';

			$out .=  cbpress_gen_attr($attr_name,$attr_info['default']);
			
			if(1 == 2){
				// Select
				if ( count( $attr_info['values'] ) && $attr_info['values'] ) {
					$out .= '<select name="' . $attr_name . '" id="cb-generator-attr-' . $attr_name . '" class="cb-generator-attr">';
					foreach ( $attr_info['values'] as $attr_value ) {
						$attr_value_selected = ( $attr_info['default'] == $attr_value ) ? ' selected="selected"' : '';
						$out .= '<option' . $attr_value_selected . '>' . $attr_value . '</option>';
					}
					$out .= '</select>';
				}else {
					$t = isset($attr_info['type']) ? $attr_info['type'] : '';
					$attr_field_type = ( $t == 'color' ) ? 'color' : 'text';
					$classname = (isset($attr_info['class'])) ? 'cb-generator-attr ' . $attr_info['class'] : 'cb-generator-attr';
					$out .= '<input type="text" name="' . $attr_name . '" value="' . $attr_info['default'] . '" id="cb-generator-attr-' . $attr_name . '" class="'.$classname.'" />';
				}
			}
			
			$out .= '</p>';
		}
	}

	// Single shortcode (not closed)
	if ( $shortcode['type'] == 'single' ) {
		$out .= '<input type="hidden" name="cb-generator-content" id="cb-generator-content" value="false" />';
	}

	// Wrapping shortcode
	else {
		$out .= '<p><label>' . __( 'Content', 'cbpress' ) . '</label><input type="text" name="cb-generator-content" id="cb-generator-content" value="' . $shortcode['content'] . '" /></p>';
	}

	$out .= '<p><a href="#" class="button" id="cb-generator-insert">' . __( 'Insert Shortcode', 'cbpress' ) . '</a> ';
	$out .= '<a href="' . CBP_IMG_URL . 'demo/' . $_GET['shortcode'] . '.png" target="_blank" class="button ">' . __( 'See Example', 'cbpress' ) . '</a></p>';
	$out .= '<input type="hidden" name="cb-generator-result" id="cb-generator-result" value="" />';
	echo $out;
		
}
	
echo cbpress_gen_load();