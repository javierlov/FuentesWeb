<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");


$file = DATA_BOLETIN_OFICIAL_PATH.$_REQUEST["ano"]."/".$_REQUEST["mes"]."/".$_REQUEST["dia"]."/".$_REQUEST["ano"]."_".$_REQUEST["mes"]."_".$_REQUEST["dia"]."_".$_REQUEST["seccion"].".pdf";

header("Content-type: application/pdf");
header('Content-Disposition: inline; filename="'.basename($file).'"');
readfile($file);
?>