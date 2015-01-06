<?php

if (!defined('ABSPATH')) die();



class CBP_feed {





	public static $options = array();

	private static $errmsg = 'Error Retrieving Feed';

	private static $burner = 1;





	private static $feeds = array(

		  'main' => array(
			   'link'  => 'Blog'
			 , 'feed'  => 'main'
			 , 'title' => 'CBPress News'
			 , 'cache' => 3200
			 , 'items' => 5
			 , 'desc' => 1
			)

		, 'faqs' => array(
			   'link'  => 'Faq'
			 , 'feed'  => 'faqs'
			 , 'title' => "FAQ's"
			 , 'cache' => 3200
			 , 'items' => 5
			 , 'desc' => 1
			)
		, 'products' => array(
			   'link'  => 'Tools'
			 , 'feed'  => 'products'
			 , 'title' => 'Recommended Tools'
			 , 'cache' => 3200
			 , 'items' => 5
			 , 'desc' => 1
			)
		, 'updates' => array(
			   'link'  => 'Updates'
			 , 'feed'  => 'updates'
			 , 'title' => 'Plugin Updates'
			 , 'cache' => 2700
			 , 'items' => 5
			 , 'desc' => 0
			)
		, 'examples' => array(
			   'link'  => 'Examples'
			 , 'feed'  => 'examples'
			 , 'title' => 'Example Sites'
			 , 'cache' => 3200
			 , 'items' => 5
			 , 'desc' => 1
			)
		, 'cbengine' => array(
			   'link'  => 'http://blog.cbengine.com/feed/'
			 , 'feed'  => 'cbengine'
			 , 'title' => 'CBEngine Blog'
			 , 'cache' => 3200
			 , 'items' => 5
			 , 'desc' => 1
			)
		);









	function __construct() {





		if(! is_admin() ) return false;



		self::$options  = CBP_settings::getter();

		self::$feeds = cbpressfn::arrayToObject(self::$feeds);



		add_action( 'wp_dashboard_setup', array(__CLASS__,'wp_dashboard_setup'));





	}





	function get_meta($f) {



		$out = (isset(self::$feeds->$f)) ? self::$feeds->$f : self::$feeds->updates;



		$out->loco = self::get_loco($out->feed);



		return $out;

	}



	function get_loco($f) {



		if($f == 'cbengine'){
			$out = 'http://blog.cbengine.com/feed/';

		}else if(self::$burner){

			$f = ($f == 'main') ? '' : '-' . $f;

			$out = 'http://feeds.feedburner.com/cbpress' . $f;

		}else if($f == 'main') {

			$out = 'http://cbpress.com/feed/';

		}else{

			$out = 'http://cbpress.com/topics/' . $f . '/feed/';

		}

		return $out;

	}

	function get_fetch(&$meta) {

		$out = fetch_feed($meta->loco);

     	$out->set_cache_duration(apply_filters('wp_feed_cache_transient_lifetime',$meta->cache, $meta->loco));

		return $out;

	}

	

	function get_head(&$meta) {



		$out = '<h3 class="subscribe">';

		$out .= '<a href="'.$meta->loco.'" target="_blank" title="Subscribe with RSS" class=""><img src="'.CBP_IMG_URL.'rss.png" alt=""/></a>';

		$out .= '<strong>' . $meta->title . '</strong>';

		$out .= '</h3>';

		return $out;



	}









	function get_feeds() { 



		return self::$feeds;

	}



	function get_feed_with_text($feed, $desc=1) { 



		$meta   = self::get_meta($feed);

		$header = self::get_head($meta);

		$feeder = self::get_fetch($meta);

		$desc = $meta->desc;

    		$maxitems = 0;

    		
		echo '<div class="feeditems">';

    		echo $header;

    		$i = 1;

    		if(!is_wp_error($feeder)) {

			$maxitems = $feeder->get_item_quantity($meta->items);

			$feed_items = $feeder->get_items(0, $maxitems); 

			echo '<ul>';

			foreach($feed_items as $item) {

				// abort($item);
				if ($i % 2 != 0){

					echo '<li class="z2">';
				} else {
					echo '<li>';
				}

				if($desc == 1){
					// echo $item->get_description();
					// echo make_clickable($item->get_description());

					echo '<strong><a href="' . $item->get_permalink() . '">'. $item->get_title() . '</a></strong><br/>';
					echo esc_html( CBP::text_limit( strip_tags( $item->get_description() ), 45 ) );

				}else{
					echo '<a href="' . $item->get_permalink() . '">'. $item->get_title() . '</a>';
				}

				echo '</li>';
				$i++;

				if($feed_items[count($feed_items) - 1] != $item){

					//  echo '<li><div class="plugin_header_content_left_division"></div></li>'; 

				}

			}

			echo '</ul>';



		} else {

			echo '<ul><li>'.self::$errmsg.'</li></ul>';

		}


		echo '</div>';

				

	}



	function get_feedxxxx($feed) 

	{

	add_meta_box( $widget_id, $widget_name, $callback, $screen->id, $location, $priority );

	

	

	}

	

	function get_feed($feed,$showdesc=1) 

	{

		$meta   = self::get_meta($feed);



		$feeduri = $meta->loco;



		$rss_items = CBP::fetch_rss_items($meta->items, $feeduri);

		

		// CBP::postbox_start($meta->title, 'line-height:16px;'); 



					

		echo '<div class="rss-widget">';

		// echo '<ul>';



		if ( !$rss_items ) {

			echo '<li class="cbpress">'.self::$errmsg.'</li>';

		} else {

			foreach ( $rss_items as $item ) {

				echo '<li class="cbpress">';

				// echo '<h3 style="margin-bottom: 5px;"><a class="rsswidget" href="'.esc_url( $item->get_permalink(), $protocolls=null, 'display' ).'">'. esc_html( $item->get_title() ) .'</a></h3>';

				echo '<strong><a class="rsswidget" href="'.esc_url( $item->get_permalink(), $protocolls=null, 'display' ).'" target="_blank">'. esc_html( $item->get_title() ) .'</a></strong>';



				if($showdesc){

					echo ' <span class="rss-date">'. $item->get_date('F j, Y') .'</span>';

				

					echo make_clickable($item->get_description());

					echo '<div class="rssSummary">'. esc_html( CBP::text_limit( strip_tags( $item->get_description() ), 150 ) ).'</div>';

				}else{

					

					// echo ' <span class="rss-date">'. $item->get_date('M j, Y') .'</span>';

				}

				

				echo '</li>';

			}

		}

		// echo '</ul>';

		

			

		if($showdesc){

			echo '<br class="clear"/><div style="margin-top:10px;border-top: 1px solid #ddd; padding-top: 10px; text-align:center;">';

			echo '<a href="'.$feeduri.'"><img src="'.CBP_IMG_URL.'rss.png" alt=""/> Subscribe with RSS</a>';

			echo ' &nbsp; &nbsp; &nbsp; ';

			echo '<a href="http://cbpress.com"><img src="'.CBP_IMG_URL.'cbp_ico.png" alt=""/>cbpress.com</a>';

		}



		

		echo '</div>';

		echo '</div>';

		

		// CBP::postbox_end();

	}



	function ______frontfeed($title="",$id='products',$show_summary=1) {

		// fetch_rss_items

		

		$feeduri = CBP_data::get('feed')->$id;		

		echo '<h3 class="subscribe">';

			echo '<a href="'.$feeduri.'" target="_blank" title="Subscribe with RSS" class=""><img src="'.CBP_IMG_URL.'rss.png" alt=""/></a>';

			echo '<strong>' . $title . '</strong>';

		echo '</h3>';

		$args = array('url' => $feeduri,'items' => '3','show_date' => 0,'show_summary' => $show_summary);

		echo '<div style="padding-left:25px;">';

			wp_widget_rss_output($args);

		echo '</div>';		

	}



	function ______wp_dashboard_feed() {

		$feeduri = CBP_data::get('feed')->main;



		$args = array('url' => $feeduri,'items' => '3','show_date' => 1,'show_summary' => 1,);



		echo '<div class="rss-widget">';

		echo '<a href="http://www.cbpress.com" target="_blank">' . CBP::img('cbpress_logo_sm.png') . '</a>';

		wp_widget_rss_output($args);

		echo '<p style="border-top: 1px solid #CCC; padding-top: 10px; font-weight: bold;">';

		echo '<a href="' . $feeduri . '"><img src="' . CBP_IMG_URL . 'rss.png" alt=""/> Subscribe with RSS</a>';

		echo "</p>";

		echo "</div>";

	}






	function wp_dashboard_setup() {

		wp_add_dashboard_widget( 'cbpress_db_widget' , 'The Latest From Cbpress' , array(__CLASS__, 'wp_dashboard') );

	}





	function wp_dashboard() 

	{

		$meta   = self::get_meta('main');



		$feeduri = $meta->loco;



		$rss_items = CBP::fetch_rss_items( 3, $feeduri);

		echo '<div class="rss-widget">';

		echo '<a href="http://cbpress.com/" title="Go to cbpress.com"><img src="'.CBP_IMG_URL.'cbpress_logo_sm.png" class="alignright" alt="Cbpress"/></a>';			

		echo '<ul>';



		if ( !$rss_items ) {

			echo '<li class="cbpress">'.self::$errmsg.'</li>';

		} else {

			foreach ( $rss_items as $item ) {

				echo '<li class="cbpress">';

				echo '<a class="rsswidget" href="'.esc_url( $item->get_permalink(), $protocolls=null, 'display' ).'">'. esc_html( $item->get_title() ) .'</a>';

				echo ' <span class="rss-date">'. $item->get_date('F j, Y') .'</span>';

				echo '<div class="rssSummary">'. esc_html( CBP::text_limit( strip_tags( $item->get_description() ), 150 ) ).'</div>';

				echo '</li>';

			}

		}

		echo '</ul>';

		echo '<br class="clear"/><div style="margin-top:10px;border-top: 1px solid #ddd; padding-top: 10px; text-align:center;">';

		echo '<a href="'.$feeduri.'"><img src="'.CBP_IMG_URL.'rss.png" alt=""/> Subscribe with RSS</a>';

		echo ' &nbsp; &nbsp; &nbsp; ';

		echo '<a href="http://cbpress.com"><img src="'.CBP_IMG_URL.'cbp_ico.png" alt=""/>cbpress.com</a>';

		echo '</div>';

		echo '</div>';

	}



}