<?php
if (!defined('ABSPATH')) die();




class CBP_shortcodes {


	var $show_mall = 0;
	var $has_mall = 0;
	var $modules = array(); // actives
	var $shortcodes = array('cbpress-list','cbpress-mall','cbpress-item','cbpress-cat','cbpress-find');








	function __construct() {
		// add_action('init', array (&$this, 'init') );
		foreach ($this->shortcodes as $m) {
			$f = str_replace( '-', '_', $m );
			add_shortcode( $m , array( &$this, $f ) );
		}
		add_shortcode( 'cbpress' , array( &$this, 'route_shorttag' ) );

		
		add_filter('cbpress-shortcode-defaults', array(&$this, 'shortcode_defaults'));

			

		if( !is_admin() ){
			add_filter( 'the_posts', array(&$this, 'loadResources'), 11 );
		}
		self::init();
	}
	function init() {


		// $currentTime = current_time('timestamp');
		// $date_format = 'D, d M Y H:i:s +0000'; // RSS
		// $updated = date(mysql2date($date_format, $currentTime, false));
		// echo $updated;
 












	}


	
	## returns the page id where the [cbpress] shortcut is located 

	function is_marketplace() {
		global $wp_query;
		if(is_singular()){
			if(isset($wp_query->queried_object)){
				$c = $wp_query->queried_object->post_content;
				$c = '...' . $c . '...'; // extra chars to make next line work
				if (strpos($c,'[cbpress]') || strpos($c,'[cbpress-mall]') > 0){
					return true;
				}
			}
		}
		return false;
	}

	
	
	function has_shortcode() {
		global $post;
		if ( !is_object($post) ) return false;
		if ( get_post_meta( $post->ID, '_cbpress_loaded', true ) ){
			return true;
		}else{
			return false;
		}
	}

	static function detect_mall(&$content) {		
		$found = (strpos($content,'[cbpress]') === false) ? '0' : '1';	
		if(!$found){
			if (strpos($content,'[cbpress]') === true){
				$found = 1;					
			}else{
				$found = (strpos($content,'[cbpress-mall') === false) ? '0' : '1';
			}
		}
		return $found;
	}
	static function getMallPageID() {
		$pages = get_pages();		
		foreach ($pages as $post) {			
			$found = self::detect_mall($post->post_content);			
			if ($found){ return $post->ID; }
		}
		return false;
	}
	static function getMallPageIDs() {
		$pages = get_pages();		
		$out = array();
		foreach ($pages as $post) {
			$found = self::detect_mall($post->post_content);
			if ($found){ $out[$post->ID] = 1; }
		}
		return $out;
	}

	function set_wp_title($title) {
		global $wp_query;
		$title = esc_attr($title);
		if(count($wp_query->posts)){
			$wp_query->queried_object->post_title .= ' : ' . $title;
			$wp_query->posts[0]->post_title = $title;
		}
	}




	function set_category_title() {
			global $cbpress;
			
			if($this->is_marketplace()) {
				global $wp_query;
				$cbq = CBP::getv('cbq','');
				if(strlen(trim($cbq)) > 0){
					$this->set_wp_title('Search Results');
				} else {
					$cid = intval(cbpressfn::getparam('cid'));
					
					$okay = 0;
					if($cid > 0){
						$okay = 1;
					} else {
						
						$root = intval($cbpress->options->cat_root);
						$cid = ($root > 0) ? $root : 0;

						$okay = ($cid > 0) ? 1 : 0;
					}
					
					if($okay > 0){
						$cat = CBP_cats::getsub($cid);
						
						
						// abort($cat);
						
						if($cat){
							$this->set_wp_title($cat->name);
						}
					}
				}
			}
	}
	function loadResources($posts) {

			$this->set_category_title();



			return $posts;
	}

	public function print_styles(){ // (2) This loads after loadResources
			wp_enqueue_style('cbpress-frontend');
	}

	public function print_scripts(){ // (3) This loads after loadResources
		global $cbpress;
		$cbpress->jquery_reset();



	}





	function toggle_cc() {

			global $cbpress;

			$cbpress->options->cc = $cbpress->options->show_cc;


	}





	function route_shorttag($atts) {

			global $post;








			$def = shortcode_atts(array(
				'root'  	=> null, // numeric id
				'product'  	=> null, // numeric id
				'vendor'  	=> null, // clickbank id
				'category' 	=> null, // numeric id
				'list'   	=> null, // numeric id
				'keywords'   	=> null, // keyword search attribute
				'sort' 		=> null, // sort column
				'display' 	=> null,  // max products
				'showdesc' 	=> 1  // max products
			), $atts);

			$def = (object) $def;
			
			// abort($def);

			$sd = " showdesc=\"{$def->showdesc}\"";
			
			if($def->product != ''){				
				$sc = "-item id=\"{$def->product}\"";


			} else if ($def->keywords != ''){
				$sc = "-find keywords=\"{$def->keywords}\"";


			} else if ($def->vendor != ''){
				$sc = "-item vendor=\"{$def->vendor}\"";
			} else if ($def->list != ''){
				$sc = "-list id=\"{$def->list}\" sort=\"{$def->sort}\"";
			} else if ($def->category != ''){
				$sc = "-cat id=\"{$def->category}\" sort=\"{$def->sort}\" display=\"{$def->display}\"";
			} else if ($def->root != ''){
				$sc = "-mall root=\"{$def->root}\"";
			}else{
				$sc = '-mall';
			}







			$sc = '[cbpress' . $sc . $sd . ']';

			$content = do_shortcode($sc);
			return $content;

	}






	function bulleted($value){
			return '<li>' . $value . '</li>';

	}




	public function cbpress_find($atts) {

		$def = shortcode_atts(array(
			'keywords' 	=> '',
			'display' 	=> 10,  // max products
			'sort' 		=> 'rank', // sort column
			'order' 	=> 'asc',  // max products
			'showdesc' 	=> 1
		), $atts);


		$data 	= CBP_query::getSearch($def);
		$items 	= &$data->result;

		$showdesc = $def['showdesc'];

		$out = Cbpress::render_items_array($items,$showdesc);
		$out = implode('', $out);

		echo $out;
	}







	public function cbpress_cat($atts) {


		global $post, $cbpress;
		$opts = &$cbpress->options;




		$opts = &$cbpress->options;







		$def = shortcode_atts(array(
				'id' 		=> 0, // numeric id
				'display' 	=> 10,  // max products
				'sort' 		=> 'rank', // sort column
				'order' 	=> 'asc',  // max products
				'showdesc' 	=> 1
		), $atts);
		
		$def['cid'] = $def['id'];




		
		unset($def['id']);
		
		
		// echo '<H1>'.  __FUNCTION__ .'</h1>';
		
		$def['active'] = 1;

		if( $def['display'] == null) $def['display'] =  10;
		if(!is_numeric($def['display'])) $def['display'] = 10;

		$def['limit'] = $def['display'];

		$data 		= CBP_query::getSearch($def);
		$items 		= &$data->result;

		// abort($data);


		$showdesc = 1;
		$html = '';
		$numitems = count($items);

		// Product List
		$output = '';

		
		if(1 == 1){
		
			$showdesc = $def['showdesc'];
			$out = '';
			$out .= '<div class="cbpresscat">';
			$cat = CBP_cats::getsub($def['cid']);
			if($cat) $out .= "<h2>".$cat->name."</h2>";
			$out .= "<ul>";
			foreach ($items as $item) {			
				$item->output($showdesc,false);				
				$out .= '<li>' . $item->formats->link;
				$out .= ($showdesc) ? $item->formats->desc : '';
			}
			$out .= "</ul>";			
			$out .= '</div>';
			$output = $out;
			unset($out);
		}else{
			
			$out = Cbpress::render_items_array($items,$def['showdesc']);
		
			
			$output .= '<div class="cbpresscat">';
			if(! $def['showdesc']){
				$out = array_map(array(&$this,'bulleted') ,$out);
				$output .= '<ul>' . implode('', $out) . '</ul>';
			}else{
				$output .= implode('', $out);
			}
			$output .= '</div>';
			
			
		}
		
		
		
		
		
		
		
		

		return $output;
	}




	function cbpress_mall( $atts ) {
		global $cbpress;
		global $post;

		// echo '<H1>'.  __FUNCTION__ .'</h1>';

		$output = '';
		if( $post->post_type == 'page' || 1 == 2 ){
			$output = $this->render_mall($atts);
		} else if (current_user_can('edit_posts')){
				$output .= '<p class="cbpress-alertbox">';
				$output .= 'Oops, please put the [cbpress] shortcode in a wordpress <em>page</em>.';
				$output .= '</p>';	
		}
		return $output;
	}

	function shortcode_defaults( $defaults ) {		
		$def = array(
				'sort' 	=> '',
				'order' 	=> '',
				'showdesc' => 1
		);		
		return $def + $defaults;
	}
	
	
	function cbpress_list( $attr ) {

		// echo '<H1>'.  __FUNCTION__ .'</h1>';
		global $cbpress;

		$defaults = array('id' => '0', 'tid'=>''); ## defaults specific to this shortcode		
		$defaults = apply_filters( 'cbpress-shortcode-defaults', $defaults ); ## global defaults
		$attr = shortcode_atts( $defaults, $attr );
		
				

		extract( $attr );

				

					$id = intval( $id );
		$out = Cbpress::render_list($id,$sort,$order,$showdesc);


		if($out->html){}
		
		// abort( $out );
		
		$out->html = '<div class="cbpresslist">'.$out->html.'</div>';
		return $out->html;
	}


































	public function cbpress_item( $atts ) {

		// echo '<H1>'.  __FUNCTION__ .'</h1>';
		$def = shortcode_atts(array(
				'id' 		=> '', // numeric id
				'vendor' 	=> '',  // max products
				'showdesc' 	=> 1
		), $atts);
		extract($def);
		
		if($id > 0){
			$lid = $id;
		}else{
			if($vendor == '') return '';
			$lid = CBP_query::hoptolid($vendor);
		}

		$output = '';

		$item  = CBP_prod::load($lid);

		if($item->lid){
			$output = Cbpress::render_item($item,$showdesc);
			$output = '<div class="cbpressitem">'.$output.'</div>';
		}

		return $output;

	}



	public function is_root_category($id) {
			global $cbpress;
			return $id == intval($cbpress->options->cat_root);
	}


	public function get_featured() {
		
		global $cbpress;
		$opts = &$cbpress->options;

		
		
		
					
					
					
					
					
		
		$cid = intval(Cbpress::get('cat_feat'));
		
		$defaults = array(
				'cid' => $cid,				
				'active' => 1,
				'limit' => 10,
				'perpage' => null,
				'sort'=> $opts->sort,
				'order'=> $opts->order,
				'billing'=> $opts->billing
		);
		
		$showdesc = intval(Cbpress::get('mk_showdesc'));
		$showdesc = 0;
		
		$data 	= CBP_query::getSearch($defaults);
		
		$found 	= $data->found;
		if($found){			
			$items 	= &$data->result;
			$out = '';
			$out .= '<div class="cbpressfeatured">';
			$out .= "<h3>Featured</h3>";
			$out .= "<ul>";
			foreach ($items as $item) {
				$item->output($showdesc,false);				
				$out .= '<li>' . $item->formats->link;
				$out .= ($showdesc) ? $item->formats->desc : '';		
			}
			$out .= "</ul>";
					
			## more link to category
			$thisurl = $_SERVER['REQUEST_URI']; 			
			$thisurl = remove_query_arg( array('cbpg'), $thisurl );	
			$link = add_query_arg('cid',$cid,remove_query_arg( 'cid', $thisurl ));
			$out .= "<a href=\"$link\">more...</a>";
							

					
			$out .= '</div>';
			// $out = implode('', $out);
		}
		
				
				
		return $out;
		
		
			
			
			
		
	}
	public function get_ranges() {
		global $cbpress;
		$params = array();
		$ranges = explode(',','min_rank,max_rank,min_gravity,max_gravity,min_commission,max_commission,min_referred,max_referred');
		foreach ($ranges as $c) {
			$params[$c] = $cbpress->options->$c;	
		}
		return $params;
	}
	
	
	
	public function render_mall($atts) {

		// if($this->has_mall) return '';

		global $post, $cbpress;
		$opts = &$cbpress->options;

		extract(shortcode_atts(array('root'=>'','category'=>false, 'tag'=>false, 'cid'=>false), $atts));

		$req = $cbpress->options->get_requested();
		

		
		$customRoot = ($root > 0) ? '1' : '0';
		$root = ($root > 0) ? $root : $req->root_id;
		
		
		// dump(get_defined_vars());
		
		$this->has_mall = 1;

		// $rootCat = &$req->root_cat;
		$thisCat = &$req->curr_cat;

		$local = new stdClass();
		$local->baselink = get_permalink($post->ID);
		$local->root_id = $root;
		$local->feat_id = $req->feat_id;
		
		
		if($customRoot > 0){
			
			$local->feat_id = '0';
			
			
		}
		$local->selected = intval(cbpressfn::igetparam('cid',$local->root_id));
		$local->path = CBP_cats::climb($local->selected);
		
		$local->topid = $root;
		
		// $local->topid = $rootCat->cid;

		
		
		$curr = $local->selected;
		$root = $local->root_id;
		$feat = $local->feat_id;
		
		if($curr == $root && $curr > 0){
			$curr = 0;
		}
		
		
		
		$local->crumbs = array();

		$i = 0;
		$allowed = array_reverse($local->path);
		foreach($allowed as $id){
			$i++;
			$link = add_query_arg('cid',$id, remove_query_arg( 'cid', $local->baselink ));
			$cat = CBP_cats::getsub($id);
			if($i == 1){
				$local->crumbs[] = '<span style="font-weight:bold;">' . $cat->name . '</span>';
			}else{
				$local->crumbs[] = cbpressfn::html_link($link, $cat->name);
			}
			if($id == $local->topid){ break; }
		}
		if($local->topid == 0){
			$local->crumbs[] = cbpressfn::html_link($local->baselink, 'Marketplace');
		}
		$local->crumbs = array_reverse($local->crumbs);
		$local->crumbs = implode(' ', $local->crumbs);


		// abort(get_defined_vars());

		// dump($local);
		// abort($thisCat);
		// search widget

		$cbq = CBP::getv('cbq','');

		$searching = 0;
		$label = "";
		$showdesc = intval(Cbpress::get('mk_showdesc'));
		$isroot = $this->is_root_category($curr);	
		$output = '';

		$noshow = 0;

		## sql query params







		$defaults = array(
			'active' => 1,
			'limit' => null,
			'lookin' => '',
			'cc' => $opts->show_cc,
			'perpage' => $opts->perpage,
			'sort'=> $opts->sort,
			'order'=> $opts->order,
			'billing'=> $opts->billing
		);

		if(strlen(trim($cbq)) > 0){
			$searching = 1;
			unset($defaults['sort'],$defaults['order']);
			$defaults['keywords'] = $cbq;
			$defaults = $defaults + self::get_ranges();
			$label = "Search Results";
			
		/*********
		} else if ($root == 0 && $feat > 0 && $curr == 0){
			
			
			$defaults['cid'] = $feat;
			$defaults['perpage'] = 10;
			$label = "Featured Products";
		***********/
			
			
			
		} else if ($curr == 0 && $root == 0){


			$defaults['pid'] = 0;
			$defaults['min_rank'] = 1;
			$defaults['max_rank'] = 1;
			$defaults['limit'] = 10;
			$defaults['perpage'] = 10;

			$label = "Most Popular";
			// $noshow = 1;


			$label = "";
			// $found = 0;




		}else{
			$defaults = $defaults + self::get_ranges();
			if( ($curr == 0 || $curr == $root) && $root > 0){
		 		$defaults['cid'] = $root;
				if($customRoot == 0){ 
					$label = "Results";
				}
			}
			
			/*********
			if($root == 0 && ($curr == $root && $feat > 0)){
		 		$defaults['cid'] = $feat;
				$label = "Featured Products";
			}
			*******/
		}
			
			
		// dump($defaults);
			
		$data 	= CBP_query::getSearch($defaults);
		
		
		
		
		
		
			// abort($data);
		
		
		$items 	= &$data->result;
		$found 	= count($items);
		$found 	= $data->found;
		
		$pagenav 	= &$data->pager->page_links_frontend();
		$show_nextn = strlen(trim($pagenav->links));
		$pagelinks =  '<div id="pager" class="pagination"><div class="tablenav-pages">' . $pagenav->html . '</div></div>';
		$pagelinks =  $pagenav->html;
		if(!$show_nextn || $defaults['limit']){
			$pagelinks =  '';
		}

		## crumbs
		$hasSubCats = 1;
		if($root > 0){
			$hasSubCats = 0;
			$row = CBP_cats::getsub($root);
			if($row->subs){
				$hasSubCats = count($row->subs);
			}
		}
		
		$showCrumbs = ($curr > 0) ? 1 : 0;
		
		if(($customRoot > 0 && ! $hasSubCats)){
			$showCrumbs = 0;
			
				// echo count($row->subs);
		}
		if($req->cid == 0 && $root > 0){
			$showCrumbs = 0;
				
				
		}
		if($showCrumbs){ $output .= '<div class="cbp_breadcrumbs">' . $local->crumbs . '</div>'; }

		## pagemsg
		//////// if($isroot){ $output .= '<p>' . $opts->pagemsg . '</p>'; }

		## catbox
		if($cbpress->options->mk_catbox > 0 || $searching == 0){
			$output .= $this->draw_categories($curr,$root);
		}

		$output .= $pagelinks;


		// Product List

		if($label != ''){
			$output .= '<h4>' . number_format($found) . ' ' . $label . '' . '</h4>';
		}
		$output .= '<div class="cbpressmall">';
		if($found == 0){
			if($searching == 0){
				if(!$isroot){
					$output .= 'This category does not contain any products';
				} elseif($isroot && $root > 0){
					$output .= 'This category does not contain any products';
				}
			}else{
				$output .= '<h4>Your search did not return any results</h4>';
			}
		}else{
				// if($noshow == 1)







				$out = Cbpress::render_items_array($items,$showdesc);


			$out = implode('', $out);			
			$out = $out;
			
			$output .= $out;
			
		}




		$output .= '</div>';
		
		$featured = '';
		if($feat > 0){
			$featured = $this->get_featured();
		}
		$output = '<div id="mall">' . $output . $pagelinks . $featured . '</div>';
		
		if($opts->mk_backlink){			
			$output .= CBP_api::backlink();			
		}

			
		return $output;
	}







	function draw_categories($cid=0,$root=0) {

		global $cbpress;

		if($root > 0){
			$root_cid = $root;
		}else{
			
			$root_cid = intval($cbpress->options->cat_root);
			$root_cid = ($root_cid == '') ? 0 : $root_cid;
		}

		$req = $cbpress->options->get_requested();
		



		$cc = $cbpress->options->show_cc;


		$cust_cids = CBP_cats::get_cids_for_custom();


		// $x = 17;
		// if(in_array($x,$cust_cids)){
		// dump($cust_cids);
		// $thename = CBP_cats::climb($x, 'name');









		// $cid = $req->cid;

		$cid = ( $cid > 0 ) ? $cid : $root_cid;
		
		$html = '';
		$thisurl = $_SERVER['REQUEST_URI']; 

			// $url =  remove_query_arg(array('cid','pid'));
			
		$thisurl = remove_query_arg( array('cbpg'), $thisurl );
		
		
		
		$out = array();
		foreach(CBP_cats::subs($cid) as $id) {

			if(CBP_cats::enabled($id)){
					$link = add_query_arg('cid',$id,remove_query_arg( 'cid', $thisurl ));




					if($cc == 0 || ($cc == 1 && in_array($id,$cust_cids)) ){ 

						$row = CBP_cats::getsub($id);

						$out[] = "<a href=\"$link\">" . $row->name . "</a>";



					}



			}
		}
		if($out){
			$html .= '<div class="cbp_catbox">';
					if($req->cat_label != '') $html .= '<div class="catboxlabel">'.$req->cat_label.'</div>';


					$out = array_map(array(&$this,'bulleted') ,$out);

					$html .= '<ul class="cbpressColumn">' . implode('', $out) . '</ul>';

			$html .= '</div>';
		}
		unset($out);
		return $html;
	}







}
