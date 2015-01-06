<?php
	// $action = cbpressfn::getparam('action');
	$cb_feedurl = $this->options->import_zip;
	$mall_page_id = CBP_shortcodes::getMallPageID();
 	// $check = CBP::getServerAbilities();
	$url = wp_nonce_url(admin_url( CBP_BASE_URL ), CBP_HOOK_NONCE);
	$cburl = "http://www.clickbank.com/affiliateAccountSignup.htm?key=cbpress";

?>


<table border="0" cellpadding="0" cellspacing="0" width="640">


  <tr>

				
    <td valign="top">
    		<h3 style="margin-top: 0px; text-align: left;"><strong>Welcome to the cbpress control panel</strong></h3>

  		<?php   		
  			$show_buttons = 1;  
			if($this->que->messages){				
				// echo '<h4 style="text-align:center;">Cbpress needs your attention</h4>';
				echo $this->print_messages();
				echo '<br>';
				echo '<br>';
			}
			if($show_buttons){
     				echo CBP_content::_front_buttons();
				echo '<div class="clear"></div>';
			}
		
		?>
		
		<br/><br/>

	<div class="resbox">
			<div>
  			<span><a href="http://go.cbpress.com/seopressor" target="_blank"><?php echo CBP::img('seopressor.png'); ?></a></span>
			<a href="http://go.cbpress.com/seopressor" target="_blank">Optimize your WordPress content for top search engine results</a> 
			</div>


			<div>
			<span><?php echo CBP::link('http://go.cbpress.com/socialmpro', CBP::img('socialmetrics.png'), '', '_blank'); ?></span>
			<a href="http://go.cbpress.com/socialmpro" target="_blank">Every WordPress Needs Social Metrics Pro. It tracks social signals the way Google sees it.</a> 
			</div>
				
			<div>
			<span><?php echo CBP::link('http://go.cbpress.com/cbengine', CBP::img('cbengine_logo2.png'), '', '_blank'); ?></span>
			<a href="http://go.cbpress.com/cbengine" target="_blank">Find ClickBank products that sell using CBengine software</a> 
			</div>
			
			<div>
  			<span><a href="<?php echo $cburl; ?>" target="_blank"><?php echo CBP::img('clickbank_sm.png'); ?></a></span>
			<a href="<?php echo $cburl; ?>" target="_blank">Need a ClickBank affiliate account? You can register free here</a> 
			</div>

	</div>


		
    </td>


    <td valign="top" width="250" style="padding: 0px 0px 0px 30px;">
	
		<div xclass="cb-table cb-table-style-1">
			<?php
				CBP::postbar_start('Product Database Summary');
					echo '<br>';					
					echo CBP_content::_show_summary();		
					echo '<p class="description">This box shows running totals about your cbpress installation</p>';

				CBP::postbar_end();			
			?>
		</div>

	  
     		<?php 	
		$feed = CBP::getv('feed','updates');



		CBP::postbar_start('Cbpress.com Updates');
					   			
		echo '<br>';
		CBP_feed::get_feed($feed,0);	   			
		echo '<br>';	
		CBP::postbar_end();			
		// $cbpress->frontfeed('Plugin Updates and Information','updates',1);
		echo '<br>';
		// $cbpress->frontfeed('Recommended Tools','products',1);




		// do_action('cbp-tab-register'); 
		?>


    </td>
  </tr>

</table>

<?php
	do_action('cbp_tooltips');
?>


