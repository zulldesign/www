<?php
class CBP_actions  {

	var $running 	= '';
	var $msg 	= '';
	var $err 	= false;
	var $comp, $form, $ref;

	var $actions = array(
			'save-cat',
			'save-list',
			'save-list-item',
			'list-item-delete',
			'list-delete',
			'list-topfive',
			'save-prod',
			'save-opts',
			'search-box'
		);

	public function __construct() {

		// parent::__construct();


		// $this->options = &$cbpress->options;

		$this->options  = CBP_settings::getter();

		$this->ref = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';
		$this->actions = array_map(array(&$this,'_actionize'), $this->actions);
		// abort($this->actions);


		// CBP::slasher();

	}



	function _precom($act)
	{
		$_act = @$_REQUEST['action'];
		$running = $_act == 'cbp-'.$act;





		if ( $running ) {

			$arr = array('save-cat','save-list','save-prod');
			if(in_array($act, $arr)){

				$name = str_replace("save-", "", $act);
				$this->ref = remove_query_arg('msg', $this->ref);


				if(isset($_POST[$name.'form'])){

					check_admin_referer( CBP_HOOK_NONCE );
					$classn = "CBP_$name";



					// dump(get_defined_vars());

					$this->form = stripslashes_deep( $_POST[$name.'form'] );
					$this->comp = new $classn($this->form); // preload class if running


				}
			}
		}
		return $running;
	}

	function _actionize($act)
	{
		$method = str_replace('-','_',$act); 	// call in this class


		add_action("admin_action_cbp-$act", array( &$this, 'hook_'.$method ) );
		return array(
			  'action'=>$act
			, 'active'=>$this->_precom($act)
			, 'method'=>$method
		);
	}

	function _redirect()
	{
			CBP::redirector($this->msg);
	}


	function hook_save_opts()
	{


		check_admin_referer( CBP_HOOK_NONCE );
		$this->ref = remove_query_arg('msg', $this->ref);
		$this->msg = '';
		if(isset($_GET['delete-settings-cbpress']) || isset($_POST['delete-settings-cbpress'])) {
			$this->msg = 'Default settings have been restored';
			$this->options->delete();
		}
		if(isset($_POST['update-options-cbpress'])) {
			$params = $_POST['cbpress_options'];
			if (get_magic_quotes_gpc()) $params  = array_map( 'stripslashes_deep', $params );
			foreach($params as $name => $value) {
				$this->options->{$name} = $value;
			}
			$this->options->save();
			$this->msg = 'Settings updated';
		}




		do_action('cbp-save-css');



		$this->_redirect();
	}

	function hook_save_stylesheet()
	{
	}
	function hook_save_cat(){


		if ( false === ($id = $this->comp->save()) ) {

			$this->msg = $this->comp->msg;

		} else {


			$this->msg = 'Category saved';
		}




		// abort($_REQUEST);
		// abort($this->comp);
		// abort('');
		if(CBP::is_ajax_request()){
				$json = array('err'=> $this->comp->err, 'msg'=> $this->comp->msg, 'cid' => $id);
				echo json_encode($json); die;
		}else{
			$this->_redirect();
		}
	}

	function hook_save_prod(){
		if ( false === ($id = $this->comp->save()) ) {


			$this->msg = $this->comp->msg;
			$this->_redirect();

		}else{

			$cids = (isset($this->form['cids_list'])) ? $this->form['cids_list'] : '';

			$cids = explode(',',$cids);

			$this->comp->update_tree($cids);


			$this->ref = CBP::admin('products') . '&lid='.$id;

			// $base = admin_url("admin.php?page=cbpress-products");

			$this->msg = 'Product saved';
			CBP::redirector($this->msg, $this->ref);
		}
	}


	## list actions

	function hook_save_list(){

		if ( false === ($id = $this->comp->save()) ) {
			$this->msg = $this->comp->msg;
		}else{

			$this->msg = 'List saved';
		}
		$this->_redirect();
	}

	function hook_list_topfive(){
		$defaults = array('active'=>1, 'pid' =>0, 'perpage'=>5, 'limit'=>5, 'sort'=>'rank', 'order'=>'asc', 'min_rank'=>1, 'max_rank'=>1);

		$data = CBP_query::getSearch($defaults);
		$lids = array();
		foreach($data->result as &$row){
			$lids[] = $row->lid;
		}
		$listname = "Top Products";
		$list_id = CBP_lists::create($listname);
		CBP_lists::add_items($list_id,$lids);

		$this->msg = 'Example list has been created';

		$this->_redirect();
	}
	function hook_list_delete(){
		$list_id = @$_REQUEST['list_id'];
		if($list_id > 0){
			$listid = CBP_lists::delete_list($list_id);
		}
		$this->msg = 'list ' . $list_id . ' has been deleted';
		$this->_redirect();
	}

	function hook_list_item_delete(){

		$list_id = @$_REQUEST['list_id'];
		$lid = @$_REQUEST['lid'];

		if($list_id > 0 && strlen($lid)){
			if($lid > 0){
				CBP_lists::delete_item($list_id,$lid);
			}
		}

		$this->msg = 'item ' . $lid . ' has been removed from the list';
		$this->_redirect();
	}

	function hook_save_list_item(){

		$list_id = @$_REQUEST['list_id'];
		$hop = @$_REQUEST['hop'];
		$lid = @$_REQUEST['lid'];
		if($list_id > 0){
			if(strlen($hop)){ $lid = CBP_query::hoptolid($hop); }
			$ids = trim(implode(',', array_map('trim', explode(',',$lid))));
			if(strlen($ids)){
				$ids = explode(',',$ids);
				$ids = array_map('trim', $ids);
				CBP_lists::add_items($list_id,$ids);
			}
		}

		$this->msg = 'items add to list';

		$this->_redirect();
	}


}