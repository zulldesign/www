<?php


	if(isset($search_args) && is_array($search_args)){


		$args = CBP_query::getSearchParams($search_args);


	}else{


		$args = CBP_query::getSearchParams();


	}


	$formwrap = 0;





?>


	<?php if($formwrap){ ?>


		<form action="<?php admin_url('admin.php?page=cbpress-products')?>" method="GET">


	<?php } ?>


	


	<table class="searchform">


		<tr>


			<td valign="top" style="padding-right: 20px;">





				<table width="100%">





				<?php if(1  === 1){ ?>





					<tr>


						<th><label><?php _e ('Status', CBPRESS_TRANS ); ?>:</label></th>


						<td>


							<select id="status" name="status">


							<option value=""<?php if($args->status == ''){print ' selected';}?>>All</option>


							<option value="active"<?php if($args->status == 'active'){print ' selected';}?>>Active</option>


							<option value="removed"<?php if($args->status == 'removed'){print ' selected';}?>>Removed</option>


							</select>





							<select id="active" name="active">


							<option value=""<?php if($args->active == ''){print ' selected';}?>>All</option>


							<option value="1"<?php if($args->active == '1'){print ' selected';}?>>Enabled</option>


							<option value="0"<?php if($args->active == '0'){print ' selected';}?>>Disabled</option>


							</select>





						</td>


					</tr>


					<?php } ?>





				<?php if($formwrap){ ?>


					<tr>


						<th><label><?php _e ('Search', CBPRESS_TRANS ); ?>:</label></th>


						<td>


							<?php echo cbpressfn::input('keywords','text',$args->keywords)?>


						</td>


					</tr>


				<?php } ?>





					<tr>


						<th><label><?php _e ('Look In', CBPRESS_TRANS ); ?>:</label></th>


						<td>


							<select id="lookin" name="lookin">


							<option value="td"<?php if($args->lookin == 'td'){print ' selected';}?>>Title & Description</option>


							<option value="t"<?php if($args->lookin == 't'){print ' selected';}?>>Title Only</option>


							<option value="d"<?php if($args->lookin == 'd'){print ' selected';}?>>Description Only</option>


							<option value="v"<?php if($args->lookin == 'v'){print ' selected';}?>>Vendor Only</option>


							<option value=""<?php if($args->lookin == ''){print ' selected';}?>>All</option>


							</select>





						</td>


					</tr>








					<tr>


						<th><label><?php _e ('Type', CBPRESS_TRANS ); ?>:</label></th>


						<td>


							<select id="source" name="source">


							<option value=""<?php if($args->source == '' || $args->source == ''){print ' selected';}?>>Any</option>


							<option value="clickbank"<?php if($args->source == 'clickbank'){print ' selected';}?>>ClickBank</option>


							<option value="custom"<?php if($args->source == 'custom'){print ' selected';}?>>Custom</option>


							</select>








							<select id="billing" name="billing">


							<option value="all"<?php if($args->billing == 'all' || $args->billing == ''){print ' selected';}?>>Any Billing Type</option>


							<option value="1"<?php if($args->billing == '1'){print ' selected';}?>>Recurring Billing</option>


							<option value="0"<?php if($args->billing == '0'){print ' selected';}?>>Standard Billing</option>


							</select>




















						</td>


					</tr>








					<tr>


						<th><label><?php _e ('Sort By', CBPRESS_TRANS ); ?>:</label></th>


						<td>


							<select id="sort" name="sort">


							<?php


								$sortopts = CBP_Meta::getSortByList();


								foreach ($sortopts as $k =>$v) {


									$sel = selected( $args->sort, $k, false );


									echo "<option value='{$k}'{$sel}>{$v}</option>";


								}


							?>


							</select>





							<select id="order" name="order">


								<option value="asc"<?php if($args->order == 'asc' || $args->order == ''){print ' selected';}?>>Asc</option>


								<option value="desc"<?php if($args->order == 'desc'){print ' selected';}?>>Desc</option>


							</select>








						</td>


					</tr>


					<tr>


						<th><label><?php _e ('Cat', CBPRESS_TRANS ); ?>:</label></th>


						<td>








								<?php


								


								// $catopts = CBP_cats::category_dropdown_tree_db(0,$args->cid);


								// $subtree = CBP_cats::get_flat_branch(0,null);


								//  abort($subtree);





									if(1 == 1){										


										// $catopts = CBP_cats::category_dropdown_tree_db(0,$args->cid);


										$catopts = CBP_cats::category_dropdown_tree(0,$args->cid);


										echo '<select id="cid" name="cid">';


										echo '<option value="All"' . ( $args->category=='all' ? ' selected="selected"' : '') . '>All</option>';


										echo $catopts;


										echo '</select>';


									}


								


								// echo cbpressfn::select( CBP_cats::get_for_select(), $args->cid );





								


								?>


						</td>


					</tr>





					<tr>


						<td colspan="2">





						</td>


					</tr>











					<tr>


						<th><label><?php _e ('Added', CBPRESS_TRANS ); ?>:</label></th>


						<td>


							<select id="added" name="added" class="">


								<option value=""<?php if($args->added == ''){print ' selected';}?>>All</option>


								<option value="new"<?php if($args->added == 'new'){print ' selected';}?>>New in CB</option>


								<option value="today"<?php if($args->added == 'today'){print ' selected';}?>>Today</option>


								<option value="week"<?php if($args->added == 'week'){print ' selected';}?>>Last 7 days</option>


								<option value="month"<?php if($args->added == 'month'){print ' selected';}?>>In Last Month</option>


								<option value="3months"<?php if($args->added == '3months'){print ' selected';}?>>3 months</option>


								<option value="6months"<?php if($args->added == '6months'){print ' selected';}?>>6 Months</option>


								<option value="year"<?php if($args->added == 'year'){print ' selected';}?>>Year</option>


							</select>


						</td>


					</tr>











				</table>








			</td>





			<td valign="top">


				<table>


					<tr>


						<td  colspan="2" >


							<table xxxxstyle="border: 1px #dddddd solid; background:#eeeeee;">


								<tr style="background:#cccccc;">


									<td><b>Filter</b></td>


									<td><b>Min</b></td>


									<td><b>Max</b></td>


								</tr>


							<?php


								$arr = CBP_Meta::getMinMaxList();


								//	abort($arr);


								foreach ($arr as $k =>$v) {


									$c = strtolower($k);


									$mincol = 'min_' . $c;


									$maxcol = 'max_' . $c;


									echo '<tr>';


									echo '<td><label>' . $v['label'] . ':</label></td>';


									if($v['type'] == 'select' && 1 == 2){


											// echo '<td>' . cbpressfn::selectSimple(''.$mincol, $v['choices'], $args->$mincol, false, 'narrow') . '</td>';


											// echo '<td>' . cbpressfn::selectSimple(''.$maxcol, $v['choices'], $args->$maxcol, false, 'narrow') . '</td>';


											echo '<td>' . cbpressfn::input(''.$mincol, 'text', $args->$mincol, 'short') . '</td>';


											echo '<td>' . cbpressfn::input(''.$maxcol, 'text', $args->$maxcol, 'short') . '</td>';


									}else{


											echo '<td>' . cbpressfn::input(''.$mincol, 'text', $args->$mincol, 'short') . '</td>';


											echo '<td>' . cbpressfn::input(''.$maxcol, 'text', $args->$maxcol, 'short') . '</td>';


									}


									echo '</tr>';


								}


							?>


							</table>


						</td>


					</tr>


				</table>





				


				


				<br/>

				<input name="cc" id="cc" type="checkbox" value="1" <?php echo $args->cc==1?"checked=\"checked\"":'';?> /> 


				Show customized products only
				
				
				

				<br/>


				



						


				<?php if($formwrap){ ?>		


					<br/><br/>				


					<div><input type="submit" value="Search" class="button-primary" /></div>


				<?php } ?>





			</td>


		</tr>


	</table>





<?php if($formwrap){ ?>


	<input id="search" name="search" type="hidden" value="1" />


	<input id="page" name="page" type="hidden" value="cbpress-products" />


	</form>


<?php } ?>











<?php if(1 == 2){ ?>


					<tr>


						<th><label><?php _e ('Billing', CBPRESS_TRANS ); ?>:</label></th>


						<td>


							<select id="billing" name="billing">


							<option value="all"<?php if($args->billing == 'all' || $args->billing == ''){print ' selected';}?>>All Products</option>


							<option value="1"<?php if($args->billing == '1'){print ' selected';}?>>Recurring Billing</option>


							<option value="0"<?php if($args->billing == '0'){print ' selected';}?>>Standard Billing</option>


							</select>


						</td>


					</tr>


					


					


			<table>


			<tr>


				<th><label><?php _e ('Per page', CBPRESS_TRANS ); ?>:</label></th>


				<td>


					<?php echo cbpressfn::selectSimple('perpage',array(10=>10,20=>20,30=>30,50=>50,100=>100),$args->perpage,false,'narrow')?>


				</td>


			</tr>


			</table>			


			<th><label><?php _e ('Detailed', CBPRESS_TRANS ); ?>:</label></th>


				<td>


				<input style="margin-top:8px;width:10px" name="result_view" id="result_view" type="checkbox" value="detailed" <?php echo $args->result_view=='detailed'?"checked=\"checked\"":'';?> />


				</td>				


					<div style="clear:both"></div>


					<tr>


						<th><label><?php _e ('Search In', CBPRESS_TRANS ); ?>:</label></th>


						<td>





						</td>


					</tr>


					<tr>


						<th><label><?php _e ('Vendor', CBPRESS_TRANS ); ?>:</label></th>


						<td>


							<?php echo cbpressfn::input('_vendor','text',$args->vendor)?>


						</td>


					</tr>


					<tr>


						<th><label><?php _e ('Active', CBPRESS_TRANS ); ?></label>:</th>


						<td>


							<input style="margin-top:8px;width:10px" id="active" name="_active" type="checkbox" value="1" <?php echo $args->active==1?'checked="checked"':'';?>/>


						</td>


					</tr>


<?php } ?>


