<?php
echo "OK. If you see ONE and ONLY ONE line, this server supports PHP.";

if ((float)phpversion() < 5)
	die(" PHP 5 or later is required. You are running " . phpversion() . " only.");
	
if (strtolower(substr(PHP_OS, 0, 3)) === 'win')
	echo " This is a Windows server.";

$support = array();

if (function_exists("mysql_connect")) $support[] = "MySQL";

try {
	$db = new PDO('sqlite::memory:');
	$support[] = "PDO_SQLITE";
} catch (Exception $e) {}

if (strtolower(substr(PHP_OS, 0, 3)) === 'win' && class_exists("COM")) $support[] = "COM";

if (count($support) > 0) echo " The PHP setup supports: " . implode(", ", $support) . ".";

exit();
?>
