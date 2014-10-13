<?php
include("../ppcfg.php");
$EWPP_LANG_PATH = "../" . $EWPP_LANG_PATH;
include("../ppfn.php");
if (strtolower(ewpp_ServerVar("HTTPS")) == "on") {
	$URL = "https://";
} else {
	$URL = "http://";
}
$URL .= ewpp_ServerVar("SERVER_NAME");
$URL .= ewpp_ScriptName();
echo "<b>Current URL:</b> <span style='color: red'>" . $URL . "</span><br>";
$Pos = strrpos($URL, "/");
$Path = substr($URL, 0, $Pos); // up one level
$Pos = strrpos($Path, "/");
$Path = substr($Path, 0, $Pos+1); // up one more level
$FullPath = $Path . "ipn.php";
echo "<b>Notify URL for IPN:</b> <a href='" . $FullPath . "'>" . $FullPath . "</a><br>";
$FullPath = $Path . "pdt.php";
echo "<b>Auto Return URL for PDT:</b> <a href='" . $FullPath . "'>" . $FullPath . "</a><br>";
echo "Click above URL to check if it is valid.";
exit();
?>
