<?php
if (!defined('ABSPATH')) die();
class CBP {

	public static $data = array();

	public static $options = array();




	function get_referer() {
		$ref = false;
		if ( ! empty( $_REQUEST['_wp_http_referer'] ) )
			$ref = $_REQUEST['_wp_http_referer'];
		else if ( ! empty( $_SERVER['HTTP_REFERER'] ) )
			$ref = $_SERVER['HTTP_REFERER'];

		if ( $ref && $ref !== $_SERVER['REQUEST_URI'] )
			return $ref;
		return false;
	}



	function redirector($msg='',$uri='') {

			if($uri == '') $uri = self::get_referer();

// echo $uri;
// die;

			$uri = remove_query_arg( array('msg','fa','updated'), $uri );

			if($msg != '') $uri = add_query_arg('msg',urlencode($msg),$uri);

			wp_safe_redirect( $uri );

			exit;
	}


	function sidebar_selectbox($name='', $current_value=false ) {
	    global $wp_registered_sidebars;


	    if( empty( $wp_registered_sidebars ) )  return;


		$xname = esc_attr($name);

		$sel_name = (empty($xname)) ? '' : ' name="'.$xname.'"';
		$sel_id = (empty($xname)) ? '' : ' id="'.$xname.'"';


		$attr = trim(trim($sel_name) . ' ' . trim($sel_id));
		$attr = ($attr != '') ? ' ' . $attr : '';
		// abort($attr);

	    $current = ( $current_value ) ? esc_attr( $current_value ) : false;
	    $selected = '';
	    ?>
	    <select<?php echo $attr; ?>>
		<option value="">- CHOOSE SIDEBAR Optional -</option>
		<option value=""></option>
	    <?php foreach( $wp_registered_sidebars as $sidebar ) : ?>
		<?php
		if( $current )
		    $selected = selected( $sidebar['id'] == $current, true, false ); ?>
		<option value="<?php echo $sidebar['id']; ?>"<?php echo $selected; ?>><?php echo $sidebar['name']; ?></option>
	    <?php endforeach; ?>
	    </select>
	    <?php
	}




	function slasher() {


		if ( get_magic_quotes_gpc() ) {
		    if(isset($_POST)) $_POST      = array_map( 'stripslashes_deep', $_POST );
		    if(isset($_GET)) $_GET       = array_map( 'stripslashes_deep', $_GET );
		    if(isset($_REQUEST)) $_REQUEST   = array_map( 'stripslashes_deep', $_REQUEST );
		}


	}



	function widget_in_sidebar($sidebar_id, $widget_id, $widget_args=array()) {




	}




    function toAttribs( &$arr = array() ) {
		$out = '';
		foreach ( $arr as $k => $v ) {
			$out .= ' ' . $k . '="' . esc_attr( $v ) . '"';
		}
		return $out;
    }


    function quicktag( $tag, $attribs = array(), $content = '' ) {
		if ( is_array( $attribs ) || is_object( $attribs ) ) {
			$closing = $tag;
			$tag .= self::toAttribs($attribs);
		} else {
			$content = $attribs;
			list( $closing ) = explode(' ', $tag, 2);
		}
		return "<{$tag}>{$content}</{$closing}>";
    }

	function widget_save_to_sidebar($sidebar_id, $widget_id, $widget_args=array()) {

		// $sidebar_id = 'secondary-widget-area';


		$SBS = self::get_sidebar_widgets();
		$WID = $widget_id;
		$SID = $sidebar_id;


		$sidebar_options = get_option('sidebars_widgets');
		if(!isset($sidebar_options[$SID])){
			$sidebar_options[$SID] = array('_multiwidget'=>1);
		}


		$the_widget = get_option('widget_'.$WID);
		if(!is_array($the_widget)) $the_widget = array();

		$count = count($the_widget)+1;

		$next_number = $count;

		if(isset($SBS->$SID->$WID)){
			$next_number = $SBS->$SID->$WID->next;
		}


		$sidebar_options[$SID][] = $WID.'-'.$next_number;
		$the_widget[$count] = $widget_args;
		$count++;

		update_option('sidebars_widgets',$sidebar_options);
		update_option('widget_'.$WID,$the_widget);
	}

	function get_sidebar_widgets() {

		global $wp_registered_widgets;
		$mysidebars = wp_get_sidebars_widgets();
		$sbs = array();
		foreach ($mysidebars as $sidebar_id => $sidebar) {
			$sbs[$sidebar_id] = array();
			if(is_array($sidebar) && $sidebar_id != 'wp_inactive_widgets'){
				foreach($sidebar as $widget){

						$w = explode('-',$widget);
						$number = $w[1];
						$widget_id = $w[0];

						if(!isset($sbs[$sidebar_id][$widget_id])){
							$sbs[$sidebar_id][$widget_id] = (object) array('numbers'=>array(),'next'=>0);
						}
						$sbs[$sidebar_id][$widget_id]->numbers[] = $number;

						if($number >= $sbs[$sidebar_id][$widget_id]->next){

							$sbs[$sidebar_id][$widget_id]->next = $number +1;

						}
				}
			}

			$sbs[$sidebar_id] = (object) $sbs[$sidebar_id];
		}


		return (object) $sbs;

	}




























    static function getv($key,$default=null) {

		if (isset($_GET[$key])){

			return $_GET[$key];

		}else if(isset($_POST[$key])){

			return $_POST[$key];

		}

		return $default;
    }

	function silence_404() {
		global $wp_query;
		$wp_query->query_vars['error'] = '';
		$wp_query->is_404 = false;
	}
	function sethttpHeaders_js() {
		header( 'Content-type: text/javascript' );
		header('Expires: '.gmdate('r',mktime(0,0,0,date('m'),(date('d')+12),date('Y'))).'');
		header('Cache-Control: public, must-revalidate, max-age=86400');
		header('Pragma: public');

	}
	static function sethttpHeaders() {
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
		header("Cache-Control: no-cache, must-revalidate" );
		header("Pragma: no-cache" );
		header("Content-type: text/x-json");
	}


	/**
	 * Formats a string
	 * Adds slashes, converts special characters to HTML entities
	 * @param string $str
	 */
	function __formatString($str) {
		if ( !get_magic_quotes_gpc() ) $str = addslashes($str);
		$str = htmlspecialchars($str, ENT_QUOTES);
		$str = trim($str);
		return $str;
	}

	function __Pagination($total_records, $start_no) {
		global $cbpress;
		$plugin_pg = ($this->wp_version >= 2.7) ? 'tools.php' : 'edit.php';
		// $get_vars = $this->__BuildGetVars('page');

		$uri = remove_query_arg( array('pg', 'page'), $_SERVER['REQUEST_URI'] );

		$pg = self::getv('pg');
		$records_per_page = $cbpress->options->admin_pp;
		$noof_pages = ceil($total_records/$records_per_page);

		for ( $i=1; $i<=$noof_pages; $i++ ) {
		   if ( $i == $naff_pg ) {
		  	  $show_pages .= '&nbsp;<strong>'.$i.'</strong>&nbsp;';
		   } else {
		  	  $show_pages .= '&nbsp;<a href="'.$uri.'&pg='.($i-1).'">'.$i.'</a>&nbsp;';
		   }
		}
		?>
		<table align="center">
		  <tr>
		   <?php if ( $pg > 1 ) { $pages_shown = 1; ?>
			  <td>
			  <a href="<?php echo $uri . '&pg=' . $pg-2 ?>">&laquo; Prev</a>
			  <?php echo $show_pages; ?>
			  </td>
		   <?php } ?>
		   <?php if ( $total_records > $records_per_page && ($total_records-$start_no) >= $records_per_page ) { ?>
			  <td>
			  <?php if ( $pages_shown != 1 ) echo $show_pages; ?>
			  <a href="<?php echo $uri . '&pg=' . $pg ;?>">Next &raquo;</a>
			  </td>
		   <?php } ?>
		  </tr>
		</table>
		<?php
	}
	/**
	 * Reverse of htmlentities() and addslashes()
	 * @param string $str
	 * @param integer $type
	 */
	function __reverseFormatString($str, $type='') {
		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
		$trans_tbl = array_flip($trans_tbl);
		$str = stripslashes($str);
		if ( !$type ) $str = htmlentities($str);
		$str = strtr($str,$trans_tbl);
		return $str;
	}

	static function build_query($array) {
		return html_entity_decode(http_build_query($array));
	}
	static function is_ajax_request() {
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
		   && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			return true;
		}
		return false;
	}

    static function status_header() {
		return $_SERVER['SERVER_PROTOCOL'] . ' 200 OK';
    }

	static function nonced()
	{

		// return true;
		if ( check_ajax_referer( 'salt' ) ) {
			return true;
		}
		die('0');
	}

	function preg_filter($filter, $str) {
		return preg_replace("/[^{$filter}]/", '', $str);
	}
	function vklrsort(&$arr, $valuekey) {
		$valuekey = self::preg_filter('A-Za-z0-9 ', $valuekey);
		uasort($arr, create_function('$a,$b', 'return strlen($b["'.$valuekey.'"]) - strlen($a["'.$valuekey.'"]);'));
	}
	function vksort(&$arr, $valuekey) {
		$valuekey = self::preg_filter('A-Za-z0-9 ', $valuekey);
		uasort($arr, create_function('$a,$b', 'return strlen($a["'.$valuekey.'"]) - strlen($b["'.$valuekey.'"]);'));
	}


	static function tip( $message, $title = '', $echo_tip = true ) {

			// question
			// pluginbuddy_tip.png
			$tip = ' <a class="pluginbuddy_tip" title="' . $title . ' - ' . $message . '"><img src="' . CBP_IMG_URL . 'question.png" alt="(?)" /></a>';
			if ( $echo_tip === true ) {
				echo $tip;
			} else {
				return $tip;
			}
		}
	// for widgets

	static function getbaselink()
	{
		global $cbpress;
		$pageid = intval($cbpress->options->pageid);
		return get_permalink($pageid);
	}

    static function link( $url, $title = '', $css='', $target='' ) {
        if ( empty( $title ) ) $title = $url;
        if ( $css != '' ) $css = " class='$css'";
        if ( $target != '' ) $target = " target='$target'";
        // return sprintf( "<a href='%s'%s%s>%s</a>", $url, $css, $target, $title );
        return sprintf( "<a href='%s'%s%s>%s</a>", esc_url( $url ), $css, $target, $title );
    }

    /**
     * Get the function call backtrace.
     *
     * @return array all functions calls leading to the place of call to this one
     */
    function get_caller_backtrace() {
            if (!is_callable('debug_backtrace')) return array();
            $bt = debug_backtrace();
            $caller = array();

            $bt = array_reverse($bt);
            foreach ((array)$bt as $call) {
                $function = $call['function'];
                if (isset($call['class'])) $function = $call['class']."->$function";
                $caller[] = $function;
            }

            unset($caller[count($caller) - 1]);
            return $caller;
	}



	function wp_redirect( $url, $status ){
		global $wp_version, $is_IIS;
		if ( $wp_version < '2.1' ) {
			status_header( $status );
			return $url;
		} elseif ( $is_IIS ) {
			header( "Refresh: 0;url=$url" );
			return $url;
		} else {
			if ( $status == 301 && php_sapi_name() == 'cgi-fcgi' ) {

				$servers_to_check = array( 'lighttpd', 'nginx' );
				foreach ( $servers_to_check as $name ) {
					if ( stripos( $_SERVER['SERVER_SOFTWARE'], $name ) !== false ) {
						status_header( $status );
						header( "Location: $url" );
						exit( 0 );
					}
				}
			}
			status_header( $status );
			return $url;
		}
	}

        function add_slashes($input) {
            if (get_magic_quotes_gpc()) return $input;
            else return addslashes($input);
        }

	static function get_include_vars($filename) {
		if (is_file($filename)) {
			include $filename;
			return (object) get_defined_vars();
		}
		return false;
	}

	static function get_include($filename) {
		if (is_file($filename)) {
			ob_start();
			include $filename;
			$contents = ob_get_contents();
			ob_end_clean();
			return $contents;
		}
		return false;
	}



	static function get_memory_usable() {
		return self::get_memory_limit() - memory_get_usage();
	}
	static function get_memory_limit() {
		return cbpressfn::return_bytes(ini_get('memory_limit'));
	}

	static function li($in) {
		return '<li>'. $in .'</li>';
	}



	static function divider() {
		return '<div class="cbpress-divider"></div>';
	}
	static function div( $content='' , $class= '' , $title= '' ) {

		$vars = get_defined_vars();
		$attr = '';
		foreach ( $vars as $k => $v ) {
			if($k != 'content' && $v != '') $attr .= ' ' . $k . '="' . esc_attr( $v ) . '"';
		}

		// abort($attr);
		unset($vars);

		return "<div$attr>" . $content . '</div>';
	}



	static function getview($tpl,$tvars=array(), $print=true)
	{

		// $test = get_defined_vars ();
		// abort($test);

		global $wp_version, $wpdb, $cbpress;

		$filename = CBP_VIEWS_DIR . $tpl . '.php';

		if (is_file($filename)) {
			foreach($tvars as $key => $val) { $$key = $val; }

			if($print){
				  include $filename;
			}else{
				  ob_start();
				  include $filename;
				  $contents = ob_get_contents();
				  ob_end_clean();
				  return $contents;

			}
		}else{
			$msg = "<p>Rendering of admin template $filename failed</p>";
			if($print){
				echo $msg;
			}else{
				return $msg;
			}
		}

	}


	static function thispage() {
		return self::getv('page');
	}
	static function current_page() {
		$page = self::thispage();
		if($page == CBPRESS_NAME) return $page;
		$len = strlen(CBPRESS_NAME)+1;
		if (substr($page, 0, $len) == CBPRESS_NAME.'-') $page = substr($page, $len);
		return $page;
	}
	/***
	 returns true or false if page=cbpress or page=cbpress-key
	***/
	static function is_plugin_page() {

		$page = self::thispage();
		if(strlen($page) < strlen(CBPRESS_NAME)) return false;

		$len = strlen(CBPRESS_NAME)+1;
		if(substr($page,0,$len) == CBPRESS_NAME.'-' || $page == CBPRESS_NAME){
			return true;
		}
		return false;
	}


	static function ajax_url($action='',$nonce='salt') {
		$qs = array();

		if($action != '') $qs[] = 'action='.$action;
		if($nonce != '') $qs[] = '_ajax_nonce='.wp_create_nonce($nonce);
		$qs = implode('&',$qs);

		$qs = ($qs != '') ? '?'.$qs : '';

		return admin_url( 'admin-ajax.php' . $qs);
	}

	function explode_lines($lines) {
		$lines = explode("\n", $lines);
		$lines = array_map('trim', $lines); //Remove any \r's
		return $lines;
	}

	static function get_arguments($params,$defaults=array())
	{

		if(is_string($defaults)){ parse_str($defaults, $defaults); } // sep 1
		$defaults = is_array($defaults) ? $defaults : array();



		if(is_array($params)){
			$args = wp_parse_args($params, $defaults);
		}else{
			parse_str($params, $params);
			$args = array_merge($defaults, $params);
		}
		return $args;
	}

	/**
	 returns current url page .. ie: reload
	*/
	static function make_current_url() {
		return self::get_admin_url(self::current_page());
	}
	/**
	 returns a uri for page within this plugin ie: admin.php?page=cbpress-page
	*/
	static function get_admin_url($page='') {

		if($page == CBPRESS_NAME) $page = '';
		if(strlen($page)) $page = '-'.$page;
		return admin_url('admin.php?page='.CBPRESS_NAME.$page);
	}
	static function admin($page='') {
		return self::get_admin_url($page);
	}


	// make_action_url('view',$page='import')
	static function make_action_url($action,$page='') {
		if(!strlen($page)) $page = self::current_page();
		return self::get_admin_url($page) . '&action=' . $action;
	}

	static function make_action_link($action,$text='') {
		if($text == '') $text = $action;
		$link = self::make_action_url($action);
		return "<a href='$link'>$text</a>";
	}

	static function fetch_rss_items( $num, $feedurl ) {
			include_once(ABSPATH . WPINC . '/feed.php');
			$rss = fetch_feed( $feedurl );

			// Bail if feed doesn't work
			if ( is_wp_error($rss) ) return false;

			$rss_items = $rss->get_items( 0, $rss->get_item_quantity( $num ) );

			// If the feed was erroneously
			if ( !$rss_items ) {
				$md5 = md5( $feedurl );
				delete_transient( 'feed_' . $md5 );
				delete_transient( 'feed_mod_' . $md5 );
				$rss = fetch_feed( $feedurl );
				$rss_items = $rss->get_items( 0, $rss->get_item_quantity( $num ) );
			}
			return $rss_items;
	}

	static function text_limit( $text, $limit, $finish = '&hellip;') {
			if( strlen( $text ) > $limit ) {
		    	$text = substr( $text, 0, $limit );
				$text = substr( $text, 0, - ( strlen( strrchr( $text,' ') ) ) );
				$text .= $finish;
			}
			return $text;
	}

	// CAB
	// add missing keys, but remove if value is same as defualt

	static function array_minimize($myarray=array(),$defaults=array(), $valuecheck=true) {

			// ADD MISSING KEYS, OR REMOVE IDENTICAL IF THEY MATCH DEFAULT VALUE
			foreach($defaults as $k => $v) {
				if(!isset($myarray[$k]))  $myarray[$k] = $v;

				if($valuecheck) if($myarray[$k] == $v) unset($myarray[$k]);
			}

			// DELETE OPTIONS NOT IN DEFAULTS
			foreach($myarray as $k => $v) {
				if(! isset($defaults[$k]) )unset($myarray[$k]);
			}

		return $myarray;
	}

    static private function testFopen() {
		if (!@ini_get('allow_url_fopen')) {
			return false;
		} else {
			return true;
		}
	}
	static function getServerAbilities() {

		# set server abilities
		$abilities = array();
		$abilities['safe_mode'] 	= intval(@ini_get('safe_mode'));
		$abilities['fopen'] 		= self::testFopen();
		$abilities['unzip'] 		= function_exists('zip_open');
		$abilities['upload_dir'] 	= is_writable(CBP_UPLOAD_DIR);
		$abilities['feed_dir'] 		= cbpressfn::isDirectoryWritable(CBP_FEED_DIR);
		$abilities['curl'] 			= function_exists('curl_exec');


		# create any information messages

		$messages = array();
		if (!$abilities['curl']) {
			$messages[] = 'cURL is not enabled. If you have access to your php.ini file, activate <b>extension=php_curl.dll</b>.';
		}
		if ($abilities['safe_mode']) {
			$messages[] = 'PHP safe mode. If you have problems importing products, please contact your web host about disabling PHP safe mode.';
		}
		if (!$abilities['fopen']) {
			$messages[] = 'Your server has disabled the "fopen" function. This may be needed for cbpress to properly run.';
		}
		if (!$abilities['unzip']) {
			$messages[] = 'Your server does not support the zip function which is needed to unzip product feed files.';
		}
		if (!$abilities['upload_dir'] || !$abilities['feed_dir']) {
 			$fix = self::admin('').'&fa=resetfolders';
			$fix = "<a href=\"$fix\" class=\"clickfix\">Click here to fix</a>";
			$messages[] = "Your uploads directory does not contain a writable 'cbpress/feeds' folder. $fix ";
		}

		$out = new stdClass();

		# create manual and auto import flags
		$out->auto = false;
		$out->manual = false;
		if ($abilities['safe_mode'] 	== false &&
			$abilities['fopen'] 	== true &&
			$abilities['unzip'] 	== true &&
			$abilities['feed_dir'] 	== true) {
			$out->auto = true;
		}
		if ($abilities['safe_mode'] 	== false ||
			$abilities['feed_dir'] 	== true) {
			$out->manual = true;
		}
		$out->passed = (count($messages)) ? false : true;
		$out->abilities = $abilities;
		$out->messages = $messages;
		$out->upload = true;
		return $out;
	}

	static function img($filename,$alt='',$id='',$class='') {
		$filename = CBP_IMG_URL . $filename;
		return "<img src=\"{$filename}\" id=\"{$id}\" title=\"{$alt}\" align=\"absmiddle\" class=\"{$class}\" />";
	}


	/**
	* generates a slug from a table
	*
	* @param mixed $string Any Title, String or whatever
	* @param mixed $id  not an id
	* @param mixed $key  name of column for NOT id check
	* @param mixed $field  name of column for slug check
	* @param mixed $table
	*/
	static function newslug($string, $id=null, $key='id', $field='slug', $table) {

		global $wpdb;
		$slug = cbpressfn::CleanForUrl($string);
		$cleaned = $slug;
		$counter = 0;
		$good = false;
		do {
			$query = "SELECT COUNT(*) FROM " . $table . " WHERE $field = '{$slug}'";
			if($id) $query .= " AND $key <> {$id}";

			if(intval($wpdb->get_var($query))) {
				$counter++;
				$slug = "{$cleaned}-{$counter}";
			} else {
				$good = true;
			}
		} while($good == false);
		return $slug;
	}

	static function len($string){


		return strlen($string);
		// return mb_strlen($string);

	}
	static function getrecordcount($params)
	{
		global $wpdb;
		$defs = array( 'id'  => '0' , 'key'  => '' , 'table'  => '' );
		extract(self::get_arguments($params,$defs));
		$query = $wpdb->prepare("SELECT COUNT('$key') FROM $table WHERE $key = %d", intval($id));
		return intval($wpdb->get_var($query));
	}
	static function recordexists($params)
	{
		global $wpdb;
		$defs = array( 'id'  => '0' , 'key'  => '' , 'table'  => '' );
		extract(self::get_arguments($params,$defs));
		$query = $wpdb->prepare("SELECT COUNT('$key') FROM $table WHERE $key = %d", intval($id));
		return intval($wpdb->get_var($query));
	}
	static function getrecord($params)
	{
		global $wpdb;
		$defs = array( 'id'  => '0' , 'key'  => '' , 'table'  => '' );
		extract(self::get_arguments($params,$defs));
		$query = $wpdb->prepare("SELECT * FROM $table WHERE $key = %d", intval($id));
		return $wpdb->get_row($query);
	}

	static function togglecol($params) {
		global $wpdb;
		$defs = array( 'id'  => '0' , 'key'  => '' , 'field'  => '' , 'table'  => '' );
		$args = self::get_arguments($params,$defs);
		extract($args);
		$new_val = 0;
		if(self::recordexists($args)){
				$val = $wpdb->get_var($wpdb->prepare("SELECT $field FROM $table WHERE $key = %d", intval($id)));

				$new_val = ($val == '1') ? '0' : '1';

				$wpdb->update($table, array( $field => $new_val ), array( $key => intval($id)));
		}
		return $new_val;
	}

	// used in catform.php so far
	function wp_nonce_field($action = -1) {
		return wp_nonce_field($action);
	}


	function wp_can_admin() {
		if ( function_exists('current_user_can') && (!current_user_can('administrator')) ) {
			wp_die('<p>'.__('You do not have admin permission to perform this operation', $this->app).'</p>');
		}
	}

	function wp_can_edit() {
		if ( function_exists('current_user_can') && (!current_user_can('manage_options')) ) {
			wp_die('<p>'.__('You do not have permission to modify the options', $this->app).'</p>');
		}
	}

	static function flush_cache() {
			global $wpdb;
			if(function_exists("wp_cache_clean_cache")) {
					@wp_cache_clean_cache('wp-cache-');
			}
			$wpdb->flush();
			wp_cache_flush();
	}

	static function toXML($array, $depth = 0){
		$indent = "";
		$return = "";
		for($i = 0; $i < $depth; $i++)
			$indent .= "\t";
		foreach($array as $key => $item){
			$return .= "{$indent}<{$key}>\n";
			if(is_array($item))
				$return .= self::toXML($item, $depth + 1);
			else
				$return .= "{$indent}\t<![CDATA[{$item}]]>\n";
			$return .= "{$indent}</{$key}>\n";
		}
		return $return;
	}

	static function metabox($title='',$content=''){
		?>


				<div id="linksubmitdiv" class="postbox " >
					<h3 class='hndle'><span><?php echo $title?></span></h3>
					<div class="inside">
						<div class="submitbox" id="submitlink">
							<div id="minor-publishing">
								<div id="misc-publishing-actions">
								<div class="misc-pub-section misc-pub-section-last">



								<?php echo $content; ?>




								</div>
								</div>
							</div>

							<div id="major-publishing-actions">
								<div id="delete-action"></div>
								<div id="publishing-action">
									<input name="save" type="submit" class="button-primary" value="Save changes" />
									<input type="hidden" name="action" value="save" />
								</div>
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
						</div>
					</div>
				</div>

		<?php

	}



	static function postboxtoggle($id, $title, $content) {
		?>
		<div id="<?php echo $id; ?>" class="postbox">
			<div class="handlediv" title="Click to toggle"><br /></div>
			<h3 class="hndle"><span><?php echo $title; ?></span></h3>
			<div class="inside">
 				<?php echo $content; ?>
 			</div>
		</div>
		<?php
	}


    function _escape(&$array) {
        global $wpdb;

        if(!is_array($array)) {
            return($wpdb->escape($array));
        }
        else {
            foreach ( (array) $array as $k => $v ) {
                if (is_array($v)) {
                    $this->_escape($array[$k]);
                } else if (is_object($v)) {
                    //skip
                } else {
                    $array[$k] = $wpdb->escape($v);
                }
            }
        }
    }

	static function postbar_start($title,$style='',$cls='') {

		global $cbpress;

		if (! $cbpress->disable_postbox){


			if($style != '') $style = ' style="'.$style.'"';
			if($cls != '') $cls = ' class="'.$cls.'"';


			echo "<div id='post-body' class='has-sidebar metabox-holder'>";
				echo "<div class='has-sidebar-content'>";
				  echo '<div class="postbox">';
				    echo "<h3 class='hndle'><span>$title</span><br class='clear'/></h3>";
				    echo "<div class='inside'$style>";

		} else {
			echo '<h3><label>'. $title . '</label></h3>';
		}
	}
	static function postbar_end() {
		global $cbpress;
		if (! $cbpress->disable_postbox){
			echo '</div></div></div></div>';
		}
	}



	static function postbox_start($title,$style='',$cls='') {

		global $cbpress;


		if (! $cbpress->disable_postbox){

			if($style != '') $style = ' style="'.$style.'"';
			if($cls != '') $cls = ' class="'.$cls.'"';
			echo '<div id="poststuff">';
				echo '<div id="post-body">';
				  echo '<div class="postbox"' . $cls . '>';
					  echo '<h3><label>'. $title . '</label></h3>';
					  echo '<div class="inside"'.$style.'>';

		} else {
			echo '<h3><label>'. $title . '</label></h3>';
		}


	}
	static function postbox_end() {
		global $cbpress;
		if (! $cbpress->disable_postbox){
			echo '</div></div></div></div>';
		}
	}

	static function scrollbox($content) {
		?>
        <div class="scrollbox">
        <div class="scrollbox-scroll">
        <?php echo $content; ?>
        </div>
        </div>
		<?php
	}
	static function postbox($id, $title, $content) {
		?>
        <div id="<?php echo $id; ?>" class="postbox">
        <h3 class="hndle"><span><?php echo $title; ?></span></h3>
        <div class="inside">
        <p>
        <?php echo $content; ?>
        </p>
        </div>
        </div>
		<?php
		// $this->toc .= '<li><a href="#'.$id.'">'.$title.'</a></li>';
	}


		/**
		 * Create a form table from an array of rows
		 */
		function form_table($rows) {
			$content = '<table class="form-table">';
			foreach ($rows as $row) {
				$content .= '<tr><th valign="top" scrope="row">';
				if (isset($row['id']) && $row['id'] != '')
					$content .= '<label for="'.$row['id'].'">'.$row['label'].':</label>';
				else
					$content .= $row['label'];
				if (isset($row['desc']) && $row['desc'] != '')
					$content .= '<br/><small>'.$row['desc'].'</small>';
				$content .= '</th><td valign="top">';
				$content .= $row['content'];
				$content .= '</td></tr>';
			}
			$content .= '</table>';
			return $content;
		}


		function textarea($id, $value, $rows=5, $cols=30) {

			$value = cbpressfn::cb_esc_editable_html($value);
			return "<textarea name='$id' id='$id' type='text' style='width:95%;' class='regular-text' cols='$cols' rows='$rows'>$value</textarea>";
		}







	###########################################################################
	/**
	 * getElapsedTime
	 *
	 * Takes a mysql formatted date/time and applies the user's offset and
	 * formats the date into user's format.
	 *
	 * @param string $startTime
	 * @param string $endTime
	 * @param bool $showText shows the text 'seconds' or not
	 */
	 ##########################################################################
	public static function getElapsedTime($startTime, $endTime, $showText)
	{
		if (($startTime == 0) || ($endTime == 0)) {
			return 'N/A';
		}

	    $divider['years']   = (60 * 60 * 24 * 365);
	    $divider['months']  = (60 * 60 * 24 * 365 / 12);
	    $divider['weeks']   = (60 * 60 * 24 / 7);
	    $divider['days']    = (60 * 60 * 24);
	    $divider['hours']   = (60 * 60);
	    $divider['minutes'] = (60);
	    $divider['seconds'] = 1;

	    $elapsedTime = ((self::getMySqlToEpoch($endTime) - self::getMySqlToEpoch($startTime)) / $divider['seconds']);
	    $elapsedTime = sprintf("%0.0f", $elapsedTime);

	    if ($showText) {
	    	$elapsedTime .= ' sec';
		}

	    return $elapsedTime;
	}

	###########################################################################
	/**
	 * getMySqlToEpoch
	 *
	 * Returns the number of seconds since the epoch for a mysql date.
	 *
	 * @param string $date
	 */
	 ##########################################################################
	public static function getMySqlToEpoch($date)
	{
	    list($year, $month, $day, $hour, $minute, $second) = split('([^0-9])', $date);
	    return date('U', mktime($hour, $minute, $second, $month, $day, $year));
	}




	###########################################################################
	/**
	 * formatDate
	 *
	 * Takes a mysql formatted date/time and applies the user's offset and
	 * formats the date into user's format.
	 *
	 * @param string $date date to offset
	 * @param bool $removeTime remove time elements from date
	 */
	 ##########################################################################
	public static function formatDate($date, $removeTime,$fancy=false)
	{


		if (($date == '') || ($date == '0000-00-00') || ($date == '0000-00-00 00:00:00')) {
			return 'N/A';
		}

		# remove the time?
		if (($removeTime == true) && (strlen($date) > 10)) {
			$date = substr($date, 0, 10);
		}

		# break up into date and time parts
		$parts = explode(' ', $date);
		$dateParts = explode('-', $parts[0]);
		if (count($parts) == 2) {
			$timeParts = explode(':', $parts[1]);
		} else {
			$timeParts = array(0, 0, 0);
		}

		# create the timestamp
		$timestamp = mktime($timeParts[0], $timeParts[1], $timeParts[2],
			$dateParts[1], $dateParts[2], $dateParts[0]);

		if ($removeTime) {
			$replace = array('h', 'H', 'i', 'a', 'A', 'g', ':', ',', 's');
		} else {
			$replace = array();
		}

		# format the date based on admin date format config setting
		 $dateFormat = ($fancy) ? 'n/j/Y g:i ' : 'n-j-Y, g:i A';

		// WP
		// return date(get_option('date_format'), $timestamp) . " " . date(get_option('time_format'), $timestamp);


		$dateFormat = str_replace($replace, '', $dateFormat);
		$dateFormat = trim(date($dateFormat, $timestamp));


		return strtolower($dateFormat);
	}


	function convert_line_breaks($string, $line_break=PHP_EOL) {
			$patterns = array("/(<br>|<br \/>|<br\/>)\s*/i", "/(\r\n|\r|\n)/");
			$replacements = array(PHP_EOL,$line_break);
			$string = preg_replace($patterns, $replacements, $string);
			return $string;
 	}

	function NlToBr($inString)
     {
         return preg_replace("%\n%", "<br>", $inString);
     }
	 function esc_attr($str) {
		return esc_attr(str_replace(array("\t", "\r\n", "\n"), ' ', $str));
	}

	function isdate($str) {

		$stamp = strtotime( $str );

  		if (!is_numeric($stamp)) return false;

		$mm = date( 'm', $stamp );
		$dd = date( 'd', $stamp );
		$yy = date( 'Y', $stamp );

		return checkdate( $mm, $dd, $yy );
	}


	public static function unzipper($sourcezip,$destination) {

		// CBP::unzipper($this->LocalZip,$this->feed_dir);


		$zip = new ZipArchive;
		if(@$zip->open($sourcezip) === true){
			$zip->extractTo($destination);
			$zip->close();
			return true;
		}
		return false;
	}
}