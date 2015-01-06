<?php
if (!defined('ABSPATH')) die();
class CBP_query {

	static $_cols_qry = array(
			'lid' => '0',
			'vin' => '',
			'source' => 'clickbank',
			'active' => '',
			'date_status' => '',
			'created' => '',
			'status' => 'active',
			'title' => '',
			'description' => '',
			'feed_title' => '',
			'feed_desc' => '',
			'slug' => '',
			'link_tid' => '',
			'landing_page'=>'',
			'auto_update'=>1,
			'thumbnail'=>'',
			'redirect_url'=>'',
			'ActivateDate' => '',
			'Commission' => '0',
			'HasRecurringProducts' => '0',
			'Gravity' => '0',
			'InitialEarningsPerSale' => '0',
			'AverageEarningsPerSale' => '0',
			'TotalRebillAmt' => '0',
			'Referred' => '0',
			'PercentPerRebill' => '0',
			'PercentPerSale' => '0',
			'PopularityRank' => '0'
		);

	static $_cols_sel = array(
			'vin' => '',
			'lid' => '0',
			'source' => 'clickbank',
			'active' => '',
			'date_status' => '',
			'created' => '',
			'status' => 'active',
			'title' => '',
			'description' => '',
			'feed_title' => '',
			'feed_desc' => '',
			'thumbnail'=>'',
			'auto_update'=>1,
			'redirect_url'=>'',
			'ActivateDate' => '',
			'Commission' => '0',
			'HasRecurringProducts' => '0',
			'Gravity' => '0',
			'InitialEarningsPerSale' => '0',
			'AverageEarningsPerSale' => '0',
			'TotalRebillAmt' => '0',
			'Referred' => '0',
			'PercentPerRebill' => '0',
			'PercentPerSale' => '0',
			'PopularityRank' => '0'
		);
		
		
	function hoptolid($hop) {
		global $wpdb;
		return intval($wpdb->get_var("SELECT lid FROM " . CBTB_PROD . " WHERE vin = '{$hop}'"));
	}

	function toggle_join($id) {
		CBP::togglecol("id=$id&key=join_id&field=join_enable&table=".CBTB_TREE);
	}
	function toggle_cat($id) {
		CBP::togglecol("id=$id&key=cid&field=enabled&table=".CBTB_CAT);
	}
	function toggle_prod($id) {
		CBP::togglecol("id=$id&key=lid&field=active&table=".CBTB_PROD);
	}

	function get_list($id) {
		return CBP::getrecord("id={$id}&key=list_id&table=" . CBTB_LIST );
	}


	function getPager($args) {
		$pager = new CBP_Pager($_GET, $_SERVER['REQUEST_URI'], $args->sort, $args->order, 'products');
		$pager->per_page = $args->perpage;
		if($args->limit > 0){
			$pager->limit = $args->limit;
		}
		// abort($pager);
		return $pager;
	}
								
	function get_max_dates($limit=3) {
		global $wpdb;		
		$query = "SELECT DATE(ActivateDate) as xdate, COUNT(lid) as xcount FROM ".CBTB_PROD." WHERE source = 'clickbank'
		group by ActivateDate order by ActivateDate desc limit $limit";
		return cbpressfn::toValuePair($wpdb->get_results($query),'xdate','xcount');
	}
								
	function get_hop_list($keywords,&$pager,$cols='') {
		global $wpdb;
		$where = array();
		if($cols == ''){ $cols = 'vin,title,lid'; }
		
		if ($keywords != '') $where[] = "(title LIKE \"%$keywords%\" OR vin LIKE \"%$keywords%\" OR lid LIKE \"%$keywords%\")";
		$where = trim(implode(' AND ',$where));
		if(strlen($where)) $where = 'WHERE ' . $where;
		$out = new stdClass();
		$out->query = "SELECT SQL_CALC_FOUND_ROWS $cols FROM ".CBTB_PROD." $where ";
		$out->query .= $pager->to_limits("");
		$out->result = $wpdb->get_results($out->query,ARRAY_A);
		$pager->set_total($wpdb->get_var ("SELECT FOUND_ROWS()"));
		$out->found = $pager->total;
		return $out;
	}
				
	function get_hop_list_by_lid($lid,&$pager) {
		global $wpdb;
		$where = array();
		if ($lid != '') $where[] = "(lid LIKE \"%$lid%\")";
		$where = trim(implode(' AND ',$where));
		if(strlen($where)) $where = 'WHERE ' . $where;
		$out = new stdClass();
		$out->query = "SELECT SQL_CALC_FOUND_ROWS lid,title FROM ".CBTB_PROD." $where ";
		$out->query .= $pager->to_limits("");
		$out->result = $wpdb->get_results($out->query,ARRAY_A);
		$pager->set_total($wpdb->get_var ("SELECT FOUND_ROWS()"));
		$out->found = $pager->total;
		return $out;
	}

	function get_vendor_list($keywords,&$pager) {
		global $wpdb;
		$where = array();
		$where[] = "status = 'active'";
		if ($keywords != '') $where[] = "(title LIKE \"%$keywords%\" OR vin LIKE \"%$keywords%\")";
		$where = trim(implode(' AND ',$where));
		if(strlen($where)) $where = 'WHERE ' . $where;
		$out = new stdClass();
		$out->query = "SELECT SQL_CALC_FOUND_ROWS vin,title,active,TotalRebillAmt,HasRecurringProducts FROM ".CBTB_PROD." $where ";
		$out->query .= $pager->to_limits("");
		$out->result = $wpdb->get_results($out->query,ARRAY_A);
		$pager->set_total($wpdb->get_var ("SELECT FOUND_ROWS()"));

		$out->found = $pager->total;
		return $out;
	}


	function gethop($args=array(),$datatype=OBJECT){
		global $wpdb;
		$tbl = CBTB_PROD;
		$defaults = array(
		  'hop'=>'0', 'lid'=>'', 'cols'=>'lid,title,description,feed_desc,feed_title,vin,source,redirect_url,slug,status,active'
		);
		extract(wp_parse_args( $args, $defaults ));
		if($lid){
			$q = $wpdb->prepare("SELECT {$cols} FROM {$tbl} WHERE lid = %d", $lid);
		}else{
			$q = $wpdb->prepare("SELECT {$cols} FROM {$tbl} WHERE vin = %s", $hop);
		}
		$result = $wpdb->get_row($q,$datatype);
		if(!$result){
			$result = cbpressfn::struct($cols);
			$result->found = false;
		}else{
			$result->found = true;
		}
		return $result;
	}

	function get_summary() {

		global $wpdb;

		$p = CBTB_PROD;
		$c = CBTB_CAT;
		$j = CBTB_TREE;
		$l = CBTB_LIST;
		$i = CBTB_LIST_ITEM;

		$query = "SELECT ";
		$query .= " (SELECT COUNT('*') FROM $l where 1 = 1) as lists";
		$query .= " , (SELECT COUNT('*') FROM $i where 1 = 1) as list_items";
		$query .= " , (SELECT COUNT('*') FROM $j where join_custom = 0) as mall_clickbank";
		$query .= " , (SELECT COUNT('*') FROM $j where join_custom = 1) as mall_custom";
		$query .= " , (SELECT COUNT('*') FROM $c where enabled = 1) as cats_enabled";
		$query .= " , (SELECT COUNT('*') FROM $c where enabled = 0) as cats_disabled";

		// new clickbank products
		$query .= " , (SELECT COUNT(lid) FROM $p WHERE source = 'clickbank' ";
		$query .= " AND DATE(created) = (SELECT DATE(MAX(created)) FROM $p WHERE source = 'clickbank') ) as new_import";
		// new clickbank products
		$query .= " , (SELECT COUNT(lid) FROM $p WHERE source = 'clickbank' ";
		$query .= " AND DATE(ActivateDate) = (SELECT DATE(MAX(ActivateDate)) FROM $p WHERE source = 'clickbank') ) as new_clickbank";
		// SELECT MAX(`value`) FROM myTable WHERE `date` BETWEEN DATE_SUB(NOW(), INTERVAL 5 DAY) AND NOW()
		$query .= " , (SELECT DATE(MAX(modified)) FROM " . CBTB_PROD . " WHERE source = 'clickbank') as last_import_date";
		$query .= " , (SELECT COUNT('*') FROM $c where type = 'clickbank') 	as cats_clickbank";
		$query .= " , (SELECT COUNT('*') FROM $c where type = 'custom') 	as cats_custom";
		$query .= " , (SELECT COUNT('*') FROM $p where source = 'clickbank') 	as items_clickbank";
		$query .= " , (SELECT COUNT('*') FROM $p where source = 'custom') 	as items_custom";
		// removed
		$query .= " , (SELECT COUNT('*') FROM $p where status = 'removed' AND source = 'clickbank' ";
		$query .= " AND DATE(date_status) =  (SELECT DATE(MAX(modified)) FROM " . CBTB_PROD . " WHERE source = 'clickbank')) 	as items_removed ";
		$query .= " , (SELECT COUNT('*') FROM $p where status = 'active' AND source = 'clickbank') 	as items_active";
		// publish
		$query .= " , (SELECT COUNT('*') FROM $p where active = 1) as items_enabled";
		$query .= " , (SELECT COUNT('*') FROM $p where active = 0) as items_disabled";

		$result = $wpdb->get_row($query);

		$query = "SELECT DATE(ActivateDate) as xdate, COUNT(lid) as xcount FROM $p WHERE source = 'clickbank'
				group by ActivateDate order by ActivateDate desc limit 3";

		$result->new = cbpressfn::fetchPairs($wpdb->get_results($query,ARRAY_N));

		unset($query);
		return $result;
	}



	function getPoductCount() {
		global $wpdb;
		return $wpdb->get_var("SELECT COUNT('import_id') FROM " . CBTB_PROD);
	}






	// Returns category ids linked to a product.

	function getCategoryIds($lid) {
		global $wpdb;
		$tbl1 = CBTB_CAT;
		$tbl2 = CBTB_TREE;
		$query = "SELECT c.cid FROM {$tbl1} c, {$tbl2} cj WHERE cj.lid = $lid AND c.cid = cj.cid";
		$query = $wpdb->get_col($query);
		return $query;
	}

	// Updates a product's properties.
	function save_item_props($lids, $data) {
		global $wpdb;
		# create array of product ids
		if (!is_array($lids)) {
			$lids = array($lids => 0);
		}
		$tbl = CBTB_PROD;
		$data = stripslashes_deep($data);
		foreach ($lids as $lid => $value) {
			$wpdb->update($tbl, $data, "lid = {$lid}");
		}
	}





	function formatSearchParam($col,$value='') {
		switch ($col) {
			case 'sort':
				$sb = array_keys(CBP_Meta::getSortByList());
				$arr = explode(',',strtolower(implode(',',$sb)));
				$pos = array_search(strtolower($value),$arr);
				$value = ($pos > 0) ? $sb[$pos] : 'created';
				return $value;
			case 'order':
				$value = strtolower($value);
				if($value == '') $value = 'desc';
				$value = in_array( $value, array('desc','asc')) ? $value : 'desc';
				return $value;
			case 'billing':
				$value = in_array( $value, array('all','0','1')) ? $value : 'all';
				return $value;
			case 'category':
			case 'subcategory':
				$value = urldecode($value);
				return $value;
			case 'vendor':
			case 'keywords':
				$value = strip_tags($value);
				return $value;
			case 'perpage':
			case 'rank':
				return (int)$value;
			default:
				return $value; break;
		}
		return $value;
	}

	function getSearchParams($params=array()) {
		$args = new stdClass();
		$defaults = array(
			'vendor' => '',
			'tc'=>0,	// title changed
			'dc'=>0,	// desc changed
			'cc'=>0,	// title and desc changed
			'keywords'=>false,
			'category'=>false,
			'source' =>false,
			'cid'=>false,
			'pid'=>false,
			'status'=>'',
			'toprank'=>false,
			'active'=>'',
			'subcategory'=>false,
			'page'=>0,
			'perpage'=>10,
			'limit'=>null,
			'sort'=>'created',
			'order'=>'desc',
			'min_rank'=>false,
			'max_rank'=>false,
			'min_gravity'=>false,
			'max_gravity'=>false,
			'min_commission'=>false,
			'max_commission'=>false,
			'min_referred'=>false,
			'max_referred'=>false,
			'rank'=>false,
			'fromdate'=>false,
			'todate'=>false,
			'added'=>false,
			'billing'=>'all',
			'lookin'=>'',
			'hasrecurring'=>false,
			'result_view'=>'',
			'display'=>''
		);		
		foreach($defaults as $key => $value){

			if(isset($params[$key])){

				$value = $params[$key];

			}else if(isset($_GET[$key])){

				$value = $_GET[$key];

			}else if(isset($_GET['_'.$key])){

				$value = $_GET['_'.$key];
			}

			$args->$key = self::formatSearchParam($key,$value);

		}
		if($args->keywords == 'clickbank'){
			$args->keywords = 'cb';
		}
		$args->joined = ($args->cid > 0 || is_numeric($args->pid)) ? 1 : 0;
		unset($defaults);
		return $args;
	}
	function getQueryColumns() {
		// returns structure to implode for select columns and default insert

		return self::$_cols_qry;

	}

	function getSelectColumns() {

		return self::$_cols_sel;

	}



	function getSearch($params=array(),$datatype='') {

		global $wpdb;
		$out = new stdClass();
		$table1 = CBTB_PROD;
		$table2 = CBTB_TREE;
		$table3 = CBTB_CAT;

		$p = CBTB_PROD;





		$args = self::getSearchParams($params);


		// abort(array('params'=>$params,'args'=>$args));

		// pass to pagination
		$pager = CBP_query::getPager($args);

		// extract
		foreach($args as $key => $value){
			$$key = $value;
		}
		// abort($args);


		$where = array();



		// columns
		$cols = array();
		$qcolumns = self::getSelectColumns();


		$cbnk = "p.source = 'clickbank'";


			// $args->source = 'clickbank';


		foreach($qcolumns as $key => $value){
			$cols['p.' . $key] = $value;
		}

		// $cols = implode(',', array_keys($cols));
		$cols = array_keys($cols);


			if($args->toprank && is_int($args->toprank)){
					$args->toprank = $args->toprank+0;
					$where[] = "t.rank <= $args->toprank";
			}

			$joined = 0;
			if ($args->cid > 0 || (is_numeric($args->pid))){ // $pid!==null ||
				$joined = 1;
			}
		// abort($joined);
		// abort($args);




			# filter column ranges

					$ranges = array();
					foreach (array('Gravity','Commission','rank') as $col) {
							$c = strtolower($col);
							$scope = 'p.';

							$sql = array();


							$scope = 'p.';
							if($col == 'rank'){
									if ($joined == 1){
										$scope = '';
									}else{
										$col = 'PopularityRank';
									}
							}

							if($col == 'rank22222222222222222'){
									$scope = ($joined == 1) ? '' : $scope;
									$col = ($scope == '') ? '' : 'PopularityRank';
							}


							$range1 = $args->{'min_'.$c};
							$range2 = $args->{'max_'.$c};

							if(is_numeric($range1)) $sql[] = $scope . "$col >= $range1";
							if(is_numeric($range2) && $range2 > 0) $sql[] = $scope . "$col <= $range2";

							if($sql){
								$sql = trim(implode(' AND ',$sql));
								if(strlen($sql) > 4){ $ranges[] = 	'(' . $sql . ')'; }
							}
							unset($c, $scope, $sql, $range1, $range2);

							// if($range2 && is_numeric($range2) && $range2 > 0) $where[] = "p.$col <= $range2";
					}
					if(count($ranges)){



								$ranges = trim(implode(' AND ',$ranges));


								if($args->source == ''){

									$ranges = "(p.source = 'custom' OR ($cbnk AND ($ranges)) )";
								}
								// abort($ranges);

								$where[] = 	$ranges;
					}
					// abort($where);


			// abort($where);

			if(in_array($args->billing,array('0','1'))){
					//  and $args->source == 'clickbank'

						$where[]  = "p.HasRecurringProducts = $args->billing";

			}



			/******
			******/
			if(is_string($args->source) && $args->source != ''){
					$where[]  = "p.source = '$args->source'";
			}



			if(is_string($args->added) && $args->added != ''){


					// $where[]  = "p.created = '$added'";



					$sql  = '';
					switch ($args->added) {
						case 'new':
							// new clickbank products
							// $sql .= "DATE(p.ActivateDate) = (SELECT DATE(MAX(pp.ActivateDate)) FROM $p pp WHERE pp.source = 'clickbank')";
							$in = cbpressfn::quotedValueList( array_keys(CBP_query::get_max_dates(3)) );
							$sql .= "DATE(p.ActivateDate) IN($in)";

							break;
						case 'week':
							$sql  .= "DATE(p.created) >= curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY";
							$sql  .= " AND DATE(p.created) < curdate() - INTERVAL DAYOFWEEK(curdate())-1 DAY";
							break;
						case 'today':
							$sql  .= "DATE(p.created) = curdate()";
							break;
						case 'month':
							$sql  .= "DATE(p.created) >= CURRENT_DATE - INTERVAL DAYOFMONTH(CURRENT_DATE)-1 DAY - INTERVAL 1 MONTH";
							$sql  .= " AND DATE(p.created)  < CURRENT_DATE - INTERVAL DAYOFMONTH(CURRENT_DATE)-1 DAY";
							break;
						case '3months':
							$sql  .= "DATE(p.created) >= CURRENT_DATE - INTERVAL DAYOFMONTH(CURRENT_DATE)-1 DAY - INTERVAL 3 MONTH";
							$sql  .= " AND DATE(p.created)  < CURRENT_DATE - INTERVAL DAYOFMONTH(CURRENT_DATE)-1 DAY";
							break;
						case '6months':
							$sql  .= "DATE(p.created) >= CURRENT_DATE - INTERVAL DAYOFMONTH(CURRENT_DATE)-1 DAY - INTERVAL 6 MONTH";
							$sql  .= " AND DATE(p.created)  < CURRENT_DATE - INTERVAL DAYOFMONTH(CURRENT_DATE)-1 DAY";
							break;
						case 'year':
							$sql  .= "DATE(p.created) >= CURRENT_DATE - INTERVAL DAYOFMONTH(CURRENT_DATE)-1 DAY - INTERVAL 12 MONTH";
							$sql  .= " AND DATE(p.created)  < CURRENT_DATE - INTERVAL DAYOFMONTH(CURRENT_DATE)-1 DAY";
							break;
					}




					// time range
					
			/************
			
					switch($args->added) {
						case 'all-time':
							$range = "DATE(p.created) < '".gmdate("Y-m-d H:i:s")."'";
							break;
						case 'today':
							$range = "$table_wpp.day = '".gmdate("Y-m-d")."'";
							break;
						case 'weekly':
							$range = "$table_wpp.day >= '".gmdate("Y-m-d")."' - INTERVAL 7 DAY";
							break;
						case 'monthly':
							$range = "$table_wpp.day >= '".gmdate("Y-m-d")."' - INTERVAL 30 DAY";
							break;
						case 'yearly':
							$range = "$table_wpp.day >= '".gmdate("Y-m-d")."' - INTERVAL 365 DAY";
							break;
						default:
							$range = "post_date_gmt < '".gmdate("Y-m-d H:i:s")."'";
							break;
					}
			
			
			*******************/
			
			
			
					if(strlen($sql) > 10){
							$where[] = 	'(' . $sql . ')';
					}


			}else{
				if(is_string($args->fromdate) && is_string($args->todate)){
					$where[]  = "p.created BETWEEN '$args->fromdate' AND '$args->todate'";
				}else{
					if(is_string($args->fromdate)){
						$where[]  = "p.created >= '$args->fromdate'";
					}
					if(is_string($args->todate)){
						$where[]  = "p.created <= '$args->todate'";
					}
				}
			}




				
			if(! is_admin()){
				$where[] = 'p.active = 1';
			}





			if ($args->status) $where[] = 'p.status = ' . "'$args->status'";
			if (is_numeric($args->active)){



				if ($joined == 1){

					$where[] = 't.join_enable = ' . $args->active;
				}else{


					$where[] = 'p.active = ' . $args->active;

				}

			}










			if ($joined == 1){

					if ($args->cid > 0){
							$where[] = 't.cid = ' . $args->cid;
					}
					if (is_numeric($args->pid)){ // $pid!=null ||
							$where[] = 'c.pid = ' . $args->pid;
					}

					$cols[] = 't.rank';
					$cols[] = 't.join_enable, t.join_id, t.join_custom';
			} else{
				$cols[] = 'p.PopularityRank AS rank';


			}



			$args->keywords = trim($args->keywords);
			
			
			// **** LOOP KEYWORDS HERE
			
			if ($args->keywords != ''){
				if($args->lookin == 'td'){
					 $where[] = "(title LIKE \"%$args->keywords%\" OR description LIKE \"%$args->keywords%\")";
				}else if($args->lookin == 't'){
					 $where[] = "(title LIKE \"%$args->keywords%\")";

				}else if($args->lookin == 'd'){
					 $where[] = "(description LIKE \"%$args->keywords%\")";

				}else if($args->lookin == 'v'){
					 $where[] = "(vin LIKE \"%$args->keywords%\")";
				}else{
					 $where[] = "(title LIKE \"%$args->keywords%\" OR vin LIKE \"%$args->keywords%\" OR description LIKE \"%$args->keywords%\")";
				}
			}




			 // $where[] = "(STRCMP(LOWER(p.title), LOWER(p.feed_title)) = -1 AND $cbnk)";
			//  || $args->cc == 1
			// $where[] = "(STRCMP(LOWER(p.description), LOWER(p.feed_desc)) = 1 AND $cbnk)";



			// title and feedtitle dif

			if ($args->cc == 1){
				 // $where[] = "((LOWER(p.title) != LOWER(p.feed_title) OR LOWER(p.description) != LOWER(p.feed_desc))  AND $cbnk)";

				 $where[] = "((LOWER(p.title) != LOWER(p.feed_title) OR LOWER(p.description) != LOWER(p.feed_desc))  OR p.source = 'custom')";

			}


			if ($args->tc == 1){
				 $where[] = "(LOWER(p.title) != LOWER(p.feed_title))";
				 // $where[] = "(LOWER(p.title) != LOWER(p.feed_title) AND $cbnk)";
			}
			if ($args->dc == 1 ){
				$where[] = "(LOWER(p.description) != LOWER(p.feed_desc))";
				// $where[] = "(LOWER(p.description) != LOWER(p.feed_desc) AND $cbnk)";
			}


		if(is_admin()){



			// has_changed_t, has_changed_d


			$cols[] = 'STRCMP(LOWER(p.title), LOWER(p.feed_title)) as tc';
			$cols[] = 'STRCMP(LOWER(p.description), LOWER(p.feed_desc)) as dc';



		}

		$cols = trim(implode(', ',$cols));
		// abort($cols);


		$where = trim(implode(' AND ',$where));
		if(strlen($where)) $where = 'WHERE ' . $where;

		$out->sql = "
				SELECT SQL_CALC_FOUND_ROWS DISTINCT $cols
				FROM $table1 p
				LEFT JOIN $table2 t ON (p.lid = t.lid)
				LEFT JOIN $table3 c ON (t.cid = c.cid)
				$where
    		    ";

		// $out->sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT $cols FROM $table1 p LEFT JOIN $table2 t ON p.lid = t.lid $where ";


		$out->sql .= $pager->to_limits("");

		// abort($out->sql);


		if($datatype != ''){
			$out->result = $wpdb->get_results($out->sql,$datatype);
		}else{
			$out->result = $wpdb->get_results($out->sql);
		}

		
		
		
		
		
		$out->found = $wpdb->get_var("SELECT FOUND_ROWS()");

		$out->args = $args;

		$pager->set_total($out->found);

		// abort($out);

		$out->pager = &$pager;

		foreach($out->result as &$row){
			$row = new CBP_prod( $row );
		}

		$args->joined = $joined;


		return $out;

	}

	function simple_search($keywords,$limit=5){
		global $wpdb;
		$tbl = CBTB_PROD;
		$columns = 'lid,title,description,vin,source,slug,redirect_url';
		$where = array();
		$search = array();			
		$keywords = cbpressfn::keywords_to_array($keywords);
		foreach($keywords as $k => $v){
				$v = $wpdb->escape($v);
				$search[] = "(title LIKE \"%$v%\" OR vin LIKE \"%$v%\")";		
		}			
		if(sizeof($search)) $where[] = trim(implode(' OR ',$search));
		$where[] = "active = 1";
		$where = trim(implode(' AND ',$where));
		if(strlen($where)) $where = 'WHERE ' . $where;		
		$query = "SELECT $columns FROM $tbl $where ORDER BY rand() LIMIT " . $limit;
		$result = $wpdb->get_results($query);
		
		foreach($result as &$row){
			$row = new CBP_prod( $row );
		}

			
			
			
		return $result;
	}
}