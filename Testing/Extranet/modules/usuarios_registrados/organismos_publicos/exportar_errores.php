<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/export_query.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isOrganismoPublico"]));

$sql =
	"SELECT te_nrofila \"FILA\", te_error \"ERROR\"
		 FROM tmp.teop_errororganismopublico
		WHERE te_transaccion = ".$_REQUEST["id"]."
 ORDER BY te_nrofila";
$exportQuery = new ExportQuery($sql, "Errores");
$exportQuery->export();
?>