<?php
/*
 * Name: WP Flash Player Plugin URI: http://www.apptha.com/category/extension/Wordpress/HD-FLV-Player-Plugin/ Description: video upload file. Version: 1.3 Author: Apptha Author URI: http://www.apptha.com License: GPL2
 */
@session_start ();
$sessionToken = $_SESSION ['app_wp_token'];
$reqToken = trim ( $_REQUEST ["hdflv_token"] );

if ($sessionToken != $reqToken) {
	die ( "You are not authorized to access this file" );
}

require_once (dirname ( __FILE__ ) . '/hdflv-config.php');

$errormsg = array ();
$file_name = '';
$error = "";
$errorcode = 12; // erros messages use when if upload is failed then we show this mess
$errormsg [0] = "<b>Upload Success:</b> File Uploaded Successfully";
$errormsg [1] = "<b>Upload Cancelled:</b> Cancelled by user111";
$errormsg [2] = "<b>Upload Failed:</b> Invalid File type specified";
$errormsg [3] = "<b>Upload Failed:</b> Your File Exceeds Server Limit size";
$errormsg [4] = "<b>Upload Failed:</b> Unknown Error Occured";
$errormsg [5] = "<b>Upload Failed:</b> The uploaded file exceeds the upload_max_filesize directive in php.ini";
$errormsg [6] = "<b>Upload Failed:</b> The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
$errormsg [7] = "<b>Upload Failed:</b> The uploaded file was only partially uploaded";
$errormsg [8] = "<b>Upload Failed:</b> No file was uploaded";
$errormsg [9] = "<b>Upload Failed:</b> Missing a temporary folder";
$errormsg [10] = "<b>Upload Failed:</b> Failed to write file to disk";
$errormsg [11] = "<b>Upload Failed:</b> File upload stopped by extension";
$errormsg [12] = "<b>Upload Failed:</b> Unknown upload error.";
$errormsg [13] = "<b>Upload Failed:</b> Please check post_max_size in php.ini settings";

$error_f = filter_input ( INPUT_GET, 'error' );
$processing_f = filter_input ( INPUT_GET, 'processing' );

$mode_f = filter_input ( INPUT_POST, 'mode' );

if (isset ( $error_f )) {
	$error = $error_f; // its is use to cancel uploading if any error is find
}

if (isset ( $processing_f )) {
	$pro = $processing_f;
}

if (isset ( $mode_f )) {
	
	$exttype = $mode_f;
	if ($exttype == 'video') // for videos upload
		$allowedExtensions = array (
				"flv",
				"FLV",
				"mp4",
				"MP4",
				"m4v",
				"M4V",
				"M4A",
				"m4a",
				"MOV",
				"mov",
				"mp4v",
				"Mp4v",
				"F4V",
				"f4v",
				"mp3",
				"MP3" 
		);
	else // for image upload
		$allowedExtensions = array (
				"jpg",
				"JPG",
				"png",
				"PNG",
				"jpeg",
				"JPEG" 
		);
}

// This if condition ctrl all functions in this file check if upload is success or cancelled.
if (! iserror ()) {
	// check if stopped by post_max_size
	if (($pro == 1) && (empty ( $_FILES ['myfile'] ))) {
		$errorcode = 13;
	} else {
		$file = $_FILES ['myfile'];
		if (noFileUploadError ( $file )) {
			
			if (isAllowedExtension ( $file )) {
				// check file size
				if (! fileSizeExceeds ( $file )) {
					doFileUploading ( $file ); // it upload the file
				}
			}
		}
	}
}
function iserror() { // if it is success return false;
	global $error;
	global $errorcode;
	if ($error == "cancel") {
		$errorcode = 1;
		return true;
	} else {
		return false;
	}
}
function noFileUploadError($file) { // for display error message
	global $errorcode;
	$error_code = $file ['error'];
	
	switch ($error_code) {
		case 1 :
			$errorcode = 5;
			return false;
		case 2 :
			$errorcode = 6;
			return false;
		case 3 :
			$errorcode = 7;
			return false;
		case 4 :
			$errorcode = 8;
			return false;
		case 6 :
			$errorcode = 9;
			return false;
		case 7 :
			$errorcode = 10;
			return false;
		case 8 :
			$errorcode = 11;
			return false;
		case 0 :
			return true;
		default :
			$errorcode = 12;
			return false;
	}
}
function isAllowedExtension($file) { // CHECK VALIDE EXTENSION -- if uploaded file extension is in our required extension then it return ture
	global $allowedExtensions;
	global $errorcode;
	$filename = $file ['name'];
	$output = in_array ( end ( explode ( ".", $filename ) ), $allowedExtensions );
	if (! $output) {
		$errorcode = 2;
		return false;
	} else {
		return true;
	}
}
function fileSizeExceeds($file) {
	$POST_MAX_SIZE = ini_get ( 'post_max_size' ); // Gets the value which are stored in the php.ini file 1000M
	$filesize = $file ['size']; // uploaded file size
	$mul = substr ( $POST_MAX_SIZE, - 1 );
	$mul = ($mul == 'M' ? 1048576 : ($mul == 'K' ? 1024 : ($mul == 'G' ? 1073741824 : 1))); // in mega bytes, kilo bytes, gega byts
	if ($_SERVER ['CONTENT_LENGTH'] > $mul * ( int ) $POST_MAX_SIZE && $POST_MAX_SIZE) {
		$errorcode = 3;
		return true;
	} else {
		return false;
	}
}
function doFileUploading($file) {
	global $options1;
	global $wptfile_abspath1;
	$options1 = get_option ( 'HDFLVSettings' );
	global $uploadpath;
	global $file;
	global $errorcode;
	global $file_name;
	global $wpdb;
	$wp_upload = wp_upload_dir ();
	$uploadpath = $wp_upload ['path'] . '/';
	$filesave = "select MAX(vid) from " . $wpdb->prefix . "hdflv";
	$fsquery = mysql_query ( $filesave );
	$row = mysql_fetch_array ( $fsquery, MYSQL_NUM );
	
	$destination_path = $uploadpath;
	
	$row1 = $row [0] + 1;
	$file_name = $row1 . "_" . $_FILES ['myfile'] ['name'];
	
	$target_path = $destination_path . "" . $file_name;
	
	if (@move_uploaded_file ( $file ['tmp_name'], $target_path )) {
		$errorcode = 0; // if success
	} else {
		$errorcode = 4; // unknow error
	}
	
	sleep ( 1 );
}
?>
<script language="javascript" type="text/javascript">
    window.top.window.updateQueue(<?php echo $errorcode; ?>,"<?php echo $errormsg[$errorcode]; ?>","<?php echo $file_name; ?>");
</script>