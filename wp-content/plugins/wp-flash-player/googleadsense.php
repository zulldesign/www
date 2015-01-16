<?php
require_once (dirname ( __FILE__ ) . '/hdflv-config.php');
global $wpdb;

$details1 = $wpdb->get_row ( "SELECT * FROM " . $wpdb->prefix . "hdflv_googlead WHERE id =1" );
echo html_entity_decode ( stripcslashes ( $details1->code ) );
?>
<script type="text/javascript"
	src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
<?php
exit ();
