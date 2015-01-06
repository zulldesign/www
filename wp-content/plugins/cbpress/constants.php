<?php 
class CBP_constants  {

	## static 

	private static $instance = null; 
	function init($plugin_file='') {
		if (! isset(self::$instance)){
			
			self::$instance = new self($plugin_file);
		}
		
		
		return self::$instance;
	}

	function plugin_version(){

		static $plugin_data;
		if(!$plugin_data){
			require_once( ABSPATH . 'wp-admin/includes/plugin.php');
			$plugin_data = get_plugin_data( CBP_BASEFILE );
		}
		return "".$plugin_data['Version'];

	}

	## this


	public $data = array();

	public function __get($k){ return $this->data[$k]; }
	public function __set($k,$v){
		$key = strtoupper($k);
		if(!defined($k) && ! is_array($v)){  define($k, $v); }
		$this->data[$k]=$v;
	}
	public function render() { 		
		$ins = self::init()->data;
		dump($ins); 	
	}
	public function getdata() { return self::init()->data; }

	public function define_tables() { 
		global $wpdb;	
		$this->CBP_TABLES = 'prod,tree,cat,import,parse_prod,parse_tree,parse_user,list,list_item';
		$this->CBP_PREFIX = $wpdb->prefix . 'cbpress_';
		$arr = explode(',',CBP_TABLES);
		foreach($arr as $tbl){
			$t = CBP_PREFIX . $tbl;
			define('CBTB_' . strtoupper($tbl) , CBP_PREFIX . $tbl);
		}
		unset($arr);
	}

	public function __construct($plugin_file='') { 
		if($plugin_file == '') $plugin_file = CBP_BASEFILE;	
		$wud = wp_upload_dir();
		$this->CBPRESS_NAME = 'cbpress';
		$this->CBPRESS_TRANS = 'cbpress';
		$this->define_tables();

		$this->CBP_UPLOAD_DIR = $wud['basedir'] . '/' . CBPRESS_NAME;
		$this->CBP_UPLOAD_URL = WP_CONTENT_URL . '/uploads/' . CBPRESS_NAME;

		$this->CBP_FEED_DIR = CBP_UPLOAD_DIR . '/feeds/';
		$this->CBP_FEED_URL = $this->CBP_UPLOAD_URL . '/feeds/';	

		$this->CBP_VERSION = self::plugin_version();

		$this->CBP_USER_AGENT = CBPRESS_NAME;
		$this->CBP_PLUGINBASE = rtrim(dirname($plugin_file),'/');
	
		$this->CBP_BLOGURL 	= (!function_exists('get_site_url')) ? get_bloginfo('url') : get_site_url();
		$this->CBP_MIN_PHP = "5.2";
		$this->CBP_MIN_WP = "3.2";
		$this->CBP_HOOK_NONCE = 'cbpress-update-key';
		$this->CBP_HOOK_LID = 'cbpress-request-lid';
		$this->CBP_HOOK_REDIR = 'cbpress-before-redirect';
		$this->CBP_HOOK_MENU = 'cbpress-admin-menu';
		$this->CBP_HOOK_ADMIN = 'cbpress-admin-page';
		$this->CBP_HOOK_PRODINFO = 'cbpress-product-info';
		$this->CBP_BASE_URL = plugins_url('', $plugin_file).'/';
		$this->CBP_BASE_DIR = rtrim(plugin_dir_path($plugin_file),'/').'/';
		$this->CBP_JS_URL = CBP_BASE_URL . 'admin/js/';
		$this->CBP_JS_DIR = CBP_BASE_DIR . 'admin/js/';

		$this->CBP_FRONT_URL = $this->CBP_UPLOAD_URL . '/frontend/';
		$this->CBP_FRONT_DIR = $this->CBP_UPLOAD_DIR . '/frontend/';

		$this->CBP_FILES_URL = CBP_BASE_URL . 'files/';
		$this->CBP_FILES_DIR = CBP_BASE_DIR . 'files/';

		$this->CBP_IMG_URL = CBP_BASE_URL . 'admin/images/';
		$this->CBP_IMG_DIR = CBP_BASE_DIR . 'admin/images/';
		$this->CBP_CSS_URL = CBP_BASE_URL . 'admin/css/';
		$this->CBP_CSS_DIR = CBP_BASE_DIR . 'admin/css/';

		// $this->CBP_CSS_URL = WP_CONTENT_URL . '/uploads/' . CBPRESS_NAME . '/';
		// $this->CBP_CSS_DIR = $this->CBP_UPLOAD_DIR . '/';

		$this->CBP_ADMIN_URL = CBP_BASE_URL . 'admin/';
		$this->CBP_ADMIN_DIR = CBP_BASE_DIR . 'admin/';
		$this->CBP_VIEWS_URL = CBP_BASE_URL . 'admin/html/';
		$this->CBP_VIEWS_DIR = CBP_BASE_DIR . 'admin/html/';
		$this->CBP_LOGO_MD = '<a href="http://www.cbpress.com" style="border-bottom:1px solid #d7d7d7; display: block; padding: 10px 0px;"><img src="' . CBP_IMG_URL . 'cbpress_logo_md.png" border="0"></a><br/><br/>';
		$this->CBP_LOGO_SM = '<a href="http://www.cbpress.com" style="border-bottom:1px solid #d7d7d7; display: block; padding: 10px 0px;"><img src="' . CBP_IMG_URL . 'cbpress_logo_sm.png" border="0"></a><br/><br/>';
		$this->CBP_DEBUG = false;
		$this->CBP_OPT = 'cbpress';
		$this->CBP_REGDATA = 'cbpress_api';	
		$this->CBP_PLUGIN = plugin_basename($plugin_file);			
		unset($wud);			
	}

	public function class_loader($dir,$recurse=false){
		$dir = dirname(CBP_BASEFILE).'/' . $dir;
		if(is_dir($dir)){
			$types = array("php");
			$handle = opendir($dir);
			while(false !== ($resource = readdir($handle))) {
				if($resource!='.' && $resource!='..'){
					$extension = substr($resource, (strrpos($resource, ".") + 1));
					if (in_array($extension, $types)) {
						if(substr($resource,0,2) != '__'){
							if(is_dir($dir.$resource)){
								if($recurse) self::class_loader($dir.$resource.'/',$recurse);
							}else{
								include_once($dir.$resource);
							}
						}
					}
				}
			}
			closedir($handle);
		}
	}	
}
function __c__($string) {
	return __($string, CBPRESS_TRANS);
}

function _cbx($string) {
	return _e($string, CBPRESS_TRANS);
}