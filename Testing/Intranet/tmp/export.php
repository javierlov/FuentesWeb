<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/export_query.php");


$sql = "SELECT * FROM aco_contrato where co_contrato < 10";
$exportQuery = new ExportQuery($sql, "Estadisticas_Informe_de_Gestion", 1, false);
$exportQuery->Export();
?>