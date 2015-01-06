<?php
if (!defined('ABSPATH')) die();
###############################################################################
/**
 * Lists
 *
 * Custom Lists object and methods.
 */
###############################################################################


class CBP_lists {


	static public function get_list($list_id) {
		global $wpdb;	
		return $wpdb->get_row($wpdb->prepare("SELECT * FROM " . CBTB_LIST . " WHERE list_id = %d", $list_id), OBJECT);
	}


	static public function getitems($list_id=0,$sort='',$order='',$active=null) {
		global $wpdb;

		$order = strtoupper($order);
		if(strlen($sort)){
			$sort = "ORDER BY p.$sort $order";			
		} else {			
			$sort = "ORDER BY i.position";			
		}
		if($active !== null) $active = "AND p.active = " . intval($active);

		$l_table = CBTB_LIST;
		$i_table = CBTB_LIST_ITEM;
		$p_table = CBTB_PROD;

		$query = "SELECT p.lid,i.item_id,i.position,title,description,thumbnail,vin,slug,active,status,redirect_url,source
				FROM  {$l_table} l
				INNER JOIN {$i_table} i ON l.list_id = i.list_id
				INNER JOIN {$p_table} p ON i.lid = p.lid
				WHERE l.list_id = {$list_id}
				$active

				$sort";

		$result = $wpdb->get_results($query);
		// abort($result);
		return $result;
	}



	static public function getitemsforlist($args=array()) { // DEAD I THINK
		global $wpdb;

		$l_table = CBTB_LIST;
		$i_table = CBTB_LIST_ITEM;
		$p_table = CBTB_PROD;

		// list slug required    args.slug=

		$defaults = array('cols'=>'p.lid,title,description,vin,source,slug', 'slug'=>'default', 'sort'=>'', 'output'=>ARRAY_A);
		extract(wp_parse_args( $args, $defaults ));


		$acols = array();
		foreach (explode(',',$cols) as $c) $acols[] = 'p.'.$c;
		$cols = implode(',',$acols);


		if ($sort != '') $sort = " order by p.{$sort}";
		// abort('');
		$query = "SELECT {$cols} FROM  {$l_table} l INNER JOIN {$i_table} i ON l.list_id = i.list_id
				INNER JOIN {$p_table} p ON i.lid = p.lid WHERE l.list_slug = '{$slug}'{$sort}";

		// dump($query);

		$result = $wpdb->get_results($query);
		return $result;
	}

	static public function get_for_select() {
		$data  = array();
		$items = self::fetchall();
		foreach ( $items AS $item ) {
			$data[$item->list_id] = ''. $item->list_id . '. ' . cbpressfn::cb_esc_attr($item->list_name);
		}
		return $data;
	}

	static public function fetchall() {
		global $wpdb;
		return  $wpdb->get_results("SELECT * FROM " . CBTB_LIST . " ORDER BY list_name");
	}
	static public function getlists($output=ARRAY_A) {
		global $wpdb;
		return  $wpdb->get_results("SELECT * FROM " . CBTB_LIST . " ORDER BY list_slug", $output);
	}
	static public function getall(&$pager) {
		global $wpdb;
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . CBTB_LIST;
		$sql .= $pager->to_limits("");		
		$result = $wpdb->get_results($sql);

		// list_name,list_slug,list_enable,created
		$pager->set_total($wpdb->get_var("SELECT FOUND_ROWS()"));

		foreach($result as &$row){
			$row = new CBP_list( $row );
		}
		
		return $result;
	}

	static public function getListID($list_slug) {
		global $wpdb;


		$list_id = intval($wpdb->get_var("SELECT list_id FROM " . CBTB_LIST . " WHERE list_slug = '{$list_slug}'"));
		return $list_id;
	}


	static public function create($name,$slug='') {
		global $wpdb;
		$cols = 'list_name,list_slug,list_enable,created';
		$vals = '%s,%s,%d,%s';
		$date = cbpressfn::getMysqlDate('now');
		if (strlen($slug) == 0) {
				$slug = CBP::newslug($name, null, 'list_id', 'list_slug', CBTB_LIST);
		}
		$pars = array($name,$slug,1,$date);
		$prep = $wpdb->prepare("INSERT INTO " . CBTB_LIST . " ({$cols}) VALUES ({$vals}) ON DUPLICATE KEY UPDATE list_slug = VALUES(list_slug)", $pars);
		$wpdb->query($prep);

		// $fields = explode(',','name,label,type,info,std,options');
		// $out = array_combine( $fields , $args );

		return self::getListID($slug);
	}

	static function getListsForItem($lid){
		global $wpdb;
		return $wpdb->get_col($wpdb->prepare("SELECT DISTINCT list_id FROM " . CBTB_LIST_ITEM . " WHERE lid = %s",array(intval($lid))));
	}


	static function save_order($items,$list_id){
		global $wpdb;
		$tbl = CBTB_LIST_ITEM;
		foreach ( $items AS $pos => $id ) {
			$wpdb->update( $tbl, array( 'position' => $pos + $list_id ), array( 'item_id' => intval( $id ) ) );
		}
	}

	static function add_items($list_id,$lids){
		global $wpdb;
		$tbl = CBTB_LIST_ITEM;
		
		if (!is_array($lids)) $lids = explode(",",$lids);
		
		$date = cbpressfn::getMysqlDate('now');
		$query = "INSERT INTO $tbl (list_id,lid,position,created) VALUES (%d,%d,%d,%s) ON DUPLICATE KEY UPDATE lid = VALUES(lid)";

		$position = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT( * ) FROM $tbl WHERE list_id=%d", $list_id ) );

		for ($i = 0, $z = count($lids); $i < $z; $i++) {
			$value = $lids[$i];
			$found = CBP::recordexists("id=$value&key=lid&table=".CBTB_PROD);
			if($found){
				$position++;
				$wpdb->query($wpdb->prepare($query,array($list_id,$value,$position,$date)));
			}
		}
		
	}
	static function delete_list($list_id){
		global $wpdb;
		$wpdb->query("DELETE FROM " . CBTB_LIST_ITEM . " WHERE list_id = $list_id");
		$wpdb->query("DELETE FROM " . CBTB_LIST . " WHERE list_id = $list_id");
	}
	static function delete_item($list_id,$lid){
		global $wpdb;
		$wpdb->query("DELETE FROM " . CBTB_LIST_ITEM . " WHERE lid=$lid AND list_id=$list_id");
	}


	/*****************************************
	**       - Ajax Calls
	*****************************************/


	public function ajax_new_list() {

		$hopfield = 'cb-product-hop';

		// check_ajax_referer('cbpress-new-list', 'security');
		$_REQUEST = array_map('stripslashes_deep', $_REQUEST);
		$name = trim($_REQUEST['name']);

		if (preg_match('/[^a-zA-Z0-9_ -]/', $name) > 0) {
			cbpressfn::ajax_error('Please only use these characters: letters, numbers, space, dash and underscore.');
		};
		if (strlen($name) < 1){
			cbpressfn::ajax_error('Please be a little more specific with your LIST name.');
		}

		// echo cbpressfn::create_slug($name);
		// abort($_REQUEST);


		$listid = self::create($name); // new list
		if(isset($_REQUEST[hopfield])) {
			$hop = trim($_REQUEST[hopfield]);
			if (strlen($hop) > 1) {
				self::add_items($listid,$hop); // add items
			}
		}

		echo "({'success':true, 'id':".$listid.", 'name':'$name'})";


		/*********
		if (isset($new_category_id['term_id'])) {
			echo "({'success':true, 'id':".$new_list_id.", 'name':'$list_slug'})";
		} else {
			$error_string = "";
			foreach ($new_category_id->get_error_messages() as $error) {
				$error_string = $error . "  ";
			}
			echo "({'success':false, 'error':'".$error_string."'})";
		}
		*******/

		die();
	}

}




class CBP_list {

	var $tbl = CBTB_LIST;
	var $items 	= array();
	var $msg 	= '';
	var $err 	= false;
	
	private $db;
	
	var $defaults = array(	
		'list_id' => 0 ,
		'list_name' => null ,
		'list_slug' => null ,
		'list_enable' => null 
	);
	
	private $data = array(	
		'list_id' => 0 ,
		'list_name' => '' ,
		'list_slug' => '' , 
		'list_enable' => 1 
	);
	//  CBP_list::load($list_id);
	
	static function load( $id ) {
		return new CBP_list( CBP_query::get_list( $id ) );
	}
	



	public function form($form=true) {

			$formend = '';
			$formstart = '';
			$form = (isset($form)) ? $form : 1;
			if($form){	
					$act = admin_url( 'admin.php' );
					$formstart = '<form method="post" accept-charset="utf-8" action="'.$act.'" class="cbpress-form" style="margin: 0px;">';			
					$formend = '<input type="hidden" name="action" value="cbp-save-list" />';
					$formend .= '<input class="button-primary" type="submit" name="savelist" value="Save"/>';
					$formend .= '</form>';
			}

			// $boxtitle = ($this->list_id > 0)? 'Edit List' : 'Create List';
			// CBP::postbox_start($boxtitle, 'line-height:16px;'); 
		?>
		<?php echo $formstart; ?>
		<div id="list_form">
			<?php CBP::wp_nonce_field(CBP_HOOK_NONCE) ?>
			<input type="hidden" name="action" value="cbp-save-list" />
			<input type="hidden" name="listform[list_id]" value="<?php echo $this->list_id; ?>"/>
			<div><label>List Name:</label></div>
			<p><input style="width: 96%;" type="text" name="listform[list_name]" value="<?php echo esc_attr( $this->list_name ); ?>"/></p>
			<?php if($this->list_id > 0){ ?>
				<input type="hidden" name="listform[list_enable]" value="0"/>
				enabled <input name="listform[list_enable]" type="checkbox" value="1" <?php echo $this->list_enable==1?"checked=\"checked\"":'';?> /> 
			<?php }else{ ?>
				<input type="hidden" name="listform[list_enable]" value="1"/>
			<?php } ?>
		</div>
		<?php echo $formend; ?>
		
				
		<?php
		// CBP::postbox_end();
	}

	public function __get($k){
		
		return ($k == 'list_id') ? intval($this->data[$k]) : ((isset($this->data[$k])) ? $this->data[$k] : null);
	}
	public function __set($k,$v){
		$this->data[$k] = $v;
	}
	
	public function __construct( $values = '' )	{
		
		
		global $wpdb;
		$this->db = &$wpdb;
		
		if ( is_object( $values ) || is_array( $values ) ) {
			foreach ( $values AS $key => $value ) {
				if(is_string($value)) $value = stripslashes($value);
			 	$this->$key = $value;
			}
		}
	}


	public function __toString(){
		
		// The string we return is outputted by the echo statement
		
		return '<li id="todo-'.$this->data['list_id'].'" class="todo">
		<div class="text">
				<span style="float:right;" class="actions">
					<a href="#" class="edit">Edit</a>
					<a href="#" class="delete">Delete</a>
				</span><a href="#" class="edit">
				'.$this->data['list_name'].'
				
				</a></div>
			</li>';
	}
	
	
	
	
	function dump() {
			dump($this->getdata());
	}
	function getdata() {
			return $this->data;
	}


	function getitemcount() {
		$id = $this->list_id;
		$found = CBP::getrecordcount("id=$id&key=list_id&table=".CBTB_LIST_ITEM);
		return $found;
	}
	function getitems() {		
		$this->items = CBP_lists::getitems($this->list_id,$sort,$order,$active=1);
		return $this->items;
	}
	
	function nameTaken() {
		$name = $this->list_name;
		$name = stripslashes($name);
		$name = mysql_real_escape_string($name,$this->db->dbh);
		$query = "SELECT COUNT(*) FROM {$this->tbl} WHERE list_name = '{$name}'";
		$query .= " AND list_id <> {$this->list_id}";
		
		// dump($query);
		// $query = $this->db->get_var($query);
		
		// abort($query);
		
		return $this->db->get_var($query);
	}


	function delete(){
		if($this->list_id > 0){
			$this->db->query("DELETE FROM " . CBTB_LIST_ITEM . " WHERE list_id = {$this->list_id}");
			$this->db->query("DELETE FROM {$this->tbl} WHERE list_id = {$this->list_id}");
		}
	}
	
	
	
	function _torow() { // prepare for sql insert
		$r = array();
		foreach($this->defaults as $c => $v){
			if(isset($this->data[$c])) $v = $this->data[$c];


			if($v !== null){
				$v = esc_attr($v);
				$v = stripslashes($v);
				// $v = mysql_real_escape_string($v,$this->db->dbh);
				$r[$c]= $v;
			}
		}
		unset($a);
		return $r;
	}

	function save() {

		if($this->has_errors()) return false;	

		$rowdata = $this->data;
		
		if($this->list_id > 0){
			$this->db->update( $this->tbl, $rowdata, array( 'list_id' => $this->list_id ) );
		}else{
			$rowdata = $this->_torow();
			if(isset($rowdata['list_id'])) unset($rowdata['list_id']);
			$rowdata['created'] = gmdate("Y-m-d H:i:s");
			
			$rowdata['list_slug'] = $this->newslug();
			$this->db->insert( $this->tbl, $rowdata );
			$this->list_id = (int) $this->db->insert_id;
		}
		return $this->list_id;
	}

	function has_errors()	{
		$this->msg = ''; // reset
		if (CBP::len($this->list_name) < 1) {
			$this->msg = 'A list must have a name of at least 1 alphanumeric character.';
		} else if ( CBP::len($this->list_name) > 100) {
			$this->msg = 'A list\'s name cannot be more then 100 characters long.';
		} else if ( $this->nameTaken() ){
			$this->msg = 'Please try a different name. A custom list already exists with that name.';
		}

		if(CBP::len($this->msg) > 1){ 
			$this->err = 1;
		}else{
			$this->msg = 'List Saved';
		}
		return $this->err;
		// abort($this->nameTaken());
	}

	function newslug() {
		$this->list_slug = CBP::newslug($this->list_name, $this->list_id, 'list_id', 'list_slug', $this->tbl);
		return $this->list_slug;
	}	

	function update( $row ) {
		if($this->list_id > 0){
			$this->list_enable = isset( $row['list_enable'] ) ? '1' : '0';
			$this->list_name   = isset( $row['list_name'] ) ? $row['list_name'] : $this->list_name;
			$this->db->update( $this->tbl , array( 'list_name' => $this->list_name, 'list_enable' => $this->list_enable ), array( 'list_id' => $this->list_id ) );
		}
	}

}