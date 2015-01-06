<?php
if (!defined('ABSPATH')) die();

class CBP_install {

	/**

	* Defines static global table names and paths to upload folders for use in plugin

	*/

	function init() {
		// abort(CBP_UPLOAD_DIR);

	}


	function deactivate_plugin() {

			$current = get_option('active_plugins');

			if(in_array(CBP_PLUGIN, $current)){

			array_splice($current, array_search(CBP_PLUGIN, $current), 1);
			update_option('active_plugins', $current);

			}

	}

	function reinstall() {

		self::delete_tables();
		self::delete_folders();
		self::delete_options();			

		self::create_tables();
		self::create_folders();
		self::create_options();

	}

	function install() {

		

		// self::init();

		self::create_tables();
		self::create_folders();
		self::check_version();

	}



	function uninstall() { 

		self::delete_tables();

		self::delete_folders();

		self::delete_options();			

		self::deactivate_plugin();

		

		wp_cache_flush(); 

	}



	function upgrade($oldver) { 



		global $wpdb;

		if ( $oldver < '0.6' ){

			$wpdb->suppress_errors();

			$tbl = CBTB_LIST_ITEM;

			$wpdb->query( "ALTER TABLE `$tbl` ADD `position` INT(11) UNSIGNED NOT NULL DEFAULT '0'" );

			$wpdb->suppress_errors( false );

		}

	}

	





	function check_version() {



		$new = CBP_VERSION;		

		if ($curr = self::getCurrentVersion()) {		

			if (version_compare($new, $curr) == 1){

				self::upgrade($curr);

			}

		}

		if ($curr != $new) self::set_version($new);

	}



	function getCurrentVersion() {

		return get_option('cbpress_version', false);

	}



	function set_version($ver) {

		delete_option('cbpress_version');

		add_option('cbpress_version', $ver);

	}



	

	

	

	

	function table_exists(){

		global $wpdb;

		$table_name = CBTB_PROD;

		$wpdb->suppress_errors();

		$installed = (boolean) $wpdb->get_results( 'DESCRIBE `' . $table_name . '`;', ARRAY_A );

		$wpdb->suppress_errors( false );

		return $installed;

	}





	function has_imported() { 

		global $wpdb;

		$table_name = CBTB_PROD;

		$wpdb->suppress_errors();

		

		$num = 0; 

		$msg = '';			

		if ( false === ($num = $wpdb->get_var("SELECT COUNT('import_id') FROM " . CBTB_IMPORT) )) {

			$msg = "" . $this->db->last_error;

		}

		if($msg !== '' && intval($num) > 0) $msg = 'not imported';		

		$wpdb->suppress_errors( false );

		abort(get_defined_vars());

		return $msg;

	}





	static function tslash($path) {

		return self::untslash($path).'/';

	}

	

	static function untslash($path) {

		return rtrim($path, '/');

	}



	static function delete_folder($dir,$recurse=true) {

		$dir = self::tslash($dir);

		if(is_dir($dir)){			

			$handle = opendir($dir);

			while(false !== ($resource = readdir($handle))) {

				if($resource!='.' && $resource!='..'){

					if(is_dir($dir.$resource)){					

						if($recurse) self::delete_folder($dir.$resource.'/',$recurse);

					}else{

						unlink($dir.$resource);

					}

				}

			}

			closedir($handle);

		}

	}

	

	

	function rmdirr($dirname){

		// Sanity check

		if (!file_exists($dirname)) {

			return false;

		}



		// Simple delete for a file

		if (is_file($dirname)) {

			return unlink($dirname);

		}



		// Loop through the folder



		$dir = dir($dirname);

		while (false !== $entry = $dir->read()) {



			// Skip pointers

			if ($entry == '.' || $entry == '..') {

				continue;

			}



			// Recurse

			self::rmdirr("$dirname/$entry" );

		}



		// Clean up

		$dir->close();

		return rmdir($dirname);

	}



	function delete_folders() { 

		$dir = self::tslash(CBP_UPLOAD_DIR);
		self::rmdirr($dir);	
	}	

	function delete_options() { 

		global $wpdb;		

		$opt_tbl = $wpdb->prefix."options";		

		$r = $wpdb->query("DELETE FROM $opt_tbl WHERE option_name = 'cbpress'");
		$r = $wpdb->query("DELETE FROM $opt_tbl WHERE option_name LIKE 'cbpress%'");
		$r = $wpdb->query("DELETE FROM $opt_tbl WHERE option_name LIKE '%_cbpress_%'"); // delete widget info

		// delete post meta keys

		$r = $wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key = '_cbpress_loaded'");

	}	

					



	

	function delete_tables() { 

		global $wpdb;

		$tables = explode(',',CBP_TABLES);

		foreach($tables as $table){

			$t = CBP_PREFIX . $table;

			if ($wpdb->get_var("SHOW TABLES LIKE '$t'") == $t) {

				$drop = "DROP TABLE $t";

				$wpdb->query($drop);

			}

		}

	}







	function insert_page($title,$slug,$content,$status='publish',$author=false){

		global $user_ID;

		get_currentuserinfo();

		$user=$author?$author:$user_ID;

		$defaults = array(

			'post_title' => $title,
			'post_name'=>$slug,
			'post_content' => $content,
			'post_status' => $status,
			'post_type' => 'page',
			'post_author' => $user,
			'comment_status'=>'closed',
			'import_id' => 0);

		$pageid = wp_insert_post($defaults);

		wp_cache_delete('all_page_ids', 'pages');

		return $pageid;

	}





		

	function resetdb() {
		self::delete_tables();
		self::create_tables();
	}



	function reset_folders() {
		self::delete_folders();
		self::create_folders();
	}



	function create_options() {
	}





	function chmod777($d) {
		$stat = @stat(dirname($d));
		$dir_perms = $stat['mode'] & 0007777;
		@chmod( $d, $dir_perms );
	}


	public static function unzipper($sourcezip,$destination) {

		$zip = new ZipArchive;
		if(@$zip->open($sourcezip) === true){
			$zip->extractTo($destination);
			$zip->close();
			return true;
		}
		return false;
	}



	function moveFrontEnd($src, $dst) {

		$files = scandir($src);
		foreach ( $files as $file ){
			if ($file != "." && $file != ".."){
				copy ( "$src/$file", "$dst/$file" );
			}
		}
	}




	function create_folders() {





		if(!is_dir(CBP_UPLOAD_DIR)){ @ wp_mkdir_p(CBP_UPLOAD_DIR, 0775); }
		if(!is_dir(CBP_FEED_DIR)){ @ wp_mkdir_p(CBP_FEED_DIR, 0775); }
		if(!is_dir(CBP_FRONT_DIR)){ @ wp_mkdir_p(CBP_FRONT_DIR, 0775); }


			// if(file_exists($new.'\frontend-template.css')){ }


			// move frontend files to uploads

			$old = CBP_FILES_DIR . "frontend";
			$new = CBP_FRONT_DIR . "";

			self::moveFrontEnd($old, $new);



			// $wud = wp_upload_dir();
			// $unzipped = self::unzipper(CBP_FILES_DIR.'uploads_cbpress.zip', $wud['basedir']);

		self::chmod777(CBP_UPLOAD_DIR);
		self::chmod777(CBP_FEED_DIR);
		self::chmod777(CBP_FRONT_DIR);
	}

	

	function create_tables() {



		global $wpdb;



		$sql = array();

		$collate = "";



		if($wpdb->supports_collation()) {

			if(!empty($wpdb->charset)) $collate = "DEFAULT CHARACTER SET $wpdb->charset";

			if(!empty($wpdb->collate)) $collate .= " COLLATE $wpdb->collate";

		}



		$sql[CBTB_IMPORT] = "
			import_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			ts_start DATETIME NOT NULL,
			ts_end DATETIME NOT NULL,
			status VARCHAR(40),
			log TEXT NULL,
			stats TEXT NULL,
			type VARCHAR(50),
			PRIMARY KEY  (import_id)
			";



		$sql[CBTB_LIST] = "
			list_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			list_name VARCHAR(255) NULL DEFAULT NULL,
			list_slug VARCHAR(255) NULL DEFAULT NULL,
			list_enable TINYINT(1) NULL DEFAULT '0',
			created DATETIME NULL DEFAULT NULL,
			modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (list_id),
			INDEX slug_idx (list_slug)
			";



			

		$sql[CBTB_LIST_ITEM] = "
			item_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			lid MEDIUMINT UNSIGNED NULL DEFAULT '0',
			list_id INT(10) UNSIGNED NULL DEFAULT NULL,
			position INT(11) UNSIGNED NOT NULL DEFAULT '0',
			created DATETIME NULL DEFAULT NULL,
			modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (item_id),
			UNIQUE INDEX idx_lookup(list_id, lid),
			INDEX idx_list_id (list_id)
			";



		$sql[CBTB_CAT] = "
			cid MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
			pid MEDIUMINT UNSIGNED NOT NULL,
			removed TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			type enum('clickbank','custom','system') DEFAULT 'clickbank',
			name VARCHAR(100) NOT NULL,
			slug TEXT NULL DEFAULT NULL,
			full TEXT NULL DEFAULT NULL,
			xpath TEXT NULL DEFAULT NULL,
			enabled TINYINT(1) UNSIGNED NOT NULL,
			depth TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			PRIMARY KEY  (cid),
			INDEX idx_name(name(10)),
			INDEX idx_pid(pid)			
			";



		$sql[CBTB_TREE] = "
			join_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			cid MEDIUMINT UNSIGNED NOT NULL,
			lid MEDIUMINT UNSIGNED NOT NULL,
			rank INT UNSIGNED NOT NULL DEFAULT '0',
			join_custom TINYINT(1) NOT NULL DEFAULT '0',
			join_enable TINYINT(1) NOT NULL DEFAULT '1',
			PRIMARY KEY  (join_id),
			UNIQUE INDEX idx_lookup(cid, lid),
			INDEX idx_rank(rank),
			INDEX idx_lid(lid),
			INDEX idx_cid(cid)
			";



		$sql[CBTB_PARSE_TREE] = "
			temp_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			cid MEDIUMINT UNSIGNED NOT NULL,
			lid MEDIUMINT UNSIGNED NOT NULL,
			rank INT UNSIGNED NOT NULL DEFAULT '0',
			join_custom TINYINT(1) NOT NULL DEFAULT '0',
			join_enable TINYINT(1) NOT NULL DEFAULT '1',
			vin VARCHAR(30) NOT NULL,
			PRIMARY KEY  (temp_id),
			UNIQUE INDEX idx_lookup(cid, vin),
			INDEX idx_rank(rank),
			INDEX idx_vin(vin),
			INDEX idx_cid(cid)
			";



		$sql[CBTB_PARSE_USER] = "
			lid MEDIUMINT UNSIGNED NOT NULL,
			cid MEDIUMINT UNSIGNED NOT NULL,
			rank INT UNSIGNED NOT NULL DEFAULT '0',
			join_custom TINYINT(1) NOT NULL DEFAULT '0',
			join_enable TINYINT(1) NOT NULL DEFAULT '1'
			";



		$sql[CBTB_PARSE_PROD] = "
			parseid MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
			lid MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
			vin VARCHAR(30) NULL,
			title VARCHAR(255) NULL,
			description TEXT NULL,
			slug VARCHAR(255) NULL DEFAULT NULL,
			ActivateDate VARCHAR(10) NULL,
			Commission DECIMAL(5,2) NULL,
			HasRecurringProducts TINYINT(1) NOT NULL DEFAULT '0',
			Gravity DECIMAL(6,2) NULL,
			InitialEarningsPerSale DECIMAL(8,2) NULL,
			AverageEarningsPerSale DECIMAL(8,2) NULL,
			TotalRebillAmt DECIMAL(6,2) NULL,
			Referred DECIMAL(5,2) NULL,
			PercentPerRebill DECIMAL(5,2) NULL,
			PercentPerSale DECIMAL(5,2) NULL,
			PRIMARY KEY  (parseid),
			INDEX idx_vin(vin)
			";



		$sql[CBTB_PROD] = "
			lid MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
			status ENUM('active', 'removed') DEFAULT 'active',
			active TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
			source ENUM('clickbank','custom') DEFAULT 'clickbank',
			vin VARCHAR(30) NULL,
			title VARCHAR(255) NULL,
			description TEXT NULL,
			clicks_raw int(11) NOT NULL default '0',
			clicks_unique int(11) NOT NULL default '0',
			feed_title VARCHAR(255) NULL,
			feed_desc TEXT NULL,
			link_tid VARCHAR(255) NULL,
			rating INT NULL DEFAULT '0',
			feature INT NULL DEFAULT '0',
			ActivateDate VARCHAR(10) NULL,
			PopularityRank DECIMAL(5,1) UNSIGNED NOT NULL DEFAULT '0',
			Commission DECIMAL(5,2) NULL,
			HasRecurringProducts TINYINT(1) NOT NULL DEFAULT '0',
			Gravity DECIMAL(6,2) NULL,
			InitialEarningsPerSale DECIMAL(8,2) NULL,
			AverageEarningsPerSale DECIMAL(8,2) NULL,
			TotalRebillAmt DECIMAL(6,2) NULL,
			Referred DECIMAL(5,2) NULL,
			PercentPerSale DECIMAL(5,2) NULL,
			PercentPerRebill DECIMAL(5,2) NULL,
			auto_update TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
			created DATETIME NULL DEFAULT NULL,
			modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			date_status DATETIME NULL DEFAULT NULL,
			redirect_url VARCHAR(255) NULL,
			slug VARCHAR(255) NULL DEFAULT NULL,
			thumbnail VARCHAR(100) NULL,
			landing_page MEDIUMTEXT NULL,
			PRIMARY KEY  (lid)
			";

			

			

		$use_delta = true;

		$use_delta = false;



		if($use_delta){

			$queries = array();

			foreach ($sql as $table => $q) {

					$queries[] = "CREATE TABLE $table ( $q ){$collate};";

			}

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

			dbDelta($queries);			

		}else{		

			foreach ($sql as $table => $q) {

				$q = "CREATE TABLE IF NOT EXISTS $table ( $q ){$collate};";

				$wpdb->query($q);

			}	

		}

		// abort('');

		return true;		

	}

}