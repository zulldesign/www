<?php
/**
 * Content Hopwords Module
 *
 * @since 2.2
 */


class CBP_Hopwords {

	function get_parent_module() { return 'hopwords'; }
	function get_child_order() { return 10; }
	function is_independent_module() { return false; }


	function get_default_settings() {
		return array(
			  'limit_lpp_value' => 5
			//, 'enable_self_links' => false
		);
	}

	public function __construct(){
	
	

	//	add_filter('the_content', array(&$this, 'hopwords_content'),10000);
		add_filter('cb_postmeta_help', array(&$this, 'postmeta_help'), 35);
		add_filter('cb_get_postmeta-hopwords', array(&$this, 'get_post_hopwords'), 10, 3);
		add_filter('cb_custom_update_postmeta-hopwords', array(&$this, 'save_post_hopwords'), 10, 4);
	}

	/********** ADMIN POST META BOX FUNCTIONS **********/

	function get_postmeta($key, $id=false) {
		if (!$id) {
			//This code is different from suwp::get_post_id();
			if (is_admin()) {


					// $_REQUEST['post']
					$_po = cbpressfn::getparam('post'); // cab


				$id = intval($_po);
				global $post;
			} elseif (in_the_loop()) {
				$id = intval(get_the_ID());
				global $post;
			} elseif (is_singular()) {
				global $wp_query;
				$id = $wp_query->get_queried_object_id();
				$post = $wp_query->get_queried_object();
			}
		}

		if ($id)
			$value = get_post_meta($id, "_cb_$key", true);
		else
			$value = '';

		$value = apply_filters("cb_get_postmeta", $value, $key, $post);
		$value = apply_filters("cb_get_postmeta-$key", $value, $key, $post);

		return $value;
	}
	function get_setting($key) {


		return Cbpress::get($key);


	}

	public function cbpress_generated_content() {
		$key = $this->options->link_cloaker;		
		$id = CBP::getv($key);
		return trim($id);
	}
	
	
	
	function hopwords_content($content) {

		global $cbpress;
		
		$cbpress->the_content;
		
		
		$cont = cbpressfn::clean_string_input($content);
		$cont = cbpressfn::remove_words($cont);
		
		// $id = apply_filters('the_cbpress_content',0);
		// abort($cont);
		// abort(htmlspecialchars($content));
		
		if ($this->get_postmeta('disable_hopwords')) return $content;

		$links = array(

			array('anchor'=>'' , 'to_type'=>'autohop' ,'to_id'=>'cbengine', 'nofollow'=>'', 'target'=>'', 'title'=>'')
		);

		// abort($content);
		if (!count($links)) return $content;

		CBP::vklrsort($links, 'anchor');

		// $content = $this->_hopwords_content($content, $links, $this->get_setting('limit_lpp_value', 5));

		return $content;
	}














	function preg_escape($str, $delim='%') {
		$chars = "\ ^ . $ | ( ) [ ] * + ? { } , ".$delim;
		$chars = explode(' ', $chars);
		foreach ($chars as $char)
			$str = str_replace($char, '\\'.$char, $str);
		return $str;
	}

	function htmlsafe_str_replace($search, $replace, $subject, $limit, &$count) {
		$search = $this->preg_escape($search);
		return $this->htmlsafe_preg_replace($search, $replace, $subject, $limit, $count);
	}

	function htmlsafe_preg_replace($search, $replace, $subject, $limit, &$count) {

		//Special thanks to the GPL-licensed "SEO Smart Links" plugin for the following find/replace regex
		//http://www.prelovac.com/vladimir/wordpress-plugins/seo-smart-links
		$reg = '/(?!(?:[^<\[]+[>\]]|[^>\]]+<\/a>))\b($name)\b/imsU';

		$search = str_replace('/', '\/', $search);
		$search_regex = str_replace('$name', $search, $reg);

		return preg_replace($search_regex, $replace, $subject, $limit, $count);
	}



	function endswith( $str, $sub ) {
	   return ( substr( $str, strlen( $str ) - strlen( $sub ) ) === $sub );
	}

	function endwith( $str, $end ) {
		if (!$this->endswith($str, $end))
			return $str.$end;
		else
			return $str;
	}

	function startswith( $str, $sub ) {
	   return ( substr( $str, 0, strlen( $sub ) ) === $sub );
	}
	function rtrim_str($str, $totrim) {
		if (strlen($str) > strlen($totrim) && $this->endswith($str, $totrim))
			return substr($str, -strlen($totrim));

		return $str;
	}

	function rtrim_substr($str, $totrim) {
		for ($i = strlen($totrim); $i > 0; $i--) {
			$totrimsub = substr($totrim, 0, $i);
			if ($this->endswith($str, $totrimsub))
				return $this->rtrim_str($str, $totrimsub);
		}

		return $str;
	}

	function ltrim_str($str, $totrim) {
		if (strlen($str) > strlen($totrim) && $this->startswith($str, $totrim))
			return substr($str, strlen($totrim));

		return $str;
	}







	function current() {
		$url = 'http';
		if ($_SERVER["HTTPS"] == "on") $url .= "s";
		$url .= "://";
		
		if ($_SERVER["SERVER_PORT"] != "80"){
			return $url.$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		}else{
			return $url.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
	}









	function _hopwords_content($content, $links, $limit) {




		// dump($content);

		$limit_enabled = $this->get_setting('limit_lpp', false);
		if ($limit_enabled && $limit < 1) return $content;
		$oldlimit = $limit;

		foreach ($links as $data) {
			$anchor = $data['anchor'];
			$to_id = cbpressfn::cb_esc_attr($data['to_id']);

			$type = $data['to_type'];

			if ((strlen(trim($anchor)) && strlen(trim((string)$to_id)) && $to_id !== 0 && $to_id != 'http://') || ($type == 'autohop') ) {


				if ($type == 'url'){
					$url = $to_id;
				}elseif ($type == 'hop') {




					$url = $to_id;

				}elseif ($type == 'autohop') {






					$url = 'LOOK-ME-UP-FROM-KEYWORD';







				}elseif (($posttype = $this->ltrim_str($type, 'posttype_')) != $type) {
					$to_id = (int)$to_id;
					if (get_post_status($to_id) != 'publish') continue;
					$url = get_permalink($to_id);
				} else {
					continue;
				}


				if (!$this->get_setting('enable_self_links', false) && $url == $this->current()) continue;

				$rel	= $data['nofollow'] ? ' rel="nofollow"' : '';
				$target	= ($data['target'] == 'blank') ? ' target="_blank"' : '';
				$title	= strlen($titletext = $data['title']) ? " title=\"$titletext\"" : '';

				$link = "<a href=\"$url\"$title$rel$target>$1</a>";

				$content = $this->htmlsafe_str_replace($anchor, $link, $content, $limit_enabled ? 1 : -1, $count);

				if ($limit_enabled) {
					$limit -= $count;
					if ($limit < 1) return $content;
				}
			}
		}

		if ($limit_enabled && $limit < $oldlimit)
			$content = $this->_hopwords_content($content, $links, $limit);

		return $content;
	}

	function admin_page_contents() {
		echo "\n<p>";
		_e('The Links section of Hopwords lets you automatically link a certain word or phrase in your post/page content to a ClickBank affiliate product or URL you specify.');
		echo "</p>\n";

		$links = $this->get_setting('links', array());
		$num_links = count($links);

		if ($this->is_action('update')) {

			$links = array();

			$guid = stripslashes($_POST['_link_guid']);

			for ($i=0; $i <= $num_links; $i++) {

				$anchor = stripslashes($_POST["link_{$i}_anchor"]);
				$to_type= stripslashes($_POST["link_{$i}_to_type__{$guid}"]);
				$to_id  = stripslashes($_POST["link_{$i}_to_id_{$to_type}__{$guid}"]);
				$title  = stripslashes($_POST["link_{$i}_title"]);

				$target = stripslashes($_POST["link_{$i}_target"]);
				if (!$target) $target = 'self';

				$nofollow = intval($_POST["link_{$i}_nofollow"]) == 1;
				$delete = intval($_POST["link_{$i}_delete"]) == 1;

				if (!$delete && (strlen($anchor) || $to_id))
					$links[] = compact('anchor', 'to_type', 'to_id', 'title', 'nofollow', 'target');
			}
			$this->update_setting('links', $links);

			$num_links = count($links);
		}

		$guid = substr(md5(time()), 0, 10);




		if ($num_links > 0) {
			$this->admin_subheader(_cbx('Edit Existing Links'));
			$this->content_links_form($guid, 0, $links);
		}

		$this->admin_subheader(_cbx('Add a New Link'));
		$this->content_links_form($guid, $num_links, array(array()), false);
	}

	function content_links_form($guid, $start_id = 0, $links, $delete_option = true) {

		//Set headers
		$headers = array(
			  'link-anchor' => _cbx('Anchor Text')
			, 'link-to_type' => _cbx('Destination Type')
			, 'link-to_id' => _cbx('Destination')
			, 'link-title' => _cbx('Title Attribute')
			, 'link-options' => _cbx('Options')
		);
		if ($delete_option) $headers['link-delete'] = _cbx('Delete');

		//Begin table; output headers
		$this->admin_wftable_start($headers);

		//Get post options
		$posttypeobjs = suwp::get_post_type_objects();
		$posttypes = $posts = $postoptions = array();
		foreach ($posttypeobjs as $posttypeobj) {

			$stati = get_available_post_statuses($posttypeobj->name);
			suarr::remove_value($stati, 'auto-draft');
			$stati = implode(',', $stati);

			$typeposts = get_posts("post_status=$stati&numberposts=-1&post_type=".$posttypeobj->name);
			if (count($typeposts)) {
				$posttypes['posttype_'.$posttypeobj->name] = $posttypeobj->labels->singular_name;
				$posts['posttype_'.$posttypeobj->name] = $typeposts_array = array_slice(suarr::simplify($typeposts, 'ID', 'post_title'), 0, 1000, true); //Let's not go too crazy with post dropdowns; cut it off at 1000
				//$postoptions['posttype_'.$posttypeobj->name] = suhtml::option_tags(array(0 => '') + $typeposts_array, null); //Maintains numeric array keys, unlike array_unshift or array_merge
			}
		}

		//Cycle through links
		$i = $start_id;
		foreach ($links as $link) {

			$postdropdowns = array();

				// dump($posts);

			foreach ($posts as $posttype => $typeposts) {



				// dump($link);

				if($link){ // cab


					$typeposts = array(0 => '') + $typeposts; // Maintains numeric array keys, unlike array_unshift or array_merge

					$postdropdowns[$posttype] = $this->get_input_element('dropdown', "link_{$i}_to_id_{$posttype}__$guid", $link['to_id'], $typeposts);




				}



				/*
				//$typeposts = array(0 => '') + $typeposts;
				$postdropdowns[$posttype] = $this->get_input_element('dropdown', "link_{$i}_to_id_{$posttype}__$guid", $link['to_id'],
					str_replace("<option value='{$link['to_id']}'>", "<option value='{$link['to_id']}' selected='selected'>", $postoptions[$posttype])
				);
				*/
			}

			/*******
			*******/

			$xxx = '';

			$toID = '';
			if(isset($link['to_id'])) $toID = $link['to_id']; // isset cab


			if(1 == 1){



				$field = "link_{$i}_to_type__$guid";
				$current_value = $link['to_type'] ? $link['to_type'] : 'url';
				$subsections = array_merge(
						array(
							'url' =>     $this->get_input_element('textbox', "link_{$i}_to_id_url__$guid", ($link['to_type'] == 'url') ? $toID : ''),
							'hop' =>     $this->get_input_element('textbox_hop', "link_{$i}_to_id_hop__$guid", ($link['to_type'] == 'hop') ? $toID : ''),
							'autohop' => $this->get_input_element('hidden_hop', "link_{$i}_to_id_autohop__$guid", ($link['to_type'] == 'autohop') ? $toID : '')
						),
						$postdropdowns
					);


				$xxx = $this->get_admin_form_subsections($field, $current_value, $subsections);

			}


			// abort($xxx);


			$cells = array(
				  'link-anchor' => $this->get_input_element('textbox', "link_{$i}_anchor", $link['anchor'])

				, 'link-to_type' => $this->get_input_element('dropdown', "link_{$i}_to_type__$guid", $link['to_type'], array(
						  'Custom' => array('url'=>'URL', 'hop'=>'Hop Link', 'autohop'=>'Auto Hop Link')
						, 'Content Items' => $posttypes
					))
				, 'link-to_id' => $xxx
				, 'link-title' => $this->get_input_element('textbox', "link_{$i}_title", $link['title'])
				, 'link-options' =>
					 $this->get_input_element('checkbox', "link_{$i}_nofollow", $link['nofollow'], _cbx('Nofollow'))
					.$this->get_input_element('checkbox', "link_{$i}_target", $link['target'] == 'blank', _cbx('New window'))
			);


			if ($delete_option) $cells['link-delete'] = $this->get_input_element('checkbox', "link_{$i}_delete");

			$this->table_row($cells, $i, 'link');

			$i++;
		}

		$this->admin_wftable_end();
		echo $this->get_input_element('hidden', '_link_guid', $guid);
	}

	function get_post_hopwords($value, $key, $post) {
		$links = $this->get_setting('links', array());
		$postlinks = '';
		foreach ($links as $link_data) {
			// dump($post);
			// dump($link_data);


			if($post){


			if ($link_data['to_type'] == 'posttype_'.$post->post_type && $link_data['to_id'] == $post->ID){
				$postlinks .= $link_data['anchor']."\r\n";
			}

			}
		}
		return trim($postlinks);
	}

	function save_post_hopwords($false, $value, $metakey, $post) {
		if ($post->post_type == 'revision') return true;

		$links = $this->get_setting('links', array());
		$new_links = array();

		$keep_anchors = array();
		$others_anchors = array();
		$new_anchors = suarr::explode_lines($value);

		foreach ($links as $link_data) {
			if ($link_data['to_type'] == 'posttype_'.$post->post_type && $link_data['to_id'] == $post->ID) {
				if (in_array($link_data['anchor'], $new_anchors)) {
					$keep_anchors[] = $link_data['anchor'];
					$new_links[] = $link_data;
				}
			} else {
				$others_anchors[] = $link_data['anchor'];
				$new_links[] = $link_data;
			}
		}

		$anchors_to_add = array_diff($new_anchors, $keep_anchors, $others_anchors);

		foreach ($anchors_to_add as $anchor_to_add)
			$new_links[] = array(
				  'anchor' => $anchor_to_add
				, 'to_type' => 'posttype_'.$post->post_type
				, 'to_id' => $post->ID
				, 'title' => ''
				, 'nofollow' => false
				, 'target' => 'self'
			);

		$this->update_setting('links', $new_links);

		return true;
	}

	function postmeta_fields($fields) {
		$fields['35|hopwords'] = $this->get_postmeta_textarea('hopwords', _cbx('Incoming Hopword Anchors:<br /><em>(one per line)</em>'));
		$fields['38|disable_hopwords'] = $this->get_postmeta_checkbox('disable_hopwords', _cbx('Don&#8217;t add hopwords to anchor texts found in this post.'), _cbx('Hopwords Exclusion:'));
		return $fields;
	}

	function postmeta_help($help) {
		$help[] = _cbx('<strong>Incoming Hopwords Anchors</strong> &mdash; When you enter anchors into this box, Hopwords will search for that anchor in all your other posts and link it to this post. For example, if the post you&#8217;re editing is about &#8220;blue widgets,&#8221; you could type &#8220;blue widgets&#8221; into the &#8220;Incoming Hopwords Anchors&#8221; box and Hopwords will automatically build internal links to this post with that anchor text (assuming other posts contain that text).');
		dump($help);
		return $help;
	}
}
