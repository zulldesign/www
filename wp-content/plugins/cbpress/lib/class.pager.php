<?php

// ======================================================================================
// This library is free software; you can redistribute it and/or
// modify it under the terms of the GNU Lesser General Public
// License as published by the Free Software Foundation; either
// version 2.1 of the License, or (at your option) any later version.
//
// This library is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
// Lesser General Public License for more details.
// ======================================================================================
// @author     John Godley (http://urbangiraffe.com)
// @version    0.2.8
// @copyright  Copyright &copy; 2007 John Godley, All Rights Reserved
// ======================================================================================
// 0.2.3 - Remember pager details in user data
// 0.2.4 - Add phpdoc comments
// 0.2.5 - Allow orderby to use tags to hide database columns
// 0.2.6 - Fix sortable columns with only 1 page
// 0.2.7 - Add a GROUP BY feature, make search work when position not 0
// 0.2.8 - WP 2.7 functions
// ======================================================================================


/**
 * Provides pagination, column-based ordering, searching, and filtering
 *
 * The class does no database queries itself but instead relies on the user modifying their queries with data
 * from the pager.  For correct pagination you must set the total number of results
 *
 * For example,
 *
 * $pager->set_total ($wpdb->get_var ("SELECT COUNT(*) FROM wp_posts").$pager->to_conditions ());
 * $rows = $wpdb->get_results ("SELECT * FROM wp_posts".$pager->to_limits ("post_type=page"));
 *
 * Searching is achieved by specifying the columns that can be searched:
 *
 * $rows = $wpdb->get_results ("SELECT * FROM wp_posts".$pager->to_limits ("post_type=page", array ('post_content', 'post_excerpt')));
 *
 * Additionally you can output column headings with correct URLs:
 *   <th><?php echo $pager->sortable ('post_username', 'Username') ?></th>
 *
 * @package default
 **/

class CBP_Pager
{
	var $url             = null;
	var $current_page    = 1;
	var $per_page        = 25;
	var $total           = 0;
	var $order_by        = null;
	var $order_original  = null;
	var $order_direction = null;
	var $order_tags      = array ();
	var $steps           = array ();
	var $search          = null;
	var $filters         = array ();
	var $id;
	var $limit  = null;
	// var $pn = 'curpage';
	var $pn = 'cbpg';

	/**
	 * Construct a pager object using the $_GET data, the current URL, and default preferences
	 *
	 * @param array $data Array of values, typically from $_GET
	 * @param string $url The current URL
	 * @param string $orderby Default database column to order data by
	 * @param string $direction Default direction of ordering (DESC or ASC)
	 * @param string $id An ID for the pager to separate it from other pagers (typically the plugin name)
	 * @return void
	 **/
	function CBP_Pager ($data, $url, $orderby='', $direction='DESC', $id='default', $tags='') {
		// Remove all pager params from the url
		$this->id  = $id;
		$this->url = $url;

		if (isset ($data[$this->pn])) $this->current_page = intval ($data[$this->pn]);


		if (isset ($data['perpage'])) {
			$this->per_page = intval ($data['perpage']);
			$per_page[get_class ($this)][$this->id] = $this->per_page;
		} else if (isset ($per_page[get_class ($this)]) && isset ($per_page[get_class ($this)][$this->id])) {
			$this->per_page = $per_page[get_class ($this)][$this->id];
		}

		if ($orderby != '')	$this->order_by = $orderby;

		// if (isset ($data['orderby'])) $this->order_by = $data['orderby'];




		// $tags[] = 'vin';
		// $tags[] = 'title';
		// $tags[] = 'PercentPerRebill';

		if (!empty ($tags)) {
			$this->order_tags = $tags;
			if (isset ($this->order_tags[$this->order_by])) $this->order_by = $this->order_tags[$this->order_by];
		}


		// dump($this->order_tags);


		$this->order_direction = $direction;
		$this->order_original  = $orderby;

		if (isset($data['order'])){
			$this->order_direction = $data['order'];
		}


		$this->order_direction = strtolower($this->order_direction);

		$this->search = isset($data['search']) ? $data['search'] : '';
		$this->steps = array (10, 25, 50, 100, 250);
		$this->url = str_replace ('&', '&amp;', $this->url);
		$this->url = str_replace ('&&amp;', '&amp;', $this->url);
		$this->url = str_replace ('&amp;', '&', $this->url);
	}


	function set_total($total) {
		$this->total = $total;
		if ($this->current_page <= 0 || $this->current_page > $this->total_pages ()) $this->current_page = 1;
	}

	function offset () {
		return ($this->current_page - 1) * $this->per_page;
	}

	function is_secondary_sort() {
		return substr ($this->order_by, 0, 1) == '_' ? true : false;
	}


	/**
	 * Returns a set of conditions without any limits.  This is suitable for a COUNT SQL
	 *
	 * @param string $conditions WHERE conditions
	 * @param array $searches Array of columns to search on
	 * @param array $filters Array of columns to filter on
	 * @return string SQL
	 **/

	function to_conditions($conditions, $searches = '', $filters = '')	{
		global $wpdb;

		$sql = '';
		if ($conditions != ''){
			$sql .= ' WHERE '.$conditions;
		}
		// Add on search conditions

		if (is_array ($searches) && $this->search != '') {
			$sql .= ($sql == '') ? ' WHERE (' : ' AND (';
			$searchbits = array ();
			foreach ($searches AS $search){
				$searchbits[] = $wpdb->prepare( $search.' LIKE "%s"', '%'.$this->search.'%' );
			}
			$sql .= implode (' OR ', $searchbits);
			$sql .= ')';
		}

		// Add filters

		if (is_array ($filters) && !empty ($this->filters)) {

			$searchbits = array ();
			foreach ($filters AS $filter){
				if (isset ($this->filters[$filter])){
					if ($this->filters[$filter] != ''){
						$searchbits[] = $wpdb->prepare( $filter." = %s", $this->filters[$filter] );
					}
				}
			}

			if (count ($searchbits) > 0){
				$sql .= ($sql == '') ? ' WHERE (' : ' AND (';
				$sql .= implode (' AND ', $searchbits);
				$sql .= ')';
			}
		}

		return $sql;
	}


	/**
	 * Returns a set of conditions with limits.
	 *
	 * @param string $conditions WHERE conditions
	 * @param array $searches Array of columns to search on
	 * @param array $filters Array of columns to filter on
	 * @return string SQL
	 **/

	function to_limits ($conditions = '', $searches = '', $filters = '', $group_by = '') {
		global $wpdb;

		$sql = $this->to_conditions ($conditions, $searches, $filters);

		if ($group_by) $sql .= ' '.$group_by.' ';

		if (strlen($this->order_by) > 0) {

			if (!$this->is_secondary_sort ()){
				$sql .= " ORDER BY ".$this->order_by.' '.$this->order_direction;
			}else{
				$sql .= " ORDER BY ".$this->order_original.' '.$this->order_direction;
			}
		}

		if ($this->limit > 0){
			$sql .= $wpdb->prepare( ' LIMIT %d', $this->limit );
		} else if ($this->per_page > 0){
			$sql .= $wpdb->prepare( ' LIMIT %d,%d', $this->offset(), $this->per_page );
		}
		return $sql;
	}


	/**
	 * Return the url with all the params added back
	 *
	 * @param int Page offset
	 * @param string $orderby Optional order
	 * @return string URL
	 **/

	function url ($offset='', $sort = '') {
		
		$this->url .= (strpos($this->url, '?') === false) ? '?' : '';

		// Position
		$url = $this->url;
		$url = remove_query_arg( $this->pn, $url );	
		if($offset > 0){
			$url = add_query_arg( $this->pn, $offset, $url);
		}

		// Order
		if ($sort != '') {
			$url = add_query_arg('sort',$sort, remove_query_arg( 'sort', $url ));

			if (!empty ($this->order_tags) && isset($this->order_tags[$sort])){
				$dir = $this->order_direction == 'asc' ? 'desc' : 'asc';
			}else if ($this->order_by == $sort){
				$dir = $this->order_direction == 'asc' ? 'desc' : 'asc';
			}else{
				$dir = $this->order_direction;
			}

			$url = add_query_arg('order',$dir, remove_query_arg( 'order', $url ));

		}


		// dump($url);

		return str_replace ('&go=go', '', $url);
	}


	/**
	 * Return current page
	 *
	 * @return int
	 **/

	function current_page () { return $this->current_page; }



	function total_pages ()	{
		if ($this->per_page == 0) return 1;
		return ceil($this->total / $this->per_page);
	}

	function have_next_page ()	{
		if ($this->current_page < $this->total_pages ()) return true;
		return false;
	}


	function have_previous_page ()	{
		if ($this->current_page > 1) return true;
		return false;
	}


	function sortable_class ($column, $class = true){
		if ($column == $this->order_by){
			if ($class){
				printf (' class="sorted"');
			}else{
				echo ' sorted';
			}
		}
	}

	/**
	 * Return a string suitable for a sortable column heading
	 *
	 * @param string $column Column to search upon
	 * @param string $text Text to display for the column
	 * @param boolean $image Whether to show a direction image
	 * @return string URL
	 **/

	function sortable ($column, $text, $image = true) {

		$url = $this->url($this->current_page, $column);
		$img = '';

		if (isset ($this->order_tags[$column])) $column = $this->order_tags[$column];
		$class = "";

		if ($column == $this->order_by) {

			$dir = CBP_IMG_URL ;

			if($this->order_direction !== 'asc'){
				$img = '<img align="absmiddle" src="'.$dir.'up.gif" alt="dir" width="16" height="7"/>';
				$class = ' class="sortup"';

			}else{
				$img = '<img align="absmiddle" src="'.$dir.'down.gif" alt="dir" width="16" height="7"/>';
				$class = ' class="sortdn"';

			}

			if ($image == false) $img = '';
		}

		if($img !== ''){
			// $img = '<div style="float:right">' . $img . '</div>';
			// $img = '<div style="padding: 5px; float:right;">' . $img . '</div>';

				return "<span$class>" . '<a href="'.$url.'">'.$text.'</a></span>';

		}else{

				return '<a href="'.$url.'">'.$text.'</a>';


		}
		// return $img . '<a href="'.$url.'"' . $class . '>'.$text.'</a>';
	}


	/**
	 * Returns an array of page numbers => link, given the current page (next and previous etc)
	 *
	 * @return array Array of page links
	 **/

	function area_pages () 	{

		$allow_dot = true;
		$pages = array ();

		if ($this->total_pages() > 1) {
			// $previous = __ ('Previous', 'cbpress');
			$previous = __ ('Back', 'cbpress');
			$next     = __ ('Next', 'cbpress');

			if ($this->have_previous_page ()){
				$pages[] = '<a href="'.$this->url($this->current_page - 1).'" class="cbprev">'.$previous.'</a>';
			}else{
				// $pages[] = $previous.' ';
				$pages[] = '<a href="'.$this->url().'" class="cbprev inactive">'.$previous.'</a>';
			}
				// $pages[] = ' |';

			for ($pos = 1; $pos <= $this->total_pages(); $pos++) {
				if ($pos == $this->current_page) {
					$pages[] = '<span class="active">'.$pos.'</span>';
					$allow_dot = true;
				} else if ($pos == 1 || abs ($this->current_page - $pos) <= 2 || $pos == $this->total_pages()) {
					$pages[] = '<a href="'.$this->url ($pos).'">'.$pos."</a>";
				} else if ($allow_dot) {
					$allow_dot = false;
					$pages[] = '&hellip; ';
				}
			}

			if ($this->have_next_page()){
				// $pages[] = ' &hellip; ';
				$pages[] = '' . '<a href="'.$this->url ($this->current_page + 1).'" class="cbnext">'.$next.'</a>';
			}else{
				$pages[] = '' . '<a href="'.$this->url ($this->current_page).'" class="cbnext inactive">'.$next.'</a>';
				// $pages[] = '' . $next;
			}
		}

		return $pages;
	}


	/**
	 * @todo
	 * @return boolean
	 **/

	function filtered ($field, $value) {
		if (isset ($this->filters[$field]) && $this->filters[$field] == $value) return true;
		return false;
	}


	/**
	 * Display a SELECT box suitable for a per-page
	 *
	 * @return void
	 **/

	function per_page($plugin = '') {
		?>
		<select name="perpage">
			<?php foreach ($this->steps AS $step) : ?>
		  	<option value="<?php echo $step ?>"<?php if ($this->per_page == $step) echo ' selected="selected"' ?>>
					<?php printf (__ ('%d per-page', $plugin), $step) ?>
				</option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	function page_links_frontend ()
	{
		$fromrec = number_format_i18n (($this->current_page () - 1) * $this->per_page + 1);
		$torec = number_format_i18n ($this->current_page () * $this->per_page > $this->total ? $this->total : $this->current_page () * $this->per_page);
		$totalrec = number_format_i18n ($this->total);

		$text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s' ) . '</span>',$fromrec,$torec,$totalrec);

		$links_array = paginate_links (
			array (
		 		'base' => str_replace ('99', '%#%', $this->url(99))
				, 'format' => '%#%'
				, 'current' => $this->current_page ()
				, 'type' => 'array'
				, 'total' => $this->total_pages ()
				, 'end_size' => 3
				, 'mid_size' => 2
				, 'prev_next' => true
			)
		);

		$links = paginate_links (
			array (
		 		'base' => str_replace ('99', '%#%', $this->url(99))
				, 'format2' => '%#%'
				, 'format' => ''
				, 'current' => $this->current_page()
				, 'total' => $this->total_pages()
				, 'end_size' => 3
				, 'mid_size' => 2
				, 'prev_next' => true
				, 'show_all' => false
				, 'type' => 'plain'
			)
		);







		
		// $aaaaa = implode('',$this->area_pages());
		// $links = htmlspecialchars($links);

		
		// die;
		// abort(get_defined_vars());
		



		$out = new stdClass();
		$out->start_row = $fromrec;
		$out->end_row = $torec;
		$out->text = $text;
		$out->array = $links_array;


		$pagi = '<div id="result_info_container">';
		$pagi .= '<div id="sticky" class="result_info">';
		$pagi .= implode('',$this->area_pages());
		$pagi .= '</div>';
		$pagi .= '</div>';		
		$out->links = $pagi;
		$out->html = $pagi;


		// $pl = '<table width="100%"><tr><td>' . $text . '</td><td align="right">' . $links . '</td></tr></table>';
		// $pl = '<div id="pager" class="pagination"><div class="tablenav-pages">' . $links . '</div></div>';
		// $out->html = $pl;	
		// $out->links = $links;
		// $out->html = '<div class="pagination">' . $links . '</div>';

		$out->html = $out->links;




		// abort(get_defined_vars());




		return $out;
	}
	function page_links ()
	{
		$text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s' ) . '</span>',
											number_format_i18n (($this->current_page () - 1) * $this->per_page + 1),
											number_format_i18n ($this->current_page () * $this->per_page > $this->total ? $this->total : $this->current_page () * $this->per_page),
											number_format_i18n ($this->total));

		$links = paginate_links (array ('base' => str_replace ('99', '%#%', $this->url (99)), 'format' => '%#%', 'current' => $this->current_page (), 'total' => $this->total_pages (), 'end_size' => 3, 'mid_size' => 2, 'prev_next' => true));



		$pl = $text.$links;

		// $pl = '<table width="100%"><tr><td>' . $text . '</td><td align="right">' . $links . '</td></tr></table>';



		return $pl;
	}
}
