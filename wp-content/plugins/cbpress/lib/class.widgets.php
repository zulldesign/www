<?php
if (!defined('ABSPATH')) die();




class cbpress_search_widget extends WP_Widget {



	function cbpress_search_widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'cbpress_search_widget', 'description' => __( 'Displays clickbank marketplace search box', CBPRESS_TRANS ) );
		/* Widget control settings. */
		$control_ops = array( 'id_base' => 'cbpress_search_widget', 'width' => 525, 'height' => 350 );
		/* Create the widget. */
		$this->WP_Widget( 'cbpress_search_widget', __( 'Cbpress Search', CBPRESS_TRANS ), $widget_ops, $control_ops );
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		$theme_search = isset( $instance['theme_search'] ) ? $instance['theme_search'] : false;

		echo $before_widget;

		/* If there is a title given, add it along with the $before_title and $after_title variables. */

		$link = CBP::getbaselink();

		if ( $instance['title'] ) echo $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;

		if ( $theme_search ) {
			get_search_form();

		} else {
			global $search_form_num;

			$search_num = ( ( $search_form_num ) ? "-{$search_form_num}" : '' );
			$search_text = ( ( is_search() ) ? esc_attr( get_search_query() ) : esc_attr( $instance['search_text'] ) );

			$search = '<form method="get" class="search-form" id="search-form' . $search_num . '" action="' . $link . '"><div>';

			if ( $instance['search_label'] ) $search .= '<label for="search-text' . $search_num . '">' . $instance['search_label'] . '</label>';

			$search .= '<input class="search-text" type="text" name="cbq" id="search-text' . $search_num . '" value="' . esc_attr( $search_text ) . '" onfocus="if(this.value==this.defaultValue)this.value=\'\';" onblur="if(this.value==\'\')this.value=this.defaultValue;" />';

			if ( $instance['search_submit'] ) $search .= '<input class="search-submit button" name="submit" type="submit" id="search-submit' . $search_num . '" value="' . esc_attr( $instance['search_submit'] ) . '" />';

			$search .= '</div></form><!-- .search-form -->';

			echo $search;

			$search_form_num++;
		}

		echo $after_widget;
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['search_label'] = strip_tags( $new_instance['search_label'] );
		$instance['search_text'] = strip_tags( $new_instance['search_text'] );
		$instance['search_submit'] = strip_tags( $new_instance['search_submit'] );
		$instance['theme_search'] = ( isset( $new_instance['theme_search'] ) ? 1 : 0 );

		CBP::flush_cache();

		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 * @since 0.6
	 */
	function form( $instance ) {

		//Defaults
		$defaults = array( 'title' => __( 'Search', CBPRESS_TRANS ), 
		'theme_search' => false, 'search_label'=>'', 'search_text'=>'' ,'search_submit'=>'' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<div class="hybrid-widget-controls columns-2">
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _cbx( 'Title'); ?>:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'search_label' ); ?>"><?php _cbx( 'Search Label'); ?>:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'search_label' ); ?>" name="<?php echo $this->get_field_name( 'search_label' ); ?>" value="<?php echo $instance['search_label']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'search_text' ); ?>"><?php _cbx( 'Search Text'); ?>:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'search_text' ); ?>" name="<?php echo $this->get_field_name( 'search_text' ); ?>" value="<?php echo $instance['search_text']; ?>" />
		</p>
		</div>

		<div class="hybrid-widget-controls columns-2 column-last">
		<p>
			<label for="<?php echo $this->get_field_id( 'search_submit' ); ?>"><?php _cbx( 'Search Submit'); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'search_submit' ); ?>" name="<?php echo $this->get_field_name( 'search_submit' ); ?>" value="<?php echo $instance['search_submit']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'theme_search' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['theme_search'], true ); ?> id="<?php echo $this->get_field_id( 'theme_search' ); ?>" name="<?php echo $this->get_field_name( 'theme_search' ); ?>" /> <?php _cbx( 'Use theme\'s <code>searchform.php</code>?'); ?></label>
		</p>
		</div>
		<div style="clear:both;">&nbsp;</div>
	<?php
	}
}


	


class cbpress_category_widget extends WP_Widget {

	var $expand;
	var $baselink;
	var $selected;
	var $stcats;
	var $okcats;
	var $defaults;
	var $path = array();

	function cbpress_category_widget() {
		global $cbpress;
		$this->defaults = array(
			'title' => 'Marketplace Categories',
			'style' => '1',
			'pageid' => $cbpress->options->pageid,
			'mkonly' => '0'
		);
		$widget_ops = array( 'classname' => __FUNCTION__, 'description' => 'Marketplace Category Navigation' );

		$control_ops = array('id_base'=>__FUNCTION__);

		$this->WP_Widget( __FUNCTION__, 'Cbpress Category Nav', $widget_ops, $control_ops );

		// add_action('wp_print_scripts', array(&$this,'add_post_scripts'),1000);




	}
	function add_post_scripts() {
	}

	function listify( $subs ) {

		$output = '';
		foreach($subs as $cid) {
			$cat = $this->stcats->category[$cid];
			if($cat->enabled){
				$link = $this->baselink . 'cid=' . $cid;
				$attribs = ($this->selected == $cid) ? ' class="on"' : '';
				$output .= "<li$attribs>";
				$output .=  "<a id='cat_$cid' href='$link'>{$cat->name}</a>";
				$output .=  "</li>";
			}
		}
		return $output;
	}





	function inpath( $id ) {
		return in_array($id, $this->path);
	}

	

	function maketree($cid=0, $level=null, $maxlevel=null) {

		static $tree = array();
		if($level === null){
				$rootcid = $cid;
				$cat = CBP_cats::getsub($rootcid);
				if($cat){
					$tree[$cat->cid] = $cat;
				}
				foreach (CBP_cats::subs($rootcid) as $id) {
					$this->maketree($id, 1,$maxlevel);
				}
				return $tree;
		} else {
			$cat = CBP_cats::getsub($cid);
			if($cat){
				$tree[$cat->cid] = $cat;
				$currlevel = $level+1;
				// $ok = (is_numeric($maxlevel) && $currlevel > $maxlevel) ? false : true;
				$ok = ($this->inpath($cat->cid));
				$children = CBP_cats::subs($cid);
				if ($ok && $children){
						foreach($children as $id) {
							$this->maketree($id, $currlevel, $maxlevel);
						}
				}
			}
		}
	}

	function can_show_cat($cid) {

		// determines if cat has custom edited products and can be shown

		global $cbpress;
		$cc = $cbpress->options->show_cc;

		if($cc == 0 || ($cc == 1 && in_array($cid,$this->okcats)) ){ 
			return true;
		}
		return false;
	}


	function build_tree($node, $level=0) {

		global $cbpress;



			static $space = ' &nbsp  &nbsp ';
			static $recursions = 0;

			$recursions++;
			if($recursions > 20000) return false;

			$output =  "<ul>";
			foreach($node as $cid) {
				$cat = CBP_cats::getsub($cid);
				if($cat){
					if($cat->enabled){
						$children = CBP_cats::subs($cid);
						$link = add_query_arg('cid',$cid, remove_query_arg( 'cid', $this->baselink ));
						$attribs = ($this->selected == $cid) ? ' class="currcat"' : '';
						$marker  = ($this->selected == $cid) ? '' : '';




						if($this->can_show_cat($cid)){


								$output .= "<li>";
		
								// $output .= str_repeat('<span class="gi">|&mdash;</span>', $level);
								$dash = ($level > 0) ? str_repeat('&mdash; ', $level) : '';
								if($dash != '' && 1 == 2){ 
									$dash = trim($dash) . ' ';
								}
								$dash = '';

		


								$output .=  "<a id='cat_$cid' href='$link'$attribs>$dash{$cat->name}</a> $marker";



								$ok = ($this->inpath($cat->cid));
								if($ok){
									if ($children){ $output .=  $this->build_tree($children, $level+1); }
								}
								$output .=  "</li>";

						}
					}
				}
			}
			$output .=  "</ul>";
			return $output;
	}





	function widget( $args, $instance ) {


		
		$instance = wp_parse_args($instance, $this->defaults);
		extract($instance);		
		
		
		// $args = wp_parse_args( $args, $this->defaults );
		extract($args, EXTR_SKIP);
		
		
		// do_shortcode('[rss feed="http://feeds.feedburner.com/cbpress" num="5"]');

		/****** *****/
		if($mkonly){
			// abort(get_defined_vars());
			// echo $mkonly;
			global $post;
			$ids = CBP_shortcodes::getMallPageIDs();
			if(!isset($ids[$post->ID])) return '';		
		}
		
		global $cbpress;

		$instance = wp_parse_args((array)$instance, $this->defaults);
		extract($instance);

		$index_cid = intval($cbpress->options->cat_feat);
		$root_cid = intval($cbpress->options->cat_root);
		$root_cid = ($root_cid == '') ? 0 : $root_cid;

		$stcats = CBP_cats::getCache();
		$okcats = CBP_cats::get_cids_for_custom();
		
		$this->baselink = get_permalink($pageid);
		
		
		

		$this->stcats = &$stcats;
		$this->okcats = &$okcats;

		$this->selected = cbpressfn::igetparam('cid',$root_cid);
		$this->expand = $this->selected;
		$req = $cbpress->options->get_requested();
		$root_cat = $req->root_cat;

		$this->path = CBP_cats::climb($this->selected);


		
		
		
		// current_user_can('administrator')



		if($this->selected > 0 && isset($stcats->category[$this->selected])){
				$cat = $stcats->category[$this->selected];
				if($cat->pid > 0) $this->expand = $cat->pid;
		}
		$title = $instance['title'];

		$subs = $stcats->subs;


		// dump(get_defined_vars());
		
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $title, $instance, $this->id_base);
		echo $before_widget;
		echo $before_title.$title.$after_title;


		$output = '';
		$style = 'drilldown';

		switch($style){


			case 'drilldown':
					$output .= '<div class="cbpresscats">';
					if($root_cid > 0){

						$link = $this->baselink;
						$link = add_query_arg('cid',$root_cat->cid, remove_query_arg( 'cid', $link ));
						$output .= '<ul>';
						$output .= '<li>';
						$output .= "<a id='cat_{$root_cat->cid}' href='$link'>{$root_cat->name}</a>";
						$output .=  $this->build_tree(CBP_cats::subs($root_cid));
						$output .= '</li>';
						$output .= '</ul>';
					}else{
						$output .=  $this->build_tree(CBP_cats::subs(0));
					}
				$output .=  "</div>";
				break;
			case 'expanding':
				$subtree = CBP_cats::get_flat_branch($this->selected,null);
				foreach ( $subtree AS $cat ) {
					if($cat->enabled){
						$cid = $cat->cid;
						$link = add_query_arg('cid',$cid, remove_query_arg( 'cid', $this->baselink ));
						$attribs = ($this->selected == $cid) ? ' class="currcat"' : '';
						$marker  = ($this->selected == $cid) ? '' : '';
						$ok = ($this->inpath($cid));
						$output .= "<li>";
						if($ok){
							$output .= " *** ";
						}
						$output .=  "<a id='cat_$cid' href='$link'$attribs>{$cat->name}</a> $marker";
						$output .=  "</li>";
					}
				}
				break;
			case 'toponly':
				$output .= '<ul class="cbpresscats">';
				foreach($subs as $cid) {
					$top = $stcats->category[$cid];
					if($top->enabled){
						$link = add_query_arg('cid',$cid, remove_query_arg( 'cid', $this->baselink ));
						$attribs = ($this->expand == $cid) ? ' class="on"' : '';
						$output .= "<li$attribs><a id='cat_$cid' href='$link'>{$top->name}</a></li>";
					}
				}
				$output .= '</ul>';
				break;
		}
		echo $output;
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {	
		$instance = $old_instance;		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['pageid'] = $new_instance['pageid'];
		$instance['mkonly'] = isset( $new_instance['mkonly'] ) ? '1': '0';
		CBP::flush_cache();
		return $instance;
	}

	function form( $instance = '') {
			$instance = wp_parse_args( $instance, $this->defaults );
			extract($instance);
			
			$linker = '<a href="%s" class="fix">Create one for me</a>';
			$u = admin_url( 'admin.php?action=cbp-create-page' );
			$nopage   = 'Cbpress cannot find a <b class="nowrap">[cbpress]</b> shortcode in any of your WordPress pages.';
			if (! CBP_shortcodes::getMallPageID() ){ 
				echo sprintf($nopage . $linker, $u);
			}
		?>
		<div class="cbpress_widget">
		
		
			<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Widget Title', CBPRESS_TRANS ); ?>:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
		
		
			<p>
			<label for="<?php echo $this->get_field_id('pageid'); ?>"><?php _e('Marketplace Page', CBPRESS_TRANS ); ?>:</label><br/>
			<?php wp_dropdown_pages("name=".$this->get_field_name('pageid')."&show_option_none=".__('- Select -')."&selected=" .$pageid); ?>
			<div><small>WordPress page with the <b>[cbpress]</b> shortcode</p></small></div>
			</p>
			<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['mkonly'], true ); ?> value="1" id="<?php echo $this->get_field_id( 'mkonly' ); ?>" name="<?php echo $this->get_field_name( 'mkonly' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'mkonly' ); ?>"><?php _e( 'Only Show on Marketplace Pages', CBPRESS_TRANS ); ?></label>
			</p>
		</div>
		<?php
	}
}









class cbpress_context_widget extends WP_widget {

	function cbpress_context_widget () {

		
		$this->defaults = array(
			'title' => 'Related Products',
			'showdesc' => '1',
		);



		$widget_ops = array( 'classname' => __FUNCTION__, 'description' => 'Displays contextual based ClickBank products based on page content' );

		$control_ops = array('id_base'=>__FUNCTION__);

		$this->WP_Widget( __FUNCTION__, 'Cbpress Context Product', $widget_ops, $control_ops );

		// add_action('wp_print_scripts', array(&$this,'add_post_scripts'),1000);

	}


	static function findAllSorted(){

			// $rows = 

			$tree=array();
			$temp=array();
			foreach($rows as $row){

				if($row->pid){

					if(array_key_exists($row->pid, $tree)){

						$tree[$row->pid][$row->cid] = array($row);

						$temp[$row->cid] = &$tree[$row->pid][$row->cid];

					}else if(array_key_exists($row->pid, $temp)){

						$temp[$row->pid][$row->cid] = array($row);
						$temp[$row->cid] = &$temp[$row->pid][$row->cid];			
					}

				} else{
					$tree[$row->cid] = array($row);
					$temp[$row->cid] = &$tree[$row->cid];
				}
			}		
			return $tree;
	}

	function widget($args, $instance) {

		extract($args);
		global $cbpress;

		$instance = wp_parse_args((array)$instance, $this->defaults );
		extract($instance);


		$out = '';




			// get words from url or referrer
			// $words = cbpressfn::extractSearchwords(); 
 			// $words = explode(',',$words);

		$onpage = $cbpress->template_get_content();
		$words = (count($onpage) > 0) ? implode(',',$onpage) : '';

		// dump($words);

		if($words !== ''){

			$items = CBP_query::simple_search($words,2);




			$out .= '<ul>';
			foreach ($items as $item) {
				$out .= $item->output($showdesc,false);
			}
			$out .= '</ul>';






			/**********
			foreach ($items as $item) {
				$out .= '<div class="cbpress item">';
					$out .= $item->output($showdesc,false);
				$out .= '</div>';
			}
			********/


			$out = '<div class="cbpresslist">'.$out.'</div>';


			$title = apply_filters('widget_title', $instance['title']);
			echo $before_widget;
			if($title) echo $before_title . $title . $after_title;
			echo $out;
			echo $after_widget;
		}
	}


	function printTree($current,$notFirst=false,$node){
		if($node){

			if($notFirst) echo "<ul>\n";
			foreach($node as $id=>$children){
				$parent=$children[0];
				unset($children[0]);
				?>
				<li class="<?php echo sizeof($children)?'parent-category':''; echo $current == $parent->slug()?' viewing-category':''?>"><?php
				echo 'link here';
				$this->printTree($current,true,$children);
				echo "</li>\n";
			}
			if($notFirst) echo "</ul>\n";
		}
	}

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['showdesc'] = isset( $new_instance['showdesc'] ) ? true : false;
		return $instance;
	}





	function form($instance) {
		// $def = array(
		// 	'title' => 'Related Products',
		// );
		// $instance = wp_parse_args( (array) $instance, $def);
		// extract($instance);



		$instance = wp_parse_args( $instance, $this->defaults );
		extract($instance);

		$f = (object) array();
		foreach ($this->defaults as $k =>$v) {
			$f->$k = (object) array('id'=>$this->get_field_id($k), 'name'=>$this->get_field_name($k));
		}

		?>
			<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">
				<?php _e('Title:'); ?>
				<input 	class="widefat" 
				id="<?php echo $this->get_field_id('title'); ?>" 
				name="<?php echo $this->get_field_name('title'); ?>" 
				type="text" 
				value="<?php echo esc_attr($title); ?>" />
			</label>
			</p>
		<?php
			## showdesc
			echo '<br>';
			$sel = checked( $instance['showdesc'], true, false );
			echo "<input class=\"checkbox\" type=\"checkbox\" id=\"{$f->showdesc->id}\"  name=\"{$f->showdesc->name}\" {$sel} />";
			echo "<label for=\"{$f->showdesc->id}\">" . _e('Show Descriptions',$c) . "</label>";

	}

}






class cbpress_list_widget extends WP_Widget {

	var $prefix;
	var $defaults;
	function cbpress_list_widget() {
		
		
		$this->defaults = array(
			'title' => '',
			'list_id' => '0',
			'showdesc' => '1',
			'sort' 	=> '',
			'order' => '',
			'tid' => ''
		);
		
		$widget_ops = array( 'classname' => __FUNCTION__, 'description' => 'Displays a list of products from one of your custom lists' );

		$control_ops = array( 'id_base' => __FUNCTION__, 'width' => 425, 'height' => 350 );
		

		$this->WP_Widget( __FUNCTION__, 'Cbpress Custom List', $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {

		extract($args);
		global $cbpress;
		global $blog_id;


		$instance = wp_parse_args((array)$instance, $this->defaults );
		extract($instance);

		$output = '';
		
		wp_print_styles( 'cbpress-list' );

		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$output .= $before_widget;

		$out = Cbpress::render_list($list_id,$sort,$order,$showdesc);




			// $out .= '<ul>';
			// foreach ($items as $item) {
			// 	$out .= $item->output($showdesc,false);
			// }
			// $out .= '</ul>';



		$out->html = '<ul>'.$out->html.'</ul>';


		// $out->html = '<div class="cbpresslist">'.$out->html.'</div>';

		$output .= $before_title.$title.$after_title;
		$output .= $out->html;
		$output .= $after_widget;
		
		echo $output;
		
		// return $output;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['list_id'] = $new_instance['list_id'];
		$instance['tid'] = strip_tags($new_instance['tid']);
		$instance['sort'] = $new_instance['sort'];
		$instance['order'] = $new_instance['order'];
		$instance['showdesc'] = isset( $new_instance['showdesc'] ) ? true : false;
		CBP::flush_cache();
		return $instance;
	}


	function form( $instance = '') {



		$c = CBPRESS_TRANS;	
		$instance = wp_parse_args( $instance, $this->defaults );
		$title = strip_tags($instance['title']);
		$tid = strip_tags($instance['tid']);


		$lists = CBP_lists::get_for_select();
		$sortopts = CBP_meta::getSortByList();
			
		$f = (object) array();
		foreach ($this->defaults as $k =>$v) {
			$f->$k = (object) array('id'=>$this->get_field_id($k), 'name'=>$this->get_field_name($k));
		}
	

		echo '<div class="cbpress_widget">';

			## title
				echo "<label for=\"{$f->title->id}\">" . _e('Title Above Products',$c) . "</label>";
				echo "<input id=\"{$f->title->id}\" name=\"{$f->title->name}\" type=\"text\" value=\"$title\" />";



				// echo '<p>';
					// _e('Choose a custom product list to display in this widget.', $c );
					// echo CBP::link(CBP::admin('lists'),'List Manager');
				// echo '</p>';


			## list
				$a = CBP::link(CBP::admin('lists'),'List Manager');
			
				echo "<label for=\"{$f->list_id->id}\">" . _e('Choose List',$c) . ' - ' . $a . "</label>";
				echo "<select id=\"{$f->list_id->id}\"  name=\"{$f->list_id->name}\">";			
				if(!count($lists)) echo '<option value="">(0 custom lists found)</option>';			
				echo  cbpressfn::select( $lists , $instance['list_id']);
				echo "</select>";

			## sort
				echo "<label for=\"{$f->sort->id}\">" . _e('Sort By',$c) . "</label>";
				echo "<select id=\"{$f->sort->id}\"  name=\"{$f->sort->name}\">";
						echo "<option value=''>(optional)</option>";
				foreach ($sortopts as $k =>$v) {
					if($k != 'rank'){
						$sel = selected( $instance['sort'], $k, false );
						echo "<option value='{$k}'{$sel}>{$v}</option>";
					}
				}
				echo "</select>";

			## sort order
				echo "<label for=\"{$f->order->id}\">" . _e('Sort Order',$c) . "</label>";
				echo "<select id=\"{$f->order->id}\"  name=\"{$f->order->name}\">";
						echo "<option value=''>(optional)</option>";
				foreach ( array('asc','desc') as $k ) {
					$sel = selected( $instance['order'], $k, false );
					echo "<option value='{$k}'{$sel}>{$k}</option>";
				}
				echo "</select>";

			## showdesc

				echo '<br>';
				
				$sel = checked( $instance['showdesc'], true, false );
				echo "<input class=\"checkbox\" type=\"checkbox\" id=\"{$f->showdesc->id}\"  name=\"{$f->showdesc->name}\" {$sel} />";
				echo "<label for=\"{$f->showdesc->id}\">" . _e('Show Descriptions',$c) . "</label>";

				// echo '<br>';
				
			## tid
				// echo "<label for=\"{$f->tid->id}\">" . _e('ClickBank TID',$c) . "</label>";
				// echo "<input id=\"{$f->tid->id}\" name=\"{$f->tid->name}\" type=\"text\" value=\"$tid\" />";


			echo '</div>';

		unset($f);
	
	// style="width:95%;"


	}
	function form222( $instance = '') {
		global $cbpress;

		$defaults = array(
			'title' => 'List Title Here',
			'list_id' 	=> 'default',
			'showdesc' => true,
			'sort' 	=> 'title',
			'order' => 'asc'
		);
		$instance = wp_parse_args( $instance, $defaults );

		$title = strip_tags($instance['title']);

		$lists = CBP_lists::get_for_select();

		$sortopts = CBP_meta::getSortByList();
		
		


		?>

		<div class="cbpress_widget">

			<?php _e('Choose a custom product list to display in this widget.', CBPRESS_TRANS ); ?>

			<a href="<?php echo CBP::get_admin_url('lists'); ?>">Manage Custom Lists</a>


			<?php if(!$lists) { ?>
				You have not created any <a href="<?php echo CBP::get_admin_url('lists'); ?>">product lists</a>
				<br />
			<?php } else { ?>

				<table width="100%" class="cbpress-form">
					<tr><th><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', CBPRESS_TRANS ); ?></label></th></tr>
					<tr><td>
						<input id="<?php echo $this->get_field_id('title'); ?>"
								name="<?php echo $this->get_field_name('title'); ?>"
								type="text" value="<?php echo $title; ?>" />
					</td></tr>


					<tr><th><label for="<?php echo $this->get_field_id('list_id'); ?>"><?php _e('Choose List', CBPRESS_TRANS ); ?></label></th></tr>
					<tr><td>
						<select id="<?php echo $this->get_field_id('list_id'); ?>"  name="<?php echo $this->get_field_name('list_id'); ?>">
							<?php echo cbpressfn::select( $lists , $instance['list_id'])?>
						</select>
					</td></tr>

								<?php if(1 == 2) { ?>
										<?php foreach ($lists as $row){
										$selected = '';
										$rowid = $row['list_id'];
										$value = $row['list_slug'];
										$name = cbpressfn::cb_esc_attr($row['list_name']);
										?>
										<option value="<?php echo $rowid; ?>" <?php selected( $instance['list_id'], $value ); ?>><?php echo $name; ?></option>
										<?php } ?>
								<?php } ?>

					<tr><th><label for="<?php echo $this->get_field_id('sort'); ?>"><?php _e('Sort By', CBPRESS_TRANS ); ?></label></th></tr>
					<tr><td>
						<select id="<?php echo $this->get_field_id('sort'); ?>" name="<?php echo $this->get_field_name('sort'); ?>">
						<?php
							foreach ($sortopts as $k =>$v) {
								if($k != 'rank'){
									$sel = selected( $instance['sort'], $k, false );
									echo "<option value='{$k}'{$sel}>{$v}</option>";
								}
							}
						?>
						</select>
					</td></tr>

					<tr><th><label for="<?php echo $this->get_field_id('sort'); ?>"><?php _e('Sort Order', CBPRESS_TRANS ); ?></label></th></tr>
					<tr><td>
						<select style="width:95%;" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
							<option value="asc"<?php if($instance['order'] == 'asc' || $instance['order'] == ''){print 'selected';}?>>Asc</option>
							<option value="desc"<?php if($instance['order'] == 'desc'){print 'selected';}?>>Desc</option>
						</select>
					</td></tr>
				</table>

				<br/>
				<p>
						<input class="checkbox" type="checkbox" <?php checked( $instance['showdesc'], true ); ?> id="<?php echo $this->get_field_id( 'showdesc' ); ?>" name="<?php echo $this->get_field_name( 'showdesc' ); ?>" />
						<label for="<?php echo $this->get_field_id( 'showdesc' ); ?>"><?php _e( 'Show Descriptions', CBPRESS_TRANS ); ?></label>
				</p>
			<?php } ?>
			</div>
			<?php
	}
}


