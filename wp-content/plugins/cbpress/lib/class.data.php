<?php 
class CBP_data {	

	public static $_vars;		
	public static function __callStatic($func, $args) {
			if (!empty($args)) {
				self::$_vars[$func] = $args[0];
			} else {
				return self::$_vars[$func];
				return (isset(self::$_vars[$func])) ? self::$_vars[$func] : null;
			}
	}


	public function __call($f, $a) { 


		// dump(get_defined_vars());

		return self::__callStatic($f, $a);  
	} 


	private static function cbp_choices($from,$to,$step=1){
		$choices = range($from,$to,$step);
		array_unshift($choices,'');
		array_combine($choices,$choices);
		return $choices;
	}

	public static $support, $feed, $que, $sortables, $shortcodes, $menus, $settings;


	public function get($name) { 
		return self::$_vars[$name];
	} 





	function __construct() {

		self::$_vars['support'] = (object) array(
			'home' 	=> 'http://cbpress.com', 
			'help' 	=> 'http://support.cbpress.com', 
			'support' => 'http://support.cbpress.com', 
			'feed' 	=> 'http://www.clickbank.com/help/account-help/account-tools/marketplace-feed/', 
			'cats' 	=> 'http://www.clickbank.com/help/affiliate-help/affiliate-basics/marketplace-categories/'
			);


		self::$_vars['feed'] = (object) array(
				'main' 		=> 'http://feeds.feedburner.com/cbpress', 
				'products' 	=> 'http://feeds.feedburner.com/cbpress-products', 
				'updates' 	=> 'http://feeds.feedburner.com/cbpress-updates', 
				'__products' 	=> 'http://cbpress.com/topics/products/feed/',
				'__updates' 	=> 'http://cbpress.com/topics/updates/feed/'
		);



		self::$_vars['que'] = (object) array(
			'actions' => array(), 
			'filters' => array(), 
			'scripts' => array(), 
			'styles' 	=> array(),
			'messages' => array()
		);

		self::$_vars['sortables'] = array(
			  'lid' =>  __('Product ID', 'cbpress')
			, 'title' =>  __('Product Title', 'cbpress')
			, 'vin' =>  __('Vendor', 'cbpress')
			, 'status' =>  __('Status', 'cbpress')
			, 'created' =>  __('Date Added', 'cbpress')
			, 'ActivateDate' =>  __('Activate Date', 'cbpress')	
			, 'Gravity' =>  __('Gravity', 'cbpress')
			, 'Commission' =>  __('Commission', 'cbpress')
			, 'rank' =>  __('Category Rank', 'cbpress')
			, 'PopularityRank' =>  __('Popularity Rank (avg)', 'cbpress')
			, 'InitialEarningsPerSale' =>  __('Initial $/sale', 'cbpress')
			, 'AverageEarningsPerSale' =>  __('Avg $/sale', 'cbpress')	
			, 'TotalRebillAmt' =>  __('Avg Rebill Total', 'cbpress')
			, 'PercentPerRebill' =>  __('Percent Per Rebill', 'cbpress')	
			, 'PercentPerSale' =>  __('Percent Per Sale', 'cbpress')
			, 'HasRecurringProducts' =>  __('Has Recurring Products', 'cbpress')
			, 'Referred' =>  __('%Referred', 'cbpress')
		);


		self::$_vars['shortcodes_args'] = array(
				'category' => array(
					'values' => false,
					'default' => '10',
					'desc' => __( 'Category ID', 'cbpress' )
				),
				'display' => array(
					'values' => array('1','3','5','7','10'),
					'default' => '5',
					'desc' => __( 'Number of products to display', 'cbpress' )
				),
				'showdesc' => array(
					'values' => array('1','0'),
					'default' => '1',
					'desc' => __( 'Show descriptions', 'cbpress' )
				),

				'vendor' => array(
					'values' => false,
					'type' => 'vin_suggest',
					'default' => '',
					'class'=>'vendorSuggest',
					'desc' => __( 'ClickBank Vendor ID', 'cbpress' )
				),

				'product' => array(
					'values' => false,
					'type' => 'lid_suggest',
					'default' => '',
					'class'=>'lidSuggest',
					'desc' => __( 'Product ID', 'cbpress' )
				),	
								
				'list' => array(
					'values' => false,
					'type' => 'list_dropdown',
					'default' => '',
					'desc' => __( 'The List ID', 'cbpress' )
				),
				'root' => array(
					'values' => false,
					'type' => 'category_dropdown',
					'default' => '',
					'class'=>'',
					'desc' => __( 'This is the category ID for your niche marketplace page', 'cbpress' )
				)
		);
		
		self::$_vars['shortcodes'] = array(


			'cbpress' => array(
				'key'  => '',
				'name' => 'Full Marketplace',
				'type' => 'single',
				'atts' => array( ),
				'usage' => '[cbpress]',
				'desc' => __( 'This shortcode enables a full ClickBank Marketplace to be placed on any Wordpress page. You also have full control on what products and categories appear on this page. These control settings can be found throughout the CBPress admin pages.', 'cbpress' )
			),

			'niche' => array(
				'key'  => 'cbpress',
				'name' => 'Niche Marketplace',
				'type' => 'single',
				'atts' => array(
					'root' => array(
						'values' => false,
						'default' => '',
						'class'=>'',
						'desc' => __( 'This is the category ID for your niche marketplace page', 'cbpress' )
					)
				),
				'usage' => '[cbpress root=5]',
				'desc' => __( 'This shortcode enables a full ClickBank Marketplace to be placed on any Wordpress page. You also have full control on what products and categories appear on this page. These control settings can be found throughout the CBPress admin pages.', 'cbpress' )
			),
			
			'list' => array(
				'key'  => 'list',
				'name' => 'Custom List',
				'type' => 'single',
				'atts' => array(
					'list' => array(
						'values' => false,
						'default' => '',
						'desc' => __( 'The List ID', 'cbpress' )
					)
				),
				'usage' => '[cbpress list="2"]',
				'desc' => __( 'Custom Lists are collections of ClickBank and Custom products that you can create and promote on any Wordpress Page or Post. This list can be sorted based on the primary settings of CBPress - by Title, Date Added, Price, etc.', 'cbpress' )
			),



			'vendor' => array(
				'key'  => 'vendor',
				'name' => 'Single Product by Vendor',
				'type' => 'single',
				'atts' => array(
					'vendor' => array(
						'values' => false,
						'default' => '',
						'class'=>'vendorSuggest',
						'desc' => __( 'ClickBank Vendor ID', 'cbpress' )
					)
				),
				'usage' => '[cbpress vendor="singorama"]',
				'desc' => __( 'This outputs a single product title, description and affiliate link for a ClickBank Vendor ID.', 'cbpress' )
			),



			'product' => array(
				'key'  => 'product',
				'name' => 'Single Product by Product ID',
				'type' => 'single',
				'atts' => array(
					'product' => array(
						'values' => false,
						'default' => '',
						'class'=>'lidSuggest',
						'desc' => __( 'Product ID', 'cbpress' )
					)
				),
				'usage' => '[cbpress product="1028"]',
				'desc' => __( 'This outputs a single product title, description and affiliate link for a ClickBank, or Custom Product by the Product ID in the database.', 'cbpress' )
			),





			'category' => array(
				'key'  => 'category',
				'name' => 'Single Category',
				'type' => 'single',
				'atts' => array(
					'category' => array(
						'values' => false,
						'default' => '10',
						'desc' => __( 'Category ID', 'cbpress' )
					),
					'display' => array(
						'values' => array('1','3','5','7','10'),
						'default' => '5',
						'desc' => __( 'Number of products to display', 'cbpress' )
					),
					'showdesc' => array(
						'values' => array('1','0'),
						'default' => '1',
						'desc' => __( 'Enable or disable product descriptions from showing', 'cbpress' )
					),
				),
				'usage' => '[cbpress category="35" display="5" showdesc="1"]',
				'desc' => __( 'Single Category creates a bulleted list of products in any category. Subcategories are not displayed for this list.', 'cbpress' )
			)

		);


		self::$_vars['menu'] = array(

				'cbpress' => array(
					'id' => 'cbpress', 
					'page' => 'cbpress', 
					'showhead' => 0, 
					'enable' => 1, 
					'menutitle' => 'Summary', 
					'func' => null,
					'pagetitle' => 'Welcome to the cbpress control panel', 
					'desc' => 'What would you like to do?',
					'scripts' => 'cluetip',
					'styles' => '',
					'include' => 'main.php'
				),



				'import' => array(
					'id' => 'import' , 
					'page' => 'cbpress-import', 
					'showhead' => 1, 
					'enable' => 1, 
					'menutitle' => 'Importer', 
					'func' => null,
					'pagetitle' => 'ClickBank Product Importer', 
					'desc' => 'This will update existing products, or add new ones from the ClickBank Marketplace XML feed.',
					'scripts' => '',
					'styles' => '',
					'include' => 'import.php'
				),


				'products' => array( 
					'id' => 'products' ,
					'page' => 'cbpress-products',  
					'showhead' => 1, 
					'enable' => 1, 
					'menutitle' => 'Product List',
					'func' => null,
					'pagetitle' => 'Product Manager', 
					'desc' => 'Product management for all imported ClickBank and custom products. Use the \'advanced search\' box to find exactly what you\'re looking for',
					'scripts' => 'cluetip,jquery-dynatree',
					'styles' => '',
					'include' => 'products.php'
				),
				'cats' => array( 
					'id' => 'cats' , 
					'page' => 'cbpress-cats', 
					'showhead' => 1, 
					'enable' => 1, 
					'menutitle' => 'Category List',
					'func' => null,
					'pagetitle' => 'Category Manager', 
					'desc' => 'This page lets you easily manage all \'ClickBank\' or \'Custom\' marketplace categories.',
					'scripts' => '',
					'styles' => '',
					'include' => 'cats.php'
				),

				'lists' => array(
					'id' => 'lists' ,
					'page' => 'cbpress-lists', 
					'showhead' => 1, 
					'enable' => 1, 
					'menutitle' => 'List Builder', 
					'func' => null,
					'pagetitle' => 'Custom List Manager', 
					'desc' => 'Custom lists give you the ability to easily display subsets of affiliate products on different pages using the \'Custom List Widget\' or \'shortcode\'.',
					'scripts' => '',
					'styles' => '',
					'include' => 'lists.php'
				),


				'settings' => array(
					'id' => 'settings' ,
					'page' => 'cbpress-settings', 
					'showhead' => 1, 
					'enable' => 1, 
					'menutitle' => 'Options', 
					'func' => null,
					'pagetitle' => 'Settings & Options', 
					'desc' => 'Manage settings for importing products, marketplace options, etc. These are the global settings for the plugin.',
					'scripts' => '',
					'styles' => '',
					'action' => 'cbp-tab-settings',
					'include' => ''
				),


				'setup' => array(
					'id' => 'setup' , 
					'page' => 'cbpress-setup', 
					'showhead' => 1, 
					'enable' => 1, 
					'menutitle' => 'Activation', 
					'func' => null,
					'pagetitle' => 'Plugin Activation', 
					'desc' => 'Registration allows you to use your own ClickBank Affiliate ID on all hoplinks.',
					'scripts' => '',
					'styles' => '',
					'action' => 'cbp-tab-register',
					'include' => ''
				),


				'rss' => array(
					'id' => 'rss' ,
					'page' => 'cbpress-rss', 
					'showhead' => 0, 
					'minlevel' => 2, 
					'enable' => 0, 
					'menutitle' => 'Resources', 
					'func' => null,
					'pagetitle' => 'Resources', 
					'desc' => 'Resources, Getting Started, CBPress News, Updates, FAQs and more',
					'scripts' => '',
					'styles' => '',
					'include' => 'rss.php'
				)
		);

		
		$create_page_link = admin_url( 'admin.php?action=cbp-create-page' );
		$create_page_link = '<a href="'.$create_page_link.'">Create one for me</a>';

		self::$_vars['settings'] = (object) array(

			'tabs' => array( 
				'main' => array(
					'id'=>'main', 'label'=>'General', 'desc'=>'General settings for hoplinks, product descriptions, categories and other marketplace options' 
					),
				'filter' => array(
					'id'=>'filter', 'label'=>'Filters', 'desc'=>'Filters allow you to set certain criteria for what products display on your marketplace page. Only products matching the following criteria will display.', 
					'action' => 'cbp-tab-filter',
					'callback' => 'panel_filter'					
					),
				'import' => array(
					'id'=>'import', 'label'=>'Importer', 'desc'=>'Settings for importing products from the ClickBank XML feed' 
					),
				'stylesheet' => array(
					'id'=>'stylesheet', 'label'=>'CSS', 'desc'=>'This allows you to edit the stylesheet to give you more control on how products appear. Only edit this if you have experience with CSS',
					'action' => 'cbp-tab-css',
					'callback' => 'panel_css'	
					),
				'system' => array(
					'id'=>'system', 'label'=>'System', 'desc'=>'Please only use this page if you\'re absolutely sure you understand the actions that it performs.',
					'action' => 'cbp-tab-system',
					'callback' => 'panel_system'
					)
			),


			'system' => array( 

				'resetoptions' => array(
					'id'=>'resetoptions', 
					'action' => 'resetoptions',
					'confirm' => 'This action cannot be undone. Continue?',
					'label'=>'Reset Options',
					'desc'=>'Resets options to default values. This cannot be undone' ),


				'resetfolders' => array(
					'id'=>'resetfolders',
					'action' => 'resetfolders',
					'confirm' => 'This action cannot be undone. Continue?',
					'label'=>'Reset Temp Folders',
					'desc'=>'Creates temporary upload folders required for running the ClickBank XML feed importer' ),
					
				'resetdb' => array(
					'id'=>'resetdb', 
					'action' => 'resetdb',
					'confirm' => 'This action cannot be undone. Continue?',
					'label'=>'Reset Database Tables',
					'desc'=>'Any existing tables created by CBPress will be overwritten and cannot be undone.' ),

				'resetapi' => array(
					'id'=>'resetapi', 
					'action' => 'resetapi',
					'confirm' => 'This action cannot be undone. Continue?',
					'label'=>'Reset Activation',
					'desc'=>'Useful if you are having trouble activating cbpress. Warning: You will have to re-activate the plugin. ' ),

					
				'reinstall' => array(
					'id'=>'reinstall', 
					'action' => 'reinstall',
					'confirm' => 'This action cannot be undone. Continue?',
					'label'=>'Reinstall',
					'desc'=>'Reinstall the plugin. Same as uninstall except plugin remains activated' ),

				'uninstall' => array(
					'id'=>'uninstall', 
					'action' => 'uninstall',
					'confirm' => 'This action cannot be undone. Continue?',
					'label'=>'Uninstall Everything',
					'desc'=>'This will completely uninstall and deactivate cbpress. This includes removal of all settings and database tables created by cbpress.' ),

				'resetstyle' => array(
					'id'=>'resetstyle', 
					'action' => 'resetstyle',
					'confirm' => 'This action cannot be undone. Continue?',
					'label'=>'Reset stylesheet changes',
					'desc'=>'Warning: this overwrites any custom css changes you\'ve made' ),

				'createpage' => array(
					'id'=>'createpage', 
					'action' => 'createpage',
					'confirm' => 'Continue creating new page?',
					'label'=>'Create New Page',
					'desc'=>'This will create a new wordpress page with the [cbpress] shortcode installed' ),

			),


			'defaults' => array( 
				  'last_import' => ''
				, 'product_cols' => 'lid,title,vin,status,created,ActivateDate,Gravity,Commission,rank,InitialEarningsPerSale,HasRecurringProducts' 
				, 'show_cc' => '0' 
				, 'link_nofollow' => '1' 
				, 'link_newwindow' => '1' 
				, 'link_tid' => ''
				, 'link_cloaker' => 'gocbp'  
				, 'desc_spacer' => '20' 
				, 'desc_limit' => '0' 
				, 'desc_links' => '0' 
				, 'desc_filter' => '1' 
				, 'desc_filter_words' => 'Max payout, upsells, downsells, Lowest Refunds, low refund, Converts, %, $, http:, commission, payouts, conversions, conversion, affiliate, affiliates, earn, % returns, return rate' 
				, 'admin_pp' => 10 
				, 'admin_sb' => '0' 
				, 'perpage' => 10 
				, 'import_excludehops' => 'testacct' 
				, 'import_throttle' => 300 
				, 'import_autosync' => '0' 
				, 'import_autoactive' => '1' 
				, 'import_notinfeed' => 'disable' 
				, 'import_zip1' => 'http://www.clickbank.com/feeds/marketplace_feed_v2.xml.zip' 
				, 'import_zip' => 'https://accounts.clickbank.com/feeds/marketplace_feed_v2.xml.zip' 


				, 'mk_morelink' => '1'
				, 'mk_backlink' => '1'
				, 'mk_showdesc' => '1' 
				, 'mk_catbox' => '1'
				, 'sort' => ''
				, 'order' => ''
				, 'billing' => 'all' 
				, 'pageid' 	=> '0'
				, 'pagetitle' 	=> 'Marketplace'
				, 'pagemsg' 	=> 'Find thousands of popular items in our marketplace!'
				, 'pageslug' 	=> 'marketplace'
				, 'cat_label' => 'Browse Categories'
				, 'cat_cols1' => 2
				, 'cat_cols2' => 3
				, 'cat_root' => '0'
				, 'cat_feat' => '0'
				, 'min_rank' => ''
				, 'max_rank' => ''
				, 'min_gravity' => ''
				, 'max_gravity' => ''
				, 'min_commission' => ''
				, 'max_commission' => ''
				, 'min_referred' => ''
				, 'max_referred' => ''
				)



			, 'meta' => array(






			       'pageid' 		=> array( 'id'=>'pageid', 'title'=>'Primary Marketplace Page', 'section'=>'main', 'type'=>'pagelist', 'desc'=>'The WordPress page containing the [cbpress] shortcode. ' . $create_page_link) 
 				, 'perpage' 		=> array( 'id'=>'perpage', 'title'=>'Products per page', 'section'=>'main', 'type'=>'select', 'desc'=>'number of products to display on each marketplace page', 'note'=>null, 'choices'=> array_combine(range(5,100,5),range(5,100,5)) ) 



				, 'import_zip' 		=> array( 'id'=>'import_zip', 
								'title'=>'ClickBank data feed archive URL', 
								'section'=>'import', 
								'type'=>'text', 
								'desc'=>'This the URL to the latest ClickBank Marketplace product feed zip archive used by the cbpress product importer. If you are having problems with the data feed URL you can find out the current valid URL <a href="https://support.clickbank.com/entries/22824126-marketplace-feed" target="_blank">on this page</a>' 
							) 




				, 'show_cc' 	=> array( 'id'=>'show_cc', 'sep'=>'1', 'title'=>'Show products with custom titles and descriptions only', 'section'=>'main', 'type'=>'checkbox', 'desc'=>'Includes only customized products', 'note'=>null ) 





				, 'link_nofollow' 	=> array( 'id'=>'link_nofollow', 'sep'=>'1', 'title'=>'Use nofollow in links', 'section'=>'main', 'type'=>'checkbox', 'desc'=>'Includes "nofollow" attribute on hoplinks (anchor tags) created by this plug-in', 'note'=>null ) 


				, 'link_newwindow' 	=> array( 'id'=>'link_newwindow', 'title'=>'Open links in new window', 'section'=>'main', 'type'=>'checkbox', 'desc'=>'Includes target="_blank" attribute on hoplinks (anchor tags) created by this plug-in', 'note'=>null ) 






				, 'link_tid' 		=> array( 'id'=>'link_tid', 'title'=>'Global ClickBank TID', 'section'=>'main', 'type'=>'text', 'desc'=>'Your ClickBank Tracker ID (TID) is optional. As an affiliate, a ClickBank TID provides the power to track and manage your campaigns by tying a specific sale back to the promotion or site that initiated it. <a href="http://www.clickbank.com/help/affiliate-help/affiliate-basics/all-about-hoplinks/" target="_blank">More about TID tracking</a>' ) 
				, 'mk_showdesc' 	=> array( 'id'=>'mk_showdesc', 'sep'=>'1', 'title'=>'Show Descriptions', 'section'=>'main', 'type'=>'checkbox', 'desc'=>'Enables product descriptions under the product title on marketplace results pages.' ) 
				, 'desc_limit' 	=> array( 'id'=>'desc_limit', 'title'=>'Description limit', 'section'=>'main', 'type'=>'select', 'desc'=>'Limits the number of characters in product descriptions (words are not cut off)', 'note'=>null, 'choices'=> self::cbp_choices(50,255,1) ) 

				, 'desc_filter' 	=> array( 'id'=>'desc_filter', 'sep'=>'1', 'title'=>'SMART Filtering', 'section'=>'main', 'type'=>'checkbox', 'desc'=>'Smart filtering is a powerful new cbpress feature that helps to make product descriptions more customer friendly. (Smart filtering is not applied to any custom edited descriptions)', 'note'=>null ) 
				, 'desc_filter_words' 	=> array( 'id'=>'desc_filter_words', 'title'=>'SMART Filter Words', 'section'=>'main', 'type'=>'textarea', 'desc'=>'(comma separated) When any of these words, phrases or characters are found, the containing sentence gets dropped. <br><br>We have found the default list of words supplied by CBPRESS to do a fantastic job in making descriptions more customer oriented, but we encourage you to tinker with it. Feel free to let us if you come up with another combination of words that works better!', 'note'=>null ) 

				, 'import_throttle' 	=> array( 'id'=>'import_throttle', 'title'=>'Import Throttle', 'section'=>'import', 'type'=>'select', 'desc'=>'Increases or decreases the number of products that are batched inserted during an import. Change this setting if you experience MySQL problems during import.', 'note'=>null, 'choices'=> array_combine(range(100,1000,100),range(100,1000,100)) ) 
				, 'import_autoactive' 	=> array( 'id'=>'import_autoactive', 'title'=>'Auto Sync Visibility Status', 'section'=>'import', 'type'=>'checkbox', 'desc'=>'<br>This will automatically toggle the \'active\' display status in the products table to keep in sync with the ClickBank XML feed. When a product is not active, it does not display on your website. <a href="http://version.cbpress.com/guide.php" target="_blank">See online documentation</a> for more information') 
				, 'import_notinfeed' 	=> array( 'id'=>'import_notinfeed', 'title'=>'Removed Products Action', 'section'=>'import', 'type'=>'radio', 'desc'=>'This applies to products that are no longer listed in the feed but active in your database',  'choices'=>array('disable'=>'Mark as removed' ,'delete'=>'Delete product from database', 'none'=>'Do nothing') ) 

				, 'sort' 		=> array( 'id'=>'sort', 'title'=>'Sort Column', 'section'=>'filter', 'type'=>'select', 'desc'=>null, 'note'=>null, 'choices'=>self::$_vars['sortables'] ) 
				, 'order' 		=> array( 'id'=>'order', 'title'=>'Sort Direction', 'section'=>'filter', 'type'=>'select', 'desc'=>null, 'note'=>null, 'choices'=>array('asc'=>'asc','desc'=>'desc') ) 
				, 'billing' 		=> array( 'id'=>'billing', 'title'=>'Billing Type', 'section'=>'filter', 'type'=>'select', 'desc'=>null, 'note'=>null, 'choices'=>array(''=>'All Products', '1'=>'Only Recurring Billing', '0'=>'Only Standard Billing') ) 



				, 'cat_root' 		=> array( 'id'=>'cat_root', 'sep'=>'1', 'title'=>'Category Root', 'section'=>'main', 'type'=>'select-cat', 'desc'=>'The starting root category for your marketplace' ) 
				, 'cat_feat' 		=> array( 'id'=>'cat_feat', 'title'=>'Feature Category', 'section'=>'main', 'type'=>'select-cat', 'desc'=>'Displays a list of featured products on the marketplace index' ) 
				, 'mk_catbox' 		=> array( 'id'=>'mk_catbox', 'title'=>'Category Box Display', 'section'=>'main', 'type'=>'checkbox', 'desc'=>'displays the sub category navigation box near the top of the marketplace page' ) 
				, 'cat_label' 		=> array( 'id'=>'cat_label', 'title'=>'Category Box Label', 'section'=>'main', 'type'=>'text', 'desc'=>'' ) 
				, 'mk_backlink' 	=> array( 'id'=>'mk_backlink', 'sep'=>'1', 'title'=>'CBPress Link', 'section'=>'main', 'type'=>'checkbox', 'desc'=>'places a small cbpress hoplink with your affiliate id on the bottom' ) 
				, 'admin_pp' 		=> array( 'id'=>'admin_pp', 'title'=>'Admin products per page', 'section'=>'main', 'type'=>'select', 'desc'=>'number of results per page in the cbpress product manager', 'choices'=> array_combine(range(10,100,5),range(10,100,5))	) 

				, 'min_rank' 		=> array( 'id'=>'min_rank', 'title'=>'Min Rank', 'section'=>'filter', 'type'=>'text', 'desc'=>'Numeric value only' ) 
				, 'max_rank' 		=> array( 'id'=>'max_rank', 'title'=>'Max Rank', 'section'=>'filter', 'type'=>'text', 'desc'=>'Numeric value only' ) 
				, 'min_gravity' 	=> array( 'id'=>'min_gravity', 'title'=>'Min Gravity', 'section'=>'filter', 'type'=>'text', 'desc'=>'Numeric value only' ) 
				, 'max_gravity' 	=> array( 'id'=>'max_gravity', 'title'=>'Max Gravity', 'section'=>'filter', 'type'=>'text', 'desc'=>'Numeric value only' ) 
				, 'min_commission' 	=> array( 'id'=>'min_commission', 'title'=>'Min Commission', 'section'=>'filter', 'type'=>'text', 'desc'=>'Numeric value only' ) 
				, 'max_commission' 	=> array( 'id'=>'max_commission', 'title'=>'Max Commission', 'section'=>'filter', 'type'=>'text', 'desc'=>'Numeric value only' ) 
				, 'min_referred' 	=> array( 'id'=>'min_referred', 'title'=>'Min Referred', 'section'=>'filter', 'type'=>'text', 'desc'=>'Numeric value only' ) 
				, 'max_referred' 	=> array( 'id'=>'max_referred', 'title'=>'Max Referred', 'section'=>'filter', 'type'=>'text', 'desc'=>'Numeric value only' ) 
			)
		);

				// , 'admin_sb' 	=> array( 'id'=>'admin_sb', 'title'=>'Admin search box', 'section'=>'main', 'type'=>'checkbox', 'desc'=>'admin product search box always visible') 
				// , 'pageslug' 	=> array( 'id'=>'pageslug', 'title'=>'Page Slug', 'section'=>'main', 'type'=>'text' ) 
				// , 'pagetitle' 	=> array( 'id'=>'pagetitle', 'title'=>'Marketplace Title', 'section'=>'main', 'type'=>'text', 'desc'=>'Marketplace page title'  ) 
				// , 'pagemsg' 		=> array( 'id'=>'pagemsg', 'title'=>'Marketplace Text', 'section'=>'main', 'type'=>'textarea', 'desc'=>'Text to display on the top of the marketplace page.' ) 
				// , 'last_import' 	=> array( 'id'=>'last_import', 'title'=>'Last Import Date', 'section'=>'admin', 'type'=>'display' ) 
				// , 'product_cols' 	=> array( 'id'=>'product_cols', 'title'=>'Product Column Display', 'section'=>'main', 'type'=>'textarea', 'desc'=>'product manager display columns', 'note'=>null ) 
				// , 'link_cloaker' 	=> array( 'id'=>'link_cloaker', 'title'=>'Link Cloak', 'section'=>'main', 'type'=>'text', 'desc'=>'outgoing cloacked link parameter (default is "gocbp")'  ) 
				// , 'desc_links' 	=> array( 'id'=>'desc_links', 'title'=>'__D - Auto link description urls', 'section'=>'main', 'type'=>'checkbox', 'desc'=>'Enabling this option will make an effort to detect and replace vendor typed URLs in product descriptions with the outgoing hoplink', 'note'=>null ) 
				// , 'desc_spacer' 	=> array( 'id'=>'desc_spacer', 'title'=>'__D - Spacing every', 'section'=>'main', 'type'=>'select', 'desc'=>'this will add a SPACE in words of a description to allow page elements to properly wrap. Prevents text bleeding onto other page elements', 'note'=>null, 'choices'=> self::cbp_choices(10,100,1)) 
				// , 'import_excludehops' => array( 'id'=>'import_excludehops', 'title'=>'Exclude products', 'section'=>'import', 'type'=>'textarea', 'desc'=>'Exlude this list of ClickBank vendors on import (comma separated each ID)', 'note'=>null ) 
				// , 'import_zip' 	=> array( 'id'=>'import_zip', 'title'=>'ClickBank Feed URL', 'section'=>'import', 'type'=>'text', 'desc'=>'This is the URL of the ClickBank product Feed.  <a href="http://www.clickbank.com/help/account-help/account-tools/marketplace-feed/" target="_blank">More info</a>', 'note'=>null ) 
				// , 'cat_cols1' 	=> array( 'id'=>'cat_cols1', 'title'=>'Category Columns 1', 'section'=>'main', 'type'=>'text', 'desc'=>'' ) 
				// , 'cat_cols2' 	=> array( 'id'=>'cat_cols2', 'title'=>'Category Columns 2', 'section'=>'main', 'type'=>'text', 'desc'=>'' ) 
				// , 'import_autosync' 	=> array( 'id'=>'import_autosync', 'title'=>'Auto Sync From Feed', 'section'=>'import', 'type'=>'checkbox', 'desc'=>'Enabled this this option to Update Existing Product Titles and Descriptions with those in the ClickBank XML feed. You can override this setting on a per product basis by unchecking "Auto Update" in the product edit form.', 'note'=>null ) 
			
		self::$_vars['settings']->tabs = cbpressfn::arrayToObject(self::$_vars['settings']->tabs);

		// abort(self::$_vars);
		
	}

}

new CBP_data;