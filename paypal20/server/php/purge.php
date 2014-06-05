<?php
// PHP5 with register_long_arrays off
if (@phpversion() >= '5.0.0' && (!@ini_get('register_long_arrays') || @ini_get('register_long_arrays') == '0' || strtolower(@ini_get('register_long_arrays')) == 'off'))
{
	$HTTP_POST_VARS = &$_POST;
	$HTTP_GET_VARS = &$_GET;
	$HTTP_SERVER_VARS = &$_SERVER;
	$HTTP_ENV_VARS = &$_ENV;
}

include("config.php");
include("ntkfn.php");

$a = @$HTTP_GET_VARS["a"];
if ($a == "purge") {
	@CleanUp();
?>
<p><?php echo NTK_PURGE_SUCCESS; ?></p>
<?php
} else {
?>
<p><?php echo NTK_CLICK_TO_PURGE; ?><br><a href="purge.php?a=purge"><?php echo NTK_PURGE; ?></a></p>
<?php
}

// Clean up old folders
function CleanUp() {
	$sDldPath = Trim(NTK_DOWNLOAD_PATH);
	$sDldPath = str_replace("/", $PathDelimiter, $sDldPath);
	if (substr($sDldPath, -1) <> $PathDelimiter)
		$sDldPath .= $PathDelimiter;
	// Clean up old folders first
	CleanupOldFolders($sDldPath);
}
?>