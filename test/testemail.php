<?php
if (!file_exists("../ppcfg.php")) {
	echo 'Missing required PHP files, please:<br \>';
	echo '1. Enter the email setting under "Options" -> "Email" tab, (specify a valid SMTP server, "localhost" may not work on your server)<br \>';
	echo '2. Generate scripts again and upload the "server" folder again.';
	exit();
}
include("../ppcfg.php");
$EWPP_LANG_PATH = "../" . $EWPP_LANG_PATH;
include("../ppfn.php");
$ewpp_EmailFrom = ewpp_PostVar("from");
$ewpp_EmailTo = ewpp_PostVar("to");
$ewpp_EmailCc = ewpp_PostVar("cc");
$ewpp_EmailBcc = ewpp_PostVar("bcc");
$ewpp_EmailSubject = ewpp_PostVar("subject");
$ewpp_EmailContent = ewpp_PostVar("body");
$ewpp_EmailFormat = ewpp_PostVar("format");
if (ewpp_PostVar("submit") <> "") {
	if (trim($ewpp_EmailFrom) == "") {
		echo "Missing sender email.";
		exit();
	}
	if (trim($ewpp_EmailTo) == "") {
		echo "Missing recipient email.";
		exit();
	}
	if (ewpp_SendEmail($ewpp_EmailFrom, $ewpp_EmailTo, $ewpp_EmailCc, $ewpp_EmailBcc, $ewpp_EmailSubject, $ewpp_EmailContent, $ewpp_EmailFormat)) {
		echo "Email sent.";
		exit();
	} else {
		die($ewpp_EmailError);
	}
}
?>
<html>
<head>
	<title>PayPal Shop Maker - Test Email Script (Unregistered version)</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript">

function submitForm(f) {
	if (f.from.value == "") {
		alert("Please enter sender email address.");
		f.from.select();
		f.from.focus();
		return false;
	}
	if (f.to.value == "") {
		alert("Please enter recipient email address.");
		f.to.select();
		f.to.focus();
		return false;
	}
	return true;
}
</script>
<meta name="generator" content="PayPal Shop Maker v5.0.0.2 (Unregistered version)">
</head>
<body>
<p><b>Email Testing Script</b></p>
<form method="post" onsubmit="submitForm(this);">
<table cellspacing="0" cellpadding="5" border="0">
<tr>
	<td valign="top"><strong>From</strong></td>
	<td><input type="text" name="from" value="sales@zulldesign.ml" size="50"></td>
</tr>
<tr>
	<td valign="top"><strong>To</strong></td>
	<td><input type="text" name="to" value="sales@zulldesign.ml" size="50"></td>
</tr>
<tr>
	<td valign="top"><strong>Cc</strong></td>
	<td><input type="text" name="cc" size="50"></td>
</tr>
<tr>
	<td valign="top"><strong>Bcc</strong></td>
	<td><input type="text" name="bcc" size="50"></td>
</tr>
<tr>
	<td valign="top"><strong>Subject</strong></td>
	<td><input type="text" name="subject" value="PayPal Shop Maker - Email Testing" size="60"></td>
</tr>
<tr>
	<td valign="top"><strong>Body</strong></td>
	<td><textarea cols="50" rows="10" name="body">This is a test email.</textarea></td>
</tr>
<tr>
	<td valign="top"><strong>Format</strong></td>
	<td><input type="radio" name="format" value="text" checked>Text&nbsp;&nbsp;<input type="radio" name="format" value="html">HTML</td>
</tr>
<tr>
	<td valign="top"></td>
	<td><input type="submit" name="submit" value="Send"></td>
</tr>
</table>
</form>
</body>
</html>
