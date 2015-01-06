<?php
if (!defined('ABSPATH')) die();

class CBP_catadmin {


	var $options = array();

	var $baseurl = null;
	var $feat = null;
	var $root = null;

	var $stcats = array();
	var $tops = array();

	var $pid = null;
	var $msg = null;
	var $currcat  = null;

	public function __construct() {

		global $cbpress, $wpdb;


 		$this->options = &$cbpress->options;

 		

		$req = $this->options->get_requested();

		$this->feat = $req->feat_id;
		$this->root = $req->root_id;
		$this->baseurl = CBP::make_current_url();

		// rootid  = root
		// indexid = feat
		
		

		
		// dump($this->options);



		$this->pid = CBP::getv('pid');
		if(isset($_GET['cid']) && isset($_GET['pid'])==false) $this->pid = 0;
		$this->msg = '';
		$this->currcat = CBP_cat::get($this->pid);

		if(isset($_GET['deletecat'])){

			$dCat = CBP_cat::get($_GET['deletecat']);

			if($dCat->can_delete()){
				$dCat->delete();

			}else{
				$this->msg = 'Category cannot be deleted because it is active in the ClickBank marketplace XML feed.';			
			}
		}


	//	define( 'SAVEQUERIES', true );
	//	dump($wpdb->queries);

		## loadObjects

		$pidval = CBP::getv('pid',0);
		$items = CBP_cats::fetchall();
		
				// echo get_num_queries();
		$i = 0;		
		foreach ($items as $st) {
			$item = new CBP_cat( $st );			
			$this->stcats[$st->cid] = $item;
			if($item->pid == $pidval){
				$this->tops[] = $st->cid;
			}
			$i++;			
		}



		// abort($this->stcats);

	}






	public function admin() {

			$oCat = $this->currcat;
		 ?>



			<table width="100%">
			  <tr>
				<td valign="top" style="width: 230px; padding-right: 25px;">

				<?php
					// form


					$newcat = CBP_cat::structNew();
					$newcat->pid = $this->pid;
					$newcat->msg = (isset($_GET['msg'])) ? $_GET['msg'] : '';

					$subcatform = array(
						'cat' => $newcat
					);

					$t = ($oCat->cid > 0)? 'Create a sub category' : 'Create top-level category';

					CBP::postbox_start($t, 'line-height:16px;');

						if($oCat->name != '') echo '/ ' . $oCat->name . ' /'; 

						echo CBP::getview( 'form_cat', $subcatform, false );

						if($oCat->cid > 0){
							echo CBP::divider();
							echo CBP::link(CBP::admin('products')."&cid=".$oCat->cid, 'Manage products assigned to current category &raquo;');
						}
					CBP::postbox_end();
				?>

				<?php CBP::postbox_start('Legend', 'line-height:16px;'); ?>

					<div class="toggle"><b>ADD CATEGORY:</b></div>
					<div class="toggle_content">
					Any Custom Categories you add will remain intact when you update from the ClickBank XML feed.
					</div>

					<div class="toggle"><b>ROOT CATEGORY:</b></div>
					<div class="toggle_content">
					If checked, your Root Category's 
					Sub Categories and Category Products will display on your 
					Marketplace instead of the entire Marketplace. 
					Remember, product displays are based on active product 
					selections and display filters.
					</div>

					<div class="toggle"><b>FEATURE CATEGORY:</b></div>
					<div class="toggle_content">
					Products in your featured category will 
					show on your Marketplace front page underneath either the entire Marketplace or your Root Category selcted.  
					Only one Category can be featured at a time. 
					Remember, product displays are based on active product selections and display filters.
					</div>

					<div class="toggle"><b>ENABLE:</b><br/></div>
					<div class="toggle_content">
					Enabled Categories are the only categories that will display in your Marketplace.
					</div>

					<div class="toggle"><b>DELETE / EDIT:</b></div>
					<div class="toggle_content">
					ClickBank Categories cannot be edited or deleted because that would cause problems with your ClickBank update. 
					Only Custom Categories may be edited or deleted.
					</div>

					
					
					<hr>
					<br/>
						<p>
							
							<b>For more info</b> about the ClickBank Marketplace Feed  							
							<a href="http://www.clickbank.com/help/account-help/account-tools/marketplace-feed/" 
							target="_blank">CLICK HERE</a>
						</p>
						
					<br/>
						
						<p>
							<b>For more info</b> about ClickBank Marketplace Categories  	
							<a href="http://www.clickbank.com/help/affiliate-help/affiliate-basics/marketplace-categories/" 
							target="_blank">CLICK HERE</a>
						</p>
						
				<?php CBP::postbox_end(); ?>	
				</td>

				<td valign="top">
					<?php 
						CBP::postbox_start('Curent Category');
							$crumbs = $oCat->breadcrumbs(array('baseurl' => $this->baseurl, 'label' => 'Top Level', 'topid'=>0, 'vp'=>'pid'));
							echo CBP::div($crumbs,'cbp_breadcrumbs'); 	  		
						CBP::postbox_end();

						if(count($oCat->subs)) echo '<p>Sub-categories :</p>';

						if($this->msg != '') echo '<p>'.$this->msg.'</p>';

						$this->table();
					?>
				</td>
			  </tr>
			</table>


		<?php 



	}




	public function table() {
		 ?>

		<div id="cats">
		<div class="cb-table cb-table-style-1">


			<table class="widefatx" summary="" xwidth="100%">
				<thead>
				<tr>
					<th>Cat ID</th>
					<th>Category Name</th>
					<th class="num"># Prods</th>
					<th>Root</th>
					<th>Feat</th>
					<th>Type</th>
					<th>Enable</th>
					<th>Del</th>
					<th>Edit</th>
				</tr>
				</thead>
				<tbody class="icons">
					<?php
					$rows = '';
					foreach($this->tops as $cid) {
						$rows = $this->recurse($cid);
						echo $rows;
					}
					?>
				</tbody>
			</table>
			<?php
					if($rows == ''){ 
						echo '<p>This category does not have any sub-categories</p>';
						echo '<p>You can add a new one from the box on the left</p>';
						if($this->pid == 0) echo '<p><a href="admin.php?page=cbpress-import">Click here to import</a> &raquo;</p>';
					}
			?>
		</div>
		</div>


		<?php

	}

	// recursive makerow

	function recurse($cid, $level=0) {
			static $space = ' &nbsp  &nbsp ';
			static $recursions = 0;
			$recursions++;
			if($recursions > 11200) return false;

			$output =  "";
			$cat = $this->stcats[$cid];
			ob_start();
				$this->to_row($cat,$level);
				$output .= ob_get_contents();
			ob_end_clean();




			return $output;

			

			$children = $cat->subs;

			if ($children) {
				foreach($children as $cid) {
					$output .=  $this->recurse($cid, $level+1);
				}
			}
			return $output;
	}
	// table row


	function enabled($cid) {
		return ($cid > 0) ? $this->stcats[$cid]->enabled : 1;
	}


	function to_row($cat,$level) {


   				//  cbp static function link( $url, $title = '', $css='', $target='' )
				//  fn	static function a($text,$path,$class=false,$dontprint=false)

		$cid = $cat->cid;
		$style = '';
		$trclass = ' class="top"';

		if($level > 0){
			$x = 30 * $level;
			if($cat->pid > 0){
				$trclass = ' class="child ' . $cat->pid . '"';
			}
		} else {

		}

		$parent_enabled = $this->enabled($cat->pid);








			$id = $cat->cid;
			$jsid = "'{$id}'";
			$sub = $pub = '';
			$name = $cat->name;
			$cssid = '';
			$subcount = count($cat->subs);
			$cssid = '';
			$iscustom = $cat->type == "custom";

			$isroot = ($this->root == $id) ? '1' : '0';
			$isfeat = ($this->feat == $id) ? '1' : '0';

			$uri_feat = $this->baseurl.'&fa=setfeat-'. (($isfeat) ? 0 : $id);
			$uri_root = $this->baseurl.'&fa=setroot-'. (($isroot) ? 0 : $id);




			$uri_delete = $this->baseurl. '&pid=' .$cat->pid . '&deletecat='.$id;



			$uri_down = $this->baseurl."&pid=$id";




			
			
			$feat_tick = 'disabled.png';
			if($isfeat){
				$feat_tick = 'featured.png';			
			}
			
			
			$isroot = ($this->root == $id) ? 'on' : 'off';
			$isfeat = ($this->feat == $id) ? 'on' : 'off';
			
			
			$feat_tick = 'check_'. $isfeat .'.png';
			
			$prod_url = CBP::admin('products').'&cid='.$id;				
			$prod_url = ($cat->prodcount > 0) ? CBP::link( $prod_url, $cat->prodcount . ' products', '', '') : '-';
		?>

		<tr id="item_<?php echo $cat->cid ?>"<?php echo $trclass?>>

			<td class="id"><?php echo $cat->cid;?></td>


			<td class="name">					
				<div<?php echo $style; ?>>
				<?php				
					echo str_repeat('<span class="gi">|&mdash;</span>', $level);
					if ($cat->can_edit()){
					// echo '<a href="javascript:void(0)" style="float:right" id="'.$cat->cid.'" class="editcat">' . CBP::img('edit-icon.png','edit category') . '</a>';
					}
					cbpressfn::a($cat->name,$uri_down);
					if(!$iscustom && $cat->removed){
						echo '&nbsp;&nbsp; <b><em title="removed from feed">(removed)</em></b>';
					}
				?>
				</div>
			</td>

			<td class="num">
			<?php echo $prod_url; ?>
			</td>
			<td class="icon"><a href="<?php echo $uri_root; ?>" class="root"><?php echo CBP::img('check_'. $isroot .'.png');?></a></td>
			<td class="icon"><a href="<?php echo $uri_feat; ?>" class="index"><?php echo CBP::img($feat_tick);?></a></td>


			<td nowrap>			
				<?php
					if(!$iscustom && $cat->removed){
						echo CBP::img('type_' . $cat->type . '_removed.png', 'This ClickBank category appears to have been removed from the XML feed)');
					} else {
						echo CBP::img('' . $cat->type . '.png', $cat->type . ' category');
					}
				?>
			</td>


			<td class="icon"><?php cbpressfn::a(CBP::img(intval($cat->enabled).'_check_'.$parent_enabled.'.png'),$this->baseurl."&cid=" .  $cat->pid . "&fa=togglecat-".$id);?></td>
			<td class="icon">
				<?php if ($cat->can_delete()): ?>
					<a href="<?php echo $uri_delete; ?>" class="deletecat"><?php echo CBP::img('icon_remove_1.png');?></a>
				<?php else : ?>
					-
				<?php endif; ?>
			</td>

			<td class="icon">
				<?php
				if ($cat->can_edit()){

					echo '<a href="javascript:void(0)" id="'.$cat->cid.'" class="editcat">' . CBP::img('edit-icon.png','edit category') . '</a>';
				}else{
					echo '-';
				}
				?>
			</td>

		</tr>





		<?php if(1  === 2){ ?>
			<td class="icon"><a href="<?php echo $uri_root; ?>" class="root"><?php echo CBP::img('icon_root_'. $isroot .'.png');?></a></td>
			<td class="icon"><a href="<?php echo $uri_feat; ?>" class="index"><?php echo CBP::img('icon_index_'. $isfeat .'.png');?></a></td>
			<tr>
				<td><?php echo $cat->cid; ?></td>
				<td<?php echo $style; ?>><?php echo $cat->name; ?></td>
				<td><?php echo $cat->enabled; ?></td>
			</tr>
			<td><?php cbpressfn::a('products',CBP::get_admin_url('products') . '&cid='.$cid,'button-secondary');?></div></td>
		<?php } ?>



		<?php

	}




}
