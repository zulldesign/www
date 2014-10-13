<?php
$PathDelimiter = (strtolower(substr(PHP_OS, 0, 3)) === 'win') ? "\\" : "/";

$filepath = "phpmailer" . $PathDelimiter . "class.phpmailer.php";
include($filepath); // add new mail class to support SMTP Authentication 

// Function to Load Email Content from input file name
// - Content Loaded to the following variables
// - Subject: sEmailSubject
// - From: sEmailFrom
// - To: sEmailTo
// - Cc: sEmailCc
// - Bcc: sEmailBcc
// - Format: sEmailFormat
// - Content: sEmailContent
function LoadEmail($fn)
{
	global $sEmailSubject;
	global $sEmailFrom;
	global $sEmailTo;
	global $sEmailCc;
	global $sEmailBcc;
	global $sEmailFormat;
	global $sEmailContent;

	$sWrk = LoadTxt($fn); // Load text file content
	$sWrk = str_replace("\r\n", "\n", $sWrk); // Convert to Lf
	$sWrk = str_replace("\r", "\n", $sWrk); // Convert to Lf

	if ($sWrk <> "") {
		// Locate Header & Mail Content
		$i = strpos($sWrk, "\n\n");
		if ($i > 0) {
			$sHeader = substr($sWrk, 0, $i);
			$sEmailContent = trim(substr($sWrk, $i, strlen($sWrk)));
			$arrHeader = split("\n",$sHeader);
			for ($j = 0; $j < count($arrHeader); $j++)
			{
				$i = strpos($arrHeader[$j], ":");
				if ($i > 0) {
					$sName = trim(substr($arrHeader[$j], 0, $i));
					$sValue = trim(substr($arrHeader[$j], $i+1, strlen($arrHeader[$j])));
					switch (strtolower($sName))
					{
						case "subject": $sEmailSubject = $sValue;
												break;
						case "from": $sEmailFrom = $sValue;
												break;
						case "to": $sEmailTo = $sValue;
												break;
						case "cc": $sEmailCc = $sValue;
												break;
						case "bcc": $sEmailBcc = $sValue;
												break;
						case "format": $sEmailFormat = $sValue;
												break;
					}
				}
			}
		}
	}

}

// Function to Load a Text File
function LoadTxt($fn)
{	
	$fobj = fopen($fn , "r");
	return fread($fobj, filesize($fn));
}

//Function to Send out Email
function Send_Email($sFrEmail, $sToEmail, $sCcEmail, $sBccEmail, $sSubject, $sMail, $sFormat)
{
	/* for debug only
	echo "sSubject: " . $sSubject . "<br>";
	echo "sFrEmail: " . $sFrEmail . "<br>";
	echo "sToEmail: " . $sToEmail . "<br>";
	echo "sCcEmail: " . $sCcEmail . "<br>"; 
	echo "sSubject: " . $sSubject . "<br>";
	echo "sMail: " . $sMail . "<br>";
	echo "sFormat: " . $sFormat . "<br>";
	*/
	
	$mail = new PHPMailer(); // create mail object for sending email
	
	$mail->IsSMTP(); 
	$mail->Host = NTK_SMTPSERVER; // set smtp host	
	$mail->SMTPAuth = (NTK_SMTPSERVER_USERNAME <> "" && NTK_SMTPSERVER_PASSWORD <> "");
	$mail->Username = NTK_SMTPSERVER_USERNAME;
	$mail->Password = NTK_SMTPSERVER_PASSWORD;
	$mail->Port = NTK_SMTPSERVER_PORT; 
	
	$mail->From = $sFrEmail;
	$mail->FromName = $sFrEmail;
	$mail->Subject = $sSubject;
	$mail->Body = $sMail;
	
	$sToEmail = str_replace(";", ",", $sToEmail);
	$arrTo = explode(",", $sToEmail);
	
	foreach ($arrTo as $sTo) {
		$mail->AddAddress(trim($sTo));
	}

	if ($sCcEmail <> "") {
		$sCcEmail = str_replace(";", ",", $sCcEmail);
		$arrCc = explode(",", $sCcEmail);
		
		foreach ($arrCc as $sCc) {
			$mail->AddCC(trim($sCc));
		}
	}
	if ($sBccEmail <> "") {
		$sBccEmail = str_replace(";", ",", $sBccEmail);
		$arrBcc = explode(",", $sBccEmail);
		
		foreach ($arrBcc as $sBcc) {
			$mail->AddBCC(trim($sBcc));
		}
	}
	
	if (strtolower($sFormat) == "html") {
		$mail->ContentType = "text/html";
	} else {
		$mail->ContentType = "text/plain";
	}
	
	if (!$mail->Send()) {
		echo "There has been a mail error sending to " . $sToEmail . "<br>";
		return false;
	}
		
	$mail->ClearAddresses();
	$mail->ClearAttachments();
	$mail->SMTPClose();
	$mail = NULL;
	return true;
}

// Function for Creating Folder
function CreateFolder($dir, $mode = 0777)
{
  if (is_dir($dir) || @mkdir($dir, $mode)) return true;
  if (!CreateFolder(dirname($dir), $mode)) return false;
  return @mkdir($dir, $mode);
}

// Function for Writing log
function WriteLog($pfx, $key, $value)
{
	global $PathDelimiter;
	$sHeader = "date" . "\t" . "time" . "\t" . "key" . "\t" .	"value";
	$sMsg = date("Y/m/d") . "\t" . date("H:i:s") . "\t" . $key . "\t" . $value;
	$folder = GetRootFolder() . $PathDelimiter .
		str_replace("/", $PathDelimiter, NTK_LOG_FOLDER);
	$file = $pfx . "_" . date("Ymd") . ".txt";
	CreateFolder($folder);
	$filename = $folder . $PathDelimiter . $file;
	
	if (file_exists($filename)) {
		$fileHandler = fopen($filename, "a+b");
	} else {
		$fileHandler = fopen($filename, "a+b");
		fwrite($fileHandler, $sHeader."\r\n");
	}
	
	fwrite($fileHandler, $sMsg."\r\n");
	fclose($fileHandler);
}

// Function for Writing to file
function WriteFile($folder, $file, $content)
{
	global $PathDelimiter;
	if ($folder <> "") {
		$wrkfile = GetRootFolder() . $PathDelimiter . $folder .
			$PathDelimiter . $file;
	} else {
		$wrkfile = realpath(".") . $PathDelimiter . $file;
	}
	$fileHandler = fopen($wrkfile, "a+b");
	fwrite($fileHandler, $content);
	fclose($fileHandler);
}

// Get content using HTTP POST by CURL (Client URL Library)
// Note: CURL must be enabled
function GetContentByCurl($url, $method, $postdata)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, NTK_PAYPAL_URL);
	if (strtoupper(trim($method)) == "POST")
		curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$fp = curl_exec($ch);
	curl_close($ch);
	return $fp;
}

// Get content using HTTP POST by socket
// Note: sockets must be enabled
function GetContentBySocket($url, $method, $postdata)
{
	$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($postdata) . "\r\n\r\n";
	$pos = strpos(strtolower($url), "sandbox");
	if ($pos === false) {
		$fp = fsockopen('www.paypal.com', 80, $errno, $errstr, 30);
	} else {
		$fp = fsockopen('www.sandbox.paypal.com', 80, $errno, $errstr, 30);
	}
	if (!$fp) {
		return "ERROR";
	} else {
		fputs($fp, $header . $postdata);
		$response = "";
		$getres = false;
		while (!feof($fp)) {
			$res = fgets($fp, 1024);
			if (!$getres && $res == "\r\n")
				$getres = true;
			if ($getres)
				$response .= $res;
		}
		fclose($fp);
	}
	return $response;
}

// Get content using HTTP POST
// url = destination url
// method = "GET", "POST"
// postdata = Post Data
// Note: either CURL or sockets must be enabled
function GetContent($url, $method, $postdata)
{
	$fp = @GetContentByCurl($url, $method, $postdata);
	if (!$fp)
		$fp = @GetContentBySocket($url, $method, $postdata);
	return trim($fp);
}

// creating a temp folder
function GetTempFolder($tx)
{
	global $PathDelimiter;
	$sDldPath = trim(NTK_DOWNLOAD_PATH);
	$sDldPath = str_replace("/", $PathDelimiter, $sDldPath);
	if (substr($sDldPath, -1) <> $PathDelimiter)
		$sDldPath .= $PathDelimiter;
	// Clean up old folders first
	CleanupOldFolders($sDldPath);
	// Get the scrambled path for this tx
	$sDestPath = Scramble($tx);
	$sDestPath = $sDldPath . $sDestPath;
	// Create temp folder
	$sPath = GetRootFolder(); // get root path
	$sPath .= $PathDelimiter . $sDestPath;
	CreateFolder($sPath);
	return $sDestPath;

}

// Get server url
function GetServerUrl()
{
	global $HTTP_SERVER_VARS;
	$url = "";
	if (@$HTTP_SERVER_VARS["HTTPS"] == "on") { //*** check
		$url .= "https://";
	} else {
		$url .= "http://";
	}
	return $url . @$HTTP_SERVER_VARS["SERVER_NAME"];
}

// copy file to temp folder
function CopyTempFile($folder, $file)
{
	global $PathDelimiter;
	$sSrcPath = NTK_DOWNLOAD_SRC_PATH;
	$sSrcPath = str_replace("/", $PathDelimiter, $sSrcPath);
	// Copy file to temp folder
	$sFromPath = GetRootFolder(); // get root path
	$sToPath = $sFromPath;
	$sFromPath .= $PathDelimiter . $sSrcPath;
	$sToPath .= $PathDelimiter . $folder;
	if (file_exists($sFromPath . $PathDelimiter . $file)) {
		if (!file_exists($sToPath . $PathDelimiter . $file))
			copy($sFromPath . $PathDelimiter . $file,
				$sToPath . $PathDelimiter . $file);
		return GetRootPathInfo() . "/" . str_replace($PathDelimiter, "/", $folder) . "/" . $file;
	} else {
		return "";
	}
}

// Add dates
function DateAdd($interval, $number, $date) {

	$date_time_array = getdate($date);
	$hours = $date_time_array['hours'];
	$minutes = $date_time_array['minutes'];
	$seconds = $date_time_array['seconds'];
	$month = $date_time_array['mon'];
	$day = $date_time_array['mday'];
	$year = $date_time_array['year'];
	
	switch ($interval) {
		case 'yyyy':
			$year += $number;
			break;
		case 'q':
			$year += $number*3;
			break;
		case 'm':
			$month += $number;
			break;
		case 'y':
		case 'd':
		case 'w':
			$day += $number;
			break;
		case 'ww':
			$day += $number*7;
			break;
		case 'h':
			$hours += $number;
			break;
		case 'n':
			$minutes += $number;
			break;
		case 's':
			$seconds += $number;
			break;
	}
	$timestamp = mktime($hours, $minutes, $seconds, $month, $day, $year);
	return $timestamp;
}

// Romove folder
function RemoveDirectory($path){
	global $PathDelimiter;
	if ($dir_handle = opendir($path)) {
		while ($file = readdir($dir_handle)) {
			if ($file == "." || $file == "..") {
				continue;
			} else {
				$filename = $path . $PathDelimiter . $file;
				if (is_dir($filename)) {
					// delete subfolder except the log folder
					if (strtolower($file) <> strtolower(substr(NTK_LOG_FOLDER, -1*strlen($file)))) {
						WriteLog("PURGE", "dir", $path);
						RemoveDirectory($filename);
						@rmdir($filename);
					}
				} elseif (is_file($filename)) {
					if (DateAdd(NTK_DOWNLOAD_TIMEOUT_UNIT,
						NTK_DOWNLOAD_TIMEOUT_INTERVAL,
						filemtime($filename)) < strtotime("now")) {
						WriteLog("PURGE", "file", $filename);
						@unlink($filename);
					}
				}
			}
		}
		closedir($dir_handle);
		return true; // all files deleted
	} else {
		return false;	// directory doesn't exist
	} 
}

// Delete old files and folders
function CleanupOldFolders($path) {
	global $PathDelimiter;
	$sPath = GetRootFolder(); // get current path
	$sPath .= $PathDelimiter . $path;
	@RemoveDirectory($sPath);
}

// Scramble name
function Scramble($str)
{
	$encstr = base64_encode(crypt($str, NTK_RANDOM_KEY));
	$encstr = str_replace("+", "", $encstr);
	$encstr = str_replace("/", "", $encstr);
	$encstr = str_replace("=", "", $encstr);
	return $str . "_" . substr($encstr, 0, 12);
}

function GetRootFolder()
{
	global $PathDelimiter;
	$path = realpath("."); // get current folder
	return ParentPath($path, 2, $PathDelimiter); // up 2 levels
}

function GetRootPathInfo()
{
	$path = GetCurrentPathInfo(); // get current path
	return ParentPath($path, 2, "/"); // up 2 levels
}

function RootPath()
{
	return GetRootPathInfo() . "/"; // return root path
}

function GetCurrentPathInfo()
{
	global $HTTP_SERVER_VARS;
	$path = @$HTTP_SERVER_VARS["PHP_SELF"]; // get current script name
	$p = strrpos($path, "/");
	if ($p !== false)
		$path = substr($path, 0, $p); // remove script name
	return $path;
}

function ParentPath($sPath, $iLevel, $sPathDlm)
{
	$wrkpath = $sPath;
	for ($i=1; $i<=$iLevel; $i++) {
		$p = strrpos($wrkpath, $sPathDlm);
		if ($p !== false)
			$wrkpath = substr($wrkpath, 0, $p);
	}
	return $wrkpath;
}

// Get item download file name
function GetDownloadFile($item_number)
{
	global $arItems;
	$url = "";
	foreach ($arItems as $key => $value) {
		if ($key == $item_number) {
			$url = $value;
			break;
		}
	}
	return $url;
}

function GetPostVars($key) {
	global $HTTP_POST_VARS;
	$value = @$HTTP_POST_VARS[$key];
	return (get_magic_quotes_gpc()) ? stripslashes($value) : $value;
}
?>
