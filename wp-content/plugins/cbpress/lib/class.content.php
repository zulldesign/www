<?php
if (!defined('ABSPATH')) die();

class CBP_content {

	public static $hooks = array(
		'activation_box',
		'activation_strip',
		'tooltips'
	);
	public static $content = array();
	public static $sections = null;
		
	public static function init() {
		foreach (self::$hooks as $hook) {
			add_action('cbp_'.$hook, array(__CLASS__,'content_'.$hook) );
		}
	}



	
	/*************
	* Admin Menus
	************/

	
	


	public static function load() {

		if (is_array(self::$sections)) return;

		self::$sections = array();
	
		$glossary = CBP_Meta::getArray();
		foreach ($glossary as $id => $info) {
				self::add('prod',$id,$info['label'],$info['desc']);
		}
		
		// $r = array ('section','id','label','desc');
			
		/*******************
		self::add('tabs','shop','Marketplace','Settings for product descriptions, hoplinks, etc');
		self::add('tabs','import','Importing','Settings for importing products from the ClickBank XML feed');
		self::add('tabs','main','General','General Settings');
		self::add('tabs','filter','Filters','Filters allow you to set certain criteria for what products display on your marketplace page. Only products matching the following criteria will display.');
		self::add('tabs','stylesheet','Stylesheet','This allows you to edit the stylesheet to give you more control on how products appear. Only edit this if you have experience with CSS');
		self::add('tabs','system','System','Advanced plugin actions');	
		*****************/

		$prodslink = CBP::admin('products');
		
		self::add('stats','mall_clickbank','Marketplace Listings','Total number of product-to-category listings in feed');
		self::add('stats','cats_clickbank','Marketplace Categories','Number of ClickBank Marketplace Categories');
		self::add('stats','items_active','Unique Products','Number of products in your database ACTIVE in feed from last import');
		self::add('stats','items_removed','Removed Products','Number of products in your database NOT in feed from last import. <br><br>This number may show \'0\' if you have set products to be automatically deleted when they are in your database but not in the ClickBank XML feed during an import.'
			, array('link'=> $prodslink.'&status=removed')
		);
		self::add('stats','new_clickbank','Newly Activated','Newly activated in feed. This count is based on the most recent \'ActivationDate\' found in the ClickBank XML feed.' 
			, array('link'=> $prodslink.'&added=new')
		);
		self::add('stats','items_enabled','Published Products','Total number of products enabled to display on your site');
		self::add('stats','items_disabled','Unpublished Products','Total number products you\'ve disabled from displaying on your site');
		self::add('stats','last_import_date','Last Import Date','Date of last ClickBank XML feed import');
		self::add('stats','new_import','Added to database','Newly Added to your database during last import');
		self::add('stats','mall_custom','Custom Marketplace','Number of custom product-to-category relationships you\'ve created');
		self::add('stats','cats_custom','Custom Categories','Number of additional custom categories in your database.');
		self::add('stats','items_custom','Custom Products','Number of custom products you\'ve added');
		self::add('stats','lists','Custom Lists','Number of custom product lists you\'ve created');


		self::add('setup','server','Server Configuration','Checks system to make sure you can run cbpress. Under most cases... you can.');
		self::add('setup','install','Installation','This will install database tables, and create a folder called \'cbpress\' in your uploads folder. This folder will also contain a \'feeds\' folder. ');
		self::add('setup','activate','Activate your copy','Activate your copy of CBpress.  <br/><br/><b>Requirements:<br/></b> * A Clickbank affiliate ID<br/> * A valid order number');
		self::add('setup','import','Import ClickBank Feed','Import products into your database from the ClickBank Marketplace XML feed.  <br/><br/>You\'ll have full control over what products and categories display in your marketplace and other customizable product displays.');
		self::add('setup','products','Manage Products','Manage products you\'ve imported.');
		self::add('setup','product','Add Custom Product','Add a non-clickbank product with a custom redirect URL.');
		self::add('setup','cats','Manage Categories','Manage marketplace categories.');
		self::add('setup','settings','Plugin Settings','Settings page for cbpress.');
		self::add('setup','marketplace','Marketplace Page','This creates a customizable clickbank marketplace product directory. It\'s also the central page for affiliate product listings... This page can be referred to as your marketplace, storefront, mall or whatever you would like to call it! <br/><br/>In order for the <em>category</em> and <em>searchbox widgets</em> to work correctly, all you need to do is put the [cbpress] shortcode in any wordpress page for it to work.');
		self::add('setup','widget','Enable category widget','This will display a list of categories on your theme\'s sidebar. This helps visitors navigate products in your marketplace. <br/><br/>Please make sure you have already created a wordpress page with the <b>[cbpress]</b> shortcode so the categories know where to link to.');

		// abort(self::$sections);
	}




	public static function add($section,$id,$label,$desc,$info=array()) {
		

		if(! isset(self::$sections[$section]) ){
			self::$sections[$section] = array();
		}
		$key = $section . '_' . $id;
		self::$sections[$section][$key] = get_defined_vars();

				
		// self::$content[$id] = get_defined_vars();
	}
	public static function tooltip($id,$desc) {
			$out = '';
			$out .= '<div id="qrcode_'.$id.'" style="display:none">';
			$out .= '<div class="hoverTxt">';
			$out .= $desc;
			$out .= '</div>';
			$out .= '</div>';
			return $out;
	}
	


	public static function get_section($section) {

		self::load();		
		return (isset(self::$sections[$section])) ? self::$sections[$section] : array();
	}
	
	public static function content_tooltips($section='') {

			self::load();

			// abort(self::$sections);
			// abort(get_defined_vars());			
			if(!empty($section)){			
				$result = array(self::$sections[$section]);
			}else{				
				$result = self::$sections;
			}
			
			echo '<div class="helpDetail_parent">';
			foreach ($result as $rows) {
			  foreach ($rows as $row) {
					echo self::tooltip($row['key'],$row['desc']);	
			  }
			}
			echo '</div>';	
	}


		
	public static function make_help() {


			self::load();


			$result = &self::$sections;
			
			$out = array();
			
			
			$colModel = 'id=ID&label=Label&desc=Description';
			parse_str($colModel, $colModel);
			$colModel = (object) $colModel;
					
			foreach ($result as  $id => $rows) {
				$out = array();
				echo '<h2>' . $id . '</h2>';

					// $h = (object) array();
					// $h->type = 'heading';
					// $h->label = $id;
					// $out[] = $h;
					
			  	foreach ($rows as $row) {
			  		
			  		// $row['label'] = $row['label'];
        			$row['type'] = 'row';
        			$row['id'] = str_replace($id . '_', '',$row['id']);
					$out[] = (object) $row;
					
					
					// echo '<p><b>' . $row['label'] . ' </b> : ' . $row['desc'] . '</p>';	
			 	}
			 	
			 	
				echo cbpressfn::array2table($out, 'id,label,desc', $colModel);
			}
			
			// echo cbpressfn::array2table($out);
	}



	public static function content_activation_strip() {
		
		global $cbpress;
		$regdata = &$cbpress->regdata;				
		if(! CBP_api::activated()){	
			echo '<div class="header_menu">';
				echo '<div class="activationStrip">';
						$msg = $regdata->msg;
						echo '<a href="admin.php?page=cbpress-setup" class="activate">' . $msg . '</a>'; 
						// echo ' <a href="admin.php?page=cbpress-setup">Click here to activate!</a>';
				echo '</div>';
			echo '</div>';		
		} else{
			echo '<div class="header_menu">';
			echo '<div class="regstrip" title="Your ClickBank Affiliate ID">Registered &nbsp; <span class="cbaff">' . $regdata->aff . '</span></div>';
			echo '</div>';
		}
	}

	public static function content_activation_box() {
		global $cbpress;			
		if(! CBP_api::activated()){	
		?>
			<div class="activationBox">
					<h3>You have not activated cbpress!</h3>
					Only the first 3 products per marketplace category will be imported.
					 <span><a href="admin.php?page=cbpress-setup">Click here to fix</a></span>
			</div>			
		<?php		
		}
	}

	public static function _resource_links() {
		$cburl = "http://www.clickbank.com/affiliateAccountSignup.htm?key=cbpress";
		?>
			<fieldset style="border:1px solid #ddd; padding:10px 10px 10px 10px; margin:0px 0px 10px 0px; text-align: center;">	
			<div style="font-size:12px; xxmargin: 5px 0px 10px 0px;">
				<div style="margin-bottom: 10px; text-align: center;"><a href="<?php echo $cburl; ?>" 
				class="metalogo" target="_blank"><?php echo CBP::img('clickbank-logo-trans.png'); ?></a></div>
				<div style="margin-left: 0px;">Need a ClickBank affiliate account? You can <a href="<?php echo $cburl; ?>" target="_blank">register here</a>. And it's FREE!
				</div>
			</div>
			</fieldset>
			<fieldset style="border:1px solid #ddd; padding:10px 10px 10px 10px; margin:0px 0px 20px 0px; text-align: center;">	
			<div style="font-size:12px; xxxmargin: 5px 0px 10px 0px;">
				<div style="margin-bottom: 10px; text-align: center;">
				<a href="http://go.cbpress.com/cbengine" class="metalogo" target="_blank"><?php echo  CBP::img('cbengine_logo.png'); ?></a></div>
				<div style="margin-left: 0px;">
				Check out <a href="http://go.cbpress.com/cbengine" target="_blank">cbengine.com</a> for ClickBank related research and promotion tools.
				</div>
			</div>		
			</fieldset>
			
			
		<?php	
	}
	
	public static function _resource_links22() {
			
		$cb_signup_url = "https://www.clickbank.com/affiliateAccountSignup.htm?key=cbpress";
		?>
			
			<table class="cb_info">
			<tr>
			<td>
			<a href="http://www.cbpress.com/" class="metalogo" target="_blank"><?php echo CBP::img('meta_logo_cbpress.png'); ?></a>
				<div style="margin: 5px 0px;">
					<b>Purchase</b>
					<p>If you do not have an order number, 
					<a href="http://www.cbpress.com/" target="_blank">click here to purchase Cbpress</a></p>
				</div>
				<div class="cbpress-divider"></div>
			</td>
			</tr>
			<tr>
			<td>
				<a href="<?php echo $cb_signup_url; ?>" class="metalogo" target="_blank"><?php echo CBP::img('clickbank_logo.png'); ?></a>
				<div style="margin: 5px 0px;">
					<b>ClickBank</b> / 
					If you don't have a ClickBank account, you can <a href="<?php echo $cb_signup_url; ?>" target="_blank">register here</a>. It's FREE!
				</div>
				<div class="cbpress-divider"></div>
			</td>
			</tr>
			<tr>
			<td>
				<a href="http://www.cbengine.com/" class="metalogo" target="_blank"><?php echo  CBP::img('cbengine_logo.png'); ?></a>
				<div style="margin: 5px 0px;">
					<b>CBengine</b>: 
					Check out cbengine.com for ClickBank related research and promotion tools. <a href="http://www.cbengine.com/" target="_blank">Click Here</a></p>
				</div>
			</td>
			</tr>
			</table>
		
		<?php	
	}
	
	
	public static function _findorder_dialog() {
		?>
		
		    <p>Your order number can be found in the receipt that''s emailed to you after your purchase, 
		    on your credit card statement, or on your PayPal receipt.</p>
		    <p>(<a id="showReceipt" href="javascript:void(0);" class="stdA">Show me an example</a>)</p>

		    <div id="receiptImg" style="display:none;">
				<p><a href="<?php echo CBP_IMG_URL ?>clickbank_receipt_1.png" target="_blank" ><img src="<?php echo CBP_IMG_URL ?>clickbank_receipt_1.png" /></a></p>
				
		    </div>

		    <h2>Credit Card</h2><p>If you paid with a credit card, you''ll find your order number located in the description of the charge.</p>
		    <p>(<a id="ccexample" href="javascript:void(0);" class="stdA">Show me an example</a>)</p>

		    <div id="creditCard" style="display:none;">
    			<p><a href="<?php echo CBP_IMG_URL ?>clickbank_receipt_2.png" target="_blank" ><img src="<?php echo CBP_IMG_URL ?>clickbank_receipt_2.png" /></a></p>    			
		    </div>

		    <h2>PayPal</h2><p>If you paid with PayPal, the order number is listed  as the Invoice ID under Transaction Details in your PayPal Account or will appear on your bill as "PAYPAL *CLICKBANK".</p>
		    <p>(<a id="pp2example" href="javascript:void(0);" class="stdA">Show me an example</a>)</p>

		    <div id="paypal2" style="display:none;">
    			<p><a href="<?php echo CBP_IMG_URL ?>clickbank_receipt_3.png" target="_blank" ><img src="<?php echo CBP_IMG_URL ?>clickbank_receipt_3.png" /></a></p>
		    </div>
				
		
		<?php	
	}


	public static function _show_summary() {
		global $cbpress;
		
		if(! CBP_install::table_exists()){
			return '<div style="text-align:center;">Database tables are not installed</div>';
		}
			$stats = CBP_query::get_summary();
			
			// abort($stats);
			$labels = "
					items_enabled : Active Products
					items_disabled : Inactive Unpublished Products
				H : ClickBank
					last_import_date : Last Import Date
					mall_clickbank : Marketplace Total Listings
					items_active 	: Marketplace Unique Products
					cats_clickbank : Total Categories
					new_clickbank : Recently activated
					items_removed 	: Removed Products
					new_import 		: Added to database
				H : Custom
					mall_custom 	: Custom Marketplace
					cats_custom 	: Custom Categories
					items_custom 	: Custom Products
					lists 	: Custom Lists";

				?>
					
			
			<?php
			
			
			
			$elems = CBP_content::get_section('stats');
			// dump($elems);

			$out = '';			
			$out = '<div class="cbdb"><table width="100%">';
			foreach ((array) $elems as $e) {
					$row = (object) $e; 
					$col = $row->id;
					$value = $stats->$col;
					$label = $row->label;
					
					if(isset($row->info['link'])){
						
						$label = CBP::link( $row->info['link'], $label, '');

						// $label = $row->label;
						
					}
					if($col !== 'last_import_date') $value = number_format($value);
					$out .= "<tr class=\"hoverable\" rel=\"#qrcode_stats_$col\">";
					$out .= "<td class=\"bul\">$label</td>";
					$out .= "<td align=\"right\" colspan=\"1\">" . $value . "</td>";
					$out .= "</tr>";
			}
					
			$out .= '</table></div>';
			return $out;
			
			
			
			
			
			
					
			$labels = explode("\r\n", trim($labels));
			$out = '';			
			$out .= '<div class="xxcbdb"><table width="100%">';
			$out2 = '<thead>
						<tr class="left">
							<th class="first" scope="col" align="center" colspan="1">Quick Stats</th>
							<th class="last" scope="col" colspan="1"></th>
						</tr>
					</thead>';
			// $out .= '<ul>';
			foreach ((array) $labels as $line) {
				$line = explode(":", trim($line));
				$col = trim($line[0]);
				$txt = trim($line[1]);
				if($col === 'H'){
					$out .= '<tr scope="col">';
					$out .= '<th class="sect" align="center" colspan="2">' . $txt . '</th>';
					$out .= '</tr>';
					//$out .= "<li><b>$txt</b></li>";
				}else{
					$value = $stats->$col;
					if($col !== 'last_import_date') $value = number_format($value);
					$out .= "<tr class=\"hoverable\" rel=\"#qrcode_stats_$col\">";
					$out .= "<td class=\"bul\">$txt</td>";
					$out .= "<td align=\"right\" colspan=\"1\">" . $value . "</td>";
					$out .= "</tr>";
					//$out .= "<li class=\"hoverable\" rel=\"#qrcode_stats_$col\">$txt<span class=\"float_r\">" . $value . "</span></li>";
				}
				unset($line,$col,$txt,$value);
			}
			foreach ((array) $stats->new as $dt => $x) {
				// $out .= '<li>'. $dt . '... ' . $x . '</li>';
			}
			// $out .= '</ul>';
			$out .= '</table></div>';

			// unset($labels,$stats);


			?>
				Recently Listed: 
				<a href="<?php echo CBP::admin('products') . '&added=new'; ?>"><?php echo number_format($stats->new_clickbank );?></a>
				
				
			<?php if(1 == 2){ ?>
				<br/>Categories: <?php echo number_format($stats->cats_clickbank );?>
				<table cellpadding="0" cellspacing="0" class="" id="xcb-stats" border="0">
				<tbody>
				<tr class="th">
					<td colspan="2"><div><b><a href="<?php echo CBP::get_admin_url('cats'); ?>">Categories</a></b></div></td>
					<td colspan="2"><div><b><a href="<?php echo CBP::get_admin_url('products'); ?>">Products</a></b></div></td>
				</tr>
				<tr>
					<td>ClickBank:</td><td width="100%"><?php echo number_format($stats->cats_clickbank );?> </td>
					<td>ClickBank:</td><td><?php echo number_format($stats->items_clickbank );?> </td>
				</tr>
				<tr>
					<td>Custom:</td><td><?php echo number_format($stats->cats_custom ); ?> </td>
					<td>Custom:</td><td><?php echo number_format($stats->items_custom ); ?> </td>
				</tr>
				<tr class="th"><td colspan="4"><div style="padding-top: 20px;"><b>Category To Product</b></div></td></tr>
				<tr>
					<td>ClickBank:</td><td><?php echo number_format($stats->mall_clickbank );?> </td>
					<td>Custom:</td><td><?php echo number_format($stats->mall_custom ); ?> </td>
				</tr>
				<tr class="th"><td colspan="4"><div style="padding-top: 20px;"><b>Lists</b></div></td></tr>
				<tr><td nowrap>Custom Lists</td><td><?php echo number_format($stats->lists ); ?></td></tr>
				<tr class="th"><td colspan="4"><div style="padding-top: 20px;"><b>Imports</b></div></td></tr>
				<tr><td nowrap>Last Import</td><td colspan="3"><?php echo $cbpress->options->last_import; ?></td></tr>
				</tbody>
				</table>
			<?php } ?>
			<?php
			
			return $out;
		}

	public static function _front_buttons() {
			$html = '<td><div class="hoverable" rel="#qrcode_setup_[key]"><a class="shortcut-button" href="[page]"><span><img src="[icon]" /><br />[label]</span></a></div></td>';
			$html_last = '<td><div class="hoverable last" rel="#qrcode_setup_[key]"><a class="shortcut-button" href="[page]"><span><img src="[icon]" /><br />[label]</span></a></div></td>';
			$keys = array('[key]','[page]','[icon]','[label]');			
			$r = array ('key','page','icon','label');     	
			$m = array(
				  array('import','import','front_cb.png','Importer'),
				  array('settings','settings','front_gears.png','Settings'),
				  array('cats','cats','front_cats.png','Categories'),
				  array('products','products','front_app.png','Products'),
				  array('product','products&tab=add','front_edit.png','Add'),
				 // array('system','stats','front_db.png','DB Summary'),
				 // array('help','help','front_links.png','Help'),
				 // array('setup','setup','front_unlock.png','Activate'),
			);
			// $out = '<ul class="shortcut-buttons-set">';
			$out = '<table class="shortcut-buttons-set" width="100%"><tr>';
			
			$i = 0;
			foreach($m as $row){
				$i++;
				$row = (object) array_combine($r,array_map('trim',$row));
				$row->icon = CBP_IMG_URL . $row->icon;
				$row->page = 'admin.php?page=cbpress-' . $row->page;
				if($i < count($m)){
					$out .= str_replace($keys, array_values( (array) $row), $html);
				}else{
					
					$out .= str_replace($keys, array_values( (array) $row), $html_last);
				}
				
				unset($row);
			} 			
			$out .= '</tr></table>';
			// $out .= '</ul>';
			unset($m,$keys,$html);
			return $out;
		}

	
}
