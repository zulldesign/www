<?php
list($usec, $sec) = explode(' ', microtime());
$seed = (float) $sec + ((float) $usec * 100000);
mt_srand($seed);
$rndstr = mt_rand(100000, 999999);

$sPathDlm = (strtolower(substr(PHP_OS, 0, 3)) === 'win') ? "\\" : "/";
$path = realpath(".."); // get parent folder
$file = $path . $sPathDlm . "download" . $sPathDlm . $rndstr . '.tmp';

// try write a file
$fileHandler = @fopen($file, "a+b");
@fwrite($fileHandler, $rndstr);
@fclose($fileHandler);

if (file_exists($file)) {
	echo "Succeeded to write file in the \"download\" folder.<br>";
	@unlink($file);
	if (file_exists($file)) {
		echo "Error: Failed to delete file in the \"download\" folder.<br>";
	} else {
		echo "Succeeded to delete file in the \"download\" folder.<br>";
		echo "The \"download\" folder is properly setup.<br>";
	}
} else {
	echo "Error: Failed to write file in \"download\" folder.<br>";
	echo "*** Please setup write permission to the folder \"$path\" on this server. ***<br>";
}
?>
