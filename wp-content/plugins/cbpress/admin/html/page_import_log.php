<?php

	$import_id = CBP::getv('id',0);
	if($import_id > 0){
		$action = @$_REQUEST['action'];
		switch ($action) {
			case "delete":
				CBP_importlog_db::delete($import_id);
				break;
			case "view":


				$r = new CBP_importlog_db();
				$q = $r->fetch($import_id);
				// $q = $r->_format();

				dump($q);

				break;
		}
	}
	$result = CBP_importlog_db::fetchAll();
	$url_view = CBP::make_action_url('view');
	$url_delete = CBP::make_action_url('delete');
?>
<div class="cb-table cb-table-style-1">
	<div id="messages" class="box" style="display:none"></div>
	<table width="100%">
		<thead>
		<tr class="head">
			<th colspan="3" align="left" class="group">IMPORT LOG</th>
			<th colspan="3" class="group">Products</th>
			<th colspan="1" class="group">Cats</th>
			<th colspan="1" class="group"><?php CBP::img('action_delete.gif'); ?></th>
		</tr>
		<tr>
			<th>Status</th>
			<th>Started</th>
			<th>Time</th>
			<th><em>Total</em></th>
			<th><em>Unique</em></th>
			<th><em>New</em></th><th><em>Total</em></th>
			<th width="1%"></th>
		</tr>
		</thead>
	<tbody>
	<?php foreach($result as $num => $row ) :?>
		<?php 
			$id = $row->import_id;
			$uu = $url_view.'&id='.$id;
			$dd = $url_delete.'&id='.$id;
		?>
		<tr>
		<td class="bul" nowrap><?php echo $row->status; ?></td>
		<td class="bul" nowrap><?php echo $row->ts_start;?></td>
		<td class="bul" align="right" nowrap><?php echo $row->tt; ?></td>
		<?php if(isset($row->stats->read)){ ?>
			<td class="bul" align="right" nowrap><?php echo number_format($row->stats->read); ?></td>
			<td class="bul" align="right" nowrap><?php echo number_format($row->stats->products); ?></td>
			<td class="bul" align="right" nowrap><?php echo number_format($row->stats->new); ?></td>
			<td class="bul" align="right" nowrap><?php echo number_format($row->stats->cats); ?></td>
		<?php }else{ ?>

			<td class="bul" align="right" nowrap>-</td>
			<td class="bul" align="right" nowrap>-</td>
			<td class="bul" align="right" nowrap>-</td>
			<td class="bul" align="right" nowrap>-</td>
		<?php } ?>
		<td nowrap><?php echo $row->actions; ?></td>

		</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
</div>
