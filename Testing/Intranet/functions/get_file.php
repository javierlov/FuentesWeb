<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");


$file = StringToLower(base64_decode($_REQUEST["fl"]));
$ext = substr($file, strrpos($file, "."));
$mode = "i";
if (isset($_REQUEST["md"]))
	$mode = $_REQUEST["md"];

if (isset($_REQUEST["ft"]))
	$fileTitle = $_REQUEST["ft"];
else
	$fileTitle = $file;

if ($ext == ".pdf")
	header("Content-type: application/pdf");
elseif ($ext == ".doc")
	header("Content-type: application/msword");
elseif (($ext == ".htm") or ($ext == ".html"))
	header("Content-type: text/html");
elseif ($ext == ".jpg")
	header("Content-type: image/jpeg");
elseif ($ext == ".ppt")
	header("Content-type: application/vnd.ms-powerpoint");
elseif ($ext == ".xls")
	header("Content-type: application/vnd.ms-excel");
else
	header("Content-Type: application/octet-stream");

//if ($ext == ".bmp")

if ($mode == "a")
	header("Content-Disposition: attachment; filename=".basename($fileTitle));
else
	header("Content-Disposition: inline; filename=".basename($fileTitle));

readfile(base64_decode($_REQUEST["fl"]));
?>