<?php 

// for pagination of
//  two types ,nomal link and dropdown menu
// for normal links pass 'links' as the third parameter while creating the object and for dropdown pagination  pass 'dropdown'.

class CBP_pagi {
		var $max_results_per_page=0; // no. of results to be shown per page
		var $total_results=0;        //no. of all  results to be shown over all pages
		var $max_pages =0;          // max. pages i.e the maximum number of pages for a particular query , calculated in the constructor
		var $current_page=0 ;      // the page no. that is currently rendered
		var $pagination_string='';  // the output string , which contains , the actual pagination  links/dropdown
		var $page_x_of_y = '';     // the output string , which contains  the string like 'page 1 of 2'
		var $qry_string = '';     // the query string with the address ,useful when the pagination is done on pages which has search feature as the GET parameters are not lost
		var $param = 'cbpg';
		

		function __construct($total_res,$res_per_page,$pagination_type){

			$this->max_results_per_page = $res_per_page;
			$this->total_results = $total_res;
			$this->max_pages = ceil($total_res/$res_per_page);

			$query_string = '';
			if(is_array($_GET)){
				foreach($_GET as $key=>$value){
					if($key!=$this->param) $query_string .= '&'.$key.'='.$value;
				}
			}
			$this->qry_string = $query_string;

			if(isset($_REQUEST[$this->param])){
				if($_REQUEST[$this->param]>$this->max_pages){
					$this->current_page = $this->max_pages;
				}else if($_REQUEST[$this->param]<1){
					$this->current_page = 1;
				}else{
					$this->current_page = $_REQUEST[$this->param];
				}
			}else{
				$this->current_page = 1;
			}
			$this->page_x_of_y = 'page '.$this->current_page.' of '.$this->max_pages;
			switch($pagination_type){
				case 'links':
					$this->page_links();
					break;
				case 'dropdown':
					$this->page_dropdown();
					break;
				default : die('Sorry no such pagination style as "'.$pagination_type.'"  exist');
			}
		}
		public function prev_next(){
			$nav = '';
			$out = (object) array();
			$out->next = '&nbsp; next &gt;&gt;'; // we're on the last page, don't print next link
			$out->last = '&nbsp;'; // nor the last page link
			$out->prev  = '&lt;&lt; prev &nbsp;'; // we're on page one, don't print previous link
			$out->first = '&nbsp;'; // nor the first page link
				
			if ($this->current_page > 1){
				$page  = $this->current_page-1;
				$out->prev  = "<a href='".$_SERVER['PHP_SELF']."?{$this->param}=$page".$this->qry_string."'>&lt;&lt; prev</a> &nbsp;";
			}
			if ($this->current_page < $this->max_pages  ){
				$page = $this->current_page + 1;
				$out->next = "&nbsp; <a href='".$_SERVER['PHP_SELF']."?{$this->param}=".$page.$this->qry_string."'>next &gt;&gt;</a> ";
			}			
			return $out;
		}
		public function page_links(){
			$out = $this->prev_next();
			$nav = '';

			for($page = 1; $page <= $this->max_pages; $page++){
				if ($page == $this->current_page){
					$nav .= " $page "; // no need to create a link to current page
				}else{
					if($page>=$this->current_page-5 && $page<=$this->current_page+5) $nav .= "<a href='".$_SERVER['PHP_SELF']."?{$this->param}=$page"."$this->qry_string'>".$page."</a> ";
				}
			}
			$this->pagination_string = $out->prev . $nav . $out->next;

		}
		public function page_dropdown(){ 

			$out = $this->prev_next();
			
			$nav = "<script>function change_page(val,page,qry_st){ window.location = page+'?{$this->param}='+val+qry_st; }</script>";
			$nav .= "<select style='background:#eeeeee;' name='{$this->param}' onChange=change_page(this.value,'".$_SERVER['PHP_SELF']."','".$this->qry_string."')>";
			for($page = 1; $page <= $this->max_pages; $page++) {

				if ($page == $this->current_page) {
				  $nav .= "<option value='".$page."' selected>".$page."</option> ";
				}else{
				  $nav .= "<option value='".$page."' >".$page."</option> ";
				}
			}
			$nav .= "</select>";
			
			$this->pagination_string = $out->prev . $nav . $out->next;
		}
		public function get_pagination_query($qry){
			$offset = ($this->current_page - 1) * $this->max_results_per_page;
			$max=" LIMIT $offset, $this->max_results_per_page";
			$new_query = $qry."".$max;
			return $new_query;
		}

	}

