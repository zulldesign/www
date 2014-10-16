<?php
if (!file_exists("php/config.php")) {
	echo 'Missing required PHP files, please:<br \>';
	echo '1. Select "PHP" under "Options" -> "ASP/PHP" tab,<br \>';
	echo '2. Enter the email setting under "Options" -> "Email" tab, (specify a valid SMTP server, "localhost" may not work on your server)<br \>';
	echo '3. Generate scripts again and upload the "server" folder again.';
	exit();
}

include("php/config.php");
include("php/ewfn.php");

$sFrEmail = ew_PostVar("from");
$sToEmail = ew_PostVar("to");
$sCcEmail = ew_PostVar("cc");
$sBccEmail = ew_PostVar("bcc");
$sSubject = ew_PostVar("subject");
$sMail = ew_PostVar("body");
$sFormat = ew_PostVar("format");

if (trim($sFrEmail) == "") {
	echo "Missing sender email.";
	exit();
}

if (trim($sToEmail) == "") {
	echo "Missing recipient email.";
	exit();
}

if (ew_SendEmail($sFrEmail, $sToEmail, $sCcEmail, $sBccEmail, $sSubject, $sMail, $sFormat)) {
	echo "Email sent.";
}
?>
