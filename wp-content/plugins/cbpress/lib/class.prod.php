<?php
if (!defined('ABSPATH')) die();


class CBP_prod  {


	static $tbl = CBTB_PROD;
	var $msg, $page 	= '';
	var $err 	= false;
	private $db;
	private $_def = array();
	private $_action_links = null;
	protected $options;

	private $used = array();

	private $_cols = array(
		  'lid' => 0
		, 'source' => 'custom'
		, 'active' => 1
		, 'auto_update' => 1
		, 'title' => ''
		, 'description' => ''
		, 'slug' => ''
		, 'link_tid' => ''
		, 'landing_page' => ''
		, 'thumbnail' => ''
		, 'rating' => '0'
		, 'redirect_url' => ''
		, 'vin' => null
		, 'date_status' => null
		, 'created' => null
		, 'status' => 'active'
		, 'feed_title' => null
		, 'feed_desc' => null
		, 'ActivateDate' => null
		, 'Commission' => null
		, 'Gravity' => null
		, 'HasRecurringProducts' => null
		, 'InitialEarningsPerSale' => null
		, 'AverageEarningsPerSale' => null
		, 'TotalRebillAmt' => null
		, 'Referred' => null
		, 'PercentPerRebill' => null
		, 'PercentPerSale' => null
		, 'PopularityRank' => null

	);


	public $data = array();




	static function addcat_($lid,$cid) {
		$com = self::create($lid);
		$com->addcat($cid);
		unset($com);
	}


	static function create( $lid ) {
		$params = array('lid'=>$lid);
		return new CBP_prod($params);
	}


	static function load( $lid, $cols="" ) {
		global $wpdb;
		$tbl = CBTB_PROD;


		if($cols == '') $cols = '*';

		$cols .= ", CASE WHEN LOWER(description) = LOWER(feed_desc) THEN 0 ELSE 1 END AS dc";
		$cols .= ", CASE WHEN LOWER(title) = LOWER(feed_title) THEN 0 ELSE 1 END AS tc";


		$res = $wpdb->get_row("SELECT $cols FROM $tbl WHERE lid = " . intval($lid), ARRAY_A);


		return new CBP_prod( $res );
	}









	public function __construct($params='')	{





		global $wpdb, $cbpress;

		$this->db = &$wpdb;
		$this->options = &$cbpress->options;



		$this->page = CBP::current_page();











		$params = empty($params) ? array() : $params;
		$params = (array) $params;

		$this->data = $params;



		foreach($this->_cols as $k => $d) {
			if(($d === null) == false){
				$this->_def[$k] = $d;
			};
			if(isset($params[$k])) $this->$k = $params[$k];
		}



		$this->lid = intval($this->lid);
		if(! $this->lid) $this->lid = '0';

		return $this;


	}




	function filter_info($c,$v='') {


		switch ($c) {
			case 'AverageEarningsPerSale':
				$v = floatval($v);
				break;
			case 'Referred':
			case 'PercentPerRebill':
			case 'PercentPerSale':
			case 'Commission':
				return $v . '%';
				break;
			case 'Gravity':
			case 'InitialEarningsPerSale':
			case 'TotalRebillAmt':
				break;
			case 'active':
			case 'HasRecurringProducts':
				return ($v == '0') ? 'No' : 'Yes';
				break;
			case 'clicks_raw':
			case 'clicks_unique':
				return intval($v);
				break;
			case 'created':
			case 'modified':
			case 'date_status':
				if(CBP::isdate($v)){
					$oDate = new DateTime($v);
					$dtf = 'n/j/Y - g:i: s A';
					$v = $oDate->format($dtf);
					unset($dtf,$oDate);
				}else{

					$v = '-';
				}
				return $v;
				break;
			case 'vin':
				return strtoupper($v);
				break;
			default:
				break;
		}
		return $v;

	}
	function filter_list($c,$v='') {

		switch ($c) {
			case 'AverageEarningsPerSale':
				$v = floatval($v);
				break;
			case 'Referred':
			case 'PercentPerRebill':
			case 'PercentPerSale':
				$v = $v . '%';
				break;

			case 'Commission':



				if($v > 0) $v = number_format($v,0);
				$v = ($v > 0) ? $v . '%' : '-';
				break;


			case 'rank':
				$v = ($v == 0) ? '' : $v;




			case 'Gravity':
			case 'InitialEarningsPerSale':
			case 'TotalRebillAmt':

				$v = ($v == 0) ? '-' : $v;

				break;
			case 'active':
			case 'HasRecurringProducts':
				$v = ($v == '0') ? 'No' : 'Yes';
				break;
			case 'clicks_raw':
			case 'clicks_unique':
				$v = intval($v);
				break;

			case 'date_status':
				if(CBP::isdate($v)){
					$v = cbpressfn::days_since($v);
				}else{

					$v = '-';
				}
				break;

			case 'ActivateDate':
				if(CBP::isdate($v)){
					$v = cbpressfn::days_since($v);


				}
				break;
			case 'created':
			case 'modified':
				if(CBP::isdate($v)){



					$v = cbpressfn::days_since($v);
				}
				break;
			case 'status':
				if($v != ''){
					$v = ($v == 'active') ? 'Active' : 'Removed';
				} else {
					$v = '-';
				}
				break;
			case 'vin':
				$v = ($v == '') ? '-' : $v;
				break;
			default:
				break;
		}
		return $v;
	}
	function filter($c,$v='') {
		switch ($this->page) {
			case 'products':
				return $this->filter_list($c,$v);
				break;
			default:
				return $this->filter_info($c,$v);
				break;
		}
		return $v;
	}






	public function redirect($tid='') {





			// if($this->link_tid !== ''){
			// $tid = $this->link_tid;
			// }


		$link = $this->redirect_getlink($tid);





		if(strpos($link, 'http') !== false){
			do_action(CBP_HOOK_REDIR);
			$this->redirect_log();
			wp_redirect($link);
		}else{
			$this->redirect_notify();
		}
		exit();
		die;
	}



	public function linkto(){
		$key = $this->options->link_cloaker;

		$out = stripslashes(CBP_BLOGURL).'/?'.$key.'='.$this->lid;
		return $out;
	}



	public function output($showdesc=true,$last=false,$textlimit=''){


		$last = ($last) ? ' last' : '';



		$lb = "\n";

		$attr = (object) array();
		$attr->href = $this->linkto();
		$attr->class = 'item-link';
		if (intval($this->options->link_nofollow)) $attr->rel = 'nofollow';
		if (intval($this->options->link_newwindow)) $attr->target = '_blank';

		$temp = (object) array();
		$temp->desc  = $this->description;
		$temp->desc  = $this->format_desc($temp->desc,$attr->href,$textlimit);
		$temp->title = CBP::esc_attr($this->title);
		$temp->href = $attr->href;




		// build thumbnail 

		$temp_img = '';
		if($this->thumbnail != '') {
			$temp_img = '<img src="' . esc_attr($this->thumbnail) . '" border="0" vspace="5" width="96" title="' . $this->title . '">';
		}






		// $temp->onclick = 'javascript:pageTracker._trackPageview(' . "'" . '/outgoing/marketplace/' . $this->vin . "');";





		// $temp->head = '';

		if($temp_img != ''){


			$temp_img = CBP::quicktag('a',$attr, $temp_img);


			$temp_img = '<div style="float:left; padding: 0 10px 0 0;">' . $temp_img . '</div>';


		}





		$temp->head .= $temp_img . CBP::quicktag('a',$attr, $this->title);

			$attr->class = 'link';
			$temp->link = CBP::quicktag('a',$attr, $this->title);


		$temp->head = CBP::quicktag('div',array('class'=>'title'), $temp->head);
		$temp->text = "<div class=\"text\">$lb{$temp->desc}$lb</div>";


		$temp->listing = $lb . "<div class=\"item$last\">" . $lb . $temp->head . $lb . $temp->text  . $lb . "</div>";



		$this->formats = $temp;






		// dump($this);





		$temp->head = '<li>'.$temp_img.'<a href="'.$temp->href.'" title="'.$this->title.'">'.$this->title.'</a></li>';









		$temp = ($showdesc) ? $temp->listing : $temp->head;

			// $temp .= '<div class="right">'.$temp_img . '</div>';







		// $temp .= $this->thumbnail;
		// $temp->img = $this->thumbnail;



		unset($attr);

		return $temp;
	}






	function get_word_filter_array() {
			$excludes = $this->options->desc_filter_words;



			$excludes = trim(implode(',', array_map('trim', explode(',',$excludes))));
			$excludes = (array) explode(',',$excludes);
			return $excludes;
	}
	function format_desc($d, $replacelink, $textlimit){



		$desc_limit = ($textlimit > 0) ? $textlimit : $this->options->desc_limit;
		$desc_spacer = $this->options->desc_spacer;
		$desc_links = $this->options->desc_links;
		$desc_filter = $this->options->desc_filter;
		$desc_filter_words = $this->options->desc_filter_words;


		if($this->dc == 1) $desc_filter = 0;



		if($desc_filter && 1 == 1){



			$d = ' '.html_entity_decode($d).' ';
			$d = preg_replace('#(?:(?:f|ht)tp://)?(?:[a-z\d][-a-z\d]*\.){1,}[a-z]{2,}[^\s?!,;:]*(?<!\.)#i', '', $d);
			$d = trim(preg_replace("#(<a( [^>]+|>))<a [^>]+([^>]+?)</a></a>#i", "$1$3", $d));
			$d = trim(str_replace( array ( "'>http://", ">http://", ">www." ), array ( "'>", ">", ">"), $d));







			$new = array();
			$chunks = $d;
			$chunks = str_replace(' and', '. and', $chunks);
			$chunks = str_replace(',', '.', $chunks);
			$chunks = str_replace('!', '.', $chunks);
			$chunks = explode('.',$chunks);
			$words = $this->get_word_filter_array();

			foreach($chunks as $sentence) {
				$stop = 0;
				foreach($words as $word) {
					$pos = strpos(strtolower($sentence), strtolower($word));

					if ($pos !== false) { $stop = 1; }
				}
				if(! $stop){
					$new[] = $sentence;
				}
			}
			$new = implode('.',$new);
			$d = $new;
			if(trim($d) == '' || strlen(trim($d)) < 3){
				$d = $this->title;
			}




		}

		if($desc_limit > 0 && strlen($d) > $desc_limit){
				if(false !== ($breakpoint = strpos($d, ' ', $desc_limit))) {
					if($breakpoint < strlen($d) - 1) $d = substr($d, 0, $breakpoint) . '...';
				}
				unset($breakpoint);
		}





		if($desc_spacer > 0) {
				$d = preg_replace('/([^\s<>]{'.$desc_spacer.'})(?![^<>]*>)/x','\1 ', $d);
		}
		unset($desc_limit,$desc_spacer,$desc_links,$desc_filter_words);
		return $d;

	}



	public function redirect_getlink($tid='') {


						// echo $tid;




		if(! $this->is_clickbank()){
			$out = $this->redirect_url;
		}else{
			$vin = $this->vin;



			$tid = trim($tid);

			/********* 

			$tid = ($tid == '') ? trim($this->link_tid) : $tid;
			$tid = (CBP::len($tid) < 1) ? trim($this->options->link_tid) : $tid;

			******************************/


			$aff = CBP_api::get('affiliate');
			if($aff == ''){ $aff = 'cbpress'; }

			$qs = array();
			if (strlen($tid)){
				$tid = urlencode(preg_replace("/[^a-zA-Z0-9]/", '', $tid));
				if (strlen($tid) > 24) $tid = substr($tid, 0, 23);
				$qs[] = "tid=$tid";
			}

			$qs = trim(implode('&',$qs));
			if(strlen($qs)) $qs = "?$qs";
			$out = "http://$aff.$vin.hop.clickbank.net/$qs";
		}
		unset($vin,$tid,$aff,$qs);
		return $out;
	}
	public function redirect_notify() {
			$back = '<a href="'.wp_get_referer().'" class="link">Click here to return to last page</a>';
			wp_die('<h1>' . $this->title . '</h1><h2>This product contains an invalid URL.</h2>' . $back, 'An error has occurred');
	}

	public function redirect_log() {
			$lid = $this->lid;
			$cookie_name = 'cbpress_'.$lid;
			$sql = "UPDATE " . CBTB_PROD . " SET clicks_raw = clicks_raw + 1";
			if ( !isset($_COOKIE[$cookie_name]) ) $sql .= ", clicks_unique = clicks_unique + 1";
			$sql .= " WHERE lid='$lid'";
			$this->db->query($sql);
			$url = parse_url(CBP_BLOGURL);
			setcookie($cookie_name, '1', time()+(3600*24)*30, $url['path'].'/');
	}


	function is_clickbank(){
		return ($this->source == 'clickbank') ? 1 : 0;
	}



	function wp_post_init() {
			add_filter('default_title', array(&$this,'topost_getTitle') );
			add_filter('default_excerpt', array(&$this,'topost_getDescription') );
			add_filter('default_content', array(&$this,'topost_getContent') );
	}
	function topost_getTitle() { return $this->title; }
	function topost_getDescription() {	return $this->description; }
	public function topost_getContent() {


		// $out = '<h3>' . $this->title . '</h3>';

		$out = '';


		$out .= '<h3 title="'.$this->title.'">'.$this->title.'</h3>';

		$out .= '<h4>Product Review</h4>';
		$out .= '<p>'.$this->description.'</p>';





		$link_to = $this->linkto();
		$out .= '<p>Check out ' . CBP::link($link_to, $this->title, 'link','_blank') . '</p>';




		// $out .= '[cbpress product="' . $this->lid . '"]';



		return $out;
	}





	function can_delete(){
		$out = true;
		if ($this->is_clickbank()) {
			$out = ($this->status == 'removed') ? true : false;
		}
		return $out;
	}

	function __get($k){
		if (isset($this->data[$k])) return $this->data[$k];
		switch ($k) {
			case 'lists': return $this->_lists();
			case 'cids':  return $this->_cids();
			case 'cats':  return $this->_cats();
			default:
				if (isset($this->_def[$k])){
					return $this->_def[$k];
				}else{
					return '';
					return array_key_exists($k, $this->data);

				}
		}
	}
	function __set($k,$v){
		$this->data[$k] = $v;
	}
	private function _lists(){
		$q = $this->db->get_col("SELECT list_id FROM " . CBTB_LIST_ITEM . " WHERE lid = " . $this->lid);
		return ($this->lists = $q);
	}
	private function _cids(){
		$arr = $this->cats;
		$ids = array();
		foreach ($arr as $cid => $info) {$ids[] = $cid; }
		return ($this->cids = $ids);
	}
	private function _cats(){
		$c = 'cid,lid,rank,join_custom,join_enable,join_id';
		$t = CBTB_TREE;
		$q = $this->db->get_results($this->db->prepare("SELECT $c FROM $t  WHERE lid = %s",array($this->lid)),OBJECT_K);
		$q = ($q) ? $q : array();
		return ($this->cats = $q);
	}


	function getdata() {
		return $this->data;
	}
	function delete() {

		if(! $this->can_delete()) return false;

		$id = intval($this->lid);
		if ($id > 0) {
			$tables = array(CBTB_LIST_ITEM,CBTB_TREE,CBTB_PROD);
			foreach($tables as $tbl) {
				$query = "DELETE FROM $tbl WHERE lid = $id";
				$this->db->query($query, $id);
			}
		}
	}

	function save() {
		$rowdata = array();


		foreach($this->_cols as $k => $v) {

			if($k != 'lid'){
				if(isset($this->data[$k])){
					$v = $this->data[$k];
					$v = stripslashes(esc_attr($v));
					if($v !== null){
						$rowdata[$k] = $v;
					}


				}
			}
		}
		if($rowdata['source'] == '') $rowdata['source'] = 'custom';
		if($this->has_errors()) return false;

		$this->db->suppress_errors();





		if($this->lid > 0) {
			if ( false === $this->db->update( CBTB_PROD, $rowdata, array('lid' => $this->lid) ) ) {
				$this->msg = "Could not update: " . $this->db->last_error;
				return false;
			}
		} else {
			if(! $this->is_clickbank()){
				$rowdata['slug'] = CBP::newslug($rowdata['title'], '0', 'lid', 'slug', CBTB_PROD);
			}




			$rowdata['created'] = gmdate("Y-m-d H:i:s");
			if ( false === $this->db->insert( CBTB_PROD, $rowdata ) ) {
				$this->msg = "Could not update: " . $this->db->last_error;
				return false;
			}
			$this->lid = (int) $this->db->insert_id;
		}

		$this->db->suppress_errors( false );

		return $this->lid;
	}


	function has_errors()	{
		$this->msg = '';
		if (CBP::len($this->title) < 1) {
			$this->msg = 'Product title cannot be empty.';
		} else if ( CBP::len($this->title) > 255) {
			$this->msg = 'Product title cannot be more then 255 characters long.';
		}

		if(CBP::len($this->msg) > 1){
			$this->err = 1;
		}else{
			$this->msg = 'Product Saved';
		}
		return $this->err;
	}



	function addcat($cid=0) {
		$id = $this->lid;
		if($cid > 0 && $id > 0){
			$query = "INSERT INTO " . CBTB_TREE . " (cid,lid,join_custom) VALUES ($cid,$id,1) ON DUPLICATE KEY UPDATE rank = rank";
			$this->db->query($query);
		}
		return $cid;
	}


	function update_tree($cids='') {

		$id = intval($this->lid);
		if(is_string($cids) && $cids != ''){
			$cids = explode(',',$cids);
		}
		$cids = is_array($cids) ? $cids : array();

		$tbl = CBTB_TREE;


		if(count($cids) > 0) {

			foreach($this->cats as $k => $v) {

				if(!in_array($k,$cids)){

					$this->db->query("DELETE FROM $tbl WHERE lid = $id AND join_custom = 1 AND cid = " . $k);

					$this->db->query("UPDATE $tbl set join_enable = 0 WHERE join_custom=0 AND lid=$id AND cid=$k");

				} else {

					$this->db->query("UPDATE $tbl set join_enable = 1 WHERE join_custom = 0 AND lid = $id AND cid = $k");

				}
			}


			$cids = array_map(array(&$this,'addcat'), $cids);



		}else{


						$this->db->query("UPDATE $tbl SET join_enable = 0 WHERE lid = $id AND join_custom = 0");


			$this->db->query("DELETE FROM $tbl WHERE lid = $id AND join_custom = 1");
		}

		return $cids;
	}

	function getAttributes() {

		$fields = array(
			'lid' 			=> 'Product ID',
			'active' 		=> 'Enabled',
			'clicks_raw' 		=> 'Clicks Raw',
			'clicks_unique' 	=> 'Clicks Unique',
			'vin' 			=> 'Vendor',
			'source' 		=> 'Product Source',
			'status' 		=> 'Feed Status',
			'created' 		=> 'Date Added',
			'modified' 		=> 'Last Updated',
			'date_status' 		=> 'Last Status Change',
			'redirect_url' 		=> 'Redirect URL',
			'link_tid' 	=> 'Custom TID',
			'PopularityRank' 	=> 'Popularity Rank',
			'Commission' 		=> 'Commission',
			'HasRecurringProducts' 	=> 'Recurring Billing',
			'Gravity' 		=> 'Gravity',
			'InitialEarningsPerSale' => 'Initial $ Per Sale',
			'PercentPerSale' 	=> '% Per Sale',
			'AverageEarningsPerSale' => 'Average $ Per Sale',
			'TotalRebillAmt' 	=> 'Avg Rebill Total',
			'Referred' 		=> '% Referred',
			'PercentPerRebill' 	=> 'Avg %/rebill'
		);
		return $fields;
	}





	function show_page($tab) {

		echo '<div class="wrapxxx">';
		echo '<div class="product-page">';

		echo '<table style="border:0px solid #ccc;"><tr><td style="padding:0px 10px 10px 0px;">';
		switch($tab) {
			case 'add':
			case 'edit':
				$this->show_edit();
				break;
			case 'delete':
				$this->show_delete();
				break;
			case 'info':
			default:
				$this->show_info();
				break;
		}

		echo '</td></tr></table>';
		echo '</div>';
		echo '</div>';

	}

	function show_delete() {
		if($this->can_delete()){
			$this->delete();
			echo '<p>Product was successfully deleted.</p>';
			$base = admin_url("admin.php?page=cbpress-products");
			echo CBP::link($base, 'Back to product list', 'button');
		} else{

			echo '<p>Only custom products can be deleted.</p>';
		}
	}













	function show_edit() {






		include_once (CBP_VIEWS_DIR . 'form_product.php');

	}
	function show_info() {



		$table1 = $this->show_clickbank_attributes('ClickBank Attributes');
		$table2 = $this->show_product_attributes('Product Attributes');
		$colspan = '';




		echo '<table width="700"><tr><td valign="top" colspan="2">';


				echo '<h2>';
				echo $this->title;
				if($this->tc && $this->is_clickbank()) echo '<span class="edited">(edited)</span>';
				echo '</h2>';

				if(! CBP_api::activated()){

					$reglink = admin_url("admin.php?page=cbpress-setup");
					$reglink = CBP::link($reglink, 'Registration required', '');
					echo '<div class=""><br>'.$reglink.' to enable your ClickBank ID on product hoplinks<br><br></div>';
				}
				$this->show_buttonbar();
				echo '<h4>Description</h4><p> ';
				echo $this->description;

				if($this->dc && $this->is_clickbank()) echo '<span class="edited">(edited)</span>';
				echo '</p>';

				echo '<h4>Marketplace Categories</h4>';
				$this->show_cats();


		echo '</td></tr>';
		echo '<tr><td valign="top"' . $colspan . '>';

			echo $table2;
			if($this->is_clickbank()){
				$this->show_product_shortcodes('Shortcode Reference');
				echo '<br/>';
			}


		echo '</td>';



			    echo '<td valign="top" width="50%">';
				echo '<div style="padding-left: 20px;">';





		if($this->is_clickbank()){
				echo $table1;

				$this->show_test_link('Test Hoplink:');


		}else{
				$this->show_product_shortcodes('Shortcode Reference');
				echo '<br/>';

				$this->show_test_link('Test Redirect URL:');

		}






				echo '</div>';

			    echo '</td>';
		if($this->is_clickbank()){
		}

		echo '
		  </tr>
		  </tr>
		    <td colspan="2">'. do_action(CBP_HOOK_PRODINFO) . '</td>
		  </tr>
		</table>
		';
	}

	function show_test_link($title='Test Link') {
			echo '<p>';
					echo '<b>'.$title.'<br/></b>';
					$link_to = $this->linkto();
					echo CBP::link($link_to, $link_to, 'link','_blank');
			echo '</p>';
	}

	function show_cats() {

		$catdata = $this->cats;

		$baseurl = $_SERVER['REQUEST_URI'];
		$baseurl = remove_query_arg('fa', $baseurl);







		if(! $catdata){
			echo '<p>This product is not assigned to any categories or was removed from the ClickBank marketplace</p>';
		}


		if($catdata){
			echo '<table class="widefatx " id="items" width="100%">';
			echo '<thead>
				<tr>
				<th>Category</th>
				<th>Pop Rank</th>
				<th>Enable / Disable</th>
				<th>Source</th>
				<th></th>
				</tr>
			</thead>';
			echo '<tbody>';



			foreach ($catdata as $cid => $info) {

				$cat = CBP_cats::getsub($cid);
				$togg = $baseurl;
				$togg = add_query_arg( array('fa'=>'togglejoin-'.$info->join_id,'noheader'=>true), $togg );
				$onoff = ($info->join_enable)?'on':'off';
				$checker = CBP::img('check_'. $onoff . '.png');



				echo '<tr>';
				$catlink = CBP::admin('products') . '&cid=' . $cid;
				$name = CBP::link($catlink, $cat->full);
				if($info->join_custom == 0){
						$img = CBP::img('clickbank_icon_sm2.png');
						echo "<td>$name</td>";
						echo "<td>$info->rank</td>";
						echo "<td>";
						cbpressfn::a($checker,$togg);
						echo "</td>";
						echo "<td>$img</td>";
						echo "<td></td>";
				} else{
						$img = '';
						echo "<td>$name</td>";
						echo "<td>n/a</td>";
						echo "<td>";
						cbpressfn::a($checker,$togg);
						echo "</td>";
						echo "<td>custom</td>";
						echo "<td></td>";
				}
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
		}


	}

	function visitlink($vin) {
		return "http://go.cbpress.com/" . $vin;
	}

	function get_action_links() {

		if(! isset($this->_action_links)){


			$lid = $this->lid;
			$base = admin_url("admin.php?page=cbpress-products&lid=");

			$out = (object) array();
			$out->post = admin_url('post-new.php?cbpress-product-id=') . $lid;
			$out->more = $base . $lid;
			$out->edit = $base . $lid . '&tab=edit';
			$out->delete = $base . $lid . '&tab=delete';
			// $out->shield = "javascript:hopshield('" . $this->vin . "');";

			$out->visit = $this->redirect_url;
			if($this->is_clickbank()){
				$out->visit = $this->visitlink($this->vin);
			}

			$out->back = wp_get_referer();

			$this->_action_links = $out;

			unset($out,$base);
		}
		return $this->_action_links;
	}


	function show_buttonbar() {




			$links = $this->get_action_links();
			if($this->is_clickbank()){
				$t = 'Product Sales Page: ' . esc_html($this->title);
			}else{
				$t = 'View Product Page: ' . esc_html($this->title);
			}

			$sep = ' or ';
			$sep = '  ';
			$modal = 0;





			$out = array();
			$out[] = CBP::link($links->edit, 'Edit product', 'button');
			if($this->can_delete()){
				$out[] = CBP::link($links->delete, 'Delete product', 'button deleteme');
			}



			$out[] = CBP::link($links->visit, 'View Site', 'hop-visit external button');

			if(!$modal) $out[] = '<a href="'.$links->back.'" class="button">Back to list</a>';





			if($this->is_clickbank()){

				// $out[] = "<a href=\"javascript:hopshield('{$this->vin}')\" class=\"button\">CB HopLink Shield</a>";
			}




			echo '<div class="buttonbar ' . $this->source . '">';
			echo implode($sep,$out);
			echo '</div>';

		unset($out);

	}



	function show_product_shortcodes($title='Shortcodes') {
		$id = $this->lid;
		$shortcode1 = '[cbpress product="' . $this->lid . '"]';
		$shortcode1 = "<div><input name='shortcode1_$id' id='shortcode1_$id' type='text' value='$shortcode1' class='shortcode1' onfocus='this.select();' /></div>";

		$shortcode2 = '[cbpress vendor="' . $this->vin . '"]';
		$shortcode2 = "<div><input name='shortcode2_$id' id='shortcode2_$id' type='text' value='$shortcode2' class='shortcode1' onfocus='this.select();' /></div>";

		if($title != '') echo '<h4>' . $title . '</h4>';

		echo '<div class="pshortcodes">';
			echo '<p>Copy & paste to any wordpress post or page</p>';
			echo $shortcode1;
			if($this->is_clickbank()) echo $shortcode2;
		echo '</div>';



	}


	function show_product_attributes($title='Product Attributes') {


		$cb = array();
		$cnt = 0;
		$attrCols = self::getAttributes();


		foreach ($attrCols as $key => $label) {
			if(!array_key_exists($key,$this->used)){
				$cnt ++;
				$class = ($cnt%2 == 0) ? ' class="alt"' : '';
				$value = $this->$key;
				$value = $this->filter($key,$value);

				$ok = true;
				if(($key == 'redirect_url' && $this->is_clickbank())){
					$ok = false;
				}
				if(($key == 'source' && $this->is_clickbank())){
					$value = '<b class="cbxml">ClickBank </b>';
				}
				if($ok) $cb[] = "<tr><td>$label</td><td>$value</td></tr>";
				$this->used[$key] = 1;
			}
		}


		$cb = implode("\n",$cb);
		$table  = '<table class="widefat">' . $cb . '</table>';


		if(! $title == '') $table = '<h4>' . $title . '</h4>' . $table;
		return $table;
	}


	function show_clickbank_attributes($title='ClickBank Attributes') {

		$cb = array();
		$cnt = 0;

		$cbcols = CBP_Meta::getClickbankCols();


		foreach ($cbcols as $key => $col) {
			if(!array_key_exists($key,$this->used)){
				$cnt ++;
				$class = ($cnt%2 == 0) ? ' class="alt"' : '';
				$label = $col['label'];
				$id = $col['id'];
				$value = $this->$id;
				$value = $this->filter($id,$value);
				$cb[] = "<tr$class><td>$label</td><td>$value</td></tr>";
				$this->used[$id] = 1;
			}
		}
		$cb = implode("\n",$cb);
		$table  = '<table class="widefat">' . $cb . '</table>';
		if(! $title == '') $table = '<h4>' . $title . '</h4>' . $table;
		return $table;
	}



}

?>