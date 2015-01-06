<?php

class CBP_ajax {

	var $post;
	var $request;
	var $options;


	function register_ajax( $action, $function = '', $priority = 10 ) {

		add_action( 'wp_ajax_cbp_'.$action, array( &$this, $function == '' ? 'hook_' . $action : $function ), $priority );

		// add_action('admin_print_scripts-'.$action, array(&$this,'scripts') );
		// add_action('admin_print_styles-'.$action, array(&$this,'styles') );
		// wp_enqueue_script( 'my-ajax-request', plugins_url( '/path/to/somefile.js', __FILE__ ) );
		// extends CBP_base

	}

	public function scripts() {
		wp_enqueue_style('jquery-ui');
		wp_enqueue_style('dashboard');
		wp_enqueue_style('thickbox');
		wp_enqueue_style('global');
		wp_enqueue_script( 'jquery-ui-tabs' );

	}

	public function styles() {
		wp_enqueue_style('cbpress-admin', CBP_CSS_URL . 'cbpress_admin.css');

	}

	public function __construct(&$opts) {
		$this->options = $opts;
		$this->init();

	}

	function init() {

		if(CBP::is_ajax_request()){
			header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
		}

		$this->request = stripslashes_deep( $_REQUEST );
		$this->post    = stripslashes_deep( $_POST );
		$this->set_magic_hooks();

		if ( current_user_can( 'administrator' ) ) {  }

	}





	function set_magic_hooks(){

		$class = new ReflectionClass(get_class($this));

		$methods = $class->getMethods();

		//Add the hook. Uses add_filter because add_action is simply a wrapper of the same.

		foreach ($methods as $method){
			//Check if the method name starts with "hook_"
			if (strpos($method->name, 'hook_') === 0){
				$hook = substr($method->name, 5);
				$this->register_ajax($hook);
			}

		}

		unset($class);

	}

	function hook_tooltip() {
		$id = CBP::getv('id','');
		echo $id;
		die('');

	}

	function hook_import() {

		$api = CBP_import::getter();
		$msg = $api->import_all_in_one();
		echo $msg;
		die('');

	}

	function hook_import_busy() {

		_log(__METHOD__);

		$api = CBP_import::getter();
		$busy = ($api->is_busy()) ? '1' : '0';
		$status = $busy ? 'busy' : 'ready';
		$json = array('status'=>$status,'data'=>$busy,'id'=>'cbp_import_busy');

		echo '' . json_encode($json) . '';
		die('');

	}



	# STEP 1: First ajax request which checks, creates log, downloads, extracts feed file




	function hook_import_start() {
		CBP::nonced();
		$api = CBP_import::getter();
		// $api = new CBP_import();
		$result = $api->import_start();
		if($result->err) echo $result->msg;
		die('');

	}

	# STEP 2: process categories, products, etc... commits to db

	function hook_import_run() {
		CBP::nonced();


		$api = CBP_import::getter();

		// $api = new CBP_import();
		$log = $api->import_run();
		if(! CBP::is_ajax_request()){
			dump($log);
		} else {
			die('');
		}

	}

	# THIS can be run anytime between step 1 & 2... polls the LOG and provides on screen update via JSON

	function import_check_recall() {

		// _log(__METHOD__);


		// called by ajax output json

		@set_time_limit(0);

		$xlog 		= cbp_importlog::create(false);
		$st 		= $xlog->getlog();
		$st2 		= $xlog->get_last_import();
		$so_far 	= 0;
		$reading 	= $xlog->read;
		$toread 	= $xlog->toread;
		$st 		= (object) $st;

		$st->toread 	= $toread;
		if($xlog->done == 1 && $toread == 0){
			$text = "100%";
			$progress = 100;
		} else {
			if($toread > 0){
				$progress = $reading / $toread * 100;
				$text = number_format($reading) . " of " . number_format($toread);
				if($reading == $toread){
					$text = "Processing $toread listings...";
				}
			} else {
				$progress = 0;
				$text = "Please wait...";
				$text = $reading . " complete";
			}
		}

		$percent 	= (float) $progress;
		$percent 	= floatval($percent);
		$percent 	= number_format($percent, 2, '.', '');
		$st->runtime 	= number_format(($st->runtime), 2);
		$st->percent 	= $percent;
		$st->pct 	= floor($percent);
		$st->width 	= intval($percent) . '%';
		$st->text 	= $text . '';

		return $st;
	}



	function hook_import_check() {

		$result = $this->import_check_recall();
		$json = json_encode($result);
		echo $json;
		die('');

	}

	# THIS can be run anytime between step 1 & 2... polls the LOG and provides on screen update via JSON


	function hook_import_check_admin() {

		$api = CBP_import::getter();
		// $api = new CBP_import();
		$result = $api->import_check();
		dump($result);
		die('');

	}

	function hook_import_cmd() {

		$cmd = CBP::getv('action');
		$importer = CBP_import::getter();

		// $importer = new CBP_import();

	}



	function hook_prod_load() {
		CBP::getview( 'product');
		die('');

	}

	function hook_prod_info() {
		CBP::getview( 'product', array('modal'=>1));
		die('');

	}



	function hook_prod_edit () {

		global $wpdb, $cbpress;
		CBP::nonced();
		add_action('admin_print_styles', array(&$this,'scripts') );

		wp_print_styles( 'cbpress-admin' );
		wp_print_styles( 'cbpress-forms' );

		CBP::getview( 'product');
		die;
	}

	function hook_prod_list () {

		echo 'List Products';

	}

	function hook_prod_tree () {

		global $wpdb;


		// get product cids

		$lid = @$_REQUEST['lid'];


		header( 'Content-type: text/javascript' );

		$product  = CBP_prod::create(intval($lid));
		$catdata = $product->cats;


		// dump($catdata);
		// maketree
		// $branch = CBP_cats::get_flat_branch_db();

		$branch = CBP_cats::get_tree();

		$treedata = CBP_cats::make_dynatree($branch, 0, $catdata);

		$json = array(
			'title' => "Marketplace Categories ",
			'icon' => "categories.png",
			'isFolder' => true,
			'hideCheckbox' => true,
			'tooltip' => "Choose Categories",
			'unselectable' => true,
			'expand' => true,
			'key' => "0",
			'children' => $treedata

		);



		$json = '['. json_encode($json) . ']';

		echo $json;

		die('');

	}



	// add multiple items to custom cat

	function hook_prod_tocat() {


		$target = CBP::getv('target');
		if($target > 0){
			if ( preg_match_all( '/=(\d*)/', $this->request['checked'], $items ) > 0) {
				foreach ( $items[1] AS $id ) {
					CBP_prod::addcat_($id,$target);
				}
			}
		}
		die();

	}



	// add multiple items to list

	function hook_prod_tolist() {
		$target = CBP::getv('target');
		if($target > 0){
			if ( preg_match_all( '/=(\d*)/', $this->request['checked'], $items ) > 0) {
				foreach ( $items[1] AS $id ) {
					CBP_lists::add_items($target,$id);
				}
			}
		}
		die();

	}



	function hook_toggle_prod() {
		$id = $this->request['id'];
		$v = CBP::togglecol("id=$id&key=lid&field=active&table=".CBTB_PROD);
		echo $v;
		die();

	}

	function hook_toggle_join() {
		$id = $this->request['id'];
		$v = CBP::togglecol("id=$id&key=join_id&field=join_enable&table=".CBTB_TREE);
		echo $v;
		die();

	}



	function hook_toggle_cat() {
		CBP::nonced();
		$id = $this->request['id'];
		$v = CBP::togglecol("id=$id&key=cid&field=enabled&table=".CBTB_CAT);
		echo $v;
		die();

	}

	function hook_list_form(){

		$id = CBP::getv('list_id',0);
		$list = CBP_list::load($id);
		$list->form(false);
		die;
	}



	function hook_save_list(){

		global $wpdb;

		$form = stripslashes_deep( $_POST['listform'] );
		$comp = new CBP_list($form);

		if ( false === ($id = $comp->save()) ) {
			$msg = $comp->msg;
		}else{
			$msg = 'List saved';
		}

		$json = array('err'=>$comp->err,'msg'=>$msg,'list_id'=>$comp->list_id);

		echo json_encode($json);

		die;

	}



	function hook_cat_form(){

		$cid = CBP::getv('cid',0);
		$cat = CBP_cat::get($cid);
		$form = false;
		CBP::getview( 'form_cat', get_defined_vars() );
		die;

	}

	function hook_save_cat(){

		global $wpdb;

		$newcat = new CBP_cat( cbpressfn::a2o(stripslashes_deep($_POST['catform'])) );

		if($newcat->name == ''){
			$json = array('err'=>true,'msg'=>'Invalid category name','cid'=>0);
		}else{
			$id = $newcat->save();
			$json = array('err'=>$newcat->err,'msg'=>$newcat->msg,'cid'=>$id);

		}

		echo json_encode($json);

		die;

	}

	function hook_searchbox_on() {

		$this->options->admin_sb = '1';

		$this->options->save();

	}

	function hook_searchbox_off() {

		$this->options->admin_sb = '0';

		$this->options->save();

	}



	function hook_save_listorder( $items, $start ) {

		global $wpdb;



		if ( preg_match_all( '/=(\d*)/', $this->post['items'], $items ) > 0) {

			$items = $items[1];
			$list_id = intval( $this->post['list_id'] );
			CBP_lists::save_order( $items, $list_id );

		}


		CBP::flush_cache();

	}



	function hook_search_box(){



		$arr = array();

		if(isset($_SERVER['HTTP_REFERER'])){
			$ref = $_SERVER['HTTP_REFERER'];
			$search_args = '';
			// $current_url = strtok($current_url, '?');
			$parts = explode('?', $ref);
			if(count($parts) > 1) $search_args = $parts[1];
			parse_str($search_args, $search_args);
			if(isset($search_args['page'])) unset($search_args['page']);
			if(isset($search_args['pid'])){
				$search_args['cid'] = $search_args['pid'];
				unset($search_args['pid']);
			}
			$arr = array('search_args'=>$search_args);



			$this->options->admin_sb = 1;
			$this->options->save();

		}

		CBP::getview( 'form_search', $arr);

		die;

	}





	function hook_items_toggle() {
		CBP::nonced();
		if ( preg_match_all( '/=(\d*)/', $this->request['checked'], $items ) > 0) {
			foreach ( $items[1] AS $id ) {
				CBP_query::toggle_prod($id);
			}
		}
		die();

	}



	function hook_get_lids () {
		$k = CBP::getv('q');
		if (strlen($k) == 0) die;
		$k = esc_html(cbpressfn::cb_esc_attr(str_replace(array("\t", "\r\n", "\n"), ' ', $k)));
		$pager = new CBP_Pager($_GET, $_SERVER['REQUEST_URI'], 'lid', '', 'vendors');
		$pager->per_page = 30;
		$out = CBP_query::get_hop_list_by_lid($k,$pager);
		for ( $i = 0; $i < count($out->result); $i++ ) {
			$str1 = strtolower($out->result[$i]['lid']);
			$str2 = cbpressfn::truncate($out->result[$i]['title'], 30, " ");
			echo "$str1|$str2\n";
		}
		die;

	}



	function hook_get_hops () {
		$k = CBP::getv('q');
		$k = ($k == '') ? CBP::getv('term') : $k;
		if (strlen($k) == 0) die;
		$k = esc_html(cbpressfn::cb_esc_attr(str_replace(array("\t", "\r\n", "\n"), ' ', $k)));
		$pager = new CBP_Pager($_GET, $_SERVER['REQUEST_URI'], 'vin', '', 'vendors');
		$pager->per_page = 30;
		$out = CBP_query::get_hop_list($k,$pager);
		for ( $i = 0; $i < count($out->result); $i++ ) {
			$str1 = strtolower($out->result[$i]['vin']);
			$str2 = cbpressfn::truncate($out->result[$i]['title'], 30, " ");
			echo "$str1|$str2\n";
		}
		die;

	}

	function hook_suggest_items () {
		$k = CBP::getv('q');
		$k = ($k == '') ? CBP::getv('term') : $k;
		if (strlen($k) == 0) die;
		$k = esc_html(cbpressfn::cb_esc_attr(str_replace(array("\t", "\r\n", "\n"), ' ', $k)));
		$pager = new CBP_Pager($_GET, $_SERVER['REQUEST_URI'], 'vin', '', 'vendors');
		$pager->per_page = 15;
		$out = CBP_query::get_hop_list($k,$pager,'lid as id, title as label, lid as value');
		echo '' . json_encode($out->result) . '';
		die;

	}

	function hook_get_vendors () {
		$k = CBP::getv('q');
		if (strlen($k) == 0) die;
		$k = esc_html(cbpressfn::cb_esc_attr(str_replace(array("\t", "\r\n", "\n"), ' ', $k)));
		$pager = new CBP_Pager($_GET, $_SERVER['REQUEST_URI'], 'vin', '', 'vendors');
		$pager->per_page = 30;
		$out = CBP_query::get_vendor_list($k,$pager);
		$out->output = '['. $out->found . ',' . json_encode($out->result) . ']';
		echo $out->output;
		die;

	}

	function hook_cat_edit() {
		CBP::nonced();
		$cid = intval( $_GET['cid'] );
		$cat = CBP_cat::get($cid);
		if ( $cat ){
			CBP::getview( 'cat_edit', get_defined_vars()  );
		}
		die();

	}

	function hook_cat_add() {
		$cid = intval( $_GET['cid'] );
		$oCat = CBP_cat::get($cid);
		dump($oCat);
		if ( $oCat ){
		}
		die();

	}

	function hook_list_edit() {
		$id = intval( $_GET['id'] );
		if ( check_ajax_referer( 'cbpress-list_'.$id ) ) {
			die('----');
		}
	}


}