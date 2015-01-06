<?php
if (!defined('ABSPATH')) die();
class CBP_settings {
	//  extends CBP_base
	private static $instance = null;
	static public function getter() {
			if (!self::$instance) self::$instance = new self();
			return self::$instance;
	}


	private $sectionFields = array();
	private $checkboxes;
	private $settings;
	private $catdata = null;
	public $options = array();
	public $sections = null;
	public $data = null;
	private $defaults = null;
	private $store_name = 'cbpress';
	public $_requested = null; ## global access to vartiables on frontend and some admin pages
	public $css = array(
		'dir' => array('custom'=>null,'backup'=>null,'template'=>null),
		'url' => array('custom'=>null,'backup'=>null,'template'=>null)
	);

	protected $_base = 'cbpress_base_options';

	public function __construct() {


		$this->data = CBP_data::get('settings');
		$this->defaults = &$this->data->defaults;
		$this->options = $this->load_options();
		$this->sections = &$this->data->tabs;

		## add do_action callbacks for tabs
		foreach ( $this->sections as $row ){
				if(isset($row->action)){
					add_action($row->action, array( &$this , $row->callback));
				}
		}
		## populate css paths
		$this->csscode = '';
		foreach($this->css as $k => &$dat){
			$loco = constant('CBP_FRONT_'. strtoupper($k)); // url or dir
			foreach($dat as $p => &$v){
				$v = $loco . 'frontend-' . $p . '.css';
			}
			$dat = (object) $dat;
		}
		$this->css = (object) $this->css;
		// abort($this->css);


	}





	function create_page() {

			$t = ($this->pagetitle != '') ? $this->pagetitle : "Marketplace";
			$s = ($this->pageslug != '') ? $this->pageslug : "marketplace";

			$content = '<p>Find thousands of popular items in our marketplace!</p> ' . "\\" . '[cbpress]';


			$this->pageid = CBP_install::insert_page($t, $s,$content,'publish');

			// update opion pageslug with actual wpslug
			$id = $this->pageid;
			$post = get_post($id);
			if($post->post_name != ''){ $this->pageslug = $post->post_name; }
			$this->save();


			CBP::redirector('A new page has been created with the marketplace shortcode installed!');
	}

	## what css file are we using

		public function css_content($name) {
			return ($this->css_exists($name)) ? trim(file_get_contents($this->css->dir->$name)) : '';
		}

		public function css_exists($name) {

			return file_exists($this->css->dir->$name);

		}

		public function css_backup() {

			if ($this->css_exists('custom')){

				CBP_http::file_put_contents($this->css->dir->backup, $this->css_content('custom') );
			}
		}

		function panel_help() {
			//	$inc = CBP_VIEWS_DIR . 'the_options.htm';
			//	include_once($inc);
			$helpuri = 'http://help.cbpress.com';
			// CBP::metabox('d','x');
			?>
			<?php
		}
		public function panel_system() {

				$arr = &$this->data->system;

				$out = array();
				foreach($arr as $id => $dat){
					$dat = (object) $dat;
					// abort($dat);

					$attr = (object) array();
					$attr->href = CBP::get_admin_url('').'&fa=' . $dat->action;
					if($dat->confirm != '') $attr->onclick = "return confirm('{$dat->confirm}')";
					$attr->class = 'link-button';
					$desc = $dat->desc;

					if($dat->action == 'createpage'){
						$pageid = CBP_shortcodes::getMallPageID();
						if($pageid > 0){
							$desc = "<span class='note'><b>note: shortcode already installed</b></span>. <br/>" . $desc;
						}
					}
					// $attr->class = '';
					$out[] = '<tr>' . CBP::quicktag('td',CBP::quicktag('a',$attr, $dat->label)) . CBP::quicktag('td',$desc) . '</tr>';
				}
				$out = '<div class="system_panel"><table>' . implode('<tr><td colspan="2">&nbsp;</td></tr>',$out) . '</table></div>';
				echo $out;

		}

		public function panel_css() {
			$contents = $this->css_content('custom');
			if(strlen($contents) < 2) $contents = $this->css_content('template');
			$contents = stripslashes($contents);


			$msg = @$_REQUEST['msg'];
			if(strlen($msg) > 1){
					// echo '<div style="padding: 10px 0px; color:#880000;">' . $msg . '</div>';
			}

			?>
			<p>Every time you update this stylesheet a backup is saved
			<b><a href="<?php echo $this->css->url->backup; ?>" target="_blank">here</a></b> before any updates occur.</p>



				<?php if(1 == 2){ ?>
					<form action="<?php echo admin_url( 'admin.php' ); ?>" enctype="multipart/form-data" method="POST" class="cbpress-form">
					<?php CBP::wp_nonce_field(CBP_HOOK_NONCE) ?>
					<input type="hidden" name="action" value="cbp-save-css" />
					<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
				<?php } ?>

				<?php
				$sty = 'font-size:13px; line-height: 1em; font-family: monospace; width: 100%; min-height: 300px; height: 100%;';
				// $sty = '';
				echo '<textarea class="stylesheet" id="csscode" name="csscode" style="'.$sty.'">' . $contents. '</textarea>';
				?>



				<?php if(1 == 2){ ?>

					<div>
					<input type="submit" name="submitstyle" class="button-primary" value="Save Stylesheet" />
					<span style='padding-left: 50px'><input type="submit" name="resetstyle" class="button-primary" value="Reset to default" /></span>
					</div>
					</form>
				<?php } ?>

			<?php
		}


	function panel_filter() {
		?>

			<table>

			<tr>
			<td valign="top">



				<table cellspacing="5">
					<tr>
						<td><b>Filter</b></td>
						<td><b>Min</b></td>
						<td><b>Max</b></td>
					</tr>
					<tr><td colspan="3"><div class="cbpress-divider"></div></td></tr>
					<?php
					$arr = CBP_Meta::getMinMaxList();
					foreach ($arr as $k =>$v) {
						$c = strtolower($k);
						$mincol = 'min_' . $c;
						$maxcol = 'max_' . $c;


						$mincol_name = "cbpress_options[" . $mincol . "]";
						$maxcol_name = "cbpress_options[" . $maxcol . "]";


						echo '<tr>';
						echo '<td><label>' . $v['label'] . ':</label></td>';
						if($v['type'] == 'select' && 1 == 2){

							echo '<td>' . cbpressfn::selectSimple($mincol_name, $v['choices'], $this->$mincol, false, 'narrow') . '</td>';
							echo '<td>' . cbpressfn::selectSimple($maxcol_name, $v['choices'], $this->$maxcol, false, 'narrow') . '</td>';
						}else{
							echo '<td>' . cbpressfn::input($mincol_name,'text', $this->$mincol ,'short') . '</td>';
							echo '<td>' . cbpressfn::input($maxcol_name,'text', $this->$maxcol ,'short') . '</td>';
						}
						echo '</tr>';
					}
					?>

					<tr><td colspan="3"><div class="cbpress-divider"></div></td></tr>

				</table>
			</td>

			<td valign="top" style="padding-left: 25px;">

				<table cellspacing="5">
					<tr>
						<td><b><label>Billing Type:</label></b></td>
					</tr>
					<tr>
						<td colspan="1">
							<select id="billing" name="cbpress_options[billing]">
							<option value="all"<?php if($this->billing == 'all' || $this->billing == ''){print 'selected';}?>>All Products</option>
							<option value="1"<?php if($this->billing == '1'){print 'selected';}?>>Only Recurring Billing</option>
							<option value="0"<?php if($this->billing == '0'){print 'selected';}?>>Only Standard Billing</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><b><label>Sort By:</label></b></td>
					</tr>
					<tr>
						<td colspan="1">
							<?php
								$sortopts = CBP_Meta::getSortByList();
								$selvalue = $this->sort;
								echo '<select id="sort" name="cbpress_options[sort]">';
								foreach ($sortopts as $k =>$v) {
									$sel = selected( $selvalue, $k, false );
									echo "<option value='{$k}'{$sel}>{$v}</option>";
								}
								echo '</select>';
							?>
						</td>
					</tr>
					<tr>
						<td><b><label>Sort Order:</label></b></td>
					</tr>
					<tr>
						<td colspan="1">
							<select id="order" name="cbpress_options[order]">
								<option value="asc"<?php if($this->order == 'asc' || $this->order == ''){print 'selected';}?>>Asc</option>
								<option value="desc"<?php if($this->order == 'desc'){print 'selected';}?>>Desc</option>
							</select>
						</td>
					</tr>
				</table>
			</td>

			</tr>
			</table>
			<p class="info" style="line-height: 26px;">
					<b>Gravity</b>: total number of affiliates who referred at least one
					sale of the product over a 12-week period<b> <br>
					Commission</b>: percentage paid to affiliates per referred sale<b>
					<br>
					Rank</b>: product popularity within specific marketplace categories<b>
					<br>
					%Referred</b>: percent of total product sales referred by affiliates<br>
			</p>



		<?php
	}
		public function css_save() {
			$this->css_backup();
			$contents = '';


			$contents = @$_REQUEST['csscode'];

			if (isset($_POST['submitstyle'])) {
				$contents = $_POST['csscode'];
			} elseif (isset($_POST['resetstyle'])) {
				$contents = $this->css_content('template');
			}



			// abort(get_defined_vars());


			if(strlen($contents) > 2){
				CBP_http::file_put_contents($this->css->dir->custom, $contents);
				CBP::flush_cache();
			}
			/*********
			$msg = 'Stylesheet saved';
			$ref = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : CBP::admin('css');
			$ref = remove_query_arg('msg', $ref);
			$ref = add_query_arg('msg',urlencode($msg),$ref);
			wp_redirect( $ref );
			exit();
			*********/
		}




	/**
	* lOADS curent category variables for global access
	*
	*/

	function get_requested() {
		## for front end global access
		if($this->_requested === null){
			$out = (object) array();
			$out->root_id = intval($this->cat_root);
			$out->cid = intval(CBP::getv('cid'));
			$out->topcat = $out->cid;
			$out->subcat = $out->cid;
			$out->catcols = intval($this->cat_cols1);
			if($out->cid > 0 || $out->root_id > 0){


				$out->catcols = intval($this->cat_cols2);

				if($out->cid > 0){
					$out->cat = CBP_cats::getsub($out->cid);
					if($out->cat){
						if($out->cat->pid > 0) $out->topcat = $out->cat->pid;
					}
				}
			}
			$out->cid = intval($out->cid);
			$out->subcat = intval($out->subcat);
			$out->topcat = intval($out->topcat);
			$out->feat_id = intval($this->cat_feat);
			$out->cat_label = $this->cat_label;
			$out->aff = $this->aff;
			$out->root_cat = CBP_cat::get($out->root_id); // CLASS

			if($out->root_id == $out->cid){
				$out->curr_cat  = &$out->root_cat;
			}else{
				$out->curr_cat = CBP_cat::get($out->cid);
			}
			$this->_requested = $out;
			unset($out);
		}
		return $this->_requested;
	}




	function restore(){
		$this->delete();
		$this->save();
	}


	function isEmpty(){
	    return empty($this->options);
	}

	function getArray(){
	    return $this->options;
	}

	public function get_options() {
		return get_option(CBP_OPT);
	}
	public function load_options() {
		$dat = $this->get_options();
		$dat = empty($dat) ? array() : $dat;
		$this->options = wp_parse_args($dat, $this->defaults);
		$this->options = apply_filters($this->_base,$this->options); // append
		return $this->options;
	}



	function __get($k){
		return (isset($this->options[$k])) ? $this->options[$k] : $this->getdefault($k);
	}
	function __set($k,$v){
		$this->options[$k] = $v;
	}
	function set_default($id) {
		$this->$id = $this->getdefault($id);
		return $id;
	}


	function save($resetFields='') {

		if($resetFields != ''){
			// Optional pass in a list of fields and reset them to default value
			array_map(array(&$this,'set_default'), explode(',',$resetFields) );
		}
		// filter_options
		$this->options = CBP::array_minimize($this->options, $this->defaults);

		wp_cache_delete(CBP_OPT, 'options');
		wp_cache_delete('notoptions', 'options');

		add_option(CBP_OPT,$this->options);  // add if not exists.
		update_option(CBP_OPT,$this->options); //  update because above does nothing if exists
		$this->options = $this->load_options();
	}


	function delete(){
		delete_option(CBP_OPT);
	}

	public function create_setting( $args = array() ) {

		$defs = array(
			'id'      => 'default_field',
			'title'   => '',
			'desc'    => '',
			'std'     => '',
			'type'    => 'text',
			'section' => 'general',
			'choices' => '',
			'class'   => '',
			'note'   => '',
			'sep' => '0'
		);
		$info = wp_parse_args($args, $defs);

		if ( $info['type'] == 'checkbox' ) $this->checkboxes[] = $info['id'];

		$s = '' . $info['section'];

		if(isset($this->sections->$s)){
			$sect = &$this->sections->$s;
			if(!isset($sect->fields)) $sect->fields = array();
			$sect->fields[] = $info;
		}

	}



	function exists($id) {
		return isset($this->defaults[$id]);
	}
	function getdefault($id) {
		return (isset($this->defaults[$id])) ? $this->defaults[$id] : null;
	}


	function select( $id , $value, $choices, $field_class) {
		$out = '';
		$out .= '<select class="select' . $field_class . '" name="cbpress_options[' . $id . ']">';
		foreach ( $choices as $v => $label ){
				$out .= '<option value="' . esc_attr( $v ) . '"' . selected( $v, $value, false ) . '>' . $label . '</option>';
		}
		$out .= '</select>';
		return $out;
	}

	function load_catdata() {
		$blank = array('0'=>'(optional)');
		$this->catdata = $blank + CBP_cats::category_dropdown_array(0, array('showpath'=>0,'type'=>null) );
	}


	public function display_setting( $args = array(), $showdesc=true ) {

		global $cbpress;
		extract( $args );
		$out = '';
		$value = $this->$id;
		$field_class = '';

		$value = $this->$id;

		$def_value = $this->getdefault($id);
		$placeholder = ' placeholder = "$def_value"';




		$name = "cbpress_options[" . $id . "]";

		switch ( $type ) {

			case 'select-cat':

				if(is_null($this->catdata)){
						$this->load_catdata();
				}
				$out .=  $this->select( $id , $value, $this->catdata, $field_class);
				if ( $desc != '' ) $out .= '<br />';

				break;


			case 'pagelist':
				$pageargs = array(
					'name' => $name,
					'child_of'     => 0,
					'echo'=> 0,
					'depth'=> 0,
					'selected' => $value,
					'sort_order'   => 'ASC',
					'sort_column'  => 'post_title',
					'hierarchical' => 1,
					'exclude'      => '',
					'include'      => '',
					'meta_key'     => '',
					'meta_value'   => '',
					'authors'      => '',
					'exclude_tree' => ''
				);

				$out .=  wp_dropdown_pages($pageargs);
				if ( $desc != '' ) $out .= '<br />';
				break;

			case 'checkbox':
				$out .= '<input type="hidden" name="'.$name.'" value="0" /> ';
				$out .= '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="'.$name.'" value="1" ' . checked( $value, 1, false ) . ' /> ';
				break;

			case 'select':
				$out .=  $this->select( $id , $value, $choices, $field_class);
				if ( $desc != '' ) $out .= '<br />';
				break;

			case 'radio':
				$i = 0;
				foreach ( $choices as $v => $label ) {
					$out .= '<input class="radio' . $field_class . '" type="radio" name="'.$name.'" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $v, $value, false ) . '>';
					$out .= '<label for="' . $id . $i . '">' . $label . '</label><br> ';
					// if ( $i < count( $choices ) - 1 ) echo '<br />';
					$out .= ' ';
					$i++;
				}
				if ( $desc != '' ) $out .= '<br />';
				break;

			case 'textarea':
				$out .= '<textarea class="' . $field_class . '" id="' . $id . '" name="'.$name.'" rows="5" cols="30">' . wp_htmledit_pre( $value ) . '</textarea>';
				if ( $desc != '' ) $out .= '<br />';
				break;

			case 'color':
		 		$out .= '<input autocomplete="off" class="color" type="text" id="' . $id . '" name="'.$name.'" style="width:100px;" value="' . esc_attr( $value ) . '" />';
				break;

			case 'display':
		 		$out .= esc_attr( $value );
		 		break;

			case 'text':
			default:

		 		$placeholder;
		 		$value = esc_attr( $value );
		 		$pl = esc_attr($def_value);
		 		$classnames = trim('regular-text cbp-text-box ' . $field_class);
		 		$out .= '<div class="fieldwrapper"><input class="'.$classnames.'" type="text" id="'.$id.'" name="'.$name.'" value="'. $value.'" placeholder="'.$pl.'" /></div>';





		



				// if ( $desc != '' ) $out .= '<br />';
				//  form-field
		 		break;

		}

		if ($showdesc && $desc != '' ){
			if($type == 'checkbox') {
				$out .=  '<span class="description" for="' . $id . '">' . $desc . '</span>';
			}else{
				$out .=  '<div class="description">' . $desc . '</div>';
			}



				if($id == 'import_zip'){
					$dv = $this->getdefault($id);
					$out .=  '<br>Default URL: <a href="' . $dv . '" target="_blank" class="bluenon">' . $dv . '</a><br><br>';
				}

		}
		return $out;
	}





	function save_notification() {

		//	$msg = CBP::getv('msg');
		//	if (!$msg == '') {
		//			echo '<div class="updated"><strong><p>' . $msg . '</p></strong></div>';
		//	}
		// Save main settings
		if ( isset( $_POST['save'] ) && $_GET['page'] == 'cbpress-settings' ) {
			echo '<div class="updated"><p><strong>' . __( 'Settings saved', CBPRESS_TRANS ) . '</strong></p></div>';
		}


		// Save custom css
		if ( isset( $_POST['save-css'] ) && $_GET['page'] == 'cbpress-settings' ) {
			echo '<div class="updated"><p><strong>' . __( 'Custom CSS saved', CBPRESS_TRANS ) . '</strong></p></div>';
		}
	}

	public function form_buttons() {
			?>
			<div class="submit">
			<input type="submit" name="update-options-<?php echo CBPRESS_NAME ?>" class="button-primary" value="<?php _e('Save Changes',CBPRESS_TRANS) ?>" />
			<input type="submit" name="delete-settings-<?php echo CBPRESS_NAME ?>"
			onclick='return confirm("<?php _e('Are you sure you want to reset the plugin settings?',CBPRESS_TRANS); ?>");'
			class="swg_warning" value="<?php _e('Delete/Reset Settings',CBPRESS_TRANS) ?>" />
			</div>

			<input type="submit" name="delete-settings-cbpress" value="Reset Options" onclick='return confirm("Are you sure you want to reset the plugin settings?")'>



			<?php
	}

	public function registration() {
		$values = cbpressfn::getQueryString();
		$values+= cbpressfn::getFormValues();
		$step = (isset($_GET['step'])) ? $_GET['step'] : CBP_api::get_step();
		CBP_api::form_activation();
		echo '<div id="find_order" style="display:none;">';
				CBP_content::_findorder_dialog();
		echo '</div>';
	}


	public function form() {

		global $cbpress;


		$this->sections = &$this->data->tabs;
		$m = &$this->data->meta;
		foreach ($m as $ss) {
			$this->create_setting( $ss );
		}
		$this->save_notification();

			echo '<p>For help with settings, see online <a href="http://version.cbpress.com/guide.php" target="_blank">documentation</a></p>';

		echo '<form name="settingsform" id="settingsform" action="'. admin_url( 'admin.php' ) .'" class="cbpress-form" method="post" autocomplete="off">';
		$this->form_tabbed();
		CBP::wp_nonce_field(CBP_HOOK_NONCE);
		echo '<input type="hidden" name="action" value="cbp-save-opts" />';
		echo '<div class="submit">';
		echo '<input type="submit" name="update-options-cbpress" class="button-primary" value="Save Changes" />';
		echo '</div>';
		echo '</form>';
		wp_print_scripts( 'cbpress-cookie' );
	}


	public function form_all() {

		global $cbpress, $wp_version;


		 ?>


			<style>
			.form-field{
				float:left;
				margin-bottom:8px;
				border:2px solid #888;
				padding:5px;
				font-family:Arial, Helvetica, sans-serif;
				font-size:14px;
				width:300px;
			}
			.form-holder{
				width:400px;
			}
			.tabs input[type=text] {
				width: 400px;
			}

			.tabs2 input[type=text],
			.tabs2 textarea,
			.tabs2 select {
				border: 1px solid #ccc;
    					border:solid 1px #989898;
				-webkit-appearance: none;

			}

			</style>

			<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery('.xxxform-field').each( function () {
						jQuery(this).val(jQuery(this).attr('placeholder'));
						jQuery(this).css({color:'#ccc'});
					});

					jQuery('.xxxxform-field').focus(function(){
						if ( jQuery(this).val() == jQuery(this).attr('placeholder') ){
						jQuery(this).val('');
						jQuery(this).css({color:'#000'});
						}
					});
					jQuery('.xxxxxform-field').blur(function(){
						if (jQuery(this).val() == '' ){
						jQuery(this).val(jQuery(this).attr('placeholder'));
						jQuery(this).css({color:'#ccc'});
						}
					});
				});
			</script>


				<?php


					echo '<table cellpadding="0" width="" class="xwidefat xtabledef">';
					foreach ( $this->sections as $section ){
						$section = (array) $section;
						$ss = $section['id'];
						$fields = (isset($section['fields'])) ? $section['fields'] : array();
						if(count($fields)){
							echo '<thead><tr><th colspan="3" class="heading app-edit-icon"><div class="">' . $section['label'] . '</div></th></tr></thead>'."\n";
							foreach ( $fields as $field ){
								$id = $field['id'];
									$note = ($field['note'] != '') ? '<span class="note"> <b>Note:</b> ' . $field['note'] . '</span>' : '';
									$desc = '<span class="description2222">' . $field['desc'] . $note . '</span>';
								echo '<tr id="options_' . $id . '">'."\n";
								echo '<th class="arrow" nowrap><label for="'. $id .'">' . $field['title'] . '</label></th>';
								if($field['type'] == 'checkbox' && 1 == 2) {
									echo '<td colspan="2">';
										echo $this->display_setting($field,false);
										echo $desc;
									echo '</td>';
								}else{
									echo '<td>';
									echo $this->display_setting($field,false);
									echo '</td>';
									echo '<td>';
									echo $desc;
									echo '</td>';
								}
								echo '</tr>';
								if($field['type'] != 'checkbox' && $field['desc'] != '') {
											//  echo '<tr class="no-top-border"><td></td><td>' . $desc . '</td></tr>';
								}
								if(1 == 2){
									echo '</td>';
									echo '<td>';
										echo $field['desc'];
										if($field['note'] != '') echo ' <div class="note"> <b>Note:</b> ' . $field['note'] . '</div>';
									echo '</td>';
									echo '</tr>';
								}
							}
						}
					}
					echo '</table>';




					// echo '<table><tr><td>';
					// echo '</td></tr></table>';
				?>
			<div id="cb_setting_tabs" class="tabs">
			</div>

		<?php

	}

	public function form_tabbed() {


		global $wp_version;
		?>


			<script type="text/javascript">
			   jQuery(document).ready(function(){
			       <?php if(version_compare($wp_version,"2.8-beta2",">=")) { ?>
						jQuery("#cb_setting_tabs").tabs({
								fx: { opacity: "toggle", duration: "fast" },
								cookie: { expires: 1 }
							}
						);
			       <?php } else { ?>
						jQuery("#cb_setting_tabs > ul").tabs({
								fx: { opacity: "toggle", duration: "fast" },
								cookie: { expires: 1 }
							}
						);
			       <?php } ?>


			   });
				//	cbp_textbox_value_changed(this,'default', '{$id}_reset')
			</script>


			<style>
				.tab_content {
					padding: 10px 20px 20px 20px !important;
				}
				.descbox {
					color: #111;
					border-bottom: 1px dotted #666666;
					padding: 10px 0px;
					font-size: 13px;
					margin-bottom: 15px;
				}
				.system_panel td {
					padding-right: 30px;
					vertical-align: top;
				}
			</style>


			<div class="container">
			<div id="cb_setting_tabs" class="tabs">
				<ul>
					<?php
					foreach ( $this->sections as $section ){
						$section = (array) $section;
						$ss = $section['id'];
						echo '<li><a href="#options_' . $ss . '">' . $section['label'] . '</a></li>'."\n";
					}
					?>
				</ul>
				<div class="tab_container">
					<?php
					foreach ( $this->sections as $section ){
						$ss = $section->id;
						$fields = (isset($section->fields)) ? $section->fields : array();
						echo '<div id="options_' . $ss . '" class="tab_content">'."\n";
							echo '<div class="descbox">' . $section->desc . '</div>';
							if(isset($section->action)){
								do_action($section->action);
							}else{
								echo '<table cellpadding="0" class="form-table">';
								foreach ( $fields as $field ){
									$id = $field['id'];
									$sep = $field['sep'];




									if($sep){

										echo '<tr>';
										echo '<th colspan="2"><div class="cbpress-divider"></div>';
										echo '</th>';
										echo '</tr>';

									}


									echo '<tr>';
									echo '<th><strong>' .  $field['title'] . '</strong></th>';
									echo '<td>';
									echo $this->display_setting($field);
									echo '</td>';
									echo '</tr>';
								}
								echo '</table>';
							}
						echo '</div>'."\n";
					}
					?>
				</div>
			</div>
			</div>
		<?php
	}


}
