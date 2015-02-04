<?php
	$options['widget'] = isset( $options['widget'] ) ? $options['widget'] : 'enabled';
	$options['embed'] = isset( $options['embed'] ) ? $options['embed'] : 'enabled';
?>
<div id="japkin_settings">
	<img class="logo" src="<?php echo $logo; ?>" alt="Japkin Logo" />
	<div class="top">Plugin Information</div>
	<div class="middle">
		<input id="user_id" type="hidden" value="<?php echo $options['id']; ?>" />
		
		<div class="label">Email Address</div>
		<div class="input settings">
			<input id="user_email" type="text" value="<?php echo $options['email']; ?>" readonly="readonly" />
		</div>
		<div class="label">API Key</div>
		<div class="input settings">
			<input id="user_key" type="text" value="<?php echo $options['key']; ?>" readonly="readonly" />
		</div>
		<div class="options">
			<div class="header">Options:</div>
			<div class="option for_widget <?php echo $options['widget']; ?>"><div class="check"></div>Widget is <span><?php echo $options['widget']; ?></span>.</div>
			<div class="option for_embed <?php echo $options['embed']; ?>"><div class="check"></div>Embed code support is <span><?php echo $options['embed']; ?></span>.</div>
		</div>
		<div class="message"></div>
		<div class="actions">
			<div class="button small change-user">
				<div class="left"></div>
				<div class="mid">Sign In as a Different User</div>
				<div class="right"></div>
				<div class="clear"></div>
			</div>
			<div class="links">
				<a href="http://www.japkin.com/websites/japkin-user-inbox" target="_blank">My Inbox</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.japkin.com/websites/my-widget" target="_blank">Widget Settings</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.japkin.com/websites/support" target="_blank">Contact Support</a>
			</div>
		</div>
	</div>
	<div class="bottom"></div>
</div> 