<?php
	if(1 == 1){
?>

<!-- tabs -->
<ul class="tabs">
	<li><a href="#1">Settings</a></li>
	<li><a href="#2">Stylesheet</a></li>
	<li><a href="#3">Other</a></li>
</ul>




<div id="menu-settings-column" class="metabox-holder">
	<div id="side-sortables" class="meta-box-sortables">


		<div id="nav-menu-theme-locations" class="postbox " >


			<div class="handlediv" title="Click to toggle"><br /></div>
			<h3 class='hndle'><span>Theme Locations</span></h3>


			<div class="inside">

				<p class="howto">Your theme supports 1 menu. Select which menu you would like to use.</p>		
				<p>
					<label class="howto" for="locations-primary">
					<span>Primary Navigation</span>
					<select name="menu-locations[primary]" id="locations-primary">
					<option value="0"></option>
					<option	value="3">sidenav</option>
					</select>
					</label>
				</p>
				<p class="button-controls">
					<img class="waiting" src="http://cocos/legitbiz/wp-admin/images/wpspin_light.gif" alt="" />
					<input type="submit" name="nav-menu-locations" id="nav-menu-locations" class="button-primary" value="Save"  />

				</p>
			</div>	
		</div>
	</div>
</div>



		<?php
			// do_action('cbp-edit-css');
		
		
				$this->options->form();
				$x = 1;
		?>





<!-- panes -->
<div class="panes">
	<div class="pane" style="display:block">






	
	</div>
	<div class="pane">
		<p>
		</p>
	</div>	
	<div class="pane">		
		<p>
			
		</p>
	</div>
</div>
<script>
	jQuery(".tabs").tabs(".pane");
</script>

<?php
	}
?>




