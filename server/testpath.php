<?php
include("php/config.php");
include("php/ewfn.php");

if (strtolower(ew_ServerVar("HTTPS")) == "on") {
	$URL = "https://";
} else {
	$URL = "http://";
}
$URL .= ew_ServerVar("SERVER_NAME");
$URL .= ew_ScriptName();
echo "<b>Current URL:</b> <font color=#0000FF>" . $URL . "</font><br>";
$Pos = strrpos($URL, "/");
$Path = substr($URL, 0, $Pos+1);
$FullPath = $Path . "php/ipn.php";
echo "<b>Notify URL for IPN:</b> <a href='" . $FullPath . "'>" . $FullPath . "</a><br>";
$FullPath = $Path . "php/pdt.php";
echo "<b>Auto Return URL for PDT:</b> <a href='" . $FullPath . "'>" . $FullPath . "</a><br>";
echo "Click above URL to check if it is valid.";
?>
