<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/images.php");


$width = -1;
if (isset($_REQUEST["width"]))
	$width = $_REQUEST["width"];
getImage(base64_decode($_REQUEST["file"]), $width);
?>