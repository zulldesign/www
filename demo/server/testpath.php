<?php
// PHP5 with register_long_arrays off
if (@phpversion() >= '5.0.0' && (!@ini_get('register_long_arrays') || @ini_get('register_long_arrays') == '0' || strtolower(@ini_get('register_long_arrays')) == 'off'))
{
	$HTTP_POST_VARS = &$_POST;
	$HTTP_GET_VARS = &$_GET;
	$HTTP_SERVER_VARS = &$_SERVER;
	$HTTP_ENV_VARS = &$_ENV;
}

if (strtolower(ntkGetServerVar("HTTPS")) == "on") {
	$URL = "https://";
} else {
	$URL = "http://";
}
$URL .= ntkGetServerVar("SERVER_NAME");
$URL .= ntkScriptName();
echo "<b>Current URL:</b> <font color=#0000FF>" . $URL . "</font><br>";
$Pos = strrpos($URL, "/");
$Path = substr($URL, 0, $Pos+1);
$FullPath = $Path . "php/ipn.php";
echo "<b>Notify URL for IPN:</b> <a href='" . $FullPath . "'>" . $FullPath . "</a><br>";
$FullPath = $Path . "php/pdt.php";
echo "<b>Auto Return URL for PDT:</b> <a href='" . $FullPath . "'>" . $FullPath . "</a><br>";
echo "Click above URL to check if it is valid.";

function ntkScriptName() {
	$sScriptFileName = ntkGetServerVar("PHP_SELF");	
	if (empty($sScriptFileName)) $sScriptFileName = ntkGetServerVar("SCRIPT_NAME");
	if (empty($sScriptFileName)) $sScriptFileName = ntkGetServerVar("ORIG_PATH_INFO");
	if (empty($sScriptFileName)) $sScriptFileName = ntkGetServerVar("ORIG_SCRIPT_NAME");
	if (empty($sScriptFileName)) $sScriptFileName = ntkGetServerVar("REQUEST_URI");
	if (empty($sScriptFileName)) $sScriptFileName = ntkGetServerVar("URL");
	if (empty($sScriptFileName)) $sScriptFileName = "UNKNOWN";
	return $sScriptFileName;
}

function ntkGetServerVar($name) {
	global $HTTP_ENV_VARS;
	global $HTTP_SERVER_VARS;
	$sValue = @$HTTP_ENV_VARS[$name];
	if (empty($sValue)) $sValue = @$HTTP_SERVER_VARS[$name];
	return $sValue;
}
?>
