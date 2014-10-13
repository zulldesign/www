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

$sTx = @$HTTP_GET_VARS["tx"];
$testipn = @$HTTP_GET_VARS["testipn"];
if ($testipn == "1") {
	$testind = " " . NTK_TEST_TRANSACTION;
} else {
	$testind = "";
}

if ($sTx <> "") {
	$sDldPath = trim(NTK_DOWNLOAD_PATH);
	$sDldPath = str_replace("/", $PathDelimiter, $sDldPath);
	if (substr($sDldPath, -1) <> $PathDelimiter) $sDldPath .= $PathDelimiter;
	// Get the scrambled path for this tx
	$sDestPath = Scramble($sTx);
	$sDestPath = $sDldPath . $sDestPath;
	// Check if download folder exists
	$sPath = GetRootFolder(); // get root path
	$sPath .= $PathDelimiter . $sDestPath;
	if (file_exists($sPath)) {
		// Check if file exists
		$sFile = "notify_$sTx.txt";
		if (file_exists($sPath . $PathDelimiter . $sFile)) {
			LoadEmail($sPath . $PathDelimiter . $sFile);
			// sEmailFrom already set up
			// sEmailTo already set up
			$sEmailSubject .= $testind; // ***
			// Set up Bcc
			if ($sEmailBcc <> "") $sEmailBcc .= $sEmailBcc & ";";
			$sEmailBcc .= NTK_RECIPIENT_EMAIL; // Bcc recipient
			// sEmailContent already set up
			if (Send_Email($sEmailFrom, $sEmailTo, $sEmailCc, $sEmailBcc, $sEmailSubject, $sEmailContent, $sEmailFormat)) {
				WriteLog("Approval", NTK_EMAIL_SENT_TO, $sEmailTo);
			} else {
				WriteLog("Approval", NTK_EMAIL_SENT_TO, $sEmailTo);
				WriteLog("Approval", NTK_EMAIL_SENT_ERROR, "Failed to send email to $sEmailTo");
			}
?>
<p><?php echo NTK_EMAIL_SENT; ?></p>
<?php
		}
	}
}
?>