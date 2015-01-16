<?php
/*
 * Name: WP Flash Player Plugin URI: http://www.apptha.com/category/extension/Wordpress/HD-FLV-Player-Plugin/ Description: HD FLV Player manage file. Version: 1.3 Author: Apptha Author URI: http://www.apptha.com License: GPL2
 */
$contus = dirname ( plugin_basename ( __FILE__ ) );
$siteUrl = get_option ( 'siteurl' );
define ( 'SITEURL', $siteUrl );
define ( 'PLUGINNAME', $contus );
$content_name = str_replace ( site_url () . '/', '', content_url () );
$jsDirPaht = content_url () . '/plugins/' . $contus . '/js';
$pluginDir = content_url () . '/plugins/' . $contus;
@session_start ();
unset ( $_SESSION ['app_wp_token'] );
global $user_ID;
$_SESSION ['app_wp_token'] = md5 ( $user_ID );
?>
<style type="text/css">
#poststuff h3,.metabox-holder h3 {
	cursor: default;
}
</style>
<script type="text/javascript"
	src="<?php echo $jsDirPaht; ?>/jquery-1.3.2.min.js"></script>
<script type="text/javascript"
	src="<?php echo $jsDirPaht; ?>/jquery-ui-1.7.1.custom.min.js"></script>
<script type="text/javascript"
	src="<?php echo $jsDirPaht; ?>/hdflvscript.js"></script>
<script type="text/javascript">
	var content_name = '<?php echo $content_name; ?>';
    // When the document is ready set up our sortable with it's inherant function(s)
    $(document).ready(function() {
        $("#test-list").sortable({
            handle : '.handle',
            update : function () {
                var order = $('#test-list').sortable('serialize');
                var playid = document.getElementById('playlistid2').value;
                $("#info").load("<?php echo $pluginDir; ?>/process-sortable.php?"+order+"&playid="+playid+"&hdflv_token="+"<?php echo $_SESSION['app_wp_token']; ?>");
                showUser(playid,order);
            }
        });
    });
</script>
<script>

    var uploadqueue = [];
    var uploadmessage = '';
    var plugin_dir = '<?php echo $contus; ?>';
    function addQueue(whichForm,myfile)
    {
        var  extn = extension(myfile);
        if( whichForm == 'normalvideoform' || whichForm == 'hdvideoform' )
        {
            if(extn != 'flv' && extn != 'FLV' && extn != 'mp4' && extn != 'MP4' && extn != 'm4v' && extn != 'M4V' && extn != 'mp4v' && extn != 'Mp4v' && extn != 'm4a' && extn != 'M4A' && extn != 'mov' && extn != 'MOV' && extn != 'f4v' && extn != 'F4V' && extn != 'mp3' && extn != 'MP3')
            {
                document.getElementById('upload_video_message').style.display = 'block';
                document.getElementById('upload_video_message').innerHTML = extn+" is not a valid Video Extension";
                return false;
            }
        }
        else
        {
            if(extn != 'jpg' && extn != 'png' && extn != 'jpeg')
            {
                document.getElementById('upload_thumb_message').style.display = 'block';
                document.getElementById('upload_thumb_message').innerHTML = extn+" is not a valid Image Extension";
                return false;
            }
        }
        uploadqueue.push(whichForm);
        if (uploadqueue.length == 1)
        {

            processQueue();
        }
        else
        {
            holdQueue();
        }
    }
    function processQueue()
    {
        if (uploadqueue.length > 0)
        {
            form_handler = uploadqueue[0];
            setStatus(form_handler,'Uploading');
            submitUploadForm(form_handler);
        }
    }
    function holdQueue()
    {
        form_handler = uploadqueue[uploadqueue.length-1];
        setStatus(form_handler,'Queued');
    }
    function updateQueue(statuscode,statusmessage,outfile)
    {
        uploadmessage = statusmessage;
        form_handler = uploadqueue[0];
        if (statuscode == 0)
            document.getElementById(form_handler+"-value").value = outfile;
        setStatus(form_handler,statuscode);
        uploadqueue.shift();
        processQueue();
    }

    function submitUploadForm(form_handle)
    {
        var token = '<?php echo $_SESSION['app_wp_token']; ?>';
        document.forms[form_handle].target = "uploadvideo_target";
        document.forms[form_handle].action = "<?php echo content_url(); ?>/plugins/"+plugin_dir+"/uploadVideo.php?processing=1&hdflv_token="+token;
        document.forms[form_handle].submit();
    }
    
    function setStatus(form_handle,status)
    {
        switch(form_handle)
        {
            case "normalvideoform":
                divprefix = 'f1';
                break;
            case "hdvideoform":
                divprefix = 'f2';
                break;
            case "thumbimageform":
                divprefix = 'f3';
                break;
            case "previewimageform":
                divprefix = 'f4';
                break;
        }
        switch(status)
        {
            case "Queued":
                document.getElementById(divprefix + "-upload-form").style.display = "none";
                document.getElementById(divprefix + "-upload-progress").style.display = "";
                document.getElementById(divprefix + "-upload-status").innerHTML = "Queued";
                document.getElementById(divprefix + "-upload-message").style.display = "none";
                document.getElementById(divprefix + "-upload-filename").innerHTML = document.forms[form_handle].myfile.value;
                document.getElementById(divprefix + "-upload-image").src = '<?php echo content_url(); ?>/plugins/'+plugin_dir+'/images/empty.gif';
                document.getElementById(divprefix + "-upload-cancel").innerHTML = '<a style="float:right;padding-right:10px;" href=javascript:cancelUpload("'+form_handle+'") name="submitcancel">Cancel</a>';
                break;

            case "Uploading":
                document.getElementById(divprefix + "-upload-form").style.display = "none";
                document.getElementById(divprefix + "-upload-progress").style.display = "";
                document.getElementById(divprefix + "-upload-status").innerHTML = "Uploading";
                document.getElementById(divprefix + "-upload-message").style.display = "none";
                document.getElementById(divprefix + "-upload-filename").innerHTML = document.forms[form_handle].myfile.value;
                document.getElementById(divprefix + "-upload-image").src = '<?php echo content_url(); ?>/plugins/'+plugin_dir+'/images/loader.gif';
                document.getElementById(divprefix + "-upload-cancel").innerHTML = '<a style="float:right;padding-right:10px;" href=javascript:cancelUpload("'+form_handle+'") name="submitcancel">Cancel</a>';
                break;
            case "Retry":
            case "Cancelled":
                //uploadqueue = [];
                document.getElementById(divprefix + "-upload-form").style.display = "";
                document.getElementById(divprefix + "-upload-progress").style.display = "none";
                document.forms[form_handle].myfile.value = '';
                enableUpload(form_handle);
                break;
            case 0:
                document.getElementById(divprefix + "-upload-image").src = '<?php echo content_url(); ?>/plugins/'+plugin_dir+'/images/success.gif';
                document.getElementById(divprefix + "-upload-status").innerHTML = "";
                document.getElementById(divprefix + "-upload-message").style.display = "";
                document.getElementById("upload_video_message").style.display = "none";
				if(document.getElementById("upload_thumb_message")){
                document.getElementById("upload_thumb_message").style.display = "none";
				}
                document.getElementById(divprefix + "-upload-message").style.backgroundColor = "#CEEEB2";
                document.getElementById(divprefix + "-upload-message").innerHTML = uploadmessage;
                document.getElementById(divprefix + "-upload-cancel").innerHTML = '';
                break;


            default:
                document.getElementById(divprefix + "-upload-image").src = '<?php echo content_url(); ?>/plugins/'+plugin_dir+'/images/error.gif';
                document.getElementById(divprefix + "-upload-status").innerHTML = " ";
                document.getElementById(divprefix + "-upload-message").style.display = "";
                document.getElementById(divprefix + "-upload-message").innerHTML = uploadmessage + " <a href=javascript:setStatus('" + form_handle + "','Retry')>Retry</a>";
                document.getElementById(divprefix + "-upload-cancel").innerHTML = '';
                break;
        }
    }

    function enableUpload(whichForm,myfile)
    {
        if (document.forms[whichForm].myfile.value != '')
            document.forms[whichForm].uploadBtn.disabled = "";
        else
            document.forms[whichForm].uploadBtn.disabled = "disabled";
    }

    function cancelUpload(whichForm)
    {
        document.getElementById('uploadvideo_target').src = '';
        setStatus(whichForm,'Cancelled');
        pos = uploadqueue.lastIndexOf(whichForm);
        if (pos == 0)
        {
            if (uploadqueue.length >= 1)
            {
                uploadqueue.shift();
                processQueue();
            }
        }
        else
        {
            uploadqueue.splice(pos,1);
        }

    }
    function chkbut()
    {
        if(uploadqueue.length <= 0 )
        {
            if(document.getElementById('btn2').checked)
            {
                document.getElementById('youtube-value').value= document.getElementById('filepath1').value;
                document.getElementById('customurl1').value = document.getElementById('filepath2').value;
                document.getElementById('customhd1').value = document.getElementById('filepath3').value;
                return true;
            }
            if(document.getElementById('btn3').checked || document.getElementById('btn4').checked)
            {
                document.getElementById('customurl1').value = document.getElementById('filepath2').value;
                document.getElementById('customhd1').value = document.getElementById('filepath3').value;
                document.getElementById('customimage').value = document.getElementById('filepath4').value;
                document.getElementById('custompreimage').value = document.getElementById('filepath5').value;
                return true;
            }
        }else { alert("Wait for Uploading to Finish"); return false; }

    }
    function extension(fname)
    {
        var pos = fname.lastIndexOf(".");

        var strlen = fname.length;

        if(pos != -1 && strlen != pos+1)
        {
            var ext = fname.split(".");
            var len = ext.length;
            var extension = ext[len-1].toLowerCase();
        }
        else
        {

            extension = "No extension found";

        }

        return extension;

    }
</script>

<?php
class HDFLVManage {
	var $mode = 'main';
	var $wptfile_abspath;
	var $wp_urlpath;
	var $act_vid = false;
	var $act_pid = false;
	var $base_page = '?page=hdflv';
	var $PerPage = 10;
	function HDFLVManage() {
		global $hdflv;
		
		// get the options
		$this->options = get_option ( 'HDFLVSettings' );
		
		// Manage upload dir
		// add_filter('upload_dir', array(&$this, 'upload_dir'));
		
		$wp_upload = wp_upload_dir ();
		$this->wptfile_abspath = $wp_upload ['path'] . '/';
		$this->wp_urlpath = $wp_upload ['url'] . '';
		$this->editFile_abspath = $wp_upload ['path'] . '';
		// output Manage screen
		$this->controller (); // it is main function in this we calling all tabs
	}
	
	/**
	 * Renders an admin section of display code
	 * 
	 * @author John Godley (http://urbangiraffe.com)
	 *        
	 * @param string $ug_name
	 *        	Name of the admin file (without extension)
	 * @param string $array
	 *        	Array of variable name=>value that is available to the display code (optional)
	 * @return void
	 *
	 */
	function render_admin($ug_name, $ug_vars = array()) {
		// echo $ug_name."".$ug_vars;
		// exit();
		$function_name = array (
				$this,
				'show_' . $ug_name 
		); // show_playlist , show_[function]
		
		if (is_callable ( $function_name )) // return 1 or 0 $ug_vars is parameters to send function
			call_user_func_array ( $function_name, $ug_vars ); // Call a user function given with an array of parameters http://php.net/manual/en/function.call-user-func-array.php
		else
			echo "<p>Rendering of admin function show_$ug_name failed</p>";
	}
	
	// Return custom upload dir/url
	function upload_dir($uploads) {
		if ($this->options [0] [27] ['v'] == 0) {
			$dir = ABSPATH . trim ( $this->options [0] [28] ['v'] );
			$url = trailingslashit ( get_option ( 'siteurl' ) ) . trim ( $this->options [0] [28] ['v'] );
			
			// Make sure we have an uploads dir
			if (! wp_mkdir_p ( $dir )) {
				$message = sprintf ( __ ( 'Unable to create directory %s. Is its parent directory writable by the server?', 'hdflv' ), $dir );
				$uploads ['error'] = $message;
				return $uploads;
			}
			$uploads = array (
					'path' => $dir,
					'url' => $url,
					'error' => false 
			);
		}
		return $uploads;
	}
	function manage_render_message($message, $timeout = 0) {
		?>
<div style="margin-bottom: 0px; margin-top: -20px;" class="wrap">
	<div class="fade updated" id="message"
		onclick="this.parentNode.removeChild (this)">
		<p>
			<strong><?php echo $message ?></strong>
		</p>
	</div>
</div>
<?php
	}
	function haflvMenuTab() {
		$modeType = trim ( filter_input ( INPUT_GET, 'mode' ) );
		$page = trim ( filter_input ( INPUT_GET, 'page' ) ); // To Display menu tabs;
		?>
<h2 class="nav-tab-wrapper">
	<a id="hdflv" href="?page=hdflv"
		class="nav-tab <?php
		
if ($page == 'hdflv' && empty ( $modeType )) {
			echo 'nav-tab-active';
		}
		?>"> Manage Videos</a> <a id="video"
		href="?page=hdflv&mode=video"
		class="nav-tab <?php
		
if ($page == 'hdflv' && ! empty ( $modeType )) {
			echo 'nav-tab-active';
		}
		?>"> Add Video</a> <a id="playlist" href="?page=hdflvplaylist"
		class="nav-tab <?php
		
if ($page == 'hdflvplaylist') {
			echo 'nav-tab-active';
		}
		?>">Categories</a> <a id="hdflvvideoads"
		href="?page=hdflvvideoads&mode=managevideoads"
		class="nav-tab <?php
		
if ($page == 'hdflvvideoads' && (empty ( $modeType ) || $modeType == 'managevideoads')) {
			echo 'nav-tab-active';
		}
		?>">Video Ads</a> <a id="videoads"
		href="?page=hdflvvideoads&mode=videoads"
		class="nav-tab <?php
		
if ($page == 'hdflvvideoads' && ($modeType == 'videoads')) {
			echo 'nav-tab-active';
		}
		?>">Add Video Ads</a> <a id="adsense"
		href="?page=hdflvadsense&mode=hdflvadsense"
		class="nav-tab <?php
		
if ($page == 'hdflvadsense') {
			echo 'nav-tab-active';
		}
		?>">Google Adsense</a> <a id="settings"
		href="?page=hdflvplugin.php"
		class="nav-tab <?php
		
if ($page == 'hdflvplugin.php') {
			echo 'nav-tab-active';
		}
		?>">Settings</a>
</h2>

<?php
	}
	function controller() {
		global $wpdb;
		filter_input ( INPUT_GET, 'playid' );
		$modeType = trim ( filter_input ( INPUT_GET, 'mode' ) ); // it show the menu tab;
		$page = trim ( filter_input ( INPUT_GET, 'page' ) );
		if (isset ( $modeType )) {
			switch ($modeType) {
				case 'playlist' :
					$this->mode = 'playlist';
					break;
				case 'video' :
					$this->mode = 'add';
					break;
				case 'videoads' :
					$this->mode = 'videoads';
					break;
				case 'managevideoads' :
					$this->mode = 'managevideoads';
					break;
				case 'hdflvadsense' :
					$this->mode = 'hdflvadsense';
					break;
				case 'edit' :
					$this->mode = 'edit';
					break;
				case 'delete' :
					$this->mode = 'hdflv';
					
					$video_name_in = filter_input ( INPUT_GET, 'video_name' );
					$video_name = substr ( $video_name_in, 0, 35 ) . ' ...';
					$id = filter_input ( INPUT_GET, 'id' );
					hd_delete_media ( $id, 0, $video_name );
					$this->mode = 'main';
					
					break;
			}
		}
		if ($page == 'hdflvplaylist') {
			$this->mode = 'playlist';
		}
		if ($page == 'hdflvadsense') {
			$this->mode = 'hdflvadsense';
		}
		if ($page == 'hdflvvideoads') {
			$this->mode = 'managevideoads';
		}
		if ($page == 'hdflvvideoads' && $modeType == 'videoads') {
			$this->mode = 'videoads';
		}
		
		$id = filter_input ( INPUT_GET, 'id' );
		$pid = filter_input ( INPUT_GET, 'pid' );
		
		$this->act_vid = ( int ) $id;
		$this->act_pid = ( int ) $pid;
		
		// TODO:Include nonce !!!
		$add_media = filter_input ( INPUT_POST, 'add_media' );
		if (isset ( $add_media )) {
			hd_add_media ( $this->wptfile_abspath, $this->wp_urlpath );
			$this->mode = 'add';
		}
		
		$youtube_media = filter_input ( INPUT_POST, 'youtube_media' );
		if (isset ( $youtube_media )) {
			$act1 = youtubeurl_player ();
			$act0_str = str_replace ( '"', '', $act1 [0] );
			?>
<input type="hidden" name="act" id="act3" value="<?php echo $act1[3] ?>" />
<input type="hidden" name="act" id="act0"
	value="<?php echo stripslashes($act0_str); ?>" />
<input type="hidden" name="act" id="act4" value="<?php echo $act1[4] ?>" />
<?php
			$this->mode = 'add'; // hd_add_media($this->wptfile_abspath, $this->wp_urlpath);
		}
		$edit_update = filter_input ( INPUT_POST, 'edit_update' );
		
		$edit_cancel = filter_input ( INPUT_POST, 'edit_cancel' );
		global $videoname;
		if (isset ( $edit_update ) || isset ( $edit_cancel )) {
			$videoname = hd_update_media ( $this->act_vid );
			$this->mode = 'main';
			$paged = filter_input ( INPUT_GET, 'paged' );
			if ($paged) { // for pagination
				$siteUrl = '?page=hdflv&edit=succ&paged=' . $paged;
			} else {
				$siteUrl = '?page=hdflv&edit=succ';
			}
		}
		
		$edit = filter_input ( INPUT_GET, 'edit' );
		if (isset ( $edit )) {
			manage_render_message ( 'Video file "' . $videoname . '" Updated Successfully' );
		}
		/* updating thumb image */
		$thumbupdate = filter_input ( INPUT_POST, 'thumb-update' );
		if (isset ( $thumbupdate )) {
			hd_update_thumb ( $this->editFile_abspath, $this->wp_urlpath, $this->act_vid );
			
			$this->mode = 'main';
		}
		/* updating preview image */
		$previewupdate = filter_input ( INPUT_POST, 'preview-update' );
		if (isset ( $previewupdate )) {
			hd_update_preview ( $this->editFile_abspath, $this->wp_urlpath, $this->act_vid );
			$this->mode = 'main';
		}
		$cancel = filter_input ( INPUT_POST, 'cancel' );
		$cancel_play = filter_input ( INPUT_POST, 'cancelplay' );
		$cancel_ad = filter_input ( INPUT_POST, 'cancelad' );
		$search = filter_input ( INPUT_POST, 'search' );
		$search_playlist = filter_input ( INPUT_POST, 'search_playlist' );
		$search_ad = filter_input ( INPUT_POST, 'search_ad' );
		
		if (isset ( $cancel ) || isset ( $search ))
			$this->mode = 'main';
		if (isset ( $cancel_play ) || isset ( $search_playlist ))
			$this->mode = 'playlist';
		if (isset ( $cancel_ad ) || isset ( $search_ad ))
			$this->mode = 'managevideoads';
		$show_add = filter_input ( INPUT_POST, 'show_add' );
		if (isset ( $show_add ))
			$this->mode = 'add';
		$add_pl = filter_input ( INPUT_POST, 'add_pl' );
		if (isset ( $add_pl )) {
			hd_add_playlist ();
			$this->mode = 'edit';
		}
		$add_pl1 = filter_input ( INPUT_POST, 'add_pl1' );
		if (isset ( $add_pl1 )) {
			
			hd_add_playlist ();
			$this->mode = 'add';
		}
		$add_playlist = filter_input ( INPUT_POST, 'add_playlist' );
		if (isset ( $add_playlist )) {
			hd_add_playlist (); // in function.php file
			$this->mode = 'playlist';
		}
		
		$update_playlist = filter_input ( INPUT_POST, 'update_playlist' );
		if (isset ( $update_playlist )) {
			hd_update_playlist ();
			$this->mode = 'playlist';
		}
		$video_name = filter_input ( INPUT_GET, 'video_name' );
		if ($this->mode == 'delete') {
			$video_name = substr ( $video_name, 0, 35 ) . ' ...';
			hd_delete_media ( $this->act_vid, $this->options ['deletefile'], $video_name );
			$this->mode = 'main';
		}
		
		// Let's show the main screen if no one selected
		if (empty ( $this->mode ))
			$this->mode = 'main';
			
			// render the admin screen
		$this->render_admin ( $this->mode );
	}
	function phpSlashes($string, $type = 'add') {
		if ($type == 'add') {
			if (get_magic_quotes_gpc ()) {
				return $string;
			} else {
				if (function_exists ( 'addslashes' )) {
					return addslashes ( $string );
				} else {
					return mysql_real_escape_string ( $string );
				}
			}
		} else if ($type == 'strip') {
			return stripslashes ( $string );
		} else {
			die ( 'error in PHP_slashes (mixed,add | strip)' );
		}
	}
	function show_main() {
		global $wpdb;
		
		// init variables
		$pledit = true;
		$where = '';
		$join = '';
		
		$search_inputbox = filter_input ( INPUT_POST, 'search' );
		$search_query = '';
		$pl_id = '0';
		if (isset ( $_GET ['search'] )) {
			$search_query = $_GET ['search'];
		}
		$search_input = (isset ( $search_inputbox )) ? $search_inputbox : (isset ( $search_query ) ? $search_query : '');
		
		// check for page navigation
		$search = (isset ( $search_input )) ? $search_input : '';
		$search = $this->phpSlashes ( $search );
		$search_results = $search;
		if (isset ( $_GET ['playlistid'] )) {
			$pl_id = $_GET ['playlistid'];
		}
		$plfil = filter_input ( INPUT_POST, 'plfilter' );
		
		// $plfilter = ( isset($plfil)) ? $plfil : (isset($pl_id) ? $pl_id : '0' );
		if (isset ( $plfil )) {
			$plfilter = $plfil;
		} else if (isset ( $pl_id )) {
			$plfilter = $pl_id;
		} else {
			$plfilter = '0';
		}
		
		$playlist_id = $plfilter;
		// print_r($_POST);
		if ($search != '') {
			if ($where != '')
				$where .= " AND ";
			$where1 = " (name LIKE '%$search_results%')";
			$kt = preg_split ( "/[\s,]+/", $search ); // Breaking the string to array of words
			                                     // Now let us generate the sql
			while ( list ( $key, $search ) = each ( $kt ) ) {
				if ($search != " " and strlen ( $search ) > 0) {
					$where1 = " (name LIKE '%$search%')";
				}
			}
			$where .= $where1;
		}
		
		if ($plfilter != '0' && $plfilter != 'no') {
			$join = " LEFT JOIN " . $wpdb->prefix . "hdflv_med2play ON (vid = media_id) ";
			if ($where != '')
				$where .= " AND ";
			$where .= " (playlist_id = '" . $plfilter . "') ";
			$pledit = true;
		} elseif ($plfilter == 'no') {
			$join = "  WHERE `vid` NOT IN( SELECT media_id FROM " . $wpdb->prefix . "hdflv_med2play ) ";
			$pledit = false;
		}
		if ($search != '') {
			$join = " LEFT JOIN " . $wpdb->prefix . "hdflv_med2play ON (vid = media_id) ";
			$pledit = false;
		} else
			$pledit = false;
		
		if ($where != '')
			$where = " WHERE " . $where;
			// echo "SELECT COUNT(*) FROM " . $wpdb->prefix . "hdflv" . $join . $where;
		
		$total_pages = $wpdb->get_var ( "SELECT COUNT(*) FROM " . $wpdb->prefix . "hdflv" . $join . $where );
		
		$paged = filter_input ( INPUT_GET, 'pagenum' );
		
		$pagenum = isset ( $_GET ['pagenum'] ) ? absint ( $_GET ['pagenum'] ) : 1;
		$limit = 20;
		$offset = ($pagenum - 1) * $limit;
		
		if (! $paged) {
			$limit = "LIMIT 0 , $limit";
		} else {
			$lowLimit = $paged * ($limit);
			$lowLimit = $lowLimit - ($limit);
			if ($lowLimit < 0) {
				$lowLimit = 0;
			}
			$hiLimit = $limit;
			$limit = "LIMIT $lowLimit , $hiLimit ";
		}
		$getorderDirection = filter_input ( INPUT_GET, 'order' );
		$getorderBy = filter_input ( INPUT_GET, 'orderby' );
		$orderBy = array (
				'id',
				'title',
				'desc',
				'fea',
				'publish',
				'date',
				'ordering' 
		);
		$order = '';
		if (isset ( $getorderBy ) && in_array ( $getorderBy, $orderBy )) {
			$order = $getorderBy;
		}
		
		switch ($order) {
			case 'id' :
				$order = 'vid';
				$sortind = 'id';
				break;
			
			case 'title' :
				$order = 'name';
				$sortindt = 'title';
				break;
			
			case 'publish' :
				$order = 'is_active';
				$sortindpub = 'pub';
				break;
			
			default :
				$order = 'vid';
				$sortind = 'id';
				$getorderDirection = 'asc';
		}
		
		$tables = $wpdb->get_results ( "SELECT * FROM " . $wpdb->prefix . "hdflv" . $join . $where . ' order by ' . $order . ' ' . $getorderDirection . " $limit " );
		$pluginDirPath = content_url () . '/plugins/' . dirname ( plugin_basename ( __FILE__ ) ) . '/images';
		?>
<!-- Manage Video-->
<div class="wrap hdflvwrap">

<?php $this->haflvMenuTab(); ?>
            <form name="filterType" method="post" id="posts-filter">
		<h2><?php _e('Manage Videos', 'hdflv'); ?></h2>
		<div class="admin_hdflv_video_info">
			<span class="hint_head">How To Use?</span>
			<p>
				To display single video player on any page or post use the plugin
				code in any of the formats specified below. <br />
				<br /> <strong>[hdplay id=3 playlistid=2 width=400 height=400]</strong>
				or <strong>[hdplay playlistid=2]</strong> or <strong>[hdplay id=3]</strong>
				<br />
				<br /> id - The Video ID, you can find the video id on 'Manage
				Videos' admin page.<br />
				<br /> Playlist id - You can find the Category ID on Categories
				page.<br />
				<br /> Both the Video ID and Playlist ID will be generated
				automatically once you add new Video or Category to 'Wordpress HD
				FLV Player'.<br />
				<br /> You can use the plugin code with flashvars when you would
				like to display a player on any page/post with some specific
				settings. <br />
				<br /> <strong>[hdplay id=2 flashvars="autoplay=false&timer=false"
					width=250 height=250]</strong><br />
				<br />
			</p>
		</div>
		<!--                for show select playlist and Add vidoes features. -->


          <?php
		$orderField = filter_input ( INPUT_GET, 'order' );
		$direction = isset ( $orderField ) ? $orderField : false;
		$reverse_direction = ($direction == 'DESC' ? 'ASC' : 'DESC');
		if ($search_results != '') {
			?>
        <div class="updated below-h2">

<?php
			$url = get_bloginfo ( 'url' ) . '/wp-admin/admin.php?page=hdflv';
			if ($total_pages >= 1) {
				echo $total_pages . "   Search Result(s) for '" . $search_results . "'.&nbsp&nbsp&nbsp<a href='$url' >Back to Videos List</a>";
			} else {
				echo " No Search Result(s) for '" . $search_results . "'.&nbsp&nbsp&nbsp<a href='$url' >Back to Videos List</a>";
			}
			?>
    </div>    <?php } ?>

                <div style="margin: 23px 0px 10px 1px;">
			<div class="alignleft actions">
        	                Select Playlist<?php $this->playlist_filter($plfilter); ?>
                    <input title="To Filter Playlist"
					class="button-secondary" id="post-query-submit" type="submit"
					name="startfilter" value="<?php _e('Filter', 'hdflv'); ?> &raquo;"
					class="button" />
			</div>

			<p class="search-box">
				<input type="text" class="search-input" name="search"
					id="videosearch" value="<?php echo $search; ?>" size="10" /> <input
					type="submit" class="button-secondary"
					onclick="return validatevideosearch();"
					value="<?php _e('Search Videos', 'hdflv'); ?>" /> <span
					style="color: red;" id="bind_video_search_error"></span> <input
					type="hidden" name="cancel" value="2" />
			</p>

		</div>

		<!-- Table -->
		<table class="widefat" cellspacing="0" style="margin-top: 6%;"
			id="videostableid">
			<thead>
				<tr>
					<th style="width: 4%;" id="id" class="manage-column column-id"
						scope="col"><a
						href="<?php echo $_SERVER['PHP_SELF'] .'?page=hdflv&orderby=id&order='.$reverse_direction; ?>"
						class="video_cat_id"> <span><?php _e('Video ID', 'hdflv'); ?> </span>
							<span
							class="sortingindicator<?php echo $sortind.$reverse_direction; ?>"></span>
					</a></th>
					<th style="width: 38%;" id="title"
						class="manage-column column-title" scope="col"><a
						href="<?php echo $_SERVER['PHP_SELF'] .'?page=hdflv&orderby=title&order='.$reverse_direction; ?>"
						class="video_cat_title"> <span><?php _e('Title', 'hdflv'); ?> </span>
							<span
							class="sortingindicator<?php echo $sortindt.$reverse_direction; ?>"></span>
					</a></th>
					<th style="width: 51%;" id="path" class="manage-column column-path"
						scope="col"><?php _e('Path', 'hdflv'); ?> </th>
<?php
		$id1 = 0;
		$playid = filter_input ( INPUT_GET, 'playid' );
		if (isset ( $playlist_id ) && $playlist_id != 'no' && $playlist_id != '0' || isset ( $playid )) {
			$id1 = '1';
			?>
                    <th id="path" class="manage-column column-path"
						scope="col"><?php _e('Sort Order', 'hdflv'); ?> </th><?php } ?>
                    <th id="Status" class="manage-column column-id"
						scope="col"><a
						href="<?php echo $_SERVER['PHP_SELF'] .'?page=hdflv&orderby=publish&order='.$reverse_direction; ?>"
						class="video_cat_pub"> <span><?php _e('Status', 'hdflv'); ?></span>
							<span
							class="sortingindicator<?php echo $sortindpub.$reverse_direction; ?>"></span>
					</a></th>

				</tr>
			</thead>
			<tbody id="test-list" class="list:post">
				<input type=hidden id=playlistid2 name=playlistid2
					value=<?php echo $playlist_id ?> />
				<input type="hidden" id="imagepath" name="imagepath"
					value="<?php echo $pluginDirPath; ?>" />
				<div name=txtHint></div>
<?php
		if ($tables) {
			$i = 0;
			$paged = filter_input ( INPUT_GET, 'paged' );
			if ($paged) {
				$editPageid = 'paged=' . $paged;
			} else {
				$editPageid = '';
			}
			$showArrowImage = "<img src='" . content_url () . "/plugins/" . dirname ( plugin_basename ( __FILE__ ) ) . "/images/arrow.png' alt='move' width='16' height='16' class='handle' /></th>\n";
			$class = 'class="alternate"';
			foreach ( $tables as $table ) {
				echo "<tr $class id=\"listItem=$table->vid\" >\n";
				echo "<th style='width:5%;' scope=\"row\" > $table->vid ";
				echo "&nbsp";
				if ($id1 == '1') {
					echo $showArrowImage;
				}
				
				echo "<td  class='post-title column-title' style='text-align: left;'><strong><a title='" . __ ( 'Edit this media', 'hdflv' ) . "' href='$this->base_page&amp;mode=edit&amp;id=$table->vid'>" . stripslashes ( $table->name ) . "</a></strong>\n";
				echo "<input type='hidden' name='video_name' value=' .  $table->name . '>";
				echo "<span class='edit'>
                                                                <a title='" . __ ( 'Edit this video', 'hdflv' ) . "' href='$this->base_page&amp;mode=edit&amp;id=$table->vid&amp;$editPageid'>" . __ ( 'Edit' ) . "</a>
                                                              </span> | ";
				echo "<span class='delete'>
                                                                <a title='" . __ ( 'Delete this video', 'hdflv' ) . "' href='$this->base_page&amp;mode=delete&amp;id=$table->vid&amp;video_name=$table->name' onclick=\"javascript:check=confirm( '" . __ ( "Delete this video ?", 'hdflv' ) . "');if(check==false) return false;\">" . __ ( 'Delete' ) . "</a>
                                                              </span>";
				echo "</td>\n";
				echo "<td>" . htmlspecialchars ( stripslashes ( $table->file ), ENT_QUOTES ) . "</td>\n";
				$playid = filter_input ( INPUT_GET, 'playid' );
				$plfilter = filter_input ( INPUT_POST, 'plfilter' );
				$tablevid = intval ( $table->vid );
				if (isset ( $plfilter ) && $plfilter != 'no' && $plfilter != '0') {
					$a1 = mysql_query ( "SELECT sorder FROM " . $wpdb->prefix . "hdflv_med2play where playlist_id=" . $plfilter . " and media_id=$tablevid" );
					$playlist1 = mysql_fetch_array ( $a1 );
					echo "<td id=txtHint[$table->vid] >" . $playlist1 [0] . "</td>\n";
				} elseif (isset ( $playid )) {
					$a1 = mysql_query ( "SELECT sorder FROM " . $wpdb->prefix . "hdflv_med2play where playlist_id=" . $playid . " and media_id=$tablevid" );
					$playlist1 = mysql_fetch_array ( $a1 );
					echo "<td id=txtHint[$table->vid]>" . $playlist1 [0] . "</td>\n";
				}
				if ($table->is_active) {
					
					echo "<td  style = 'padding-left: 2%;'id='status$table->vid'><img  title='deactive' style='cursor:pointer;' onclick='setVideoStatusOff($table->vid,0)'  src=$pluginDirPath/hdflv_active.png /></td>\n";
				} else {
					
					echo "<td style = 'padding-left: 2%;' id='status$table->vid'><img  title='active' style='cursor:pointer;' onclick='setVideoStatusOff($table->vid,1)' src=$pluginDirPath/hdflv_deactive.png /></td>\n";
				}
				
				echo '</tr>';
				$i ++;
			}
		} else {
			echo '<tr><td colspan="7" align="center"><b>' . __ ( 'No entries found', 'hdflv' ) . '</b></td></tr>';
		}
		?>
                    </tbody>
		</table>
		<div style="float: right; margin-top: 0%;">
                     <?php
		$limit = 20;
		$pagenum = isset ( $_GET ['pagenum'] ) ? absint ( $_GET ['pagenum'] ) : 1;
		$num_of_pages = ceil ( $total_pages / $limit );
		if (isset ( $search ))
			$arr_params = array (
					"search" => "$search",
					"playlistid" => $plfil,
					"pagenum" => "%#%" 
			);
		else if (isset ( $plfil ))
			$arr_params = array (
					'playlistid' => $plfil,
					'pagenum' => '%#%' 
			);
		else
			$arr_params = array (
					'pagenum' => '%#%' 
			);
		$page_links = paginate_links ( array (
				'base' => add_query_arg ( $arr_params ),
				'format' => '',
				'prev_text' => __ ( '&laquo;', 'aag' ),
				'next_text' => __ ( '&raquo;', 'aag' ),
				'total' => $num_of_pages,
				'current' => $pagenum 
		) );
		
		if ($page_links) {
			echo '<div class="tablenav" style=" margin-right: 15px; "><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
		}
		?>
                </div>
	</form>
	<script type="text/javascript">

            function validatevideosearch()
            {
                search_playlist = document.getElementById('videosearch').value;
                if(search_playlist.trim()==''){
                    document.getElementById("bind_video_search_error").innerHTML='Please enter search keyword';
                    document.getElementById("videosearch").focus;
                    return false;
                }
            }
                                    </script>
</div>
<?php
	}
	
	// function end for Manage Video files page
	function show_edit() {
		global $wpdb;
		$actvid = intval ( $this->act_vid );
		$media = $wpdb->get_row ( "SELECT * FROM " . $wpdb->prefix . "hdflv where vid = $actvid" );
		$act_name = htmlspecialchars ( stripslashes ( $media->name ) );
		$act_filepath = stripslashes ( $media->file );
		$act_hdpath = stripslashes ( $media->hdfile );
		$act_image = stripslashes ( $media->image );
		$act_link = stripslashes ( $media->link );
		$act_opimg = stripslashes ( $media->opimage );
		?>
<!-- Edit Video -->

<div class="wrap">
	<h2> <?php _e('Edit Video', 'hdflv') ?> </h2>
	<form name="table_options" method="post" id="video_options"
		enctype="multipart/form-data" onSubmit="return edtValidate();">
		<div id="poststuff" class="has-right-sidebar a">
			<div class="inner-sidebar">
				<div id="submitdiv" class="postbox">
					<h3 class="hndle">
						<span><?php _e('Playlists', 'hdflv') ?></span>
					</h3>
					<div class="inside">
						<div id="submitpost" class="submitbox">
							<div class="misc-pub-section">
								<p>
<?php _e('If you want to show this media file in your page, enter the tag :', 'hdflv') ?><br />
									<strong>[hdplay id=<?php echo $this->act_vid; ?>]</strong>
								</p>
							</div>
							<div class="misc-pub-section">


								<h4> <?php _e('Playlist', 'hdflv'); ?> &nbsp;&nbsp;
                                    <a style="cursor: pointer"
										onclick="playlistdisplay()"><?php _e('Create New', 'hdflv') ?></a>
								</h4>
								<div id="playlistcreate"><?php _e('Name', 'hdflv'); ?><input
										type="text" size="20" name="p_name" id="p_name" value="" /> <input
										type="button" class="button-primary" name="add_pl1"
										value="<?php _e('Add'); ?>"
										onclick="return savePlaylist(document.getElementById('p_name') , <?php echo$this->act_vid ?>);"
										class="button button-highlighted" /> <a
										style="cursor: pointer" onclick="playlistclose()"><b>Close</b></a>
								</div>
								<p id="jaxcat"
									style="color: red; font-size: 12px; font-weight: bold"></p>
								<div id="playlistchecklist"><?php get_playlist_for_dbx(filter_input(INPUT_GET, 'id')); ?></div>
							</div>

							<div id="major-publishing-actions">
								<input type="submit" class="button-primary" name="edit_update"
									value="<?php _e('Update'); ?>"
									class="button button-highlighted" /> <input type="button"
									onclick="window.location.href='admin.php?page=hdflv'"
									class="button-secondary" name="cancel"
									value="<?php _e('Cancel'); ?>" class="button" /> <input
									type="hidden"
									value="<?php
		
$contus = dirname ( plugin_basename ( __FILE__ ) );
		echo content_url () . '/plugins/' . $contus;
		?>"
									id="pluginUrl" name="pluginUrl" />
							</div>
						</div>
					</div>
				</div>

			</div>
			<div id="post-body" class="has-sidebar">
				<div id="post-body-content" class="has-sidebar-content">
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Video Title', 'hdflv') ?></th>
							<td><input type="text" size="50" name="act_name"
								value="<?php echo $act_name ?>" id="act_name" /><br /><?php if ($act_name != ''): ?> <span
								id="alert_title"
								style="color: red; font-size: 12px; font-weight: bold;"></span><?php endif; ?></td>
						</tr>
                        <?php if (!empty($media->streamer_path)){ ?>
                        <tr valign="top" id="stream1">
							<th scope="row"><?php _e('Streamer Path', 'video_gallery') ?></th>
							<td class="rtmp_td"><input type="text" name="streamname"
								id="streamname" value="<?php echo $media->streamer_path; ?>" />

								<span id="streamermessage"
								style="color: red; display: block; color: #ff0000;"></span>
								<p><?php _e('Here you need to enter the RTMP Streamer Path', 'video_gallery') ?></p>
							</td>

						</tr>
						<tr valign="top" id="islive_visible">
							<th scope="row"><?php _e('Is Live', 'video_gallery') ?></th>
							<td><input type="radio" style="float: none;" name="islive"
								id="islive2"
								<?php
			if (isset ( $media->islive ) && $media->islive == '1') {
				echo 'checked="checked" ';
			}
			?>
								value="1" /><label><?php _e('Yes', 'video_gallery') ?></label> <input
								type="radio" style="float: none;" name="islive" id="islive1"
								<?php
			if (isset ( $media->islive ) && $media->islive == '0') {
				echo 'checked="checked" ';
			}
			?>
								value="0" /><label><?php _e('No', 'video_gallery') ?></label> <span
								id="rtmplivemessage" style="display: block;"></span>
								<p><?php _e('Here you need to select whether your RTMP video is a live video or not', 'video_gallery') ?></p>
							</td>
						</tr>
                                <?php } ?>
                        <tr valign="top">
							<th scope="row"><?php _e('Video URL', 'hdflv') ?></th>
							<td><input type="text" size="80" name="act_filepath"
								value="<?php echo $act_filepath ?>" id="act_filepath" />
								<p><?php _e('Here you need to enter the URL to the file ( MP4, M4V, M4A, MOV, Mp4v or F4V)', 'hdflv') ?></p>
								<p><?php echo _e('Example Youtube link: http://www.youtube.com/watch?v=tTGHCRUdlBs', 'hdflv') ?></p>
<?php if ($act_filepath != ''): ?><span id="alert_VUrl"
								style="color: red; font-size: 12px; font-weight: bold;"></span><?php endif; ?>
                            </td>
						</tr>
                         <?php if (empty($media->streamer_path)){ ?>
                        <tr valign="top">
							<th scope="row"><?php _e('HD Video URL', 'hdflv') ?></th>
							<td><input type="text" size="80" name="act_hdpath"
								value="<?php echo $act_hdpath ?>" id="act_hdpath" />
								<p><?php _e('Enter the URL to HD video file', 'hdflv') ?><?php if ($act_hdpath != ''): ?></p>
								<span id="alert_HDURL"
								style="color: red; font-size: 12px; font-weight: bold;"></span><?php endif; ?></td>
						</tr>
                         <?php } ?>
                        <tr valign="top">
							<th scope="row"><?php _e('Thumbnail URL', 'hdflv') ?></th>
							<td><input type="text" size="80" name="act_image"
								value="<?php echo $act_image ?>" id="act_image" />
								<p><?php _e('Enter the URL to show a thumbnail of the video file or upload a image', 'hdflv') ?><?php if ($act_image != ''): ?></p>
								<span id="alert_IMGURL"
								style="color: red; font-size: 12px; font-weight: bold;"></span><?php endif; ?>
                                    <br />
								<form name="edit_thumb_form" method="post"
									enctype="multipart/form-data">
									<input type="file" name="edit_thumb" id="edit_thumb"> <input
										type="submit" name="thumb-update" value="Upload"
										class="button-secondary" onclick="return validateFileExt();">
								</form> <br /> <span id="errmsg_thumbimg"
								style="color: red; font-size: 12px; font-weight: bold; display: none;"></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Preview Image URL', 'hdflv') ?></th>
							<td><input type="text" size="80" name="act_opimg"
								value="<?php echo $act_opimg ?>" id="act_opimg" />
								<p><?php _e('Enter the URL to show a preview of the video file or upload a image', 'hdflv') ?><?php if ($act_opimg != ''): ?></p>
								<span id="alert_prIMGURL"
								style="color: red; font-size: 12px; font-weight: bold;"></span><?php endif; ?>
                                        <br />
								<form name="edit_preview_form" method="post"
									enctype="multipart/form-data"
									onSubmit="return validateFileExt();">
									<input type="file" name="edit_preview" id="edit_preview"> <input
										type="submit" name="preview-update" value="Upload"
										class="button-secondary">
								</form> <br /> <span id="errmsg_previewimg"
								style="color: red; font-size: 12px; font-weight: bold; display: none;"></span>
							</td>
						</tr>
						<!--                                <tr valign="top">
                                    <th scope="row"><?php _e('Link URL', 'hdflv') ?></th>
                                    <td><input type="text" size="80" name="act_link" value="<?php echo $act_link ?>" id="act_link"/>
                                        <br /><?php _e('Enter the URL to the page/file, if you click on the player', 'hdflv') ?><?php if ($act_link != ''): ?><br /> <span id="alert_linkURL" style="color:red;font-size:12px;font-weight:bold;"></span><?php endif; ?></td>
                                    </tr>-->
					</table>
				</div>
				<p>
					<input type="submit" class="button-primary" name="edit_update"
						value="<?php _e('Update'); ?>" class="button button-highlighted" />
					<input type="button"
						onclick="window.location.href='admin.php?page=hdflv'"
						class="button-secondary" name="cancel"
						value="<?php _e('Cancel'); ?>" class="button" />
				</p>
			</div>
		</div>
		<!--END Poststuff -->

	</form>

</div>
<script> document.getElementById('playlistcreate').style.display = "none";

                function playlistdisplay()
                {
                    document.getElementById('playlistcreate').style.display = "block";
                }
                function playlistclose()
                {
                    document.getElementById('playlistcreate').style.display = "none";
                }

            </script>
<!--END wrap -->
<?php
	}
	
	// function show_edit() end hear
	function show_add() { // Add A Video
		?>
<div class="wrap">
	<script type="text/javascript">

                                                    function t1(t2)
                                                    {
                                                        if(t2.value == "y" ){
                                                            document.getElementById('message').style.display = "none";
                                                            document.getElementById('upload2').style.display = "block";
                                                            document.getElementById('youtube').style.display = "none";
                                                            document.getElementById('customurl').style.display = "none";
                                                        }
                                                        if(t2.value == "c" ){
                                                            document.getElementById('message').style.display = "none";
                                                            document.getElementById('youtube').style.display = "block";
                                                            document.getElementById('upload2').style.display = "none";
                                                            document.getElementById('customurl').style.display = "none";
                                                        }
                                                        if(t2.value == "url" ){
                                                            document.getElementById('msg_video_url').innerHTML = "Enter the video URL";
                                                            document.getElementById('video_url_example').innerHTML = "Example: http://www.domain.com/videopath/video.mp4";
                                                            document.getElementById('message').style.display = "none";
                                                            document.getElementById('stream1').style.display = "none";
                                                            document.getElementById('islive_visible').style.display = "none";
                                                            document.getElementById('customurl').style.display = "block";
                                                            document.getElementById('youtube').style.display = "none";
                                                            document.getElementById('upload2').style.display = "none";
                                                        }else if(t2.value == "rtmp"){
                                                            document.getElementById('msg_video_url').innerHTML = "Enter the video file name";
                                                            document.getElementById('video_url_example').innerHTML = "Example: video.mp4";
                                                            document.getElementById('customurl').style.display = "block";
                                                            document.getElementById('islive_visible').style.display = "";
                                                            document.getElementById('stream1').style.display = "";
                                                            document.getElementById('hdvideourl').style.display = "none";
                                                            document.getElementById('youtube').style.display = "none";
                                                            document.getElementById('upload2').style.display = "none";
                                                        }
                                                    }


                                                </script>
<?php $this->haflvMenuTab(); ?>
                                                <div
		id="playlistResponse"></div>
	<h2> <?php _e('Add Video', 'hdflv'); ?> </h2>
	<div id="poststuff" class="has-right-sidebar b">
		<div class="stuffbox" style="float: left; width: 74%;" name="youtube">
			<h3 class="hndle">
				<span><input type="radio" name="agree" id="btn1" value="y"
					onClick="t1(this)" /> Upload file <input type="radio" name="agree"
					id="btn2" value="c" checked="checked" onClick="t1(this)" /> YouTube
					URL <input type="radio" name="agree" id="btn3" value="url"
					onClick="t1(this)" /> Custom URL <input type="radio" name="agree"
					id="btn4" value="rtmp" onClick="t1(this)" /> RTMP</span>
			</h3>
			<span id="message"
				style="margin-top: 100px; margin-left: 300px; color: red; font-size: 12px; font-weight: bold;"></span>
			<form method=post>
				<div id="youtube" class="inside" style="margin: 15px;">
					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('Youtube Video URL', 'hdflv') ?></th>
							<td><input type="text" size="50" name="filepath" id="filepath1"
								onkeyup="generate12(this.value);" />&nbsp;&nbsp<input
								id="generate" type="submit" name="youtube_media"
								class="button-primary"
								value="<?php _e('Generate Details', 'hdflv'); ?>" />
								<p><?php _e('Enter the youtube video URL', 'hdflv') ?></p>
								<p><?php _e('Example: http://www.youtube.com/watch?v=tTGHCRUdlBs', 'hdflv') ?></p>
								<span id="youtube_url_message"
								style="color: red; display: none; font-size: 12px; font-weight: bold;">Enter
									Youtube URL</span></td>
						</tr>
					</table>
				</div>

				<div id="customurl" class="inside" style="margin: 15px;">
<?php _e('<b>Supported video formats: </b>( MP4, M4V, M4A, MOV, Mp4v or F4V)', 'hdflv')?>
                                                            <table
						class="form-table">
						<tr id="stream1">
							<th scope="row"><?php _e('Streamer Path', 'video_gallery') ?></th>
							<td class="rtmp_td"><input type="text" name="streamname"
								id="streamname" value="" /> <span id="streamermessage"
								style="color: red; display: block; font-size: 12px; font-weight: bold;"></span>
								<p><?php _e('Here you need to enter the RTMP Streamer Path', 'video_gallery') ?></p>
							</td>

						</tr>
						<tr id="islive_visible">
							<th scope="row"><?php _e('Is Live', 'video_gallery') ?></th>
							<td><input type="radio" style="float: none;" name="islive"
								id="islive2" value="1" /><label><?php _e('Yes', 'video_gallery') ?></label>
								<input type="radio" style="float: none;" name="islive"
								id="islive1" checked="checked" value="0" /><label><?php _e('No', 'video_gallery') ?></label>
								<span id="rtmplivemessage" style="display: block;"></span>
								<p><?php _e('Here you need to select whether your RTMP video is a live video or not', 'video_gallery') ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Video URL', 'hdflv') ?></th>
							<td><input type="text" size="50" name="filepath2" id="filepath2" />
								<p>
									<span id="msg_video_url"></span>
								</p>
								<p>
									<span id="video_url_example"></span>
								</p>
								<p>
									<span id="video_url_message"
										style="color: red; display: none; font-size: 12px; font-weight: bold;">Please
										enter Video URL</span>
								</p></td>
						</tr>
						<tr id="hdvideourl">
							<th scope="row"><?php _e('HD Video URL (optional)', 'hdflv') ?></th>
							<td><input type="text" size="50" name="filepath3" id="filepath3" />
								<p><?php _e('Enter the URL of the HD video file', 'hdflv') ?></p>
								<p><?php echo _e('Example: http://www.domain.com/videopath/hdvideo.mp4', 'hdflv') ?></p>
								<p>
									<span id="videohd_url_message"
										style="color: red; display: none; font-size: 12px; font-weight: bold;">Please
										enter valid Hd Url</span>
								</p></td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Thumb Image URL', 'hdflv') ?></th>
							<td><input type="text" size="50" name="filepath4" id="filepath4" />
								<p><?php _e('Enter the URL to your thumb image', 'hdflv') ?></p>
								<p><?php echo _e('Example: http://www.domain.com/imagepath/thumb.png', 'hdflv') ?></p>
								<p>
									<span id="thumb_url_message"
										style="color: red; display: none; font-size: 12px; font-weight: bold;">Please
										enter Thumb Image Url</span>
								</p></td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Preview Image URL (optional)', 'hdflv') ?></th>
							<td><input type="text" size="50" name="filepath5" id="filepath5" />
								<p><?php _e('Enter the URL to your preview image', 'hdflv') ?></p>
								<p><?php echo _e('Example: http://www.domain.com/imagepath/preview.png', 'hdflv') ?></p>
								<p>
									<span id="preview_url_message"
										style="color: red; display: none; font-size: 12px; font-weight: bold;">Please
										enter Preview Image Url</span>
								</p></td>
						</tr>
					</table>
				</div>

			</form>
			<!--   upload file coding -->
			<div id="upload2" class="inside" style="width: 72%;">
<?php _e('<b>Supported video formats: </b>( MP4, M4V, M4A, MOV, Mp4v or F4V)', 'hdflv')?>
                                        <table class="form-table"
					style="width: 122%;">
					<tr id="ffmpeg_disable_new1" name="ffmpeg_disable_new1">
						<td>Upload Video</td>
						<td>
							<div id="f1-upload-form">
								<form name="normalvideoform" method="post"
									enctype="multipart/form-data">
									<input type="file" name="myfile"
										onchange="enableUpload(this.form.name);" /> <input
										type="button" class="button" name="uploadBtn"
										value="Upload Video" disabled="disabled"
										onclick="return addQueue(this.form.name,this.form.myfile.value);" />
									<input type="hidden" name="mode" value="video" />
								</form>
							</div>
							<div id="f1-upload-progress" style="display: none">
								<div style="float: left">
									<img id="f1-upload-image"
										src="<?php echo content_url() . '/plugins/' . dirname(plugin_basename(__FILE__)) . '/images/empty.gif' ?>"
										alt="Uploading" style="padding-top: 2px" /> <label
										style="padding-top: 0px; padding-left: 4px; font-size: 14px; font-weight: bold; vertical-align: top"
										id="f1-upload-filename">PostRoll.flv</label>
								</div>
								<div style="float: right">
									<span id="f1-upload-cancel"> <a
										style="float: right; padding-right: 10px;"
										href="javascript:cancelUpload('normalvideoform');"
										name="submitcancel">Cancel</a>
									</span> <label id="f1-upload-status"
										style="float: right; padding-right: 40px; padding-left: 20px;">Uploading</label>
									<span id="f1-upload-message"
										style="float: right; font-size: 10px; background: #FFAFAE;"> <b>Upload
											Failed:</b> User Cancelled the upload
									</span>
								</div>


							</div> <span id="upload_video_message"
							style="color: red; display: none; font-size: 12px; font-weight: bold;"></span>
						</td>
					</tr>

					<tr id="ffmpeg_disable_new2" name="ffmpeg_disable_new1">
						<td>Upload HD Video(optional)</td>
						<td>
							<div id="f2-upload-form">
								<form name="hdvideoform" method="post"
									enctype="multipart/form-data">
									<input type="file" name="myfile"
										onchange="enableUpload(this.form.name);" /> <input
										type="button" class="button" name="uploadBtn"
										value="Upload Video" disabled="disabled"
										onclick="return addQueue(this.form.name,this.form.myfile.value);" />
									<input type="hidden" name="mode" value="video" />
								</form>
							</div>
							<div id="f2-upload-progress" style="display: none">
								<div style="float: left">
									<img id="f2-upload-image"
										src="<?php echo content_url() . '/plugins/' . dirname(plugin_basename(__FILE__)) . '/images/empty.gif' ?>"
										alt="Uploading" style="padding-top: 2px" /> <label
										style="padding-top: 0px; padding-left: 4px; font-size: 14px; font-weight: bold; vertical-align: top"
										id="f2-upload-filename">PostRoll.flv</label>
								</div>
								<div style="float: right">
									<span id="f2-upload-cancel"> <a
										style="float: right; padding-right: 10px;"
										href="javascript:cancelUpload('hdvideoform');"
										name="submitcancel">Cancel</a>

									</span> <label id="f2-upload-status"
										style="float: right; padding-right: 40px; padding-left: 20px;">Uploading</label>
									<span id="f2-upload-message"
										style="float: right; font-size: 10px; background: #FFAFAE;"> <b>Upload
											Failed:</b> User Cancelled the upload
									</span>
								</div>

							</div>
						</td>
					</tr>



					<tr id="ffmpeg_disable_new3" name="ffmpeg_disable_new1">
						<td>Upload Thumb Image</td>
						<td>
							<div id="f3-upload-form">
								<form name="thumbimageform" method="post"
									enctype="multipart/form-data">
									<input type="file" name="myfile"
										onchange="enableUpload(this.form.name);" /> <input
										type="button" class="button" name="uploadBtn"
										value="Upload Image" disabled="disabled"
										onclick="return addQueue(this.form.name,this.form.myfile.value);" />
									<input type="hidden" name="mode" value="image" />
								</form>
							</div>
							<div id="f3-upload-progress" style="display: none">
								<div style="float: left">
									<img id="f3-upload-image"
										src="<?php echo content_url() . '/plugins/' . dirname(plugin_basename(__FILE__)) . '/images/empty.gif' ?>"
										alt="Uploading" style="padding-top: 2px" /> <label
										style="padding-top: 0px; padding-left: 4px; font-size: 14px; font-weight: bold; vertical-align: top"
										id="f3-upload-filename">PostRoll.flv</label>
								</div>
								<div style="float: right">
									<span id="f3-upload-cancel"> <a
										style="float: right; padding-right: 10px;"
										href="javascript:cancelUpload('thumbimageform');"
										name="submitcancel">Cancel</a>
									</span> <label id="f3-upload-status"
										style="float: right; padding-right: 40px; padding-left: 20px;">Uploading</label>
									<span id="f3-upload-message"
										style="float: right; font-size: 10px; background: #FFAFAE;"> <b>Upload
											Failed:</b> User Cancelled the upload
									</span>
								</div>

							</div> <span id="upload_thumb_message"
							style="color: red; display: none; font-size: 12px; font-weight: bold;"></span>
						</td>
					</tr>

					<tr id="ffmpeg_disable_new4" name="ffmpeg_disable_new1">
						<td>Upload Preview Image(optional)</td>
						<td>
							<div id="f4-upload-form">
								<form name="previewimageform" method="post"
									enctype="multipart/form-data">
									<input type="file" name="myfile"
										onchange="enableUpload(this.form.name);" /> <input
										type="button" class="button" name="uploadBtn"
										value="Upload Image" disabled="disabled"
										onclick="return addQueue(this.form.name,this.form.myfile.value);" />
									<input type="hidden" name="mode" value="image" />
								</form>
							</div>
							<div id="f4-upload-progress" style="display: none">
								<div style="float: left">
									<img id="f4-upload-image"
										src="<?php echo content_url() . '/plugins/' . dirname(plugin_basename(__FILE__)) . '/images/empty.gif' ?>"
										alt="Uploading" style="padding-top: 2px" /> <label
										style="padding-top: 0px; padding-left: 4px; font-size: 14px; font-weight: bold; vertical-align: top"
										id="f4-upload-filename">PostRoll.flv</label>
								</div>
								<div style="float: right">
									<span id="f4-upload-cancel"> <a
										style="float: right; padding-right: 10px;"
										href="javascript:cancelUpload('previewimageform');"
										name="submitcancel">Cancel</a>
									</span> <label id="f4-upload-status"
										style="float: right; padding-right: 40px; padding-left: 20px;">Uploading</label>
									<span id="f4-upload-message"
										style="float: right; font-size: 10px; background: #FFAFAE;"> <b>Upload
											Failed:</b> User Cancelled the upload
									</span>
								</div>


							</div>
							<div id="nor">
								<iframe id="uploadvideo_target" name="uploadvideo_target"
									src="#" style="width: 0; height: 0; border: 0px solid #fff;"></iframe>
							</div>
						</td>
					</tr>

				</table>
			</div>
		</div>
	</div>

	<form name="table_options" enctype="multipart/form-data" method="post"
		id="video_options" onsubmit="return chkbut()">
		<div id="poststuff" class="has-right-sidebar videourl c">
			<input type="hidden" name="normalvideoform-value"
				id="normalvideoform-value" value="" /> <input type="hidden"
				name="hdvideoform-value" id="hdvideoform-value" value="" /> <input
				type="hidden" name="thumbimageform-value" id="thumbimageform-value"
				value="" /> <input type="hidden" name="previewimageform-value"
				id="previewimageform-value" value="" /> <input type="hidden"
				name="youtube-value" id="youtube-value" value="" /> <input
				type="hidden" name="streamerpath-value" id="streamerpath-value"
				value="" /> <input type="hidden" name="islive-value"
				id="islive-value" value="" /> <input type="hidden" name="customurl"
				id="customurl1" value="" /> <input type="hidden" name="customhd"
				id="customhd1" value="" /> <input type="hidden" name="customimage"
				id="customimage" value="" /> <input type="hidden"
				name="custompreimage" id="custompreimage" value="" /> <input
				type="hidden"
				value="<?php
		
$contus = dirname ( plugin_basename ( __FILE__ ) );
		echo content_url () . '/plugins/' . $contus;
		?>"
				id="pluginUrl" name="pluginUrl" />
			<div class="inner-sidebar">
				<div id="submitdiv" class="postbox">
					<h3 class="hndle">
						<span><?php _e('Playlists', 'hdflv') ?></span>
					</h3>
					<div class="inside">
						<div id="submitpost" class="submitbox">

							<div class="misc-pub-section"><?php //if(mysql_num_rows($playid1)) {    ?>
                                        <h4><?php _e('Playlist', 'hdflv'); ?>&nbsp;&nbsp;
                                            <a style="cursor: pointer"
										onclick="playlistdisplay()"><?php _e('Create New', 'hdflv') ?></a>
								</h4>

								<div id="playlistcreate1"><?php _e('Name', 'hdflv'); ?><input
										type="text" size="20" name="p_name" id="p_name" value="" /> <input
										type="button" class="button-primary" name="add_pl1"
										value="<?php _e('Add'); ?>"
										onclick="return savePlaylist(document.getElementById('p_name') , <?php echo $this->act_vid ?>);"
										class="button button-highlighted" /> <a
										style="cursor: pointer" onclick="playlistclose()"><b>Close</b></a>
								</div>

								<p id="jaxcat"
									style="color: red; font-size: 12px; font-weight: bold"></p>

								<div id="playlistchecklist"><?php get_playlist(); ?></div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="post-body" class="has-sidebar">
				<br>
				<div id="post-body-content" class="has-sidebar-content">

					<div class="stuffbox">
						<h3 class="hndle">
							<span><?php _e('Enter Video Title / Name', 'hdflv'); ?></span>
						</h3>
						<div class="inside" style="margin: 15px;">
							<table class="form-table">
								<tr>
									<th scope="row"><?php _e('Video Title / Name', 'hdflv') ?></th>
									<td><input type="text" size="50" maxlength="200" name="name"
										id="name" /> <br />
									<span style="color: red; font-size: 12px; font-weight: bold"
										id="Errormsgname"> </span></td>
								</tr>
							</table>
						</div>
					</div>

				</div>
				<p>
					<input type="submit" name="add_media" class="button-primary"
						onclick="return validateInput();"
						value="<?php _e('Add Video', 'hdflv'); ?>" class="button" /> <input
						type="button"
						onclick="window.location.href='admin.php?page=hdflv'"
						class="button-secondary" name="cancel"
						value="<?php _e('Cancel'); ?>" class="button" />

				</p>

			</div>
		</div>
		<!--END Poststuff -->

	</form>
	<script type="text/javascript">

                document.getElementById('upload2').style.display = "none";
                document.getElementById('customurl').style.display = "none";
                document.getElementById('name').value = document.getElementById('act0').value;
                document.getElementById('filepath1').value = document.getElementById('act4').value;

            </script>
	<script> document.getElementById('playlistcreate1').style.display = "none";
                document.getElementById('generate').style.visibility  = "hidden";
                function playlistdisplay()
                {
                    document.getElementById('playlistcreate1').style.display = "block";
                }
                function playlistclose()
                {
                    document.getElementById('playlistcreate1').style.display = "none";
                }
                function generate12(str1)
                {
                     var theurl=str1;
                     if (theurl.indexOf("youtube.com") != -1 || theurl.indexOf("youtu.be") != -1){
                        document.getElementById('generate').style.visibility = "visible";
                        document.getElementById('youtube_url_message').style.display = 'none';
                }
                    else document.getElementById('generate').style.visibility  = "hidden";
                }
            </script>

</div>
<!--END wrap -->
<?php
	}
	function show_plydel() {
		$playlist_name = filter_input ( INPUT_GET, 'pname' );
		$message = hd_delete_playlist ( $this->act_pid, $playlist_name );
		$this->mode = 'playlist';
		// show playlist
		$this->render_admin ( $this->mode );
	}
	function show_plyedit() {
		// use the same output as playlist
		$this->render_admin ( 'playlist' );
	}
	
	// Edit or update playlst
	function show_playlist() {
		global $wpdb;
		
		// get the tables
		$did = filter_input ( INPUT_GET, 'did' );
		$pid = filter_input ( INPUT_GET, 'pid' );
		$pname = filter_input ( INPUT_GET, 'pname' );
		$where = $sortind = $sortindt = '';
		$search_inputbox = filter_input ( INPUT_POST, 'search_playlist' );
		if (isset ( $_GET ['search_playlist'] )) {
			$search_query = $_GET ['search_playlist'];
		}
		$search_input = (isset ( $search_inputbox )) ? $search_inputbox : (isset ( $search_query ) ? $search_query : '');
		
		// check for page navigation
		$search = (isset ( $search_input )) ? $search_input : '';
		$search = $this->phpSlashes ( $search );
		$search_results = $search;
		
		if ($search != '') {
			$where .= " WHERE";
			$where1 = "  (playlist_name LIKE '%$search_results%')";
			$kt = preg_split ( "/[\s,]+/", $search ); // Breaking the string to array of words
			                                     // Now let us generate the sql
			while ( list ( $key, $search ) = each ( $kt ) ) {
				if ($search != " " and strlen ( $search ) > 0) {
					$where1 = "  (playlist_name LIKE '%$search%')";
				}
			}
			$where .= $where1;
		}
		
		if (isset ( $pid )) {
			$id = $wpdb->escape ( $pid );
			$update = $wpdb->get_row ( "SELECT pid , playlist_name FROM " . $wpdb->prefix . "hdflv_playlist WHERE pid = $id " );
		} else if (isset ( $did )) {
			hd_delete_playlist ( $did, $pname );
		}
		$pluginDirPath = content_url () . '/plugins/' . dirname ( plugin_basename ( __FILE__ ) ) . '/images';
		
		$pagenum = isset ( $_GET ['playpagenum'] ) ? absint ( $_GET ['playpagenum'] ) : 1;
		$limit = 20;
		$offset = ($pagenum - 1) * $limit;
		$getorderDirection = filter_input ( INPUT_GET, 'order' );
		$getorderBy = filter_input ( INPUT_GET, 'orderby' );
		$orderBy = array (
				'id',
				'title',
				'desc',
				'publish',
				'sorder' 
		);
		$order = 'id';
		
		if (isset ( $getorderBy ) && in_array ( $getorderBy, $orderBy )) {
			$order = $getorderBy;
		}
		
		switch ($order) {
			case 'id' :
				$order = 'pid';
				$sortind = 'id';
				break;
			
			case 'title' :
				$order = 'playlist_name';
				$sortindt = 'title';
				break;
			case 'publish' :
				$order = 'is_publish';
				break;
			
			case 'sorder' :
				$order = 'ordering';
				break;
			
			default :
				$order = 'pid';
				$sortind = 'id';
		}
		
		$tables = $wpdb->get_results ( "SELECT pid , playlist_name , is_pactive FROM " . $wpdb->prefix . "hdflv_playlist $where order by $order $getorderDirection LIMIT $offset, $limit" );
		$Playlist_count = $wpdb->get_var ( "SELECT count(pid) FROM " . $wpdb->prefix . "hdflv_playlist $where" );
		
		?>
<!-- Manage Playlist -->
<div class="wrap">
<?php
		$this->haflvMenuTab ();
		$tablehdflv = $wpdb->prefix . 'hdflv';
		$tablePlaylist = $wpdb->prefix . 'hdflv_playlist';
		$sql = "SHOW TABLES LIKE '$tablehdflv'"; // is table in DB or not
		$isTable = $wpdb->query ( $sql ); // if TRUE THEN 1 ELSE 0
		if ($isTable) { // yes already in DB so add fields only
			$sql = "SHOW COLUMNS FROM $tablehdflv";
			$numOfCol = $wpdb->query ( $sql );
			if ($numOfCol < 8) { // add one col like is_active
			}
		} else { // create table
		}
		$sql = "SHOW TABLES LIKE '$tablePlaylist'"; // is table in DB or not
		$isTable = $wpdb->query ( $sql ); // if TRUE THEN 1 ELSE 0
		
		if ($isTable) { // yes already in DB so add fields only
			$sql = "SHOW COLUMNS FROM $tablePlaylist";
			$numOfCol = $wpdb->query ( $sql );
			if ($numOfCol < 5) { // add one col like is_pactive
			}
		}
		?>
                                                <h2><?php _e('Manage Playlists', 'hdflv'); ?></h2>
	<div class="admin_hdflv_video_info">
		<span class="hint_head">How To Use?</span>
		<p>
			You can use the following plugin code to display videos from
			particular category.<br />
			<br />
			<strong> [hdplay playlistid=id ]</strong><br />
			<br />
			<strong>id</strong> - It is the category id which will be generated
			automatically when you create new category.
	
	</div>
 <?php
		$orderField = filter_input ( INPUT_GET, 'order' );
		$direction = isset ( $orderField ) ? $orderField : false;
		$reverse_direction = ($direction == 'DESC' ? 'ASC' : 'DESC');
		if ($search_results != '') {
			?>
        <div class="updated below-h2">

<?php
			$url = get_bloginfo ( 'url' ) . '/wp-admin/admin.php?page=hdflvplaylist';
			if ($Playlist_count >= 1) {
				echo $Playlist_count . "   Search Result(s) for '" . $search_results . "'.&nbsp&nbsp&nbsp<a href='$url' >Back to Playlists List</a>";
			} else {
				echo " No Search Result(s) for '" . $search_results . "'.&nbsp&nbsp&nbsp<a href='$url' >Back to Playlists List</a>";
			}
			?>
    </div>    <?php } ?>

                                                <form id="editplist"
		name="editplist" method="post">
		<input type="hidden" id="imagepath" name="imagepath"
			value="<?php echo $pluginDirPath; ?>" />
		<div style="margin: 23px 0px 10px 1px;">
			<!--                    <div class="alignleft actions">
        	                Select Status<?php $this->playlist_filter($plfilter); ?>
                    <input title="To Filter Playlist" class="button-secondary" id="post-query-submit" type="submit" name="startfilter"  value="<?php _e('Filter', 'hdflv'); ?> &raquo;" class="button" />
                </div>-->

			<p class="search-box" style="margin-bottom: 10px;">
				<input type="text" class="search-input" id="search_playlist"
					name="search_playlist" value="<?php echo $search; ?>" size="10" />
				<input type="submit" class="button-secondary"
					onclick="return validatesearch();"
					value="<?php _e('Search Playlist', 'hdflv'); ?>" /> <input
					type="hidden" name="cancel" value="2" /> <span style="color: red;"
					id="bind_playlist_search_error"></span>
			</p>

		</div>
		<table class="widefat" cellspacing="0">
			<thead>
				<tr>
					<th style="padding-left: 2%;" scope="col"><a
						href="<?php echo $_SERVER['PHP_SELF'] . '?page=hdflvplaylist&orderby=id&order='.$reverse_direction; ?>"
						class="video_cat_id"> <span><?php _e('Category ID', 'hdflv'); ?></span>
							<span
							class="sortingindicator<?php echo $sortind.$reverse_direction; ?>"></span>
					</a></th>
					<th scope="col"><a
						href="<?php echo $_SERVER['PHP_SELF'] . '?page=hdflvplaylist&orderby=title&order='.$reverse_direction; ?>"
						class="video_cat_title"> <span><?php _e('Name', 'hdflv'); ?></span>
							<span
							class="sortingindicator<?php echo $sortindt.$reverse_direction; ?>"></span>
					</a></th>
					<th scope="col" colspan="2"><?php _e('Action'); ?></th>
					<th scope="col"><?php _e('Status'); ?></th>
				</tr>
			</thead>
<?php
		if ($tables) {
			$i = 0;
			
			foreach ( $tables as $table ) {
				if ($i % 2 == 0) {
					echo "<tr class='alternate'>\n";
				} else {
					echo "<tr>\n";
				}
				echo "<th style='padding-left: 2%;' scope=\"row\">$table->pid</th>\n";
				echo "<td><a title='" . stripslashes ( $table->playlist_name ) . "' onclick=\"submitplay($table->pid)\" href=\"?page=hdflvplaylist&pid=$table->pid#playlist_edit\" >" . stripslashes ( $table->playlist_name ) . "</td>\n";
				echo "<td><a title='Edit' href=?page=hdflvplaylist&pid=$table->pid#playlist_edit class=\"edit\">" . __ ( 'Edit' ) . "</a> |
                                                      <a title='Delete' href='?page=hdflvplaylist&pname=$table->playlist_name&did=$table->pid' class=\"delete\" onclick=\"javascript:check=confirm( '" . __ ( "Delete this playlist?", 'hdflv' ) . "');if(check==false) return false;\">" . __ ( 'Delete' ) . "</a></td>\n";
				echo "<td></td>";
				if ($table->is_pactive) {
					
					echo "<td  style = 'padding-left: 2%;' id='status$table->pid'><img  title='deactive' style='cursor:pointer;' onclick='setVideoStatusOff($table->pid,0,1)'  src=$pluginDirPath/hdflv_active.png /></td>\n";
				} else {
					
					echo "<td  style = 'padding-left: 2%;' id='status$table->pid'><img  title='active' style='cursor:pointer;' onclick='setVideoStatusOff($table->pid,1,1)' src=$pluginDirPath/hdflv_deactive.png /></td>\n";
				}
				echo '</tr>';
				$i ++;
			} // foreach end
		} 		// if end hear
		else {
			echo '<tr><td colspan="7" align="center"><b>' . __ ( 'No entries found', 'hdflv' ) . '</b></td></tr>';
		}
		?>
                                        </table>
		<input type="hidden" name="playid" id="playid" value="" />
	</form>
</div>
<?php
		$limit = 20;
		$pagenum = isset ( $_GET ['playpagenum'] ) ? absint ( $_GET ['playpagenum'] ) : 1;
		$total = $Playlist_count;
		$num_of_pages = ceil ( $total / $limit );
		if (isset ( $search ))
			$arr_params = array (
					"search_playlist" => "$search",
					"playpagenum" => "%#%" 
			);
		else
			$arr_params = array (
					'playpagenum' => '%#%' 
			);
		$page_links = paginate_links ( array (
				'base' => add_query_arg ( $arr_params ),
				'format' => '',
				'prev_text' => __ ( '&laquo;', 'aag' ),
				'next_text' => __ ( '&raquo;', 'aag' ),
				'total' => $num_of_pages,
				'current' => $pagenum 
		) );
		
		if ($page_links) {
			echo '<div class="tablenav" style=" margin-right: 15px; "><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
		}
		?>
<div class="wrap">
	<div id="poststuff" class="metabox-holder d">
		<div id="playlist_edit" class="stuffbox">
			<h3 style="cursor: none;"><?php
		$pid = filter_input ( INPUT_GET, 'pid' );
		if (! isset ( $pid ))
			echo _e ( 'Add Playlist', 'hdflv' );
		else
			echo _e ( 'Update Playlist', 'hdflv' );
		?></h3>
			<div class="inside">
				<form id="addplist"
					action="<?php echo $_SERVER['PHP_SELF'] . '?page=hdflvplaylist'; ?>"
					method="post">
					<input type="hidden" value="<?php echo $this->act_pid ?>"
						name="p_id" />
					<p><?php _e('Name:', 'hdflv'); ?><br /> <input type="text"
							value="<?php if(isset($update->playlist_name)) echo $update->playlist_name; ?>"
							name="p_name" id="p_name" />
					</p>
					<span style="color: red;" id="bind_playlist_error"></span>
					<div class="submit" style="margin: 0px; padding: 4px;">
<?php
		if (! isset ( $pid ))
			echo '<input type="submit"  name="add_playlist" value="' . __ ( 'Add Playlist', 'hdflv' ) . '" class="button-primary" onClick="return validatePlaylist();"/>';
		else
			echo '<input type="submit" name="update_playlist" value="' . __ ( 'Update Playlist', 'hdflv' ) . '" class="button-primary" />';
		?>
                                            <input type="button"
							onclick="window.location.href='?page=hdflvplaylist'"
							name="cancel" value="<?php _e('Cancel', 'hdflv'); ?>" />
					</div>
				</form>
				<script type="text/javascript">
                                        function validatePlaylist()
                                        {
                                            var testPlaylist;
                                            testPlaylist = document.getElementById('p_name').value;
                                            if(testPlaylist.trim()==''){
                                                document.getElementById("bind_playlist_error").innerHTML='Please enter playlist name';
                                                return false;
                                            }
                                        }
                                        function validatesearch()
                                        {
                                            search_playlist = document.getElementById('search_playlist').value;
                                            if(search_playlist.trim()==''){
                                                document.getElementById("bind_playlist_search_error").innerHTML='Please enter search keyword';
                                                document.getElementById("search_playlist").focus;
                                                return false;
                                            }
                                        }
                                    </script>
			</div>
		</div>
	</div>
</div>
<?php
	}
	function show_hdflvadsense() {
		global $wpdb;
		
		$videoadsense = filter_input ( INPUT_POST, 'videoadsense' );
		$videoadsense_id = filter_input ( INPUT_POST, 'id' );
		if (isset ( $videoadsense )) {
			$code = filter_input ( INPUT_POST, 'code' );
			$showoption = filter_input ( INPUT_POST, 'showoption' );
			$reopenadd = filter_input ( INPUT_POST, 'reopenadd' );
			$ropen = filter_input ( INPUT_POST, 'ropen' );
			$publish = filter_input ( INPUT_POST, 'publish' );
			$closeadd = filter_input ( INPUT_POST, 'closeadd' );
			$videoadData = array (
					'code' => $code,
					'showoption' => $showoption,
					'reopenadd' => $reopenadd,
					'ropen' => $ropen,
					'publish' => $publish,
					'closeadd' => $closeadd 
			);
			if (! empty ( $videoadsense_id )) { // update for video ad if starts
				
				$updateflag = $wpdb->update ( $wpdb->prefix . "hdflv_googlead", $videoadData, array (
						'id' => $videoadsense_id 
				) );
				
				if ($updateflag) {
					$this->admin_redirect ( "admin.php?page=hdflvadsense&mode=hdflvadsense&update=1" );
				} else {
					$this->admin_redirect ( "admin.php?page=hdflvadsense&mode=hdflvadsense&update=0" );
				}
			} 			// update for video ad if ends
			else { // adding video ad else starts
				if ($wpdb->insert ( $wpdb->prefix . "hdflv_googlead", $videoadData )) {
					$addflag = $wpdb->insert_id;
				}
				// echo "<pre>";print_r($wpdb);exit;
				if (! $addflag) {
					$this->admin_redirect ( "admin.php?page=hdflvadsense&mode=hdflvadsense&add=0" );
				} else {
					$this->admin_redirect ( "admin.php?page=hdflvadsense&mode=hdflvadsense&add=1" );
				}
			} // adding video ad else ends
		}
		
		$googleadDetails = $wpdb->get_row ( "SELECT * FROM " . $wpdb->prefix . "hdflv_googlead WHERE id =1" );
		
		?>
<div class="wrap">
<?php
		$this->haflvMenuTab ();
		?>
                                                <h2><?php _e('Add Google Adsense', 'hdflv'); ?></h2>
	<form action="" name="videoadsenseform" class="videoform" method="post"
		enctype="multipart/form-data">
		<table class="form-table">
			<tr>
				<th scope="row">Enter the Code:</th>
				<td><textarea rows="9" cols="40" style="width: 370px;" name="code"
						id="name"><?php
		if (isset ( $googleadDetails->code )) {
			echo stripslashes ( $googleadDetails->code );
		}
		?></textarea> <label> Default size 468 x 60 </label></td>
			</tr>
			<tr>
				<th scope="row">Option
				
				</td>
				<td><input type="radio" name="showoption" value="0" checked />Always
					Show&nbsp;&nbsp;<input type="radio" name="showoption" value=1
					<?php
		if ($googleadDetails->showoption == '1') {
			echo 'checked';
		}
		?> />Close After : <input type="text" name="closeadd"
					value="<?php echo $googleadDetails->closeadd; ?>" />&nbsp;Sec</td>
			</tr>
			<tr>
				<th scope="row">Reopen
				
				</td>
				<td><input type="checkbox" name="reopenadd" value="0"
					<?php
		if ($googleadDetails->reopenadd == '0') {
			echo 'checked';
		}
		?> />&nbsp;&nbsp;Re-open After : <input type="text" name="ropen"
					value="<?php
		echo $googleadDetails->ropen;
		?>" />&nbsp;Sec</td>
			</tr>
			<tr>
				<th scope="row">Published
				
				</td>
				<td><input type="radio" name="publish" value=1
					<?php
		if ($googleadDetails->publish == '1' || $googleadDetails->publish == '') {
			echo 'checked';
		}
		?> />Yes <input type="radio" name="publish" value="0"
					<?php
		if ($googleadDetails->publish == '0' && $googleadDetails->publish != '') {
			echo 'checked';
		}
		?> />No</td>
			</tr>

		</table>
		<input type="hidden" name="id"
			value="<?php	echo $googleadDetails->id;	?>" />
		<p>
			<input type="submit" name="videoadsense" class="button-primary"
				value="<?php _e('Add Adsnese', 'video_gallery'); ?>" class="button" />
		</p>
	</form>
</div>
<?php
	}
	function show_managevideoads() {
		global $wpdb;
		
		// get the tables
		$addeid = filter_input ( INPUT_GET, 'addeid' );
		$adid = filter_input ( INPUT_GET, 'adid' );
		$status = filter_input ( INPUT_GET, 'status' );
		$where = $sortind = $sortindt = '';
		$search_inputbox = filter_input ( INPUT_POST, 'search_ad' );
		if (isset ( $_GET ['search_ad'] )) {
			$search_query = $_GET ['search_ad'];
		}
		$search_input = (isset ( $search_inputbox )) ? $search_inputbox : (isset ( $search_query ) ? $search_query : '');
		
		// check for page navigation
		$search = (isset ( $search_input )) ? $search_input : '';
		$search = $this->phpSlashes ( $search );
		$search_results = $search;
		
		if ($search != '') {
			$where .= " WHERE";
			$where1 = "  (title LIKE '%$search_results%')";
			$kt = preg_split ( "/[\s,]+/", $search ); // Breaking the string to array of words
			                                     // Now let us generate the sql
			while ( list ( $key, $search ) = each ( $kt ) ) {
				if ($search != " " and strlen ( $search ) > 0) {
					$where1 = "  (title LIKE '%$search%')";
				}
			}
			$where .= $where1;
		}
		if (isset ( $addeid )) {
			$wpdb->query ( 'DELETE FROM ' . $wpdb->prefix . 'hdflv_vgads  WHERE ads_id IN (' . $addeid . ')' );
		}
		if (isset ( $status )) {
			$wpdb->update ( $wpdb->prefix . 'hdflv_vgads', array (
					'publish' => $status 
			), array (
					'ads_id' => $adid 
			) );
		}
		$pluginDirPath = content_url () . '/plugins/' . dirname ( plugin_basename ( __FILE__ ) ) . '/images';
		
		$pagenum = isset ( $_GET ['playpagenum'] ) ? absint ( $_GET ['playpagenum'] ) : 1;
		$limit = 20;
		$offset = ($pagenum - 1) * $limit;
		$getorderDirection = filter_input ( INPUT_GET, 'order' );
		$getorderBy = filter_input ( INPUT_GET, 'orderby' );
		
		switch ($getorderBy) {
			case 'id' :
				$order = 'ads_id';
				$sortind = 'id';
				break;
			
			case 'title' :
				$order = 'title';
				$sortindt = 'title';
				break;
			case 'publish' :
				$order = 'publish';
				break;
			case 'path' :
				$order = 'path';
				break;
			
			default :
				$order = 'ads_id';
				$sortind = 'id';
		}
		
		$gridVideoad = $wpdb->get_results ( "SELECT * FROM " . $wpdb->prefix . "hdflv_vgads $where order by $order $getorderDirection LIMIT $offset, $limit" );
		$ads_count = $wpdb->get_var ( "SELECT count(ads_id) FROM " . $wpdb->prefix . "hdflv_vgads $where" );
		?>
<!-- Manage Ads -->
<div class="wrap">
<?php
		$this->haflvMenuTab ();
		$tablehdflv_vgads = $wpdb->prefix . 'hdflv_vgads';
		$sql = "SHOW TABLES LIKE '$tablehdflv_vgads'"; // is table in DB or not
		$isTable = $wpdb->query ( $sql ); // if TRUE THEN 1 ELSE 0
		
		if ($isTable) { // yes already in DB so add fields only
			$sql = "SHOW COLUMNS FROM $tablehdflv_vgads";
			$numOfCol = $wpdb->query ( $sql );
			if ($numOfCol < 5) { // add one col like is_pactive
			}
		}
		$orderField = filter_input ( INPUT_GET, 'order' );
		$direction = isset ( $orderField ) ? $orderField : false;
		$reverse_direction = ($direction == 'DESC' ? 'ASC' : 'DESC');
		if ($search_results != '') {
			?>
        <div class="updated below-h2">

<?php
			$url = get_bloginfo ( 'url' ) . '/wp-admin/admin.php?page=hdflvvideoads&mode=managevideoads';
			if ($ads_count >= 1) {
				echo $ads_count . "   Search Result(s) for '" . $search_results . "'.&nbsp&nbsp&nbsp<a href='$url' >Back to Video ads List</a>";
			} else {
				echo " No Search Result(s) for '" . $search_results . "'.&nbsp&nbsp&nbsp<a href='$url' >Back to Video ads List</a>";
			}
			?>
    </div>    <?php } ?>

                                                <form id="editadlist"
		name="editadlist" method="post">
		<input type="hidden" id="imagepath" name="imagepath"
			value="<?php echo $pluginDirPath; ?>" />
		<div style="margin: 23px 0px 10px 1px;">

			<p class="search-box" style="margin-bottom: 10px;">
				<input type="text" class="search-input" id="search_ad"
					name="search_ad" value="<?php echo $search; ?>" size="10" /> <input
					type="submit" class="button-secondary"
					onclick="return validateadsearch();"
					value="<?php _e('Search Video ad', 'hdflv'); ?>" /> <input
					type="hidden" name="cancel" value="2" /> <span style="color: red;"
					id="bind_ad_search_error"></span>
			</p>
			<script type="text/javascript">
                                        function validateadsearch()
                                        {
                                            search_ad = document.getElementById('search_ad').value;
                                            if(search_ad.trim()==''){
                                                document.getElementById("bind_ad_search_error").innerHTML='Please enter search keyword';
                                                document.getElementById("search_ad").focus;
                                                return false;
                                            }
                                        }
                                    </script>
		</div>
		<table class="widefat" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" class="manage-column column-cb check-column"
						style=""><input type="checkbox" name=""></th>
					<th style="padding-left: 2%;" scope="col"><a
						href="<?php echo $_SERVER['PHP_SELF'] . '?page=hdflvvideoads&mode=managevideoads&orderby=id&order='.$reverse_direction; ?>"
						class="video_cat_id"> <span><?php _e('Ad ID', 'hdflv'); ?></span>
							<span
							class="sortingindicator<?php echo $sortind.$reverse_direction; ?>"></span>
					</a></th>
					<th scope="col"><a
						href="<?php echo $_SERVER['PHP_SELF'] . '?page=hdflvvideoads&mode=managevideoads&orderby=title&order='.$reverse_direction; ?>"
						class="video_cat_title"> <span><?php _e('Title', 'hdflv'); ?></span>
							<span
							class="sortingindicator<?php echo $sortindt.$reverse_direction; ?>"></span>
					</a></th>
					</th>
					<th scope="col" class="manage-column column-name sortable desc"
						style=""><a
						href="<?php echo get_site_url()?>/wp-admin/admin.php?page=hdflvvideoads&mode=managevideoads&orderby=path&order=<?php echo $reverse_direction; ?>">
							<span><?php _e('Path', 'video_gallery'); ?></span> <span
							class="sorting-indicator"></span>
					</a></th>
					<th scope="col"><?php _e('Action'); ?></th>
					<th scope="col"
						class="manage-column column-description sortable desc" style=""><span><?php _e('Ad Type', 'video_gallery'); ?></span>
					</th>
					<th scope="col"
						class="manage-column column-description sortable desc" style=""><span><?php _e('Ad Method', 'video_gallery'); ?></span>
					</th>
					<th scope="col"
						class="manage-column column-description sortable desc" style=""><a
						href="<?php echo get_site_url()?>/wp-admin/admin.php?page=hdflvvideoads&mode=managevideoads&orderby=publish&order=<?php echo $reverse_direction; ?>"><span><?php _e('Publish', 'video_gallery'); ?></span>
							<span class="sorting-indicator"></span> </a></th>
				</tr>
			</thead>
<?php
		foreach ( $gridVideoad as $videoAdview ) {
			?>
                <tr>
				<th scope="row" class="check-column"><input type="checkbox"
					name="videoad_id[]" value="<?php echo $videoAdview->ads_id; ?>"></th>
				<td style="text-align: center;"><a
					title="Edit <?php echo $videoAdview->title; ?>"
					href="<?php echo get_site_url()?>/wp-admin/admin.php?page=hdflvvideoads&mode=videoads&adid=<?php echo $videoAdview->ads_id;  ?>#videoad_edit"><?php echo $videoAdview->ads_id;?></a>
				<div class="row-actions"></td>
				<td><a title="Edit <?php echo $videoAdview->title; ?>"
					class="row-title"
					href="<?php echo get_site_url()?>/wp-admin/admin.php?page=hdflvvideoads&mode=videoads&adid=<?php echo $videoAdview->ads_id;  ?>#videoad_edit"><?php echo  $videoAdview->title; ?></a>
				</td>
				<td class="description column-description">
                        <?php if($videoAdview->admethod == "prepost") echo $videoAdview->file_path; ?>
						<?php
			
if ($videoAdview->admethod == "imaad") {
				if (strlen ( $videoAdview->imaadpath ) > 65) {
					echo substr ( $videoAdview->imaadpath, 0, 65 ) . '..';
				} else {
					echo $videoAdview->imaadpath;
				}
			}
			?>
                    </td>
				<td><a title='Delete'
					href='?page=hdflvvideoads&mode=managevideoads&addeid=<?php echo $videoAdview->ads_id;  ?>'
					class="delete"
					onclick="javascript:check=confirm( '<?php _e('Delete this playlist?', 'hdflv'); ?>');if(check==false) return false;"><?php _e('Delete'); ?></a></td>
					
                        <?php
			
$status = 1;
			$image = "hdflv_deactive.png";
			$publish = "Click here to Activate";
			if ($videoAdview->publish == 1) {
				$status = 0;
				$image = "hdflv_active.png";
				$publish = "Click here to Deactivate";
			}
			?>
                    <td>
                        <?php echo  $videoAdview->admethod; ?>
                    </td>
				<td>
                        <?php if($videoAdview->admethod != "midroll") echo  $videoAdview->adtype; ?>
                    </td>
				<td class="description column-description"><a
					href="<?php echo get_site_url()?>/wp-admin/admin.php?page=hdflvvideoads&mode=managevideoads&adid=<?php echo $videoAdview->ads_id;  ?>&status=<?php echo $status;?>">
						<img
						src="<?php  echo content_url() . '/plugins/' . dirname(plugin_basename(__FILE__)) . '/images/'.$image ?>"
						title="<?php echo $publish;?>" title="<?php echo $publish;?>" />
				</a></td>
			</tr>
             <?php
		}
		if (count ( $gridVideoad ) == 0) {
			?>
        <tr class="no-items">
				<td class="colspanchange" colspan="5">No video ad found.</td>
			</tr>
            <?php
		}
		?>
                                        </table>
		<input type="hidden" name="playid" id="playid" value="" />
	</form>
</div>
<?php
		$limit = 20;
		$pagenum = isset ( $_GET ['playpagenum'] ) ? absint ( $_GET ['playpagenum'] ) : 1;
		$total = $ads_count;
		$num_of_pages = ceil ( $total / $limit );
		if (isset ( $search ))
			$arr_params = array (
					"search_ad" => "$search",
					"playpagenum" => "%#%" 
			);
		else
			$arr_params = array (
					'playpagenum' => '%#%' 
			);
		$page_links = paginate_links ( array (
				'base' => add_query_arg ( $arr_params ),
				'format' => '',
				'prev_text' => __ ( '&laquo;', 'aag' ),
				'next_text' => __ ( '&raquo;', 'aag' ),
				'total' => $num_of_pages,
				'current' => $pagenum 
		) );
		
		if ($page_links) {
			echo '<div class="tablenav" style=" margin-right: 15px; "><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
		}
		?>
                                
<?php
	}
	public function admin_redirect($url) {
		echo "<script>window.open('" . $url . "','_top',false)</script>";
	}
	function show_videoads() {
		global $wpdb;
		$addnewVideoad = filter_input ( INPUT_POST, 'videoadsadd' );
		$videoadId = filter_input ( INPUT_GET, 'adid' );
		if (isset ( $addnewVideoad )) {
			$videoadName = $videoadwidth = $videoadheight = $videoimaadpath = $publisherId = $contentId = $imaadType = $channels = $description = $targeturl = $clickurl = $impressionurl = $admethod = $adtype = $videoadFilepath = $videoadPublish = '';
			$videoadName = filter_input ( INPUT_POST, 'videoadname' );
			$videoadwidth = filter_input ( INPUT_POST, 'videoimaadwidth' );
			$videoadheight = filter_input ( INPUT_POST, 'videoimaadheight' );
			$videoimaadpath = filter_input ( INPUT_POST, 'imaadpath' );
			$publisherId = filter_input ( INPUT_POST, 'publisherId' );
			$contentId = filter_input ( INPUT_POST, 'contentId' );
			$imaadType = filter_input ( INPUT_POST, 'imaadType' );
			$channels = filter_input ( INPUT_POST, 'channels' );
			$description = filter_input ( INPUT_POST, 'description' );
			$targeturl = filter_input ( INPUT_POST, 'targeturl' );
			$clickurl = filter_input ( INPUT_POST, 'clickurl' );
			$impressionurl = filter_input ( INPUT_POST, 'impressionurl' );
			$admethod = filter_input ( INPUT_POST, 'admethod' );
			$adtype = filter_input ( INPUT_POST, 'adtype' );
			$videoadFilepath = filter_input ( INPUT_POST, 'normalvideoform-value' );
			$dir = dirname ( plugin_basename ( __FILE__ ) );
			$dirExp = explode ( '/', $dir );
			$dirPage = $dirExp [0];
			if (empty ( $videoadFilepath ))
				$videoadFilepath = filter_input ( INPUT_POST, 'videoadfilepath' );
			else {
				$videoadFilepath = $this->wp_urlpath . '/' . $videoadFilepath;
			}
			$videoadPublish = filter_input ( INPUT_POST, 'videoadpublish' );
			
			$videoadData = array (
					'title' => $videoadName,
					'description' => $description,
					'targeturl' => $targeturl,
					'clickurl' => $clickurl,
					'impressionurl' => $impressionurl,
					'file_path' => $videoadFilepath,
					'adtype' => $adtype,
					'admethod' => $admethod,
					'imaadwidth' => $videoadwidth,
					'imaadheight' => $videoadheight,
					'imaadpath' => $videoimaadpath,
					'publisherId' => $publisherId,
					'contentId' => $contentId,
					'imaadType' => $imaadType,
					'channels' => $channels,
					'publish' => $videoadPublish 
			);
			
			$videoadDataformat = array (
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%d',
					'%d',
					'%s',
					'%s',
					'%s',
					'%d',
					'%s',
					'%d' 
			);
			
			if (isset ( $videoadId )) { // update for video ad if starts
				
				$updateflag = $wpdb->update ( $wpdb->prefix . "hdflv_vgads", $videoadData, array (
						'ads_id' => $videoadId 
				), $videoadDataformat );
				
				if ($updateflag) {
					$this->admin_redirect ( "admin.php?page=hdflvvideoads&mode=videoads&adid=" . $videoadId . "&update=1" );
				} else {
					$this->admin_redirect ( "admin.php?page=hdflvvideoads&mode=videoads&adid=" . $videoadId . "&update=0" );
				}
			} 			// update for video ad if ends
			else { // adding video ad else starts
				if ($wpdb->insert ( $wpdb->prefix . "hdflv_vgads", $videoadData, $videoadDataformat )) {
					$addflag = $wpdb->insert_id;
				}
				
				if (! $addflag) {
					$this->admin_redirect ( "admin.php?page=hdflvvideoads&mode=managevideoads&add=0" );
				} else {
					$this->admin_redirect ( "admin.php?page=hdflvvideoads&mode=managevideoads&add=1" );
				}
			} // adding video ad else ends
		}
		
		$videoadEdit = $wpdb->get_row ( "SELECT * FROM " . $wpdb->prefix . "hdflv_vgads WHERE ads_id ='$videoadId'" );
		$dir = dirname ( plugin_basename ( __FILE__ ) );
		$dirExp = explode ( '/', $dir );
		$dirPage = $dirExp [0];
		$uploads_path = str_replace ( site_url () . '/', '', wp_upload_dir () );
		if (isset ( $videoadEdit->file_path ) && ! strstr ( $videoadEdit->file_path, $uploads_path )) {
			$uploaded_video = 0;
		} else {
			$uploaded_video = 1;
		}
		$this->haflvMenuTab ();
		?>

<div class="wrap">
	<div id="poststuff" class="has-sidebar">
		<div id="post-body-content" class="has-sidebar-content">
			<div class="stuffbox">


				<h3 class="hndle videoform_title">
					<span> <input type="radio" name="videoadtype" id="prepostroll"
						value="1"
						<?php
		if (isset ( $videoadEdit ) && $videoadEdit->admethod == 'prepost') {
			echo 'checked="checked" ';
		}
		?>
						onClick="Videoadtype('prepostroll')" /> Pre-roll/Post-roll Ad
					</span> <span> <input type="radio" name="videoadtype" id="midroll"
						value="2"
						<?php
		if (isset ( $videoadEdit ) && $videoadEdit->admethod == 'midroll') {
			echo 'checked="checked" ';
		}
		?>
						onClick="Videoadtype('midroll')" /> Mid-roll Ad
					</span> <span> <input type="radio" name="videoadtype" id="imaad"
						value="3"
						<?php
		if (isset ( $videoadEdit ) && $videoadEdit->admethod == 'imaad') {
			echo 'checked="checked" ';
		}
		?>
						onClick="Videoadtype('imaad')" /> IMA Ad
					</span>
				</h3>
				<table class="form-table">
					<tr id="videoadmethod" name="videoadmethod">
						<td width="150"><?php _e('Select file type', 'video_gallery') ?></td>
						<td><input type="radio" name="videoad" id="filebtn" value="1"
							onClick="Videoadtypemethod('fileuplo');" /> File <input
							type="radio" name="videoad" id="urlbtn" value="2"
							onClick="Videoadtypemethod('urlad');" /> URL</td>
					</tr>
				</table>
				<div id="upload2" class="form-table">

					<table class="form-table">

						<tr id="ffmpeg_disable_new1" name="ffmpeg_disable_new1">
							<td width="150"><?php _e('Upload Video', 'video_gallery') ?></td>
							<td>
								<div id="f1-upload-form">
									<form name="normalvideoform" method="post"
										enctype="multipart/form-data">
										<input type="file" name="myfile"
											onchange="enableUpload(this.form.name);" /> <input
											type="button" class="button" name="uploadBtn"
											value="<?php _e('Upload Video', 'video_gallery') ?>"
											disabled="disabled"
											onclick="return addQueue(this.form.name,this.form.myfile.value);" />
										<input type="hidden" name="mode" value="video" /> <label
											id="lbl_normal"><?php echo (isset($videoadEdit->file_path)  && $uploaded_video == 1) ? $videoadEdit->file_path : ""; ?></label>
									</form>
                                    <?php _e('<b>Supported video formats:</b>( MP4, M4V, M4A, MOV, Mp4v or F4V)', 'video_gallery')?>
                                </div> <span id="uploadmessage"
								style="display: block; margin-top: 10px; margin-left: 300px; color: red; font-size: 12px; font-weight: bold;"></span>
								<div id="f1-upload-progress" style="display: none">
									<div style="float: left">
										<img id="f1-upload-image"
											src="<?php echo content_url() . '/plugins/'.$dirPage.'/images/empty.gif' ?>"
											alt="Uploading" style="padding-top: 2px" /> <label
											style="padding-top: 0px; padding-left: 4px; font-size: 14px; font-weight: bold; vertical-align: top"
											id="f1-upload-filename">PostRoll.flv</label>
									</div>
									<div style="float: right">
										<span id="f1-upload-cancel"> <a
											style="float: right; padding-right: 10px;"
											href="javascript:cancelUpload('normalvideoform');"
											name="submitcancel">Cancel</a>
										</span> <label id="f1-upload-status"
											style="float: right; padding-right: 40px; padding-left: 20px;">Uploading</label>
										<span id="f1-upload-message"
											style="float: right; font-size: 10px; background: #FFAFAE;">
											<b><?php _e('Upload Failed:', 'video_gallery') ?></b> <?php _e('User Cancelled the upload', 'video_gallery')?>
                                        </span>
									</div>


								</div>
								<div id="nor">
									<iframe id="uploadvideo_target" name="uploadvideo_target"
										src="#" style="width: 0; height: 0; border: 0px solid #fff;"></iframe>
								</div> <span id="upload_video_message"
								style="display: block; color: red;"></span>
							</td>
						</tr>
					</table>
				</div>
				<form action="" name="videoadsform" class="videoform" method="post"
					enctype="multipart/form-data">
					<div id="videoadurl" style="display: none;">
						<table class="form-table">
							<tr>
								<td scope="row" width="150"><?php _e('Video Ad URL', 'video_gallery') ?></td>
								<td><input type="text" size="50" onchange="clear_upload();"
									onkeyup="validateerrormsg();" name="videoadfilepath"
									id="videoadfilepath"
									value="<?php echo (isset($videoadEdit->file_path)) ? $videoadEdit->file_path : ""; ?>" />&nbsp;&nbsp
									<br /><?php _e('Here you need to enter the video ad URL', 'video_gallery')?>
                                    <br /><?php _e('It accept also a Youtube link : http://www.youtube.com/watch?v=tTGHCRUdlBs', 'video_gallery')?>
                                <span id="filepatherrormessage"
									style="display: block; color: red;"></span></td>
							</tr>
						</table>
					</div>
					<table id="videoimaaddetails" style="display: none;"
						class="form-table">
						<tr>
							<td scope="row" width="150"><?php _e('IMA Ad Type', 'video_gallery') ?></td>
							<td><input type="radio" name="imaadType" id="imaadTypetext"
								onclick="changeimaadtype('textad');" value="1"
								<?php
		if (isset ( $videoadEdit->imaadType ) && $videoadEdit->imaadType == 1) {
			echo "checked";
		}
		?>><label>Text/Overlay</label> <input
								type="radio" name="imaadType" id="imaadTypevideo"
								onclick="changeimaadtype('videoad');" value="0"
								<?php
		if (isset ( $videoadEdit->imaadType ) && $videoadEdit->imaadType == 0) {
			echo "checked";
		}
		?>><label>Video</label>
						
						</tr>
						<tr id="adimapath" style="display: none;">
							<td scope="row" width="150"><?php _e('IMA Ad Path', 'video_gallery') ?></td>
							<td><input type="text" size="50" onkeyup="validateerrormsg();"
								name="imaadpath" id="imaadpath"
								value="<?php if(isset($videoadEdit->imaadpath)){ echo $videoadEdit->imaadpath; }else{ echo '';} ?>" />
								<span id="imaadpatherrormessage"
								style="display: block; color: red;"></span></td>
						</tr>
						<tr id="adimawidth" style="display: none;">
							<td scope="row" width="150"><?php _e('Ad Slot Width', 'video_gallery') ?></td>
							<td><input type="text" size="50" name="videoimaadwidth"
								id="adwidth"
								value="<?php echo (isset($videoadEdit->imaadwidth)) ? $videoadEdit->imaadwidth : ""; ?>" />
							</td>
						</tr>
						<tr id="adimaheight" style="display: none;">
							<td scope="row" width="150"><?php _e('Ad Slot Height', 'video_gallery') ?></td>
							<td><input type="text" size="50" name="videoimaadheight"
								id="adheight"
								value="<?php echo (isset($videoadEdit->imaadheight)) ? $videoadEdit->imaadheight : ""; ?>" />
							</td>
						</tr>
						<tr id="adimapublisher" style="display: none;">
							<td scope="row" width="150"><?php _e('Publisher ID', 'video_gallery') ?></td>
							<td><input type="text" size="50" onkeyup="validateerrormsg();"
								name="publisherId" id="publisherId"
								value="<?php echo (isset($videoadEdit->publisherId)) ? $videoadEdit->publisherId : ''; ?>" />
								<span id="imapublisherIderrormessage"
								style="display: block; color: red;"></span></td>
						</tr>
						<tr id="adimacontentid" style="display: none;">
							<td scope="row" width="150"><?php _e('Content ID', 'video_gallery') ?></td>
							<td><input type="text" size="50" name="contentId"
								onkeyup="validateerrormsg();" id="contentId"
								value="<?php echo (isset($videoadEdit->contentId)) ? $videoadEdit->contentId : ''; ?>" />
								<span id="imacontentIderrormessage"
								style="display: block; color: red;"></span></td>
						</tr>

						<tr id="adimachannels" style="display: none;">
							<td scope="row" width="150"><?php _e('Channels', 'video_gallery') ?></td>
							<td><input type="text" size="50" onkeyup="validateerrormsg();"
								name="channels" id="channels"
								value="<?php echo (isset($videoadEdit->channels)) ? $videoadEdit->channels : ''; ?>" />
								<span id="imachannelserrormessage"
								style="display: block; color: red;"></span></td>
						</tr>
					</table>
					<table id="videoaddetails" style="display: none;"
						class="form-table">
						<tr id="adtitle" style="display: none;">
							<td scope="row" width="150"><?php _e('Title / Name', 'video_gallery') ?></td>
							<td><input type="text" size="50" onkeyup="validateerrormsg();"
								maxlength="200" name="videoadname" id="name"
								value="<?php echo (isset($videoadEdit->title)) ? $videoadEdit->title : ""; ?>" />
								<span id="nameerrormessage" style="display: block; color: red;"></span>
							</td>
						</tr>
						<tr id="addescription" style="display: none;">
							<td scope="row" width="150"><?php _e('Description', 'video_gallery') ?></td>
							<td><input type="text" size="50" name="description"
								id="description"
								value="<?php echo (isset($videoadEdit->description)) ? $videoadEdit->description : ""; ?>" />
							</td>
						</tr>
						<tr id="adtargeturl" style="display: none;">
							<td scope="row" width="150"><?php _e('Target URL', 'video_gallery') ?></td>
							<td><input type="text" size="50" name="targeturl" id="targeturl"
								value="<?php echo (isset($videoadEdit->targeturl)) ? $videoadEdit->targeturl : ''; ?>" />
								<span id="targeterrormessage"
								style="display: block; color: red;"></span></td>
						</tr>
						<tr id="adclickurl" style="display: none;">
							<td scope="row" width="150"><?php _e('Click Hits URL', 'video_gallery') ?></td>
							<td><input type="text" size="50" name="clickurl" id="clickurl"
								value="<?php echo (isset($videoadEdit->clickurl)) ? $videoadEdit->clickurl : ''; ?>" />
								<span id="clickerrormessage" style="display: block; color: red;"></span>
							</td>
						</tr>
						<tr id="adimpresurl" style="display: none;">
							<td scope="row" width="150"><?php _e('Impression Hits URL', 'video_gallery') ?></td>
							<td><input type="text" size="50" name="impressionurl"
								id="impressionurl"
								value="<?php echo (isset($videoadEdit->impressionurl)) ? $videoadEdit->impressionurl : ''; ?>" />
								<span id="impressionerrormessage"
								style="display: block; color: red;"></span></td>
						</tr>
					</table>



					<table class="form-table add_video_publish">
						<tr>
							<td scope="row" width="150"><?php _e('Publish', 'video_gallery') ?></td>
							<td class="checkbox">

                                <?php //echo $act_feature;   ?>
                                <input type="radio"
								name="videoadpublish" value="1"
								<?php
		if (isset ( $videoadEdit->publish )) {
			echo "checked";
		}
		?>><label>Yes</label> <input
								type="radio" name="videoadpublish" value="0"
								<?php
		if (! isset ( $videoadEdit->publish )) {
			echo "checked";
		}
		?>><label>No</label>

							</td>
						</tr>
					</table>
					<p>
<?php if (isset($videoadId)) { ?>
					<input type="submit" name="videoadsadd" class="button-primary"
							onclick="return validateadInput();"
							value="<?php _e('Update Video Ad', 'video_gallery'); ?>"
							class="button" /> <?php } else { ?> <input type="submit"
							name="videoadsadd" class="button-primary"
							onclick="return validateadInput();"
							value="<?php _e('Add Video Ad', 'video_gallery'); ?>"
							class="button" /> <?php } ?>
                                    <input type="button"
							onclick="window.location.href='admin.php?page=videoads'"
							class="button-secondary" name="cancel"
							value="<?php _e('Cancel'); ?>" class="button" />
					</p>
					<input type="hidden" name="normalvideoform-value"
						id="normalvideoform-value"
						value="<?php if(isset($videoadEdit->file_path) && $uploaded_video == 1) { $videoadEdit->file_path; } else{ echo ""; } ?>" />
					<input type="hidden" name="admethod" id="admethod"
						value="<?php echo (isset($videoadEdit->admethod)) ? $videoadEdit->admethod : ""; ?>" />
					<input type="hidden" name="adtype" id="adtype"
						value="<?php echo (isset($videoadEdit->adtype)) ? $videoadEdit->adtype : ""; ?>" />
				</form>

			</div>
		</div>
	</div>
	<script type="text/javascript">
<?php
		
		if (isset ( $videoadEdit->file_path ) && $uploaded_video == 1) {
			?>
document.getElementById("filebtn").checked = true;
Videoadtypemethod('fileuplo');
<?php
		} else {
			?>
document.getElementById("urlbtn").checked = true;
Videoadtypemethod('urlad');
<?php
		}
		if (isset ( $videoadEdit->admethod ) && $videoadEdit->admethod == 'midroll') {
			?>
document.getElementById("midroll").checked = true;
Videoadtype('midroll');
<?php
		} else if (isset ( $videoadEdit->admethod ) && $videoadEdit->admethod == 'imaad') {
			?>
document.getElementById("imaad").checked = true;
Videoadtype('imaad');
<?php
		} else {
			?>
document.getElementById("prepostroll").checked = true;
Videoadtype('prepostroll');
<?php
		}
		if (! empty ( $videoadEdit->imaadpath )) {
			?> changeimaadtype('videoad');<?php
		} else {
			?> changeimaadtype('textad'); <?php
		}
		?>
										function Videoadtype(adtype)
{
    if (adtype === "prepostroll")
    {
        document.getElementById('admethod').value = "prepost";
        document.getElementById('videoadmethod').style.display = "block";
        document.getElementById('videoaddetails').style.display = "block";
        document.getElementById('adimpresurl').style.display = "block";
        document.getElementById('adclickurl').style.display = "block";
        document.getElementById('adtargeturl').style.display = "block";
        document.getElementById('addescription').style.display = "block";
        document.getElementById('adtitle').style.display = "block";
        document.getElementById('videoimaaddetails').style.display = "none";
		<?php if (isset($videoadEdit->file_path) && $uploaded_video != 1) { ?>
        Videoadtypemethod('urlad');
		<?php } ?>
    }

    if (adtype === "midroll")
    {
        document.getElementById('upload2').style.display = "none";
        document.getElementById('videoadmethod').style.display = "none";
        document.getElementById('admethod').value = "midroll";
        document.getElementById('videoadurl').style.display = "none";
        document.getElementById('videoaddetails').style.display = "block";
        document.getElementById('adimpresurl').style.display = "block";
        document.getElementById('adclickurl').style.display = "block";
        document.getElementById('adtargeturl').style.display = "block";
        document.getElementById('addescription').style.display = "block";
        document.getElementById('adtitle').style.display = "block";
        document.getElementById('videoimaaddetails').style.display = "none";
    }
    else if (adtype === "imaad")
    {
        document.getElementById('upload2').style.display = "none";
        document.getElementById('videoadmethod').style.display = "none";
        document.getElementById('admethod').value = "imaad";
        document.getElementById('videoadurl').style.display = "none";
        document.getElementById('videoaddetails').style.display = "block";
        document.getElementById('videoimaaddetails').style.display = "block";
        document.getElementById('adimpresurl').style.display = "none";
        document.getElementById('adclickurl').style.display = "none";
        document.getElementById('adtargeturl').style.display = "none";
        document.getElementById('addescription').style.display = "none";
        document.getElementById('adtitle').style.display = "";
        document.getElementById('imaadTypevideo').checked = true;
        changeimaadtype('videoad');
    }


}
function Videoadtypemethod(adtype)
{
    if (adtype === "fileuplo")
    {
        document.getElementById('upload2').style.display = "block";
        document.getElementById('videoadurl').style.display = "none";
        document.getElementById('adtype').style.display = "file";
    }

    else if (adtype === "urlad")
    {
        document.getElementById('upload2').style.display = "none";
        document.getElementById('videoadurl').style.display = "block";
        document.getElementById('adtype').value = "url";
    }


}
function changeimaadtype(adtype)
{
    if (adtype === "textad")
    {
        document.getElementById('adimapath').style.display = "none";
        document.getElementById('adimawidth').style.display = "";
        document.getElementById('adimaheight').style.display = "";
        document.getElementById('adimapublisher').style.display = "";
        document.getElementById('adimacontentid').style.display = "";
        document.getElementById('adimachannels').style.display = "";
        document.getElementById('imaadTypetext').checked = true;
    }

    else if (adtype === "videoad")
    {
        document.getElementById('adimapath').style.display = "";
        document.getElementById('adimawidth').style.display = "none";
        document.getElementById('adimaheight').style.display = "none";
        document.getElementById('adimapublisher').style.display = "none";
        document.getElementById('adimacontentid').style.display = "none";
        document.getElementById('adimachannels').style.display = "none";
        document.getElementById('imaadTypevideo').checked = true;
    }
}

function validateadInput() {
    var tomatch = /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/;
    if (document.getElementById('targeturl').value !== '') {
        var thevideoadurl = document.getElementById("targeturl").value;
        if (!tomatch.test(thevideoadurl))
        {
            document.getElementById('targeterrormessage').innerHTML = 'Enter Valid Target URL';
            document.getElementById("targeturl").focus();
            return false;
        }
    }
    if (document.getElementById('clickurl').value !== '') {
        var thevideoadurl = document.getElementById("clickurl").value;
        if (!tomatch.test(thevideoadurl))
        {
            document.getElementById('clickerrormessage').innerHTML = 'Enter Valid Target URL';
            document.getElementById("clickurl").focus();
            return false;
        }
    }
    if (document.getElementById('impressionurl').value !== '') {
        var thevideoadurl = document.getElementById("impressionurl").value;
        if (!tomatch.test(thevideoadurl))
        {
            document.getElementById('impressionerrormessage').innerHTML = 'Enter Valid Target URL';
            document.getElementById("impressionurl").focus();
            return false;
        }
    }

    if (document.getElementById('prepostroll').checked === true)
    {
        if (document.getElementById('filebtn').checked === true && document.getElementById('normalvideoform-value').value === '')
        {
            document.getElementById('filepathuploaderrormessage').innerHTML = 'Upload file for Ad';
            return false;
        } else if (document.getElementById('urlbtn').checked === true)
        {
            if (document.getElementById('videoadfilepath').value === '') {
                document.getElementById('filepatherrormessage').innerHTML = 'Enter Ad URL';
                document.getElementById('videoadfilepath').focus();
                return false;
            } else {
                var thevideoadurl = document.getElementById("videoadfilepath").value;
                var tomatch = /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/;
                if (!tomatch.test(thevideoadurl))
                {
                    document.getElementById('filepatherrormessage').style.display = 'block';
                    document.getElementById('filepatherrormessage').innerHTML = 'Enter Valid Ad URL';
                    document.getElementById("videoadfilepath").focus();
                    return false;
                }
            }
        }
        if (document.getElementById('name').value === '') {
            document.getElementById('nameerrormessage').style.display = "block";
            document.getElementById('nameerrormessage').innerHTML = 'Enter Ad Name';
            document.getElementById('name').focus();
            return false;
        }
    } if (document.getElementById('imaad').checked === true) {
        if (document.getElementById('imaadTypetext').checked === true && document.getElementById('publisherId').value === '')
        {
            document.getElementById('imapublisherIderrormessage').innerHTML = 'Enter IMA Ad Publisher ID';
            document.getElementById('publisherId').focus();
            return false;

        } else if (document.getElementById('imaadTypetext').checked === true && document.getElementById('contentId').value === '')
        {
            document.getElementById('imacontentIderrormessage').innerHTML = 'Enter IMA Ad Content ID';
            document.getElementById('contentId').focus();
            return false;

        } else if (document.getElementById('imaadTypetext').checked === true && document.getElementById('channels').value === '')
        {
            document.getElementById('imachannelserrormessage').innerHTML = 'Enter IMA Ad Channel';
            document.getElementById('channels').focus();
            return false;

        } else {
            if (document.getElementById('imaadTypevideo').checked === true)
            {
                if (document.getElementById('imaadpath').value === '') {
                    document.getElementById('imaadpatherrormessage').innerHTML = 'Enter IMA Ad Path';
                    document.getElementById('imaadpath').focus();
                    return false;
                } else {
                    var thevideoadurl = document.getElementById("imaadpath").value;
                    var tomatch = /(http:\/\/|https:\/\/)[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}|(http:\/\/|https:\/\/)/;
                    if (!tomatch.test(thevideoadurl))
                    {
                        document.getElementById('imaadpatherrormessage').innerHTML = 'Enter Valid IMA Ad URL';
                        document.getElementById("imaadpath").focus();
                        return false;
                    }
                }

            }
        }

    } if (document.getElementById('name').value === '') {
        document.getElementById('nameerrormessage').style.display = "block";
        document.getElementById('nameerrormessage').innerHTML = 'Enter Ad Name';
        document.getElementById('name').focus();
        return false;

    }
}
</script>
</div>
<?php
	}
	
	// Display sort form filter
	// Display playlist form filter
	function playlist_filter($plfilter) {
		global $wpdb;
		?>
<select name="plfilter">
	<option value='0'>All</option>
<?php
		$dbresults = $wpdb->get_results ( " SELECT * FROM " . $wpdb->prefix . "hdflv_playlist where is_pactive=1" );
		if ($dbresults) {
			foreach ( $dbresults as $dbresult ) :
				echo '<option value="' . $dbresult->pid . '"';
				if ($plfilter == $dbresult->pid)
					echo 'selected="selected"';
				echo '>' . $dbresult->playlist_name . '</option>';
			endforeach
			;
		}
		?>
                                </select>
<?php
	}
}
?>
<input type="hidden" name="app_wp_token" id="app_wp_token"
	value="<?php echo $_SESSION['app_wp_token']; ?>" />
<input type="hidden" name="plugin_name" id="plugin_name"
	value="<?php echo $contus; ?>" />