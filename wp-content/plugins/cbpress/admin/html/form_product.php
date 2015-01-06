<?php if( !defined( 'ABSPATH' ) ) die( 'No direct access allowed' );


$new_item = $this->lid == 0;

$act = admin_url( 'admin-ajax.php' );
do_action('admin_print_styles');
$catdata = $this->cats;
$cids = $this->cids;

?>

<div class="tablenav">
	<div class="alignleft actions">
		<h3>
		<?php
			$p_id =  ' (' . $this->lid . ')';
			if($this->is_clickbank()){
				echo ($new_item)? 'Add ClickBank Product' : 'Edit ClickBank Product' . $p_id . ' (vendor: ' . esc_attr( $this->vin ) . ' )';
			}else{
				echo ($new_item)? 'Add Custom Product' : 'Edit Custom Product' . $p_id;
			}
		?>
		</h3>        
	</div>
	<div class="tablenav-pages">
		<?php
		if(!$new_item){
			$link = $_SERVER['REQUEST_URI'];
			$link = add_query_arg('tab','info',remove_query_arg( array('tab','action','msg'), $link ));
			cbpressfn::a('back to product info',$link);
		}
		?>

	</div>
</div>

<div class="clear"></div><hr>

<?php
	// $msg = @$_REQUEST['msg'];
	// if(strlen($msg) > 1){
		// echo '<div style="padding: 10px 0px; color:#880000;">' . $msg . '</div>';
	// }
		
	$cids_list = implode(',',$this->cids); 
	$cids_count = count($this->cids);			
						
?>


<form action="<?php echo admin_url( 'admin.php' ); ?>" method="POST" id="product_form" class="cbpress-form8">

<?php CBP::wp_nonce_field(CBP_HOOK_NONCE) ?>
<input type="hidden" name="action" value="cbp-save-prod" />

<input type="hidden" name="prodform[tid]" value="<?php echo intval($this->tid); ?>"/>
<input type="hidden" name="prodform[lid]" value="<?php echo intval($this->lid); ?>"/>
<input type="hidden" name="prodform[active]" value="0"/>
<input type="hidden" name="prodform[disable_search]" value="0"/>
<input type="hidden" name="prodform[auto_update]" value="0"/>
<input type="hidden" name="prodform[vin]" value="<?php echo esc_attr( $this->vin ); ?>"/>
<input type="hidden" name="prodform[cids_list]" id="product_cid_list" value="<?php echo $cids_list; ?>"/>
<input type="hidden" name="prodform[source]" id="prod_source" value="<?php echo $this->source; ?>"/>
<input type="hidden" name="prodform[status]" value="<?php echo $this->status; ?>"/>
<input type="hidden" name="prodform[rating]" value="<?php echo $this->rating; ?>"/>

<table width="740">
  <tr>
    <td valign="top" width="75%" style="padding-right: 30px; xline-height:30px;">

	<!-- title -->

	<label class="label" for="prod_title"><?php if ($this->is_clickbank()): ?><span style="float: right; font-size:11px;">[ <a href="javascript:void" onclick="jQuery('#feed_title').toggle()">compare to feed</a> ]</span><?php endif ?><strong>Product Title:</strong></label><br/>
	<input style="width: 100%;" type="text" name="prodform[title]" id="prod_title" value="<?php echo esc_attr( $this->title ); ?>"/>
	<?php if ($this->is_clickbank()): ?><div id="feed_title" style="background:#eeeeee; padding:10px; font-size:11px; display: none;"><?php echo $this->feed_title ?></div><?php endif ?>

	<div class="sep"><br/></div>

	<!-- description -->

	<label class="label" for="prod_description"><?php if ($this->is_clickbank()): ?><span class="float_r" style="float: right; font-size:11px;">[ <a href="javascript:void" onclick="jQuery('#feed_desc').toggle()">compare to feed</a> ]</span><?php endif ?><strong class="float_l" >Product Description:</strong></label><br/>
	<textarea class="Description" name="prodform[description]" id="prod_description" style="width: 100%; height: 120px;"><?php echo $this->description; ?></textarea>
	<?php if ($this->is_clickbank()): ?><div id="feed_desc" style="background:#eeeeee; padding:10px; font-size:11px; display: none;"><?php echo $this->feed_desc ?></div><?php endif ?>

	<div class="sep"><br/></div>

	<?php if ($this->is_clickbank()): ?>

		<?php if(1 == 2){ ?>
		<!-- tid -->
		<label class="label" for="prod_link_tid"><strong>Hoplink TID:</strong></label><br/>
		<input style="width: 200px" type="text" id="prod_link_tid" name="prodform[link_tid]" value="<?php echo esc_attr( $this->link_tid ); ?>"/>
		<small>(optional)</small>
		<p></p>
		<?php } ?>



	<?php endif ?>






	<?php if(! $this->is_clickbank()){ ?>
		<label class="label" title="Product link URL (http://)"><strong>Link To: </strong><span>(http://)</span></label>
		<input style="width: 100%;" type="text" name="prodform[redirect_url]"  id="prod_redirect_url" value="<?php echo esc_attr( $this->redirect_url ); ?>"/>
		<span class="sep"></span>
		<?php } else { ?>
	<?php } ?>

	<p>
	<input type="checkbox" id="product_active" name="prodform[active]" value="1" <?php if ( $this->active == 1 ) echo ' checked="checked"' ?>/>
	<label for="product_active" ><?php _e ('Display on website', CBPRESS_TRANS ); ?></label><br/>
	<?php if($this->is_clickbank()): ?>
	<input type="checkbox" id="product_auto_update" name="prodform[auto_update]" value="1" <?php if ( $this->auto_update == 1 ) echo ' checked="checked"' ?>/>
	<label for="product_auto_update"><?php _e ('Always update title and description from XML feed', CBPRESS_TRANS ); ?></label>
	<?php endif ?>
	</p>









		<br>
		<table>
		<tr valign="top">
		<th scope="row" style="text-align:left;">Upload Image / Thumbnail</th>
		</tr>
		<tr valign="top" style="background:#eeeeee;">
		<td>	
		<label for="upload_image">	
		<input id="upload_image" type="text" size="36" name="prodform[thumbnail]" value="<?php echo esc_attr( $this->thumbnail ); ?>" />
		<input id="upload_image_button" type="button" value="Select Image" />
			<br />Enter an URL or upload an image for the thumbnail from your media library. 
			Best when choosing "thumbnail" as the size when selecting an image from your media library.
		</label>
	
		</td>
		</tr>
		</table>

<?php if($this->thumbnail != '') { ?>

<img src="<?php echo esc_attr( $this->thumbnail ); ?>" border="0" vspace="5">


<?php } ?>







	<hr>
	<div id="prod_error"></div>
	<p><input class="button-primary" type="submit" name="save" value="<?php _e( 'Save Product', CBPRESS_TRANS ); ?>"/></p>


    </td>
    <td valign="top">

	<p>Choose Marketplace categories below</p>
	<div>Total Selected: <span id="product_cats_count"><?php echo $cids_count; ?></span></div>
	<div id="product_cats" name="cids"></div>
	<div id="product_lists" name="list_ids"></div>
	<div><span id="catnames"></span></div>

    </td>
  </tr>
</table>

<span id="info_<?php echo esc_attr( $this->lid ) ?>"></span>









</form>



