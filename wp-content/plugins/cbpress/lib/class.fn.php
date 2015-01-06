<?php
/* global helper functions */
define ('cbpPUT','put');
define ('cbpGET','get');
define ('cbpPOST','post');
define ('cbpDELETE','delete');

class cbpressfn {


		function whitespaceToLinebreak($input) {
			$output = str_replace(' ', "\n", $input);
			return $output;
		}

	function dirify_plus_for_php($s,$t) {

		$a = substr($t, 0, 1);
		$b = substr($t, 1, 1);
		$c = substr($t, 2, 1);
		convert_high_ascii($s);
		$s = strip_tags($s);						## remove HTML tags.
		$s = preg_replace('!&[^;\s]+;!', '', $s); 	## remove HTML entities.
		$s = preg_replace('![^\w\s\\\/]!', '', $s);	## remove non-word/space chars.
		$s = str_replace('  ', ' ', $s);			## remove 2 spaces in a row
		if ($b == "l")
		{
			$s =	strtolower($s);					## lower-case.
		}
		elseif ($b == "i")	{
			$s =	strtolower($s);					## lower-case.
			$s = ucwords($s);						## captialize words
		}
		elseif ($b == "c")
		{
			$s = ucwords($s);						## captialize words
		}
		if ($a == "p")
		{
			$s = preg_replace('![^\w\s]!', '', $s);			## remove non-word/space chars.
		}
		elseif ($a == "s")
		{
			$s = preg_replace('![^\w\s\/]!', '', $s);		## remove non-word/space'/' chars.
		}
		elseif ($a == "b")
		{
			$s = preg_replace('![^\w\s\\\]!', '', $s);		## remove non-word/space'\' chars.
		}
		elseif ($a == "c")
		{
			$s = preg_replace('![^\w\s\\\]!', '', $s);		## remove non-word/space'\' chars.
			$s = str_replace('\\','/',$s);					## reverse backslashes
		}
		elseif ($a == "r")
		{
			$s = preg_replace('![^\w\s\/]!', '', $s);		## remove non-word/space'/' chars.
			$s = str_replace('/','\\',$s);					## reverse slashes
		}
		if (($c == "u") || (!$c))
		{
			$s = str_replace(' ','_',$s);					## change space chars to underscores.
		}
		elseif ($c == "n")
		{
			$s = str_replace(' ','',$s);					## delete space
		}
		elseif ($c == "d")
		{
			$s = str_replace(' ','-',$s);					## change space chars to dashes.
		}
		return($s);
	}

















    // bullet proof slug creator I think
	function CleanForUrl($string, $maxLength = null) {

		$string = str_replace(array(" ", '"',"'", '&quot;','&#039;','&amp;'), array("_", "", "", "",'',''), $string);
		$string = strtolower(trim(preg_replace(array('~[^0-9a-z]~i', '~-+~'), '-', $string), '-')); // _Slugify
		$string = strtolower(preg_replace(array('/[^a-z0-9\- ]/i', '/[ \-]+/'), array('', '-'), trim($string)));
		if ($maxLength) $string = substr($string, 0, $maxLength);

		return $string;

	}



	static function select( $items, $default = '' ) {
		if ( count( $items ) > 0 ) {
			foreach ( $items AS $key => $value ) {
				if ( is_array( $value ) )	{
					echo '<optgroup label="'.esc_attr( $key ).'">';
					foreach ( $value AS $sub => $subvalue ) {
						echo '<option value="'.esc_attr( $sub ).'"'.( $sub == $default ? ' selected="selected"' : '' ).'>'.esc_html( $subvalue ).'</option>';
					}
					echo '</optgroup>';
				}
				else
					echo '<option value="'.esc_attr( $key ).'"'.( $key == $default ? ' selected="selected"' : '' ).'>'.esc_html( $value ).'</option>';
			}
		}
	}



        /**
          * Extract wordpress searchwords from $_GET global
          *
          * @return string searchwords
          */
        function extractBlogSearchwords(){
            if (is_search() && !is_paged() && !is_admin() ) {
                $query = urldecode(stripslashes($_GET['s']));
                $query=preg_replace('=[\"\'\\n\\r]+=','',$query);
                // array of searchwords - from wp-query.php
                preg_match_all('/".*?("|$)|((?<=[\\s",+])|^)[^\\s",+]+/', $query, $matches);
                $searchwords=$matches[0];
                // lowercase all words
                $searchwords = array_map('strtolower', $searchwords);
                // return the resulting string
                return(implode(' ',$searchwords));
            }else{
                return FALSE;
            }
        }
      function extractSearchwords(){

	  	// returns keywords from common query string variable names
	  	// from both local and referer

            $ret = array();
			$search_args = array();





	        $watch = explode(',','wp_s,s,query,q,search,words,p,qs,k,kw,kewords,cat,category,term,terms,text');

			if(isset($_SERVER['HTTP_REFERER'])){
				$ref = $_SERVER['HTTP_REFERER'];
				$search_args = '';
				$parts = explode('?', $ref);
				if(count($parts) > 1) $search_args = $parts[1];
				parse_str($search_args, $search_args);
		  	}
			// $search_args['wp_s'] = get_search_query();
			$search_args['wp_s'] = self::extractBlogSearchwords();

	        foreach($watch as $c){
				if(isset($search_args[$c])){

					$query_parts = urldecode($search_args[$c]);
 					$query_parts = stripslashes($query_parts);
		            // replace whitespaces
		            $query_parts = preg_replace(array('=\'=','=\"='),'',$query_parts);
		            // array of single searchwords -> split by possible sub delimiter



		            $searchwords = preg_split('=[\+\s\.,]+=', $query_parts);
		            // lowercase all words
		            $searchwords = array_map('strtolower', $searchwords);
		            $searchwords = array_map('trim', $searchwords);

		            $searchwords = implode(' ',$searchwords);
		            if($searchwords != ''){
						$ret[] = $searchwords;
		            }
		            unset($query_parts,$searchwords);
				}
			}

           	$ret = implode(' ',$ret);
           	$ret = explode(' ',$ret);


		    $out = array();
	      	foreach($ret as $str){
	      			if(strlen($str) > 2 && ! preg_match('/[^a-zA-Z0-9_ -]/', $str) > 0 ) {
						if(! is_numeric($str) || is_string($str)) $out[] = $str;
					}
			}
			$ret = $out;
           	$ret = implode(',',$ret);
 			$ret = stripslashes($ret);
 			$ret = explode(',',$ret);

		            $ret = array_map('trim', $ret);
           	$ret = array_unique($ret);
           	$ret = implode(',',$ret);

       		// abort($ret);

			// $ret = self::remove_words($ret);
       		//  $wordlist = preg_split('%\s*\W+\s*%', strtolower($ret));
			// dump($wordlist);
		    unset($out,$watch,$search_args);
		    return $ret;
        }

    function calc_array_average($arr) {

		return array_sum($arr)/count($arr);

    }

		function explodeTree($array, $delimiter = '_', $baseval = false)
		{
			if(!is_array($array)) return false;
			$splitRE   = '/' . preg_quote($delimiter, '/') . '/';
			$returnArr = array();
			foreach ($array as $key => $val) {
				// Get parent parts and the current leaf
				$parts	= preg_split($splitRE, $key, -1, PREG_SPLIT_NO_EMPTY);
				$leafPart = array_pop($parts);

				// Build parent structure
				// Might be slow for really deep and large structures
				$parentArr = &$returnArr;
				foreach ($parts as $part) {
					if (!isset($parentArr[$part])) {
						$parentArr[$part] = array();
					} elseif (!is_array($parentArr[$part])) {
						if ($baseval) {
							$parentArr[$part] = array('__base_val' => $parentArr[$part]);
						} else {
							$parentArr[$part] = array();
						}
					}
					$parentArr = &$parentArr[$part];
				}

				// Add the final part to the structure
				if (empty($parentArr[$leafPart])) {
					$parentArr[$leafPart] = $val;
				} elseif ($baseval && is_array($parentArr[$leafPart])) {
					$parentArr[$leafPart]['__base_val'] = $val;
				}
			}
			return $returnArr;
		}



    function get_table_data($params) {
		global $wpdb;
        $params = is_array($params) ? $params : array();
        $defaults = array(
            'table' => 'types',
            'columns' => '*',
            'orderby' => 'id ASC',
            'where' => 1,
            'array_key' => 'id'
        );
        $params = (object) array_merge($defaults, $params);

		$query = "SELECT $params->columns FROM $params->table WHERE $params->where ORDER BY $params->orderby";
		$result = $wpdb->get_results($query,ARRAY_A);
		$num = $wpdb->num_rows;
		$data = false;
        if (0 < $num) {
			for($i = 0; $i < count($result); $i++ ) {
                $data[$result[$i][$params->array_key]] = $result[$i];
        	}
        }
        return $data;
    }

	function return_bytes($val) {
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}
		return $val;
	}

	function return_bytes_nice($bytes) {
		$units = array('B', 'K', 'M', 'G');

		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);

		$bytes /= pow(1024, $pow);

		return round($bytes, 0) . $units[$pow];
	}




	function get_input_element($args) {

		global $cbpress;
		$defaults = array(
			'name' => 'name',
			'type' => 'text',
			'extra' => false,
			'inputid' => true,
			'value' => null,
			'br' => 1,
			'size'=>''
		);
		// cbpressfn::get_input_element('text','import_autosync')

		// $r = wp_parse_args( $args, $defaults );
		// extract($r, EXTR_SKIP);

		extract(wp_parse_args( $args, $defaults ), EXTR_SKIP);


		if ($inputid === true) $inputid = $name;


		// if (isset($id])){ $inputid = $name; }


		if (strlen($inputid)){
		 // $inputid = " id='".self::cb_esc_attr($inputid)."'";

		 $inputid = " id='".$inputid."'";

		}
		if ($size > 0){
			$size = " size='".$size."'";
		} else{
			$size = '';
		}


        $html = '';

        //Get HTML element
		switch ($type) {
			case 'pagelist':
				$pageargs = array(
				'name' => $name,
				'child_of'     => 0,
				'echo'=> 0,
				'depth'=> 0,
				'selected' => $value,
				'sort_order'   => 'ASC',
				'sort_column'  => 'post_title',
				'hierarchical' => 1,
				'exclude'      => '',
				'include'      => '',
				'meta_key'     => '',
				'meta_value'   => '',
				'authors'      => '',
				'exclude_tree' => ''
				);
				$html .= wp_dropdown_pages($pageargs);
				if (is_string($extra)) {
					$html = "<label>$html&nbsp;$extra</label>";
				}
				break;

			case 'text':
			case 'textbox':
				$value = self::cb_esc_editable_html($value);
				$html .= "<input name='$name'$inputid"."$size value='$value' type='text' class='textbox ' />";
				if (is_string($extra)) {
					$extra = self::cb_esc_html($extra);
					$html = "<label>$html&nbsp;$extra</label>";
				}
				break;
			case 'textbox_hop':
				$value = self::cb_esc_editable_html($value);
				$html .= "<input name='$name'$inputid value='$value' type='text' title='Enter ClickBank Product Vendor ID' class='textbox_hop regular-text' />";
				break;
			case 'textarea':
				// $value = esc_textarea( $value );


				$value = self::cb_esc_editable_html($value);
				$html .= "<textarea name='$name'$inputid type='text' rows='3' cols='50' class='textarea regular-text'>$value</textarea>";
				break;
			case 'checkbox':


				$checked = $value ? " checked='checked'" : '';


				$html .= "<input type='hidden' name='{$name}' value='0'>";
				$html .= "<input name='$name'$inputid value='1' type='checkbox' class='checkbox'$checked />";
				if (is_string($extra)) {
					if (strlen($extra) > 0) {
						$extra = self::cb_esc_html($extra);
						$html = "<label>$html&nbsp;$extra</label>";
					}
				}

				break;
			case 'dropdown':
				$html = "<select name='$name'$inputid onchange='javascript:cbe_toggle_select_children(this)' class='dropdown'>";
				if (is_array($extra)) $html .= self::option_tags($extra, $value); else $html .= $extra;
				$html .= "</select>";
				break;
			case 'hidden_hop':
				$html .= "*automatic hoplink*<input name='$name'$inputid value='$value' type='hidden' />";
				break;
			case 'hidden':
				$html .= "<input name='$name'$inputid value='$value' type='hidden' />";
				break;
		}


		if($br > 0) $html = $html . str_repeat('<br>',$br);
		return $html;
	}




	function option_tags($options, $current = true) {
		$html = '';
		foreach ($options as $value => $label) {
			if (is_array($label)) {
				$html .= "<optgroup label='$value'>\n".self::option_tags($label, $current)."</optgroup>\n";
			} else {
				//if (is_numeric($value)) $value = '';
				$html .= "\t<option value='$value'";
				if ($value == $current) $html .= " selected='selected'";
				$html .= ">$label</option>\n";
			}
		}
		return $html;
	}
	function cb_esc_attr($str) {
		$str = str_replace(array("\t", "\r\n", "\n"), ' ', $str);
		return esc_attr($str);
	}

	function cb_esc_html($str) {
		return esc_html($str);
	}
	function cb_esc_editable_html($str) {
		return _wp_specialchars($str, ENT_QUOTES, false, true);
	}

    /**
     * Create a form table from an array of rows
    */
    function form_table($rows,$alternate=1) {
            $content = '<table class="form-table">';
            $i = 1;
            foreach ($rows as $row) {
                $class = '';

                if($alternate == 1){
					if ($i > 1) $class .= 'cbp_row';
					if ($i % 2 == 0) $class .= ' even';
                }


				if($row['id'] == 'heading'){

					$content .= '<tr class="cbp_heading"><th colspan="2"><div class="cbp_heading">'.$row['label'].'</div></th></tr>';
				}else{

					// $class .= '';
	                $content .= '<tr class="'.$row['id'].'_row '.$class.'"><th valign="top" scrope="row">';
	                if (isset($row['id']) && $row['id'] != ''){
	                    $content .= '<label for="'.$row['id'].'">'.$row['label'].':</label>';
					}else{
	                    $content .= $row['label'];
					}
                	$content .= '</th><td valign="top">';
                	$content .= $row['content'];
                	if ( isset($row['desc']) && !empty($row['desc']) ) {
                    	$content .= '<div class="cbp_help_content"><div class="cbp_desc">'.$row['desc'].'</div></div>';
                	}
                	$content .= '</td></tr>';
               		/*
                	 if ( isset($row['desc']) && !empty($row['desc']) ) {
                    $content .= '<tr class="'.$row['id'].'_row '.$class.'"><td colspan="2" class="yst_desc"><small>'.$row['desc'].'</small></td></tr>';
                	}
                	*/

				}


                $i++;
            }

            $content .= '</table>';
            return $content;
    }

    // wpcb_get_uri
    function local_uri($path_to_file_from_root) {
        return plugins_url($path_to_file_from_root, __FILE__);
    }






    // cbCommunication




    static function createUrlAndQuery($url,$array){

    }
    static function cleanUrl($dirty_url){
        list($clean_url)= explode('?',htmlspecialchars(strip_tags($dirty_url),ENT_NOQUOTES));
        return $clean_url;
    }
    static function getMethod(){
        $tempMethod=$_SERVER['REQUEST_METHOD'];
        if(strcasecmp($tempMethod,cbpPUT)==0)
            return cbpPUT;
        else if(strcasecmp($tempMethod,cbpPOST)==0){
            if(isset($_POST['_method'])){
            if(strcasecmp($_POST['_method'],cbpPUT)==0)
                return cbpPUT;
            if(strcasecmp($_POST['_method'],cbpDELETE)==0)
                return cbpDELETE;
            }
            return cbpPOST;
        }else if(strcasecmp($tempMethod,cbpGET)==0)
            return cbpGET;
        else if(strcasecmp($tempMethod,cbpDELETE)==0)
            return cbpDELETE;
    }
    static function getQueryString(){
        if(defined('TESTING')){
            global $testquery;
            return $testquery;
        }else{
            global $wp_query;
            if(isset($wp_query) && !empty($wp_query->query_vars))
                return $wp_query->query_vars;
            else
                return $_GET;
        }
    }
    static function getFormValues($keys=false){
        if($keys){
        $values = array();
        $values=array_intersect_key($_POST,$keys);
        return $values;
        }
        return $_POST;
    }
    static function getUpload($keys){
        $files=array_intersect_key($_FILES,$keys);
        return $files;
    }
    static function getReferer(){
        if(function_exists('wp_get_referer'))
            return wp_get_referer();
        else
            return $_SERVER['HTTP_REFERER'];
    }

    static function redirectTo($url,$data=false){
            $data=ltrim($data,"&");
        if(is_array($data))
            $data=http_build_query($data);
        if(strpos($url,'?')===false)
            $redirect=$url."?".$data;
        else
            $redirect = $url."&".$data;
        if(function_exists('wp_redirect'))
            wp_redirect($redirect);
        else{
            header( 'Location: '.$redirect );
            exit;
        }
    }
    static function useRedirect(){
        return self::array_key_exists_v('_redirect',$_POST);
    }




        function array_key_exists_v($needle,$haystack){
            foreach($haystack as $key => $value){
                if($needle==$key) return $value;
            }
            return false;
        }

        function array_search_key($search,$haystack){
            $array= array();
            foreach($haystack as $key => $value){
                $sub=stristr($key,$search);
                if($sub) $array[$key]=$value;
            }
            return $array;
    }







function escaper($string, $esc_type = 'html', $char_set = 'ISO-8859-1'){
    switch ($esc_type) {
        case 'html':
            return htmlspecialchars($string, ENT_QUOTES, $char_set);

        case 'htmlall':
            return htmlentities($string, ENT_QUOTES, $char_set);

        case 'url':
            return rawurlencode($string);

        case 'urlpathinfo':
            return str_replace('%2F','/',rawurlencode($string));

        case 'quotes':
            // escape unescaped single quotes
            return preg_replace("%(?<!\\\\)'%", "\\'", $string);

        case 'hex':
            // escape every character into hex
            $return = '';
            for ($x=0; $x < strlen($string); $x++) {
                $return .= '%' . bin2hex($string[$x]);
            }
            return $return;

        case 'hexentity':
            $return = '';
            for ($x=0; $x < strlen($string); $x++) {
                $return .= '&#x' . bin2hex($string[$x]) . ';';
            }
            return $return;

        case 'decentity':
            $return = '';
            for ($x=0; $x < strlen($string); $x++) {
                $return .= '&#' . ord($string[$x]) . ';';
            }
            return $return;

        case 'javascript':
            // escape quotes and backslashes, newlines, etc.
            return strtr($string, array('\\'=>'\\\\',"'"=>"\\'",'"'=>'\\"',"\r"=>'\\r',"\n"=>'\\n','</'=>'<\/'));

        case 'mail':
            // safe way to display e-mail address on a web page
            return str_replace(array('@', '.'),array(' [AT] ', ' [DOT] '), $string);

        case 'nonstd':
           // escape non-standard chars, such as ms document quotes
           $_res = '';
           for($_i = 0, $_len = strlen($string); $_i < $_len; $_i++) {
               $_ord = ord(substr($string, $_i, 1));
               // non-standard char, escape it
               if($_ord >= 126){
                   $_res .= '&#' . $_ord . ';';
               }
               else {
                   $_res .= substr($string, $_i, 1);
               }
           }
           return $_res;

        default:
            return $string;
    }
}



    public function array_key_intersect(&$a, &$b) {
        $array = array();
        while (list($key,$value) = each($a)) {
            if (isset($b[$key])) $array[$key] = $value;
        }
        return $array;
    }



    function array_diff_assoc_recursive($array1, $array2) {
        foreach($array1 as $key => $value) {
        if(is_array($value))
        {
              if(!isset($array2[$key]))
              {
              $difference[$key] = $value;
              }
              elseif(!is_array($array2[$key]))
              {
              $difference[$key] = $value;
              }
              else
              {
              $new_diff = self::array_diff_assoc_recursive($value, $array2[$key]);
              if($new_diff != FALSE)
              {
                $difference[$key] = $new_diff;
              }
              }
          }
          elseif(!isset($array2[$key]) || $array2[$key] != $value)
          {
              $difference[$key] = $value;
          }
        }
        return !isset($difference) ? 0 : $difference;
    }





    function create_formfield($name,$type,$value,$valtochk=null)
    {
        switch ($type) {

        case "checkbox":
            echo "<input name=\"$name\" type=\"$type\" id=\"$name\" ";self::formfield_selected($value,$valtochk,$type);echo " />\n";
            break;

        case "text":
            echo "<input name=\"$name\" type=\"$type\" id=\"$name\" value=\"$value\" />\n";
            break;

        case "textarea":
            echo "<textarea cols=\"20\" rows=\"5\" name=\"$name\">$val</textarea>";
            break;

        case "password":
            echo "<input name=\"$name\" type=\"$type\" id=\"$name\" />\n";
            break;

        case "hidden":
            echo "<input name=\"$name\" type=\"$type\" value=\"$value\" />\n";
            break;

        }
    }



    function formfield_selected($value,$valtochk,$type=null)
    {
        if($value == $valtochk){ echo "checked"; }
    }



	static function hexToStr2($hex){
          $string = '';
          for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
              $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
          }
          return $string;
	}


	static function strToHex($input){
		return(bin2hex($input));
	}
	static function hexToStr($input) {
		return(pack("H*",$input));
	}





    // indents subcat with x number of characters for path length

    function indentCategory($fpath) {

        $iteration = count(explode(CBE_CATSEP,$fpath))-1;

        return str_repeat("-",$iteration);

    }





    static function ajax_error($message='') {
        $message = str_replace("'","",$message);
        $string = "({'success':false, 'error':'myerror'})";
        $string = str_replace('myerror',$message,$string);
        echo $string;
        die;
    }

    static function create_slug($str) {
        $str = preg_replace("/([_ ])/", "-", trim($str));
        $str = preg_replace("/([^0-9a-z-.])/", "", strtolower($str));
        $str = preg_replace("/(-){2,}/", "-", $str);
        return $str;
    }

    static function createslug($string) {
        return sanitize_title(trim(strtolower($string)));
    }



    static function thispage() {
        return self::getparam('page');
    }

    function is_cb_page() {

        $pg = self::thispage();
        if(substr($pg,0,8) == 'cbpress-' || $pg == 'cbpress'){
            return true;
        }
        return false;
    }


	/**
	 * Merges any number of arrays / parameters recursively,
	 *
	 * Replacing entries with string keys with values from latter arrays.
	 * If the entry or the next value to be assigned is an array, then it
	 * automagically treats both arguments as an array.
	 * Numeric entries are appended, not replaced, but only if they are
	 * unique
	 *
	 * @source http://us3.php.net/array_merge_recursive
	 * @version 1.4
	**/

	 function array_merge_recursive_distinct () {
	  $arrays = func_get_args();
	  $base = array_shift($arrays);
	  if(!is_array($base)) $base = empty($base) ? array() : array($base);
	  foreach($arrays as $append) {
		if(!is_array($append)) $append = array($append);
		foreach($append as $key => $value) {
		  if(!array_key_exists($key, $base) and !is_numeric($key)) {
			$base[$key] = $append[$key];
			continue;
		  }
		  if(is_array($value) or is_array($base[$key])) {
			$base[$key] = self::array_merge_recursive_distinct($base[$key], $append[$key]);
		  } else if(is_numeric($key)) {
			if(!in_array($value, $base)) $base[] = $value;
		  } else {
			$base[$key] = $value;
		  }
		}
	  }
	  return $base;
	}


	function days_since($date1, $return_number = false) {

		if(empty($date1)) return "";

		// In case a passed date cannot be ready by strtotime()
		// $date1 = apply_filters('CB_F_days_since', $date1);
		$date1 = apply_filters('cbpressfn_days_since', $date1);

		$date2 = date("Y-m-d");
		$date1 = date("Y-m-d", strtotime($date1));

				$future = false;
		// determine if future or past
		if(strtotime($date2) < strtotime($date1)) $future = true;

		$difference = abs(strtotime($date2) - strtotime($date1));
		$days = round(((($difference/60)/60)/24), 0);

		if($return_number)
			return $days;

		if($days == 0) {	return __('Today', CBPRESS_TRANS); }
		elseif($days == 1) { return($future ? " Tomorrow " : " Yesterday "); }
		elseif($days > 1 && $days <= 6) { return ($future ? " in $days days " : " $days days ago"); }
		elseif($days > 6) { return date(get_option('date_format'), strtotime($date1)); }
	}



	function nice_time($time, $args = false) {


 		$defaults = array('format' => 'date_and_time');
		extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );

		if(!$time) return false;

		if($format == 'date') return date(get_option('date_format'), $time);

		if($format == 'time') return date(get_option('time_format'), $time);

		if($format == 'date_and_time') return date(get_option('date_format'), $time) . " " . date(get_option('time_format'), $time);



		return false;

	}


    static function struct($cols='',$default='') {

        $out = new stdClass();

        if(strlen($cols)){
            $arr = explode(',',$cols);
            foreach($arr as $key){ $out->$key = $default;  }
        }


        // abort($cols);

        return $out;
    }

    static function struct222($cols='') {

        // $out = new stdClass();

        $data = array(); foreach(self::listtoarray($cols) as $c) $data[$c] = '';

        return self::a2o($data);

    }


	function arrayToObject(&$array) {
		if(!is_array($array)) {
			return $array;
		}

		$object = new stdClass();
		if(is_array($array) && count($array) > 0) {
			foreach($array as $name => $value) {
				$name = strtolower(trim($name));
				if(!empty($name)) {
					$object->$name = self::arrayToObject($value);
				}
			}
			return $object;
		} else {
			return false;
		}
	}

    static function o2a($object) { // object_to_array

        if(!is_object( $object ) && !is_array( $object )) return $object;

        if(is_object($object) ) $object = get_object_vars( $object );

        return array_map(array('cbpressfn' , 'o2a'), $object );
     }


    static function a2o($array = array()) { // array_to_object

        if (!empty($array)) {
            $data = false;
            foreach ($array as $akey => $aval) {
                $data -> {$akey} = $aval;
            }
            return $data;
        }
        return false;
    }
    static function listtoarray($lst,$delim=','){
        if(is_array($lst)) return $lst;
        return explode($delim,$lst);
    }
    static function l2a($lst,$delim=','){
        if(is_array($lst)) return $lst;
        return explode($delim,$lst);
    }

    public static function getCurrentURL()
    {
        $isHTTPS = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on");
        $port = (isset($_SERVER["SERVER_PORT"]) && ((!$isHTTPS && $_SERVER["SERVER_PORT"] != "80") || ($isHTTPS && $_SERVER["SERVER_PORT"] != "443")));
        $port = ($port) ? ':'.$_SERVER["SERVER_PORT"] : '';
        $url = ($isHTTPS ? 'https://' : 'http://').$_SERVER["SERVER_NAME"].$port.$_SERVER["REQUEST_URI"];
        return $url;
    }




    static function getparam($keyname,$default=null) {
        if (isset($_GET[$keyname])){
            return $_GET[$keyname];
        }else if(isset($_POST[$keyname])){
            return $_POST[$keyname];
        }
        return $default;
    }


    // like above but with a !not check
    function igetparam($keyname,$default=null) {
        $v = self::getparam($keyname);
        if (!$v) $v = $default;
        return $v;
    }



	function parse_tab($filename){
            $mappings = array();
            $id = fopen($filename, "r"); //open the file
            $data = fgetcsv($id, filesize($filename), "\t");
            if(!$mappings){
                $mappings = $data;
            }
            while($data = fgetcsv($id, filesize($filename), "\t")){
                if($data[0]){
                	$out = new stdClass();
                    foreach($data as $key => $value) {
                    	if($mappings[$key] != ''){
                    		$out->$mappings[$key] = addslashes($value);
						}
					}
					$arr[] = $out;
                }
            }
            fclose($id); //close file
            return $arr;
    }



    static function divtag( $content = '' ,$class= '' ) {
        if ( $class != '' ) $class = " class='$class'";
        return "<div$class>" . $content . '</div>';
    }

    static function html_link( $url, $title = '', $css='' ) {
        if ( empty( $title ) ) $title = $url;
        if ( $css != '' ) $css = " class='$css'";
        return sprintf( "<a href='%s'%s>%s</a>", esc_url( $url ), $css, $title );
    }

    static function cbe_html( $tag, $attributes = array(), $content = '' ) {
        if ( is_array( $attributes ) ) {
            $closing = $tag;
            foreach ( $attributes as $key => $value ) {
                $tag .= ' ' . $key . '="' . esc_attr( $value ) . '"';
            }
        } else {
            $content = $attributes;
            list( $closing ) = explode(' ', $tag, 2);
        }

        return "<{$tag}>{$content}</{$closing}>";
    }


    // used by hopads contextual
    // used by hopads contextual
    // used by hopads contextual

    static function get_stop_words() {
        /********** ***********/



        // $arr = $str;
        // $arr = explode(',',$arr);
        // $arr = array_unique($arr);
        // $arr = implode(',',$arr);
        // $arr = explode(',',$arr);



		$more = self::get_include_contents(CBP_FILES_DIR.'stop-words.txt');

		$more = explode("\r\n", trim($more));
		// $more = implode(',',$more) . ',' . $str;
        // $more = explode(',',$more);
        // $more = array_unique($more);
		// $out = implode("\r\n", $more);
		// echo '<pre>'.$out.'</pre>';
		// abort($more);
        // $arr = array_filter($arr);


        return $more;
    }


    static function remove_words($contents) {

        $contents = self::extract_keywords($contents,25);
        return $contents;
    }


	function keywords_to_array($keywords) {
    		if (!empty($keywords)) {
			        $keywords = stripslashes($keywords);
			        $keywords = strip_tags($keywords);
			        $keywords = str_replace(array("\r", "\n", "\t"), '', $keywords);
			        $keywords = str_replace(array(", ", " ,"), ',', $keywords);
					return explode(',',$keywords);
			}
			return array();
	}

	function toAscii($str, $replace = array(), $delimiter = ' '){
		if( !empty($replace) ){
			$str = str_replace((array)$replace, ' ', $str);
		}

		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

		return $clean;
	}
    static function extract_keywords($content,$num_to_ret = 25) {

        $stopwords = self::get_stop_words();


      //   abort($content);
        // $content = (preg_replace("/[^A-Za-z0-9\s\s+\-]/", "", $content));
		// $content = ereg_replace("[^A-Za-z0-9]", "", $content );

		// $content = preg_replace("/[^A-Za-z0-9\s\s+]/", "",$content);


        $wordlist = preg_split('%\s*\W+\s*%', strtolower($content));

        // Build an array of the unique words and number of times they occur.

        $a = array_count_values($wordlist);

        // Remove the stop words from the list.

        foreach ($stopwords as $word) {
            unset($a[$word]);
        }

        arsort($a, SORT_NUMERIC);

        $num_words = count($a);

        $num_to_ret = $num_words > $num_to_ret ? $num_to_ret : $num_words;

        $outwords = array_slice($a, 0, $num_to_ret);



        return implode(',', array_keys($outwords));
    }





    static function clean_string_input($string) {

        $interim = strip_tags($string);
        if(get_magic_quotes_gpc()) {
            $interim=stripslashes($interim);
        }
        // now check for pure ASCII input
        // special characters that might appear here:
        //   96: opening single quote (not strictly illegal, but substitute anyway)
        //   145: opening single quote
        //   146: closing single quote
        //   147: opening double quote
        //   148: closing double quote
        //   133: ellipsis (...)
        //   163: pound sign (this is safe, so no substitution required)
        //   10 lf , 13 cr
        //   these can be substituted for safe equivalents

        $result = '';
        for ($i=0; $i<strlen($interim); $i++) {
            $char = $interim{$i};
            $a_v = ord($char); // asciivalue
            if ($a_v == 96) {
                $result .= "\\'";
            } else if (($a_v > 31 && $a_v < 127) || ($a_v == 163) || ($a_v == 10) || ($a_v == 13)) {
                $result .= $char; // it's already safe ASCII
            } else if ($a_v == 145){
                $result .= "\\'";
            } else if ($a_v == 146){
                $result .= "'";
            } else if ($a_v == 147){
                $result .= '"';
            } else if ($a_v == 148){
                $result .= '"';
            } else if ($a_v == 133){
                $result .= '...';
            }
        }
        $result = str_replace("\n"," ",$result);
        $result = str_replace("'"," ",$result);
        $result = str_replace('"'," ",$result);
        $result = str_replace("%"," ",$result);
        $result = str_replace("“"," ",$result);
        $result = str_replace("”"," ",$result);
        $result = str_replace("’"," ",$result);
        $result = str_replace("‘"," ",$result);
        $result = str_replace("."," ",$result);
        $result = str_replace("("," ",$result);
        $result = str_replace(")"," ",$result);
        $result = str_replace("\\","",$result);
        $result = str_replace(",","",$result);
        $result = str_replace("!","",$result);

        return $result;
    }



    // END used by hopads contextual
    // END used by hopads contextual
    // END used by hopads contextual











    // returns current module directory url for requesting file     FROM FILE

    static function plugin_dir_url( $file ) {

        // __FILE__

        return trailingslashit( plugins_url( '', $file ) );
    }


    static function quotedValueList($in){
    		$in = is_array($in) ? implode(',',$in) : $in;
 		$in = "'" . str_replace(array("'", ","), array("\\'", "','"), $in) . "'";
		return $in;
    }
    static function toValuePair($result,$keycol,$valuecol=''){
        $data = array();
	   foreach ($result as $id => $r) {

	   	  $o = (is_object($r)) ? 1 : 0;
	   	  $r = (array) $r;
	   	  $v = $r[$keycol];

	   	  if(isset($r[$valuecol])){
		  		$data[$v] = $r[$valuecol];
		  }else{
		  		$r = ($o == 1) ? (object) $r : $r;
				$data[$v] = $r;
		  }
            unset($o,$id,$v,$r);
        }
        unset($result);
        return $data;
    }

    static function fetchPairs($result){
        // uses ARRAY_N
        $data = array();
        for($j=0; $j<count($result); $j++) {
            $data[$result[$j][0]] = $result[$j][1];
        }
        return $data;
    }



    // $c = cbpressfn::generateUniqueCode();
    // abort($c);

    // $get_vars = cbpressfn::getBuildGetVars('page');
    // abort($get_vars);

    static function getBuildGetVars($var='') {
        $get_vars = '';
        foreach ( (array) $_GET as $key => $val ) {
            if ( $key == $var ) {
                $get_vars = $key.'='.$val.'&';
                break;
            } else {
                $get_vars .= $key.'='.$val.'&';
            }
        }
        return $get_vars;
    }
    static function generateUniqueCode() {
        $microtime = microtime();
        $unique_code = substr(md5($microtime),0,8);
        return $unique_code;
    }



    static function isDirectoryWritable($path) {
        # generate random file
        $path = $path . uniqid(mt_rand()) . '.tmp';
        $file = @fopen($path, 'a');
        if ($file === false) {
            return false;
        }

        @fclose($file);
        @unlink($path);
        return true;
    }

    static function getMysqlDate($period) {
        # return a formatted, UTC mysql date (yyyy-mm-dd)
        switch ($period) {
            case 'now':
                # also return the time
                return date('Y-m-d H:i:s');
                break;
            case 'today':
                return date('Y-m-d');
                break;
            case 'month':
            default:
                return date('Y-m-01');
                break;
        }
    }

    static function get_post_id() {
        if (is_admin())
            return intval($_REQUEST['post']);
        elseif (in_the_loop())
            return intval(get_the_ID());
        elseif (is_singular()) {
            global $wp_query;
            return $wp_query->get_queried_object_id();
        }

        return false;
    }



    // Creates a search engine friendly link from a string
    // CBFRONT was getSefLinkFromString

    static function string_to_seflink($string) {
        $string = trim(strtolower($string));
        $string = preg_replace('/^\W+|\W+$/', '', $string);
        $string = preg_replace("@[^A-Za-z0-9\-_]+@i","-", $string);
        $string = preg_replace('{(-)\1+}', '-', $string);
        if (strlen($string) > 255) {
            $string = substr($string, 0, 255);
        }
        return $string;
    }



    static function product_permalink_base(){

        $token = '?cbgo=';
        return CBE_BLOGURL . $token;

        // echo self::get_template_name();

        // abort('');

        $token = cbengine_get_option('storetoken');
        $token1 = $cbeo->storetoken . '/';
        $token = '?cbgo=';
        return CBE_BLOG_URL . $token;

    }

    /**
     * Get the current page template and return its filename if it's a
     * PageSpot template.  Otherwise return false.
     *
     * @return false|string
     */
    static function get_template_name($id=null) {
        if (empty($id)) {
            $id = get_the_ID();
        }
        //$template_file = get_page_template();
        $template_file = TEMPLATEPATH.'/'.get_post_meta($id, '_wp_page_template', true);
        //print_r($template_file); exit;
        if (false === strpos(strtolower($template_file), '.php')
            || 0 !== strpos(basename(strtolower($template_file)), 'pagespot')
            || !file_exists($template_file)) {

            return false;
        }
        return $template_file;
    }


    // prepares product for display based on options




    static function get_filter(){
        global $cb_engine;
        return $cb_engine->get_module_settings('filter');
    }




    // used for product desciptions
    static function truncate($string, $limit, $break=".", $pad="...") {
        if(strlen($string) <= $limit) return $string;
        if(false !== ($breakpoint = strpos($string, $break, $limit))) {
            if($breakpoint < strlen($string) - 1) {
                $string = substr($string, 0, $breakpoint) . $pad;
            }
        }
        return $string;
    }


    // remove email @ address
    static function remove_email($text){
        return eregi_replace('([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})', '', $text);
    }

    // Remove Last Period
    static function RemovePeriod($str) {
        $str = trim($str);
        $last = $str[strlen($str)-1];
        if($last == '.'){
            $len = strlen($str);
            $str = substr($str,0,($len-1));
        }
        return $str;
    }

    static function getTime() {
        $a = explode (' ',microtime());
        return(double) $a[0] + $a[1];
    }





    /**
     * Convert a string from one UTF-8 char to one UTF-16 char.
     *
     * Normally should be handled by mb_convert_encoding, but
     * provides a slower PHP-only method for installations
     * that lack the multibye string extension.
     *
     * This method is from the Solar Framework by Paul M. Jones
     *
     * @link   http://solarphp.com
     * @param string $utf8 UTF-8 character
     * @return string UTF-16 character
     */
    static function _utf82utf16($utf8) {
            // Check for mb extension otherwise do by hand.
            if( function_exists('mb_convert_encoding') ) {
                return mb_convert_encoding($utf8, 'UTF-16', 'UTF-8');
            }

            switch (strlen($utf8)) {
                case 1:
                    // this case should never be reached, because we are in ASCII range
                    // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    return $utf8;

                case 2:
                    // return a UTF-16 character from a 2-byte UTF-8 char
                    // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    return chr(0x07 & (ord($utf8{0}) >> 2))
                         . chr((0xC0 & (ord($utf8{0}) << 6))
                             | (0x3F & ord($utf8{1})));

                case 3:
                    // return a UTF-16 character from a 3-byte UTF-8 char
                    // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    return chr((0xF0 & (ord($utf8{0}) << 4))
                             | (0x0F & (ord($utf8{1}) >> 2)))
                         . chr((0xC0 & (ord($utf8{1}) << 6))
                             | (0x7F & ord($utf8{2})));
            }

            // ignoring UTF-32 for now, sorry
            return '';
    }

    static function encodeUnicodeString($value) {
        $strlen_var = strlen($value);
        $ascii = "";

        /**
         * Iterate over every character in the string,
         * escaping with a slash or encoding to UTF-8 where necessary
         */
        for($i = 0; $i < $strlen_var; $i++) {
            $ord_var_c = ord($value[$i]);

            switch (true) {
            case (($ord_var_c >= 0x20) && ($ord_var_c <= 0x7F)):
                // characters U-00000000 - U-0000007F (same as ASCII)
                $ascii .= $value[$i];
                break;

            case (($ord_var_c & 0xE0) == 0xC0):
                // characters U-00000080 - U-000007FF, mask 110XXXXX
                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                $char = pack('C*', $ord_var_c, ord($value[$i + 1]));
                $i += 1;
                $utf16 = self::_utf82utf16($char);
                $ascii .= sprintf('\u%04s', bin2hex($utf16));
                break;

            case (($ord_var_c & 0xF0) == 0xE0):
                // characters U-00000800 - U-0000FFFF, mask 1110XXXX
                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                $char = pack('C*', $ord_var_c,
                     ord($value[$i + 1]),
                     ord($value[$i + 2]));
                $i += 2;
                $utf16 = self::_utf82utf16($char);
                $ascii .= sprintf('\u%04s', bin2hex($utf16));
                break;

            case (($ord_var_c & 0xF8) == 0xF0):
                // characters U-00010000 - U-001FFFFF, mask 11110XXX
                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                $char = pack('C*', $ord_var_c,
                     ord($value[$i + 1]),
                     ord($value[$i + 2]),
                     ord($value[$i + 3]));
                $i += 3;
                $utf16 = self::_utf82utf16($char);
                $ascii .= sprintf('\u%04s', bin2hex($utf16));
                break;

            case (($ord_var_c & 0xFC) == 0xF8):
                // characters U-00200000 - U-03FFFFFF, mask 111110XX
                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                $char = pack('C*', $ord_var_c,
                     ord($value[$i + 1]),
                     ord($value[$i + 2]),
                     ord($value[$i + 3]),
                     ord($value[$i + 4]));
                $i += 4;
                $utf16 = self::_utf82utf16($char);
                $ascii .= sprintf('\u%04s', bin2hex($utf16));
                break;

            case (($ord_var_c & 0xFE) == 0xFC):
                // characters U-04000000 - U-7FFFFFFF, mask 1111110X
                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                $char = pack('C*', $ord_var_c,
                     ord($value[$i + 1]),
                     ord($value[$i + 2]),
                     ord($value[$i + 3]),
                     ord($value[$i + 4]),
                     ord($value[$i + 5]));
                $i += 5;
                $utf16 = self::_utf82utf16($char);
                $ascii .= sprintf('\u%04s', bin2hex($utf16));
                break;
            }
        }

        return $ascii;
    }











    /**
     * Loads a webpage and returns its HTML as a string.
     *
     * @param string $url The URL of the webpage to load.
     * @param string $ua The user agent to use.
     * @return string The HTML of the URL.
     */


    static function load_webpage($url, $ua) {
		// $referer

        $options = array();
        $options['headers'] = array(
            'User-Agent' => $ua
        );

        $response = wp_remote_request($url, $options);

        if ( is_wp_error( $response ) ) return false;
        if ( 200 != $response['response']['code'] ) return false;

        return trim( $response['body'] );
    }

    // array2table (CAB)

    static function array2table($arr,$cols='',$colModel=''){
        $out = '';
        $count = count($arr);
		$use_headings = false;

		if(is_object($colModel)){
			$use_headings = true;



			// $heads = array_values($colModel);
			// $cols = array_values((array) $colModel);


			$heads = array_values((array) $colModel);
			$cols = array_keys((array) $colModel);

			$colModel = (array) $colModel;



		} else {
			$colModel = array();
	        if (!is_array($cols)) if(strlen($cols)) $cols = explode(',',$cols);
	        if (!is_array($cols)) $cols = array();
			$heads = $cols;
		}


       // if(count($cols) > count($headers)){    }

      //  abort(get_defined_vars());

        if($count > 0){
            reset($arr);
            $num = count(current($arr));
            $out .= "<table class=\"widefat\" align=\"center\" border=\"0\"cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";

            $out .= "<tr><thead>\n";


			$thead = '';



            foreach(current($arr) as $key => $value){


            		$label = $key;

                if(!count($cols)){
               		$cell = "<th>".$label."&nbsp;"."</th>\n";
                    $thead .= $cell;
                }else if(isset($colModel[$key])){
					$label = $colModel[$key];
               		$cell = "<th>".$label."&nbsp;"."</th>\n";
                    $thead .= $cell;

                }else if(in_array($key, $cols)){

               		$cell = "<th>".$label."&nbsp;"."</th>\n";
                    $thead .= $cell;
                }
            }
			$out .= $thead;
            $out .= "</thead></tr>\n";

            $out .= "<tr><tfoot>" . $thead . "</tfoot></tr>\n";


            $out .= "<tbody>";
            while ($curr_row = current($arr)) {
                $out .= "<tr>\n";
                $col = 1;

                foreach($curr_row as $key => $value){
                    $cell = "<td>".$value."&nbsp;"."</td>\n";
                    if(!count($cols)){
                        $out .= $cell;
                    }else if(in_array($key, $cols)){
                        $out .= $cell;
                    }
                }
                $out .= "</tr>\n";
                next($arr);
            }
            $out .= "</tbody>";
            $out .= "</table>\n";
        }
        return trim($out);
    }


    static function array_overlay($a1,$a2){
        foreach($a1 as $k => $v) {
            if(!array_key_exists($k,$a2)) continue;
            if(is_array($v) && is_array($a2[$k])){
            $a1[$k] = self::array_overlay($v,$a2[$k]);
            }else{
            $a1[$k] = $a2[$k];
            }
        }
        return $a1;
    }




    // arrays key prefix scoping (CAB)

    static function array_key_prefix( &$array, $prefix ) {
        $r = array();
        foreach ($array as $key=>$value) { $r[$prefix.$key] = $value; }
        return $r;
    }




    // Force script enqueue
    static function do_scripts( $handles ) {
        global $wp_scripts;

        if ( ! is_a( $wp_scripts, 'WP_Scripts' ) )
            $wp_scripts = new WP_Scripts();

        $wp_scripts->do_items( ( array ) $handles );
    }

    // Force style enqueue

    static function do_styles( $handles ) {
        self::do_scripts( 'jquery' );

        global $wp_styles;

        if ( ! is_a( $wp_styles, 'WP_Styles' ) )
            $wp_styles = new WP_Styles();

        ob_start();
        $wp_styles->do_items( ( array ) $handles );
        $content = str_replace( array( '"', "\n" ), array( "'", '' ), ob_get_clean() );

        echo "<script type='text/javascript'>\n";
        echo "jQuery( document ).ready( function( $ ) {\n";
        echo "$( 'head' ).prepend( \"$content\" );\n";
        echo "} );\n";
        echo "</script>";
    }

/**************************************

************************/




    // Enable delayed activation ( to be used with scb_init() )
    static function add_activation_hook( $plugin, $callback ) {
        add_action( 'cbe_activation_' . plugin_basename( $plugin ), $callback );
    }

    // Have more than one uninstall hooks; also prevents an UPDATE query on each page load
    static function add_uninstall_hook( $plugin, $callback ) {
        register_uninstall_hook( $plugin, '__return_false' );    // dummy

        add_action( 'uninstall_' . plugin_basename( $plugin ), $callback );
    }













    // Apply a function to each element of a ( nested ) array recursively

    static function array_map_recursive( $callback, $array ) {
        array_walk_recursive( $array, array( __CLASS__, 'array_map_recursive_helper' ), $callback );
        return $array;
    }

    static function array_map_recursive_helper( &$val, $key, $callback ) {
        $val = call_user_func( $callback, $val );
    }

    // Extract certain $keys from $array
    static function array_extract( $array, $keys ) {


        if (!is_array($keys) ){
            if(strlen($keys)) $keys = explode(',',$keys);
        }

        $r = array();
        foreach ( $keys as $key ){
            if ( array_key_exists( $key, $array ) ) $r[$key] = $array[$key];
        }

        return $r;
    }

    // Extract a certain value from a list of arrays
    static function array_pluck( $array, $key ) {
        $r = array();
        foreach ( $array as $value ) {
            if ( is_object( $value ) ) $value = get_object_vars( $value );
            if ( array_key_exists( $key, $value ) ) $r[] = $value[$key];
        }
        return $r;
    }

    // Transform a list of objects into an associative array

    static function objects_to_assoc( $objects, $key, $value ) {
        $r = array();
        foreach ( $objects as $obj ){ $r[$obj->$key] = $obj->$value; }
        return $r;
    }

    // Prepare an array for an IN statement

    static function array_to_sql( $values ) {
        foreach ( $values as &$val ){ $val = "'" . esc_sql( trim( $val ) ) . "'"; }
        return implode( ',', $values );
    }

    // Example: split_at( '</', '<a></a>' ) => array( '<a>', '</a>' )
    static function split_at( $delim, $str ) {
        $i = strpos( $str, $delim );
        if ( false === $i ) return false;
        $start = substr( $str, 0, $i );
        $finish = substr( $str, $i );
        return array( $start, $finish );
    }















    ###########################################################################
    ###########################################################################

    /**** CBFRONT helpers.php ****/

    ###########################################################################
    ###########################################################################


    /* getIpAddress */
    public static function getIpAddress($returnAsUnsigned = false) {
        $ip = @$_SERVER['REMOTE_ADDR'];
        if (!$ip) {
            $ip = 0;
        }

        if ($returnAsUnsigned) {
            return ip2long($ip);
        } else {
            return $ip;
        }
    }
    public static function searchInArray($needle, $haystack, $inverse = false, $limit = 1) {
        $path = array ();
        $count = 0;
        if ($inverse) {
            $haystack = array_reverse($haystack, true);
        }
        foreach($haystack as $key => $value) {
            # check for return
            if ($count > 0 && $count == $limit) {
                return $path;
            }

            # check for val
            if ($value === $needle) {
                $path[] = $key;
                $count++;
            } else if(is_array($value)) {
                # fetch subs
                $sub = self::searchInArray($needle, $value, $inverse, $limit);

                # check if there are subs
                if (count($sub) > 0) {
                    $path[$key] = $sub;
                    $count += count ($sub);
                }
            }
        }

        return $path;
    }
    public static function inArrayMulti($needle, $array) {
        foreach ($array as $key => $item) {
            # if item is not an array, check the value of the string
            if (!is_array($item)) {
                if ($item == $needle) {
                    return true;
                }
            } else {
                # see if this array matches our value
                if (in_array($needle, $item)) {
                    return true;
                } else if (self::inArrayMulti($needle, $item)) {
                     return true;
                }
            }
        }
        # couldn't find the value in array
        return false;
    }


    public static function generatePassword($length = 9, $strength = 0) {
        $vowels = 'aeuy';
        $consonants = 'bdghjmnpqrstvz';
        if ($strength & 1) {
            $consonants .= 'BDGHJLMNPQRSTVWXZ';
        }
        if ($strength & 2) { $vowels .= "AEUY"; }
        if ($strength & 4) { $consonants .= '23456789'; }
        if ($strength & 8) { $consonants .= '@#$%'; }
        $password = '';
        $alt = time() % 2;
        for ($i = 0; $i < $length; $i++) {
            if ($alt == 1) {
                $password .= $consonants[(rand() % strlen($consonants))];
                $alt = 0;
            } else {
                $password .= $vowels[(rand() % strlen($vowels))];
                $alt = 1;
            }
        }
        return $password;
    }

    // Finds and returns changed elements of two associative arrays.
    public static function findChangedProperties($array1, $array2, $escapeHTML) {
        $newArray = array();
        foreach ($array1 as $key => $value) {
            if (isset($array2[$key])) {
                if ($value <> $array2[$key]) {
                    if ($escapeHTML) {
                        $newArray[$key] = htmlentities($array2[$key]);
                    } else {
                        $newArray[$key] = $array2[$key];
                    }
                }
            }
        }
        return $newArray;
    }




    // add an element at the beginning of an array:
    // $item can be of any type
    public static function array_rpush(&$arr, $item){
        $arr = array_pad($arr, -(count($arr) + 1), $item);
    }




    public static function get_include_contents($filename) {
          if (is_file($filename)) {
          ob_start();
          include $filename;
          $contents = ob_get_contents();
          ob_end_clean();
          return $contents;
          }
          return false;
    }
    public static function toArray($data){
        $array = array();
        foreach ($data as $key => $value) {
            $array[$key] = $value;
        }
        return $array;
    }




    ###############################################################################
    /**
     * getDateTimeFormats
     *
     * Returns an array of various date/time formats.
     */
     ##############################################################################

    public static function getDateTimeFormats() {
        $array = array(
            'n/j/Y g:i A', 'n/d/Y g:iA', 'D d M, Y',
            'D d M, Y g:i a', 'D d M, Y H:i', 'D M d, Y',
            'D M d, Y g:i a', 'D M d, Y H:i', 'D jS M g:i a',
            'D jS M H:i', 'jS F Y', 'jS F Y, g:i a',
            'jS F Y, H:i', 'F jS Y', 'F jS Y, g:i a',
            'F jS Y, H:i', 'j/n/Y', 'j/n/Y, g:i a',
             'j/n/Y, H:i', 'n/j/Y', 'n/j/Y, g:i a',
             'n/j/Y, H:i', 'Y-m-d', 'Y-m-d, g:i a',
            'Y-m-d, H:i');
        return $array;
    }




    /**** CBFRONT END ****/



















	static function selectDropdownSorted($id,$array,$selectedValues=false,$dontprint=false){
		$select="<select id=\"$id\" name=\"$id\" >";
		foreach($array as $key => $pair){
			if(is_array($pair)){

				if(is_string($pair['parent']) || is_int($pair['parent']))
					$select.=self::option(str_replace('"','',$key),$pair['display'],$selectedValues==$key,true);
				else
					$select.=self::option($pair['parent']->id,$pair['parent'],$selectedValues==$pair['parent'].'',true );
				$children=$pair['children'];
				if(is_array($children)){
					foreach($children as $key2=>$element){
						if(is_string($element) || is_int($element)){
							$select.=self::option(str_replace('"','',$key2),' - '.$element,$selectedValues==$key2,true);
						}
						else
							$select.=self::option($element->id,' - '.$element,$selectedValues==$element.'',true );
					}
				}
			}else{

				// abort($pair);
				if(is_string($pair) || is_int($pair)) {
					$select.=self::option(str_replace('"','',$pair),$pair,$selectedValues==$pair,true);
				} else {



					$select.= self::option($pair->id,$pair,$selectedValues==$pair.'',true );
					// abort($pair->id);
				}
			}
		}
		$select.='</select>';
		if($dontprint)
			return $select;
		echo $select;
	}
    static function option($value,$display,$selected=false,$dontprint=false){
		$text="<option value=\"$value\">$display</option>";
		if($selected)
		$text="<option selected=\"selected\" value=\"$value\">$display</option>";
		if($dontprint)
		return $text;
		echo $text;

	}


	static function input($id,$type,$value,$class=false,$dontprint=false){
		$class=$class?"class=\"$class\" ":'';
		// if($dontprint)
		return "<input id=\"$id\" name=\"$id\" type=\"$type\" value=\"$value\"  $class />";
		// echo  "<input id=\"$id\" name=\"$id\" type=\"$type\" value=\"$value\"  $class />";
	}
	static function selectSimple($id,$array,$selectedValues='',$dontprint=false){

		// echo $id;
		if($id == '_min_commission'){

			// abort($selectedValues);

		}

		$select="<select id=\"$id\" name=\"$id\" >";
		if(is_array($array))
			foreach($array as $key=>$element){
				if(is_string($element) || is_int($element)){
					$select.=self::option(str_replace('"','',$key),$element,$selectedValues==$key||$selectedValues==$element,true);
				}
				else{
					$select.=self::option($element->id,$element,$selectedValues==$element.'',true );
				}
			}
		$select.='</select>';
		// if($dontprint)
		return $select;
		// echo $select;
	}




	static function editLink($uri,$text,$id,$nonce,$dontprint=false){
		$nonce=wp_create_nonce($nonce);
		if($dontprint) return "<a href=\"$uri&Id=$id&_asnonce=$nonce\" class=\"button-secondary\" >$text</a>";
		echo "<a href=\"$uri&Id=$id&_asnonce=$nonce\" class=\"button-secondary\" >$text</a>";
	}
	static function viewLink($uri,$text,$id,$dontprint=false){
		if($dontprint) return "<a href=\"$uri&Id=$id\" class=\"button-secondary\" >$text</a>";
		echo "<a href=\"$uri&Id=$id\" class=\"button-secondary\" >$text</a>";
	}
	static function ax($text,$path,$class=false,$target=false,$onclick=false,$dontprint=false){
		$class=$class?" class=\"$class\" ":'';
		$target=$target?" target=\"$target\" ":'';
		$onclick=$onclick?" onclick=\"$onclick\" ":'';
		$text=stripslashes($text);
		if($dontprint) return "<a href=\"$path\" $class>$text</a>";
		echo "<a href=\"$path\" $class>$text</a>";
	}
	static function a($text,$path,$class=false,$dontprint=false){
		$class=$class?" class=\"$class\" ":'';
		$text=stripslashes($text);
		if($dontprint) return "<a href=\"$path\" $class>$text</a>";
		echo "<a href=\"$path\" $class>$text</a>";
	}



	static function paging($href,$total,$currentpage,$perpage,$dontprint=false){
		$paging='<div class="tablenav-pages">';
		$paging.='<span class="displaying-num">';

		$pages=ceil($total/$perpage);
		$pages=$pages>15?15:$pages;
		$start=$total?($perpage*$currentpage-$perpage+1):0;
		$end=($perpage*$currentpage<=$total)?$perpage*$currentpage:$total;
		$paging.="Displaying $start-$end of ".'<span class="total-type-count">'.intval($total).'</span></span>';
		if($pages>10 && ($currentpage-1)>1)
			$paging.=self::a('&laquo;',"$href&current=".intval($page).'&perpage='.intval($perpage),'page-numbers prev',true).' ';
		if($pages>1)
			for($page=1;$page<=$pages;$page++){
				if(fmod($page,10)==0){
					$paging.=self::a("[$page]","$href&current=".intval($page).'&perpage='.intval($perpage),'page-numbers',true);
					if($page+10<=$pages)
						$page+=9;
				}
				else if($page!=$currentpage)
					$paging.=self::a($page,"$href&current=".intval($page).'&perpage='.intval($perpage),'page-numbers',true);
				else
					$paging.='<span class="page-numbers current">'.intval($currentpage).'</span>';
			}
		if($pages>10 && ($currentpage-1)<$pages)
			$paging.=' '.self::a('&raquo;',"$href&current=".intval($page).'&perpage='.intval($perpage),'page-numbers next',true);
		$paging.="</div>";
		if($dontprint)
			return $paging;
		echo $paging;

	}


	static function table($id,$data,$headlines=false,$nonce=false,$useLinks=false){
		$tbody = '';
		$table="<table id=\"$id\" class=\"ui-widget ui-corner-all\">\n";
		$tbody.="<tbody>\n";
		foreach($data as $row){
			$class=strtolower(get_class($row));
			$tbody.="<tr>\n";
			$tbody.="<td class=\"first\" style=\"vertical-align:middle;\">".self::editLink(admin_url("admin.php?page=$class&action=edit"),'Edit',$row->getId(),$nonce,true)."</td>\n";
			if(!$headlines)
				$tbody.="<td class=\"center\" style=\"width:20px;text-align:center\">".self::input($class.'[]','checkbox',$row->getId(),'all',true)."</td>\n";
			$tbody.="</tr>";
		}
		$tbody.="</tbody>\n";
		$ths='';
		foreach($headlines as $column){
			if(is_array($column))
					$column=$column[0];
			$ths.="<th class=\"".strtolower($column)."\">$column</th>\n";
		}
		$table.="<thead>\n<tr>\n<th class=\"edit\"></th><th class=\"center\" style=\"width:20px;text-align:center\">".self::input('selectAllTop','checkbox','all',false,true)."</th>$ths\n<th class=\"delete\"></th></tr></thead>";
		$table.="<tfoot>\n<tr>\n<th></th>\n<th class=\"center\" style=\"width:20px;text-align:center\">".self::input('selectAllBottom','checkbox','all',false,true)."</th>$ths\n<th class=\"delete\"></th></tr></tfoot>";
		$table.=$tbody;
		$table.="</table>\n</div>";

		$script="
		jQuery('#selectAllTop').click(function(){
		val=this.checked;
		jQuery('.all').each(function(index) {this.checked=val;});
		jQuery('#selectAllBottom').attr('checked',this.checked);
		});
		jQuery('#selectAllBottom').click(function(){
		val=this.checked;
		jQuery('.all').each(function(index) {this.checked=val;});
		jQuery('#selectAllTop').attr('checked',this.checked);
	});
		";
		// self::registerFooterScript($script);
		echo $table;
	}



}
