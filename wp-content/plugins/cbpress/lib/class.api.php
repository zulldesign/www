<?php
class CBP_api {

	static protected $_regdata = null;
	static protected $_default = array('aff'=>'','rec'=>'','api'=>'','upd'=>'','agreed'=>0,'installed'=>0,'activated'=>0);
	static protected $_onload = array('mode'=>'demo', 'msgid'=>'demo', 'affiliate'=>'cbpress');

	static protected $_remotes  = array(
		'verify'=> '687474703a2f2f636270726573732e636f6d2f77702d636f6e74656e742f706c7567696e732f636270726573732d69706e2d746f6f6c2f6170692f7665726966792e706870',
		'activate'=> '687474703a2f2f636270726573732e636f6d2f77702d636f6e74656e742f706c7567696e732f636270726573732d69706e2d746f6f6c2f6170692f61637469766174652e706870'
	);

		static protected $_message = array(
		'http' => '' ,
		'success' => 'Registered' ,
		'found' => 'Registered' , 		'demo' => 'Please take a minute to activate cbpress',
		'fail' => 'There is a problem with your receipt number. Click here to fix',
		'noaff' => 'Please enter your clickbank affiliate id to activate' ,
		'norec' => 'Please enter your clickbank receipt number to activate' ,
		'mismatch' => 'Your activation info could not be validated. Click here to fix' ,
		'noipn' => 'An order does not exist for your ClickBank Receipt. Click here to fix' ,
		'edits' => 'There is a problem with your ClickBank Affiliate ID. Click here to fix',
		'terms' => 'You must agree to the terms of service by checking the box'
	);
		static protected $_problem = array(
		'http' => '' ,
		'success' => 'You are currently activated.' ,
		'found' => 'You are currently activated.' , 		'demo' => 'You are currently in DEMO mode, please activate',
		'fail' => 'Your receipt is not valid or a refund was issued', 		'noaff' => 'You have not entered your ClickBank affiliate id' ,
		'norec' => 'You have not entered your ClickBank receipt number' ,
		'mismatch' => 'Your affiliate id or receipt do not match your activation' ,
		'noipn' => 'An order does not exist for your ClickBank Receipt.' ,
		'edits' => 'Your ClickBank Affiliate ID could not be changed. Please email support@cbpress.com' ,
		'terms' => 'You must agree to the terms of service by checking the box'
	);

		static protected $_notices = array(
		'http' => 'Could not connect, please try again' ,
		'success' => 'Your activation was successful' ,
		'found' => 'Your activation was successful' ,
		'demo' => '',
		'fail' => 'Your receipt is not valid or a refund was issued', 		'noaff' => 'Please enter a valid ClickBank Affiliate ID' ,
		'norec' => 'Please enter a valid ClickBank Receipt' ,
		'mismatch' => 'Incorrect ClickBank Receipt or ClickBank Affiliate ID entered' ,
		'noipn' => 'An order does not exist for the ClickBank Receipt number entered.' ,
		'edits' => 'Your ClickBank Affiliate ID has been changed too many times for this Receipt #. Please email support@cbpress.com' ,
		'terms' => 'You must agree to the terms of service by checking the box'
	);

	static protected $_steps = array('terms','setup','activation','activation','activation','activation' );

	static protected $_usejson = false;
	static protected $_valid  = false;
	static protected $_timeout = array( 'verify'=> 43200, 'fail'=> 60 );
	static protected $_msgid = 'demo';
	static protected $_request = array();
	static protected $_formfill = array();
	static protected $_testing = null;
	static protected $_cacheurl = null;
	static protected $_cachekey = null;

	static protected $_structs = null;

	static protected $_opts = null;



		private function load() {
		$str = get_option(CBP_REGDATA);
		$str = base64_decode($str);
				$data = unserialize($str);
		if(! is_array($data)) $data = self::$_default;
		$data = array_map('trim', $data);


		$data = CBP::array_minimize($data, self::$_default, false); 		$data = array_merge($data, self::$_onload); 		$data = (object) $data;


		return $data;
	}

	private function get_uri($loco) {

		return pack("H*",self::$_remotes[$loco]);
	}

	function init($testing=0) {



		if(self::$_regdata === null){

			self::$_testing = (self::$_testing === null) ? $testing : self::$_testing;

			self::$_request = stripslashes_deep( $_REQUEST );

			add_filter('cbpress_base_options', array(__CLASS__, 'options_filter' ));

			self::$_notices = (object) self::$_notices;
			self::$_message = (object) self::$_message;
			self::$_problem = (object) self::$_problem;
			self::$_timeout  = (object) self::$_timeout;

			self::$_cacheurl = self::get_uri('verify');
			self::$_cachekey = 'cbpress_' . md5(self::$_cacheurl);


			self::$_regdata = self::load();


			add_action("admin_action_cbp-terms", array( __CLASS__, 'process_terms' ) );
			add_action("admin_action_cbp-setup", array( __CLASS__, 'process_setup' ) );
			add_action("admin_action_cbp-activation", array(__CLASS__, 'process_activation' ) );

			self::validate();
			self::get_level();

			self::$_formfill = array_merge( (array) self::$_regdata, (array) self::$_request, $_GET);


		}
		return self::get_regdata();
	}


	private function get_transient() {

		$reg = self::get_regdata();
		$cacheurl = self::$_cacheurl;
		$cachekey = self::$_cachekey;


		$uri = $cacheurl . '?rec=' . $reg->rec;

		if (false === ($data = get_transient( $cachekey ))) {

			$response = wp_remote_retrieve_body(wp_remote_get($uri));

			if (empty($response)) return self::set_trans('0','fail');

			$data = json_decode($response, true);

			if(! isset($data['aok'])) return self::set_trans('0','fail');

			$data['upd'] = date('Y-m-d H:i:s');
		}

		$out = self::set_trans($data,'verify');


		return $out;

	}
	private function set_trans($data,$timeout) {
		set_transient(self::$_cachekey, $data, self::$_timeout->$timeout);
		return $data;
	}



	private function validate() {

		$reg = self::get_regdata();

		$serial = self::serial($reg->rec,$reg->aff);

		$msgid = 'success';

		if ($serial == 0 || $reg->activated == 0){


			$msgid = 'demo';

		/**************

		} else if ($serial != $reg->api){

			if($reg->rec == '' && $reg->aff != ''){

				$msgid = 'norec';

			} else if ($reg->aff == '' && $reg->rec != ''){

				$msgid = 'noaff';

			} else {

				$msgid = 'mismatch';
			}
		*************/


		}else{

			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
				$msgid = 'success';
			}else{

				$response = self::get_transient();

				if(is_string($response)){
					$msgid = 'success';
				} else {
					$response = (object) $response;
					$response->aok = (isset($response->aok)) ? intval($response->aok) : intval(0);
						$msgid = 'fail';
					if($response->aok == 1){
						$msgid = 'success';
						self::set('activated','1');
						self::save();
					}
				}
			}

		}


		$msgid = 'success';



		self::$_msgid = $msgid;
		$msg = self::set_msg($msgid);
		if($msgid == 'success'){
			self::set('activated','1');
			self::set('mode','pro');
			self::set('affiliate',self::get('aff'));
		}else{
			self::set('activated','0');
		}


	}

	public function get_alerts($id='') {


		$msgid = ($id != '') ? $id : self::get('msgid');
		$out = (object) array();
		$out->msg = self::$_message->$msgid;
		$out->problem = self::$_problem->$msgid;

		$out->notice = '';



		if($msgid == 'norec'){
			$msgid = 'success';
		}



		if(isset($_REQUEST['msgid'])){
			$out->notice = self::$_notices->$msgid;

		}

		return $out;
	}
	private function set_msg($id) {
		return self::set('msgid',$id);
	}

	function delete() {
		delete_transient(self::$_cachekey);
		delete_option(CBP_REGDATA);

	}

	function save() {
		$data = (array) self::get_regdata();












		$data['upd'] = date('Y-m-d H:i:s');
		$data = CBP::array_minimize($data, self::$_default, false);
		$str = serialize($data);

		$str = base64_encode($str);
		add_option(CBP_REGDATA,$str);
		update_option(CBP_REGDATA,$str);


		self::$_regdata = self::load();
		self::get_level();


	}


	function backlink() {

		$aff = self::get('aff');
		if($aff == ''){
						$out = 'http://e0972xiaq6wmrt17piz9-d0y7s.hop.clickbank.net/';
		} else{
			$out = "http://$aff.cbpress.hop.clickbank.net/";
		}
		$out = '<div><div class="cbpress-backlinkx"><a href="'.$out.'" target="_blank"><span>CB PRESS</span></a></div></div>';
		return $out;
	}

	public function set($k,$v) {
		self::$_regdata->$k = $v;
		return $v;
	}

	public function get($k) {
		return (isset(self::$_regdata->$k)) ? self::$_regdata->$k : null;
	}


	private function serial($rec='',$aff='') {
		$in  = trim($rec) . trim($aff);
		$index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$base  = strlen($index);
		$in  = strrev($in);
		$out = 0;
		$len = strlen($in) - 1;
		for ($t = 0; $t <= $len; $t++) {
			$bcpow = pow($base, $len - $t);
			$out   = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
		}
		$out = sprintf('%F', $out);
		$out = substr($out, 0, strpos($out, '.'));
		return $out;
	}


	private function get_level() {
		$reg = self::get_regdata();
		self::$_regdata->currlevel = absint($reg->agreed) + absint($reg->installed) + absint($reg->activated);
		return self::$_regdata->currlevel;
	}
	public function get_step() {
		return self::$_steps[self::get_level()];
	}

	public function getAffiliate() {
		return self::get('affiliate');
	}
	public function activated() {
		return (self::get('mode') == 'pro') ? '1' : '0';
	}
	private function get_regdata() {
		return self::$_regdata;
	}


	private function _getrequest($postinfo) {


		$uri = self::get_uri('activate');

		global $wp_version;
		$body = $postinfo;
		$options = array('method' => 'POST', 'timeout' => 10, 'body' => $body);
		$options['headers']= array(
			'Content-Type' => 'application/x-www-form-urlencoded; charset=' . get_option('blog_charset'),
			'Content-Length' => strlen(implode(',',$body)),
			'user-agent' => 'WordPress/' . $wp_version,
			'referer'=> get_site_url()
		);
		$raw_response = wp_remote_post($uri, $options);


		if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200)){
			$result = $raw_response['body'];
			$result = (array) json_decode($result);
			if (! is_array( $result ) ) {
				return false;
			}else{
				return $result;
			}
			return $result;
		}else{
			return false;
		}
	}

	function options_load($arr) {
	}
	function options_filter($arr) {

			self::$_opts = (object) $arr;

			$arr['_swingline'] = 'red';
			$arr['api'] = self::get('api');
			$arr['rec'] = self::get('rec');
			$arr['aff'] = self::get('affiliate');
			ksort($arr);

			return $arr;

	}

	function process_terms() {
		$redir = self::get_redir();
		if(isset($_POST['agreed'])){
			self::set('agreed',1);
			self::save();
		}else{
			$redir = add_query_arg('msgid','terms',$redir);
		}

		wp_redirect( $redir );
		exit();

	}
	function process_setup() {

			do_action('cbpress_createpage');
			self::set('installed',1);
			self::save();

	}
	function process_activation() {
		$prev = self::get_regdata();
		$postdata = (object) array_merge((array) $prev, self::$_request);








		$RemoteData = self::_getrequest(array(
				'action' => 'activate',
				'blog_url' => CBP_BLOGURL,
				'rec' => trim($postdata->rec),
				'aff' => trim($postdata->aff)
		));


		$RemoteData = (object) (is_array($RemoteData) ? $RemoteData : array());


















		// 10:03 PM 12/26/2012

		if(1 == 1){

			$save_me = 0;

			if(isset($_POST['aff'])){ 
				$postdata->aff = $_POST['aff']; 
				self::set('aff',$postdata->aff);
				$save_me = 1;
			}
			if(isset($_POST['rec'])){ 
				$postdata->rec = $_POST['rec']; 
				self::set('rec',$postdata->rec);


				// 8 char limit and alphanumeric dash check 
				$isrec = 0;
				if (preg_match("/[A-Za-z0-9_-]/",$postdata->rec)) { $isrec = 1; }
				if(strlen($postdata->rec) != 8 || $isrec == 0){
					$RemoteData->msgid = 'mismatch';
				}

				$save_me = 1;
			}

			if($save_me == 1){

				self::save();
			}
		}
















		$out = (object) array();
		$okay = '0';

		if(isset($RemoteData->aok)){


			$out->msgid = self::set_msg($RemoteData->msgid);
			$out->upd = date('Y-m-d H:i:s');
			$out = (object) array_merge((array) $prev, (array) $RemoteData);

			if($RemoteData->aok == 1){

				$out->mode = 'pro';
				$out->activated = 1;
				$out->affiliate = $postdata->aff;
				$out->aff = $postdata->aff;
				$out->rec = $postdata->rec;
				$okay = '1';

			}else if(self::activated() && $prev->activated == 1){

				$okay = '0';

			}else{
				$out->mode = 'demo';
				$out->activated = 0;
				$out->aff = $postdata->aff;
				$out->rec = $postdata->rec;
				$okay = '1';
			}
		} else {
			$out->msgid = 'http';
		}
		$redir = self::get_redir();



			self::save();


		if($okay == 1){
			self::$_regdata = (object) array_merge((array) $prev, (array) $out);



















			self::save();
			self::set_trans((array) $RemoteData,'verify');
		} else{
			$redir = add_query_arg( array('rec'=>$_POST['rec'],'aff'=>$_POST['aff']), $redir );
		}


		$id = isset($out->msgid) ? $out->msgid : self::get('msgid');
		if($id == 'edits'){
				$redir = remove_query_arg('aff', $redir);
		}
		$alerts = self::get_alerts($id);
		$redir = add_query_arg('msgid',$id,$redir);


		wp_redirect( $redir );
		exit();
		return $okay;

	}
	private function get_redir() {
		$redir = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';
		$redir = remove_query_arg(array('msgid','action','rec','aff'), $redir);
		return $redir;
	}

	function form_start($step='') {
			if($step == '') $step = self::get_step();
			$action = 'cbp-' . $step;



			$id = @$_REQUEST['msgid'];

			$id = (isset($id)) ? $id : self::get('msgid');
			$alerts = self::get_alerts($id);

			if(! $alerts->notice == '') echo "<div class=\"updated below-h2\" id=\"message\"><p>".$alerts->notice."</p></div>";


		echo '<form action="'. admin_url( 'admin.php' ) . '" method="POST">';
		CBP::wp_nonce_field(CBP_HOOK_NONCE);
		echo '<input type="hidden" name="action" value="'. $action .'" />';


	}
	function form_end() {

		echo '<p><input type="submit" class="button-primary" value="Submit form" /></p></form>';

	}


	function form_activation() {


		$reg = self::get_regdata();

		$cb_signup_url = "http://www.clickbank.com/affiliateAccountSignup.htm?key=cbpress";

		$form = (object) self::$_formfill;

		self::form_start('activation');




		echo '<table class="form-table">';
		echo '<tr><th scope="row"><label for="cbpress_api_rec">Your ClickBank Receipt *</label></th></tr>';
		echo '<tr><td><span class="description2">Enter your ClickBank Receipt # from your CBPress or other qualifying purchase.<br/><br/></span></td></tr>';
		echo '<tr><td><input id="cbp_api_rec" class="regular-text" type="text" value="';
		echo $form->rec;
		echo '"  name="rec" />';
		echo '<span class="act">(<a id="findOrder" href="javascript:void(0);">Where do I find this?</a>)</span>';
		echo '</td></tr><tr><td colspan="2">&nbsp;&nbsp;</td></tr>';
		echo '<tr><th scope="row"><label for="cbpress_api_aff">Your ClickBank Affiliate ID *</label></th></tr>';
		echo '<tr><td><span class="description">Only one ClickBank affiliate ID can be used along with your receipt number.</span></td></tr>';
		echo '<tr><td><input id="cbp_api_aff" class="regular-text" type="text" value="';

		echo $form->aff;
		echo '" maxlength="16" name="aff" />';
		echo '<span class="act">(<a href="';
		echo $cb_signup_url;
		echo '" target="_blank">Obtain one at clickbank.com, it\'s free!</a>)</span></td></tr><tr><td colspan="1" style="padding: 10px 0px;"><br/></td></tr></table>';




		self::form_end();
	}

	function form_terms() {
	}

	function form_setup() {

	}


}

?>