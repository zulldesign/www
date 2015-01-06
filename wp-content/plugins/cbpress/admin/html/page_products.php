<?php

class cbp_products_controller {



	static private $opts;

	static private $args;

	static private $result;

	static private $tools;

	static private $toggle;



	// products-product controller



	function index() {



		

		

		global $cbpress;

		self::$tools = 1;

		self::$toggle = 0;



		self::$opts = &$cbpress->options;



		$tab = @$_REQUEST['tab'];

		// $msg = @$_REQUEST['msg'];

		$modal = (!isset($modal)) ? 0 : $modal;

		// if(! $msg == '') echo "<div class=\"updated below-h2\" id=\"message\"><p>$msg</p></div>";



		if(isset($_REQUEST['lid'])){

			$lid = @$_REQUEST['lid'];

			$lid = absint($lid);



		

			$tab = isset($tab) ? $tab : 'info';

			// if(is_numeric($lid) || isset($_POST['save']) ) $action = 'edit';

		

		} else{

			$tab = isset($tab) ? $tab : 'list';

			$lid = @$_REQUEST['lid'];

			$lid = absint($lid);

		}





				// abort(get_defined_vars());

		

		switch ($tab) {

			case 'add':

			case 'edit':

			case 'info':

			case 'delete':



				$lid = absint($lid);

				$item = CBP_prod::load($lid);

				// abort($tab);

				$tab = ($item->lid < 1) ? 'add' : $tab;

				$item->show_page($tab);



				// include(CBP_VIEWS_DIR . 'prod.php');

				break;



			case 'list':

			default:

				self::load_results();

				break;

		}













	}







	function load_results() {



		global $cbpress;



		########



		$out = new stdClass();



		$data 	= CBP_query::getSearch(array(

				'perpage'=> self::$opts->admin_pp

			), 'ARRAY_A');







		// Convert to object class



			self::$args   = &$data->args;

			self::$result = &$data->result;

			

			/**********

			$i = 0;

			foreach(self::$result as &$row){

				$i++;

				$row = new CBP_prod( $row );

			}

			***********/



			$found 	= $data->found;

			$pager = &$data->pager;



			$thispage = CBP::thispage();



		########



		$pagelinks =  '<div id="pager" class="tablenav"><div class="tablenav-pages">' . $pager->page_links(). '</div></div>';

		/******

		$pag = new CBP_pagination();

		$pag->Items($found);

		$pag->limit(self::$args->perpage);

		$pag->target("admin.php?page=$thispage");

		$pag->currentPage($pager->current_page);

		******/

		

		########



		$dtf = get_option('date_format');

		$dtf = "Y-m-d";

		$showdetail = 1;



		########



		$ranklabel = __('Avg Rank', CBPRESS_TRANS);

		$rankcol = 'PopularityRank';

		$rankcol = '';

		if(self::$args->cid > 0){

			$ranklabel = __('Rank', CBPRESS_TRANS);

			$rankcol = 'rank';

		}

		

		

		

		$addlink = admin_url("admin.php?page=cbpress-products&tab=add");

		$baseurl = $_SERVER['REQUEST_URI'];

		$baseurl = remove_query_arg('fa', $baseurl);







		########









		if ( $found > 0 ) {

					// BREADCRUMBS

					$oCat = CBP_cat::get(self::$args->cid);

					$backcid = (isset($_REQUEST['backcid'])) ? $_REQUEST['backcid'] : $oCat->pid;

					$baseurl = CBP::make_current_url() . '&backcid=' . $backcid;

					$topurl = CBP::get_admin_url('cats') . '&pid=' . $backcid;

					if(self::$args->cid > 0){

						echo cbpressfn::divtag( $oCat->breadcrumbs( array( 'baseurl'=>$baseurl, 'topurl'=>$topurl, 'label'=>' ... categories ' ) ) ,'cbp_breadcrumbs');

					}

		?>



						

						

						

					<div class="tablenav">

						<div class="alignleft actions">

							<span class="displaying-num">

							Results (<?php echo number_format($found) ?> products)

							</span>

							<?php

								//	abort(get_defined_vars());

							

								if(self::$args->perpage < $found){

									$pag1 = new CBP_pagi($found,self::$args->perpage,'dropdown');

									echo $pag1->pagination_string;

								}



								if(self::$args->perpage < $found && 1 == 2){

									$pagenav 	= &$data->pager->page_links_frontend();

									$show_nextn = strlen(trim($pagenav->links));

									$pagelinks =  '<div id="pager" class="pagination"><div class="tablenav-pages">' . $pagenav->html . '</div></div>';

									$pagelinks =  $pagenav->html;

									echo $pagelinks;

								}

							?>

							

							

						</div>

					</div>

					

							



					

					

					<div style="margin: 20px 0px 20px 0px;">

						<div class="cb-table cb-table-style-1">

						<div id="products">

							<?php

								self::show_results( get_defined_vars()  );

							?>

						</div>

						</div>

						<?php 

							if(self::$tools){

								self::show_tools(); 

							}

						?>

					</div>



			<?php }else{

	

					$link = $_SERVER['REQUEST_URI'];

					$link = remove_query_arg( 'page', $link );

					$addlink = $link . '&page=cbpress-products&tab=add';

					$implink = $link . '&page=cbpress-import';

					echo 'No products were found';

					echo '<br><br><br><br>';

					echo '<li><a href="' . $addlink . '" class="">Add Custom Product<span class="new-window"></span></a></li>';

					echo '<li><a href="' . $implink . '" class="">Import New Products<span class="new-window"></span></a></li>';

					echo '<div class="noproducts"></div>';



			}











	}





	function show_tools() {



		$o = array('showpath'=>0, 'type'=>'custom');

		$cats = CBP_cats::category_dropdown_array(0, $o );

		?>

		<table><tr><td>

		<div class="pager2 pagertools">

			<table border="0" class="" cellpadding="0" cellspacing="0">

				<tr>

				    <td style="padding-right:20px;">

					<?php

						echo '<select name="add_cat_id" id="add_cat_id" class="select">';

						echo '<option value="" selected>* add checked to category *</option>';

						echo cbpressfn::select( $cats );

						if(!count($cats)) echo '<option value="">(0 categories found)</option>';

						echo '</select>';

						echo '<input class="arr add-all-cat" type="submit" value="submit"/>';

					?>

				    </td>

				    <td>

					<?php

						$lists = CBP_lists::get_for_select();

						echo '<select name="add_list_id" id="add_list_id" class="select">';

						echo '<option value="" selected>* add checked to custom list *</option>';

						echo cbpressfn::select( $lists );

						if(!count($lists)) echo '<option value="">(0 custom lists found)</option>';

						echo '</select>';

						echo '<input class="arr add-all-list" type="submit" value="submit"/>';

					?>

				    </td>

				  </tr>

			</table>

		</div>

		</td></tr></table>





		<?php



	}



	function show_results($tvars=array()) {



		$showdetail = 0;





		// global self::$args;

		

		

		/*******************

		

		$heads = CBP_Meta::getResultHeaders();

		$prodcols = explode(',',$this->options->product_cols);

		$reshead = array();

		foreach ($prodcols as $id) {

				if(isset($heads[$id])){

					$reshead[$id] = $heads[$id];

				}

		}

		

		***************/

		

		

		// abort($reshead);

		do_action('cbp_tooltips','prod');



		$reshead = CBP_Meta::getResultHeaders();









		foreach($tvars as $key => $val) { $$key = $val; }





		$show_desc = 0;

		$i = 0;



		?>





			<table class="widefat" id="items" cellspacing="0" cellpadding="0" style="width:100%;">





			<thead class="tooltips">

				<tr>

					<th class="">Type</th>

					<?php if(self::$tools){ ?>

						<th class="check-column"><input type="checkbox" name="check-all" /></th>

					<?php } ?>

					<?php if(self::$toggle){ ?>

						<th class="toggle_column">

							<?php echo (self::$args->joined) ? 'c' : 'p'; ?>

						</th>

					<?php } ?>





					<?php 

						foreach ($reshead as $id => $col) {

							$label = $col['short'];

							$okay = 1;

							if($id == 'rank'){

								$id = $rankcol;

								$label = $ranklabel;								

								if($rankcol == '') $okay = 0;								

							}

							$rel = ' rel="#qrcode_prod_' . $id . '"';

						?>

						<?php if($okay > 0){ ?>

						<th<?php $pager->sortable_class($id) ?><?php echo $rel?>><?php echo $pager->sortable($id, __($label, CBPRESS_TRANS)) ?></th>

						<?php } ?>					

					<?php } ?>

					<th class="add_column"></th>
					<th class="add_column"></th>

				</tr>

			</thead>

			<tbody>



			<?php 

			foreach(self::$result as $prod){

					$arr = array();

					foreach ($reshead as $id => $col) {

							// $arr[$id] = $prod->filter($id,$prod->$id);

					}

					// abort($arr);

					$data = $prod->data;



					foreach ($data as $c => $v) {

							$arr[$c] = $prod->filter($c,$v);

					}



					// abort($arr);

					$item = array_merge($data,$arr);

					extract($item);

					// abort($item);



				$i++;





				$links = $prod->get_action_links();



				/*****************

				$oDate = new DateTime($created);

				$sDate = $oDate->format("m/d/y");



				$ago = cbpressfn::days_since($ActivateDate);

				$ago = new DateTime($ActivateDate);

				$ago = $ago->format("Y, M d");

				

				****************/



				

				$toggleURL = $baseurl;

				$toggleFA = (isset($join_id)) ? 'toggleprod-'.$join_id : 'toggleprod-'.$lid;

				$toggleURL = add_query_arg( array('fa'=>$toggleFA,'noheader'=>true), $baseurl );

				if(isset($join_id)){

					$onoff = ($join_enable) ? 'on':'off';

					$onoff = 'check_'. $onoff . '.png';

				}else{									

					$onoff = ($data['active']) ? '1':'0';

					$onoff = $onoff.'_check.png';

				}



				if(1 == 1){

					$checker = CBP::img($onoff);

					$togg = CBP::link( $toggleURL, $checker, '');

				}



				// cbpressfn::a($checker, $togg );





				$_cbox = '<input type="checkbox" name="checked[]" value="' . $lid . '" />';





				// $_vin = ($vin == '') ? '<div class="icon_na">n/a</div>' : $vin;







				$recurring = ($prod->HasRecurringProducts) ? 'Yes' : '-';

				

				$_tclass = '';

				$_dclass = '';



				if($tc) $_tclass = '<span class="icon_tc" title="title different from feed title"></span>';

				if($dc) $_dclass = '<span class="icon_dc" title="description different from feed description"></span>';



				$clss = '';

					// if ($i > 1) $clss = ' cbp_row';

					if ($i % 2 == 0) $clss = ' alt';





					$statusinfo = '';

					if($source == 'clickbank'){

						if($data['status'] == 'removed'){

							$statusinfo = "Removed from marketplace $date_status";						

						}

					}

				?>



				<tr class="item<?php echo $clss ?>">

				

				

				

					<td>

					<?php 

					

						echo CBP::link( $links->edit, CBP::img('type_'.$source.'.png', $source . ' product'), '');

						?></td>





					<?php if(self::$tools){ ?>

						<th class="check-column"><?php echo $_cbox ?></th>

					<?php } ?>

					

					<?php if(self::$toggle){ ?>

						<td><?php echo $togg ?></td>

					<?php } ?>

					

					

					

					<td><?php echo $lid ?></td>

					<td class="vin"><?php echo $vin ?></td>

					<td class="first">





							<?php if($source == 'clickbank'){



								echo CBP::link($links->more, esc_html($title), 'title') . (($tc)?'<em title="Product title has been edited"> ***</em>':'');





								if($show_desc){



									if($dc){

										echo CBP::div($description  . '<em>[diff]</em>' ,'desc', 'Product description does not match ClickBank XML feed');

									} else {

										echo CBP::div($description,'desc');

									}

								}



							} else {



								echo CBP::link($links->more, esc_html($title), 'title');



								// echo $redirect_url;

								///// echo CBP::div('' . $redirect_url,'url');

										if($show_desc) echo CBP::div($description,'desc');



							}

							?>







					</td>

					<td<?php if($status == "Removed") { echo ' class="removed"'; } ?> title="<?php echo $statusinfo; ?>">

					

					<?php 

						echo $status;

					?>



					</td>

					<td><?php echo $created ?></td>

					<td><?php echo $ActivateDate ?></td>

					<?php if($rankcol != ''){ ?>

						<td><?php echo $rank ?></td>

					<?php } ?>

					<td><?php echo $Gravity ?></td>

					<td><?php echo $Commission ?></td>

					<td><?php echo $InitialEarningsPerSale ?></td>

						

					<?php if($prod->is_clickbank()){ ?>

						<td><?php echo $recurring; ?></td>

					<?php }else{ ?>

						<td>-</td>

					<?php } ?>

						

					<td class="actions" width="1%" nowrap>
						<?php if(1 == 11){ ?>
							<a href="<?php echo $links->view ?>" class="thickbox">View<span class="new-window"></span></a>
							<a href="<?php echo $links->more ?>">Detail</a>
						<?php } ?>




						<?php 	

						echo CBP::link( $links->edit, CBP::img('edit4.gif','edit'), ''); 

							//$visitlink = $links->visit;

							// echo CBP::link( $links->delete, CBP::img('icon-delete.gif','delete product'), '');

						?>

						&nbsp;

						<a href="<?php echo $links->visit ?>" title="Product Sales Page: <?php echo esc_html($title) ?>" class="hop-visit external"><?php echo CBP::img('1314957592_search.gif');?></a>

					</td>
					<td class="actions" width="1%" nowrap>
						<a href="<?php echo $links->post ?>">Make Post</span></a>
					</td>

				</tr>

			

			

			<?php }; ?>

			

			</tbody>

			</table>



		<?php



	}

















}





cbp_products_controller::index();

