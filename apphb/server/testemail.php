<?php
// PHP5 with register_long_arrays off
if (@phpversion() >= '5.0.0' && (!@ini_get('register_long_arrays') || @ini_get('register_long_arrays') == '0' || strtolower(@ini_get('register_long_arrays')) == 'off'))
{
	$HTTP_POST_VARS = &$_POST;
	$HTTP_GET_VARS = &$_GET;
	$HTTP_SERVER_VARS = &$_SERVER;
	$HTTP_ENV_VARS = &$_ENV;
}

include("php/config.php");
include("php/ntkfn.php");

$sFrEmail = @$HTTP_POST_VARS["from"];
$sToEmail = @$HTTP_POST_VARS["to"];
$sCcEmail = @$HTTP_POST_VARS["cc"];
$sBccEmail = @$HTTP_POST_VARS["bcc"];
$sSubject = @$HTTP_POST_VARS["subject"];
$sMail = @$HTTP_POST_VARS["body"];
$sFormat = @$HTTP_POST_VARS["format"];

if (trim($sFrEmail) == "") {
	echo "Missing sender email.";
	exit();
}

if (trim($sToEmail) == "") {
	echo "Missing recipient email.";
	exit();
}

if (Send_Email($sFrEmail, $sToEmail, $sCcEmail, $sBccEmail, $sSubject, $sMail, $sFormat)) {
	echo "Email sent.";
}
?>
