<?php
if (!defined('ABSPATH')) die();

/*** admin ui pages and elements ***/

class CBP_admin {

	public static $vars = array();

    const POST_TYPE = 'url';


	function CBP_admin() {
	}

	static function getKeywords() {
		return @$_REQUEST['keywords'];
	}
	static function formSearchStart() {
		$id = "cbpasb_button";
		$id = "header_search";
		$page = "cbpress-products";
		$action = admin_url('admin.php?page=' . $page);
		echo '<form action="'.$action.'" method="GET" id="'.$id.'" autocomplete="off">';
		echo '<input id="page" name="page" type="hidden" value="' . $page . '" />';
	}
	static function formSearchEnd() {
		echo '</form>';
	}

	static function searchbox1() {
		$value  = self::getKeywords();
		$input  = '<input type="text" name="keywords" id="xsearch-keywords" class="input_text" value="'.$value.'" />';
		$button = '<input type="submit" value="Search" id="xsearch-submit" xclass="button-primary" />';

		// $button = '<a class="large super bttn">Search &raquo;</a> ';
		// searchpanel_btn
		$advlink = '';


		echo '<input type="text" name="keywords" class="bigInput" size="20"  value="'.$value.'">';
		echo '<input type="submit" class="bigButton" value="Search">';
		echo '<input type="button" class="advButton searchpanel_btn" align="middle"/>';
	}

	static function searchbox2() {
		$value = self::getKeywords();
		self::formSearchStart();
		?>
			<div id="searchbox2"><div>
			<input type="text" name="keywords" class="search-input" value="<?php echo $value ?>" placeholder="Product Search" />
			<input type="image" align="middle" src="<?php echo CBP_IMG_URL; ?>icon-search.gif" />
			</div></div>
		<?php
		self::formSearchEnd();
	}

	static function header() {
		global $cbpress, $wp_version;

		$info = '';

		$regdata = &$cbpress->regdata;

		// $imp = CBP_import::getter();
		// $busy = ($imp->is_busy()) ? 'importer is running' : 'not running';

		$out = array();
		$sep = '&nbsp;&nbsp;&nbsp;&nbsp;';
		// $out[] = 'IMPORT: ' . $busy;
		// $out[] = '<b>Ver PHP, WP </b> = ' . PHP_VERSION . ' : ' . $wp_version;
		// $out = '<div style="padding: 0px 10px 10px 10px;" class="v11">' . implode($sep,$out) . '</div>';



		// echo $out;

		$actionLink = admin_url('admin.php?page=cbpress-setup&step=activation');


		if(! CBP_api::activated()){

			$alerts = CBP_api::get_alerts($regdata->msgid);
			$info = '<span class="activationStrip">';
			$info .= '<a href="'.$actionLink.'" class="activate">' . $alerts->msg . '</a>';
			$info .= '</span>';
			// $info = '<div class="updated">' . $alerts->msg . '</div>';
		} else{
			$link = '<a href="'.$actionLink.'" class="xactivate">'.$regdata->aff.'</a>';
			$info = '<div title="Plugin Registered to ClickBank Affiliate ID">Registered affiliate ID:  <span class="cbaff">' . $link . '</span></div>';

		}

		$add = CBP::admin('products');
		$ver = CBP_VERSION;
		$addlink = admin_url("admin.php?page=cbpress-products&tab=add")
		?>


		<br class="clear" />


		<?php self::formSearchStart(); ?>
		<div class='cbpress-admin-header'>

			<table border="0" cellpadding="0"  cellspacing="0">
			  <tr>
			    <td rowspan="2">
					<a href="<?php echo CBP::get_admin_url(''); ?>"  title="Official WordPress Plugin for the ClickBank Marketplace" class="cbpress-logo"><span>Cbpress</span></a>
			    </td>
			    <td rowspan="2" style="padding: 0px 20px 0px 20px;" valign="" nowrap>

					<div class="social">
							<a class="footerSocLink" id="ftrFacebook" href="http://facebook.com/cbpress1"></a>
							<a class="footerSocLink" id="ftrTwitter" href="http://twitter.com/cbpress"></a>
							<a class="footerSocLink" id="ftrBlog" href="http://cbpress.com/blog"></a>
							<a class="footerSocLink" id="ftrYoutube" href="http://www.youtube.com/cbpress"></a>
							<a class="footerSocLink" id="ftrGooglePlus" href="https://plus.google.com/111191681510914084513"></a>
					</div>
			    </td>
			    <td style="padding: 0px 20px 0px 20px;" valign="" nowrap>
					<div class="tagline">ClickBank Plugin for WordPress</div>
			    </td>


			  </tr>
			  <tr>
			    <td style="padding: 0px 20px 0px 20px;" valign="">
			    	<?php echo $info; ?>
			    </td>
			  </tr>
			</table>


			<div class="header_main">
				<div class="header_nav">
					<?php

						$value  = self::getKeywords();
						echo '<input type="text" name="keywords" class="bigInput" size="20"  value="'.$value.'">';
						echo '<input type="submit" class="bigButton" value="Search">';
						echo '<input type="button" class="advButton searchpanel_btn" align="middle"/>';


						echo '<span class="linkbar">';
						echo '<a href="http://version.cbpress.com/" target="_blank">v '. $ver . '</a>';
						echo '<a href="http://version.cbpress.com/guide.php" target="_blank">Guide</a>';
						echo '<a href="http://support.cbpress.com/" target="_blank">Support</a>';
						if(! CBP_api::activated()){
							echo '<a id="" href="http://1.cbpress.pay.clickbank.net" target="_blank" class="purchase">Purchase</a>';
						} else {
							echo '<a href="'. $addlink .'" title="Add Custom Product">Add Product</a>';
						}
						echo '</span>';
					?>
				</div>
			</div>
		</div>
		<div id="searchpanel"></div>
		<?php self::formSearchEnd(); ?>

		<div id="category_dialog" style="display:none;"></div>
		<div id="editwin" style="display:none;"></div>
		<div id="cbpasb_div" style="display:none"></div>


		<?php
	}
	static function admin() {

	}


	static function addvar($in) {
		self::$vars[] = $in;
	}
	static function debug_footer() {
		self::addvar('Query Count: ' . get_num_queries());
		$arr = self::$vars;
		echo '<div id="debugfooter"> ';
		foreach ( $arr as $v ) { echo $v . '<br>'; }
		echo '</div>';
	}

}