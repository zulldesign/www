<?php
if(!defined('ABSPATH')) {
die();
}

		if(!function_exists('_log')){
		  function _log( $message ) {
		    if( WP_DEBUG === true || 1 == 1 ){
		      if( is_array( $message ) || is_object( $message ) ){
			error_log( print_r( $message, true ) );
		      } else {
			error_log( $message );
		      }
		    }
		  }
		}



class CBP_importlog_db {

	private $importRecord;
	private $db;
	private $tbl;
	private $logData = array();

	public function __construct() {

		global $wpdb;
		$this->db = &$wpdb;

		$this->tbl = CBTB_IMPORT;
	}

	function create($importMethod = 'cb-auto') {

		// $currentTime = cbpressfn::getMysqlDate('now');
		$currentTime = gmdate('Y-m-d H:i:s', current_time('timestamp'));
		$newRecord = array(
			'ts_start' => $currentTime,
			'ts_end' => '0000-00-00 00:00:00',
			'status' => 'In Progress',
			'log' => '',
			'type' => $importMethod
		);

		$this->db->insert($this->tbl, $newRecord);

		$lastid = $this->db->insert_id; // wp

		$this->importRecord = $newRecord;
		$this->importRecord['import_id'] = $lastid;

		return $lastid;
	}

	function load($impid = 0) {

		$id = intval($impid);
		$query = $this->db->prepare("SELECT * FROM {$this->tbl} WHERE import_id = %d", array($id));
		$this->importRecord = $this->db->get_row($query, ARRAY_A);
		return $impid;
	}

	static function fetchLatestDate($type) {
		global $wpdb;

		$tbl = CBTB_IMPORT;

		if($type == 'clickbank'){
			$type = "'cb-auto', 'cb-manual'";
		} else {
			$type = "'pdc-auto', 'pdc-manual'";
		}
		$query = "SELECT ts_end FROM {$tbl} WHERE type IN (" . $type . ") ORDER BY ts_end DESC"; // AND status = ?

		$importDate = $wpdb->get_var($query);

		if(!$importDate){
			return '';
			return 'N/A';
		} else {
			return CBP::formatDate($importDate, false);
			// return $importDate;
		}
	}

	static function fetchLatest($limit) {
		global $wpdb;
		$tbl = CBTB_IMPORT;
		$query = "SELECT import_id, ts_start, ts_end, status, type FROM {$tbl} ORDER BY ts_end DESC LIMIT ?";
		$q = $wpdb->get_row($query, $limit);
		return $q;
	}

	static function fetchAll() {
		global $wpdb;
		$tbl = CBTB_IMPORT;
		$query = "SELECT import_id, ts_start, ts_end, status, stats, type FROM {$tbl} ORDER BY ts_end DESC";
		$q = $wpdb->get_results($query, ARRAY_A);
		$out = array();
		foreach ($q as &$row) {
			$id = $row['import_id'];
			$row['actions'] = '';
			$dt = new DateTime($row['ts_start']);
			$ddd = $dt->format("M d, Y");
			$ttt = $dt->format("g:i a");

			// $row['actions'] = '<a href="' . CBP::make_action_url('view&id='.$id) . '" class="button-secondary">Details</a>';
			$row['actions'] .= ' ' . '<a href="' . CBP::make_action_url('delete&id=' . $id) . '" class="deleteme">' . CBP::img('action_delete.gif') . '</a>';
			$row['tt'] = CBP::getElapsedTime($row['ts_start'], $row['ts_end'], true);
			// $row['ts_start'] = $ddd;
			$row['time'] = $ttt;
			$row['ts_start'] = CBP::formatDate($row['ts_start'], false, false);
			$row['ts_end'] = CBP::formatDate($row['ts_end'], false, false);
			$row['stats'] = (object) unserialize($row['stats']);
			$out[] = (object) $row;
		}

		unset($q);

		// abort($q);
		return $out;
	}

	function addStatEntry($entry) {
		$entry = empty($entry) ? array() : $entry;
		$entry = is_array($entry) ? $entry : array();
		// $entry = json_encode((array) $entry);

		// $entry = base64_encode(serialize($entry));
		$entry = serialize($entry);
		$params = array($entry, $this->importRecord['import_id']);
		$query = "UPDATE {$this->tbl} SET stats = %s WHERE import_id = %d";
		$prep = $this->db->prepare($query, $params);
		$this->db->query($prep);
	}

	function addLogEntry($entry, $writeLog = false) {
		if(strlen($entry) == 0) {
			return;
		}
		$this->logData[] = $entry;
		if($writeLog) {
			$this->writeLog();
		}
	}

	function writeLog() {
		if(count($this->logData) == 0) {
			return;
		}

		// generate string and add timestamp
		$currentTime = cbpressfn::getMysqlDate('now');
		$entry = join("\n", $this->logData);
		$entry = "{$currentTime}: {$entry}\n";

		// update database

		$params = array($entry, $this->importRecord['import_id']);
		$query = "UPDATE {$this->tbl} SET log = CONCAT(log, %s) WHERE import_id = %d";
		$prep = $this->db->prepare($query, $params);
		$this->db->query($prep);

		$this->logData = array();
	}

	function close($status) {
		// $currentTime = cbpressfn::getMysqlDate('now');

		$currentTime = gmdate('Y-m-d H:i:s', current_time('timestamp'));

		$params = array($currentTime, $status, $this->importRecord['import_id']);
		$prep = $this->db->prepare("UPDATE {$this->tbl} SET ts_end = %s, status = %s WHERE import_id = %d", $params);
		$this->db->query($prep);
	}

	function fetch($id) {
		global $wpdb;
		$id = intval($id);
		$query = $wpdb->prepare("SELECT * FROM {CBTB_IMPORT} WHERE import_id = %d", array($id));
		$this->importRecord = $wpdb->get_row($query, ARRAY_A);
		return $this->_format();
	}

	function fetchInProgress() {
		$query = "SELECT * FROM {$this->tbl} WHERE status = ?";
		$this->importRecord = $this->db->get_row($query, 'In Progress');
		return $this->_format();
	}

	function _format() {
		// * makes import record a human readable array.
		if($this->importRecord === null) {
			return null;
		}
		// format dates
		$this->importRecord['ts_start'] = TimeDate::formatDate($this->importRecord['ts_start'], false);
		$this->importRecord['ts_end'] = TimeDate::formatDate($this->importRecord['ts_end'], false);
		if($this->importRecord['ts_end'] == null){
			$this->importRecord['ts_end'] = 'In progress';
		}
		// format log
		$this->importRecord['log'] = nl2br($this->importRecord['log']);
		return $this->importRecord;
	}

	function cancel($id) {
		if($id) {
			$this->db->update($this->tbl, array('status' => 'Finished'), array('import_id' => $id));
		}
	}

	function delete($id) {
		global $wpdb;
		if($id) {
			$wpdb->query("DELETE FROM " . CBTB_IMPORT . " WHERE import_id = $id LIMIT 1");
		}
	}
}

class cbp_importlog2 {

	var $log;
	private $name = 'cbpress_logger';

	public $data = array();

	public function __get($k) {
		return $this->data[$k];
	}

	public function __set($k, $v) {
		$this->data[$k] = $v;
	}







	public function setter($arr) {
		foreach ($arr as $name => $value) {

			if($name == 'step'){

				$this->step($value);

			}else if ($name == 'error'){

				$this->error($value);

			}else{
				$this->{$name} = $value;
			}
		}
	}

	public function __construct($_clear = false) {
		if($_clear){
			$this->clear();
		} else {
			$this->load();
		}
	}

	function load() {
		// $this->data = unserialize(urldecode(get_option($this->name)));
		$mydata = get_option($this->name);
		if(empty($mydata)) {
			$mydata = array();
		}

		$defaults = array(
			'done' => 0
		, 'toread' => 0
		, 'runtime' => 0
		, 'read' => 0
		, 'downloaded' => 0
		, 'unzipped' => 0
		, 'cats' => 0
		, 'products' => 0
		, 'batch' => 0
		, 'new' => 0
		, 'removed' => 0
		, 'relisted' => 0
		, 'updated' => 0
		, 'newcats' => 0
		, 'textupdate' => ''
		, 'cats_new' => 0
		, 'cats_removed' => 0
		, 'startTime' => time()
		, 'run' => array()
		, 'errors' => array()
		, 'steps' => array()
		);
		foreach ($defaults as $name => $value) {
			if(array_key_exists($name, $mydata)){
				$this->{$name} = $mydata[$name];
			} else {
				$this->{$name} = $defaults[$name];
			}
		}


	}

	function step($value) {
		// $this->steps[] = $value;
		$this->data['steps'][] = $value;

		$this->save();
	}

	function error($value) {
		$this->data['errors'][] = $value;
		$this->data['done'] = 1;
		$this->save();
	}

	function set_run($runstep, $complete = 0) {
		$this->data['run'][$runstep] = $complete;
		$this->save();
	}

	function did_run($runstep) {
		if(isset($this->data['run'][$runstep])){

			// $t = $this->data['_lastrun'];

			if($this->data['run'][$runstep] == 1) {
				return true;
			} // means it completed
		}
		return false;
	}

	function get_last_import() {
		$n = 'cbpress_last_import';
		return get_option($n);
	}

	function set_last_import() {
		$n = 'cbpress_last_import';
		// $newdata = urlencode(serialize($this->data));
		add_option($n, $this->data);
		update_option($n, $this->data);
		$this->clear();
	}

	function save() {
		// $newdata = urlencode(serialize($this->data));
		add_option($this->name, $this->data);
		update_option($this->name, $this->data);
	}

	function clear() {
		$busyfile = CBP_FEED_DIR . 'busy.txt';
		if(is_file($busyfile)) {
			unlink($busyfile);
		}

		delete_option($this->name);
		$this->load();
	}

	function getlog() {
		return $this->data;
	}
}

class cbp_importlog1 {

	static function createOption($clear = false) {
		return new cbp_importlog2($clear);
	}
}

class cbp_importlog {

	static function create($clear = false) {
		return cbp_importlog1::createOption($clear);
	}
}

class cbp_import_fn {

	static function fixCharacters($string) {
		$string = str_replace(array('&amp;', '&quot;', '&apos;', '&lt;', '&gt;'), array('&', '"', "'", '<', '>'), $string);
		return $string;
	}

	static function mtime($filepath) {
		if(is_file($filepath)){
			return filectime($filepath); // filemtime
		}
		return false;
	}

	static function get_correct_mtime($filePath) {
		$time = filemtime($filePath); // filemtime

		// $time2 = CBP::formatDate($time, false,false);
		// $time2 = gmdate("Y-m-d H:i:s", $time);
		// echo $time2 . '<br>';

		$isDST = (date('I', $time) == 1);
		$systemDST = (date('I') == 1);
		$adjustment = 0;
		if($isDST == false && $systemDST == true){
			$adjustment = 3600;
		} else {
			if($isDST == true && $systemDST == false){
				$adjustment = -3600;
			} else {
				$adjustment = 0;
			}
		}
		return ($time + $adjustment);
	}

	static function has_file_expired($cache_file, $seconds_to_live = 240) {

		_log(__METHOD__);



		if(!is_file($cache_file)) {
			return true;
		}

		// $cache_life = '120'; // caching time, in seconds

		$cache_life = $seconds_to_live;

		// $cache_life = '440';

		$thetime = self::get_correct_mtime($cache_file);

		$out = 0;

		if(!$thetime || ((time() - $thetime) >= $cache_life)){
			$out = 1;
		}
		// $test = (time() - $thetime) >= $cache_life;

		// $ttime = time();
		// $dif = time() - $thetime;
		// if($dif > $cache_life) $out = 1;
		// abort(get_defined_vars());

		return $out;

	}

	static function forceUTF8($text) {
		/**
		 * Function forceUTF8
		 *
		 * This function leaves UTF8 characters alone, while converting almost all non-UTF8 to UTF8.
		 *
		 * It may fail to convert characters to unicode if they fall into one of these scenarios:
		 *
		 * 1) when any of these characters:   ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞß
		 *	are followed by any of these:  ("group B")
		 *									¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶•¸¹º»¼½¾¿
		 * For example:   %ABREPRESENT%C9%BB. «REPRESENTÉ»
		 * The "«" (%AB) character will be converted, but the "É" followed by "»" (%C9%BB)
		 * is also a valid unicode character, and will be left unchanged.
		 *
		 * 2) when any of these: àáâãäåæçèéêëìíîï  are followed by TWO chars from group B,
		 * 3) when any of these: ðñòó  are followed by THREE chars from group B.
		 *
		 * @name forceUTF8
		 * @param string $text  Any string.
		 * @return string  The same string, UTF8 encoded
		 *
		 */

		if(is_array($text)){
			foreach ($text as $k => $v) {
				$text[$k] = self::forceUTF8($v);
			}
			return $text;
		}

		$max = strlen($text);
		$buf = "";
		for ($i = 0; $i < $max; $i++) {
			$c1 = $text{$i};
			if($c1 >= "\xc0"){ //Should be converted to UTF8, if it's not UTF8 already
				$c2 = $i + 1 >= $max ? "\x00" : $text{$i + 1};
				$c3 = $i + 2 >= $max ? "\x00" : $text{$i + 2};
				$c4 = $i + 3 >= $max ? "\x00" : $text{$i + 3};
				if($c1 >= "\xc0" & $c1 <= "\xdf"){ //looks like 2 bytes UTF8
					if($c2 >= "\x80" && $c2 <= "\xbf"){ //yeah, almost sure it's UTF8 already
						$buf .= $c1 . $c2;
						$i++;
					} else { //not valid UTF8.  Convert it.
						$cc1 = (chr(ord($c1) / 64) | "\xc0");
						$cc2 = ($c1 & "\x3f") | "\x80";
						$buf .= $cc1 . $cc2;
					}
				} elseif($c1 >= "\xe0" & $c1 <= "\xef") { //looks like 3 bytes UTF8
					if($c2 >= "\x80" && $c2 <= "\xbf" && $c3 >= "\x80" && $c3 <= "\xbf"){ //yeah, almost sure it's UTF8 already
						$buf .= $c1 . $c2 . $c3;
						$i = $i + 2;
					} else { //not valid UTF8.  Convert it.
						$cc1 = (chr(ord($c1) / 64) | "\xc0");
						$cc2 = ($c1 & "\x3f") | "\x80";
						$buf .= $cc1 . $cc2;
					}
				} elseif($c1 >= "\xf0" & $c1 <= "\xf7") { //looks like 4 bytes UTF8
					if($c2 >= "\x80" && $c2 <= "\xbf" && $c3 >= "\x80" && $c3 <= "\xbf" && $c4 >= "\x80" && $c4 <= "\xbf"){ //yeah, almost sure it's UTF8 already
						$buf .= $c1 . $c2 . $c3;
						$i = $i + 2;
					} else { //not valid UTF8.  Convert it.
						$cc1 = (chr(ord($c1) / 64) | "\xc0");
						$cc2 = ($c1 & "\x3f") | "\x80";
						$buf .= $cc1 . $cc2;
					}
				} else { //doesn't look like UTF8, but should be converted
					$cc1 = (chr(ord($c1) / 64) | "\xc0");
					$cc2 = (($c1 & "\x3f") | "\x80");
					$buf .= $cc1 . $cc2;
				}
			} elseif(($c1 & "\xc0") == "\x80") { // needs conversion
				$cc1 = (chr(ord($c1) / 64) | "\xc0");
				$cc2 = (($c1 & "\x3f") | "\x80");
				$buf .= $cc1 . $cc2;
			} else { // it doesn't need convesion
				$buf .= $c1;
			}
		}
		return $buf;
	}

	static function forceLatin1($text) {
		if(is_array($text)){
			foreach ($text as $k => $v) {
				$text[$k] = self::forceLatin1($v);
			}
			return $text;
		}
		return utf8_decode(self::forceUTF8($text));
	}

	static function fixUTF8($text) {
		if(is_array($text)){
			foreach ($text as $k => $v) {
				$text[$k] = self::fixUTF8($v);
			}
			return $text;
		}

		$last = "";
		while ($last <> $text) {
			$last = $text;
			$text = self::forceUTF8(utf8_decode(self::forceUTF8($text)));
		}
		return $text;

	}

	static function fix_double_encoding($string) {
		$utf8_chars = explode(' ', 'À Á Â Ã Ä Å Æ Ç È É Ê Ë Ì Í Î Ï Ð Ñ Ò Ó Ô Õ Ö × Ø Ù Ú Û Ü Ý Þ ß à á â ã ä å æ ç è é ê ë ì í î ï ð ñ ò ó ô õ ö');
		$utf8_double_encoded = array();
		foreach ($utf8_chars as $utf8_char) {
			$utf8_double_encoded[] = utf8_encode(utf8_encode($utf8_char));
		}
		$string = str_replace($utf8_double_encoded, $utf8_chars, $string);
		return $string;
	}

	static function isInternal($catname) {
		$internal = array('ClickBank Internal Use Accounts', 'Uncategorized', 'Foreign Language Skills Needed', 'Missing Site URL', 'No ClickBank Payment Link', 'Uncategorized', 'URL not functioning');
		if(in_array($catname, $internal)){
			return true;
		}
		return false;
	}

	static function timer_start() {
		$mtime = explode(' ', microtime());
		$mtime = $mtime[1] + $mtime[0];
		return $mtime;
	}

	static function timer_stop($start) {
		$mtime = explode(' ', microtime());
		$mtime = $mtime[1] + $mtime[0];
		return $mtime - $start;
	}

	static function clean_directory($dir, $recurse = false) {

		_log(__METHOD__);



		// $types = array("xml","zip","dtd");
		$types = array("zip", "dtd");
		$handle = opendir($dir);
		while (false !== ($resource = readdir($handle))) {
			if($resource != '.' && $resource != '..'){
				$extension = substr($resource, (strrpos($resource, ".") + 1));
				if(in_array($extension, $types)){
					if(substr($resource, 0, 2) != '__'){
						if(is_dir($dir . $resource)){
							if($recurse) {
								self::clean_directory($dir . $resource . '/', $recurse);
							}
						} else {
							unlink($dir . $resource);
						}
					}
				}
			}
		}
		closedir($handle);
	}

	static function strip_period($string) {
		return preg_replace("/\.$/", "", $string);
	}

	static function save_file($url, $fullpath) {

		_log(__METHOD__);


		$out = fopen($fullpath, 'wb');
		if($out == FALSE){
			return false;
			exit;
		}
		$ch = curl_init();
		// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,60); 
		curl_setopt($ch, CURLOPT_TIMEOUT,20);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_FILE, $out);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_exec($ch);
		curl_close($ch);
		return true;

	}
}






class CBP_import {

	private static $instance = null;
	static protected $opts = null;
	static public function getter() {
		if (!self::$instance){
			self::$instance = new self();
			self::$opts = CBP_settings::getter();
			
		}
		return self::$instance;
	}





	public $options;

	var $logger;
	var $log;
	var $error_message = null;
	var $globalCounter = 0;
	var $savenow = false;
	var $downcount = 0;
	var $cleancount = 0;
	var $_timeTaken = 0;
	var $_timeRemaining = null;
	var $ppc = 0;

	var $work = array(
		'ranks' => array(),
		'batch' => array(),
		'vins' => array(),
		'catpool' => array(),
		'catslugsfeed' => array(),
		'cat_slugs_in_db' => array(),
		'hopcids' => array(),
		'catcache' => array(),
		'hopcache' => array(),
		'cat_stack' => array(),
		'exclude_ids' => array(),
		'time_start' => 0,
		'time_end' => 0,
		'hopcount' => 0,
		'downloaded' => 0,
		'extracted' => 0,
		'feedloaded' => 0,
		'product_struct' => array(
			'vin' => 'Id',
			'title' => 'Title',
			'description' => 'Description',
			'ActivateDate' => 'ActivateDate',
			'PopularityRank' => 'PopularityRank',
			'Commission' => 'Commission',
			'HasRecurringProducts' => 'HasRecurringProducts',
			'Gravity' => 'Gravity',
			'InitialEarningsPerSale' => 'InitialEarningsPerSale',
			'AverageEarningsPerSale' => 'AverageEarningsPerSale',
			'TotalRebillAmt' => 'TotalRebillAmt',
			'Referred' => 'Referred',
			'PercentPerRebill' => 'PercentPerRebill',
			'PercentPerSale' => 'PercentPerSale'
		),
		'product_columns' => array(
			'vin' => '',
			'title' => '',
			'description' => '',
			'slug' => '',
			'ActivateDate' => '',
			'Commission' => '0',
			'HasRecurringProducts' => '0',
			'Gravity' => '0',
			'InitialEarningsPerSale' => '0',
			'AverageEarningsPerSale' => '0',
			'TotalRebillAmt' => '0',
			'Referred' => '0',
			'PercentPerRebill' => '0',
			'PercentPerSale' => '0'
		)
	);

	protected $statistics = array(
		'newProducts' => 0,
		'newCategories' => 0,
		'removedProducts' => 0,
		'productErrors' => 0,
		'productErrorText' => '',
		'updatedProducts' => 0
	);

	public $pergroup = 0;
	public $buffer = 0;
	public $xyzzy = 0;
	public $feed_dir = CBP_FEED_DIR;
	public $busyfile = null;
	private $stopfile = null;
	private $logfile = null;
	private $dom = null;
	private $logname = 'cbpress_logger';
	public $_path2feed = null;

	private $batch_columns = null;

	public function __construct($b = 0) {

		_log(__METHOD__);



		set_time_limit(0);

		global $wpdb;
		$this->db = &$wpdb;

		// $this->ppc = (CBP_api::activated()) ? 0 : 3;

		$this->ppc = 0;

		$this->buffer = $b;

		$this->error_message = '';

		$this->_status = array();

		// echo 'available zip: <br /><pre>';
		// print_r( $this->_zip_methods );
		// echo '</pre>';

		$this->_breakpoint = '';

		$this->options = CBP_settings::getter();

		$this->work = (object) $this->work;
		$this->work->exclude_ids = $this->get_exludes_arary();
		$this->work->time_start = cbp_import_fn::timer_start();

		$this->pergroup = $this->options->import_throttle;
		if(!is_numeric($this->pergroup)) {
			$this->pergroup = 300;
		}

		$this->feed_dir = CBP_FEED_DIR;
		$this->busyfile = CBP_FEED_DIR . 'busy.txt';
		$this->stopfile = CBP_FEED_DIR . 'stop.txt';
		$this->logfile = CBP_FEED_DIR . 'parselog.txt';
		$this->dom = new DOMDocument('1.0', 'iso-8859-1');
		$this->logname = 'cbpress_logger';

		$this->_path2feed = CBP_FEED_DIR . 'marketplace_feed_v2.xml';


		$this->batch_columns = implode(',', array_keys($this->work->product_columns));




		$this->usewpfilesystem = false; // url fopen
		$this->usewpfilesystem = true; // gd2

		$this->RemoteZip = $this->options->import_zip;
		// $this->RemoteZip = 'http://www.clickbank.com/feeds/marketplace_feed_v2.xml.zip';
		// $this->RemoteZip = 'https://accounts.clickbank.com/feeds/marketplace_feed_v2.xml.zip';



		$this->LocalZip = CBP_FEED_DIR . 'marketplace_feed_v2.xml.zip';
		$this->LocalFeed = CBP_FEED_DIR . 'marketplace_feed_v2.xml';


		// if($this->should_extract()){ }

		$this->captions = (object) array(
			'no_file_system' 	=> 'Failed: Filesystem preventing downloads',
			'http_no_url' 		=> 'Failed: Not a valid URL or upload',
			'incompatible_archive' 	=> 'Failed: Incompatible archive',
			'empty_archive' 	=> 'Failed: Empty Archive',
			'mkdir_failed' 		=> 'Failed: mkdir Failure',
			'copy_failed' 		=> 'Failed: Copy Failed',
			'too_many_requests' 	=> 'Empty Zip Archive. Please try again later'
		);


			// add_action( 'admin_head', array( &$this, 'importer_update_head' ) );


		// abort(get_defined_vars());
	}










	/***************************

	begin new zip testing 2

	 **************************/


	function use_wp_system() {

		_log(__METHOD__);

		return $this->usewpfilesystem;
	}

	function seterror($m) {

		_log(__METHOD__ . ' ---- ' . $m);



		$this->error_message = $m;
		return false;
	}

	function remotefeedurl() {
		return $this->RemoteZip;
	}




	function file_get_contents_curl($url) {

		_log(__METHOD__);


		if ( $url == '' || $url == null ) { return ''; }
		$data = '';
		$response = wp_remote_get( $url );

		if ( is_wp_error( $response ) ) {
			$data  = $url;
		} else {
			$data = $response['body'];
		}
		return $data;
	} 



	// Checks for presence of the cURL extension.
	function _iscurlinstalled() {
		if  (in_array  ( 'curl', get_loaded_extensions())) {
			if (function_exists( 'curl_init')) {
				return true;
			} else {
				return false;
			}
		}
		else{
			if (function_exists( 'curl_init')) {
				return true;
			} else {
				return false;
			}
		}
	}
	//TinyURL
	function getTinyUrl($url) {
		$tinyurl = file_get_contents_curl( "http://tinyurl.com/api-create.php?url=".$url);
		return $tinyurl;
	}















	function download_package($package) {
		if ( ! preg_match('!^(http|https|ftp)://!i', $package) && file_exists($package) ){  return $package; } //Local file or remote?
		if ( empty($package) ){ return new WP_Error('no_package', 'no package'); }
		$download_file = download_url($package);
		if ( is_wp_error($download_file) ){ return new WP_Error('download_failed', 'download failed', $download_file->get_error_message()); }
		return $download_file;
	}




	function unpack_package($package) {

		global $wp_filesystem;

		$upgrade_folder = $wp_filesystem->wp_content_dir() . 'upgrade/';
		$upgrade_files  = $wp_filesystem->dirlist($upgrade_folder);
		if ( !empty($upgrade_files) ) {
			foreach ( $upgrade_files as $file ){
				$wp_filesystem->delete($upgrade_folder . $file['name'], true);
			}
		}
		$wd = $upgrade_folder . basename($package, '.zip'); // We need a working directory
		if ( $wp_filesystem->is_dir($wd) ){ $wp_filesystem->delete($wd, true); } // Clean up working directory
		$result = unzip_file($package, $wd); 	// Unzip package to working directory


		/***/ $data = get_defined_vars(); abort($data); /***/ 



		if ( is_wp_error($result) ) {
			$wp_filesystem->delete($wd, true);
			return $result;
		}

		return $working_dir;
	}





			/*********** 
				try { 

					echo "Upload Complete!"; 
				} catch (Exception $e) { 
					die ('File did not upload: ' . $e->getMessage()); 
				} 

			*********/




	function getcap($ec='',$append=0) {
		if(isset($this->captions->{$ec})){
			if($append == 1){  
				return $this->captions->{$ec} . ' - ' . $ec;
			}else{
				return $this->captions->{$ec};
			}
		}
		return $ec;
	}


	public function C__call($f, $a) { 

			_log(__METHOD__);

		// dump(get_defined_vars());

		// return self::__callStatic($f, $a);  
	} 



	function CCC__call($method,$args){


			_log(__METHOD__);


		if(method_exists($this,$method)){
			$this->$method($args);
		}
	}




	function init_filesystem() { 





		_log(__METHOD__);





		// _log(array('it' => 'works'));



		global $wp_filesystem;
       		if (!$wp_filesystem || !is_object($wp_filesystem)) {
			require_once (preg_replace("/wp-content.*/","/wp-admin/includes/file.php",__FILE__));
			WP_Filesystem();

		}
		if (!is_object($wp_filesystem)){ 
			return FALSE; 
		}        
		return TRUE;
	}









	function kill_path2zip() {

		_log(__METHOD__);


		if(is_file($this->LocalZip)) {
			// unlink($this->LocalZip);
		}
	}

	function should_download() {

		_log(__METHOD__);


		$out = cbp_import_fn::has_file_expired($this->LocalZip, '240');
		if($out){
			// $this->kill_path2zip();
		}
		return $out;
	}

	function should_extract() {

		_log(__METHOD__);


		$out = cbp_import_fn::has_file_expired($this->LocalFeed, '240');
		if($out){
			if(is_file($this->LocalFeed)) {



				return false;


				// unlink($this->LocalFeed);
			}
		}
		return $out;
	}

	function getSourceFiles() {


		_log(__METHOD__);


		$source_files  = array();

		if($this->init_filesystem()){

			global $wp_filesystem;
			$source_files = array_keys( $wp_filesystem->dirlist(CBP_FEED_DIR) );

		}
		return $source_files;
	}









	function grab_unpack() {





		_log(__METHOD__);



		@set_time_limit( 300 );

		$this->downcount++;


		$out = (object) array('msg' => 'Feed successfully downloaded and extracted', 'err' => '0');

		$tmp 	= $this->LocalZip;
		$error  = '';
		$method = 'unknown';
		$notes 	= $this->log;

		// if($this->downcount == 1){




		if($this->work->feedloaded == 0){

			// TRY 1
			if(!$this->init_filesystem()){



				$error = $this->getcap('no_file_system')  . ' - ' . $method; 



			} else {






				global $wp_filesystem;
				$method = get_filesystem_method();

				if(isset($_POST['cbpress_ftp_cred'])){
					$cred = unserialize(base64_decode($_POST['cbpress_ftp_cred']));
					$filesystem = WP_Filesystem($cred);
				} else {
					$filesystem = WP_Filesystem();
				}






				if($filesystem && $this->should_download()){

					_log('------- filesystem and shoud_download pass');

					$tmp = download_url($this->RemoteZip); // DL
					if(is_wp_error($tmp)){


						_log('------- ERROR download_url: ' . $tmp->get_error_code());


						$error = $this->getcap($tmp->get_error_code(), 1);
					} else {



					}

				}
				if($filesystem){

					_log('------- unzip_file');

					$contents = unzip_file($tmp, $this->feed_dir);
					if(is_wp_error($contents)){
						$error = $contents->get_error_code();
						$data = $contents->get_error_data($error);
						$error = $this->getcap($error, 1) . ' ' . $data;

						_log('------- ERROR unzip_file: ' . $error);

					}else{
						unlink($tmp);
					}
				}
			}



			// FALLBACK

			if(trim($error) !== ''){

					_log('------- FALLBACK for download and extract');

				$okay = cbp_import_fn::save_file($this->RemoteZip, $this->LocalZip); // TRY CURL
				if(! $okay){					
					$zipcontents = $this->file_get_contents_curl($this->RemoteZip); // TRY wp_remote_get
					if(strlen($zipcontents) > 2000){
						$okay = CBP_http::file_put_contents($this->LocalZip,$zipcontents); // TRY fopen fwrite to save
					}








				}
				if($okay){
					$error = '';
					$notes->setter(array('downloaded' => '1', 'step' => 'Downloading Feed', 'textupdate' => 'Downloading Feed'));



					$unzipped = CBP::unzipper($this->LocalZip,$this->feed_dir);

					if($unzipped){
						$this->work->extracted = 1;
						$notes->unzipped = 1;
						if(is_file($this->LocalZip)){
								// unlink($this->LocalZip);
						}
						$error = '';
					} else {
						$error = $this->getcap('too_many_requests',1);
						$notes->setter(array('unzipped' => '-1', 'error' => $error, 'textupdate' => $error));
					}
				} else {
					$error = 'Unable to download zip archive using cURL.';
					$notes->setter(array('downloaded' => '-1', 'error' => $error, 'textupdate' => $error));
				}
			}


			/***************
			if(! is_file($tmp)) $zipdo = 0;

			if($this->should_extract()){
			}
			*************/



			if($error == ''){
				$out->err = 0;
				$out->msg = 'okay';
				$this->work->feedloaded = 1;
			}else{
				$out->msg = $error;
				$out->err = 1;
				$this->seterror($error);
			}	
		}


		return $out;



		// if(is_file($tmp)) unlink($tmp);
		// $data = get_defined_vars();
		// abort($data);
		// if(is_file($this->LocalFeed))  unlink($this->LocalFeed);
	}




		// echo $error. ' - '; print_r($data);
		// $data = get_defined_vars();
		// abort($data);
		// echo '<p>Successfully downloaded AND extracted Markeplace zip.</p>';
     		// $url = admin_url( 'admin.php?page=cbpress-import' );
		// $cc = request_filesystem_credentials ( $url );
		// abort($cc);








	function __destruct() {
		return true;
	}

	function getlog() {

		_log(__METHOD__);

		$this->log = new cbp_importlog(false);
	}

	function is_busy() {


		// _log(__METHOD__);

		// busy if file exists and not too old
		if(is_file($this->busyfile)){
			$status = true;
			if(cbp_import_fn::has_file_expired($this->busyfile, '240')){
				$this->busy_done();
				$status = true;
			}
		} else {
			$status = false;
		}
		return $status;
	}

	function busy_new() {

		// _log(__METHOD__);

		CBP_http::file_put_contents($this->busyfile, 'xxxxx' . $this->work->time_start);
	}

	function busy_done() {

		// _log(__METHOD__);

		if(is_file($this->busyfile)){
			unlink($this->busyfile);
		}
	}






	// manual import
	function manual_import_step1() {

		_log(__METHOD__);

		@set_time_limit(0);
		$impid = 0;
		$this->log = cbp_importlog::create(true);


		$okay = $this->grab_unpack();


		if($this->error_message == ''){
			$this->busy_new();
			$impid = $this->newImportLog('Feed file opened, parsing...');
			$this->category_extract();
			return $impid;

		} else {
			$this->busy_done();
			return false;
		}
	}

	function manual_import_step2($impid = 0) {

		_log(__METHOD__);

		@set_time_limit(0);

		$this->log = cbp_importlog::create(false);


		// extract to temp tables

			$this->busy_new();
			$impid = $this->newImportLog('Feed file opened, parsing...');
			$this->category_extract();
			return $impid;


		// do database work

		mysql_query('BEGIN');
		$this->clean_house();
		mysql_query('COMMIT');
		$this->set_done();
		$this->truncate_parse_tables();

		// add import log

		$message = "Manual Import Complete";
		$this->importRecord = new CBP_importlog_db();
		$this->importRecord->load($impid);
		$this->importRecord->addLogEntry($message, true);
		$this->importRecord->addStatEntry($this->log->getlog());
		$this->importRecord->close('Finished');

		$this->busy_done();

	}


	// STEP 1
	function newImportLog($msg) {

		_log(__METHOD__);

		$this->importRecord = new CBP_importlog_db();
		$id = $this->importRecord->create();
		$this->importRecord->addLogEntry($msg, true);
		return $id;
	}

	// STEP 1
	function import_all_in_one() {

		_log(__METHOD__);

		@set_time_limit(0);
		$this->busy_new();
		// start a new log entries
		$this->log = cbp_importlog::create(true);

		$this->newImportLog('Feed file opened, parsing...');

		$okay = $this->grab_unpack();

		if($okay->err == 0){




			$this->category_extract();

			$this->importRecord->close('Finished');
			$this->options->last_import = gmdate('Y-m-d H:i:s', current_time('timestamp'));
			$this->options->save();
		}


		/**************
		if($this->use_wp_system()){

			$okay = $this->grab_unpack();

		} else {
			if(!$this->download_feed()){
				$this->busy_done();
				return 'Error: could not download new feed.';
			}
			if(!$this->unzip_feed()){
				$this->busy_done();
				return 'Error: could not extract feed from zip file.';
			}
		}
		**************/


	}













	// STEP 1
	function import_start() {

		_log(__METHOD__);


		CBP::flush_cache();

		$this->busy_new();
		$this->log = cbp_importlog::create(true);
		$this->log->set_run(1, 0);

		$result = $this->grab_unpack();




		if($result->err){
			return $result;
		}
		$this->log->set_run(1, 1);

		return $result;
	}

	// STEP 2
	function import_run() {

		_log(__METHOD__);


		$this->busy_new();
		@set_time_limit(0);

		// start a new log entries
		$this->newImportLog('Feed file opened, parsing...');

		$this->log = cbp_importlog::create(false);
		$this->log->set_run(2, 0);
		if(!$this->log->did_run(1)){
			$this->log = cbp_importlog::create(true);
		}

		$this->category_extract();

		$this->options->last_import = gmdate('Y-m-d H:i:s', current_time('timestamp'));
		$this->options->save();
		$this->log->set_run(2, 1);

		return $this->log;
	}



	function get_exludes_arary() {


		$excludes = $this->options->import_excludehops;
		$excludes = trim(implode(',', array_map('trim', explode(',', $excludes))));
		$excludes = cbpressfn::struct($cols = $excludes);
		$excludes = (array) $excludes;
		return $excludes;
	}

	function is_excluded($hop) {


		return isset($this->work->exclude_ids[$hop]);
	}

	function map_feed_fields($prod) {
		$out = array();
		foreach ($this->work->product_struct as $dbcol => $feedcol) {
			$skip = ($feedcol === null ? true : false);
			if(!$skip || 1 == 1){
				if(isset($prod[$feedcol])){
					$out[$dbcol] = $this->formatcol($dbcol, $prod[$feedcol]);
				}
			}
			unset($skip);
		}
		return $out;
	}

	function how_many_nodes() {

		_log(__METHOD__);




		if(1 == 2){
			if($this->work->feedloaded == 0){



				$result = $this->grab_unpack();

				if($result->err) {
					return $result;
				}
			}
		}








		$feedFile = $this->LocalFeed;
		$reader = new XMLReader();
		$reader->open($feedFile);
		$counter = 0;
		$percategory = 0; // might cause problem having this here
		$catcounter = 0;
		while ($reader->read()) {
			switch ($reader->nodeType) {
				case XMLReader::ELEMENT:
					switch ($reader->name) {
						case 'Name':
							$percategory = 0;
							$catcounter++;
							break;
						case 'Site':
							$percategory++;
							if($this->ppc > 0 && $percategory > $this->ppc){
								break;
							}
							$counter++;
							break;
					}
					break;
			}
		}
		$reader->close();
		unset($reader, $catcounter);
		return $counter;
	}

	function mysql_escape($v) {
		return mysql_real_escape_string($v, $this->db->dbh);
	}

	function prod_to_string($product) {
		$string = array();
		foreach ($this->work->product_columns as $column => $default) {
			if(isset($product[$column]) || ($product[$column] !== null)){
				$v = $product[$column];
			} else {
				$v = $default;
			}
			$v = stripslashes($v);
			$string[] = '"' . $this->mysql_escape($v) . '"';
			unset($v, $column, $default);
		}

		$string = implode(',', $string);
		$string = "($string)";

		$this->work->batch[] = $string;

		// insert bulk
		if(count($this->work->batch) >= $this->pergroup){
			$this->offload_batch();
		}

		unset($data, $string, $product);

	}

	function rank_to_string($vin = '', $lid = '', $cid = '', $rank = '') {

		$data = get_defined_vars();
		$string = array();
		foreach ($data as $k => $v) {
			$v = stripslashes($v);
			$string[] = ($k == 'vin') ? '"' . $this->mysql_escape($v) . '"' : $this->mysql_escape($v);
			unset($k, $v);
		}

		$string = implode(',', $string);
		$string = "($string)";
		$this->work->ranks[] = $string;

		// insert bulk
		if(count($this->work->ranks) >= $this->pergroup){
			$this->offload_ranks();
		}

		unset($data, $string, $vin, $lid, $cid, $rank);
	}

	function offload_batch() {

		// _log(__METHOD__);

		if(count($this->work->batch) > 0){
			$sql = $this->work->batch;
			$this->work->batch = array();
			$sql = implode(',', $sql);
			$sql = 'INSERT INTO ' . CBTB_PARSE_PROD . ' (' . $this->batch_columns . ') VALUES ' . $sql;
			mysql_query('BEGIN');
			$this->db->query($sql);
			mysql_query('COMMIT');
			unset($sql);
		}
	}

	function offload_ranks() {

		// _log(__METHOD__);

		if(count($this->work->ranks) > 0){
			$sql = $this->work->ranks;
			$this->work->ranks = array();
			$sql = implode(',', $sql);
			$sql = 'INSERT INTO ' . CBTB_PARSE_TREE . ' (vin,lid,cid,rank) VALUES ' . $sql;
			mysql_query('BEGIN');
			$this->db->query($sql);
			mysql_query('COMMIT');
			unset($sql);
		}
	}

	function update_counter_thing($hop) {

		if(!isset($this->work->hopcache->active->$hop) && !isset($this->work->hopcache->removed->$hop)){
			$this->log->new++;
		} else {
			if(isset($this->work->hopcache->active->$hop)){
				$this->log->updated++;
			} else {
				$this->log->relisted++;
			}
		}
		$this->log->products++;
	}

	function flush_output() {
		wp_ob_end_flush_all();
		flush();
	}

	function category_extract() {

		_log(__METHOD__);





		$this->truncate_parse_tables();


		$this->load_cache();



		$feedFile = $this->LocalFeed;

		$this->log->toread = $this->how_many_nodes();





		$this->log->save();

		$counter = 0;
		$cid = 0;  // might cause problem having this here

		$reader = new XMLReader();
		$reader->open($feedFile);

		$started = $this->work->time_start;

		if($this->buffer == 0){
			$this->importRecord->addLogEntry("Reading XML Feed", true);
		}

		$this->log->textupdate = 'Reading XML Feed... ';

		while ($reader->read()) {

			switch ($reader->nodeType) {
				case XMLReader::ELEMENT:
					switch ($reader->name) {
						case 'Category':
							# do nothing
							break;
						case 'Name':
							$reader->read();
							$category = trim($reader->value);
							array_push($this->work->cat_stack, $category);

							$stcat = $this->process_cat($this->work->cat_stack);

							$cid = $stcat->cid;
							$this->work->catslugsfeed[$stcat->slug] = $cid;
							$this->work->catpool[$stcat->cid] = $stcat->slug;

							$this->log->cats++;
							$this->log->runtime = $this->get_runtime();
							// $this->log->textupdate = 'processing ' . $this->log->cats . ' categories';
							$this->log->save();

							if($this->buffer == 1){
								echo $stcat->slug . '<br>';
								$this->flush_output();
							}

							$percategory = 0;
							unset($stcat, $category);
							break;

						case 'Site':

							if(!$productText = @$reader->expand()){
								break;
							}
							$product = $this->get_productfromtext($productText);
							$hop = $product['vin'];
							$this->log->read++;
							if($this->is_excluded($hop) === false){
								if(!isset($this->work->vins[$hop])){
									$this->work->vins[$hop] = 1;
									$this->update_counter_thing($hop);
									$product['lid'] = 0;
									$product['slug'] = cbpressfn::CleanForUrl($product['title'], 255) . '-' . $hop;
									$this->prod_to_string($product); // adds to batch
								}
								$this->rank_to_string($hop, '0', $cid, $product['PopularityRank']); // adds to ranks
							}
							unset($hop, $productText, $product);
							break;
					}
					break;
				case XMLReader::END_ELEMENT:
					switch ($reader->name) {
						case 'Category':
							$percategory = 0;
							$pop = array_pop($this->work->cat_stack);
							break;
					}
			}
		}

		$reader->close();
		unset($reader);

		// insert bulk remaining
		$this->offload_batch();
		$this->offload_ranks();
		$this->log->textupdate = 'Finalizing import & verifying data...';
		$this->log->save();

		// report removed count
		$report_removed = 1;
		if($report_removed){
			// Removed Product Count
			foreach ($this->work->hopcache->removed as $key => $value) {
				if(!isset($this->work->vins[$key])){
					$this->log->removed++;
				}
			}
			// Removed Category Count
			foreach ($this->work->cat_slugs_in_db as $key => $value) {
				if(!isset($this->work->catslugsfeed[$key])){
					$this->set_category_status($value, 1);
					$this->log->cats_removed++;
				} else {
					$this->set_category_status($value, 0);
				}
			}
			unset($this->work->hopcache);
			unset($this->work->catslugsfeed);
		}
		unset($this->work->vins);
		$this->log->save();

		if($this->buffer == 0){
			mysql_query('BEGIN');








			// echo 'cleaning house from category extract';

			$this->clean_house();

			mysql_query('COMMIT');

			$ended = cbpressfn::getTime();
			$this->set_done();
			$this->truncate_parse_tables();

			## add import log
			$message = "Finished parsing.\nStatistics:\nNew Products: {$this->log->new}\n
			New Categories: {$this->statistics['newCategories']}\n
			Removied Categories: {$this->log->cats_removed}\n
			";
			$this->importRecord->addLogEntry($message, true);
			$this->importRecord->addStatEntry($this->log->getlog());
			$this->importRecord->close('Finished');

		}

		$this->busy_done();

		unset($this->work);
	}

	function get_productfromtext($productText) {

		$xdom = new DOMDocument('1.0', 'iso-8859-1');
		$product = $xdom->importNode($productText, true);
		$xdom->appendChild($product);
		unset($xdom);
		$product = simplexml_import_dom($product);

		$product->Title = (string) $product[0]->Title;
		$product->Description = (string) $product[0]->Description;

		$product = get_object_vars($product); // CONVERT TO ARRAY
		$product = array_map('trim', $product);
		$product = array_map('html_entity_decode', $product);

		$product = $this->map_feed_fields($product);

		$fcols = array('description', 'title');
		foreach ($fcols as $c) {
			$product[$c] = cbp_import_fn::strip_period($product[$c]);
			$product[$c] = cbp_import_fn::fixUTF8($product[$c]);
			$product[$c] = cbp_import_fn::fixCharacters($product[$c]);
			$product[$c] = strip_tags($product[$c]);
		}
		unset($fcols, $productText);

		$product['vin'] = strtolower($product['vin']);

		return $product;
	}

	function set_category_status($cid = 0, $removed = 0) {

		global $wpdb;

		$query = 'UPDATE ' . CBTB_CAT . " SET removed = $removed WHERE cid = $cid LIMIT 1";
		$wpdb->query($query);
		return true;
	}

	function get_runtime() {

		// _log(__METHOD__);

		return cbp_import_fn::timer_stop($this->work->time_start);
	}

	function set_runtime() {

		_log(__METHOD__);

		$this->log->runtime = $this->get_runtime();
		$this->log->save();
	}

	function set_done() {

		_log(__METHOD__);

		$this->log->runtime = $this->get_runtime();
		$this->log->toread = 0;
		$this->log->done = 1;
		$this->log->save();
	}

	function load_cache() {

		_log(__METHOD__);

		$this->load_products();
		$this->load_categories();
		return true;
	}

	function load_products() {

		_log(__METHOD__);

		$query = "SELECT vin, lid, status FROM " . CBTB_PROD . " WHERE source = 'clickbank'";
		$result = $this->db->get_results($query);

		$out = new stdClass();
		$out->active = (object) array();
		$out->removed = (object) array();
		if(count($result)){
			foreach ($result as $row) {
				$out->{$row->status}->{$row->vin} = $row->lid;
			}
		}
		$this->work->hopcache = &$out;
		unset($result);
		return true;
	}

	function load_categories() {

		_log(__METHOD__);

		$query = "SELECT xpath,cid,pid,slug,name,depth,full,enabled";
		$query .= " FROM " . CBTB_CAT . " WHERE type = 'clickbank' ORDER BY xpath ";
		$result = $this->db->get_results($query, OBJECT_K);
		if($result){
			$this->work->catcache = &$result;
			foreach ($result as $xpath => $cat) {
				$this->work->cat_slugs_in_db[$cat->slug] = $cat->cid;
			}
		}
		unset($query);
		return true;

	}

	function truncate_parse_tables() {

		_log(__METHOD__);

		$this->db->query("TRUNCATE TABLE " . CBTB_PARSE_TREE);
		$this->db->query("TRUNCATE TABLE " . CBTB_PARSE_PROD);
		return 'Temp Tables Cleared';
	}

	function pp_params($cols) {
		// prepare sql for specific clickbank attributes
		$params = array();
		foreach (explode(',', $cols) as $c) {
			$params[] = CBTB_PROD . ".{$c} = " . CBTB_PARSE_PROD . ".{$c}";
		}
		$params = implode(',', $params);
		return $params;
	}

	function clean_house() {


		_log(__METHOD__);




		if($this->cleancount > 0){

			return false;


		}

		$this->cleancount++;




		$join = CBTB_TREE;
		$prod = CBTB_PROD;
		$parse = CBTB_PARSE_PROD;
		$ranktemp = CBTB_PARSE_TREE;
		$jointemp = CBTB_PARSE_USER;

		$update_lid_in_parse = "update {$parse} inner join {$prod} on ({$parse}.vin = {$prod}.vin) set {$parse}.lid = {$prod}.lid";
		$update_lid_in_ranktemp = "update {$ranktemp} inner join {$prod} on ({$ranktemp}.vin = {$prod}.vin) set {$ranktemp}.lid = {$prod}.lid";

		$sql = array();

		$date = date("Y-m-d H:i:s");

		$stats_cols = 'ActivateDate,Commission,HasRecurringProducts,Gravity,InitialEarningsPerSale,AverageEarningsPerSale,TotalRebillAmt,Referred,PercentPerRebill,PercentPerSale';

		$update_cols = 'title,description,slug';

		$insert_cols = 'vin,title,description,slug,ActivateDate,Commission,HasRecurringProducts,Gravity,InitialEarningsPerSale,AverageEarningsPerSale,TotalRebillAmt,Referred,PercentPerRebill,PercentPerSale';
		$auto_active = $this->options->import_autoactive;
		$notinfeed = $this->options->import_notinfeed;

		$sync = $this->options->import_autosync;

		$treecols = 'lid,cid,rank,join_custom,join_enable';

		$params_clickbank = $this->pp_params($stats_cols); // sql for specific clickbank attributes
		$params_primary = $this->pp_params($update_cols); // sql for title, description update if applicable

		$renew = array();
		$renew[] = "$prod.status = 'active'";
		$renew[] = "$prod.date_status = NOW()";
		if($auto_active == 1){
			$renew[] = "$prod.active = 1";
		}
		$params_renew = implode(', ', $renew);

		$vin_vin = "{$parse}.vin = {$prod}.vin";

		# UPDATE LID IN PARSE AND RANK TEMP

		$sql[] = $update_lid_in_parse;
		$sql[] = $update_lid_in_ranktemp;

		# IMPORT_AUTOACTIVE re-activate products where active = 0 and not in feed

		if($auto_active == 1){
			$sql[] = "UPDATE $prod SET $prod.active = 1 WHERE EXISTS ( SELECT $parse.vin FROM $parse WHERE $vin_vin AND $prod.active = 0 )";
		}

		# Reactivate previously removed from feed
		$sql[] = "UPDATE $prod SET $params_renew WHERE EXISTS (SELECT $parse.vin FROM $parse WHERE $vin_vin AND $prod.status = 'removed')";

		# HANDLE NOTINFEED
		$pool = new stdClass();
		$not_exists = "$prod.source = 'clickbank'
		AND status <> 'removed'
		AND (NOT EXISTS (SELECT $parse.vin FROM $parse WHERE $vin_vin))";

		$pool->disable = "UPDATE $prod SET $prod.active = 0, $prod.status = 'removed', $prod.date_status = NOW() WHERE $not_exists";
		$pool->delete = "DELETE FROM $prod WHERE $not_exists";

		// $notinfeed is a setting that match one of the aboove two

		if(isset($pool->$notinfeed)) {
			$sql[] = $pool->$notinfeed;
		}

		# Insert new products
		$sql[] = "INSERT INTO {$prod} ({$insert_cols},status,source,created) SELECT {$insert_cols},'active','clickbank',NOW() FROM {$parse} WHERE {$parse}.lid = 0";

		# update lid in parse and rank temp, Again
		$sql[] = $update_lid_in_parse;
		$sql[] = $update_lid_in_ranktemp;

		# AUTO SYNC unless auto_update = 0 (gotta be a better way to do this)
		# Update Feed title and Desc
		# set Products.FEED_TITLE ___ to ___ Parsed.TITLE
		//// add stat: updated title change if edited count

		## update where it has not changed
		$sql[] = " UPDATE {$prod} p INNER JOIN {$parse} prs ON (p.vin = prs.vin)
			 SET p.title = prs.title
			 WHERE (STRCMP(LOWER(p.title), LOWER(p.feed_title)) = 0)
			 AND p.source = 'clickbank'";

		$sql[] = " UPDATE {$prod} p INNER JOIN {$parse} prs ON (p.vin = prs.vin)
			 SET p.description = prs.description
			 WHERE (STRCMP(LOWER(p.description), LOWER(p.feed_desc)) = 0)
			 AND p.source = 'clickbank'";

		/// now update feed_title and feed_desc with one from feed
		$sql[] = " UPDATE {$prod} p INNER JOIN {$parse} prs ON (p.vin = prs.vin)
			 SET p.slug = prs.slug,
			 p.feed_title = prs.title,
			 p.feed_desc = prs.description";

		## 1. sync unedited title
		## 1. update feed_title

		if($sync && 1 == 2){
			// this might be irrelevant
			$sql[] = " UPDATE {$prod} p INNER JOIN {$parse} prs ON (p.vin = prs.vin)
			SET p.title = prs.title,
			p.description = prs.description
			WHERE p.auto_update = 1";
		}

		# Update Average Rank from new rankings
		$sql[] = "UPDATE {$prod} p SET p.PopularityRank = (SELECT AVG(j.rank) FROM {$ranktemp} j WHERE j.vin = p.vin) WHERE EXISTS ( SELECT r.vin FROM {$ranktemp} r WHERE r.vin = p.vin )";
		# Update PopularityRank to zero for any products that are not in feed
		$sql[] = "UPDATE {$prod} p SET p.PopularityRank = 0 WHERE NOT EXISTS ( SELECT ppp.vin FROM {$parse} ppp WHERE ppp.vin = p.vin )";
		# Update clickbank specific stats
		$sql[] = "update {$prod} inner join {$parse} on ({$prod}.vin = {$parse}.vin) SET {$params_clickbank}";
		# clear old entries
		$sql[] = "TRUNCATE TABLE " . $jointemp;
		# preserve tree state
		$sql[] = "INSERT INTO {$jointemp} ($treecols) SELECT $treecols FROM {$join}";
		# Flush tree
		$sql[] = "TRUNCATE TABLE {$join}";
		# Insert new
		$sql[] = "INSERT INTO {$join} (lid,cid,rank) SELECT lid,cid,rank FROM {$ranktemp}";
		# re-insert only if the product wasn't moved to category by feed
		$sql[] = "INSERT INTO {$join} ($treecols) SELECT $treecols FROM {$jointemp}  WHERE {$jointemp}.join_custom = 1";
		# Restore Join_Enable value
		$sql[] = "UPDATE {$join} j INNER JOIN {$jointemp} t ON (j.cid = t.cid) AND (j.lid = t.lid) SET j.join_enable = t.join_enable";
		$sql[] = "TRUNCATE TABLE " . $jointemp;

		// abort($sql);

		for ($i = 0; $i < count($sql); $i++) {
			$query = $sql[$i];
			$this->db->query($query);
		}

		unset($sql);

		return true;
	}

	function clean_for_url($value) {
		return cbpressfn::CleanForUrl($value);
	}

	function get_xcat($xpath, $cols = '*') {
		// arts-entertainment@photography
		// betting-systems@casino-table-games
		if(isset($this->work->catcache[$xpath])){
			return $this->work->catcache[$xpath];
		} else {
			$this->db->get_row($this->db->prepare("SELECT $cols FROM " . CBTB_CAT . " WHERE xpath = %s", array($xpath)), OBJECT);
		}
	}

	function get_pid($xpath) {
		if(isset($this->work->catcache[$xpath])){
			return $this->work->catcache[$xpath]->cid;
		} else {
			return intval($this->db->get_var($this->db->prepare("SELECT cid FROM " . CBTB_CAT . " WHERE xpath = %s", array($xpath)), OBJECT));
		}
	}

	function process_cat($in_array) {

		$name = $in_array[count($in_array) - 1]; // last element

		if(cbp_import_fn::isInternal($name)) {
			return false;
		}

		$stack = array_map(array(&$this, 'clean_for_url'), $in_array);
		$xpath = implode('@', $stack);
		$row = $this->get_xcat($xpath);

		if(is_null($row)){
			$depth = count($in_array);
			$row = (object) array();
			$row->name = $name;
			$row->full = implode(' : ', $in_array);
			$row->slug = cbpressfn::CleanForUrl($row->full);
			$row->depth = $depth - 1;
			$row->xpath = $xpath;
			$row->pid = 0;
			$row->enabled = 1;
			$row->type = 'clickbank';

			if($depth > 1){
				// this is a sub cat
				array_pop($stack);
				$row->pid = $this->get_pid(implode('@', $stack));
			}
			$this->db->insert(CBTB_CAT, (array) $row);
			$row->cid = (int) $this->db->insert_id;
			$this->statistics['newCategories']++;
			$this->log->cats_new++;
			$this->work->catcache[$xpath] = $row; // add to cache
		}

		unset($stack, $name, $xpath);
		return $row;
	}

	function formatcol($col, $value = '') {

		switch ($col) {
			case 'AverageEarningsPerSale':
				$value = floatval($value);
				$value = number_format($value, 4, '.', '');
				return $value;

			case 'Commission':
			case 'Gravity':
			case 'InitialEarningsPerSale':
			case 'TotalRebillAmt':
			case 'Referred':
			case 'PercentPerSale':
			case 'PercentPerRebill':

				$value = floatval($value);
				$value = number_format($value, 2, '.', '');
				return $value;

			case 'HasRecurringProducts':
				return ($value == 'false') ? 0 : 1;
			case 'vin':
				return strtolower($value);
			case 'date_modified_type':
				return 'Feed';
			default:
				return $value;
				break;
		}
		return false;
	}


}

class CBP_importman {

	public static $api = null;

	// for upload form
		static function e($a, $key, $default = null) {
			if(is_object($a) && isset($a->$key)){
				return $a->$key;
			}
			if(is_array($a) && isset($a[$key])){
				return $a[$key];
			}
			return $default;
		}

		static function n($a, $key, $default = null) {
			return intval(self::e($a, $key, $default));
		}
		function check_upload($f) {
			if(!isset($_FILES[$f])){
				return "no_file";
			}
			$err = self::n($_FILES[$f], 'error');
			if($err !== 0){
				return "error_$err";
			}
			if(self::n($_FILES[$f], 'size') === 0){
				return "file_empty";
			}
			return "";
		}

		function begin_form($mm, $cmd, $upload = false) {
			$nn = self::create_nonce($cmd);
			$enctype = $upload ? 'enctype="multipart/form-data"' : '';
			return "<form action='" . admin_url('admin.php?page=cbpress-import&importmod=2') . "' method='post' $enctype>
			<input type='hidden' name='mm' value='$mm' />
			<input type='hidden' name='cmd' value='$cmd' />
			<input type='hidden' name='nn' value='$nn' />
			";
		}
		function end_form() {
			return "</form>";
		}
		function create_nonce($cmd) {
			return wp_create_nonce($cmd);
		}
		function upload_listener() {
			$err = self::check_upload('cbpf');
			if($err){
				return "$err";
			}
			$wpf = $_FILES['cbpf'];
			$moved = move_uploaded_file($wpf['tmp_name'], CBP_FEED_DIR . $wpf['name']);
			return $moved;
		}

	function upload_form() {






		// $test = CBP_import::getter();
		// dump($test);










		CBP::postbar_start('Optional form to upload the clickbank zip file');

		echo '<div class="cbpress_group">';
		echo '<div>';
		echo '<p class="right">Use this option to upload the zip file from ClickBank if you are having problems</p>';
		echo self::begin_form('updates', 'upload_update', true);


		echo '<p><input type="file" name="cbpf"></p>';
		echo '<p><input class="button" name="update" type="submit" value="Upload zip archive"></p>';





		echo self::end_form();
		echo '</div>';
		echo '</div>';

		CBP::postbar_end();
	}


	// end for upload form







	function catch_errors( $catch ) {
		static $display_errors, $error_reporting;
		if ( $catch ) {
			$display_errors = @ini_set( 'display_errors', 1 );
			$error_reporting = @error_reporting( E_ALL );
			add_action( 'shutdown', array( 'CBP_importman', 'catch_errors_on_shutdown' ), 0 );
		} else {
			@ini_set( 'display_errors', $display_errors );
			@error_reporting( $error_reporting );
			remove_action( 'shutdown', array( 'CBP_importman', 'catch_errors_on_shutdown' ), 1 );
		}
	}
	function php_debug() {
		$mem1 = (int) @ini_get('memory_limit');
		$mem2 = abs(intval(WP_MEMORY_LIMIT));
		$out = array(
			'mem1_php_limit' => $mem1,
			'mem2_wp_limit' => $mem2

		);
		print_r($out);
		phpinfo();
	}
	function catch_errors_on_shutdown() {
		$php_errors = ob_get_clean();
		print_r(get_defined_vars()); 
	}
	function setbusy() {
		$ai = self::getapi();
		$ai->busy_new();
	}
	function message($msg='') {
			echo '<h4>' . $msg . '</h4>';
	}


	function init() {

		self::upload_listener();

		// self::$api = new CBP_import(1);




		self::$api = CBP_import::getter();
		// dump($test);


	}
	function getapi() {



		return self::$api;
	}

	function zip_file_exists() {
		$ai = self::getapi();
		$s = $ai->getSourceFiles();
		$out = false;
		foreach ($s as $c) {
			if($c == 'marketplace_feed_v2.xml.zip'){
				$out = true;
			}
		}
		return $out;
	}

	function xml_file_exists() {

		$ai = self::getapi();
		$s = $ai->getSourceFiles();
		$out = false;
		foreach ($s as $c) {

			if($c == 'marketplace_feed_v2.xml'){

				$out = true;

			}
		}
		return $out;
	}

	function getSourceFiles() {
		$ai = self::getapi();
		$s = $ai->getSourceFiles();
		foreach ($s as $c) {
			echo $c . '<br>';
		}
	}


	function start() {

		$ai = self::getapi();

		self::message('Running Importer... please wait');
		self::setbusy();

		$id = $ai->manual_import_step1();

		if($ai->error_message == ''){
			self::message('Data feed found.');
			echo '<p><a href="' . CBP::make_action_url('process') . '&impid='.$id . '&importmod=2">Click to process results</a></p>';
		}else{
			self::message($ai->error_message);
			self::message('Please maunually upload the XML file');
			// upload_form();

		}
		return $id;
	}



	function process() {
		$ai = self::getapi();
		$impid = (isset($_GET['impid'])) ? $_GET['impid'] : 0;
		self::message('Processing Data... please wait');
		self::setbusy();
			$ai->manual_import_step2($impid);
			$ai->busy_done();
		self::message('Processing completed');
	}

	function import_run() {
		$ai = self::getapi();
		$ai->import_run();
	}





	/// echo '<p>Found ' . $api->how_many_nodes() . ' listings in xml file</a>';
}
