<?php

	class Cbpress {

		// extends CBP_base
		var $page_keywords = array();
		var $script;
		var $widgets = array();
		var $metakey = '_cbpress_loaded';
		var $access = 'edit_pages';
		var $goolib = 'http://ajax.googleapis.com/ajax/libs/';

		private $db;
		public $app = CBPRESS_NAME;
		public $hook = CBPRESS_NAME;
		public $que, $msg, $message, $page, $ispp;
		public $regdata, $feed;
		public $options;
		public $plugin_base = CBP_PLUGINBASE;
		public $err = false;

		public $token = 'clickbank';

		private $_vars = array();
		private $menudata = null;

		static protected $opts = null;
		private static $instance = null;

		static public function getter() {
			if(!self::$instance){

				self::$instance = new self();
				self::$opts = &self::$instance->options;
			}
			return self::$instance;
		}

		static function get($k) {
			return self::$opts->$k;
		}

		// from BASE
		// from BASE end





		function is_ajax_request() {
			return isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest";
		}


		public function __construct() {
			global $wpdb;
			$this->db = &$wpdb;
			$this->ispp = CBP::is_plugin_page();
			$this->page = CBP::current_page();
			$this->regdata = CBP_api::init();
			$this->que = CBP_data::get('que');
			$this->feed = CBP_data::get('feed');
			$this->options = CBP_settings::getter();




			$this->init_uploader();



			add_filter('query_vars', array(&$this, 'init_query_vars'));
			$this->script = $_SERVER["PHP_SELF"];
			$this->script = end(explode("/", $this->script));

			new CBP_ajax($this->options);
			$this->actions_filters();


		}

















		// UPLOADER For Product Form

		function init_uploader() {



			if (isset($_GET['page']) && $_GET['page'] == 'cbpress-products') {
				if (isset($_GET['tab']) && ($_GET['tab'] == 'add' || $_GET['tab'] == 'edit')) {
					add_action('admin_print_scripts', array(&$this, 'my_admin_scripts' ) ); 
					add_action('admin_print_styles', array(&$this, 'my_admin_styles' ) );
				}
			}
		}

		function my_admin_scripts() {



			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
			wp_register_script('my-upload', WP_PLUGIN_URL.'/cbpress/admin/js/form_product.js', array('jquery','media-upload','thickbox'));
			wp_enqueue_script('my-upload');
		}

		function my_admin_styles() {
			wp_enqueue_style('thickbox');
		}
































		// from BASE

		public function __get($k) {
			return (isset($this->_vars[$k])) ? $this->_vars[$k] : null;
		}

		public function __set($k, $v) {
			$this->_vars[$k] = $v;
		}

		public function getRegKey($k) {
			return CBP_api::get($k);
		}

		public function installed() {
			$ins = CBP_api::get('installed');
			$lev = CBP_api::get('currlevel');
			if($ins && $lev >= 2){
				return '1';
			}
			return '0';
		}

		function add_action($actions, $function = '', $priority = 10, $accepted_args = 1) {
			if(!is_array($actions)){
				$actions = array($actions);
			}
			foreach ($actions as $action) {
				$this->que->actions[] = array('action' => $action, 'function' => $function);
				add_action($action, array(&$this, $function == '' ? $actions[0]
										  : $function), $priority, $accepted_args);
			}
		}

		function add_filter($filters, $function = '', $priority = 10, $accepted_args = 1) {
			if(!is_array($filters)){
				$filters = array($filters);
			}
			foreach ($filters as $filter) {
				$this->que->filters[] = array('filter' => $filter, 'function' => $function);
				add_filter($filter, array(&$this, $function == '' ? $filters[0]
										  : $function), $priority, $accepted_args);
			}
		}

		function queue_message($type = '', $message = '', $callback = '') {
			$this->que->messages[] = (object) get_defined_vars();
		}

		function print_messages() {
			$out = '';
			foreach ($this->que->messages as $m) {
				$out .= '<ul class="alertbox">';
				$out .= '<div class="text">' . $m->message . '</div>';
				$out .= '</ul>';
			}
			$this->que->messages = array();
			return $out;
		}

		// from BASE end






















		function zoo__jquery_reset() {
			$src = 'http://code.jquery.com/jquery-latest.pack.js';

			$src = $this->goolib . 'jquery/1.3.2/jquery.min.js';

			if(is_admin()){
				$sui = $this->goolib . 'jqueryui/1.8.8/jquery-ui.min.js';
			} else {
				$sui = $this->goolib . 'jqueryui/1.7.2/jquery-ui.min.js';
			}
			wp_deregister_script('jquery');
			wp_register_script('jquery', ("$src"));
			wp_enqueue_script('jquery');

			wp_deregister_script('jquery-ui');
			wp_register_script('jquery-ui', "$sui");
			wp_enqueue_script('jquery-ui', 'jquery-ui', array('jquery'));

		}

		function zoo__jquery_admin() {
			global $concatenate_scripts;
			$concatenate_scripts = false;
			wp_deregister_script('jquery');
			wp_register_script('jquery', ($this->goolib . 'jquery/1/jquery.min.js'), false, '1.x', true);
		}

		function zoo__jquery_ui() {
			wp_enqueue_script('jquery');
			$src_ui = $this->goolib . 'jqueryui/1.8.8/jquery-ui.min.js';
			wp_register_script('jquery-ui', "$src_ui");
			wp_enqueue_script('jquery-ui', 'jquery-ui', array('jquery'));
		}

		function zoo__admin_print_scripts() {


			wp_enqueue_script('jquery-ui', 'jquery-ui', array('jquery'));
			wp_enqueue_script('jquery-ui-tabs', 'jquery-ui-tabs', array('jquery', 'jquery-ui-core'));
			wp_enqueue_script('jquery-ui-dialog', 'jquery-ui-dialog', array('jquery', 'jquery-ui-core'));


			wp_enqueue_script('thickbox', 'thickbox', array('jquery'));

			wp_enqueue_style('cbpress-shortcode-generator');
			wp_enqueue_script('cbpress-shortcode-generator');

			if(isset($_GET['page']) && $this->ispp){
				foreach ($this->que->scripts as $s) {
					wp_enqueue_script($s, $s, array('jquery'));
				}
			}
			$this->zoo__admin_localize();
		}

		function zoo__admin_print_styles() {
			if(isset($_GET['page']) && $this->ispp){

				wp_enqueue_style('google-font');

				foreach ($this->que->styles as $s) {
					wp_enqueue_style($s);
				}
				$this->que->styles = array();
			}
		}

		function zoo__admin_localize() {

			$editing = '0';
			global $pagenow;
			$generator_includes_pages = array('post.php', 'edit.php', 'post-new.php', 'index.php');
			if(in_array($pagenow, $generator_includes_pages)){
				$editing = '1';
			}
			$redir = $_SERVER['REQUEST_URI'];
			$redir = remove_query_arg(array('msg'), $redir);
			if($this->ispp || $editing == 1){
				wp_enqueue_script('cbpress-admin');

				$cbpressjs = array(
					'lid' => intval(@$_REQUEST['lid']),
					'cid' => intval(@$_REQUEST['cid']),
					'thispage' => CBP::thispage(),
					'page' => $this->page,
					'editing' => $editing,
					'ispp' => $this->ispp,
					'ajaxurl' => CBP::ajax_url('', ''),
					'ax' => CBP::ajax_url('', 'salt'),
					'salt' => wp_create_nonce('salt'),
					'nonce' => wp_create_nonce(CBP_HOOK_NONCE),
					'sb' => intval($this->options->admin_sb),
					'base' => CBP_BASE_URL,
					'blogurl' => CBP_BLOGURL,
					'imageurl' => CBP_IMG_URL,
					'please_wait' => __('Please wait...', CBPRESS_TRANS),
					'type' => 1,
					'redirector' => $redir,
					'are_you_sure' => __('Are you sure?', CBPRESS_TRANS),
					'none_select' => __('No items have been selected', CBPRESS_TRANS),
					'showdebug' => 0
				);
				$cbpressjs = array_merge($cbpressjs, (array) $this->regdata); // merge regdata for setup page
				wp_localize_script("cbpress-admin", 'cbpressjs', $cbpressjs); // cbpressjs  js scope
			}
		}

		function zoo__admin_head() {
			if($this->ispp){
				wp_admin_css('css/dashboard');
				wp_enqueue_script('cbpress-admin');
			}

			if($this->script == "widgets.php" || $this->script == "themes.php" || $this->script == "plugins.php"){
				echo('<link rel="stylesheet" href="' . CBP_CSS_URL . 'admin_widgets.css" type="text/css" media="screen" />');
			}
		}

		function register_resource($name) {
			$name = trim($name);
			$id = str_replace('.', '-', $name);
			$n_scr = "js/$name.js";
			$n_css = "css/$name.css";

			if($id == 'jquery-ui'){
				if(1 == 2){
					wp_deregister_script('jquery-ui');
					wp_register_script('jquery-ui', $this->goolib . 'jqueryui/1.8.8/jquery-ui.min.js');
					wp_register_style('jquery-ui', $this->goolib . 'jqueryui/1.8.16/themes/base/jquery-ui.css');
				} else {
					wp_register_style('jquery-ui', $this->goolib . 'jqueryui/1.8.16/themes/base/jquery-ui.css');
					if(file_exists(CBP_ADMIN_DIR . $n_scr)){
						wp_register_script($id, CBP_ADMIN_URL . $n_scr, array('jquery'));
					}
				}
			} else {
				if(file_exists(CBP_ADMIN_DIR . $n_scr)){
					wp_register_script($id, CBP_ADMIN_URL . $n_scr, array('jquery'));
				}
				if(file_exists(CBP_ADMIN_DIR . $n_css)){
					wp_register_style($id, CBP_ADMIN_URL . $n_css);
				}
			}
			CBP_data::get('que')->scripts[] = $id;
			CBP_data::get('que')->styles[] = $id;

			unset($n_scr, $n_css, $name);

			return $id;
		}

		function actions_filters() {

			add_action('init', array(&$this, 'init'));
			add_action('admin_init', array(&$this, 'admin_init'));
			add_action('admin_menu', array(&$this, 'admin_menu'));
			add_action('admin_head', array(&$this, 'zoo__admin_head'));
			add_action('widgets_init', array(&$this, 'widgets_init'));

			if(1 == 1){
				add_action('admin_print_scripts', array(&$this, 'zoo__admin_print_scripts'));
				add_action('admin_print_styles', array(&$this, 'zoo__admin_print_styles'));
			}

			add_filter('custom_menu_order', array(&$this, 'enable_custom_menu_order'));
			add_filter('menu_order', array(&$this, 'custom_menu_order'));

			// add_action('edit_form_advanced', array(&$this, 'extend_post_form'));

			// add_filter('plugin_row_meta', array(&$this, 'plugin_links'),10, 2);
			add_filter('plugin_action_links', array(&$this, 'plugin_actions'), 10, 2);
			// add_action('after_plugin_row', array(&$this,'plugin_check_version'), 10, 2);

			$this->post_actions();

			// register scripts and styles






			// only load old jqueru-ui for importer page

			// if($this->page == 'import'){

			if($this->ispp){

				$resources = 'jquery, jquery-ui, jquery-ui-core, jquery-ui-dialog, jquery-ui-tabs, jquery-cookie, jquery-form';



			} else {

				$resources = 'jquery, jquery-ui-core, jquery-ui-dialog, jquery-ui-tabs, jquery-cookie, jquery-form';

			}





			$resources .= ', cluetip, jquery-dynatree, jquery-autocomplete, cbpress-admin'; // jquery-autocomplete,
			// jquery-dump,
			$resources = explode(',', $resources);
			$resources = array_map(array(&$this, 'register_resource'), $resources);
			// $this->zoo_jquery_admin();

			wp_register_style('google-font', 'http://fonts.googleapis.com/css?family=Istok+Web:400,700');

			if(is_admin()){

				// settings
				add_action('cbp-tab-register', array(&$this->options, 'registration'));
				add_action('cbp-tab-settings', array(&$this->options, 'form'));
				add_action('admin_action_cbp-save-css', array(&$this->options, 'css_save'));
				add_action('cbp-save-css', array(&$this->options, 'css_save'));
				add_action('admin_action_cbp-create-page', array(&$this->options, 'create_page'));
				add_action('cbp_search_box', array(&$this, 'search_box'));

				new CBP_actions();

			} else {

				add_filter(CBP_HOOK_LID, array(&$this, 'template_get_lid')); // current LID for addons
				$this->template_redirect();
				// add_action( 'template_redirect', array( &$this, 'template_redirect' ) );
			}
			add_action('admin_bar_menu', array(&$this, 'admin_bar_menu'), 300);
			add_action('admin_notices', array(&$this, 'admin_notices'));

		}

		function r_sanitize2($s) {
			$result = preg_replace("/[^a-zA-Z0-9'-]+/", " ", html_entity_decode($s, ENT_QUOTES));
			$result = preg_replace("/[^a-zA-Z0-9]+/", " ", html_entity_decode($s, ENT_QUOTES));
			return $result;
		}

		/*** shared by widget and shortcodes ***/
		public function template_get_content() {

			global $post;
			global $wp_query;



			$ths_seo = '';

			$id = $post->ID;
			$id = (empty($id)) ? get_the_ID() : $id;
			$is_home = ($id == get_option('page_on_front')) ? true : false;

       		$words = '';

			if (!is_home()) {
				$keys = array();
				$taxonomies = array();
				$get_taxonomies = get_taxonomies();
				foreach ($get_taxonomies as $a_taxonomy) {
					$taxonomies[] = $a_taxonomy;
				}
				$terms = wp_get_object_terms($id, $taxonomies);
				foreach ($terms as $k => $term) {
					$keys[] = $term->name;
				}
				$words = implode(', ',$keys);
			}
					$words = cbpressfn::remove_words($words);

			if (empty($words)){


					$k = '';
					$out = (object) array();
					$out->is_home = is_home();
					$out->is_front_page = is_front_page();
					$out->is_single = is_single();
					$out->is_page = is_page();
					$out->is_category = is_category();
					$k = array();
					$k[] = strip_tags( $post->post_title );
					$k[] = strip_tags( $post->post_content );
					$tags = wp_get_post_tags($post->ID);






					// abort($post);


					foreach ($tags as $tag) {
						$k[] = $tag->name;
					}
					if(is_home() || is_front_page()){
					} elseif(is_page()) {
					} elseif(is_single()) {
						// LOAD POST TITLE AS DESCRIPTION
					} elseif(is_category()) {
						// LOAD CATEGORY DESCRIPTION
						$thecat = get_the_category();
						$k[] = single_cat_title('', false);
						$k[] = $thecat[0]->category_description;
						$k[] = $thecat[0]->cat_name;
					}

					$k = implode(', ',$k);
					$k = $this->r_sanitize2($k);

					$k .= ' ' . trim(strip_tags(nl2br(str_replace("\r\n", "  ", addslashes($k))))) . " ";
					$k = cbpressfn::clean_string_input($k);
					$k = cbpressfn::remove_words($k);
					$k = explode(',', $k);
					$words = array();
					foreach ($k as $word) {
						if(strlen($word) > 2){
							$words[] = $word;
						}
					}
			}else{

				$words = explode(', ',$words);
			}

			$this->page_keywords = $words;

			return $words;
		}



		function enable_custom_menu_order($flag) {
			return TRUE;
		}

		function custom_menu_order($menu_order) {

			// Add a new separator to the menu array
			global $menu;
			$menu[] = array('', 'read', 'separator-cb', '', 'wp-menu-separator');

			$lookfor = 'cbpress/cbpress.php';
			$lookfor = 'cbpress';

			// Remove the current instance of cbpress
			$current_position = array_search($lookfor, $menu_order);
			unset($menu_order[$current_position]);

			// Create a new array to hold the menu order
			$new_menu_order = array();

			// Replicate the existing order,
			// inserting cbpress and separator where desired
			foreach ($menu_order as $menu_item) {
				$new_menu_order[] = $menu_item;
				if($menu_item == 'edit-comments.php'){
					$new_menu_order[] = 'separator-cb';
					$new_menu_order[] = $lookfor;
				}
			}

			return $new_menu_order;
		}

		function init_query_vars($arr) {
			$key = $this->options->link_cloaker;
			$arr = $arr + array('lid', 'cid', 'hop', 'cbpress-ajax', 'cbpress-product-id', 'fa', $key);
			return $arr;
		}

		function admin_body_class($classes) {
			if($this->ispp){
				$classes .= "cbpress";
			}
			return $classes;
		}

		function admin_init() {
			if($this->ispp){
				CBP_content::init();
				add_filter('admin_body_class', array(&$this, 'admin_body_class'));
			}
			$this->admin_listen();
		}

		function init() {

			## fronend loader

			new CBP_shortcodes();

			if(!is_admin()){
				if(!preg_match("/\/wp-login\.php/", $_SERVER["REQUEST_URI"])){

					$css = &$this->options->css;
					$loadme = ($this->options->css_exists('custom')) ? $css->url->custom : $css->url->template;
					wp_register_style('cbpress-frontend', $loadme);
					wp_register_script('cbpress-frontend', CBP_FRONT_URL . 'frontend.js', array('jquery'));

					add_filter('wp_print_scripts', array(&$this, 'frontend_wp_scripts_print'));
					add_filter('wp_print_styles', array(&$this, 'frontend_wp_styles_print'));
				}
			} else {

				new CBP_feed();


				new CBP_editor();
			}




			if($this->ispp){

				if (!is_dir(CBP_FRONT_DIR)) {
					// mkdir(CBP_FRONT_DIR);
					CBP_install::reset_folders();
				}



			}

		}

		function plugin_actions($links, $file) {
			if($file == CBP_PLUGIN){
				$links[] = '<a href="admin.php?page=cbpress-setup">Registration</a>';
			}
			return $links;
		}

		public function widgets_init() {
			register_widget('cbpress_category_widget');
			register_widget('cbpress_list_widget');
			register_widget('cbpress_search_widget');
			register_widget('cbpress_context_widget');
		}

		public function version_checks() {
			global $wp_version, $wpdb, $pagenow;

			$messages = array();
			$linker = ' <a href="%s" class="fix">%s</a>';
			$errors = (object) array();
			$errors->nopage = 'Cbpress cannot find a <b class="nowrap">[cbpress]</b> shortcode in any of your WordPress pages.';
			$errors->noprod = 'You have not imported any ClickBank products';
			$errors->nowidget = 'You have not added the Marketplace Category Widget into your theme.';
			$errors->notable = 'Database tables are not installed.';
			$errors->activate = 'Activation required to enable your ClickBank affiliate ID in affiliate links';
			$errors->phpver = 'Cbpress runs best using PHP 5.2 or greater. You are currently running ' . PHP_VERSION;

			// $fix = CBP::link(CBP::admin().'&fa=resetdb','Click here to fix');

			## tables
			if(!CBP_install::table_exists()){

				$messages[] = sprintf($errors->notable . $linker, CBP::admin() . '&fa=resetdb', 'continue');


			} else {

				if(!CBP_query::getPoductCount()){
					if($this->page != 'import'){
						$messages[] = sprintf($errors->noprod . $linker, CBP::admin('import'), 'go');
					}
				}

				## activate
				if($this->page != 'setup'){
					if(!CBP_api::activated()){
						$messages[] = sprintf($errors->activate . $linker, CBP::admin('setup'), 'go');
					}
				}

				if(!CBP_shortcodes::getMallPageID()){

					$u = CBP::admin() . '&fa=createpage';
					$u = admin_url('admin.php?action=cbp-create-page');

					$messages[] = sprintf($errors->nopage . $linker, $u, 'create one for me');
				}


				## server checks
				$check = CBP::getServerAbilities();
				$messages = array_merge((array) $messages, (array) $check->messages);
			}

			## php
			if(version_compare(PHP_VERSION, CBP_MIN_PHP, '<')){
				$messages[] = $errors->phpver;
			}
			if($messages){
				foreach ((array) $messages as $msg) {

					$this->queue_message('info', $msg, '');
				}
			}

			if(1 == 2){
				## wp
				if(!version_compare($wp_version, CBP_MIN_WP, ">=")){
					$msg = "Cbpress recommends running WordPress " . CBP_MIN_WP . " or greater.";

					$this->queue_message('warning', $msg);
				}

				## memory
				$mem = CBP::get_memory_usable();
				if(26214400 > $mem || 1 == 2){
					$setmem = cbpressfn::return_bytes_nice(CBP::get_memory_limit() + 27000000);
					$msg = 'Your site has less than 25MB of memory available. For optimal performance, try setting';
					$msg .= " <code>memory_limit = $setmem</code> in your" . "<code>php.ini</code>";
					$this->queue_message('info', $msg);
				}
			}

			unset($errors);
		}

		public function admin_notices() {
			global $wp_version, $pagenow;
			if($pagenow == 'plugins.php'){
				return false;
			}
			if($this->ispp){
				$this->version_checks();
			}
		}

		public function admin_bar_menu() {
			global $wp_admin_bar;
			$par = 'wpcbp-menu';
			$wp_admin_bar->add_menu(array('id' => $par, 'title' => __('CBPRESS'), 'href' => CBP::admin(''),));
			$wp_admin_bar->add_menu(array('parent' => $par, 'id' => 'wpcbp-settings', 'title' => __('* Settings'), 'href' => CBP::admin('settings'),));
			$wp_admin_bar->add_menu(array('parent' => $par, 'id' => 'wpcbp-cats', 'title' => __('* Categories'), 'href' => CBP::admin('cats'),));
			$wp_admin_bar->add_menu(array('parent' => $par, 'id' => 'wpcbp-products', 'title' => __('* Products'), 'href' => CBP::admin('products'),));
			$wp_admin_bar->add_menu(array('parent' => $par, 'id' => 'wpcbp-lists', 'title' => __('* Custom Lists'), 'href' => CBP::admin('lists'),));
			$wp_admin_bar->add_menu(array('parent' => $par, 'id' => 'wpcbp-import', 'title' => __('* Importer'), 'href' => CBP::admin('import'),));
			$wp_admin_bar->add_menu(array('parent' => $par, 'id' => 'wpcbp-cbpress-visit', 'title' => __('Visit cbpress.com'), 'href' => 'http://www.cbpress.com/', 'meta' => array('target' => '_blank')));
			$wp_admin_bar->add_menu(array('parent' => $par, 'id' => 'wpcbp-cbengine-visit', 'title' => __('Visit cbengine.com'), 'href' => 'http://www.cbengine.com/', 'meta' => array('target' => '_blank')));


		}

		public function admin_listen() {

			# Listeners

			if(!$this->ispp){
				// pre populate new post form with product data
				$id = CBP::getv('cbpress-product-id', 0);
				if($id > 0){
					$product = CBP_prod::load($id);
					$product->wp_post_init();
					return false;
				}

			}

			$fa = @$_REQUEST['fa'];
			$fa = CBP::getv('fa');
			if($fa === null){
				return false;
			}

			if($this->ispp){
				$id = 0;
				if(count($info = explode('-', $fa)) > 1){
					// for togglecat-id, toggleprod-id, togglejoin-id, setroot-id, setindex-id
					$fa = $info[0];
					$id = $info[1];
				}
				$redir = true;
				$refer = wp_get_referer();

				if(in_array($fa, array('reinstall', 'install', 'uninstall', 'resetdb'))){
					CBP::wp_can_admin();
				}
				// abort(get_defined_vars());
				$opt = &$this->options;
				$msg = '';

				switch ($fa) {
					case 'createpage':
						do_action('cbp-create-page');
						break;
					case 'resetapi':
						CBP_api::delete();
						break;
					case 'togglecat':
						CBP_query::toggle_cat($id);
						break;
					case 'togglejoin':
						CBP_query::toggle_join($id);
						break;
					case 'toggleprod':
						CBP_query::toggle_prod($id);
						break;
					case 'setfeat':
						$opt->cat_feat = $id;
						$msg = 'Featured Category Set';
						break;
					case 'setroot':
						$opt->cat_root = $id;
						$msg = 'Root Category Set';
						break;
					case 'reinstall':
						CBP_install::reinstall();
						$msg = 'Reinstall Completed';
						break;
					case 'install':
						CBP_install::install();
						$msg = 'Install Completed';
						break;
					case 'uninstall':
						CBP_install::uninstall();
						$refer = admin_url('plugins.php');
						break;
					case 'resetdb' :
						$msg = 'Database has been reset';
						CBP_install::resetdb();
						$opt->save('cat_feat,cat_root');
						break;
					case 'resetoptions':
						$opt->restore();
						$msg = 'Options have been reset';
						break;
					case 'resetfolders':
						$msg = 'cbpress upload folders have been reset';
						CBP_install::reset_folders();
						break;
					default:
						$redir = false;
						break;
				}

				if($redir){
					$opt->save();
					if(isset($result)){
						unset($result);
					}
					$redirectto = remove_query_arg('fa', remove_query_arg('updated', $refer));

					CBP::redirector($msg, $redirectto);

					// wp_safe_redirect( $redirectto );
					// exit;
					return false;
				}

			}
		}

		function wp_redirect_cancel($location, $status) {
			return false;
		}

		public function template_get_lid() {
			$key = $this->options->link_cloaker;
			$id = CBP::getv($key);
			return trim($id);
		}

		public function template_redirect() {









			if(!is_admin()){
				$id = apply_filters(CBP_HOOK_LID, 0); // calls template_get_lid
				if(!empty($id)){
					if($id > 0){




						$tid = ($this->options->link_tid === null) ? '' : $this->options->link_tid;




							$item = CBP_prod::load($id, 'vin,lid,redirect_url,source,status,title');
							$item->redirect($tid);
							exit;
					}
				}
			}

		}

		public function __call($method, $args) {
			if(method_exists($this, $method)){
				$this->$method($args);
			}
		}

		function search_box() {
			CBP::getview('form_search');
		}

		/* ===============================
		 ADMIN MENU and PAGES
	  ================================*/

		function get_pagex($pg = '') {
			if($pg == ''){
				$pg = $this->page;
			}
			$out = $this->menudata->{$pg};
			return $out;
		}

		function admin_page_que() {
			$pg = $this->page;
			$pagex = $this->get_pagex($pg);
			return false;
		}

		function admin_page() {
			global $wp_version;

			$pg = $this->page;
			$showcrumbs = 0;

			$pagex = $this->get_pagex($pg);

			$inc = CBP_VIEWS_DIR . 'page_' . $pagex->include;

			$pageinfo = '<div class="pageinfo">';
			$pageinfo .= "<div class=\"pagetitle\">{$pagex->pagetitle}</div>";
			$pageinfo .= "<div class=\"pagedesc\">{$pagex->desc}</div>";
			$pageinfo .= "</div>";

			echo '<div class="page-wrap">';

			echo '<table width="100%" cellpadding="0" cellspacing="0">';
			echo '<tr><td>';

			echo '<div class="xwrap">';
			CBP_admin::header();
			echo '</div>';

			echo '</td></tr>';

			if($this->que->messages && 1 == 2){
				echo '<tr><td>';

				echo $this->print_messages();
				echo '</td></tr>';
			}

			echo '<tr><td>';
			if($showcrumbs){
				$crumbs = CBP::link(CBP::admin(''), 'cbpress');
				$crumbs .= ' ' . CBP::link(CBP::admin($pagex->id), $pagex->menutitle);
				echo CBP::div($crumbs, 'cbp_breadcrumbs');
			}
			if($pagex->showhead && 1 == 2){
				echo $pageinfo;
			}

			echo '</td></tr>';

			echo '<tr><td>';

			echo '<div class="wrap">';

			$msg = CBP::getv('msg');
			if($msg != ''){
				echo '<div class="updated"><strong><p>' . $msg . '</p></strong></div>';
			}

			echo '<div class="thepage page_' . $this->page . '">';
			do_action(CBP_HOOK_ADMIN);
			if(isset($pagex->action)){
				do_action($pagex->action);
			} else {
				if(is_file($inc)){
					include_once($inc);
				}
			}
			echo '</div>';
			echo '</div>';

			echo '</td></tr>';
			echo '</table>';
			echo '</div>';

			echo '<div id="dialog"></div>';

			## loading stuff
			echo '<div id="addresult"></div>';
			echo '<div id="loading" style="display: none">';
			echo '<img src="' . CBP_IMG_URL . 'loading.gif" alt="loading" width="32" height="32"/>';
			echo '</div>';
			echo '<img src="' . CBP_IMG_URL . 'progress.gif" width="50" height="16" alt="Progress" style="display: none"/>';
			echo '<div id="debug"></div>';


		}

		function admin_menu_add($pagetitle = '', $menutitle = '', $page = '', $func = 'admin_page', $admin_page_hook = '') {
			global $plugin_page;
			if($func == ''){
				$func = 'admin_page';
			}
			$subitem = add_submenu_page('cbpress', 'Cbpress ' . $pagetitle, $menutitle, 'edit_pages', $page, array(&$this, $func));
			if($admin_page_hook && ($subitem == 'cbpress_page_' . $plugin_page)){
				add_action(CBP_HOOK_ADMIN, $admin_page_hook, 100);
			}
			return $subitem;

		}

		function admin_menu() {
			$arr = CBP_data::get('menu');
			$this->menudata = (object) array();
			foreach ($arr as $row) {
				if($row['enable'] == 1){
					$this->menudata->$row['id'] = (object) $row;
				}
				unset($row);
			}
			unset($arr);
			$lid = @$_REQUEST['lid'];
			$curr = @$_REQUEST['page'];

			// add_menu_page("CBPress &rsaquo;", "Cbpress", $this->access, __FILE__, "gigpress_add", WP_PLUGIN_URL . "/gigpress/images/gigpress-icon-16.png");
			// abort($this->menudata);

			$m = add_menu_page("Edit CBPress", "CB PRESS", $this->access, CBPRESS_NAME, array(&$this, 'admin_page'), CBP_IMG_URL . 'icon.png');
			foreach ($this->menudata as $page => &$row) {
				$sub = $this->admin_menu_add($row->pagetitle, $row->menutitle, $row->page, $row->func);
				$row->callback = $sub;
				if($curr == $row->page){
					add_action('load-' . $row->callback, array(&$this, 'admin_page_que'));
				}
			}
			do_action('cbpress-admin-menu');
		}

		/* ===============================
		P O S T S   A C T I O N S
	  ================================*/

		function post_actions() {
			add_action('save_post', array(&$this, 'post_save'));
			add_action('post_updated', array(&$this, 'post_save'), 11, 3);
			add_action('delete_post', array(&$this, 'post_delete'));
		}

		function post_delete($post_id) {
			if($this->options->pageid == $post_id){
				$this->options->pageid = 0;
				$this->options->pageslug = $this->options->getdefault('pageslug');
				$this->options->pagetitle = $this->options->getdefault('pagetitle');
				$this->options->save();
			}
		}

		function post_save($post_id) {
			if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
				return;
			}

			$post_id = (wp_is_post_revision($post_id)) ? wp_is_post_revision($post_id) : $post_id;
			$post = get_post($post_id);

			preg_match('/\[cbpress[^\]]*\]/is', $post->post_content, $matches); // shortcodes check
			if(count($matches) == 0){
				// reset option reference if post_id matches pageid
				if($post->post_type == 'page'){
					$this->post_delete($post_id);
				}

				delete_post_meta($post_id, $this->metakey);

				// abort('delete ' . $this->metakey . ' in ' . $post_id);

			} else {
				update_post_meta($post_id, $this->metakey, '1');
			}
		}

		/* ===============================
		 FRONT END FUNCTIONS
	  ================================*/
		function fe_css_wrapper($in) {
			return '<div class="cbpress">' . $in . '</div>';
		}

		function frontend_wp_styles_print() {
			wp_enqueue_style('cbpress-frontend');
		}

		function frontend_wp_scripts_print() {
			wp_enqueue_script('cbpress-frontend');
			$this->frontend_wp_scripts_localize();
		}

		function frontend_wp_scripts_localize() {
			$out = $this->options->get_requested();
			$cbpressjs = array(
				'cid' => $out->cid,
				'topcat' => $out->topcat,
				'subcat' => $out->subcat,
				'catcols' => $out->catcols,
				'version' => CBP_VERSION,
				'blogurl' => CBP_BLOGURL
			);
			wp_localize_script("cbpress-frontend", 'cbpressjs', $cbpressjs); // cbpressjs  js scope
		}

		## frontend item rendering

		function render_item(&$item, $showdesc = true) {
			return $item->output($showdesc, false);
		}

		function render_items(&$items, $showdesc = true) {
			$out = '';
			$i = 0;
			$found = count($items);
			foreach ($items as $item) {
				$i++;
				$last = ($i == $found) ? true : false;
				// $out .= '<li>' . $item->output($showdesc,$last) . '</li>';
				$out .= '' . $item->output($showdesc, $last) . '';
			}
			return $out;
		}

		function render_items_array(&$items, $showdesc = true) {
			$out = array();
			$i = 0;
			$found = count($items);
			foreach ($items as $item) {
				$i++;
				$last = ($i == $found) ? true : false;
				$out[] = $item->output($showdesc, $last);
			}
			return $out;
		}

		function render_list($list_id = 0, $sort = '', $order = '', $showdesc = true) {
			global $wpdb;

			global $cbpress;
			$list_id = ((!is_numeric($list_id)) ? 0 : $list_id);
			if($sort == ''){
				$sort = $cbpress->options->sort;
				if($sort == 'rank'){
					$sort = '';
				}
			}
			if($sort == ''){
				$order = '';
			}
			$out = CBP_lists::get_list($list_id);

			if($out){
				$items = CBP_lists::getitems($list_id, $sort, $order, $active = 1);
				$out->title = $out->list_name;
				$out->html = '';
				$counter = 0;
				$numitems = count($items);
				foreach ($items as &$row) {
					$row = new CBP_prod($row);
					$last = ($counter == $numitems) ? true : false;
					// $out->html .= '<li>' . $row->output($showdesc,$last) . '</li>';
					$out->html .= '' . $row->output($showdesc, $last) . '';
				}
				if($out->html != ''){
					// $out->html = '<ul>' . $out->html . '</ul>';
				}
			} else {
				$out = new stdClass();
				$out->title = '';
				$out->html = '';
			}
			return $out;
		}

	}