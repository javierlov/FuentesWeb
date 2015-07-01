<?
session_start();

if (false) {
//if ($_SERVER["HTTPS"] != "on") {
//	header("Location: https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
	echo "Location: https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	exit();
}

print_r($_SERVER);
?>