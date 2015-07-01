<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/images.php");


// Max height..
$mh = -1;
if (isset($_REQUEST["mh"]))
	$mh = $_REQUEST["mh"];

// Max width..
$mw = -1;
if (isset($_REQUEST["mw"]))
	$mw = $_REQUEST["mw"];

// Width..
$width = -1;
if (isset($_REQUEST["width"]))
	$width = $_REQUEST["width"];

getImage(base64_decode($_REQUEST["file"]), $width, $mw, $mh);
?>