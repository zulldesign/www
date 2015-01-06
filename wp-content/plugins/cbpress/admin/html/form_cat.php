<?php	
	$formend = '';
	$formstart = '';
	$form = (isset($form)) ? $form : 1;
	if($form){	
			$act = admin_url( 'admin.php' ) . '?pid='.$cat->pid;
			$formstart = '<form method="post" accept-charset="utf-8" action="'.$act.'" class="cbpress-form" style="margin: 0px;">';			
			$formend = '<input type="hidden" name="action" value="cbp-save-cat" />';
			$formend .= '<input class="button-primary" type="submit" name="savecat" value="Save"/>';
			$formend .= '</form>';
	}
	?>
<?php echo $formstart; ?>
<div id="cat_form">

	<?php CBP::wp_nonce_field(CBP_HOOK_NONCE) ?>

	<input type="hidden" name="catform[pid]" value="<?php echo intval($cat->pid); ?>"/>
	<input type="hidden" name="catform[enabled]" value="<?php echo intval($cat->enabled); ?>"/>
	<input type="hidden" name="catform[type]" value="<?php echo $cat->type; ?>"/>
	<input type="hidden" name="catform[cid]" value="<?php echo intval($cat->cid); ?>"/>

	<?php
		// if(strlen($cat->msg) > 1){
		// 	echo '<div style="padding: 10px 0px; color:#880000;">' . $cat->msg . '</div>';
		// }
	?>
	<div id="error_message_<?php echo intval($cat->cid); ?>"></div>
	<p><input style="width: 100%;" type="text" name="catform[name]" value="<?php echo esc_attr( $cat->name ); ?>"/></p>
</div>
<?php echo $formend; ?>
