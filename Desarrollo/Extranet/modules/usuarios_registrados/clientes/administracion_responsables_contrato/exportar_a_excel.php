<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/export_query.php");


$sql = $_REQUEST["sql"];
$exportQuery = new ExportQuery($sql, "Administracion_Usuarios_".date("dmY"));
$exportQuery->export();
?>