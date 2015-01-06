<?php
	$action = cbpressfn::getparam('action');
	$list_id = cbpressfn::getparam('list_id');
	$hop = cbpressfn::getparam('hop');
	$lid = cbpressfn::getparam('lid');

	$baseurl = CBP::make_current_url();
	$link = array();
	$link = cbpressfn::html_link($baseurl,'Categories');
	$thispage = CBP::thispage();
	$pid = cbpressfn::getparam('cid');

	$listname = cbpressfn::getparam('list_name');


	$pager = new CBP_Pager ($_GET, $_SERVER['REQUEST_URI'], 'list_id', 'DESC', 'lists');
	$pager->per_page = 10;
	$lists = CBP_lists::getall($pager);

	$dateFormat = 'n/j/Y g:i A';


	$counter = 0;
	echo '<br>';

	class CBP_listadmin {
		var $curr = array();
		var $listcom;
		var $results;

		public function tabledef() {
			$colModel = CBP::get_arguments('list_id=ID&list_name=List Name&list_slug=Slug&list_enable=Enabled&created=Creaded&modified=Modified');
			$colModel = (object) $colModel;
			$params = array(
				'tfoot'    => 0,
				'id' 	   => 'list_id',
				'colModel' => $colModel,
				'data' 	   => $this->results
			);
			return $params;
		}
		public function __construct($lists) {
			$this->results = &$lists;
			$list_id = cbpressfn::getparam('list_id');
			$this->listcom = CBP_list::load($list_id);
		}
		public function table1() {
			$params = $this->tabledef();
			echo cbpressfn::array2table($this->results,'list_id,list_name',$params['colModel']);
		}
		public function listform() {
			$this->listcom->form();
		}
		public function table() {
		}
	}

	$manager = new CBP_listadmin($lists);
	$listcom = &$manager->listcom;

?>

<div id="category_dialog" style="display:none;"></div>
<div id="editwin" style="display:none;"></div>


<table width="100%">
	<tr>
		<td valign="top" style="width: 230px; padding-right: 25px;">
			<?php $manager->listform(); ?>
			
			

				<?php
						$link = admin_url('admin.php?action=cbp-list-topfive');
						$link = cbpressfn::a('Click here',$link,'',true);	
				?>
			<fieldset style="line-height 20px; border:1px solid #ddd; padding:10px 10px 10px 10px; margin:0px 0px 10px 0px;">	
		
			<strong>Need an example?<br/></strong>			
			To create a list containing the Top 5 Ranking ClickBank Marketplace products... 
			<?php echo $link; ?>
			</fieldset>
			
			
			
			
		</td>
		<td valign="top">


				<?php

					$pagelinks =  '<div id="pager" class="tablenav"><div class="tablenav-pages">' . $pager->page_links(). '</div></div>';
					echo $pagelinks;



				?>

		<table id="report" class="">
		<thead>
		<tr><th width="1%">ID</th><th>List Name</th><th>Products in list</th><th>Shortcode</th><th></th><th></th></tr>
		</thead>
		<tbody>
		<?php if($lists) {?>

			<?php foreach($lists as $row) :?>
				<?php
			$counter++;
				$id = $row->list_id;
				$items = CBP_lists::getitems($id);
				$expand = ($id == $list_id);
				if($list_id == '' && $counter == 1){
					$expand = true;
				}
					$shortcode = '[cbpress list=' . $row->list_id . ']';
					$shortcode = "<input name='shortcode_$id' id='shortcode_$id' type='text' value='$shortcode' class='shortcode' />";
				?>
			<tr id="parent" >
				<td><?php echo $row->list_id;?></td>
				<td><b><?php echo $row->list_name;?></b></td>
				<td><?php echo count($items);?></td>
				<td><?php echo $shortcode; ?></td>					
				<td><?php 					
				$ld = admin_url('admin.php?action=cbp-list-delete&list_id='.$id);				
				cbpressfn::a('delete list',$ld,'button-secondary');					
				?></td>
				<td><div class="arrow"></div></td>
			</tr>
			
			<tr id="child" <?php if($expand) echo ' class="expand"'?>>
				<td colspan="1"></td>
				<td colspan="5">
					<div>
						<table>
							<tr>
							<td valign="top" style="padding: 0px 0px 0px 30px;">							
								<label><strong>Add product by vendor:</strong></label>									
								<form action="<?php echo $baseurl; ?>" method="GET" title="type clickbank vendor">
									<?php echo cbpressfn::input('hop','text','','vendorSuggest')?>								
									<?php echo cbpressfn::input('action','hidden','cbp-save-list-item')?>								
									<?php echo cbpressfn::input('list_id','hidden',$row->list_id)?>
									<?php echo cbpressfn::input('','submit','Add Product','additem button-secondary')?> 									
								</form>								
							</td>
							<td valign="top" style="padding: 0px 0px 0px 30px;">
								<label><strong>Add product by id:</strong></label>									
								<form action="<?php echo $baseurl; ?>" method="GET" title="type product ID">
									<?php echo cbpressfn::input('lid','text','','lidSuggest')?>								
									<?php echo cbpressfn::input('action','hidden','cbp-save-list-item')?>								
									<?php echo cbpressfn::input('list_id','hidden',$row->list_id)?>
									<?php echo cbpressfn::input('','submit','Add Product','additem button-secondary')?> 									
								</form>									
							</td>
							</tr>
						</table>

					</div>
					<?php if($items) {?>
						<span style="padding-left: 30px;">&nbsp;</span>
						<br>
						<table class="widefat" border="1" style="width: 80%; margin-left: 20px;">
							<?php
							foreach($items as $key=>$data){
								$t =  cbpressfn::truncate($data->title, 30, " ");
								$deletelink = admin_url('admin.php?action=cbp-list-item-delete&list_id='.$id.'&lid='.$data->lid);
								$deletelink = cbpressfn::a('[x]',$deletelink,'',true);
								echo '<tr>';
								echo  "<td>$deletelink</td>";
								echo  '<td><a href="' . CBP::get_admin_url('products') . '&lid=' . $data->lid . '">' . $t . '</a></td>';
								echo  "<td>{$data->vin}</td>";
								echo '</tr>';
							}
							?>
						</table>
					<?php } ?>
				</td>
			</tr>
			<?php endforeach; ?>

		<?php } ?>

		</tbody>

		</table>

		<br/>

		<?php if(! $lists) {?>
			You have not created any custom product lists.<br/><br/>
			
			&laquo; ... use the form to the left to create one.
			
		<?php } ?>





		</td>
	</tr>
</table>