<?php
if( !defined( 'ABSPATH' ) ) die( 'No direct access allowed' );

	$feeduri = $this->options->import_zip;
	// $manual = CBP_ADMIN_URL . 'import.php';


	$normal = admin_url( 'admin.php?page=cbpress-import' );
	$manual = admin_url( 'admin.php?page=cbpress-import&importmod=2' );

	$importmod = (@$_REQUEST['importmod'] == '') ? 1 : 2;


?>



            <?php screen_icon( 'tools' ); ?>
            <h2>ClickBank Marketplace Importer</h2>


<div style="width: 600px;" id="importpage">
		<div style="margin: 0px 0px 15px 0px;"></div>


		<div style="font-size: 13px; font-family: verdana;">
			<p>
				This page downloads and extracts the latest ClickBank 
				Marketplace product feed into your cbpress installation from 


		<?php
			// echo CBP::img('zip_48.png'); 
		?>
				<a href="<?php echo $feeduri; ?>" target="_blank">ClickBank.com</a>. 
				Please note that running the importer many times 
				over a short period may cause ClickBank to temporarily ban your 
				IP address for 24 hours.

			</p>
			<p>
				All imported data is stored separate from Wordpress blog posts to ensure data integrity.
				Any prior custom relationships between categories and products you've established 
				will remain intact. For products to display on your site, make sure you've added 
				the [cbpress] shortcode to any WordPress page. 
			</p>

		</div>




	<?php 

		CBP_importman::init();

		// CBP_importman::getSourceFiles();



		function import_regular() {
			?>
				<div style="font-size: 13px;">
					<div style="margin: 20px 0px 20px 0px;">
						<div id="import-done">
							<div id="import-busy-check" style="display: none;"></div>
							<div id="import-button" style="display: none;">
							<a href="javascript:void(0)" class="cbpress-importlog button-primary">Begin ClickBank Feed Import &raquo;</a>
							</div>
						</div>
					</div>
					<div id="import-loading" style="display: none"><img src="<?php echo CBP_IMG_URL; ?>loading.gif" alt="loading" width="32" height="32"/></div>
					<div id="import-results"></div>
					<div id="import-error" style="display: none"></div><span id="import-percent"></span>
					<div id="import-busy" style="display: none"><?php echo  CBP::img('import-busy2.gif'); ?></div>


					<!-- progress bar -->
					<div id="progressbarText1"></div>
					<div id="progressbarWrapper" style="display: none; height:15px; width: 300px;" class="ui-widget-default">
						<div id="progressbar" style="height:100%;"></div>
					</div>

					<div id="progressbarText2"></div>
					<div id="progbar" style="display: none; width: 300px;">
					<div id='cbpress_progress_bar_wrapper'>
					<div id='cbpress_progress_bar'>
					<div class='cbp-progress-wrap'>
					<div class='cbp-progress-value' id='cbp-progress-value' style='width:1%;'>
					<div class='cbp-progress-text' id='cbp-progress-text' >importing...</div>
					</div>
					</div>
					</div>
					</div>
					</div>
				</div>
			<?php 
		}



		function import_manual() {

			global $importmod, $cbpress;

			$action = cbpressfn::getparam('action');
			$feeduri = $cbpress->options->import_zip;




			if($action == 'start'){

				CBP_importman::start();

			}else if($action == 'process'){

				CBP_importman::process();


			}else if($action == 'php'){

				phpinfo();

			}else if($action == 'cbpimport1'){

				CBP_importman::import_run();

			}else{

				CBP_importman::upload_form();

				// CBP::postbar_start('Manual mode two-step importer');


				echo '<div style="font-size: 13px;">';

					if(CBP_importman::zip_file_exists()){


						echo '<div style="padding: 0px; margin: 0px 0px 5px 0px;"><a href="' . CBP::make_action_url('start') . '&importmod=2" class="button-secondary">Click to unzip xml feed</a></div>';
						echo '<div style="padding: 10px 20px;">';
						echo 'This will extract the zip file containing the XML feed';
						echo '</div>';


					} else {


						echo '<div style="padding: 0px; margin: 0px 0px 5px 0px;"><a href="' . CBP::make_action_url('start') . '&importmod=2" class="button-secondary">Click to attempt download</a></div>';
						echo '<div style="padding: 10px 20px;">';
						echo 'This will try to download and extract a zip file from <a href="' . $feeduri . '" target="_blank">ClickBank.com</a>. ';


						echo 'You can also manually download and upload the zip file to: ';
						echo '<span style="padding-left: 0px; font-weight: 500; color: red;">wp-content &gt; uploads &gt; cbpress &gt; feeds &gt; marketplace_feed_v2.xml.zip </span><br> ';
						echo '</div>';


					}





					// &bull;  





					if(CBP_importman::xml_file_exists()){




						echo '<h3 style="padding: 0px; margin: 0px 0px 5px 0px;"><a href="' . CBP::make_action_url('process') . '&importmod=2" class="button-secondary">Click to process the data feed</a></h3>';
						echo '<div style="padding: 10px 20px;">';
						echo 'Once the zip file is uploaded, you can click the the process button to update the cbpress categories and products from the xml feed.';
						echo '</div>';



					}









					echo '<br>';
					echo '<a href="' . CBP::make_action_url('php') . '&importmod=2" style="font-size: 14px;">View your PHP configuration</a>';
					echo '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
					echo '<a href="' . $regular . '" style="font-size: 14px;">Switch to regular mode</a>';

					// echo CBP::quicktag('p',array('class'=>''), CBP::link($regular,'Switch to regular mode'));


				echo '</div>';

				// CBP::postbar_end();
			}




		}

		if($importmod == 1){
			// echo CBP::quicktag('p',array('class'=>''), CBP::link($manual,'Try manual mode'));
			import_regular();


			echo 'If you are having problems running the importer, <a href="' . $manual . '">click here for more options</a>.';


			echo '<div style="padding-top: 25px;">';
			include(dirname(__FILE__).'/page_import_log.php');
			echo '</div>';
		}else{
			import_manual();

		}


		// echo CBP::img('clickbank-logo-blue.png'); 
		// $importer = new CBP_import();
		// echo 'Using WP File System: ' . $importer->use_wp_system();

	?>






</div>
<div id="admin_import_info"></div>

