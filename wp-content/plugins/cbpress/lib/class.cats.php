<?php
if (!defined('ABSPATH')) die();

class CBP_cats {


	public static $stcats = null;

	public static $okcats = null;

	public function __construct() {


	}

	function fetchall($type='') {
		global $wpdb;
		return  $wpdb->get_results("SELECT * FROM " . CBTB_CAT . " ORDER BY full");
	}
	function fetchtype($type='clickbank') {
		global $wpdb;
		return  $wpdb->get_results("SELECT * FROM " . CBTB_CAT . " WHERE type='$type' ORDER BY full");
	}

	function get_catfrom($name, $value, $output=ARRAY_A) {
		global $wpdb;
		return $wpdb->get_row($wpdb->prepare("SELECT * FROM " . CBTB_CAT . " WHERE $name = %s",array($value)),$output);
	}
	function getCatFromSlug($value, $output=ARRAY_A) {
		return self::get_catfrom('slug', $value, $output);
	}
	function getCatFromXPath($value, $output=ARRAY_A) {
		return self::get_catfrom('xpath', $value, $output);
	}


	/**
	 *
	 *
	 *  .subs = array of top level cids
	 *      stcat.category[subid] = cat struct
	 *      each cat struct has an array of .subs
	 **/




	function getCategoryCacheTree() {



		global $wpdb;

		$tbl = CBTB_CAT;
		$join = CBTB_TREE;
		$prod = CBTB_PROD;

		$query = "SELECT cid, pid, type, name, enabled FROM " . CBTB_CAT;
			// abort($result);

		if(1 == 2){
				$result = $wpdb->get_results($query,ARRAY_A);
				$nodes = array();
				$tree = array();
				foreach ($result as &$node) {
					  $node['children'] = array();
					  $cid = $node['cid'];
					  $pid = $node['pid'];
					  $nodes[$cid] =& $node;
					  if (array_key_exists($pid, $nodes)) {
					    $nodes[$pid]['children'][] =& $node;
					  } else {
					    $tree[] =& $node;
					  }
				}
				return $tree;




		}else{




				$result = $wpdb->get_results($query);


				$nodes = array();
				$tree = array();
				foreach ($result as &$node) {
					  $node->children = array();
					  $cid = $node->cid;
					  $pid = $node->pid;
					  $nodes[$cid] =& $node;
					  if (array_key_exists($pid, $nodes)) {
					    $nodes[$pid]->children[] =& $node;
					  } else {
					    $tree[] =& $node;
					  }
				}
				return $tree;
		}


	}




	function getCategoryCache() {

		global $wpdb;

		$tbl = CBTB_CAT;
		$join = CBTB_TREE;
		$prod = CBTB_PROD;


		$query = "SELECT c.cid, c.pid, c.depth, c.type, c.name, c.slug, c.xpath, c.enabled ";
		$query .= ", (SELECT count(p.lid) FROM $join t INNER JOIN $prod p ON (t.lid = p.lid) WHERE (t.cid = c.cid) ) as prodcount";
		$query .= " FROM $tbl c WHERE 1 = 1 ORDER BY c.pid asc, c.name asc ";
		$result = $wpdb->get_results($query,OBJECT_K);

				$topids = array();
				$first = array(
					'cid' => '0',
					'pid' => '',
					'depth' => '0',
					'type' => 'clickbank',
					'name' => 'Categories',
					'full' => 'Categories',
					'slug' => '',
					'xpath' => '',
					'enabled' => 1,
					'prodcount' => 0,
					'subs' => ''
				);

				foreach($result as $num => &$row){
						$id = $row->cid;
						if(! isset($result[$id]->children)) $row->children = array();

						// add self id to parent subs
						if(isset($result[$row->pid])){
						$result[$row->pid]->children[$row->name] = $id;
						}else if($row->pid == 0) {
							$topids[] = $id;
						}
						$row->full = array();
						$row->path = array();
						$good = false;
						do { if(isset($result[$id])){
								$row->full[] = $result[$id]->name;
								$row->path[] = $id;
								$id = $result[$id]->pid;
							}else{ $good = true; }
						} while($good == false);

						$row->depth = count($row->path)-1;
						$row->full = implode(' : ', array_reverse($row->full));
						$row->path = implode(',', array_reverse($row->path));

						unset($good,$id);
				}

				$first['subs'] = $topids;

				/// sort by full path key, sort subs by name
				$res = array();
				foreach($result as $num => &$row){
					ksort($row->children);
					$row->subs = array_values($row->children);
					unset($row->children);
					$res[$row->full] = $row;
				}
				ksort($res);

				// put back with cid as index
				$result = array();
				foreach($res as $num => &$row){
					$result[$row->cid] = $row;
				}
				unset($res);

				// abort($result);


		$out = new stdClass();
		$out->subs 	= $topids;
		$out->category 	= $result;
		$out->top 	= $first;



		// cids with custom edited products
		$out->cust 	= self::load_cids_for_custom();




		// abort($out);


		return $out;
	}


	function getCategoryTree() {

		global $wpdb;
		$tbl = CBTB_CAT;
		$query = "SELECT t.cid, t.pid, t.depth, t.name, t.full, t.slug, t.xpath
                    FROM $tbl t WHERE 1 = 1 ORDER BY t.full";

		$result = $wpdb->get_results($query);

		$tempCategories = new stdClass();

		for($i = 0; $i < count($result); $i++) {
			$thiscat = $result[$i];
			$path = explode(':',$thiscat->full);
			$_subcategory = '';
			if($thiscat->depth > 1) {
				$_subcategory = trim($path[count($path) - 1]);
			}
			$_category = trim($path[0]);
			$thiscat->subcategory = trim(cbpressfn::escaper($_subcategory,'quotes'));
			$thiscat->category = cbpressfn::escaper($_category,'quotes');
			$tempCategories->{$thiscat->cid} = $thiscat;
		}
		// $categories = array();
		$categories = new stdClass();
		$keyname = 'category';	$subkey = 'subcategory';
		$keyname = 'pid';
		$subkey = 'cid';

		foreach($tempCategories as $cid => $cc) {
			if(!isset($categories->{$cc->$keyname}) && isset($tempCategories->{$cc->$keyname})) {
				$categories->{$cc->$keyname} = $tempCategories->{$cc->$keyname};
			}
			if($cc->subcategory) {
				$categories->{$cc->$keyname}->children->$cid = $tempCategories->$cid;
			}
		}

		// abort($categories);
		return $categories;

	}


	function getcats($pid = '',$enabled = '',$output = OBJECT_K) {

		global $wpdb;
		$where = array();
		if(is_numeric($pid)) $where[] = 'pid = ' . $pid;
		if(is_numeric($enabled)) $where[] = 'enabled = ' . $enabled;
		$where = trim(implode(' AND ',$where));
		if(strlen($where)) $where = 'WHERE ' . $where;

		$tbl = CBTB_CAT;
		$tbl3 = CBTB_TREE;

		$query = "SELECT c.cid, c.pid, c.name, c.full, c.slug, c.depth, c.enabled, COUNT(t.lid) AS prodcount
					  FROM {$tbl} c LEFT JOIN {$tbl3} t ON c.cid = t.cid {$where}
					  GROUP BY c.cid, c.pid, c.name, c.full, c.depth, c.enabled ORDER BY c.full ASC";

		$result = $wpdb->get_results($query,$output);

		return $result;

	}


	function fetch($cid = '') {
		global $wpdb;
		if(!is_numeric($cid)) $cid = 0;
		$query = "SELECT * FROM " . CBTB_CAT . " WHERE cid = {$cid}";
		$result = $wpdb->get_row($query,object);
		return $result;
	}




	function get_cat_select() {
		global $wpdb;
		$tbl = CBTB_CAT;
		$query = "SELECT cid, pid, name, full, slug FROM {$tbl} WHERE enabled = 1 ORDER BY full";
		$result = $wpdb->get_results($query,ARRAY_A);
		for($i = 0; $i < count($result); $i++) {
			if($result[$i]['pid'] > 0) {
				$iteration = count(explode(':',$result[$i]['full'])) - 1;
				$result[$i]['name'] = str_repeat("-",$iteration) . $result[$i]['name'];
			}
		}
		$first = array('cid' => 0,'name' => 'ANY','pid' => 0,'full' => 'ANY');
		array_unshift($result,$first);
		return $result;
	}

	function getPairedlist() {
		global $wpdb;
		$query = "SELECT slug,full FROM " . CBTB_CAT;
		$result = $wpdb->get_results($query,ARRAY_N);
		$result = cbpressfn::fetchPairs($result);
		return $result;
	}


	function fetchlist($pid = '',$enabled = '',$output = ARRAY_A) {
		global $wpdb;

		$where = array();
		if(is_numeric($pid)) $where[] = 'pid = ' . $pid;
		if(is_numeric($enabled)) $where[] = 'enabled = ' . $enabled;
		$where = trim(implode(' AND ',$where));
		if(strlen($where)) $where = 'WHERE ' . $where;

		$table = CBTB_CAT;
		$cols = 'cid, pid, name, full, slug, enabled';

		$query = "SELECT {$cols} FROM {$table} {$where} ORDER BY full";
		$result = $wpdb->get_results($query,$output);
		return $result;
	}








	// (so far called from widget)

	function getCategoryList($pid,$output_type = OBJECT_K) {
		global $wpdb;

		$tbl = CBTB_CAT;

		// type can be: all, parents, children
		// if type is children, there must be a parent id
		// $type = isset($params['type']) ? $params['type'] : 'all';
		// $pid = isset($params['pid']) ? intval($params['pid']) : '0';

		$enabled = 1;
		$args = array($pid,$enabled);
		if(is_numeric($pid)) {
			$query = "SELECT cid,name,pid,full,slug FROM {$tbl} WHERE pid = {$pid} AND enabled = 1 ORDER BY name";
		} else {
			$query = 'SELECT * FROM {$tbl} WHERE enabled = 1 ORDER BY full';
		}
		$result = $wpdb->get_results($query,$output_type);
		return $result;
	}

	 /**** static cache access methods ****/


	 // climbs a category path
	static function climb($pid=0, $withcol='cid') {
		self::initCache();
		$counter = 0;
		$good = false;
		$stack = array();
		do {
			$counter++;
			$cat = self::getsub($pid);
			if($cat){
				// $stack[] = $cat;
				$stack[] = $cat->$withcol;
				$pid = $cat->pid;
			}else{
				$good = true;
			}
		} while($good == false);
		$stack = array_reverse($stack);
		return $stack;
	}

	static function subs($id=0) {
		self::initCache();
		if(isset(self::$stcats->category[$id])){
			$subs = self::$stcats->category[$id]->subs;
		}else{
			$subs = self::$stcats->subs;
		}
		return $subs;
	}





	static function crumbs($params) {

		$defs = array( 'id'=>0, 'sep'=>' ', 'uri'=>$_SERVER['REQUEST_URI'], 'topid'=>0, 'topuri'=>$_SERVER['REQUEST_URI'], 'toplabel'=>'Marketplace' );
		$args = CBP::get_arguments($params,$defs);
		extract($args);




		$path = self::climb($id);

		$crumbs = array();
		$i = 0;

		$allowed = array_reverse($path);
		foreach($allowed as $id){

			$i++;

			$link = add_query_arg('cid',$id, remove_query_arg( 'cid', $uri ));

			$cat = self::getsub($id);

			if($i == 1){
				$crumbs[] = '<span style="font-weight:bold;">' . $cat->name . '</span>';
			}else{
				$crumbs[] = cbpressfn::html_link($link, $cat->name);
			}
			if($id == $topid) break;
		}


		if($topid == 0){
			$link = remove_query_arg( 'cid', $topuri );
			$crumbs[] = cbpressfn::html_link($link, $toplabel);
		}



		$crumbs = array_reverse($crumbs);
		$crumbs = implode($sep, $crumbs);

		return $crumbs;

	}




	static function enabled($id=0) {
		self::initCache();
		if(isset(self::$stcats->category[$id])){
			return self::$stcats->category[$id]->enabled;
		}else{
			return false;
		}
	}


	function get_for_select() {
		$data  = array();
		$items = self::fetchall();
		foreach ( $items AS $item ) {
			$data[$item->cid] = $item->full;
		}
		return $data;
	}





	function get_for_select_custom() {
		$data  = array();
		$items = self::fetchtype('custom');
		foreach ( $items AS $item ) {
			$data[$item->cid] = $item->full;
		}
		return $data;
	}



	function explode_xpath() {
		// $tree = self::get_tree(0);
		global $wpdb;
		$tree = $wpdb->get_results($wpdb->prepare("SELECT xpath, cid FROM " . CBTB_CAT . " WHERE pid = %s ORDER BY xpath asc",array(0)),ARRAY_N );
		$tree = cbpressfn::fetchPairs($tree);
		$tree = cbpressfn::explodeTree($tree, "@", true);
		abort($tree);
	}


	function category_dropdown_array($cid=null, $opts=array(), $iteration=0, $r=null) {

			static $default_options = array(
				'showpath' => false,
				'type' => null
			);
			$opts = CBP::get_arguments($opts,$default_options);
						extract($opts);


						////// echo $iteration . ' ';



			// abort($opts);

		static $arr = array();
		static $counter = 0;
		if(!is_numeric($cid)) $cid = 0;
		if($counter == 0) $iteration = 0;
		$depth = $iteration;

		$cat = self::getsub($cid);

		if($cat){

				if($r === null) $r = $cid;

				if($r == $cid) $depth = 0;

				if($showpath){
					$thename = self::climb($cid, 'name');
					$thename = implode(' : ', $thename);
				}else{
					if($depth > 0) $depth--;

					$thename = str_repeat("---", $depth) . ' ' . stripslashes($cat->name);
				}
				$arr[$cid] = trim($thename);
				unset($thename);

		}



		$counter++;
		$children = self::subs($cid);
		if($children){
			foreach($children as $id) {
				self::category_dropdown_array($id, $opts, $iteration+1, $r);
			}
		}
		// unset($children);
		// if($r === null){
		if($iteration == 0){
				// unset($ids);
				return $arr;
		}
	}

















	// gets all active cids where a customized clickbank product exists



	function get_cids_for_custom() {

		if(isset(self::$stcats->cust)){
			$out = self::$stcats->cust;
		} else {
			$out = self::load_cids_for_custom();
		}
		return $out;
		
	}


	function load_cids_for_custom($output_type = OBJECT_K) {


			global $wpdb;


			// $output_type = OBJECT_K;


			$table1 = CBTB_PROD;
			$table2 = CBTB_TREE;
			$table3 = CBTB_CAT;

			$query = "SELECT DISTINCT c.cid, c.pid ";
			$query .= "FROM $table1 p ";
			$query .= "LEFT JOIN $table2 t ON (p.lid = t.lid) ";
			$query .= "LEFT JOIN $table3 c ON (t.cid = c.cid) ";
			$query .= "WHERE ";
			// $query .= "(LOWER(p.title) != LOWER(p.feed_title) AND p.source = 'clickbank') OR ";
			// $query .= "(LOWER(p.description) != LOWER(p.feed_desc) AND p.source = 'clickbank') ";



			$query .= "(LOWER(p.title) != LOWER(p.feed_title)) OR ";
			$query .= "(LOWER(p.description) != LOWER(p.feed_desc)) ";



			$result = $wpdb->get_results($query,$output_type);

			$cids  = array();
			foreach ( $result AS $item ) {
				$cids[] = $item->pid;
				$cids[] = $item->cid;
			}

			$result = $cids;
			return $result;


			// $result = array_keys($result);
			// if(self::$ctcats === null){
			// self::$cccats = $cids;
			// return self::$cccats;
			// }
			// return self::$cccats;
			// $cids = array_values($cids);
			// $cids = implode(',', array_values($cids));
			// $cids = explode(',', $cids);
			// dump($cids);
			// $cids = array_keys($cids);
			// $cids = implode(',', array_keys($cids));
			// $result = implode(',', array_keys($result));
			// dump($result);


	}












	function category_dropdown_tree($cid=null, $sel=null) {
		$output = '';
		$cid = (!is_numeric($cid)) ? 0 : $cid;
		$children = self::get_flat_branch($cid);
		foreach($children as $id => $cat) {
			$selected = ($sel == $id) ? " selected='selected'" : '';
			$thename = str_repeat("&nbsp;&nbsp;&nbsp;", $cat->depth) . ' ' . stripslashes($cat->name);
			$output .= "<option class=\"level-{$cat->depth}\" value=\"$id\" $selected>" .$thename. "</option>\r\n";
		}
		return $output;
	}
	function category_dropdown_tree_db($cid=null, $sel=null) {
		$output = '';
		$cid = (!is_numeric($cid)) ? 0 : $cid;
		$children = self::get_flat_branch_db($cid);
		foreach($children as $id => $cat) {
			$selected = ($sel == $id) ? " selected='selected'" : '';
			$thename = str_repeat("&nbsp;&nbsp;&nbsp;", $cat->depth) . ' ' . stripslashes($cat->name);
			$output .= "<option class=\"level-{$cat->depth}\" value=\"$id\" $selected>" .$thename. "</option>\r\n";
		}
		return $output;
	}

	static function getsub($id) {
		self::initCache();
		if(isset(self::$stcats->category[$id])){
			return self::$stcats->category[$id];
		}else{
			return null;
		}
	}

	function category_checkbox_tree($args) {



			static $defs = array(
				'cid' => null,
				'selected' => array(),
				'disabled' => array(),
				'checkname' => 'cids',
				'iteration' => null
			);
			$args = CBP::get_arguments($args,$defs);
			extract($args);

		$output = '';
		if(!is_array($selected)) $selected = explode(',',$selected);
		if(!is_array($disabled)) $disabled = explode(',',$disabled);
		if(!is_numeric($cid)) $cid = 0;
		$class = 'rootcat';




		$children = self::subs($cid);
		if($children){

			$icat = self::getsub($cid);
			if($icat){
					$class = ($icat->pid == 0) ? 'topcat' : 'subcat';
			}

			$output .= '<ul class="'.$class.'">';

			$i = 0;

			foreach($children as $id) {
					$cat = self::getsub($id);
					$checked = (in_array( $cat->cid, $selected ) ? ' checked="checked"' : "" );
					// $checked = ($selected_id == $cat->cid) ? ' checked="checked"' : '';

					$dis = (in_array( $id, $disabled ) ? ' disabled' : "" );


					$i++;

					$name = stripslashes($cat->name);


					$last = ($i == count($children) && $class == 'topcat') ? ' last' : '';


					$output .= "\n<li id='category-$id' class='$class $last'>";

					$class2 = ($cat->pid == 0) ? ' toplevel' : '';

					$output .= '<label class="selectit'.$class2.'">';
					$output .= '<input value="' . $id . '" type="checkbox" name="'.$checkname.'" id="in-category-'.$id.'"' . $checked . $dis . '/> ';
					$name = esc_html($name);
					if($dis != ''){
						$name = '<span class="cbxf">'.$name.'</span>';
					}
					$output .= $name;






					$output .= '</label>';
					$output .= self::category_checkbox_tree( array( 'cid' => $id, 'selected' => $selected, 'disabled' => $disabled, 'checkname' => $checkname, 'iteration' => $iteration+1 )	);
					$output .= "</li>\n";

					// $output .= "<label $selected value='{$id}'>".trim($name)."</label>\r\n";
			}
			$output .= '</ul>';
		}
		return $output;
	}





	function get_flat_branch($cid=0, $level=null, $maxlevel=null) {

		static $tree = array();

		if($level === null){
				$rootcid = $cid;
				$cat = self::getsub($rootcid);
				if($cat){
					$tree[$cat->cid] = $cat;
				}
				foreach (self::subs($rootcid) as $id) {
					self::get_flat_branch($id, 1,$maxlevel);
				}
				return $tree;

		} else {
			$cat = self::getsub($cid);
			if($cat){
				$tree[$cat->cid] = $cat;
				$currlevel = $level+1;
				$ok = (is_numeric($maxlevel) && $currlevel > $maxlevel) ? false : true;

				$children = self::subs($cid);
				if ($ok && $children){
						foreach($children as $id) {
							self::get_flat_branch($id, $currlevel, $maxlevel);
						}
				}
			}
		}
	}


	function get_flat_branch_db($cid=0, $level=0, $pname='') {

		$tree = array();


		$cat = self::get_category($cid,'cid,pid,name,type');
		if($cat){


			if($level == 0){


				// abort($cat);
				// backtrack up to get pathup from first

				$pname = self::climb_db($cid, 'name');

				$cat->depth = count($pname)-1;


				$level = $cat->depth;

				$pname = implode(' : ', $pname);
			} else {
				$pname = (($pname != '') ? "$pname : " : '') . $cat->name;
				$cat->depth = $level;
			}

			$cat->full = $pname;
			$tree[$cat->cid] = $cat;

			$level++;
		}

		$children = self::get_children($cid);
		foreach($children as $id) {
			$tree += self::get_flat_branch_db($id, $level, $pname);
		}

		return $tree;


	}

	static function make_tree(&$unsorted, $start_node) {
			$out=array();
			foreach($unsorted as $key=>$node) {
				if ($node->pid==$start_node) {
				    // $node->children=self::_build_tree($unsorted, $key);
				    // unset($unsorted[$key]);
				    $out[]=array(
						    'name'=>$node->name ,
						    'pid'=>$node->pid,
						    'cid'=>$node->cid,
						    'type'=>$node->type,
				    		'children' => self::make_tree($unsorted, $key)
				    );
				}
			}
			return $out;
	}







	// like for use with dynatree

	static function get_tree($cid=0) {
		$tree = array();

		// echo 'get_tree';
		// $cat = self::get_category($cid,'cid,pid,name,type');
		$cat = self::getsub($cid);
		if($cat){
			$tree[$cat->cid] = $cat;
		}
		// $children = self::get_children($cid);

		$children = self::subs($cid);
		// abort($children);


		foreach($children as $id) {
			$tree += self::get_tree($id);
		}
		unset($level,$cat,$children);
		return $tree;
	}

	static function make_dynatree(&$unsorted, $start_node=0, &$selected=array()) {
			$out=array();
			foreach($unsorted as $key=>$node) {
				if ($node->pid==$start_node) {


					$sel = false;
					$status = '';

				    $tt = $node->full;

					if(isset($selected[$node->cid])){

								// dump($selected[$node->cid]);
								// abort($node);


				  		$sel = true;

				  		if(is_object($selected[$node->cid])){

							if( $selected[$node->cid]->join_custom ==  0) {
								if( $selected[$node->cid]->join_enable ==  0) {

									$status = '<b>(disabled)</b>';


				  					$sel = false;
				   					$tt = 'This product is linked to this CB category in the XML feed but you have disabled it';

								}
							}
						}

					}




							// abort($selected);
					// if($node->cid == 10){	}

				   //  $sel = (in_array($key, $selected)) ? true : false;


				    $title = $node->name;
				    if($sel) $title .= ' ***';
				    $n=array(
						    'title'=>$title . ' '  . $status,
						    'tooltip'=>$tt,
						    'type'=>$node->type,
						    'isFolder'=> false,
						    'key'=>$node->cid,
						    'icon' => 'type_' . $node->type.'.png',
						    'select' => $sel,
				    		'children' => self::make_dynatree($unsorted, $key, $selected)
				    );
				    $out[] = $n;
				    unset($n,$sel,$title);
				}
			}
			return $out;
	}







	function get_pathup($pid=0) {
		$stack = self::climb_db($pid,'');
		$out = new stdClass();
		foreach ( $stack as $cat ) {
			$out->{$cat->cid} = $cat->name;
		}
		unset($stack);
		return $out;
	}
	function climb_db($pid=0, $withcol='cid') {
		$counter = 0;
		$good = false;
		$stack = array();
		$maxloop = 30;
		do {
			$cat = self::get_category($pid,'cid,pid,name');
			if(!$cat || $counter == $maxloop){
				$good = true;
			}else{
				$stack[] = ($withcol=='' || !isset($cat->$withcol)) ? $cat : $cat->$withcol;
				$pid = $cat->pid;
			}
			$counter++;
		} while($good == false);
		$stack = array_reverse($stack);
		return $stack;
	}


	function get_category($cid,$cols='*'){
		global $wpdb;
		return $wpdb->get_row($wpdb->prepare("SELECT $cols FROM " . CBTB_CAT . " WHERE cid = %s",array(intval($cid))));
	}
	function get_children($pid){
		global $wpdb;
		return $wpdb->get_col($wpdb->prepare("SELECT cid FROM " . CBTB_CAT . " WHERE pid = %s ORDER BY name asc",array(intval($pid))));
	}
	function get_prodcount($cid){
		global $wpdb;
		$table1 = CBTB_TREE;
		$table2 = CBTB_PROD;
		return $wpdb->get_var("SELECT count(p.lid) FROM $table1 t INNER JOIN $table2 p ON (t.lid = p.lid) WHERE (t.cid = " . intval($cid) . ")");
	}






	// return all cats or an array with parent

	static function getall($id=null) {
		self::initCache();
		if($id === null || $id == 0){
			$out = &self::$stcats->category;
		} else {
			$out = self::get_flat_branch($id);
		}

		return $out;
	}
	static function initCache($reload=false) {
		if(self::$stcats === null || $reload == true){

			self::$stcats = self::getCategoryCache();
		}
	}

	static function getCache() {
		self::initCache();
		return self::$stcats;
	}


}





class CBP_cat {

	private $db;
	var $msg 	= '';
	var $err 	= false;
	var $tbl 	= CBTB_CAT;
	public $_data 	= array();


	static function get( $cid ) {
		global $wpdb;

		$row = CBP_cats::get_category($cid,'*');
		if ( $row ){
			return new CBP_cat( $row );
		} else {
			$row  = CBP_cat::structNew();
			return new CBP_cat( $row );
		}
		return false;
	}




	function __construct(  $values = ''  )	{

		global $wpdb;
		$this->db = &$wpdb;

		$row  = CBP_cat::structNew();
		unset($row->subs);
		foreach ( $row AS $key => $value ) {
			$this->$key = $value;
		}
		// $cid	= absint($this->cid);
		if ( is_object( $values ) || is_array( $values ) ) {
			foreach ( $values AS $key => $value ) {
				if(is_string($value)) $value = stripslashes($value);
			 	$this->$key = $value;
			}
		}
			// abort($this->_data);
			// abort(get_defined_vars());
	}


	function __get($k){
        if (isset($this->_data[$k])) return $this->_data[$k];
		switch ($k) {
			case 'subs': return ($this->subs = CBP_cats::get_children(intval($this->cid)));
			case 'stack':  return ($this->stack = CBP_cats::get_pathup(intval($this->cid)));
			case 'prodcount':  return ($this->prodcount = CBP_cats::get_prodcount(intval($this->cid)));
            default: return array_key_exists($k, $this->_data);
		}
	}
	function __set($k,$v){
		$this->_data[$k] = $v;
	}







	function __set222($k,$value){
		$this->_data[$k] = $value;
	}





	static function structNew ( ) {
		$row = array('cid'=>0, 'pid'=>0, 'type'=>'custom', 'name'=>'', 'slug'=>'', 'full'=>'', 'xpath'=>'', 'enabled'=>1, 'depth'=>0,'removed'=>0,'subs'=>array());
		$row  = (object) $row;
		return $row;
	}

	static function getQueryColumns() {
		// returns structure to implode for select columns and a default for insert
		$out = array(
			'cid'  		=> 0,
			'pid'  		=> 0,
			'type' 	 	=> 'custom',
			'slug' 	 	=> null,
			'removed' 	=> 0,
			'name' 	 	=> '',
			'full' 	 	=> '',
			'depth' 	=> 0,
			'xpath' 	=> null,
			'enabled' 	=> 1
		);
		return $out;
	}


	function getArray() {
			$data = array();
			$defaults  = CBP_cat::getQueryColumns();
			foreach ( $defaults AS $key => $value ) {
				if(isset($this->$key)){
					$value = $this->$key;
				}
				$data[$key]= stripslashes($value);
			}
			return $data;
	}

	function nameTaken() {
		global $wpdb;
		$name = $this->name;
		$pid = intval($this->pid);
		$cid = intval($this->cid);

		$name = stripslashes($name);
		$name = mysql_real_escape_string($name,$wpdb->dbh);

		$query = "SELECT COUNT(*) FROM {$this->tbl} WHERE name = '{$name}'";
		$query .= " AND pid = {$pid}";
		if($cid > 0){
				$query .= " AND cid <> {$cid}";
		}
		$found = $wpdb->get_var($query);
		return $found;
	}








	function update_meta() {

		global $wpdb;

		// update full,depth,slug for this & subs

		if($this->err == 0 &&  $this->cid > 0){

			$branch = CBP_cats::get_flat_branch_db($this->cid);
			foreach($branch AS $st) {
				$slug = CBP::newslug($st->full, $st->cid, 'cid', 'slug', $this->tbl);
				$data = array('full'=>$st->full, 'slug'=>$slug, 'depth'=>$st->depth);
				$wpdb->update($this->tbl , $data, array('cid'=>$st->cid));
			}
		}


	}



	function delete(){
		global $wpdb;

		if($this->can_delete()){

			$paths = CBP_cats::get_flat_branch_db($this->cid);

			foreach($paths AS $cat) {
				$cid = $cat->cid;
				$wpdb->query("DELETE FROM " . CBTB_TREE . " WHERE cid = $cid");
				$wpdb->query("DELETE FROM {$this->tbl} WHERE cid = $cid");
			}

				// abort($out);
				// $subs = $this->getsubs();

				return true;
		}
		return false;



	}


	// prepare for sql insert

	function _torow() {
		global $wpdb;

		$r = array();
		$a = CBP_cat::getQueryColumns();
		foreach($a AS $c => $v){
			if(isset($this->_data[$c])) $v = $this->$c;
			if($v !== null){
				$v = esc_attr($v);
				$v = stripslashes($v);
				$v = mysql_real_escape_string($v,$wpdb->dbh);
				$r[$c]= $v;
			}
		}
		unset($a);
		return $r;
	}

	function save() {

		global $wpdb;

		$found = false;
		$id = intval($this->cid);
		$found = CBP::recordexists("id=$id&key=cid&table=".$this->tbl);
		$this->name = trim(str_replace(":"," ",$this->name));
		if(! $found){
			$this->newslug();
			if($this->type == '') $this->type = 'custom';
		}


		if($this->has_errors()) return false;

		$rowdata = $this->_torow();
		if($found){
			$wpdb->update($this->tbl, $rowdata, array('cid'=>$id));
		}else{
			$wpdb->insert($this->tbl, $rowdata );
			$id = (int) $wpdb->insert_id;
		}




		/************************
		if($this->cid > 0) {
			if ( false === $wpdb->update( $this->tbl, $rowdata, array('cid'=>$id) ) ) {
					return new WP_Error('db_update_error', __('Could not update'), $wpdb->last_error);
			}
		} else {
			$arr['created'] = gmdate("Y-m-d H:i:s");
			if ( false === $wpdb->insert( $this->tbl, $rowdata ) ) {
					return new WP_Error('db_insert_error', __('Could not insert'), $wpdb->last_error);
			}
			$this->cid = (int) $this->db->insert_id;
		}
		*************/



		$this->cid = $id;
		$this->update_meta();
		return $this->cid;
	}

	function getmeta() {




			$id = $this->pid;

		if($this->cid > 0){
			// $id = $this->cid;
		}else{
		}
		$this->name = trim(str_replace(":"," ",$this->name));

		$path = CBP_cats::climb($id);
		$full = array();
		foreach($path as $id){
			$cat = CBP_cats::getsub($id);
			$full[] = $cat->name;
		}
		$out = new stdClass();
		if($this->name != '') $full[] = $this->name;

		$this->path 	= implode(',',$path);
		$this->depth 	= count($full);
		$this->full 	= implode(' : ',$full);
		$this->slug 	= CBP::newslug($this->full, $this->cid, 'cid', 'slug', CBTB_CAT);

	}

	function has_errors()	{

		$this->msg = ''; // reset

		if (CBP::len($this->name) < 1) {
			$this->msg = 'A category must have a name of at least 1 alphanumeric character.';

		} else if (preg_match('/[^a-zA-Z0-9_ -]/', $this->name) > 0 ) {
			$this->msg = 'Please only use these characters: letters, numbers, space, dash and underscore.';
		} else if ( CBP::len($this->name) > 100) {
			$this->msg = 'Category name cannot be more then 100 characters long.';
		} else if ( $this->nameTaken() ){
			$this->msg = 'A sub-category already exists with that name.';
		}
		if(CBP::len($this->msg) > 1){
			$this->err = 1;
		}else{
			$this->msg = 'Category Saved';
		}

		return $this->err;
	}






	function can_edit(){
		$out = false;
		if ($this->cid > 0) {
			if($this->type == 'clickbank'){
				if($this->removed == 1){
					$out = true;
				}
			} else {
					$out = true;
			}
		}
		return $out;
	}




	function can_delete(){

		/// if custom    allow delete
		/// if clickbank and removed=1    allow delete
		/// if clickbank and removed=0    no delete

		$out = false;

		if ($this->cid > 0) {
			if($this->type == 'clickbank'){
				if($this->removed == 1){
					$out = true;
				}
			} else {
				$out = true;
			}
		}
		return $out;
	}








	function config() {
	}


	function newslug() {
		$this->slug = CBP::newslug($this->full, $this->cid, 'cid', 'slug', CBTB_CAT);
	}


	function breadcrumbs($args) {


		$def = array('baseurl'=>'', 'label'=>'','topurl'=>'','vp'=>'cid','topid'=>0);
		extract(wp_parse_args( $args, $def ));

		$arr = $this->stack;
		$out = array();




		if(is_admin()){

			$url =  remove_query_arg(array('_'.$vp,$vp));
		}else{
			$url =  remove_query_arg(array('cid','pid'));

		}


		$topurl = $url;
		if($topurl == ''){
		}





		$allowed = array();
		foreach ( $arr AS $cid => $name ) { $allowed[] = $cid; }

		$allowed = array_reverse($allowed);
		$i = 0;

		foreach ( $allowed AS $cid ) {
			$name = $arr->$cid;

			$i++;

				$link = add_query_arg($vp,$cid,$url);
			if($i == 1){

				// $out[] = '<span style="font-weight:bold;">' . $name . '</span>';

				$out[] = CBP::link($link, esc_html($name), 'curr');
			}else{
				// $out[] = cbpressfn::html_link($link, $name);

				$out[] = CBP::link($link, esc_html($name));

			}


			if($cid == $topid){
				break;
			}

		}
		if($topid == 0){
				$out[] = cbpressfn::html_link($topurl,$label);

		}
				$out = array_reverse($out);




		// $out = implode(' &raquo; ', $out);
		$out = implode('', $out);
		// abort($out);
		return $out;
	}

	function update( $data ) {
		global $wpdb;
		if($this->cid > 0){
			$this->enabled = isset( $data['enabled'] ) ? '1' : '0';
			$this->name   = isset( $data['name'] ) ? $data['name'] : $this->name;
			$wpdb->update( CBTB_CAT , array( 'name' => $this->name, 'enabled' => $this->enabled ), array( 'cid' => $this->cid ) );
		}else{
			$data = CBP_cat::structNew();
		}
	}

	function move_to( $pid ) {
		global $wpdb;
		$wpdb->update( CBTB_CAT , array( 'pid' => intval( $pid ) ), array( 'cid' => $this->cid ) );
	}
	function type() {
		if ( $this->xpath == '' ) return 'custom';
		return 'clickbank';
	}

}
